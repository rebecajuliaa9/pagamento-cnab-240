<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Favorecido;

class FavorecidoConta
{
    public $tipoConta;
    public $agencia;
    public $conta;
    public $conta_digito;
    public $codigoBanco;

    public function __construct($codigoBanco, $tipoConta, $agencia, $conta, $conta_digito)
    {
        $this->tipoConta = filter_var($tipoConta, FILTER_SANITIZE_STRING);
        $this->agencia = filter_var($agencia, FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($conta, FILTER_SANITIZE_NUMBER_INT);
        $this->conta_digito = filter_var($conta_digito, FILTER_SANITIZE_STRING);
        $this->codigoBanco = filter_var($codigoBanco, FILTER_SANITIZE_NUMBER_INT);
    }
}