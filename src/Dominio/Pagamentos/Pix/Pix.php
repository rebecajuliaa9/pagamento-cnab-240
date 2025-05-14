<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Pix;

interface Pix
{
    public function getTipoChave();
    public function getChave();
}