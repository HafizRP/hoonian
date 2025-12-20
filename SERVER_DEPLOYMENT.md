# 🚀 Cara Jalankan Hoonian di Server

## Persiapan Awal

### 1. Upload Project ke Server
```bash
# Di komputer lokal
cd ~/Documents/Campus/Semester\ 4/Webpro\ III/hoonian

# Compress project (exclude file yang tidak perlu)
tar -czf hoonian.tar.gz \
  --exclude=node_modules \
  --exclude=vendor \
  --exclude=.git \
  --exclude=storage/logs/*.log \
  .

# Upload ke server (sesuaikan dengan server kamu)
scp hoonian.tar.gz user@server-ip:~/
```

### 2. Extract di Server
```bash
# Login ke server
ssh user@server-ip

# Extract project
mkdir -p ~/hoonian
tar -xzf hoonian.tar.gz -C ~/hoonian/
cd ~/hoonian
```

## Setup Database

### Opsi A: Pakai Database yang Sudah Ada (Recommended)

Jika server sudah punya MariaDB/MySQL yang jalan:

```bash
# Login ke database
mysql -u root -p

# Buat database baru
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Lalu edit `.env`:
```env
DB_HOST=mariadb          # atau 'host.docker.internal' jika pakai Docker
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=password-database-kamu
```

### Opsi B: Pakai Database dari Docker

Database akan otomatis dibuat oleh Docker Compose (lihat langkah selanjutnya).

## Konfigurasi Environment

```bash
# Copy file .env
cp .env.example .env

# Edit .env
nano .env
```

**Setting penting di .env:**
```env
APP_NAME=Hoonian
APP_ENV=production
APP_KEY=                              # Akan di-generate nanti
APP_DEBUG=false
APP_URL=http://server-ip:8004         # Ganti dengan IP server kamu
APP_PORT=8004

# Database (sesuaikan dengan setup kamu)
DB_CONNECTION=mysql
DB_HOST=mariadb                       # atau 'host.docker.internal'
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=password-kamu

# Redis (otomatis dari Docker)
REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis

# Google OAuth (opsional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://server-ip:8004/auth/google/callback
```

## Jalankan dengan Docker

### 1. Build Docker Image
```bash
# Build image
sudo docker compose build

# Atau build tanpa cache (jika ada masalah)
sudo docker compose build --no-cache
```

### 2. Generate Application Key
```bash
# Generate APP_KEY
sudo docker compose run --rm app php artisan key:generate --show

# Copy hasilnya (contoh: base64:abc123...)
# Paste ke .env di baris APP_KEY=
nano .env
```

### 3. Start Containers
```bash
# Start semua container
sudo docker compose up -d

# Cek status
sudo docker compose ps
```

Kamu akan lihat container:
- ✅ `hoonian-nginx` (port 8004)
- ✅ `hoonian-app` (PHP-FPM)
- ✅ `hoonian-redis` (Cache)

### 4. Run Database Migrations
```bash
# Migrations otomatis jalan saat container start
# Atau run manual:
sudo docker compose exec app php artisan migrate --force

# (Opsional) Seed data dummy
sudo docker compose exec app php artisan db:seed --force
```

### 5. Akses Aplikasi
Buka browser: **http://server-ip:8004**

## Perintah Berguna

### Manajemen Container
```bash
# Lihat logs
sudo docker compose logs -f app
sudo docker compose logs -f nginx

# Restart container
sudo docker compose restart

# Stop container
sudo docker compose down

# Start container
sudo docker compose up -d

# Rebuild dan restart
sudo docker compose down
sudo docker compose build
sudo docker compose up -d
```

### Laravel Commands
```bash
# Masuk ke container
sudo docker compose exec app bash

# Clear cache
sudo docker compose exec app php artisan cache:clear
sudo docker compose exec app php artisan config:clear
sudo docker compose exec app php artisan route:clear
sudo docker compose exec app php artisan view:clear

# Atau clear semua sekaligus
sudo docker compose exec app php artisan optimize:clear

# Optimize untuk production
sudo docker compose exec app php artisan optimize

# Run migrations
sudo docker compose exec app php artisan migrate

# Create storage link
sudo docker compose exec app php artisan storage:link
```

### Database Commands
```bash
# Cek koneksi database
sudo docker compose exec app php artisan db:show

# Akses database dari host
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian

# Backup database
sudo docker compose exec app php artisan db:backup
# atau
mysqldump -h 127.0.0.1 -P 3306 -u root -p hoonian > backup.sql

# Restore database
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian < backup.sql
```

## Troubleshooting

### 1. Port Sudah Dipakai
```bash
# Cek port yang dipakai
sudo netstat -tulpn | grep 8004

# Ganti port di .env
nano .env
# Ubah: APP_PORT=8005

# Restart
sudo docker compose down
sudo docker compose up -d
```

### 2. Container Tidak Start
```bash
# Lihat error logs
sudo docker compose logs app
sudo docker compose logs nginx

# Rebuild dari awal
sudo docker compose down -v
sudo docker compose build --no-cache
sudo docker compose up -d
```

### 3. Permission Error
```bash
# Fix permission storage
sudo docker compose exec app chmod -R 775 storage bootstrap/cache
sudo docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### 4. Database Connection Error
```bash
# Cek apakah database sudah ready
sudo docker compose exec app php artisan db:show

# Cek environment variables
sudo docker compose exec app env | grep DB_

# Test koneksi manual
sudo docker compose exec app php artisan tinker
# Lalu ketik: DB::connection()->getPdo();
```

### 5. Nginx 502 Bad Gateway
```bash
# Cek PHP-FPM
sudo docker compose exec app ps aux | grep php-fpm

# Restart app
sudo docker compose restart app

# Cek nginx config
sudo docker compose exec nginx nginx -t
```

### 6. Image/File Upload Tidak Muncul
```bash
# Create storage link
sudo docker compose exec app php artisan storage:link

# Fix permission
sudo docker compose exec app chmod -R 775 storage/app/public
sudo docker compose exec app chown -R www-data:www-data storage/app/public
```

## Update Aplikasi

```bash
# Pull code terbaru (jika pakai git)
git pull origin main

# Atau upload file baru
# scp hoonian.tar.gz user@server-ip:~/
# tar -xzf hoonian.tar.gz -C ~/hoonian/

# Rebuild dan restart
cd ~/hoonian
sudo docker compose down
sudo docker compose build
sudo docker compose up -d

# Run migrations
sudo docker compose exec app php artisan migrate --force

# Clear cache
sudo docker compose exec app php artisan optimize
```

## Monitoring

```bash
# Lihat semua container
sudo docker ps

# Resource usage
sudo docker stats

# Logs real-time
sudo docker compose logs -f

# Disk usage
sudo docker system df
```

## Firewall (Jika Perlu)

```bash
# UFW
sudo ufw allow 8004/tcp
sudo ufw reload

# Firewalld
sudo firewall-cmd --permanent --add-port=8004/tcp
sudo firewall-cmd --reload
```

## Backup Otomatis

### Script Backup Database
```bash
# Buat file backup.sh
nano ~/backup-hoonian.sh
```

Isi file:
```bash
#!/bin/bash
BACKUP_DIR=~/backups/hoonian
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup database
cd ~/hoonian
sudo docker compose exec -T app php artisan db:backup > $BACKUP_DIR/db-$DATE.sql

# Hapus backup lama (lebih dari 7 hari)
find $BACKUP_DIR -name "db-*.sql" -mtime +7 -delete

echo "Backup completed: $BACKUP_DIR/db-$DATE.sql"
```

```bash
# Buat executable
chmod +x ~/backup-hoonian.sh

# Test
~/backup-hoonian.sh

# Setup cron (backup setiap hari jam 2 pagi)
crontab -e
# Tambahkan:
0 2 * * * ~/backup-hoonian.sh
```

## Checklist Production

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` sudah di-generate
- [ ] Database password kuat
- [ ] Firewall configured
- [ ] Backup strategy setup
- [ ] SSL/TLS (pakai reverse proxy seperti Nginx/Caddy)
- [ ] Google OAuth configured (jika perlu)
- [ ] Monitoring setup

## Port yang Dipakai

| Service | Port | Akses |
|---------|------|-------|
| Nginx | 8004 | Public (http://server-ip:8004) |
| PHP-FPM | 9000 | Internal only |
| Redis | 6379 | Internal only |
| MariaDB | 3306 | Host/External |

## Struktur Docker

```
┌─────────────────────────────────────┐
│         Nginx (Port 8004)           │
│         (hoonian-nginx)             │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│      PHP-FPM 8.2 (Port 9000)        │
│         (hoonian-app)               │
│      - Laravel Application          │
│      - Migrations auto-run          │
└──────┬──────────────────────┬───────┘
       │                      │
       ▼                      ▼
┌──────────────┐      ┌──────────────┐
│    Redis     │      │   MariaDB    │
│ (hoonian-    │      │  (external   │
│  redis)      │      │   or mariadb │
└──────────────┘      │  container)  │
                      └──────────────┘
```

---

**Butuh bantuan?**
- Detail lengkap: `DOCKER_DEPLOYMENT.md`
- Invoice system: `INVOICE_SYSTEM.md`
- External DB: `EXTERNAL_DATABASE.md`

**Happy Deploying! 🚀**
