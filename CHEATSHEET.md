# 📋 Hoonian Docker Cheat Sheet

Quick reference untuk perintah yang sering dipakai.

## 🚀 Setup Awal

```bash
# 1. Copy environment
cp .env.example .env

# 2. Edit konfigurasi
nano .env

# 3. Setup otomatis
chmod +x docker.sh
./docker.sh setup
```

## 🎮 Container Management

```bash
# Start
./docker.sh start
docker compose up -d

# Stop
./docker.sh stop
docker compose down

# Restart
./docker.sh restart
docker compose restart

# Rebuild
./docker.sh rebuild
docker compose down && docker compose build --no-cache && docker compose up -d

# Status
./docker.sh status
docker ps
```

## 📝 Logs & Monitoring

```bash
# All logs
./docker.sh logs
docker compose logs -f

# Specific service
./docker.sh logs app
docker compose logs -f app

# Last 100 lines
docker compose logs --tail=100 app

# Resource usage
docker stats
```

## 🗄️ Database

```bash
# Run migrations
./docker.sh migrate
docker compose exec app php artisan migrate --force

# Seed database
./docker.sh seed
docker compose exec app php artisan db:seed

# Fresh migration (WARNING: drops all tables)
./docker.sh fresh
docker compose exec app php artisan migrate:fresh --force

# Access database
./docker.sh db
docker compose exec app php artisan db

# Backup
./docker.sh backup
docker compose exec app mysqldump hoonian > backup.sql

# Show database info
docker compose exec app php artisan db:show
```

## 🔧 Laravel Commands

```bash
# Clear cache
./docker.sh clear
docker compose exec app php artisan optimize:clear

# Optimize
./docker.sh optimize
docker compose exec app php artisan optimize

# Artisan commands
./docker.sh artisan route:list
docker compose exec app php artisan route:list

# Tinker
docker compose exec app php artisan tinker

# Storage link
docker compose exec app php artisan storage:link
```

## 📦 Composer

```bash
# Install package
./docker.sh composer require vendor/package
docker compose exec app composer require vendor/package

# Update packages
./docker.sh composer update
docker compose exec app composer update

# Dump autoload
docker compose exec app composer dump-autoload
```

## 🐚 Shell Access

```bash
# App container
./docker.sh shell
docker compose exec app bash

# Nginx container
docker compose exec nginx sh

# Redis
docker compose exec redis redis-cli

# Database
docker compose exec app mysql -u root -p hoonian
```

## 🔍 Debugging

```bash
# Check environment variables
docker compose exec app env | grep DB_

# Test database connection
docker compose exec app php artisan db:show

# Check PHP version
docker compose exec app php -v

# Check PHP extensions
docker compose exec app php -m

# View config
docker compose exec app php artisan config:show

# Check routes
docker compose exec app php artisan route:list
```

## 🧹 Cleanup

```bash
# Clean Docker
./docker.sh clean
docker system prune -a

# Remove volumes (WARNING: deletes data)
docker compose down -v

# Remove specific volume
docker volume rm hoonian_storage-data
```

## 📊 Health Check

```bash
# Container status
docker ps --format "table {{.Names}}\t{{.Status}}"

# Health check
docker inspect hoonian-app | grep -A 10 Health

# Disk usage
docker system df

# Network
docker network ls
docker network inspect hoonian-network
```

## 🔄 Update Application

```bash
# Full update
./docker.sh update

# Manual update
git pull origin main
docker compose build
docker compose up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize
```

## 💾 Backup & Restore

```bash
# Backup database
./docker.sh backup
docker compose exec app mysqldump -u root -p hoonian > backup-$(date +%Y%m%d).sql

# Backup storage
tar -czf storage-backup.tar.gz storage/

# Restore database
./docker.sh restore backup.sql
docker compose exec -T app mysql -u root -p hoonian < backup.sql

# Restore storage
tar -xzf storage-backup.tar.gz
```

## 🔐 Security

```bash
# Fix permissions
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache

# Generate APP_KEY
docker compose run --rm app php artisan key:generate --show

# Clear sensitive cache
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

## 🌐 Network

```bash
# List networks
docker network ls

# Inspect network
docker network inspect hoonian-network

# Check ports
docker ps --format "table {{.Names}}\t{{.Ports}}"
netstat -tulpn | grep 8004
```

## 📁 Files & Volumes

```bash
# List volumes
docker volume ls

# Inspect volume
docker volume inspect hoonian_storage-data

# Copy file to container
docker cp file.txt hoonian-app:/var/www/

# Copy file from container
docker cp hoonian-app:/var/www/file.txt ./
```

## ⚡ Quick Fixes

```bash
# Port conflict
# Edit .env: APP_PORT=8005
./docker.sh restart

# Permission error
./docker.sh shell
chmod -R 775 storage bootstrap/cache

# Database connection error
# Check .env: DB_HOST, DB_PASSWORD
./docker.sh restart

# Cache issues
./docker.sh clear
./docker.sh optimize

# Container won't start
./docker.sh logs app
./docker.sh rebuild
```

## 🎯 One-Liners

```bash
# Full reset (WARNING: deletes all data)
docker compose down -v && docker system prune -af && ./docker.sh setup

# Quick restart
docker compose restart && docker compose logs -f app

# Check everything
docker ps && docker compose exec app php artisan db:show && curl -I http://localhost:8004

# Backup everything
./docker.sh backup && tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/

# Update and optimize
git pull && docker compose build && docker compose up -d && ./docker.sh migrate && ./docker.sh optimize
```

## 📖 Documentation

| File | Description |
|------|-------------|
| `QUICKSTART.md` | Quick start guide (5 min) |
| `SERVER_DEPLOYMENT.md` | Full deployment guide (ID) ⭐ |
| `DOCKER_DEPLOYMENT.md` | Docker details (EN) |
| `TROUBLESHOOTING.md` | Common issues & solutions |
| `INVOICE_SYSTEM.md` | Invoice system docs |
| `EXTERNAL_DATABASE.md` | External DB setup |

## 🆘 Help

```bash
# Show all docker.sh commands
./docker.sh help

# Docker compose help
docker compose --help

# Laravel help
docker compose exec app php artisan list
```

---

**Pro Tip:** Bookmark this file for quick reference! 📌
