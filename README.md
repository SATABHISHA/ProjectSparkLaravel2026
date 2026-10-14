# Project SPARK · Laravel

API-first Laravel 12 website for Project SPARK, a collaborative health-awareness initiative of CE Foundation and CMRI.

## Run locally

1. Make sure the local XAMPP MariaDB server is running on port `3306`.
2. Create the database if needed:

   ```powershell
   C:\xampp\mysql\bin\mysql.exe --host=localhost --port=3306 --user=root -e "CREATE DATABASE IF NOT EXISTS projectspark CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

3. From this directory, run:

   ```powershell
   php artisan migrate --seed
   php artisan serve --host=127.0.0.1 --port=8000
   ```

Open `http://127.0.0.1:8000` for the public site and `http://127.0.0.1:8000/admin/login` for the admin studio.

The seeded admin account is `aryanlife6@gmail.com` with password `Ccf@2026`. Change it after first sign-in.

## API surface

- `GET /api/site` — public settings, content sections, focus areas and partners.
- `POST /api/contact` — public collaboration/contact submission.
- `POST /api/auth/login` — admin API token login for future Flutter/mobile clients.
- `GET /api/auth/me` and `POST /api/auth/logout` — bearer-token session endpoints.
- `/api/admin/*` — protected content, settings, inquiries and admin management endpoints.

The public page is API-backed and the admin studio edits the same database records. SMTP can be configured in the admin Settings panel for password reset and credential emails; when it is not configured, the UI offers a local reset link or a `mailto:` draft fallback.

## Content model

Content is stored in MySQL across `site_settings`, `content_sections`, `focus_areas`, `partners`, `contact_submissions`, `users`, and `api_tokens`. The supplied logo is available at `public/images/logo.jpeg`.
