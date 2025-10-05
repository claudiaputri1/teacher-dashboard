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
                    <div class="stat-change" id="students-change">Memuat...</div>
                </div>
                <div class="stat-card progress">
                    <div class="stat-value" style="color: #48bb78;" id="avg-progress">0%</div>
                    <div class="stat-label">Rata-rata Progress</div>
                    <div class="stat-change" id="progress-change">Memuat...</div>
                </div>
                <div class="stat-card assessments">
                    <div class="stat-value" style="color: #ed8936;" id="pending-tasks">0</div>
                    <div class="stat-label">Tugas Pending</div>
                    <div class="stat-change" id="tasks-change">Memuat...</div>
                </div>
                <div class="stat-card engagement">
                    <div class="stat-value" style="color: #9f7aea;" id="engagement-rate">0%</div>
                    <div class="stat-label">Tingkat Engagement</div>
                    <div class="stat-change" id="engagement-change">Memuat...</div>
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
                    <h3 class="card-title">Daftar Sekolah</h3>
                </div>
                
                <div id="classes-grid" class="grid-3">
                    <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                        <div style="font-size: 48px; margin-bottom: 16px;">🏫</div>
                        <p>Belum ada kelas</p>
                        <p style="font-size: 12px;">Kelas akan muncul setelah dibuat melalui sistem</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa</h3>
                    <button class="btn btn-success" onclick="openModal('addStudent')">+ Tambah Siswa</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Sekolah</th>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
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

        // Dashboard data loading - now properly configured
        let dashboardDataLoaded = false;
        async function loadDashboardData() {
            if (dashboardDataLoaded) return;
            
            try {
                console.log('Loading dashboard data from Supabase...');
                const response = await fetch('/api/dashboard/stats', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Dashboard data received:', data);
                
                // Update statistics
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
                console.log('Dashboard data loaded successfully');
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                // Show error message to user
                document.getElementById('students-change').textContent = 'Gagal memuat data';
                document.getElementById('progress-change').textContent = 'Gagal memuat data';
                document.getElementById('tasks-change').textContent = 'Gagal memuat data';
                document.getElementById('engagement-change').textContent = 'Gagal memuat data';
                dashboardDataLoaded = true; // Prevent retry loops
            }
        }

        // Classroom data loading - now properly configured
        let classroomDataLoaded = false;
        async function loadClassroomData() {
            if (classroomDataLoaded) return;
            
            try {
                console.log('Loading classroom data from Supabase...');
                const response = await fetch('/api/dashboard/classroom', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Classroom data received:', data);
                
                const classesGrid = document.getElementById('classes-grid');
                const studentsTable = document.getElementById('students-table');
                
                // Update classes/schools grid
                if (data.classes && data.classes.length === 0) {
                    classesGrid.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                            <div style="font-size: 48px; margin-bottom: 16px;">🏫</div>
                            <p>Belum ada sekolah</p>
                            <p style="font-size: 12px;">Data sekolah akan muncul dari Supabase</p>
                        </div>
                    `;
                } else if (data.classes) {
                    classesGrid.innerHTML = data.classes.map(school => `
                        <div class="class-card">
                            <h4>${school.name}</h4>
                            <p>${school.students} siswa</p>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${school.progress}%"></div>
                            </div>
                            <p style="font-size: 12px; color: #718096;">${school.progress}% progress</p>
                        </div>
                    `).join('');
                }
                
                // Update students table
                if (data.recent_students && data.recent_students.length === 0) {
                    studentsTable.innerHTML = `
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">👥</div>
                                <p>Belum ada siswa</p>
                                <p style="font-size: 12px;">Data siswa akan muncul dari Supabase</p>
                            </td>
                        </tr>
                    `;
                } else if (data.recent_students) {
                    studentsTable.innerHTML = data.recent_students.map(student => `
                        <tr>
                            <td>${student.name}</td>
                            <td>${student.class}</td>
                            <td>${student.joined}</td>
                            <td><span class="badge ${student.status.toLowerCase()}">${student.status}</span></td>
                            <td>
                                <button class="btn btn-primary" style="font-size: 12px; padding: 4px 8px;">Ubah</button>
                                <button class="btn btn-danger" style="font-size: 12px; padding: 4px 8px;">Hapus</button>
                            </td>
                        </tr>
                    `).join('');
                }
                
                classroomDataLoaded = true;
                console.log('Classroom data loaded successfully');
            } catch (error) {
                console.error('Error loading classroom data:', error);
                // Show error in UI
                const classesGrid = document.getElementById('classes-grid');
                const studentsTable = document.getElementById('students-table');
                
                if (classesGrid) {
                    classesGrid.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #e53e3e; grid-column: 1 / -1;">
                            <p>Gagal memuat data sekolah</p>
                        </div>
                    `;
                }
                
                if (studentsTable) {
                    studentsTable.innerHTML = `
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #e53e3e;">
                                Gagal memuat data siswa
                            </td>
                        </tr>
                    `;
                }
                
                classroomDataLoaded = true; // Prevent retry loops
            }
        }

        // Progress data loading - now properly configured
        async function loadProgressData() {
            try {
                console.log('Loading progress data from Supabase...');
                const response = await fetch('/api/dashboard/progress', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Progress data received:', data);
                
                const progressGrid = document.getElementById('progress-grid');
                
                if (data.students && data.students.length === 0) {
                    progressGrid.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #718096; grid-column: 1 / -1;">
                            <div style="font-size: 48px; margin-bottom: 16px;">📈</div>
                            <p>Belum ada data progress</p>
                            <p style="font-size: 12px;">Data akan muncul setelah siswa mulai belajar</p>
                        </div>
                    `;
                } else if (data.students) {
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
                
                console.log('Progress data loaded successfully');
            } catch (error) {
                console.error('Error loading progress data:', error);
                const progressGrid = document.getElementById('progress-grid');
                if (progressGrid) {
                    progressGrid.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #e53e3e; grid-column: 1 / -1;">
                            <p>Gagal memuat data progress</p>
                        </div>
                    `;
                }
            }
        }

        // Assessment data loading - now properly configured
        async function loadAssessmentData() {
            try {
                console.log('Loading assessment data from Supabase...');
                const response = await fetch('/api/dashboard/assessment', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Assessment data received:', data);
                
                // Update pending count
                if (data.pending_reviews !== undefined) {
                    document.getElementById('pending-count').textContent = data.pending_reviews;
                }
                
                const assessmentsTable = document.getElementById('assessments-table');
                
                if (data.assessments && data.assessments.length === 0) {
                    assessmentsTable.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                                <div style="font-size: 48px; margin-bottom: 16px;">🤖</div>
                                <p>Belum ada penilaian AI pending</p>
                                <p style="font-size: 12px;">Penilaian akan muncul setelah siswa submit tugas</p>
                            </td>
                        </tr>
                    `;
                } else if (data.assessments) {
                    assessmentsTable.innerHTML = data.assessments.map(assessment => `
                        <tr>
                            <td>${assessment.student}</td>
                            <td>${assessment.assignment}</td>
                            <td><span class="badge ${assessment.ai_score >= 80 ? 'excellent' : 'pending'}">${assessment.ai_score}%</span></td>
                            <td>${assessment.confidence}%</td>
                            <td><span class="badge ${assessment.status}">${assessment.status}</span></td>
                            <td>
                                <button class="btn btn-success" style="font-size: 12px; padding: 4px 8px;">Tinjau</button>
                                <button class="btn btn-primary" style="font-size: 12px; padding: 4px 8px;">Setujui</button>
                            </td>
                        </tr>
                    `).join('');
                }
                
                console.log('Assessment data loaded successfully');
            } catch (error) {
                console.error('Error loading assessment data:', error);
                const assessmentsTable = document.getElementById('assessments-table');
                if (assessmentsTable) {
                    assessmentsTable.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #e53e3e;">
                                Gagal memuat data penilaian
                            </td>
                        </tr>
                    `;
                }
            }
        }


        // Analytics data loading - now properly configured
        async function loadAnalyticsData() {
            try {
                console.log('Loading analytics data from Supabase...');
                const response = await fetch('/api/dashboard/analytics', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Analytics data received:', data);
                
                // Update analytics stats
                if (data.stats) {
                    document.getElementById('total-interactions').textContent = data.stats.total_interactions || 0;
                    document.getElementById('avg-session-time').textContent = (data.stats.avg_session_time || 0) + ' min';
                    document.getElementById('completion-rate').textContent = (data.stats.completion_rate || 0) + '%';
                    document.getElementById('satisfaction-score').textContent = (data.stats.satisfaction || 0).toFixed(1);
                }
                
                // Update growth indicators
                if (data.trends) {
                    document.getElementById('interactions-growth').textContent = '+' + (data.trends.interactions_growth || 0) + '%';
                    document.getElementById('session-growth').textContent = '+' + (data.trends.time_growth || 0) + '%';
                    document.getElementById('completion-growth').textContent = '+' + (data.trends.completion_growth || 0) + '%';
                    document.getElementById('satisfaction-growth').textContent = '+' + (data.trends.satisfaction_growth || 0) + '%';
                }
                
                console.log('Analytics data loaded successfully');
            } catch (error) {
                console.error('Error loading analytics data:', error);
                // Show error in analytics cards
                document.getElementById('total-interactions').textContent = 'Gagal';
                document.getElementById('avg-session-time').textContent = 'Gagal';
                document.getElementById('completion-rate').textContent = 'Gagal';
                document.getElementById('satisfaction-score').textContent = 'Gagal';
            }
        }


        // Filter Progress by Class
        function filterProgressByClass(classId) {
            // TODO: Implement class filtering
            loadProgressData();
        }



        // Initialize Charts and Load Data
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved preferences
            loadPreferences();
            
            // Load initial data
            loadDashboardData();
            loadClassroomData();
            
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
                        labels: ['Sekolah 1', 'Sekolah 2', 'Sekolah 3'],
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
                        labels: ['Sangat Baik (90-100)', 'Baik (80-89)', 'Cukup (70-79)', 'Perlu Perbaikan (<70)'],
                        datasets: [{
                            data: [0, 0, 0, 0],
                            backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#e53e3e']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Confidence Chart
            const confidenceCtx = document.getElementById('confidenceChart');
            if (confidenceCtx) {
                new Chart(confidenceCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Skor Kepercayaan (%)',
                            data: [0, 0, 0, 0, 0, 0],
                            borderColor: '#9f7aea',
                            backgroundColor: 'rgba(159, 122, 234, 0.1)',
                            tension: 0.4
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


            // Learning Trend Chart
            const learningTrendCtx = document.getElementById('learningTrendChart');
            if (learningTrendCtx) {
                new Chart(learningTrendCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
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
                    
                    console.log('Profile updated successfully:', { name, email });
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


        function savePreferences() {
            const emailNotifications = document.getElementById('email-notifications').checked;
            const autoApprove = document.getElementById('auto-approve').checked;
            const darkMode = document.getElementById('dark-mode').checked;
            
            // Save to localStorage
            localStorage.setItem('emailNotifications', emailNotifications);
            localStorage.setItem('autoApprove', autoApprove);
            localStorage.setItem('darkMode', darkMode);
            
            showNotification('Preferensi berhasil disimpan!');
        }

        // Load saved preferences on page load
        function loadPreferences() {
            const emailNotifications = localStorage.getItem('emailNotifications');
            const autoApprove = localStorage.getItem('autoApprove');
            const darkMode = localStorage.getItem('darkMode');
            
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
                'analytics': 'Analytics',
                'settings': 'Pengaturan'
            };
            
            document.getElementById('page-title').textContent = titles[pageId] || 'Dashboard Guru';
            
            // Load data for specific pages
            switch(pageId) {
                case 'dashboard':
                    // Dashboard data already loaded on page load
                    break;
                case 'classroom':
                    // Classroom data already loaded on page load
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
                case 'analytics':
                    if (!window.analyticsDataLoaded) {
                        loadAnalyticsData();
                        window.analyticsDataLoaded = true;
                    }
                    break;
            }
            
            console.log('Page switched to:', pageId);
        }
    </script>
</body>
</html>
