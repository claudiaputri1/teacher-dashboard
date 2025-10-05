# 🔧 Quick Fix for Cache Error

## The Issue
Laravel is trying to use database-based caching, but the `cache` table doesn't exist in your Supabase database.

## ✅ Immediate Fix

**Update these lines in your `.env` file:**

```env
# Change from database to file-based
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🚀 Commands to Run

```bash
# 1. Clear configuration cache
php artisan config:clear

# 2. Now try cache clear (should work)
php artisan cache:clear

# 3. Test if everything works
php artisan route:list
```

## 📋 Complete .env Configuration for Supabase

```env
# Application
APP_NAME="Teacher Dashboard"
APP_ENV=local
APP_KEY=your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database - Supabase
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-ref
DB_PASSWORD=your-database-password
DB_SSLMODE=prefer

# Cache & Session (File-based for Supabase compatibility)
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync

# Supabase
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
```

## 🎯 Why This Happens

- Supabase doesn't include Laravel's default tables (`cache`, `sessions`, `jobs`)
- We use file-based alternatives that don't require database tables
- This is the recommended approach for Supabase integration

## ✅ After Fixing

Your application should work normally with:
- File-based caching (stored in `storage/framework/cache/`)
- File-based sessions (stored in `storage/framework/sessions/`)
- Synchronous queue processing (no database queue table needed)
