<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;

class Conta
{
    public $agencia;
    public $conta;
    public $contaDv;
    public $empresa;

    public function __construct($agencia, $conta, $contaDv, Empresa $empresa)
    {
        $this->agencia = $agencia;
        $this->conta = $conta;
        $this->contaDv = $contaDv;
        $this->empresa = $empresa;
    }
}