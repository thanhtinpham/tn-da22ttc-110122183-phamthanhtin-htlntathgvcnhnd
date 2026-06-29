@extends('layouts.app')

@section('title', 'Luyện tập: ' . $lesson->TenBai)

@section('content')
<div class="container-fluid pt-5 pb-10 px-4">
    <div class="row">
        <!-- Left Menu: Topics -->
        <div class="col-lg-2 mb-4 position-relative" style="z-index: 50;">
            <div class="card bg-[var(--brand-card-bg)] border border-[var(--brand-border)] shadow-sm backdrop-blur-md" style="border-radius: 15px;">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-[var(--brand-secondary)] mb-3"><i class="fas fa-list me-2"></i>Chủ đề nghe</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="nav flex-column nav-pills">
                        @if(isset($topics))
                            @foreach($topics as $topic)
                                <div class="topic-item-container mb-2">
                                    <a href="{{ route('public.topics.detail', $topic->MaCD ?? $topic->id) }}" class="nav-link {{ isset($lesson) && $lesson->MaCD == ($topic->MaCD ?? $topic->id) ? 'active shadow-sm' : 'text-[var(--text-primary)]' }} rounded-3 d-flex align-items-center justify-content-between" style="transition: all 0.2s;">
                                        <span>
                                            <i class="{{ $topic->icon_class }} me-2"></i> {{ $topic->TenCD ?? $topic->name }}
                                        </span>
                                        <i class="fas fa-chevron-right text-[10px] opacity-40"></i>
                                    </a>
                                    
                                    <!-- Popover Dropdown for Tests of this Topic -->
                                    <div class="topic-tests-dropdown">
                                        <div class="fw-bold text-[var(--brand-secondary)] mb-2 px-2 pb-1 border-bottom border-slate-100 dark:border-slate-800 text-xs uppercase tracking-wider">
                                            Bài test: {{ $topic->TenCD ?? $topic->name }}
                                        </div>
                                        <div class="d-flex flex-column gap-1 max-h-[300px] overflow-y-auto custom-scrollbar">
                                            @if($topic->baitests->count() > 0)
                                                @foreach($topic->baitests as $t)
                                                    <a href="{{ route('user.test.show', $t->MaBai) }}" class="test-item-link {{ isset($lesson) && $lesson->MaBai == $t->MaBai ? 'active-test' : '' }}">
                                                        <i class="fas fa-headphones text-xs me-2"></i>{{ $t->TenBai }}
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-[var(--text-secondary)] px-2 py-1.5 italic">Chưa có bài test nào</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column: Test Content -->
        <div class="col-lg-7 mb-4">
            <!-- Header -->
            <div class="card bg-[var(--brand-card-bg)] border border-[var(--brand-border)] shadow-sm mb-3 overflow-hidden backdrop-blur-md" style="border-radius: 12px;">
                <div class="card-body py-2.5 px-4 bg-[var(--brand-bg)]/30 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <h4 class="fw-bold text-[var(--text-primary)] mb-0" style="font-size: 1.1rem; font-family: var(--font-display), sans-serif;">{{ $lesson->TenBai }}</h4>
                        <span class="badge bg-primary px-2.5 py-1.5 rounded-pill text-[11px] d-inline-flex align-items-center gap-1">
                            <i class="{{ $lesson->chude ? $lesson->chude->icon_class : 'fas fa-headphones' }}"></i> {{ $lesson->chude ? $lesson->chude->TenCD : 'Tổng hợp' }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @if($lesson->MoTa)
                            <small class="text-[var(--text-secondary)] d-none d-md-inline text-xs">{{ $lesson->MoTa }}</small>
                            <span class="text-slate-300 d-none d-md-inline">|</span>
                        @endif
                        <small class="text-[var(--text-secondary)] fw-semibold text-xs"><i class="fas fa-question-circle me-1"></i>{{ $lesson->phan->sum(function($p) { return $p->cauhoi->count(); }) }} câu hỏi</small>
                    </div>
                </div>
            </div>

            <!-- Questions Form -->
            <form action="{{ route('user.test.submit', $lesson->MaBai) }}" method="POST">
                @csrf
                <input type="hidden" name="start_time" value="{{ time() }}">
                <div class="tab-content" id="v-pills-tabContent">
                    @php $globalQuestionIndex = 1; @endphp
                    @foreach($lesson->phan as $index => $phan)
                    
                    <!-- Phan Section (Tab Pane) -->
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                         id="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}" 
                         role="tabpanel" 
                         aria-labelledby="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}-tab">
                         
                        <div class="card bg-[var(--brand-card-bg)] border border-[var(--brand-border)] shadow-sm mb-3 backdrop-blur-md" style="border-radius: 15px;">
                            <div class="card-header bg-transparent py-2 px-3.5 border-bottom-0">
                                <h4 class="fw-bold text-[var(--brand-secondary)] mb-1">{{ $phan->TenPhan }}</h4>
                                @if($phan->MoTaPhan)
                                    <p class="text-[var(--text-secondary)] mb-0">{{ $phan->MoTaPhan }}</p>
                                @endif
                            </div>
                            
                            <!-- Audio Player for this Phan -->
                            @if($phan->tepamthanh)
                            <div class="card-body px-4 py-0">
                                <!-- Custom Audio Decoder Card matching media__1781608791159.png -->
                                <div class="audio-decoder-card py-3 px-3.5 mb-3 bg-[#f8fafc] dark:bg-[var(--brand-card-bg)] border border-slate-200 dark:border-slate-800 rounded-3xl shadow-[0_10px_30px_rgba(0,0,0,0.03)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)] relative overflow-hidden">
                                    <!-- Hidden native audio element -->
                                    <audio id="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}" class="d-none">
                                        <source src="{{ asset('storage/' . $phan->tepamthanh->DuongDan) }}" type="audio/mpeg">
                                    </audio>

                                    <!-- Top header row -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center text-primary dark:text-[var(--brand-secondary)] text-sm shadow-sm" style="width: 40px; height: 40px; min-width: 40px;">
                                                <i class="fas fa-headphones"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-[var(--text-primary)] mb-0.5" style="font-family: var(--font-display), sans-serif; font-size: 1rem;">Audio Decoder</h5>
                                                <span class="text-[10px] font-mono text-primary dark:text-[var(--brand-secondary)] uppercase tracking-wider">CHANNEL: {{ $lesson->chude ? $lesson->chude->TenCD : 'Tổng hợp' }} // {{ $lesson->capdonghe ? $lesson->capdonghe->TenCDN : 'Tổng quát' }}</span>
                                            </div>
                                        </div>
                                        <span class="badge bg-transparent border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 px-3 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-wider flex items-center gap-1.5 audio-status-badge">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 status-dot"></span> STREAMING
                                        </span>
                                    </div>

                                    <!-- Media controls -->
                                    <div class="d-flex justify-content-center align-items-center gap-3.5 mb-2 mt-0">
                                        <!-- Rewind Button -->
                                        <button type="button" class="btn btn-link text-slate-400 dark:text-slate-600 hover:text-primary dark:hover:text-white p-0 btn-rewind" data-target="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}">
                                            <i class="fas fa-step-backward text-lg"></i>
                                        </button>
                                        
                                        <!-- Play/Pause toggle -->
                                        <button type="button" class="rounded-full bg-primary dark:bg-[var(--brand-secondary)] text-white shadow-md d-flex align-items-center justify-center btn-play-pause" style="width: 44px; height: 44px; border: none; font-size: 1.1rem;" data-target="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}">
                                            <i class="fas fa-play"></i>
                                        </button>

                                        <!-- Fast Forward Button -->
                                        <button type="button" class="btn btn-link text-slate-400 dark:text-slate-600 hover:text-primary dark:hover:text-white p-0 btn-forward" data-target="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}">
                                            <i class="fas fa-step-forward text-lg"></i>
                                        </button>
                                    </div>

                                    <!-- Horizontal Progress Bar container -->
                                    <div class="p-2 bg-slate-100/50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 rounded-xl mb-1.5">
                                        <div class="audio-progress-container py-2 position-relative" style="cursor: pointer;" data-target="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}">
                                            <div class="progress bg-slate-200 dark:bg-slate-700 rounded-pill" style="height: 6px;">
                                                <div class="progress-bar bg-primary dark:bg-[var(--brand-secondary)] rounded-pill" role="progressbar" style="width: 0%;"></div>
                                            </div>
                                            <div class="progress-handle position-absolute rounded-full bg-primary dark:bg-[var(--brand-secondary)]" style="width: 12px; height: 12px; top: 5px; left: 0%; transform: translateX(-50%); transition: left 0.1s ease; border: 2px solid white; display: none;"></div>
                                        </div>
                                    </div>

                                    <!-- Duration Labels -->
                                    <div class="d-flex justify-content-between px-1 mb-2">
                                        <small class="text-[var(--text-secondary)] font-mono current-time">00:00</small>
                                        <small class="text-[var(--text-secondary)] font-mono duration-time">00:00</small>
                                    </div>
                                    
                                    <!-- Speed Control & Info below -->
                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-1.5 border-top border-slate-200 dark:border-slate-800 flex-wrap gap-2">
                                        <div>
                                            <small class="text-[var(--text-secondary)]">Giới hạn: {{ $phan->tepamthanh->GioiHanPhat }} lần phát</small>
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 playback-speed-control" data-target="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}">
                                            <small class="text-[var(--text-secondary)] me-1"><i class="fas fa-running me-1"></i>Tốc độ:</small>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 text-xs rounded-pill speed-btn active" data-speed="1.0" style="font-size: 0.75rem;">1.0x</button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 text-xs rounded-pill speed-btn" data-speed="0.75" style="font-size: 0.75rem;">0.75x</button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 text-xs rounded-pill speed-btn" data-speed="1.25" style="font-size: 0.75rem;">1.25x</button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 text-xs rounded-pill speed-btn" data-speed="1.5" style="font-size: 0.75rem;">1.5x</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Questions for this Phan -->
                            <div class="card-body p-4 pt-2">
                                @foreach($phan->cauhoi as $question)
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-2 text-[var(--text-primary)]">
                                        <span class="text-[var(--brand-secondary)] me-2">Câu {{ $globalQuestionIndex++ }}:</span>
                                        {{ $question->NDCauHoi }}
                                    </h5>
                                    
                                    <div class="options-list row g-3">
                                        @foreach($question->phuongancauhoi->sortBy('NDPA') as $option)
                                        <div class="col-md">
                                            <div class="form-check custom-option p-3 border border-[var(--brand-border)] rounded-3 transition-all text-[var(--text-primary)] h-100">
                                                <input class="form-check-input ms-0 me-3" type="radio" name="question_{{ trim($question->MaCauHoi ?? $question->id) }}" id="option_{{ trim($option->MaPA ?? $option->id) }}" value="{{ trim($option->MaPA ?? $option->id) }}">
                                                <label class="form-check-label d-block cursor-pointer" for="option_{{ trim($option->MaPA ?? $option->id) }}">
                                                    {{ $option->NDPA }}
                                                    @if($option->HinhAnh)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $option->HinhAnh) }}" alt="Option Image" style="max-height: 150px; object-fit: contain;" class="border rounded mx-auto">
                                                        </div>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-5 mb-5 d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-primary btn-lg px-4 py-3 shadow-sm" style="border-radius: 30px;" id="btn-prev-part" style="display: none;">
                        <i class="fas fa-arrow-left me-2"></i> Phần trước
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-lg px-4 py-3 shadow-sm" style="border-radius: 30px;" id="btn-next-part">
                        Phần tiếp theo <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-sm" style="border-radius: 30px;" id="btn-submit-test">
                        <i class="fas fa-paper-plane me-2"></i> Nộp bài
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Menu: Test Parts Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card bg-[var(--brand-card-bg)] border border-[var(--brand-border)] shadow-sm backdrop-blur-md" style="border-radius: 15px; position: sticky; top: 20px;">
                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-[var(--brand-secondary)] mb-3"><i class="fas fa-tasks me-2"></i>Các phần test</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($lesson->phan as $index => $phan)
                            <button class="nav-link text-start {{ $index === 0 ? 'active shadow-sm' : 'text-[var(--text-primary)]' }} mb-3 rounded-3 border border-[var(--brand-border)] p-3 custom-tab-btn" 
                                    id="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}-tab" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-part-{{ $phan->MaPhan ?? $phan->id }}" 
                                    type="button" role="tab" 
                                    aria-controls="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}" 
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ $phan->TenPhan }}</span>
                                    <span class="badge bg-[var(--brand-bg)] text-[var(--brand-secondary)] rounded-pill border border-[var(--brand-border)]">{{ $phan->cauhoi->count() }} câu</span>
                                </div>
                                @if($phan->MoTaPhan)
                                    <small class="text-[var(--text-secondary)] d-block mt-2 text-truncate">{{ $phan->MoTaPhan }}</small>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Topic Popover Dropdown Menu */
    .topic-item-container {
        position: relative;
    }
    .topic-tests-dropdown {
        position: absolute;
        left: 100%;
        top: 0;
        min-width: 280px;
        max-width: 340px;
        background: var(--brand-card-bg);
        border: 1px solid var(--brand-border);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 12px;
        z-index: 1050;
        margin-left: 12px;
        visibility: hidden;
        opacity: 0;
        transform: translateX(10px);
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        backdrop-filter: blur(15px);
    }
    .topic-item-container:hover .topic-tests-dropdown {
        visibility: visible;
        opacity: 1;
        transform: translateX(0);
    }
    .dark .topic-tests-dropdown {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }
    .test-item-link {
        display: block;
        padding: 8px 12px;
        color: var(--text-primary);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.15s ease;
        border-left: 3px solid transparent;
        white-space: normal;
        word-wrap: break-word;
    }
    .test-item-link:hover {
        background-color: var(--brand-bg);
        color: var(--brand-secondary) !important;
        border-left-color: var(--brand-secondary);
        padding-left: 16px;
    }
    .test-item-link.active-test {
        background-color: var(--brand-bg);
        color: var(--brand-secondary) !important;
        border-left-color: var(--brand-secondary);
        font-weight: 700;
    }

    /* Reduce top padding under navigation header */
    .main-content-wrapper {
        padding-top: 90px !important;
    }
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

    /* Elevated floating grid layout cards */
    .card {
        border-radius: 1.5rem !important;
        border: 1px solid var(--brand-border) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
        background: var(--brand-card-bg) !important;
    }
    .dark .card {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
    }

    .custom-option {
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        border-radius: 1rem !important;
    }
    .custom-option:hover {
        background-color: var(--brand-bg) !important;
        border-color: var(--brand-secondary) !important;
    }
    .form-check-input:checked + .form-check-label {
        font-weight: bold;
        color: var(--brand-secondary);
    }
    .form-check-input {
        cursor: pointer;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .custom-option input[type="radio"] {
        margin-top: 0.3rem;
    }
    
    /* Custom Sidebar Styles */
    .nav-pills .nav-link {
        color: var(--text-primary);
        border-radius: 0.75rem !important;
    }
    .nav-pills .nav-link:hover {
        background-color: var(--brand-bg);
    }
    .nav-pills .nav-link.active {
        background-color: var(--brand-secondary) !important;
        color: #fff !important;
    }
    .custom-tab-btn.active .badge {
        background-color: var(--brand-bg) !important;
        color: var(--brand-secondary) !important;
    }
    .custom-tab-btn.active small.text-[var(--text-secondary)] {
        color: #e9ecef !important;
    }

    /* Waveform interaction & play center button styles */
    .play-center-btn {
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .play-center-btn:hover {
        transform: scale(1.08) rotate(5deg) !important;
    }
    .play-center-btn.playing {
        box-shadow: 0 0 25px var(--brand-secondary) !important;
        border-color: var(--brand-secondary) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Option selection
        document.querySelectorAll('.custom-option').forEach(item => {
            item.addEventListener('click', function(e) {
                const radio = this.querySelector('input[type="radio"]');
                if (radio && e.target !== radio) {
                    radio.checked = true;
                }
            });
        });

        // Part Navigation Logic
        const tabBtns = Array.from(document.querySelectorAll('.custom-tab-btn'));
        const btnPrev = document.getElementById('btn-prev-part');
        const btnNext = document.getElementById('btn-next-part');
        const btnSubmit = document.getElementById('btn-submit-test');
        
        let currentIndex = 0;

        function updateButtons() {
            if (tabBtns.length <= 1) {
                btnPrev.style.display = 'none';
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-block';
                return;
            }

            if (currentIndex === 0) {
                btnPrev.style.display = 'none';
                btnNext.style.display = 'inline-block';
                btnSubmit.style.display = 'none';
            } else if (currentIndex === tabBtns.length - 1) {
                btnPrev.style.display = 'inline-block';
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-block';
            } else {
                btnPrev.style.display = 'inline-block';
                btnNext.style.display = 'inline-block';
                btnSubmit.style.display = 'none';
            }
        }

        tabBtns.forEach((btn, index) => {
            btn.addEventListener('shown.bs.tab', function () {
                currentIndex = index;
                updateButtons();
            });
        });

        if (btnNext) {
            btnNext.addEventListener('click', function() {
                if (currentIndex < tabBtns.length - 1) {
                    let nextTab = new bootstrap.Tab(tabBtns[currentIndex + 1]);
                    nextTab.show();
                }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function() {
                if (currentIndex > 0) {
                    let prevTab = new bootstrap.Tab(tabBtns[currentIndex - 1]);
                    prevTab.show();
                }
            });
        }

        // Custom Audio Players Logic
        document.querySelectorAll('.audio-decoder-card').forEach(playerCard => {
            const audio = playerCard.querySelector('audio');
            if (!audio) return;
            
            const btnPlayPause = playerCard.querySelector('.btn-play-pause');
            const playCenterBtn = playerCard.querySelector('.play-center-btn');
            const btnRewind = playerCard.querySelector('.btn-rewind');
            const btnForward = playerCard.querySelector('.btn-forward');
            const currentTimeLabel = playerCard.querySelector('.current-time');
            const durationTimeLabel = playerCard.querySelector('.duration-time');
            const statusBadge = playerCard.querySelector('.audio-status-badge');
            const statusDot = playerCard.querySelector('.status-dot');
            const speedBtns = playerCard.querySelectorAll('.speed-btn');
            
            let isPlaying = false;

            function formatTime(secs) {
                if (isNaN(secs) || secs === Infinity) return '00:00';
                const m = Math.floor(secs / 60);
                const s = Math.floor(secs % 60);
                return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }

            function togglePlay() {
                if (audio.paused) {
                    // Pause all other audio elements on the page first
                    document.querySelectorAll('audio').forEach(a => {
                        if (a !== audio) {
                            a.pause();
                            a.dispatchEvent(new Event('pause'));
                        }
                    });
                    
                    audio.play();
                } else {
                    audio.pause();
                }
            }

            // Bind click events to play buttons
            btnPlayPause.addEventListener('click', togglePlay);
            if (playCenterBtn) playCenterBtn.addEventListener('click', togglePlay);

            btnRewind.addEventListener('click', () => {
                audio.currentTime = Math.max(0, audio.currentTime - 10);
            });

            btnForward.addEventListener('click', () => {
                audio.currentTime = Math.min(audio.duration || 0, audio.currentTime + 10);
            });

            // Set duration when metadata is available
            audio.addEventListener('loadedmetadata', () => {
                durationTimeLabel.textContent = formatTime(audio.duration);
            });
            
            // In case it's already cached/loaded
            if (audio.readyState >= 1) {
                durationTimeLabel.textContent = formatTime(audio.duration);
            }

            // Sync with playing state
            audio.addEventListener('play', () => {
                isPlaying = true;
                btnPlayPause.innerHTML = '<i class="fas fa-pause"></i>';
                if (playCenterBtn) {
                    playCenterBtn.innerHTML = '<i class="fas fa-pause"></i>';
                    playCenterBtn.classList.add('playing');
                }
                statusDot.classList.remove('bg-slate-400');
                statusDot.classList.add('bg-success', 'animate-ping');
                statusBadge.classList.add('border-success/30', 'text-success');
                statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-success animate-ping status-dot"></span> PLAYING';
            });

            audio.addEventListener('pause', () => {
                isPlaying = false;
                btnPlayPause.innerHTML = '<i class="fas fa-play"></i>';
                if (playCenterBtn) {
                    playCenterBtn.innerHTML = '<i class="fas fa-play play-icon"></i>';
                    playCenterBtn.classList.remove('playing');
                }
                statusDot.classList.add('bg-slate-400');
                statusDot.classList.remove('bg-success', 'animate-ping');
                statusBadge.classList.remove('border-success/30', 'text-success');
                statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-slate-400 status-dot"></span> PAUSED';
            });

            audio.addEventListener('ended', () => {
                audio.pause();
                audio.currentTime = 0;
            });

            audio.addEventListener('timeupdate', () => {
                currentTimeLabel.textContent = formatTime(audio.currentTime);
                if (audio.duration) {
                    durationTimeLabel.textContent = formatTime(audio.duration);
                    
                    // Update horizontal progress bar
                    const pct = (audio.currentTime / audio.duration) * 100;
                    const progressBar = playerCard.querySelector('.progress-bar');
                    const progressHandle = playerCard.querySelector('.progress-handle');
                    if (progressBar) progressBar.style.width = `${pct}%`;
                    if (progressHandle) {
                        progressHandle.style.left = `${pct}%`;
                        progressHandle.style.display = 'block';
                    }
                }
            });

            // Seek click on horizontal progress bar
            playerCard.querySelector('.audio-progress-container').addEventListener('click', function(e) {
                if (!audio.duration) return;
                const rect = this.getBoundingClientRect();
                const clickX = e.clientX - rect.left;
                const percent = Math.max(0, Math.min(1, clickX / rect.width));
                audio.currentTime = percent * audio.duration;
            });
            
            // Speed controller
            speedBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const speed = parseFloat(btn.getAttribute('data-speed'));
                    audio.playbackRate = speed;
                    
                    speedBtns.forEach(b => {
                        b.classList.remove('active', 'btn-primary', 'text-white');
                        b.classList.add('btn-outline-secondary');
                    });
                    btn.classList.add('active', 'btn-primary', 'text-white');
                    btn.classList.remove('btn-outline-secondary');
                });
            });
        });
        
        // Initial setup
        updateButtons();
    });
</script>
@endsection
