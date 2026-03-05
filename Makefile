.DEFAULT_GOAL := help

help:
	@echo "Targets:"
	@echo "  make init        -> build + up (dev) + install deps (php/node)"
	@echo "  make dev         -> docker compose --profile dev up -d --build"
	@echo "  make prod        -> docker compose --profile prod up -d --build"
	@echo "  make deps        -> composer install + npm ci"
	@echo "  make workers     -> start optional workers (queue/scheduler)"
	@echo "  make down        -> docker compose down"
	@echo "  make logs        -> docker compose logs -f --tail=200"
	@echo "  make ps          -> docker compose ps"
	@echo "  make api-sh      -> shell in php container"
	@echo "  make web-sh      -> shell in frontend-dev container (dev profile)"
	@echo "  make rebuild     -> down + build (no cache) + up (dev)"

init: dev deps

dev:
	docker compose --profile dev up -d --build

prod:
	docker compose --profile prod up -d --build

deps:
	docker compose run --rm -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction --prefer-dist --no-progress
	docker compose --profile dev run --rm frontend-dev npm ci

workers:
	docker compose --profile workers up -d --build

down:
	docker compose down

logs:
	docker compose logs -f --tail=200

ps:
	docker compose ps

api-sh:
	docker compose exec php sh

web-sh:
	docker compose --profile dev exec frontend-dev sh

rebuild:
	docker compose down
	docker compose build --no-cache
	docker compose --profile dev up -d
