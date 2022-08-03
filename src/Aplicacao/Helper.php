<?php

namespace Leandroferreirama\PagamentoCnab240\Aplicacao;

class Helper
{
    public static function picture($picture)
    {
        return preg_match('/[X9]\(\d+\)(V9\(\d+\))?/', $picture);
    }

    public static function explodePicture($picture)
    {
        $pictureExploded = explode("-", preg_replace("/[^0-9A-Z]/", "-", $picture));

        return [
            'firstType' => $pictureExploded[0],
            'firstQuantity' => $pictureExploded[1],
            'secondType' => !isset($pictureExploded[2])?: $pictureExploded[2],
            'secondQuantity' => !isset($pictureExploded[3])?: $pictureExploded[3]
        ];
    }

    public static function desconstrutorCodigoBarras($barcode){
        $clearBarcode = preg_replace("/[^0-9]/", '', $barcode);
        
        $banco = mb_substr($clearBarcode, 0, 3);
        $codigo_moeda = mb_substr($clearBarcode, 3, 1);
        $digito_verificador = mb_substr($clearBarcode, 32, 1);
        $favor_vencimento = mb_substr($clearBarcode, 33, 4);
        $valor = mb_substr($clearBarcode, 37, 10);
        $campo_livre = mb_substr($clearBarcode, 4, 5).mb_substr($clearBarcode, 10, 10).mb_substr($clearBarcode, 21, 10);
        
        return [
            "banco" => $banco,
            "codigo_moeda" => $codigo_moeda,
            "digito_verificador" => $digito_verificador,
            "fator_vencimento" => $favor_vencimento,
            "valor" => $valor,
            "campo_livre" => $campo_livre
        ];
        
    }

    public static function converterFavorVencimentoEmData($fatorVencimento)
    {
        $intervaloData = new \DateInterval("P{$fatorVencimento}D");
        $dataBase = new \DateTime("1997-10-07");
        return $dataBase->add($intervaloData)->format("dmY");
    }

    /**
     * @param $valor
     * @return string
     */
    public static function valorParaNumero($valor)
    {
        $valor = self::formatoBrlParaUS($valor);

        return number_format($valor, 2, '', '');
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function formatoBrlParaUS($valor)
    {
        $formatoBrl = preg_match('/,/', $valor);
        if($formatoBrl) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }

        return $valor;
    }

    public static function limpaNumero($dado)
    {
        return preg_replace('/[^0-9]/', '', (string) $dado);
    }

    /**
     * @param $documento
     * @return int
     */
    public static function vericaTipoPessoa($documento)
    {
        $documento = self::limpaNumero($documento);
        if (mb_strlen($documento) == 14){
            return TipoInscricao::CNPJ;
        }
        return TipoInscricao::CPF;
    }

    /**
     * @param $date
     * @return mixed
     */
    public static function formataDataParaRemessa($date)
    {
        $formatoBrasileiro = preg_match('/\d{2}\/\d{2}\/\d{4}/', $date);
        if($formatoBrasileiro){
            return \DateTime::createFromFormat('d/m/Y', $date)->format('dmY');
        }


        $formatoAmericano = preg_match('/\d{4}\-\d{2}\-\d{2}/', $date);
        if($formatoAmericano){
            return \DateTime::createFromFormat('Y-m-d', $date)->format('dmY');
        }
    }
}
