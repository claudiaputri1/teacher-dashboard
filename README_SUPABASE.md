# Teacher Dashboard - Supabase Integration

## 🎯 Status: Project Sudah Disesuaikan dengan Supabase

Project Laravel ini sudah **sepenuhnya disesuaikan** dengan struktur database Supabase yang sudah ada.

## ✅ Yang Sudah Dilakukan

### 1. Model Laravel Disesuaikan
Semua model sudah diupdate untuk menggunakan tabel Supabase:
- ✅ `User` → `profiles` (UUID primary key)
- ✅ `Classroom` → `classrooms` (UUID primary key)
- ✅ `Assignment` → `assignments` (UUID primary key)
- ✅ `StudentProgress` → `student_progress` (UUID primary key)
- ✅ `Module` → `modules` (UUID primary key)
- ✅ `Lesson` → `lessons` (UUID primary key)

### 2. Migrations Disabled
Folder `migrations` → `migrations.disabled` karena menggunakan struktur database yang sudah ada di Supabase.

### 3. Demo Accounts Dibuat
3 akun dummy sudah dibuat dan siap digunakan untuk testing.

## 🔑 Demo Accounts

### Teacher (Untuk Testing Dashboard)
```
Email: guru@demo.com
Password: demo123
```

### Teacher (Alternatif)
```
Email: test@example.com
Password: password123
```

### Student
```
Email: student@demo.com
Password: demo123
```

**Lihat detail lengkap di:** `DEMO_ACCOUNTS.md`

## 📚 Dokumentasi

- **`SUPABASE_SETUP.md`** - Panduan lengkap setup dan struktur database
- **`DEMO_ACCOUNTS.md`** - Kredensial akun demo dan cara penggunaan
- **`supabase-schema.txt`** - Struktur lengkap tabel Supabase

## 🛠️ Scripts Helper

### Membuat User Baru
```bash
php create-demo-user.php
```

### Fix User Roles
```bash
php fix-user-roles.php
```

### Check Database Structure
```bash
php check-tables.php
```

## 🚀 Quick Start

1. **Pastikan .env sudah dikonfigurasi**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   
   SUPABASE_URL=https://your-project.supabase.co
   SUPABASE_ANON_KEY=your-anon-key
   SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
   ```

2. **Test koneksi database**
   ```bash
   php artisan db:show
   ```

3. **Login menggunakan demo account**
   - Email: `guru@demo.com`
   - Password: `demo123`

## ⚠️ Penting

1. **JANGAN jalankan migrations** - Database sudah ada di Supabase
2. **JANGAN gunakan UserSeeder** - User harus dibuat via Supabase Auth
3. **Authentication dihandle oleh Supabase** - Bukan Laravel Auth
4. **Semua ID menggunakan UUID** - Bukan auto-increment integer

## 🔄 Relasi Antar Tabel

```
profiles (users)
  └── classrooms (teacher_id)
       ├── classroom_members (classroom_id, student_id)
       └── assignments (classroom_id)
            └── assignment_submissions (assignment_id, student_id)

modules
  └── lessons (module_id)
       ├── student_progress (lesson_id, user_id)
       └── assignments (lesson_id)
```

## 📞 Support

Jika ada masalah dengan:
- **Database connection** → Check .env credentials
- **User tidak bisa login** → Check Supabase Auth dashboard
- **Data tidak muncul** → Check tabel di Supabase dashboard

---

**Last Updated:** 2025-09-30
**Status:** ✅ Ready for Development
