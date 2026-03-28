.DEFAULT_GOAL := help

.PHONY: help setup dev build prod deps migrate fixtures reset-demo workers down down-clean logs api-sh web-sh rebuild frontend-refresh tests test-db

help:
	@echo "Targets:"
	@echo "  make setup             -> deps + dev + migrate + fixtures"
	@echo "  make dev               -> start development containers"
	@echo "  make build             -> build development images"
	@echo "  make prod              -> start production-like stack"
	@echo "  make deps              -> composer install + npm ci"
	@echo "  make migrate           -> run Symfony migrations"
	@echo "  make fixtures          -> load Symfony demo fixtures"
	@echo "  make reset-demo        -> reload demo fixtures"
	@echo "  make workers           -> start optional workers"
	@echo "  make tests             -> prepare test database and run backend test suite"
	@echo "  make down              -> stop development containers"
	@echo "  make down-clean        -> stop development containers and remove volumes"
	@echo "  make logs              -> follow logs"
	@echo "  make api-sh            -> shell in php container"
	@echo "  make web-sh            -> shell in frontend-dev container"
	@echo "  make rebuild           -> rebuild development images without cache"
	@echo "  make frontend-refresh  -> restart frontend dev server"

setup: deps dev migrate fixtures

dev:
	docker compose --profile dev up -d

build:
	docker compose --profile dev build

prod:
	docker compose --profile prod up -d --build

deps:
	docker compose run --rm -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction --prefer-dist --no-progress
	cd apps/kaizenforge-web && npm ci

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

reset-demo:
	docker compose exec php php bin/console doctrine:fixtures:load --no-interaction --purge-with-truncate

workers:
	docker compose --profile workers up -d --build

tests: dev test-db
	docker compose exec php composer test

test-db:
	docker compose exec -e APP_ENV=test php php bin/console doctrine:database:drop --force --if-exists
	docker compose exec -e APP_ENV=test php php bin/console doctrine:database:create
	docker compose exec -e APP_ENV=test php php bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec -e APP_ENV=test php php bin/console doctrine:fixtures:load --no-interaction

down:
	docker compose --profile dev down

down-clean:
	docker compose --profile dev down -v

logs:
	docker compose logs -f --tail=200

api-sh:
	docker compose exec php sh

web-sh:
	docker compose --profile dev exec frontend-dev sh

rebuild:
	docker compose --profile dev down
	docker compose --profile dev build --no-cache
	docker compose --profile dev up -d

frontend-refresh:
	docker compose --profile dev restart frontend-dev