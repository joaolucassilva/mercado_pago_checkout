# Laravel Payment Gateway: Mercado Pago Integration

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)
![Mercado Pago](https://img.shields.io/badge/Mercado_Pago-SDK-009EE3?style=flat&logo=mercadopago)
![Tests](https://img.shields.io/badge/Tests-Passing-success)

## 📖 Sobre o Projeto

Este projeto é uma implementação de referência para um **Checkout Transparente e Resiliente** utilizando Laravel e
Mercado Pago.

O objetivo não é apenas processar pagamentos, mas demonstrar uma arquitetura de software robusta, preparada para
escalabilidade e fácil manutenção. O sistema foca em resolver problemas comuns de integrações financeiras, como *
*concorrência**, **idempotência de webhooks** e **desacoplamento de gateway**.

---

## 🏗 Arquitetura & Design Patterns

A arquitetura foi desenhada seguindo princípios de **Clean Code** e **SOLID**.

### Destaques Técnicos:

* **Adapter Pattern:** Implementação de uma camada de abstração (`PaymentGatewayInterface`) para o SDK do Mercado Pago.
  Isso permite a troca do provedor de pagamentos (ex: para Stripe) sem alterar a lógica de negócios (
  Controllers/Services).
* **Webhooks Assíncronos (Queues):** O processamento de notificações do Mercado Pago é feito via Jobs em background,
  garantindo resposta imediata ao gateway e alta disponibilidade.
* **Idempotency Handling:** Mecanismo para garantir que o mesmo Webhook não seja processado duas vezes, evitando
  duplicidade de liberação de saldo/produto.
* **Database Transactions & Locking:** Uso de `lockForUpdate` para prevenir Race Conditions durante atualizações de
  status de pedidos simultâneos.
* **Audit Logging:** Tabela dedicada (`order_logs`) para rastrear todas as mudanças de estado e payloads recebidos (
  Event Sourcing simplificado).

> Para detalhes profundos da arquitetura, consulte:
> * [System Design Doc](./docs/SYSTEM_DESIGN.md)
> * [Architecture Decision Records (ADRs)](./docs/adr/)

---

## 🛠 Tech Stack

* **Framework:** Laravel 12
* **Language:** PHP 8.4+
* **Database:** MySQL
* **Queue Driver:** Redis
* **Containerization:** Docker (via Laravel Sail)
* **Testing:** PHPUnit

---

## 🚀 Como Rodar Localmente

Este projeto utiliza **Laravel Sail** para facilitar o setup do ambiente de desenvolvimento.

### Pré-requisitos

* Docker Desktop instalado
* WSL2 (se estiver no Windows)

### Instalação

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/seu-usuario/laravel-mercadopago-checkout.git](https://github.com/seu-usuario/laravel-mercadopago-checkout.git)
   cd laravel-mercadopago-checkout
2. **Suba os containers:**
   ```bash
   ./vendor/bin/sail up -d
   ```
3. **Instale as dependências do Composer:**
   ```bash
   ./vendor/bin/sail composer install
   ```
4. **Configure o arquivo `.env`:**
   ```bash
   ./vendor/bin/sail cp .env.example .env
   ```
   Preencha as variáveis de ambiente, especialmente as chaves do Mercado Pago.
5. Execute as Migrations:
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

### Rodando os Testes

A suite de testes cobre fluxos do checkout, processamento de webhooks e isolamento de adapter.

```bash
 ./vendor/bin/sail artisan test
```

### Documentação da API

A documentação dos endpoints (Swagger/OpenAPI) está disponível em:

http://localhost/docs/api

