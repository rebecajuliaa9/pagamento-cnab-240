<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

class TransferenciaPix implements Pagamento
{
    const TELEFONE = '01';
    const EMAIL = '02';
    const DOCUMENTO = '03';
    const CHAVEALEATORIA = '04';
    /**
     * @var Favorecido
     */
    private $favorecido;
    private $dataPagamento;
    private $seuNumero;
    private $tipoChave;
    private $chave;

    public function __construct(Favorecido $favorecido, $valorPagamento, $dataPagamento, $seuNumero, $tipoChave, $chave)
    {
        $this->favorecido = $favorecido;
        $this->valorPagamento = $valorPagamento;
        $this->dataPagamento = $dataPagamento;
        $this->seuNumero = $seuNumero;
        $this->tipoChave = $tipoChave;
        $this->chave = $chave;
    }

    public function conteudo(Banco $banco, Transacao $transacao)
    {
        /** numero_registro: Obrigatório passar valor zero, o valor é calculado automaticamente
         forma_iniciacao:
         01” – Chave Pix – tipo Telefone
        “02” – Chave Pix – tipo Email
        “03” – Chave Pix – tipo CPF/CNPJ
        “04” – Chave Aleatoria
        “05” - Dados Bancários
         */
        $camara_centralizadora = '009';
        return [
            'codigo_lote' => $transacao->codigoLote(),
            'tipo_movimento' => 0,
            'camara_centralizadora' => $camara_centralizadora,
            'tipo_inscricao_pagador' => $banco->conta->empresa->tipoInscricao,
            'numero_inscricao_pagador' => $banco->conta->empresa->inscricao,
            'nome_pagador' => $banco->conta->empresa->nome,
            'tipo_inscricao_favorecido' => $this->favorecido->tipoInscricao,
            'numero_inscricao_favorecido' => $this->favorecido->inscricao,
            'nome_favorecido' => $this->favorecido->nome,
            'numero_registro' => 0,
            'valor_pagamento' => Helper::valorParaNumero($this->valorPagamento),
            'data_pagamento' => Helper::formataDataParaRemessa($this->dataPagamento),
            'seu_numero' => $this->seuNumero,
            'forma_iniciacao' => $this->tipoChave,
            'chave' => $this->chave
        ];
    }
}