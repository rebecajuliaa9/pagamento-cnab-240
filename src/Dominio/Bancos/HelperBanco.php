<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

/**
 * Classe de apoio para as classes do banco
 */
class HelperBanco
{
    /**
     * @param $banco
     * @return false|string
     */
    public static function pastaRemessa($banco)
    {
        $banco = ucfirst($banco);
        return realpath(__DIR__ . "/../../leiaute/{$banco}");
    }
}