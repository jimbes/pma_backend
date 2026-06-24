# PMA Backend - Security Guidelines

Critical security information for developers and administrators.

## 🚨 Never Commit Sensitive Data

These files must **NEVER** be committed to Git:

| File | What to Protect |
|------|-----------------|
| `.env` | Database credentials, API keys, secrets |
| `.env.production` | Production secrets |
| `firebase-service-account.json` | Firebase credentials |
| `storage/keys/*` | Application encryption keys |
| Any file with passwords, tokens, or credentials |

**Check before pushing:**
```bash
# See what would be committed
git status

# Ensure .env files are NOT listed
# If they are, DON'T commit!
```

## ✅ .gitignore Verification

The `.gitignore` file excludes sensitive files. Verify it's working:

```bash
# Check if .env is being tracked (should show "not tracked")
git ls-files | grep -E "\.env|credentials|secrets|keys"

# Should return nothing if properly ignored
```

If `.env` was accidentally committed:

```bash
# Remove from Git history (URGENT!)
git rm --cached .env
git commit -m "Remove .env from tracking"
git push

# Then force-delete from history (advanced)
# See: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/removing-sensitive-data-from-a-repository
```

## 🔑 Environment Variables

### Required Variables

**NEVER leave these blank in production:**
```env
APP_KEY=              # Generate with: php artisan key:generate
APP_DEBUG=false       # MUST be false in production
DB_PASSWORD=          # Strong password (20+ characters)
MAIL_PASSWORD=        # SMTP password
FIREBASE_PRIVATE_KEY= # Firebase credentials
```

### Development vs. Production

**Local Development (.env)**
```env
APP_ENV=local
APP_DEBUG=true
DB_PASSWORD=dev_password_123
```

**Production (never commit this!)**
```env
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=SuperSecure#!@2024RandomPassword
FIREBASE_PRIVATE_KEY=[40+ character encrypted key]
```

## 🔐 Database Security

### Passwords

1. **Create strong database passwords:**
   ```bash
   # Generate random password
   openssl rand -base64 32
   ```

2. **Update password regularly:**
   ```bash
   # Change database password
   mysql -u root -p
   # ALTER USER 'pma_user'@'localhost' IDENTIFIED BY 'new_strong_password';
   ```

3. **Restrict database access:**
   ```bash
   # Only allow local connections
   mysql -u root -p
   # GRANT ALL PRIVILEGES ON pma_production.* TO 'pma_user'@'localhost' IDENTIFIED BY 'password';
   # DO NOT use '%' (all hosts) in production
   ```

### Backups

1. **Encrypt database backups:**
   ```bash
   mysqldump -u pma_user -p pma_production | gpg --encrypt -r your@email.com > backup.sql.gpg
   ```

2. **Store backups securely:**
   - Don't store on the same server
   - Use encrypted cloud storage (AWS S3, Google Cloud)
   - Rotate backup keys regularly

## 🛡️ API Security

### Authentication

- **Use HTTPS only** (redirect HTTP to HTTPS)
- **Use strong tokens** (Laravel Sanctum generates 40-char random tokens)
- **Token expiration:** Set reasonable session lifetime
- **Revoke tokens on logout** (Sanctum does this by default)

### Rate Limiting

Protect endpoints from abuse:
```php
// In routes - example
Route::post('/auth/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1'); // 5 attempts per minute
```

### CORS (Cross-Origin Requests)

Configure `config/cors.php`:
```php
'allowed_origins' => ['https://your-app.com'], // Never use '*'
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['Content-Type', 'Authorization'],
```

## 📝 Logging & Monitoring

### What to Log

✅ **DO log:**
- Authentication attempts (success & failure)
- Admin actions
- User data changes
- Errors and exceptions

❌ **DON'T log:**
- Passwords or password reset links
- API tokens or session cookies
- Credit card numbers or sensitive PII
- Firebase credentials

### View Logs Safely

```bash
# View production logs
tail -f storage/logs/laravel.log

# Search for security issues
grep -i "error\|exception\|unauthorized\|failed" storage/logs/laravel.log

# Monitor login attempts
grep "login" storage/logs/laravel.log | tail -20
```

### Alert on Suspicious Activity

Monitor for:
- Multiple failed login attempts
- Unauthorized API access (401/403)
- Database query errors
- Unusual file access patterns

## 🚀 Deployment Security

### Before Production

**Pre-deployment checklist:**
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] HTTPS certificate installed
- [ ] `.env` file NOT in repository
- [ ] `.gitignore` updated with all sensitive files
- [ ] Database backed up
- [ ] API keys rotated (Firebase, SMTP)
- [ ] Admin password changed from default
- [ ] Firewall rules configured
- [ ] Regular backup strategy in place

### Server Configuration

```bash
# Restrict file permissions
chmod 640 .env
chmod 755 public
chmod 775 storage bootstrap/cache

# Disable directory listing
# In Nginx nginx.conf:
# autoindex off;

# In Apache .htaccess:
# Options -Indexes
```

## 🔄 API Key Rotation

Rotate credentials regularly:

```bash
# Firebase
# 1. Generate new service account in Firebase Console
# 2. Update FIREBASE_PRIVATE_KEY in .env
# 3. Test notifications work
# 4. Delete old service account

# SMTP Password
# 1. Generate new app password in email provider
# 2. Update MAIL_PASSWORD in .env
# 3. Send test email
# 4. Revoke old password

# Database Password
# Change with:
# mysql -u root -p
# ALTER USER 'pma_user'@'localhost' IDENTIFIED BY 'new_password';
# Update DB_PASSWORD in .env
```

## 🚨 Security Incident Response

If you suspect a breach:

1. **Immediately stop the service:**
   ```bash
   # Stop web server
   sudo systemctl stop nginx  # or apache2
   ```

2. **Rotate all credentials:**
   ```bash
   # Generate new APP_KEY
   php artisan key:generate
   
   # Change database password
   # Change API keys (Firebase, SMTP)
   # Change admin password
   ```

3. **Audit logs:**
   ```bash
   # Check who accessed what
   tail -500 storage/logs/laravel.log | grep -E "auth|error|exception"
   ```

4. **Backup and investigate:**
   ```bash
   # Backup current state
   mysqldump -u pma_user -p pma_production > incident_backup.sql
   cp -r . ../backup_$(date +%Y%m%d)
   ```

5. **Update dependencies:**
   ```bash
   composer update
   php artisan migrate
   ```

## 📚 Resources

- [Laravel Security](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Sanctum Authentication](https://laravel.com/docs/sanctum)
- [Firebase Security](https://firebase.google.com/docs/projects/security/)

---

**Last Updated:** 2026-06-24 | **Version:** 1.0.0
