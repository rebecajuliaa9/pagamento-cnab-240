<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Transacoes;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;

interface Transacao
{
    /**
     * @return mixed
     */
    public function segmentos();

    /**
     * @param Banco $banco
     * @return mixed
     */
    public function headerLote(Banco $banco);

    /**
     * @param Banco $banco
     * @return mixed
     */
    public function trailerLote(Banco $banco);
}
