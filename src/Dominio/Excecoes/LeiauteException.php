<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Excecoes;

use Exception;

class LeiauteException extends Exception
{
    /**
     * @param $mensagem
     */
    public function __construct($mensagem){
        parent::__construct($mensagem);
    }
}