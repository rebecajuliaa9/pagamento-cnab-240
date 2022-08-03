<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;

class Empresa
{
    public $nome;
    public $inscricao;
    public $rua;
    public $numero;
    public $complemento;
    public $cep;
    public $cidade;
    public $estado;
    public $tipoInscricao;

    public function __construct($nome, $inscricao, $rua, $numero, $complemento, $cep, $cidade, $estado)
    {
        $this->nome = $nome;
        $this->inscricao = Helper::limpaNumero($inscricao);
        $this->tipoInscricao = Helper::vericaTipoPessoa($inscricao);
        $this->rua = $rua;
        $this->numero = $numero;
        $this->complemento = $complemento;
        $this->cep = Helper::limpaNumero($cep);
        $this->cidade = $cidade;
        $this->estado = $estado;
    }
}