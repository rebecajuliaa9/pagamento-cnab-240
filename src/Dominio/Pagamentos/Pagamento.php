<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

interface Pagamento
{
    /**
     * @param Banco $banco
     * @param Transacao $transacao
     * @return mixed
     */
    public function conteudo(Banco $banco, Transacao $transacao);
}