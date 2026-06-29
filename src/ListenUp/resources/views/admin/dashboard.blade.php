@extends('layouts.admin')

@section('admin_content')
<div class="dashboard-wrapper">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="h3 fw-bold mb-1 text-dark"><i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard Quản trị</h2>
            <p class="text-muted mb-0 small"><i class="fas fa-calendar-alt me-1"></i> Báo cáo hoạt động học tập và bài học của hệ thống</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-1"></i> Làm mới
            </button>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-5">
        <!-- Total Users Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-users h-100 p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-uppercase text-muted fs-7 fw-bold mb-1 d-block">Tổng học viên</span>
                        <h3 class="display-6 fw-bold mb-0 text-dark">{{ $stats['total_users'] ?? 0 }}</h3>
                    </div>
                    <div class="icon-box bg-purple-100 text-purple-600 rounded-3">
                        <i class="fas fa-users fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Lessons Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-lessons h-100 p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-uppercase text-muted fs-7 fw-bold mb-1 d-block">Tổng bài học</span>
                        <h3 class="display-6 fw-bold mb-0 text-dark">{{ $stats['total_lessons'] ?? 0 }}</h3>
                    </div>
                    <div class="icon-box bg-success-100 text-success-600 rounded-3">
                        <i class="fas fa-book fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Activity Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-activity h-100 p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-uppercase text-muted fs-7 fw-bold mb-1 d-block">Tổng lượt học</span>
                        <h3 class="display-6 fw-bold mb-0 text-dark">{{ $stats['total_results'] ?? 0 }}</h3>
                    </div>
                    <div class="icon-box bg-warning-100 text-warning-600 rounded-3">
                        <i class="fas fa-history fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Lessons Card -->
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-active h-100 p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-uppercase text-muted fs-7 fw-bold mb-1 d-block">Bài học đang mở</span>
                        <h3 class="display-6 fw-bold mb-0 text-dark">{{ $stats['active_lessons'] ?? 0 }}</h3>
                    </div>
                    <div class="icon-box bg-info-100 text-info-600 rounded-3">
                        <i class="fas fa-lock-open fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row g-4 mb-4">
        <!-- Line Chart (Activity Trend) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Tần suất hoạt động học tập</h5>
                        <small class="text-muted">Tổng số lượt hoàn thành bài test trong 7 ngày qua</small>
                    </div>
                    <span class="badge bg-purple-100 text-purple-700 py-2 px-3 rounded-pill fw-semibold">Xu hướng</span>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart (Levels Distribution) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Phân bố cấp độ</h5>
                    <small class="text-muted">Phân chia bài test theo trình độ nghe</small>
                </div>
                <div class="card-body px-4 pb-4 d-flex flex-column align-items-center justify-content-center">
                    <div class="chart-container mb-3" style="position: relative; height: 220px; width: 100%;">
                        <canvas id="levelChart"></canvas>
                    </div>
                    <div id="levelLegend" class="w-100 d-flex flex-wrap justify-content-center gap-3 mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-4">
        <!-- Horizontal Bar Chart (Lessons by Topic) -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Bài học theo chủ đề</h5>
                    <small class="text-muted">Thống kê số lượng bài học theo từng chủ đề đề xuất</small>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="chart-container" style="position: relative; height: 260px; width: 100%;">
                        <canvas id="topicChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styling for the custom Stats Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Colors and glow variables */
    .bg-purple-100 { background-color: rgba(124, 58, 237, 0.08); }
    .text-purple-600 { color: #7c3aed; }
    .text-purple-700 { color: #6d28d9; }
    
    .bg-success-100 { background-color: rgba(16, 185, 129, 0.08); }
    .text-success-600 { color: #10b981; }
    
    .bg-warning-100 { background-color: rgba(245, 158, 11, 0.08); }
    .text-warning-600 { color: #f59e0b; }
    
    .bg-info-100 { background-color: rgba(6, 182, 212, 0.08); }
    .text-info-600 { color: #06b6d4; }

    /* Custom borders/glows for cards */
    .stat-users { border-left: 4px solid #7c3aed; }
    .stat-lessons { border-left: 4px solid #10b981; }
    .stat-activity { border-left: 4px solid #f59e0b; }
    .stat-active { border-left: 4px solid #06b6d4; }

    .fs-7 { font-size: 0.75rem; }

    .dark .stat-card {
        background: #0d1425;
        border-color: rgba(255, 255, 255, 0.05);
    }
    .dark .text-dark { color: #f8fafc !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. Line Chart: Activity Trend ---
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        
        // Gradient background
        const activityGradient = activityCtx.createLinearGradient(0, 0, 0, 300);
        activityGradient.addColorStop(0, 'rgba(124, 58, 237, 0.3)');
        activityGradient.addColorStop(1, 'rgba(124, 58, 237, 0.00)');

        const activityLabels = {!! json_encode(array_keys($activityData)) !!};
        const activityValues = {!! json_encode(array_values($activityData)) !!};

        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: activityLabels,
                datasets: [{
                    label: 'Số lượt học',
                    data: activityValues,
                    borderColor: '#7c3aed',
                    borderWidth: 3,
                    pointBackgroundColor: '#7c3aed',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    backgroundColor: activityGradient,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        min: 0,
                        ticks: {
                            stepSize: 1,
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(241, 245, 249, 0.8)'
                        }
                    }
                }
            }
        });

        // --- 2. Doughnut Chart: Levels Distribution ---
        const levelCtx = document.getElementById('levelChart').getContext('2d');
        
        const levelDataMap = {!! json_encode($lessonsByLevel) !!};
        const levelLabels = Object.keys(levelDataMap);
        const levelValues = Object.values(levelDataMap);

        const levelColors = [
            '#7c3aed', // Purple
            '#06b6d4', // Cyan
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#ec4899', // Pink
            '#64748b'  // Slate
        ];

        const levelChartInstance = new Chart(levelCtx, {
            type: 'doughnut',
            data: {
                labels: levelLabels,
                datasets: [{
                    data: levelValues,
                    backgroundColor: levelColors.slice(0, levelLabels.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        cornerRadius: 8
                    }
                }
            }
        });

        // Generate Custom Legend for Doughnut
        const legendDiv = document.getElementById('levelLegend');
        if (legendDiv && levelLabels.length > 0) {
            levelLabels.forEach((label, index) => {
                const color = levelColors[index % levelColors.length];
                const value = levelValues[index];
                
                const legendItem = document.createElement('div');
                legendItem.className = 'd-flex align-items-center';
                legendItem.innerHTML = `
                    <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: ${color};"></span>
                    <span class="fs-7 fw-semibold text-muted">${label} (${value})</span>
                `;
                legendDiv.appendChild(legendItem);
            });
        }

        // --- 3. Horizontal Bar Chart: Lessons by Topic ---
        const topicCtx = document.getElementById('topicChart').getContext('2d');
        
        const topicDataMap = {!! json_encode($lessonsByTopic) !!};
        const topicLabels = Object.keys(topicDataMap);
        const topicValues = Object.values(topicDataMap);

        const barGradient = topicCtx.createLinearGradient(0, 0, 500, 0);
        barGradient.addColorStop(0, '#7c3aed');
        barGradient.addColorStop(1, '#06b6d4');

        new Chart(topicCtx, {
            type: 'bar',
            data: {
                labels: topicLabels,
                datasets: [{
                    data: topicValues,
                    backgroundColor: barGradient,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 16
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 10,
                        backgroundColor: '#0f172a',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        min: 0,
                        ticks: {
                            stepSize: 1,
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(241, 245, 249, 0.8)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#475569',
                            font: {
                                weight: '500',
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush