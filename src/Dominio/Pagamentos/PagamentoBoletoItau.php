<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos;

use Leandroferreirama\PagamentoCnab240\Aplicacao\Helper;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Banco;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transacao;

class PagamentoBoletoItau extends PagamentoBoleto
{

    public function conteudo(Banco $banco, Transacao $transacao)
    {
        $arrayCodigoBarras = Helper::desconstrutorCodigoBarras($this->codigoBarras);
        $codigoBarras = implode('', $arrayCodigoBarras);
        //tipo o 4 campo
        $calculoDac = mb_substr($codigoBarras, 0, 3).mb_substr($codigoBarras, 4);
        $dac = Helper::calculoDAC($calculoDac);
        /**
         * ParÂmetros personalizados do pagamento com boleto
         * numero_registro -> Obrigatório passar valor zero, o valor é calculado automaticamente
         */
        /*return [

            'codigo_lote' => $transacao->codigoLote(),
            'tipo_movimento' => 0,
            'tipo_inscricao_pagador' => $banco->conta->empresa->tipoInscricao,
            'numero_inscricao_pagador' => $banco->conta->empresa->inscricao,
            'nome_pagador' => $banco->conta->empresa->nome,
            'tipo_inscricao_beneficiario' => $this->favorecido->tipoInscricao,
            'numero_inscricao_beneficiario' => $this->favorecido->inscricao,
            'nome_beneficiario' => $this->favorecido->nome,
            'tipo_inscricao_sacador' => $this->favorecido->tipoInscricao,
            'numero_inscricao_sacador' => $this->favorecido->inscricao,
            'nome_sacador' => $this->favorecido->nome,
            'banco_favorecido' => $arrayCodigoBarras["banco"],
            'moeda' => $arrayCodigoBarras["codigo_moeda"],
            'dv' => $arrayCodigoBarras["digito_verificador"],
            'data_vencimento' => Helper::converterFavorVencimentoEmData($arrayCodigoBarras["fator_vencimento"]),
            'vencimento' =>$arrayCodigoBarras["fator_vencimento"],
            'valor' => $arrayCodigoBarras["valor"],
            'campo_livre' => $arrayCodigoBarras["campo_livre"],
            'numero_registro' => 0,
            'valor_pagamento' => Helper::valorParaNumero($this->valorPagamento),
            'data_pagamento' => Helper::formataDataParaRemessa($this->dataPagamento),
            'seu_numero' => $this->seuNumero,
        ];*/

        return [
            'codigo_lote' => 0,
            'numero_registro' => 0,
            'banco_favorecido' => $arrayCodigoBarras["banco"],
            'moeda' => $arrayCodigoBarras["codigo_moeda"],
            'dv' => $arrayCodigoBarras["digito_verificador"],
            'fator_vencimento' =>$arrayCodigoBarras["fator_vencimento"],
            'valor' => $arrayCodigoBarras["valor"],
            'campo_livre' => $arrayCodigoBarras["campo_livre"],
            'nome_favorecido' => $this->favorecido->nome,
            'data_vencimento' => Helper::converterFavorVencimentoEmData($arrayCodigoBarras["fator_vencimento"]),
            'valor_titulo' => $arrayCodigoBarras["valor"],
            'data_pagamento' => Helper::formataDataParaRemessa($this->dataPagamento),
            'valor_pagamento' => Helper::valorParaNumero($this->valorPagamento),
            'descontos' => Helper::valorParaNumero($this->desconto),
            'acrescimos' => Helper::valorParaNumero($this->acrescimos),
            'seu_numero' => $this->seuNumero,
            'tipo_inscricao_pagador' => $banco->conta->empresa->tipoInscricao,
            'numero_inscricao_pagador' => $banco->conta->empresa->inscricao,
            'nome_pagador' => $banco->conta->empresa->nome,
            'tipo_inscricao_beneficiario' => $this->favorecido->tipoInscricao,
            'numero_inscricao_beneficiario' => $this->favorecido->inscricao,
            'nome_beneficiario' => $this->favorecido->nome,
            'tipo_inscricao_sacador' => $this->favorecido->tipoInscricao,
            'numero_inscricao_sacador' => $this->favorecido->inscricao,
            'nome_sacador' => $this->favorecido->nome,
        ];
    }
}