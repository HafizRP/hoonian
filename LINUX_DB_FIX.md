# 🔧 Quick Fix - Database Connection on Linux

## Problem
`host.docker.internal` doesn't work on Linux. Container can't connect to host's MariaDB.

## Solution
Use Docker bridge gateway IP: `172.17.0.1`

## Quick Fix Steps

### 1. Stop Container
```bash
sudo docker compose down
```

### 2. Update .env
```bash
nano .env
```

Change this line:
```env
# OLD (doesn't work on Linux):
DB_HOST=host.docker.internal

# NEW (works on Linux):
DB_HOST=172.17.0.1
```

Full database config:
```env
DB_CONNECTION=mysql
DB_HOST=172.17.0.1
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=your-mariadb-password
```

### 3. Verify MariaDB Accepts Connections from Docker
```bash
# Check MariaDB bind address
sudo docker exec mariadb cat /config/custom.cnf

# Or check if MariaDB listens on all interfaces
sudo netstat -tlnp | grep 3306
# Should show: 0.0.0.0:3306 or :::3306
```

### 4. Grant Access (If Needed)
```bash
# Connect to MariaDB
mysql -h 127.0.0.1 -P 3306 -u root -p

# Grant access from Docker network
GRANT ALL PRIVILEGES ON hoonian.* TO 'root'@'172.17.%' IDENTIFIED BY 'your-password';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Restart Containers
```bash
sudo docker compose up -d
```

### 6. Check Logs
```bash
# Should see "Database is ready!" instead of timeout
sudo docker logs -f hoonian-app
```

### 7. Verify Connection
```bash
# Test from container
sudo docker compose exec app mysqladmin ping -h 172.17.0.1 -P 3306 -u root -p

# Should output: mysqld is alive
```

## Alternative Solutions

### Option 1: Use Server's IP Address
```env
# In .env
DB_HOST=192.168.x.x  # Your server's actual IP
```

### Option 2: Use Host Network Mode
Edit `docker-compose.yml`:
```yaml
services:
  app:
    network_mode: "host"
    # Then use:
    # DB_HOST=127.0.0.1
```

### Option 3: Connect to Specific MariaDB Container
If you want to use the `mariadb` container directly:
```yaml
# In docker-compose.yml, add to networks:
networks:
  hoonian-network:
    external: true
    name: mariadb_default  # Or whatever network mariadb uses
```

Then:
```env
DB_HOST=mariadb
```

## Troubleshooting

### Still Can't Connect?

#### Check Docker Bridge IP
```bash
# Find Docker bridge IP
ip addr show docker0
# Look for: inet 172.17.0.1/16

# Or
docker network inspect bridge | grep Gateway
```

#### Check MariaDB Container Network
```bash
# Get MariaDB container IP
sudo docker inspect mariadb | grep IPAddress

# Use that IP in .env
DB_HOST=172.18.0.2  # Example
```

#### Test Connection from Host
```bash
# Make sure you can connect from host
mysql -h 127.0.0.1 -P 3306 -u root -p

# If this works, the issue is Docker network access
```

#### Check Firewall
```bash
# Allow Docker network
sudo iptables -I INPUT -s 172.17.0.0/16 -j ACCEPT

# Or disable firewall temporarily to test
sudo ufw disable
```

## Recommended Configuration

For **LinuxServer MariaDB** container (which you're using):

### 1. Update .env
```env
DB_HOST=172.17.0.1
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=your-mariadb-root-password
```

### 2. Ensure MariaDB Binds to All Interfaces
```bash
# Check MariaDB config
sudo docker exec mariadb cat /config/custom.cnf

# Should have:
# bind-address = 0.0.0.0
```

### 3. Grant Docker Network Access
```bash
mysql -h 127.0.0.1 -P 3306 -u root -p

GRANT ALL PRIVILEGES ON hoonian.* TO 'root'@'172.17.%' IDENTIFIED BY 'your-password';
GRANT ALL PRIVILEGES ON hoonian.* TO 'root'@'%' IDENTIFIED BY 'your-password';
FLUSH PRIVILEGES;
```

### 4. Restart Everything
```bash
# Restart MariaDB container
sudo docker restart mariadb

# Wait a bit
sleep 5

# Restart Hoonian
cd ~/Documents/apps/hoonian
sudo docker compose down
sudo docker compose up -d

# Check logs
sudo docker logs -f hoonian-app
```

## Success Indicators

You should see in logs:
```
Waiting for database to be ready...
Database is ready!
Setting permissions for storage and cache directories...
Creating storage symlink...
Running database migrations...
Migration table created successfully.
Migrating: ...
Migrated: ...
```

---

**TL;DR:** Change `DB_HOST=172.17.0.1` in `.env` and restart! 🚀
