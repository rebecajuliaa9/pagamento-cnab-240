<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;

class Bradesco extends Banco
{
    private $codigoArquivo;
    private $codigoConvenio;
    private $pix;

    public function __construct($codigoArquivo, Conta $conta, $codigoConvenio, $pix = '')
    {
        parent::__construct($codigoArquivo, $conta);
        $this->codigoArquivo = $codigoArquivo;
        $this->conta = $conta;
        $this->codigoConvenio = $codigoConvenio;
        $this->pix = $pix;
    }

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

    public function numero()
    {
        return 237;
    }

    public function nome()
    {
        return 'BRADESCO';
    }

    public function pastaBanco()
    {
        return 'Bradesco';
    }

    public function strPadNumero()
    {
        return STR_PAD_LEFT;
    }

    public function strPadTexto()
    {
        return STR_PAD_RIGHT;
    }

    public function recuperarCodigoLote()
    {
        return $this->codigoArquivo;
    }

    public function recuperarCodigoConvenio()
    {
        return $this->codigoConvenio;
    }
}