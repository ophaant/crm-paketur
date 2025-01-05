# CRM Sederhana

## Requirements

- PHP 8.4
- Laravel 11
- Composer 2.8.3
- PostgreSQL
- Git
- tymon/jwt-auth (v2.1)
- spatie/laravel-permission (v6.10)
- yaza/laravel-repository-service (v5.1)

## Installation

Follow these steps to set up the project on your local machine:

### 1. Clone the Repository

```bash
git clone https://github.com/ophaant/crm-paketur.git
cd crm-paketur
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Set Up Environment Variables

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the `.env` file with your environment-specific settings:

- **APP_NAME**: Your application name
- **APP_URL**: Application URL (e.g., http://localhost)
- **DB_CONNECTION**: Database driver (mysql, pgsql, etc.)
- **DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD**: Database connection details

### 4. Generate Application Key

```bash
php artisan key:generate

php artisan jwt:secret
```

### 5. Configure Permissions (Optional)

Ensure the `storage` and `bootstrap/cache` directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

### 6. Run Migrations

Create the database and run the migrations:

```bash
php artisan migrate:fresh --seed
```

### 7. Seed the Database (Optional)

To populate the database with sample data:

```bash
php artisan db:seed
```

### 8. Serve the Application

```bash
php artisan serve
```

Visit `http://localhost:8000/api/` in your browser.

## Running Tests

To run the test suite:

```bash
php artisan test
```

## List User Test

| Username                | Password   |
|-------------------------|------------|
| `test@example.com`      | `password` |
| `employee1@example.com` | `password` |
| `manager1@example.com`  | `password` |

## Troubleshooting

- Ensure you have the correct PHP and Composer versions.
- If you encounter permission issues, ensure correct ownership of files.

