# Changelog - Teacher Dashboard

## [2025-10-01] - Removed Class Creation Feature

### Removed
- ❌ **Tombol "Buat Kelas Baru"** dari halaman Manajemen Kelas
- ❌ **Modal create class** dari kedua file dashboard
- ❌ **Route POST /api/classes** (store method)
- ❌ **Method `store()` dan `destroy()`** dari ClassController

### Modified
- ✅ **ClassController** - Hanya mendukung read dan update
  - `index()` - List kelas (read-only)
  - `show()` - Detail kelas
  - `update()` - Update kelas existing
  - `addStudent()` - Tambah siswa ke kelas
  
- ✅ **Routes API** - Classes endpoint sekarang read-only
  ```php
  Route::apiResource('classes', ClassController::class)->except(['store', 'destroy']);
  ```

- ✅ **Dashboard Views** - Text diubah dari "Klik 'Buat Kelas Baru' untuk memulai" menjadi "Kelas akan muncul setelah dibuat melalui sistem"

### Reason
Kelas akan dibuat melalui sistem lain atau admin panel, bukan dari teacher dashboard.

---

## [2025-09-30] - Supabase Integration

### Added
- ✅ Integrasi dengan database Supabase
- ✅ Model disesuaikan dengan struktur tabel Supabase (UUID primary keys)
- ✅ Demo accounts untuk testing (guru@demo.com, test@example.com, student@demo.com)
- ✅ Helper scripts untuk membuat user via Supabase Auth

### Changed
- ✅ User model menggunakan tabel `profiles` dari Supabase
- ✅ Migrations di-disable (folder renamed ke `migrations.disabled`)
- ✅ Semua model menggunakan UUID sebagai primary key

### Documentation
- 📄 `README_SUPABASE.md` - Overview integrasi Supabase
- 📄 `SUPABASE_SETUP.md` - Panduan setup dan struktur database
- 📄 `DEMO_ACCOUNTS.md` - Kredensial akun demo
