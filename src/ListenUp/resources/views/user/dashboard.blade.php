@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between items-center mb-3">
            <div>
                <h2 class="text-[var(--text-primary)]"><i class="fas fa-user"></i> Dashboard Học viên</h2>
                <p class="mb-0 text-[var(--text-secondary)]">Xin chào <strong class="text-[var(--text-primary)]">{{ Auth::user()->UserName }}</strong>! Tổng điểm: <span class="badge bg-info">{{ Auth::user()->TongDiem }}</span></p>
            </div>
            <div>
                <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-primary shadow-sm"><i class="fas fa-user-edit"></i> Chỉnh sửa hồ sơ</a>
            </div>
        </div>
        <div class="col-12">
            <hr class="border-[var(--brand-border)]">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                    <h5 class="text-[var(--text-primary)] mb-0"><i class="fas fa-chart-line"></i> Kết quả luyện tập</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-primary">{{ $myResultsCount }}</h3>
                    <p class="text-[var(--text-secondary)]">Tổng số lần luyện tập</p>
                    <a href="{{ route('user.results') }}" class="btn btn-primary rounded-pill px-4">Xem chi tiết</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                    <h5 class="text-[var(--text-primary)] mb-0"><i class="fas fa-trophy"></i> Tổng điểm</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-warning">{{ number_format(Auth::user()->TongDiem) }}</h3>
                    <p class="text-[var(--text-secondary)]">Điểm tích lũy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần 2.1: Lộ trình Học tập Thích ứng (Adaptive Lesson Recommendation) -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-[var(--brand-card-bg)]" style="border: 1px solid var(--brand-border) !important; backdrop-filter: blur(12px);">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1 text-[var(--text-primary)]">
                            <i class="fas fa-magic text-primary me-2"></i> Lộ Trình Luyện Nghe Thích Ứng
                        </h4>
                        <p class="text-[var(--text-secondary)] small mb-0">Hệ thống AI tự động đề xuất dựa trên điểm số và tiến trình của bạn.</p>
                    </div>
                    <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fas fa-brain me-1"></i> Smart Engine v1.0</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($recommendedTests as $rec)
                            @php
                                $topicName = $rec->chude ? $rec->chude->TenCD : 'Tổng hợp';
                                $iconClass = $rec->chude ? $rec->chude->icon_class : 'fas fa-headphones';
                                $levelName = $rec->capdonghe ? $rec->capdonghe->TenCDN : 'Tổng quát';
                                
                                // Map colors for Bootstrap compatibility
                                $colorMap = [
                                    'blue' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20',
                                    'orange' => 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-20',
                                    'indigo' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-20',
                                    'slate' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20',
                                    'rose' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20',
                                    'cyan' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-20',
                                    'emerald' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-20',
                                    'violet' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20',
                                    'pink' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20',
                                    'amber' => 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-20',
                                    'purple' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20',
                                ];
                                $badgeStyle = $colorMap[$rec->chude->color_class ?? 'purple'] ?? 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20';
                                
                                // Tag styles for recommendation type
                                $typeMap = [
                                    'weak_topic' => 'bg-danger text-white',
                                    'adventure_map' => 'bg-success text-white',
                                    'new_discovery' => 'bg-info text-dark',
                                    'fallback' => 'bg-secondary text-white',
                                ];
                                $typeBadge = $typeMap[$rec->recommendation_type ?? 'fallback'] ?? 'bg-secondary text-white';
                            @endphp
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm bg-[var(--brand-card-bg)] border border-[var(--brand-border)] transition-all hover-translate-y" style="border-radius: 12px; transition: transform 0.2s ease;">
                                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge {{ $badgeStyle }} px-3 py-1.5 rounded-pill d-inline-flex align-items-center">
                                                    <i class="{{ $iconClass }} me-1.5"></i> {{ $topicName }}
                                                </span>
                                                <span class="badge bg-light text-dark border small">{{ $levelName }}</span>
                                            </div>
                                            
                                            <h5 class="card-title fw-bold text-[var(--text-primary)] mb-2">{{ $rec->TenBai }}</h5>
                                            <p class="card-text text-[var(--text-secondary)] small text-truncate-2 mb-3">
                                                {{ $rec->MoTa ?? 'Luyện nghe tiếng Anh tương tác với các trò chơi hấp dẫn và câu hỏi phản xạ.' }}
                                            </p>
                                        </div>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="badge {{ $typeBadge }} text-xs px-2.5 py-1 rounded">
                                                    <i class="fas fa-check-circle me-1"></i> {{ $rec->recommendation_reason }}
                                                </span>
                                            </div>
                                            <a href="{{ route('user.test.show', $rec->MaBai) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                                                <i class="fas fa-play-circle me-1.5"></i> Bắt đầu luyện
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .hover-translate-y:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>

    @if($recentResults->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm">
                <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                    <h5 class="text-[var(--text-primary)] mb-0"><i class="fas fa-list"></i> Luyện tập gần đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-[var(--text-primary)]">
                            <thead>
                                <tr class="text-[var(--text-primary)]">
                                    <th class="text-[var(--text-primary)]">Bài test</th>
                                    <th class="text-[var(--text-primary)]">Ngày làm</th>
                                    <th class="text-[var(--text-primary)]">Thời gian</th>
                                    <th class="text-[var(--text-primary)]">Kết quả</th>
                                    <th class="text-[var(--text-primary)]">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentResults as $result)
                                @php
                                    $testName = $result->baitest ? $result->baitest->TenBai : 'Test ID: ' . $result->MaBai;
                                    $duration = $result->ThoiGianLam ?? 0;
                                    $minutes = floor($duration / 60);
                                    $seconds = $duration % 60;
                                    $submitDate = $result->CreatedAt ? \Carbon\Carbon::parse($result->CreatedAt)->format('d/m/Y H:i') : 'N/A';
                                @endphp
                                <tr class="text-[var(--text-primary)]">
                                    <td class="fw-bold text-[var(--text-primary)]">{{ $testName }}</td>
                                    <td class="text-[var(--text-primary)]">{{ $submitDate }}</td>
                                    <td class="text-[var(--text-primary)]">{{ sprintf("%02d:%02d", $minutes, $seconds) }}</td>
                                    <td><span class="badge bg-success">{{ $result->SoCauDung ?? 0 }}/{{ $result->TongSoCau ?? 0 }}</span></td>
                                    <td>
                                        <a href="{{ route('user.results.show', $result->MaCTLB) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection