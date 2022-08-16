<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;

/**
 * Classe responsável por gerar um arquivo no banco Itaú
 */
class Itau extends Banco
{
    /**
     * @param $codigoArquivo
     * @param Conta $conta
     */
    public function __construct($codigoArquivo, Conta $conta)
    {
        parent::__construct($codigoArquivo, $conta);
    }

    /**
     * @return string
     */
    public function numero()
    {
        return '341';
    }

    /**
     * @return string
     */
    public function nome()
    {
        return 'Banco Itau';
    }

    /**
     * @return string
     */
    public function pastaBanco()
    {
        return 'itau';
    }

    /**
     * @return array
     */
    public function headerArquivo()
    {
        return[
            'codigo_banco' => $this->numero(),
            'nome_banco' => $this->nome(),
            'codigo_arquivo' => $this->codigoArquivo,
            'agencia' => $this->conta->agencia,
            'conta' => $this->conta->conta,
            'conta_digito' => $this->conta->contaDv,
            'nome_empresa' => $this->conta->empresa->nome,
            'empresa_inscricao' => $this->conta->empresa->tipoInscricao,
            'inscricao_numero' => $this->conta->empresa->inscricao,
            'data_geracao' =>date("dmY"),
            'hora_geracao'=> date("His")

        ];
    }

    /**
     * @return int
     */
    public function strPadNumero()
    {
        return STR_PAD_LEFT;
    }

    /**
     * @return int
     */
    public function strPadTexto()
    {
        return STR_PAD_RIGHT;
    }

    /**
     * @return mixed
     */
    public function recuperarCodigoArquivo()
    {
        return $this->codigoArquivo;
    }

    /**
     * @return string
     */
    public function formaPagamentoMesmoBanco()
    {
        return '01';
    }

    /**
     * @return string
     */
    public function formaPagamentoTed()
    {
        return '41';
    }
}