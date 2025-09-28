<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Dashboard - GeoCetak</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                <span class="nav-icon">🤖</span>
                Penilaian AI
            </div>
            <div class="nav-item" onclick="showPage('assignments')">
                <span class="nav-icon">📝</span>
                Tugas & Konten
            </div>
            <div class="nav-item" onclick="showPage('analytics')">
                <span class="nav-icon">📊</span>
                Analytics
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

        <!-- Real-time Status Indicator -->
        <div id="realtime-indicator" style="position: fixed; top: 80px; right: 20px; background: #48bb78; color: white; padding: 8px 12px; border-radius: 20px; font-size: 12px; z-index: 1001; display: none;">
            <span id="realtime-status">🔄 Real-time aktif</span>
        </div>

        <!-- Dashboard Page -->
        <div id="dashboard" class="page active">
            <div class="stats-grid">
                <div class="stat-card students">
                    <div class="stat-value" style="color: #4299e1;" id="total-students">3</div>
                    <div class="stat-label">Total Siswa</div>
                    <div class="stat-change positive" id="students-change">+2 siswa baru</div>
                </div>
                <div class="stat-card progress">
                    <div class="stat-value" style="color: #48bb78;" id="avg-progress">84.7%</div>
                    <div class="stat-label">Rata-rata Progress</div>
                    <div class="stat-change positive" id="progress-change">+12% minggu ini</div>
                </div>
                <div class="stat-card assessments">
                    <div class="stat-value" style="color: #ed8936;" id="pending-tasks">2</div>
                    <div class="stat-label">Tugas Pending</div>
                    <div class="stat-change negative" id="tasks-change">2 perlu review</div>
                </div>
                <div class="stat-card engagement">
                    <div class="stat-value" style="color: #9f7aea;" id="engagement-rate">87.5%</div>
                    <div class="stat-label">Tingkat Engagement</div>
                    <div class="stat-change positive" id="engagement-change">Engagement tinggi</div>
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

        <!-- Other pages would go here... -->
        <div id="progress" class="page">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progress Siswa</h3>
                    <div>
                        <select class="form-control" style="width: 200px;" onchange="filterProgressByClass(this.value)">
                            <option value="">Semua Kelas</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid-3" id="progress-grid">
                    <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                        <div style="font-size: 48px; margin-bottom: 16px;">📈</div>
                        <p>Belum ada data progress siswa</p>
                        <p style="font-size: 12px;">Data akan muncul setelah siswa mulai belajar</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Progress Kelas</h3>
                </div>
                <div class="chart-container">
                    <canvas id="classProgressChart"></canvas>
                </div>
            </div>
        </div>

        <div id="assessment" class="page">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penilaian AI - Review Pending</h3>
                    <div class="badge" style="background: #ed8936; color: white;" id="pending-count">0</div>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tugas</th>
                            <th>AI Score</th>
                            <th>Confidence</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="assessments-table">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">🤖</div>
                                <p>Belum ada penilaian AI pending</p>
                                <p style="font-size: 12px;">Penilaian akan muncul setelah siswa submit tugas</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Penilaian AI</h3>
                </div>
                <div class="grid-2">
                    <div class="chart-container">
                        <canvas id="aiScoreChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <canvas id="confidenceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div id="assignments" class="page">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tugas & Konten</h3>
                    <button class="btn btn-primary" onclick="openModal('createAssignment')">+ Buat Tugas Baru</button>
                </div>
                
                <div class="grid-2">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Tugas Aktif</h4>
                        </div>
                        <div id="active-assignments">
                            <div style="text-align: center; padding: 20px; color: #718096;">
                                <p>Belum ada tugas aktif</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Submission Terbaru</h4>
                        </div>
                        <div id="recent-submissions">
                            <div style="text-align: center; padding: 20px; color: #718096;">
                                <p>Belum ada submission</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Semua Tugas</h3>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kelas</th>
                            <th>Deadline</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="assignments-table">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">📝</div>
                                <p>Belum ada tugas</p>
                                <p style="font-size: 12px;">Klik "Buat Tugas Baru" untuk memulai</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="analytics" class="page">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" style="color: #4299e1;" id="total-interactions">0</div>
                    <div class="stat-label">Total Interaksi</div>
                    <div class="stat-change positive" id="interactions-growth">+0%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #48bb78;" id="avg-session-time">0 min</div>
                    <div class="stat-label">Rata-rata Sesi</div>
                    <div class="stat-change positive" id="session-growth">+0%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #ed8936;" id="completion-rate">0%</div>
                    <div class="stat-label">Tingkat Penyelesaian</div>
                    <div class="stat-change positive" id="completion-growth">+0%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" style="color: #9f7aea;" id="satisfaction-score">0.0</div>
                    <div class="stat-label">Skor Kepuasan</div>
                    <div class="stat-change positive" id="satisfaction-growth">+0%</div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tren Pembelajaran</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="learningTrendChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribusi Aktivitas</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="activityDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Performa per Modul</h3>
                </div>
                <div class="chart-container">
                    <canvas id="modulePerformanceChart"></canvas>
                </div>
            </div>
        </div>

        <div id="settings" class="page">
            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profil Guru</h3>
                    </div>
                    <form id="profile-form">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" id="profile-name" class="form-control" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" id="profile-email" class="form-control" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Asal Sekolah</label>
                            <input type="text" id="profile-school" class="form-control" value="{{ Auth::user()->school_name ?? 'Belum diisi' }}">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="updateProfile()">Update Profil</button>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Preferensi Dashboard</h3>
                    </div>
                    <form id="preferences-form">
                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" id="email-notifications" checked onchange="toggleNotifications(this)"> Notifikasi Email
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" id="auto-approve" checked onchange="toggleAutoApprove(this)"> Auto-approve AI Scores > 90%
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" id="dark-mode" onchange="toggleDarkMode(this)"> Dark Mode
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bahasa</label>
                            <select class="form-control" id="language-select" onchange="changeLanguage(this.value)">
                                <option value="id">Indonesia</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-success" onclick="savePreferences()">Simpan Preferensi</button>
                    </form>
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
                <input type="text" class="form-control" placeholder="Contoh: XII IPA 1">
            </div>
            <div class="form-group">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control" placeholder="2025/2026">
            </div>
            <div class="form-group">
                <label class="form-label">Kapasitas Maksimal</label>
                <input type="number" class="form-control" placeholder="30" value="30">
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
                <input type="text" id="student-name" class="form-control" placeholder="Nama lengkap siswa" required>
            </div>
            <div class="form-group">
                <label class="form-label">NIS</label>
                <input type="text" id="student-nis" class="form-control" placeholder="Nomor Induk Siswa">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="student-email" class="form-control" placeholder="email@siswa.com">
            </div>
            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select id="student-class" class="form-control">
                    <option value="">Pilih Kelas</option>
                </select>
            </div>
            <button type="button" class="btn btn-success" onclick="addStudent()">Tambah Siswa</button>
        </div>
    </div>

    <div id="createAssignment" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buat Tugas Baru</h3>
                <span class="close" onclick="closeModal('createAssignment')">&times;</span>
            </div>
            <div class="form-group">
                <label class="form-label">Judul Tugas</label>
                <input type="text" class="form-control" placeholder="Contoh: Latihan Geometri 3D">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" rows="3" placeholder="Deskripsi tugas..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select class="form-control">
                    <option>Pilih Kelas</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Deadline</label>
                <input type="datetime-local" class="form-control">
            </div>
            <button class="btn btn-primary">Buat Tugas</button>
        </div>
    </div>

    <script>
        // Page Navigation
        function showPage(pageId) {
            // Hide all pages
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected page
            document.getElementById(pageId).classList.add('active');
            
            // Add active class to clicked nav item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard Guru',
                'classroom': 'Manajemen Kelas',
                'progress': 'Progress Siswa',
                'assessment': 'Penilaian AI',
                'assignments': 'Tugas & Konten',
                'analytics': 'Analytics',
                'settings': 'Pengaturan'
            };
            
            document.getElementById('page-title').textContent = titles[pageId] || 'Dashboard Guru';
        }

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            
            // Load classes when opening add student modal
            if (modalId === 'addStudent') {
                loadClassesForDropdown();
            }
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

        // Load Dashboard Data (optional - data is already displayed)
        let dashboardDataLoaded = false;
        async function loadDashboardData() {
            if (dashboardDataLoaded) return;
            
            try {
                const response = await fetch('/api/dashboard/stats');
                const data = await response.json();
                
                // Update statistics (optional since data is already shown)
                if (data.total_students !== undefined) {
                    document.getElementById('total-students').textContent = data.total_students;
                    document.getElementById('avg-progress').textContent = data.avg_progress + '%';
                    document.getElementById('pending-tasks').textContent = data.pending_tasks;
                    document.getElementById('engagement-rate').textContent = data.engagement_rate + '%';
                    
                    // Update change indicators
                    document.getElementById('students-change').textContent = data.students_change;
                    document.getElementById('progress-change').textContent = data.progress_change;
                    document.getElementById('tasks-change').textContent = data.tasks_change;
                    document.getElementById('engagement-change').textContent = data.engagement_change;
                }
                
                dashboardDataLoaded = true;
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                dashboardDataLoaded = true; // Prevent retry loops
            }
        }

        // Load Classroom Data (only once)
        let classroomDataLoaded = false;
        async function loadClassroomData() {
            if (classroomDataLoaded) return;
            
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
                
                classroomDataLoaded = true;
            } catch (error) {
                console.error('Error loading classroom data:', error);
                classroomDataLoaded = true; // Prevent retry loops
            }
        }

        // Load Progress Data
        async function loadProgressData() {
            try {
                const response = await fetch('/api/dashboard/progress');
                const data = await response.json();
                
                const progressGrid = document.getElementById('progress-grid');
                
                if (data.students.length === 0) {
                    progressGrid.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                            <div style="font-size: 48px; margin-bottom: 16px;">📈</div>
                            <p>Belum ada data progress</p>
                            <p style="font-size: 12px;">Data akan muncul setelah siswa mulai belajar</p>
                        </div>
                    `;
                } else {
                    progressGrid.innerHTML = data.students.map(student => `
                        <div class="student-card">
                            <div class="student-avatar-large">${student.name.substring(0, 2).toUpperCase()}</div>
                            <h4 style="text-align: center; margin-bottom: 10px;">${student.name}</h4>
                            <p style="text-align: center; color: #718096; font-size: 12px;">${student.class}</p>
                            <div style="margin: 15px 0;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span>Modul:</span>
                                    <span>${student.modules_completed}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span>Waktu:</span>
                                    <span>${student.study_time}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span>Level:</span>
                                    <span>${student.level}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <span>Streak:</span>
                                    <span>${student.streak}</span>
                                </div>
                                <div class="badge ${student.status.toLowerCase().replace(' ', '-')}">${student.status}</div>
                            </div>
                        </div>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading progress data:', error);
            }
        }

        // Load Assessment Data
        async function loadAssessmentData() {
            try {
                const response = await fetch('/api/dashboard/assessment');
                const data = await response.json();
                
                document.getElementById('pending-count').textContent = data.pending_reviews;
                
                const assessmentsTable = document.getElementById('assessments-table');
                
                if (data.assessments.length === 0) {
                    assessmentsTable.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">🤖</div>
                                <p>Belum ada penilaian AI pending</p>
                                <p style="font-size: 12px;">Penilaian akan muncul setelah siswa submit tugas</p>
                            </td>
                        </tr>
                    `;
                } else {
                    assessmentsTable.innerHTML = data.assessments.map(assessment => `
                        <tr>
                            <td>${assessment.student}</td>
                            <td>${assessment.assignment}</td>
                            <td><span class="badge ${assessment.ai_score >= 80 ? 'excellent' : 'pending'}">${assessment.ai_score}%</span></td>
                            <td>${assessment.confidence}%</td>
                            <td><span class="badge ${assessment.status}">${assessment.status}</span></td>
                            <td>
                                <button class="btn btn-success" style="font-size: 12px; padding: 4px 8px;">Review</button>
                                <button class="btn btn-primary" style="font-size: 12px; padding: 4px 8px;">Approve</button>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading assessment data:', error);
            }
        }

        // Load Assignment Data
        async function loadAssignmentData() {
            try {
                const response = await fetch('/api/dashboard/assignments');
                const data = await response.json();
                
                const assignmentsTable = document.getElementById('assignments-table');
                const activeAssignments = document.getElementById('active-assignments');
                const recentSubmissions = document.getElementById('recent-submissions');
                
                if (data.assignments.length === 0) {
                    assignmentsTable.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">📝</div>
                                <p>Belum ada tugas</p>
                                <p style="font-size: 12px;">Klik "Buat Tugas Baru" untuk memulai</p>
                            </td>
                        </tr>
                    `;
                    
                    activeAssignments.innerHTML = `
                        <div style="text-align: center; padding: 20px; color: #718096;">
                            <p>Belum ada tugas aktif</p>
                        </div>
                    `;
                    
                    recentSubmissions.innerHTML = `
                        <div style="text-align: center; padding: 20px; color: #718096;">
                            <p>Belum ada submission</p>
                        </div>
                    `;
                } else {
                    assignmentsTable.innerHTML = data.assignments.map(assignment => `
                        <tr>
                            <td>${assignment.title}</td>
                            <td>${assignment.class}</td>
                            <td>${assignment.deadline}</td>
                            <td>${assignment.submitted}</td>
                            <td><span class="badge ${assignment.status}">${assignment.status}</span></td>
                            <td>
                                <button class="btn btn-primary" style="font-size: 12px; padding: 4px 8px;">Edit</button>
                                <button class="btn btn-danger" style="font-size: 12px; padding: 4px 8px;">Delete</button>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading assignment data:', error);
            }
        }

        // Load Analytics Data
        async function loadAnalyticsData() {
            try {
                const response = await fetch('/api/dashboard/analytics');
                const data = await response.json();
                
                // Update analytics stats
                document.getElementById('total-interactions').textContent = data.stats.total_interactions || 0;
                document.getElementById('avg-session-time').textContent = (data.stats.avg_session_time || 0) + ' min';
                document.getElementById('completion-rate').textContent = (data.stats.completion_rate || 0) + '%';
                document.getElementById('satisfaction-score').textContent = (data.stats.satisfaction || 0).toFixed(1);
                
                // Update growth indicators
                document.getElementById('interactions-growth').textContent = '+' + (data.trends.interactions_growth || 0) + '%';
                document.getElementById('session-growth').textContent = '+' + (data.trends.time_growth || 0) + '%';
                document.getElementById('completion-growth').textContent = '+' + (data.trends.completion_growth || 0) + '%';
                document.getElementById('satisfaction-growth').textContent = '+' + (data.trends.satisfaction_growth || 0) + '%';
                
            } catch (error) {
                console.error('Error loading analytics data:', error);
            }
        }

        // Filter Progress by Class
        function filterProgressByClass(classId) {
            // TODO: Implement class filtering
            loadProgressData();
        }

        // Test function to update UI (for debugging)
        function testUpdateUI() {
            const testName = 'Test User';
            const testSchool = 'Test School';
            
            // Update welcome message
            const welcomeElement = document.querySelector('.header p');
            if (welcomeElement) {
                welcomeElement.textContent = `Selamat datang kembali, ${testName}`;
            }
            
            // Update user name
            const userNameElement = document.querySelector('.user-info div p:first-child');
            if (userNameElement) {
                userNameElement.textContent = testName;
            }
            
            // Update school name
            const schoolElement = document.querySelector('.user-info div p:last-child');
            if (schoolElement) {
                schoolElement.textContent = testSchool;
            }
            
            // Update avatar
            const avatarElement = document.querySelector('.user-avatar');
            if (avatarElement) {
                avatarElement.textContent = testName.substring(0, 2).toUpperCase();
            }
            
            showNotification('UI Test Update berhasil!', 'success');
        }

        // Real-time data refresh intervals
        let dashboardInterval;
        let progressInterval;
        let assessmentInterval;
        let assignmentInterval;
        let analyticsInterval;

        // Start real-time updates for current page
        function startRealTimeUpdates(pageId) {
            // Clear existing intervals
            stopRealTimeUpdates();
            
            // Show real-time indicator
            const indicator = document.getElementById('realtime-indicator');
            const status = document.getElementById('realtime-status');
            
            switch(pageId) {
                case 'dashboard':
                    // Refresh dashboard stats every 30 seconds
                    dashboardInterval = setInterval(() => {
                        console.log('Auto-refreshing dashboard stats...');
                        dashboardDataLoaded = false;
                        loadDashboardData();
                    }, 30000);
                    break;
                    
                case 'progress':
                    // Refresh progress data every 60 seconds
                    progressInterval = setInterval(() => {
                        console.log('Auto-refreshing progress data...');
                        loadProgressData();
                    }, 60000);
                    break;
                    
                case 'assessment':
                    // Refresh assessment data every 45 seconds
                    assessmentInterval = setInterval(() => {
                        console.log('Auto-refreshing assessment data...');
                        loadAssessmentData();
                    }, 45000);
                    break;
                    
                case 'assignments':
                    // Refresh assignment data every 60 seconds
                    assignmentInterval = setInterval(() => {
                        console.log('Auto-refreshing assignment data...');
                        loadAssignmentData();
                    }, 60000);
                    break;
                    
                case 'analytics':
                    // Refresh analytics data every 120 seconds
                    analyticsInterval = setInterval(() => {
                        console.log('Auto-refreshing analytics data...');
                        loadAnalyticsData();
                    }, 120000);
                    break;
            }
            
            // Show indicator and update status
            if (indicator && status) {
                indicator.style.display = 'block';
                status.textContent = `🔄 Real-time aktif (${pageId})`;
                
                // Hide indicator after 3 seconds
                setTimeout(() => {
                    indicator.style.display = 'none';
                }, 3000);
            }
        }

        // Stop all real-time updates
        function stopRealTimeUpdates() {
            if (dashboardInterval) clearInterval(dashboardInterval);
            if (progressInterval) clearInterval(progressInterval);
            if (assessmentInterval) clearInterval(assessmentInterval);
            if (assignmentInterval) clearInterval(assignmentInterval);
            if (analyticsInterval) clearInterval(analyticsInterval);
        }

        // Initialize Charts and Load Data
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved preferences
            loadPreferences();
            
            // Load initial data
            loadDashboardData();
            loadClassroomData();
            
            // Start real-time updates for dashboard (default page)
            startRealTimeUpdates('dashboard');
            
            // Add test function to window for debugging
            window.testUpdateUI = testUpdateUI;
            window.startRealTimeUpdates = startRealTimeUpdates;
            window.stopRealTimeUpdates = stopRealTimeUpdates;
            
            // Initialize Progress Chart
            const progressCtx = document.getElementById('progressChart');
            if (progressCtx) {
                new Chart(progressCtx, {
                    type: 'line',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Modul Diselesaikan',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: '#4299e1',
                            backgroundColor: 'rgba(66, 153, 225, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Initialize other charts
            initializeCharts();
        });

        // Initialize all charts
        function initializeCharts() {
            // Class Progress Chart
            const classProgressCtx = document.getElementById('classProgressChart');
            if (classProgressCtx) {
                new Chart(classProgressCtx, {
                    type: 'bar',
                    data: {
                        labels: ['XII IPA 1', 'XII IPA 2', 'XII IPA 3'],
                        datasets: [{
                            label: 'Progress (%)',
                            data: [0, 0, 0],
                            backgroundColor: ['#4299e1', '#48bb78', '#ed8936']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }

            // AI Score Chart
            const aiScoreCtx = document.getElementById('aiScoreChart');
            if (aiScoreCtx) {
                new Chart(aiScoreCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Excellent (90-100)', 'Good (80-89)', 'Average (70-79)', 'Below Average (<70)'],
                        datasets: [{
                            data: [0, 0, 0, 0],
                            backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#f56565']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Learning Trend Chart
            const learningTrendCtx = document.getElementById('learningTrendChart');
            if (learningTrendCtx) {
                new Chart(learningTrendCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Aktivitas Pembelajaran',
                            data: [0, 0, 0, 0, 0, 0],
                            borderColor: '#4299e1',
                            backgroundColor: 'rgba(66, 153, 225, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        // Settings Functions
        async function updateProfile() {
            const name = document.getElementById('profile-name').value;
            const email = document.getElementById('profile-email').value;
            const school = document.getElementById('profile-school').value;
            
            // Validation
            if (!name.trim()) {
                showNotification('Nama tidak boleh kosong!', 'error');
                return;
            }
            
            if (!email.trim()) {
                showNotification('Email tidak boleh kosong!', 'error');
                return;
            }
            
            try {
                const response = await fetch('/dashboard/profile/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        school_name: school
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showNotification(data.message || 'Profil berhasil diupdate!', 'success');
                    
                    // Debug: Log elements found
                    console.log('Updating UI elements...');
                    
                    // Update welcome message in header
                    const welcomeElement = document.querySelector('.header p');
                    console.log('Welcome element found:', welcomeElement);
                    if (welcomeElement) {
                        welcomeElement.textContent = `Selamat datang kembali, ${name}`;
                        console.log('Updated welcome message');
                    }
                    
                    // Update user name in user-info section
                    const userNameElement = document.querySelector('.user-info div p:first-child');
                    console.log('User name element found:', userNameElement);
                    if (userNameElement) {
                        userNameElement.textContent = name;
                        console.log('Updated user name');
                    }
                    
                    // Update school name in user-info section
                    const schoolElement = document.querySelector('.user-info div p:last-child');
                    console.log('School element found:', schoolElement);
                    if (schoolElement) {
                        schoolElement.textContent = school || 'Guru';
                        console.log('Updated school name');
                    }
                    
                    // Update avatar initials
                    const avatarElement = document.querySelector('.user-avatar');
                    console.log('Avatar element found:', avatarElement);
                    if (avatarElement && name.length >= 2) {
                        avatarElement.textContent = name.substring(0, 2).toUpperCase();
                        console.log('Updated avatar initials');
                    }
                    
                    // Force a small visual feedback
                    const userInfoDiv = document.querySelector('.user-info');
                    if (userInfoDiv) {
                        userInfoDiv.style.transition = 'all 0.3s ease';
                        userInfoDiv.style.transform = 'scale(1.05)';
                        setTimeout(() => {
                            userInfoDiv.style.transform = 'scale(1)';
                        }, 300);
                    }
                    
                    console.log('Profile updated successfully:', { name, email, school });
                } else {
                    const errorMessage = data.message || 'Gagal update profil!';
                    showNotification(errorMessage, 'error');
                    
                    // Show validation errors if any
                    if (data.errors) {
                        Object.values(data.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                showNotification(error, 'error');
                            });
                        });
                    }
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                showNotification('Terjadi kesalahan saat mengupdate profil!', 'error');
            }
        }


        function toggleNotifications(checkbox) {
            const status = checkbox.checked ? 'diaktifkan' : 'dinonaktifkan';
            showNotification(`Notifikasi email ${status}`);
            localStorage.setItem('emailNotifications', checkbox.checked);
        }

        function toggleAutoApprove(checkbox) {
            const status = checkbox.checked ? 'diaktifkan' : 'dinonaktifkan';
            showNotification(`Auto-approve AI scores ${status}`);
            localStorage.setItem('autoApprove', checkbox.checked);
        }

        function toggleDarkMode(checkbox) {
            if (checkbox.checked) {
                document.body.classList.add('dark-mode');
                showNotification('Dark mode diaktifkan');
            } else {
                document.body.classList.remove('dark-mode');
                showNotification('Dark mode dinonaktifkan');
            }
            localStorage.setItem('darkMode', checkbox.checked);
        }

        // Student Management Functions
        async function addStudent() {
            const name = document.getElementById('student-name').value;
            const nis = document.getElementById('student-nis').value;
            const email = document.getElementById('student-email').value;
            const classId = document.getElementById('student-class').value;
            
            // Validation
            if (!name.trim()) {
                showNotification('Nama siswa tidak boleh kosong!', 'error');
                return;
            }
            
            try {
                const response = await fetch('/dashboard/students/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        name: name,
                        nis: nis || null,
                        email: email || null,
                        class_id: classId || null
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showNotification('Siswa berhasil ditambahkan!', 'success');
                    closeModal('addStudent');
                    
                    // Clear form
                    document.getElementById('student-name').value = '';
                    document.getElementById('student-nis').value = '';
                    document.getElementById('student-email').value = '';
                    document.getElementById('student-class').value = '';
                    
                    // Refresh student list if on classroom page
                    if (document.getElementById('classroom').classList.contains('active')) {
                        loadClassroomData();
                    }
                    
                    // Update dashboard stats
                    loadDashboardData();
                } else {
                    const errorMessage = data.message || 'Gagal menambahkan siswa!';
                    showNotification(errorMessage, 'error');
                    
                    // Show validation errors if any
                    if (data.errors) {
                        Object.values(data.errors).forEach(errorArray => {
                            errorArray.forEach(error => {
                                showNotification(error, 'error');
                            });
                        });
                    }
                }
            } catch (error) {
                console.error('Error adding student:', error);
                showNotification('Terjadi kesalahan saat menambahkan siswa!', 'error');
            }
        }

        // Load classes for dropdown
        async function loadClassesForDropdown() {
            try {
                const response = await fetch('/dashboard/classes');
                if (response.ok) {
                    const classes = await response.json();
                    const select = document.getElementById('student-class');
                    
                    // Clear existing options except first
                    select.innerHTML = '<option value="">Pilih Kelas</option>';
                    
                    classes.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = cls.name;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading classes:', error);
            }
        }

        function changeLanguage(language) {
            const langName = language === 'id' ? 'Indonesia' : 'English';
            showNotification(`Bahasa diubah ke ${langName}`);
            localStorage.setItem('language', language);
            
            // TODO: Implement actual language change
            if (language === 'en') {
                // Change some text to English as example
                document.getElementById('page-title').textContent = 'Teacher Dashboard';
            } else {
                document.getElementById('page-title').textContent = 'Dashboard Guru';
            }
        }

        function savePreferences() {
            const emailNotifications = document.getElementById('email-notifications').checked;
            const autoApprove = document.getElementById('auto-approve').checked;
            const darkMode = document.getElementById('dark-mode').checked;
            const language = document.getElementById('language-select').value;
            
            // Save to localStorage
            localStorage.setItem('emailNotifications', emailNotifications);
            localStorage.setItem('autoApprove', autoApprove);
            localStorage.setItem('darkMode', darkMode);
            localStorage.setItem('language', language);
            
            showNotification('Preferensi berhasil disimpan!');
        }

        // Load saved preferences on page load
        function loadPreferences() {
            const emailNotifications = localStorage.getItem('emailNotifications');
            const autoApprove = localStorage.getItem('autoApprove');
            const darkMode = localStorage.getItem('darkMode');
            const language = localStorage.getItem('language');
            
            if (emailNotifications !== null) {
                document.getElementById('email-notifications').checked = emailNotifications === 'true';
            }
            
            if (autoApprove !== null) {
                document.getElementById('auto-approve').checked = autoApprove === 'true';
            }
            
            if (darkMode === 'true') {
                document.getElementById('dark-mode').checked = true;
                document.body.classList.add('dark-mode');
            }
            
            if (language) {
                document.getElementById('language-select').value = language;
            }
        }

        // Show notification function (improved)
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification.toast');
            existingNotifications.forEach(notification => notification.remove());
            
            // Create new notification
            const notification = document.createElement('div');
            notification.className = 'notification toast';
            
            const colors = {
                success: 'linear-gradient(135deg, #48bb78, #38a169)',
                error: 'linear-gradient(135deg, #f56565, #e53e3e)',
                warning: 'linear-gradient(135deg, #ed8936, #dd6b20)',
                info: 'linear-gradient(135deg, #4299e1, #3182ce)'
            };
            
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${colors[type] || colors.success};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                z-index: 10000;
                font-weight: 500;
                animation: slideIn 0.3s ease-out;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 14px;
                max-width: 300px;
                word-wrap: break-word;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 3000);
        }

        // Load data when switching pages
        function showPage(pageId) {
            // Hide all pages
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected page
            document.getElementById(pageId).classList.add('active');
            
            // Add active class to clicked nav item
            event.target.closest('.nav-item').classList.add('active');
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard Guru',
                'classroom': 'Manajemen Kelas',
                'progress': 'Progress Siswa',
                'assessment': 'Penilaian AI',
                'assignments': 'Tugas & Konten',
                'analytics': 'Analytics',
                'settings': 'Pengaturan'
            };
            
            document.getElementById('page-title').textContent = titles[pageId] || 'Dashboard Guru';
            
            // Load data for specific pages and start real-time updates
            switch(pageId) {
                case 'dashboard':
                    // Dashboard data already loaded on page load
                    break;
                case 'progress':
                    if (!window.progressDataLoaded) {
                        loadProgressData();
                        window.progressDataLoaded = true;
                    }
                    break;
                case 'assessment':
                    if (!window.assessmentDataLoaded) {
                        loadAssessmentData();
                        window.assessmentDataLoaded = true;
                    }
                    break;
                case 'assignments':
                    if (!window.assignmentDataLoaded) {
                        loadAssignmentData();
                        window.assignmentDataLoaded = true;
                    }
                    break;
                case 'analytics':
                    if (!window.analyticsDataLoaded) {
                        loadAnalyticsData();
                        window.analyticsDataLoaded = true;
                    }
                    break;
            }
            
            // Start real-time updates for the current page
            startRealTimeUpdates(pageId);
        }
    </script>
</body>
</html>
