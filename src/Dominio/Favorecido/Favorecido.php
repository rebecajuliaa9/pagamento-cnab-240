<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Favorecido;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;

class Favorecido
{
    /**
     * @var string
     */
    public $nome;
    /**
     * @var string
     */
    public $inscricao;
    /**
     * @var int
     */
    public $tipoInscricao;

    /**
     * @param $nome
     * @param $inscricao
     */
    public function __construct($nome, $inscricao)
    {
        $this->nome = mb_substr(filter_var($nome, FILTER_SANITIZE_STRING), 0, 30);
        $this->inscricao = Helper::limpaNumero(filter_var($inscricao, FILTER_SANITIZE_NUMBER_INT));
        $this->tipoInscricao = Helper::vericaTipoPessoa($this->inscricao);
    }
}
