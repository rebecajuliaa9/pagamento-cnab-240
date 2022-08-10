<?php

use Leandroferreirama\PagamentoCnab240\Aplicacao\Constantes\TipoChave;
use Leandroferreirama\PagamentoCnab240\Aplicacao\Constantes\TipoConta;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Bradesco;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Itau;
use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;
use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Empresa;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\FavorecidoConta;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\PagamentoBoleto;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\PagamentoBoletoItau;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaMesmoBanco;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaPix;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaTed;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Boleto;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Pix;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Ted;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transferencia;

require '../vendor/autoload.php';

$codigoArquivo = 20;
$codigoLote = 1;
$seuNumero = 26;
/**
 * EXEMPLO PIX
 */


$favorecido = new Favorecido('Leandro Ferreira Marcelli', '035.976.079-18');
$pagamentoPix = new TransferenciaPix($favorecido, '1,01', date("Y-m-d"), $seuNumero, TipoChave::TELEFONE, '+5541997780000');
$pix = new Pix($codigoLote);
$pix->adicionar($pagamentoPix);
$seuNumero++;
$codigoLote++;

$favorecido = new Favorecido('Isabel Farias', '045.567.019-61');
$pagamentoPix = new TransferenciaPix($favorecido, '1,02', date("Y-m-d"), $seuNumero, TipoChave::EMAIL, 'secretariaisabelcunha@gmail.com');
$pix2 = new Pix($codigoLote);
$pix2->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '035.976.079-18');
$pagamentoPix = new TransferenciaPix($favorecido, '1,03', date("Y-m-d"), $seuNumero, TipoChave::DOCUMENTO, '035.976.079-18');
$pix3 = new Pix($codigoLote);
$pix3->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '13.053.435/0001-46');
$pagamentoPix = new TransferenciaPix($favorecido, '1,04', date("Y-m-d"), $seuNumero, TipoChave::DOCUMENTO, '13.053.435/0001-46');
$pix4 = new Pix($codigoLote);
$pix4->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '13.053.435/0001-46');
$pagamentoPix = new TransferenciaPix($favorecido, '1,05', date("Y-m-d"), $seuNumero, TipoChave::CHAVEALEATORIA, '5a087227-512c-44f5-a514-67186071958a');
$pix5 = new Pix($codigoLote);
$pix5->adicionar($pagamentoPix);

$empresaImaginarte = new Empresa('Oliveira Formaturas e Eventos Ltda - Me', '13.676.094/0001-65', 'David Geronasso', '587', '', '82540-150', 'Curitiba', 'PR');
$contaImaginarte = new Conta(3702, 17514, 6, $empresaImaginarte);

$itau = new Itau($codigoArquivo, $contaImaginarte);
$itau->adicionar($pix)->adicionar($pix2)->adicionar($pix3)->adicionar($pix4)->adicionar($pix5);
echo $itau->gerarArquivo();

/*
 * BOLETO / TED / TRANSFERENCIA

$favorecidoBoletoImaginarte = new Favorecido('Oliveira Formaturas', '13.676.094/0001-65');
$codigoBarrasImaginarte = '75691.43683 01136.817606 19268.080017 8 90470000017808';

$favorecidoBoleto = new Favorecido('Brl Eventos', '20.136.193/0001-10');
$codigoBarras = '34191.09008 34203.480768 21217.130000 1 90570000013775';
$pagamento = new PagamentoBoletoItau($codigoBarras, $favorecidoBoleto, '137,75', date("Y-m-d"),$seuNumero);
$boleto = new Boleto($codigoLote);
$boleto->adicionar($pagamento);
$seuNumero++;
$codigoLote++;

$favorecido = new Favorecido('João Gilberto Soares', '063.392.649-30');
$contaFavorecido = new FavorecidoConta(341, TipoConta::CONTACORRENTE, 3702, 16576, 6);
$mesmoBanco = new TransferenciaMesmoBanco($favorecido, $contaFavorecido, '1,01', date("Y-m-d"), $seuNumero);

$transferenciaMesmoBanco = new Transferencia($codigoLote);
$transferenciaMesmoBanco->adicionar($mesmoBanco);
$seuNumero++;
$codigoLote++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '035.976.079-18');
$contaFavorecido = new FavorecidoConta(237, TipoConta::CONTACORRENTE, 3127, 11470, 7);

$ted = new TransferenciaTed($favorecido, $contaFavorecido, '1,02', date("Y-m-d"), $seuNumero);
$transferencia = new Ted($codigoLote);
$transferencia->adicionar($ted);

$empresaBRL = new Empresa('Brl Eventos', '20.136.193/0001-10', 'David Geronasso', '587', '', '82540-150', 'Curitiba', 'PR');
$contaBRL = new Conta(762, 12171, 3, $empresaBRL);

$empresaImaginarte = new Empresa('Oliveira Formaturas e Eventos Ltda - Me', '13.676.094/0001-65', 'David Geronasso', '587', '', '82540-150', 'Curitiba', 'PR');
$contaImaginarte = new Conta(3702, 17514, 6, $empresaImaginarte);

$itau = new Itau($codigoArquivo, $contaImaginarte);
$itau->adicionar($boleto)->adicionar($transferenciaMesmoBanco)->adicionar($transferencia);
echo $itau->gerarArquivo(); */

