# Database Migration Guide - Supabase Integration

## ✅ Completed Adjustments

This document outlines all the changes made to align the Laravel application with the existing Supabase database schema.

### 1. Database Configuration Updated

**File: `.env.example`**
- Changed from SQLite to PostgreSQL (Supabase)
- Updated connection parameters for Supabase pooler
- Added Supabase-specific environment variables

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-id
DB_PASSWORD=your-database-password

SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
```

### 2. Dummy Data Removed

**All seeders have been cleaned:**
- `DatabaseSeeder.php` - Removed test user creation
- `DashboardSeeder.php` - Removed sample classes, modules, students, and progress data
- `TeacherDashboardSeeder.php` - Removed sample teacher and student data
- `UserSeeder.php` - Already properly configured for Supabase

### 3. Migrations Disabled

**File: `database/migrations`**
- Disabled the `class_siswas` table creation migration
- Added comments explaining that Supabase schema is used instead

### 4. Models Updated for Supabase Schema

#### ✅ Properly Configured Models:

**User Model (`app/Models/User.php`)**
- Uses `profiles` table (Supabase)
- UUID primary key
- Added role-based methods and relationships
- Added scopes for teachers/students

**Classroom Model (`app/Models/Classroom.php`)**
- Uses `classrooms` table
- UUID primary key
- Proper relationships with User and Assignment models

**StudentProgress Model (`app/Models/StudentProgress.php`)**
- Uses `student_progress` table
- UUID primary key
- Relationships with User and Lesson models

**Assignment Model (`app/Models/Assignment.php`)**
- Uses `assignments` table
- UUID primary key
- Proper relationships with Classroom, User, Module, Lesson

**Module Model (`app/Models/Module.php`)**
- Uses `modules` table
- UUID primary key
- Relationships with Lesson and Assignment models

**Lesson Model (`app/Models/Lesson.php`)**
- Uses `lessons` table
- UUID primary key
- Relationships with Module and StudentProgress models

#### ✅ New Model Created:

**AssignmentSubmission Model (`app/Models/AssignmentSubmission.php`)**
- Uses `assignment_submissions` table
- UUID primary key
- Relationships with Assignment and User models
- Handles JSON fields for files and feedback

#### ⚠️ Deprecated Models:

**ClassSiswa, Student, Teacher Models**
- Marked as deprecated with warning logs
- Kept for backward compatibility
- Should be replaced with User model and role filtering

### 5. Database Schema Alignment

The application now fully aligns with the Supabase schema:

```
profiles (users)
├── classrooms (teacher_id → profiles.id)
│   ├── classroom_members (classroom_id, student_id → profiles.id)
│   └── assignments (classroom_id, teacher_id → profiles.id)
│       └── assignment_submissions (assignment_id, student_id → profiles.id)
└── student_progress (user_id → profiles.id, lesson_id → lessons.id)

modules
└── lessons (module_id → modules.id)
    ├── student_progress (lesson_id → lessons.id)
    └── assignments (lesson_id → lessons.id)
```

## 🚀 Next Steps

### For Developers:

1. **Update your `.env` file** with actual Supabase credentials
2. **Stop using deprecated models** (ClassSiswa, Student, Teacher)
3. **Use User model with role filtering** instead:
   ```php
   // Get teachers
   $teachers = User::teachers()->get();
   
   // Get students
   $students = User::students()->get();
   
   // Check user role
   if ($user->isTeacher()) { ... }
   if ($user->isStudent()) { ... }
   ```

### For Database Operations:

1. **Don't run migrations** - Database schema already exists in Supabase
2. **Don't run seeders** - Use application interface to create data
3. **Create users through Supabase Auth** - Not through Laravel

### Testing the Setup:

1. Update your `.env` with Supabase credentials
2. Test database connection: `php artisan db:show`
3. Test authentication with existing Supabase users
4. Verify data retrieval through the application

## 📋 Supabase Tables Reference

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `profiles` | Users (teachers & students) | `id` (UUID), `role`, `email`, `full_name` |
| `classrooms` | Class management | `id` (UUID), `teacher_id`, `name`, `class_code` |
| `classroom_members` | Student enrollment | `classroom_id`, `student_id`, `joined_at` |
| `student_progress` | Learning progress | `user_id`, `lesson_id`, `completion_percentage` |
| `assignments` | Teacher assignments | `classroom_id`, `teacher_id`, `title`, `due_date` |
| `assignment_submissions` | Student submissions | `assignment_id`, `student_id`, `score` |
| `modules` | Course modules | `id` (UUID), `title`, `order_index` |
| `lessons` | Individual lessons | `module_id`, `title`, `lesson_type` |

## ⚠️ Important Notes

1. **Authentication is handled by Supabase** - Not Laravel's built-in auth
2. **All IDs are UUIDs** - Not auto-incrementing integers
3. **No dummy data** - All data should be real and created through the application
4. **Database schema is immutable** - Changes should be made in Supabase, not Laravel migrations

---

**Status:** ✅ Database fully aligned with Supabase
**Last Updated:** 2025-10-03
