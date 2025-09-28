<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - GeoCetak</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1a202c;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            padding: 20px 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .logo {
            padding: 0 20px 30px;
            border-bottom: 1px solid #4a5568;
            margin-bottom: 20px;
        }

        .logo h2 {
            font-size: 24px;
            font-weight: 700;
            color: #63b3ed;
        }

        .nav-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item:hover, .nav-item.active {
            background-color: #2d3748;
            border-left-color: #63b3ed;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            opacity: 0.8;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            color: #2d3748;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card.students { border-left-color: #4299e1; }
        .stat-card.progress { border-left-color: #48bb78; }
        .stat-card.assessments { border-left-color: #ed8936; }
        .stat-card.engagement { border-left-color: #9f7aea; }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #718096;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-change {
            font-size: 12px;
            margin-top: 8px;
        }

        .stat-change.positive { color: #48bb78; }
        .stat-change.negative { color: #f56565; }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #4299e1;
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-warning {
            background: #ed8936;
            color: white;
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: #f7fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tr:hover {
            background: #f7fafc;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge.completed { background: #c6f6d5; color: #22543d; }
        .badge.in-progress { background: #fef5e7; color: #744210; }
        .badge.pending { background: #fed7d7; color: #742a2a; }
        .badge.excellent { background: #bee3f8; color: #2a4365; }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #4a5568;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #48bb78, #38a169);
            transition: width 0.3s ease;
        }

        .student-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.2s ease;
        }

        .student-card:hover {
            border-color: #4299e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .student-avatar-large {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            border-radius: 12px;
            padding: 30px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .close {
            font-size: 24px;
            cursor: pointer;
            color: #718096;
        }

        .chat-container {
            height: 400px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 8px;
            max-width: 70%;
        }

        .message.sent {
            background: #4299e1;
            color: white;
            margin-left: auto;
        }

        .message.received {
            background: #f7fafc;
            color: #2d3748;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert.success {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            color: #22543d;
        }

        .alert.warning {
            background: #fffbeb;
            border: 1px solid #fed7aa;
            color: #9c4221;
        }

        .alert.info {
            background: #ebf8ff;
            border: 1px solid #bee3f8;
            color: #2a4365;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h2>GeoCetak</h2>
            <p style="font-size: 12px; color: #a0aec0;">Teacher Dashboard</p>
        </div>
        
        <nav>
            <div class="nav-item active" onclick="showPage('dashboard')">
                <span class="nav-icon">📊</span>
                Dashboard
            </div>
            <div class="nav-item" onclick="showPage('classroom')">
                <span class="nav-icon">👥</span>
                Manajemen Kelas
            </div>
            <div class="nav-item" onclick="showPage('progress')">
                <span class="nav-icon">📈</span>
                Progress Siswa
            </div>
            <div class="nav-item" onclick="showPage('assessment')">
                <span class="nav-icon">📝</span>
                Penilaian AI
            </div>
            <div class="nav-item" onclick="showPage('assignments')">
                <span class="nav-icon">📚</span>
                Tugas & Konten
            </div>
            <div class="nav-item" onclick="showPage('analytics')">
                <span class="nav-icon">📋</span>
                Laporan Analytics
            </div>
            <div class="nav-item" onclick="showPage('settings')">
                <span class="nav-icon">⚙️</span>
                Pengaturan
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div>
                <h1 id="page-title">Dashboard Guru</h1>
                <p style="color: #718096; margin-top: 4px;">Selamat datang kembali, Bu Sari</p>
            </div>
            <div class="user-info">
                <div>
                    <p style="font-weight: 600;">Bu Sari Wijayanti</p>
                    <p style="font-size: 12px; color: #718096;">Guru</p>
                </div>
                <div class="user-avatar">SW</div>
            </div>
        </header>

        <!-- Dashboard Page -->
        <div id="dashboard" class="page active">
            <div class="stats-grid">
                <div class="stat-card students">
                    <div class="stat-value" style="color: #4299e1;">156</div>
                    <div class="stat-label">Total Siswa</div>
                    <div class="stat-change positive">↗ +12 siswa baru</div>
                </div>
                <div class="stat-card progress">
                    <div class="stat-value" style="color: #48bb78;">84%</div>
                    <div class="stat-label">Rata-rata Progress</div>
                    <div class="stat-change positive">↗ +5% minggu ini</div>
                </div>
                <div class="stat-card assessments">
                    <div class="stat-value" style="color: #ed8936;">24</div>
                    <div class="stat-label">Tugas Pending</div>
                    <div class="stat-change negative">↗ Perlu review</div>
                </div>
                <div class="stat-card engagement">
                    <div class="stat-value" style="color: #9f7aea;">92%</div>
                    <div class="stat-label">Tingkat Engagement</div>
                    <div class="stat-change positive">↗ Sangat baik</div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Progress Pembelajaran</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Terbaru</h3>
                    </div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <div class="message received">Ahmad Nurul menyelesaikan "Geometri 3D"</div>
                        <div class="message received">5 siswa mengumpulkan tugas</div>
                        <div class="message received">AI selesai menilai 12 essay</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Chart Area -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Progress Pembelajaran Siswa</h3>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button class="tab-btn active px-4 py-2 text-sm font-medium rounded-md bg-white text-blue-600 shadow-sm" data-period="weekly">
                            Mingguan
                        </button>
                        <button class="tab-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700" data-period="monthly">
                            Bulanan
                        </button>
                        <button class="tab-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700" data-period="yearly">
                            Tahunan
                        </button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>

            <!-- Recent Students -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Siswa Aktif Terbaru</h3>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</button>
                </div>
                <div class="space-y-4">
                    @forelse($recentStudents as $student)
                    <div class="student-item flex items-center p-3 bg-gray-50 rounded-lg transition-transform">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                            {{ $student['initials'] }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $student['name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $student['status'] }}: {{ Str::limit($student['module'], 25) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="w-20 bg-gray-200 rounded-full h-2 mb-1">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $student['progress'] }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500">{{ number_format($student['progress'], 0) }}%</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">📚</div>
                        <p>Belum ada aktivitas siswa hari ini</p>
                        <p class="text-sm">Data akan muncul ketika siswa mulai belajar</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button class="content-tab-btn active px-4 py-2 text-sm font-medium rounded-md bg-white text-blue-600 shadow-sm" data-content="activities">
                        Aktivitas
                    </button>
                    <button class="content-tab-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700" data-content="assessments">
                        Penilaian
                    </button>
                    <button class="content-tab-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700" data-content="assignments">
                        Tugas
                    </button>
                </div>
            </div>

            <!-- Activities Content -->
            <div id="activities-content" class="content-section">
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-center p-4 border-l-4 
                        @if($activity['type'] === 'progress') border-green-200 bg-green-50 @endif
                        @if($activity['type'] === 'assignment') border-yellow-200 bg-yellow-50 @endif
                        @if($activity['type'] === 'assessment') border-pink-200 bg-pink-50 @endif
                        rounded-r-lg">
                        <div class="w-10 h-10 
                            @if($activity['type'] === 'progress') bg-green-100 @endif
                            @if($activity['type'] === 'assignment') bg-yellow-100 @endif
                            @if($activity['type'] === 'assessment') bg-pink-100 @endif
                            rounded-full flex items-center justify-center mr-4 text-lg">
                            {{ $activity['icon'] }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $activity['title'] }}</div>
                            <div class="text-sm text-gray-500">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">🔔</div>
                        <p>Belum ada aktivitas terbaru</p>
                        <p class="text-sm">Aktivitas siswa akan muncul di sini</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Assessments Content -->
            <div id="assessments-content" class="content-section hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor AI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAssessments as $assessment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $assessment['student_name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($assessment['module_title'], 30) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assessment['score'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $assessment['status']['class'] }}">
                                        {{ $assessment['status']['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900">Review</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="text-2xl mb-2">📋</div>
                                    <p>Belum ada penilaian</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Assignments Content -->
            <div id="assignments-content" class="content-section hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignments as $assignment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $assignment['title'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment['class'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment['deadline'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assignment['submitted'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $assignment['status']['class'] }}">
                                        {{ $assignment['status']['label'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="text-2xl mb-2">📝</div>
                                    <p>Belum ada tugas</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data dari controller
        const weeklyData = @json($weeklyData);
        const monthlyData = @json($monthlyData);
        const yearlyData = @json($yearlyData);
        
        // Initialize Chart
        const ctx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: weeklyData.labels,
                datasets: [{
                    label: 'Modul Diselesaikan',
                    data: weeklyData.completedModules,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Waktu Belajar (jam)',
                    data: weeklyData.studyTime,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#E5E7EB'
                        }
                    },
                    x: {
                        grid: {
                            color: '#E5E7EB'
                        }
                    }
                }
            }
        });

        // Tab switching for chart periods
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                    b.classList.add('text-gray-500', 'hover:text-gray-700');
                });
                this.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                this.classList.remove('text-gray-500', 'hover:text-gray-700');
                
                const period = this.dataset.period;
                updateChartData(period);
            });
        });

        // Tab switching for content sections
        document.querySelectorAll('.content-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.content-tab-btn').forEach(b => {
                    b.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                    b.classList.add('text-gray-500', 'hover:text-gray-700');
                });
                this.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                this.classList.remove('text-gray-500', 'hover:text-gray-700');
                
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.add('hidden');
                });
                
                const contentId = this.dataset.content + '-content';
                document.getElementById(contentId).classList.remove('hidden');
            });
        });

        function updateChartData(period) {
            if (period === 'weekly') {
                progressChart.data.labels = weeklyData.labels;
                progressChart.data.datasets[0].data = weeklyData.completedModules;
                progressChart.data.datasets[1].data = weeklyData.studyTime;
            } else if (period === 'monthly') {
                progressChart.data.labels = monthlyData.labels;
                progressChart.data.datasets[0].data = monthlyData.completedModules;
                progressChart.data.datasets[1].data = monthlyData.studyTime;
            } else if (period === 'yearly') {
                progressChart.data.labels = yearlyData.labels;
                progressChart.data.datasets[0].data = yearlyData.completedModules;
                progressChart.data.datasets[1].data = yearlyData.studyTime;
            }
            progressChart.update();
        }

        // Simulate real-time updates
        setInterval(() => {
            const progressBars = document.querySelectorAll('.bg-green-500');
            progressBars.forEach(bar => {
                const currentWidth = parseInt(bar.style.width) || 50;
                const randomChange = Math.random() > 0.5 ? 1 : -1;
                const newWidth = Math.max(0, Math.min(100, currentWidth + randomChange));
                bar.style.width = newWidth + '%';
            });
        }, 5000);
    </script>
</body>
</html>
