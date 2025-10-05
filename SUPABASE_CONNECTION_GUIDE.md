# Supabase Connection Troubleshooting Guide

## 🚨 Current Issue: Database Authentication Failed

The error `FATAL: password authentication failed for user "postgres"` indicates incorrect Supabase credentials.

## 🔧 How to Fix

### Step 1: Get Correct Supabase Credentials

1. Go to your **Supabase Dashboard** (https://supabase.com/dashboard)
2. Select your project
3. Go to **Settings** → **Database**
4. Copy the connection details

### Step 2: Update Your .env File

Replace the database section in your `.env` file with:

#### Option A: Pooled Connection (Recommended for Production)
```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.YOUR_PROJECT_REF
DB_PASSWORD=YOUR_DATABASE_PASSWORD
DB_SSLMODE=prefer
```

#### Option B: Direct Connection (If pooled fails)
```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.compute.amazonaws.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=YOUR_DATABASE_PASSWORD
DB_SSLMODE=require
```

### Step 3: Find Your Credentials

**Project Reference (YOUR_PROJECT_REF):**
- Found in your Supabase project URL: `https://YOUR_PROJECT_REF.supabase.co`
- Example: If URL is `https://ltpnrocfcdpmeicfnlsa.supabase.co`, then ref is `ltpnrocfcdpmeicfnlsa`

**Database Password:**
- This is the password you set when creating the Supabase project
- If forgotten, you can reset it in Settings → Database → Database password

**Full Username for Pooled Connection:**
- Format: `postgres.YOUR_PROJECT_REF`
- Example: `postgres.ltpnrocfcdpmeicfnlsa`

### Step 4: Test Connection

After updating `.env`:

```bash
# Clear config cache
php artisan config:clear

# Test database connection
php artisan db:show

# If successful, you should see database tables
php artisan db:table --table=profiles
```

## 🔍 Common Issues & Solutions

### Issue 1: "duplicate SASL authentication request"
**Solution:** Change `DB_SSLMODE=require` to `DB_SSLMODE=prefer`

### Issue 2: "password authentication failed"
**Solutions:**
1. Double-check your database password
2. Ensure username format is correct (`postgres.project-ref` for pooled)
3. Try direct connection instead of pooled

### Issue 3: Connection timeout
**Solutions:**
1. Check if your IP is whitelisted in Supabase (if RLS is enabled)
2. Try different SSL modes: `prefer`, `require`, `disable`

## 📋 Quick Checklist

- [ ] Project reference is correct in username
- [ ] Database password is correct
- [ ] SSL mode is set to `prefer` or `require`
- [ ] Port is correct (6543 for pooled, 5432 for direct)
- [ ] Configuration cache is cleared

## 🆘 If Still Not Working

1. **Try SQLite temporarily** for development:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

2. **Check Supabase project status** in dashboard

3. **Contact support** with your project reference (without sharing passwords)

---

**Note:** Never commit your actual `.env` file with real credentials to version control!
