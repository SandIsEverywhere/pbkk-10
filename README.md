# PBKK-10 — Academic Dashboard and Agentic AI Platform

This Laravel application combines the existing agentic-AI chat and tool-execution demo with an academic administration dashboard. Authentication is provided by Laravel Breeze, the admin interface uses Laravel AdminLTE, and roles and permissions are stored in the database with Spatie Laravel Permission.

## Requirements

- PHP 8.3 or later
- Composer
- Node.js and npm
- A database supported by Laravel (SQLite is configured by default)

## Setup

From the project directory, install dependencies, configure the environment, migrate and seed the database, and build the frontend assets:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

In PowerShell, use `Copy-Item .env.example .env` instead of `cp` if the `.env` file does not already exist. Configure the database in `.env` before running migrations if you are not using the default SQLite setup.

Use `php artisan migrate:fresh --seed` only when you intend to drop and recreate all tables and seeded data.

## Authentication and academic dashboard

Sign in at `/login` using a verified account. Breeze registration creates a `mahasiswa` user; NRP and department are optional registration fields.

The AdminLTE dashboard is at `/admin/dashboard`. It is restricted to authenticated, email-verified users with the `admin` role. It displays live counts for mahasiswa, departments, and tugas, and provides a paginated mahasiswa list searchable by name or NRP. The regular `/dashboard` route redirects admins to the academic dashboard and shows the Breeze dashboard to other signed-in users.

Admins can manage tasks from `/admin/tugas`:

- Filter by department and search task titles or descriptions.
- Create tasks with a title, department, optional due date, and optional description.
- Pagination links retain the selected filters.
- Delete requests require both the `admin` role and the `kelola-tugas` permission.

The AdminLTE navigation shows dashboard and task links according to the signed-in user's permissions. Unauthorized dashboard or task-management requests are denied server-side.

## Roles and permissions

The `RoleAndPermissionSeeder` initializes Spatie's database-backed authorization records. Its roles and permissions are:

| Role | Permissions | Current access |
|---|---|---|
| `admin` | `kelola-nilai`, `kelola-tugas` | Academic dashboard and task management; deleting tasks also checks `kelola-tugas` |
| `asisten-dosen` | `kelola-nilai` | Has the grade-management permission; no grade-management page or route is currently implemented |
| `mahasiswa` | None by default | Student account; cannot access the admin route group |

Authorization data is stored in Spatie's `roles`, `permissions`, and pivot tables. Middleware aliases for `role` and `permission` are registered in `bootstrap/app.php`.

Run `php artisan db:seed --class=RoleAndPermissionSeeder` to initialize or update the roles and permissions. Run `php artisan db:seed` to seed all application demo data, including three departments and an admin account:

- Email: `test@example.com`
- Password: `password`

This is a development-only account. Change or remove its credentials before using seeded data in a deployed environment.

The separate `ChatDemoSeeder` creates a `demo@example.com` account with the password `password` and sample chat and agent data. That account is for the chat demo and is not assigned the `admin` role.

## Existing chat and agent demo

The application also includes user-owned chat sessions, messages, and polymorphic tool-execution logs associated with chat messages and agent scanner models. Signed-in users can browse their sessions at `/sessions`; the agent log overview is available at `/agents`.

## Tests

Run the Laravel test suite with:

```bash
php artisan test
```
