<?php

namespace RebecaJulia\PagamentoCnab240\Aplicacao;

use RebecaJulia\PagamentoCnab240\Dominio\Bancos\Banco;
use RebecaJulia\PagamentoCnab240\Dominio\Excecoes\LeiauteException;
use RebecaJulia\PagamentoCnab240\Dominio\Yaml;
use mikehaertl\tmp\File;

class GerarArquivo
{
    /**
     * @var Banco
     */
    private $banco;
    private $pasta;
    private $yaml;
    private $conteudoArquivo;
    private $qtde_registros_arquivo;
    private $codigo_lote;
    private $numero_registro;
    private $valorTotalPagamento;
    private $qtde_registros_lote;
    private $codigoArquivo;

    public function __construct(Banco $banco)
    {
        $this->banco = $banco;
        $this->pasta = $banco->pastaRemessa();
        $this->yaml = new Yaml($this->pasta);
        $this->qtde_registros_arquivo = 1;
        $this->codigo_lote = 0;
        $this->numero_registro = 0;
        $this->codigoArquivo = $banco->recuperarCodigoArquivo();
    }

    public function gerar($retorno)
    {
        /**
         * Gero o Header do Arquivo
         */
        $this->gerarConteudo('Header do Arquivo', 'header_arquivo', $this->banco->headerArquivo());
        $this->qtde_registros_arquivo++;
        /**
         * Gero os lotes
         */
        $this->gerarLote();
        /**
         * Gero o trailer do Arquivo
         */
        $this->gerarConteudo('Trailer do Arquivo', 'trailer_arquivo', $this->banco->trailerArquivo());
        try {
            $file = new File($this->conteudoArquivo, '.txt');

        } catch(\Exception $e) {
            throw new LeiauteException("Não foi possível baixar o arquivo.");
        }
        if($retorno === 1){
            $this->downloadArquivo($file->getFileName());
            $file->delete = true;
            return;
        }
        if($retorno === 2){
            $file->delete = false;
            return $file->getFileName();
        }
        throw new \InvalidArgumentException('Não foi informado o tipo de retorno corretamente!');
    }

    public function gerarLote()
    {
        /**
         * Recupero todas as transações realizadas: LoteBoleto, ted e etc.
         */
        foreach ($this->banco->recuperarLotes() as $transacao) {
            $this->valorTotalPagamento = 0;
            $this->qtde_registros_lote = 1;
            $this->numero_registro = 0;
            $this->codigo_lote++;
            /**
             * Monto o header do lote
             */
            $header_lote = $transacao->headerLote($this->banco);
            $this->gerarConteudo('Header do Lote', 'header_lote', $header_lote);
            $this->qtde_registros_arquivo++;

            /**
             * Monto os conteudos
             */
            foreach ($transacao->recuperarConteudo() as $conteudo) {
                $detalhes = $conteudo->conteudo($this->banco, $transacao);
                $this->valorTotalPagamento += Helper::formatoBrlParaUS($conteudo->valorPagamento);
                /**
                 * listo os segmentos deste tipo de transação
                 */
                foreach ($transacao->segmentos() as $segmento) {
                    $this->qtde_registros_lote++;
                    $this->numero_registro++;
                    $this->qtde_registros_arquivo++;
                    /**
                     * Gero o item do segmento
                     */
                    $this->gerarConteudo($segmento, $segmento, $detalhes);
                }
            }

            /**
             * Monto o trailer do lote
             */
            $this->qtde_registros_lote++;
            $trailer_lote = $transacao->trailerLote($this->banco);
            $this->gerarConteudo('Trailer do Lote', 'trailer_lote', $trailer_lote);
            $this->qtde_registros_arquivo++;
        }

    }

    private function gerarConteudo($nomeSegmento, $segmento, $conteudo)
    {
        /**
         * Faz a leitura e validação do arquivo de configuração YAML
         */
        $yaml = $this->yaml->lerAquivo($nomeSegmento, "{$segmento}.yml");
        /**
         * Incrementa a chave value no array yaml para ser utilizado na geração dos dados.
         */
        $dados = $this->unirArquivo($nomeSegmento, $conteudo, $yaml);
        /**
         * Gero o Header do arquivo
         */
        $this->criarConteudo($nomeSegmento, $dados);
    }

    public function unirArquivo($segmento, $dados, $yaml)
    {
        if (empty($dados)) {
            throw new LeiauteException('Não localizei os dados do segmento: ' . $segmento);
        }

        foreach ($dados as $key => $conteudo) {
            /**
             * Atualizo a informação para o valor passado SE EXISTIR
             */
            if (array_key_exists($key, $yaml)) {
                $yaml[$key]['value'] = $conteudo;
                /**
                 * Caso seja uma informação reservada eu substituo
                 */
                if($key == 'numero_registro'){
                    $yaml[$key]['value'] = $this->numero_registro;
                }
                if($key == 'total_qtd_registros'){
                    $yaml[$key]['value'] = $this->qtde_registros_arquivo;
                }
                if($key == 'total_qtd_lotes'){
                    $yaml[$key]['value'] = $this->codigo_lote;
                }
                if($key == 'codigo_lote'){
                    $yaml[$key]['value'] = $this->codigo_lote;
                }
                if($key == 'total_qtd_registros_lote'){
                    $yaml[$key]['value'] = $this->qtde_registros_lote;
                }
                if($key == 'total_valor_pagtos'){
                    $yaml[$key]['value'] = Helper::valorParaNumero($this->valorTotalPagamento);
                }
            }
        }
        /**
         * Retorno o array atualizado com os dados passados
         */
        return $yaml;

    }

    public function criarConteudo($segmento, $conteudo)
    {
        if (!is_array($conteudo)) {
            throw new LeiauteException("O segmento: {$segmento} é inválido");
        }

        foreach ($conteudo as $nomeCampo => $conteudoCampo) {
            $chavesArray = array_keys($conteudo);
            $ultimoCampo = end($chavesArray) == $nomeCampo;

            $this->conteudoArquivo .= $this->criarCampo($nomeCampo, $conteudoCampo, $ultimoCampo);
        }
        $this->conteudoArquivo .= "\r\n";
    }

    private function criarCampo($nomeCampo, $conteudoCampo, $ultimoCampo)
    {
        $valorDefinido = null;
        if (preg_match('/branco/', $nomeCampo)) {
            $valorDefinido = ' ';
        }
        if (isset($conteudoCampo['value']) && $valorDefinido === null) {
            $valorDefinido = $conteudoCampo['value'];
        } else if ($valorDefinido === null && isset($conteudoCampo['default'])) {
            $valorDefinido = $conteudoCampo['default'];
        }

        $picture = Helper::explodePicture($conteudoCampo['picture']);

        if (mb_strlen($valorDefinido) > $picture['firstQuantity']) {
            throw new LeiauteException("O Valor Passado no campo {$nomeCampo} / {$valorDefinido} está maior que os campos disponíveis:" . $picture['firstQuantity']);
        }

        return $this->formataValor($picture['firstType'], $picture['firstQuantity'], $valorDefinido);
    }

    private function formataValor($tipo, $quantidade, $valor)
    {
        if ($tipo == 9) {
            return str_pad($valor, $quantidade, "0", $this->banco->strPadNumero());
        }
        return str_pad(
            strtr(
                utf8_decode(mb_convert_case($valor, MB_CASE_UPPER)),
                utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
                'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'
            ),
            $quantidade,
            " ",
            $this->banco->strPadTexto()
        );
    }

    private function downloadArquivo($caminho)
    {
        $arquivo = 'pg'.str_pad($this->codigoArquivo, 6, 0, STR_PAD_LEFT).'.txt';
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.$arquivo.'"');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($caminho));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        // Envia o arquivo para o cliente
        readfile($caminho);

    }
}
