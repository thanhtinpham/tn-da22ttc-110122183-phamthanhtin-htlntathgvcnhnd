@extends('layouts.admin')
@section('title', 'Chi tiết User')
@section('admin_content')
<div class='container mt-4'>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-circle me-2"></i> Chi tiết người dùng: <span class="text-primary">{{ $item->UserName }}</span></h2>
        <a href="{{ route('admin.user.index') }}" class='btn btn-outline-secondary'><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-0" style="border-radius: 15px;">
                <div class="card-header bg-white fw-bold text-primary pt-3 pb-2"><i class="fas fa-info-circle me-1"></i> Thông tin tài khoản</div>
                <div class="card-body">
                    <p class="mb-2"><i class="fas fa-envelope text-muted me-2" style="width: 20px;"></i> <strong>Email:</strong> {{ $item->Email }}</p>
                    <p class="mb-2"><i class="fas fa-user-shield text-muted me-2" style="width: 20px;"></i> <strong>Vai trò:</strong> <span class="badge {{ $item->Role == 'admin' ? 'bg-danger' : 'bg-primary' }}">{{ strtoupper($item->Role) }}</span></p>
                    <p class="mb-2"><i class="fas fa-calendar-alt text-muted me-2" style="width: 20px;"></i> <strong>Ngày tạo:</strong> {{ $item->CreatedAt ? $item->CreatedAt->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p class="mb-2"><i class="fas fa-sign-in-alt text-muted me-2" style="width: 20px;"></i> <strong>Đăng nhập cuối:</strong> {{ $item->LastLoginAt ? $item->LastLoginAt->format('d/m/Y H:i') : 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-0" style="border-radius: 15px;">
                <div class="card-header bg-white fw-bold text-success pt-3 pb-2"><i class="fas fa-chart-bar me-1"></i> Thành tích</div>
                <div class="card-body">
                    <h1 class="display-4 text-center text-success fw-bold mb-0">{{ $item->TongDiem ?? 0 }}</h1>
                    <p class="text-center text-muted">Tổng điểm</p>
                    <hr>
                    <p class="mb-0 text-center">Đã làm <strong>{{ $item->results->count() }}</strong> lần test.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-header bg-white fw-bold pt-4 pb-0 border-0">
            <h4 class="text-primary mb-3"><i class="fas fa-history me-2"></i>Lịch sử làm bài</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">STT</th>
                            <th>Bài test</th>
                            <th>Chủ đề</th>
                            <th>Cấp độ</th>
                            <th>Thời gian nộp</th>
                            <th>Thời gian làm</th>
                            <th class="text-center pe-4">Kết quả</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($item->results->sortByDesc('CreatedAt') as $index => $result)
                            @php
                                $testName = $result->baitest ? $result->baitest->TenBai : 'Không xác định';
                                $topicName = $result->baitest && $result->baitest->chude ? $result->baitest->chude->TenCD : 'N/A';
                                $levelName = $result->baitest && $result->baitest->capdonghe ? $result->baitest->capdonghe->TenCDN : 'N/A';
                                
                                $correctCount = $result->SoCauDung ?? 0;
                                $totalCount = $result->TongSoCau ?? 0;
                                
                                // Format thời gian làm bài (giây -> phút:giây)
                                $duration = $result->ThoiGianLam ?? 0;
                                $minutes = floor($duration / 60);
                                $seconds = $duration % 60;
                                $durationStr = $duration > 0 ? sprintf("%02d:%02d", $minutes, $seconds) : 'N/A';
                                
                                // Format ngày nộp
                                $submitDate = $result->CreatedAt ? \Carbon\Carbon::parse($result->CreatedAt)->format('d/m/Y H:i') : 'N/A';
                            @endphp
                        <tr>
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $testName }}</td>
                            <td><span class="badge bg-info text-dark">{{ $topicName }}</span></td>
                            <td><span class="badge bg-secondary">{{ $levelName }}</span></td>
                            <td>{{ $submitDate }}</td>
                            <td><span class="badge bg-light text-dark border"><i class="fas fa-clock text-warning me-1"></i> {{ $durationStr }}</span></td>
                            <td class="text-center pe-4">
                                <span class="fw-bold {{ $totalCount > 0 && $correctCount > ($totalCount/2) ? 'text-success' : 'text-danger' }}">
                                    {{ $correctCount }} / {{ $totalCount }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                Người dùng này chưa làm bài test nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
