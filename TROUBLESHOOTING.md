# 🔧 Troubleshooting Guide - Database Connection Issues

## Problem: Database Connection Timeout

If you see this error:
```
Database is unavailable - sleeping
Database is unavailable - sleeping
...
ERROR: Database did not become ready in time
```

## Solutions

### 1. Check Database Container Status
```bash
# Check if database container is running
sudo docker ps | grep hoonian-db

# If not running, check logs
sudo docker compose logs db
```

### 2. Verify Database Password
Make sure `DB_PASSWORD` in `.env` matches in both places:
```env
# In .env file
DB_PASSWORD=your-password-here
```

The password must be set before starting containers!

### 3. Start Database First
```bash
# Stop all containers
sudo docker compose down

# Start database first
sudo docker compose up -d db

# Wait 10 seconds
sleep 10

# Check database is ready
sudo docker compose exec db mysql -u root -p

# Then start other containers
sudo docker compose up -d
```

### 4. Manual Database Check
```bash
# Test database connection from app container
sudo docker compose exec app mysqladmin ping -h db -P 3306 -u root -p

# If successful, you'll see: mysqld is alive
```

### 5. Rebuild Containers
```bash
# Stop and remove containers
sudo docker compose down

# Remove volumes (WARNING: This deletes database data!)
sudo docker volume rm hoonian_dbdata

# Rebuild
sudo docker compose build --no-cache

# Start fresh
sudo docker compose up -d
```

### 6. Check Network
```bash
# Verify containers are in same network
sudo docker network inspect hoonian_hoonian-network

# Should show both 'app' and 'db' containers
```

### 7. Increase Wait Time
Edit `docker/entrypoint.sh` and increase max_attempts:
```bash
max_attempts=60  # Increase from 30 to 60
```

Then rebuild:
```bash
sudo docker compose build app
sudo docker compose up -d
```

### 8. Skip Auto Migration
If you want to start containers without auto migration:

Comment out migration in `docker/entrypoint.sh`:
```bash
# Run migrations
# echo "Running database migrations..."
# php artisan migrate --force
```

Then run migration manually after containers start:
```bash
sudo docker compose exec app php artisan migrate --force
```

## Common Causes

### ❌ Wrong Password
- `.env` file has wrong `DB_PASSWORD`
- Password contains special characters that need escaping

### ❌ Database Not Started
- Database container failed to start
- Check: `sudo docker compose logs db`

### ❌ Network Issues
- Containers not in same network
- Firewall blocking internal communication

### ❌ Port Conflict
- Port 3308 already in use
- Change `DB_EXTERNAL_PORT` in `.env`

## Quick Fix Commands

```bash
# 1. Stop everything
sudo docker compose down

# 2. Check .env has DB_PASSWORD set
cat .env | grep DB_PASSWORD

# 3. Start database only
sudo docker compose up -d db

# 4. Wait and check
sleep 10
sudo docker compose logs db

# 5. Test connection
sudo docker compose exec db mysql -u root -p
# Enter your DB_PASSWORD

# 6. If successful, start app
sudo docker compose up -d app

# 7. Check app logs
sudo docker compose logs -f app
```

## Prevention

### Always Set DB_PASSWORD Before Starting
```bash
# In .env
DB_PASSWORD=MySecurePassword123!
```

### Start Containers in Order
```bash
# 1. Database first
sudo docker compose up -d db redis

# 2. Wait
sleep 10

# 3. Then app
sudo docker compose up -d app nginx
```

### Use Health Checks
The database container has built-in health checks. Wait for it to be healthy:
```bash
# Check health status
sudo docker ps

# Look for "healthy" in STATUS column
```

## Still Not Working?

### Check Database Logs
```bash
sudo docker compose logs db --tail=100
```

### Check App Logs
```bash
sudo docker compose logs app --tail=100
```

### Manual Connection Test
```bash
# From host machine
mysql -h 127.0.0.1 -P 3308 -u root -p
# Enter DB_PASSWORD

# If this works, the issue is with app container
```

### Contact Support
If none of these work, provide:
1. Output of `sudo docker compose logs db`
2. Output of `sudo docker compose logs app`
3. Your `.env` file (remove sensitive data)
4. Output of `sudo docker ps`

---

**Most Common Fix:** Make sure `DB_PASSWORD` is set in `.env` before running `docker compose up`!
