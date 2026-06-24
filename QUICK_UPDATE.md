# Quick Update Guide

Fast reference for updating PMA backend on your production server.

## ⚡ 5-Minute Update (Standard)

```bash
# 1. SSH to server
ssh username@your-server.com

# 2. Navigate to app
cd ~/pma-api

# 3. Pull latest code
git pull origin main

# 4. Install dependencies
composer install --no-dev --optimize-autoloader

# 5. Run migrations
php artisan migrate --force

# 6. Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Restart PHP-FPM
sudo systemctl restart php8.4-fpm

# 8. Verify
curl https://your-domain.com/api/v1/admin/stats
```

## 🔄 One-Command Update

Save this as `~/update.sh`:

```bash
#!/bin/bash
set -e
cd ~/pma-api
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.4-fpm
echo "✓ Updated successfully!"
```

Run it:
```bash
chmod +x ~/update.sh
./update.sh
```

## 📋 Checklist Before Updating

- [ ] Backup database: `mysqldump -u pma_user -p pma_production > backup.sql`
- [ ] Check logs for errors: `tail ~/pma-api/storage/logs/laravel.log`
- [ ] Verify .env file exists: `ls ~/pma-api/.env`

## ⚠️ If Something Goes Wrong

```bash
# Rollback to previous version
cd ~/pma-api
git revert HEAD

# Revert database migrations
php artisan migrate:rollback

# Restore from backup
mysql -u pma_user -p pma_production < backup.sql

# Clear caches
php artisan cache:clear
php artisan config:cache
```

## 📊 Check Status After Update

```bash
# View API response
curl https://your-domain.com/api/v1/admin/stats

# Check logs for errors
tail -20 ~/pma-api/storage/logs/laravel.log

# Test login endpoint
curl -X POST https://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@your-domain.com","password":"your-password"}'
```

## 🔐 Update with Zero Downtime (Optional)

```bash
# 1. Pull & build in staging
cd ~/pma-api
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force --pretend  # Preview migrations

# 2. If ready, actually run
php artisan migrate --force

# 3. Soft restart (no connection drops)
php artisan cache:clear
php artisan config:cache
```

## 📅 Automatic Updates (Cron)

Add to crontab for automatic updates:
```bash
crontab -e

# Update every Sunday at 2 AM
0 2 * * 0 ~/update.sh >> ~/update.log 2>&1
```

---

**For detailed guide, see [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)**
