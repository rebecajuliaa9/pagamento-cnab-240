<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Transacoes;

use RebecaJulia\PagamentoCnab240\Dominio\Bancos\Banco;
use RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\TransferenciaTed;

class LoteTed implements Transacao
{
    /**
     * @var array
     */
    private $conteudo;

    /**
     *
     */
    public function __construct()
    {
        $this->conteudo = [];
    }

    /**
     * @return string[]
     */
    public function segmentos()
    {
        return [
            'A',
            'B'
        ];
    }

    /**
     * @param TransferenciaTed $ted
     * @return $this
     */
    public function adicionar(TransferenciaTed $ted)
    {
        array_push($this->conteudo, $ted);
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
            'layout_lote' => '045',
            'codigo_lote' => 0,
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
            'forma_pagamento' => $banco->formaPagamentoTed(),
            'total_qtd_registros'=> 0,
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