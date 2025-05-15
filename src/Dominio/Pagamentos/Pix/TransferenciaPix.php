<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Pix;

use RebecaJulia\PagamentoCnab240\Aplicacao\Helper;
use RebecaJulia\PagamentoCnab240\Dominio\Bancos\Banco;
use RebecaJulia\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Pagamento;
use RebecaJulia\PagamentoCnab240\Dominio\Transacoes\Transacao;

class TransferenciaPix implements Pagamento
{
    /**
     * @var Favorecido
     */
    private $favorecido;
    /**
     * @var mixed
     */
    private $dataPagamento;
    /**
     * @var mixed
     */
    private $seuNumero;

    /**
     * @var Pix
     */
    private $pix;
    /**
     * @param Favorecido $favorecido
     * @param $valorPagamento
     * @param $dataPagamento
     * @param $seuNumero
     * @param $tipoChave
     * @param $chave
     */
    public function __construct(Favorecido $favorecido, Pix $pix, $valorPagamento, $seuNumero, $dataPagamento = null)
    {
        $this->favorecido = $favorecido;
        $this->pix = $pix;
        $this->valorPagamento = filter_var($valorPagamento, FILTER_SANITIZE_STRING);
        $this->seuNumero = filter_var($seuNumero, FILTER_SANITIZE_STRING);
        $this->setDataPagamento($dataPagamento);
    }

    /**
     * @param $dataPagamento
     * @return void
     */
    private function setDataPagamento($dataPagamento)
    {
        if(is_null($dataPagamento)){
            $dataPagamento = date("Y-m-d");
        }
        $this->dataPagamento = filter_var($dataPagamento, FILTER_SANITIZE_STRING);
    }

    /**
     * @param Banco $banco
     * @param Transacao $transacao
     * @return array
     */
    public function conteudo(Banco $banco, Transacao $transacao)
    {
        /** numero_registro: Obrigatório passar valor zero, o valor é calculado automaticamente
         **forma_iniciacao:
         **01” – Chave LotePix – tipo Telefone
         **“02” – Chave LotePix – tipo Email
         **“03” – Chave LotePix – tipo CPF/CNPJ
         **“04” – Chave Aleatoria
         **“05” - Dados Bancários
         */
        $camara_centralizadora = '009';
        $conteudo = [
            'codigo_lote' => 0,
            'tipo_movimento' => 0,
            'camara_centralizadora' => $camara_centralizadora,
            'tipo_inscricao_pagador' => $banco->conta->empresa->tipoInscricao,
            'numero_inscricao_pagador' => $banco->conta->empresa->inscricao,
            'nome_pagador' => $banco->conta->empresa->nome,
            'tipo_inscricao_favorecido' => $this->favorecido->tipoInscricao,
            'numero_inscricao_favorecido' => $this->favorecido->inscricao,
            'nome_favorecido' => $this->favorecido->nome,
            'numero_registro' => 0,
            'valor_pagamento' => Helper::valorParaNumero($this->valorPagamento),
            'data_pagamento' => Helper::formataDataParaRemessa($this->dataPagamento),
            'seu_numero' => $this->seuNumero,
            'forma_iniciacao' => $this->pix->getTipoChave(),
            'chave' => $this->pix->getChave()
        ];

        if ($this->pix->getTipoChave() === '05') {
            /** @var PixDadosBancarios $pix */
            $pix = $this->pix;

            $conteudo = array_merge($conteudo, [
                'banco_favorecido'      => $pix->getCodigoBanco(),
                'agencia_favorecido'    => $pix->getAgencia(),
                'conta_favorecido'      => $pix->getConta(),
                'conta_dv_favorecido'   => $pix->getContaDigito(),
                'tipo_conta_favorecido' => $pix->getTipoConta(),
            ]);
        }

        return $conteudo;
    }
}