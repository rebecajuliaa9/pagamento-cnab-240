<?php

namespace Leandroferreirama\PagamentoCnab240\Dominio\Bancos;

class Itau extends Banco
{
    public function numero()
    {
        return '341';
    }

    public function nome()
    {
        return 'Banco Itau';
    }

    public function pastaBanco()
    {
        return 'itau';
    }

    public function headerArquivo()
    {
        // TODO: Implement headerArquivo() method.
    }

    public function strPadNumero()
    {
        // TODO: Implement strPadNumero() method.
    }

    public function strPadTexto()
    {
        // TODO: Implement strPadTexto() method.
    }

    public function recuperarCodigoLote()
    {
        // TODO: Implement recuperarCodigoLote() method.
    }
}