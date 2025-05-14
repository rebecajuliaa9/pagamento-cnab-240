<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Empresa;

use RebecaJulia\PagamentoCnab240\Aplicacao\Helper;

/**
 * Classe responsável pela geração dos dados da empresa que deve ser instanciado nas classes derivadas do banco
 */
class Empresa
{
    /**
     * @var string
     */
    public $nome;
    /**
     * @var array|string|string[]|null
     */
    public $inscricao;
    /**
     * @var mixed
     */
    public $rua;
    /**
     * @var mixed
     */
    public $numero;
    /**
     * @var mixed
     */
    public $complemento;
    /**
     * @var array|string|string[]|null
     */
    public $cep;
    /**
     * @var mixed
     */
    public $cidade;
    /**
     * @var mixed
     */
    public $estado;
    /**
     * @var int
     */
    public $tipoInscricao;

    /**
     * @param $nome
     * @param $inscricao
     * @param $rua
     * @param $numero
     * @param $complemento
     * @param $cep
     * @param $cidade
     * @param $estado
     */
    public function __construct($nome, $inscricao, $rua = NULL, $numero = NULL, $complemento = NULL, $cep = NULL, $cidade = NULL, $estado = NULL)
    {
        $this->nome = mb_substr(filter_var($nome, FILTER_SANITIZE_STRING), 0, 30);
        $this->inscricao = Helper::limpaNumero(filter_var($inscricao, FILTER_SANITIZE_NUMBER_INT));
        $this->tipoInscricao = Helper::vericaTipoPessoa($this->inscricao);
        $this->rua = filter_var($rua, FILTER_SANITIZE_STRING);
        $this->numero = filter_var($numero, FILTER_SANITIZE_STRING);
        $this->complemento = filter_var($complemento, FILTER_SANITIZE_STRING);
        $this->cep = Helper::limpaNumero(filter_var($cep, FILTER_SANITIZE_NUMBER_INT));
        $this->cidade = filter_var($cidade, FILTER_SANITIZE_STRING);
        $this->estado = filter_var($estado, FILTER_SANITIZE_STRING);
    }
}