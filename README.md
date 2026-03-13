# Kaizen Forge — React (TS) + Symfony (Docker, infra-first)

Repo layout:

- `apps/kaizenforge-api`  → Symfony API (`/api`, versioned endpoints under `/api/v1`)
- `apps/kaizenforge-web`  → React + Vite frontend (TypeScript, HMR in dev)
- `nginx/`                → Nginx configuration for dev and production
- `docker-compose.yml`    → Local orchestration for dev/prod profiles
- `Makefile`              → Project commands for setup, development and maintenance

## Quick start (dev)

```bash
cp .env.example .env
make setup
```

This will:

- install backend and frontend dependencies
- start the development containers
- run database migrations
- load demo fixtures

Available after setup:

- Frontend (Vite HMR): http://localhost:15173
- API health (Symfony): http://localhost:18000/api/health

Demo credentials:

- `demo@kaizenforge.app` / `Demo1234!`
- `admin@kaizenforge.app` / `Demo1234!`

## Prod-like run

```bash
cp .env.example .env
make prod
```

- App (static frontend + `/api` proxy): http://localhost:18080

## Optional workers (not started by default)

Workers are intentionally kept behind the `workers` profile (infra-first base). Start them when you actually add Messenger/scheduling:

```bash
make workers
```

## Useful commands

```bash
make logs        # follow container logs
make down        # stop dev environment
make rebuild     # rebuild dev images
make api-sh      # shell inside Symfony container
make web-sh      # shell inside frontend container
```

## Ports (override in `.env`)

- `APP_PORT` (API): 18000
- `FRONTEND_DEV_PORT` (Vite): 15173
- `PROD_WEB_PORT` (Prod web): 18080
- `DB_PORT` (MariaDB): 13306
- `REDIS_PORT` (Redis): 16379