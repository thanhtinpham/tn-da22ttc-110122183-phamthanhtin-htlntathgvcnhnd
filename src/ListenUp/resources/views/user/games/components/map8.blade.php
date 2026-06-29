@php
    $soManhGhep = $test->SoManhGhep ?? 4;
    $gridSize = (int) sqrt($soManhGhep);
    if ($gridSize * $gridSize !== (int)$soManhGhep) {
        $gridSize = 2;
        $soManhGhep = 4;
    }
    $parts = $test->phan->values();
    $totalQuestions = 0;
    foreach($parts as $p) {
        $totalQuestions += $p->cauhoi->count();
    }
    
    // Fallback image if AnhTroChoi is not set
    $puzzleImg = $test->AnhTroChoi ? asset('images/' . $test->AnhTroChoi) : asset('images/map8_puzzle.png');
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap');
    
    .font-game {
        font-family: 'Outfit', sans-serif;
    }

    /* Bouncy 3D elements */
    .btn-3d {
        position: relative;
        transition: all 0.1s ease;
        border-bottom-width: 4px;
    }
    .btn-3d:active {
        transform: translateY(4px);
        border-bottom-width: 0px !important;
    }

    /* Glassmorphism elements */
    .glass-panel {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .glass-panel-dark {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Grid layout backgrounds */
    .bg-game-pattern {
        background-color: #f0f4f8;
        background-image: radial-gradient(#cbd5e1 1.2px, transparent 1.2px);
        background-size: 20px 20px;
    }

    /* Glowing locked covers */
    .locked-glowing-cover {
        box-shadow: inset 0 0 20px rgba(99, 102, 241, 0.35);
        border: 1px solid rgba(99, 102, 241, 0.4);
    }
    .locked-glowing-cover:hover {
        box-shadow: inset 0 0 25px rgba(236, 72, 153, 0.4);
        border-color: rgba(236, 72, 153, 0.6);
    }

    /* Puzzle cells animation */
    .puzzle-cell {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .puzzle-cell:hover:not(.unlocked) {
        transform: scale(1.04) rotate(1deg);
        z-index: 10;
    }

    /* Audio wave simulation */
    @keyframes wavePulse {
        0%, 100% { transform: scaleY(0.25); }
        50% { transform: scaleY(1); }
    }
    .wave-line {
        animation: wavePulse 1s ease-in-out infinite;
        transform-origin: bottom;
    }

    /* Particle spark effects */
    .sparkle-particle {
        position: absolute;
        pointer-events: none;
        animation: sparkOut 0.8s ease-out forwards;
    }
    @keyframes sparkOut {
        0% { transform: translate(0, 0) scale(1); opacity: 1; }
        100% { transform: translate(var(--tw-x, 30px), var(--tw-y, -30px)) scale(0.3); opacity: 0; }
    }
    /* Unlocked puzzle piece mask fadeout */
    .mask-unlocked {
        opacity: 0 !important;
        pointer-events: none !important;
    }
</style>

<div id="game-workspace" class="w-full min-h-[760px] bg-game-pattern p-6 rounded-[2.5rem] shadow-xl border-4 border-white relative overflow-hidden font-game text-slate-800 flex flex-col justify-between">
    
    <!-- Decorative floating items -->
    <div class="absolute top-10 left-10 w-24 h-24 bg-pink-300/20 rounded-full blur-2xl animate-pulse pointer-events-none"></div>
    <div class="absolute bottom-12 right-12 w-32 h-32 bg-cyan-300/20 rounded-full blur-2xl animate-pulse pointer-events-none" style="animation-duration: 4s;"></div>

    @if($parts->isEmpty())
        <div class="glass-panel border border-red-200 rounded-[2rem] p-8 shadow-md text-center max-w-xl mx-auto space-y-4 relative z-10 my-auto">
            <div class="w-16 h-16 rounded-3xl bg-rose-50 border border-rose-200 text-rose-500 flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4 class="text-xl font-black text-slate-800">CẤU HÌNH CHƯA SẴN SÀNG</h4>
            <p class="text-slate-500 font-bold text-xs leading-relaxed">Gói bài test này chưa được thêm các phần thi hoặc câu hỏi. Hãy liên hệ admin để thiết lập trước khi chơi nhé.</p>
            <a href="{{ route('public.games') }}" class="btn-3d inline-block px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition border-0 border-b-slate-400 text-xs">Quay lại Bản đồ</a>
        </div>
    @else
        <!-- GAME MASTER FORM -->
        <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="puzzle-game-form" class="flex-grow flex flex-col justify-between h-full space-y-6 relative z-10">
            @csrf
            <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
            <input type="hidden" name="start_time" value="{{ time() }}">
            
            @foreach($parts as $part)
                @foreach($part->cauhoi as $question)
                    <input type="hidden" name="answers[{{ $question->MaCauHoi }}]" id="ans-{{ $question->MaCauHoi }}" value="" required>
                @endforeach
            @endforeach

            <!-- 1. COMPACT TOP NAVIGATION BAR -->
            <div class="glass-panel p-4 rounded-3xl shadow-sm flex flex-wrap items-center justify-between gap-4">
                <!-- Left: Title & Map info -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-puzzle-piece text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-indigo-950 font-black text-base leading-none mb-1">Mảnh Ghép Kỳ Diệu</h4>
                        <div class="text-[10px] text-purple-600 font-extrabold uppercase tracking-wider">Cấp Độ: {{ $map->TenBanDo ?? 'Bí ẩn' }}</div>
                    </div>
                </div>

                <!-- Center: Progress Bar -->
                <div class="flex-grow max-w-xs px-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiến Độ Mở Hình</span>
                        <span class="text-xs font-black text-indigo-600" id="progress-text-hud">0%</span>
                    </div>
                    <div class="h-4 bg-slate-100 rounded-full border border-slate-200 p-0.5 shadow-inner">
                        <div id="progress-bar-hud" class="h-full rounded-full bg-gradient-to-r from-purple-500 via-pink-500 to-amber-500 w-0 transition-all duration-300"></div>
                    </div>
                </div>

                <!-- Right: Stats Panel & Info -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-rose-50 border border-rose-100 px-3 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-rose-500 font-black text-sm" id="lives-counter-text">❤️ 3</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-indigo-600 font-black text-xs" id="unlocked-pieces-display">🧩 0 / {{ $soManhGhep }} Mảnh</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-amber-600 font-black text-sm" id="score-counter-text">⭐ 0 XP</span>
                        </div>
                    </div>

                    <!-- Reset/Guide popup trigger -->
                    <button type="button" onclick="alert('Nhấp vào các mảnh ghép bí ẩn để làm hiển thị các câu hỏi nghe hiểu. Trả lời đúng để mở từng mảnh tranh. Mục tiêu là mở khóa toàn bộ bức tranh bí ẩn!')" class="w-10 h-10 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 flex items-center justify-center cursor-pointer transition shadow-sm">
                        <i class="fas fa-info-circle text-base"></i>
                    </button>
                </div>
            </div>

            <!-- 2. MAIN HERO GAME AREA -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch flex-grow" id="game-arena-wrapper">
                
                <!-- Left Panel: Center Stage Mystery Image Board & Audio Player -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <!-- Mystery Image Board -->
                    <div class="glass-panel p-6 rounded-[2rem] shadow-sm flex flex-col items-center justify-center relative overflow-hidden flex-grow border-2 border-indigo-100">
                        <div class="text-center mb-3">
                            <span class="text-[10px] font-black text-purple-600 uppercase tracking-widest block mb-0.5">Bảng Tranh Bí Ẩn</span>
                            <span class="text-[11px] text-slate-400 font-bold" id="pieces-status-text">Đang khóa 0 / {{ $soManhGhep }} mảnh</span>
                        </div>

                        <!-- Puzzle Board Grid -->
                        <div id="puzzle-grid" class="grid gap-1.5 bg-slate-950 p-3 rounded-3xl border-4 border-slate-900 shadow-2xl w-full max-w-[280px] sm:max-w-[320px] aspect-square" style="grid-template-columns: repeat({{ $gridSize }}, minmax(0, 1fr));">
                            @for($i = 0; $i < $soManhGhep; $i++)
                                @php
                                    $part = $parts->get($i);
                                    $r = (int) floor($i / $gridSize);
                                    $c = $i % $gridSize;
                                    $xPos = ($gridSize > 1) ? ($c / ($gridSize - 1)) * 100 : 0;
                                    $yPos = ($gridSize > 1) ? ($r / ($gridSize - 1)) * 100 : 0;
                                    
                                    $hasPart = $part ? 'true' : 'false';
                                    $partId = $part ? $part->MaPhan : '';
                                    $totalPartQuestions = $part ? $part->cauhoi->count() : 0;
                                @endphp
                                <div class="puzzle-cell relative aspect-square rounded-2xl overflow-hidden cursor-pointer border-2 border-transparent transition-all select-none group"
                                     id="puzzle-cell-{{ $i }}"
                                     data-index="{{ $i }}"
                                     data-has-part="{{ $hasPart }}"
                                     data-part-id="{{ $partId }}"
                                     data-total-questions="{{ $totalPartQuestions }}"
                                     onclick="selectPiece({{ $i }})">
                                    
                                    <!-- Sliced image fragment -->
                                    <div class="absolute inset-0 bg-cover bg-no-repeat transition-all duration-500"
                                         style="
                                            background-image: url('{{ $puzzleImg }}');
                                            background-size: {{ $gridSize * 100 }}% {{ $gridSize * 100 }}%;
                                            background-position: {{ $xPos }}% {{ $yPos }}%;
                                         "></div>

                                    <!-- Locked mask overlay cover -->
                                    <div class="puzzle-mask absolute inset-0 bg-slate-950/85 backdrop-blur-xs transition-all duration-500 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-950/70 border border-white/5 locked-glowing-cover">
                                        <div class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700/50 flex items-center justify-center shadow-md mb-1 transition group-hover:scale-110">
                                            <i class="fas fa-lock text-slate-400 text-xs"></i>
                                        </div>
                                        <span class="text-[9px] font-black tracking-wide text-indigo-300">{{ $i + 1 }}</span>
                                    </div>

                                    <!-- Unlocked checkmark bubble -->
                                    <div class="puzzle-unlocked-tick absolute inset-0 bg-emerald-500/20 flex items-center justify-center opacity-0 transition-all duration-300">
                                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg transform scale-50">
                                            <i class="fas fa-check text-base"></i>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Audio Challenge Card (Integrated) -->
                    <div id="audio-challenge-card" class="bg-gradient-to-br from-indigo-900 to-indigo-950 text-white p-5 rounded-[2rem] shadow-xl space-y-4 relative overflow-hidden hidden">
                        <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-pink-500/10 rounded-full blur-xl pointer-events-none"></div>
                        
                        <div class="flex justify-between items-center border-b border-indigo-800/40 pb-2">
                            <span class="text-[9px] font-black text-indigo-300 uppercase tracking-widest">Gợi ý Audio Phát Âm</span>
                            <span class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full" id="listens-counter">Còn 5 lượt nghe</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Play Audio button -->
                            <button type="button" id="btn-audio-play" class="btn-3d w-12 h-12 rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 hover:from-sky-500 hover:to-indigo-600 text-white flex items-center justify-center shadow-lg cursor-pointer border-0 border-b-indigo-700">
                                <i class="fas fa-play text-sm" id="play-icon"></i>
                            </button>
                            <button type="button" id="btn-audio-replay" class="w-9 h-9 rounded-xl bg-indigo-950/60 border border-indigo-800/40 hover:border-indigo-700 text-slate-300 flex items-center justify-center cursor-pointer transition">
                                <i class="fas fa-redo text-xs"></i>
                            </button>

                            <!-- Waveform bars -->
                            <div class="flex-grow flex items-end justify-center gap-1 h-8 opacity-70" id="audio-waveform-container">
                                <!-- Wave bars injected by JS -->
                            </div>
                        </div>

                        <div class="text-[10px] text-slate-400 font-bold truncate" id="audio-track-name">Chưa phát audio</div>
                        <audio id="global-audio-player" class="hidden"></audio>
                    </div>

                </div>

                <!-- Right Panel: Questions / Answering Deck -->
                <div class="lg:col-span-7 bg-white/80 backdrop-blur border border-white/80 p-6 rounded-[2rem] shadow-sm flex flex-col justify-between min-h-[420px] relative overflow-hidden">
                    <div class="flex-grow flex flex-col justify-between h-full relative">
                        
                        <!-- Empty State Guide -->
                        <div id="no-piece-selected-guide" class="flex flex-col items-center justify-center text-center p-8 text-slate-400 font-bold text-xs bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-3xl shadow-inner select-none my-auto">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-400 mb-3 shadow-xs">
                                <i class="fas fa-hand-pointer text-lg animate-bounce"></i>
                            </div>
                            <h5 class="text-slate-800 font-black text-sm mb-1">Chọn Mảnh Tranh Cần Giải Mã</h5>
                            <p class="text-[11px] text-slate-400 max-w-[240px] leading-relaxed">Click trực tiếp vào mảnh ghép trên bảng tranh để mở gói câu hỏi nghe hiểu!</p>
                        </div>

                        <!-- Active Question Panel (Rendered inside Wrapper) -->
                        <div id="questions-wrapper">
                            @foreach($parts as $idx => $part)
                                <div class="part-questions-panel hidden space-y-4" id="part-questions-{{ $part->MaPhan }}">
                                    
                                    <div class="flex items-center justify-between border-b border-indigo-50 pb-2 mb-3">
                                        <h5 class="font-black text-indigo-950 text-xs uppercase flex items-center gap-2 mb-0">
                                            <i class="fas fa-folder-open text-indigo-500"></i> Phần thi: {{ $part->TenPhan }}
                                        </h5>
                                        <span class="text-[9px] bg-indigo-50 text-indigo-600 font-black px-2 py-0.5 rounded-full">{{ $part->cauhoi->count() }} câu hỏi</span>
                                    </div>

                                    <div class="space-y-4 max-h-[360px] overflow-y-auto pr-1 custom-scrollbar">
                                        @foreach($part->cauhoi as $qIdx => $question)
                                            <div class="question-card p-4 rounded-3xl border border-slate-200/80 bg-white shadow-xs space-y-3 relative overflow-hidden"
                                                 id="question-card-{{ $question->MaCauHoi }}"
                                                 data-question-id="{{ $question->MaCauHoi }}">
                                                
                                                <div class="flex items-start gap-2">
                                                    <span class="bg-indigo-100 text-indigo-600 text-[10px] font-black px-2 py-1 rounded-lg shrink-0 mt-0.5">Câu {{ $qIdx + 1 }}</span>
                                                    <h6 class="font-extrabold text-slate-800 text-sm leading-relaxed mb-0">
                                                        {{ $question->NDCauHoi }}
                                                    </h6>
                                                </div>

                                                <!-- Option Button Cards -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @foreach($question->phuongancauhoi as $option)
                                                        @php
                                                            $isCorrect = ($option->DapAn === 'Dung' || $option->DapAn == 1) ? 'true' : 'false';
                                                        @endphp
                                                        <button type="button"
                                                                class="option-card text-start flex flex-col items-stretch p-3 border-2 border-slate-200 bg-white rounded-2xl hover:border-indigo-400 active:scale-[0.98] transition-all select-none w-full outline-none"
                                                                data-question-id="{{ $question->MaCauHoi }}"
                                                                data-option-id="{{ $option->MaPA }}"
                                                                data-correct="{{ $isCorrect }}"
                                                                onclick="checkOptionAnswer(this)">
                                                            @if(!empty($option->HinhAnh))
                                                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl overflow-hidden mb-2 flex items-center justify-center aspect-video">
                                                                    <img src="{{ asset('storage/' . $option->HinhAnh) }}" onerror="this.onerror=null; this.src='{{ asset('images/' . $option->HinhAnh) }}';" class="max-w-full max-h-full object-contain">
                                                                </div>
                                                            @endif
                                                            <div class="flex items-center w-full">
                                                                <div class="option-dot w-4.5 h-4.5 rounded-full border-2 border-slate-300 flex items-center justify-center mr-2 shrink-0">
                                                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 scale-0 transition-transform"></div>
                                                                </div>
                                                                @if(!empty($option->NDPA))
                                                                    <span class="text-xs text-slate-600 font-bold leading-normal">{{ $option->NDPA }}</span>
                                                                @else
                                                                    <span class="text-xs text-slate-400 font-bold italic leading-normal">Hình ảnh</span>
                                                                @endif
                                                            </div>
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <!-- Correct Answer success status overlay -->
                                                <div class="question-status-overlay absolute inset-0 bg-emerald-50/90 backdrop-blur-xs flex items-center justify-center rounded-3xl opacity-0 pointer-events-none transition-all duration-300">
                                                    <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 py-1.5 px-4 rounded-2xl font-black text-xs shadow-sm"><i class="fas fa-check-circle mr-1 text-sm"></i> CHÍNH XÁC</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <!-- SCREEN 1: Game Launch Screen -->
                        <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-45 space-y-6 select-none bg-white rounded-3xl">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white text-3xl shadow-lg shadow-indigo-500/20">
                                <i class="fas fa-puzzle-piece animate-bounce" style="animation-duration: 3s;"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-wide">MẢNH GHÉP KỲ DIỆU</h3>
                                <p class="text-slate-500 text-xs max-w-sm mt-2 leading-relaxed font-bold">Hãy nhấp mở các mảnh ghép tranh, hoàn thành các gói câu hỏi nghe hiểu để giải mã bức tranh bí ẩn.</p>
                            </div>
                            <button type="button" id="btn-game-start" class="btn-3d px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black text-sm tracking-wider rounded-2xl shadow-lg cursor-pointer border-0 border-b-indigo-700">
                                BẮT ĐẦU GIẢI MÃ
                            </button>
                        </div>

                        <!-- SCREEN 2: Game Over Screen -->
                        <div id="screen-gameover" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-45 space-y-6 select-none bg-white rounded-3xl hidden">
                            <div class="w-16 h-16 rounded-3xl bg-rose-50 border-2 border-rose-300 text-rose-500 flex items-center justify-center text-2xl shadow-lg">
                                <i class="fas fa-heart-broken animate-pulse"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-rose-600 uppercase tracking-wider">CẠN KIỆT NĂNG LƯỢNG</h3>
                                <p class="text-slate-500 text-xs max-w-xs mt-2 leading-relaxed font-bold">Bạn đã trả lời sai quá số lần quy định. Hãy lắng nghe lại cẩn thận hơn nhé!</p>
                            </div>
                            <button type="button" id="btn-game-replay" class="btn-3d px-6 py-3 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-extrabold text-xs tracking-wider rounded-xl cursor-pointer border-0 border-b-red-800 shadow-md">
                                <i class="fas fa-redo mr-1"></i> THỬ SỨC LẠI
                            </button>
                        </div>

                        <!-- SCREEN 3: Level Completed Screen (Full Image Revealed celebration!) -->
                        <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-45 space-y-6 select-none bg-white rounded-3xl hidden">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-amber-400 to-orange-500 flex items-center justify-center text-white text-3xl shadow-xl shadow-orange-500/30">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-amber-500 uppercase tracking-wide">GIẢI MÃ THÀNH CÔNG!</h3>
                                <p class="text-slate-500 text-xs max-w-sm mt-2 leading-relaxed font-bold">Xuất sắc! Bạn đã mở khóa hoàn toàn toàn bộ tranh và trả lời đúng gói câu hỏi.</p>
                                
                                <div class="grid grid-cols-3 gap-3 mt-4 max-w-xs mx-auto">
                                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-2.5">
                                        <span class="text-[9px] text-slate-400 font-black block uppercase">XP Đạt Được</span>
                                        <span class="text-sm font-black text-indigo-600">+120 XP</span>
                                    </div>
                                    <div class="bg-pink-50 border border-pink-100 rounded-2xl p-2.5">
                                        <span class="text-[9px] text-slate-400 font-black block uppercase">Chính xác</span>
                                        <span class="text-sm font-black text-pink-600" id="accuracy-display-win">100%</span>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-2.5">
                                        <span class="text-[9px] text-slate-400 font-black block uppercase">Đánh giá</span>
                                        <span class="text-sm font-black text-amber-600">⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-3d group flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-sm tracking-wider px-8 py-3.5 rounded-2xl shadow-lg border-0 border-b-emerald-700 cursor-pointer" id="btn-submit-test">
                                <i class="fas fa-crown text-amber-200 group-hover:animate-bounce"></i>
                                HOÀN THÀNH THỬ THÁCH
                            </button>
                        </div>

                    </div>
                </div>

            </div>

            <!-- 3. BOTTOM PANEL: Power-ups -->
            <div class="glass-panel p-4 rounded-3xl shadow-sm flex flex-wrap items-center justify-between gap-4">
                <!-- Circular power-up buttons -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Gói bổ trợ:</span>
                    <button type="button" id="btn-power-hint" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-400 to-yellow-500 text-white flex items-center justify-center cursor-pointer shadow-md border-0 border-b-yellow-700 text-sm" title="🔍 Gợi ý câu trả lời đúng">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" id="btn-power-replay" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-500 text-white flex items-center justify-center cursor-pointer shadow-md border-0 border-b-blue-700 text-sm" title="🎧 Phát lại âm thanh">
                        <i class="fas fa-headphones"></i>
                    </button>
                    <button type="button" id="btn-power-reveal" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 text-white flex items-center justify-center cursor-pointer shadow-md border-0 border-b-pink-700 text-sm" title="🧩 Mở khóa tự động một mảnh ghép">
                        <i class="fas fa-puzzle-piece"></i>
                    </button>
                    <button type="button" id="btn-power-time" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white flex items-center justify-center cursor-pointer shadow-md border-0 border-b-emerald-700 text-sm" title="⏱ Nhận gợi ý bằng giọng đọc AI">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>

                <!-- Helper guide keys -->
                <div class="text-xs text-slate-400 font-bold flex items-center gap-4">
                    <span><i class="fas fa-puzzle-piece mr-1"></i> Giải hết câu hỏi để mở khóa mảnh ghép</span>
                    <span><i class="fas fa-heart mr-1"></i> Trả lời đúng để bảo toàn năng lượng</span>
                </div>
            </div>

        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Check if parts exist before executing game script
    if (!document.getElementById('puzzle-grid')) return;

    // Gather dynamic data structures from PHP backend
    const partsData = {
        @foreach($parts as $idx => $part)
            "{{ $part->MaPhan }}": {
                index: {{ $idx }},
                name: "{{ addslashes($part->TenPhan) }}",
                audio: "{{ $part->tepamthanh ? asset('storage/' . $part->tepamthanh->DuongDan) : '' }}",
                questions: [
                    @foreach($part->cauhoi as $question)
                        "{{ $question->MaCauHoi }}",
                    @endforeach
                ]
            },
        @endforeach
    };

    const solvedQuestions = {};
    const questionToPartMap = {};

    Object.keys(partsData).forEach(partId => {
        partsData[partId].questions.forEach(qId => {
            questionToPartMap[qId] = partId;
            solvedQuestions[qId] = false;
        });
    });

    // Game stats
    let lives = 3;
    let unlockedCount = 0;
    const totalPieces = {{ $soManhGhep }};
    let activePieceIndex = null;
    let isPlaying = false;
    let totalIncorrect = 0;
    let totalCorrect = 0;

    // Audio synthesizer context
    let audioCtx = null;
    const initAudioContext = () => {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    };

    const synthBeep = (freq, duration, type = 'sine') => {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.06, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch(e) {}
    };

    const playCorrectSound = () => {
        synthBeep(523.25, 0.1, 'sine');
        setTimeout(() => synthBeep(659.25, 0.1, 'sine'), 80);
        setTimeout(() => synthBeep(783.99, 0.2, 'sine'), 160);
    };

    const playWrongSound = () => {
        synthBeep(160, 0.3, 'triangle');
    };

    const playClickSound = () => {
        synthBeep(750, 0.04, 'sine');
    };

    const playRevealSound = () => {
        synthBeep(587.33, 0.08, 'sine');
        setTimeout(() => synthBeep(783.99, 0.08, 'sine'), 60);
        setTimeout(() => synthBeep(987.77, 0.08, 'sine'), 120);
        setTimeout(() => synthBeep(1174.66, 0.25, 'sine'), 180);
    };

    // Text to speech synthesizers
    const speakText = (text) => {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        }
    };

    // UI bindings
    const heartsContainer = document.getElementById('lives-counter-text');
    const unlockedPiecesDisplay = document.getElementById('unlocked-pieces-display');
    const scoreCounterText = document.getElementById('score-counter-text');
    const piecesStatusText = document.getElementById('pieces-status-text');
    const progressBarHud = document.getElementById('progress-bar-hud');
    const progressTextHud = document.getElementById('progress-text-hud');
    const listensCounter = document.getElementById('listens-counter');
    
    const screenLaunch = document.getElementById('screen-launch');
    const screenGameOver = document.getElementById('screen-gameover');
    const screenWin = document.getElementById('screen-win');
    
    const audioChallengeCard = document.getElementById('audio-challenge-card');
    const audioTrackName = document.getElementById('audio-track-name');
    const globalAudioPlayer = document.getElementById('global-audio-player');
    const playIcon = document.getElementById('play-icon');
    
    const noPieceSelectedGuide = document.getElementById('no-piece-selected-guide');
    const gameArenaWrapper = document.getElementById('game-arena-wrapper');

    // Waveform rendering
    const waveformContainer = document.getElementById('audio-waveform-container');
    const barCount = 18;
    for (let i = 0; i < barCount; i++) {
        const bar = document.createElement('div');
        bar.className = 'w-1 bg-sky-300 rounded-t h-1 transition-all duration-100';
        waveformContainer.appendChild(bar);
    }
    const waveBars = waveformContainer.children;

    let isAudioPlaying = false;
    let remainingListens = 5;

    const playCurrentAudio = () => {
        if (!isPlaying || activePieceIndex === null) return;
        initAudioContext();

        const cell = document.getElementById(`puzzle-cell-${activePieceIndex}`);
        const partId = cell.dataset.partId;
        const part = partsData[partId];

        if (!part || !part.audio) {
            // TTS speak first question text
            const firstQId = part.questions[0];
            const qCard = document.getElementById(`question-card-${firstQId}`);
            if (qCard) {
                const qText = qCard.querySelector('h6').innerText.replace(/^Câu \d+:\s*/, '');
                speakText(qText);
            }
            return;
        }

        if (isAudioPlaying) {
            globalAudioPlayer.pause();
            isAudioPlaying = false;
            playIcon.className = 'fas fa-play';
            stopWaveformAnimation();
            return;
        }

        if (remainingListens <= 0) {
            alert('Bạn đã dùng hết số lượt nghe miễn phí cho mảnh này!');
            return;
        }

        globalAudioPlayer.src = part.audio;
        globalAudioPlayer.play().then(() => {
            isAudioPlaying = true;
            playIcon.className = 'fas fa-pause';
            remainingListens--;
            listensCounter.innerText = `Còn ${remainingListens} lượt nghe`;
            startWaveformAnimation();
        }).catch(() => {
            speakText("Audio loading failed. Speak fallback.");
        });
    };

    globalAudioPlayer.addEventListener('ended', () => {
        isAudioPlaying = false;
        playIcon.className = 'fas fa-play';
        stopWaveformAnimation();
    });

    let waveTimer = null;
    const startWaveformAnimation = () => {
        clearInterval(waveTimer);
        waveTimer = setInterval(() => {
            for (let i = 0; i < barCount; i++) {
                const hVal = Math.floor(4 + Math.random() * 24);
                waveBars[i].style.height = `${hVal}px`;
            }
        }, 100);
    };

    const stopWaveformAnimation = () => {
        clearInterval(waveTimer);
        for (let i = 0; i < barCount; i++) {
            waveBars[i].style.height = '4px';
        }
    };

    // Particle Burst Reveal
    const spawnSparklesAtCell = (cellId) => {
        const cell = document.getElementById(cellId);
        if (!cell) return;

        const rect = cell.getBoundingClientRect();
        const parentRect = gameArenaWrapper.getBoundingClientRect();
        const startX = rect.left - parentRect.left + rect.width / 2;
        const startY = rect.top - parentRect.top + rect.height / 2;

        const count = 16;
        const colors = ['#f472b6', '#38bdf8', '#fbbf24', '#34d399', '#ffffff'];

        for (let i = 0; i < count; i++) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle-particle';
            const size = 4 + Math.random() * 5;
            sparkle.style.width = `${size}px`;
            sparkle.style.height = `${size}px`;
            sparkle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            sparkle.style.boxShadow = `0 0 8px ${sparkle.style.backgroundColor}`;
            
            const dx = (Math.random() - 0.5) * 120;
            const dy = -30 - Math.random() * 80;
            sparkle.style.setProperty('--tw-x', `${dx}px`);
            sparkle.style.setProperty('--tw-y', `${dy}px`);
            sparkle.style.left = `${startX}px`;
            sparkle.style.top = `${startY}px`;

            gameArenaWrapper.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 1000);
        }
    };

    // Render stats
    const updateStatsDisplay = () => {
        heartsContainer.innerText = `❤️ ${lives}`;
        unlockedPiecesDisplay.innerText = `🧩 ${unlockedCount} / ${totalPieces}`;
        scoreCounterText.innerText = `⭐ ${totalCorrect * 25} XP`;
        piecesStatusText.innerText = `Đã mở ${unlockedCount} / ${totalPieces} mảnh`;

        const pct = Math.round((unlockedCount / totalPieces) * 100);
        progressBarHud.style.width = `${pct}%`;
        progressTextHud.innerText = `${pct}%`;
    };

    // Selection click puzzle cell
    window.selectPiece = (index) => {
        if (!isPlaying) return;
        initAudioContext();
        playClickSound();

        const cell = document.getElementById(`puzzle-cell-${index}`);
        if (!cell || cell.classList.contains('unlocked')) return;

        document.querySelectorAll('.puzzle-cell').forEach(c => {
            c.classList.remove('ring-4', 'ring-amber-400');
        });
        cell.classList.add('ring-4', 'ring-amber-400');
        activePieceIndex = index;

        noPieceSelectedGuide.classList.add('hidden');
        document.querySelectorAll('.part-questions-panel').forEach(p => p.classList.add('hidden'));

        const hasPart = cell.dataset.hasPart === 'true';
        const partId = cell.dataset.partId;

        // Reset audio player
        globalAudioPlayer.pause();
        isAudioPlaying = false;
        playIcon.className = 'fas fa-play';
        stopWaveformAnimation();

        if (hasPart && partId) {
            const part = partsData[partId];
            document.getElementById(`part-questions-${partId}`).classList.remove('hidden');

            audioChallengeCard.classList.remove('hidden');
            audioTrackName.innerText = part.audio ? `Mảnh ${index + 1}: ${part.name}` : `Đọc văn bản giọng AI`;
            
            remainingListens = 5;
            listensCounter.innerText = `Còn ${remainingListens} lượt nghe`;

            if (part.audio) {
                globalAudioPlayer.src = part.audio;
                // Auto play
                setTimeout(() => {
                    globalAudioPlayer.play().then(() => {
                        isAudioPlaying = true;
                        playIcon.className = 'fas fa-pause';
                        startWaveformAnimation();
                    }).catch(() => {});
                }, 300);
            }
        } else {
            audioChallengeCard.classList.add('hidden');
        }
    };

    // Option Answer Selection
    window.checkOptionAnswer = (button) => {
        if (!isPlaying) return;
        initAudioContext();

        const questionId = button.dataset.questionId;
        const optionId = button.dataset.optionId;
        const isCorrect = button.dataset.correct === 'true';

        if (solvedQuestions[questionId]) return;

        const parentCard = document.getElementById(`question-card-${questionId}`);
        const allOptions = parentCard.querySelectorAll('.option-card');

        if (isCorrect) {
            playCorrectSound();
            solvedQuestions[questionId] = true;
            totalCorrect++;

            button.classList.add('opt-correct');
            button.querySelector('.option-dot div').classList.remove('scale-0');

            allOptions.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-70');
            });

            // Set hidden form value
            const ansInp = document.getElementById(`ans-${questionId}`);
            if (ansInp) ansInp.value = optionId;

            // Show checked overlay
            const overlay = parentCard.querySelector('.question-status-overlay');
            if (overlay) {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            }

            // Verify if parent part is fully solved
            const partId = questionToPartMap[questionId];
            if (partId) {
                const part = partsData[partId];
                const allSolved = part.questions.every(q => solvedQuestions[q] === true);
                if (allSolved) {
                    unlockPiece(partId);
                }
            }
            updateStatsDisplay();
        } else {
            playWrongSound();
            totalIncorrect++;
            
            button.classList.add('opt-wrong');
            button.querySelector('.option-dot div').classList.remove('scale-0');

            parentCard.classList.add('animate-shake');
            setTimeout(() => {
                parentCard.classList.remove('animate-shake');
                button.classList.remove('opt-wrong');
                button.querySelector('.option-dot div').classList.add('scale-0');
            }, 500);

            lives--;
            updateStatsDisplay();

            if (lives <= 0) {
                finishGame(false);
            }
        }
    };

    const unlockPiece = (partId) => {
        const cell = document.querySelector(`.puzzle-cell[data-part-id="${partId}"]`);
        if (!cell || cell.classList.contains('unlocked')) return;

        cell.classList.add('unlocked');
        cell.querySelector('.puzzle-mask').classList.add('mask-unlocked');
        
        const tick = cell.querySelector('.puzzle-unlocked-tick');
        if (tick) {
            tick.style.opacity = '1';
            setTimeout(() => { tick.style.opacity = '0'; }, 800);
        }

        playRevealSound();
        spawnSparklesAtCell(`puzzle-cell-${cell.dataset.index}`);

        unlockedCount++;
        updateStatsDisplay();

        // Check completion condition
        if (unlockedCount >= totalPieces) {
            setTimeout(() => finishGame(true), 800);
        }
    };

    // Auto unlock slots with no question binders
    const unlockEmptySlices = () => {
        document.querySelectorAll('.puzzle-cell').forEach(cell => {
            const hasPart = cell.dataset.hasPart === 'true';
            if (!hasPart) {
                cell.classList.add('unlocked');
                cell.querySelector('.puzzle-mask').classList.add('mask-unlocked');
                unlockedCount++;
            }
        });
        updateStatsDisplay();

        if (unlockedCount >= totalPieces) {
            finishGame(true);
        }
    };

    // Powerup: Hint
    document.getElementById('btn-power-hint').addEventListener('click', () => {
        if (!isPlaying || activePieceIndex === null) return;
        
        const cell = document.getElementById(`puzzle-cell-${activePieceIndex}`);
        const partId = cell.dataset.partId;
        const part = partsData[partId];
        if (!part) return;

        // Find first unsolved question in active part
        const unsolvedQId = part.questions.find(q => solvedQuestions[q] === false);
        if (!unsolvedQId) return;

        const qCard = document.getElementById(`question-card-${unsolvedQId}`);
        const correctOpt = Array.from(qCard.querySelectorAll('.option-card')).find(opt => opt.dataset.correct === 'true');

        if (correctOpt) {
            correctOpt.classList.add('ring-4', 'ring-amber-400');
            setTimeout(() => correctOpt.classList.remove('ring-4', 'ring-amber-400'), 1500);
        }
    });

    // Powerup: Replay
    document.getElementById('btn-power-replay').addEventListener('click', () => {
        if (!isPlaying) return;
        playCurrentAudio();
    });

    // Powerup: Auto Reveal Piece
    document.getElementById('btn-power-reveal').addEventListener('click', () => {
        if (!isPlaying) return;
        
        // Find first locked cell
        const lockedCell = Array.from(document.querySelectorAll('.puzzle-cell')).find(c => !c.classList.contains('unlocked') && c.dataset.hasPart === 'true');
        if (!lockedCell) return;

        const partId = lockedCell.dataset.partId;
        const part = partsData[partId];

        // Solve all questions in this part
        part.questions.forEach(qId => {
            solvedQuestions[qId] = true;
            
            const qCard = document.getElementById(`question-card-${qId}`);
            const correctOpt = Array.from(qCard.querySelectorAll('.option-card')).find(opt => opt.dataset.correct === 'true');
            if (correctOpt) {
                correctOpt.classList.add('opt-correct');
                correctOpt.querySelector('.option-dot div').classList.remove('scale-0');
            }
            qCard.querySelectorAll('.option-card').forEach(btn => btn.disabled = true);
            const overlay = qCard.querySelector('.question-status-overlay');
            if (overlay) overlay.style.opacity = '1';

            const ansInp = document.getElementById(`ans-${qId}`);
            if (ansInp && correctOpt) ansInp.value = correctOpt.dataset.optionId;
        });

        unlockPiece(partId);
    });

    // Powerup: Voice Magic TTS reading active question
    document.getElementById('btn-power-time').addEventListener('click', () => {
        if (!isPlaying || activePieceIndex === null) return;
        const cell = document.getElementById(`puzzle-cell-${activePieceIndex}`);
        const partId = cell.dataset.partId;
        const part = partsData[partId];
        if (!part) return;

        const unsolvedQId = part.questions.find(q => solvedQuestions[q] === false);
        if (!unsolvedQId) return;

        const qCard = document.getElementById(`question-card-${unsolvedQId}`);
        if (qCard) {
            const text = qCard.querySelector('h6').innerText.replace(/^Câu \d+:\s*/, '');
            speakText(text);
        }
    });

    const startGame = () => {
        initAudioContext();
        screenLaunch.classList.add('hidden');
        screenGameOver.classList.add('hidden');
        screenWin.classList.add('hidden');

        lives = 3;
        unlockedCount = 0;
        activePieceIndex = null;
        isPlaying = true;
        totalIncorrect = 0;
        totalCorrect = 0;

        // Reset solved keys
        Object.keys(solvedQuestions).forEach(qId => {
            solvedQuestions[qId] = false;
            const ansInp = document.getElementById(`ans-${qId}`);
            if (ansInp) ansInp.value = '';
        });

        // Reset cards & buttons
        document.querySelectorAll('.question-status-overlay').forEach(overlay => {
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
        });
        document.querySelectorAll('.option-card').forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('opt-correct', 'opt-wrong', 'opacity-70', 'ring-4', 'ring-amber-400');
            const dot = btn.querySelector('.option-dot div');
            if (dot) dot.classList.add('scale-0');
        });

        // Reset cells
        document.querySelectorAll('.puzzle-cell').forEach(c => {
            c.classList.remove('unlocked', 'ring-4', 'ring-amber-400');
            c.querySelector('.puzzle-mask').classList.remove('mask-unlocked');
        });

        noPieceSelectedGuide.classList.remove('hidden');
        document.querySelectorAll('.part-questions-panel').forEach(p => p.classList.add('hidden'));

        audioChallengeCard.classList.add('hidden');
        globalAudioPlayer.pause();
        globalAudioPlayer.src = '';
        isAudioPlaying = false;
        playIcon.className = 'fas fa-play';
        stopWaveformAnimation();

        updateStatsDisplay();
        unlockEmptySlices();
        synthBeep(440, 0.15, 'triangle');
    };

    const finishGame = (isWin) => {
        isPlaying = false;
        globalAudioPlayer.pause();
        stopWaveformAnimation();
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }

        if (isWin) {
            document.querySelectorAll('.puzzle-mask').forEach(mask => mask.classList.add('mask-unlocked'));
            
            const total = totalCorrect + totalIncorrect;
            const accuracyVal = total > 0 ? Math.round((totalCorrect / total) * 100) : 100;
            document.getElementById('accuracy-display-win').innerText = `${accuracyVal}%`;
            
            screenWin.classList.remove('hidden');
            playRevealSound();
        } else {
            screenGameOver.classList.remove('hidden');
            playWrongSound();
        }
    };

    document.getElementById('btn-audio-play').addEventListener('click', playCurrentAudio);
    document.getElementById('btn-audio-replay').addEventListener('click', () => {
        isAudioPlaying = false;
        playCurrentAudio();
    });

    document.getElementById('btn-game-start').addEventListener('click', startGame);
    document.getElementById('btn-game-replay').addEventListener('click', startGame);
});
</script>
