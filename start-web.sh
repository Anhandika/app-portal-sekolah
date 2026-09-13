#!/usr/bin/env bash
set -e

# Pastikan .env & APP_KEY ada (Railway menyuplai env lewat platform, bukan file .env).
# Mencegah error: file_get_contents(/app/.env): Failed to open stream.
bash ensure-env.sh

# Pastikan direktori penyimpanan runtime tersedia (fresh clone di Railway).
mkdir -p storage/framework/{cache,sessions,views,testing} storage/logs storage/fonts storage/app/public bootstrap/cache

echo "Linking public storage (storage:link)..."
# Non-fatal: jangan biarkan set -e membunuh container bila symlink gagal
# (mis. masalah izin volume) — aplikasi tetap bisa boot.
if [ -e public/storage ] || [ -L public/storage ]; then
  rm -rf public/storage
fi
php artisan storage:link --no-interaction || echo "WARNING: storage:link gagal — lanjut boot"

echo "Clearing stale caches..."
php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

echo "Running database migrations..."
php artisan migrate --force || true

echo "Seeding feature flags (idempotent, hanya isi key yang belum ada)..."
if php artisan db:seed --class="Database\Seeders\FeatureFlagsSeeder" --force --no-interaction; then
  echo "FeatureFlagsSeeder OK"
else
  echo "!!! FeatureFlagsSeeder GAGAL — periksa storage/logs/laravel.log"
fi

echo "Seeding data portal (idempotent via firstOrCreate)..."
if php artisan db:seed --class="Database\Seeders\PortalFullSeeder" --force --no-interaction; then
  echo "PortalFullSeeder OK"
else
  echo "!!! PortalFullSeeder GAGAL — data test TIDAK terbuat. Periksa storage/logs/laravel.log dan jalankan manual via console Railway."
fi

echo "Caching config and routes..."
# NOTE: route:cache GAGAL bila ada closure route (welcome, health, fitur-terkunci
# memakai closure) -> jangan biarkan set -e membunuh container (crash loop Railway).
php artisan config:cache --no-interaction || true
php artisan route:cache --no-interaction || { echo "route:cache skip (closure route)"; php artisan route:clear --no-interaction || true; }

echo "Starting web server on port ${PORT:-8080}..."
echo "Workers: ${PHP_CLI_SERVER_WORKERS:-4}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
