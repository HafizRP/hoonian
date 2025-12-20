# 🚀 Quick Start - Hoonian

## Deployment Cepat (5 Menit)

### 1. Setup Environment
```bash
# Copy .env
cp .env.example .env

# Edit .env (sesuaikan DB_PASSWORD, APP_URL, dll)
nano .env
```

### 2. Jalankan Aplikasi
```bash
# Buat script executable
chmod +x docker.sh

# Setup otomatis (build + start + migrate)
./docker.sh setup
```

### 3. Akses Aplikasi
Buka browser: **http://localhost:8004**

---

## Perintah Penting

```bash
# Start/Stop
./docker.sh start          # Start containers
./docker.sh stop           # Stop containers
./docker.sh restart        # Restart containers

# Logs & Status
./docker.sh logs           # Lihat semua logs
./docker.sh logs app       # Lihat logs app saja
./docker.sh status         # Status containers

# Database
./docker.sh migrate        # Run migrations
./docker.sh seed           # Seed database
./docker.sh backup         # Backup database
./docker.sh db             # Akses database CLI

# Laravel
./docker.sh artisan route:list        # Run artisan command
./docker.sh composer require pkg      # Install package
./docker.sh clear                     # Clear cache
./docker.sh optimize                  # Optimize app

# Maintenance
./docker.sh shell          # Masuk ke container
./docker.sh rebuild        # Rebuild dari awal
./docker.sh update         # Update app (git pull + rebuild)
```

---

## Dokumentasi Lengkap

- **Deployment di Server**: `SERVER_DEPLOYMENT.md` ⭐ **BACA INI DULU**
- **Docker Detail**: `DOCKER_DEPLOYMENT.md`
- **Quick Deploy**: `QUICK_DEPLOY.md`
- **Invoice System**: `INVOICE_SYSTEM.md`
- **External Database**: `EXTERNAL_DATABASE.md`

---

## Troubleshooting Cepat

### Port sudah dipakai
```bash
# Edit .env, ubah APP_PORT=8005
./docker.sh restart
```

### Container error
```bash
./docker.sh logs app       # Cek error
./docker.sh rebuild        # Rebuild
```

### Database error
```bash
./docker.sh logs           # Cek logs
# Pastikan DB_HOST, DB_PASSWORD di .env benar
```

### Permission error
```bash
./docker.sh shell
chmod -R 775 storage bootstrap/cache
```

---

**Butuh bantuan?** Baca `SERVER_DEPLOYMENT.md` untuk panduan lengkap! 🚀
