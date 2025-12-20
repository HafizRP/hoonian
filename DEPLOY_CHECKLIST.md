# ✅ Deployment Checklist - Step by Step

## 📋 Langkah-Langkah Deploy di Server

### ❌ JANGAN langsung `docker compose up`!

Ada beberapa langkah penting yang harus dilakukan dulu:

---

## 🚀 Langkah Deploy yang Benar

### **Step 1: Upload Project ke Server**

```bash
# Di komputer lokal
cd ~/Documents/Campus/Semester\ 4/Webpro\ III/hoonian

# Compress project
tar -czf hoonian.tar.gz \
  --exclude=node_modules \
  --exclude=vendor \
  --exclude=.git \
  --exclude=storage/logs/*.log \
  .

# Upload ke server (sesuaikan dengan server kamu)
scp hoonian.tar.gz user@server-ip:~/
```

### **Step 2: Extract di Server**

```bash
# SSH ke server
ssh user@server-ip

# Extract
mkdir -p ~/hoonian
tar -xzf hoonian.tar.gz -C ~/hoonian/
cd ~/hoonian
```

### **Step 3: Setup .env File** ⚠️ **PENTING!**

```bash
# Copy .env.example
cp .env.example .env

# Edit .env
nano .env
```

**Minimal yang HARUS diisi:**
```env
APP_NAME=Hoonian
APP_ENV=production
APP_KEY=                              # ⚠️ WAJIB di-generate (lihat step 4)
APP_DEBUG=false
APP_URL=http://your-server-ip:8004    # ⚠️ Ganti dengan IP server kamu

DB_HOST=mariadb                       # atau host.docker.internal
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456                    # ⚠️ Ganti untuk production!

REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

Save dengan `Ctrl+O`, `Enter`, `Ctrl+X`

### **Step 4: Generate APP_KEY** ⚠️ **WAJIB!**

```bash
# Generate key
docker compose run --rm app php artisan key:generate --show

# Output contoh: base64:abc123def456...
# COPY output tersebut!

# Edit .env lagi
nano .env

# Paste di baris APP_KEY=
# Jadi: APP_KEY=base64:abc123def456...

# Save
```

### **Step 5: Pastikan Database Siap**

```bash
# Test koneksi database
mysql -h 127.0.0.1 -P 3306 -u hoonian_admin -p123456 hoonian -e "SHOW TABLES;"

# Jika error "Unknown database", buat database:
mysql -u root -p
```

```sql
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian_admin'@'%';
FLUSH PRIVILEGES;
EXIT;
```

### **Step 6: Build Docker Image**

```bash
# Build image (ini akan memakan waktu beberapa menit)
docker compose build

# Atau pakai helper script
chmod +x docker.sh
./docker.sh rebuild
```

### **Step 7: Start Containers**

```bash
# Start containers di background
docker compose up -d

# Cek status
docker compose ps
```

Kamu harus lihat:
- ✅ `hoonian-app` - running
- ✅ `hoonian-nginx` - running  
- ✅ `hoonian-redis` - running

### **Step 8: Migrations** ⚠️ **PENTING!**

Migrations akan **otomatis jalan** saat container start (via entrypoint.sh).

Tapi untuk memastikan, cek:

```bash
# Lihat logs
docker compose logs app

# Atau run manual
docker compose exec app php artisan migrate --force

# Cek status migrations
docker compose exec app php artisan migrate:status
```

### **Step 9: (Optional) Seed Data**

```bash
# Seed data dummy (admin user, properties, dll)
docker compose exec app php artisan db:seed --force
```

### **Step 10: Akses Aplikasi** 🎉

```
http://your-server-ip:8004
```

**Default admin (jika sudah seed):**
- Email: `admin@hoonian.com`
- Password: `password`

---

## 🔍 Verifikasi

### Cek Container Running
```bash
docker compose ps

# Harus ada 3 containers:
# - hoonian-app (healthy)
# - hoonian-nginx (up)
# - hoonian-redis (up)
```

### Cek Database Connection
```bash
docker compose exec app php artisan db:show

# Harus tampil info database
```

### Cek Logs
```bash
# Lihat logs app
docker compose logs -f app

# Jika ada error, akan muncul di sini
```

### Test HTTP
```bash
# Test dari server
curl -I http://localhost:8004

# Harus dapat response 200 OK atau 302 redirect
```

---

## ❌ Kesalahan Umum

### 1. Langsung `docker compose up` tanpa setup .env
**Error:** Container crash atau database connection failed

**Solusi:** Setup .env dulu (Step 3-4)

### 2. APP_KEY tidak di-generate
**Error:** "No application encryption key has been specified"

**Solusi:** Generate APP_KEY (Step 4)

### 3. Database belum dibuat
**Error:** "Unknown database 'hoonian'"

**Solusi:** Buat database (Step 5)

### 4. Tidak build image dulu
**Error:** "No such image"

**Solusi:** Build dulu dengan `docker compose build`

### 5. Port 8004 sudah dipakai
**Error:** "port is already allocated"

**Solusi:** 
```bash
# Ganti port di .env
nano .env
# Ubah: APP_PORT=8005

# Restart
docker compose down
docker compose up -d
```

---

## 🎯 Quick Deploy (All-in-One)

Jika semua sudah siap, gunakan helper script:

```bash
# Buat executable
chmod +x docker.sh

# Full setup otomatis
./docker.sh setup
```

Script ini akan:
1. ✅ Build image
2. ✅ Start containers
3. ✅ Run migrations (otomatis via entrypoint)
4. ✅ Show status

---

## 📝 Checklist Deployment

**Sebelum `docker compose up`:**
- [ ] Project sudah di-upload ke server
- [ ] File `.env` sudah dibuat dari `.env.example`
- [ ] `APP_KEY` sudah di-generate
- [ ] `APP_URL` sudah disesuaikan
- [ ] Database credentials benar di `.env`
- [ ] Database `hoonian` sudah dibuat
- [ ] User `hoonian_admin` sudah ada dan punya privileges

**Setelah `docker compose up`:**
- [ ] Containers running (cek `docker compose ps`)
- [ ] Migrations berhasil (cek logs)
- [ ] Aplikasi bisa diakses via browser
- [ ] Login berhasil

**Production:**
- [ ] Ganti `DB_PASSWORD` dengan password kuat
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Setup SSL/TLS
- [ ] Setup backup otomatis
- [ ] Setup monitoring

---

## 🆘 Troubleshooting

### Container tidak start
```bash
# Lihat error
docker compose logs app

# Rebuild
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Database connection error
```bash
# Cek environment variables
docker compose exec app env | grep DB_

# Test koneksi
docker compose exec app php artisan db:show
```

### Permission error
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

---

## 📚 Dokumentasi Lengkap

- **Full Guide**: `SERVER_DEPLOYMENT.md`
- **Troubleshooting**: `TROUBLESHOOTING.md`
- **Commands**: `CHEATSHEET.md`
- **Quick Setup**: `SETUP_NOW.md`

---

## 🎉 Summary

**Urutan yang benar:**

1. Upload project ✅
2. Setup `.env` ✅
3. Generate `APP_KEY` ✅
4. Pastikan database ready ✅
5. Build: `docker compose build` ✅
6. Start: `docker compose up -d` ✅
7. Verify: `docker compose ps` ✅
8. Access: `http://server-ip:8004` ✅

**Jangan skip langkah 1-5!** ⚠️

---

**Need help?** Run `./docker.sh help` atau cek `TROUBLESHOOTING.md`
