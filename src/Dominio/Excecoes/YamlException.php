<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Excecoes;

use Exception;

class YamlException extends Exception
{
    /**
     * @param $mensagem
     */
    public function __construct($mensagem){
        parent::__construct($mensagem);
    }
}