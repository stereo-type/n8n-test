# n8n-test

Symfony 7.2 project (PHP 8.2+).

## Requirements

- PHP 8.2+
- Composer 2.x
- PostgreSQL 16 (or adjust `DATABASE_URL` in `.env.local`)

## Installation

```bash
cp .env .env.local
# Edit .env.local — set DATABASE_URL and APP_SECRET

composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Or use the Makefile:

```bash
make install
make migrate
```

## Tech Stack

| Layer | Package |
|---|---|
| Framework | symfony/framework-bundle 7.2 |
| ORM | doctrine/orm + doctrine/migrations |
| Admin | easycorp/easyadmin-bundle |
| API Docs | nelmio/api-doc-bundle (Swagger UI at `/api/doc`) |
| Templates | symfony/twig-bundle |
| Security | symfony/security-bundle |
| Validation | symfony/validator |
| Logging | symfony/monolog-bundle |
| i18n | symfony/translation (default: `ru`) |
| Serializer | symfony/serializer-pack |
| Rate Limiting | symfony/rate-limiter |
| Static Analysis | phpstan/phpstan level 8 |
| Code Style | friendsofphp/php-cs-fixer |
| Tests | phpunit/phpunit via symfony/test-pack |
| Profiler | symfony/profiler-pack (dev only) |
| Generator | symfony/maker-bundle (dev only) |

## Development Commands

```bash
make test           # Run PHPUnit tests
make phpstan        # Static analysis (PHPStan level 8)
make cs             # Check code style
make cs-fix         # Fix code style
make lint           # phpstan + cs
make migration      # Generate new migration
make migrate        # Run migrations
make security-check # Audit dependencies for vulnerabilities
```

## Project Structure

```
src/
├── Core/
│   ├── Domain/        # Entities, Value Objects, Domain Services
│   ├── Application/   # Services, DTOs, Use Cases
│   ├── Infrastructure/ # Repositories, External Services
│   └── UI/
│       ├── Controller/Admin/  # EasyAdmin Dashboard
│       └── Templates/         # Twig templates (@Core namespace)
├── Features/          # Feature modules (each with Domain/Application/Infrastructure/UI)
└── Kernel.php
migrations/            # Doctrine migrations
translations/          # i18n files
tests/                 # PHPUnit tests
```

## Twig Namespaces

- `@Core/` → `src/Core/UI/Templates/`
- `@Features/` → `src/Features/`

## Admin Panel

Available at `/admin` (EasyAdminBundle).

## API Documentation

Swagger UI available at `/api/doc`.
