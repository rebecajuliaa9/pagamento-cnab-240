<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Pix;

class PixDadosBancarios implements Pix
{
    private $tipoChave = '05';

    private $codigoBanco;
    private $agencia;
    private $agenciaDv;
    private $conta;
    private $conta_digito;
    private $tipoConta;

    public function __construct($codigoBanco, $agencia, $agenciaDv, $conta, $conta_digito, $tipoConta)
    {
        $this->tipoConta = filter_var($tipoConta, FILTER_SANITIZE_STRING);
        $this->agencia = filter_var($agencia, FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($conta, FILTER_SANITIZE_NUMBER_INT);
        $this->conta_digito = filter_var($conta_digito, FILTER_SANITIZE_STRING);
        $this->codigoBanco = filter_var($codigoBanco, FILTER_SANITIZE_NUMBER_INT);
    }

    public function getTipoChave(): string
    {
        return $this->tipoChave;
    }

    public function getChave(): string
    {
        return '';
    }

    public function getCodigoBanco(): string { return $this->codigoBanco; }
    public function getAgencia(): string { return $this->agencia; }
    public function getConta(): string { return $this->conta; }
    public function getContaDigito(): string { return $this->conta_digito; }
    public function getTipoConta(): string { return $this->tipoConta; }
}
