<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

class HelperBanco
{
    public static function pastaRemessa($banco)
    {
        $banco = ucfirst($banco);
        return realpath(__DIR__ . "/../../leiaute/{$banco}/remessa");
    }

    public static function pastaRetorno($banco)
    {
        $banco = ucfirst($banco);
        return realpath(__DIR__ . "/../../leiaute/{$banco}/returno");
    }
}