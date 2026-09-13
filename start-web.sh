#!/usr/bin/env bash
# =============================================================================
# Start script Railway — dioptimasi anti "terputus / mencoba kembali".
#
# 3 PENYEBAB UTAMA restart-loop SEBELUMNYA:
#  1. Boot LAMA: migrate -vvv + DatabaseSeeder BERAT (demo+portal+lms+tugas+nilai)
#     jalan di SETIAP restart -> healthcheck /up timeout -> Railway kill & retry.
#     FIX: seeder berat hanya jalan SEKALI (marker file), restart cukup migrate.
#  2. Langsung migrate tanpa tunggu Postgres siap -> koneksi ditolak -> crash.
#     FIX: tunggu DB responsif dulu (maks 60 dtk) sebelum migrate.
#  3. route:cache selalu GAGAL (closure routes) + config:cache tiap boot
#     menambah waktu start tanpa manfaat.
#     FIX: hapus keduanya, cukup clear.
# =============================================================================
set -e

cd "$(dirname "$0")"

bash ensure-env.sh

mkdir -p storage/framework/{cache,sessions,views,testing} storage/logs storage/fonts storage/app/public bootstrap/cache

echo "Linking public storage (storage:link)..."
if [ -e public/storage ] || [ -L public/storage ]; then
  rm -rf public/storage
fi
php artisan storage:link --no-interaction || echo "WARNING: storage:link gagal — lanjut boot"

echo "Clearing stale caches..."
php artisan config:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

# ---- Tunggu database siap (hindari crash saat Postgres cold-start) ----
echo "Waiting for database..."
DB_READY=0
for i in $(seq 1 30); do
  if php artisan db:show --no-interaction >/dev/null 2>&1; then
    DB_READY=1
    echo "Database OK (percobaan ke-$i)"
    break
  fi
  echo "  DB belum siap (percobaan $i/30) — tunggu 2 dtk..."
  sleep 2
done
if [ "$DB_READY" != "1" ]; then
  echo "!!! DB tidak merespons setelah 60 dtk — lanjut boot, /up akan 503 sampai DB pulih"
fi

echo "Running database migrations..."
php artisan migrate --force --no-interaction || echo "!!! MIGRATE GAGAL — lihat storage/logs/laravel.log"

# ---- Seed ringan (idempoten, cepat): flags + admin — SETIAP boot aman ----
echo "Seeding feature flags + admin (ringan, idempoten)..."
php artisan db:seed --class="Database\Seeders\FeatureFlagsSeeder" --force --no-interaction \
  && echo "FeatureFlagsSeeder OK" \
  || echo "!!! FeatureFlagsSeeder GAGAL — periksa storage/logs/laravel.log"

# ---- Seed BERAT (demo/portal/lms/tugas/nilai): hanya SEKALI per volume ----
# Alasan: DatabaseSeeder memakan waktu MENITAN dan membuat healthcheck timeout
# -> Railway mengira container mati -> kill -> "terputus dan mencoba kembali".
# Kontrol via env: SEED_ONCE=0 untuk memaksa skip total (mis. data prod sudah ada).
SEED_MARKER="storage/framework/.seed_heavy_done"
if [ "${SEED_ONCE:-1}" = "0" ]; then
  echo "SEED_ONCE=0 — seed berat dilewati (data dianggap sudah ada)."
elif [ -f "$SEED_MARKER" ]; then
  echo "Seed berat sudah pernah sukses (marker ada) — dilewati agar boot < 30 dtk."
else
  echo "Seed berat pertama kali (bisa 1-5 menit, hanya sekali ini)..."
  if php artisan db:seed --force --no-interaction; then
    echo "DatabaseSeeder OK — tulis marker agar restart berikutnya cepat."
    date -u +"%Y-%m-%dT%H:%M:%SZ" > "$SEED_MARKER"
  else
    echo "!!! DatabaseSeeder GAGAL — marker TIDAK ditulis, akan dicoba lagi saat deploy berikut."
  fi
fi

# NOTE: route:cache SENGAJA tidak dipakai (closure routes -> selalu gagal).
# config:cache juga dilewati: env Railway berubah dinamis per deploy.

# ---- Start server dengan worker + batas memori eksplisit ----
# PHP built-in server (artisan serve) + PHP_CLI_SERVER_WORKERS agar tidak
# single-thread. Opcache CLI diaktifkan untuk respons /up yang cepat.
WORKERS="${PHP_CLI_SERVER_WORKERS:-5}"
echo "Starting web server on port ${PORT:-8080} with ${WORKERS} workers..."
export PHP_CLI_SERVER_WORKERS="$WORKERS"
exec php -d opcache.enable_cli=1 -d opcache.jit=off -d memory_limit=512M \
  artisan serve --host=0.0.0.0 --port="${PORT:-8080}" --no-reload
