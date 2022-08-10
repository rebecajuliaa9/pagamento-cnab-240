<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Transacoes;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Pagamento;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaMesmoBanco;

class Transferencia implements Transacao
{
    private $conteudo;
    private $codigoLote;

    public function __construct($codigoLote)
    {
        $this->conteudo = [];
        $this->codigoLote = $codigoLote;
    }

    public function segmentos()
    {
        return [
            'A',
            'B'
        ];
    }

    public function adicionar(TransferenciaMesmoBanco $transferencia)
    {
        array_push($this->conteudo, $transferencia);
        return $this;
    }

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
            'forma_pagamento' => $banco->formaPagamentoMesmoBanco(),
            'total_qtd_registros'=> 0,
            'total_valor_pagtos' => 0
        ];

        return $headeLote;
    }

    public function trailerLote(Banco $banco)
    {
        return [
            'codigo_lote' => $this->codigoLote,
            'total_qtd_registros_lote' => 0,
            'total_valor_pagtos' => 0
        ];
    }
    public function recuperarConteudo()
    {
        return $this->conteudo;
    }

    public function codigoLote()
    {
        return $this->codigoLote;
    }
}