<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Transacoes;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;

interface Transacao
{
    public function codigoLote();
    public function segmentos();
    public function headerLote(Banco $banco);
    public function trailerLote(Banco $banco);
}
