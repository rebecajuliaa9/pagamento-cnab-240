<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use http\Exception\InvalidArgumentException;
use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\FavorecidoConta;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

class TransferenciaMesmoBanco implements Pagamento
{
    /**
     * @var Favorecido
     */
    private $favorecido;
    /**
     * @var FavorecidoConta
     */
    private $conta;
    private $dataPagamento;
    private $seuNumero;

    public function __construct(Favorecido $favorecido, FavorecidoConta $conta, $valorPagamento, $dataPagamento, $seuNumero)
    {
        $this->favorecido = $favorecido;
        $this->conta = $conta;
        $this->valorPagamento = filter_var($valorPagamento, FILTER_SANITIZE_STRING);
        $this->dataPagamento = filter_var($dataPagamento, FILTER_SANITIZE_STRING);
        $this->seuNumero = filter_var($seuNumero, FILTER_SANITIZE_STRING);
    }

    public function conteudo(Banco $banco, Transacao $transacao)
    {
        /**
         * Valido se a transação é no mesmo banco
         * @throw InvalidArgumentException
         */
        if($banco->numero() != $this->conta->codigoBanco){
            throw new InvalidArgumentException('O banco passado não é o mesmo que o banco que fará a transferência! Utilize a opção TED!');
        }
        return [
            'codigo_lote' => 0,
            'tipo_movimento' => 0,
            'tipo_inscricao_pagador' => $banco->conta->empresa->tipoInscricao,
            'numero_inscricao_pagador' => $banco->conta->empresa->inscricao,
            'nome_pagador' => $banco->conta->empresa->nome,
            'tipo_inscricao_favorecido' => $this->favorecido->tipoInscricao,
            'numero_inscricao_favorecido' => $this->favorecido->inscricao,
            'codigo_banco_favorecido' => $this->conta->codigoBanco,
            'nome_favorecido' => $this->favorecido->nome,
            'agencia_favorecido' => $this->conta->agencia,
            'conta_favorecido' => $this->conta->conta,
            'conta_digito_favorecido' => $this->conta->conta_digito,
            'numero_registro' => 0,
            'valor_pagamento' => Helper::valorParaNumero($this->valorPagamento),
            'data_pagamento' => Helper::formataDataParaRemessa($this->dataPagamento),
            'seu_numero' => $this->seuNumero,
        ];
    }
}