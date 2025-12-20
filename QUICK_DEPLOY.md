# 🚀 Quick Deployment Guide - Hoonian on Server

## Server Info
Your server already has:
- **swap-hub** (nginx: 5541, db: 3307)
- **mariadb** standalone (3306)

## Hoonian Ports (No Conflicts)
- **Nginx**: 8004
- **MariaDB**: 3308 (external access)
- **PHP-FPM**: 9000 (internal only)
- **Redis**: 6379 (internal only)

## Quick Deploy Steps

### 0. Prepare Database (First Time Only)
```bash
# Login to your existing MariaDB
mysql -h 127.0.0.1 -P 3306 -u root -p

# Create database
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 1. Upload Project to Server
```bash
# On your local machine
cd ~/Documents/Campus/Semester\ 4/Webpro\ III/hoonian
tar -czf hoonian.tar.gz --exclude=node_modules --exclude=vendor --exclude=.git .

# Upload to server (adjust path as needed)
scp hoonian.tar.gz b14@b14homeserver:~/Documents/apps/
```

### 2. Extract on Server
```bash
# On server
cd ~/Documents/apps/
mkdir -p hoonian
tar -xzf hoonian.tar.gz -C hoonian/
cd hoonian
```

### 3. Configure Environment
```bash
# Copy and edit .env
cp .env.example .env
nano .env
```

**Important .env settings:**
```env
APP_NAME=Hoonian
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=http://your-server-ip:8004
APP_PORT=8004

# External Database (using existing MariaDB on port 3306)
DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=your-mariadb-password

REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

> ⚠️ **IMPORTANT**: 
> - Use `DB_HOST=host.docker.internal` to access host's database from Docker
> - `DB_PASSWORD` must match your existing MariaDB root password
> - Database `hoonian` must be created first (see step 0)

### 4. Build and Start
```bash
# Build images
sudo docker compose build

# Start containers (only app, nginx, redis - no database)
sudo docker compose up -d

# Check status
sudo docker ps
```

You should see:
- `hoonian-nginx` (port 8004)
- `hoonian-app`
- `hoonian-redis`

**Note:** No `hoonian-db` container - using existing MariaDB!

### 5. Run Migrations
```bash
# Migrations run automatically on container start
# Or run manually:
sudo docker compose exec app php artisan migrate --force

# (Optional) Seed database
sudo docker compose exec app php artisan db:seed --force
```

### 6. Verify Database
```bash
# Check tables were created
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian -e "SHOW TABLES;"
```

### 7. Access Application
Open browser: `http://your-server-ip:8004`

## Useful Commands

### Container Management
```bash
# View logs
sudo docker compose logs -f app
sudo docker compose logs -f nginx

# Restart containers
sudo docker compose restart

# Stop containers
sudo docker compose down

# Start containers
sudo docker compose up -d
```

### Laravel Commands
```bash
# Access container shell
sudo docker compose exec app bash

# Clear cache
sudo docker compose exec app php artisan cache:clear

# Run migrations
sudo docker compose exec app php artisan migrate

# Optimize
sudo docker compose exec app php artisan optimize
```

### Database Access
```bash
# From server (external port 3308)
mysql -h 127.0.0.1 -P 3308 -u root -p hoonian

# From inside container
sudo docker compose exec db mysql -u root -p hoonian
```

## Troubleshooting

### Port Already in Use
If port 8004 is taken, change in `.env`:
```env
APP_PORT=8005
```
Then restart:
```bash
sudo docker compose down
sudo docker compose up -d
```

### Container Won't Start
```bash
# Check logs
sudo docker compose logs app
sudo docker compose logs db

# Rebuild
sudo docker compose down
sudo docker compose build --no-cache
sudo docker compose up -d
```

### Permission Issues
```bash
sudo docker compose exec app chmod -R 775 storage bootstrap/cache
sudo docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Failed
```bash
# Check if database is ready
sudo docker compose exec app php artisan db:show

# Check database logs
sudo docker compose logs db
```

## Current Setup on Server

After deployment, you'll have:

| Container | Port | Service |
|-----------|------|---------|
| mariadb (standalone) | 3306 | **Shared Database** |
| swap-hub-nginx | 5541 | Swap Hub |
| swap-hub-db | 3307 | Swap Hub DB (unused) |
| **hoonian-nginx** | **8004** | **Hoonian Web** |
| **hoonian-app** | **-** | **Hoonian App** |
| **hoonian-redis** | **-** | **Hoonian Cache** |

**Note:** Hoonian uses the standalone MariaDB on port 3306 (shared with other apps).

## Firewall Configuration

If using firewall, allow port 8004:
```bash
# UFW
sudo ufw allow 8004/tcp

# Firewalld
sudo firewall-cmd --permanent --add-port=8004/tcp
sudo firewall-cmd --reload
```

## Backup

### Database Backup
```bash
# Create backup
sudo docker compose exec db mysqldump -u root -p hoonian > backup-$(date +%Y%m%d).sql

# Restore backup
sudo docker compose exec -T db mysql -u root -p hoonian < backup-20251220.sql
```

### Full Backup
```bash
# Backup volumes
sudo docker run --rm -v hoonian_dbdata:/data -v $(pwd):/backup alpine tar czf /backup/db-backup.tar.gz /data
sudo docker run --rm -v hoonian_storage-data:/data -v $(pwd):/backup alpine tar czf /backup/storage-backup.tar.gz /data
```

## Update Application

```bash
# Pull latest code
git pull origin main

# Rebuild and restart
sudo docker compose build
sudo docker compose up -d

# Run migrations
sudo docker compose exec app php artisan migrate --force

# Clear cache
sudo docker compose exec app php artisan optimize
```

## Monitoring

```bash
# View all containers
sudo docker ps

# Resource usage
sudo docker stats

# Container logs
sudo docker compose logs -f
```

---

**Need help?** Check `DOCKER_DEPLOYMENT.md` for detailed documentation.
