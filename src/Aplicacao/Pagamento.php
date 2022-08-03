<?php

namespace Leandroferreirama\PagamentoCnab240\Aplicacao;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Servicos\Remessa;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\DadosArquivo;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

class Pagamento
{
    protected $banco;
    protected $transacao;

    public function __construct(Banco $banco, Transacao $transacao)
    {
        $this->banco = $banco;
        $this->transacao = $transacao;
    }

    public function gerarArquivo(DadosArquivo $dados){
        $remessa = new Remessa($this->banco, $this->transacao);
        $remessa->gerarArquivo($dados);
    }
}