@php
    $questions = [];
    foreach($test->phan as $phan) {
        foreach($phan->cauhoi as $question) {
            if ($question->phuongancauhoi->isEmpty()) {
                continue;
            }
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->firstWhere('DapAn', 1) ?? $question->phuongancauhoi->first();
            $targetText = $correctOption ? preg_replace('/^[A-D]\.\s*/', '', $correctOption->NDPA) : '';
            
            $questions[] = [
                'question' => $question,
                'audio' => $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '',
                'correct_option' => $correctOption,
                'target_text' => trim($targetText),
                'options' => $question->phuongancauhoi,
            ];
        }
    }
@endphp

<div id="game-workspace" class="w-full relative z-10 animate-fade-in text-slate-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full items-stretch">
        
        <!-- LEFT SIDE: Stats & Control Dashboard -->
        <div class="lg:col-span-4 bg-white/90 backdrop-blur border border-indigo-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Background glow -->
            <div class="absolute -right-12 -bottom-12 w-36 h-36 bg-emerald-200/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-6 flex flex-col h-full justify-between relative z-10">
                <!-- Game Header Title -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 border-b border-indigo-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-md shadow-emerald-500/10">
                            <i class="fas fa-apple-alt text-xs"></i>
                        </div>
                        <h5 class="font-extrabold text-indigo-950 mb-0 tracking-wide text-sm uppercase">Bẫy từ phát âm</h5>
                    </div>

                    <!-- Lives and Score panel -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-emerald-50/50 border border-emerald-100/50 rounded-2xl p-4 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Giáp năng lượng</span>
                            <div class="flex items-center justify-center gap-1.5 h-7" id="hearts-container">
                                <i class="fas fa-heart text-rose-500 animate-pulse"></i>
                                <i class="fas fa-heart text-rose-500 animate-pulse"></i>
                                <i class="fas fa-heart text-rose-500 animate-pulse"></i>
                            </div>
                        </div>
                        <div class="bg-emerald-50/50 border border-emerald-100/50 rounded-2xl p-4 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Điểm hiện tại</span>
                            <div class="text-xl font-black text-amber-500" id="game-score-display">0 / {{ count($questions) * 10 }}</div>
                        </div>
                    </div>

                    <!-- Interactive Guide Bubble -->
                    <div class="flex items-start gap-3 bg-gradient-to-r from-emerald-500/5 to-teal-500/5 border border-emerald-500/10 p-4 rounded-2xl">
                        <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-12 h-12 object-contain shrink-0 animate-bounce" style="animation-duration: 5s;">
                        <div>
                            <div class="text-[9px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">AI Hướng Dẫn Viên</div>
                            <p class="text-xs text-slate-500 font-bold leading-relaxed mb-0">Lắng nghe kỹ phát âm âm thanh, sau đó click thật nhanh vào quả táo ghi từ chính xác đang rơi trước khi nó chạm đất!</p>
                        </div>
                    </div>
                </div>

                <!-- Sound Replayer Transmitter -->
                <div class="space-y-4 pt-6 border-t border-indigo-100/50 mt-6">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">Máy phát âm thanh</span>
                    <div class="bg-gradient-to-r from-emerald-500/5 to-teal-500/5 border border-emerald-100/60 p-5 rounded-2xl flex flex-col items-center gap-4 text-center shadow-inner relative overflow-hidden">
                        <div class="flex items-end gap-0.5 h-6 w-8 justify-center mb-1">
                            <span class="w-1 bg-emerald-500 rounded-full sound-bar bar-1" style="height: 30%"></span>
                            <span class="w-1 bg-emerald-400 rounded-full sound-bar bar-2" style="height: 60%"></span>
                            <span class="w-1 bg-teal-500 rounded-full sound-bar bar-3" style="height: 40%"></span>
                            <span class="w-1 bg-teal-400 rounded-full sound-bar bar-4" style="height: 20%"></span>
                        </div>
                        <button type="button" 
                                id="btn-sound-play"
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-all cursor-pointer border border-emerald-400/20 disabled:opacity-50"
                                disabled>
                            <i class="fas fa-volume-up text-2xl"></i>
                        </button>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Click để nghe lại từ phát âm</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Falling Apple Arena -->
        <div class="lg:col-span-8 bg-white/90 backdrop-blur border border-indigo-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Hidden form for saving points and framing details -->
            <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="apple-catch-form" class="space-y-6 h-full flex flex-col justify-between">
                @csrf
                <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
                <input type="hidden" name="start_time" value="{{ time() }}">
                
                @foreach($questions as $qData)
                    <input type="hidden" name="answers[{{ $qData['question']->MaCauHoi }}]" id="ans-{{ $qData['question']->MaCauHoi }}" value="">
                @endforeach

                <div class="flex-grow flex flex-col justify-between h-full">
                    <!-- Progress Bar HUD -->
                    <div class="flex items-center justify-between border-b border-indigo-100 pb-3 mb-4 select-none">
                        <span class="text-xs font-black text-emerald-600 uppercase tracking-wider" id="hud-progress-title">Bắt đầu trò chơi</span>
                        <div class="w-1/3 bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200">
                            <div id="hud-progress-bar" class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full w-0 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Falling Game Arena -->
                    <div id="game-arena" class="relative w-full h-[480px] overflow-hidden rounded-2xl border border-emerald-500/20 shadow-inner flex flex-col justify-between">
                        <!-- Space grid overlays -->
                        <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none"></div>
                        
                        <!-- Falling tracking playground -->
                        <div id="falling-zone" class="absolute inset-0 hidden">
                            <!-- Apple nodes will be appended dynamically -->
                        </div>

                        <!-- Energy Shield barrier line at bottom -->
                        <div id="energy-shield" class="absolute bottom-[20px] inset-x-0 h-3 bg-gradient-to-t from-emerald-500/20 to-transparent border-t-2 border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.4)] z-20 hidden"></div>

                        <!-- SCREEN 1: Game Launch Screen -->
                        <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-slate-950/90 rounded-2xl">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-500 flex items-center justify-center text-white text-3xl shadow-lg shadow-emerald-500/30">
                                <i class="fas fa-apple-alt animate-bounce" style="animation-duration: 3s;"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 uppercase tracking-wide">MINIMAL PAIR CATCH</h4>
                                <p class="text-slate-400 text-sm max-w-sm mt-2 leading-relaxed">Lắng nghe thật kỹ từ phát âm và nhanh chóng click chọn quả táo có từ trùng khớp trước khi nó chạm đất!</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-start"
                                    class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-base tracking-wider rounded-2xl shadow-lg shadow-emerald-500/20 active:translate-y-[2px] transition-all cursor-pointer border border-emerald-400/20">
                                BẮT ĐẦU THỬ THÁCH
                            </button>
                        </div>

                        <!-- SCREEN 2: Game Over Screen -->
                        <div id="screen-gameover" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-slate-950/90 rounded-2xl hidden">
                            <div class="w-16 h-16 rounded-full bg-red-950 border border-red-500 text-red-500 flex items-center justify-center text-2xl shadow-lg shadow-red-500/25">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-red-500 uppercase tracking-wider">LÁ CHẮN SỤP ĐỔ</h4>
                                <p class="text-slate-400 text-xs max-w-xs mt-2 leading-relaxed">Bạn đã để quả táo đáp án chạm đất hoặc chọn sai từ quá nhiều lần. Hãy nghe lại kỹ hơn và thử sức lại nhé!</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-replay"
                                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-extrabold text-sm tracking-wider rounded-xl active:translate-y-[2px] transition-all cursor-pointer border border-red-500/20 shadow-md shadow-red-600/10">
                                <i class="fas fa-redo mr-1"></i> THỬ LẠI CỬA ẢI
                            </button>
                        </div>

                        <!-- SCREEN 3: Level Completed Screen -->
                        <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-slate-950/90 rounded-2xl hidden">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white text-3xl shadow-xl shadow-emerald-500/30">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-emerald-400 uppercase tracking-wide">BẢO VỆ THÀNH CÔNG!</h4>
                                <p class="text-slate-400 text-sm max-w-sm mt-2 leading-relaxed">Tuyệt vời! Bạn đã vượt qua tất cả các bẫy phát âm gần giống một cách xuất sắc!</p>
                            </div>
                            <button type="submit" 
                                    class="group relative flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-base tracking-wider px-8 py-4 rounded-2xl shadow-lg shadow-emerald-500/25 hover:scale-105 active:scale-95 transition-all duration-300 border border-emerald-400/20"
                                    id="btn-submit-test">
                                <i class="fas fa-crown text-amber-200 group-hover:animate-bounce"></i>
                                HOÀN THÀNH THỬ THÁCH
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    #game-arena {
        background-image: url('{{ asset('images/map5_bg.png') }}');
        background-size: cover;
        background-position: center;
    }

    .bg-grid-pattern {
        background-size: 30px 30px;
        background-image: linear-gradient(to right, rgba(16, 185, 129, 0.08) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(16, 185, 129, 0.08) 1px, transparent 1px);
    }

    /* Apple container */
    .apple-node {
        position: absolute;
        width: 80px;
        height: 80px;
        cursor: pointer;
        user-select: none;
        will-change: transform, top, left;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: transform 0.1s;
    }

    .apple-node:active {
        transform: scale(0.92);
    }

    /* Apple Body styling with glossy gradients */
    .apple-body {
        position: relative;
        width: 60px;
        height: 55px;
        background: radial-gradient(circle at 35% 35%, #ff4d4d 0%, #cc0000 70%, #800000 100%);
        border-radius: 50% 50% 50% 50% / 40% 40% 60% 60%;
        box-shadow: 0 8px 16px rgba(204, 0, 0, 0.4), inset 3px 3px 6px rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ff9999;
    }

    /* Green apple alternate color theme */
    .apple-node.green-apple .apple-body {
        background: radial-gradient(circle at 35% 35%, #34d399 0%, #059669 70%, #064e3b 100%);
        box-shadow: 0 8px 16px rgba(5, 150, 105, 0.4), inset 3px 3px 6px rgba(255, 255, 255, 0.4);
        border: 1.5px solid #a7f3d0;
    }

    /* Cute leaf */
    .apple-leaf {
        position: absolute;
        top: 2px;
        left: 48px;
        width: 14px;
        height: 8px;
        background: linear-gradient(135deg, #10b981, #047857);
        border-radius: 100% 0 100% 0;
        transform: rotate(-10deg);
        box-shadow: 0 1px 2px rgba(0,0,0,0.15);
        z-index: 5;
    }

    /* Cute stem */
    .apple-stem {
        position: absolute;
        top: 0px;
        left: 38px;
        width: 4px;
        height: 12px;
        background: #5c2d18;
        border-radius: 2px;
        transform: rotate(5deg);
        z-index: 4;
    }

    .apple-text {
        color: #ffffff;
        font-weight: 850;
        font-size: 0.72rem;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0,0,0,0.7);
        padding: 0 4px;
        word-break: break-all;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Wrong input/action shaking */
    .apple-shake {
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    /* Explosion particles */
    .apple-particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
    }

    /* Sound active indicator waves */
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
                options: [
                    @foreach($qData['options'] as $oIdx => $option)
                        {
                            id: "{{ $option->MaPA }}",
                            text: "{{ addslashes(trim(preg_replace('/^[A-D]\.\s*/', '', $option->NDPA))) }}",
                            isCorrect: {{ ($option->MaPA === ($qData['correct_option'] ? $qData['correct_option']->MaPA : '')) ? 'true' : 'false' }}
                        },
                    @endforeach
                ]
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
    const btnStart = document.getElementById('btn-game-start');
    const btnReplay = document.getElementById('btn-game-replay');
    const btnSoundPlay = document.getElementById('btn-sound-play');
    const heartsContainer = document.getElementById('hearts-container');
    const gameScoreDisplay = document.getElementById('game-score-display');
    const hudProgressTitle = document.getElementById('hud-progress-title');
    const hudProgressBar = document.getElementById('hud-progress-bar');
    const gameArena = document.getElementById('game-arena');

    // Game state parameters
    let activeQuestionIndex = 0;
    let score = 0;
    let lives = 3;
    let isGameRunning = false;
    let animationFrameId = null;
    let targetWordAudio = new Audio();
    
    // Apples falling tracking variables
    let activeApples = [];
    let baseSpeed = 0.8;
    let audioPanelIndicator = document.querySelector('.bg-gradient-to-r.from-emerald-505');
    if (!audioPanelIndicator) {
        audioPanelIndicator = document.querySelector('.bg-gradient-to-r.from-emerald-500\\/5');
    }

    // Play target audio
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

    btnSoundPlay.addEventListener('click', () => {
        initAudioContext();
        playLaserSound();
        setTimeout(playCurrentAudio, 150);
    });

    // Render Hearts UI
    const renderHearts = () => {
        let heartsHtml = '';
        for (let i = 0; i < 3; i++) {
            if (i < lives) {
                heartsHtml += '<i class="fas fa-heart text-rose-500 animate-pulse text-lg"></i>';
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

    // Explosion particles
    const createAppleExplosion = (x, y, baseColor = '#ef4444') => {
        const particlesCount = 20;
        const colors = [baseColor, '#fbbf24', '#ffffff', '#f59e0b'];
        
        for (let i = 0; i < particlesCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'apple-particle';
            particle.style.left = `${x}px`;
            particle.style.top = `${y}px`;
            
            const size = 3 + Math.random() * 7;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            particle.style.boxShadow = '0 0 10px ' + particle.style.backgroundColor;
            
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

    // Spawn apples at top
    const spawnApples = () => {
        fallingZone.innerHTML = '';
        activeApples = [];
        
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;
        
        const arenaWidth = gameArena.clientWidth;
        const optionCount = currentQ.options.length;
        
        currentQ.options.forEach((opt, idx) => {
            const appleEl = document.createElement('div');
            const colorClass = idx % 2 === 0 ? 'red-apple' : 'green-apple';
            appleEl.className = `apple-node ${colorClass}`;
            
            appleEl.innerHTML = `
                <div class="apple-stem"></div>
                <div class="apple-leaf"></div>
                <div class="apple-body">
                    <span class="apple-text">${opt.text}</span>
                </div>
            `;
            
            // Safe spacing X coordinates
            const laneWidth = arenaWidth / (optionCount + 1);
            const posX = laneWidth * (idx + 1) - 40; 
            appleEl.style.left = `${posX}px`;
            
            const startY = -80 - (Math.random() * 60);
            appleEl.style.top = `${startY}px`;
            
            fallingZone.appendChild(appleEl);
            
            appleEl.addEventListener('click', () => {
                handleAppleClick(opt, appleEl);
            });
            
            // Add slight speed randomness to create challenge
            const speed = baseSpeed + (activeQuestionIndex * 0.12) + (Math.random() * 0.3);
            
            activeApples.push({
                element: appleEl,
                id: opt.id,
                isCorrect: opt.isCorrect,
                y: startY,
                speed: speed
            });
        });
    };

    // Handle clicking apple
    const handleAppleClick = (option, appleEl) => {
        if (!isGameRunning) return;
        
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;
        
        initAudioContext();
        
        const rect = appleEl.getBoundingClientRect();
        const arenaRect = gameArena.getBoundingClientRect();
        const clickX = rect.left - arenaRect.left + rect.width / 2;
        const clickY = rect.top - arenaRect.top + rect.height / 2;
        
        if (option.isCorrect) {
            const hiddenInp = document.getElementById('ans-' + currentQ.id);
            if (hiddenInp) {
                hiddenInp.value = option.id;
            }
            
            playCorrectSound();
            createAppleExplosion(clickX, clickY, '#10b981');
            
            score += 10;
            activeQuestionIndex++;
            updateHUD();
            
            activeApples.forEach(apple => apple.element.remove());
            activeApples = [];
            
            if (activeQuestionIndex >= questionsData.length) {
                endGame(true);
            } else {
                gameArena.classList.add('bg-emerald-950/20');
                setTimeout(() => gameArena.classList.remove('bg-emerald-950/20'), 300);
                
                setTimeout(() => {
                    spawnApples();
                    playCurrentAudio();
                }, 800);
            }
        } else {
            // Incorrect apple click
            playWrongSound();
            createAppleExplosion(clickX, clickY, '#ef4444');
            
            appleEl.classList.add('apple-shake');
            setTimeout(() => {
                appleEl.classList.remove('apple-shake');
                appleEl.remove();
            }, 300);
            
            activeApples = activeApples.filter(apple => apple.id !== option.id);
            
            lives--;
            renderHearts();
            
            if (lives <= 0) {
                endGame(false);
            }
        }
    };

    // Game loop ticks
    const gameLoop = () => {
        if (!isGameRunning) return;
        
        const arenaHeight = gameArena.clientHeight;
        const shieldLineY = arenaHeight - 20; 
        
        let correctAppleHitBottom = false;
        
        activeApples.forEach((apple) => {
            apple.y += apple.speed;
            apple.element.style.top = `${apple.y}px`;
            
            if (apple.y >= shieldLineY - 70) {
                if (apple.isCorrect) {
                    correctAppleHitBottom = true;
                } else {
                    // Incorrect apple hit bottom: disappear & burst
                    const rect = apple.element.getBoundingClientRect();
                    const arenaRect = gameArena.getBoundingClientRect();
                    const burstX = rect.left - arenaRect.left + rect.width / 2;
                    createAppleExplosion(burstX, shieldLineY - 10, '#ef4444');
                    apple.element.remove();
                }
            }
        });
        
        activeApples = activeApples.filter(apple => apple.y < shieldLineY - 70);
        
        if (correctAppleHitBottom) {
            playWrongSound();
            energyShield.classList.add('bg-rose-500/80', 'border-rose-400');
            setTimeout(() => {
                energyShield.classList.remove('bg-rose-500/80', 'border-rose-400');
            }, 300);
            
            createAppleExplosion(gameArena.clientWidth / 2, shieldLineY - 10, '#ef4444');
            
            lives--;
            renderHearts();
            
            activeApples.forEach(apple => apple.element.remove());
            activeApples = [];
            
            if (lives <= 0) {
                endGame(false);
            } else {
                spawnApples();
                setTimeout(playCurrentAudio, 300);
            }
        }
        
        if (isGameRunning) {
            animationFrameId = requestAnimationFrame(gameLoop);
        }
    };

    // Start game trigger
    const startGame = () => {
        initAudioContext();
        screenLaunch.classList.add('hidden');
        screenGameOver.classList.add('hidden');
        screenWin.classList.add('hidden');
        
        fallingZone.classList.remove('hidden');
        energyShield.classList.remove('hidden');
        btnSoundPlay.disabled = false;

        activeQuestionIndex = 0;
        score = 0;
        lives = 3;
        isGameRunning = true;

        renderHearts();
        updateHUD();
        spawnApples();
        playLaserSound();
        setTimeout(playCurrentAudio, 300);

        animationFrameId = requestAnimationFrame(gameLoop);
    };

    // End Game trigger
    const endGame = (isWin) => {
        isGameRunning = false;
        cancelAnimationFrame(animationFrameId);
        btnSoundPlay.disabled = true;
        targetWordAudio.pause();
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        if (audioPanelIndicator) audioPanelIndicator.classList.remove('sound-pulse');

        fallingZone.classList.add('hidden');
        energyShield.classList.add('hidden');

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
