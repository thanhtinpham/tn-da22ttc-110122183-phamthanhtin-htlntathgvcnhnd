@php
    $questions = [];
    foreach($test->phan as $phan) {
        foreach($phan->cauhoi as $question) {
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung');
            $targetText = $correctOption ? preg_replace('/^[A-D]\.\s*/', '', $correctOption->NDPA) : '';
            
            $questions[] = [
                'question' => $question,
                'audio' => $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '',
                'correct_option' => $correctOption,
                'target_text' => trim($targetText),
            ];
        }
    }
@endphp

<div id="game-workspace" class="w-full relative z-10 animate-fade-in text-slate-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full items-stretch">
        
        <!-- LEFT SIDE: Stats & Control Dashboard -->
        <div class="lg:col-span-4 bg-white/70 backdrop-blur-xl border border-white/60 shadow-[0_20px_40px_rgba(99,102,241,0.05)] rounded-[2.5rem] p-6 flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative pastel bg circles -->
            <div class="absolute -right-12 -bottom-12 w-36 h-36 bg-pink-200/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -top-12 w-36 h-36 bg-sky-200/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-6 flex flex-col h-full justify-between relative z-10">
                <!-- Game Header Title -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 border-b border-indigo-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-pink-400 via-purple-500 to-indigo-500 text-white flex items-center justify-center shadow-lg shadow-purple-500/20">
                            <i class="fas fa-keyboard text-sm"></i>
                        </div>
                        <div>
                            <h5 class="font-extrabold text-indigo-950 mb-0 tracking-wide text-xs uppercase">Game Âm Nhạc</h5>
                            <h4 class="font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600 text-base mb-0">GÕ NHANH CHÍNH TẢ</h4>
                        </div>
                    </div>

                    <!-- Lives and Score panel -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-pink-50/60 border border-pink-100/50 rounded-2xl p-4 text-center shadow-[inset_0_2px_4px_rgba(244,63,94,0.02)]">
                            <span class="text-[9px] font-black text-pink-500 uppercase tracking-widest block mb-2">Năng lượng</span>
                            <div class="flex items-center justify-center gap-1.5 h-7" id="hearts-container">
                                <i class="fas fa-heart text-pink-500 animate-pulse text-lg"></i>
                                <i class="fas fa-heart text-pink-500 animate-pulse text-lg"></i>
                                <i class="fas fa-heart text-pink-500 animate-pulse text-lg"></i>
                            </div>
                        </div>
                        <div class="bg-amber-50/60 border border-amber-100/50 rounded-2xl p-4 text-center shadow-[inset_0_2px_4px_rgba(245,158,11,0.02)]">
                            <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest block mb-2">Điểm đạt được</span>
                            <div class="text-lg font-black text-amber-500 h-7 flex items-center justify-center" id="game-score-display">0 / {{ count($questions) * 10 }}</div>
                        </div>
                    </div>

                    <!-- Interactive Guide Bubble (Mascot) -->
                    <div id="ai-helper-card" class="flex items-start gap-4 bg-gradient-to-r from-pink-500/5 via-purple-500/5 to-cyan-500/5 border border-purple-500/10 p-4 rounded-3xl shadow-sm transition-all duration-300">
                        <div class="relative shrink-0">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-pink-400 to-cyan-400 rounded-full opacity-35 blur animate-pulse"></div>
                            <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-12 h-12 object-contain relative z-10 animate-bounce" style="animation-duration: 4s;">
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-600 uppercase tracking-widest leading-none mb-1.5">Trợ lý vui vẻ</div>
                            <p class="text-xs text-slate-600 font-bold leading-relaxed mb-0">Bóng âm thanh đang rơi! Click vào quả bóng để nghe, sau đó gõ thật nhanh từ nghe được trước khi nó chạm vạch cầu vồng nhé!</p>
                        </div>
                    </div>
                </div>

                <!-- Control guide -->
                <div class="space-y-4 pt-5 border-t border-indigo-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Phím tắt & mẹo nhỏ</span>
                    <div class="bg-slate-50/80 border border-slate-100 p-4 rounded-2xl text-xs space-y-2.5 text-slate-600 font-semibold">
                        <div class="flex justify-between items-center">
                            <span>Nghe lại từ:</span>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[9px] font-bold shadow-sm text-indigo-600">Bấm bóng âm thanh</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Nộp đáp án nhanh:</span>
                            <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[9px] font-bold shadow-sm text-indigo-600">Phím Enter</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Meteor Speed Typer Interactive Arena -->
        <div class="lg:col-span-8 bg-white/70 backdrop-blur-xl border border-white/60 shadow-[0_20px_40px_rgba(99,102,241,0.05)] rounded-[2.5rem] p-6 flex flex-col justify-between relative overflow-hidden">
            <!-- Hidden form for saving points and framing details -->
            <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="meteor-typer-form" class="space-y-6 h-full flex flex-col justify-between">
                @csrf
                <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
                <input type="hidden" name="start_time" value="{{ time() }}">
                
                @foreach($questions as $qData)
                    <input type="hidden" name="answers[{{ $qData['question']->MaCauHoi }}]" id="ans-{{ $qData['question']->MaCauHoi }}" value="">
                @endforeach

                <div class="flex-grow flex flex-col justify-between h-full">
                    <!-- Progress Bar HUD -->
                    <div class="flex items-center justify-between border-b border-indigo-50 pb-4 mb-4 select-none">
                        <span class="text-xs font-black text-purple-600 uppercase tracking-wider" id="hud-progress-title">Bắt đầu trò chơi</span>
                        <div class="w-1/2 bg-slate-100 h-3 rounded-full overflow-hidden border border-indigo-50 shadow-inner">
                            <div id="hud-progress-bar" class="bg-gradient-to-r from-pink-400 via-purple-500 to-cyan-400 h-full w-0 transition-all duration-500 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Falling Game Arena -->
                    <div id="game-arena" class="relative w-full h-[480px] bg-gradient-to-b from-sky-100/90 via-purple-50/80 to-pink-100/90 overflow-hidden rounded-[2rem] border-2 border-white shadow-[inset_0_4px_20px_rgba(99,102,241,0.06)] flex flex-col justify-between">
                        <!-- Playful decorative backgrounds -->
                        <div class="absolute top-10 left-10 w-24 h-24 bg-pink-300/20 rounded-full blur-xl pointer-events-none"></div>
                        <div class="absolute bottom-20 right-10 w-32 h-32 bg-cyan-300/20 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute top-1/3 right-1/4 w-16 h-16 bg-yellow-200/20 rounded-full blur-lg pointer-events-none animate-pulse"></div>
                        
                        <!-- Soft dot grid pattern -->
                        <div class="absolute inset-0 bg-grid-dots opacity-30 pointer-events-none"></div>
                        
                        <!-- Falling meteor tracking playground -->
                        <div id="falling-zone" class="absolute inset-0 hidden">
                            <!-- Meteor block -->
                            <div id="meteor-node" class="absolute flex flex-col items-center justify-center cursor-pointer select-none">
                                <div class="meteor-core relative flex items-center justify-center">
                                    <div class="pulse-ring"></div>
                                    <i class="fas fa-volume-up text-white text-lg relative z-10 drop-shadow"></i>
                                </div>
                                <span class="text-[9px] font-black text-purple-750 bg-white/95 px-2.5 py-1 rounded-full border border-purple-100 shadow-sm uppercase tracking-widest mt-2 hover:scale-105 active:scale-95 transition-transform">Bấm để nghe</span>
                            </div>
                        </div>

                        <!-- Rainbow Energy Shield barrier line at bottom -->
                        <div id="energy-shield" class="absolute bottom-[90px] inset-x-0 h-4 bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-400 border-t border-white shadow-[0_0_20px_rgba(167,139,250,0.5)] z-20 hidden transition-all duration-300"></div>

                        <!-- Bottom input control center -->
                        <div id="control-dock" class="absolute bottom-0 inset-x-0 h-[90px] bg-white/80 backdrop-blur-md border-t border-indigo-50 p-4 flex gap-3 items-center z-30 select-none rounded-b-[2rem] shadow-sm hidden">
                            <input type="text" 
                                   id="word-type-input" 
                                   class="flex-grow bg-slate-50/80 border-2 border-indigo-100 focus:border-purple-400 focus:bg-white text-indigo-950 font-extrabold text-sm px-5 py-3 rounded-xl outline-none shadow-sm transition-all uppercase tracking-widest placeholder:text-slate-400"
                                   placeholder="Gõ từ nghe được..." 
                                   autocomplete="off"
                                   disabled>
                            <button type="button" 
                                    id="btn-submit-word"
                                    class="h-[46px] px-6 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 hover:from-pink-600 hover:to-indigo-600 text-white font-black text-xs tracking-wider rounded-xl cursor-pointer border-0 shadow-lg shadow-purple-500/20 hover:scale-105 active:scale-95 transition-all">
                                NỘP BÀI
                            </button>
                        </div>

                        <!-- SCREEN 1: Game Launch Screen -->
                        <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-white/75 backdrop-blur-md rounded-[2rem]">
                            <div class="relative">
                                <div class="absolute -inset-2 bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400 rounded-3xl opacity-40 blur-lg animate-pulse"></div>
                                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-pink-400 via-purple-500 to-cyan-400 flex items-center justify-center text-white text-3xl shadow-lg relative z-10 animate-bounce" style="animation-duration: 3s;">
                                    <i class="fas fa-meteor"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <h4 class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 via-purple-600 to-cyan-600 uppercase tracking-wider">BÓNG ÂM THANH KỲ DIỆU</h4>
                                <p class="text-slate-500 text-xs max-w-sm mt-2 font-bold leading-relaxed">Luyện tai nhanh nhạy, gõ chữ chính xác! Click vào bong bóng âm thanh đang rơi, gõ lại đúng từ nghe được trước khi bóng chạm đất.</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-start"
                                    class="px-8 py-4 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 hover:from-pink-600 hover:to-indigo-600 text-white font-black text-sm tracking-wider rounded-2xl shadow-lg shadow-purple-500/25 active:translate-y-[2px] transition-all hover:scale-105 cursor-pointer border-0">
                                BẮT ĐẦU CHƠI
                            </button>
                        </div>

                        <!-- SCREEN 2: Game Over Screen -->
                        <div id="screen-gameover" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-white/85 backdrop-blur-md rounded-[2rem] hidden">
                            <div class="w-16 h-16 rounded-full bg-red-50 border-2 border-red-400 text-red-500 flex items-center justify-center text-2xl shadow-md">
                                <i class="fas fa-heart-broken animate-bounce"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-lg font-black text-red-500 uppercase tracking-wider">HẾT LƯỢT CHƠI</h4>
                                <p class="text-slate-500 text-xs max-w-xs mt-2 font-bold leading-relaxed">Đừng nản lòng nhé! Luyện tập gõ nhanh hơn nữa và thử sức lại ngay nào!</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-replay"
                                    class="px-6 py-3 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white font-extrabold text-xs tracking-wider rounded-xl active:translate-y-[2px] transition-all hover:scale-105 cursor-pointer border-0 shadow-md">
                                <i class="fas fa-redo mr-1"></i> CHƠI LẠI
                            </button>
                        </div>

                        <!-- SCREEN 3: Level Completed Screen -->
                        <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-white/85 backdrop-blur-md rounded-[2rem] hidden">
                            <div class="relative">
                                <div class="absolute -inset-3 bg-gradient-to-r from-amber-400 to-yellow-300 rounded-full opacity-30 blur-lg animate-pulse"></div>
                                <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-yellow-400 to-amber-500 flex items-center justify-center text-white text-3xl shadow-xl relative z-10">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-teal-600 to-cyan-600 uppercase tracking-wide">CHIẾN THẮNG RỰC RỠ!</h4>
                                <p class="text-slate-500 text-xs max-w-sm mt-2 font-bold leading-relaxed">Bạn thật xuất sắc! Hãy lưu lại điểm số để nhận thưởng và mở khóa các chặng tiếp theo.</p>
                            </div>
                            <button type="submit" 
                                    class="group relative flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-sm tracking-wider px-8 py-4 rounded-2xl shadow-lg hover:scale-105 active:scale-95 transition-all duration-300 border-0 cursor-pointer"
                                    id="btn-submit-test">
                                <i class="fas fa-crown text-amber-200 group-hover:animate-bounce"></i>
                                HOÀN THÀNH CỬA ẢI
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .bg-grid-dots {
        background-size: 20px 20px;
        background-image: radial-gradient(circle, rgba(99, 102, 241, 0.15) 1px, transparent 1px);
    }

    /* Falling Meteor node styling */
    #meteor-node {
        width: 120px;
        height: 120px;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        will-change: top, transform;
    }

    .meteor-core {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B9B 0%, #A78BFA 50%, #38BDF8 100%);
        box-shadow: 0 12px 30px rgba(167, 139, 250, 0.4), inset 2px 2px 8px rgba(255, 255, 255, 0.6);
        border: 3px solid #ffffff;
        transition: transform 0.15s ease;
    }

    .meteor-core:active {
        transform: scale(0.9);
    }

    /* Audio pulsing ring decoration */
    .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid rgba(167, 139, 250, 0.6);
        animation: pulse-ring-expand 1.8s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        pointer-events: none;
    }

    @keyframes pulse-ring-expand {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    /* Wrong input shaking animation */
    .input-shake {
        animation: shake 0.3s ease-in-out;
        border-color: #ef4444 !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.3) !important;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    /* Explosion sparks particles */
    .meteor-particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
    }

    /* Sound active indicator bar styling */
    .sound-bar {
        transition: height 0.15s ease-in-out;
    }
    .sound-pulse .bar-1 { animation: sound-bar-grow 1s ease-in-out infinite; transform-origin: bottom; }
    .sound-pulse .bar-2 { animation: sound-bar-grow 1.2s ease-in-out infinite; transform-origin: bottom; }
    .sound-pulse .bar-3 { animation: sound-bar-grow 0.8s ease-in-out infinite; transform-origin: bottom; }
    .sound-pulse .bar-4 { animation: sound-bar-grow 1.4s ease-in-out infinite; transform-origin: bottom; }

    @keyframes sound-bar-grow {
        0%, 100% { transform: scaleY(0.3); }
        50% { transform: scaleY(1); }
    }
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
                targetWord: "{{ addslashes($qData['target_text']) }}",
                cleanedWord: "{{ strtolower(trim(preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()?]/', '', $qData['target_text']))) }}"
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
            
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            if (decay) {
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
            }
            
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch(e) {
            console.error('Synth beep error', e);
        }
    };

    const playCorrectSound = () => {
        synthBeep(523.25, 0.15, 'sine'); // C5
        setTimeout(() => synthBeep(659.25, 0.25, 'sine'), 100); // E5
    };

    const playWrongSound = () => {
        synthBeep(150, 0.35, 'sawtooth', false);
    };

    const playLaserSound = () => {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(800, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(150, audioCtx.currentTime + 0.25);
            gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.25);
        } catch(e) {}
    };

    // Text to speech fallback pronouncer
    const speakWordFallback = (text) => {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        }
    };

    // HTML elements references
    const screenLaunch = document.getElementById('screen-launch');
    const screenGameOver = document.getElementById('screen-gameover');
    const screenWin = document.getElementById('screen-win');
    const fallingZone = document.getElementById('falling-zone');
    const energyShield = document.getElementById('energy-shield');
    const controlDock = document.getElementById('control-dock');
    const inputField = document.getElementById('word-type-input');
    const btnSubmitWord = document.getElementById('btn-submit-word');
    const btnStart = document.getElementById('btn-game-start');
    const btnReplay = document.getElementById('btn-game-replay');
    const heartsContainer = document.getElementById('hearts-container');
    const gameScoreDisplay = document.getElementById('game-score-display');
    const hudProgressTitle = document.getElementById('hud-progress-title');
    const hudProgressBar = document.getElementById('hud-progress-bar');
    const gameArena = document.getElementById('game-arena');
    const meteorNode = document.getElementById('meteor-node');

    // Game state parameters
    let activeQuestionIndex = 0;
    let score = 0;
    let lives = 3;
    let isGameRunning = false;
    let animationFrameId = null;
    let targetWordAudio = new Audio();
    
    // Meteor falling tracking variables
    let meteorY = -120;
    let baseSpeed = 0.6;
    let currentSpeed = 0.6;
    let maxDistance = 300; // Falling zone height minus shield line
    let audioPanelIndicator = document.getElementById('ai-helper-card') || document.querySelector('.bg-gradient-to-r.from-rose-500\\/5');

    // Play target audio with SpeechSynthesis fallback
    const playCurrentAudio = () => {
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;

        if (audioPanelIndicator) audioPanelIndicator.classList.add('sound-pulse');

        if (currentQ.audioUrl && currentQ.audioUrl !== '') {
            targetWordAudio.src = currentQ.audioUrl;
            targetWordAudio.play().catch(e => {
                console.log('Audio autoplay blocked, falling back to TTS');
                speakWordFallback(currentQ.targetWord);
            });
        } else {
            speakWordFallback(currentQ.targetWord);
        }
    };

    targetWordAudio.addEventListener('ended', () => {
        if (audioPanelIndicator) audioPanelIndicator.classList.remove('sound-pulse');
    });

    // Handle clicking the meteor directly to listen again
    meteorNode.addEventListener('click', () => {
        initAudioContext();
        playLaserSound();
        setTimeout(playCurrentAudio, 150);
    });

    // Render Hearts UI
    const renderHearts = () => {
        let heartsHtml = '';
        for (let i = 0; i < 3; i++) {
            if (i < lives) {
                heartsHtml += '<i class="fas fa-heart text-pink-500 animate-pulse text-lg"></i>';
            } else {
                heartsHtml += '<i class="far fa-heart text-slate-300 text-lg"></i>';
            }
        }
        heartsContainer.innerHTML = heartsHtml;
    };

    // Update HUD display
    const updateHUD = () => {
        if (!isGameRunning) return;
        const total = questionsData.length;
        hudProgressTitle.innerText = `CÂU HỎI ${activeQuestionIndex + 1} / ${total}`;
        const pct = ((activeQuestionIndex) / total) * 100;
        hudProgressBar.style.width = `${pct}%`;
        gameScoreDisplay.innerText = `${score} / ${total * 10}`;
    };

    // Particles explosion animation
    const createMeteorExplosion = (x, y) => {
        const particlesCount = 20;
        const colors = ['#FF6B9B', '#A78BFA', '#38BDF8', '#34D399', '#FBBF24', '#F472B6'];
        const arenaRect = gameArena.getBoundingClientRect();
        
        for (let i = 0; i < particlesCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'meteor-particle';
            
            const size = 3 + Math.random() * 7;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            particle.style.boxShadow = '0 0 10px ' + particle.style.backgroundColor;
            
            // Absolute positioning inside arena
            particle.style.left = `${x}px`;
            particle.style.top = `${y}px`;
            gameArena.appendChild(particle);

            const angle = Math.random() * Math.PI * 2;
            const velocity = 3 + Math.random() * 7;
            const dx = Math.cos(angle) * velocity;
            const dy = Math.sin(angle) * velocity;
            
            let alpha = 1;
            let currentX = x;
            let currentY = y;

            const updateParticle = () => {
                currentX += dx;
                currentY += dy;
                alpha -= 0.035;
                particle.style.transform = `translate(${currentX - x}px, ${currentY - y}px) scale(${alpha})`;
                particle.style.opacity = alpha;

                if (alpha > 0) {
                    requestAnimationFrame(updateParticle);
                } else {
                    particle.remove();
                }
            };
            requestAnimationFrame(updateParticle);
        }
    };

    // Reset meteor to the top
    const resetMeteor = () => {
        meteorY = -100;
        meteorNode.style.top = `${meteorY}px`;
        
        // Random horizontal position (20% to 80% width)
        const randX = 20 + Math.random() * 60;
        meteorNode.style.left = `${randX}%`;
        
        // Adjust speed based on progress
        currentSpeed = baseSpeed + (activeQuestionIndex * 0.08);
        
        // Clear old input value and focus
        inputField.value = '';
        inputField.focus();
    };

    // Submit and check typed word
    const checkWordInput = () => {
        if (!isGameRunning) return;
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;

        const typed = inputField.value.trim().toLowerCase().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?]/g,"");
        
        if (typed === currentQ.cleanedWord) {
            // Write answer choice to hidden field
            const hiddenInp = document.getElementById('ans-' + currentQ.id);
            if (hiddenInp) {
                hiddenInp.value = currentQ.correctOptionId;
            }

            // Burst explosion at meteor coordinates
            const mRect = meteorNode.getBoundingClientRect();
            const aRect = gameArena.getBoundingClientRect();
            const burstX = mRect.left - aRect.left + mRect.width / 2;
            const burstY = mRect.top - aRect.top + mRect.height / 2;

            playCorrectSound();
            createMeteorExplosion(burstX, burstY);

            score += 10;
            activeQuestionIndex++;
            updateHUD();

            if (activeQuestionIndex >= questionsData.length) {
                endGame(true);
            } else {
                gameArena.classList.add('bg-emerald-950/20');
                setTimeout(() => gameArena.classList.remove('bg-emerald-950/20'), 300);

                resetMeteor();
                setTimeout(playCurrentAudio, 400);
            }
        } else {
            // Shake input bar in red to notify error
            playWrongSound();
            inputField.classList.add('input-shake');
            setTimeout(() => {
                inputField.classList.remove('input-shake');
            }, 300);
        }
    };

    // Check action events
    btnSubmitWord.addEventListener('click', checkWordInput);
    inputField.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            checkWordInput();
        }
    });

    // Main ticker game loop
    const gameLoop = () => {
        if (!isGameRunning) return;

        // Retrieve current boundaries
        const arenaHeight = gameArena.clientHeight;
        const shieldLineY = arenaHeight - 110; // Position of energy-shield line

        meteorY += currentSpeed;
        meteorNode.style.top = `${meteorY}px`;

        // Check if meteor hits shield line
        if (meteorY >= shieldLineY - 80) {
            // Alarm hit!
            playWrongSound();
            
            // Flash shield in bright red
            energyShield.classList.add('bg-rose-500/80', 'border-rose-400');
            setTimeout(() => {
                energyShield.classList.remove('bg-rose-500/80', 'border-rose-400');
            }, 300);

            // Explosive particles at shield contact
            const mRect = meteorNode.getBoundingClientRect();
            const aRect = gameArena.getBoundingClientRect();
            const burstX = mRect.left - aRect.left + mRect.width / 2;
            const burstY = shieldLineY;
            createMeteorExplosion(burstX, burstY);

            lives--;
            renderHearts();

            if (lives <= 0) {
                endGame(false);
            } else {
                // Respawn meteor from top
                resetMeteor();
                setTimeout(playCurrentAudio, 300);
            }
        }

        if (isGameRunning) {
            animationFrameId = requestAnimationFrame(gameLoop);
        }
    };

    // Start Game
    const startGame = () => {
        initAudioContext();
        screenLaunch.classList.add('hidden');
        screenGameOver.classList.add('hidden');
        screenWin.classList.add('hidden');
        
        fallingZone.classList.remove('hidden');
        energyShield.classList.remove('hidden');
        controlDock.classList.remove('hidden');
        inputField.disabled = false;

        activeQuestionIndex = 0;
        score = 0;
        lives = 3;
        isGameRunning = true;

        renderHearts();
        updateHUD();
        resetMeteor();
        playLaserSound();
        setTimeout(playCurrentAudio, 300);

        // Start game tick
        animationFrameId = requestAnimationFrame(gameLoop);
    };

    // End Game
    const endGame = (isWin) => {
        isGameRunning = false;
        cancelAnimationFrame(animationFrameId);
        inputField.disabled = true;
        targetWordAudio.pause();
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        if (audioPanelIndicator) audioPanelIndicator.classList.remove('sound-pulse');

        fallingZone.classList.add('hidden');
        energyShield.classList.add('hidden');
        controlDock.classList.add('hidden');

        if (isWin) {
            hudProgressBar.style.width = '100%';
            hudProgressTitle.innerText = 'NHIỆM VỤ HOÀN THÀNH';
            screenWin.classList.remove('hidden');
            playCorrectSound();
        } else {
            screenGameOver.classList.remove('hidden');
        }
    };

    btnStart.addEventListener('click', startGame);
    btnReplay.addEventListener('click', startGame);
});
</script>
