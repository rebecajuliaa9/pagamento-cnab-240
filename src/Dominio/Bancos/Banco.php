<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

use Leandroferreirama\PagamentoCnab240\Aplicacao\GerarArquivo;
use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

abstract class Banco
{
    private $codigoArquivo;
    private $lotes;
    /**
     * @var Conta
     */
    public $conta;

    public function __construct($codigoArquivo, Conta $conta)
    {

        $this->codigoArquivo = $codigoArquivo;
        $this->lotes = [];
        $this->conta = $conta;
    }

    public function adicionar(Transacao $transacao)
    {
        array_push($this->lotes, $transacao);
        return $this;
    }

    public function gerarArquivo()
    {
        $gerarArquivo = new GerarArquivo($this);
        return $gerarArquivo->gerar();
    }

    public function recuperarLotes()
    {
        return $this->lotes;
    }

    public function trailerArquivo()
    {
        return [
            'total_qtd_registros' => 0,
            'total_qtd_lotes' => 0
        ];
    }

    public function pastaRemessa()
    {
        return HelperBanco::pastaRemessa($this->pastaBanco());
    }

    public function pastaRetorno()
    {
        return HelperBanco::pastaRetorno($this->pastaBanco());
    }

    abstract public function numero();
    abstract public function nome();
    abstract public function pastaBanco();
    abstract public function headerArquivo();
    abstract public function strPadNumero();
    abstract public function strPadTexto();
    abstract public function recuperarCodigoLote();
}