# 🔧 Troubleshooting Guide - Hoonian

Panduan lengkap untuk mengatasi masalah umum saat deployment.

## 🚨 Masalah Umum & Solusi

### 1. Container Tidak Bisa Start

#### Error: "port is already allocated"
```bash
# Cek port yang dipakai
sudo netstat -tulpn | grep 8004

# Solusi 1: Ganti port di .env
nano .env
# Ubah: APP_PORT=8005

# Solusi 2: Stop container yang pakai port tersebut
docker ps
docker stop <container-id>

# Restart
./docker.sh restart
```

#### Error: "no space left on device"
```bash
# Cek disk usage
df -h
docker system df

# Clean up Docker
docker system prune -a
docker volume prune

# Remove unused images
docker image prune -a
```

### 2. Database Connection Error

#### Error: "SQLSTATE[HY000] [2002] Connection refused"
```bash
# Cek 1: Database container running?
docker ps | grep mariadb

# Cek 2: Environment variables benar?
./docker.sh shell
env | grep DB_

# Cek 3: Database host benar?
# Jika pakai external DB: DB_HOST=host.docker.internal
# Jika pakai Docker DB: DB_HOST=mariadb

# Cek 4: Test koneksi manual
./docker.sh shell
php artisan db:show
```

#### Error: "Access denied for user"
```bash
# Password salah di .env
nano .env
# Pastikan DB_PASSWORD sama dengan database password

# Test login manual
mysql -h 127.0.0.1 -P 3306 -u root -p
# Masukkan password yang benar
```

#### Error: "Unknown database 'hoonian'"
```bash
# Database belum dibuat
mysql -u root -p
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Restart container
./docker.sh restart
```

### 3. Migration Error

#### Error: "Migration table not found"
```bash
# Run migrations
./docker.sh migrate

# Jika masih error, fresh migration
./docker.sh fresh  # WARNING: Hapus semua data!
```

#### Error: "Syntax error in migration"
```bash
# Lihat error detail
./docker.sh logs app

# Rollback migration
./docker.sh shell
php artisan migrate:rollback

# Fix migration file, lalu migrate lagi
./docker.sh migrate
```

### 4. Permission Error

#### Error: "Permission denied" di storage/logs
```bash
# Masuk ke container
./docker.sh shell

# Fix permission
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Atau rebuild (permission sudah di-set di Dockerfile)
./docker.sh rebuild
```

#### Error: "Failed to create symbolic link"
```bash
# Create storage link manual
./docker.sh shell
php artisan storage:link

# Atau hapus link lama dulu
rm -f public/storage
php artisan storage:link
```

### 5. Nginx 502 Bad Gateway

#### Penyebab: PHP-FPM tidak running
```bash
# Cek PHP-FPM
./docker.sh shell
ps aux | grep php-fpm

# Jika tidak ada, restart container
exit
./docker.sh restart app

# Cek logs
./docker.sh logs app
```

#### Penyebab: Nginx config salah
```bash
# Test nginx config
docker compose exec nginx nginx -t

# Lihat logs
./docker.sh logs nginx

# Restart nginx
docker compose restart nginx
```

### 6. File Upload Tidak Muncul

#### Storage link tidak ada
```bash
# Create storage link
./docker.sh shell
php artisan storage:link

# Cek apakah link sudah ada
ls -la public/ | grep storage
```

#### Permission salah
```bash
./docker.sh shell
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public
```

### 7. Cache Issues

#### Config cache outdated
```bash
# Clear semua cache
./docker.sh clear

# Atau manual
./docker.sh shell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan optimize
```

#### Redis connection error
```bash
# Cek Redis container
docker ps | grep redis

# Restart Redis
docker compose restart redis

# Test Redis
./docker.sh shell
php artisan tinker
# >>> Cache::put('test', 'value', 60);
# >>> Cache::get('test');
```

### 8. Build Error

#### Error: "composer install failed"
```bash
# Clear composer cache
docker compose run --rm app composer clear-cache

# Rebuild tanpa cache
./docker.sh rebuild
```

#### Error: "Could not find package"
```bash
# Update composer
./docker.sh shell
composer update

# Atau rebuild
exit
./docker.sh rebuild
```

### 9. Invoice PDF Error

#### Error: "Class 'DomPDF' not found"
```bash
# Install DomPDF
./docker.sh composer require barryvdh/laravel-dompdf

# Clear cache
./docker.sh clear
```

#### PDF tidak ter-generate
```bash
# Cek logs
./docker.sh logs app

# Test manual
./docker.sh shell
php artisan tinker
# >>> $pdf = PDF::loadView('test');
# >>> $pdf->save('test.pdf');
```

### 10. Google OAuth Error

#### Error: "Invalid redirect URI"
```bash
# Pastikan GOOGLE_REDIRECT_URI benar di .env
nano .env
# GOOGLE_REDIRECT_URI=http://your-domain:8004/auth/google/callback

# Clear config cache
./docker.sh clear
```

#### Error: "Invalid client"
```bash
# Cek GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET
nano .env

# Pastikan credentials benar di Google Console
# https://console.cloud.google.com/apis/credentials
```

## 🔍 Debugging Tools

### View Logs
```bash
# All logs
./docker.sh logs

# Specific service
./docker.sh logs app
./docker.sh logs nginx
./docker.sh logs redis

# Follow logs (real-time)
./docker.sh logs -f app

# Last 100 lines
docker compose logs --tail=100 app
```

### Access Container Shell
```bash
# App container
./docker.sh shell

# Nginx container
docker compose exec nginx sh

# Redis container
docker compose exec redis redis-cli
```

### Check Container Status
```bash
# List containers
docker ps

# Container details
docker inspect hoonian-app

# Resource usage
docker stats

# Health check
docker ps --format "table {{.Names}}\t{{.Status}}"
```

### Database Debugging
```bash
# Access database
./docker.sh db

# Or manual
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian

# Show tables
SHOW TABLES;

# Describe table
DESCRIBE users;

# Check data
SELECT * FROM users LIMIT 5;
```

### Laravel Debugging
```bash
# Tinker (Laravel REPL)
./docker.sh shell
php artisan tinker

# Test database
>>> DB::connection()->getPdo();

# Test cache
>>> Cache::put('test', 'value');
>>> Cache::get('test');

# Test config
>>> config('app.name');
>>> config('database.default');
```

## 📊 Health Check

### Quick Health Check
```bash
# 1. Containers running?
docker ps

# 2. Database accessible?
./docker.sh shell
php artisan db:show

# 3. Application accessible?
curl http://localhost:8004

# 4. Storage writable?
ls -la storage/

# 5. Cache working?
php artisan cache:clear
```

### Full System Check
```bash
# Run all checks
./docker.sh shell

# 1. PHP version
php -v

# 2. Extensions
php -m | grep -E 'pdo_mysql|redis|gd|zip'

# 3. Composer
composer --version

# 4. Laravel
php artisan --version

# 5. Database
php artisan db:show

# 6. Config
php artisan config:show

# 7. Routes
php artisan route:list
```

## 🆘 Emergency Recovery

### Reset Everything
```bash
# WARNING: Ini akan hapus SEMUA data!

# 1. Stop containers
./docker.sh stop

# 2. Remove containers & volumes
docker compose down -v

# 3. Clean Docker
docker system prune -a

# 4. Rebuild from scratch
./docker.sh setup
```

### Backup Before Reset
```bash
# 1. Backup database
./docker.sh backup

# 2. Backup storage
tar -czf storage-backup.tar.gz storage/

# 3. Backup .env
cp .env .env.backup

# Now safe to reset
```

### Restore After Reset
```bash
# 1. Setup fresh
./docker.sh setup

# 2. Restore .env
cp .env.backup .env

# 3. Restore database
./docker.sh restore backup-20251220.sql

# 4. Restore storage
tar -xzf storage-backup.tar.gz

# 5. Fix permissions
./docker.sh shell
chmod -R 775 storage
```

## 📞 Getting Help

### Check Documentation
1. `QUICKSTART.md` - Quick reference
2. `SERVER_DEPLOYMENT.md` - Deployment guide
3. `DOCKER_DEPLOYMENT.md` - Docker details
4. `INVOICE_SYSTEM.md` - Invoice system

### Common Commands
```bash
./docker.sh help          # Show all commands
./docker.sh logs app      # View logs
./docker.sh shell         # Access container
./docker.sh status        # Check status
```

### Still Having Issues?

1. **Check logs first:**
   ```bash
   ./docker.sh logs app
   ```

2. **Try rebuilding:**
   ```bash
   ./docker.sh rebuild
   ```

3. **Check environment:**
   ```bash
   ./docker.sh shell
   env | grep -E 'DB_|APP_|REDIS_'
   ```

4. **Test components:**
   ```bash
   # Database
   php artisan db:show
   
   # Cache
   php artisan cache:clear
   
   # Config
   php artisan config:show
   ```

---

**Masih ada masalah?** Buat issue di repository atau hubungi support.
