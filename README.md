# Laravel URL Shortener

A simple Laravel MVC assignment project for creating public short URLs inside a multi-company system.

## Features

- Login and logout using Laravel session authentication.
- Exactly three roles: SuperAdmin, Admin, and Member.
- SuperAdmin can invite an Admin while creating a new company.
- Admin can invite Admins and Members only inside their own company.
- Admin and Member users can create short URLs.
- SuperAdmin cannot create short URLs.
- Public short URL redirects work without login.
- URL listing is scoped by role.

## Technology Used

- Laravel 10
- PHP 8.1 or newer
- MySQL
- Laravel Blade
- Simple HTML/CSS

## Requirements

- PHP 8.1+
- Composer
- MySQL server with the `laravel_url_shortener` database

## Installation

Clone the existing repository and install dependencies:

```bash
composer install
```

Copy the environment file:

```bash
cp .env.example .env
```

Generate the app key:

```bash
php artisan key:generate
```

## Database Configuration

The project uses MySQL. Configure `.env` with:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_url_shortener
DB_USERNAME=root
DB_PASSWORD=
```

Create the `laravel_url_shortener` database in MySQL before running migrations.

## Migrations and Seeder

Run migrations and seed the SuperAdmin:

```bash
php artisan migrate --seed
```

To rebuild the database during development:

```bash
php artisan migrate:fresh --seed
```

## Run the Project

```bash
php artisan serve
```

Open:

```text
http://localhost:8000
```

## SuperAdmin Credentials

```text
Email: superadmin@example.com
Password: password
```

## Role Permissions

SuperAdmin:
- Can see all short URLs from every company.
- Can invite an Admin into a new company.
- Cannot create short URLs.

Admin:
- Can invite another Admin or Member in their own company.
- Can create short URLs.
- Can see all short URLs from their own company.
- Cannot access another company's users or URLs.

Member:
- Can create short URLs.
- Can see only short URLs created by themselves.
- Cannot invite users.

## Important Routes

- `GET /login` - login form
- `POST /login` - login
- `POST /logout` - logout
- `GET /dashboard` - role-based dashboard
- `GET /invitations/create` - invite user form
- `POST /invitations` - create invitation and user
- `GET /short-urls/create` - create short URL form
- `POST /short-urls` - save short URL
- `GET /{code}` - public short URL redirect

## How Short URLs Work

When an Admin or Member submits an original URL, the app creates a random 6-character code and stores it in `short_urls`.

Example:

```text
Original URL: https://example.com/very/long/url
Short URL: http://localhost:8000/abc123
```

Opening `/abc123` does not require login. The app finds the matching `short_urls.code` record and redirects to the original URL.

## Basic Testing

Run the automated tests:

```bash
php artisan test
```

The feature tests cover:
- Admin can create short URLs.
- Member can create short URLs.
- SuperAdmin cannot create short URLs.
- SuperAdmin sees all company URLs.
- Admin sees only company URLs.
- Member sees only their own URLs.
- Public short URL redirect works without login.
- Admin cannot access another company's URLs.
- Member cannot access another user's URLs.
- Invitation permissions are enforced.

## Demo Video Checklist

1. Login as SuperAdmin.
2. SuperAdmin invites an Admin for a new company.
3. Login as that Admin.
4. Admin invites another Admin or Member.
5. Admin creates a short URL.
6. Login as Member.
7. Member creates a short URL.
8. Admin sees URLs from their own company.
9. Member sees only their own URLs.
10. SuperAdmin sees URLs from all companies.
11. SuperAdmin is prevented from creating a short URL.
12. Open a short URL without login.
13. Show that it redirects to the original URL.

## Interview Explanation

The app uses normal Laravel MVC. Companies own users and short URLs. Users have a simple `role` column for SuperAdmin, Admin, or Member. Controllers enforce authorization before saving or showing data, so security does not depend on hidden buttons in Blade views.

The invitation flow is intentionally simple for the assignment: inviting a user creates an invitation record and a user account with a temporary password. SuperAdmin creates a new company while inviting its first Admin. Admin users invite only inside their own company.
