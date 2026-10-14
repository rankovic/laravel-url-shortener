# laravel-url-shortener

A small Laravel demo showcasing a URL shortener API with Redis-backed caching, queued jobs, and comprehensive tests. This is a public demo repository meant to demonstrate the tech stack highlighted in applications (Laravel, Redis, queues, PHPUnit, Docker, CI/CD).

## Features

- **Short link creation** - Create short codes for long URLs (`POST /api/links`)
- **Redirects with visit tracking** - Visiting `/{code}` dispatches a queued `RegisterVisit` job and increments a visit counter
- **Redis-backed caching** - Code → URL lookups are cached for faster hot-path redirects
- **Asynchronous processing** - Visit counting happens in the queue, keeping redirects fast
- **Statistics endpoint** - Retrieve link metadata and visit counts (`GET /api/links/{code}`)
- **Input validation** - URL validation on link creation
- **Test coverage** - Feature and unit tests for API, redirects, and job behaviour
- **Docker & docker-compose** - Ready-to-run with app, queue worker, Redis, and MySQL
- **GitHub Actions CI** - Automated tests and Laravel Pint linting

## Tech stack

- Laravel 13 (PHP 8.3+)
- Redis (caching and queues)
- MySQL
- PHPUnit
- Docker / docker-compose
- GitHub Actions

## Quick start (local)

```bash
# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and tests
php artisan migrate
php artisan test

# Start the application
php artisan serve
```

## Quick start (Docker)

```bash
# Start all services
docker compose up --build

# Run the queue worker (already included as a service in docker-compose)
docker compose exec worker php artisan queue:work
```

The application will be available at http://localhost:8000.

## API endpoints

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/links` | Create a short link. Body: `{"url": "https://example.com/..."}` |
| `GET` | `/api/links/{code}` | Get link details and visit count |
| `GET` | `/{code}` | Redirect to the original URL and queue a visit |

## Testing

```bash
# Run all tests
php artisan test

# Lint check
./vendor/bin/pint --test

# Fix lint issues
./vendor/bin/pint
```

## Architecture notes

- Route model binding uses `code` instead of `id` for the Link model.
- The redirect endpoint caches the resolved URL in Redis for one hour (hot paths stay fast).
- `RegisterVisit` implements `ShouldQueue` so visit counting does not block redirects.
- Tests use SQLite in-memory database, `array` cache, and `sync` queue to keep tests fast and deterministic.

## Notes on the demo

This is intentionally small and focused. It demonstrates backend fundamentals relevant to the CRAE backend-focused role: queues, caching, event-driven thinking, clean architecture, input validation, testing, containerisation, and CI.