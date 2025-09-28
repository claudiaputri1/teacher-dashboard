<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - GeoCetak</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
            z-index: 1000;
            overflow-y: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Notification animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
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
        }

        /* Notification animations */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Notification toast styles */
        .notification.toast {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            max-width: 300px;
            word-wrap: break-word;
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
                <p style="color: #718096; margin-top: 4px;">Selamat datang kembali, {{ Auth::user()->name }}</p>
            </div>
            <div class="user-info">
                <div>
                    <p style="font-weight: 600;">{{ Auth::user()->name }}</p>
                    <p style="font-size: 12px; color: #718096;">{{ Auth::user()->school_name ?? 'Guru' }}</p>
                </div>
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <form method="POST" action="{{ route('logout') }}" style="margin-left: 15px;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 6px 12px;">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Dashboard Page -->
        <div id="dashboard" class="page active">
            <div class="stats-grid">
                <div class="stat-card students">
                    <div class="stat-value" style="color: #4299e1;" id="total-students">0</div>
                    <div class="stat-label">Total Siswa</div>
                    <div class="stat-change positive" id="students-change">Memuat data...</div>
                </div>
                <div class="stat-card progress">
                    <div class="stat-value" style="color: #48bb78;" id="avg-progress">0%</div>
                    <div class="stat-label">Rata-rata Progress</div>
                    <div class="stat-change positive" id="progress-change">Memuat data...</div>
                </div>
                <div class="stat-card assessments">
                    <div class="stat-value" style="color: #ed8936;" id="pending-tasks">0</div>
                    <div class="stat-label">Tugas Pending</div>
                    <div class="stat-change negative" id="tasks-change">Memuat data...</div>
                </div>
                <div class="stat-card engagement">
                    <div class="stat-value" style="color: #9f7aea;" id="engagement-rate">0%</div>
                    <div class="stat-label">Tingkat Engagement</div>
                    <div class="stat-change positive" id="engagement-change">Memuat data...</div>
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
                    <div id="recent-activities" style="max-height: 300px; overflow-y: auto;">
                        <div style="text-align: center; padding: 40px; color: #718096;">
                            <div style="font-size: 48px; margin-bottom: 16px;">📊</div>
                            <p>Belum ada aktivitas</p>
                            <p style="font-size: 12px;">Data akan muncul setelah siswa mulai belajar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classroom Management Page -->
        <div id="classroom" class="page">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manajemen Kelas</h3>
                    <button class="btn btn-primary" onclick="openModal('createClass')">+ Buat Kelas Baru</button>
                </div>
                
                <div id="classes-grid" class="grid-3">
                    <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                        <div style="font-size: 48px; margin-bottom: 16px;">🏫</div>
                        <p>Belum ada kelas</p>
                        <p style="font-size: 12px;">Klik "Buat Kelas Baru" untuk memulai</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Siswa Terbaru</h3>
                    <button class="btn btn-success" onclick="openModal('addStudent')">+ Tambah Siswa</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Tanggal Bergabung</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="students-table">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">👥</div>
                                <p>Belum ada siswa</p>
                                <p style="font-size: 12px;">Tambahkan siswa untuk memulai pembelajaran</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Progress Page -->
        <div id="progress" class="page">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progress Siswa</h3>
                    <div>
                        <select class="form-control" style="width: auto; display: inline-block;">
                            <option>Semua Kelas</option>
                            <option>XII IPA 1</option>
                            <option>XII IPA 2</option>
                        </select>
                    </div>
                </div>
                
                <div class="analytics-grid">
                    <div class="card">
                        <h4>Progress Heatmap</h4>
                        <div class="chart-container">
                            <canvas id="heatmapChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="card">
                        <h4>Engagement Metrics</h4>
                        <div class="chart-container">
                            <canvas id="engagementChart"></canvas>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Modul Selesai</th>
                            <th>Waktu Belajar</th>
                            <th>Level XP</th>
                            <th>Streak</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmad Nurul</td>
                            <td>XII IPA 1</td>
                            <td>15/18</td>
                            <td>24.5 jam</td>
                            <td>Level 8 (850 XP)</td>
                            <td>🔥 7 hari</td>
                            <td><span class="badge excellent">Excellent</span></td>
                        </tr>
                        <tr>
                            <td>Siti Putri</td>
                            <td>XII IPA 2</td>
                            <td>12/18</td>
                            <td>18.2 jam</td>
                            <td>Level 6 (620 XP)</td>
                            <td>🔥 3 hari</td>
                            <td><span class="badge in-progress">Good</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AI Assessment Page -->
        <div id="assessment" class="page">
            <div class="alert info">
                <strong>Info:</strong> Ada 24 tugas yang perlu direview dari penilaian AI.
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Review Penilaian AI</h3>
                    <button class="btn btn-primary">Review Semua</button>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tugas</th>
                            <th>Skor AI</th>
                            <th>Confidence</th>
                            <th>Flagged Issues</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmad Nurul</td>
                            <td>Essay Geometri 3D</td>
                            <td>85/100</td>
                            <td>95%</td>
                            <td>-</td>
                            <td><span class="badge completed">Approved</span></td>
                            <td><button class="btn btn-secondary">Review</button></td>
                        </tr>
                        <tr>
                            <td>Siti Putri</td>
                            <td>Analisis Bangun Ruang</td>
                            <td>72/100</td>
                            <td>78%</td>
                            <td>Unclear reasoning</td>
                            <td><span class="badge pending">Pending</span></td>
                            <td><button class="btn btn-warning">Review</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assignments Page -->
        <div id="assignments" class="page">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manajemen Tugas</h3>
                    <button class="btn btn-primary">+ Buat Tugas Baru</button>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul Tugas</th>
                            <th>Kelas</th>
                            <th>Deadline</th>
                            <th>Submitted</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kreasi Model 3D Prisma</td>
                            <td>XII IPA 1</td>
                            <td>30 Sep 2025</td>
                            <td>28/32</td>
                            <td><span class="badge in-progress">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Analisis Transformasi</td>
                            <td>XII IPA 2</td>
                            <td>2 Okt 2025</td>
                            <td>30/30</td>
                            <td><span class="badge completed">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Analytics Page -->
        <div id="analytics" class="page">
            <div class="stats-grid">
                <div class="stat-card students">
                    <div class="stat-value" style="color: #4299e1;">2,345</div>
                    <div class="stat-label">Total Interaksi 3D</div>
                    <div class="stat-change positive">↗ +15% bulan ini</div>
                </div>
                <div class="stat-card progress">
                    <div class="stat-value" style="color: #48bb78;">18.5</div>
                    <div class="stat-label">Rata-rata Waktu Session</div>
                    <div class="stat-change positive">↗ +2.3 menit</div>
                </div>
            </div>
        </div>

        <!-- Settings Page -->
        <div id="settings" class="page">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profil Guru</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asal Sekolah</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->school_name ?? 'Belum diisi' }}">
                    </div>
                    <button class="btn btn-primary">Update Profil</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Preferensi Dashboard</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" checked> Notifikasi Email
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" checked> Auto-approve AI Scores > 90%
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox"> Dark Mode
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bahasa</label>
                        <select class="form-control">
                            <option>Indonesia</option>
                            <option>English</option>
                        </select>
                    </div>
                    <button class="btn btn-success">Simpan Preferensi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="createClass" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buat Kelas Baru</h3>
                <span class="close" onclick="closeModal('createClass')">&times;</span>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Kelas</label>
                <input type="text" class="form-control" placeholder="contoh: XII IPA 3">
            </div>
            <div class="form-group">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control" placeholder="2025/2026">
            </div>
            <button class="btn btn-primary">Buat Kelas</button>
        </div>
    </div>

    <div id="addStudent" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Siswa</h3>
                <span class="close" onclick="closeModal('addStudent')">&times;</span>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Siswa</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">NIS</label>
                <input type="text" class="form-control">
            </div>
            <button class="btn btn-success">Tambah Siswa</button>
        </div>
    </div>

    <script>
        // Page Navigation
        function showPage(pageId) {
            // Hide all pages
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Remove active from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected page
            document.getElementById(pageId).classList.add('active');
            
            // Add active to clicked nav item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard Guru',
                'classroom': 'Manajemen Kelas',
                'progress': 'Progress Siswa',
                'assessment': 'Penilaian AI',
                'assignments': 'Tugas & Konten',
                'analytics': 'Laporan Analytics',
                'settings': 'Pengaturan'
            };
            
            document.getElementById('page-title').textContent = titles[pageId] || 'Dashboard Guru';
        }

        // Modal Management
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Load Dashboard Data
        async function loadDashboardData() {
            try {
                const response = await fetch('/api/dashboard/stats');
                const data = await response.json();
                
                // Update statistics
                document.getElementById('total-students').textContent = data.total_students;
                document.getElementById('avg-progress').textContent = data.avg_progress + '%';
                document.getElementById('pending-tasks').textContent = data.pending_tasks;
                document.getElementById('engagement-rate').textContent = data.engagement_rate + '%';
                
                // Update change indicators
                document.getElementById('students-change').textContent = data.students_change;
                document.getElementById('progress-change').textContent = data.progress_change;
                document.getElementById('tasks-change').textContent = data.tasks_change;
                document.getElementById('engagement-change').textContent = data.engagement_change;
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Load Classroom Data
        async function loadClassroomData() {
            try {
                const response = await fetch('/api/dashboard/classroom');
                const data = await response.json();
                
                const classesGrid = document.getElementById('classes-grid');
                const studentsTable = document.getElementById('students-table');
                
                if (data.classes.length === 0) {
                    classesGrid.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                            <div style="font-size: 48px; margin-bottom: 16px;">🏫</div>
                            <p>Belum ada kelas</p>
                            <p style="font-size: 12px;">Klik "Buat Kelas Baru" untuk memulai</p>
                        </div>
                    `;
                } else {
                    // TODO: Render classes when data is available
                }
                
                if (data.recent_students.length === 0) {
                    studentsTable.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">👥</div>
                                <p>Belum ada siswa</p>
                                <p style="font-size: 12px;">Tambahkan siswa untuk memulai pembelajaran</p>
                            </td>
                        </tr>
                    `;
                } else {
                    // TODO: Render students when data is available
                }
            } catch (error) {
                console.error('Error loading classroom data:', error);
            }
        }

        // Real-time subscriptions
        let activitySubscription;
        let submissionSubscription;
        let progressSubscription;
        let assessmentSubscription;
        let studentSubscription;

        function initializeRealtime() {
            const teacherId = {{ auth()->id() }};
            
            // Subscribe to activities
            if (typeof subscribeToActivities !== 'undefined') {
                activitySubscription = subscribeToActivities(teacherId, (payload) => {
                    addNewActivity(payload.new);
                    updateActivityFeed();
                });
            }
            
            // Subscribe to submissions
            if (typeof subscribeToSubmissions !== 'undefined') {
                submissionSubscription = subscribeToSubmissions(teacherId, (payload) => {
                    updatePendingTasks();
                    showNotification('New submission received!');
                });
            }
            
            // Subscribe to progress
            if (typeof subscribeToProgress !== 'undefined') {
                progressSubscription = subscribeToProgress(teacherId, (payload) => {
                    updateProgressStats();
                    updateProgressCharts();
                });
            }

            // Subscribe to assessments
            if (typeof subscribeToAssessments !== 'undefined') {
                assessmentSubscription = subscribeToAssessments(teacherId, (payload) => {
                    updateAssessmentData();
                    if (payload.eventType === 'INSERT') {
                        showNotification('New AI assessment completed!');
                    }
                });
            }

            // Subscribe to new students
            if (typeof subscribeToStudents !== 'undefined') {
                studentSubscription = subscribeToStudents(teacherId, (payload) => {
                    updateStudentCount();
                    loadClassroomData(); // Reload classroom data
                    showNotification(`New student joined: ${payload.new.name}`);
                });
            }
        }

        function addNewActivity(activity) {
            const activitiesContainer = document.getElementById('recent-activities');
            if (activitiesContainer) {
                // Remove empty state if exists
                const emptyState = activitiesContainer.querySelector('[style*="text-align: center"]');
                if (emptyState) {
                    emptyState.remove();
                }

                const activityElement = document.createElement('div');
                activityElement.className = 'message received';
                activityElement.textContent = activity.description;
                activitiesContainer.insertBefore(activityElement, activitiesContainer.firstChild);

                // Keep only last 10 activities
                const activities = activitiesContainer.querySelectorAll('.message');
                if (activities.length > 10) {
                    activities[activities.length - 1].remove();
                }
            }
        }

        function updateActivityFeed() {
            // Refresh activity feed
            loadDashboardData();
        }

        function updatePendingTasks() {
            // Update pending tasks counter
            loadDashboardData();
        }

        function updateProgressStats() {
            // Update progress statistics
            loadDashboardData();
        }

        function updateProgressCharts() {
            // Update progress charts
            // TODO: Implement chart updates
        }

        function updateAssessmentData() {
            // Update assessment data
            loadDashboardData();
        }

        function updateStudentCount() {
            // Update student count
            loadDashboardData();
        }

        function showNotification(message) {
            // Create toast notification
            const notification = document.createElement('div');
            notification.className = 'notification toast';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
                z-index: 10000;
                font-weight: 500;
                animation: slideIn 0.3s ease-out;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Cleanup subscriptions when page unloads
        window.addEventListener('beforeunload', function() {
            if (typeof unsubscribeAll !== 'undefined') {
                unsubscribeAll();
            }
        });

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data
            loadDashboardData();
            loadClassroomData();
            
            // Initialize real-time subscriptions
            initializeRealtime();
            // Progress Chart
            const progressCtx = document.getElementById('progressChart');
            if (progressCtx) {
                new Chart(progressCtx, {
                    type: 'line',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Modul Diselesaikan',
                            data: [12, 19, 15, 25, 22, 30, 28],
                            borderColor: '#4299e1',
                            backgroundColor: 'rgba(66, 153, 225, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                            x: { grid: { color: '#e2e8f0' } }
                        }
                    }
                });
            }

            // Heatmap Chart
            const heatmapCtx = document.getElementById('heatmapChart');
            if (heatmapCtx) {
                new Chart(heatmapCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Geometri 3D', 'Transformasi', 'Koordinat', 'Bangun Ruang'],
                        datasets: [{
                            label: 'Kesulitan Rata-rata',
                            data: [3.2, 4.1, 2.8, 3.7],
                            backgroundColor: ['#48bb78', '#ed8936', '#4299e1', '#9f7aea']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // Engagement Chart
            const engagementCtx = document.getElementById('engagementChart');
            if (engagementCtx) {
                new Chart(engagementCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['High Engagement', 'Medium', 'Low'],
                        datasets: [{
                            data: [60, 30, 10],
                            backgroundColor: ['#48bb78', '#ed8936', '#f56565']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        });

        // Simulate real-time updates
        setInterval(() => {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const currentWidth = parseInt(bar.style.width);
                if (Math.random() > 0.8) {
                    const newWidth = Math.max(0, Math.min(100, currentWidth + (Math.random() > 0.5 ? 1 : -1)));
                    bar.style.width = newWidth + '%';
                }
            });
        }, 5000);
    </script>
</body>
</html>
