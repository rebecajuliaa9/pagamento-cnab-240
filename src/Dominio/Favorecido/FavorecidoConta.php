<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Favorecido;

class FavorecidoConta
{
    const CONTACORRENTE = 'CC';
    const POUPANCA = 'PP';

    public $tipoConta;
    public $agencia;
    public $conta;
    public $conta_digito;
    public $codigoBanco;

    public function __construct($codigoBanco, $tipoConta, $agencia, $conta, $conta_digito)
    {
        $this->tipoConta = $tipoConta;
        $this->agencia = $agencia;
        $this->conta = $conta;
        $this->conta_digito = $conta_digito;
        $this->codigoBanco = $codigoBanco;
    }
}