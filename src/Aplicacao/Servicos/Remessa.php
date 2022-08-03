<?php

namespace Leandroferreirama\PagamentoCnab240\Aplicacao\Servicos;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\DadosArquivo;
use Leandroferreirama\PagamentoCnab240\Dominio\Excecoes\LeiauteException;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;
use Leandroferreirama\PagamentoCnab240\Dominio\Yaml;

/**
 * Esta classe é responsável por unir os dados passados como parâmetro através da classe DadosArquivo
 * e juntar com os dados do arquivo de configuração. 
 */
class Remessa
{
    private $banco;
    private $yaml;
    private $transacao;

    public function __construct(Banco $banco, Transacao $transacao)
    {
        $this->banco = $banco;
        $this->transacao = $transacao;
        $this->yaml = new Yaml($this->banco->pasta_remessa());
    }

    public function gerarArquivo(DadosArquivo $dados)
    {
        $headerArquivo = $this->unirHeaderArquivo($dados);
        $headerLote = $this->unirHeaderLote($dados);
        $detalhe = $this->unirDetalhe($dados);
        /**
         * Temporário
         */
        $yamHeaderLote = $this->yaml->headerLote();
        $yamDetalhe = $this->yaml->detalhe($this->transacao);
        $yamTrailerLote = $this->yaml->trailerLote();
        $yamTrailerLote = $this->yaml->trailerArquivo();
    }

    public function unirHeaderArquivo(DadosArquivo $dados)
    {
        if (empty($dados->header_arquivo)) {
            throw new LeiauteException('Não localizei os dados do header do arquivo');
        }
        /**
         * Retorna um array contendo todas as posições do arquivo de configuração do header do arquivo
         */
        $yamlHeaderArquivo = $this->yaml->headerArquivo();

        foreach ($dados->header_arquivo as $key => $conteudo) {
            /**
             * Valido se foi passado uma informação que não está no arquivo yaml
             */
            if (!array_key_exists($key, $yamlHeaderArquivo)) {
                throw new LeiauteException("A chave {$key} não está presente no arquivo de configuração!");
            }
            /**
             * Atualizo a informação para o valor passado
             */
            $yamlHeaderArquivo[$key]['value'] = $conteudo;
        }
        /**
         * Retorno o array atualizado com os dados passados
         */
        return $yamlHeaderArquivo;
    }

    public function unirHeaderLote(DadosArquivo $dados)
    {
        if (empty($dados->header_lote)) {
            throw new LeiauteException('Não localizei os dados do header do lote');
        }
        /**
         * Retorna um array contendo todas as posições do arquivo de configuração do header do lote
         */
        $yamlHeaderLote = $this->yaml->headerLote();

        foreach ($dados->header_lote as $key => $conteudo) {
            /**
             * Valido se foi passado uma informação que não está no arquivo yaml
             */
            if (!array_key_exists($key, $yamlHeaderLote)) {
                throw new LeiauteException("A chave {$key} não está presente no arquivo de configuração!");
            }
            /**
             * Atualizo a informação para o valor passado
             */
            $yamlHeaderLote[$key]['value'] = $conteudo;
        }
        /**
         * Retorno o array atualizado com os dados passados
         */
        return $yamlHeaderLote;
    }

    public function unirDetalhe(DadosArquivo $dados)
    {
        
        /*echo '<h3>VarDump Detalhe</h3><pre>';
        var_dump($dados->detalhe);
        echo '</pre><br><hr>';*/
        if (empty($dados->detalhe)) {
            throw new LeiauteException('Não localizei os dados do detalhe');
        }
        
        $dadosDetalhe = [];
        /**
         * Retorna um array contendo todas as posições do arquivo de configuração do detalhe
         * @param Transacao
         */
        $yamSegmento = $this->yaml->detalhe($this->transacao);
        /*echo '<h3>YAML Segmento</h3><pre>';
        var_dump($yamSegmento);
        echo '</pre>';*/
        /**
         * pego os registros dos lotes
         */
        foreach($dados->detalhe as $key => $detalhes){
            /**
             * Repasso os segmentos
             */
            foreach($yamSegmento as $segmento){
                /**
                 * Desmembro os dados
                 */
                foreach($detalhes as $campo => $conteudo){
                    /**
                    * Valido se foi passado uma informação que não está no arquivo yaml
                    */
                    if (!array_key_exists($campo, $detalhes)) {
                        throw new LeiauteException("A chave {$key} não está presente no arquivo de configuração!");
                    }
                    /**
                    * Atualizo a informação para o valor passado
                    */
                    $segmento[$key]['value'] = $conteudo;
                }
                $dadosDetalhe[] = $segmento;    
            }
            
        }
        echo '<h3>Resultado Detalhe</h3><pre>';
        var_dump($dadosDetalhe);
        echo '</pre><br><hr>';
    }


}
