<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Pagamentos;

use RebecaJulia\PagamentoCnab240\Dominio\Bancos\Banco;
use RebecaJulia\PagamentoCnab240\Dominio\Transacoes\Transacao;

interface Pagamento
{
    /**
     * @param Banco $banco
     * @param Transacao $transacao
     * @return mixed
     */
    public function conteudo(Banco $banco, Transacao $transacao);
}