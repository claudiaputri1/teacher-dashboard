# Deploy Laravel Teacher Dashboard ke Vercel

Panduan lengkap deploy aplikasi Laravel ke Vercel dengan serverless functions.

## ⚠️ PENTING: Batasan Vercel untuk Laravel

Vercel **TIDAK** native support PHP/Laravel. Deployment ini menggunakan **serverless workaround** dengan batasan:

### Batasan:
- ❌ **Cold Start**: Request pertama lambat (3-5 detik)
- ❌ **Timeout**: Maximum 10 detik per request (bisa upgrade ke 60 detik di Pro plan)
- ❌ **Storage**: Filesystem read-only (kecuali `/tmp`)
- ❌ **Database**: Harus external (Supabase ✅ sudah OK)
- ❌ **File Upload**: Terbatas, maksimal 4.5MB
- ❌ **WebSocket**: Tidak support
- ⚠️ **Performance**: Lebih lambat dari Railway/dedicated server

### Kelebihan:
- ✅ **Free**: 100GB bandwidth/bulan
- ✅ **Auto-deploy**: Otomatis dari GitHub
- ✅ **CDN Global**: Fast asset delivery
- ✅ **HTTPS**: Auto SSL certificate

---

## 📋 Persiapan

### 1. Pastikan Kode Sudah di GitHub

```bash
# Jika belum init git
git init
git add .
git commit -m "Prepare for Vercel deployment"

# Push ke GitHub
git remote add origin https://github.com/username/teacher-dashboard.git
git branch -M main
git push -u origin main
```

### 2. Install Dependencies Lokal (Opsional)

```bash
composer install
npm install
npm run build
```

---

## 🚀 Deploy ke Vercel

### Step 1: Sign Up Vercel

1. Buka [vercel.com](https://vercel.com)
2. Klik **"Sign Up"** dan gunakan GitHub untuk sign in
3. Authorize Vercel untuk akses GitHub repos

### Step 2: Import Project

1. Di Vercel dashboard, klik **"Add New..."** → **"Project"**
2. Pilih **"Import Git Repository"**
3. Cari dan pilih repository **teacher-dashboard**
4. Klik **"Import"**

### Step 3: Configure Project

**Framework Preset**: Pilih **"Other"** (bukan Laravel, karena Vercel tidak support Laravel native)

**Build & Output Settings**:
- Build Command: `npm run vercel-build`
- Output Directory: `public`
- Install Command: `npm install`

Klik **"Deploy"** untuk pertama kali (akan gagal, itu normal)

### Step 4: Configure Environment Variables

1. Setelah deployment pertama, klik **"Settings"** tab
2. Pilih **"Environment Variables"**
3. Tambahkan semua environment variables berikut:

```env
APP_NAME=GeoCetak
APP_ENV=production
APP_KEY=base64:M+q8dERfAIc6cSxZo4DEq+CDAByk7+Z6URyOVI5gvbc=
APP_DEBUG=false
APP_URL=https://your-app.vercel.app

# Vercel Serverless Paths
VIEW_COMPILED_PATH=/tmp/views
CACHE_COMPILED_PATH=/tmp/cache
APP_STORAGE=/tmp/storage

LOG_CHANNEL=errorlog
LOG_LEVEL=error

# Database - Supabase PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.cfuslcgubngcthxvxoqj
DB_PASSWORD=inidatabasegweh
DB_SSLMODE=require

# Session & Cache (MUST use database for serverless)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

# Supabase Configuration
SUPABASE_URL=https://cfuslcgubngcthxvxoqj.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key

# Vite Environment Variables
VITE_SUPABASE_URL=https://cfuslcgubngcthxvxoqj.supabase.co
VITE_SUPABASE_ANON_KEY=your-anon-key
```

**⚠️ PENTING:**
- Ganti `APP_URL` dengan URL Vercel Anda (misal: `teacher-dashboard.vercel.app`)
- Gunakan `APP_KEY` yang sudah ada di `.env` lokal Anda
- Pastikan semua kredensial database Supabase benar
- Ganti `SUPABASE_ANON_KEY` dan `SUPABASE_SERVICE_ROLE_KEY` dengan key asli Anda

### Step 5: Trigger Redeploy

1. Kembali ke **"Deployments"** tab
2. Klik **"Redeploy"** pada deployment terakhir
3. Atau push commit baru ke GitHub untuk auto-deploy

---

## 🔄 Run Migrations

**PENTING**: Vercel tidak bisa auto-run migrations seperti Railway!

Anda harus run migrations secara manual dari lokal:

```bash
# Update .env lokal dengan database production (Supabase)
php artisan migrate --force

# Atau gunakan SSH/terminal di server lain yang bisa akses Supabase
```

**Alternatif**: Gunakan Supabase SQL Editor untuk run migrations manual.

---

## ✅ Verifikasi Deployment

1. Buka URL Vercel Anda: `https://teacher-dashboard.vercel.app`
2. **Jika muncul error**: Check logs di Vercel Dashboard → Deployments → View Function Logs
3. Test login dengan akun teacher yang ada
4. Test dashboard stats (akan lambat di cold start)

---

## 🔧 Troubleshooting

### Issue: "500 Internal Server Error"

**Solution:**
1. Check logs: **Deployments** → Click deployment → **"View Function Logs"**
2. Pastikan `APP_KEY` sudah diset di environment variables
3. Verifikasi database credentials
4. Pastikan migrations sudah di-run

### Issue: "Function Execution Timeout"

**Solution:**
1. Request melebihi 10 detik timeout
2. Optimize database queries (tambahkan indexes)
3. Reduce Supabase API calls
4. Upgrade ke Vercel Pro ($20/month) untuk 60 detik timeout

### Issue: "SQLSTATE[08006] Connection failed"

**Solution:**
1. Pastikan Supabase database accessible dari internet
2. Check firewall settings di Supabase
3. Verifikasi `DB_SSLMODE=require` sudah diset
4. Test koneksi dari external tool (TablePlus, pgAdmin)

### Issue: "Session Not Working"

**Solution:**
1. Pastikan `SESSION_DRIVER=database` (BUKAN 'file')
2. Pastikan sessions table sudah ada (run migration)
3. Check `APP_URL` di environment variables

### Issue: "Assets Not Loading (CSS/JS)"

**Solution:**
1. Pastikan `npm run build` berhasil (check build logs)
2. Verifikasi `VITE_*` environment variables
3. Check vercel.json routes configuration
4. Clear browser cache

### Issue: "Cold Start Lambat (3-5 detik)"

**Solution:**
1. Ini **NORMAL** untuk serverless PHP di Vercel
2. Tidak bisa dihindari di free tier
3. Solusi:
   - Upgrade ke Vercel Pro ($20/month) untuk faster cold starts
   - Atau pindah ke Railway/Heroku untuk traditional server

---

## 📊 File Structure untuk Vercel

```
teacher-dashboard/
├── api/
│   └── index.php          # Serverless entry point (BARU)
├── public/
│   ├── build/            # Vite compiled assets
│   ├── css/
│   ├── js/
│   └── index.php         # Laravel public index (tidak dipakai di Vercel)
├── vercel.json           # Vercel configuration (BARU)
├── .vercelignore         # Files to ignore (BARU)
├── package.json          # Updated with vercel-build script
└── .env.example          # Updated with Vercel paths
```

---

## 🔄 Update Aplikasi

Setelah melakukan perubahan kode:

```bash
git add .
git commit -m "Update: description of changes"
git push origin main
```

Vercel akan **auto-deploy** secara otomatis!

**Jika ada perubahan database schema:**

```bash
# Run migrations manual dari lokal
php artisan migrate --force
```

---

## 📝 Environment Variables Checklist

Pastikan semua ini sudah diset di Vercel Settings → Environment Variables:

- [x] `APP_NAME`
- [x] `APP_ENV=production`
- [x] `APP_KEY` (dari .env lokal)
- [x] `APP_DEBUG=false`
- [x] `APP_URL` (URL Vercel Anda)
- [x] `VIEW_COMPILED_PATH=/tmp/views`
- [x] `CACHE_COMPILED_PATH=/tmp/cache`
- [x] `APP_STORAGE=/tmp/storage`
- [x] `DB_CONNECTION=pgsql`
- [x] `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- [x] `SESSION_DRIVER=database`
- [x] `CACHE_STORE=database`
- [x] `SUPABASE_URL`, `SUPABASE_ANON_KEY`, `SUPABASE_SERVICE_ROLE_KEY`
- [x] `VITE_SUPABASE_URL`, `VITE_SUPABASE_ANON_KEY`

---

## 💰 Pricing

### Vercel Free (Hobby):
- 100GB bandwidth/bulan
- 100 deployments/hari
- 10 detik function timeout
- TIDAK untuk production app dengan traffic tinggi

### Vercel Pro ($20/month):
- 1TB bandwidth/bulan
- Unlimited deployments
- 60 detik function timeout
- Password protection
- Analytics

---

## 🆘 Rekomendasi

**Untuk Production App**, saya **SANGAT REKOMENDASIKAN** Railway instead of Vercel karena:

| Feature | Vercel | Railway |
|---------|--------|---------|
| Cold Start | ❌ 3-5 detik | ✅ Always warm |
| Timeout | ⚠️ 10 detik (60s di Pro) | ✅ No limit |
| Performance | ⚠️ Slower | ✅ Fast |
| PHP Support | ❌ Workaround | ✅ Native |
| Auto Migration | ❌ Manual | ✅ Automatic |
| File Upload | ⚠️ 4.5MB limit | ✅ No limit |
| **Harga Free** | ✅ $0 | ✅ $5 credit/month |
| **Best For** | Static sites, Next.js | Laravel, PHP apps |

**Kesimpulan**: Jika ini app serius, **pakai Railway**. Vercel untuk testing/demo saja.

---

## 📚 Resources

- Vercel Docs: https://vercel.com/docs
- Laravel Docs: https://laravel.com/docs
- Vercel PHP Runtime: https://github.com/vercel-community/php

---

**Happy Deploying! 🚀**

**Note**: Jika deployment Vercel terlalu kompleks atau lambat, consider switching to Railway (sudah disiapkan di `DEPLOY_RAILWAY.md`)
