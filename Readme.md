# Crie arquivos no padrão CNAB240 para pagamento com PHP
Essa biblioteca foi desenvolvida para facilitar a integração do seu sistema com os 
bancos para realização de pagamento. Ela aceita diversas formas de pagamentos.

## Arquivo
Cada arquivo podem conter vários lotes, **EXCETO o PIX**. Para pagamento em PIX deve-se 
utilizar um **arquivo exclusivo**.

## Lotes por tipo de pagamentos
Para cada tipo de pagamento deve ser utilizado um lote diferente. Entre os tipos de 
pagamentos
temos implementado:
- PIX (Chave) - **Arquivo deve ser separado das outras formas de pagamento**;
- TED;
- Transferência mesmo banco;
- Pagamento de Boleto de Cobrança.

## Bancos Homologados - Geração de Arquivo
- Itaú
- Bradesco

## Instalação

```bash
composer require rebecajuliaa9/pagamento-cnab-240
```

## Exemplo de Uso (Pagamento em boleto)

### Include do autoload
```bash
require "vendor/autoload.php";
```

```bash
#dados da empresa
$nomeEmpresa = 'Sua empresa';
$documentoEmpresa = '00.000.000/0000-00'; #aceita com ou sem máscara

$empresa = new Empresa($nomeEmpresa, $documentoEmpresa);

#Dados da Conta
$agencia = '1234';
$conta = '12345';
$digito = '6';

$conta = new Conta($agencia, $conta, $digito, $empresa);

#Instancio o banco
$codigoArquivo = 1
$itau = new Itau($codigoArquivo, $conta);

#Crio o pagamento
$seuNumero = 1; #Número sequencial por pagamento controlado por você
$favorecidoBoleto = new Favorecido('Empresa dona do boleto', '00.000.000/0001-00');
$codigoBarras = '00000.00000 00000.000000 00000.000000 0 00000000000000';
$pagamento = new PagamentoBoletoItau($codigoBarras, $favorecidoBoleto, '100,00', date("Y-m-d"),$seuNumero);

#Crio o lote do boleto
## Obrigatoriamente precisa separar os lotes que são do mesmo banco dos que são em outros bancos
## Passar como parâmetro na geração do lote as seguintes opções:
## FormaPagamentoBoleto::MESMO_BANCO | FormaPagamentoBoleto::OUTRO_BANCO
## Lembrando que sempre os 3 primeiros dígitos é o número do banco, então você consegue validar
## na sua aplicação a geração do lote
$boleto = new LoteBoleto(FormaPagamentoBoleto::MESMO_BANCO);
$boleto->adicionar($pagamento);

#Gero o arquivo
$itau->adicionar($boleto);
$itau->gerarArquivo(Arquivo::DOWNLOAD);
```
Fork realizado a partir do projeto https://github.com/leandroferreirama/pagamento-cnab-240, cuja o autor é o Leandro Ferreira Marcelli
