# 🚀 Setup Hoonian - Quick Guide untuk Server Kamu

## ✅ Yang Sudah Siap

- ✅ Database user: `hoonian_admin` (password: `123456`)
- ✅ Dockerfile sudah dioptimasi
- ✅ Docker helper script siap
- ✅ Dokumentasi lengkap

## 📋 Langkah Setup (Copy-Paste Ready!)

### 1. Setup Environment File

```bash
# Copy .env.example
cp .env.example .env

# Credentials sudah sesuai dengan user yang kamu buat!
# DB_USERNAME=hoonian_admin
# DB_PASSWORD=123456
```

**PENTING:** Edit `.env` dan sesuaikan:
```bash
nano .env
```

Pastikan setting ini:
```env
APP_URL=http://your-server-ip:8004
DB_HOST=mariadb                    # atau host.docker.internal jika external DB
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456
```

### 2. Generate Application Key

```bash
# Generate APP_KEY
docker compose run --rm app php artisan key:generate --show

# Copy output (contoh: base64:abc123...)
# Paste ke .env
nano .env
# Update baris: APP_KEY=base64:abc123...
```

### 3. Pastikan Database Siap

```bash
# Test koneksi database
mysql -h 127.0.0.1 -P 3306 -u hoonian_admin -p123456 hoonian -e "SHOW TABLES;"

# Jika database belum ada, buat:
mysql -u root -p
```
```sql
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian_admin'@'%';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Jalankan Aplikasi

```bash
# Buat script executable
chmod +x docker.sh

# Setup otomatis (build + start + migrate)
./docker.sh setup
```

**Atau manual:**
```bash
# Build
docker compose build

# Start
docker compose up -d

# Cek status
docker compose ps

# Run migrations
docker compose exec app php artisan migrate --force

# (Optional) Seed data dummy
docker compose exec app php artisan db:seed --force
```

### 5. Akses Aplikasi

```
http://localhost:8004
```

Atau jika di server:
```
http://your-server-ip:8004
```

## 🎯 Perintah Berguna

```bash
# Lihat logs
./docker.sh logs app

# Restart
./docker.sh restart

# Shell access
./docker.sh shell

# Clear cache
./docker.sh clear

# Backup database
./docker.sh backup

# Lihat semua perintah
./docker.sh help
```

## 🔍 Troubleshooting Cepat

### Container tidak start?
```bash
./docker.sh logs app
./docker.sh rebuild
```

### Database connection error?
```bash
# Cek environment
./docker.sh shell
env | grep DB_

# Test koneksi
php artisan db:show
```

### Permission error?
```bash
./docker.sh shell
chmod -R 775 storage bootstrap/cache
```

## 📊 Verifikasi Setup

```bash
# 1. Container running?
docker ps

# 2. Database connected?
./docker.sh shell
php artisan db:show

# 3. Application accessible?
curl -I http://localhost:8004

# 4. Migrations done?
./docker.sh shell
php artisan migrate:status
```

## 🔐 Security Checklist

- [ ] `.env` file sudah dikonfigurasi
- [ ] `APP_KEY` sudah di-generate
- [ ] Database connection berhasil
- [ ] Migrations berhasil dijalankan
- [ ] Application bisa diakses
- [ ] **GANTI password database untuk production!**

## ⚠️ PENTING untuk Production

**Password database saat ini (`123456`) TIDAK AMAN!**

Ganti password untuk production:
```sql
mysql -u root -p
ALTER USER 'hoonian_admin'@'%' IDENTIFIED BY 'StrongPassword123!@#';
FLUSH PRIVILEGES;
EXIT;
```

Lalu update `.env`:
```env
DB_PASSWORD=StrongPassword123!@#
```

Restart aplikasi:
```bash
./docker.sh restart
```

## 📚 Dokumentasi Lengkap

- **Quick Start**: `QUICKSTART.md`
- **Server Deployment**: `SERVER_DEPLOYMENT.md` ⭐
- **Database Setup**: `DATABASE_USER_SETUP.md`
- **Troubleshooting**: `TROUBLESHOOTING.md`
- **Cheat Sheet**: `CHEATSHEET.md`
- **All Docs**: `DOCS_INDEX.md`

## 🎉 Next Steps

Setelah aplikasi running:

1. **Login ke backoffice**
   - URL: http://your-server:8004/backoffice
   - Default admin (jika sudah seed):
     - Email: admin@hoonian.com
     - Password: password

2. **Test fitur utama**
   - Browse properties
   - Create property
   - Place bid
   - Generate invoice

3. **Setup production**
   - Ganti password database
   - Setup SSL/TLS
   - Setup backup otomatis
   - Setup monitoring

---

**Need Help?** Check `TROUBLESHOOTING.md` atau `./docker.sh help`

**Ready to deploy!** 🚀
