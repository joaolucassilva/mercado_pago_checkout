# Variáveis
SAIL := ./vendor/bin/sail
DOCKER_COMPOSE := docker-compose

# Cores para output
GREEN := \033[0;32m
NC := \033[0m # No Color

.PHONY: help install up down restart shell test logs

# Ajuda (Default)
help:
	@echo "Comandos disponíveis:"
	@echo "  ${GREEN}make install${NC}  - Instalação inicial completa (do zero)"
	@echo "  ${GREEN}make up${NC}       - Sobe os containers (Start)"
	@echo "  ${GREEN}make down${NC}     - Derruba os containers (Stop)"
	@echo "  ${GREEN}make restart${NC}  - Reinicia os containers"
	@echo "  ${GREEN}make shell${NC}    - Entra no terminal do container app"
	@echo "  ${GREEN}make test${NC}     - Roda a suíte de testes (Pest)"
	@echo "  ${GREEN}make logs${NC}     - Mostra logs dos containers"

# 🚀 Instalação Inteligente
install:
	@echo "${GREEN}🚀 Iniciando setup do projeto...${NC}"

	@echo "${GREEN}1. Configurando variáveis de ambiente (.env)...${NC}"
	@if [ ! -f .env ]; then cp .env.example .env; fi

	@echo "${GREEN}2. Instalando dependências (via Docker temporário)...${NC}"
	@# Este comando permite rodar composer install sem ter PHP/Composer na máquina host
	@docker run --rm \
	    -u "$$(id -u):$$(id -g)" \
	    -v "$$(pwd):/var/www/html" \
	    -w /var/www/html \
	    laravelsail/php84-composer:latest \
	    composer install --ignore-platform-reqs

	@echo "${GREEN}3. Subindo containers (Sail)...${NC}"
	@$(SAIL) up -d

	@echo "${GREEN}4. Gerando chave da aplicação...${NC}"
	@$(SAIL) artisan key:generate

	@echo "${GREEN}5. Rodando Migrations e Seeders...${NC}"
	@# Espera o MySQL ficar pronto antes de rodar (sleep simples ou wait-for-it)
	@sleep 5
	@$(SAIL) artisan migrate:fresh --seed

	@echo "${GREEN}✅ Projeto instalado com sucesso! Acesse: http://localhost${NC}"

# Comandos de Rotina
up:
	$(SAIL) up -d
	@echo "${GREEN}Ambiente rodando!${NC}"

down:
	$(SAIL) down

restart: down up

shell:
	$(SAIL) shell

test:
	$(SAIL) artisan test

logs:
	$(SAIL) logs -f
