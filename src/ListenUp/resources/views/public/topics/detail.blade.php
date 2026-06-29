@extends('layouts.app')

@section('title', 'Chủ đề: ' . $topic->TenCD)

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

    /* Editorial typography & custom styling */
    .editorial-label {
        font-family: monospace;
        font-size: 10px;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--brand-secondary);
    }
    
    .editorial-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text-primary);
    }

    .topic-breadcrumb {
        font-family: monospace;
        font-size: 10px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    /* Modern 3D blocky card styling */
    .lesson-3d-card {
        background: var(--brand-card-bg);
        border: 1.5px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.5rem;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.25s ease, box-shadow 0.25s ease;
        position: relative;
    }
    .dark .lesson-3d-card {
        border-color: rgba(255, 255, 255, 0.08);
    }
    
    /* Dynamic 3D depth shadows and transformations per topic theme */
    .lesson-3d-card-cyan {
        box-shadow: 0 6px 0px rgba(6, 182, 212, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .lesson-3d-card-cyan:hover {
        transform: translateY(-6px);
        border-color: #06b6d4 !important;
        box-shadow: 0 12px 0px rgba(6, 182, 212, 0.2), 0 20px 35px rgba(6, 182, 212, 0.15);
    }
    .dark .lesson-3d-card-cyan {
        box-shadow: 0 6px 0px rgba(6, 182, 212, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .lesson-3d-card-cyan:hover {
        box-shadow: 0 12px 0px rgba(6, 182, 212, 0.35), 0 20px 35px rgba(6, 182, 212, 0.3);
    }

    .lesson-3d-card-amber {
        box-shadow: 0 6px 0px rgba(245, 158, 11, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .lesson-3d-card-amber:hover {
        transform: translateY(-6px);
        border-color: #f59e0b !important;
        box-shadow: 0 12px 0px rgba(245, 158, 11, 0.2), 0 20px 35px rgba(245, 158, 11, 0.15);
    }
    .dark .lesson-3d-card-amber {
        box-shadow: 0 6px 0px rgba(245, 158, 11, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .lesson-3d-card-amber:hover {
        box-shadow: 0 12px 0px rgba(245, 158, 11, 0.35), 0 20px 35px rgba(245, 158, 11, 0.3);
    }

    .lesson-3d-card-emerald {
        box-shadow: 0 6px 0px rgba(16, 185, 129, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .lesson-3d-card-emerald:hover {
        transform: translateY(-6px);
        border-color: #10b981 !important;
        box-shadow: 0 12px 0px rgba(16, 185, 129, 0.2), 0 20px 35px rgba(16, 185, 129, 0.15);
    }
    .dark .lesson-3d-card-emerald {
        box-shadow: 0 6px 0px rgba(16, 185, 129, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .lesson-3d-card-emerald:hover {
        box-shadow: 0 12px 0px rgba(16, 185, 129, 0.35), 0 20px 35px rgba(16, 185, 129, 0.3);
    }

    .lesson-3d-card-purple {
        box-shadow: 0 6px 0px rgba(168, 85, 247, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .lesson-3d-card-purple:hover {
        transform: translateY(-6px);
        border-color: #a855f7 !important;
        box-shadow: 0 12px 0px rgba(168, 85, 247, 0.2), 0 20px 35px rgba(168, 85, 247, 0.15);
    }
    .dark .lesson-3d-card-purple {
        box-shadow: 0 6px 0px rgba(168, 85, 247, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .lesson-3d-card-purple:hover {
        box-shadow: 0 12px 0px rgba(168, 85, 247, 0.35), 0 20px 35px rgba(168, 85, 247, 0.3);
    }
</style>
@endpush

@section('content')
@php
    $topicId = $topic->MaCD ?? '';
    $themeColors = [
        'CD01' => [
            'cardClass' => 'lesson-3d-card-cyan',
            'iconBg' => 'bg-cyan-500/10 dark:bg-cyan-500/20',
            'iconText' => 'text-cyan-500 dark:text-cyan-400',
            'btnClass' => 'bg-cyan-500 hover:bg-cyan-600 border-cyan-500 hover:shadow-cyan-500/20'
        ],
        'CD02' => [
            'cardClass' => 'lesson-3d-card-amber',
            'iconBg' => 'bg-amber-500/10 dark:bg-amber-500/20',
            'iconText' => 'text-amber-500 dark:text-amber-400',
            'btnClass' => 'bg-amber-500 hover:bg-amber-600 border-amber-500 hover:shadow-amber-500/20'
        ],
        'CD03' => [
            'cardClass' => 'lesson-3d-card-emerald',
            'iconBg' => 'bg-emerald-500/10 dark:bg-emerald-500/20',
            'iconText' => 'text-emerald-500 dark:text-emerald-400',
            'btnClass' => 'bg-emerald-500 hover:bg-emerald-600 border-emerald-500 hover:shadow-emerald-500/20'
        ],
        'default' => [
            'cardClass' => 'lesson-3d-card-purple',
            'iconBg' => 'bg-purple-500/10 dark:bg-purple-500/20',
            'iconText' => 'text-purple-500 dark:text-purple-400',
            'btnClass' => 'bg-[var(--brand-secondary)] hover:bg-[var(--brand-secondary)]/90 border-[var(--brand-secondary)]'
        ]
    ];
    $theme = $themeColors[$topicId] ?? $themeColors['default'];
@endphp

<div class="max-w-7xl mx-auto px-6 py-12 relative">
    
    <!-- Minimal Breadcrumb -->
    <nav class="mb-8" aria-label="breadcrumb">
        <ol class="flex items-center gap-2.5 topic-breadcrumb text-[var(--text-muted)] p-0 m-0 list-none">
            <li>
                <a href="{{ route('public.topics') }}" class="hover:text-[var(--brand-secondary)] transition-colors">CHỦ ĐỀ NGHE</a>
            </li>
            <li>/</li>
            <li class="text-[var(--text-primary)] font-bold">{{ $topic->TenCD }}</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="mb-12 relative">
        <div class="editorial-label mb-2">TOPIC // {{ strtoupper($topic->TenCD) }}</div>
        <h1 class="text-4xl md:text-5xl editorial-title mb-3 flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $theme['iconBg'] }} {{ $theme['iconText'] }}" style="width: 48px; height: 48px;">
                <i class="{{ $topic->icon_class }} text-xl"></i>
            </div>
            Chủ đề: {{ $topic->TenCD }}
        </h1>
        <p class="text-[var(--text-secondary)] text-sm md:text-base max-w-2xl font-sans mt-2">{{ $topic->MoTa }}</p>
        <div class="w-full border-t border-[var(--brand-border)] mt-8"></div>
    </div>

    <!-- Title and Grid -->
    <div class="relative">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-xs font-mono text-[var(--brand-secondary)] tracking-widest uppercase m-0">DANH SÁCH BÀI NGHE / LESSONS LIST</h4>
            <span class="text-[9px] font-mono text-[var(--text-muted)] tracking-wider">TOTAL: {{ count($lessons) }} LESSONS</span>
        </div>
        
        <!-- Grid with Corner Decors -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative py-2">
            <!-- Align marks -->
            <span class="absolute -top-3 -left-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>
            <span class="absolute -top-3 -right-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>
            <span class="absolute -bottom-3 -left-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>
            <span class="absolute -bottom-3 -right-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>

            @forelse($lessons as $lesson)
            <div>
                <div class="lesson-3d-card p-4 d-flex flex-column justify-content-between shadow-sm h-100 {{ $theme['cardClass'] }} backdrop-blur-md">
                    <div>
                        <!-- Top Header row -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 {{ $theme['iconBg'] }} {{ $theme['iconText'] }}" style="width: 44px; height: 44px;">
                                    <i class="fas fa-headphones"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-[var(--text-primary)] mb-0.5">{{ $lesson->TenBai }}</h5>
                                    <span class="text-[9px] font-mono text-[var(--brand-secondary)] uppercase tracking-wider">LEVEL: {{ $lesson->capdonghe ? strtoupper($lesson->capdonghe->TenCDN) : 'N/A' }}</span>
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
                    
                    <!-- Action button -->
                    <div class="shrink-0">
                        @auth
                            <a href="{{ route('user.test.show', $lesson->MaBai) }}" class="btn text-white w-100 rounded-xl py-2 fw-bold text-xs font-mono uppercase tracking-wider d-flex align-items-center justify-center gap-1.5 hover-lift {{ $theme['btnClass'] }}">
                                START <i class="fas fa-arrow-right text-[9px]"></i>
                            </a>
                        @else
                            <button onclick="openAuthModal('login')" class="btn btn-outline-secondary w-100 rounded-xl py-2 fw-bold text-xs font-mono uppercase tracking-wider d-flex align-items-center justify-center gap-1.5 focus:outline-none">
                                LOG IN <i class="fas fa-lock text-[9px]"></i>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="p-4 bg-[var(--brand-card-bg)] border border-[var(--brand-border)] rounded-xl text-center text-xs font-mono text-[var(--text-secondary)]">
                    <i class="fas fa-info-circle mr-1.5 text-[var(--brand-secondary)]"></i> Hiện tại chưa có bài nghe nào trong chủ đề này.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
