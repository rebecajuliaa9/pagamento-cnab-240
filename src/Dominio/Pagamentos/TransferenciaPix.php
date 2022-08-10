<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Constantes\TipoChave;
use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

class TransferenciaPix implements Pagamento
{
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
        $this->valorPagamento = filter_var($valorPagamento, FILTER_SANITIZE_STRING);
        $this->dataPagamento = filter_var($dataPagamento, FILTER_SANITIZE_STRING);
        $this->seuNumero = filter_var($seuNumero, FILTER_SANITIZE_STRING);
        $this->tipoChave = filter_var($tipoChave, FILTER_SANITIZE_STRING);
        $chave = filter_var($chave, FILTER_SANITIZE_STRING);
        if($this->tipoChave == TipoChave::TELEFONE){
            $chaveNumeros = preg_replace('/[^0-9]/', "", $chave);
            //incluo o + em frente
            $chave = '+'.filter_var($chaveNumeros, FILTER_SANITIZE_NUMBER_INT);
        }

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
            'codigo_lote' => 0,
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