@php
    $allQuestions = [];
    $audios = [];
    foreach($test->phan as $phan) {
        if ($phan->tepamthanh) {
            $audios[] = [
                'name' => $phan->TenPhan,
                'url' => asset('storage/' . $phan->tepamthanh->DuongDan)
            ];
        }
        foreach($phan->cauhoi as $question) {
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
            $englishText = $correctOption ? preg_replace('/^[A-D]\.\s*/', '', $correctOption->NDPA) : '';
            
            $allQuestions[] = [
                'id' => $question->MaCauHoi,
                'vietnamese' => trim($question->NDCauHoi),
                'english' => trim($englishText),
                'option_id' => $correctOption ? $correctOption->MaPA : '',
            ];
        }
    }
    $totalQuestions = count($allQuestions);
@endphp

<!-- Style overrides for Mobile Game Experience -->
<style>
    /* Google Fonts Outfit/Inter */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap');
    
    .font-game {
        font-family: 'Outfit', sans-serif;
    }

    /* Bouncy 3D Button Style */
    .btn-3d {
        position: relative;
        transition: all 0.1s ease;
        border-bottom-width: 4px;
    }
    .btn-3d:active {
        transform: translateY(4px);
        border-bottom-width: 0px !important;
    }

    /* 3D Card Styling */
    .card-3d {
        position: relative;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-bottom-width: 5px;
    }
    .card-3d:hover:not(.disabled) {
        transform: translateY(-4px);
    }
    .card-3d:active:not(.disabled) {
        transform: translateY(2px);
        border-bottom-width: 1px;
    }

    /* Grid overlay pattern */
    .bg-game-pattern {
        background-color: #f8fafc;
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
        background-size: 24px 24px;
    }

    /* Sparkles floating animation */
    .sparkle {
        position: absolute;
        pointer-events: none;
        animation: floatSparkle 1.2s ease-out forwards;
    }
    @keyframes floatSparkle {
        0% { transform: translate(0, 0) scale(1); opacity: 1; }
        100% { transform: translate(var(--tw-x, 20px), var(--tw-y, -50px)) scale(0.5); opacity: 0; }
    }

    /* Waveform Animation */
    @keyframes waveformAnim {
        0%, 100% { transform: scaleY(0.3); }
        50% { transform: scaleY(1); }
    }
    .wave-bar {
        animation: waveformAnim 1s ease-in-out infinite;
        transform-origin: bottom;
    }

    /* Glow connections */
    .connection-line {
        position: absolute;
        pointer-events: none;
        background: linear-gradient(90deg, #38bdf8, #ec4899);
        height: 4px;
        z-index: 20;
        border-radius: 2px;
        box-shadow: 0 0 10px rgba(56, 189, 248, 0.6);
        animation: pulseLine 0.5s ease-out forwards;
    }
    @keyframes pulseLine {
        0% { opacity: 0.3; transform: scaleY(0.5); }
        100% { opacity: 1; transform: scaleY(1); }
    }
    /* Shaking animation for incorrect match */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        75% { transform: translateX(6px); }
    }
    .animate-shake {
        animation: shake 0.2s ease-in-out 2;
    }
    #game-workspace {
        background-image: url('{{ asset('images/map6_bg.png') }}') !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
    }
</style>

<div id="game-workspace" class="w-full min-h-[720px] bg-game-pattern p-6 rounded-[2.5rem] shadow-xl border-4 border-white/60 relative overflow-hidden font-game text-slate-800 flex flex-col justify-between">
    <!-- Inner Decorative Elements -->
    <div class="absolute top-8 left-6 w-32 h-32 bg-indigo-300/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-8 right-6 w-40 h-40 bg-pink-300/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- MAIN FORM SUBMISSION -->
    <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="game-cards-form" class="flex-grow flex flex-col justify-between h-full space-y-6">
        @csrf
        <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
        <input type="hidden" name="start_time" value="{{ time() }}">
        
        @foreach($allQuestions as $q)
            <input type="hidden" name="answers[{{ $q['id'] }}]" id="ans-{{ $q['id'] }}" value="">
        @endforeach

        <!-- GAME AREA WRAPPER -->
        <div class="flex flex-col flex-grow justify-between relative z-10 space-y-6">
            
            <!-- 1. COMPACT TOP HEADER -->
            <div class="flex flex-wrap items-center justify-between gap-4 bg-white/70 backdrop-blur border border-white/80 p-4 rounded-3xl shadow-sm">
                <!-- Left: Title & Level -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-layer-group text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-indigo-950 font-black text-base leading-none mb-1">Siêu Ghép Cặp</h4>
                        <div class="text-[10px] text-indigo-500 font-extrabold uppercase tracking-wider">Level: {{ $map->TenBanDo ?? 'Thử thách' }}</div>
                    </div>
                </div>

                <!-- Center: XP Progress Bar -->
                <div class="flex-grow max-w-xs px-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiến Trình Học</span>
                        <span class="text-xs font-black text-indigo-600" id="progress-text-hud">0%</span>
                    </div>
                    <div class="h-4 bg-slate-100 rounded-full border border-slate-200 overflow-hidden p-0.5 shadow-inner">
                        <div id="progress-bar-hud" class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 w-0 transition-all duration-300 shadow-sm"></div>
                    </div>
                </div>

                <!-- Right: Stats Panel & Settings -->
                <div class="flex items-center gap-3">
                    <!-- Lives & Gold badge -->
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1 bg-rose-50 border border-rose-100 px-3 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-rose-500 font-black text-sm" id="lives-counter-text">❤️ 5</span>
                        </div>
                        <div class="flex items-center gap-1 bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-amber-600 font-black text-sm" id="score-counter-text">⭐ 0 XP</span>
                        </div>
                        <div id="streak-badge" class="hidden flex items-center gap-1 bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-orange-500 font-black text-sm">🔥 <span id="streak-counter-text">0</span></span>
                        </div>
                    </div>

                    <!-- Settings Button -->
                    <button type="button" onclick="alert('Hãy lắng nghe các đoạn phát âm bên dưới và click chọn 1 từ tiếng Anh kết nối với dịch nghĩa tiếng Việt tương ứng.')" class="w-10 h-10 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 flex items-center justify-center cursor-pointer transition shadow-sm">
                        <i class="fas fa-info-circle text-base"></i>
                    </button>
                </div>
            </div>

            <!-- 2. MAIN INTERACTIVE ARENA -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch flex-grow">
                
                <!-- Left Sidebar: Challenge Hero Card -->
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <!-- Challenge Card -->
                    <div class="bg-white/80 backdrop-blur border border-white/80 p-6 rounded-[2rem] shadow-sm flex flex-col justify-between flex-grow relative overflow-hidden">
                        <!-- Tiny helper mascot inside -->
                        <div class="space-y-4">
                            <div class="text-center pb-2 border-b border-indigo-50">
                                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest block mb-1">Nhiệm vụ của bạn</span>
                                <h3 class="text-indigo-950 font-black text-lg">Ghép Cặp Từ Vựng</h3>
                                <p class="text-xs text-slate-500 font-bold leading-relaxed mt-1">Nghe phát âm từ khóa gợi ý và ghép đúng từ Tiếng Anh với bản dịch tương ứng.</p>
                            </div>

                            <!-- Equalizer sound visualization inside -->
                            <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 text-white rounded-3xl p-5 shadow-inner space-y-4 relative overflow-hidden">
                                <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-pink-500/10 rounded-full blur-xl pointer-events-none"></div>
                                <div class="flex justify-between items-center border-b border-indigo-800/40 pb-2">
                                    <span class="text-[9px] font-black text-indigo-300 uppercase tracking-widest">Gợi ý Audio Phát Âm</span>
                                    <span class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full" id="listens-counter">Còn 5 lượt nghe</span>
                                </div>

                                <!-- Audio Selection Dropdown -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Chọn đoạn audio cần nghe:</label>
                                    <select id="audio-select-track" class="w-full bg-indigo-950 border border-indigo-700/60 rounded-xl px-3 py-2 text-xs font-bold text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        @foreach($audios as $idx => $aud)
                                            <option value="{{ $aud['url'] }}" data-name="{{ $aud['name'] }}">{{ $aud['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Audio Controller -->
                                <div class="flex items-center gap-3">
                                    <button type="button" id="btn-audio-play" class="w-12 h-12 rounded-2xl bg-indigo-500 hover:bg-indigo-400 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 cursor-pointer active:scale-95 transition-all outline-none border-0">
                                        <i class="fas fa-play text-sm" id="play-icon"></i>
                                    </button>
                                    <button type="button" id="btn-audio-replay" class="w-10 h-10 rounded-2xl bg-indigo-950/60 border border-indigo-800/40 hover:border-indigo-800 text-slate-300 flex items-center justify-center cursor-pointer active:scale-95 transition-all outline-none">
                                        <i class="fas fa-redo text-xs"></i>
                                    </button>
                                    
                                    <!-- Waveform visualizer -->
                                    <div class="flex-grow flex items-end justify-center gap-[3px] h-8 opacity-60" id="audio-waveform-container">
                                        <!-- bars injected by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mascot bubble -->
                        <div class="flex items-start gap-3 bg-indigo-50/50 border border-indigo-100/30 p-4 rounded-2xl mt-4">
                            <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-10 h-10 object-contain shrink-0 animate-bounce" style="animation-duration: 4s;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=AI&background=6366F1&color=fff';">
                            <div>
                                <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest block leading-none mb-1">Mẹo học tập</span>
                                <p class="text-xs text-slate-500 font-bold leading-normal mb-0">Nhấp vào thẻ từ Tiếng Anh để AI phát âm to từ đó giúp bạn dễ ghi nhớ!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Match Board Area -->
                <div class="lg:col-span-8 bg-white/80 backdrop-blur border border-white/80 p-6 rounded-[2.5rem] shadow-sm flex flex-col justify-between min-h-[480px] relative overflow-hidden">
                    <div id="game-play-board" class="flex flex-col justify-between h-full flex-grow relative">
                        
                        <!-- UPPER SECTION: English Words -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-indigo-50 pb-2">
                                <span class="text-xs font-black text-indigo-950 uppercase tracking-wider">Thẻ Tiếng Anh (English Words)</span>
                                <span class="text-[10px] text-slate-400 font-bold" id="en-cards-count">Còn 0 thẻ</span>
                            </div>
                            <div id="english-cards-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <!-- English Cards Injected dynamically -->
                            </div>
                        </div>

                        <!-- LOWER SECTION: Vietnamese Meanings -->
                        <div class="space-y-3 mt-8">
                            <div class="flex items-center justify-between border-b border-pink-50 pb-2">
                                <span class="text-xs font-black text-pink-950 uppercase tracking-wider">Bản Dịch Tiếng Việt (Vietnamese Meanings)</span>
                                <span class="text-[10px] text-slate-400 font-bold" id="vi-cards-count">Còn 0 thẻ</span>
                            </div>
                            <div id="vietnamese-cards-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <!-- Vietnamese Cards Injected dynamically -->
                            </div>
                        </div>

                        <!-- Floating Combo system animation -->
                        <div id="combo-badge-floating" class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-30 opacity-0 transform scale-50 transition-all duration-300">
                            <div class="bg-gradient-to-tr from-orange-500 to-yellow-500 text-white font-black px-6 py-4 rounded-3xl shadow-xl shadow-orange-500/30 text-xl border-4 border-white flex flex-col items-center">
                                <span class="text-2xl animate-bounce">🔥 COMBO!</span>
                                <span class="text-sm font-extrabold uppercase mt-1">HỆ SỐ X<span id="combo-multiplier-val">2</span></span>
                            </div>
                        </div>

                        <!-- SCREEN 1: Start/Launch Game -->
                        <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-white rounded-3xl">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white text-3xl shadow-lg shadow-indigo-500/30">
                                <i class="fas fa-brain animate-bounce" style="animation-duration: 3s;"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-wide">THỬ THÁCH GHÉP CẶP</h3>
                                <p class="text-slate-500 text-xs max-w-sm mt-2 leading-relaxed font-bold">Hãy lắng nghe các đoạn hội thoại hoặc từ phát âm, ghép chính xác các cặp từ Tiếng Anh và dịch nghĩa tương ứng để vượt qua cửa ải.</p>
                            </div>
                            <button type="button" id="btn-game-start" class="btn-3d px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black text-sm tracking-wider rounded-2xl shadow-lg shadow-indigo-500/20 cursor-pointer border-0 border-b-indigo-700">
                                BẮT ĐẦU THỬ THÁCH
                            </button>
                        </div>

                        <!-- SCREEN 2: Game Over Screen -->
                        <div id="screen-gameover" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-white rounded-3xl hidden">
                            <div class="w-16 h-16 rounded-3xl bg-rose-50 border-2 border-rose-300 text-rose-500 flex items-center justify-center text-2xl shadow-lg shadow-rose-500/10">
                                <i class="fas fa-heart-broken animate-pulse"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-rose-600 uppercase tracking-wider">CẠN KIỆT SINH LỰC</h3>
                                <p class="text-slate-500 text-xs max-w-xs mt-2 leading-relaxed font-bold">Bạn đã lật sai quá số lượt cho phép. Hãy cẩn thận hơn vào lần sau nhé!</p>
                            </div>
                            <button type="button" id="btn-game-replay" class="btn-3d px-6 py-3 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-750 text-white font-extrabold text-xs tracking-wider rounded-xl cursor-pointer border-0 border-b-red-800 shadow-md">
                                <i class="fas fa-redo mr-1"></i> CHƠI LẠI NGAY
                            </button>
                        </div>

                        <!-- SCREEN 3: Level Completed Screen (Win Modal) -->
                        <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-white rounded-3xl hidden">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-amber-400 to-orange-500 flex items-center justify-center text-white text-3xl shadow-xl shadow-orange-500/30">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-amber-500 uppercase tracking-wide">CHIẾN THẮNG XUẤT SẮC!</h3>
                                <p class="text-slate-500 text-xs max-w-sm mt-2 leading-relaxed font-bold">Tuyệt vời! Bạn đã kết nối đúng tất cả các thẻ chữ Tiếng Anh và dịch nghĩa thành công!</p>
                                
                                <div class="grid grid-cols-3 gap-3 mt-4 max-w-xs mx-auto">
                                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-2.5">
                                        <span class="text-[9px] text-slate-400 font-black block uppercase">Điểm XP</span>
                                        <span class="text-sm font-black text-indigo-600">+<span id="win-xp">100</span> XP</span>
                                    </div>
                                    <div class="bg-pink-50 border border-pink-100 rounded-2xl p-2.5">
                                        <span class="text-[9px] text-slate-400 font-black block uppercase">Chính xác</span>
                                        <span class="text-sm font-black text-pink-600" id="win-accuracy">100%</span>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-2.5">
                                        <span class="text-[9px] text-slate-400 font-black block uppercase">Đánh giá</span>
                                        <span class="text-sm font-black text-amber-600">⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-3d group flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-sm tracking-wider px-8 py-3.5 rounded-2xl shadow-lg shadow-emerald-500/25 border-0 border-b-emerald-700 cursor-pointer" id="btn-submit-test">
                                <i class="fas fa-crown text-amber-200 group-hover:animate-bounce"></i>
                                HOÀN THÀNH THỬ THÁCH
                            </button>
                        </div>

                    </div>
                </div>

            </div>

            <!-- 3. BOTTOM UTILITIES & POWER-UPS -->
            <div class="flex flex-wrap items-center justify-between gap-4 bg-white/70 backdrop-blur border border-white/80 p-4 rounded-3xl shadow-sm">
                <!-- Power-ups circular buttons -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Bổ Trợ:</span>
                    <button type="button" id="btn-power-hint" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-400 to-yellow-500 text-white flex items-center justify-center cursor-pointer shadow-md shadow-yellow-500/20 border-0 border-b-yellow-700 text-sm" title="Gợi ý một cặp từ">
                        <i class="fas fa-lightbulb"></i>
                    </button>
                    <button type="button" id="btn-power-replay" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-500 text-white flex items-center justify-center cursor-pointer shadow-md shadow-blue-500/20 border-0 border-b-blue-700 text-sm" title="Phát lại Audio">
                        <i class="fas fa-volume-up"></i>
                    </button>
                    <button type="button" id="btn-power-reveal" class="btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 text-white flex items-center justify-center cursor-pointer shadow-md shadow-pink-500/20 border-0 border-b-pink-700 text-sm" title="Tự động mở một cặp ngẫu nhiên">
                        <i class="fas fa-bolt"></i>
                    </button>
                </div>

                <!-- Helper guide keys -->
                <div class="text-xs text-slate-400 font-bold flex items-center gap-4">
                    <span><i class="fas fa-mouse mr-1"></i> Nhấp chọn 1 thẻ anh + 1 thẻ việt</span>
                    <span><i class="fas fa-volume-up mr-1"></i> English phát âm khi click</span>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- GAME CONTROLLER JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Load dynamic vocabulary pairs from PHP
    const vocabPairs = [
        @foreach($allQuestions as $q)
            {
                id: "{{ $q['id'] }}",
                english: "{{ addslashes($q['english']) }}",
                vietnamese: "{{ addslashes($q['vietnamese']) }}",
                optionId: "{{ $q['option_id'] }}"
            },
        @endforeach
    ];

    // Web Audio Synthesizer
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
            gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
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
        synthBeep(180, 0.3, 'triangle');
    };

    const playClickSound = () => {
        synthBeep(600, 0.05, 'sine');
    };

    // English speech synthesis
    const speakWord = (text) => {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        }
    };

    // UI elements references
    const screenLaunch = document.getElementById('screen-launch');
    const screenGameOver = document.getElementById('screen-gameover');
    const screenWin = document.getElementById('screen-win');
    const englishCardsGrid = document.getElementById('english-cards-grid');
    const vietnameseCardsGrid = document.getElementById('vietnamese-cards-grid');
    const livesCounterText = document.getElementById('lives-counter-text');
    const scoreCounterText = document.getElementById('score-counter-text');
    const progressBarHud = document.getElementById('progress-bar-hud');
    const progressTextHud = document.getElementById('progress-text-hud');
    const listensCounter = document.getElementById('listens-counter');
    const selectTrack = document.getElementById('audio-select-track');
    
    // Waveform Container
    const waveformContainer = document.getElementById('audio-waveform-container');
    const barCount = 28;
    for (let i = 0; i < barCount; i++) {
        const bar = document.createElement('div');
        bar.className = 'w-1 bg-indigo-400 rounded-t h-1 transition-all duration-100';
        waveformContainer.appendChild(bar);
    }
    const waveBars = waveformContainer.children;

    // Game variables
    let lives = 5;
    let score = 0;
    let streak = 0;
    let matchedPairsCount = 0;
    let isGameRunning = false;
    let isEvaluating = false;
    
    let selectedEnglishCard = null;
    let selectedVietnameseCard = null;
    let audioTrack = new Audio();
    let isPlayingAudio = false;
    let audioProgressTimer = null;
    let remainingListens = 5;

    // Powerup count
    let hintUsed = false;

    // Play & Pause control
    const playCurrentSelectedTrack = () => {
        initAudioContext();
        if (isPlayingAudio) {
            audioTrack.pause();
            isPlayingAudio = false;
            document.getElementById('play-icon').className = 'fas fa-play text-sm';
            stopWaveformAnimation();
            return;
        }

        if (remainingListens <= 0) {
            alert('Bạn đã dùng hết số lượt nghe miễn phí!');
            return;
        }

        const url = selectTrack.value;
        audioTrack.src = url;
        audioTrack.play().then(() => {
            isPlayingAudio = true;
            document.getElementById('play-icon').className = 'fas fa-pause text-sm';
            remainingListens--;
            listensCounter.innerText = `Còn ${remainingListens} lượt nghe`;
            startWaveformAnimation();
        }).catch(e => {
            speakWord(vocabPairs[Math.floor(Math.random() * vocabPairs.length)].english);
        });
    };

    audioTrack.addEventListener('ended', () => {
        isPlayingAudio = false;
        document.getElementById('play-icon').className = 'fas fa-play text-sm';
        stopWaveformAnimation();
    });

    let waveTimer = null;
    const startWaveformAnimation = () => {
        clearInterval(waveTimer);
        waveTimer = setInterval(() => {
            for (let i = 0; i < barCount; i++) {
                const heightVal = Math.floor(5 + Math.random() * 25);
                waveBars[i].style.height = `${heightVal}px`;
            }
        }, 120);
    };

    const stopWaveformAnimation = () => {
        clearInterval(waveTimer);
        for (let i = 0; i < barCount; i++) {
            waveBars[i].style.height = '4px';
        }
    };

    // Shuffler
    const shuffleArray = (array) => {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    };

    // Card face styling helpers
    const openCard = (card) => {
        card.classList.remove('closed');
        const type = card.dataset.type;
        const text = card.dataset.text;
        if (type === 'english') {
            card.className = 'card-3d bg-white text-indigo-900 border-3 border-blue-500 px-4 py-4 rounded-2xl text-center font-black text-xs md:text-sm cursor-pointer shadow-[0_4px_15px_rgba(59,130,246,0.25)] transition select-none flex items-center justify-center gap-2 ring-4 ring-blue-100 scale-105 min-h-[64px]';
            card.innerHTML = `<span>${text}</span> <i class="fas fa-volume-up text-xs text-blue-500"></i>`;
        } else {
            card.className = 'card-3d bg-white text-rose-900 border-3 border-pink-500 px-4 py-4 rounded-2xl text-center font-black text-xs md:text-sm cursor-pointer shadow-[0_4px_15px_rgba(244,63,94,0.25)] transition select-none flex items-center justify-center ring-4 ring-pink-100 scale-105 min-h-[64px]';
            card.innerHTML = `<span>${text}</span>`;
        }
    };

    const closeCard = (card) => {
        card.classList.add('closed');
        const type = card.dataset.type;
        if (type === 'english') {
            card.className = 'card-3d closed bg-gradient-to-br from-indigo-500 via-blue-500 to-cyan-400 text-white border-2 border-white border-b-cyan-300 px-4 py-4 rounded-2xl text-center font-bold text-xs md:text-sm cursor-pointer shadow-[0_4px_12px_rgba(59,130,246,0.25)] transition select-none flex items-center justify-center min-h-[64px] hover:scale-105 hover:shadow-[0_0_15px_rgba(59,130,246,0.5)]';
            card.innerHTML = `<i class="fas fa-question text-white text-xl font-black drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] animate-pulse"></i>`;
        } else {
            card.className = 'card-3d closed bg-gradient-to-br from-purple-500 via-pink-500 to-rose-400 text-white border-2 border-white border-b-rose-300 px-4 py-4 rounded-2xl text-center font-bold text-xs md:text-sm cursor-pointer shadow-[0_4px_12px_rgba(244,63,94,0.25)] transition select-none flex items-center justify-center min-h-[64px] hover:scale-105 hover:shadow-[0_0_15px_rgba(244,63,94,0.5)]';
            card.innerHTML = `<i class="fas fa-question text-white text-xl font-black drop-shadow-[0_2px_4px_rgba(0,0,0,0.15)] animate-pulse"></i>`;
        }
    };

    // 3D scale flip transition animation
    const flipOpen = (card, onComplete) => {
        card.style.transform = 'scale(0.8) rotateY(90deg)';
        card.style.opacity = '0.3';
        setTimeout(() => {
            onComplete();
            card.style.transform = 'scale(1.05) rotateY(0deg)';
            card.style.opacity = '1';
            setTimeout(() => {
                card.style.transform = 'scale(1) rotateY(0deg)';
            }, 150);
        }, 150);
    };

    const flipClose = (card, onComplete) => {
        card.style.transform = 'scale(0.8) rotateY(90deg)';
        card.style.opacity = '0.3';
        setTimeout(() => {
            onComplete();
            card.style.transform = 'scale(1) rotateY(0deg)';
            card.style.opacity = '1';
        }, 150);
    };

    // Render Play Board
    const renderPlayBoard = () => {
        englishCardsGrid.innerHTML = '';
        vietnameseCardsGrid.innerHTML = '';
        selectedEnglishCard = null;
        selectedVietnameseCard = null;

        // Shuffle arrays separately
        let englishList = vocabPairs.map(p => ({ id: p.id, text: p.english, optionId: p.optionId }));
        let vietnameseList = vocabPairs.map(p => ({ id: p.id, text: p.vietnamese, optionId: p.optionId }));

        englishList = shuffleArray(englishList);
        vietnameseList = shuffleArray(vietnameseList);

        document.getElementById('en-cards-count').innerText = `Còn ${englishList.length} thẻ`;
        document.getElementById('vi-cards-count').innerText = `Còn ${vietnameseList.length} thẻ`;

        // Render English cards
        englishList.forEach(item => {
            const card = document.createElement('div');
            card.dataset.id = item.id;
            card.dataset.type = 'english';
            card.dataset.text = item.text;
            card.dataset.optionId = item.optionId;
            closeCard(card);
            
            card.addEventListener('click', () => handleCardSelect(card));
            englishCardsGrid.appendChild(card);
        });

        // Render Vietnamese cards
        vietnameseList.forEach(item => {
            const card = document.createElement('div');
            card.dataset.id = item.id;
            card.dataset.type = 'vietnamese';
            card.dataset.text = item.text;
            closeCard(card);
            
            card.addEventListener('click', () => handleCardSelect(card));
            vietnameseCardsGrid.appendChild(card);
        });
    };

    // Sparkle explosion particle burst
    const spawnSparkles = (x, y) => {
        const count = 15;
        const colors = ['#f472b6', '#38bdf8', '#34d399', '#fbbf24', '#ffffff'];
        for (let i = 0; i < count; i++) {
            const star = document.createElement('i');
            star.className = 'fas fa-star sparkle text-xs';
            star.style.color = colors[Math.floor(Math.random() * colors.length)];
            
            const randX = (Math.random() - 0.5) * 120;
            const randY = -50 - Math.random() * 80;
            star.style.setProperty('--tw-x', `${randX}px`);
            star.style.setProperty('--tw-y', `${randY}px`);
            star.style.left = `${x}px`;
            star.style.top = `${y}px`;
            
            document.getElementById('game-play-board').appendChild(star);
            setTimeout(() => star.remove(), 1200);
        }
    };

    // Handle Card Click selection
    const handleCardSelect = (card) => {
        if (!isGameRunning || isEvaluating) return;
        if (card.classList.contains('disabled')) return;
        if (card === selectedEnglishCard || card === selectedVietnameseCard) return;

        initAudioContext();
        playClickSound();

        const type = card.dataset.type;

        if (type === 'english') {
            const prevEn = selectedEnglishCard;
            selectedEnglishCard = card;
            
            flipOpen(card, () => {
                openCard(card);
                speakWord(card.dataset.text);
            });

            if (prevEn) {
                flipClose(prevEn, () => {
                    closeCard(prevEn);
                });
            }
        } else {
            const prevVi = selectedVietnameseCard;
            selectedVietnameseCard = card;

            flipOpen(card, () => {
                openCard(card);
            });

            if (prevVi) {
                flipClose(prevVi, () => {
                    closeCard(prevVi);
                });
            }
        }

        if (selectedEnglishCard && selectedVietnameseCard) {
            isEvaluating = true;
            setTimeout(() => {
                checkSelectedPair();
            }, 400);
        }
    };

    // Evaluate matching pair
    const checkSelectedPair = () => {
        const enId = selectedEnglishCard.dataset.id;
        const viId = selectedVietnameseCard.dataset.id;

        if (enId === viId) {
            // MATCH SUCCESS!
            playCorrectSound();
            
            // Spawn sparkles at center of English card
            const enRect = selectedEnglishCard.getBoundingClientRect();
            const parentRect = document.getElementById('game-play-board').getBoundingClientRect();
            const x = enRect.left - parentRect.left + enRect.width / 2;
            const y = enRect.top - parentRect.top + enRect.height / 2;
            spawnSparkles(x, y);

            // Set Matched styles
            selectedEnglishCard.className = 'card-3d bg-gradient-to-br from-emerald-400 via-teal-500 to-green-600 text-white border-2 border-emerald-300 border-b-green-800 px-4 py-4 rounded-2xl text-center font-bold text-xs md:text-sm shadow-[0_0_15px_rgba(16,185,129,0.4)] opacity-70 select-none flex items-center justify-center gap-2 disabled min-h-[64px]';
            selectedEnglishCard.innerHTML = `<span>${selectedEnglishCard.dataset.text}</span> <i class="fas fa-check-circle text-emerald-100"></i>`;

            selectedVietnameseCard.className = 'card-3d bg-gradient-to-br from-emerald-400 via-teal-500 to-green-600 text-white border-2 border-emerald-300 border-b-green-800 px-4 py-4 rounded-2xl text-center font-bold text-xs md:text-sm shadow-[0_0_15px_rgba(16,185,129,0.4)] opacity-70 select-none flex items-center justify-center gap-2 disabled min-h-[64px]';
            selectedVietnameseCard.innerHTML = `<span>${selectedVietnameseCard.dataset.text}</span> <i class="fas fa-check-circle text-emerald-100"></i>`;

            // Set answer in form
            const inputField = document.getElementById('ans-' + enId);
            if (inputField) {
                inputField.value = selectedEnglishCard.dataset.optionId;
            }

            // Streak & score updates
            streak++;
            score += 15 * streak;
            scoreCounterText.innerText = `⭐ ${score} XP`;
            
            // Show streak badge
            if (streak > 1) {
                document.getElementById('streak-badge').classList.remove('hidden');
                document.getElementById('streak-counter-text').innerText = streak;
                // Floating combo badge animation
                const comboBadge = document.getElementById('combo-badge-floating');
                document.getElementById('combo-multiplier-val').innerText = streak;
                comboBadge.classList.remove('opacity-0', 'scale-50');
                comboBadge.classList.add('opacity-100', 'scale-100');
                setTimeout(() => {
                    comboBadge.classList.remove('opacity-100', 'scale-100');
                    comboBadge.classList.add('opacity-0', 'scale-50');
                }, 1000);
            }

            matchedPairsCount++;
            const pct = Math.round((matchedPairsCount / vocabPairs.length) * 100);
            progressBarHud.style.width = `${pct}%`;
            progressTextHud.innerText = `${pct}%`;

            selectedEnglishCard = null;
            selectedVietnameseCard = null;
            isEvaluating = false;

            // Check Victory Condition
            if (matchedPairsCount >= vocabPairs.length) {
                setTimeout(() => finishGame(true), 800);
            }

        } else {
            // MATCH FAILURE
            playWrongSound();
            
            // Reset streak
            streak = 0;
            document.getElementById('streak-badge').classList.add('hidden');

            const shakeEn = selectedEnglishCard;
            const shakeVi = selectedVietnameseCard;

            shakeEn.className = 'card-3d bg-gradient-to-br from-rose-500 via-red-500 to-red-650 text-white border-2 border-rose-300 border-b-red-800 px-4 py-4 rounded-2xl text-center font-bold text-xs md:text-sm shadow-[0_0_15px_rgba(239,68,68,0.5)] select-none flex items-center justify-center gap-2 animate-shake min-h-[64px]';
            shakeVi.className = 'card-3d bg-gradient-to-br from-rose-500 via-red-500 to-red-650 text-white border-2 border-rose-300 border-b-red-800 px-4 py-4 rounded-2xl text-center font-bold text-xs md:text-sm shadow-[0_0_15px_rgba(239,68,68,0.5)] select-none flex items-center justify-center gap-2 animate-shake min-h-[64px]';

            setTimeout(() => {
                flipClose(shakeEn, () => {
                    closeCard(shakeEn);
                });
                flipClose(shakeVi, () => {
                    closeCard(shakeVi);
                });

                selectedEnglishCard = null;
                selectedVietnameseCard = null;
                isEvaluating = false;
            }, 1200);

            // Reduce lives
            lives--;
            livesCounterText.innerText = `❤️ ${lives}`;

            if (lives <= 0) {
                setTimeout(() => finishGame(false), 500);
            }
        }
    };

    // Powerup: Hint
    document.getElementById('btn-power-hint').addEventListener('click', () => {
        if (!isGameRunning || isEvaluating) return;
        initAudioContext();
        
        // Find first unmatched pair
        const unmatchedEn = Array.from(englishCardsGrid.children).find(c => !c.classList.contains('disabled'));
        if (!unmatchedEn) return;

        const matchingVi = Array.from(vietnameseCardsGrid.children).find(c => c.dataset.id === unmatchedEn.dataset.id);
        
        isEvaluating = true;
        // Flip open both matching cards
        flipOpen(unmatchedEn, () => openCard(unmatchedEn));
        flipOpen(matchingVi, () => openCard(matchingVi));

        setTimeout(() => {
            // Flip close both matching cards
            flipClose(unmatchedEn, () => closeCard(unmatchedEn));
            flipClose(matchingVi, () => closeCard(matchingVi));
            isEvaluating = false;
        }, 1800);
    });

    // Powerup: Replay audio
    document.getElementById('btn-power-replay').addEventListener('click', () => {
        if (!isGameRunning) return;
        playCurrentSelectedTrack();
    });

    // Powerup: Auto Reveal Pair
    document.getElementById('btn-power-reveal').addEventListener('click', () => {
        if (!isGameRunning || isEvaluating) return;
        initAudioContext();
        
        const unmatchedEn = Array.from(englishCardsGrid.children).find(c => !c.classList.contains('disabled'));
        if (!unmatchedEn) return;

        const matchingVi = Array.from(vietnameseCardsGrid.children).find(c => c.dataset.id === unmatchedEn.dataset.id);

        selectedEnglishCard = unmatchedEn;
        selectedVietnameseCard = matchingVi;

        // Flip both open first
        flipOpen(unmatchedEn, () => openCard(unmatchedEn));
        flipOpen(matchingVi, () => openCard(matchingVi));

        isEvaluating = true;
        setTimeout(() => {
            checkSelectedPair();
        }, 400);
    });

    // Game lifecycle hooks
    const startGame = () => {
        initAudioContext();
        screenLaunch.classList.add('hidden');
        screenGameOver.classList.add('hidden');
        screenWin.classList.add('hidden');

        lives = 5;
        score = 0;
        streak = 0;
        matchedPairsCount = 0;
        remainingListens = 5;

        livesCounterText.innerText = `❤️ ${lives}`;
        scoreCounterText.innerText = `⭐ ${score} XP`;
        listensCounter.innerText = `Còn ${remainingListens} lượt nghe`;
        progressBarHud.style.width = '0%';
        progressTextHud.innerText = '0%';

        isGameRunning = true;
        renderPlayBoard();
        stopWaveformAnimation();
    };

    const finishGame = (isWin) => {
        isGameRunning = false;
        audioTrack.pause();
        stopWaveformAnimation();

        if (isWin) {
            document.getElementById('win-xp').innerText = score + 50;
            const accuracyVal = Math.round((matchedPairsCount / (matchedPairsCount + (5 - lives))) * 100) || 100;
            document.getElementById('win-accuracy').innerText = `${accuracyVal}%`;
            
            screenWin.classList.remove('hidden');
            playCorrectSound();
        } else {
            screenGameOver.classList.remove('hidden');
            playWrongSound();
        }
    };

    // Set play buttons trigger
    document.getElementById('btn-audio-play').addEventListener('click', playCurrentSelectedTrack);
    document.getElementById('btn-audio-replay').addEventListener('click', () => {
        isPlayingAudio = false;
        playCurrentSelectedTrack();
    });

    // Start/Replay Triggers
    document.getElementById('btn-game-start').addEventListener('click', startGame);
    document.getElementById('btn-game-replay').addEventListener('click', startGame);
});
</script>
