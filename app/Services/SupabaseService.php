<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SupabaseService
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SUPABASE_URL'), '/');
        $this->apiKey = env('SUPABASE_ANON_KEY');

        // Use local database if Supabase not configured
        $this->useLocalDb = empty($this->baseUrl) || empty($this->apiKey);
    }

    public function makeRequest($method, $table, $data = null, $filters = [])
    {
        $url = $this->baseUrl . '/rest/v1/' . $table;

        $headers = [
            'apikey' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        // Add filters to URL
        if (!empty($filters)) {
            $queryParams = [];
            foreach ($filters as $key => $value) {
                $queryParams[] = $key . '=' . urlencode($value);
            }
            $url .= '?' . implode('&', $queryParams);
        }

        // Add timeout to prevent hanging requests
        $response = Http::timeout(10)->withHeaders($headers);

        try {
            switch (strtoupper($method)) {
                case 'GET':
                    return $response->get($url);
                case 'POST':
                    return $response->post($url, $data);
                case 'PUT':
                    return $response->put($url, $data);
                case 'PATCH':
                    return $response->patch($url, $data);
                case 'DELETE':
                    return $response->delete($url);
                default:
                    throw new \Exception('Unsupported HTTP method');
            }
        } catch (\Exception $e) {
            \Log::error("Supabase request failed: {$method} {$table} - " . $e->getMessage());
            throw $e;
        }
    }

    // Dashboard Statistics
    public function getDashboardStats($teacherId)
    {
        try {
            \Log::info('Getting dashboard stats for teacher: ' . $teacherId);

            // Initialize default values
            $totalStudents = 0;
            $avgProgress = 0;
            $pendingTasks = 0;
            $engagementRate = 0;

            // Get real data from Supabase with better error handling
            \Log::info('Fetching students...');
            try {
                $studentsResponse = $this->makeRequest('GET', 'profiles', null, [
                    'role' => 'eq.student',
                    'select' => 'id,full_name,created_at'
                ]);
                \Log::info('Students response status: ' . $studentsResponse->status());
                if ($studentsResponse->successful()) {
                    $students = $studentsResponse->json();
                    $totalStudents = count($students);
                    \Log::info('Total students found: ' . $totalStudents);
                } else {
                    \Log::warning('Students fetch failed: ' . $studentsResponse->body());
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching students: ' . $e->getMessage());
            }

            \Log::info('Fetching progress...');
            try {
                $progressResponse = $this->makeRequest('GET', 'student_progress', null, [
                    'select' => 'completion_percentage,time_spent_seconds,updated_at',
                    'limit' => '100'
                ]);
                \Log::info('Progress response status: ' . $progressResponse->status());
                if ($progressResponse->successful()) {
                    $progress = $progressResponse->json();
                    $avgProgress = $progress ? collect($progress)->avg('completion_percentage') : 0;
                    \Log::info('Average progress calculated: ' . $avgProgress);
                } else {
                    \Log::warning('Progress fetch failed: ' . $progressResponse->body());
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching progress: ' . $e->getMessage());
            }

            \Log::info('Fetching assignments...');
            try {
                $assignmentsResponse = $this->makeRequest('GET', 'assignments', null, [
                    'teacher_id' => 'eq.' . $teacherId,
                    'select' => 'id,is_published,created_at'
                ]);
                \Log::info('Assignments response status: ' . $assignmentsResponse->status());
                if ($assignmentsResponse->successful()) {
                    $assignments = $assignmentsResponse->json();
                    $pendingTasks = collect($assignments)->where('is_published', false)->count();
                    \Log::info('Pending tasks calculated: ' . $pendingTasks);
                } else {
                    \Log::warning('Assignments fetch failed: ' . $assignmentsResponse->body());
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching assignments: ' . $e->getMessage());
            }

            \Log::info('Calculating engagement rate...');
            try {
                $engagementRate = $this->calculateEngagementRate($teacherId);
                \Log::info('Engagement rate calculated: ' . $engagementRate);
            } catch (\Exception $e) {
                \Log::error('Error calculating engagement rate: ' . $e->getMessage());
            }

            // Calculate changes - each wrapped in try-catch to prevent failures
            \Log::info('Calculating changes...');
            $studentsChange = $this->getStudentsChange($teacherId);
            $progressChange = $this->getProgressChange($teacherId);
            $tasksChange = $this->getTasksChange($teacherId);
            $engagementChange = $this->getEngagementChange($teacherId);
            \Log::info('Changes calculated');

            $result = [
                'total_students' => $totalStudents,
                'avg_progress' => round($avgProgress, 1),
                'pending_tasks' => $pendingTasks,
                'engagement_rate' => $engagementRate,
                'students_change' => $studentsChange,
                'progress_change' => $progressChange,
                'tasks_change' => $tasksChange,
                'engagement_change' => $engagementChange
            ];

            \Log::info('Dashboard stats result: ' . json_encode($result));
            return $result;

        } catch (\Exception $e) {
            \Log::error('Error getting dashboard stats: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            // Return empty data if fails
            return [
                'total_students' => 0,
                'avg_progress' => 0,
                'pending_tasks' => 0,
                'engagement_rate' => 0,
                'students_change' => 'Error loading data',
                'progress_change' => 'Error loading data',
                'tasks_change' => 'Error loading data',
                'engagement_change' => 'Error loading data'
            ];
        }
    }

    // Get Classes (using classrooms table)
    public function getClasses($teacherId)
    {
        try {
            $response = $this->makeRequest('GET', 'classrooms', null, [
                'teacher_id' => 'eq.' . $teacherId,
                'select' => 'id,name,description,created_at'
            ]);
            return (object) ['data' => $response->successful() ? $response->json() : []];
        } catch (\Exception $e) {
            \Log::error('Error getting classes: ' . $e->getMessage());
            return (object) ['data' => []];
        }
    }

    // Get Students (using profiles table with role='student')
    public function getStudents($teacherId, $classId = null)
    {
        try {
            // Jika ada classId, ambil siswa dari classroom tertentu
            if ($classId) {
                $response = $this->makeRequest('GET', 'classroom_members', null, [
                    'classroom_id' => 'eq.' . $classId,
                    'select' => 'student_id,joined_at,profiles!student_id(id,full_name,email,school_name,grade_level,created_at)'
                ]);
                
                if ($response->successful()) {
                    $members = $response->json();
                    $students = collect($members)->map(function ($member) {
                        $profile = $member['profiles'] ?? [];
                        return array_merge($profile, [
                            'joined_at' => $member['joined_at']
                        ]);
                    })->toArray();
                    
                    return (object) ['data' => $students];
                }
            }
            
            // Jika tidak ada classId, ambil semua siswa
            $filters = [
                'role' => 'eq.student',
                'select' => 'id,full_name,email,school_name,grade_level,created_at'
            ];
            
            $response = $this->makeRequest('GET', 'profiles', null, $filters);
            return (object) ['data' => $response->successful() ? $response->json() : []];
        } catch (\Exception $e) {
            \Log::error('Error getting students: ' . $e->getMessage());
            return (object) ['data' => []];
        }
    }

    // Get Students with Progress (using profiles and student_progress tables)
    public function getStudentsWithProgress($teacherId)
    {
        try {
            // Get students from profiles table
            $studentsResponse = $this->makeRequest('GET', 'profiles', null, [
                'role' => 'eq.student',
                'select' => 'id,full_name,school_name,grade_level'
            ]);
            
            // Get progress data
            $progressResponse = $this->makeRequest('GET', 'student_progress', null, [
                'select' => 'user_id,completion_percentage,time_spent_seconds,updated_at'
            ]);
            
            $students = $studentsResponse->successful() ? $studentsResponse->json() : [];
            $progressData = $progressResponse->successful() ? $progressResponse->json() : [];
            
            // Merge student data with progress
            $studentsWithProgress = collect($students)->map(function ($student) use ($progressData) {
                $progress = collect($progressData)->firstWhere('user_id', $student['id']);
                return array_merge($student, [
                    'progress' => $progress ?: [
                        'completion_percentage' => 0,
                        'time_spent_seconds' => 0,
                        'quiz_score' => 0
                    ]
                ]);
            });
            
            return (object) ['data' => $studentsWithProgress->toArray()];
        } catch (\Exception $e) {
            \Log::error('Error getting students with progress: ' . $e->getMessage());
            return (object) ['data' => []];
        }
    }

    // Get Assignments
    public function getAssignments($teacherId)
    {
        try {
            $response = $this->makeRequest('GET', 'assignments', null, [
                'teacher_id' => 'eq.' . $teacherId,
                'select' => 'id,title,description,deadline,status,classes(name),student_submissions(id,student_id)'
            ]);
            return (object) ['data' => $response->successful() ? $response->json() : []];
        } catch (\Exception $e) {
            return (object) ['data' => []];
        }
    }

    // Get AI Assessments (using assignment_submissions table)
    public function getAIAssessments($teacherId)
    {
        try {
            $response = $this->makeRequest('GET', 'assignment_submissions', null, [
                'select' => 'id,ai_score,confidence_score,status,student_id,assignment_id,created_at'
            ]);
            
            if (!$response->successful()) {
                return (object) ['data' => []];
            }
            
            $submissions = $response->json();
            
            // Get additional data for each submission
            $enrichedSubmissions = collect($submissions)->map(function ($submission) {
                // Get student name
                $studentResponse = $this->makeRequest('GET', 'profiles', null, [
                    'id' => 'eq.' . $submission['student_id'],
                    'select' => 'full_name'
                ]);
                $student = $studentResponse->successful() ? $studentResponse->json()[0] ?? null : null;
                
                // Get assignment title
                $assignmentResponse = $this->makeRequest('GET', 'assignments', null, [
                    'id' => 'eq.' . $submission['assignment_id'],
                    'select' => 'title'
                ]);
                $assignment = $assignmentResponse->successful() ? $assignmentResponse->json()[0] ?? null : null;
                
                return array_merge($submission, [
                    'student_name' => $student['full_name'] ?? 'Unknown',
                    'assignment_title' => $assignment['title'] ?? 'Unknown'
                ]);
            });
            
            return (object) ['data' => $enrichedSubmissions->toArray()];
        } catch (\Exception $e) {
            \Log::error('Error getting AI assessments: ' . $e->getMessage());
            return (object) ['data' => []];
        }
    }
    // Calculate Engagement Rate
    private function calculateEngagementRate($teacherId)
    {
        try {
            // Hitung engagement berdasarkan assignment submissions dengan limit
            $response = $this->makeRequest('GET', 'assignment_submissions', null, [
                'select' => 'id,status,submitted_at',
                'limit' => '100'
            ]);
            if (!$response->successful()) return 0;
            
            $submissions = $response->json();
            $totalSubmissions = count($submissions);
            $submittedCount = collect($submissions)->where('status', 'submitted')->count();
            
            return $totalSubmissions > 0 ? round(($submittedCount / $totalSubmissions) * 100, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    // Get Students Change
    private function getStudentsChange($teacherId)
    {
        try {
            $response = $this->makeRequest('GET', 'profiles', null, [
                'role' => 'eq.student',
                'created_at' => 'gte.' . now()->subWeek()->toISOString()
            ]);
            
            if (!$response->successful()) return "Tidak ada data";
            
            $newStudents = count($response->json());
            return $newStudents > 0 ? "+{$newStudents} siswa baru minggu ini" : "Tidak ada siswa baru minggu ini";
        } catch (\Exception $e) {
            return "Tidak ada data";
        }
    }

    // Get Progress Change
    private function getProgressChange($teacherId)
    {
        try {
            // Get current week progress
            $currentResponse = $this->makeRequest('GET', 'student_progress', null, [
                'updated_at' => 'gte.' . now()->subWeek()->toISOString()
            ]);

            if (!$currentResponse->successful()) return 'Tidak ada perubahan';

            // Fix: Use 'completion_percentage' instead of 'progress_percentage'
            $currentProgress = collect($currentResponse->json())->avg('completion_percentage') ?? 0;

            if ($currentProgress > 0) {
                return '+' . round($currentProgress, 1) . '% minggu ini';
            } else {
                return 'Tidak ada perubahan';
            }
        } catch (\Exception $e) {
            return 'Tidak ada perubahan';
        }
    }

    // Get Tasks Change
    private function getTasksChange($teacherId)
    {
        try {
            // Fix: Use 'is_published' instead of 'status'
            $response = $this->makeRequest('GET', 'assignments', null, [
                'teacher_id' => 'eq.' . $teacherId,
                'is_published' => 'eq.false'
            ]);

            if (!$response->successful()) return 'Tidak ada tugas pending';

            $pendingTasks = count($response->json());
            return $pendingTasks > 0 ? "{$pendingTasks} perlu review" : 'Semua tugas selesai';
        } catch (\Exception $e) {
            return 'Tidak ada tugas pending';
        }
    }

    // Get Engagement Change
    private function getEngagementChange($teacherId)
    {
        try {
            // Get current month engagement
            $currentEngagement = $this->calculateEngagementRate($teacherId);
            
            // Return message based on engagement rate
            if ($currentEngagement > 80) {
                return 'Engagement tinggi';
            } elseif ($currentEngagement > 60) {
                return 'Engagement sedang';
            } elseif ($currentEngagement > 0) {
                return 'Engagement rendah';
            } else {
                return 'Tidak ada aktivitas';
            }
        } catch (\Exception $e) {
            return 'Tidak ada data';
        }
    }

    // CRUD Methods
    public function createClass($data)
    {
        try {
            $response = $this->makeRequest('POST', 'classes', $data);
            if (!$response->successful()) {
                throw new \Exception('Failed to create class: ' . $response->body());
            }
            return (object) ['data' => $response->json()];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createStudent($data)
    {
        try {
            $response = $this->makeRequest('POST', 'students', $data);
            if (!$response->successful()) {
                throw new \Exception('Failed to create student: ' . $response->body());
            }
            return (object) ['data' => $response->json()];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createAssignment($data)
    {
        try {
            $response = $this->makeRequest('POST', 'assignments', $data);
            if (!$response->successful()) {
                throw new \Exception('Failed to create assignment: ' . $response->body());
            }
            return (object) ['data' => $response->json()];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
