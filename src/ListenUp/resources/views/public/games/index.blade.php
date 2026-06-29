@extends('layouts.app')
@section('title', 'Bản đồ Trò chơi')
@section('content')
<div class="w-full max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
    
    <!-- Top HUD Bar: Score Badge & Leaderboard Button -->
    <div class="flex items-center justify-between w-full mb-6 relative z-30">
        <!-- Personal Score HUD -->
        @if($user)
            <div class="flex items-center gap-2.5 bg-white/70 backdrop-blur-md border border-white/60 px-4 py-2 rounded-full shadow-lg shadow-sky-900/5 hover:scale-102 transition-transform duration-300">
                <div class="w-8 h-8 rounded-full bg-amber-400 flex items-center justify-center text-white text-sm shadow-inner">
                    <i class="fas fa-star animate-pulse"></i>
                </div>
                <div class="pr-2">
                    <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-500 block leading-none">Điểm của bạn</span>
                    <span class="text-sm font-display font-black text-slate-800 leading-none mt-0.5 block">
                        {{ number_format($user->DiemMan) }} XP
                    </span>
                </div>
            </div>
        @else
            <div></div>
        @endif

        <!-- Floating Leaderboard Toggle Button -->
        <button onclick="toggleLeaderboard()" class="w-12 h-12 rounded-full bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-white shadow-lg shadow-yellow-500/20 hover:shadow-yellow-500/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center border-2 border-white/80 group relative">
            <i class="fas fa-trophy text-xl group-hover:scale-110 transition-transform"></i>
            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                <span class="w-1 h-1 bg-white rounded-full animate-ping"></span>
            </span>
        </button>
    </div>

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/35 text-red-800 rounded-2xl p-4 mb-6 max-w-2xl mx-auto flex items-center gap-3 backdrop-blur-md" role="alert">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            <p class="font-semibold text-sm">{{ session('error') }}</p>
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/35 text-green-800 rounded-2xl p-4 mb-6 max-w-2xl mx-auto flex items-center gap-3 backdrop-blur-md" role="alert">
            <i class="fas fa-check-circle text-green-600 text-lg"></i>
            <p class="font-semibold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @php
        // Spacious, non-overlapping snake layout: low-point (index 0) at top-left, high-point (index 9) at bottom-right
        $positions = [
            ['left' => '15%', 'top' => '15%'],   // 0. Top-Left
            ['left' => '45%', 'top' => '17%'],   // 1. Top-Center
            ['left' => '80%', 'top' => '15%'],   // 2. Top-Right
            ['left' => '75%', 'top' => '50%'],   // 3. Mid-Right
            ['left' => '50%', 'top' => '42%'],   // 4. Center
            ['left' => '20%', 'top' => '52%'],   // 5. Mid-Left
            ['left' => '10%', 'top' => '80%'],   // 6. Bottom-Left
            ['left' => '38%', 'top' => '85%'],   // 7. Bottom-Center-Left
            ['left' => '62%', 'top' => '72%'],   // 8. Bottom-Center-Right
            ['left' => '85%', 'top' => '85%'],   // 9. Bottom-Right
        ];
    @endphp

    <div class="relative w-full">
        <!-- Desktop Map Layout: Increased height and width for larger islands -->
        <div class="hidden lg:block relative w-full h-[800px] overflow-hidden max-w-[1400px] mx-auto">
            <!-- SVG Canvas for desktop bridges -->
            <svg id="desktop-bridges" class="absolute inset-0 w-full h-full pointer-events-none z-10"></svg>
            
            <!-- Absolute positioned desktop nodes -->
            @foreach($games as $game)
                @php
                    $isLocked = false;
                    if (!$user) {
                        $isLocked = true;
                    } else {
                        if ($game->YeuCauBanDo !== null && $game->YeuCauBanDo !== '' && !$loop->first) {
                            $requiredScore = (int) $game->YeuCauBanDo;
                            if ($user->DiemMan < $requiredScore) {
                                $isLocked = true;
                            }
                        }
                    }
                @endphp
                
                <div class="desktop-island-node absolute group/island" style="left: {{ $positions[$loop->index]['left'] }}; top: {{ $positions[$loop->index]['top'] }}; transform: translate(-50%, -50%);" data-index="{{ $loop->index }}">
                    <div class="relative flex flex-col items-center select-none" style="width: 240px;">
                        @if(!$isLocked)
                            <div class="absolute -top-4 w-48 h-48 bg-amber-400/25 rounded-full blur-2xl opacity-0 group-hover/island:opacity-100 transition-opacity duration-500 -z-10 animate-pulse"></div>
                        @endif
                        
                        <!-- Floats locked or unlocked in sync -->
                        <div class="w-56 h-36 relative z-20 animate-float-island" style="animation-delay: {{ $loop->index * 0.4 }}s;">
                            @if($isLocked)
                                <!-- Black silhouette with question mark for locked map -->
                                <div class="absolute inset-0 flex items-center justify-center z-30 pb-2">
                                    <span class="text-white font-display font-black text-6xl drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] opacity-90">?</span>
                                </div>
                            @endif
                            
                            @if($game->HinhAnh)
                                @if($isLocked)
                                    <img src="{{ asset('images/' . $game->HinhAnh) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $game->HinhAnh) }}';" alt="{{ $game->TenBanDo }}" class="w-full h-full object-contain object-bottom filter drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)] brightness-0 opacity-90">
                                @else
                                    <a href="{{ route('user.games.play', $game->MaBanDo) }}" class="block w-full h-full">
                                        <img src="{{ asset('images/' . $game->HinhAnh) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $game->HinhAnh) }}';" alt="{{ $game->TenBanDo }}" class="w-full h-full object-contain object-bottom filter drop-shadow-[0_12px_12px_rgba(0,0,0,0.6)] hover:scale-110 hover:-translate-y-1 transition-all duration-300">
                                    </a>
                                @endif
                            @else
                                <div class="w-28 h-28 mx-auto rounded-[2rem] flex items-center justify-center text-5xl shadow-xl relative z-20 transition-all duration-500 {{ $isLocked ? 'bg-slate-900 text-slate-700 border border-slate-800' : 'bg-gradient-to-br from-violet-600 via-indigo-600 to-purple-600 text-amber-300 border border-violet-400/30' }}">
                                    @if($isLocked)
                                        <i class="fas fa-lock opacity-30 hidden"></i>
                                    @else
                                        <a href="{{ route('user.games.play', $game->MaBanDo) }}" class="flex items-center justify-center w-full h-full text-amber-300">
                                            <i class="fas fa-map-marked-alt drop-shadow-[0_4px_8px_rgba(0,0,0,0.3)]"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <!-- Floating shadow -->
                        <div class="w-32 h-2 bg-black/20 rounded-full blur-[2px] mt-1 animate-island-shadow" style="animation-delay: {{ $loop->index * 0.4 }}s;"></div>
                        
                        <div class="mt-0.5 relative z-30 flex flex-col items-center">
                            <span class="text-sm font-display font-black text-slate-800 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full border border-slate-200/50 shadow-md group-hover/island:bg-violet-600 group-hover/island:text-white group-hover/island:border-violet-500 transition-all duration-300 text-center truncate max-w-[200px]">
                                {{ $game->TenBanDo }}
                            </span>
                            @if($isLocked)
                                <span class="mt-1 text-xs font-extrabold px-3 py-0.5 bg-slate-900/80 text-amber-400 border border-amber-500/20 rounded-full flex items-center gap-1 shadow-sm">
                                    <i class="fas fa-lock text-[10px]"></i> {{ number_format($game->YeuCauBanDo) }} XP
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Layout: Vertical Zigzag -->
        <div class="block lg:hidden relative w-full py-16 px-4 overflow-hidden">
            <!-- SVG Canvas for mobile bridges -->
            <svg id="mobile-bridges" class="absolute inset-0 w-full h-full pointer-events-none z-10"></svg>
            
            <!-- Mobile nodes -->
            <div class="relative z-20 flex flex-col gap-32">
                @foreach($games as $game)
                    @php
                        $isLocked = false;
                        if (!$user) {
                            $isLocked = true;
                        } else {
                            if ($game->YeuCauBanDo !== null && $game->YeuCauBanDo !== '' && !$loop->first) {
                                $requiredScore = (int) $game->YeuCauBanDo;
                                if ($user->DiemMan < $requiredScore) {
                                    $isLocked = true;
                                }
                            }
                        }
                        
                        // Zigzag: left, right
                        $alignClass = 'justify-start ml-[5%]';
                        if ($loop->index % 2 == 1) {
                            $alignClass = 'justify-end mr-[5%]';
                        }
                    @endphp
                    
                    <div class="flex {{ $alignClass }}">
                        <div class="mobile-island-node group/island relative flex flex-col items-center select-none" style="width: 180px;" data-index="{{ $loop->index }}">
                            @if(!$isLocked)
                                <div class="absolute -top-4 w-40 h-40 bg-amber-400/25 rounded-full blur-2xl opacity-0 group-hover/island:opacity-100 transition-opacity duration-500 -z-10 animate-pulse"></div>
                            @endif
                            
                            <div class="w-40 h-24 relative z-20 animate-float-island" style="animation-delay: {{ $loop->index * 0.4 }}s;">
                                @if($isLocked)
                                    <!-- Black silhouette with question mark for locked map -->
                                    <div class="absolute inset-0 flex items-center justify-center z-30 pb-2">
                                        <span class="text-white font-display font-black text-4xl drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] opacity-90">?</span>
                                    </div>
                                @endif
                                
                                @if($game->HinhAnh)
                                    @if($isLocked)
                                        <img src="{{ asset('images/' . $game->HinhAnh) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $game->HinhAnh) }}';" alt="{{ $game->TenBanDo }}" class="w-full h-full object-contain object-bottom filter drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)] brightness-0 opacity-90">
                                    @else
                                        <a href="{{ route('user.games.play', $game->MaBanDo) }}" class="block w-full h-full">
                                            <img src="{{ asset('images/' . $game->HinhAnh) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $game->HinhAnh) }}';" alt="{{ $game->TenBanDo }}" class="w-full h-full object-contain object-bottom filter drop-shadow-[0_12px_12px_rgba(0,0,0,0.6)] hover:scale-110 hover:-translate-y-1 transition-all duration-300">
                                        </a>
                                    @endif
                                @else
                                    <div class="w-20 h-20 mx-auto rounded-[1.5rem] flex items-center justify-center text-4xl shadow-xl relative z-20 transition-all duration-500 {{ $isLocked ? 'bg-slate-900 text-slate-700 border border-slate-800' : 'bg-gradient-to-br from-violet-600 via-indigo-600 to-purple-600 text-amber-300 border border-violet-400/30' }}">
                                        @if($isLocked)
                                            <i class="fas fa-lock opacity-30 hidden"></i>
                                        @else
                                            <a href="{{ route('user.games.play', $game->MaBanDo) }}" class="flex items-center justify-center w-full h-full text-amber-300">
                                                <i class="fas fa-map-marked-alt drop-shadow-[0_4px_8px_rgba(0,0,0,0.3)]"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <div class="w-20 h-1.5 bg-black/20 rounded-full blur-[2px] mt-0.5 animate-island-shadow" style="animation-delay: {{ $loop->index * 0.4 }}s;"></div>
                            
                            <div class="mt-0.5 relative z-30 flex flex-col items-center">
                                <span class="text-xs font-display font-black text-slate-800 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full border border-slate-200/50 shadow-md group-hover/island:bg-violet-600 group-hover/island:text-white transition-all duration-300 text-center truncate max-w-[150px]">
                                    {{ $game->TenBanDo }}
                                </span>
                                @if($isLocked)
                                    <span class="mt-1 text-[10px] font-extrabold px-2.5 py-0.5 bg-slate-900/80 text-amber-400 border border-amber-500/20 rounded-full flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-lock text-[8px]"></i> {{ number_format($game->YeuCauBanDo) }} XP
                                    </span>
                                @else
                                    <span class="mt-1 text-[10px] font-extrabold px-2.5 py-0.5 bg-emerald-500 text-white rounded-full flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-play text-[8px]"></i> Khám phá
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Leaderboard Modal -->
<div id="leaderboard-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden opacity-0 transition-all duration-300">
    <svg width="0" height="0" class="absolute">
        <defs>
            <linearGradient id="goldGradSmall" x1="0" y1="0" x2="0" y2="24" gradientUnits="userSpaceOnUse">
                <stop stop-color="#FEF08A"/>
                <stop offset="0.4" stop-color="#EAB308"/>
                <stop offset="1" stop-color="#854D0E"/>
            </linearGradient>
            <linearGradient id="silverGradSmall" x1="0" y1="0" x2="0" y2="24" gradientUnits="userSpaceOnUse">
                <stop stop-color="#F3F4F6"/>
                <stop offset="0.5" stop-color="#9CA3AF"/>
                <stop offset="1" stop-color="#4B5563"/>
            </linearGradient>
            <linearGradient id="bronzeGradSmall" x1="0" y1="0" x2="0" y2="24" gradientUnits="userSpaceOnUse">
                <stop stop-color="#FFEDD5"/>
                <stop offset="0.5" stop-color="#F97316"/>
                <stop offset="1" stop-color="#7C2D12"/>
            </linearGradient>
        </defs>
    </svg>

    <div class="bg-slate-900/90 border border-slate-800 rounded-[2.5rem] w-full max-w-md p-6 shadow-2xl relative transform scale-95 transition-all duration-300" id="leaderboard-modal-content">
        <!-- Close Button -->
        <button onclick="toggleLeaderboard()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors w-10 h-10 rounded-full bg-slate-800/50 flex items-center justify-center border border-slate-700/30">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 text-2xl">
                <i class="fas fa-trophy animate-bounce"></i>
            </div>
            <div>
                <h3 class="font-display font-black text-2xl text-white">Bảng Xếp Hạng</h3>
                <p class="text-xs text-slate-400 font-medium">Top 10 Cao Thủ Phiêu Lưu</p>
            </div>
        </div>

        <div class="space-y-2.5 max-h-[450px] overflow-y-auto pr-2 custom-scrollbar">
            @if(isset($gameRankings) && $gameRankings->count() > 0)
                @foreach($gameRankings as $index => $rankUser)
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-800/30 border border-slate-800/50 hover:bg-slate-800/60 hover:border-slate-700 transition">
                    <!-- Rank badge -->
                    @if($index == 0)
                        <div class="relative flex items-center justify-center flex-shrink-0 w-10 h-10">
                            <div class="absolute -inset-1 bg-yellow-500 opacity-40 blur-sm rounded-full animate-pulse"></div>
                            <svg class="w-9 h-9 drop-shadow-md relative" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="url(#goldGradSmall)" stroke="#FFFBEB" stroke-width="1" stroke-linejoin="round"/>
                                <text x="12" y="15.5" font-size="10" font-weight="900" fill="#FFFFFF" text-anchor="middle" font-family="Arial, sans-serif" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">1</text>
                            </svg>
                        </div>
                    @elseif($index == 1)
                        <div class="relative flex items-center justify-center flex-shrink-0 w-9 h-9 mx-0.5">
                            <svg class="w-8 h-8 drop-shadow-md relative" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="url(#silverGradSmall)" stroke="#FFFFFF" stroke-width="1" stroke-linejoin="round"/>
                                <text x="12" y="15" font-size="10" font-weight="900" fill="#1F2937" text-anchor="middle" font-family="Arial, sans-serif" style="text-shadow: 1px 1px 2px rgba(255,255,255,0.7);">2</text>
                            </svg>
                        </div>
                    @elseif($index == 2)
                        <div class="relative flex items-center justify-center flex-shrink-0 w-8 h-8 mx-1">
                            <svg class="w-7 h-7 drop-shadow-md relative" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="url(#bronzeGradSmall)" stroke="#FFEDD5" stroke-width="1" stroke-linejoin="round"/>
                                <text x="12" y="14.5" font-size="9" font-weight="900" fill="#FFFFFF" text-anchor="middle" font-family="Arial, sans-serif" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.6);">3</text>
                            </svg>
                        </div>
                    @else
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm bg-slate-800 text-slate-400 shrink-0 mx-1">
                            {{ $index + 1 }}
                        </div>
                    @endif
                    
                    @if($rankUser->Vien)
                        <div class="relative w-14 h-14 flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/' . $rankUser->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rankUser->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full">
                            <img src="{{ $rankUser->AnhDaiDien ? asset('storage/' . $rankUser->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rankUser->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                        </div>
                    @else
                        <img src="{{ $rankUser->AnhDaiDien ? asset('storage/' . $rankUser->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rankUser->UserName).'&background=random&color=fff' }}" class="w-11 h-11 rounded-full object-cover shrink-0 border border-slate-700" alt="Avatar">
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm text-slate-200 truncate">{{ $rankUser->UserName }}</div>
                        <div class="text-xs text-amber-400 font-semibold mt-0.5 flex items-center gap-1">
                            <i class="fas fa-star text-[10px]"></i> {{ number_format($rankUser->DiemMan) }} XP
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-sm text-slate-500 text-center py-8">Chưa có dữ liệu xếp hạng.</div>
            @endif
        </div>
    </div>
<!-- Reward / Frame Unlock Congratulations Modal -->
@if(session('unlocked_frame'))
<div id="reward-unlock-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md animate-fade-in">
    <!-- Glowing backgrounds -->
    <div class="absolute w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute w-80 h-80 bg-amber-400/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    
    <div class="bg-gradient-to-b from-[#1a193d] via-[#100e26] to-[#090818] border-4 border-amber-400 rounded-[3rem] w-full max-w-lg p-8 shadow-[0_0_50px_rgba(234,179,8,0.3)] text-center relative overflow-hidden transform scale-100 transition-all duration-300">
        <!-- Confetti/Stars decoration -->
        <div class="absolute -top-10 -left-10 w-24 h-24 bg-pink-500/10 rounded-full blur-xl"></div>
        <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-cyan-500/10 rounded-full blur-xl"></div>

        <!-- Animated sparkles -->
        <div class="absolute top-10 left-10 text-amber-300 animate-pulse"><i class="fas fa-sparkles text-xl"></i></div>
        <div class="absolute bottom-16 right-12 text-pink-400 animate-pulse" style="animation-delay: 500ms;"><i class="fas fa-star text-lg"></i></div>

        <!-- Glowing Crown Header -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-amber-400 via-yellow-300 to-orange-500 flex items-center justify-center text-white text-4xl shadow-xl shadow-amber-500/40 relative animate-bounce" style="animation-duration: 2.5s;">
                <i class="fas fa-trophy"></i>
                <div class="absolute -inset-1 rounded-full border border-amber-300 animate-ping opacity-50"></div>
            </div>
        </div>

        <!-- Congratulations Title -->
        <h2 class="text-3xl font-display font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-orange-500 mb-2 tracking-wide uppercase">
            MỞ KHÓA THÀNH CÔNG!
        </h2>
        <p class="text-slate-300 text-sm font-semibold mb-6 uppercase tracking-wider">
            Chúc mừng bạn đã mở khóa thành công <br>
            <span class="text-amber-400 font-extrabold text-lg">Map {{ session('unlocked_map_number') }}: {{ session('unlocked_map_name') }}</span><br>
            và đạt được phần quà đặc biệt!
        </p>

        <!-- Unlocked Gift Display Card -->
        <div class="bg-slate-950/60 border border-slate-800 rounded-3xl p-6 mb-8 relative flex flex-col items-center justify-center">
            <!-- Neon glow ring -->
            <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-purple-600 rounded-3xl opacity-30 blur"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <!-- Avatar Preview with Frame -->
                <div class="relative w-36 h-36 flex items-center justify-center mb-4 hover:scale-105 transition-transform duration-300">
                    <!-- Frame Image -->
                    <img src="{{ asset('images/' . session('unlocked_frame')) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . session('unlocked_frame')) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-[0_4px_12px_rgba(234,179,8,0.5)]">
                    <!-- User Avatar -->
                    <img src="{{ Auth::user()->AnhDaiDien ? asset('storage/' . Auth::user()->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->UserName).'&background=0D8ABC&color=fff' }}" class="w-[72%] h-[72%] rounded-full object-cover z-0 shadow-inner">
                </div>
                
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Phần quà độc quyền</span>
                <span class="text-base font-extrabold text-white">Viền Avatar Map {{ session('unlocked_map_number') }}</span>
                <span class="text-[10px] font-bold text-emerald-400 mt-1 uppercase tracking-wider flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Đã tự động trang bị cho tài khoản
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3 relative z-10">
            <button onclick="closeRewardModal()" class="w-full py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 text-slate-950 font-black text-base tracking-wider shadow-lg shadow-orange-500/20 active:translate-y-[2px] transition-all cursor-pointer border border-amber-300/30">
                TIẾP TỤC HÀNH TRÌNH
            </button>
        </div>
    </div>
</div>

<script>
    function closeRewardModal() {
        const modal = document.getElementById('reward-unlock-modal');
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transition = 'opacity 0.4s ease-out';
            setTimeout(() => {
                modal.remove();
            }, 400);
        }
    }
</script>
@endif

@endsection

@push('styles')
<style>
    /* Full screen cartoon sky background */
    body {
        background-color: #bae6fd !important;
        background-image: url('{{ asset('images/sky_bg.png') }}') !important;
        background-size: cover !important;
        background-position: center bottom !important;
        background-repeat: no-repeat !important;
        background-attachment: fixed !important;
        color: #0f172a !important;
        overflow-x: hidden;
        position: relative;
    }

    .main-content-wrapper {
        background: transparent !important;
    }

    /* Floating Map Animations */
    @keyframes float-island {
        0% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-12px) rotate(0.5deg);
        }
        100% {
            transform: translateY(0px) rotate(0deg);
        }
    }
    
    @keyframes island-shadow {
        0% {
            transform: scale(1);
            opacity: 0.25;
        }
        50% {
            transform: scale(0.78);
            opacity: 0.12;
        }
        100% {
            transform: scale(1);
            opacity: 0.25;
        }
    }
    
    .animate-float-island {
        animation: float-island 4.8s ease-in-out infinite;
    }
    
    .animate-island-shadow {
        animation: island-shadow 4.8s ease-in-out infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    // Draw SVG bridges connecting islands, shortened to connect only the visual edges
    function drawBridges(svgId, nodeClass) {
        const svg = document.getElementById(svgId);
        if (!svg) return;
        svg.innerHTML = '';
        
        const nodes = Array.from(document.querySelectorAll(nodeClass))
                           .sort((a, b) => parseInt(a.dataset.index) - parseInt(b.dataset.index));
                           
        const containerRect = svg.getBoundingClientRect();
        if (containerRect.width === 0 || containerRect.height === 0) return;
        
        for (let i = 0; i < nodes.length - 1; i++) {
            const current = nodes[i];
            const next = nodes[i+1];
            
            const currentRect = current.getBoundingClientRect();
            const nextRect = next.getBoundingClientRect();
            
            // Central coordinates of nodes relative to the SVG container
            const x1 = (currentRect.left + currentRect.width / 2) - containerRect.left;
            const y1 = (currentRect.top + currentRect.height / 2) - containerRect.top;
            const x2 = (nextRect.left + nextRect.width / 2) - containerRect.left;
            const y2 = (nextRect.top + nextRect.height / 2) - containerRect.top;
            
            const dx = x2 - x1;
            const dy = y2 - y1;
            const dist = Math.sqrt(dx*dx + dy*dy);
            
            if (dist < 100) continue; // too close, skip drawing
            
            // Unit direction vector
            const ux = dx / dist;
            const uy = dy / dist;
            
            // Radius of the island offset (in pixels) to shorten the bridges
            const R = 95; // Since islands are enlarged to 240px wide (radius ~ 95px)
            
            // New bridge endpoints at island edges
            const startX = x1 + ux * R;
            const startY = y1 + uy * R;
            const endX = x2 - ux * R;
            const endY = y2 - uy * R;
            
            const newDx = endX - startX;
            const newDy = endY - startY;
            const newDist = Math.sqrt(newDx*newDx + newDy*newDy);
            
            // Perpendicular Normal Vector for handrail offsets
            const nx = -newDy / newDist;
            const ny = newDx / newDist;
            
            // Shortened bridge sag factor based on new distance
            const sag = Math.min(newDist * 0.08, 24);
            
            const mx = (startX + endX) / 2;
            const my = (startY + endY) / 2 + sag;
            
            // Left handrail
            const rail1 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            rail1.setAttribute('d', `M ${startX - nx * 8} ${startY - ny * 8} Q ${mx - nx * 8} ${my - ny * 8} ${endX - nx * 8} ${endY - ny * 8}`);
            rail1.setAttribute('stroke', '#4b2505');
            rail1.setAttribute('stroke-width', '2');
            rail1.setAttribute('fill', 'none');
            svg.appendChild(rail1);

            // Right handrail
            const rail2 = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            rail2.setAttribute('d', `M ${startX + nx * 8} ${startY + ny * 8} Q ${mx + nx * 8} ${my + ny * 8} ${endX + nx * 8} ${endY + ny * 8}`);
            rail2.setAttribute('stroke', '#4b2505');
            rail2.setAttribute('stroke-width', '2');
            rail2.setAttribute('fill', 'none');
            svg.appendChild(rail2);

            // Main support rope (center base)
            const base = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            base.setAttribute('d', `M ${startX} ${startY} Q ${mx} ${my} ${endX} ${endY}`);
            base.setAttribute('stroke', '#78350f');
            base.setAttribute('stroke-width', '5');
            base.setAttribute('fill', 'none');
            svg.appendChild(base);

            // Wooden steps / planks (using dashed array)
            const planks = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            planks.setAttribute('d', `M ${startX} ${startY} Q ${mx} ${my} ${endX} ${endY}`);
            planks.setAttribute('stroke', '#d97706');
            planks.setAttribute('stroke-width', '12');
            planks.setAttribute('stroke-dasharray', '4, 12');
            planks.setAttribute('fill', 'none');
            svg.appendChild(planks);

            // Binding rope highlights
            const ropeBinds = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            ropeBinds.setAttribute('d', `M ${startX} ${startY} Q ${mx} ${my} ${endX} ${endY}`);
            ropeBinds.setAttribute('stroke', '#f59e0b');
            ropeBinds.setAttribute('stroke-width', '8');
            ropeBinds.setAttribute('stroke-dasharray', '1, 15');
            ropeBinds.setAttribute('fill', 'none');
            svg.appendChild(ropeBinds);
        }
    }

    // Modal Leaderboard toggler
    function toggleLeaderboard() {
        const modal = document.getElementById('leaderboard-modal');
        const content = document.getElementById('leaderboard-modal-content');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        } else {
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    // Initialize map drawing on document loaded
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            drawBridges('desktop-bridges', '.desktop-island-node');
            drawBridges('mobile-bridges', '.mobile-island-node');
        }, 150);
    });

    window.addEventListener('load', () => {
        drawBridges('desktop-bridges', '.desktop-island-node');
        drawBridges('mobile-bridges', '.mobile-island-node');
    });

    window.addEventListener('resize', () => {
        drawBridges('desktop-bridges', '.desktop-island-node');
        drawBridges('mobile-bridges', '.mobile-island-node');
    });
</script>
@endpush
