# Portfolio API

Laravel 12 API for the portfolio frontend. Production deployment is prepared for Render Docker, Neon PostgreSQL, and Cloudinary.

## Local setup

```bash
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Create or update the administrator securely:

```bash
php artisan admin:create
```

See the repository-level `DEPLOYMENT_FREE.md` for the complete free deployment process.
