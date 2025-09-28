<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Module;
use App\Models\StudentProgress;
use App\Models\ClassSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
        
        // Debug: Log the teacher ID
        \Log::info('Dashboard stats requested for teacher ID: ' . $teacherId);
        
        $stats = $this->supabase->getDashboardStats($teacherId);
        
        return response()->json([
            'total_students' => $stats['total_students'],
            'avg_progress' => $stats['avg_progress'],
            'pending_tasks' => $stats['pending_tasks'],
            'engagement_rate' => $stats['engagement_rate'],
            'students_change' => $stats['students_change'],
            'progress_change' => $stats['progress_change'],
            'tasks_change' => $stats['tasks_change'],
            'engagement_change' => $stats['engagement_change']
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

    public function index()
    {
        return view('teacher-dashboard-complete');
    }

    // Helper methods yang belum diimplementasi
    private function getStudentsChange($teacherId)
    {
        return "Belum ada data";
    }

    private function getProgressChange($teacherId)
    {
        return "Belum ada data";
    }

    private function getTasksChange($teacherId)
    {
        return "Belum ada data";
    }

    private function getEngagementChange($teacherId)
    {
        return "Belum ada data";
    }

    private function formatAssessmentData($assessments)
    {
        return collect($assessments)->map(function ($assessment) {
            return [
                'id' => $assessment['id'],
                'student' => $assessment['students']['name'] ?? 'N/A',
                'assignment' => $assessment['assignments']['title'] ?? 'N/A',
                'ai_score' => $assessment['ai_score'] ?? 0,
                'confidence' => $assessment['confidence_score'] ?? 0,
                'status' => $assessment['status']
            ];
        })->toArray();
    }

    private function formatAssignmentData($assignments)
    {
        return collect($assignments)->map(function ($assignment) {
            return [
                'id' => $assignment['id'],
                'title' => $assignment['title'],
                'class' => $assignment['classes']['name'] ?? 'N/A',
                'deadline' => Carbon::parse($assignment['deadline'])->format('d M Y'),
                'submitted' => count($assignment['student_submissions'] ?? []),
                'status' => $assignment['status']
            ];
        })->toArray();
    }

    private function calculateClassProgress($classId)
    {
        // Placeholder - implementasi sebenarnya akan menggunakan Supabase
        return rand(60, 95);
    }

    private function getModulesCompleted($studentId)
    {
        // Placeholder - implementasi sebenarnya akan menggunakan Supabase
        return rand(5, 15) . '/' . rand(15, 20);
    }

    private function formatStudyTime($minutes)
    {
        $hours = floor($minutes / 60);
        return $hours . '.' . ($minutes % 60) . ' jam';
    }

    private function formatLevel($xp)
    {
        $level = floor($xp / 100) + 1;
        return "Level $level ($xp XP)";
    }

    private function getPerformanceStatus($percentage)
    {
        if ($percentage >= 90) return 'Excellent';
        if ($percentage >= 75) return 'Good';
        if ($percentage >= 60) return 'Average';
        return 'Needs Improvement';
    }

    public function getAnalyticsData()
    {
        $teacherId = auth()->id();
        
        // Get analytics data from Supabase or return dummy data
        return response()->json([
            'stats' => [
                'total_interactions' => 1250,
                'avg_session_time' => 45,
                'completion_rate' => 78,
                'satisfaction' => 4.2
            ],
            'trends' => [
                'interactions_growth' => 15,
                'time_growth' => 8,
                'completion_growth' => 12,
                'satisfaction_growth' => 5
            ]
        ]);
    }
}
