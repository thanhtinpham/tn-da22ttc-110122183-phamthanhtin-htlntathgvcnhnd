@php
    $questions = [];
    foreach($test->phan as $phan) {
        foreach($phan->cauhoi as $question) {
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung');
            $targetText = $correctOption ? preg_replace('/^[A-D]\.\s*/', '', $correctOption->NDPA) : '';
            // Split sentence by spaces to get words
            $words = array_values(array_filter(explode(' ', $targetText)));
            
            $questions[] = [
                'question' => $question,
                'audio' => $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '',
                'correct_option' => $correctOption,
                'target_text' => $targetText,
                'words' => $words,
            ];
        }
    }
@endphp

<div id="game-workspace" class="w-full relative z-10 animate-fade-in text-slate-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full items-stretch">
        
        <!-- LEFT SIDE: Stats & Control Dashboard -->
        <div class="lg:col-span-4 bg-white/90 backdrop-blur border border-indigo-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Background glow -->
            <div class="absolute -right-12 -bottom-12 w-36 h-36 bg-purple-200/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-6 flex flex-col h-full justify-between relative z-10">
                <!-- Game Header Title -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 border-b border-indigo-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 text-white flex items-center justify-center shadow-md shadow-purple-500/10">
                            <i class="fas fa-comment-dots text-xs"></i>
                        </div>
                        <h5 class="font-extrabold text-indigo-950 mb-0 tracking-wide text-sm uppercase">Trạm bong bóng từ</h5>
                    </div>

                    <!-- Lives and Score panel -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-purple-50/50 border border-purple-100/50 rounded-2xl p-4 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Mạng còn lại</span>
                            <div class="flex items-center justify-center gap-1.5 h-7" id="hearts-container">
                                <i class="fas fa-heart text-rose-500 animate-pulse"></i>
                                <i class="fas fa-heart text-rose-500 animate-pulse"></i>
                                <i class="fas fa-heart text-rose-500 animate-pulse"></i>
                            </div>
                        </div>
                        <div class="bg-purple-50/50 border border-purple-100/50 rounded-2xl p-4 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Điểm hiện tại</span>
                            <div class="text-xl font-black text-amber-500" id="game-score-display">0 / {{ count($questions) * 10 }}</div>
                        </div>
                    </div>

                    <!-- Interactive Guide Bubble -->
                    <div class="flex items-start gap-3 bg-gradient-to-r from-purple-500/5 to-pink-500/5 border border-purple-500/10 p-4 rounded-2xl">
                        <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-12 h-12 object-contain shrink-0 animate-bounce" style="animation-duration: 5s;">
                        <div>
                            <div class="text-[9px] font-black text-purple-600 uppercase tracking-widest leading-none mb-1">AI Hướng Dẫn Viên</div>
                            <p class="text-xs text-slate-500 font-bold leading-relaxed mb-0">Lắng nghe kỹ câu đàm thoại. Sau đó, click vào các bong bóng chữ theo đúng thứ tự câu để ghép thành câu hoàn chỉnh!</p>
                        </div>
                    </div>
                </div>

                <!-- Sound Replayer transmitter -->
                <div class="space-y-4 pt-6 border-t border-indigo-100/50 mt-6">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">Máy phát câu nói</span>
                    <div class="bg-gradient-to-r from-purple-500/5 to-pink-500/5 border border-purple-100/60 p-5 rounded-2xl flex flex-col items-center gap-4 text-center shadow-inner relative overflow-hidden">
                        <div class="flex items-end gap-0.5 h-6 w-8 justify-center mb-1">
                            <span class="w-1 bg-purple-500 rounded-full sound-bar bar-1" style="height: 30%"></span>
                            <span class="w-1 bg-purple-400 rounded-full sound-bar bar-2" style="height: 60%"></span>
                            <span class="w-1 bg-pink-500 rounded-full sound-bar bar-3" style="height: 40%"></span>
                            <span class="w-1 bg-pink-400 rounded-full sound-bar bar-4" style="height: 20%"></span>
                        </div>
                        <button type="button" 
                                id="btn-sound-play"
                                class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-all cursor-pointer border border-purple-400/20 disabled:opacity-50"
                                disabled>
                            <i class="fas fa-volume-up text-2xl"></i>
                        </button>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Click để nghe lại câu hỏi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Word Bubble Scramble Interactive Arena -->
        <div class="lg:col-span-8 bg-white/90 backdrop-blur border border-indigo-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Hidden form for saving points and framing details -->
            <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="bubble-scramble-form" class="space-y-6 h-full flex flex-col justify-between">
                @csrf
                <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
                <input type="hidden" name="start_time" value="{{ time() }}">
                
                @foreach($questions as $qData)
                    <input type="hidden" name="answers[{{ $qData['question']->MaCauHoi }}]" id="ans-{{ $qData['question']->MaCauHoi }}" value="">
                @endforeach

                <div class="flex-grow flex flex-col justify-between h-full">
                    <!-- Progress Bar HUD -->
                    <div class="flex items-center justify-between border-b border-indigo-100 pb-3 mb-4 select-none">
                        <span class="text-xs font-black text-purple-600 uppercase tracking-wider" id="hud-progress-title">Bắt đầu trò chơi</span>
                        <div class="w-1/3 bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200">
                            <div id="hud-progress-bar" class="bg-gradient-to-r from-purple-500 to-pink-500 h-full w-0 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Sandbox Arena -->
                    <div id="game-arena" class="relative w-full h-[480px] overflow-hidden rounded-2xl border border-purple-500/20 shadow-inner flex flex-col justify-between p-4" style="background-image: url('{{ asset('images/map3_bg.png') }}'); background-size: cover; background-position: center;">
                        <!-- Space grid overlays -->
                        <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none"></div>
                        
                        <!-- Top area: Completed sentence slots display -->
                        <div id="completed-container" class="relative w-full min-h-[90px] border-2 border-dashed border-purple-500/30 rounded-2xl bg-slate-900/60 p-4 z-10 flex flex-wrap items-center justify-center gap-2 select-none">
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest text-center w-full" id="empty-sentence-guide">Câu hoàn chỉnh sẽ xuất hiện ở đây...</div>
                        </div>

                        <!-- Bubble sandbox floating playground -->
                        <div id="sandbox-zone" class="relative w-full h-[330px] overflow-hidden hidden"></div>

                        <!-- SCREEN 1: Game Launch Screen -->
                        <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-30 space-y-6 select-none bg-slate-950/80 backdrop-blur-sm rounded-2xl">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-purple-500 via-pink-500 to-rose-500 flex items-center justify-center text-white text-3xl shadow-lg shadow-purple-500/30">
                                <i class="fas fa-comment-dots animate-bounce" style="animation-duration: 3s;"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-400 to-rose-400 uppercase tracking-wide">WORD BUBBLE SCRAMBLE</h4>
                                <p class="text-slate-400 text-sm max-w-sm mt-2 leading-relaxed">Luyện nghe câu đàm thoại giao tiếp xã hội và sắp xếp lại các từ theo cấu trúc chuẩn!</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-start"
                                    class="px-8 py-4 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-black text-base tracking-wider rounded-2xl shadow-lg shadow-purple-500/20 active:translate-y-[2px] transition-all cursor-pointer border border-purple-400/20">
                                BẮT ĐẦU THỬ THÁCH
                            </button>
                        </div>

                        <!-- SCREEN 2: Game Over Screen -->
                        <div id="screen-gameover" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-30 space-y-6 select-none bg-slate-950/80 backdrop-blur-sm rounded-2xl hidden">
                            <div class="w-16 h-16 rounded-full bg-red-950 border border-red-500 text-red-500 flex items-center justify-center text-2xl shadow-lg shadow-red-500/25">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-red-500 uppercase tracking-wider">CỬA ẢI THẤT BẠI</h4>
                                <p class="text-slate-400 text-xs max-w-xs mt-2 leading-relaxed">Bạn đã hết mạng để ghép từ. Hãy nghe lại thật kỹ và thử sức lại nhé!</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-replay"
                                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-extrabold text-sm tracking-wider rounded-xl active:translate-y-[2px] transition-all cursor-pointer border border-red-500/20 shadow-md shadow-red-600/10">
                                <i class="fas fa-redo mr-1"></i> THỬ LẠI CỬA ẢI
                            </button>
                        </div>

                        <!-- SCREEN 3: Level Completed Screen -->
                        <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-30 space-y-6 select-none bg-slate-950/80 backdrop-blur-sm rounded-2xl hidden">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white text-3xl shadow-xl shadow-emerald-500/30">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-emerald-400 uppercase tracking-wide">NHIỆM VỤ THÀNH CÔNG!</h4>
                                <p class="text-slate-400 text-sm max-w-sm mt-2 leading-relaxed">Tuyệt vời! Bạn đã sắp xếp chính xác toàn bộ các cấu trúc câu giao tiếp!</p>
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
    .bg-grid-pattern {
        background-size: 30px 30px;
        background-image: linear-gradient(to right, rgba(168, 85, 247, 0.08) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(168, 85, 247, 0.08) 1px, transparent 1px);
    }

    /* Completed Slot Card design */
    .word-slot-card {
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #1e1b4b, #2e1065);
        border: 2px solid #a855f7;
        color: #f3e8ff;
        font-weight: 800;
        font-size: 0.875rem;
        border-radius: 1rem;
        box-shadow: 0 0 10px rgba(168, 85, 247, 0.3);
        animation: pop-slot-in 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes pop-slot-in {
        from { transform: scale(0.6); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Floating bubble styles */
    .word-bubble-element {
        position: absolute;
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(30, 27, 75, 0.95) 0%, rgba(15, 12, 45, 0.95) 100%);
        border: 2px solid #a855f7;
        box-shadow: 0 0 15px rgba(168, 85, 247, 0.6), inset 0 0 10px rgba(168, 85, 247, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.4rem;
        text-align: center;
        color: #ffffff;
        font-weight: 800;
        font-size: 0.75rem;
        word-break: break-word;
        cursor: pointer;
        user-select: none;
        box-sizing: border-box;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        backdrop-filter: blur(2px);
        transition: transform 0.1s;
        animation: float-bubble 6s ease-in-out infinite alternate;
        will-change: transform, left, top;
    }

    .word-bubble-element:hover {
        border-color: #c084fc;
        box-shadow: 0 0 25px rgba(168, 85, 247, 0.9), 0 0 10px rgba(255, 255, 255, 0.5);
        transform: scale(1.08);
    }

    /* Red bubble shake animation on failure */
    .word-bubble-element.wrong-hit {
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, rgba(239, 68, 68, 0.5) 70%, rgba(159, 18, 57, 0.8) 100%) !important;
        border-color: #ef4444 !important;
        box-shadow: 0 0 25px rgba(239, 68, 68, 0.8) !important;
        animation: shake 0.3s ease-in-out !important;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    /* Keyframe floats */
    @keyframes float-bubble {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
        100% { transform: translateY(15px) rotate(-3deg); }
    }

    /* Explosion lights */
    .bubble-particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
    }

    /* Sound equalizer animations */
    .sound-bar {
        transition: height 0.15s ease-in-out;
    }
    .sound-pulse .bar-1 { animation: sound-bar-grow 1s ease-in-out infinite; transform-origin: bottom; }
    .sound-pulse .bar-2 { animation: sound-bar-grow 1.2s ease-in-out infinite; transform-origin: bottom; }
    .sound-pulse .bar-3 { animation: sound-bar-grow 0.8s ease-in-out infinite; transform-origin: bottom; }
    .sound-pulse .bar-4 { animation: sound-bar-grow 1.4s ease-in-out infinite; transform-origin: bottom; }
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
                targetSentence: "{{ addslashes($qData['target_text']) }}",
                words: [
                    @foreach($qData['words'] as $wIdx => $word)
                        {
                            index: {{ $wIdx }},
                            text: "{{ addslashes($word) }}",
                            cleanedText: "{{ strtolower(preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()?]/', '', $word)) }}"
                        },
                    @endforeach
                ]
            },
        @endforeach
    ];

    // Web Audio Synthesizer for Retro Beeps & Pops
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

    const playBubblePopSound = () => {
        synthBeep(800, 0.08, 'sine');
        setTimeout(() => synthBeep(1200, 0.12, 'sine'), 40);
    };

    const playCorrectSound = () => {
        synthBeep(523.25, 0.12, 'sine'); // C5
        setTimeout(() => synthBeep(659.25, 0.12, 'sine'), 80); // E5
        setTimeout(() => synthBeep(783.99, 0.2, 'sine'), 160); // G5
    };

    const playWrongSound = () => {
        synthBeep(180, 0.35, 'sawtooth', false);
    };

    const playLaserSound = () => {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(900, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(200, audioCtx.currentTime + 0.2);
            gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.2);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.2);
        } catch(e) {}
    };

    // HTML elements references
    const screenLaunch = document.getElementById('screen-launch');
    const screenGameOver = document.getElementById('screen-gameover');
    const screenWin = document.getElementById('screen-win');
    const sandboxZone = document.getElementById('sandbox-zone');
    const completedContainer = document.getElementById('completed-container');
    const emptySentenceGuide = document.getElementById('empty-sentence-guide');
    const btnStart = document.getElementById('btn-game-start');
    const btnReplay = document.getElementById('btn-game-replay');
    const btnSoundPlay = document.getElementById('btn-sound-play');
    const heartsContainer = document.getElementById('hearts-container');
    const gameScoreDisplay = document.getElementById('game-score-display');
    const hudProgressTitle = document.getElementById('hud-progress-title');
    const hudProgressBar = document.getElementById('hud-progress-bar');
    const gameArena = document.getElementById('game-arena');

    // Game variables state
    let activeQuestionIndex = 0;
    let currentWordIndex = 0;
    let score = 0;
    let lives = 3;
    let isGameRunning = false;
    let activeBubbles = [];
    let animationFrameId = null;
    let targetSentenceAudio = new Audio();
    let audioPanelIndicator = document.querySelector('.bg-gradient-to-r.from-purple-500\\/5');

    // Play current sentence audio
    const playCurrentAudio = () => {
        const currentQ = questionsData[activeQuestionIndex];
        if (currentQ && currentQ.audioUrl) {
            targetSentenceAudio.src = currentQ.audioUrl;
            if (audioPanelIndicator) audioPanelIndicator.classList.add('sound-pulse');
            targetSentenceAudio.play().catch(e => console.log('Audio autoplay blocked'));
        }
    };

    targetSentenceAudio.addEventListener('ended', () => {
        if (audioPanelIndicator) audioPanelIndicator.classList.remove('sound-pulse');
    });

    btnSoundPlay.addEventListener('click', () => {
        initAudioContext();
        playLaserSound();
        setTimeout(playCurrentAudio, 150);
    });

    // Render hearts
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

    // Update HUD Progress
    const updateHUD = () => {
        if (!isGameRunning) return;
        const total = questionsData.length;
        hudProgressTitle.innerText = `CÂU HỎI ${activeQuestionIndex + 1} / ${total}`;
        const pct = ((activeQuestionIndex) / total) * 100;
        hudProgressBar.style.width = `${pct}%`;
        gameScoreDisplay.innerText = `${score} / ${total * 10}`;
    };

    // Bubble Burst Exploder effect
    const createBubbleBurst = (x, y) => {
        const particlesCount = 16;
        const colors = ['#a855f7', '#ec4899', '#f43f5e', '#3b82f6', '#ffffff'];
        for (let i = 0; i < particlesCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'bubble-particle';
            particle.style.left = `${x}px`;
            particle.style.top = `${y}px`;
            
            const size = 4 + Math.random() * 8;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            particle.style.boxShadow = '0 0 8px ' + particle.style.backgroundColor;
            
            gameArena.appendChild(particle);

            const angle = Math.random() * Math.PI * 2;
            const velocity = 3 + Math.random() * 6;
            const dx = Math.cos(angle) * velocity;
            const dy = Math.sin(angle) * velocity;
            
            let alpha = 1;
            let currentX = x;
            let currentY = y;

            const updateParticle = () => {
                currentX += dx;
                currentY += dy;
                alpha -= 0.03;
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

    // Spawn floating word bubbles in sandbox
    const spawnWordBubbles = () => {
        sandboxZone.innerHTML = '';
        completedContainer.innerHTML = '';
        completedContainer.appendChild(emptySentenceGuide);
        emptySentenceGuide.classList.remove('hidden');

        activeBubbles = [];
        currentWordIndex = 0;

        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;

        // Shuffle words
        const shuffledWords = [...currentQ.words];
        shuffledWords.sort(() => Math.random() - 0.5);

        const arenaWidth = sandboxZone.clientWidth;
        const arenaHeight = sandboxZone.clientHeight;

        shuffledWords.forEach((wordObj) => {
            const bubbleEl = document.createElement('div');
            bubbleEl.className = 'word-bubble-element';
            bubbleEl.innerText = wordObj.text;
            
            // Random floating coordinates
            const randX = 30 + Math.random() * (arenaWidth - 140);
            const randY = 20 + Math.random() * (arenaHeight - 120);

            bubbleEl.style.left = `${randX}px`;
            bubbleEl.style.top = `${randY}px`;
            
            // Add custom animation delays for organic float effect
            bubbleEl.style.animationDelay = `${Math.random() * -6}s`;
            bubbleEl.style.animationDuration = `${5 + Math.random() * 3}s`;

            sandboxZone.appendChild(bubbleEl);

            // Speed and angle parameters for movement
            const angle = Math.random() * Math.PI * 2;
            const speed = 0.5 + Math.random() * 0.7;

            const bubble = {
                element: bubbleEl,
                text: wordObj.text,
                cleanedText: wordObj.cleanedText,
                index: wordObj.index,
                x: randX,
                y: randY,
                dx: Math.cos(angle) * speed,
                dy: Math.sin(angle) * speed,
                radius: 42,
                isClicked: false
            };

            bubbleEl.addEventListener('click', (e) => handleBubbleClick(bubble, e));
            activeBubbles.push(bubble);
        });
    };

    // Bubble Click handler
    const handleBubbleClick = (bubble, e) => {
        if (bubble.isClicked || !isGameRunning) return;
        
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;

        initAudioContext();

        // Get expected word at current index
        const expectedWord = currentQ.words[currentWordIndex];
        if (!expectedWord) return;

        const rect = bubble.element.getBoundingClientRect();
        const arenaRect = gameArena.getBoundingClientRect();
        const clickX = rect.left - arenaRect.left + rect.width / 2;
        const clickY = rect.top - arenaRect.top + rect.height / 2;

        // Verify if correct word bubble clicked
        if (bubble.cleanedText === expectedWord.cleanedText) {
            bubble.isClicked = true;
            playBubblePopSound();
            createBubbleBurst(clickX, clickY);

            // Hide empty guide text
            emptySentenceGuide.classList.add('hidden');

            // Add word card to completed sentence container
            const wordCard = document.createElement('div');
            wordCard.className = 'word-slot-card';
            wordCard.innerText = bubble.text;
            completedContainer.appendChild(wordCard);

            // Remove bubble from DOM and active tracker
            bubble.element.remove();
            activeBubbles = activeBubbles.filter(b => b !== bubble);

            currentWordIndex++;

            // Check if sentence is completed
            if (currentWordIndex >= currentQ.words.length) {
                // Save correct value in form submit fields
                const hiddenInp = document.getElementById('ans-' + currentQ.id);
                if (hiddenInp) {
                    hiddenInp.value = currentQ.correctOptionId;
                }

                playCorrectSound();
                score += 10;
                activeQuestionIndex++;
                updateHUD();

                if (activeQuestionIndex >= questionsData.length) {
                    endGame(true);
                } else {
                    // Flash positive background
                    gameArena.classList.add('bg-emerald-950/20');
                    setTimeout(() => gameArena.classList.remove('bg-emerald-950/20'), 300);

                    setTimeout(() => {
                        spawnWordBubbles();
                        playCurrentAudio();
                    }, 1000);
                }
            }
        } else {
            // Incorrect word bubble clicked
            playWrongSound();
            bubble.element.classList.add('wrong-hit');
            lives--;
            renderHearts();

            setTimeout(() => {
                bubble.element.classList.remove('wrong-hit');
            }, 600);

            if (lives <= 0) {
                endGame(false);
            }
        }
    };

    // Physics Loop to move and bounce bubbles inside sandbox limits
    const updatePhysics = () => {
        if (!isGameRunning) return;

        const arenaWidth = sandboxZone.clientWidth;
        const arenaHeight = sandboxZone.clientHeight;
        const bubbleSize = 84;

        activeBubbles.forEach((b) => {
            b.x += b.dx;
            b.y += b.dy;

            // Bounce off left/right borders
            if (b.x < 10) {
                b.x = 10;
                b.dx *= -1;
            } else if (b.x > arenaWidth - bubbleSize - 10) {
                b.x = arenaWidth - bubbleSize - 10;
                b.dx *= -1;
            }

            // Bounce off top/bottom borders
            if (b.y < 10) {
                b.y = 10;
                b.dy *= -1;
            } else if (b.y > arenaHeight - bubbleSize - 10) {
                b.y = arenaHeight - bubbleSize - 10;
                b.dy *= -1;
            }

            b.element.style.left = `${b.x}px`;
            b.element.style.top = `${b.y}px`;
        });

        if (isGameRunning) {
            animationFrameId = requestAnimationFrame(updatePhysics);
        }
    };

    // Start Game
    const startGame = () => {
        initAudioContext();
        screenLaunch.classList.add('hidden');
        screenGameOver.classList.add('hidden');
        screenWin.classList.add('hidden');
        
        sandboxZone.classList.remove('hidden');
        btnSoundPlay.disabled = false;

        activeQuestionIndex = 0;
        score = 0;
        lives = 3;
        isGameRunning = true;

        renderHearts();
        updateHUD();
        spawnWordBubbles();
        playLaserSound();
        setTimeout(playCurrentAudio, 300);

        // Start animation/physics frame tick
        animationFrameId = requestAnimationFrame(updatePhysics);
    };

    // End Game
    const endGame = (isWin) => {
        isGameRunning = false;
        cancelAnimationFrame(animationFrameId);
        btnSoundPlay.disabled = true;
        targetSentenceAudio.pause();
        if (audioPanelIndicator) audioPanelIndicator.classList.remove('sound-pulse');

        sandboxZone.classList.add('hidden');

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
