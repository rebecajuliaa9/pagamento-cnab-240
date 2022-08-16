<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Pix;


class PixAleatoria implements Pix
{
    private $tipoChave;
    private $chave;

    /**
     * @param $tipoChave
     * @param $chave
     */
    public function __construct($chave)
    {
        $this->tipoChave = '04';
        $this->setChave($chave);
    }

    private function setChave($chave)
    {
        $chave = filter_var($chave, FILTER_SANITIZE_STRING);

        $this->chave = $chave;
    }

    public function getTipoChave()
    {
        return $this->tipoChave;
    }

    public function getChave()
    {
        return $this->chave;
    }
}