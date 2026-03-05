# Kaizen Forge — React (TS) + Symfony (Docker, infra-first)

Repo layout:

- `apps/kaizenforge-api`  → Symfony API (served under `/api`)
- `apps/kaizenforge-web`  → React + Vite (TypeScript, dev server with HMR)
- `nginx/`                → Nginx configs
- `docker-compose.yml`    → Dev/Prod profiles (+ optional workers profile)
- `Makefile`              → Convenience commands

## Quick start (dev)

```bash
cp .env.example .env
make init
```

- Frontend (Vite HMR): http://localhost:15173
- API health (Symfony): http://localhost:18000/api/health

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

## Ports (override in `.env`)

- `APP_PORT` (API): 18000
- `FRONTEND_DEV_PORT` (Vite): 15173
- `PROD_WEB_PORT` (Prod web): 18080
- `DB_PORT` (MariaDB): 13306
- `REDIS_PORT` (Redis): 16379
