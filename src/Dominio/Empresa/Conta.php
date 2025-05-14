<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Empresa;


/**
 * Classe responsável pela geração dos dados da conta que deve ser instanciado nas classes derivadas do banco
 */
class Conta
{
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
    public $contaDv;
    /**
     * @var Empresa
     */
    public $empresa;

    /**
     * @param $agencia
     * @param $conta
     * @param $contaDv
     * @param Empresa $empresa
     */
    public function __construct($agencia, $conta, $contaDv, Empresa $empresa)
    {
        $this->agencia = filter_var($agencia, FILTER_SANITIZE_NUMBER_INT);
        $this->conta = filter_var($conta, FILTER_SANITIZE_NUMBER_INT);
        $this->contaDv = filter_var($contaDv, FILTER_SANITIZE_STRING);
        $this->empresa = $empresa;
    }
}