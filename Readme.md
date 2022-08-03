# Informações de implementação
## Divisão por tipo de pagamentos
Para cada tipo de pagamento deve ser utilizado um lote diferente. Entre os tipos de pagamentos
temos implementado: 
- TED;
- Transferência mesmo banco;
- Pagamento de Boleto.

## Números de Controles
A biblioteca utiliza dois números de controles. Há um número de controle do arquivo e outro número de controle do lote.

**Exemplo:**

Arquivo nº 1 pode ter o lote nº 1 de transferencia para mesma conta, lote nº 2 contendo transferênciavia TED, lote nº 3 contendo Pagamento de boleto.

Arquivo nº 2 pode ter o lote nº 4 contendo transferênciavia TED, lote nº 5 contendo Pagamento de boleto.

