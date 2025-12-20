# 🗄️ External Database Setup Guide

## Overview
Hoonian menggunakan database MariaDB/MySQL yang sudah ada di server Anda (port 3306), bukan membuat container database baru.

## Database Configuration

### 1. Create Database
Login ke MariaDB yang sudah ada:
```bash
mysql -h 127.0.0.1 -P 3306 -u root -p
```

Create database untuk Hoonian:
```sql
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- (Optional) Create dedicated user
CREATE USER 'hoonian'@'%' IDENTIFIED BY 'your-password-here';
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian'@'%';
FLUSH PRIVILEGES;

-- Verify
SHOW DATABASES;
EXIT;
```

### 2. Configure .env
Edit `.env` file:
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=your-database-password
```

**Important Notes:**
- `DB_HOST=host.docker.internal` - Allows Docker container to access host's database
- Use the same password as your existing MariaDB root password
- Or use the dedicated `hoonian` user credentials

### 3. Test Connection
Before starting containers, test database connection:
```bash
# From server
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian

# Should connect successfully
```

## Docker Compose Services

Hoonian only creates these containers:
- **hoonian-app** (PHP-FPM)
- **hoonian-nginx** (Web server)
- **hoonian-redis** (Cache)

**No database container** - Uses your existing MariaDB on port 3306.

## Deployment Steps

### 1. Setup Environment
```bash
cp .env.example .env
nano .env
```

Set these values:
```env
DB_HOST=host.docker.internal  # For Docker to access host
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=your-actual-db-password
```

### 2. Build and Start
```bash
sudo docker compose build
sudo docker compose up -d
```

### 3. Run Migrations
```bash
# Migrations will run automatically on container start
# Or run manually:
sudo docker compose exec app php artisan migrate --force
```

### 4. Verify
```bash
# Check tables were created
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian -e "SHOW TABLES;"
```

## Current Server Setup

Your server has:
```
┌─────────────────────────────────────┐
│  Server (b14homeserver)             │
├─────────────────────────────────────┤
│                                     │
│  MariaDB (Port 3306)                │
│  ├── swap_hub database              │
│  └── hoonian database (new)         │
│                                     │
│  Docker Containers:                 │
│  ├── swap-hub-nginx (5541)          │
│  ├── swap-hub-app                   │
│  ├── swap-hub-db (3307)             │
│  ├── swap-hub-redis                 │
│  │                                   │
│  ├── hoonian-nginx (8004)           │
│  ├── hoonian-app                    │
│  └── hoonian-redis                  │
│                                     │
└─────────────────────────────────────┘
```

## Advantages of External Database

✅ **Shared Database Server**
- One MariaDB instance for multiple apps
- Easier backup management
- Less resource usage

✅ **Persistent Data**
- Data survives container removal
- No volume management needed
- Direct database access from host

✅ **Easier Maintenance**
- Standard MySQL tools work
- phpMyAdmin compatible
- Backup scripts unchanged

## Database Access

### From Host (Server)
```bash
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian
```

### From Docker Container
```bash
sudo docker compose exec app php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::table('users')->count();
```

### Using phpMyAdmin
If you have phpMyAdmin installed:
- Host: `127.0.0.1` or `localhost`
- Port: `3306`
- Database: `hoonian`
- Username: `root`
- Password: your MariaDB password

## Backup & Restore

### Backup
```bash
# Backup hoonian database
mysqldump -h 127.0.0.1 -P 3306 -u root -p hoonian > hoonian-backup-$(date +%Y%m%d).sql

# Compress
gzip hoonian-backup-$(date +%Y%m%d).sql
```

### Restore
```bash
# Restore from backup
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian < hoonian-backup-20251220.sql
```

## Troubleshooting

### Connection Refused
```bash
# Check if MariaDB is running
sudo systemctl status mariadb

# Check if port 3306 is listening
sudo netstat -tlnp | grep 3306
```

### Access Denied
```bash
# Verify credentials
mysql -h 127.0.0.1 -P 3306 -u root -p

# If using dedicated user, grant permissions
mysql -u root -p
GRANT ALL PRIVILEGES ON hoonian.* TO 'hoonian'@'%';
FLUSH PRIVILEGES;
```

### host.docker.internal Not Working
If `host.docker.internal` doesn't work, use server's IP:
```env
# In .env
DB_HOST=192.168.1.100  # Your server's IP
```

Or use `172.17.0.1` (Docker bridge gateway):
```env
DB_HOST=172.17.0.1
```

### Tables Not Created
```bash
# Run migrations manually
sudo docker compose exec app php artisan migrate --force

# Check migration status
sudo docker compose exec app php artisan migrate:status
```

## Security Notes

1. **Strong Password**: Use strong password for database
2. **User Permissions**: Consider creating dedicated user for Hoonian
3. **Firewall**: Ensure port 3306 is not exposed externally
4. **Backup**: Regular automated backups
5. **SSL**: Consider using SSL for database connections in production

## Migration from Container DB

If you were using container database before:

### 1. Export Data
```bash
sudo docker compose exec db mysqldump -u root -p hoonian > old-data.sql
```

### 2. Import to External DB
```bash
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian < old-data.sql
```

### 3. Update docker-compose.yml
Already done - db service removed.

### 4. Update .env
```env
DB_HOST=host.docker.internal
```

### 5. Restart Containers
```bash
sudo docker compose down
sudo docker compose up -d
```

---

**Using external database simplifies deployment and maintenance!** 🎉
