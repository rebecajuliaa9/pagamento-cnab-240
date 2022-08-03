<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Transacoes;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Pagamento;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\PagamentoTransferencia;

class Pix implements Transacao
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
            'B-PIX'
        ];
    }

    public function adicionar(Pagamento $pagamento)
    {
        array_push($this->conteudo, $pagamento);
        return $this;
    }

    public function headerLote(Banco $banco)
    {
        /**
         * forma_pagamento: 45 Pix transferência
         */
        $empresa = $banco->conta->empresa;
        return [
            'layout_lote' => '045',
            'codigo_lote' => $this->codigoLote,
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
            'codigo_convenio' => $banco->recuperarCodigoConvenio(),
            'tipo_pagamento' => 20,
            'forma_pagamento' => 45,
            'total_qtd_registros'=> 0,
            'total_valor_pagtos' => 0
        ];
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