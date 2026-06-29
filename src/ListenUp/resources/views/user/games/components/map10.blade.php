@php
    $questions = [];
    foreach($test->phan as $phan) {
        foreach($phan->cauhoi as $question) {
            $correctOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->firstWhere('DapAn', 1);
            
            $questions[] = [
                'question' => $question,
                'options' => $question->phuongancauhoi,
                'audio' => $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '',
                'correct_option' => $correctOption,
                'part_title' => $phan->TenPhan,
            ];
        }
    }
    $numQuestions = count($questions);
    $maxHP = $numQuestions * 10;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap');
    
    .font-game {
        font-family: 'Outfit', sans-serif;
    }

    /* Bouncy 3D RPG buttons */
    .btn-3d {
        position: relative;
        transition: all 0.1s ease;
        border-bottom-width: 4px;
    }
    .btn-3d:active {
        transform: translateY(4px);
        border-bottom-width: 0px !important;
    }

    /* Glassmorphism Panels */
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

    /* Arena backgrounds */
    .bg-boss-volcano {
        background: linear-gradient(to bottom, #1e0b1c, #3b0d1e, #1a0826);
        background-size: cover;
        position: relative;
    }
    .bg-game-pattern {
        background-color: #f3f4f6;
        background-image: radial-gradient(#d1d5db 1px, transparent 1px);
        background-size: 24px 24px;
    }

    /* Boss Float animation */
    .boss-float {
        animation: bossFloat 3.2s ease-in-out infinite;
    }
    @keyframes bossFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(1deg); }
    }

    /* Demon Wings animation */
    .wing-left {
        animation: wingLeftFlap 3s ease-in-out infinite;
        transform-origin: 50px 45px;
    }
    .wing-right {
        animation: wingRightFlap 3s ease-in-out infinite;
        transform-origin: 50px 45px;
    }
    @keyframes wingLeftFlap {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(-8deg); }
    }
    @keyframes wingRightFlap {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(8deg); }
    }

    /* Boss Eyes glow */
    .boss-eyes-glow {
        animation: eyeGlow 1.5s ease-in-out infinite alternate;
    }
    @keyframes eyeGlow {
        from { filter: drop-shadow(0 0 2px #f43f5e); }
        to { filter: drop-shadow(0 0 8px #f43f5e); r: 5; }
    }

    /* Attack leaping animations */
    .player-leap {
        animation: playerLeapAttack 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    @keyframes playerLeapAttack {
        0% { transform: scale(1) translateX(0); }
        30% { transform: scale(0.9) translateX(-15px); }
        50% { transform: scale(1.15) translateX(160px) rotate(10deg); }
        100% { transform: scale(1) translateX(0); }
    }

    /* Boss attack projectiles */
    .projectile-fireball {
        position: absolute;
        width: 32px;
        height: 32px;
        background: radial-gradient(circle, #fb923c 20%, #ef4444 80%);
        box-shadow: 0 0 15px #f97316, 0 0 30px #dc2626;
        border-radius: 50%;
        pointer-events: none;
        z-index: 50;
        animation: fireballTravel 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }
    @keyframes fireballTravel {
        0% { right: 80px; top: 40%; transform: scale(0.5); }
        100% { left: 80px; top: 50%; transform: scale(1.2); }
    }

    /* Sword Slash effect overlay */
    .sword-slash-overlay {
        position: absolute;
        width: 160px;
        height: 8px;
        background: #22d3ee;
        box-shadow: 0 0 20px #06b6d4;
        transform: rotate(-35deg) scaleX(0);
        transform-origin: center;
        pointer-events: none;
        z-index: 52;
        animation: slashAction 0.4s ease-out forwards;
    }
    @keyframes slashAction {
        0% { transform: rotate(-35deg) scaleX(0); opacity: 1; }
        50% { transform: rotate(-35deg) scaleX(1.3); opacity: 1; }
        100% { transform: rotate(-35deg) scaleX(1); opacity: 0; }
    }

    /* Damage popups */
    .damage-number-pop {
        position: absolute;
        font-size: 1.8rem;
        font-weight: 900;
        color: #ef4444;
        text-shadow: 0 3px 6px rgba(0,0,0,0.9);
        z-index: 60;
        pointer-events: none;
        animation: damageFloatUp 0.8s ease-out forwards;
    }
    @keyframes damageFloatUp {
        0% { transform: translateY(0) scale(0.6); opacity: 0.2; }
        30% { transform: translateY(-20px) scale(1.2); opacity: 1; }
        100% { transform: translateY(-60px) scale(0.9); opacity: 0; }
    }

    /* Screen shake on damage */
    .shake-arena {
        animation: shakeArenaAnim 0.35s ease-in-out;
    }
    @keyframes shakeArenaAnim {
        0%, 100% { transform: translate(0, 0); }
        20%, 60% { transform: translate(-4px, 4px); }
        40%, 80% { transform: translate(4px, -4px); }
    }

    /* Skill Button Glowing Cooldowns */
    .skill-btn {
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .skill-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 0 15px rgba(168, 85, 247, 0.4);
    }
    .skill-btn:active {
        transform: translateY(1px);
    }

    /* Audio Equalizer pulse */
    @keyframes barPulse {
        0%, 100% { transform: scaleY(0.2); }
        50% { transform: scaleY(1.1); }
    }
    .equalizer-bar {
        animation: barPulse 0.8s ease-in-out infinite alternate;
        transform-origin: bottom;
    }
</style>

@if($numQuestions === 0)
    <div class="glass-panel text-center p-12 rounded-[2rem] border border-slate-200 shadow-lg max-w-lg mx-auto my-auto space-y-4">
        <div class="w-16 h-16 rounded-3xl bg-indigo-50 border border-indigo-200 text-indigo-500 flex items-center justify-center text-3xl mx-auto shadow-sm">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h4 class="text-xl font-black text-slate-800">CẤU HÌNH CHƯA SẴN SÀNG</h4>
        <p class="text-slate-500 font-bold text-xs leading-relaxed">Bài test này chưa chứa câu hỏi hoặc file âm thanh. Vui lòng thêm câu hỏi ở trang quản lý trước khi bắt đầu.</p>
        <a href="{{ route('public.games') }}" class="btn-3d inline-block px-6 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl border-0 border-b-slate-400 text-xs">Quay lại Bản đồ</a>
    </div>
@else
    <div id="game-workspace" class="w-full min-h-[760px] bg-game-pattern p-6 rounded-[2.5rem] shadow-xl border-4 border-white relative overflow-hidden font-game text-slate-800 flex flex-col justify-between">
        
        <!-- Background glows -->
        <div class="absolute top-20 left-10 w-24 h-24 bg-purple-300/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-20 right-10 w-36 h-36 bg-orange-300/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- FORM SUBMITTER -->
        <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="boss-battle-form" class="flex-grow flex flex-col justify-between h-full space-y-6 relative z-10">
            @csrf
            <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
            <input type="hidden" name="start_time" value="{{ time() }}">
            
            @foreach($questions as $qIdx => $qData)
                <input type="hidden" name="answers[{{ $qData['question']->MaCauHoi }}]" id="ans-{{ $qData['question']->MaCauHoi }}" value="" required>
            @endforeach

            <!-- 1. HEADER BAR -->
            <div class="glass-panel p-4 rounded-3xl shadow-sm flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 text-white flex items-center justify-center shadow-lg shadow-purple-500/25">
                        <i class="fas fa-dragon text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-indigo-950 font-black text-base leading-none mb-1">Quyết Chiến Ma Vương</h4>
                        <div class="text-[10px] text-purple-600 font-extrabold uppercase tracking-wider">Thử thách: {{ $map->TenBanDo ?? 'Thủ lĩnh' }}</div>
                    </div>
                </div>

                <!-- XP Progress bar -->
                <div class="flex-grow max-w-xs px-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiến Trình Trận Đấu</span>
                        <span class="text-xs font-black text-indigo-650" id="progress-text-hud">Câu 0 / {{ $numQuestions }}</span>
                    </div>
                    <div class="h-4 bg-slate-100 rounded-full border border-slate-200 p-0.5 shadow-inner">
                        <div id="progress-bar-hud" class="h-full rounded-full bg-gradient-to-r from-purple-500 via-pink-500 to-amber-500 w-0 transition-all duration-300"></div>
                    </div>
                </div>

                <!-- Stats values -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="bg-rose-50 border border-rose-100 px-3.5 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-rose-500 font-black text-sm" id="hud-player-hp">❤️ HP: {{ $maxHP }}</span>
                        </div>
                        <div class="bg-purple-50 border border-purple-100 px-3.5 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-purple-600 font-black text-xs" id="hud-combo-combo">🔥 Combo: x0</span>
                        </div>
                        <div class="bg-amber-50 border border-amber-100 px-3.5 py-1.5 rounded-2xl shadow-sm">
                            <span class="text-amber-600 font-black text-sm" id="score-counter-text">⭐ 0 XP</span>
                        </div>
                    </div>

                    <button type="button" onclick="alert('Nhấp vào các câu hỏi để làm bài. Trả lời đúng sẽ giúp bạn tấn công boss và giảm HP của nó. Trả lời sai bạn sẽ bị boss phản công và trừ máu!')" class="w-10 h-10 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 flex items-center justify-center cursor-pointer transition shadow-sm">
                        <i class="fas fa-question-circle text-base"></i>
                    </button>
                </div>
            </div>

            <!-- 2. BOSS BATTLE ARENA & CONTROLS -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch flex-grow">
                
                <!-- Arena Viewport Column -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    
                    <!-- Battle Arena Card (Main Focus) -->
                    <div id="battle-arena-viewport" class="bg-boss-volcano w-full h-[360px] rounded-[2.5rem] border-4 border-slate-900 shadow-2xl relative overflow-hidden flex flex-col justify-between p-6">
                        <!-- Lava particle glows overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-red-600/10 via-transparent to-transparent pointer-events-none z-0"></div>
                        
                        <!-- Top HUD: Character HP Boards -->
                        <div class="flex items-center justify-between z-20 relative select-none">
                            <!-- Hero HP -->
                            <div class="w-5/12 bg-slate-950/80 border border-slate-800 p-2.5 rounded-2xl flex items-center gap-2.5 backdrop-blur-sm shadow-lg">
                                <div class="w-7 h-7 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-md">
                                    <i class="fas fa-heart text-[10px]"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-center mb-0.5">
                                        <span class="text-[9px] font-black text-slate-300 uppercase">NGƯỜI CHƠI</span>
                                        <span class="text-[10px] font-black text-emerald-400" id="arena-player-hp-val">{{ $maxHP }} / {{ $maxHP }}</span>
                                    </div>
                                    <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                                        <div id="arena-player-hp-bar" class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 w-full transition-all duration-300"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 py-1 bg-red-600 border border-red-500 text-[10px] font-black text-white rounded-lg shadow-md rotate-2">
                                VS
                            </div>

                            <!-- Boss HP -->
                            <div class="w-5/12 bg-slate-950/80 border border-slate-800 p-2.5 rounded-2xl flex items-center gap-2.5 backdrop-blur-sm shadow-lg">
                                <div class="flex-grow text-right">
                                    <div class="flex justify-between items-center mb-0.5">
                                        <span class="text-[10px] font-black text-rose-500" id="arena-boss-hp-val">{{ $maxHP }} / {{ $maxHP }}</span>
                                        <span class="text-[9px] font-black text-slate-300 uppercase">MA VƯƠNG</span>
                                    </div>
                                    <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                                        <div id="arena-boss-hp-bar" class="h-full bg-gradient-to-l from-rose-500 to-red-650 w-full transition-all duration-300 float-right"></div>
                                    </div>
                                </div>
                                <div class="w-7 h-7 rounded-xl bg-rose-600 flex items-center justify-center text-white shrink-0 shadow-md">
                                    <i class="fas fa-skull text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Mid Arena: Battle stage avatars -->
                        <div class="flex-grow flex items-end justify-between relative z-10 px-4 mb-4">
                            <!-- Left: Player -->
                            <div class="flex flex-col items-center">
                                <div id="player-avatar-container" class="relative w-20 h-20 mb-2 flex items-center justify-center transition-all duration-350">
                                    <!-- Avatar picture frame -->
                                    <img src="{{ Auth::user()->AnhDaiDien ? asset('storage/' . Auth::user()->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->UserName).'&background=4F46E5&color=fff' }}" class="w-16 h-16 rounded-full border-4 border-emerald-400 object-cover shadow-2xl bg-indigo-950 relative z-10" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Hero&background=4F46E5&color=fff';">
                                    <div class="absolute inset-0 rounded-full border-2 border-emerald-300 animate-ping opacity-20 z-0"></div>
                                    <!-- Glow base -->
                                    <div class="absolute -bottom-2 w-16 h-2 bg-emerald-500/20 blur-xs rounded-full"></div>
                                </div>
                                <!-- Ground ledge -->
                                <div class="w-24 h-4 bg-slate-800 border border-slate-700 rounded-full shadow-lg relative">
                                    <div class="absolute inset-x-2 top-0 h-0.5 bg-slate-600 rounded-full"></div>
                                </div>
                            </div>

                            <!-- Right: Boss -->
                            <div class="flex flex-col items-center">
                                <div id="boss-sprite-container" class="boss-float relative mb-2 flex items-center justify-center transition-all duration-350">
                                    <!-- Dynamic animated SVG Boss -->
                                    <svg class="drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]" viewBox="0 0 100 100" width="110" height="110">
                                        <!-- Shadow -->
                                        <ellipse cx="50" cy="92" rx="34" ry="5" fill="rgba(0,0,0,0.6)" />
                                        
                                        <!-- Animated wings -->
                                        <g class="wing-left">
                                            <path d="M 45 45 C 5 22 8 -3 3 15 C -2 33 22 53 45 45 Z" fill="#310e3a" opacity="0.85" />
                                        </g>
                                        <g class="wing-right">
                                            <path d="M 55 45 C 95 22 92 -3 97 15 C 102 33 78 53 55 45 Z" fill="#310e3a" opacity="0.85" />
                                        </g>
                                        
                                        <!-- Horns -->
                                        <path d="M 24 35 Q 5 12 19 4 Q 28 12 32 30 Z" fill="#7c3aed" stroke="#5b21b6" stroke-width="2" />
                                        <path d="M 76 35 Q 95 12 81 4 Q 72 12 68 30 Z" fill="#7c3aed" stroke="#5b21b6" stroke-width="2" />
                                        
                                        <!-- Head armor shape -->
                                        <path d="M 26 32 L 74 32 L 80 58 L 50 86 L 20 58 Z" fill="#1e1b4b" stroke="#3c0747" stroke-width="3" />
                                        
                                        <!-- Demonic Eyes -->
                                        <circle cx="39" cy="46" r="4.5" fill="#f43f5e" class="boss-eyes-glow" />
                                        <circle cx="61" cy="46" r="4.5" fill="#f43f5e" class="boss-eyes-glow" />
                                        <path d="M 39 39 Q 50 43 61 39" stroke="#f43f5e" stroke-width="2.5" fill="none" />
                                        
                                        <!-- Core power crystal -->
                                        <polygon points="50,60 56,70 50,80 44,70" fill="#f43f5e" />
                                    </svg>
                                </div>
                                <!-- Boss ground ledge -->
                                <div class="w-28 h-4 bg-purple-950 border border-purple-900 rounded-full shadow-lg relative">
                                    <div class="absolute inset-x-2 top-0 h-0.5 bg-purple-800 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <!-- SCREEN 1: Game Launch Screen -->
                        <div id="screen-launch" class="absolute inset-0 bg-slate-950/90 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center z-40 space-y-5 rounded-[2.2rem]">
                            <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-purple-600 to-pink-500 flex items-center justify-center text-white text-2xl shadow-xl shadow-purple-500/30">
                                <i class="fas fa-dragon animate-bounce"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white uppercase tracking-wider">QUYẾT CHIẾN MA VƯƠNG</h3>
                                <p class="text-slate-400 text-xs max-w-sm mt-1.5 leading-relaxed font-bold">Luyện tập nghe hiểu để tung chiêu hạ gục Ma Vương. Đừng để hết máu trước kẻ địch!</p>
                            </div>
                            <button type="button" id="btn-game-start" class="btn-3d px-8 py-3.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-black text-xs tracking-widest rounded-2xl cursor-pointer border-0 border-b-purple-700 uppercase">
                                Bắt đầu khiêu chiến
                            </button>
                        </div>

                        <!-- SCREEN 2: Defeat Screen -->
                        <div id="screen-defeat" class="absolute inset-0 bg-red-950/95 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center z-40 space-y-4 rounded-[2.2rem] hidden">
                            <div class="w-14 h-14 rounded-full bg-red-900 border-2 border-red-500 text-white flex items-center justify-center text-xl shadow-lg">
                                <i class="fas fa-skull"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-red-550 uppercase tracking-wider">BẠN ĐÃ THẤT BẠI!</h3>
                                <p class="text-slate-450 text-xs max-w-xs mt-1.5 leading-relaxed font-bold">Ma Vương quá mạnh mẽ. Hãy nghe lại thật kỹ các dữ liệu âm thanh và thử lại.</p>
                            </div>
                            <button type="button" id="btn-game-retry" class="btn-3d px-6 py-3 bg-gradient-to-r from-slate-700 to-slate-800 hover:from-slate-600 hover:to-slate-700 text-white font-extrabold text-xs tracking-wider rounded-xl cursor-pointer border-0 border-b-slate-900">
                                <i class="fas fa-redo mr-1"></i> Quyết chiến lại
                            </button>
                        </div>

                        <!-- SCREEN 3: Victory Screen -->
                        <div id="screen-win" class="absolute inset-0 bg-slate-950/90 backdrop-blur-xs flex flex-col items-center justify-center p-6 text-center z-40 space-y-4 rounded-[2.2rem] hidden">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 flex items-center justify-center text-white text-2xl shadow-xl shadow-orange-500/20">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-amber-500 uppercase tracking-wide">CHIẾN THẮNG HUY HOÀNG!</h3>
                                <p class="text-slate-400 text-xs max-w-sm mt-1 leading-relaxed font-bold">Tuyệt vời! Bạn đã trả lời đúng các thử thách nghe và hạ gục Ma Vương thành công.</p>
                                
                                <div class="grid grid-cols-2 gap-3 mt-4 max-w-xs mx-auto">
                                    <div class="bg-purple-950/80 border border-purple-800/40 rounded-xl p-2">
                                        <span class="text-[9px] text-slate-400 font-bold block uppercase">Điểm thưởng</span>
                                        <span class="text-xs font-black text-purple-400">+150 XP</span>
                                    </div>
                                    <div class="bg-amber-950/80 border border-amber-800/40 rounded-xl p-2">
                                        <span class="text-[9px] text-slate-400 font-bold block uppercase">Đánh giá</span>
                                        <span class="text-xs font-black text-amber-400">⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-3d group flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-xs tracking-wider px-8 py-3.5 rounded-2xl border-0 border-b-emerald-700 cursor-pointer shadow-lg uppercase" id="btn-submit-test">
                                <i class="fas fa-crown text-amber-300 group-hover:animate-bounce"></i>
                                Nhận thưởng & Tiếp tục
                            </button>
                        </div>
                    </div>

                    <!-- Audio challenge bar below the arena -->
                    <div id="audio-panel-card" class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-5 rounded-3xl shadow-xl space-y-3 relative overflow-hidden hidden">
                        <div class="absolute -right-8 -bottom-8 w-20 h-20 bg-pink-500/5 rounded-full blur-xl pointer-events-none"></div>
                        
                        <div class="flex justify-between items-center border-b border-indigo-900/40 pb-2">
                            <span class="text-[9px] font-black text-indigo-300 uppercase tracking-widest flex items-center gap-1">
                                <i class="fas fa-volume-up"></i> Bản tin mật mã nghe
                            </span>
                            <span class="text-[9px] font-black text-sky-400" id="audio-playback-speed-lbl">Tốc độ: 1.0x</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Play Audio -->
                            <button type="button" id="btn-sound-play" class="btn-3d w-12 h-12 rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white flex items-center justify-center shadow-lg border-0 border-b-pink-700 cursor-pointer" disabled>
                                <i class="fas fa-play text-sm" id="play-icon"></i>
                            </button>
                            
                            <div class="flex items-center gap-2">
                                <button type="button" id="btn-speed-slow" class="px-2.5 py-1.5 text-[9px] font-black rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-350 cursor-pointer transition select-none">0.8x</button>
                                <button type="button" id="btn-speed-normal" class="px-2.5 py-1.5 text-[9px] font-black rounded-xl bg-indigo-950 border-2 border-purple-500 text-purple-400 cursor-pointer transition select-none">1.0x</button>
                            </div>

                            <!-- Audio Waveform -->
                            <div id="audio-equalizer-hud" class="flex-grow flex items-end justify-center gap-1 h-8 opacity-75">
                                @for($i = 0; $i < 14; $i++)
                                    <div class="w-1 bg-purple-400 rounded-t h-1 transition-all duration-100"></div>
                                @endfor
                            </div>
                        </div>

                        <audio id="global-audio-player" class="hidden"></audio>
                    </div>

                </div>

                <!-- Right Side: Question Statement & Options Cards -->
                <div class="lg:col-span-4 bg-white/80 backdrop-blur border border-white/60 p-5 rounded-[2rem] shadow-sm flex flex-col justify-between min-h-[380px] relative overflow-hidden">
                    <div class="flex-grow flex flex-col justify-between h-full relative">
                        
                        <!-- Question Card -->
                        <div class="space-y-4">
                            <div class="bg-indigo-50/50 border border-indigo-100 p-4 rounded-2xl shadow-inner">
                                <span class="text-[9px] font-black text-purple-600 uppercase tracking-widest block mb-0.5">NỘI DUNG THỬ THÁCH</span>
                                <h5 id="combat-question-text" class="text-xs font-bold text-slate-700 leading-relaxed min-h-[50px] flex items-center">
                                    Nhấp nút bắt đầu trận đấu để triệu hồi quái vật...
                                </h5>
                            </div>

                            <!-- Options List (Injected dynamically) -->
                            <div id="combat-options-grid" class="flex flex-col gap-3">
                                <!-- Option card buttons inserted here -->
                            </div>
                        </div>

                        <!-- Circular Skill Buttons at Bottom -->
                        <div class="border-t border-slate-100 pt-4 mt-4 space-y-3">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Chiêu thức bổ trợ (Skills):</span>
                            
                            <div class="flex items-center gap-3">
                                <!-- Power Strike -->
                                <button type="button" id="btn-power-strike" class="skill-btn btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center shadow-md border-0 border-b-indigo-800 text-sm" title="⚔️ Power Strike: Đánh thẳng vào boss">
                                    <i class="fas fa-gavel"></i>
                                </button>
                                <!-- Double Damage -->
                                <button type="button" id="btn-double-damage" class="skill-btn btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-md border-0 border-b-orange-700 text-sm" title="🔥 Double Damage: Nhân đôi sát thương">
                                    <i class="fas fa-fire"></i>
                                </button>
                                <!-- Replay Audio -->
                                <button type="button" id="btn-power-replay" class="skill-btn btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-sky-450 to-blue-500 text-white flex items-center justify-center shadow-md border-0 border-b-blue-700 text-sm" title="🎧 Replay: Nghe lại audio">
                                    <i class="fas fa-headphones"></i>
                                </button>
                                <!-- Hint -->
                                <button type="button" id="btn-power-hint" class="skill-btn btn-3d w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white flex items-center justify-center shadow-md border-0 border-b-teal-700 text-sm" title="💡 Hint: Nhận gợi ý phương án đúng">
                                    <i class="fas fa-lightbulb"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </form>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', () => {
    const optionsGrid = document.getElementById('combat-options-grid');
    if (!optionsGrid) return;

    // Load question databases
    const questionsData = [
        @foreach($questions as $qIdx => $qData)
            {
                id: "{{ $qData['question']->MaCauHoi }}",
                audioUrl: "{{ $qData['audio'] }}",
                correctOptionId: "{{ $qData['correct_option'] ? $qData['correct_option']->MaPA : '' }}",
                statement: "{{ addslashes($qData['question']->NDCauHoi) }}",
                options: [
                    @foreach($qData['options'] as $option)
                        {
                            id: "{{ $option->MaPA }}",
                            text: "{{ addslashes($option->NDPA) }}"
                        },
                    @endforeach
                ]
            },
        @endforeach
    ];

    // Audio synthesizer helper
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
        synthBeep(880, 0.08, 'triangle');
        setTimeout(() => synthBeep(1200, 0.15, 'sine'), 60);
    };

    const playWrongSound = () => {
        synthBeep(220, 0.3, 'sawtooth');
    };

    const playVictorySound = () => {
        synthBeep(523.25, 0.1, 'sine');
        setTimeout(() => synthBeep(659.25, 0.1, 'sine'), 80);
        setTimeout(() => synthBeep(783.99, 0.1, 'sine'), 160);
        setTimeout(() => synthBeep(1046.50, 0.25, 'sine'), 240);
    };

    // UI binders
    const screenLaunch = document.getElementById('screen-launch');
    const screenDefeat = document.getElementById('screen-defeat');
    const screenWin = document.getElementById('screen-win');
    
    const btnSoundPlay = document.getElementById('btn-sound-play');
    const btnSpeedSlow = document.getElementById('btn-speed-slow');
    const btnSpeedNormal = document.getElementById('btn-speed-normal');
    const audioPlaybackSpeedLbl = document.getElementById('audio-playback-speed-lbl');
    const audioEqualizerHud = document.getElementById('audio-equalizer-hud');
    const equalizerBars = audioEqualizerHud.children;
    
    const progressTextHud = document.getElementById('progress-text-hud');
    const progressBarHud = document.getElementById('progress-bar-hud');
    const statementText = document.getElementById('combat-question-text');
    
    const hudPlayerHp = document.getElementById('hud-player-hp');
    const hudComboCombo = document.getElementById('hud-combo-combo');
    const scoreCounterText = document.getElementById('score-counter-text');
    
    const arenaPlayerHpVal = document.getElementById('arena-player-hp-val');
    const arenaPlayerHpBar = document.getElementById('arena-player-hp-bar');
    const arenaBossHpVal = document.getElementById('arena-boss-hp-val');
    const arenaBossHpBar = document.getElementById('arena-boss-hp-bar');
    
    const gameArenaViewport = document.getElementById('battle-arena-viewport');
    const globalAudioPlayer = document.getElementById('global-audio-player');
    const playIcon = document.getElementById('play-icon');

    const audioPanelCard = document.getElementById('audio-panel-card');

    // Game states
    const maxHP = {{ $maxHP }};
    let activeQuestionIndex = 0;
    let playerHP = maxHP;
    let bossHP = maxHP;
    let comboCount = 0;
    let isGameRunning = false;
    let isAnimating = false;
    let currentSpeed = 1.0;
    let isAudioPlaying = false;
    let doubleDamageActive = false;

    // Reset equalizer
    const resetEqualizer = () => {
        for (let i = 0; i < equalizerBars.length; i++) {
            equalizerBars[i].style.height = '4px';
            equalizerBars[i].classList.remove('equalizer-bar');
        }
    };

    const startEqualizer = () => {
        for (let i = 0; i < equalizerBars.length; i++) {
            equalizerBars[i].classList.add('equalizer-bar');
            equalizerBars[i].style.animationDelay = `${i * 0.07}s`;
        }
    };

    const playCurrentAudio = () => {
        if (!isGameRunning || activeQuestionIndex >= questionsData.length) return;
        initAudioContext();

        const currentQ = questionsData[activeQuestionIndex];
        if (!currentQ.audioUrl) return;

        if (isAudioPlaying) {
            globalAudioPlayer.pause();
            isAudioPlaying = false;
            playIcon.className = 'fas fa-play';
            resetEqualizer();
            return;
        }

        globalAudioPlayer.src = currentQ.audioUrl;
        globalAudioPlayer.playbackRate = currentSpeed;
        globalAudioPlayer.play().then(() => {
            isAudioPlaying = true;
            playIcon.className = 'fas fa-pause';
            startEqualizer();
        }).catch(() => {});
    };

    globalAudioPlayer.addEventListener('ended', () => {
        isAudioPlaying = false;
        playIcon.className = 'fas fa-play';
        resetEqualizer();
    });

    btnSoundPlay.addEventListener('click', playCurrentAudio);

    // Speed controls
    btnSpeedSlow.addEventListener('click', () => {
        currentSpeed = 0.8;
        globalAudioPlayer.playbackRate = currentSpeed;
        audioPlaybackSpeedLbl.innerText = 'Tốc độ: 0.8x';
        btnSpeedSlow.className = 'px-2.5 py-1.5 text-[9px] font-black rounded-xl bg-indigo-950 border-2 border-purple-500 text-purple-400 cursor-pointer select-none';
        btnSpeedNormal.className = 'px-2.5 py-1.5 text-[9px] font-black rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-350 cursor-pointer select-none';
    });

    btnSpeedNormal.addEventListener('click', () => {
        currentSpeed = 1.0;
        globalAudioPlayer.playbackRate = currentSpeed;
        audioPlaybackSpeedLbl.innerText = 'Tốc độ: 1.0x';
        btnSpeedNormal.className = 'px-2.5 py-1.5 text-[9px] font-black rounded-xl bg-indigo-950 border-2 border-purple-500 text-purple-400 cursor-pointer select-none';
        btnSpeedSlow.className = 'px-2.5 py-1.5 text-[9px] font-black rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-350 cursor-pointer select-none';
    });

    const updateStatsDisplay = () => {
        // Top HUD
        hudPlayerHp.innerText = `❤️ HP: ${playerHP}`;
        hudComboCombo.innerText = `🔥 Combo: x${comboCount}`;
        scoreCounterText.innerText = `⭐ ${activeQuestionIndex * 20} XP`;

        const pct = Math.round((activeQuestionIndex / questionsData.length) * 100);
        progressBarHud.style.width = `${pct}%`;
        progressTextHud.innerText = `Câu ${activeQuestionIndex + 1} / ${questionsData.length}`;

        // Arena Player
        arenaPlayerHpVal.innerText = `${playerHP} / ${maxHP}`;
        arenaPlayerHpBar.style.width = `${(playerHP / maxHP) * 100}%`;

        // Arena Boss
        arenaBossHpVal.innerText = `${bossHP} / ${maxHP}`;
        arenaBossHpBar.style.width = `${(bossHP / maxHP) * 100}%`;
    };

    // Load active question details
    const loadCurrentQuestion = () => {
        if (activeQuestionIndex >= questionsData.length) {
            finishBattle();
            return;
        }

        const currentQ = questionsData[activeQuestionIndex];
        statementText.innerText = currentQ.statement;

        // Render option buttons
        optionsGrid.innerHTML = '';
        currentQ.options.forEach(opt => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'custom-combat-option flex items-center p-3 text-start bg-white border-2 border-b-4 border-slate-200 rounded-2xl cursor-pointer active:translate-y-[2px] active:border-b-2 transition-all duration-100 select-none w-full outline-none';
            btn.setAttribute('data-option-id', opt.id);
            btn.innerHTML = `
                <div class="custom-radio-circle w-4.5 h-4.5 rounded-full border-2 border-slate-300 flex items-center justify-center mr-2.5 shrink-0">
                    <div class="w-2.5 h-2.5 rounded-full bg-purple-600 scale-0 transition-transform"></div>
                </div>
                <span class="option-text text-slate-600 font-bold text-xs">${opt.text}</span>
            `;
            optionsGrid.appendChild(btn);
        });

        // Toggle Audio Display
        if (currentQ.audioUrl) {
            audioPanelCard.classList.remove('hidden');
            btnSoundPlay.disabled = false;
        } else {
            audioPanelCard.classList.add('hidden');
            btnSoundPlay.disabled = true;
        }

        // Reset player states
        globalAudioPlayer.pause();
        isAudioPlaying = false;
        playIcon.className = 'fas fa-play';
        resetEqualizer();

        updateStatsDisplay();

        if (currentQ.audioUrl) {
            setTimeout(playCurrentAudio, 400);
        }
    };

    // Handle Option Selection Click
    optionsGrid.addEventListener('click', (e) => {
        if (!isGameRunning || isAnimating) return;
        const btn = e.target.closest('.custom-combat-option');
        if (!btn) return;

        handleCombatAction(btn.getAttribute('data-option-id'));
    });

    const handleCombatAction = (selectedOptionId) => {
        initAudioContext();
        isAnimating = true;

        const currentQ = questionsData[activeQuestionIndex];
        const isCorrect = (selectedOptionId === currentQ.correctOptionId);

        // Store value
        const ansInp = document.getElementById(`ans-${currentQ.id}`);
        if (ansInp) ansInp.value = selectedOptionId;

        // Highlight option button
        const btn = document.querySelector(`.custom-combat-option[data-option-id="${selectedOptionId}"]`);
        if (btn) {
            btn.classList.add(isCorrect ? 'correct-glow' : 'wrong-glow');
            btn.querySelector('.custom-radio-circle div').classList.remove('scale-0');
        }

        if (isCorrect) {
            playCorrectSound();
            comboCount++;

            // Player attacks Boss leap animation
            const playerContainer = document.getElementById('player-avatar-container');
            playerContainer.classList.add('player-leap');

            setTimeout(() => {
                // Slash effect on boss
                const slash = document.createElement('div');
                slash.className = 'sword-slash-overlay';
                slash.style.right = '60px';
                slash.style.top = '40%';
                gameArenaViewport.appendChild(slash);
                setTimeout(() => slash.remove(), 400);

                // Shake boss
                const bossContainer = document.getElementById('boss-sprite-container');
                bossContainer.classList.add('shake-arena');
                setTimeout(() => bossContainer.classList.remove('shake-arena'), 400);

                // Damage number pop
                const dmgPop = document.createElement('div');
                dmgPop.className = 'damage-number-pop';
                
                let damageDealt = 10;
                if (doubleDamageActive) {
                    damageDealt = 20;
                    doubleDamageActive = false;
                    document.getElementById('btn-double-damage').classList.remove('ring-4', 'ring-amber-400');
                }
                dmgPop.innerText = `-${damageDealt} HP`;
                dmgPop.style.right = '90px';
                dmgPop.style.top = '35%';
                gameArenaViewport.appendChild(dmgPop);
                setTimeout(() => dmgPop.remove(), 850);

                bossHP = Math.max(0, bossHP - damageDealt);
                updateStatsDisplay();
            }, 300);

            setTimeout(() => {
                playerContainer.classList.remove('player-leap');
                proceedNextStep();
            }, 900);

        } else {
            playWrongSound();
            comboCount = 0;
            doubleDamageActive = false;
            document.getElementById('btn-double-damage').classList.remove('ring-4', 'ring-amber-400');

            // Boss attacks Player with fireball projectile
            const fireball = document.createElement('div');
            fireball.className = 'projectile-fireball';
            gameArenaViewport.appendChild(fireball);

            setTimeout(() => {
                fireball.remove();

                // Shake player
                const playerContainer = document.getElementById('player-avatar-container');
                playerContainer.classList.add('shake-arena');
                setTimeout(() => playerContainer.classList.remove('shake-arena'), 400);

                // Damage number on Player
                const dmgPop = document.createElement('div');
                dmgPop.className = 'damage-number-pop';
                dmgPop.innerText = '-10 HP';
                dmgPop.style.left = '90px';
                dmgPop.style.top = '40%';
                gameArenaViewport.appendChild(dmgPop);
                setTimeout(() => dmgPop.remove(), 850);

                playerHP = Math.max(0, playerHP - 10);
                updateStatsDisplay();

                if (playerHP <= 0) {
                    finishBattle();
                }
            }, 600);

            setTimeout(() => {
                if (playerHP > 0) {
                    proceedNextStep();
                }
            }, 1250);
        }
    };

    const proceedNextStep = () => {
        activeQuestionIndex++;
        isAnimating = false;
        loadCurrentQuestion();
    };

    // Cooldown skill animations helper
    const triggerSkillCooldown = (btnId) => {
        const btn = document.getElementById(btnId);
        btn.disabled = true;
        btn.style.opacity = '0.5';
        setTimeout(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
        }, 8000);
    };

    // Powerup: Power Strike
    document.getElementById('btn-power-strike').addEventListener('click', () => {
        if (!isGameRunning || isAnimating) return;
        initAudioContext();
        synthBeep(987.77, 0.12, 'sawtooth');

        triggerSkillCooldown('btn-power-strike');

        // Instantly deal 10 damage to boss
        const dmgPop = document.createElement('div');
        dmgPop.className = 'damage-number-pop';
        dmgPop.innerText = '-10 HP';
        dmgPop.style.right = '90px';
        dmgPop.style.top = '35%';
        gameArenaViewport.appendChild(dmgPop);
        setTimeout(() => dmgPop.remove(), 850);

        bossHP = Math.max(0, bossHP - 10);
        updateStatsDisplay();

        if (bossHP <= 0) {
            finishBattle();
        }
    });

    // Powerup: Double Damage
    document.getElementById('btn-double-damage').addEventListener('click', () => {
        if (!isGameRunning || isAnimating) return;
        initAudioContext();
        synthBeep(830.61, 0.08, 'sine');
        
        doubleDamageActive = true;
        document.getElementById('btn-double-damage').classList.add('ring-4', 'ring-amber-400');
        triggerSkillCooldown('btn-double-damage');
    });

    // Powerup: Replay Audio
    document.getElementById('btn-power-replay').addEventListener('click', () => {
        if (!isGameRunning) return;
        isAudioPlaying = false;
        playCurrentAudio();
    });

    // Powerup: Hint correct answer
    document.getElementById('btn-power-hint').addEventListener('click', () => {
        if (!isGameRunning || isAnimating) return;
        initAudioContext();
        synthBeep(659.25, 0.1, 'sine');

        triggerSkillCooldown('btn-power-hint');

        const currentQ = questionsData[activeQuestionIndex];
        const correctOpt = Array.from(optionsGrid.children).find(btn => btn.getAttribute('data-option-id') === currentQ.correctOptionId);
        
        if (correctOpt) {
            correctOpt.classList.add('ring-4', 'ring-emerald-450');
            setTimeout(() => correctOpt.classList.remove('ring-4', 'ring-emerald-450'), 1800);
        }
    });

    const finishBattle = () => {
        isGameRunning = false;
        btnSoundPlay.disabled = true;
        globalAudioPlayer.pause();
        resetEqualizer();

        if (bossHP <= 0 || playerHP > bossHP) {
            screenWin.classList.remove('hidden');
            playVictorySound();
        } else {
            screenDefeat.classList.remove('hidden');
            synthBeep(130, 0.3, 'sawtooth');
        }
    };

    const startGame = () => {
        initAudioContext();

        screenLaunch.classList.add('hidden');
        screenDefeat.classList.add('hidden');
        screenWin.classList.add('hidden');

        activeQuestionIndex = 0;
        playerHP = maxHP;
        bossHP = maxHP;
        comboCount = 0;
        isGameRunning = true;
        isAnimating = false;
        doubleDamageActive = false;
        currentSpeed = 1.0;

        btnSoundPlay.disabled = false;
        audioPlaybackSpeedLbl.innerText = 'Tốc độ: 1.0x';
        
        // Reset skills state
        document.querySelectorAll('.skill-btn').forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.classList.remove('ring-4', 'ring-amber-400');
        });

        // Reset inputs
        questionsData.forEach(q => {
            const ansInp = document.getElementById(`ans-${q.id}`);
            if (ansInp) ansInp.value = '';
        });

        loadCurrentQuestion();
    };

    document.getElementById('btn-game-start').addEventListener('click', startGame);
    document.getElementById('btn-game-retry').addEventListener('click', startGame);
});
</script>
