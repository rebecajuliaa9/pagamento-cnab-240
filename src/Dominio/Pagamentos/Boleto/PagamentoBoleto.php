<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Boleto;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Pagamento;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

abstract class PagamentoBoleto implements Pagamento
{
    /**
     * @var mixed
     */
    protected $codigoBarras;
    /**
     * @var mixed
     */
    public $valorPagamento;
    /**
     * @var mixed
     */
    protected $dataPagamento;
    /**
     * @var mixed
     */
    protected $seuNumero;
    /**
     * @var int
     */
    protected $desconto;
    /**
     * @var int
     */
    protected $acrescimos;
    /**
     * @var Favorecido
     */
    protected $favorecido;

    /**
     * @param $codigoBarras
     * @param Favorecido $favorecido
     * @param $valorPagamento
     * @param $dataPagamento
     * @param $seuNumero
     * @param $desconto
     * @param $acrescimos
     */
    public function __construct($codigoBarras, Favorecido $favorecido, $valorPagamento, $seuNumero, $dataPagamento = null, $desconto = 0, $acrescimos = 0)
    {
        $this->codigoBarras = filter_var($codigoBarras, FILTER_SANITIZE_NUMBER_INT);
        $this->valorPagamento = filter_var($valorPagamento, FILTER_SANITIZE_STRING);
        $this->setDataPagamento($dataPagamento);
        $this->seuNumero = filter_var($seuNumero, FILTER_SANITIZE_STRING);
        $this->desconto = filter_var($desconto, FILTER_SANITIZE_STRING);
        $this->acrescimos = filter_var($acrescimos, FILTER_SANITIZE_STRING);
        $this->favorecido = $favorecido;
    }

    private function setDataPagamento($dataPagamento)
    {
        if (is_null($dataPagamento)) {
            $dataPagamento = date("Y-m-d");
        }
        $this->dataPagamento = filter_var($dataPagamento, FILTER_SANITIZE_STRING);
    }

    /**
     * @param Banco $banco
     * @param Transacao $transacao
     * @return mixed
     */
    abstract public function conteudo(Banco $banco, Transacao $transacao);
}