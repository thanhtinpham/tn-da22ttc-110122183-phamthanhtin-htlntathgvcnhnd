@extends('layouts.app')

@section('title', 'Danh sách bài học')

@push('styles')
<style>
    /* Page grid background override */
    body {
        background-color: #f3f5f8 !important;
        background-image: linear-gradient(rgba(226, 232, 240, 0.6) 1px, transparent 1px), linear-gradient(90deg, rgba(226, 232, 240, 0.6) 1px, transparent 1px) !important;
        background-size: 30px 30px !important;
    }
    .dark body {
        background-color: #090d16 !important;
        background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px) !important;
    }

    .lesson-grid-card {
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 1.5rem;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .dark .lesson-grid-card {
        background: var(--brand-card-bg);
        border: 1px solid var(--brand-border);
    }
    .lesson-grid-card:hover {
        transform: translateY(-6px);
        border-color: var(--brand-secondary) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03) !important;
    }
    .dark .lesson-grid-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2 block">[ 03 / PRACTICAL EXERCISES ]</span>
            <h2 class="fw-bold text-[var(--text-primary)]" style="font-family: var(--font-display), sans-serif; font-size: 2rem;"><i class="fas fa-book-open me-2 text-[var(--brand-secondary)]"></i>Danh sách bài học</h2>
            <p class="text-[var(--text-secondary)] mt-1">Chọn bài học phù hợp với trình độ của bạn để bắt đầu thử thách</p>
        </div>
    </div>

    <div class="row">
        @foreach($lessons as $index => $lesson)
        @php
            $colors = ['success', 'primary', 'info', 'warning', 'danger'];
            $color = $colors[$index % count($colors)];
        @endphp
        <div class="col-md-4 mb-4">
            <div class="lesson-grid-card p-4 h-100 d-flex flex-col justify-between shadow-sm">
                <div>
                    <!-- Top header row -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center text-[var(--brand-secondary)] text-md shadow-sm" style="width: 44px; height: 44px; min-width: 44px;">
                                <i class="{{ $lesson->chude ? $lesson->chude->icon_class : 'fas fa-headphones' }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-[var(--text-primary)] mb-0.5" style="font-family: var(--font-display), sans-serif; font-size: 0.95rem;">{{ $lesson->TenBai }}</h6>
                                <span class="text-[9px] font-mono text-primary dark:text-[var(--brand-secondary)] uppercase tracking-wider">CHANNEL: {{ $lesson->chude ? $lesson->chude->TenCD : 'Tổng hợp' }}</span>
                            </div>
                        </div>
                        <span class="badge bg-transparent border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 px-2.5 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                            {{ $lesson->SoCauHoi }} CÂU
                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-[var(--text-secondary)] text-xs mb-3 line-clamp-2 leading-relaxed" style="min-height: 36px;">
                        {{ $lesson->MoTa ?? 'Luyện nghe tiếng Anh tương tác với các câu hỏi chi tiết và phản xạ nhạy bén.' }}
                    </p>
                </div>

                <!-- Footer button -->
                <div class="mt-2">
                    <a href="{{ route('user.test.show', $lesson->MaBai) }}" class="btn btn-primary w-100 rounded-xl py-2 fw-bold text-xs font-mono uppercase tracking-wider d-flex align-items-center justify-center gap-1.5 hover-lift" style="background-color: var(--brand-secondary); border-color: var(--brand-secondary);">
                        Start Practice <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $lessons->links() }}
        </div>
    </div>
</div>
@endsection