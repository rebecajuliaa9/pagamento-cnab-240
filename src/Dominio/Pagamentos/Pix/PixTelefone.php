<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Pagamentos\Pix;

class PixTelefone implements Pix
{
    private $tipoChave;
    private $chave;

    /**
     * @param $tipoChave
     * @param $chave
     */
    public function __construct($chave)
    {
        $this->tipoChave = '01';
        $this->setChave($chave);
    }

    private function setChave($chave)
    {
        $chaveBruta = filter_var($chave, FILTER_SANITIZE_STRING);
        $chaveNumeros = preg_replace('/[^0-9]/', "", $chaveBruta);
        if(strlen($chaveNumeros) < 10){
            throw new \InvalidArgumentException('A chave do telefone precisa ser obrigatoriamente passado o DDD + Telefone');
        }
        if(strlen($chaveNumeros) < 12){
            $chaveNumeros = '55'.$chaveNumeros;
        }
        //incluo o + em frente
        $chave = '+'.filter_var($chaveNumeros, FILTER_SANITIZE_NUMBER_INT);

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