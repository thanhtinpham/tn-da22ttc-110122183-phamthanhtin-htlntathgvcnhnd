@extends('layouts.app')
@section('title', 'Bảng vàng danh dự - ListenUp')

@push('styles')
<style>
    .podium-pillar {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .podium-group:hover .podium-pillar {
        opacity: 0.9;
    }
    .podium-group:hover .podium-pillar:hover {
        transform: translateY(-5px);
        opacity: 1;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
    }
    .leaderboard-row {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .leaderboard-row:hover {
        transform: translateX(4px);
    }
    
    /* Glowing Effects */
    .gold-glow {
        box-shadow: 0 10px 30px -5px rgba(245, 158, 11, 0.15), 0 0 20px rgba(245, 158, 11, 0.05);
    }
    .gold-glow:hover {
        box-shadow: 0 20px 40px -5px rgba(245, 158, 11, 0.3), 0 0 30px rgba(245, 158, 11, 0.2);
    }
    
    .mesh-bg {
        background-image: radial-gradient(rgba(11, 27, 61, 0.04) 1.5px, transparent 1.5px);
        background-size: 24px 24px;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }
    .animate-float-gold {
        animation: float 4s ease-in-out infinite;
    }
    
    /* Custom thin scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(203, 213, 225, 0.8);
        border-radius: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.8);
    }
    .rank-number {
        font-family: 'Outfit', sans-serif;
    }
</style>
@endpush

@section('content')
<!-- SEO Heading -->
<h1 class="sr-only">Bảng Xếp Hạng ListenUp - Vinh Danh Học Viên và Nhà Thám Hiểm Xuất Sắc Nhất</h1>

<div class="mesh-bg min-h-screen py-10 relative overflow-hidden">
    <!-- Decorative Ambient Blobs -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute top-[20%] right-1/4 w-[550px] h-[550px] bg-secondary/5 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-[1550px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Premium Hero Header -->
        <div class="text-center mb-10 relative mt-4">
            <h2 class="text-2xl md:text-4xl lg:text-4xl font-extrabold font-display text-[var(--text-primary)] mb-4 tracking-tight">
                <span class="bg-gradient-to-r from-amber-500 via-purple-600 to-[var(--brand-secondary)] bg-clip-text text-transparent">Bảng Vàng Danh Dự</span>
            </h2>
        </div>

        <!-- Main Split Dashboard Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-10 items-stretch">
            
            <!-- LEFT PANEL: HỌC VIÊN XUẤT SẮC -->
            <div class="bg-gradient-to-b from-amber-200 via-purple-100 dark:from-amber-950/30 dark:via-purple-950/20 dark:to-[var(--brand-card-bg)] backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 md:p-8 shadow-[0_15px_40px_-15px_rgba(11,27,61,0.05)] flex flex-col justify-between h-full">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-xl font-extrabold text-[var(--text-primary)] flex items-center gap-2">
                                <i class="fas fa-user-graduate text-purple-600 dark:text-purple-400 text-lg"></i> Học Viên Xuất Sắc
                            </h3>
                            <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">Xếp hạng theo tổng điểm bài kiểm tra tích lũy</p>
                        </div>
                        <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-full text-[10px] font-bold uppercase tracking-wider">Top 10</span>
                    </div>

                    <div class="flex items-end justify-center w-full mt-12 mb-8 max-w-md mx-auto">
                        <!-- TOP 2 (SILVER) -->
                        <div class="flex-1 flex flex-col items-center">
                            @if(count($studentRankings) >= 2)
                                @php $rank2 = $studentRankings[1]; @endphp
                                <div class="flex flex-col items-center mb-2 z-10 transition-transform duration-300 hover:-translate-y-1 w-full text-center">
                                    @if($rank2->Vien)
                                        <div class="relative w-16 h-16 flex items-center justify-center shrink-0 mx-auto">
                                            <img src="{{ asset('images/' . $rank2->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank2->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                            <img src="{{ $rank2->AnhDaiDien ? asset('storage/' . $rank2->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank2->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                    @else
                                        <div class="relative w-13 h-13 rounded-full border-2 border-slate-300 shadow-md flex items-center justify-center overflow-hidden bg-white mx-auto">
                                            @if($rank2->AnhDaiDien)
                                                <img src="{{ asset('storage/' . $rank2->AnhDaiDien) }}" 
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                     alt="Avatar" class="w-full h-full object-cover">
                                                <div class="hidden w-full h-full bg-gradient-to-br from-slate-400 to-slate-500 text-white items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank2->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank2->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <h4 class="font-extrabold text-slate-700 dark:text-[var(--text-primary)] text-[11px] truncate w-full mt-1.5 px-1">{{ $rank2->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <!-- Silver pedestal column -->
                                <div class="w-full h-20 bg-gradient-to-b from-slate-100 to-slate-200/90 dark:from-slate-800 dark:to-slate-900/90 rounded-t-xl border border-b-0 border-slate-250 dark:border-slate-750 shadow-inner flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-slate-300/10"></div>
                                    <span class="text-4xl font-black text-slate-400/50 dark:text-slate-600/50 rank-number">2</span>
                                    <div class="text-[10px] font-extrabold text-slate-600 dark:text-slate-200 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full mt-1 shadow-sm border border-slate-200/50 dark:border-slate-700">
                                        {{ number_format($rank2->TongDiem) }} <i class="fas fa-star text-amber-400 text-[8px]"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Empty Seat -->
                                <div class="flex flex-col items-center mb-2 opacity-40 w-full text-center">
                                    <div class="w-10 h-10 rounded-full border border-dashed border-slate-300 flex items-center justify-center bg-slate-55 mx-auto">
                                        <i class="fas fa-user text-slate-400 text-xs"></i>
                                    </div>
                                    <span class="font-bold text-[10px] text-slate-400 mt-1">Đang chờ</span>
                                </div>
                                <div class="w-full h-20 bg-gradient-to-b from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 rounded-t-xl border border-b-0 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center relative overflow-hidden opacity-50">
                                    <span class="text-3xl font-black text-slate-300/40 dark:text-slate-700/40 rank-number">2</span>
                                </div>
                            @endif
                        </div>

                        <!-- TOP 1 (GOLD) -->
                        <div class="flex-1 flex flex-col items-center -mx-1 z-10">
                            @if(count($studentRankings) >= 1)
                                @php $rank1 = $studentRankings[0]; @endphp
                                <div class="flex flex-col items-center mb-2 w-full text-center">
                                    <div class="relative">
                                        <i class="fas fa-crown text-amber-500 absolute -top-5 left-1/2 -translate-x-1/2 text-lg animate-bounce" style="animation-duration: 3s"></i>
                                        <div class="absolute inset-0 bg-amber-400 rounded-full blur-md opacity-25 animate-pulse"></div>
                                        @if($rank1->Vien)
                                            <div class="relative w-20 h-20 flex items-center justify-center shrink-0 mx-auto">
                                                <img src="{{ asset('images/' . $rank1->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank1->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                                <img src="{{ $rank1->AnhDaiDien ? asset('storage/' . $rank1->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank1->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                            </div>
                                        @else
                                            <div class="relative w-16 h-16 rounded-full border-3 border-amber-500 shadow-lg flex items-center justify-center overflow-hidden bg-white mx-auto">
                                                @if($rank1->AnhDaiDien)
                                                    <img src="{{ asset('storage/' . $rank1->AnhDaiDien) }}" 
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                         alt="Avatar" class="w-full h-full object-cover">
                                                    <div class="hidden w-full h-full bg-gradient-to-br from-amber-400 to-yellow-500 text-white items-center justify-center font-bold text-base">
                                                        {{ strtoupper(substr($rank1->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-amber-400 to-yellow-500 text-white flex items-center justify-center font-bold text-base">
                                                        {{ strtoupper(substr($rank1->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="font-extrabold text-[var(--brand-secondary)] dark:text-[var(--text-primary)] text-xs truncate w-full mt-1.5 px-1">{{ $rank1->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <!-- Gold pedestal column -->
                                <div class="w-full h-28 bg-gradient-to-b from-amber-100 to-amber-500 dark:from-amber-900/80 dark:to-amber-600 rounded-t-2xl border border-b-0 border-amber-300/60 dark:border-amber-700/60 shadow-[0_10px_25px_rgba(245,158,11,0.1)] flex flex-col items-center justify-center relative overflow-hidden podium-pillar border-l border-r">
                                    <div class="absolute inset-0 bg-amber-400/5"></div>
                                    <span class="text-5xl font-black text-black dark:text-slate-900 rank-number">1</span>
                                    <div class="text-[11px] font-black text-amber-800 dark:text-amber-200 bg-white dark:bg-slate-800 px-2.5 py-0.5 rounded-full mt-1 shadow-md border border-amber-200 dark:border-amber-700">
                                        {{ number_format($rank1->TongDiem) }} <i class="fas fa-star text-amber-500 text-[9px] animate-spin" style="animation-duration: 10s"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- TOP 3 (BRONZE) -->
                        <div class="flex-1 flex flex-col items-center">
                            @if(count($studentRankings) >= 3)
                                @php $rank3 = $studentRankings[2]; @endphp
                                <div class="flex flex-col items-center mb-2 z-10 transition-transform duration-300 hover:-translate-y-1 w-full text-center">
                                    @if($rank3->Vien)
                                        <div class="relative w-16 h-16 flex items-center justify-center shrink-0 mx-auto">
                                            <img src="{{ asset('images/' . $rank3->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank3->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                            <img src="{{ $rank3->AnhDaiDien ? asset('storage/' . $rank3->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank3->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                    @else
                                        <div class="relative w-13 h-13 rounded-full border-2 border-amber-700/30 shadow-md flex items-center justify-center overflow-hidden bg-white mx-auto">
                                            @if($rank3->AnhDaiDien)
                                                <img src="{{ asset('storage/' . $rank3->AnhDaiDien) }}" 
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                     alt="Avatar" class="w-full h-full object-cover">
                                                <div class="hidden w-full h-full bg-gradient-to-br from-amber-600 to-amber-800 text-white items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank3->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-amber-600 to-amber-800 text-white flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank3->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <h4 class="font-extrabold text-slate-700 dark:text-[var(--text-primary)] text-[11px] truncate w-full mt-1.5 px-1">{{ $rank3->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <!-- Bronze pedestal column -->
                                <div class="w-full h-16 bg-gradient-to-b from-orange-50 to-orange-100 dark:from-orange-900/60 dark:to-orange-950 rounded-t-xl border border-b-0 border-orange-250 dark:border-orange-850 shadow-inner flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-orange-350/5"></div>
                                    <span class="text-4xl font-black text-amber-700/40 dark:text-amber-600/40 rank-number">3</span>
                                    <div class="text-[10px] font-extrabold text-amber-800 dark:text-amber-200 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full mt-1 shadow-sm border border-orange-100 dark:border-orange-800">
                                        {{ number_format($rank3->TongDiem) }} <i class="fas fa-star text-amber-500 text-[8px]"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Empty Seat -->
                                <div class="flex flex-col items-center mb-2 opacity-40 w-full text-center">
                                    <div class="w-10 h-10 rounded-full border border-dashed border-slate-300 flex items-center justify-center bg-slate-50 mx-auto">
                                        <i class="fas fa-user text-slate-400 text-xs"></i>
                                    </div>
                                    <span class="font-bold text-[10px] text-slate-400 mt-1">Đang chờ</span>
                                </div>
                                <div class="w-full h-16 bg-gradient-to-b from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 rounded-t-xl border border-b-0 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center relative overflow-hidden opacity-50">
                                    <span class="text-3xl font-black text-slate-300/40 dark:text-slate-700/40 rank-number">3</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- List Ranks 4-10 for Students -->
                    <div class="space-y-2.5 max-h-[460px] overflow-y-auto pr-1.5 custom-scrollbar mt-4">
                        @forelse($studentRankings as $index => $rank)
                        @if($index >= 3)
                        <div class="leaderboard-row flex items-center justify-between p-3.5 bg-white/60 dark:bg-[var(--brand-card-bg)] border border-slate-100 dark:border-slate-800/60 hover:border-purple-250 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200">
                            <!-- Rank & User Info -->
                            <div class="flex items-center gap-3.5">
                                <span class="w-7 h-7 flex items-center justify-center font-bold text-xs text-slate-400 dark:text-slate-500 bg-slate-100/60 dark:bg-slate-800/60 rounded-full rank-number">
                                    {{ $index + 1 }}
                                </span>
                                @if($rank->Vien)
                                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/' . $rank->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full">
                                        <img src="{{ $rank->AnhDaiDien ? asset('storage/' . $rank->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-700 relative flex items-center justify-center">
                                        @if($rank->AnhDaiDien)
                                            <img src="{{ asset('storage/' . $rank->AnhDaiDien) }}" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                 alt="Avatar" class="w-full h-full object-cover">
                                            <div class="hidden w-full h-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($rank->UserName ?? 'U', 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($rank->UserName ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-extrabold text-sm text-slate-800 dark:text-[var(--text-primary)] truncate max-w-[150px]">{{ $rank->UserName ?? 'Người dùng Ẩn' }}</h4>
                                    <p class="text-[10px] text-slate-400 dark:text-[var(--text-secondary)] mt-0.5 truncate max-w-[150px]">{{ $rank->name ?? 'Học viên' }}</p>
                                </div>
                            </div>
                            <!-- Score Tag -->
                            <div class="flex items-center gap-1.5 bg-purple-50 dark:bg-purple-900/30 border border-purple-100/50 dark:border-purple-800/50 px-3.5 py-1 rounded-full text-purple-700 dark:text-purple-300 font-extrabold text-xs">
                                <span>{{ number_format($rank->TongDiem) }}</span>
                                <i class="fas fa-star text-amber-500 text-[10px]"></i>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-2 border border-slate-100 mx-auto">
                                <i class="fas fa-user-graduate text-slate-350"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-500">Bảng xếp hạng trống</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>

            <!-- RIGHT PANEL: TRÒ CHƠI PHIÊU LƯU -->
            <div class="bg-gradient-to-b from-green-200 via-yellow-100 dark:from-green-950/30 dark:via-yellow-950/20 dark:to-[var(--brand-card-bg)] backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 md:p-8 shadow-[0_15px_40px_-15px_rgba(11,27,61,0.05)] flex flex-col justify-between h-full">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-xl font-extrabold text-[var(--text-primary)] flex items-center gap-2">
                                <i class="fas fa-gamepad text-green-600 dark:text-green-400 text-lg"></i> Trò Chơi Phiêu Lưu
                            </h3>
                            <p class="text-[11px] text-[var(--text-secondary)] mt-0.5">Xếp hạng theo tổng điểm màn chơi phiêu lưu</p>
                        </div>
                        <span class="px-2.5 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-[10px] font-bold uppercase tracking-wider">Top 10</span>
                    </div>

                    <!-- Connected 3D Podium Block for Games -->
                    <div class="flex items-end justify-center w-full mt-12 mb-8 max-w-sm mx-auto podium-group px-2">
                        
                        <!-- TOP 2 (SILVER) -->
                        <div class="flex-1 flex flex-col items-center">
                            @if(count($gameRankings) >= 2)
                                @php $rank2 = $gameRankings[1]; @endphp
                                <div class="flex flex-col items-center mb-2 z-10 transition-transform duration-300 hover:-translate-y-1 w-full text-center">
                                    @if($rank2->Vien)
                                        <div class="relative w-16 h-16 flex items-center justify-center shrink-0 mx-auto">
                                            <img src="{{ asset('images/' . $rank2->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank2->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                            <img src="{{ $rank2->AnhDaiDien ? asset('storage/' . $rank2->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank2->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                    @else
                                        <div class="relative w-13 h-13 rounded-full border-2 border-slate-300 shadow-md flex items-center justify-center overflow-hidden bg-white mx-auto">
                                            @if($rank2->AnhDaiDien)
                                                <img src="{{ asset('storage/' . $rank2->AnhDaiDien) }}" 
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                     alt="Avatar" class="w-full h-full object-cover">
                                                <div class="hidden w-full h-full bg-gradient-to-br from-slate-400 to-slate-500 text-white items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank2->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-slate-400 to-slate-500 text-white flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank2->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <h4 class="font-extrabold text-slate-700 text-[11px] truncate w-full mt-1.5 px-1">{{ $rank2->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <!-- Silver pedestal column -->
                                <div class="w-full h-20 bg-gradient-to-b from-slate-100 to-slate-200/90 dark:from-slate-800 dark:to-slate-900/90 rounded-t-xl border border-b-0 border-slate-250 dark:border-slate-750 shadow-inner flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-slate-300/10"></div>
                                    <span class="text-4xl font-black text-slate-400/50 dark:text-slate-600/50 rank-number">2</span>
                                    <div class="text-[10px] font-extrabold text-green-700 dark:text-slate-200 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full mt-1 shadow-sm border border-slate-200/50 dark:border-slate-700">
                                        {{ number_format($rank2->DiemMan) }} <i class="fas fa-leaf text-green-500 text-[8px]"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Empty Seat -->
                                <div class="flex flex-col items-center mb-2 opacity-40 w-full text-center">
                                    <div class="w-10 h-10 rounded-full border border-dashed border-slate-300 flex items-center justify-center bg-slate-50 mx-auto">
                                        <i class="fas fa-user text-slate-400 text-xs"></i>
                                    </div>
                                    <span class="font-bold text-[10px] text-slate-400 mt-1">Đang chờ</span>
                                </div>
                                <div class="w-full h-20 bg-gradient-to-b from-slate-50 to-slate-100 rounded-t-xl border border-b-0 border-dashed border-slate-200 flex flex-col items-center justify-center relative overflow-hidden opacity-50">
                                    <span class="text-3xl font-black text-slate-300/40 rank-number">2</span>
                                </div>
                            @endif
                        </div>

                        <!-- TOP 1 (GOLD) -->
                        <div class="flex-1 flex flex-col items-center -mx-1 z-10">
                            @if(count($gameRankings) >= 1)
                                @php $rank1 = $gameRankings[0]; @endphp
                                <div class="flex flex-col items-center mb-2 w-full text-center">
                                    <div class="relative">
                                        <i class="fas fa-crown text-amber-500 absolute -top-5 left-1/2 -translate-x-1/2 text-lg animate-bounce" style="animation-duration: 3s"></i>
                                        <div class="absolute inset-0 bg-amber-400 rounded-full blur-md opacity-25 animate-pulse"></div>
                                        @if($rank1->Vien)
                                            <div class="relative w-20 h-20 flex items-center justify-center shrink-0 mx-auto">
                                                <img src="{{ asset('images/' . $rank1->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank1->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                                <img src="{{ $rank1->AnhDaiDien ? asset('storage/' . $rank1->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank1->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                            </div>
                                        @else
                                            <div class="relative w-16 h-16 rounded-full border-3 border-amber-400 shadow-lg flex items-center justify-center overflow-hidden bg-white mx-auto">
                                                @if($rank1->AnhDaiDien)
                                                    <img src="{{ asset('storage/' . $rank1->AnhDaiDien) }}" 
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                         alt="Avatar" class="w-full h-full object-cover">
                                                    <div class="hidden w-full h-full bg-gradient-to-br from-amber-400 to-yellow-500 text-white items-center justify-center font-bold text-base">
                                                        {{ strtoupper(substr($rank1->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-amber-400 to-yellow-500 text-white flex items-center justify-center font-bold text-base">
                                                        {{ strtoupper(substr($rank1->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="font-extrabold text-primary text-xs truncate w-full mt-1.5 px-1">{{ $rank1->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <!-- Gold pedestal column -->
                                <div class="w-full h-28 bg-gradient-to-b from-amber-100 to-amber-500 dark:from-amber-900/80 dark:to-amber-600 rounded-t-2xl border border-b-0 border-amber-300/60 dark:border-amber-700/60 shadow-[0_10px_25px_rgba(245,158,11,0.1)] flex flex-col items-center justify-center relative overflow-hidden podium-pillar border-l border-r">
                                    <div class="absolute inset-0 bg-amber-400/5"></div>
                                    <span class="text-5xl font-black text-black dark:text-slate-900 rank-number">1</span>
                                    <div class="text-[11px] font-black text-green-700 dark:text-amber-200 bg-white dark:bg-slate-800 px-2.5 py-0.5 rounded-full mt-1 shadow-md border border-amber-200 dark:border-amber-700">
                                        {{ number_format($rank1->DiemMan) }} <i class="fas fa-leaf text-green-500 text-[9px] animate-bounce" style="animation-duration: 2.5s"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- TOP 3 (BRONZE) -->
                        <div class="flex-1 flex flex-col items-center">
                            @if(count($gameRankings) >= 3)
                                @php $rank3 = $gameRankings[2]; @endphp
                                <div class="flex flex-col items-center mb-2 z-10 transition-transform duration-300 hover:-translate-y-1 w-full text-center">
                                    @if($rank3->Vien)
                                        <div class="relative w-16 h-16 flex items-center justify-center shrink-0 mx-auto">
                                            <img src="{{ asset('images/' . $rank3->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank3->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                            <img src="{{ $rank3->AnhDaiDien ? asset('storage/' . $rank3->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank3->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                    @else
                                        <div class="relative w-13 h-13 rounded-full border-2 border-amber-700/30 shadow-md flex items-center justify-center overflow-hidden bg-white mx-auto">
                                            @if($rank3->AnhDaiDien)
                                                <img src="{{ asset('storage/' . $rank3->AnhDaiDien) }}" 
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                     alt="Avatar" class="w-full h-full object-cover">
                                                <div class="hidden w-full h-full bg-gradient-to-br from-amber-600 to-amber-800 text-white items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank3->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-amber-600 to-amber-800 text-white flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($rank3->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <h4 class="font-extrabold text-slate-700 text-[11px] truncate w-full mt-1.5 px-1">{{ $rank3->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <!-- Bronze pedestal column -->
                                <div class="w-full h-16 bg-gradient-to-b from-orange-50 to-orange-100 dark:from-orange-900/60 dark:to-orange-950 rounded-t-xl border border-b-0 border-orange-250 dark:border-orange-850 shadow-inner flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-orange-350/5"></div>
                                    <span class="text-4xl font-black text-amber-700/40 dark:text-amber-600/40 rank-number">3</span>
                                    <div class="text-[10px] font-extrabold text-green-700 dark:text-amber-200 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full mt-1 shadow-sm border border-orange-100 dark:border-orange-800">
                                        {{ number_format($rank3->DiemMan) }} <i class="fas fa-leaf text-green-500 text-[8px]"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Empty Seat -->
                                <div class="flex flex-col items-center mb-2 opacity-40 w-full text-center">
                                    <div class="w-10 h-10 rounded-full border border-dashed border-slate-300 flex items-center justify-center bg-slate-50 mx-auto">
                                        <i class="fas fa-user text-slate-400 text-xs"></i>
                                    </div>
                                    <span class="font-bold text-[10px] text-slate-400 mt-1">Đang chờ</span>
                                </div>
                                <div class="w-full h-16 bg-gradient-to-b from-slate-50 to-slate-100 rounded-t-xl border border-b-0 border-dashed border-slate-200 flex flex-col items-center justify-center relative overflow-hidden opacity-50">
                                    <span class="text-3xl font-black text-slate-300/40 rank-number">3</span>
                                </div>
                            @endif
                        </div>
                        
                    </div>

                    <!-- List Ranks 4-10 for Games -->
                    <div class="space-y-2.5 max-h-[460px] overflow-y-auto pr-1.5 custom-scrollbar mt-4">
                        @forelse($gameRankings as $index => $rank)
                        @if($index >= 3)
                        <div class="leaderboard-row flex items-center justify-between p-3.5 bg-white/60 dark:bg-[var(--brand-card-bg)] border border-slate-100 dark:border-slate-800/60 hover:border-green-250 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200">
                            <!-- Rank & User Info -->
                            <div class="flex items-center gap-3.5">
                                <span class="w-7 h-7 flex items-center justify-center font-bold text-xs text-slate-400 dark:text-slate-500 bg-slate-100/60 dark:bg-slate-800/60 rounded-full rank-number">
                                    {{ $index + 1 }}
                                </span>
                                @if($rank->Vien)
                                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                                        <img src="{{ asset('images/' . $rank->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full">
                                        <img src="{{ $rank->AnhDaiDien ? asset('storage/' . $rank->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-700 relative flex items-center justify-center">
                                        @if($rank->AnhDaiDien)
                                            <img src="{{ asset('storage/' . $rank->AnhDaiDien) }}" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                 alt="Avatar" class="w-full h-full object-cover">
                                            <div class="hidden w-full h-full bg-gradient-to-br from-green-500 to-emerald-600 text-white items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($rank->UserName ?? 'U', 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-green-500 to-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($rank->UserName ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-extrabold text-sm text-slate-800 dark:text-[var(--text-primary)] truncate max-w-[100px]">{{ $rank->UserName ?? 'Người dùng Ẩn' }}</h4>
                                        <!-- Badges based on scores -->
                                        @if($rank->DiemMan >= 500)
                                            <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-300 text-[8px] font-extrabold rounded-full border border-rose-100 dark:border-rose-800 shadow-sm">Legend</span>
                                        @elseif($rank->DiemMan >= 300)
                                            <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-300 text-[8px] font-extrabold rounded-full border border-amber-100 dark:border-amber-800 shadow-sm">Master</span>
                                        @elseif($rank->DiemMan >= 100)
                                            <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 text-[8px] font-extrabold rounded-full border border-indigo-100 dark:border-indigo-800 shadow-sm">Elite</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-150 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[8px] font-extrabold rounded-full border border-slate-200 dark:border-slate-700">Rookie</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-[var(--text-secondary)] mt-0.5 truncate max-w-[150px]">{{ $rank->name ?? 'Nhà thám hiểm' }}</p>
                                </div>
                            </div>
                            <!-- Score Tag -->
                            <div class="flex items-center gap-1.5 bg-green-50 dark:bg-green-900/30 border border-green-100/50 dark:border-green-800/50 px-3.5 py-1 rounded-full text-green-700 dark:text-green-300 font-extrabold text-xs">
                                <span>{{ number_format($rank->DiemMan) }}</span>
                                <i class="fas fa-leaf text-green-500 text-[10px]"></i>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="py-12 text-center text-slate-400">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-2 border border-slate-100 mx-auto">
                                <i class="fas fa-gamepad text-slate-350"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-500">Chưa có ai vượt qua thử thách</p>
                        </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
