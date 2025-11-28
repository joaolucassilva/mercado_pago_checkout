# ADR 004: Estratégia de Representação Monetária (Integer & Value Object)

## Contexto

O sistema lida com transações financeiras. A representação de valores monetários usando tipos de ponto flutuante (
`float`, `double`) é suscetível a erros de precisão devido ao padrão IEEE 754 (ex: `0.1 + 0.2 != 0.3`).
Além disso, o uso de tipos primitivos (ex: `int $amount`) em toda a aplicação leva ao anti-pattern "Primitive
Obsession", onde a lógica de validação e formatação fica espalhada e duplicada.

## Decisão

1. **Persistência (Banco de Dados):** Todos os valores monetários serão armazenados como **BigInteger** representando
   centavos (ex: R$ 10,00 = `1000`). Isso elimina completamente erros de arredondamento no nível de armazenamento.
2. **Domínio (Aplicação):** O código PHP não manipulará inteiros ou floats diretamente. Utilizaremos um **Value Object**
   dedicado (`App\Domain\ValueObject\Money`).
3. **Conversão Transparente:** Utilizaremos **Laravel Castas** (`MoneyCast`) para converter automaticamente o inteiro do
   banco para o objeto `Money` ao hidratar os Models.

## Consequências

* **Precisão Absoluta:** Cálculos matemáticos tornam-se seguros.
* **Segurança de Tipo:** É impossível passar uma string ou um float inválido para métodos que exigem dinheiro.
* **Encapsulamento:** Regras como "dinheiro não pode ser negativo" ficam centralizados na classe `Money`.
* **Leitura Humana (DB):** Consultas SQL diretas exigirão divisão mental por 100 para entender os valores (ex: `1990` =
  `19,90`).

## Status

Aceito
