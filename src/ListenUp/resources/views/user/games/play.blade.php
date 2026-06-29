@extends('layouts.app')

@section('title', 'Thử thách: ' . $map->TenBanDo)

@section('content')
<!-- Holographic colorful aurora grid pattern overlay for gaming style -->
<div class="min-h-screen py-10 px-4 sm:px-6 md:px-8 relative overflow-hidden" 
     style="background: linear-gradient(135deg, rgba(224, 242, 254, 0.8), rgba(243, 232, 255, 0.85), rgba(253, 242, 248, 0.85)), url('{{ $map->HinhAnh ? asset('images/'.$map->HinhAnh) : '' }}') no-repeat center center fixed; background-size: cover;">
    
    <!-- Unified Game Console Cabinet -->
    <div class="w-full max-w-7xl mx-auto bg-gradient-to-br from-white/95 to-indigo-50/50 border-4 border-indigo-200/40 rounded-[2.5rem] p-6 md:p-8 shadow-[0_20px_50px_rgba(99,102,241,0.15)] relative overflow-visible animate-fade-in">
        
        <!-- Subtle tech-grid pattern running behind the console -->
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.03] pointer-events-none rounded-[2.5rem]"></div>

        <!-- LEFT SIDE FLOATING DECAL (Overlapping the cabinet border for a 3D effect) -->
        <div class="hidden lg:flex absolute -left-7 top-[40%] flex-col items-center gap-6 pointer-events-none select-none z-20 animate-fade-in" style="animation-delay: 200ms;">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-500/40 animate-bounce" style="animation-duration: 3s;">
                <i class="fas fa-headphones-alt text-xl animate-pulse"></i>
            </div>
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-500/40 rotate-12 animate-pulse">
                <i class="fas fa-music text-sm"></i>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/40 -rotate-6">
                <i class="fas fa-puzzle-piece text-base"></i>
            </div>
        </div>

        <!-- RIGHT SIDE FLOATING DECAL (Overlapping the cabinet border for a 3D effect) -->
        <div class="hidden lg:flex absolute -right-7 top-[40%] flex-col items-center gap-6 pointer-events-none select-none z-20 animate-fade-in" style="animation-delay: 400ms;">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/40 animate-bounce" style="animation-duration: 3.5s;">
                <i class="fas fa-crown text-xl animate-pulse"></i>
            </div>
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 text-white flex items-center justify-center shadow-lg shadow-yellow-500/40 -rotate-12 animate-pulse" style="animation-delay: 500ms;">
                <i class="fas fa-star text-sm"></i>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/40 rotate-6">
                <i class="fas fa-brain text-base"></i>
            </div>
        </div>

        <!-- Integrated Top HUD Header Bar -->
        <div class="bg-gradient-to-r from-indigo-500/10 via-purple-500/5 to-pink-500/10 border-b border-indigo-100 p-5 -mx-6 md:-mx-8 -mt-6 md:-mt-8 mb-8 flex flex-wrap items-center justify-between gap-4 px-8 relative z-10">
            <!-- Stage/Mission Title -->
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/25">
                    <i class="fas fa-gamepad text-lg animate-pulse"></i>
                </div>
                <div>
                    <div class="text-[9px] font-black text-indigo-500 uppercase tracking-widest leading-none mb-1">Nhiệm vụ cửa ải</div>
                    <div class="text-lg font-black text-slate-800 tracking-wide">{{ $map->TenBanDo }}</div>
                </div>
            </div>
            
            <!-- Live Indicator Beacon -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-400/30 px-4 py-2 rounded-full shadow-inner select-none">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-black text-emerald-700 tracking-wider uppercase">Live Decryption Deck</span>
                </div>
            </div>

            <!-- Score Trophy Stats -->
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Điểm số tích lũy</div>
                    <div class="text-lg font-black text-amber-500 tracking-wider flex items-center gap-1 justify-end">
                        <span>{{ auth()->user()->DiemMan ?? 0 }}</span>
                        <span class="text-xs font-bold text-slate-500">VP</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/20">
                    <i class="fas fa-trophy text-lg"></i>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-center flex items-center justify-center gap-2 shadow-sm relative z-10">
                <i class="fas fa-exclamation-triangle text-red-500"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Main Workspace -->
        @php
            $mapNumber = (int) str_replace('BD', '', $map->MaBanDo);
        @endphp
        @include('user.games.components.map' . $mapNumber)
    </div>
</div>

<style>
    /* Custom blueprint grid background styling */
    .bg-grid-pattern {
        background-size: 24px 24px;
        background-image: linear-gradient(to right, rgba(37, 99, 235, 0.04) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(37, 99, 235, 0.04) 1px, transparent 1px);
    }

    /* Styling scrollbars for vertical list overflow */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(59, 130, 246, 0.03);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.15);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.3);
    }

    /* Webkit audio player custom settings */
    .custom-audio::-webkit-media-controls-panel {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .custom-audio::-webkit-media-controls-play-button {
        background-color: #3b82f6; /* blue-500 */
        border-radius: 50%;
    }
    .custom-audio::-webkit-media-controls-current-time-display,
    .custom-audio::-webkit-media-controls-time-remaining-display {
        color: #475569;
    }

    /* Sound wave animation keyframes */
    @keyframes sound-bar-grow {
        0%, 100% { transform: scaleY(0.3); }
        50% { transform: scaleY(1); }
    }
    .animate-sound-bar-1 { animation: sound-bar-grow 1s ease-in-out infinite; transform-origin: bottom; }
    .animate-sound-bar-2 { animation: sound-bar-grow 1.2s ease-in-out infinite; transform-origin: bottom; }
    .animate-sound-bar-3 { animation: sound-bar-grow 0.8s ease-in-out infinite; transform-origin: bottom; }
    .animate-sound-bar-4 { animation: sound-bar-grow 1.4s ease-in-out infinite; transform-origin: bottom; }

    /* Simple Fade In Animation */
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

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
            <a href="{{ route('public.games') }}" class="w-full py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 text-slate-950 font-black text-center text-base tracking-wider shadow-lg shadow-orange-500/20 active:translate-y-[2px] transition-all cursor-pointer border border-amber-300/30 block no-underline">
                TIẾP TỤC HÀNH TRÌNH
            </a>
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
