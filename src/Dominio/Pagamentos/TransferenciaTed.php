<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use http\Exception\InvalidArgumentException;
use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\FavorecidoConta;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

class TransferenciaTed implements Pagamento
{
    /**
     * @var Favorecido
     */
    private $favorecido;
    /**
     * @var FavorecidoConta
     */
    private $conta;
    /**
     * @var mixed
     */
    private $dataPagamento;
    /**
     * @var mixed
     */
    private $seuNumero;

    /**
     * @param Favorecido $favorecido
     * @param FavorecidoConta $conta
     * @param $valorPagamento
     * @param $dataPagamento
     * @param $seuNumero
     */
    public function __construct(Favorecido $favorecido, FavorecidoConta $conta, $valorPagamento, $seuNumero, $dataPagamento = null)
    {
        $this->favorecido = $favorecido;
        $this->conta = $conta;
        $this->valorPagamento = filter_var($valorPagamento, FILTER_SANITIZE_STRING);
        $this->seuNumero = filter_var($seuNumero, FILTER_SANITIZE_STRING);
        $this->setDataPagamento($dataPagamento);
    }

    private function setDataPagamento($dataPagamento)
    {
        if(is_null($dataPagamento)){
            $dataPagamento = date("Y-m-d");
        }
        $this->dataPagamento = filter_var($dataPagamento, FILTER_SANITIZE_STRING);
    }

    /**
     * @param Banco $banco
     * @param Transacao $transacao
     * @return array
     */
    public function conteudo(Banco $banco, Transacao $transacao)
    {
        /**
         * Valido se a transação é no mesmo banco
         * @throw InvalidArgumentException
         */
        if($banco->numero() == $this->conta->codigoBanco){
            throw new InvalidArgumentException('O banco passado é o mesmo que o banco que fará a transferência! Utilize a opção Transferência mesma conta!');
        }
        //Monto os parâmetros
        $codigoFinalidadeTed = '00007';
        $tipoConta = $this->conta->tipoConta;
        $camara_centralizadora = '018';

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
            'codigo_banco_favorecido' => $this->conta->codigoBanco,
            'agencia_favorecido' => $this->conta->agencia,
            'conta_favorecido' => $this->conta->conta,
            'conta_digito_favorecido' => $this->conta->conta_digito,
            'numero_registro' => 0,
            'valor_pagamento' => Helper::valorParaNumero($this->valorPagamento),
            'data_pagamento' => Helper::formataDataParaRemessa($this->dataPagamento),
            'seu_numero' => $this->seuNumero,
            'codigo_finalidade_ted' => $codigoFinalidadeTed,
            'tipo_conta' => $tipoConta,
        ];
    }
}