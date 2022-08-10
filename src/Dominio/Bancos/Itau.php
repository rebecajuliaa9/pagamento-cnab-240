<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;

class Itau extends Banco
{
    public function __construct($codigoArquivo, Conta $conta)
    {
        parent::__construct($codigoArquivo, $conta);
    }

    public function numero()
    {
        return '341';
    }

    public function nome()
    {
        return 'Banco Itau';
    }

    public function pastaBanco()
    {
        return 'itau';
    }

    public function headerArquivo()
    {
        return[
            'codigo_banco' => $this->numero(),
            'nome_banco' => $this->nome(),
            'codigo_arquivo' => $this->codigoArquivo,
            'agencia' => $this->conta->agencia,
            'conta' => $this->conta->conta,
            'conta_digito' => $this->conta->contaDv,
            'nome_empresa' => $this->conta->empresa->nome,
            'empresa_inscricao' => $this->conta->empresa->tipoInscricao,
            'inscricao_numero' => $this->conta->empresa->inscricao,
            'data_geracao' =>date("dmY"),
            'hora_geracao'=> date("His")

        ];
    }

    public function strPadNumero()
    {
        return STR_PAD_LEFT;
    }

    public function strPadTexto()
    {
        return STR_PAD_RIGHT;
    }

    public function recuperarCodigoArquivo()
    {
        return $this->codigoArquivo;
    }

    public function formaPagamentoMesmoBanco()
    {
        return '01';
    }

    public function formaPagamentoTed()
    {
        return '41';
    }
}