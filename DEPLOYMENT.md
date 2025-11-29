# Deploying StoryVerse to Vercel 🚀

Deploying a Laravel application to Vercel requires some specific configurations because Vercel is a **serverless** and **ephemeral** environment. This means:
1.  **No Persistent Local Storage**: Files uploaded to `storage/app/public` will disappear after a deployment or function restart. You **MUST** use a cloud storage service like AWS S3, Cloudinary, or R2.
2.  **No Local Database**: You need an external database provider (e.g., Neon, PlanetScale, Supabase, or a managed VPS database).

## 1. Prerequisites

### 1. Database (Aiven MySQL)
We are using **Aiven for MySQL** as the production database.
- **Service URI**: `mysql://avnadmin:PASSWORD@mysql-34d15f8f-bbaehaqee-e23b.g.aivencloud.com:19290/defaultdb?ssl-mode=REQUIRED`
- **SSL**: Required. Ensure your environment supports SSL connections.

**Environment Variables:**
```env
DB_CONNECTION=mysql
DB_HOST=mysql-34d15f8f-bbaehaqee-e23b.g.aivencloud.com
DB_PORT=19290
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=your_aiven_password
MYSQL_ATTR_SSL_CA=/path/to/ca.pem (Optional, if strict verification is needed)
```

### Storage (Critical for Covers)
For novel covers to persist, you must configure a cloud disk.
1.  Create an account on **Cloudinary**.
2.  Get your `CLOUDINARY_URL` from the dashboard.
3.  Add it to your Vercel Environment Variables.
4.  Set `FILESYSTEM_DISK` and `FILAMENT_FILESYSTEM_DISK` to `cloudinary`.

## 2. Configuration

I have already added the necessary files for Vercel:
-   `vercel.json`: Configures the PHP runtime and routing.
-   `api/index.php`: Entry point for the application.

## 3. Filament Production Setup

Filament requires its assets (CSS/JS) to be available. In production, we need to ensure these are built.

### Option A: Commit Assets (Easiest)
Run this locally and commit the files to Git:
```bash
php artisan filament:assets
git add public/css/filament public/js/filament
git commit -m "Build filament assets for production"
git push
```

### Option B: Build on Vercel
Add the command to your "Build Command" in Vercel settings (see below).

## 4. Vercel Project Setup

1.  **Import Project**: Go to Vercel Dashboard -> Add New -> Project -> Import `storyverse` from GitHub.
2.  **Framework Preset**: Select **Other**.
3.  **Build Command**:
    ```bash
    composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan filament:optimize
    ```
4.  **Output Directory**: `public`
5.  **Environment Variables**:
    Add these in the Vercel Project Settings:

    | Variable | Value |
    | :--- | :--- |
    | `APP_KEY` | (Copy from your local .env) |
    | `APP_URL` | `https://your-project.vercel.app` |
    | `DB_CONNECTION` | `pgsql` (or `mysql`) |
    | `DB_HOST` | (Your DB Host) |
    | `DB_PORT` | (Your DB Port) |
    | `DB_DATABASE` | (Your DB Name) |
    | `DB_USERNAME` | (Your DB User) |
    | `DB_PASSWORD` | (Your DB Password) |
    | `FILESYSTEM_DISK` | `cloudinary` (CRITICAL for uploads) |
    | `FILAMENT_FILESYSTEM_DISK` | `cloudinary` (Required for Filament uploads) |
    | `CLOUDINARY_URL` | `cloudinary://API_KEY:API_SECRET@CLOUD_NAME` |

## 5. Post-Deployment

After deployment, you need to run migrations. Since you can't SSH into Vercel, you can:
1.  Connect to your database remotely from your local machine and run `php artisan migrate`.
2.  Or create a temporary route in `routes/web.php` to run `Artisan::call('migrate')` (delete immediately after use!).

## ⚠️ Important Note on "Storage Link"
The command `php artisan storage:link` **does not work** on Vercel because the filesystem is read-only at runtime. This is why using **S3/Cloud storage is mandatory** for user uploads (covers, avatars).
