# 🔧 Fix: Docker Network Error - SOLVED!

## ❌ Error yang Terjadi

```
Error response from daemon: invalid config for network bridge: invalid endpoint settings:
network-scoped alias is supported only for containers in user defined networks
```

## ✅ Sudah Diperbaiki!

File `docker-compose.yml` sudah diperbaiki dengan:
1. ✅ Remove `version: '3.8'` (sudah obsolete)
2. ✅ Remove external bridge network (penyebab error)
3. ✅ Add `extra_hosts` untuk akses database di host

## 🚀 Cara Pakai Sekarang

### Opsi 1: Database di Container Lain (mariadb)

Jika database kamu ada di container `mariadb` yang sudah running:

**Di `.env`:**
```env
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456
```

**Jalankan:**
```bash
# Pastikan container mariadb sudah running
docker ps | grep mariadb

# Generate APP_KEY dengan cara berbeda (tanpa network issue)
docker run --rm -v $(pwd):/app -w /app composer:latest php -r "require 'vendor/autoload.php'; echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"

# Atau install PHP di server dan generate manual
php artisan key:generate --show

# Lalu start
docker compose up -d
```

### Opsi 2: Database di Host (Recommended untuk Server Kamu)

Jika database kamu running langsung di server (bukan di container):

**Di `.env`:**
```env
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456
```

**Jalankan:**
```bash
# Generate APP_KEY (cara alternatif)
# Opsi 1: Pakai PHP di server
php artisan key:generate --show

# Opsi 2: Generate manual
echo "base64:$(openssl rand -base64 32)"

# Copy hasilnya, paste ke .env di baris APP_KEY=

# Start
docker compose up -d
```

## 🎯 Generate APP_KEY - 3 Cara

### Cara 1: PHP di Server (Paling Mudah)
```bash
# Install PHP dulu jika belum ada
sudo apt install php-cli

# Generate
php artisan key:generate --show

# Copy hasilnya
```

### Cara 2: OpenSSL
```bash
# Generate dengan openssl
echo "base64:$(openssl rand -base64 32)"

# Copy hasilnya, paste ke .env
```

### Cara 3: Docker Run (Tanpa Network Issue)
```bash
# Generate dengan Docker
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"

# Copy hasilnya
```

## 📋 Langkah Deploy yang Benar (Updated)

### 1. Setup .env
```bash
cp .env.example .env
nano .env
```

Edit:
```env
APP_URL=http://your-server-ip:8004
DB_HOST=host.docker.internal    # Untuk database di host
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456
```

### 2. Generate APP_KEY
```bash
# Cara termudah (jika ada PHP)
php artisan key:generate --show

# Atau pakai openssl
echo "base64:$(openssl rand -base64 32)"

# Copy hasilnya
nano .env
# Paste di APP_KEY=
```

### 3. Test Database Connection
```bash
# Test dari server
mysql -h 127.0.0.1 -P 3306 -u hoonian_admin -p123456 hoonian -e "SELECT 1;"
```

### 4. Build & Start
```bash
# Build
docker compose build

# Start
docker compose up -d

# Cek status
docker compose ps
```

### 5. Verify
```bash
# Cek logs
docker compose logs app

# Test database connection dari container
docker compose exec app php artisan db:show
```

## 🔍 Troubleshooting

### Masih Error Network?
```bash
# Clean up semua
docker compose down
docker network prune

# Rebuild
docker compose build
docker compose up -d
```

### Container mariadb tidak ditemukan?
Kamu punya 2 opsi:

**Opsi A: Pakai database di host**
```env
DB_HOST=host.docker.internal
```

**Opsi B: Pakai container mariadb yang sudah ada**
```bash
# Cek nama container database
docker ps | grep maria

# Jika ada container mariadb, pastikan di network yang sama
# Atau gunakan host.docker.internal
```

### Orphan container hoonian-db?
```bash
# Remove orphan containers
docker compose down --remove-orphans
```

## ✅ Verification Checklist

- [ ] `docker-compose.yml` sudah di-update (no version, no external network)
- [ ] `.env` file sudah dibuat
- [ ] `APP_KEY` sudah di-generate dan di-paste ke `.env`
- [ ] `DB_HOST` sudah benar (`host.docker.internal` atau `mariadb`)
- [ ] Database connection berhasil di-test
- [ ] `docker compose build` berhasil
- [ ] `docker compose up -d` berhasil
- [ ] Containers running: `docker compose ps`
- [ ] Aplikasi accessible: `http://server-ip:8004`

## 📝 Summary

**Yang Berubah:**
- ✅ `docker-compose.yml` diperbaiki (no external bridge network)
- ✅ Added `extra_hosts` untuk akses host database
- ✅ Removed obsolete `version` field

**Cara Generate APP_KEY:**
- ✅ Pakai PHP: `php artisan key:generate --show`
- ✅ Pakai OpenSSL: `echo "base64:$(openssl rand -base64 32)"`
- ✅ Pakai Docker: `docker run --rm php:8.2-cli php -r "..."`

**Database Connection:**
- ✅ Untuk DB di host: `DB_HOST=host.docker.internal`
- ✅ Untuk DB di container: `DB_HOST=mariadb`

---

**Sekarang coba lagi!** 🚀
