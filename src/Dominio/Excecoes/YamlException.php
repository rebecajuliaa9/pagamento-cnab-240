<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Excecoes;

use Exception;

class YamlException extends Exception
{
    public function __construct($mensagem){
        parent::__construct($mensagem);
    }
}