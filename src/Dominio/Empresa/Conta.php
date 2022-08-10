<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Empresa;


class Conta
{
    public $agencia;
    public $conta;
    public $contaDv;
    public $empresa;

    public function __construct($agencia, $conta, $contaDv, Empresa $empresa)
    {
        $this->agencia = filter_var($agencia, FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($conta, FILTER_SANITIZE_NUMBER_INT);
        $this->contaDv = filter_var($contaDv, FILTER_SANITIZE_STRING);
        $this->empresa = $empresa;
    }
}