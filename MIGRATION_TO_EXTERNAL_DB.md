# 🔄 Migration Guide - Switch to External Database

## Current Situation
You have `hoonian-db` container running (port 3308), but we want to use the existing MariaDB on port 3306 instead.

## Quick Migration Steps

### 1. Stop and Remove Hoonian Containers
```bash
cd ~/Documents/apps/hoonian

# Stop all hoonian containers
sudo docker compose down

# Verify hoonian containers are stopped
sudo docker ps | grep hoonian
# Should show nothing
```

### 2. Remove Database Volume (Optional)
```bash
# List volumes
sudo docker volume ls | grep hoonian

# Remove database volume (if you don't need the data)
sudo docker volume rm hoonian_dbdata

# Keep storage volume (for uploaded files)
# Don't remove: hoonian_storage-data
```

### 3. Create Database in Existing MariaDB
```bash
# Connect to existing MariaDB (port 3306)
mysql -h 127.0.0.1 -P 3306 -u root -p

# Create database
CREATE DATABASE hoonian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Verify
SHOW DATABASES;
EXIT;
```

### 4. Update .env Configuration
```bash
nano .env
```

Change these values:
```env
# OLD (using hoonian-db container):
DB_HOST=db
DB_PORT=3306

# NEW (using external MariaDB):
DB_HOST=host.docker.internal
DB_PORT=3306
DB_PASSWORD=your-mariadb-root-password
```

**Important:** Use the password for the `mariadb` container (port 3306), not the old `hoonian-db` password!

### 5. Rebuild and Start
```bash
# Make sure you're using the latest docker-compose.yml
# (Should NOT have 'db' service)

# Build
sudo docker compose build

# Start (will only create: app, nginx, redis)
sudo docker compose up -d

# Verify - should see only 3 hoonian containers
sudo docker ps | grep hoonian
```

Expected output:
```
hoonian-nginx    (port 8004)
hoonian-app      
hoonian-redis    
```

**No `hoonian-db`!**

### 6. Run Migrations
```bash
# Migrations should run automatically
# Check logs
sudo docker compose logs -f app

# Or run manually
sudo docker compose exec app php artisan migrate --force
```

### 7. Verify Database
```bash
# Check tables in MariaDB (port 3306)
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian -e "SHOW TABLES;"

# Should see Laravel tables: users, properties, transactions, etc.
```

### 8. Test Application
```bash
# Access application
curl http://localhost:8004

# Or open in browser
http://your-server-ip:8004
```

## Troubleshooting

### If hoonian-db Still Appears
```bash
# Force remove
sudo docker rm -f hoonian-db

# Remove from docker-compose
# Make sure docker-compose.yml doesn't have 'db' service
```

### If Can't Connect to Database
```bash
# Test connection from app container
sudo docker compose exec app mysqladmin ping -h host.docker.internal -P 3306 -u root -p

# If fails, try using server IP instead
# In .env:
DB_HOST=172.17.0.1  # Docker bridge gateway
# Or
DB_HOST=192.168.x.x  # Your server IP
```

### If Migrations Fail
```bash
# Check database connection
sudo docker compose exec app php artisan tinker
>>> DB::connection()->getPdo();

# Check logs
sudo docker compose logs app
```

## Data Migration (If Needed)

If you had data in `hoonian-db` that you want to keep:

### 1. Export from Old Container
```bash
# Before stopping hoonian-db
sudo docker compose exec db mysqldump -u root -p hoonian > hoonian-old-data.sql
```

### 2. Import to External MariaDB
```bash
# After creating database in external MariaDB
mysql -h 127.0.0.1 -P 3306 -u root -p hoonian < hoonian-old-data.sql
```

## Final Container List

After migration:
```
CONTAINER ID   IMAGE              PORTS                    NAMES
xxxxxxxx       nginx:alpine       0.0.0.0:8004->80/tcp     hoonian-nginx
xxxxxxxx       hoonian-app        9000/tcp                 hoonian-app
xxxxxxxx       redis:alpine       6379/tcp                 hoonian-redis
12741621       nginx:alpine       0.0.0.0:5541->80/tcp     swap-hub-nginx
40f56c04       swap-hub-app       9000/tcp                 swap-hub-app
a69916bd       redis:alpine       6379/tcp                 swap-hub-redis
a349fdca       mariadb:10.11      0.0.0.0:3307->3306/tcp   swap-hub-db
42d14407       mariadb:11.4.5     0.0.0.0:3306->3306/tcp   mariadb ← SHARED
```

**Note:** 
- `hoonian-db` removed ✅
- Using `mariadb` (port 3306) for Hoonian ✅
- `swap-hub-db` (port 3307) still exists for Swap Hub

## Benefits

✅ One less container to manage  
✅ Shared database server  
✅ Easier backups  
✅ Less resource usage  

---

**Ready to migrate? Follow the steps above!** 🚀
