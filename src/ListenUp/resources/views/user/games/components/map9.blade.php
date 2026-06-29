@php
    $questions = [];
    foreach($test->phan as $phan) {
        foreach($phan->cauhoi as $question) {
            // Find correct option (DapAn is 'Dung' or 1)
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->firstWhere('DapAn', 1);
            
            // Map options to True / False
            $trueOption = null;
            $falseOption = null;
            
            foreach($question->phuongancauhoi as $option) {
                $ndpaLower = strtolower($option->NDPA);
                if (strpos($ndpaLower, 'true') !== false) {
                    $trueOption = $option;
                } else if (strpos($ndpaLower, 'false') !== false) {
                    $falseOption = $option;
                }
            }
            
            // Fallbacks in case naming is different
            if (!$trueOption && count($question->phuongancauhoi) > 0) {
                $trueOption = $question->phuongancauhoi[0];
            }
            if (!$falseOption && count($question->phuongancauhoi) > 1) {
                $falseOption = $question->phuongancauhoi[1];
            }
            
            $correctValue = 'true';
            if ($correctOption && $falseOption && $correctOption->MaPA == $falseOption->MaPA) {
                $correctValue = 'false';
            }
            
            $questions[] = [
                'question' => $question,
                'audio' => $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '',
                'correct_option' => $correctOption,
                'true_option' => $trueOption,
                'false_option' => $falseOption,
                'correct_value' => $correctValue,
                'part_title' => $phan->TenPhan,
            ];
        }
    }
    $numPlanks = count($questions);
@endphp

@if($numPlanks === 0)
    <div class="text-center p-12 text-slate-500 bg-white/80 rounded-3xl border border-indigo-100 shadow-sm">
        <i class="fas fa-exclamation-circle text-4xl text-indigo-400 mb-3 block"></i>
        <h5 class="font-extrabold text-indigo-950">Chưa có câu hỏi!</h5>
        <p class="text-sm text-slate-400 mb-0">Hiện tại chưa có câu hỏi True/False cho bài test này.</p>
    </div>
@else
<!-- Component: GamePage -->
<div id="game-workspace" class="w-full flex flex-col items-center select-none font-display text-slate-800">
    
    <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="map9-game-form" class="w-full max-w-4xl flex flex-col gap-6 relative">
        @csrf
        <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
        <input type="hidden" name="start_time" value="{{ time() }}">
        
        @foreach($questions as $qData)
            <input type="hidden" name="answers[{{ $qData['question']->MaCauHoi }}]" id="ans-{{ $qData['question']->MaCauHoi }}" value="">
        @endforeach

        <!-- UPPER PANEL: HUD & Controls -->
        <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4 items-stretch relative z-30">
            
            <!-- Component: ScoreBoard -->
            <div class="md:col-span-5 bg-white/95 backdrop-blur-md rounded-3xl p-5 border border-indigo-150 shadow-md flex flex-col justify-between">
                <div class="flex items-center gap-3 border-b border-indigo-100 pb-3 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-md">
                        <i class="fas fa-route text-xs"></i>
                    </div>
                    <span class="font-black text-indigo-950 uppercase tracking-wider text-xs">Tiến trình vượt ải</span>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-xs font-black text-slate-650">
                        <span>CÂU HỎI HIỆN TẠI:</span>
                        <span id="hud-progress-text" class="text-indigo-600">Câu 0 / {{ $numPlanks }}</span>
                    </div>
                    <div class="w-full bg-slate-200 h-3 rounded-full overflow-hidden p-0.5 border border-slate-300 shadow-inner">
                        <div id="hud-progress-bar" class="bg-gradient-to-r from-emerald-400 to-teal-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-100">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider">ĐIỂM TÍCH LŨY:</span>
                    <span id="hud-score-text" class="text-xl font-black text-amber-500">0</span>
                </div>
            </div>

            <!-- Component: AudioPlayer -->
            <div class="md:col-span-7 bg-white/95 backdrop-blur-md rounded-3xl p-5 border border-indigo-150 shadow-md flex flex-col justify-between">
                <div class="flex items-center gap-3 border-b border-indigo-100 pb-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-400 to-blue-500 text-white flex items-center justify-center shadow-md">
                        <i class="fas fa-volume-up text-xs"></i>
                    </div>
                    <span class="font-black text-indigo-950 uppercase tracking-wider text-xs">Máy nghe nhận định</span>
                </div>

                <div class="flex flex-col items-center justify-center gap-3 py-1">
                    <!-- Equalizer Wave -->
                    <div id="equalizer-container" class="flex items-end gap-1 h-5 w-12 justify-center mb-1">
                        <span class="w-1.5 bg-sky-500 rounded-full sound-bar" style="height: 30%"></span>
                        <span class="w-1.5 bg-sky-400 rounded-full sound-bar" style="height: 55%"></span>
                        <span class="w-1.5 bg-indigo-500 rounded-full sound-bar" style="height: 40%"></span>
                        <span class="w-1.5 bg-indigo-400 rounded-full sound-bar" style="height: 25%"></span>
                    </div>

                    <div class="flex items-center gap-5">
                        <!-- Play/Pause Button -->
                        <button type="button" id="btn-sound-play" class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 hover:from-sky-650 hover:to-indigo-750 text-white flex items-center justify-center shadow-md hover:scale-105 active:scale-95 transition-all border border-sky-400/20 disabled:opacity-50 disabled:pointer-events-none">
                            <i class="fas fa-play text-lg ml-0.5"></i>
                        </button>

                        <div class="flex gap-2">
                            <button type="button" id="btn-speed-slow" class="px-3 py-1.5 text-xs font-black rounded-xl border border-slate-350 text-slate-550 hover:bg-slate-50 transition-all select-none">
                                0.8x
                            </button>
                            <button type="button" id="btn-speed-normal" class="px-3 py-1.5 text-xs font-black rounded-xl border border-sky-500 text-sky-600 bg-sky-50 transition-all select-none">
                                1.0x
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PLAYING FIELD: Vertical Scrolling Track -->
        <div id="game-arena" class="w-full h-[650px] rounded-[2.5rem] border-4 border-indigo-200/50 shadow-inner relative overflow-hidden bg-cover bg-center bg-no-repeat transition-all duration-500 select-none" style="background-image: url('{{ asset('images/map9_bg.jpg') }}');">
            <!-- Light overlay to make elements read better -->
            <div id="arena-overlay" class="absolute inset-0 bg-slate-900/10 pointer-events-none z-10"></div>
            
            <!-- Scrollable Track holding QuestionRows (climbing from bottom to top) -->
            <div id="game-track" class="w-full flex flex-col-reverse items-center absolute left-0 top-0 transition-transform duration-500 ease-out" style="padding-top: 150px; padding-bottom: 250px;">
                
                <!-- Safe Starting Platform at the bottom of the track -->
                <div id="row-0" class="plank-row w-full flex justify-center py-6" data-row="0">
                    <div id="start-platform" class="relative px-8 py-3.5 bg-gradient-to-r from-amber-400 to-orange-500 border-2 border-amber-500 rounded-2xl text-slate-950 font-black text-xs shadow-md select-none tracking-widest uppercase">
                        Vùng bắt đầu
                    </div>
                </div>
                
                @foreach($questions as $index => $qData)
                    <!-- Component: QuestionRow -->
                    <div class="plank-row w-full flex flex-col items-center my-16 px-6 relative transition-all duration-350" id="row-{{ $index + 1 }}" data-row="{{ $index + 1 }}">
                        
                        <!-- Question Card -->
                        <div class="mb-4 bg-white/95 backdrop-blur-md px-6 py-3 rounded-2xl border border-indigo-100 shadow-sm text-center max-w-lg mx-auto select-none transition-all duration-300 transform scale-95 opacity-60 active-row-visible" id="question-card-{{ $index + 1 }}">
                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest block mb-0.5">Nội dung câu hỏi {{ $index + 1 }}</span>
                            <div class="text-xs font-bold text-slate-700 leading-normal" id="question-text-{{ $index + 1 }}">
                                (Nhấp phát audio để xem nội dung câu hỏi)
                            </div>
                        </div>

                        <!-- Flex Row containing 2 platforms -->
                        <div class="flex flex-row justify-center items-center gap-8 md:gap-20 w-full">
                            <!-- Component: GrassPlatform (TRUE) -->
                            <div class="platform-btn relative w-[160px] h-[65px] md:w-[220px] md:h-[75px] cursor-pointer transition-transform duration-200" data-choice="true">
                                <img src="{{ asset('images/map9_platform_true.png') }}" class="w-full h-full object-contain pointer-events-none drop-shadow-md" alt="True platform">
                                <span class="absolute inset-0 flex items-center justify-center text-white font-black text-lg md:text-2xl tracking-widest uppercase select-none drop-shadow-[0_2px_4px_rgba(0,0,0,0.85)] pb-2">TRUE</span>
                            </div>

                            <!-- Component: GrassPlatform (FALSE) -->
                            <div class="platform-btn relative w-[160px] h-[65px] md:w-[220px] md:h-[75px] cursor-pointer transition-transform duration-200" data-choice="false">
                                <img src="{{ asset('images/map9_platform_false.png') }}" class="w-full h-full object-contain pointer-events-none drop-shadow-md" alt="False platform">
                                <span class="absolute inset-0 flex items-center justify-center text-white font-black text-lg md:text-2xl tracking-widest uppercase select-none drop-shadow-[0_2px_4px_rgba(0,0,0,0.85)] pb-2">FALSE</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Component: PlayerCharacter -->
            <div id="player-character" class="player-character absolute w-[60px] h-[60px] z-30 pointer-events-none" style="display: none;">
                <div class="char-shadow absolute bottom-[-4px] left-[15%] w-[70%] h-[5px] bg-black/30 rounded-full blur-[1px]"></div>
                <div class="char-inner w-full h-full relative char-idle">
                    <img src="{{ Auth::user()->AnhDaiDien ? asset('storage/' . Auth::user()->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->UserName).'&background=0D8ABC&color=fff' }}" alt="Player Avatar" class="w-full h-full rounded-full border-2 border-white shadow-md bg-indigo-950 object-cover">
                    <div class="absolute inset-[-2px] rounded-full border border-amber-400 animate-ping opacity-30"></div>
                </div>
            </div>

            <!-- Screen: Launch / Screen 1 -->
            <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 bg-slate-950/80 backdrop-blur-sm rounded-[2.5rem]">
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-sky-400 to-indigo-600 flex items-center justify-center text-white text-3xl shadow-lg border-2 border-white/20">
                    <i class="fas fa-play animate-bounce"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400 uppercase tracking-widest">THỬ THÁCH VƯỢT ẢI</h4>
                    <p class="text-slate-400 text-xs max-w-sm mt-3 leading-relaxed">Hãy lắng nghe các nhận định và nhảy lên thảm cỏ tương ứng ở mỗi hàng. Nếu chọn đúng, bạn sẽ tiếp tục hành trình. Chọn sai sẽ rơi tự do!</p>
                </div>
                <button type="button" id="btn-game-start" class="px-8 py-4 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-650 hover:to-indigo-755 text-white font-black text-sm tracking-wider rounded-2xl shadow-lg hover:scale-105 active:scale-95 transition-all select-none border border-sky-450/20 uppercase">
                    Bắt đầu thử thách
                </button>
            </div>

            <!-- Component: GameOverModal (Wrong Answer / Overlay Screen 2) -->
            <div id="screen-wrong" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-45 bg-red-950/90 backdrop-blur-sm rounded-[2.5rem] hidden">
                <div class="w-16 h-16 rounded-full bg-red-900 border-2 border-red-500 flex items-center justify-center text-white text-3xl mb-4 animate-bounce">
                    <i class="fas fa-tint"></i>
                </div>
                <h4 class="text-2xl font-black text-red-550 uppercase tracking-wider mb-2">BẠN ĐÃ RƠI XUỐNG VỰC!</h4>
                <p class="text-slate-350 text-xs max-w-xs leading-relaxed">Nhảy sai rồi! Thảm cỏ biến mất và bạn rơi tự do. Nhận định này không đúng với âm thanh nghe được.</p>
            </div>

            <!-- Component: GameOverModal (Victory / Overlay Screen 3) -->
            <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 bg-slate-950/85 backdrop-blur-sm rounded-[2.5rem] hidden">
                <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white text-3xl shadow-xl shadow-emerald-500/30 border-2 border-white/20 animate-bounce">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-emerald-450 uppercase tracking-wider">HOÀN THÀNH CHẶNG ĐƯỜNG!</h4>
                    <p class="text-slate-400 text-xs max-w-sm mt-3 leading-relaxed">Tuyệt vời! Bạn đã vượt qua tất cả nhận định nghe hiểu và về đích an toàn.</p>
                </div>
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-sm tracking-wider rounded-2xl shadow-lg hover:scale-105 active:scale-95 transition-all select-none border border-emerald-400/20 uppercase">
                    Nhận Phần Thưởng
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    /* Question row highlights */
    .active-row .platform-btn {
        animation: active-pulse 2s infinite alternate;
    }
    .active-row [id^="question-card-"] {
        transform: scale(1) !important;
        opacity: 1 !important;
        border-color: #818cf8 !important;
        box-shadow: 0 4px 12px rgba(129, 140, 248, 0.15) !important;
    }
    @keyframes active-pulse {
        0% { filter: drop-shadow(0 4px 6px rgba(99, 102, 241, 0.2)); }
        100% { filter: drop-shadow(0 8px 12px rgba(99, 102, 241, 0.45)); transform: scale(1.02); }
    }

    /* Hover effect on active platforms */
    .active-row .platform-btn:hover {
        transform: scale(1.08) translateY(-4px) !important;
        filter: drop-shadow(0 12px 18px rgba(56, 189, 248, 0.5)) !important;
    }

    /* Correct/Incorrect styles */
    .platform-btn.correct-glow {
        filter: drop-shadow(0 0 15px rgba(16, 185, 129, 0.8)) !important;
        transform: scale(1.05) !important;
    }
    .platform-btn.wrong-glow {
        animation: platform-shake 0.5s ease-in-out;
        filter: drop-shadow(0 0 15px rgba(239, 68, 68, 0.8)) !important;
    }
    @keyframes platform-shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-8px); }
        40%, 80% { transform: translateX(8px); }
    }

    /* Character layout */
    .player-character {
        position: absolute;
        width: 60px;
        height: 60px;
        z-index: 30;
    }
    .char-idle {
        animation: float-anim 2s infinite ease-in-out;
    }
    @keyframes float-anim {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    /* Jump & Fall CSS animations */
    .char-jump {
        animation: jump-arc-anim 0.6s ease-in-out forwards;
    }
    @keyframes jump-arc-anim {
        0% { transform: scale(1) translateY(0); }
        50% { transform: scale(1.3) translateY(-45px); }
        100% { transform: scale(1) translateY(0); }
    }

    .char-fall {
        animation: fall-spin-anim 0.5s cubic-bezier(0.55, 0.085, 0.68, 0.53) forwards;
    }
    @keyframes fall-spin-anim {
        0% { transform: rotate(0deg) scale(1); opacity: 1; }
        100% { transform: rotate(360deg) scale(0.1) translateY(350px); opacity: 0; }
    }

    /* Equalizer and sound animation */
    .sound-bar {
        transition: height 0.15s ease-in-out;
    }
    @keyframes sound-pulse-bar {
        0%, 100% { transform: scaleY(0.3); }
        50% { transform: scaleY(1); }
    }
    .animate-sound-bar-1 { animation: sound-pulse-bar 0.9s ease-in-out infinite; transform-origin: bottom; }
    .animate-sound-bar-2 { animation: sound-pulse-bar 1.1s ease-in-out infinite; transform-origin: bottom; }
    .animate-sound-bar-3 { animation: sound-pulse-bar 0.7s ease-in-out infinite; transform-origin: bottom; }
    .animate-sound-bar-4 { animation: sound-pulse-bar 1.3s ease-in-out infinite; transform-origin: bottom; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Data Loader from PHP Blade variables
    const questionsData = [
        @foreach($questions as $qIdx => $qData)
            {
                id: "{{ $qData['question']->MaCauHoi }}",
                audioUrl: "{{ $qData['audio'] }}",
                correctOptionId: "{{ $qData['correct_option'] ? $qData['correct_option']->MaPA : '' }}",
                trueOptionId: "{{ $qData['true_option'] ? $qData['true_option']->MaPA : '' }}",
                falseOptionId: "{{ $qData['false_option'] ? $qData['false_option']->MaPA : '' }}",
                correctValue: "{{ $qData['correct_value'] }}",
                statement: "{!! addslashes($qData['question']->NDCauHoi) !!}"
            },
        @endforeach
    ];

    // Web Audio Synthesizer for Retro Beeps
    let audioCtx = null;
    const initAudioContext = () => {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    };

    const synthBeep = (freq, duration, type = 'sine', decay = true) => {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            
            gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
            if (decay) {
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
            }
            
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch(e) {}
    };

    const playCorrectSound = () => {
        synthBeep(523.25, 0.12, 'sine'); // C5
        setTimeout(() => synthBeep(659.25, 0.12, 'sine'), 70); // E5
        setTimeout(() => synthBeep(783.99, 0.18, 'sine'), 140); // G5
    };

    const playWrongSound = () => {
        synthBeep(180, 0.35, 'sawtooth', false);
    };

    // HTML elements references
    const screenLaunch = document.getElementById('screen-launch');
    const screenWrong = document.getElementById('screen-wrong');
    const screenWin = document.getElementById('screen-win');
    
    const btnStart = document.getElementById('btn-game-start');
    
    const btnSoundPlay = document.getElementById('btn-sound-play');
    const btnSpeedSlow = document.getElementById('btn-speed-slow');
    const btnSpeedNormal = document.getElementById('btn-speed-normal');
    const equalizerContainer = document.getElementById('equalizer-container');

    const progressText = document.getElementById('hud-progress-text');
    const progressBar = document.getElementById('hud-progress-bar');
    const scoreText = document.getElementById('hud-score-text');

    const gameArena = document.getElementById('game-arena');
    const gameTrack = document.getElementById('game-track');
    const character = document.getElementById('player-character');
    const startPlatform = document.getElementById('start-platform');

    // Game variables state
    let activeQuestionIndex = 0;
    let score = 0;
    let isGameRunning = false;
    let isAnimating = false;
    let currentSpeed = 1.0;
    let choicesEnabled = false;
    
    // Core game audio source
    let gameAudio = new Audio();

    // Get absolute position relative to gameArena
    const getRelativeOffset = (element, parent) => {
        let top = 0;
        let left = 0;
        let el = element;
        while (el && el !== parent) {
            top += el.offsetTop;
            left += el.offsetLeft;
            el = el.offsetParent;
        }
        return { top, left };
    };

    // Setup character initially
    const placeCharacterOnStart = () => {
        startPlatform.appendChild(character);
        character.style.transition = 'none';
        character.style.left = 'calc(50% - 30px)';
        character.style.top = '-40px';
        character.style.display = 'block';
        character.offsetHeight; // force repaint
    };

    const playCurrentAudio = () => {
        const currentQ = questionsData[activeQuestionIndex];
        if (currentQ && currentQ.audioUrl) {
            gameAudio.src = currentQ.audioUrl;
            gameAudio.playbackRate = currentSpeed;
            
            // Start equalizer animation
            const bars = equalizerContainer.querySelectorAll('.sound-bar');
            bars.forEach((bar, index) => {
                bar.className = `w-1.5 bg-sky-500 rounded-full sound-bar animate-sound-bar-${index + 1}`;
            });
            btnSoundPlay.querySelector('i').className = 'fas fa-pause text-lg';
            
            gameAudio.play().catch(e => {
                console.log('Audio autoplay blocked or cancelled');
                stopEqualizer();
                btnSoundPlay.querySelector('i').className = 'fas fa-play text-lg ml-0.5';
            });
        }
    };

    const stopEqualizer = () => {
        const bars = equalizerContainer.querySelectorAll('.sound-bar');
        bars.forEach((bar) => {
            bar.className = 'w-1.5 bg-sky-500 rounded-full sound-bar';
        });
    };

    gameAudio.addEventListener('ended', () => {
        stopEqualizer();
        btnSoundPlay.querySelector('i').className = 'fas fa-play text-lg ml-0.5';
    });

    btnSoundPlay.addEventListener('click', () => {
        initAudioContext();
        if (gameAudio.paused) {
            playCurrentAudio();
        } else {
            gameAudio.pause();
            stopEqualizer();
            btnSoundPlay.querySelector('i').className = 'fas fa-play text-lg ml-0.5';
        }
    });

    // Speed Controls
    btnSpeedSlow.addEventListener('click', () => {
        currentSpeed = 0.8;
        gameAudio.playbackRate = currentSpeed;
        btnSpeedSlow.className = 'px-2.5 py-1 text-xs font-black rounded-lg border border-sky-500 text-sky-650 bg-sky-50 transition-all select-none';
        btnSpeedNormal.className = 'px-2.5 py-1 text-xs font-black rounded-lg border border-slate-350 text-slate-500 hover:bg-slate-50 transition-all select-none';
    });

    btnSpeedNormal.addEventListener('click', () => {
        currentSpeed = 1.0;
        gameAudio.playbackRate = currentSpeed;
        btnSpeedNormal.className = 'px-2.5 py-1 text-xs font-black rounded-lg border border-sky-500 text-sky-650 bg-sky-50 transition-all select-none';
        btnSpeedSlow.className = 'px-2.5 py-1 text-xs font-black rounded-lg border border-slate-350 text-slate-500 hover:bg-slate-50 transition-all select-none';
    });

    // Jump Animation Cycle
    const jumpToPlatform = (targetPlatform, isCorrect, onComplete) => {
        isAnimating = true;
        disableChoices();

        // 1. Get current absolute position of character relative to gameArena
        const currentAbsPos = getRelativeOffset(character, gameArena);
        
        // 2. Append character to gameArena so it can jump across parents
        gameArena.appendChild(character);
        character.style.transition = 'none';
        character.style.left = `${currentAbsPos.left}px`;
        character.style.top = `${currentAbsPos.top}px`;
        
        // Force repaint
        character.offsetHeight;
        
        // 3. Get target absolute position of center-top of targetPlatform relative to gameArena
        const targetAbsPos = getRelativeOffset(targetPlatform, gameArena);
        const targetLeft = targetAbsPos.left + (targetPlatform.offsetWidth / 2) - 30;
        const targetTop = targetAbsPos.top - 40; // slightly above platform top
        
        // 4. Start jump animation
        character.style.transition = 'left 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), top 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        const charInner = character.querySelector('.char-inner');
        charInner.classList.remove('char-idle');
        charInner.classList.add('char-jump');
        
        // Move character style coordinates
        character.style.left = `${targetLeft}px`;
        character.style.top = `${targetTop}px`;
        
        setTimeout(() => {
            // Jump complete
            charInner.classList.remove('char-jump');
            charInner.classList.add('char-idle');
            
            if (isCorrect) {
                // Append to target platform so it moves with the track scroll
                targetPlatform.appendChild(character);
                character.style.transition = 'none';
                character.style.left = 'calc(50% - 30px)';
                character.style.top = '-40px';
                
                isAnimating = false;
                if (onComplete) onComplete();
            } else {
                // If wrong, play fall animation
                character.style.transition = 'top 0.5s cubic-bezier(0.55, 0.085, 0.68, 0.53)';
                charInner.classList.remove('char-idle');
                charInner.classList.add('char-fall');
                character.style.top = '720px'; // falls below the 650px arena viewport
                
                setTimeout(() => {
                    isAnimating = false;
                    if (onComplete) onComplete();
                }, 500);
            }
        }, 600);
    };

    // Load active question state
    const loadCurrentQuestion = () => {
        if (activeQuestionIndex >= questionsData.length) {
            endGame(true);
            return;
        }

        // Enable choices
        choicesEnabled = true;

        // Focus and scroll active row
        const activeRow = document.getElementById(`row-${activeQuestionIndex + 1}`);
        if (activeRow) {
            const offset = activeRow.offsetTop - 200;
            gameTrack.style.transform = `translateY(-${Math.max(0, offset)}px)`;

            document.querySelectorAll('.plank-row').forEach(row => {
                row.classList.remove('active-row');
            });
            activeRow.classList.add('active-row');

            // Set opacity of question card
            document.querySelectorAll('.active-row-visible').forEach(card => {
                card.style.opacity = '0.6';
                card.style.transform = 'scale(0.95)';
            });
            const activeCard = document.getElementById(`question-card-${activeQuestionIndex + 1}`);
            if (activeCard) {
                activeCard.style.opacity = '1';
                activeCard.style.transform = 'scale(1)';
            }
        }

        // Update HUD
        progressText.innerText = `Câu ${activeQuestionIndex + 1} / ${questionsData.length}`;
        progressBar.style.width = `${((activeQuestionIndex) / questionsData.length) * 100}%`;

        // Update active question card
        const currentQ = questionsData[activeQuestionIndex];
        const activeCardText = document.getElementById(`question-text-${activeQuestionIndex + 1}`);
        if (activeCardText) {
            activeCardText.innerText = currentQ.statement;
            activeCardText.classList.add('text-indigo-900', 'font-black');
        }

        // Reset audio state
        gameAudio.pause();
        stopEqualizer();
        btnSoundPlay.querySelector('i').className = 'fas fa-play text-lg ml-0.5';

        // Play audio automatically
        playCurrentAudio();
    };

    const enableChoices = () => {
        choicesEnabled = true;
    };

    const disableChoices = () => {
        choicesEnabled = false;
    };

    const spawnSparkles = (platform) => {
        const rect = platform.getBoundingClientRect();
        const arenaRect = gameArena.getBoundingClientRect();
        const left = rect.left - arenaRect.left + (rect.width / 2);
        const top = rect.top - arenaRect.top;
        
        for (let i = 0; i < 15; i++) {
            const particle = document.createElement('div');
            particle.className = 'absolute pointer-events-none z-45 text-amber-400 text-lg animate-ping';
            particle.innerHTML = '✨';
            particle.style.left = `${left + (Math.random() - 0.5) * 160}px`;
            particle.style.top = `${top + (Math.random() - 0.5) * 45}px`;
            
            gameArena.appendChild(particle);
            setTimeout(() => particle.remove(), 800);
        }
    };

    // Choice Handler (TRUE / FALSE click)
    const handleChoice = (choiceStr, clickedPlatform) => {
        if (!isGameRunning || isAnimating || !choicesEnabled) return;

        initAudioContext();
        disableChoices();

        const currentQ = questionsData[activeQuestionIndex];
        const isCorrect = (currentQ.correctValue === choiceStr);

        // Set hidden input value for database validation submit
        const hiddenAns = document.getElementById('ans-' + currentQ.id);
        if (hiddenAns) {
            hiddenAns.value = (choiceStr === 'true') ? currentQ.trueOptionId : currentQ.falseOptionId;
        }

        if (isCorrect) {
            playCorrectSound();
            clickedPlatform.classList.add('correct-glow');
            spawnSparkles(clickedPlatform);

            jumpToPlatform(clickedPlatform, true, () => {
                score += 10;
                scoreText.innerText = score;

                // Move to next question or win
                activeQuestionIndex++;
                if (activeQuestionIndex >= questionsData.length) {
                    setTimeout(() => {
                        endGame(true);
                    }, 500);
                } else {
                    setTimeout(() => {
                        loadCurrentQuestion();
                    }, 800);
                }
            });
        } else {
            playWrongSound();
            clickedPlatform.classList.add('wrong-glow');

            jumpToPlatform(clickedPlatform, false, () => {
                // Show drowned reset screen
                screenWrong.classList.remove('hidden');

                setTimeout(() => {
                    // Hide drowned screen and start over from question 1
                    screenWrong.classList.add('hidden');
                    
                    // Reset platform styles
                    document.querySelectorAll('.platform-btn').forEach(btn => {
                        btn.className = 'platform-btn relative w-[160px] h-[65px] md:w-[220px] md:h-[75px] cursor-pointer transition-transform duration-200';
                    });

                    // Restart game
                    restartGame();
                }, 2200);
            });
        }
    };

    // Event listeners on platforms
    document.querySelectorAll('.platform-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const row = btn.closest('.plank-row');
            const rowNum = parseInt(row.getAttribute('data-row'));

            // Only allow clicking on the active row
            if (rowNum !== activeQuestionIndex + 1) return;

            const choice = btn.getAttribute('data-choice');
            handleChoice(choice, btn);
        });
    });

    const startGame = () => {
        initAudioContext();

        screenLaunch.classList.add('hidden');
        screenWrong.classList.add('hidden');
        screenWin.classList.add('hidden');

        character.style.opacity = '1';
        const charInner = character.querySelector('.char-inner');
        charInner.className = 'char-inner w-full h-full relative char-idle';

        btnSoundPlay.disabled = false;

        activeQuestionIndex = 0;
        score = 0;
        scoreText.innerText = score;
        isGameRunning = true;
        isAnimating = false;

        // Reset active row question cards text
        for (let i = 1; i <= questionsData.length; i++) {
            const cardText = document.getElementById(`question-text-${i}`);
            if (cardText) {
                cardText.innerText = `(Nhấp loa để phát âm thanh câu ${i})`;
                cardText.className = 'text-xs font-bold text-slate-705 leading-normal';
            }
        }

        // Put character at start platform
        placeCharacterOnStart();

        // Load first question
        loadCurrentQuestion();
    };

    const restartGame = () => {
        character.style.opacity = '1';
        const charInner = character.querySelector('.char-inner');
        charInner.className = 'char-inner w-full h-full relative char-idle';

        activeQuestionIndex = 0;
        score = 0;
        scoreText.innerText = score;
        isAnimating = false;

        // Reset active row question cards text
        for (let i = 1; i <= questionsData.length; i++) {
            const cardText = document.getElementById(`question-text-${i}`);
            if (cardText) {
                cardText.innerText = `(Nhấp loa để phát âm thanh câu ${i})`;
                cardText.className = 'text-xs font-bold text-slate-705 leading-normal';
            }
        }

        // Reset vertical track translation
        gameTrack.style.transform = 'translateY(0px)';

        placeCharacterOnStart();
        loadCurrentQuestion();
    };

    const endGame = (isWin) => {
        isGameRunning = false;
        btnSoundPlay.disabled = true;
        gameAudio.pause();
        stopEqualizer();
        btnSoundPlay.querySelector('i').className = 'fas fa-play text-lg ml-0.5';

        disableChoices();

        if (isWin) {
            screenWin.classList.remove('hidden');
            playCorrectSound();
        }
    };

    btnStart.addEventListener('click', startGame);
});
</script>
@endif
