<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

abstract class PagamentoBoleto implements Pagamento
{
    protected $codigoBarras;
    public $valorPagamento;
    protected $dataPagamento;
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

    public function __construct($codigoBarras, Favorecido $favorecido, $valorPagamento, $dataPagamento, $seuNumero, $desconto = 0, $acrescimos = 0)
    {
        $this->codigoBarras = filter_var($codigoBarras, FILTER_SANITIZE_NUMBER_INT);
        $this->valorPagamento = filter_var($valorPagamento, FILTER_SANITIZE_STRING);
        $this->dataPagamento = filter_var($dataPagamento, FILTER_SANITIZE_STRING);
        $this->seuNumero = filter_var($seuNumero, FILTER_SANITIZE_STRING);
        $this->desconto = filter_var($desconto, FILTER_SANITIZE_STRING);
        $this->acrescimos = filter_var($acrescimos, FILTER_SANITIZE_STRING);
        $this->favorecido = $favorecido;
    }

    abstract public function conteudo(Banco $banco, Transacao $transacao);
}