<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use App\Models\StudentProgress;
use App\Models\Classroom;
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
        $teacher = auth()->user();
        // Use teacher->id directly instead of user_id (which can be NULL)
        $teacherId = $teacher->id;

        // Debug: Log the teacher ID
        \Log::info('Dashboard stats requested for teacher ID: ' . $teacherId);

        $stats = $this->supabase->getDashboardStats($teacherId);

        // Debug: Log the stats data
        \Log::info('Dashboard stats data: ', $stats);

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
        $teacher = auth()->user();
        $teacherId = $teacher->id;
        $classes = $this->supabase->getClasses($teacherId);
        $recentStudents = $this->supabase->getStudents($teacherId);

        return response()->json([
            'classes' => $this->formatClassesData($classes->data),
            'recent_students' => $this->formatStudentsData(array_slice($recentStudents->data, 0, 10))
        ]);
    }

    public function getProgressData()
    {
        $teacher = auth()->user();
        $teacherId = $teacher->id;
        $students = $this->supabase->getStudentsWithProgress($teacherId);

        return response()->json([
            'students' => $this->formatProgressData($students->data)
        ]);
    }

    public function getAssessmentData()
    {
        $teacher = auth()->user();
        $teacherId = $teacher->id;
        $assessments = $this->supabase->getAIAssessments($teacherId);
        $assignments = $this->supabase->getAssignments($teacherId);

        return response()->json([
            'assessments' => $this->formatAssessmentData($assessments->data),
            'assignments' => $this->formatAssignmentData($assignments->data)
        ]);
    }

    public function getAnalyticsData()
    {
        $teacher = auth()->user();
        $teacherId = $teacher->id;

        // Placeholder data - akan diimplementasi sesuai kebutuhan
        return response()->json([
            'stats' => [
                'total_interactions' => 0,
                'avg_session_time' => 0,
                'completion_rate' => 0,
                'satisfaction' => 0
            ],
            'trends' => [
                'interactions_growth' => 0,
                'time_growth' => 0,
                'completion_growth' => 0,
                'satisfaction_growth' => 0
            ]
        ]);
    }

    // Helper methods for data formatting
    private function formatClassesData($classes)
    {
        return collect($classes)->map(function ($class) {
            return [
                'id' => $class['id'],
                'name' => $class['name'] ?? 'Unnamed Class',
                'students' => $this->getStudentCountForClass($class['id']),
                'progress' => $this->calculateClassProgress($class['id'])
            ];
        })->toArray();
    }

    private function formatStudentsData($students)
    {
        return collect($students)->map(function ($student) {
            return [
                'id' => $student['id'],
                'name' => $student['full_name'] ?? 'Unknown',
                'school' => $student['school_name'] ?? 'N/A',
                'class' => $student['grade_level'] ?? 'N/A',
                'joined' => Carbon::parse($student['created_at'])->format('d M Y'),
                'status' => 'active' // Default status since not in profiles table
            ];
        })->toArray();
    }

    private function getStudentCountForClass($classId)
    {
        // Get student count from classroom_members table
        try {
            $members = $this->supabase->makeRequest('GET', 'classroom_members', null, [
                'classroom_id' => 'eq.' . $classId
            ]);
            return $members->successful() ? count($members->json()) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function formatProgressData($students)
    {
        return collect($students)->map(function ($student) {
            $progress = $student['progress'] ?? [];
            return [
                'name' => $student['full_name'] ?? 'Unknown',
                'class' => $student['school_name'] ?? 'N/A',
                'modules_completed' => $this->getModulesCompleted($student['id']),
                'study_time' => $this->formatStudyTime($progress['time_spent_seconds'] ?? 0),
                'level' => $this->formatLevel($progress['quiz_score'] ?? 0),
                'streak' => '🔥 0 hari', // Streak tidak ada di schema Supabase
                'status' => $this->getPerformanceStatus($progress['completion_percentage'] ?? 0)
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
                'student' => $assessment['student_name'] ?? 'N/A',
                'assignment' => $assessment['assignment_title'] ?? 'N/A',
                'ai_score' => $assessment['ai_score'] ?? 0,
                'confidence' => $assessment['confidence_score'] ?? 0,
                'status' => $assessment['status'] ?? 'pending'
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

    private function formatStudyTime($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' detik';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            return $minutes . ' menit';
        } else {
            $hours = floor($seconds / 3600);
            $remainingMinutes = floor(($seconds % 3600) / 60);
            return $hours . 'j ' . $remainingMinutes . 'm';
        }
    }

    private function formatLevel($quizScore)
    {
        // Convert quiz score to level
        if ($quizScore >= 90) return 'Level 5 - Expert';
        if ($quizScore >= 75) return 'Level 4 - Advanced';
        if ($quizScore >= 60) return 'Level 3 - Intermediate';
        if ($quizScore >= 40) return 'Level 2 - Beginner';
        if ($quizScore > 0) return 'Level 1 - Novice';
        return 'Level 0 - Belum ada data';
    }

    private function getPerformanceStatus($percentage)
    {
        if ($percentage >= 90) return 'Excellent';
        if ($percentage >= 75) return 'Good';
        if ($percentage >= 60) return 'Average';
        return 'Needs Improvement';
    }

}
