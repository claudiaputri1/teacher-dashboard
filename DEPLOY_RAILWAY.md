# Deploy Laravel Teacher Dashboard ke Railway

Panduan lengkap deploy aplikasi Laravel ke Railway.app

## 📋 Persiapan

### 1. Pastikan Kode Sudah di GitHub

```bash
# Jika belum init git
git init
git add .
git commit -m "Prepare for Railway deployment"

# Push ke GitHub
git remote add origin https://github.com/username/teacher-dashboard.git
git branch -M main
git push -u origin main
```

### 2. Jalankan Migration Lokal (Opsional)

```bash
php artisan migrate
```

---

## 🚀 Deploy ke Railway

### Step 1: Sign Up Railway

1. Buka [railway.app](https://railway.app)
2. Klik **"Login"** dan gunakan GitHub untuk sign in
3. Authorize Railway untuk akses GitHub repos

### Step 2: Create New Project

1. Di Railway dashboard, klik **"New Project"**
2. Pilih **"Deploy from GitHub repo"**
3. Pilih repository **teacher-dashboard**
4. Railway akan auto-detect Laravel dan mulai build

### Step 3: Configure Environment Variables

1. Di Railway project, klik **"Variables"** tab
2. Tambahkan environment variables berikut:

```env
APP_NAME=GeoCetak
APP_ENV=production
APP_KEY=base64:M+q8dERfAIc6cSxZo4DEq+CDAByk7+Z6URyOVI5gvbc=
APP_DEBUG=false
APP_URL=https://your-app.railway.app

LOG_CHANNEL=errorlog
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.cfuslcgubngcthxvxoqj
DB_PASSWORD=inidatabasegweh
DB_SSLMODE=require
POOL_MODE=transaction

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

SUPABASE_URL=https://cfuslcgubngcthxvxoqj.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key

VITE_SUPABASE_URL=https://cfuslcgubngcthxvxoqj.supabase.co
VITE_SUPABASE_ANON_KEY=your-anon-key
```

**⚠️ PENTING:**
- Ganti `APP_URL` dengan URL Railway Anda (akan dapat setelah deploy)
- Gunakan `APP_KEY` yang sudah ada di `.env` lokal Anda
- Pastikan semua kredensial database benar

### Step 4: Deploy

1. Railway akan otomatis deploy setelah environment variables diset
2. Tunggu build selesai (~3-5 menit)
3. Railway akan memberikan public URL

### Step 5: Generate Domain

1. Di Railway project, klik **"Settings"**
2. Scroll ke **"Domains"**
3. Klik **"Generate Domain"**
4. Copy URL yang digenerate (misal: `teacher-dashboard-production.up.railway.app`)
5. Update `APP_URL` di environment variables dengan URL ini

### Step 6: Trigger Redeploy

1. Klik **"Deployments"** tab
2. Klik **"Redeploy"** pada deployment terakhir
3. Atau push commit baru ke GitHub (auto-deploy)

---

## ✅ Verifikasi Deployment

1. Buka URL Railway Anda: `https://teacher-dashboard-production.up.railway.app`
2. Test login dengan akun teacher yang ada
3. Test dashboard stats
4. Pastikan database connection berfungsi

---

## 🔧 Troubleshooting

### Issue: "500 Internal Server Error"

**Solution:**
1. Check logs di Railway: **"Deployments" → Click deployment → "View Logs"**
2. Pastikan `APP_KEY` sudah diset
3. Verifikasi database credentials

### Issue: "Session Not Working"

**Solution:**
1. Pastikan `SESSION_DRIVER=database` di environment variables
2. Check apakah migration sessions table sudah run
3. Lihat logs untuk error

### Issue: "Database Connection Failed"

**Solution:**
1. Verifikasi DB credentials di Railway environment variables
2. Pastikan Supabase database accessible dari internet
3. Check `DB_SSLMODE=require` sudah diset

### Issue: "Assets Not Loading"

**Solution:**
1. Pastikan `npm run build` berhasil (check build logs)
2. Verifikasi `APP_URL` sudah benar (dengan https://)
3. Check `VITE_*` environment variables

---

## 🔄 Update Aplikasi

Setelah melakukan perubahan kode:

```bash
git add .
git commit -m "Update: description of changes"
git push origin main
```

Railway akan **auto-deploy** secara otomatis!

---

## 📊 Monitoring

1. **Logs**: Railway Dashboard → Deployments → View Logs
2. **Metrics**: Railway Dashboard → Metrics tab
3. **Database**: Check Supabase dashboard untuk database queries

---

## 💰 Pricing

- **Free Tier**: $5 credit per month (cukup untuk testing)
- **Hobby Plan**: $5/month usage-based
- **Database**: Gratis (pakai Supabase yang sudah ada)

---

## 🆘 Need Help?

- Railway Docs: https://docs.railway.app
- Laravel Docs: https://laravel.com/docs
- GitHub Issues: Create issue di repo ini

---

## 📝 Notes

- **Auto-deploy**: Setiap push ke `main` branch akan trigger deploy
- **Zero-downtime**: Railway tidak support zero-downtime deployment di free tier
- **Logs retention**: 7 hari di free tier
- **Custom domain**: Bisa tambahkan custom domain di Railway Settings

---

**Happy Deploying! 🚀**
