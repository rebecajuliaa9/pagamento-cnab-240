<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Pix;

class PixDocumento implements Pix
{
    private $tipoChave;
    private $chave;

    /**
     * @param $tipoChave
     * @param $chave
     */
    public function __construct($chave)
    {
        $this->tipoChave = '03';
        $this->setChave($chave);
    }

    private function setChave($chave)
    {
        $chaveBruta = filter_var($chave, FILTER_SANITIZE_STRING);
        $chave = preg_replace('/[^0-9]/', "", $chaveBruta);

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