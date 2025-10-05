# Setup Supabase untuk Teacher Dashboard

## Struktur Database

Project ini sudah disesuaikan untuk menggunakan database Supabase yang sudah ada dengan struktur berikut:

### Tabel Utama:
- **profiles** - Data profil user (teacher & student)
- **classrooms** - Data kelas yang dibuat oleh teacher
- **classroom_members** - Relasi many-to-many antara classroom dan student
- **student_progress** - Progress belajar siswa per lesson
- **assignments** - Tugas yang dibuat teacher
- **assignment_submissions** - Submission tugas dari siswa
- **modules** - Modul pembelajaran
- **lessons** - Lesson dalam setiap modul

## Authentication

Supabase menggunakan sistem authentication terpisah (`auth.users`). Tabel `profiles` di public schema ter-link dengan `auth.users`.

### Cara Membuat Dummy Account:

#### 1. Melalui Supabase Dashboard (Recommended)
1. Buka Supabase Dashboard → Authentication → Users
2. Klik "Add User" → "Create new user"
3. Masukkan:
   - Email: `guru@demo.com`
   - Password: `demo123`
   - Auto Confirm User: ✓ (checked)
4. Klik "Create user"
5. Copy UUID user yang baru dibuat
6. Buka SQL Editor dan jalankan:

```sql
-- Insert profile untuk teacher
INSERT INTO profiles (id, email, full_name, role, school_name, grade_level)
VALUES 
  ('UUID_DARI_AUTH_USER', 'guru@demo.com', 'Guru Demo', 'teacher', 'SD Negeri 1 Jakarta', '4');

-- Untuk student
INSERT INTO profiles (id, email, full_name, role, school_name, grade_level)
VALUES 
  ('UUID_DARI_AUTH_USER_STUDENT', 'student@demo.com', 'Siswa Demo', 'student', 'SD Negeri 1 Jakarta', '4');
```

#### 2. Melalui Supabase Client (Programmatic)

Jika ingin membuat user secara programmatic, gunakan Supabase Admin API atau buat endpoint khusus yang menggunakan service role key.

## Model Laravel yang Sudah Disesuaikan

Semua model sudah disesuaikan dengan struktur Supabase:
- `User` model → menggunakan tabel `profiles`
- `Classroom` model → menggunakan tabel `classrooms`
- `Assignment` model → menggunakan tabel `assignments`
- `StudentProgress` model → menggunakan tabel `student_progress`
- `Module` model → menggunakan tabel `modules`
- `Lesson` model → menggunakan tabel `lessons`

Semua menggunakan UUID sebagai primary key (bukan auto-increment).

## Migrations

Folder `migrations` telah di-rename menjadi `migrations.disabled` karena kita menggunakan struktur database yang sudah ada di Supabase, bukan membuat tabel baru dari Laravel.

## Testing Login

Setelah membuat user di Supabase Dashboard:

1. **Email**: `guru@demo.com`
2. **Password**: `demo123`

User ini bisa digunakan untuk testing login melalui Supabase Auth.

## Environment Variables

Pastikan `.env` sudah dikonfigurasi dengan benar:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=your_username
DB_PASSWORD=your_password

SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
```

## Catatan Penting

1. **Jangan gunakan `UserSeeder`** untuk membuat user baru karena akan gagal (foreign key constraint dengan auth.users)
2. **Selalu buat user melalui Supabase Authentication** terlebih dahulu
3. **Profile akan otomatis dibuat** jika ada trigger di Supabase, atau buat manual via SQL
4. **UUID harus match** antara auth.users dan profiles
