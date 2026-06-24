# PMA Backend - Couples Medical Appointment & Medication Manager

API backend for managing shared medical appointments and medication schedules for couples undergoing fertility treatment.

## 📋 What is PMA Backend?

PMA is a REST API that powers the [PMA mobile app](https://github.com/yourusername/pma-flutter). It provides:

✅ **User Management**
- Dual-account system (two partners access same data)
- Partner invitation and connection
- Secure email/password authentication
- Admin dashboard with user management

✅ **Medical Data**
- Appointment scheduling and tracking
- Medication management with dosages
- Medication schedule tracking
- Medication adherence logs
- Mark appointments as complete

✅ **Notifications**
- Push notifications (Firebase Cloud Messaging)
- Email reminders (SMTP)
- Flexible notification routing (send to one or both partners)
- Notification history and status tracking

✅ **Admin Features**
- Public landing page with product info
- Protected admin dashboard
- User list and management
- GDPR-compliant user deletion (cascade deletes all data)
- Real-time KPI metrics

## 🚀 Quick Start

### Prerequisites
- PHP 8.4+
- MySQL 8.0+ or MariaDB 11.4+
- Composer
- Git

### Local Development Setup

1. **Clone repository:**
   ```bash
   git clone https://github.com/yourusername/pma-backend.git
   cd pma-backend
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

4. **Edit `.env` with your local database credentials:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pma_local
   DB_USERNAME=your_user
   DB_PASSWORD=your_password
   ```
   ⚠️ **NEVER commit `.env` to Git** - it's in `.gitignore` for security

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Create database:**
   ```bash
   mysql -u root -p -e "CREATE DATABASE pma_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

7. **Run migrations:**
   ```bash
   php artisan migrate
   ```

8. **Start local server:**
   ```bash
   php artisan serve
   ```

   API available at: `http://127.0.0.1:8000/api/v1`

### Docker Setup (Recommended)

```bash
cd pma-backend

# Start all services (Laravel, Nginx, MariaDB)
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# View logs
docker-compose logs -f app
```

API available at: `http://127.0.0.1:8000/api/v1`

Database credentials (from `docker-compose.yml`):
- Username: `pma_user`
- Password: (set in docker-compose.yml)
- Database: `pma`

## 📚 API Endpoints

All endpoints use JSON and require `Content-Type: application/json` header.

### Authentication (6 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Register new account |
| POST | `/auth/login` | Login (returns API token) |
| GET | `/auth/me` | Get current user info |
| POST | `/auth/logout` | Logout |
| POST | `/auth/invite-partner` | Send partner invitation |
| POST | `/auth/accept-invite` | Accept partner invitation |

### Appointments (6 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/appointments` | List all appointments |
| POST | `/appointments` | Create appointment |
| GET | `/appointments/{id}` | Get appointment details |
| PUT | `/appointments/{id}` | Update appointment |
| DELETE | `/appointments/{id}` | Delete appointment |
| POST | `/appointments/{id}/complete` | Mark as complete |

### Medications (4 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/medications` | List all medications |
| POST | `/medications` | Create medication |
| PUT | `/medications/{id}` | Update medication |
| DELETE | `/medications/{id}` | Delete medication |

### Medication Schedules (4 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/medication-schedules` | List schedules |
| POST | `/medication-schedules` | Create schedule |
| PUT | `/medication-schedules/{id}` | Update schedule |
| DELETE | `/medication-schedules/{id}` | Delete schedule |

### Medication Logs (3 endpoints)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/medication-taken-logs` | View medication history |
| POST | `/medication-taken-logs` | Log medication taken |
| DELETE | `/medication-taken-logs/{id}` | Delete log entry |

### Other Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/device-tokens` | Register device for push notifications |
| DELETE | `/device-tokens/{token}` | Revoke device token |
| GET | `/partner` | Get partner info |
| DELETE | `/partner` | Disconnect from partner |
| GET | `/notifications` | View notification history |

### Admin Endpoints (Requires admin token)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/users` | List all users |
| DELETE | `/admin/users/{id}` | Delete user (GDPR) |
| GET | `/admin/stats` | Get KPI statistics |

### Public Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Landing page |
| GET | `/admin/stats` | Public statistics (no auth required) |

## 🔑 Authentication

Use bearer token authentication:

```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     https://your-api.com/api/v1/appointments
```

## 📖 Documentation

- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Full deployment to production server
- **[QUICK_UPDATE.md](QUICK_UPDATE.md)** - Fast reference for updates
- **[ARCHITECTURE.md](../ARCHITECTURE.md)** - Database schema and API design
- **[AUTH_IMPLEMENTATION.md](AUTH_IMPLEMENTATION.md)** - Authentication details
- **[RESOURCES_IMPLEMENTATION.md](RESOURCES_IMPLEMENTATION.md)** - CRUD endpoints
- **[NOTIFICATIONS_IMPLEMENTATION.md](NOTIFICATIONS_IMPLEMENTATION.md)** - Notification system
- **[EMAIL_AND_FIREBASE_SETUP.md](EMAIL_AND_FIREBASE_SETUP.md)** - Email & push setup
- **[TESTING_GUIDE.md](TESTING_GUIDE.md)** - Running tests

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

**Current test coverage:** 17+ tests covering auth, CRUD, and notifications

## 🔐 Security

✅ **Secure by default:**
- Environment variables in `.env` (never committed)
- Password hashing with bcrypt
- CSRF protection on web routes
- SQL injection protection (Eloquent ORM)
- API token validation on all protected endpoints
- GDPR-compliant data deletion

⚠️ **Before Production:**
- [ ] Set `APP_DEBUG=false`
- [ ] Use HTTPS only
- [ ] Change APP_KEY
- [ ] Set strong database password
- [ ] Configure SMTP for emails
- [ ] Configure Firebase for push notifications
- [ ] Enable database backups
- [ ] Monitor logs regularly

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) → Security Checklist

## 🐛 Troubleshooting

**500 Error?**
```bash
tail -50 storage/logs/laravel.log
```

**Database connection error?**
```bash
# Verify .env settings
cat .env | grep DB_

# Test connection
php artisan tinker
# In tinker: DB::connection()->getPdo();
```

**Migration issues?**
```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback all
php artisan migrate:reset
```

See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) → Troubleshooting section for more

## 📦 Dependencies

Core packages:
- **laravel/framework** - Web framework
- **laravel/sanctum** - API token authentication
- **firebase/php-jwt** - JWT tokens
- **guzzlehttp/guzzle** - HTTP client

See `composer.json` for full list.

## 📱 Mobile App

Frontend Flutter app: [pma-flutter](https://github.com/yourusername/pma-flutter)

## 🚢 Deployment

1. **Quick reference:** See [QUICK_UPDATE.md](QUICK_UPDATE.md)
2. **Full guide:** See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

## 📝 License

Proprietary - All rights reserved

## 👥 Support

For issues, see:
- Documentation files listed above
- [GitHub Issues](https://github.com/yourusername/pma-backend/issues)
- Backend logs: `storage/logs/laravel.log`

---

**Last Updated:** 2026-06-24 | **Version:** 1.0.0 | **Status:** Production Ready ✅
