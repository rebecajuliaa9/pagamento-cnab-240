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
        $this->nome = $nome;
        $this->inscricao = Helper::limpaNumero($inscricao);
        $this->tipoInscricao = Helper::vericaTipoPessoa($inscricao);
    }
}
