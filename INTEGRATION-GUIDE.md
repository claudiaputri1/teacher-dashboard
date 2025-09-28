# 🚀 Panduan Integrasi Supabase - Teacher Dashboard

Panduan lengkap untuk menambahkan fungsi-fungsi dashboard dan menyambungkan ke Supabase.

## 📋 Daftar Isi
1. [Setup Supabase](#1-setup-supabase)
2. [Konfigurasi Laravel](#2-konfigurasi-laravel)
3. [Database Schema](#3-database-schema)
4. [Authentication Integration](#4-authentication-integration)
5. [Dashboard Functions](#5-dashboard-functions)
6. [API Endpoints](#6-api-endpoints)
7. [Real-time Features](#7-real-time-features)
8. [Testing](#8-testing)

---

## 1. 🔧 Setup Supabase

### 1.1 Buat Project Supabase
```bash
# 1. Buka https://supabase.com
# 2. Klik "New Project"
# 3. Pilih Organization
# 4. Isi Project Name: "teacher-dashboard"
# 5. Database Password: buat password yang kuat
# 6. Region: Southeast Asia (Singapore)
# 7. Klik "Create new project"
```

### 1.2 Dapatkan Credentials
```bash
# Dari Supabase Dashboard > Settings > API
SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
```

### 1.3 Install Supabase Client
```bash
composer require supabase/supabase-php
npm install @supabase/supabase-js
```

---

## 2. ⚙️ Konfigurasi Laravel

### 2.1 Update .env
```env
# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=db.your-project-id.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-database-password

# Supabase Configuration
SUPABASE_URL=https://your-project-id.supabase.co
SUPABASE_ANON_KEY=your-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-service-role-key
```

### 2.2 Buat Supabase Service
```bash
php artisan make:service SupabaseService
```

```php
<?php
// app/Services/SupabaseService.php

namespace App\Services;

use Supabase\CreateClient;

class SupabaseService
{
    private $supabase;

    public function __construct()
    {
        $this->supabase = CreateClient::create(
            config('services.supabase.url'),
            config('services.supabase.anon_key')
        );
    }

    public function getClient()
    {
        return $this->supabase;
    }

    // Dashboard Statistics
    public function getDashboardStats($teacherId)
    {
        $students = $this->supabase
            ->from('students')
            ->select('*')
            ->eq('teacher_id', $teacherId)
            ->execute();

        $totalStudents = count($students->data);

        $progress = $this->supabase
            ->from('student_progress')
            ->select('progress_percentage')
            ->execute();

        $avgProgress = collect($progress->data)->avg('progress_percentage') ?? 0;

        $pendingTasks = $this->supabase
            ->from('assignments')
            ->select('*')
            ->eq('teacher_id', $teacherId)
            ->eq('status', 'pending')
            ->execute();

        return [
            'total_students' => $totalStudents,
            'avg_progress' => round($avgProgress, 1),
            'pending_tasks' => count($pendingTasks->data),
            'engagement_rate' => $this->calculateEngagementRate($teacherId)
        ];
    }

    // Get Classes
    public function getClasses($teacherId)
    {
        return $this->supabase
            ->from('classes')
            ->select('*, students(*)')
            ->eq('teacher_id', $teacherId)
            ->execute();
    }

    // Get Students
    public function getStudents($teacherId, $classId = null)
    {
        $query = $this->supabase
            ->from('students')
            ->select('*, classes(name), student_progress(*)')
            ->eq('teacher_id', $teacherId);

        if ($classId) {
            $query = $query->eq('class_id', $classId);
        }

        return $query->execute();
    }

    // Get Assignments
    public function getAssignments($teacherId)
    {
        return $this->supabase
            ->from('assignments')
            ->select('*, classes(name), student_submissions(*)')
            ->eq('teacher_id', $teacherId)
            ->order('created_at', ['ascending' => false])
            ->execute();
    }

    // Get AI Assessments
    public function getAIAssessments($teacherId)
    {
        return $this->supabase
            ->from('ai_assessments')
            ->select('*, students(name), assignments(title)')
            ->eq('teacher_id', $teacherId)
            ->eq('status', 'pending_review')
            ->execute();
    }

    private function calculateEngagementRate($teacherId)
    {
        // Logic untuk menghitung engagement rate
        // Berdasarkan aktivitas siswa dalam 7 hari terakhir
        $activities = $this->supabase
            ->from('student_activities')
            ->select('*')
            ->eq('teacher_id', $teacherId)
            ->gte('created_at', now()->subDays(7)->toISOString())
            ->execute();

        $totalStudents = $this->supabase
            ->from('students')
            ->select('id')
            ->eq('teacher_id', $teacherId)
            ->execute();

        if (count($totalStudents->data) == 0) return 0;

        $activeStudents = collect($activities->data)
            ->unique('student_id')
            ->count();

        return round(($activeStudents / count($totalStudents->data)) * 100, 1);
    }
}
```

### 2.3 Update config/services.php
```php
<?php
// config/services.php

return [
    // ... existing services

    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'anon_key' => env('SUPABASE_ANON_KEY'),
        'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY'),
    ],
];
```

---

## 3. 🗄️ Database Schema

### 3.1 Buat Tables di Supabase SQL Editor

```sql
-- 1. Classes Table
CREATE TABLE classes (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    teacher_id BIGINT NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    max_capacity INTEGER DEFAULT 30,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 2. Students Table (Update existing)
ALTER TABLE students ADD COLUMN IF NOT EXISTS class_id BIGINT;
ALTER TABLE students ADD COLUMN IF NOT EXISTS teacher_id BIGINT;
ALTER TABLE students ADD COLUMN IF NOT EXISTS nis VARCHAR(50);
ALTER TABLE students ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active';

-- 3. Student Progress Table
CREATE TABLE student_progress (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT NOT NULL,
    module_id BIGINT NOT NULL,
    progress_percentage DECIMAL(5,2) DEFAULT 0,
    completed_at TIMESTAMP WITH TIME ZONE,
    study_time_minutes INTEGER DEFAULT 0,
    xp_earned INTEGER DEFAULT 0,
    streak_days INTEGER DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 4. Assignments Table
CREATE TABLE assignments (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    teacher_id BIGINT NOT NULL,
    class_id BIGINT NOT NULL,
    deadline TIMESTAMP WITH TIME ZONE NOT NULL,
    status VARCHAR(20) DEFAULT 'draft',
    resources JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 5. Student Submissions Table
CREATE TABLE student_submissions (
    id BIGSERIAL PRIMARY KEY,
    assignment_id BIGINT NOT NULL,
    student_id BIGINT NOT NULL,
    content TEXT,
    file_url VARCHAR(500),
    submitted_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    status VARCHAR(20) DEFAULT 'submitted',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 6. AI Assessments Table
CREATE TABLE ai_assessments (
    id BIGSERIAL PRIMARY KEY,
    submission_id BIGINT NOT NULL,
    teacher_id BIGINT NOT NULL,
    ai_score DECIMAL(5,2),
    confidence_score DECIMAL(5,2),
    flagged_issues TEXT[],
    feedback TEXT,
    status VARCHAR(20) DEFAULT 'pending_review',
    reviewed_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 7. Student Activities Table
CREATE TABLE student_activities (
    id BIGSERIAL PRIMARY KEY,
    student_id BIGINT NOT NULL,
    teacher_id BIGINT NOT NULL,
    activity_type VARCHAR(50) NOT NULL,
    description TEXT,
    metadata JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- 8. Modules Table
CREATE TABLE modules (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content JSONB,
    difficulty_level INTEGER DEFAULT 1,
    estimated_duration INTEGER, -- in minutes
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Add Foreign Key Constraints
ALTER TABLE classes ADD CONSTRAINT fk_classes_teacher FOREIGN KEY (teacher_id) REFERENCES users(id);
ALTER TABLE students ADD CONSTRAINT fk_students_class FOREIGN KEY (class_id) REFERENCES classes(id);
ALTER TABLE students ADD CONSTRAINT fk_students_teacher FOREIGN KEY (teacher_id) REFERENCES users(id);
ALTER TABLE student_progress ADD CONSTRAINT fk_progress_student FOREIGN KEY (student_id) REFERENCES students(id);
ALTER TABLE student_progress ADD CONSTRAINT fk_progress_module FOREIGN KEY (module_id) REFERENCES modules(id);
ALTER TABLE assignments ADD CONSTRAINT fk_assignments_teacher FOREIGN KEY (teacher_id) REFERENCES users(id);
ALTER TABLE assignments ADD CONSTRAINT fk_assignments_class FOREIGN KEY (class_id) REFERENCES classes(id);
ALTER TABLE student_submissions ADD CONSTRAINT fk_submissions_assignment FOREIGN KEY (assignment_id) REFERENCES assignments(id);
ALTER TABLE student_submissions ADD CONSTRAINT fk_submissions_student FOREIGN KEY (student_id) REFERENCES students(id);
ALTER TABLE ai_assessments ADD CONSTRAINT fk_assessments_submission FOREIGN KEY (submission_id) REFERENCES student_submissions(id);
ALTER TABLE ai_assessments ADD CONSTRAINT fk_assessments_teacher FOREIGN KEY (teacher_id) REFERENCES users(id);
ALTER TABLE student_activities ADD CONSTRAINT fk_activities_student FOREIGN KEY (student_id) REFERENCES students(id);
ALTER TABLE student_activities ADD CONSTRAINT fk_activities_teacher FOREIGN KEY (teacher_id) REFERENCES users(id);

-- Create Indexes for Performance
CREATE INDEX idx_students_teacher ON students(teacher_id);
CREATE INDEX idx_students_class ON students(class_id);
CREATE INDEX idx_progress_student ON student_progress(student_id);
CREATE INDEX idx_assignments_teacher ON assignments(teacher_id);
CREATE INDEX idx_submissions_assignment ON student_submissions(assignment_id);
CREATE INDEX idx_assessments_teacher ON ai_assessments(teacher_id);
CREATE INDEX idx_activities_teacher ON student_activities(teacher_id);
CREATE INDEX idx_activities_created ON student_activities(created_at);
```

### 3.2 Enable Row Level Security (RLS)
```sql
-- Enable RLS on all tables
ALTER TABLE classes ENABLE ROW LEVEL SECURITY;
ALTER TABLE students ENABLE ROW LEVEL SECURITY;
ALTER TABLE student_progress ENABLE ROW LEVEL SECURITY;
ALTER TABLE assignments ENABLE ROW LEVEL SECURITY;
ALTER TABLE student_submissions ENABLE ROW LEVEL SECURITY;
ALTER TABLE ai_assessments ENABLE ROW LEVEL SECURITY;
ALTER TABLE student_activities ENABLE ROW LEVEL SECURITY;

-- Create RLS Policies
-- Teachers can only see their own data
CREATE POLICY "Teachers can view own classes" ON classes FOR ALL USING (teacher_id = auth.uid());
CREATE POLICY "Teachers can view own students" ON students FOR ALL USING (teacher_id = auth.uid());
CREATE POLICY "Teachers can view own assignments" ON assignments FOR ALL USING (teacher_id = auth.uid());
CREATE POLICY "Teachers can view own assessments" ON ai_assessments FOR ALL USING (teacher_id = auth.uid());
```

---

## 4. 🔐 Authentication Integration

### 4.1 Update RegisteredUserController
```php
<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

use App\Services\SupabaseService;

class RegisteredUserController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'school' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create user in Laravel
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'school_name' => $request->school,
            'password' => Hash::make($request->password),
        ]);

        // Sync user to Supabase
        $this->supabase->getClient()
            ->from('users')
            ->insert([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'school_name' => $user->school_name,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
            ])
            ->execute();

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
```

---

## 5. 📊 Dashboard Functions

### 5.1 Update DashboardController
```php
<?php
// app/Http/Controllers/DashboardController.php

use App\Services\SupabaseService;

class DashboardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function getDashboardStats()
    {
        $teacherId = auth()->id();
        $stats = $this->supabase->getDashboardStats($teacherId);
        
        return response()->json([
            'total_students' => $stats['total_students'],
            'avg_progress' => $stats['avg_progress'],
            'pending_tasks' => $stats['pending_tasks'],
            'engagement_rate' => $stats['engagement_rate'],
            'students_change' => $this->getStudentsChange($teacherId),
            'progress_change' => $this->getProgressChange($teacherId),
            'tasks_change' => $this->getTasksChange($teacherId),
            'engagement_change' => $this->getEngagementChange($teacherId)
        ]);
    }

    public function getClassroomData()
    {
        $teacherId = auth()->id();
        $classes = $this->supabase->getClasses($teacherId);
        $recentStudents = $this->supabase->getStudents($teacherId);

        return response()->json([
            'classes' => $this->formatClassesData($classes->data),
            'recent_students' => $this->formatStudentsData(array_slice($recentStudents->data, 0, 10))
        ]);
    }

    public function getProgressData()
    {
        $teacherId = auth()->id();
        $students = $this->supabase->getStudents($teacherId);

        return response()->json([
            'students' => $this->formatProgressData($students->data)
        ]);
    }

    public function getAssessmentData()
    {
        $teacherId = auth()->id();
        $assessments = $this->supabase->getAIAssessments($teacherId);

        return response()->json([
            'pending_reviews' => count($assessments->data),
            'assessments' => $this->formatAssessmentData($assessments->data)
        ]);
    }

    public function getAssignmentData()
    {
        $teacherId = auth()->id();
        $assignments = $this->supabase->getAssignments($teacherId);

        return response()->json([
            'assignments' => $this->formatAssignmentData($assignments->data)
        ]);
    }

    // Helper methods for data formatting
    private function formatClassesData($classes)
    {
        return collect($classes)->map(function ($class) {
            return [
                'id' => $class['id'],
                'name' => $class['name'],
                'students' => count($class['students'] ?? []),
                'progress' => $this->calculateClassProgress($class['id'])
            ];
        })->toArray();
    }

    private function formatStudentsData($students)
    {
        return collect($students)->map(function ($student) {
            return [
                'id' => $student['id'],
                'name' => $student['name'],
                'nis' => $student['nis'],
                'class' => $student['classes']['name'] ?? 'N/A',
                'joined' => Carbon::parse($student['created_at'])->format('d M Y'),
                'status' => $student['status']
            ];
        })->toArray();
    }

    private function formatProgressData($students)
    {
        return collect($students)->map(function ($student) {
            $progress = $student['student_progress'][0] ?? null;
            return [
                'name' => $student['name'],
                'class' => $student['classes']['name'] ?? 'N/A',
                'modules_completed' => $this->getModulesCompleted($student['id']),
                'study_time' => $this->formatStudyTime($progress['study_time_minutes'] ?? 0),
                'level' => $this->formatLevel($progress['xp_earned'] ?? 0),
                'streak' => '🔥 ' . ($progress['streak_days'] ?? 0) . ' hari',
                'status' => $this->getPerformanceStatus($progress['progress_percentage'] ?? 0)
            ];
        })->toArray();
    }

    // Additional helper methods...
}
```

---

## 6. 🔗 API Endpoints

### 6.1 Buat API Controllers
```bash
php artisan make:controller Api/ClassController
php artisan make:controller Api/StudentController
php artisan make:controller Api/AssignmentController
php artisan make:controller Api/AssessmentController
```

### 6.2 Update routes/api.php
```php
<?php
// routes/api.php

use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AssessmentController;

Route::middleware('auth:sanctum')->group(function () {
    // Classes
    Route::apiResource('classes', ClassController::class);
    Route::post('classes/{class}/students', [ClassController::class, 'addStudent']);
    
    // Students
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/progress', [StudentController::class, 'getProgress']);
    
    // Assignments
    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
    
    // AI Assessments
    Route::get('assessments', [AssessmentController::class, 'index']);
    Route::post('assessments/{assessment}/review', [AssessmentController::class, 'review']);
    Route::post('assessments/{assessment}/approve', [AssessmentController::class, 'approve']);
});
```

---

## 7. ⚡ Real-time Features

### 7.1 Setup Supabase Realtime
```javascript
// resources/js/realtime.js

import { createClient } from '@supabase/supabase-js'

const supabaseUrl = process.env.MIX_SUPABASE_URL
const supabaseAnonKey = process.env.MIX_SUPABASE_ANON_KEY

const supabase = createClient(supabaseUrl, supabaseAnonKey)

// Subscribe to student activities
export function subscribeToActivities(teacherId, callback) {
    return supabase
        .channel('student_activities')
        .on('postgres_changes', {
            event: 'INSERT',
            schema: 'public',
            table: 'student_activities',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Subscribe to assignment submissions
export function subscribeToSubmissions(teacherId, callback) {
    return supabase
        .channel('submissions')
        .on('postgres_changes', {
            event: 'INSERT',
            schema: 'public',
            table: 'student_submissions',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Subscribe to progress updates
export function subscribeToProgress(teacherId, callback) {
    return supabase
        .channel('progress')
        .on('postgres_changes', {
            event: 'UPDATE',
            schema: 'public',
            table: 'student_progress',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}
```

### 7.2 Update Dashboard JavaScript
```javascript
// Add to teacher-dashboard-complete.blade.php

// Real-time subscriptions
let activitySubscription;
let submissionSubscription;
let progressSubscription;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize real-time subscriptions
    initializeRealtime();
    
    // Load initial data
    loadDashboardData();
    loadClassroomData();
});

function initializeRealtime() {
    const teacherId = {{ auth()->id() }};
    
    // Subscribe to activities
    activitySubscription = subscribeToActivities(teacherId, (payload) => {
        addNewActivity(payload.new);
        updateActivityFeed();
    });
    
    // Subscribe to submissions
    submissionSubscription = subscribeToSubmissions(teacherId, (payload) => {
        updatePendingTasks();
        showNotification('New submission received!');
    });
    
    // Subscribe to progress
    progressSubscription = subscribeToProgress(teacherId, (payload) => {
        updateProgressStats();
        updateProgressCharts();
    });
}

function addNewActivity(activity) {
    const activitiesContainer = document.getElementById('recent-activities');
    const activityElement = document.createElement('div');
    activityElement.className = 'message received';
    activityElement.textContent = activity.description;
    activitiesContainer.insertBefore(activityElement, activitiesContainer.firstChild);
}

function showNotification(message) {
    // Create toast notification
    const notification = document.createElement('div');
    notification.className = 'notification toast';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
```

---

## 8. 🧪 Testing

### 8.1 Buat Test Data
```sql
-- Insert test data
INSERT INTO modules (title, description, difficulty_level, estimated_duration) VALUES
('Geometri 3D Dasar', 'Pengenalan konsep geometri 3 dimensi', 1, 60),
('Transformasi Geometri', 'Rotasi, translasi, dan refleksi', 2, 90),
('Bangun Ruang', 'Kubus, balok, prisma, dan limas', 1, 75);

-- Insert test class
INSERT INTO classes (name, teacher_id, academic_year) VALUES
('XII IPA 1', 1, '2025/2026');

-- Insert test students
INSERT INTO students (name, email, class_id, teacher_id, nis) VALUES
('Ahmad Nurul', 'ahmad@student.com', 1, 1, '12345'),
('Siti Putri', 'siti@student.com', 1, 1, '12346');

-- Insert test progress
INSERT INTO student_progress (student_id, module_id, progress_percentage, study_time_minutes, xp_earned, streak_days) VALUES
(1, 1, 85.5, 120, 850, 7),
(2, 1, 72.0, 95, 620, 3);
```

### 8.2 Test API Endpoints
```bash
# Test dashboard stats
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/dashboard/stats

# Test classroom data
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://localhost:8000/api/dashboard/classroom

# Test create class
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"name":"XII IPA 3","academic_year":"2025/2026"}' \
     http://localhost:8000/api/classes
```

---

## 9. 🚀 Deployment

### 9.1 Environment Variables
```env
# Production .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=db.your-project.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-production-password

SUPABASE_URL=https://your-project.supabase.co
SUPABASE_ANON_KEY=your-production-anon-key
SUPABASE_SERVICE_ROLE_KEY=your-production-service-key
```

### 9.2 Build Commands
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run production

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📚 Resources

### Dokumentasi
- [Supabase PHP Client](https://github.com/supabase-community/supabase-php)
- [Supabase JavaScript Client](https://supabase.com/docs/reference/javascript)
- [Laravel Documentation](https://laravel.com/docs)

### Contoh Implementasi
- [Dashboard dengan Real-time](https://github.com/supabase/supabase/tree/master/examples)
- [Laravel + Supabase Auth](https://supabase.com/docs/guides/getting-started/tutorials/with-laravel)

---

## 🎯 Next Steps

1. **Setup Supabase Project** - Buat project dan dapatkan credentials
2. **Install Dependencies** - Install Supabase client dan konfigurasi
3. **Create Database Schema** - Jalankan SQL untuk membuat tables
4. **Implement Authentication** - Integrasikan auth dengan Supabase
5. **Build API Endpoints** - Implementasikan semua API functions
6. **Add Real-time Features** - Setup subscriptions untuk live updates
7. **Test Everything** - Test semua fitur dan API endpoints
8. **Deploy** - Deploy ke production dengan environment yang benar

Ikuti langkah-langkah ini secara berurutan untuk implementasi yang sukses! 🚀
