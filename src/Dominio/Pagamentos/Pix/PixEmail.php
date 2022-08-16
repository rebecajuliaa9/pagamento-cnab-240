<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Pix;


class PixEmail implements Pix
{
    private $tipoChave;
    private $chave;

    /**
     * @param $tipoChave
     * @param $chave
     */
    public function __construct($chave)
    {
        $this->tipoChave = '02';
        $this->setChave($chave);
    }

    private function setChave($chave)
    {
        $chave = filter_var($chave, FILTER_SANITIZE_STRING);
        if(! filter_var($chave, FILTER_VALIDATE_EMAIL)){
            throw new \InvalidArgumentException("E-mail: {$chave}, passado na chave pix inválido");
        }
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