<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Favorecido;

class FavorecidoConta
{
    /**
     * @var mixed
     */
    public $tipoConta;
    /**
     * @var mixed
     */
    public $agencia;
    /**
     * @var mixed
     */
    public $conta;
    /**
     * @var mixed
     */
    public $conta_digito;
    /**
     * @var mixed
     */
    public $codigoBanco;

    /**
     * @param $codigoBanco
     * @param $tipoConta
     * @param $agencia
     * @param $conta
     * @param $conta_digito
     */
    public function __construct($codigoBanco, $tipoConta, $agencia, $conta, $conta_digito)
    {
        $this->tipoConta = filter_var($tipoConta, FILTER_SANITIZE_STRING);
        $this->agencia = filter_var($agencia, FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($conta, FILTER_SANITIZE_NUMBER_INT);
        $this->conta_digito = filter_var($conta_digito, FILTER_SANITIZE_STRING);
        $this->codigoBanco = filter_var($codigoBanco, FILTER_SANITIZE_NUMBER_INT);
    }
}