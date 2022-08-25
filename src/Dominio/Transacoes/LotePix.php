<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Transacoes;

use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\Pix\TransferenciaPix;

class LotePix implements Transacao
{
    /**
     * @var array
     */
    private $conteudo;

    /**
     *
     */
    public function __construct()
    {
        $this->conteudo = [];
    }

    /**
     * @return string[]
     */
    public function segmentos()
    {
        return [
            'A',
            'B-PIX'
        ];
    }

    /**
     * @param TransferenciaPix $pix
     * @return $this
     */
    public function adicionar(TransferenciaPix $pix)
    {
        array_push($this->conteudo, $pix);
        return $this;
    }

    /**
     * @param Banco $banco
     * @return array
     */
    public function headerLote(Banco $banco)
    {
        /**
         * forma_pagamento: 45 LotePix transferência
         */
        $empresa = $banco->conta->empresa;
        $headeLote = [];
        $layout_lote = '040';
        if (method_exists($banco, "recuperarCodigoConvenio")) {
            $headeLote = $headeLote + ['codigo_convenio' => $banco->recuperarCodigoConvenio()];
            $layout_lote = '045';
        }
        $headeLote = $headeLote + [
            'layout_lote' => $layout_lote,
            'codigo_lote' => '0',
            'inscricao_numero' => $empresa->inscricao,
            'empresa_inscricao' => $empresa->tipoInscricao,
            'agencia' => $banco->conta->agencia,
            'conta' => $banco->conta->conta,
            'conta_digito' => $banco->conta->contaDv,
            'nome_empresa' => $empresa->nome,
            'endereco_empresa' => $empresa->rua,
            'numero' => $empresa->numero,
            'complemento' => $empresa->complemento,
            'cep' => $empresa->cep,
            'cidade' => $empresa->cidade,
            'estado' => $empresa->estado,
            'tipo_pagamento' => 20,
            'forma_pagamento' => 45,
            'total_qtd_registros'=> 0,
            'total_valor_pagtos' => 0
        ];
        return $headeLote;
    }

    /**
     * @param Banco $banco
     * @return int[]
     */
    public function trailerLote(Banco $banco)
    {
        return [
            'codigo_lote' => 0,
            'total_qtd_registros_lote' => 0,
            'total_valor_pagtos' => 0
        ];
    }

    /**
     * @return array
     */
    public function recuperarConteudo()
    {
        return $this->conteudo;
    }
}