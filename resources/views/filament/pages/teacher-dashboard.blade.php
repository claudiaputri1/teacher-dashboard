<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-l-blue-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ $totalStudents }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Siswa</div>
                        <div class="text-xs text-green-600 mt-2">↗ +12 siswa baru</div>
                    </div>
                    <div class="text-blue-500">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-l-green-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($averageProgress, 0) }}%</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rata-rata Progress</div>
                        <div class="text-xs text-green-600 mt-2">↗ +5% minggu ini</div>
                    </div>
                    <div class="text-green-500">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Assignments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-l-orange-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-orange-600 mb-2">{{ $pendingAssignments }}</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tugas Pending</div>
                        <div class="text-xs text-orange-600 mt-2">↗ Perlu review</div>
                    </div>
                    <div class="text-orange-500">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Engagement Rate -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-l-purple-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($engagementRate, 0) }}%</div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tingkat Engagement</div>
                        <div class="text-xs text-green-600 mt-2">↗ Sangat baik</div>
                    </div>
                    <div class="text-purple-500">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Area -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Progress Pembelajaran Siswa</h3>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button class="tab-btn active px-4 py-2 text-sm font-medium rounded-md transition-colors" data-period="weekly">
                            Mingguan
                        </button>
                        <button class="tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors" data-period="monthly">
                            Bulanan
                        </button>
                        <button class="tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors" data-period="yearly">
                            Tahunan
                        </button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>

            <!-- Recent Students -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Siswa Aktif Terbaru</h3>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</button>
                </div>
                <div class="space-y-4">
                    @foreach($recentStudents as $student)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                            {{ $student['initials'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $student['name'] }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ Str::limit($student['module'], 20) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="w-20 bg-gray-200 rounded-full h-2 mb-1">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $student['progress'] }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500">{{ number_format($student['progress'], 0) }}%</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button class="content-tab-btn active px-4 py-2 text-sm font-medium rounded-md transition-colors" data-content="activities">
                        Aktivitas
                    </button>
                    <button class="content-tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors" data-content="assessments">
                        Penilaian
                    </button>
                    <button class="content-tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors" data-content="assignments">
                        Tugas
                    </button>
                </div>
            </div>

            <!-- Activities Content -->
            <div id="activities-content" class="content-section">
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-center p-4 border-l-4 border-gray-200 bg-gray-50 rounded-r-lg">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 text-lg
                            @if($activity['type'] === 'progress') bg-green-100 @endif
                            @if($activity['type'] === 'assignment') bg-yellow-100 @endif
                            @if($activity['type'] === 'assessment') bg-pink-100 @endif">
                            {{ $activity['icon'] }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $activity['title'] }}</div>
                            <div class="text-sm text-gray-500">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                    @endforeach
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Ahmad Nurul</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Essay Geometri 3D</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">85/100</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900">Review</button>
                                </td>
                            </tr>
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
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kreasi Model 3D Prisma</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">XII IPA 1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">30 Sep 2025</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">28/32</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Aktif</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chart
            const ctx = document.getElementById('progressChart').getContext('2d');
            const weeklyData = @json($weeklyProgress);
            
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
                        data: weeklyData.studyHours,
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
                    // Update active tab
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
                    // Update active tab
                    document.querySelectorAll('.content-tab-btn').forEach(b => {
                        b.classList.remove('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                        b.classList.add('text-gray-500', 'hover:text-gray-700');
                    });
                    this.classList.add('active', 'bg-white', 'text-blue-600', 'shadow-sm');
                    this.classList.remove('text-gray-500', 'hover:text-gray-700');
                    
                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.add('hidden');
                    });
                    
                    // Show selected content
                    const contentId = this.dataset.content + '-content';
                    document.getElementById(contentId).classList.remove('hidden');
                });
            });

            function updateChartData(period) {
                if (period === 'weekly') {
                    progressChart.data.labels = weeklyData.labels;
                    progressChart.data.datasets[0].data = weeklyData.completedModules;
                    progressChart.data.datasets[1].data = weeklyData.studyHours;
                } else if (period === 'monthly') {
                    progressChart.data.labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
                    progressChart.data.datasets[0].data = [85, 92, 78, 95];
                    progressChart.data.datasets[1].data = [65, 70, 58, 75];
                } else if (period === 'yearly') {
                    progressChart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                    progressChart.data.datasets[0].data = [320, 450, 380, 520, 480, 650];
                    progressChart.data.datasets[1].data = [240, 340, 290, 380, 360, 480];
                }
                progressChart.update();
            }

            // Initialize active states
            document.querySelector('.tab-btn.active').classList.add('bg-white', 'text-blue-600', 'shadow-sm');
            document.querySelector('.content-tab-btn.active').classList.add('bg-white', 'text-blue-600', 'shadow-sm');
        });
    </script>
    @endpush
</x-filament-panels::page>
