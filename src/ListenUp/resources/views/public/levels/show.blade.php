@extends('layouts.app')

@section('title', $level->TenCDN . ' - English Listening')

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

    .level-breadcrumb {
        font-family: monospace;
        font-size: 10px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    /* Modern 3D blocky card styling */
    .topic-3d-card {
        background: var(--brand-card-bg);
        border: 1.5px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.5rem;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.25s ease, box-shadow 0.25s ease;
        position: relative;
    }
    .dark .topic-3d-card {
        border-color: rgba(255, 255, 255, 0.08);
    }
    
    /* Dynamic 3D depth shadows and transformations per category */
    .topic-3d-card-cyan {
        box-shadow: 0 6px 0px rgba(6, 182, 212, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .topic-3d-card-cyan:hover {
        transform: translateY(-6px);
        border-color: #06b6d4 !important;
        box-shadow: 0 12px 0px rgba(6, 182, 212, 0.2), 0 20px 35px rgba(6, 182, 212, 0.15);
    }
    .dark .topic-3d-card-cyan {
        box-shadow: 0 6px 0px rgba(6, 182, 212, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .topic-3d-card-cyan:hover {
        box-shadow: 0 12px 0px rgba(6, 182, 212, 0.35), 0 20px 35px rgba(6, 182, 212, 0.3);
    }

    .topic-3d-card-amber {
        box-shadow: 0 6px 0px rgba(245, 158, 11, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .topic-3d-card-amber:hover {
        transform: translateY(-6px);
        border-color: #f59e0b !important;
        box-shadow: 0 12px 0px rgba(245, 158, 11, 0.2), 0 20px 35px rgba(245, 158, 11, 0.15);
    }
    .dark .topic-3d-card-amber {
        box-shadow: 0 6px 0px rgba(245, 158, 11, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .topic-3d-card-amber:hover {
        box-shadow: 0 12px 0px rgba(245, 158, 11, 0.35), 0 20px 35px rgba(245, 158, 11, 0.3);
    }

    .topic-3d-card-emerald {
        box-shadow: 0 6px 0px rgba(16, 185, 129, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .topic-3d-card-emerald:hover {
        transform: translateY(-6px);
        border-color: #10b981 !important;
        box-shadow: 0 12px 0px rgba(16, 185, 129, 0.2), 0 20px 35px rgba(16, 185, 129, 0.15);
    }
    .dark .topic-3d-card-emerald {
        box-shadow: 0 6px 0px rgba(16, 185, 129, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .topic-3d-card-emerald:hover {
        box-shadow: 0 12px 0px rgba(16, 185, 129, 0.35), 0 20px 35px rgba(16, 185, 129, 0.3);
    }

    .topic-3d-card-purple {
        box-shadow: 0 6px 0px rgba(168, 85, 247, 0.12), 0 12px 24px rgba(0, 0, 0, 0.02);
    }
    .topic-3d-card-purple:hover {
        transform: translateY(-6px);
        border-color: #a855f7 !important;
        box-shadow: 0 12px 0px rgba(168, 85, 247, 0.2), 0 20px 35px rgba(168, 85, 247, 0.15);
    }
    .dark .topic-3d-card-purple {
        box-shadow: 0 6px 0px rgba(168, 85, 247, 0.2), 0 12px 24px rgba(0, 0, 0, 0.25);
    }
    .dark .topic-3d-card-purple:hover {
        box-shadow: 0 12px 0px rgba(168, 85, 247, 0.35), 0 20px 35px rgba(168, 85, 247, 0.3);
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endpush

@section('content')
@php
    $topicColors = [
        'CD01' => [
            'cardClass' => 'topic-3d-card-cyan',
            'iconBg' => 'bg-cyan-500/10 dark:bg-cyan-500/20',
            'iconText' => 'text-cyan-500 dark:text-cyan-400',
            'hoverArrow' => 'group-hover:text-cyan-500'
        ],
        'CD02' => [
            'cardClass' => 'topic-3d-card-amber',
            'iconBg' => 'bg-amber-500/10 dark:bg-amber-500/20',
            'iconText' => 'text-amber-500 dark:text-amber-400',
            'hoverArrow' => 'group-hover:text-amber-500'
        ],
        'CD03' => [
            'cardClass' => 'topic-3d-card-emerald',
            'iconBg' => 'bg-emerald-500/10 dark:bg-emerald-500/20',
            'iconText' => 'text-emerald-500 dark:text-emerald-400',
            'hoverArrow' => 'group-hover:text-emerald-500'
        ],
        'default' => [
            'cardClass' => 'topic-3d-card-purple',
            'iconBg' => 'bg-purple-500/10 dark:bg-purple-500/20',
            'iconText' => 'text-purple-500 dark:text-purple-400',
            'hoverArrow' => 'group-hover:text-purple-500'
        ]
    ];
@endphp

<div class="max-w-7xl mx-auto px-6 py-12 relative">
    
    <!-- Minimal Breadcrumb -->
    <nav class="mb-8" aria-label="breadcrumb">
        <ol class="flex items-center gap-2.5 level-breadcrumb text-[var(--text-muted)] p-0 m-0 list-none">
            <li>
                <a href="/" class="hover:text-[var(--brand-secondary)] transition-colors">TRANG CHỦ</a>
            </li>
            <li>/</li>
            <li>
                <a href="{{ route('public.levels') }}" class="hover:text-[var(--brand-secondary)] transition-colors">CẤP ĐỘ</a>
            </li>
            <li>/</li>
            <li class="text-[var(--text-primary)] font-bold">{{ $level->TenCDN }}</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="mb-12 relative">
        <div class="editorial-label mb-2">MODULE 02 // STUDY_SPACE</div>
        <h1 class="text-4xl md:text-5xl editorial-title mb-3">{{ $level->TenCDN }}</h1>
        <p class="text-[var(--text-secondary)] text-sm md:text-base max-w-2xl font-sans mt-2">{{ $level->MoTaCDN }}</p>
        <div class="w-full border-t border-[var(--brand-border)] mt-8"></div>
    </div>

    <!-- Title and Grid -->
    <div class="relative">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-xs font-mono text-[var(--brand-secondary)] tracking-widest uppercase m-0">CHỌN CHỦ ĐỀ NGHE / SELECT TOPIC</h4>
            <span class="text-[9px] font-mono text-[var(--text-muted)] tracking-wider">TOTAL: {{ count($topics) }} TOPICS</span>
        </div>
        
        <!-- Grid with Corner Decors -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative py-2">
            <!-- Align marks -->
            <span class="absolute -top-3 -left-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>
            <span class="absolute -top-3 -right-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>
            <span class="absolute -bottom-3 -left-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>
            <span class="absolute -bottom-3 -right-2 font-mono text-[10px] text-[var(--text-muted)] opacity-50">+</span>

            @foreach($topics as $topic)
            @php
                $color = $topicColors[$topic->MaCD] ?? $topicColors['default'];
            @endphp
            <div>
                <a href="{{ route('public.topics.show', [$level->MaCDN, $topic->MaCD]) }}" class="block text-decoration-none group">
                    <div class="topic-3d-card p-4 flex items-center justify-between gap-4 {{ $color['cardClass'] }} backdrop-blur-md">
                        <div class="flex items-center gap-4">
                            <!-- Beautiful Colored Icon Wrapper -->
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition-transform duration-300 group-hover:scale-110 {{ $color['iconBg'] }} {{ $color['iconText'] }}" style="width: 48px; height: 48px;">
                                <i class="{{ $topic->icon_class }} text-base"></i>
                            </div>
                            
                            <!-- Text Details -->
                            <div>
                                <h5 class="text-sm font-bold text-[var(--text-primary)] group-hover:text-[var(--brand-secondary)] transition-colors mb-0.5">{{ $topic->TenCD }}</h5>
                                <p class="text-xs text-[var(--text-secondary)] mb-0 font-sans line-clamp-1">{{ $topic->MoTa }}</p>
                            </div>
                        </div>
                        
                        <!-- Hover indicator arrow -->
                        <div class="text-[var(--text-muted)] {{ $color['hoverArrow'] }} group-hover:translate-x-1.5 transition-all duration-300 shrink-0">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Uncategorized Lessons Section -->
    @if(isset($uncategorizedLessons) && $uncategorizedLessons->isNotEmpty())
    <div class="mt-16 border-t border-[var(--brand-border)] pt-12 relative">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-xs font-mono text-[var(--brand-secondary)] tracking-widest uppercase m-0">BÀI LÀM TỰ DO / GENERAL LESSONS</h4>
            <span class="text-[9px] font-mono text-[var(--text-muted)] tracking-wider">TOTAL: {{ count($uncategorizedLessons) }} LESSONS</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-2">
            @foreach($uncategorizedLessons as $lesson)
            <div class="bg-white dark:bg-[#1e293b] border border-slate-200/60 dark:border-slate-800 p-6 rounded-3xl transition-all duration-300 hover:-translate-y-1.5 hover:border-[var(--brand-secondary)] hover:shadow-lg flex flex-col justify-between h-full">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-[var(--brand-secondary)] flex items-center justify-center shrink-0">
                            <i class="fas fa-headphones text-base"></i>
                        </div>
                        <span class="badge bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2.5 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-wider">
                            {{ $lesson->SoCauHoi }} CÂU
                        </span>
                    </div>
                    
                    <h5 class="text-sm font-bold text-[var(--text-primary)] mb-1">{{ $lesson->TenBai }}</h5>
                    <p class="text-xs text-[var(--text-secondary)] font-sans line-clamp-2 mb-4">{{ $lesson->MoTa ?? 'Không có mô tả cho bài test này.' }}</p>
                </div>
                
                <div class="mt-auto pt-2">
                    @auth
                        <a href="{{ route('user.test.show', $lesson->MaBai) }}" class="block w-full text-center text-xs font-mono uppercase tracking-wider py-2.5 px-4 rounded-xl font-bold bg-[var(--brand-secondary)] hover:bg-[var(--brand-secondary-hover, #6d28d9)] text-white no-underline transition-all duration-200">
                            BẮT ĐẦU LUYỆN <i class="fas fa-arrow-right text-[9px] ms-1.5"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center text-xs font-mono uppercase tracking-wider py-2.5 px-4 rounded-xl font-bold border border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400 no-underline hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                            ĐĂNG NHẬP <i class="fas fa-lock text-[9px] ms-1.5"></i>
                        </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
