<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

use Leandroferreirama\PagamentoCnab240\Dominio\Conta;
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

    abstract public function numero();
    abstract public function nome();
    abstract public function pastaRemessa();
    abstract public function pastaRetorno();
    abstract public function headerArquivo();
    abstract public function strPadNumero();
    abstract public function strPadTexto();
    abstract public function recuperarCodigoLote();
}