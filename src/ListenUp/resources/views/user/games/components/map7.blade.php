@php
    $questions = [];
    foreach($test->phan as $phan) {
        foreach($phan->cauhoi as $question) {
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
            $joinedWords = $correctOption ? preg_replace('/^[A-D]\.\s*/', '', $correctOption->NDPA) : '';
            
            $questions[] = [
                'id' => $question->MaCauHoi,
                'vietnamese' => trim($question->NDCauHoi),
                'joined_words' => trim($joinedWords), // Separated by '|'
                'audio' => $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '',
                'option_id' => $correctOption ? $correctOption->MaPA : '',
            ];
        }
    }
@endphp

<div id="game-workspace" class="w-full relative z-10 animate-fade-in text-slate-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full items-stretch">
        
        <!-- LEFT SIDE: Stats & Control Dashboard -->
        <div class="lg:col-span-4 bg-white/90 backdrop-blur border border-indigo-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Background glow -->
            <div class="absolute -right-12 -bottom-12 w-36 h-36 bg-amber-200/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-6 flex flex-col h-full justify-between relative z-10">
                <!-- Game Header Title -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 border-b border-indigo-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 text-white flex items-center justify-center shadow-md shadow-amber-500/10">
                            <i class="fas fa-list-ol text-xs"></i>
                        </div>
                        <h5 class="font-extrabold text-indigo-950 mb-0 tracking-wide text-sm uppercase">Sắp xếp câu đúng</h5>
                    </div>

                    <!-- Lives and Score panel -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-amber-50/50 border border-amber-100/50 rounded-2xl p-4 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Năng lượng</span>
                            <div class="flex items-center justify-center gap-1.5 h-7" id="hearts-container">
                                <i class="fas fa-heart text-amber-500 animate-pulse"></i>
                                <i class="fas fa-heart text-amber-500 animate-pulse"></i>
                                <i class="fas fa-heart text-amber-500 animate-pulse"></i>
                            </div>
                        </div>
                        <div class="bg-amber-50/50 border border-amber-100/50 rounded-2xl p-4 text-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Điểm hiện tại</span>
                            <div class="text-xl font-black text-amber-500" id="game-score-display">0 / {{ count($questions) * 10 }}</div>
                        </div>
                    </div>

                    <!-- Interactive Guide Bubble -->
                    <div class="flex items-start gap-3 bg-gradient-to-r from-amber-500/5 to-orange-500/5 border border-amber-500/10 p-4 rounded-2xl">
                        <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-12 h-12 object-contain shrink-0 animate-bounce" style="animation-duration: 5s;" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=AI&background=F59E0B&color=fff';">
                        <div>
                            <div class="text-[9px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">AI Hướng Dẫn Viên</div>
                            <p class="text-xs text-slate-500 font-bold leading-relaxed mb-0">Lắng nghe kỹ câu nói từ audio, sau đó click vào các từ để sắp xếp chúng thành một câu hoàn chỉnh, đúng ngữ pháp nhé!</p>
                        </div>
                    </div>
                </div>

                <!-- Control guide -->
                <div class="space-y-4 pt-6 border-t border-indigo-100/50 mt-6">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">Gợi ý thao tác nhanh</span>
                    <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-2xl text-xs space-y-2 text-slate-500 font-bold">
                        <div class="flex justify-between items-center">
                            <span>Nghe phát âm:</span>
                            <kbd class="px-2 py-1 bg-white border border-slate-300 rounded text-[10px] shadow-sm">Click biểu tượng Loa</kbd>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Chọn/Bỏ từ:</span>
                            <kbd class="px-2 py-1 bg-white border border-slate-300 rounded text-[10px] shadow-sm">Click chuột vào từ</kbd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Word Puzzle Arena -->
        <div class="lg:col-span-8 bg-white/90 backdrop-blur border border-indigo-100 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <!-- Form for saving points -->
            <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="sentence-builder-form" class="space-y-6 h-full flex flex-col justify-between">
                @csrf
                <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
                <input type="hidden" name="start_time" value="{{ time() }}">
                
                @foreach($questions as $qData)
                    <input type="hidden" name="answers[{{ $qData['id'] }}]" id="ans-{{ $qData['id'] }}" value="">
                @endforeach

                <div class="flex-grow flex flex-col justify-between h-full">
                    <!-- Progress Bar HUD -->
                    <div class="flex items-center justify-between border-b border-indigo-100 pb-3 mb-4 select-none">
                        <span class="text-xs font-black text-amber-600 uppercase tracking-wider" id="hud-progress-title">Bắt đầu trò chơi</span>
                        <div class="w-1/3 bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200">
                            <div id="hud-progress-bar" class="bg-gradient-to-r from-amber-500 to-orange-500 h-full w-0 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Game Arena Playground -->
                    <div id="game-arena" class="relative w-full min-h-[460px] bg-slate-950 overflow-hidden rounded-2xl border border-amber-500/20 shadow-inner flex flex-col justify-between p-6">
                        <!-- Space grid overlays -->
                        <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none"></div>
                        
                        <!-- PLAYING WORKSPACE (Hidden initially on start screens) -->
                        <div id="playing-zone" class="w-full flex-grow flex flex-col justify-between relative z-10 hidden">
                            <!-- Question Clue & Pronounce Audio -->
                            <div class="flex flex-col items-center justify-center space-y-4 mb-6">
                                <!-- Sound play trigger -->
                                <button type="button" 
                                        id="btn-play-pronounce"
                                        class="w-16 h-16 rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all outline-none cursor-pointer border border-amber-400/20">
                                    <i class="fas fa-volume-up text-2xl" id="pronounce-icon"></i>
                                </button>
                                
                                <!-- Vietnamese meaning banner -->
                                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl px-6 py-3 text-center max-w-lg shadow-sm">
                                    <span class="text-[9px] font-black text-amber-400 uppercase tracking-widest block mb-1">Nghĩa dịch Tiếng Việt</span>
                                    <p class="text-sm font-bold text-slate-300 mb-0 leading-relaxed" id="clue-vietnamese">Tôi đi học mỗi ngày.</p>
                                </div>
                            </div>

                            <!-- Target Sentence Drop/Placement Zone -->
                            <div class="w-full border-2 border-dashed border-slate-800 bg-slate-950/40 rounded-2xl p-4 min-h-[80px] flex flex-wrap items-center justify-center gap-2 mb-6" id="sentence-target-zone">
                                <!-- Selected words go here -->
                            </div>

                            <!-- Available Words Pool -->
                            <div class="w-full flex flex-wrap justify-center gap-2.5 p-4 bg-slate-900/20 rounded-2xl border border-slate-800/40 mb-6" id="words-pool-zone">
                                <!-- Shuffled word buttons go here -->
                            </div>

                            <!-- Check Result Dock -->
                            <div class="flex justify-end pt-4 border-t border-slate-900">
                                <button type="button" 
                                        id="btn-check-sentence"
                                        class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black text-xs tracking-wider rounded-xl cursor-pointer border border-amber-300/20 shadow-md shadow-orange-500/10 active:translate-y-[1px] transition-all">
                                    KIỂM TRA
                                </button>
                            </div>
                        </div>

                        <!-- SCREEN 1: Game Launch Screen -->
                        <div id="screen-launch" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-slate-950/95 rounded-2xl">
                            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-500 flex items-center justify-center text-white text-3xl shadow-lg shadow-orange-500/30">
                                <i class="fas fa-list-ol animate-bounce" style="animation-duration: 3s;"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-400 to-yellow-400 uppercase tracking-wide">BUILD THE SENTENCE</h4>
                                <p class="text-slate-400 text-sm max-w-sm mt-2 leading-relaxed">Sắp xếp câu theo thứ tự từ chuẩn! Lắng nghe phát âm và sắp xếp lại các khối từ để tạo nên câu đúng ngữ pháp.</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-start"
                                    class="px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-black text-base tracking-wider rounded-2xl shadow-lg shadow-orange-500/20 active:translate-y-[2px] transition-all cursor-pointer border border-orange-400/20">
                                BẮT ĐẦU THỬ THÁCH
                            </button>
                        </div>

                        <!-- SCREEN 2: Game Over Screen -->
                        <div id="screen-gameover" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-slate-950/95 rounded-2xl hidden">
                            <div class="w-16 h-16 rounded-full bg-red-950 border border-red-500 text-red-500 flex items-center justify-center text-2xl shadow-lg shadow-red-500/25">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-red-500 uppercase tracking-wider">CẠN KIỆT NĂNG LƯỢNG</h4>
                                <p class="text-slate-400 text-xs max-w-xs mt-2 leading-relaxed">Lá chắn bảo vệ đã cạn kiệt. Hãy nghe kỹ lại bài và thử sức lại nhé!</p>
                            </div>
                            <button type="button" 
                                    id="btn-game-replay"
                                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-extrabold text-sm tracking-wider rounded-xl active:translate-y-[2px] transition-all cursor-pointer border border-red-500/20 shadow-md shadow-red-600/10">
                                <i class="fas fa-redo mr-1"></i> THỬ LẠI CỬA ẢI
                            </button>
                        </div>

                        <!-- SCREEN 3: Level Completed Screen -->
                        <div id="screen-win" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center z-40 space-y-6 select-none bg-slate-950/95 rounded-2xl hidden">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white text-3xl shadow-xl shadow-emerald-500/30">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-black text-emerald-400 uppercase tracking-wide">NHIỆM VỤ HOÀN THÀNH!</h4>
                                <p class="text-slate-400 text-sm max-w-sm mt-2 leading-relaxed">Tuyệt vời! Bạn đã sắp xếp chính xác tất cả các câu và hoàn thành bài kiểm tra!</p>
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
        background-image: linear-gradient(to right, rgba(245, 158, 11, 0.08) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(245, 158, 11, 0.08) 1px, transparent 1px);
    }

    /* Word bubble chips styling */
    .word-chip-btn {
        background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
        border: 2px solid rgba(245, 158, 11, 0.3);
        color: #fef3c7;
        font-weight: 800;
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 1rem;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        outline: none;
    }

    .word-chip-btn:hover:not(:disabled) {
        border-color: #f59e0b;
        transform: translateY(-2px);
        box-shadow: 0 0 12px rgba(245, 158, 11, 0.4);
    }

    .word-chip-btn:active:not(:disabled) {
        transform: translateY(1px);
    }

    .word-chip-btn:disabled {
        opacity: 0.25;
        cursor: default;
        border-color: rgba(255,255,255,0.08);
        box-shadow: none;
    }

    .word-chip-placed {
        background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%);
        border: 2px solid #3b82f6;
        color: #eff6ff;
        font-weight: 800;
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 1rem;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .word-chip-placed:hover {
        border-color: #ef4444;
        background: linear-gradient(135deg, #7f1d1d 0%, #450a0a 100%);
        color: #fef2f2;
    }

    /* Wrong arrangement shake */
    .arena-shake {
        animation: shake 0.35s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    /* Wave pulse glow for audio */
    .audio-pulsing {
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
        animation: audio-glow 1.5s infinite cubic-bezier(0.66, 0, 0, 1);
    }

    @keyframes audio-glow {
        to {
            box-shadow: 0 0 0 20px rgba(245, 158, 11, 0);
        }
    }

    /* Particles spark */
    .spark-particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Load questions data from Blade PHP
    const questionsData = [
        @foreach($questions as $qIdx => $qData)
            {
                id: "{{ $qData['id'] }}",
                vietnamese: "{{ addslashes($qData['vietnamese']) }}",
                wordsList: "{{ addslashes($qData['joined_words']) }}".split('|'),
                audioUrl: "{{ $qData['audio'] }}",
                optionId: "{{ $qData['option_id'] }}"
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
        } catch(e) {
            console.error('Synth beep error', e);
        }
    };

    const playCorrectSound = () => {
        synthBeep(523.25, 0.1, 'sine'); // C5
        setTimeout(() => synthBeep(659.25, 0.1, 'sine'), 80); // E5
        setTimeout(() => synthBeep(783.99, 0.2, 'sine'), 160); // G5
    };

    const playWrongSound = () => {
        synthBeep(180, 0.35, 'sawtooth', false);
    };

    const playClickSound = () => {
        synthBeep(987.77, 0.05, 'sine'); // B5 (Soft short plop)
    };

    const playLaserSound = () => {
        if (!audioCtx) return;
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(700, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(300, audioCtx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.15);
        } catch(e) {}
    };

    // Text to speech fallback pronouncer
    const speakSentence = (text) => {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        }
    };

    // UI elements
    const screenLaunch = document.getElementById('screen-launch');
    const screenGameOver = document.getElementById('screen-gameover');
    const screenWin = document.getElementById('screen-win');
    const playingZone = document.getElementById('playing-zone');
    const clueVietnamese = document.getElementById('clue-vietnamese');
    const targetZone = document.getElementById('sentence-target-zone');
    const poolZone = document.getElementById('words-pool-zone');
    
    const btnPlayPronounce = document.getElementById('btn-play-pronounce');
    const pronounceIcon = document.getElementById('pronounce-icon');
    const btnCheck = document.getElementById('btn-check-sentence');
    const btnStart = document.getElementById('btn-game-start');
    const btnReplay = document.getElementById('btn-game-replay');
    
    const heartsContainer = document.getElementById('hearts-container');
    const gameScoreDisplay = document.getElementById('game-score-display');
    const hudProgressTitle = document.getElementById('hud-progress-title');
    const hudProgressBar = document.getElementById('hud-progress-bar');
    const gameArena = document.getElementById('game-arena');

    // Game states
    let activeQuestionIndex = 0;
    let score = 0;
    let lives = 3;
    let isGameRunning = false;
    let activeAudio = new Audio();
    
    let currentPoolWords = []; // Shuffled list of words
    let currentPlacedWords = []; // Selected indices mapping to currentPoolWords

    // Play target audio guide
    const playTargetAudio = () => {
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;

        initAudioContext();
        btnPlayPronounce.classList.add('audio-pulsing');
        
        // Joined sentence string for TTS fallback
        const rawSentence = currentQ.wordsList.join(' ');

        if (currentQ.audioUrl && currentQ.audioUrl !== '') {
            activeAudio.src = currentQ.audioUrl;
            activeAudio.play().catch(e => {
                console.log('Audio block fallback to SpeechSynthesis');
                speakSentence(rawSentence);
            });
        } else {
            speakSentence(rawSentence);
        }
    };

    activeAudio.addEventListener('ended', () => {
        btnPlayPronounce.classList.remove('audio-pulsing');
    });

    btnPlayPronounce.addEventListener('click', playTargetAudio);

    // Shuffle helper
    const shuffleArray = (array) => {
        const arr = [...array];
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    };

    // Render Hearts
    const renderHearts = () => {
        let heartsHtml = '';
        for (let i = 0; i < 3; i++) {
            if (i < lives) {
                heartsHtml += '<i class="fas fa-heart text-amber-500 animate-pulse text-lg"></i>';
            } else {
                heartsHtml += '<i class="far fa-heart text-slate-300 text-lg"></i>';
            }
        }
        heartsContainer.innerHTML = heartsHtml;
    };

    // Particles Match Success Burst
    const createExplosionAtTarget = () => {
        const rect = targetZone.getBoundingClientRect();
        const arenaRect = gameArena.getBoundingClientRect();
        const burstX = rect.left - arenaRect.left + rect.width / 2;
        const burstY = rect.top - arenaRect.top + rect.height / 2;

        const particlesCount = 15;
        const colors = ['#f59e0b', '#fbbf24', '#fef3c7', '#34d399', '#ffffff'];

        for (let i = 0; i < particlesCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'spark-particle';
            const size = 3 + Math.random() * 6;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            particle.style.boxShadow = '0 0 10px ' + particle.style.backgroundColor;
            particle.style.left = `${burstX}px`;
            particle.style.top = `${burstY}px`;

            gameArena.appendChild(particle);

            const angle = Math.random() * Math.PI * 2;
            const velocity = 3 + Math.random() * 6;
            const dx = Math.cos(angle) * velocity;
            const dy = Math.sin(angle) * velocity;

            let alpha = 1;
            let currentX = burstX;
            let currentY = burstY;

            const updateParticle = () => {
                currentX += dx;
                currentY += dy;
                alpha -= 0.035;
                particle.style.transform = `translate(${currentX - burstX}px, ${currentY - burstY}px) scale(${alpha})`;
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

    // Render workspace (Target Zone and Pool)
    const renderWorkspace = () => {
        // Render Target Zone
        targetZone.innerHTML = '';
        if (currentPlacedWords.length === 0) {
            targetZone.innerHTML = `<span class="text-xs font-semibold text-slate-500 uppercase tracking-wider select-none py-1">Click các từ để bắt đầu ghép câu...</span>`;
        } else {
            currentPlacedWords.forEach((poolIdx) => {
                const word = currentPoolWords[poolIdx];
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'word-chip-placed animate-fade-in';
                btn.textContent = word;
                btn.addEventListener('click', () => removeWord(poolIdx));
                targetZone.appendChild(btn);
            });
        }

        // Render Pool Zone
        poolZone.innerHTML = '';
        currentPoolWords.forEach((word, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'word-chip-btn';
            btn.textContent = word;
            
            // Disable button if it is already placed/selected
            if (currentPlacedWords.includes(idx)) {
                btn.disabled = true;
            } else {
                btn.addEventListener('click', () => placeWord(idx));
            }
            poolZone.appendChild(btn);
        });
    };

    // Add word to target sentence placement
    const placeWord = (poolIdx) => {
        if (!isGameRunning) return;
        initAudioContext();
        playClickSound();

        // Speak individual word if TTS is supported
        speakSentence(currentPoolWords[poolIdx]);

        currentPlacedWords.push(poolIdx);
        renderWorkspace();
    };

    // Remove word back to pool
    const removeWord = (poolIdx) => {
        if (!isGameRunning) return;
        initAudioContext();
        playClickSound();

        currentPlacedWords = currentPlacedWords.filter(idx => idx !== poolIdx);
        renderWorkspace();
    };

    // HUD Stats Update
    const updateHUD = () => {
        const total = questionsData.length;
        hudProgressTitle.innerText = `CÂU HỎI ${activeQuestionIndex + 1} / ${total}`;
        const pct = (activeQuestionIndex / total) * 100;
        hudProgressBar.style.width = `${pct}%`;
        gameScoreDisplay.innerText = `${score} / ${total * 10}`;
    };

    // Load active question details
    const loadQuestion = () => {
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) {
            endGame(true);
            return;
        }

        btnPlayPronounce.classList.remove('audio-pulsing');
        activeAudio.pause();

        clueVietnamese.innerText = currentQ.vietnamese;
        
        // Shuffle words list for word pool
        currentPoolWords = shuffleArray(currentQ.wordsList);
        currentPlacedWords = [];

        updateHUD();
        renderWorkspace();
        
        // Automatically play pronunciation guide on start
        setTimeout(playTargetAudio, 400);
    };

    // Check target arrangement correctness
    const checkSentence = () => {
        if (!isGameRunning) return;
        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ) return;

        // Build constructed sentence
        const constructedList = currentPlacedWords.map(idx => currentPoolWords[idx]);
        const constructedStr = constructedList.join(' ');
        const correctStr = currentQ.wordsList.join(' ');

        // Clean comparison helper
        const cleanString = (str) => str.trim().toLowerCase().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?]/g,"");

        if (cleanString(constructedStr) === cleanString(correctStr)) {
            // Success Match
            initAudioContext();
            playCorrectSound();
            createExplosionAtTarget();

            // Populate answer input
            const hiddenInp = document.getElementById('ans-' + currentQ.id);
            if (hiddenInp) {
                hiddenInp.value = currentQ.optionId;
            }

            score += 10;
            activeQuestionIndex++;

            if (activeQuestionIndex >= questionsData.length) {
                endGame(true);
            } else {
                // Flash success container background briefly
                gameArena.classList.add('bg-emerald-950/20');
                setTimeout(() => gameArena.classList.remove('bg-emerald-950/20'), 300);

                setTimeout(loadQuestion, 500);
            }
        } else {
            // Mismatch shake warning
            initAudioContext();
            playWrongSound();

            gameArena.classList.add('arena-shake', 'bg-red-950/20');
            setTimeout(() => {
                gameArena.classList.remove('arena-shake', 'bg-red-950/20');
            }, 350);

            lives--;
            renderHearts();

            if (lives <= 0) {
                endGame(false);
            }
        }
    };

    btnCheck.addEventListener('click', checkSentence);

    // Game Lifecycle
    const startGame = () => {
        initAudioContext();
        screenLaunch.classList.add('hidden');
        screenGameOver.classList.add('hidden');
        screenWin.classList.add('hidden');
        
        playingZone.classList.remove('hidden');

        activeQuestionIndex = 0;
        score = 0;
        lives = 3;
        isGameRunning = true;

        renderHearts();
        loadQuestion();
        playLaserSound();
    };

    const endGame = (isWin) => {
        isGameRunning = false;
        activeAudio.pause();
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }

        playingZone.classList.add('hidden');

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
