# PMA Laravel Backend - Deployment Guide

Complete guide to deploy the PMA Laravel backend to a production server and keep it updated.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Initial Server Setup](#initial-server-setup)
- [First-Time Deployment](#first-time-deployment)
- [Database Setup](#database-setup)
- [Updating the Application](#updating-the-application)
- [Maintenance & Monitoring](#maintenance--monitoring)
- [Troubleshooting](#troubleshooting)

---

## Prerequisites

### Requirements
- **Server**: PlanetHoster or similar shared/dedicated hosting
- **PHP**: 8.4+ with extensions: `pdo_mysql`, `json`, `mbstring`, `bcmath`, `ctype`, `fileinfo`
- **MySQL/MariaDB**: 8.0+
- **Composer**: Latest version
- **Git**: For version control and updates
- **SSH Access**: To the server

### Verify PHP Version
```bash
php -v
# Should show PHP 8.4 or higher
```

---

## Initial Server Setup

### 1. Connect to Your Server
```bash
ssh username@your-server.com
```

### 2. Install Required PHP Extensions
```bash
# For shared hosting, contact your provider
# For dedicated servers:
sudo apt update && sudo apt install -y php8.4-{pdo,mysql,json,mbstring,bcmath,ctype,fileinfo,curl,xml}
```

### 3. Create Application Directory
```bash
# Create directory (e.g., in public_html or custom location)
mkdir -p ~/pma-api
cd ~/pma-api

# Set proper permissions
chmod 755 ~/pma-api
```

### 4. Install Composer
```bash
# If not already installed
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 5. Configure Web Server

#### For Nginx
Create `/etc/nginx/sites-available/pma-api`:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /home/username/pma-api/public;
    index index.php;

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/pma-api /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### For Apache
Create `.htaccess` in `public/` folder (usually included with Laravel):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

### 6. Install SSL Certificate
```bash
# Using Let's Encrypt (Certbot)
sudo apt install certbot python3-certbot-nginx -y
sudo certbot certonly --nginx -d your-domain.com
```

---

## First-Time Deployment

### 1. Clone Repository
```bash
cd ~/pma-api
git clone https://github.com/yourusername/pma-backend.git .
# Or if already in directory:
git init
git remote add origin https://github.com/yourusername/pma-backend.git
git pull origin main
```

### 2. Install PHP Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Edit configuration
nano .env
```

**Critical .env values:**
```env
APP_NAME=PMA
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pma.besse.dev

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=pma_production
DB_USERNAME=pma_user
DB_PASSWORD=your-secure-password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Create Database
```bash
# Login to MySQL
mysql -u root -p

# In MySQL shell:
CREATE DATABASE pma_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pma_user'@'localhost' IDENTIFIED BY 'your-secure-password';
GRANT ALL PRIVILEGES ON pma_production.* TO 'pma_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6. Run Database Migrations
```bash
php artisan migrate --force
```

### 7. Create Admin User
```bash
php artisan tinker
```

In Tinker shell:
```php
$admin = new \App\Models\User([
    'name' => 'Admin',
    'email' => 'admin@your-domain.com',
    'password' => bcrypt('your-secure-password'),
    'is_admin' => true,
]);
$admin->save();
exit();
```

### 8. Set File Permissions
```bash
# Set directory ownership
sudo chown -R www-data:www-data ~/pma-api

# Set proper permissions
chmod -R 755 ~/pma-api
chmod -R 775 ~/pma-api/storage ~/pma-api/bootstrap/cache
```

### 9. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 10. Test the Deployment
```bash
# Test health endpoint
curl https://your-domain.com/api/v1/health

# Test stats endpoint
curl https://your-domain.com/api/v1/admin/stats

# Test login endpoint
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@your-domain.com","password":"your-secure-password"}'
```

---

## Database Setup

### Backup Database
```bash
# Local backup
mysqldump -u pma_user -p pma_production > pma_backup_$(date +%Y%m%d_%H%M%S).sql

# Automated daily backups
# Add to crontab: 0 2 * * * mysqldump -u pma_user -p pma_production > ~/backups/pma_$(date +\%Y\%m\%d).sql
```

### Restore Database
```bash
mysql -u pma_user -p pma_production < pma_backup_20260624.sql
```

### Run New Migrations
After updating code, run:
```bash
php artisan migrate --force
```

---

## Updating the Application

### Standard Update Process

#### 1. Pull Latest Code
```bash
cd ~/pma-api
git pull origin main
```

#### 2. Install Dependency Updates
```bash
composer install --no-dev --optimize-autoloader
```

#### 3. Run New Migrations
```bash
php artisan migrate --force
```

#### 4. Clear and Rebuild Caches
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5. Restart PHP-FPM (if using)
```bash
sudo systemctl restart php8.4-fpm
```

#### 6. Verify Update
```bash
# Check API responds
curl https://your-domain.com/api/v1/admin/stats

# Check application logs
tail -f ~/pma-api/storage/logs/laravel.log
```

### Complete Update Script

Create `~/update.sh`:
```bash
#!/bin/bash

cd ~/pma-api

echo "Pulling latest code..."
git pull origin main || { echo "Git pull failed!"; exit 1; }

echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader || { echo "Composer install failed!"; exit 1; }

echo "Running migrations..."
php artisan migrate --force || { echo "Migrations failed!"; exit 1; }

echo "Clearing caches..."
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Restarting PHP-FPM..."
sudo systemctl restart php8.4-fpm

echo "✓ Update complete!"
```

Make executable and run:
```bash
chmod +x ~/update.sh
~/update.sh
```

### Scheduled Updates

Add to crontab for automatic updates at specific times:
```bash
crontab -e

# Update every Sunday at 2 AM
0 2 * * 0 ~/update.sh >> ~/pma_updates.log 2>&1
```

---

## Maintenance & Monitoring

### View Application Logs
```bash
# Real-time logs
tail -f ~/pma-api/storage/logs/laravel.log

# Last 50 lines
tail -50 ~/pma-api/storage/logs/laravel.log

# Search for errors
grep "ERROR" ~/pma-api/storage/logs/laravel.log
```

### Monitor Disk Space
```bash
df -h ~/pma-api

# Clean old logs if needed
find ~/pma-api/storage/logs -name "*.log" -mtime +30 -delete
```

### Monitor Database Size
```bash
mysql -u pma_user -p -e "SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb FROM information_schema.tables WHERE table_schema='pma_production' ORDER BY size_mb DESC;"
```

### Clear Old Sessions
```bash
php artisan session:table
php artisan migrate

# Clear session files
find ~/pma-api/storage/framework/sessions -type f -delete
```

### Check PHP Configuration
```bash
php -i | grep -E "max_execution_time|memory_limit|upload_max_filesize"
```

---

## Troubleshooting

### 500 Internal Server Error

1. **Check logs:**
   ```bash
   tail -50 ~/pma-api/storage/logs/laravel.log
   ```

2. **Verify .env file:**
   ```bash
   php artisan tinker
   # Check values load correctly
   exit()
   ```

3. **Rebuild caches:**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```

4. **Check permissions:**
   ```bash
   sudo chown -R www-data:www-data ~/pma-api/storage
   chmod -R 775 ~/pma-api/storage ~/pma-api/bootstrap/cache
   ```

### Database Connection Error

```bash
# Test MySQL connection
mysql -h localhost -u pma_user -p pma_production -e "SELECT 1;"

# Check .env DB settings
cat ~/pma-api/.env | grep DB_

# Verify credentials in MySQL
mysql -u root -p -e "SELECT user, host FROM mysql.user WHERE user='pma_user';"
```

### Composer Issues

```bash
# Clear composer cache
composer clear-cache

# Reinstall dependencies
rm -rf vendor composer.lock
composer install --no-dev
```

### Migration Rollback (if needed)

```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback to specific migration
php artisan migrate:rollback --step=3

# Reset entire database (WARNING: deletes all data!)
php artisan migrate:reset
```

### Slow API Responses

1. **Check database queries:**
   ```bash
   # Enable query logging in .env
   # DB_LOG_QUERIES=true
   tail -f ~/pma-api/storage/logs/laravel.log | grep "SELECT"
   ```

2. **Add database indexes:**
   ```bash
   php artisan migrate:refresh --seed # Only for development!
   ```

3. **Monitor server resources:**
   ```bash
   top
   free -h
   ```

---

## Rollback to Previous Version

If update causes issues:

```bash
# See commit history
git log --oneline -10

# Rollback to previous commit
git revert HEAD

# Or reset to specific commit
git reset --hard abc123def456

# Rerun migrations
php artisan migrate:rollback
php artisan migrate
```

---

## Useful Commands

```bash
# Check Laravel version
php artisan --version

# Test email configuration
php artisan tinker
Mail::raw('Test', function($message) { $message->to('test@example.com')->subject('Test'); })

# Generate new app key (CAUTION: invalidates all tokens!)
php artisan key:generate

# Clear all caches
php artisan optimize:clear

# Schedule test
php artisan schedule:list

# Run background jobs
php artisan queue:work
```

---

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS only (SSL certificate installed)
- [ ] Keep sensitive data in `.env` (never commit to Git)
- [ ] Regular database backups
- [ ] Monitor logs for suspicious activity
- [ ] Update PHP and dependencies regularly
- [ ] Disable directory listing in web server
- [ ] Set strong database password
- [ ] Use environment-specific configurations

---

## Support & Resources

- [Laravel Documentation](https://laravel.com/docs)
- [PlanetHoster Support](https://www.planethoster.com/support)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

**Last Updated:** 2026-06-24
