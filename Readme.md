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
composer require leandroferreirama/pagamento-cnab-240
```

## Exemplo de Uso

### Include do autoload
```bash
require "vendor/autoload.php";
```
### 1º Cria uma empresa
```bash
#dados da empresa
$nomeEmpresa = 'Sua empresa';
$documentoEmpresa = '00.000.000/0000-00'; #aceita com ou sem máscara
$rua = 'sua rua';
$numero = 'seu número';
$complemento = '';
$bairro = 'seubairro';
$cep = '00.000-000'; #aceita com ou sem máscara
$cidade = 'Sua cidade';
$estado = 'PR'; #Sigla do estado

$empresa = new Empresa(
    $nomeEmpresa, $documentoEmpresa, $rua, $numero, $complemento, $bairro, $cep, $cidade, $estado
);
```
### 2º Cria uma conta
```bash
#dados da conta
$agencia = '1234';
$conta = '12345';
$digito = '6';

$conta = new Conta($agencia, $conta, $digito, $empresa);
```
### 3º Cria um banco
```bash
$codigoArquivo = 1; #Número sequencial controlado por você *Não deve repetir*
```
#### Itaú
```bash
$itau = new Itau($codigoArquivo, $conta);
```
#### Bradesco
```bash
#Para o bradesco é necessário passar o cósigo do convênio
$codigoConvenio = '123456';

bradesco = new Bradesco($codigoArquivo, $conta, $codigoConvenio);
```

### 4º Cria um lote de uma forma de pagamento

#### TED e transferência para o mesmo banco
Para TED e transferência para o mesmo banco, deve-se criar a conta do favorecido. 

É necessário que você trate na sua aplicação a diferença entre transferência para o mesmo 
banco e transferencia para outro banco (TED).
```bash
/**
 * Gerando uma Ted
 */
#Criando um Pagamento
#dados da conta
$codigoBanco = '123';
$tipoConta = TipoConta::CONTACORRENTE;# ou TipoConta::POUPANCA

#dados do favorecido (quem receberá o valor)
$nomeFavorecido = 'nome';
$documentoFavorecido = '000.000.000-00'; #aceito CPF E CNPJ. Com ou sem máscara

$favorecido = new Favorecido($nomeFavorecido, $documentoFavorecido);

$contaFavorecido = new FavorecidoConta(
    $numerobanco, $tipoConta,'agencia', 'conta', 'dv', $favorecido
);
#dados da primeira transacao
$valor = '0,00'; #Aceita padrão brasileiro (0,00) ou americano (0.00)

/**
 * Seu número
 * número criado por vc para controlar cada transação, através deste número você poderá identificar no retorno 
 * a resposta do banco
 */
$seuNumero = '12345678';

/**
 * Data de Pagamento (opcional)
 * É a data que será realizado o pagamento, caso não seja passada a data a biblioteca vai gerar 
 * o pagamento para a data de hoje, você pode agendar para uma data futura
 */
$dataPagamento = '01/08/2022'; #Aceita padrão brasileiro (dd/mm/yyyy) ou americano (yyyy-mm-dd)

$ted1 = new TransferenciaTed(
  $favorecido, $contaFavorecido, $seuNumero, $valor, $dataPagamento 
);
  
$ted2 = new TransferenciaTed(
  $favorecido2, $contaFavorecido2, $seuNumero2, $valor2, $dataPagamento2 
);

#Incluindo os pagamentos no lote de TED

$ted = new Ted();
$ted->adicionar($ted1);
$ted->adicionar($ted2);

#Também é possível encadear o cadastro
$ted->adicionar($ted1)->adicionar($ted2);
  
/**
 * Gerando uma Transferencia para o mesmo banco
 * Funciona exatamente igual a ted, cria-se todas as transferências informado o favorecido e adiciona na transferência
 */
 
$transferencia1 = new TransferenciaMesmoBanco(
    $favorecido, $contaFavorecido, $seuNumero, $valor, $dataPagamento
);

$transferencia2 = ...

$transferencia = new Transferencia();
$transferencia->adicionar($transferencia1)->adicionar($transferencia2);

```