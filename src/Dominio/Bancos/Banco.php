<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Constantes\Arquivo;
use Leandroferreirama\PagamentoCnab240\Aplicacao\GerarArquivo;
use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;
use Leandroferreirama\PagamentoCnab240\Dominio\Excecoes\LeiauteException;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

abstract class Banco
{
    /**
     * @var mixed
     */
    protected $codigoArquivo;
    /**
     * @var array
     */
    private $lotes;
    /**
     * @var Conta
     */
    public $conta;

    /**
     * @param $codigoArquivo
     * @param Conta $conta
     */
    public function __construct($codigoArquivo, Conta $conta)
    {

        $this->codigoArquivo = filter_var($codigoArquivo, FILTER_SANITIZE_NUMBER_INT);
        $this->lotes = [];
        $this->conta = $conta;
    }

    /**
     * @param Transacao $transacao
     * @return $this
     */
    public function adicionar(Transacao $transacao)
    {
        array_push($this->lotes, $transacao);
        return $this;
    }

    /**
     * @return string
     * @throws LeiauteException
     * @param $retorno
     */
    public function gerarArquivo($retorno)
    {
        if(! isset($retorno)){
            throw new \InvalidArgumentException('Não localizei o tipo de retorno!');
        }
        if(! in_array($retorno, [1,2])){
            throw new \InvalidArgumentException('O tipo de retorno é inválido!');
        }
        $gerarArquivo = new GerarArquivo($this);
        return $gerarArquivo->gerar($retorno);
    }

    /**
     * @return array
     */
    public function recuperarLotes()
    {
        return $this->lotes;
    }

    /**
     * @return int[]
     */
    public function trailerArquivo()
    {
        return [
            'total_qtd_registros' => 0,
            'total_qtd_lotes' => 0
        ];
    }

    /**
     * @return false|string
     */
    public function pastaRemessa()
    {
        return HelperBanco::pastaRemessa($this->pastaBanco());
    }

    /**
     * @return mixed
     */
    abstract public function numero();

    /**
     * @return mixed
     */
    abstract public function nome();

    /**
     * @return mixed
     */
    abstract public function pastaBanco();

    /**
     * @return mixed
     */
    abstract public function headerArquivo();

    /**
     * @return mixed
     */
    abstract public function strPadNumero();

    /**
     * @return mixed
     */
    abstract public function strPadTexto();

    /**
     * @return mixed
     */
    abstract public function recuperarCodigoArquivo();

    /**
     * @return mixed
     */
    abstract public function formaPagamentoMesmoBanco();

    /**
     * @return mixed
     */
    abstract public function formaPagamentoTed();
}