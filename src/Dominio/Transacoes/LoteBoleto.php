<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Transacoes;

use RebecaJulia\PagamentoCnab240\Aplicacao\Constantes\FormaPagamentoBoleto;
use RebecaJulia\PagamentoCnab240\Dominio\Bancos\Banco;
use RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Boleto\PagamentoBoleto;

class LoteBoleto implements Transacao
{
    /**
     * @var array
     */
    private $conteudo;
    private $formaPagamento;

    /**
     * @param $formaPagamento
     */
    public function __construct($formaPagamento)
    {
        $this->conteudo = [];
        if(!in_array($formaPagamento, array(FormaPagamentoBoleto::MESMO_BANCO, FormaPagamentoBoleto::OUTRO_BANCO))){
            throw new \InvalidArgumentException('Forma Pagamento inválida');
        }
        $this->formaPagamento = $formaPagamento;
    }

    /**
     * @return string[]
     */
    public function segmentos()
    {
        return [
            'J',
            'J52'
        ];
    }

    /**
     * @param PagamentoBoleto $pagamentoBoleto
     * @return $this
     */
    public function adicionar(PagamentoBoleto $pagamentoBoleto)
    {
        array_push($this->conteudo, $pagamentoBoleto);
        return $this;
    }

    /**
     * @param Banco $banco
     * @return array
     */
    public function headerLote(Banco $banco)
    {
        /**
         * tipo_pagamento = 20 - Pagamento Fornecedor
         */
        $empresa = $banco->conta->empresa;
        $headeLote = [];
        /**
         * Somente o bradesco possui esse método
         */
        if (method_exists($banco, "recuperarCodigoConvenio")) {
            $headeLote = $headeLote + ['codigo_convenio' => $banco->recuperarCodigoConvenio()];
        }
        $headeLote = $headeLote + [
            'codigo_lote' => '0',
            'inscricao_numero' => $empresa->inscricao,
            'empresa_inscricao' => $empresa->tipoInscricao,
            'agencia' => $banco->conta->agencia,
            'conta' => $banco->conta->conta,
            'conta_digito' => $banco->conta->contaDv,
            'nome_empresa' => $empresa->nome,
            'endereco_empresa' => $empresa->rua,
            'numero' => $empresa->numero,
            'complemento' => $empresa->complemento,
            'cep' => $empresa->cep,
            'cidade' => $empresa->cidade,
            'estado' => $empresa->estado,
            'tipo_pagamento' => 20,
            'forma_pagamento' => $this->formaPagamento,
            'total_qtd_registros' => 0,
            'total_valor_pagtos' => 0
        ];

        return $headeLote;
    }

    /**
     * @param Banco $banco
     * @return int[]
     */
    public function trailerLote(Banco $banco)
    {
        return [
            'codigo_lote' => 0,
            'total_qtd_registros_lote' => 0,
            'total_valor_pagtos' => 0
        ];
    }

    /**
     * @return array
     */
    public function recuperarConteudo()
    {
        return $this->conteudo;
    }
}