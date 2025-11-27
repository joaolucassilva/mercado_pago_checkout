# ADR 002: Validação de Assinatura HMAC em Webhooks

## Contexto
Endpoints de Webhooks são públicos. Sem proteção, atacantes podem forjar requisições para alterar status de pedidos indevidamente. (Replay attacks ou Spoofing)

## Decisão
Implementar um **Middleware** que intercepta todas as requisições POST para `/webhook/mercadopago`
O middleware validará o header `x-signature` utilizando HMAC-SHA256 e a chave secreta (`MP_WEBHOOK_SECRET`).

## Consequências
* **Segurança:** Bloqueia requisições forjadas antes que atinjam o Controller ou Banco de dados
* **Timing Attack Protection:** Utilização de `hash_equals` em vez de `==` para comparação de strings.
* **Complexidade:** Exige gestão correta das chaves de ambiente (Secrets Manager)

## Status
Aceito
