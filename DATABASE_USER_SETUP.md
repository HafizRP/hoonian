# 🗄️ Database User Setup - Hoonian

## Database User yang Sudah Dibuat

**Username:** `hoonian_admin`  
**Password:** `123456`  
**Database:** `hoonian`

## Cara Membuat Database User (Reference)

Jika kamu perlu membuat user database baru di masa depan, ikuti langkah ini:

### 1. Login ke MariaDB/MySQL
```bash
# Login sebagai root
mysql -u root -p
```

### 2. Buat Database (jika belum ada)
```sql
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Buat User Baru
```sql
-- Buat user dengan password
CREATE USER 'hoonian_admin'@'%' IDENTIFIED BY '123456';

-- Atau untuk production, gunakan password yang lebih kuat:
-- CREATE USER 'hoonian_admin'@'%' IDENTIFIED BY 'StrongPassword123!@#';
```

### 4. Berikan Privileges
```sql
-- Grant semua privileges untuk database hoonian
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian_admin'@'%';

-- Flush privileges
FLUSH PRIVILEGES;
```

### 5. Verifikasi User
```sql
-- Lihat user yang ada
SELECT User, Host FROM mysql.user WHERE User = 'hoonian_admin';

-- Lihat privileges
SHOW GRANTS FOR 'hoonian_admin'@'%';

-- Exit
EXIT;
```

### 6. Test Koneksi
```bash
# Test login dengan user baru
mysql -u hoonian_admin -p123456 hoonian

# Jika berhasil, kamu akan masuk ke database
# Test dengan query sederhana:
SHOW TABLES;
EXIT;
```

## Konfigurasi di Hoonian

### Update .env File
```env
DB_CONNECTION=mysql
DB_HOST=mariadb                    # atau host.docker.internal
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456
```

### Untuk Docker
Jika menggunakan Docker dan ingin connect ke database di host:

```env
DB_HOST=host.docker.internal       # Untuk Mac/Windows Docker
# atau
DB_HOST=172.17.0.1                 # Untuk Linux Docker
```

## Security Best Practices

### ⚠️ PENTING untuk Production!

**Password saat ini (`123456`) TIDAK AMAN untuk production!**

### Ganti Password untuk Production
```sql
-- Login sebagai root
mysql -u root -p

-- Ganti password
ALTER USER 'hoonian_admin'@'%' IDENTIFIED BY 'StrongPassword123!@#';

-- Flush privileges
FLUSH PRIVILEGES;
EXIT;
```

### Password yang Baik
- Minimal 12 karakter
- Kombinasi huruf besar, kecil, angka, dan simbol
- Tidak menggunakan kata yang mudah ditebak
- Contoh: `H00n!@nAdm1n#2025$`

### Update .env setelah ganti password
```env
DB_PASSWORD=StrongPassword123!@#
```

## Troubleshooting

### Error: "Access denied for user 'hoonian_admin'"

**Penyebab:** Password salah atau user belum dibuat

**Solusi:**
```bash
# 1. Cek apakah user ada
mysql -u root -p
SELECT User, Host FROM mysql.user WHERE User = 'hoonian_admin';

# 2. Jika tidak ada, buat user (lihat langkah di atas)

# 3. Jika ada, coba reset password
ALTER USER 'hoonian_admin'@'%' IDENTIFIED BY '123456';
FLUSH PRIVILEGES;
EXIT;

# 4. Test koneksi
mysql -u hoonian_admin -p123456 hoonian
```

### Error: "Can't connect to MySQL server"

**Penyebab:** Database server tidak running atau host salah

**Solusi:**
```bash
# 1. Cek apakah MariaDB running
docker ps | grep mariadb
# atau
systemctl status mariadb

# 2. Cek host di .env
# Untuk Docker: DB_HOST=mariadb atau host.docker.internal
# Untuk local: DB_HOST=127.0.0.1

# 3. Test koneksi manual
mysql -h 127.0.0.1 -P 3306 -u hoonian_admin -p123456
```

### Error: "Unknown database 'hoonian'"

**Penyebab:** Database belum dibuat

**Solusi:**
```sql
mysql -u root -p
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian_admin'@'%';
FLUSH PRIVILEGES;
EXIT;
```

### Error: "Host 'xxx.xxx.xxx.xxx' is not allowed to connect"

**Penyebab:** User hanya bisa connect dari localhost

**Solusi:**
```sql
mysql -u root -p

-- Buat user yang bisa connect dari mana saja
CREATE USER 'hoonian_admin'@'%' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian_admin'@'%';
FLUSH PRIVILEGES;

-- Atau untuk lebih aman, specify IP tertentu
-- CREATE USER 'hoonian_admin'@'172.17.0.1' IDENTIFIED BY '123456';
-- GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian_admin'@'172.17.0.1';

EXIT;
```

## Verifikasi Setup

### 1. Test dari Host
```bash
mysql -h 127.0.0.1 -P 3306 -u hoonian_admin -p123456 hoonian -e "SHOW TABLES;"
```

### 2. Test dari Docker Container
```bash
# Start container
./docker.sh start

# Test koneksi
./docker.sh shell
php artisan db:show

# Atau
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::select('SELECT DATABASE()');
```

### 3. Test Migration
```bash
./docker.sh migrate
```

## User Privileges yang Dibutuhkan

Hoonian membutuhkan privileges berikut:

```sql
-- Minimal privileges untuk Hoonian
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER 
ON hoonian.* TO 'hoonian_admin'@'%';

FLUSH PRIVILEGES;
```

## Multiple Environments

### Development
```env
DB_USERNAME=hoonian_admin
DB_PASSWORD=123456
```

### Staging
```env
DB_USERNAME=hoonian_staging
DB_PASSWORD=StagingPassword123!
```

### Production
```env
DB_USERNAME=hoonian_prod
DB_PASSWORD=VeryStrongProductionPassword123!@#
```

## Backup User Info

**SIMPAN INFORMASI INI DI TEMPAT AMAN!**

```
Database: hoonian
Username: hoonian_admin
Password: 123456 (GANTI untuk production!)
Host: mariadb (Docker) atau 127.0.0.1 (local)
Port: 3306
```

## Quick Commands

```bash
# Login ke database
mysql -u hoonian_admin -p123456 hoonian

# Show databases
mysql -u hoonian_admin -p123456 -e "SHOW DATABASES;"

# Show tables
mysql -u hoonian_admin -p123456 hoonian -e "SHOW TABLES;"

# Backup database
mysqldump -u hoonian_admin -p123456 hoonian > backup.sql

# Restore database
mysql -u hoonian_admin -p123456 hoonian < backup.sql
```

## Checklist

- [x] User `hoonian_admin` sudah dibuat
- [x] Password: `123456` (development only)
- [x] Database `hoonian` sudah dibuat
- [x] Privileges sudah di-grant
- [ ] Update `.env` dengan credentials yang benar
- [ ] Test koneksi database
- [ ] Run migrations
- [ ] **GANTI PASSWORD untuk production!** ⚠️

---

**Security Note:** Password `123456` hanya untuk development. WAJIB ganti untuk production! 🔒
