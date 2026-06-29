@extends('layouts.app')

@section('title', $topic->TenCD . ' - ' . $level->TenCDN)

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

    .lesson-grid-icon-wrapper {
        width: 44px;
        height: 44px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 12px;
        background: white;
        color: var(--brand-secondary);
        transition: all 0.3s ease;
    }
    .dark .lesson-grid-icon-wrapper {
        background: #1e293b;
        border-color: var(--brand-border);
    }
    .lesson-grid-card:hover .lesson-grid-icon-wrapper {
        background: var(--brand-secondary);
        color: white;
        border-color: var(--brand-secondary);
        box-shadow: 0 0 15px rgba(124, 58, 237, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-family: monospace; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-[var(--brand-secondary)] text-decoration-none">TRANG CHỦ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('public.levels') }}" class="text-[var(--brand-secondary)] text-decoration-none">CẤP ĐỘ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('public.levels.show', $level->MaCDN) }}" class="text-[var(--brand-secondary)] text-decoration-none">{{ strtoupper($level->TenCDN) }}</a></li>
            <li class="breadcrumb-item active text-[var(--text-primary)] font-bold" aria-current="page">{{ strtoupper($topic->TenCD) }}</li>
        </ol>
    </nav>

    <div class="mb-5">
        <span class="text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2 block">TOPIC // {{ strtoupper($topic->TenCD) }}</span>
        <h1 class="fw-bold d-flex align-items-center gap-3 text-[var(--text-primary)]" style="font-family: var(--font-display), sans-serif; font-size: 2.25rem;">
            <i class="{{ $topic->icon_class }} text-[var(--brand-secondary)] me-2"></i>
            <span>{{ $topic->TenCD }}</span>
            <span class="text-[var(--text-secondary)] fs-4 ms-2">in {{ $level->TenCDN }}</span>
        </h1>
        <p class="text-[var(--text-secondary)] mt-2">{{ $topic->MoTa ?? 'Danh sách các bài luyện nghe thuộc chủ đề này.' }}</p>
    </div>

    <div class="row">
        @foreach($lessons as $lesson)
        <div class="col-md-4 mb-4">
            <div class="lesson-grid-card p-4 h-100 d-flex flex-col justify-between shadow-sm">
                <div>
                    <!-- Top header row -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="lesson-grid-icon-wrapper flex items-center justify-center shrink-0">
                                <i class="fas fa-headphones"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-[var(--text-primary)] mb-0.5">{{ $lesson->TenBai }}</h5>
                                <span class="text-[9px] font-mono text-[var(--brand-secondary)] uppercase tracking-wider">LEVEL: {{ strtoupper($level->TenCDN) }}</span>
                            </div>
                        </div>
                        <span class="badge bg-transparent border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 px-2.5 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                            {{ $lesson->SoCauHoi }} CÂU
                        </span>
                    </div>

                    
                </div>

                <!-- Footer button -->
                <div class="mt-2">
                    @auth
                        <a href="{{ route('user.test.show', $lesson->MaBai) }}" class="btn btn-primary w-100 rounded-xl py-2 fw-bold text-xs font-mono uppercase tracking-wider d-flex align-items-center justify-center gap-1.5 hover-lift" style="background-color: var(--brand-secondary); border-color: var(--brand-secondary);">
                            START PRACTICE <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 rounded-xl py-2 fw-bold text-xs font-mono uppercase tracking-wider d-flex align-items-center justify-center gap-1.5 focus:outline-none">
                            LOG IN <i class="fas fa-lock text-[9px]"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach

        @if($lessons->isEmpty())
            <div class="col-12 text-center py-5">
                <div class="opacity-50 mb-3 text-[var(--text-secondary)]">
                    <i class="fas fa-folder-open fa-4x"></i>
                </div>
                <h5 class="text-[var(--text-secondary)]">Chưa có bài học nào trong mục này.</h5>
            </div>
        @endif
    </div>
</div>
@endsection
