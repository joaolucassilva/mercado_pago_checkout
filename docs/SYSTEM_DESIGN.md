## Objetivo

Sistema de venda simples para produtos digitais (ex: Ebook/Acesso VIP) com liberação automática via Webhook.

## Tech Stack

- **Framework:** Laravel
- **Gateway:** Mercado Pago (SDK PHP)
- **Database:** MySQL
- **Infra Local:** Ngrok (para expor Webhooks)

---

## Banco de Dados (Schema)

A estrutura foca na persistência do pedido e do status de pagamento.

```mermaid
erDiagram
    USERS ||--o{ ORDERS: "faz"
    PRODUCTS ||--o{ ORDERS: "contem"
    ORDERS ||--o{ ORDERS_LOGS: "histórico"
    
    Users {
        bigint id PK
        string name
        string email
        string password
        boolean is_premium "Liberação de acesso"
    }

    PRODUCTS {
        bigint id PK
        string name
        decimal price
        string description
    }

    ORDERS {
        bigint id PK
        bigint user_id PK
        bigint product_id PK
        decimal transaction_amount "Preço snapshot"
        string external_reference "UUID único MP"
        string mercadopago_id "ID do Gateway"
        string status "pending, approved, in_process, rejected"
        string payment_method_id "pix, credit_card"
    }

    ORDERS_LOGS {
        bigint id PK
        bigint order_id FK
        string previous_status
        string new_status
        json payload "Dados crus do Webhook"
        timestamp created_at
    }
```

## Fluxo de Pagamento 
O fluxo "Happy Path" de uma compra
```mermaid
sequenceDiagram
    participant U as Usuário
    participant C as CheckoutController
    participant S as PaymentService (Interface)
    participant Q as Queue (Redis/DB)
    participant MP as Mercado Pago API
    Note over U, MP: Fluxo de Compra
    U ->> C: 1. Comprar
    C ->> S: 2. createPreference(Order)
    S ->> MP: 3. API Request
    MP -->> S: 4. URL Checkout
    S -->> C: 5. Return URL
    C ->> U: 6. Redirect
    Note over MP, Q: Fluxo de Webhook (Async)
    MP -> C: 7. POST /webhook
    C -> Q: 8. Dispatch ProcessPaymentJob
    C -->> MP: 9. HTTP 200/201 OK (Fast Response)

    loop Worker Process
        Q ->> S: 10. Processa o Job
        S ->> MP: 11. Valida Status Real (Anti-fraude)
        S ->> DB: 12. Transaction & Lock Row
        DB ->> DB: 13. Update Status e Log Eventos
    end
```

## Máquina de Estados (Status do Pedido)
Transições possíveis para o campo `status` do Order.
```mermaid
stateDiagram-v2
    [*] --> PENDING: Pedido Criado
    PENDING --> APPROVED: Webhook Pagamento Recebido
    PENDING --> REJECTED: Webhook Cartão Recusado
    APPROVED --> REFUNDED: Admin Estorno Manual
    REJECTED --> PENDING: Usuário tenta pagar de novo
```

## Regras de Negócio e Segurança
1. External Reference: É a chave mestra. Nunca criar uma preferência no MP sem enviar um UUID gerado pelo nosso sistema. É ele que garante que sabemos quem pagou o quê quando Webhook chegar.
2. Idempotência: O Webhook pode enviar a mesma notificação múltiplas vezes. Verificar se order já esta aprovada antes de processar para evitar duplicidade.
3. Nunca liberar o acesso(is_premium) baseado no redirecionamento do usuário(Return URL). Apenas o Webhook é confiável.
4. O valor cobrado deve vir sempre do Banco de Dados(products.price), nunca do input do usuário.
