# Laravel Payment Gateway: Agnostic Core & High Performance

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)
![Redis](https://img.shields.io/badge/Redis-Stream-DC382D?style=flat&logo=redis)
![Architecture](https://img.shields.io/badge/Pattern-Hexagonal-orange)

## 📖 Sobre o Projeto

Este projeto é uma implementação de referência para um sistema de pagamentos de **Alta Resiliência** e **Agnóstico ao
Fornecedor**.
Diferente de implementações tradicionais acopladas, este sistema utiliza **Clean Architecture** para permitir que o
núcleo da aplicação desconheça o provedor de pagamento (Mercado Pago, Stripe, etc). Além disso, implementa uma
estratégia de ingestão de Webhooks baseada em **Event Streaming (Redis)** para suportar picos de tráfego massico (ex:
Black Friday) sem desagradar o banco de dados principal.

---

## Arquitetura e Diferenciais

### 1. Gateway Agnostic Core (Hexagonal)

O sistema segue o princípio **Open/Close**. Adicionar um novo gateway (ex: Stripe) não exige alteração no código
existente.

* **Interface Unificada:** `PaymentGatewayInterface` padroniza a comunicação.
* **Adapters:** Classes específicas traduzem payloads externos para DTOs internos.
* **Factory:** Decisão dinâmica de qual driver usar em tempo de execução.

### 2. Ingestão Híbrida (Redis Streams + MySQL)

Resolvemos o trade-off entre performance e auditoria:

* **Entrada (Hot Path):** O webhook é validado e gravado no Redis Stream em milissegundos.
* **Processamento (Async):** Um Worker consome o stream, persiste o log bruto no MySQL (Audit Logging) e processa regra
  de negócio.

### 3. Segurança & Auditoria

* **HMAC Validation:** Middleware dedicado para validar assinaturas de webhooks (`x-signature`).
* **Double Check:** O sistema nunca confia cegamente no payload do webhook. O status é sempre validado na API do
  provedor antes de liberar o acesso.
* **Event Sourcing Light:** Histórico completo de transições de estado na tabela `order_logs`.

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
* **Testing:** Pest PHP (Unit, Feature & Architecture Tests)
* **Docs:** OpenAPI (Swagger), AsyncAPI, Mermaid.js

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

