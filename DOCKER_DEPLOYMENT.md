# 🐳 Docker Deployment Guide - Hoonian

## Architecture

Hoonian menggunakan arsitektur multi-container Docker:
- **app**: PHP-FPM 8.2 (Laravel application)
- **nginx**: Nginx web server (reverse proxy ke PHP-FPM)
- **db**: MariaDB 10.11 (database)
- **redis**: Redis (cache & session storage)

## Prerequisites
- Docker installed (version 20.10+)
- Docker Compose installed (version 2.0+)

## Quick Start

### 1. Clone & Setup Environment
```bash
# Navigate to project directory
cd /path/to/hoonian

# Copy environment file
cp .env.example .env
```

### 2. Configure Environment
Edit `.env` file:
```env
# App Configuration
APP_NAME=Hoonian
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=http://localhost:8004
APP_PORT=8004

# Database (Docker MariaDB)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=your-secure-password-here

# Redis (Docker)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis

# Google OAuth (Optional)
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8004/auth/google/callback
```

### 3. Generate Application Key
```bash
# If you haven't generated an app key yet
php artisan key:generate
```

### 4. Build and Start
```bash
# Build Docker images
docker-compose build

# Start all containers
docker-compose up -d

# Check container status
docker-compose ps
```

### 5. Run Migrations
```bash
# Run database migrations
docker-compose exec app php artisan migrate --force

# (Optional) Seed database
docker-compose exec app php artisan db:seed --force
```

### 6. Access Application
Open your browser: **http://localhost:8004**

## Docker Helper Script

Gunakan script helper untuk kemudahan:

```bash
# Make script executable
chmod +x docker.sh

# Full setup (first time)
./docker.sh setup

# Other commands
./docker.sh start      # Start containers
./docker.sh stop       # Stop containers
./docker.sh restart    # Restart containers
./docker.sh logs       # View logs
./docker.sh migrate    # Run migrations
./docker.sh shell      # Access app container shell
./docker.sh artisan route:list  # Run artisan commands
```

## Container Management

### Start/Stop Containers
```bash
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# Restart specific container
docker-compose restart app
docker-compose restart nginx

# View logs
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

### Access Container Shell
```bash
# Access app container
docker-compose exec app bash

# Access database
docker-compose exec db mysql -u root -p hoonian
```

## Laravel Commands in Docker

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimize for production
docker-compose exec app php artisan optimize

# Create storage link
docker-compose exec app php artisan storage:link

# Run queue worker
docker-compose exec app php artisan queue:work

# Run tinker
docker-compose exec app php artisan tinker
```

## Volumes & Data Persistence

Data yang persisten disimpan di Docker volumes:
- `dbdata`: Database MariaDB
- `storage-data`: Laravel storage (uploads, logs, cache)

```bash
# List volumes
docker volume ls

# Inspect volume
docker volume inspect hoonian_dbdata
docker volume inspect hoonian_storage-data

# Backup database
docker-compose exec db mysqldump -u root -p hoonian > backup.sql

# Restore database
docker-compose exec -T db mysql -u root -p hoonian < backup.sql
```

## Network Configuration

Semua container terhubung dalam network `hoonian-network`:
- app dapat mengakses db via hostname `db`
- app dapat mengakses redis via hostname `redis`
- nginx dapat mengakses app via hostname `app:9000`

## Port Mapping

Default ports:
- **8004**: Nginx (HTTP) → App
- **3306**: MariaDB (exposed untuk akses eksternal)

Untuk mengubah port:
```env
# In .env
APP_PORT=8080
DB_PORT=3307
```

## Troubleshooting

### Container tidak start
```bash
# Check logs
docker-compose logs app
docker-compose logs nginx
docker-compose logs db

# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Issues
```bash
# Check if database is ready
docker-compose exec app php artisan db:show

# Check database logs
docker-compose logs db

# Connect to database manually
docker-compose exec db mysql -u root -p
```

### Nginx 502 Bad Gateway
```bash
# Check if PHP-FPM is running
docker-compose exec app ps aux | grep php-fpm

# Check nginx logs
docker-compose logs nginx

# Restart app container
docker-compose restart app
```

### Clear All Caches
```bash
docker-compose exec app php artisan optimize:clear
docker-compose restart app
docker-compose restart nginx
```

## Invoice System in Docker

Invoice PDF generation sudah fully supported:
- ✅ DomPDF library installed
- ✅ DejaVu Sans fonts included
- ✅ GD extension dengan FreeType
- ✅ Storage directory configured

Test invoice generation:
1. Login ke backoffice
2. Go to Transactions
3. Accept a transaction
4. Click "Generate Invoice"
5. PDF akan ter-download

## Production Deployment

### 1. Build Production Image
```bash
docker build -t hoonian-app:production .
```

### 2. Tag & Push to Registry
```bash
# Tag image
docker tag hoonian-app:production your-registry.com/hoonian:latest

# Push to registry
docker push your-registry.com/hoonian:latest
```

### 3. Deploy to Server
```bash
# On production server
docker pull your-registry.com/hoonian:latest
docker-compose -f docker-compose.prod.yml up -d
```

### 4. Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY`
- [ ] Use strong database password
- [ ] Configure mail server
- [ ] Setup SSL/TLS (use reverse proxy)
- [ ] Setup backup strategy
- [ ] Configure monitoring
- [ ] Setup log rotation

## Environment Variables Reference

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_PORT` | Nginx port | 8004 |
| `DB_PASSWORD` | Database password | - |
| `DB_PORT` | Database port (external) | 3306 |
| `GOOGLE_CLIENT_ID` | Google OAuth Client ID | - |
| `GOOGLE_CLIENT_SECRET` | Google OAuth Secret | - |

## Monitoring

### Check Container Health
```bash
# View container status
docker-compose ps

# Check resource usage
docker stats

# View container processes
docker-compose top
```

### Logs
```bash
# Follow all logs
docker-compose logs -f

# Follow specific service
docker-compose logs -f app

# Last 100 lines
docker-compose logs --tail=100 app
```

## Backup & Restore

### Backup Database
```bash
# Create backup
docker-compose exec db mysqldump -u root -p${DB_PASSWORD} hoonian > backup-$(date +%Y%m%d).sql

# Compress backup
gzip backup-$(date +%Y%m%d).sql
```

### Backup Storage
```bash
# Backup storage volume
docker run --rm -v hoonian_storage-data:/data -v $(pwd):/backup alpine tar czf /backup/storage-backup-$(date +%Y%m%d).tar.gz /data
```

### Restore Database
```bash
# Restore from backup
docker-compose exec -T db mysql -u root -p${DB_PASSWORD} hoonian < backup-20251220.sql
```

## Security Best Practices

1. **Strong Passwords**: Use strong, unique passwords
2. **Environment Variables**: Never commit `.env` to git
3. **SSL/TLS**: Use HTTPS in production (reverse proxy)
4. **Firewall**: Limit exposed ports
5. **Updates**: Keep Docker images updated
6. **Backups**: Regular automated backups
7. **Monitoring**: Setup monitoring & alerts

## Support

- **Docker Issues**: Check this guide
- **Invoice System**: See `INVOICE_SYSTEM.md`
- **Laravel**: Laravel documentation

---

**Happy Deploying! 🚀**
