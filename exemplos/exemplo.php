<?php

use Leandroferreirama\PagamentoCnab240\Aplicacao\Constantes\TipoChave;
use Leandroferreirama\PagamentoCnab240\Aplicacao\Constantes\TipoConta;
use Leandroferreirama\PagamentoCnab240\Dominio\Bancos\Bradesco;
use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Conta;
use Leandroferreirama\PagamentoCnab240\Dominio\Empresa\Empresa;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\Favorecido;
use Leandroferreirama\PagamentoCnab240\Dominio\Favorecido\FavorecidoConta;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\PagamentoBoleto;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaMesmoBanco;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaPix;
use Leandroferreirama\PagamentoCnab240\Dominio\Pagamentos\TransferenciaTed;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Boleto;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Pix;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Ted;
use Leandroferreirama\PagamentoCnab240\Dominio\Transacoes\Transferencia;

require '../vendor/autoload.php';

$codigoArquivo = 45;
$codigoLote = 60;
$seuNumero = 15;
/**
 * EXEMPLO PIX
 */
/*

$empresa = new Empresa('Oliveira Formaturas', '13.676.094/0001-65', 'David Geronasso', '587', '', '82540-150', 'Curitiba', 'PR');
$conta = new Conta(3127, 7797, 6, $empresa);
$bradesco = new Bradesco($codigoArquivo, $conta, 393678, 'PIX');
$bradesco->adicionar($pix);
echo $bradesco->gerarArquivo();*/
/*
 * EXEMPLO COM TED, TRANSFERÊNCIA MESMO BANCO e PAGAMENTO DE BOLETO
 */
$favorecido = new Favorecido('Leandro Ferreira Marcelli', '035.976.079-18');
$contaFavorecido = new FavorecidoConta(237, TipoConta::CONTACORRENTE, 3127, 11470, 7);
$mesmoBanco = new TransferenciaMesmoBanco($favorecido, $contaFavorecido, 150, '2022-08-03', 5);

$transferencia = new Transferencia($codigoLote);
$transferencia->adicionar($mesmoBanco);
$codigoLote++;


$contaFavorecido2 = new FavorecidoConta(260, 'CC',0001,69717119, 8);
$pagamentoTed = new TransferenciaTed($favorecido, $contaFavorecido2, 188.3, '2022-08-03', 6);
$ted = new Ted($codigoLote);
$ted->adicionar($pagamentoTed);
$codigoLote++;

$favorecidoBoleto = new Favorecido('Brl Eventos', '20.136.193/0001-10');
$codigoBarras = '34191.09008 34203.630768 21217.130000 5 91190000013775';
$pagamento = new PagamentoBoleto($codigoBarras, $favorecidoBoleto, '137,75', '03/08/2022',7);
$boleto = new Boleto($codigoLote);
$boleto->adicionar($pagamento);
$codigoLote++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '035.976.079-18');
$pagamentoPix = new TransferenciaPix($favorecido, '203,75', '2022-08-03', $seuNumero, TipoChave::TELEFONE, '5541997780000');
$pix = new Pix($codigoLote);
$pix->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Isabel Farias', '045.567.019-61');
$pagamentoPix = new TransferenciaPix($favorecido, '287,75', '2022-08-03', $seuNumero, TipoChave::EMAIL, 'secretariaisabelcunha@gmail.com');
$pix2 = new Pix($codigoLote);
$pix2->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '035.976.079-18');
$pagamentoPix = new TransferenciaPix($favorecido, '203,75', '2022-08-03', $seuNumero, TipoChave::DOCUMENTO, '');
$pix3 = new Pix($codigoLote);
$pix3->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '13.053.435/0001-46');
$pagamentoPix = new TransferenciaPix($favorecido, '203,75', '2022-08-03', $seuNumero, TipoChave::DOCUMENTO, '');
$pix4 = new Pix($codigoLote);
$pix4->adicionar($pagamentoPix);
$codigoLote++;
$seuNumero++;

$favorecido = new Favorecido('Leandro Ferreira Marcelli', '13.053.435/0001-46');
$pagamentoPix = new TransferenciaPix($favorecido, '203,75', '2022-08-03', $seuNumero, TipoChave::CHAVEALEATORIA, '5a087227-512c-44f5-a514-67186071958a');
$pix5 = new Pix($codigoLote);
$pix5->adicionar($pagamentoPix);

$empresa = new Empresa('Oliveira Formaturas', '13.676.094/0001-65', 'David Geronasso', '587', '', '82540-150', 'Curitiba', 'PR');
$conta = new Conta(3127, 7797, 6, $empresa);
$bradesco = new Bradesco($codigoArquivo, $conta, 393678, 'PIX');
$bradesco->adicionar($pix)->adicionar($pix2)->adicionar($pix3)->adicionar($pix4)->adicionar($pix5);
echo $bradesco->gerarArquivo();
