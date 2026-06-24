# PMA Backend - Setup Checklist

Complete this checklist before using the application in development or production.

## 📋 Local Development Setup

### Prerequisites
- [ ] PHP 8.4+ installed (`php -v`)
- [ ] MySQL 8.0+ or MariaDB installed
- [ ] Composer installed (`composer -v`)
- [ ] Git installed (`git -v`)

### Project Setup
- [ ] Repository cloned: `git clone ...`
- [ ] Dependencies installed: `composer install`
- [ ] `.env` file created from `.env.example`: `cp .env.example .env`

### Database Configuration
- [ ] Database created: `CREATE DATABASE pma_local;`
- [ ] Database user created with proper permissions
- [ ] `.env` DB values match your local setup:
  - [ ] `DB_HOST` = `127.0.0.1` (or your host)
  - [ ] `DB_PORT` = `3306` (or your port)
  - [ ] `DB_DATABASE` = `pma_local`
  - [ ] `DB_USERNAME` = correct username
  - [ ] `DB_PASSWORD` = correct password

### Application Setup
- [ ] App key generated: `php artisan key:generate`
- [ ] Migrations ran: `php artisan migrate`
- [ ] Server started: `php artisan serve`
- [ ] API responds: `curl http://127.0.0.1:8000/api/v1/admin/stats`

### Verification
- [ ] No errors in logs: `cat storage/logs/laravel.log`
- [ ] Database tables created: `php artisan migrate:status`
- [ ] API endpoints accessible
- [ ] `.env` is in `.gitignore` (not tracked by Git)

---

## 🐳 Docker Setup

### Prerequisites
- [ ] Docker installed (`docker --version`)
- [ ] Docker Compose installed (`docker-compose --version`)

### Startup
- [ ] Navigated to project: `cd pma-backend`
- [ ] Containers started: `docker-compose up -d`
- [ ] Services running: `docker-compose ps`

### Database
- [ ] Migrations ran: `docker-compose exec app php artisan migrate`
- [ ] Database initialized: `docker-compose exec app php artisan migrate:status`

### Verification
- [ ] API responds: `curl http://127.0.0.1:8000/api/v1/admin/stats`
- [ ] Nginx running: `curl http://127.0.0.1:8000/`
- [ ] No errors: `docker-compose logs app | tail -20`

### Cleanup
- [ ] Containers stopped: `docker-compose down`
- [ ] Volumes cleared if needed: `docker-compose down -v`

---

## 🚀 Production Deployment

### Server Preparation
- [ ] SSH access to server verified
- [ ] PHP 8.4+ installed on server
- [ ] MySQL/MariaDB running on server
- [ ] Composer installed on server
- [ ] SSL certificate obtained (Let's Encrypt)
- [ ] Domain DNS configured

### Application Deployment
- [ ] Repository cloned to server
- [ ] Composer dependencies installed: `composer install --no-dev`
- [ ] `.env` file created with production values
- [ ] **`.env` NOT committed to Git**
- [ ] App key generated: `php artisan key:generate`
- [ ] Database migrations ran: `php artisan migrate --force`

### Web Server Configuration
- [ ] Nginx configured (or Apache)
- [ ] SSL certificate installed
- [ ] HTTP redirects to HTTPS
- [ ] Document root points to `public/` directory
- [ ] File permissions set correctly:
  - [ ] `chmod 755 public`
  - [ ] `chmod 775 storage bootstrap/cache`

### Email Configuration
- [ ] SMTP provider selected (SendGrid, Mailtrap, AWS SES, etc.)
- [ ] SMTP credentials added to `.env`:
  - [ ] `MAIL_HOST`
  - [ ] `MAIL_PORT`
  - [ ] `MAIL_USERNAME`
  - [ ] `MAIL_PASSWORD`
- [ ] Test email sent successfully

### Firebase Configuration (Push Notifications)
- [ ] Firebase project created
- [ ] Service account created
- [ ] Credentials added to `.env`:
  - [ ] `FIREBASE_PROJECT_ID`
  - [ ] `FIREBASE_PRIVATE_KEY`
  - [ ] `FIREBASE_CLIENT_EMAIL`
- [ ] Test notification sent successfully

### Admin User
- [ ] Admin user created: `php artisan tinker`
  - [ ] Name set
  - [ ] Email set
  - [ ] Password secure (20+ characters)
  - [ ] `is_admin` = true
- [ ] Admin can login to dashboard
- [ ] Admin can access `/dashboard`

### Security
- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] Strong database password set (20+ characters)
- [ ] `.gitignore` includes all sensitive files
- [ ] `.env` file is NOT in Git history: `git log --all --full-history -- .env`
- [ ] File permissions secure
- [ ] Firewall configured (port 80, 443 only)

### Caching & Optimization
- [ ] Caches cleared: `php artisan cache:clear`
- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Views compiled: `php artisan view:cache`

### Database Backup
- [ ] Automated backup scheduled (cron job)
- [ ] Test backup restoration works
- [ ] Backups stored securely (encrypted cloud storage)

### Monitoring & Logs
- [ ] Log rotation configured (logrotate)
- [ ] Monitoring alerts set up
- [ ] Daily log review scheduled
- [ ] Error tracking enabled (optional: Sentry, Bugsnag)

### Final Verification
- [ ] API responds to requests: `curl https://your-domain.com/api/v1/admin/stats`
- [ ] Landing page loads: `curl https://your-domain.com/`
- [ ] Admin login works
- [ ] Database migrations successful
- [ ] No sensitive data in Git

---

## 📱 Mobile App Integration

### Flutter App Configuration
- [ ] Backend URL configured in Flutter app
- [ ] API version matches backend
- [ ] Authentication tokens work
- [ ] Push notifications receive tokens

### Testing
- [ ] Register user through app
- [ ] Login succeeds
- [ ] Create appointment through app
- [ ] Appointment appears in database
- [ ] User appears in admin dashboard
- [ ] Receive push notification (if configured)

---

## 🔄 Ongoing Maintenance

### Weekly
- [ ] Check error logs: `tail storage/logs/laravel.log`
- [ ] Monitor disk space: `df -h`
- [ ] Verify backups completed

### Monthly
- [ ] Backup restoration test
- [ ] Update security patches: `composer update`
- [ ] Review user activity
- [ ] Check for suspicious login attempts

### Quarterly
- [ ] Rotate API credentials (Firebase, SMTP)
- [ ] Update PHP/dependencies
- [ ] Security audit
- [ ] Performance review

---

## ❌ Common Mistakes to Avoid

- [ ] ❌ Committing `.env` file to Git
- [ ] ❌ Using `APP_DEBUG=true` in production
- [ ] ❌ Not using HTTPS in production
- [ ] ❌ Weak database passwords
- [ ] ❌ Running migrations without `--force` flag in production
- [ ] ❌ Not backing up database before migrations
- [ ] ❌ Leaving default admin credentials
- [ ] ❌ Not rotating API keys regularly
- [ ] ❌ Ignoring error logs
- [ ] ❌ Not testing backup restoration

---

## ✅ Ready to Go!

If all items are checked, your PMA backend is ready to use!

**Next Steps:**
1. Read [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for detailed documentation
2. Use [QUICK_UPDATE.md](QUICK_UPDATE.md) for regular updates
3. Follow [SECURITY.md](SECURITY.md) for security best practices
4. Check [README.md](README.md) for API documentation

---

**Last Updated:** 2026-06-24 | **Version:** 1.0.0
