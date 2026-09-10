# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Laravel 12 (PHP 8.2+; Docker images build on PHP 8.4) backend for a smart **locker / parcel-box** system. It serves two distinct JSON API surfaces:

1. **Admin dashboard API** — root `app/Http/Controllers/*` + `app/Services/*`, guard `auth:api` (JWT, `App\Models\User`).
2. **Device / mobile API** — `app/Http/Controllers/AppMovil/*` + `app/Services/AppMovil/*`, guard `auth:locker` (JWT, `App\Models\Locker`). Physical lockers authenticate as a `Locker`; `GET /api/database` returns a full data snapshot for offline sync.

`routes/web.php` is almost entirely commented out — the app is currently API-only. Blade views under `resources/views` exist but are not wired to active routes.

## Commands

```bash
composer dev             # server + queue listener + vite, concurrently (main dev entrypoint)
php artisan serve        # HTTP server only
npm run dev              # vite dev server
npm run build            # production asset build
composer test            # config:clear + php artisan test
php artisan test --filter=SomeTest   # run a single test / method
./vendor/bin/pint        # format PHP (Laravel Pint, default preset — no pint.json)
php artisan queue:work   # process the database queue
php artisan migrate      # ONLY creates Laravel infra tables (see Database)
```

## Architecture

**Controller → Service, one service per controller.** Controllers are thin: read `$request` fields with `is_null(...) ? default : ...`, wrap everything in `try/catch`, log with `Log::info("XController method " . jsonLog($request->all()))`, and return a fixed envelope:

```php
return response()->json([
    'meta' => ['code' => 200, 'status' => 'success', 'message' => '...'],
    'data' => $result,
]);
```

Services hold all business logic, are constructor-injected, and log at method entry the same way.

**Data access is `DB::table()` query builder, not Eloquent.** Only three models exist (`User`, `Locker`, `UserCompany`), and they are used mainly for auth/JWT. Domain tables (`locker`, `door`, `controller`, `department`, `user`, `movement`, `building`, `company`, `api_integration`, `request_comand`, `rol`, `users_rol`, `user_company`, `door_size`) are all accessed directly via `DB::table('...')`. Do not add Eloquent relationships/models for these unless deliberately migrating the pattern.

**Database schema is NOT managed by Laravel migrations.** The authoritative schema is raw SQL in `database.sql` (MySQL, snake_case, `*_id` PKs, `create_at`/`update_at` columns). `database/migrations/` only contains stock Laravel tables (users, cache, jobs, telescope, personal_access_tokens). Consequence: service-layer feature tests need a real MySQL DB; the `sqlite :memory:` config in `phpunit.xml` won't have the domain schema.

### Auth (JWT — tymon/jwt-auth)

Three guards in `config/auth.php`: `web` (session, unused), `api` (jwt → `User`), `locker` (jwt → `Locker`). Tokens are minted manually in controllers via `JWTAuth::customClaims([...])->fromUser($model, $customClaims)` with domain claims (`company_id`, `building_id`, `locker_id`, ...). `JWT_TTL` defaults to ~5 years.

Helpers in `app/helpers.php` (autoloaded via `composer.json` `files`):
- `getUser()` / `getLocker()` — `JWTAuth::parseToken()->authenticate()` then return the JWT payload; services call `getLocker()->get('building_id')` etc.
- `jsonLog($data)` — pretty JSON for logging (used everywhere).
- `VerificateRol($name)`, `sizeDoor($int)`, `getDoor()`, `getComandOpen()` / `getComandClosed()` — hardware door command hex tables.

Note: `AuthController::login` looks the user up by email only and does not verify a password.

### External integrations

- **RabbitMQ** (`app/RabbitMQ.php`, `MQ_*` env): `publish()` sends a JSON message to the `open` queue to physically open a door. Called from `DoorService::openDoor()`.
- **"Experience" system** (`URL_APP_EXPERIENCE`, `TOKEN_EXPERIENCE` env): Guzzle HTTP calls to import buildings/departments/residents (`DepartmentService::createbyApi`) and push movement notifications (`app/Services/AppMovil/MovementService.php`).
- **Telescope** — registered only in `local` environment.

Queue, cache, and session all use the `database` driver.

## Frontend

Vite + Tailwind CSS v4 + Alpine.js + plain TypeScript (no framework). Libraries: DataTables, SweetAlert2, Dropzone, Flatpickr, `form-serialize`, `validator`. Entry points are listed explicitly in `vite.config.js` (`resources/js/pages/*/index.ts`, `resources/js/layout.ts`, CSS files).

## Deployment

- **CapRover**: `captain-definition` → `.deploy/Dockerfile` (multi-stage, PHP 8.4 Alpine, non-root `app` user). `.deploy/entrypoint.sh` runs `composer dump-autoload`, cache/route clear, `migrate --force`, then `supervisord` (`.deploy/config/supervisor.conf`: php-fpm + Caddy + crond; `schedule:run` every minute).
- Root `Dockerfile` + `docker-entrypoint.sh` are an alternative Apache-based image that also starts `queue:work --daemon`.

## Conventions

- Code, log strings, response messages, and git branch names mix Spanish and English.
- `.env` is committed and contains real credentials (DB, `JWT_SECRET`, RabbitMQ, `TOKEN_EXPERIENCE`) — treat these as live secrets; do not echo them into new files or external requests.
- DataTable endpoints take `pageSize`/`pageIndex`/`active`/`direction`/`search` and return `{ paginate, sort, <collection> }`.
