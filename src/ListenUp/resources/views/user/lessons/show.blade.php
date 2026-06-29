@extends('layouts.app')

@section('title', $lesson->TenBai)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.lessons') }}">Bài học</a></li>
                    <li class="breadcrumb-item active">{{ $lesson->TenBai }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm backdrop-blur-md mb-4">
                <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                    <h4 class="text-[var(--text-primary)] font-bold">{{ $lesson->TenBai }}</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">
                            {{ $lesson->MaCDN }}
                        </span>
                        <small class="text-[var(--text-secondary)]">Số câu hỏi: {{ $lesson->SoCauHoi }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-[var(--text-primary)]">{{ $lesson->MoTa }}</p>
                    
                    <!-- Transcript/Content placeholder -->
                    <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm">
                        <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                            <h6 class="text-[var(--text-primary)] font-bold"><i class="fas fa-file-alt"></i> Nội dung bài học</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-[var(--text-secondary)]">Nội dung chi tiết đang được cập nhật.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm backdrop-blur-md mb-4">
                <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                    <h6 class="text-[var(--text-primary)] font-bold"><i class="fas fa-question-circle"></i> Câu hỏi ({{ $lesson->cauhoi->count() }})</h6>
                </div>
                <div class="card-body">
                    @if($lesson->cauhoi->count() > 0)
                        <p class="text-[var(--text-primary)]">Hoàn thành việc nghe và trả lời các câu hỏi để kiểm tra hiểu biết của bạn.</p>
                        <a href="{{ route('user.test.show', $lesson->MaBai) }}" class="btn btn-success w-100 rounded-pill fw-bold">
                            <i class="fas fa-play"></i> Bắt đầu làm bài
                        </a>
                    @else
                        <p class="text-[var(--text-secondary)]">Chưa có câu hỏi cho bài học này.</p>
                    @endif
                </div>
            </div>

            <div class="card bg-[var(--brand-card-bg)] border-[var(--brand-border)] shadow-sm backdrop-blur-md">
                <div class="card-header bg-transparent border-bottom border-[var(--brand-border)]">
                    <h6 class="text-[var(--text-primary)] font-bold"><i class="fas fa-info-circle"></i> Thông tin</h6>
                </div>
                <div class="card-body">
                    <p class="text-[var(--text-primary)]"><strong class="text-[var(--text-primary)]">Chủ đề:</strong> <i class="{{ $lesson->chude->icon_class }} text-secondary me-1"></i> {{ $lesson->chude->TenCD }}</p>
                    <p class="text-[var(--text-primary)]"><strong class="text-[var(--text-primary)]">Cấp độ:</strong> {{ $lesson->MaCDN }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function startQuiz() {
    alert('Tính năng làm bài sẽ được phát triển trong phiên bản tiếp theo!');
}
</script>
@endsection