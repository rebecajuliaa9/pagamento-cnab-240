<?php

namespace RebecaJulia\PagamentoCnab240\Dominio\Bancos;

use RebecaJulia\PagamentoCnab240\Dominio\Empresa\Conta;

/**
 * Classe responsável por gerar um arquivo no banco Bradesco
 */
class Bradesco extends Banco
{
    /**
     * @var mixed
     */
    private $codigoConvenio;
    /**
     * @var mixed
     */
    private $pix;

    /**
     * @param $codigoArquivo
     * @param Conta $conta
     * @param $codigoConvenio
     * @param $pix
     */
    public function __construct($codigoArquivo, Conta $conta, $codigoConvenio, $pix = '')
    {
        parent::__construct(filter_var($codigoArquivo, FILTER_SANITIZE_NUMBER_INT), $conta);
        $this->codigoConvenio = filter_var($codigoConvenio, FILTER_SANITIZE_NUMBER_INT);
        $this->pix = filter_var($pix, FILTER_SANITIZE_STRING);
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
            'codigo_convenio' => $this->codigoConvenio,
            'data_geracao' =>date("dmY"),
            'hora_geracao'=> date("His"),
            'pix' => $this->pix

        ];
    }

    /**
     * @return int
     */
    public function numero()
    {
        return 237;
    }

    /**
     * @return string
     */
    public function nome()
    {
        return 'BRADESCO';
    }

    /**
     * @return string
     */
    public function pastaBanco()
    {
        return 'Bradesco';
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
     * @return mixed
     */
    public function recuperarCodigoConvenio()
    {
        return $this->codigoConvenio;
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
        return '03';
    }
}