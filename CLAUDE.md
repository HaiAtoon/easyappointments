# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Easy!Appointments (v1.5.2) - Open-source web appointment scheduling system built on **CodeIgniter 3** with **PHP 8.1+** and **MySQL/MariaDB**.

## Development Environment

- **Runtime**: XAMPP on Windows (Apache + PHP + MySQL)
- **Base URL**: `http://localhost/ea`
- **Database**: `ea_db` with table prefix `ea_`, charset `utf8mb4`
- **Config**: Root `config.php` holds DB credentials, BASE_URL, LANGUAGE, Google Calendar settings, and DEBUG_MODE
- **Language**: Currently configured for Hebrew (`LANGUAGE = 'hebrew'`)

## Commands

```bash
# Install/update PHP dependencies
composer install

# Run tests (no project-level tests currently exist, only vendor tests)
composer test    # APP_ENV=testing php vendor/bin/phpunit
```

There is no JS build system - static assets in `assets/` are used directly.

## Architecture

### MVC Structure (CodeIgniter 3)

- **Entry point**: `index.php` -> default controller is `Booking` (public booking page)
- **Controllers**: `application/controllers/` - web controllers + `api/v1/` subdirectory for REST API
- **Models**: `application/models/` - 14 models (Appointments, Customers, Providers, Services, Admins, Secretaries, etc.)
- **Views**: `application/views/` with reusable `components/` subdirectory
- **Libraries**: `application/libraries/` - business logic services (Availability, Notifications, Synchronization, Permissions, etc.)
- **Helpers**: `application/helpers/` - utility functions

### Base Classes (`application/core/`)

- **EA_Controller** extends CI_Controller - all controllers inherit from this; provides typed property access to models and libraries
- **EA_Model** extends CI_Model - adds `$casts` array for type casting and `$api_resource` array for API field name mapping (camelCase API fields <-> snake_case DB columns)

### API (v1)

RESTful API at `/api/v1/{resource}` with standard CRUD operations. Resources: appointments, admins, customers, providers, secretaries, services, service_categories, unavailabilities, webhooks, blocked_periods, settings, availabilities. CORS is configured in `application/config/routes.php`.

### Integrations

- **Google Calendar Sync** (`libraries/Google_Sync.php`) - toggle via `GOOGLE_SYNC_FEATURE` in `config.php`
- **CalDAV Sync** (`libraries/Caldav_Sync.php`)
- **LDAP Authentication** (`libraries/Ldap_client.php`)
- **Webhooks** (`libraries/Webhooks_client.php`)
- **iCalendar** (`libraries/Ics_file.php`, `libraries/Ics_calendar.php`)

### Database Migrations

Located in `application/migrations/` (numbered `001` through `017+`). Migration config in `application/config/migration.php`.

### Storage

`storage/` directory contains `backups/`, `cache/`, `logs/`, `sessions/`, `uploads/` - all must be writable by the web server.

### Routing

Defined in `application/config/routes.php`. Backend routes: `/calendar`, `/customers`, `/services`, `/providers`, `/secretaries`, `/admins`. Special pages: `/login`, `/logout`, `/installation`, `/about`, `/privacy`, `/consents`.

## Git & Repository

- **Remote**: `git@github.com-personal:HaiAtoon/easyappointments.git` (uses SSH alias `github.com-personal`)
- **Only the personal GitHub account (HaiAtoon / haiaton92@gmail.com) is allowed to push to this repo.** Never use the work account (Hai-Medhub / hai.a@medhub-ai.com) for commits or pushes here.

## Key Patterns

- Models use `$api_resource` array to map between camelCase API field names and snake_case database columns
- Models use `$casts` array for automatic type casting of database values
- Deprecated methods (e.g., `get_value`, `get_record`) delegate to newer equivalents (`value`, `find`) via `method_exists` checks
- 20+ language files in `application/language/` for internationalization
- `patch.php` handles incremental updates from CDN
