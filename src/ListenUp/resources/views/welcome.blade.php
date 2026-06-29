@extends('layouts.app')

@section('content')
<!-- Custom Styles for Premium Light/Dark Editorial Aesthetic & Theme Customizer -->
@push('styles')
<style>
    /* Default variables for Light Mode */
    :root {
        --brand-hue: 205;
        --brand-saturation: 85%;
        
        --brand-primary: HSL(var(--brand-hue), var(--brand-saturation), 16%);
        --brand-secondary: HSL(var(--brand-hue), var(--brand-saturation), 42%);
        --brand-accent: HSL(var(--brand-hue), var(--brand-saturation), 55%);
        --brand-bg: HSL(var(--brand-hue), 25%, 97.5%);
        --brand-card-bg: rgba(255, 255, 255, 0.72);
        --brand-border: HSLA(var(--brand-hue), var(--brand-saturation), 20%, 0.07);
        --brand-border-hover: HSLA(var(--brand-hue), var(--brand-saturation), 40%, 0.22);
        --brand-glow: HSLA(var(--brand-hue), var(--brand-saturation), 60%, 0.12);
        
        --text-primary: var(--brand-primary);
        --text-secondary: HSL(var(--brand-hue), 15%, 45%);
        --text-muted: #64748B;
        
        --nav-bg: rgba(255, 255, 255, 0.75);
        --nav-text: HSL(var(--brand-hue), 25%, 28%);
        --nav-hover: var(--brand-secondary);
        
        --white-const: #ffffff;
        --card-shadow: 0 8px 32px 0 rgba(15, 23, 42, 0.02);
        --grid-color: rgba(15, 23, 42, 0.015);
        --dark-invert: 0;
        --dark-opacity: 0.8;

        /* Leaderboard styling */
        --leaderboard-row-bg: rgba(255, 255, 255, 0.5);
        --leaderboard-row-bg-hover: #ffffff;
        --leaderboard-row-bg-top1: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(255, 251, 235, 0.9), rgba(251, 191, 36, 0.1));
        --leaderboard-row-bg-top2: linear-gradient(135deg, rgba(148, 163, 184, 0.12), rgba(248, 250, 252, 0.9), rgba(203, 213, 225, 0.1));
        --leaderboard-row-bg-top3: linear-gradient(135deg, rgba(249, 115, 22, 0.12), rgba(255, 247, 237, 0.9), rgba(253, 186, 116, 0.08));
    }

    /* Dark Mode overrides */
    .dark {
        --brand-primary: #f8fafc;
        --brand-secondary: HSL(var(--brand-hue), var(--brand-saturation), 60%);
        --brand-accent: HSL(calc(var(--brand-hue) - 30), var(--brand-saturation), 55%);
        --brand-bg: #030712;
        --brand-card-bg: rgba(17, 24, 39, 0.65);
        --brand-border: rgba(168, 85, 247, 0.15);
        --brand-border-hover: rgba(168, 85, 247, 0.35);
        --brand-glow: rgba(168, 85, 247, 0.2);
        
        --text-primary: #f8fafc;
        --text-secondary: #cbd5e1;
        --text-muted: #94a3b8;
        
        --nav-bg: rgba(3, 7, 18, 0.85);
        --nav-text: #94a3b8;
        --nav-hover: #f8fafc;
        
        --white-const: #0f172a;
        --card-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
        --grid-color: rgba(255, 255, 255, 0.015);
        --dark-invert: 1;
        --dark-opacity: 0.15;

        /* Leaderboard styling */
        --leaderboard-row-bg: rgba(17, 24, 39, 0.4);
        --leaderboard-row-bg-hover: rgba(17, 24, 39, 0.7);
        --leaderboard-row-bg-top1: linear-gradient(135deg, rgba(245, 158, 11, 0.22), rgba(30, 27, 22, 0.85), rgba(251, 191, 36, 0.08));
        --leaderboard-row-bg-top2: linear-gradient(135deg, rgba(148, 163, 184, 0.18), rgba(30, 32, 38, 0.85), rgba(203, 213, 225, 0.05));
        --leaderboard-row-bg-top3: linear-gradient(135deg, rgba(249, 115, 22, 0.18), rgba(35, 28, 24, 0.85), rgba(253, 186, 116, 0.05));
    }

    /* Page Background & Text Overrides */
    body {
        background-color: var(--brand-bg) !important;
        color: var(--text-primary) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    .main-content-wrapper {
        padding-top: 80px;
        background-color: transparent !important;
        transition: background-color 0.3s ease;
    }

    /* Fixed Glass Navigation Override */
    .glass-nav {
        background: var(--nav-bg) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-bottom: 1px solid var(--brand-border) !important;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02) !important;
        transition: border-color 0.3s ease, background 0.3s ease;
    }
    .glass-nav a, .glass-nav button {
        color: var(--nav-text) !important;
        transition: color 0.3s ease;
    }
    .glass-nav a:hover, .glass-nav button:hover {
        color: var(--nav-hover) !important;
    }
    
    /* Brand logo background/text */
    .glass-nav .bg-primary {
        background-color: var(--brand-primary) !important;
        transition: background-color 0.3s ease;
    }
    .glass-nav .text-primary {
        color: var(--text-primary) !important;
        transition: color 0.3s ease;
    }

    /* Dropdown Menus in Navbar */
    .glass-nav .group .absolute .bg-white {
        background-color: var(--white-const) !important;
        border-color: var(--brand-border) !important;
        box-shadow: var(--card-shadow) !important;
    }
    .glass-nav .group .absolute a {
        color: var(--text-secondary) !important;
    }
    .glass-nav .group .absolute a:hover {
        background-color: HSLA(var(--brand-hue), var(--brand-saturation), 50%, 0.1) !important;
        color: var(--text-primary) !important;
    }
    .glass-nav .group .absolute .border-b {
        border-bottom-color: var(--brand-border) !important;
    }
    
    /* Login & Register buttons */
    .glass-nav a[href*="register"], .glass-nav .bg-primary.text-white {
        background-color: var(--brand-primary) !important;
        color: var(--white-const) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .glass-nav a[href*="register"]:hover, .glass-nav .bg-primary.text-white:hover {
        background-color: var(--brand-secondary) !important;
        color: #ffffff !important;
    }

    /* Footer Light/Dark adaptivity */
    footer {
        background-color: var(--white-const) !important;
        border-top: 1px solid var(--brand-border) !important;
        transition: border-color 0.3s ease, background-color 0.3s ease;
    }
    footer h4, footer a.text-primary {
        color: var(--text-primary) !important;
        transition: color 0.3s ease;
    }
    footer p, footer a {
        color: var(--text-secondary) !important;
        transition: color 0.3s ease;
    }
    footer a:hover {
        color: var(--brand-secondary) !important;
    }
    footer .border-t {
        border-top-color: var(--brand-border) !important;
    }

    /* Soft Floating Accent Orbs */
    .glow-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        opacity: 0.1;
        z-index: 0;
        pointer-events: none;
    }
    .glow-orb-primary {
        background: radial-gradient(circle, var(--brand-accent) 0%, transparent 70%);
        width: 600px;
        height: 600px;
        top: -100px;
        left: -150px;
        animation: orb-float 15s infinite alternate ease-in-out;
    }
    .glow-orb-secondary {
        background: radial-gradient(circle, var(--brand-secondary) 0%, transparent 70%);
        width: 500px;
        height: 500px;
        bottom: 15%;
        right: -100px;
        animation: orb-float 18s infinite alternate-reverse ease-in-out;
    }
    @keyframes orb-float {
        0% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(50px, 30px) scale(1.08); }
        100% { transform: translate(-30px, -50px) scale(0.95); }
    }

    /* Light/Dark Glass Cyber Panels */
    .cyber-panel {
        background: var(--brand-card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--brand-border);
        box-shadow: var(--card-shadow);
        transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    }
    .cyber-panel:hover {
        border-color: var(--brand-border-hover);
        box-shadow: 0 10px 40px -5px var(--brand-glow);
    }

    /* Grid Overlay Layout */
    .cyber-grid-overlay {
        background-image: 
            linear-gradient(var(--grid-color) 1px, transparent 1px),
            linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    /* Tech structural corner detailing */
    .corner-decor {
        position: relative;
    }
    .corner-decor::before, .corner-decor::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        border-color: var(--brand-secondary);
        border-style: solid;
        pointer-events: none;
        opacity: 0.35;
        transition: border-color 0.3s ease;
    }
    .corner-decor::before {
        top: -1px;
        left: -1px;
        border-width: 1px 0 0 1px;
    }
    .corner-decor::after {
        bottom: -1px;
        right: -1px;
        border-width: 0 1px 1px 0;
    }

    /* Audio wave animations (Water Blue & Accent colors) */
    @keyframes soundWaveHeight {
        0%, 100% { transform: scaleY(0.15); }
        50% { transform: scaleY(1); }
    }
    .wave-bar {
        transform-origin: bottom;
        animation: soundWaveHeight 1.4s ease-in-out infinite;
        animation-play-state: paused;
        background-color: var(--brand-secondary);
        transition: background-color 0.3s ease;
    }
    .wave-playing .wave-bar {
        animation-play-state: running;
    }
    .wave-bar:nth-child(2n) { animation-delay: 0.15s; background-color: var(--brand-accent); }
    .wave-bar:nth-child(3n) { animation-delay: 0.3s; background-color: var(--brand-secondary); }
    .wave-bar:nth-child(4n) { animation-delay: 0.45s; background-color: var(--brand-accent); }
    .wave-bar:nth-child(5n) { animation-delay: 0.2s; background-color: var(--brand-secondary); }
    .wave-bar:nth-child(6n) { animation-delay: 0.5s; background-color: var(--brand-accent); }

    /* Custom thin scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.01);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: var(--brand-secondary);
    }

    /* Disk rotate animation */
    @keyframes disk-rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .disk-anim {
        animation: disk-rotate 8s linear infinite;
        animation-play-state: paused;
    }
    .wave-playing .disk-anim {
        animation-play-state: running;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-6px) rotate(1.5deg); }
    }
    @keyframes float-gentle {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-4px); }
    }
    .animate-float-gold {
        animation: float 4s ease-in-out infinite;
    }
    .animate-float-crown {
        animation: float-gentle 3s ease-in-out infinite;
    }

    @keyframes gold-pulse {
        0%, 100% { border-color: rgba(245, 158, 11, 0.55); box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.1), 0 4px 15px -3px rgba(245, 158, 11, 0.2), inset 0 0 12px rgba(245, 158, 11, 0.05); }
        50% { border-color: rgba(245, 158, 11, 0.9); box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2), 0 4px 22px 2px rgba(245, 158, 11, 0.4), inset 0 0 15px rgba(245, 158, 11, 0.12); }
    }
    @keyframes silver-pulse {
        0%, 100% { border-color: rgba(148, 163, 184, 0.5); box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.08), 0 4px 15px -3px rgba(148, 163, 184, 0.15), inset 0 0 12px rgba(148, 163, 184, 0.04); }
        50% { border-color: rgba(148, 163, 184, 0.85); box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.18), 0 4px 22px 2px rgba(148, 163, 184, 0.3), inset 0 0 15px rgba(148, 163, 184, 0.1); }
    }
    @keyframes bronze-pulse {
        0%, 100% { border-color: rgba(249, 115, 22, 0.45); box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.08), 0 4px 15px -3px rgba(249, 115, 22, 0.15), inset 0 0 12px rgba(249, 115, 22, 0.04); }
        50% { border-color: rgba(249, 115, 22, 0.8); box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.18), 0 4px 22px 2px rgba(249, 115, 22, 0.3), inset 0 0 15px rgba(249, 115, 22, 0.1); }
    }

    /* Glowing Border for Active/Current User Card */
    @keyframes cardBorderGlow {
        0%, 100% { border-color: rgba(168, 85, 247, 0.4); box-shadow: 0 0 15px rgba(168, 85, 247, 0.1); }
        50% { border-color: rgba(99, 102, 241, 0.8); box-shadow: 0 0 25px rgba(99, 102, 241, 0.25); }
    }
    .current-user-card-glow {
        animation: cardBorderGlow 4s ease-in-out infinite;
        border-width: 2px !important;
    }

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

    .gold-glow {
        box-shadow: 0 10px 30px -5px rgba(245, 158, 11, 0.25), 0 0 20px rgba(245, 158, 11, 0.1);
    }
    .gold-glow:hover {
        box-shadow: 0 20px 45px -5px rgba(245, 158, 11, 0.45), 0 0 30px rgba(245, 158, 11, 0.25);
    }
    
    .silver-glow {
        box-shadow: 0 10px 25px -5px rgba(148, 163, 184, 0.2), 0 0 15px rgba(148, 163, 184, 0.1);
    }
    .silver-glow:hover {
        box-shadow: 0 15px 35px -5px rgba(148, 163, 184, 0.35), 0 0 25px rgba(148, 163, 184, 0.2);
    }
    
    .bronze-glow {
        box-shadow: 0 10px 25px -5px rgba(249, 115, 22, 0.2), 0 0 15px rgba(249, 115, 22, 0.1);
    }
    .bronze-glow:hover {
        box-shadow: 0 15px 35px -5px rgba(249, 115, 22, 0.35), 0 0 25px rgba(249, 115, 22, 0.2);
    }

    .glow-gold-avatar {
        box-shadow: 0 0 15px 2px rgba(245, 158, 11, 0.45);
    }
    .glow-silver-avatar {
        box-shadow: 0 0 12px 2px rgba(148, 163, 184, 0.35);
    }
    .glow-bronze-avatar {
        box-shadow: 0 0 12px 2px rgba(249, 115, 22, 0.35);
    }

    /* Leaderboard custom styles */
    .leaderboard-row-0 {
        background: var(--leaderboard-row-bg-top1) !important;
        border-color: rgba(245, 158, 11, 0.55) !important;
        border-width: 2px !important;
        animation: gold-pulse 3s infinite ease-in-out;
    }
    .leaderboard-row-0:hover {
        border-color: rgba(245, 158, 11, 1) !important;
        animation: none;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.25), 0 8px 28px -4px rgba(245, 158, 11, 0.45), inset 0 0 15px rgba(245, 158, 11, 0.15) !important;
    }
    .leaderboard-row-1 {
        background: var(--leaderboard-row-bg-top2) !important;
        border-color: rgba(148, 163, 184, 0.5) !important;
        border-width: 2px !important;
        animation: silver-pulse 3s infinite ease-in-out;
    }
    .leaderboard-row-1:hover {
        border-color: rgba(148, 163, 184, 1) !important;
        animation: none;
        box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.22), 0 8px 28px -4px rgba(148, 163, 184, 0.35), inset 0 0 15px rgba(148, 163, 184, 0.12) !important;
    }
    .leaderboard-row-2 {
        background: var(--leaderboard-row-bg-top3) !important;
        border-color: rgba(249, 115, 22, 0.45) !important;
        border-width: 2px !important;
        animation: bronze-pulse 3s infinite ease-in-out;
    }
    .leaderboard-row-2:hover {
        border-color: rgba(249, 115, 22, 1) !important;
        animation: none;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.22), 0 8px 28px -4px rgba(249, 115, 22, 0.35), inset 0 0 15px rgba(249, 115, 22, 0.12) !important;
    }
    .leaderboard-row-default {
        background: var(--leaderboard-row-bg) !important;
        border-color: var(--brand-border) !important;
        transition: all 0.2s ease;
    }
    .leaderboard-row-default:hover {
        background: var(--leaderboard-row-bg-hover) !important;
        border-color: var(--brand-border-hover) !important;
        transform: translateX(4px);
    }
</style>
@endpush

<!-- Dynamic Hue-Shifting Background Flow Image -->
<div class="fixed inset-0 pointer-events-none z-0 bg-cover bg-top bg-no-repeat bg-fixed mix-blend-multiply transition-all duration-300" style="background-image: url('{{ asset('images/flowing_blue_bg.png') }}'); filter: hue-rotate(calc(var(--brand-hue) * 1deg - 205deg)) brightness(calc(1 - var(--dark-invert) * 0.75)); opacity: var(--dark-opacity);"></div>

<!-- Soft Decorative Background Orbs -->
<div class="glow-orb glow-orb-primary"></div>
<div class="glow-orb glow-orb-secondary"></div>

<div class="max-w-[100rem] mx-auto w-full px-4 lg:px-8 relative z-10 cyber-grid-overlay pt-5 pb-8">
    <div class="grid lg:grid-cols-12 gap-8 relative">
        
        <!-- Left Side: Main Core Content (Col-span 8) -->
        <div class="lg:col-span-8 space-y-20">
            
            <!-- Hero Section -->
            <section class="pt-2 pb-16 flex items-center relative overflow-hidden">
                <div class="grid lg:grid-cols-2 gap-12 items-center w-full">
                    
                    <!-- Left Column: Premium Text Content -->
                    <div class="space-y-8 z-10">
                        @guest
                            <!-- Tiny Monospace Tag -->
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/20 text-xs font-mono tracking-wider text-[var(--brand-secondary)]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--brand-accent)] animate-pulse"></span>
                                [ AUDIO DECODER v1.4 // SYSTEM ONLINE ]
                            </div>
                            
                            <!-- Main Title -->
                            <h1 class="font-display font-extrabold text-5xl lg:text-7xl leading-none tracking-tight text-[var(--text-primary)] space-y-2">
                                <span>Listen Up</span> <br>
                                <span class="bg-gradient-to-r from-[var(--brand-secondary)] via-[var(--brand-accent)] to-[var(--brand-secondary)] bg-clip-text text-transparent drop-shadow-[0_2px_10px_rgba(var(--brand-hue),40%,80%,0.2)]">Level Up</span>
                            </h1>
                            
                            <!-- High-Taste Tagline -->
                            <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-xl">
                                Chinh phục kỹ năng nghe Tiếng Anh thông qua hệ thống âm thanh tương tác trực quan. Theo dõi tiến độ thời gian thực, duy trì chuỗi luyện tập và thách thức thứ hạng của bạn trong đấu trường học tập ListenUp.
                            </p>
                            
                            <!-- CTA Buttons Row -->
                            <div class="flex flex-wrap items-center gap-4 pt-2">
                                <a href="{{ route('login') }}" class="bg-[var(--brand-primary)] hover:bg-[var(--brand-secondary)] text-white px-8 py-4 rounded-xl font-medium text-base transition-all duration-300 transform hover:-translate-y-1 shadow-lg shadow-[var(--brand-primary)]/10 hover:shadow-[var(--brand-primary)]/20 flex items-center gap-2">
                                    Bắt đầu học <i class="fas fa-bolt text-xs text-yellow-400 animate-pulse"></i>
                                </a>
                                
                                <a href="#levels" class="px-8 py-4 rounded-xl font-medium text-[var(--text-primary)] bg-[var(--white-const)] border border-[var(--brand-border)] hover:border-[var(--brand-border-hover)] hover:bg-[var(--brand-card-bg)] transition-all duration-200 flex items-center gap-2 shadow-sm">
                                    <i class="fas fa-compass text-[var(--brand-secondary)]"></i> Khám phá
                                </a>
                            </div>
                            
                            <!-- Social Proof Widget -->
                            <div class="pt-8 flex items-center gap-6 border-t border-[var(--brand-border)]">
                                <div class="flex -space-x-3">
                                    <img class="w-10 h-10 rounded-full border-2 border-[var(--white-const)] object-cover shadow-sm" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="User">
                                    <img class="w-10 h-10 rounded-full border-2 border-[var(--white-const)] object-cover shadow-sm" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=100&q=80" alt="User">
                                    <img class="w-10 h-10 rounded-full border-2 border-[var(--white-const)] object-cover shadow-sm" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&q=80" alt="User">
                                    <div class="w-10 h-10 rounded-full border-2 border-[var(--white-const)] bg-[var(--brand-card-bg)] flex items-center justify-center text-xs font-bold text-[var(--text-secondary)] shadow-sm">+2k</div>
                                </div>
                                <div class="text-sm">
                                    <div class="font-bold text-[var(--text-primary)] flex items-center gap-1">
                                        <i class="fas fa-star text-amber-500 text-xs"></i> 4.9 / 5.0
                                    </div>
                                    <div class="text-[var(--text-secondary)] text-xs font-mono">[ 2,000+ LEARNERS DECODING ]</div>
                                </div>
                            </div>
                        @else
                            <!-- Main Title for Auth User -->
                            <h1 class="font-display font-extrabold text-4xl lg:text-5xl leading-none tracking-tight text-[var(--text-primary)] space-y-1.5 mb-4">
                                <span>Listen Up</span> <br>
                                <span class="bg-gradient-to-r from-[var(--brand-secondary)] via-[var(--brand-accent)] to-[var(--brand-secondary)] bg-clip-text text-transparent drop-shadow-[0_2px_10px_rgba(var(--brand-hue),40%,80%,0.2)]">Level Up</span>
                            </h1>

                            @if(empty(Auth::user()->learning_goal))
                                <!-- Survey Form Card (Premium Light Glassmorphic Form Card) -->
                                <div class="bg-[var(--brand-card-bg)] backdrop-blur-md border-2 border-[var(--brand-border)] p-6 rounded-3xl shadow-lg space-y-5 relative overflow-hidden max-w-xl">
                                    <div class="absolute -top-12 -right-12 w-36 h-36 bg-[var(--brand-secondary)]/10 rounded-full blur-2xl pointer-events-none"></div>
                                    
                                    <div class="border-b border-[var(--brand-border)] pb-3">
                                        <h4 class="font-display font-bold text-base text-[var(--text-primary)] flex items-center gap-2">
                                            <i class="fas fa-magic text-[var(--brand-accent)] animate-pulse"></i> Cá nhân hóa lộ trình của bạn
                                        </h4>
                                        <p class="text-xs text-[var(--text-secondary)] mt-0.5">Chọn các mục tiêu để AI thiết lập danh sách bài học dành riêng cho bạn.</p>
                                    </div>
                                    
                                    <form id="surveyForm" action="{{ route('user.profile.update-survey') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <!-- Step 1: Goal -->
                                        <div>
                                            <label class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2">1. MỤC TIÊU HỌC TẬP</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="learning_goal" value="communication" class="hidden peer" checked>
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        Giao tiếp
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="learning_goal" value="exams" class="hidden peer">
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        Luyện thi
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="learning_goal" value="business" class="hidden peer">
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        Công việc
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Step 2: Level -->
                                        <div>
                                            <label class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2">2. TRÌNH ĐỘ HIỆN TẠI</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="current_level" value="beginner" class="hidden peer" checked>
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        Mới bắt đầu
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="current_level" value="intermediate" class="hidden peer">
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        Trung cấp
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="current_level" value="advanced" class="hidden peer">
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        Nâng cao
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Step 3: Daily Target -->
                                        <div>
                                            <label class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2">3. THỜI GIAN LUYỆN TẬP MỖI NGÀY</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="daily_target_time" value="10" class="hidden peer" checked>
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        10 phút
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="daily_target_time" value="20" class="hidden peer">
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        20 phút
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="daily_target_time" value="30" class="hidden peer">
                                                    <div class="px-3 py-2.5 text-xs font-bold text-center rounded-xl border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] hover:border-[var(--brand-border-hover)] peer-checked:border-[var(--brand-secondary)] peer-checked:bg-[var(--brand-secondary)]/10 peer-checked:text-[var(--brand-secondary)] transition-all">
                                                        30 phút
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full bg-[var(--brand-primary)] hover:bg-[var(--brand-secondary)] text-white py-3.5 rounded-xl font-bold text-xs tracking-wider font-mono uppercase shadow-lg shadow-[var(--brand-primary)]/10 transition-all mt-2 active:scale-[0.98]">
                                            TẠO LỘ TRÌNH CỦA TÔI <i class="fas fa-magic ml-1 text-amber-200"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Survey Result Summary -->
                                <div class="bg-[var(--brand-card-bg)] backdrop-blur-md border-2 border-[var(--brand-border)] p-6 rounded-3xl shadow-lg space-y-4 max-w-xl">
                                    <div class="flex justify-between items-center border-b border-[var(--brand-border)] pb-3">
                                        <h4 class="font-display font-bold text-base text-[var(--text-primary)] flex items-center gap-2">
                                            <i class="fas fa-route text-green-500"></i> Lộ trình cá nhân của bạn
                                        </h4>
                                        <span class="bg-green-500/10 border border-green-500/20 text-green-600 text-[9px] font-mono px-2 py-0.5 rounded-full uppercase tracking-wider">ĐANG ÁP DỤNG</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-3 py-1">
                                        <div class="p-3 rounded-2xl bg-[var(--white-const)] border border-[var(--brand-border)] text-center">
                                            <span class="block text-[9px] font-mono text-[var(--text-muted)] uppercase tracking-wider mb-1">MỤC TIÊU</span>
                                            <span class="text-xs font-bold text-[var(--text-primary)]">
                                                @if(Auth::user()->learning_goal == 'communication') Giao tiếp @elseif(Auth::user()->learning_goal == 'exams') Luyện thi @else Công việc @endif
                                            </span>
                                        </div>
                                        <div class="p-3 rounded-2xl bg-[var(--white-const)] border border-[var(--brand-border)] text-center">
                                            <span class="block text-[9px] font-mono text-[var(--text-muted)] uppercase tracking-wider mb-1">TRÌNH ĐỘ</span>
                                            <span class="text-xs font-bold text-[var(--text-primary)]">
                                                @if(Auth::user()->current_level == 'beginner') Cơ bản @elseif(Auth::user()->current_level == 'intermediate') Trung cấp @else Nâng cao @endif
                                            </span>
                                        </div>
                                        <div class="p-3 rounded-2xl bg-[var(--white-const)] border border-[var(--brand-border)] text-center">
                                            <span class="block text-[9px] font-mono text-[var(--text-muted)] uppercase tracking-wider mb-1">THỜI LƯỢNG</span>
                                            <span class="text-xs font-bold text-[var(--text-primary)]">{{ Auth::user()->daily_target_time }} phút/ngày</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-3 pt-2">
                                        <a href="#adaptive-path" class="flex-grow bg-[var(--brand-primary)] hover:bg-[var(--brand-secondary)] text-white text-center py-3 rounded-xl font-bold text-xs tracking-wider font-mono uppercase transition-all flex items-center justify-center gap-1.5 shadow-md shadow-[var(--brand-primary)]/10">
                                            LUYỆN NGHE NGAY <i class="fas fa-chevron-right text-[9px]"></i>
                                        </a>
                                        <button onclick="resetSurvey()" class="px-4 py-3 border border-[var(--brand-border)] bg-[var(--white-const)] hover:bg-[var(--brand-bg)] text-[var(--text-primary)] rounded-xl font-bold text-xs tracking-wider font-mono uppercase transition-all">
                                            Khảo sát lại <i class="fas fa-undo ml-1"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="resetSurveyForm" action="{{ route('user.profile.update-survey') }}" method="POST" class="hidden">
                                        @csrf
                                        <input type="hidden" name="learning_goal" value="">
                                        <input type="hidden" name="current_level" value="">
                                        <input type="hidden" name="daily_target_time" value="0">
                                    </form>
                                </div>
                            @endif
                        @endguest
                    </div>
                    
                    <!-- Right Column: Top Categories Section -->
                    <div class="relative z-10 w-full max-w-md mx-auto lg:mr-0">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[var(--brand-secondary)]/5 to-[var(--brand-accent)]/5 rounded-3xl transform rotate-2 scale-105 blur-xl pointer-events-none"></div>
                        
                        <div class="corner-decor cyber-panel rounded-2xl p-6 shadow-xl relative overflow-hidden">
                            <!-- Title Block -->
                            <div class="text-center mb-8 relative">
                                <h2 class="font-display font-extrabold text-2xl text-[var(--text-primary)] relative inline-block">
                                    Top Categories
                                    <!-- Brush stroke underline decoration -->
                                    <svg class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-28 h-2.5 text-yellow-400 dark:text-yellow-500" viewBox="0 0 100 10" preserveAspectRatio="none">
                                        <path d="M5 6 C 25 9, 50 3, 75 7 C 85 6, 92 8, 97 5" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none"/>
                                    </svg>
                                </h2>
                            </div>
                            
                            <!-- Categories Grid -->
                            <div class="grid grid-cols-2 gap-3.5 relative z-10">
                                @if(isset($all_topics) && count($all_topics) > 0)
                                    @php
                                        $colorMap = [
                                            'blue' => [
                                                'bg' => 'bg-blue-50/80 dark:bg-blue-950/20',
                                                'border' => 'border-blue-100/50 hover:border-blue-300 dark:border-blue-900/30 dark:hover:border-blue-700',
                                                'text' => 'text-blue-700 dark:text-blue-300',
                                                'icon' => 'text-blue-600 dark:text-blue-405',
                                                'iconBg' => 'bg-white dark:bg-blue-950/40 shadow-sm'
                                            ],
                                            'orange' => [
                                                'bg' => 'bg-orange-50/80 dark:bg-orange-950/20',
                                                'border' => 'border-orange-100/50 hover:border-orange-300 dark:border-orange-900/30 dark:hover:border-orange-700',
                                                'text' => 'text-orange-700 dark:text-orange-300',
                                                'icon' => 'text-orange-600 dark:text-orange-405',
                                                'iconBg' => 'bg-white dark:bg-orange-950/40 shadow-sm'
                                            ],
                                            'indigo' => [
                                                'bg' => 'bg-indigo-50/80 dark:bg-indigo-950/20',
                                                'border' => 'border-indigo-100/50 hover:border-indigo-300 dark:border-indigo-900/30 dark:hover:border-indigo-700',
                                                'text' => 'text-indigo-700 dark:text-indigo-300',
                                                'icon' => 'text-indigo-600 dark:text-indigo-405',
                                                'iconBg' => 'bg-white dark:bg-indigo-950/40 shadow-sm'
                                            ],
                                            'slate' => [
                                                'bg' => 'bg-slate-50/80 dark:bg-slate-800/40',
                                                'border' => 'border-slate-200/50 hover:border-slate-300 dark:border-slate-700 dark:hover:border-slate-600',
                                                'text' => 'text-slate-700 dark:text-slate-300',
                                                'icon' => 'text-slate-600 dark:text-slate-400',
                                                'iconBg' => 'bg-white dark:bg-slate-700 shadow-sm'
                                            ],
                                            'rose' => [
                                                'bg' => 'bg-rose-50/80 dark:bg-rose-950/20',
                                                'border' => 'border-rose-100/50 hover:border-rose-300 dark:border-rose-900/30 dark:hover:border-rose-700',
                                                'text' => 'text-rose-700 dark:text-rose-300',
                                                'icon' => 'text-rose-600 dark:text-rose-405',
                                                'iconBg' => 'bg-white dark:bg-rose-950/40 shadow-sm'
                                            ],
                                            'cyan' => [
                                                'bg' => 'bg-cyan-50/80 dark:bg-cyan-950/20',
                                                'border' => 'border-cyan-100/50 hover:border-cyan-300 dark:border-cyan-900/30 dark:hover:border-cyan-700',
                                                'text' => 'text-cyan-700 dark:text-cyan-300',
                                                'icon' => 'text-cyan-600 dark:text-cyan-405',
                                                'iconBg' => 'bg-white dark:bg-cyan-950/40 shadow-sm'
                                            ],
                                            'emerald' => [
                                                'bg' => 'bg-emerald-50/80 dark:bg-emerald-950/20',
                                                'border' => 'border-emerald-100/50 hover:border-emerald-300 dark:border-emerald-900/30 dark:hover:border-emerald-700',
                                                'text' => 'text-emerald-700 dark:text-emerald-300',
                                                'icon' => 'text-emerald-600 dark:text-emerald-405',
                                                'iconBg' => 'bg-white dark:bg-emerald-950/40 shadow-sm'
                                            ],
                                            'violet' => [
                                                'bg' => 'bg-violet-50/80 dark:bg-violet-950/20',
                                                'border' => 'border-violet-100/50 hover:border-violet-300 dark:border-violet-900/30 dark:hover:border-violet-700',
                                                'text' => 'text-violet-700 dark:text-violet-300',
                                                'icon' => 'text-violet-600 dark:text-violet-405',
                                                'iconBg' => 'bg-white dark:bg-violet-950/40 shadow-sm'
                                            ],
                                            'pink' => [
                                                'bg' => 'bg-pink-50/80 dark:bg-pink-950/20',
                                                'border' => 'border-pink-100/50 hover:border-pink-300 dark:border-pink-900/30 dark:hover:border-pink-700',
                                                'text' => 'text-pink-700 dark:text-pink-300',
                                                'icon' => 'text-pink-600 dark:text-pink-405',
                                                'iconBg' => 'bg-white dark:bg-pink-950/40 shadow-sm'
                                            ],
                                            'amber' => [
                                                'bg' => 'bg-amber-50/80 dark:bg-amber-950/20',
                                                'border' => 'border-amber-100/50 hover:border-amber-300 dark:border-amber-900/30 dark:hover:border-amber-700',
                                                'text' => 'text-amber-700 dark:text-amber-300',
                                                'icon' => 'text-amber-600 dark:text-amber-405',
                                                'iconBg' => 'bg-white dark:bg-amber-950/40 shadow-sm'
                                            ],
                                            'purple' => [
                                                'bg' => 'bg-purple-50/80 dark:bg-purple-950/20',
                                                'border' => 'border-purple-100/50 hover:border-purple-300 dark:border-purple-900/30 dark:hover:border-purple-700',
                                                'text' => 'text-purple-700 dark:text-purple-300',
                                                'icon' => 'text-purple-600 dark:text-purple-450',
                                                'iconBg' => 'bg-white dark:bg-purple-950/40 shadow-sm'
                                            ],
                                        ];
                                    @endphp
                                    @foreach($all_topics->take(8) as $topic)
                                        @php
                                            $colorName = $topic->color_class ?? 'purple';
                                            $theme = $colorMap[$colorName] ?? $colorMap['purple'];
                                        @endphp
                                        <a href="{{ route('public.topics.detail', $topic->MaCD) }}" class="flex items-center gap-3.5 p-2 pr-5 rounded-full border transition-all duration-300 hover:scale-[1.05] hover:shadow-md {{ $theme['bg'] }} {{ $theme['border'] }} group">
                                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $theme['iconBg'] }} transition-transform duration-300 group-hover:rotate-12">
                                                <i class="{{ $topic->icon_class }} {{ $theme['icon'] }} text-xs"></i>
                                            </div>
                                            <span class="text-xs font-extrabold tracking-tight {{ $theme['text'] }} truncate">{{ $topic->TenCD }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    @php
                                        $staticCategories = [
                                            ['name' => 'Mathematics', 'icon' => 'fas fa-hourglass-half', 'color' => 'bg-purple-50/80 border-purple-100/50 text-purple-700 hover:border-purple-300 dark:bg-purple-950/20 dark:border-purple-900/30 dark:text-purple-300', 'iconBg' => 'bg-white dark:bg-purple-950/40', 'iconColor' => 'text-purple-600 dark:text-purple-400'],
                                            ['name' => 'Idea Generate', 'icon' => 'fas fa-lightbulb', 'color' => 'bg-rose-50/80 border-rose-100/50 text-rose-700 hover:border-rose-300 dark:bg-rose-950/20 dark:border-rose-900/30 dark:text-rose-300', 'iconBg' => 'bg-white dark:bg-rose-950/40', 'iconColor' => 'text-rose-600 dark:text-rose-400'],
                                            ['name' => 'Chemistry', 'icon' => 'fas fa-flask', 'color' => 'bg-blue-50/80 border-blue-100/50 text-blue-700 hover:border-blue-300 dark:bg-blue-950/20 dark:border-blue-900/30 dark:text-blue-300', 'iconBg' => 'bg-white dark:bg-blue-950/40', 'iconColor' => 'text-blue-600 dark:text-blue-400'],
                                            ['name' => 'Business Analysis', 'icon' => 'fas fa-layer-group', 'color' => 'bg-amber-50/80 border-amber-100/50 text-amber-700 hover:border-amber-300 dark:bg-amber-950/20 dark:border-amber-900/30 dark:text-amber-300', 'iconBg' => 'bg-white dark:bg-amber-950/40', 'iconColor' => 'text-amber-600 dark:text-amber-400'],
                                            ['name' => 'Development', 'icon' => 'fas fa-bug', 'color' => 'bg-orange-50/80 border-orange-100/50 text-orange-700 hover:border-orange-300 dark:bg-orange-950/20 dark:border-orange-900/30 dark:text-orange-300', 'iconBg' => 'bg-white dark:bg-orange-950/40', 'iconColor' => 'text-orange-600 dark:text-orange-400'],
                                            ['name' => 'Email Marketing', 'icon' => 'fas fa-paper-plane', 'color' => 'bg-purple-50/80 border-purple-100/50 text-purple-700 hover:border-purple-300 dark:bg-purple-950/20 dark:border-purple-900/30 dark:text-purple-300', 'iconBg' => 'bg-white dark:bg-purple-950/40', 'iconColor' => 'text-purple-600 dark:text-purple-400'],
                                            ['name' => 'Arestogoy', 'icon' => 'fas fa-binoculars', 'color' => 'bg-rose-50/80 border-rose-100/50 text-rose-700 hover:border-rose-300 dark:bg-rose-950/20 dark:border-rose-900/30 dark:text-rose-300', 'iconBg' => 'bg-white dark:bg-rose-950/40', 'iconColor' => 'text-rose-600 dark:text-rose-400'],
                                            ['name' => 'IT / Technology', 'icon' => 'fas fa-bolt', 'color' => 'bg-blue-50/80 border-blue-100/50 text-blue-700 hover:border-blue-300 dark:bg-blue-950/20 dark:border-blue-900/30 dark:text-blue-300', 'iconBg' => 'bg-white dark:bg-blue-950/40', 'iconColor' => 'text-blue-600 dark:text-blue-400'],
                                        ];
                                    @endphp
                                    @foreach($staticCategories as $cat)
                                        <div class="flex items-center gap-3.5 p-2 pr-5 rounded-full border transition-all duration-300 hover:scale-[1.05] hover:shadow-md {{ $cat['color'] }} group">
                                            <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 {{ $cat['iconBg'] }} shadow-sm transition-transform duration-300 group-hover:rotate-12">
                                                <i class="{{ $cat['icon'] }} {{ $cat['iconColor'] }} text-xs"></i>
                                            </div>
                                            <span class="text-xs font-bold truncate">{{ $cat['name'] }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @auth
            <!-- Lộ Trình Luyện Nghe Thích Ứng (Adaptive Lesson Recommendation) -->
            <section id="adaptive-path" class="py-10 relative">
                <div class="corner-decor cyber-panel rounded-2xl p-6 md:p-8 space-y-6">
                    <div class="flex justify-between items-center border-b border-[var(--brand-border)] pb-4">
                        <div>
                            <h2 class="font-display font-extrabold text-2xl md:text-3xl text-[var(--text-primary)]">
                                <i class="fas fa-magic text-[var(--brand-accent)] me-2"></i> Lộ Trình Luyện Nghe Thích Ứng
                            </h2>
                            <p class="text-[var(--text-secondary)] text-sm mt-1">Đề xuất được cá nhân hóa tự động theo năng lực và tiến độ của bạn.</p>
                        </div>
                        <span class="bg-[var(--brand-accent)]/15 border border-[var(--brand-accent)]/30 text-[var(--brand-secondary)] text-xs font-mono px-3 py-1.5 rounded-full uppercase tracking-wider inline-flex items-center">
                            <i class="fas fa-brain me-1.5"></i> Smart Engine v1.0
                        </span>
                    </div>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($recommendedTests as $rec)
                            @php
                                $topicName = $rec->chude ? $rec->chude->TenCD : 'Tổng hợp';
                                $iconClass = $rec->chude ? $rec->chude->icon_class : 'fas fa-headphones';
                                $levelName = $rec->capdonghe ? $rec->capdonghe->TenCDN : 'Tổng quát';
                                
                                // Tag styles for recommendation type
                                $typeMap = [
                                    'weak_topic' => 'bg-red-500/10 text-red-600 border border-red-500/20',
                                    'adventure_map' => 'bg-green-500/10 text-green-600 border border-green-500/20',
                                    'new_discovery' => 'bg-blue-500/10 text-blue-600 border border-blue-500/20',
                                    'fallback' => 'bg-slate-500/10 text-slate-600 border border-slate-500/20',
                                ];
                                $typeStyle = $typeMap[$rec->recommendation_type ?? 'fallback'] ?? 'bg-slate-500/10 text-slate-600 border border-slate-500/20';
                            @endphp
                            
                            <div class="group relative overflow-hidden rounded-2xl bg-[var(--white-const)]/40 hover:bg-[var(--white-const)]/80 border border-[var(--brand-border)] hover:border-[var(--brand-border-hover)] p-6 transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between h-full shadow-sm hover:shadow-md">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[var(--brand-secondary)]/10 text-xs font-medium text-[var(--brand-secondary)]">
                                            <i class="{{ $iconClass }}"></i> {{ $topicName }}
                                        </span>
                                        <span class="text-[10px] font-mono text-[var(--text-muted)] border border-[var(--brand-border)] px-1.5 py-0.5 rounded">{{ $levelName }}</span>
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-display font-bold text-lg text-[var(--text-primary)] mb-2 group-hover:text-[var(--brand-secondary)] transition-colors line-clamp-2">
                                            {{ $rec->TenBai }}
                                        </h3>
                                        <p class="text-[var(--text-secondary)] text-xs leading-relaxed line-clamp-3">
                                            {{ $rec->MoTa ?? 'Luyện nghe tiếng Anh tương tác với các trò chơi hấp dẫn và câu hỏi phản xạ.' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-6 space-y-4">
                                    <div class="text-[10px] font-mono">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded {{ $typeStyle }}">
                                            <i class="fas fa-check-circle me-1"></i> {{ $rec->recommendation_reason }}
                                        </span>
                                    </div>
                                    
                                    <a href="{{ route('user.test.show', $rec->MaBai) }}" class="w-full bg-[var(--brand-primary)] hover:bg-[var(--brand-secondary)] text-white text-center py-2.5 rounded-xl font-medium text-sm transition-all duration-200 block">
                                        Bắt đầu học <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @else
            <!-- Levels Section -->
            <section id="levels" class="py-10 relative">
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 border-b border-[var(--brand-border)] pb-6">
                    <div>
                        <div class="text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2">[ 02 / CURRICULUM PATHWAYS ]</div>
                        <h2 class="font-display font-extrabold text-3xl md:text-4xl text-[var(--text-primary)]">Structured Learning Paths</h2>
                    </div>
                    <p class="text-[var(--text-secondary)] text-sm max-w-md mt-4 md:mt-0 leading-relaxed">
                        Lộ trình nghe được thiết kế bài bản từ cơ bản đến nâng cao. Lựa chọn cấp độ phù hợp để bắt đầu thử thách khả năng giải mã tiếng Anh của bạn.
                    </p>
                </div>
                
                <!-- Level Cards Grid -->
                <div class="grid md:grid-cols-3 gap-6">
                    @if(isset($levels) && count($levels) > 0)
                        @foreach($levels as $index => $level)
                            <!-- Custom Designed Level Card -->
                            <a href="{{ route('public.levels.show', $level->MaCDN) }}" class="group block relative overflow-hidden rounded-2xl cyber-panel p-8 transition-all duration-500 hover:-translate-y-1">
                                <!-- Top gradient hover background -->
                                <div class="absolute inset-0 bg-gradient-to-b from-[var(--brand-secondary)]/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="corner-decor"></div>
                                
                                <div class="flex justify-between items-start mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/20 flex items-center justify-center text-[var(--brand-secondary)] text-xl group-hover:bg-[var(--brand-secondary)] group-hover:text-white transition-all duration-300">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <span class="text-[10px] font-mono text-[var(--text-muted)] tracking-wider">0{{ $index + 1 }} // PATH</span>
                                </div>
                                
                                <h3 class="font-display font-bold text-xl text-[var(--text-primary)] mb-3 group-hover:text-[var(--brand-secondary)] transition-colors">{{ $level->TenCDN }}</h3>
                                <p class="text-[var(--text-secondary)] text-xs leading-relaxed mb-6 h-12 overflow-hidden text-ellipsis">{{ Str::limit($level->MoTaCDN, 85) }}</p>
                                
                                <div class="flex items-center text-[var(--brand-secondary)] text-xs font-mono tracking-wider group-hover:gap-2 transition-all">
                                    ENTER PATHWAY <i class="fas fa-arrow-right ml-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <!-- Fallback static premium cards -->
                        <div class="relative overflow-hidden rounded-2xl cyber-panel p-8">
                            <div class="corner-decor"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 rounded-xl bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/20 flex items-center justify-center text-[var(--brand-secondary)] text-xl">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <span class="text-[10px] font-mono text-[var(--text-muted)] tracking-wider">A1-A2</span>
                            </div>
                            <h3 class="font-display font-bold text-xl text-[var(--text-primary)] mb-3">Beginner</h3>
                            <p class="text-[var(--text-secondary)] text-xs leading-relaxed mb-6">Bắt đầu với các đoạn hội thoại giao tiếp cơ bản và từ vựng cốt lõi hàng ngày.</p>
                        </div>
                        <div class="relative overflow-hidden rounded-2xl cyber-panel p-8">
                            <div class="corner-decor"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 rounded-xl bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/20 flex items-center justify-center text-[var(--brand-secondary)] text-xl">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <span class="text-[10px] font-mono text-[var(--text-muted)] tracking-wider">B1-B2</span>
                            </div>
                            <h3 class="font-display font-bold text-xl text-[var(--text-primary)] mb-3">Intermediate</h3>
                            <p class="text-[var(--text-secondary)] text-xs leading-relaxed mb-6">Nắm bắt ngữ điệu, tốc độ nói tự nhiên của người bản xứ và hiểu sâu ngữ cảnh.</p>
                        </div>
                        <div class="relative overflow-hidden rounded-2xl cyber-panel p-8">
                            <div class="corner-decor"></div>
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 rounded-xl bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/20 flex items-center justify-center text-[var(--brand-secondary)] text-xl">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <span class="text-[10px] font-mono text-[var(--text-muted)] tracking-wider">C1-C2</span>
                            </div>
                            <h3 class="font-display font-bold text-xl text-[var(--text-primary)] mb-3">Advanced</h3>
                            <p class="text-[var(--text-secondary)] text-xs leading-relaxed mb-6">Làm chủ các chủ đề học thuật chuyên sâu, tin tức thời sự và phân tích đa chiều.</p>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Gamification & Stats Showcase Section -->
            <section class="py-10 relative">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    
                    <!-- Left: Interactive Stats Widgets -->
                    <div class="relative order-2 lg:order-1">
                        <div class="absolute -inset-4 bg-[var(--brand-secondary)]/5 rounded-3xl blur-2xl pointer-events-none"></div>
                        
                        <div class="grid grid-cols-2 gap-4 relative">
                            <!-- Metric 1: Elite Class Badge -->
                            <div class="cyber-panel p-6 rounded-2xl border border-[var(--brand-border)] space-y-4 transition duration-300">
                                <div class="w-10 h-10 rounded-lg bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/20 flex items-center justify-center text-[var(--brand-secondary)]">
                                    <i class="fas fa-medal text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[var(--text-primary)] text-sm">Hạng Tinh Anh</h4>
                                    <p class="text-[11px] font-mono text-[var(--text-muted)] mt-1">TOP 10% TUẦN NÀY</p>
                                </div>
                            </div>
                            
                            <!-- Metric 2: Vocabulary Progress -->
                            <div class="bg-gradient-to-br from-[var(--white-const)] to-[var(--brand-card-bg)] p-6 rounded-2xl border border-[var(--brand-border)] space-y-4 relative overflow-hidden group hover:border-[var(--brand-border-hover)] transition duration-300 shadow-sm">
                                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-[var(--brand-secondary)]/5 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
                                <h4 class="font-bold text-[var(--text-secondary)] text-xs uppercase tracking-wider">Vocabulary Decoded</h4>
                                <div class="text-3xl font-display font-extrabold text-[var(--text-primary)] mt-1">245</div>
                                <div class="space-y-1 mt-4">
                                    <div class="flex justify-between text-[10px] font-mono text-[var(--text-muted)]">
                                        <span>PROGRESS</span>
                                        <span>80%</span>
                                    </div>
                                    <div class="w-full bg-[var(--brand-bg)] h-1.5 rounded-full overflow-hidden border border-[var(--brand-border)]">
                                        <div class="bg-gradient-to-r from-[var(--brand-secondary)] to-[var(--brand-accent)] h-full rounded-full" style="width: 80%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Metric 3: Daily Challenge Streak -->
                            <div class="cyber-panel p-6 rounded-2xl space-y-4 col-span-2 transition duration-300">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-[var(--text-primary)] text-sm">Thử thách mỗi ngày</h4>
                                    <span class="text-[10px] font-mono text-[var(--brand-secondary)]">XP CHƯA NHẬN</span>
                                </div>
                                <div class="flex items-center gap-4 bg-[var(--white-const)] p-3 rounded-xl border border-[var(--brand-border)]">
                                    <div class="w-10 h-10 rounded-lg bg-[var(--brand-secondary)]/10 border border-[var(--brand-secondary)]/25 flex items-center justify-center text-[var(--brand-secondary)] text-lg">
                                        <i class="fas fa-fire animate-pulse text-amber-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-xs text-[var(--text-primary)]">Daily Listening Streak</div>
                                        <div class="text-[10px] font-mono text-[var(--text-secondary)] mt-0.5">12 ngày liên tục • Mục tiêu 15 phút/ngày</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-mono font-bold text-[var(--text-primary)]">10 / 15m</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Gamification Content -->
                    <div class="space-y-8 order-1 lg:order-2">
                        <div>
                            <div class="text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-2">[ 03 / GAMIFICATION ]</div>
                            <h2 class="font-display font-extrabold text-3xl md:text-4xl text-[var(--text-primary)]">Luyện nghe không nhàm chán</h2>
                        </div>
                        <p class="text-[var(--text-secondary)] text-base leading-relaxed">
                            Mỗi bài học được thiết kế giống như một màn giải mật mã. Bạn càng hoàn thành nhiều thử thách, điểm tích lũy XP càng cao, giúp bạn thăng hạng trên bảng vinh danh toàn hệ thống.
                        </p>
                        
                        <div class="space-y-4">
                            <!-- Feature 1 -->
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-[var(--brand-card-bg)] border border-[var(--brand-border)] hover:border-[var(--brand-border-hover)] transition-colors shadow-sm">
                                <div class="w-10 h-10 rounded-lg bg-green-500/10 border border-green-500/20 text-green-600 flex items-center justify-center shrink-0">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-[var(--text-primary)] text-sm">Thống kê tiến trình</h4>
                                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed">Xem lại lịch sử làm bài, các từ vựng đã học và biểu đồ phát triển kỹ năng nghe của bản thân.</p>
                                </div>
                            </div>
                            
                            <!-- Feature 2 -->
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-[var(--brand-card-bg)] border border-[var(--brand-border)] hover:border-[var(--brand-border-hover)] transition-colors shadow-sm">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fas fa-gamepad"></i>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-[var(--text-primary)] text-sm">Hệ thống Trò chơi hóa</h4>
                                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed">Tham gia các mini-game từ vựng, xếp chữ và trả lời câu hỏi phản xạ nhanh để tối đa hóa hiệu quả ghi nhớ.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endauth
        </div>

        <!-- Right Side: Sticky Global Leaderboard (Col-span 4) -->
        <div class="lg:col-span-4 pt-6 pr-2">
            @php
                $currentUser = auth()->user();
                $myRank = null;
                $userAbove = null;
                if ($currentUser) {
                    $myRank = \App\Models\User::where('TongDiem', '>', $currentUser->TongDiem)->count() + 1;
                    $userAbove = \App\Models\User::where('TongDiem', '>', $currentUser->TongDiem)
                        ->orderBy('TongDiem', 'asc')
                        ->first();
                }
                $totalUsers = \App\Models\User::count();
                $totalXP = \App\Models\User::sum('TongDiem');
            @endphp
            
            <div class="cyber-panel rounded-3xl p-6 shadow-xl border border-[var(--brand-border)] sticky top-24 z-30 space-y-6 overflow-hidden bg-gradient-to-b from-[var(--white-const)] via-[var(--brand-card-bg)] to-[var(--white-const)] dark:from-[var(--brand-card-bg)] dark:to-slate-950">
                <!-- Grid background layout (matching construction/blueprint motif) -->
                <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(var(--brand-secondary) 0.5px, transparent 0.5px); background-size: 12px 12px;"></div>
                
                <!-- Browser Chrome / App Header (Mockup application frame) -->
                <div class="flex items-center justify-between border-b border-[var(--brand-border)] pb-4 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1.5 shrink-0 mr-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400 shadow-sm"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-450 shadow-sm"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-green-400 shadow-sm"></span>
                        </div>
                        <div class="h-4 w-[1px] bg-[var(--brand-border)] mr-1.5"></div>
                        <div>
                            <h3 class="font-display font-black text-sm text-[var(--text-primary)] tracking-tight">Đấu Trường Danh Vọng</h3>
                            <p class="text-[8px] font-mono text-[var(--text-muted)] tracking-wider">SEASON 04 // LEAGUE A</p>
                        </div>
                    </div>
                    <span class="bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 text-[8px] font-black px-2 py-0.5 rounded uppercase tracking-wider animate-pulse border border-purple-200/50">ACTIVE</span>
                </div>
                
                <!-- Summary Stats Board -->
                <div class="grid grid-cols-2 gap-2 p-1.5 bg-[var(--brand-bg)] border border-[var(--brand-border)] rounded-2xl relative z-10">
                    <div class="p-2.5 text-center">
                        <span class="block text-[8px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Học Viên</span>
                        <span class="text-sm font-black text-[var(--text-primary)] mt-0.5 block">{{ number_format($totalUsers) }}</span>
                    </div>
                    <div class="p-2.5 text-center border-l border-[var(--brand-border)]">
                        <span class="block text-[8px] font-bold text-[var(--text-muted)] uppercase tracking-wider">Tổng Tích Lũy</span>
                        <span class="text-sm font-black text-purple-600 dark:text-purple-400 mt-0.5 block">{{ number_format($totalXP) }} <span class="text-[9px]">XP</span></span>
                    </div>
                </div>

                <!-- TOP 3 PODIUM SECTION -->
                <div class="relative z-10">
                    <div class="flex items-end justify-center w-full mt-12 mb-6 max-w-sm mx-auto podium-group px-1">
                        <!-- TOP 2 (SILVER) -->
                        <div class="flex-1 flex flex-col items-center">
                            @if(count($topUsers) >= 2)
                                @php $rank2 = $topUsers[1]; @endphp
                                <div class="flex flex-col items-center mb-2.5 z-10 transition-transform duration-300 hover:-translate-y-1 w-full text-center">
                                    <div class="relative">
                                        @if($rank2->Vien)
                                            <div class="relative w-16 h-16 shrink-0 flex items-center justify-center mx-auto">
                                                <img src="{{ asset('images/' . $rank2->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank2->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                                <img src="{{ $rank2->AnhDaiDien ? asset('storage/' . $rank2->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank2->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                            </div>
                                        @else
                                            <div class="relative w-12 h-12 shrink-0 rounded-full border-2 border-slate-350 flex items-center justify-center overflow-hidden bg-white mx-auto shadow-md">
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
                                        <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-slate-400 border border-slate-250 text-slate-900 rounded-full text-[10px] font-black flex items-center justify-center z-20 shadow-sm">2</span>
                                    </div>
                                    <h4 class="font-extrabold text-[var(--text-primary)] text-[10px] truncate w-full mt-2 px-1">{{ $rank2->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <div class="w-full h-16 bg-gradient-to-b from-slate-100 to-slate-250 dark:from-slate-800 dark:to-slate-900 rounded-t-xl border border-b-0 border-slate-300 dark:border-slate-700 silver-glow flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-slate-400/5"></div>
                                    <span class="text-3xl font-black text-slate-400/40 dark:text-slate-650/40 font-display">2</span>
                                    <div class="text-[8px] font-extrabold text-slate-600 dark:text-slate-300 bg-white/90 dark:bg-slate-800 px-1.5 py-0.5 rounded-full mt-0.5 shadow-sm border border-slate-200/50">
                                        {{ number_format($rank2->TongDiem) }} <span class="text-[7px]">XP</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- TOP 1 (GOLD) -->
                        <div class="flex-1 flex flex-col items-center -mx-1.5 z-10">
                            @if(count($topUsers) >= 1)
                                @php $rank1 = $topUsers[0]; @endphp
                                <div class="flex flex-col items-center mb-2.5 w-full text-center">
                                    <div class="relative">
                                        <i class="fas fa-crown text-amber-500 absolute -top-5 left-1/2 -translate-x-1/2 text-2xl animate-float-crown z-20 drop-shadow-sm"></i>
                                        <div class="absolute inset-0 bg-amber-450 rounded-full blur-md opacity-25 animate-pulse"></div>
                                        @if($rank1->Vien)
                                            <div class="relative w-20 h-20 shrink-0 flex items-center justify-center mx-auto">
                                                <img src="{{ asset('images/' . $rank1->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank1->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                                <img src="{{ $rank1->AnhDaiDien ? asset('storage/' . $rank1->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank1->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                            </div>
                                        @else
                                            <div class="relative w-16 h-16 shrink-0 rounded-full border-2 border-amber-450 glow-gold-avatar flex items-center justify-center overflow-hidden bg-white mx-auto shadow-md">
                                                @if($rank1->AnhDaiDien)
                                                    <img src="{{ asset('storage/' . $rank1->AnhDaiDien) }}" 
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                         alt="Avatar" class="w-full h-full object-cover">
                                                    <div class="hidden w-full h-full bg-gradient-to-br from-amber-400 to-yellow-500 text-white items-center justify-center font-black text-xl">
                                                        {{ strtoupper(substr($rank1->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-amber-400 to-yellow-500 text-white flex items-center justify-center font-black text-xl">
                                                        {{ strtoupper(substr($rank1->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-b from-amber-300 to-amber-500 border border-amber-250 text-slate-900 rounded-full text-xs font-black flex items-center justify-center z-20 shadow-md">1</span>
                                    </div>
                                    <h4 class="font-extrabold text-[var(--brand-secondary)] dark:text-amber-400 text-[11px] truncate w-full mt-2 px-1">{{ $rank1->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <div class="w-full h-22 bg-gradient-to-b from-amber-100 to-amber-250 dark:from-amber-900/80 dark:to-amber-700/85 rounded-t-xl border border-b-0 border-amber-300 dark:border-amber-600 gold-glow flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-amber-450/10"></div>
                                    <span class="text-4xl font-black text-amber-600/40 dark:text-slate-900/40 font-display">1</span>
                                    <div class="text-[9px] font-black text-amber-900 dark:text-amber-150 bg-white dark:bg-slate-800 px-2 py-0.5 rounded-full mt-0.5 shadow border border-amber-200">
                                        {{ number_format($rank1->TongDiem) }} <span class="text-[7px]">XP</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- TOP 3 (BRONZE) -->
                        <div class="flex-1 flex flex-col items-center">
                            @if(count($topUsers) >= 3)
                                @php $rank3 = $topUsers[2]; @endphp
                                <div class="flex flex-col items-center mb-2.5 z-10 transition-transform duration-300 hover:-translate-y-1 w-full text-center">
                                    <div class="relative">
                                        @if($rank3->Vien)
                                            <div class="relative w-16 h-16 shrink-0 flex items-center justify-center mx-auto">
                                                <img src="{{ asset('images/' . $rank3->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $rank3->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md">
                                                <img src="{{ $rank3->AnhDaiDien ? asset('storage/' . $rank3->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($rank3->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                            </div>
                                        @else
                                            <div class="relative w-12 h-12 shrink-0 rounded-full border-2 border-orange-500 flex items-center justify-center overflow-hidden bg-white mx-auto shadow-md">
                                                @if($rank3->AnhDaiDien)
                                                    <img src="{{ asset('storage/' . $rank3->AnhDaiDien) }}" 
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                         alt="Avatar" class="w-full h-full object-cover">
                                                    <div class="hidden w-full h-full bg-gradient-to-br from-orange-400 to-orange-505 text-white items-center justify-center font-bold text-sm">
                                                        {{ strtoupper(substr($rank3->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-505 text-white flex items-center justify-center font-bold text-sm">
                                                        {{ strtoupper(substr($rank3->UserName ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-orange-550 border border-orange-350 text-slate-955 rounded-full text-[10px] font-black flex items-center justify-center z-20 shadow-sm">3</span>
                                    </div>
                                    <h4 class="font-extrabold text-[var(--text-primary)] text-[10px] truncate w-full mt-2 px-1">{{ $rank3->UserName ?? 'Người dùng' }}</h4>
                                </div>
                                <div class="w-full h-14 bg-gradient-to-b from-orange-50 to-orange-200 dark:from-orange-900/60 dark:to-orange-950 rounded-t-xl border border-b-0 border-orange-300 dark:border-orange-800 bronze-glow flex flex-col items-center justify-center relative overflow-hidden podium-pillar">
                                    <div class="absolute inset-0 bg-orange-450/5"></div>
                                    <span class="text-3xl font-black text-orange-400/40 dark:text-orange-700/40 font-display">3</span>
                                    <div class="text-[8px] font-extrabold text-orange-850 dark:text-orange-300 bg-white/90 dark:bg-slate-800 px-1.5 py-0.5 rounded-full mt-0.5 shadow-sm border border-orange-200/50">
                                        {{ number_format($rank3->TongDiem) }} <span class="text-[7px]">XP</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CURRENT USER PERSONAL GLORY CARD -->
                @if($currentUser)
                    <div class="mb-2 p-3.5 rounded-2xl glass-panel border border-purple-500/30 current-user-card-glow transition-all duration-300 relative overflow-hidden bg-purple-500/5 z-10">
                        <div class="absolute top-0 right-0 bg-purple-500/10 text-purple-700 dark:text-purple-300 text-[8px] font-black uppercase px-2.5 py-0.5 rounded-bl-xl border-l border-b border-purple-500/15 tracking-wider">
                            Của Bạn
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-600 p-[1.5px] shadow-sm shrink-0 flex items-center justify-center">
                                    @if($currentUser->AnhDaiDien)
                                        <img src="{{ asset('storage/' . $currentUser->AnhDaiDien) }}" alt="Avatar" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <div class="w-full h-full bg-slate-700 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($currentUser->UserName ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-black text-xs text-[var(--text-primary)] flex items-center gap-1.5">
                                        {{ $currentUser->UserName }}
                                    </h4>
                                    <p class="text-[9px] text-[var(--text-muted)] font-semibold mt-0.5">
                                        Hạng hiện tại: <span class="text-purple-600 dark:text-purple-400 font-black">#{{ $myRank }}</span> • {{ number_format($currentUser->TongDiem) }} XP
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-1">
                                @if($myRank == 1)
                                    <div class="text-[9px] font-black text-amber-500 flex items-center gap-1">
                                        👑 Bạn đang thống trị bảng xếp hạng! Thật xuất sắc!
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-800 h-1 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-amber-400 to-yellow-500 h-full rounded-full w-full"></div>
                                    </div>
                                @else
                                    @php
                                        // If they aren't on top, find user above
                                        $pointsDiff = $userAbove ? ($userAbove->TongDiem - $currentUser->TongDiem) : 0;
                                        $ratio = $userAbove && $userAbove->TongDiem > 0 ? min(100, round(($currentUser->TongDiem / $userAbove->TongDiem) * 100)) : 15;
                                    @endphp
                                    <div class="text-[9px] font-bold text-[var(--text-secondary)] flex items-center justify-between">
                                        <span>Tiến trình lên hạng tiếp theo</span>
                                        <span class="text-purple-600 dark:text-purple-400">Thiếu {{ number_format($pointsDiff) }} XP để vượt #{{ $myRank - 1 }}</span>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-800 h-1 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-full rounded-full" style="width: {{ $ratio }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Login Callout -->
                    <div class="mb-2 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-dashed border-slate-250 dark:border-slate-800 flex items-center justify-between z-10 relative">
                        <span class="text-[10px] text-[var(--text-secondary)] font-bold">Đăng nhập để đua top và theo dõi vị trí của bạn!</span>
                        <a href="{{ route('login') }}" class="px-2.5 py-1 bg-gradient-to-r from-purple-600 to-indigo-650 text-white rounded-lg text-[9px] font-black shadow-md hover:scale-105 transition-transform">Đua Top</a>
                    </div>
                @endif

                <!-- LIST RANKS 4-10 -->
                <div class="space-y-2.5 max-h-[360px] overflow-y-auto custom-scrollbar pr-1 relative z-10">
                    @if(isset($topUsers) && count($topUsers) > 0)
                        @foreach($topUsers as $index => $user)
                        @if($index >= 3)
                        @php
                            $isRowCurrentUser = ($currentUser && $user->UserID == $currentUser->UserID);
                            
                            // Dynamic stable streak using User ID
                            $streak = (abs(crc32($user->UserID)) % 10) + 3;

                            // Dynamic league tags
                            if ($index == 3 || $index == 4) {
                                $leagueName = 'Bạch Kim';
                                $leagueBg = 'bg-slate-100 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 border-slate-200/50 dark:border-slate-800';
                            } else {
                                $leagueName = 'Cao Thủ';
                                $leagueBg = 'bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/40';
                            }
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-white/50 dark:bg-[var(--brand-card-bg)] border {{ $isRowCurrentUser ? 'border-purple-500 bg-purple-500/5 dark:bg-purple-950/20 shadow-inner' : 'border-slate-100 dark:border-slate-850' }} hover:border-purple-400 dark:hover:border-purple-500/60 rounded-xl transition-all duration-200 group">
                            <!-- Left: Rank & Avatar & Username -->
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-6 h-6 flex items-center justify-center font-bold text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100/70 dark:bg-slate-800/80 rounded-full rank-number">
                                    {{ $index + 1 }}
                                </span>
                                
                                <div class="relative shrink-0">
                                    @if($user->Vien)
                                        <div class="relative w-9 h-9 flex items-center justify-center">
                                            <img src="{{ asset('images/' . $user->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $user->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full">
                                            <img src="{{ $user->AnhDaiDien ? asset('storage/' . $user->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($user->UserName).'&background=random&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-700 relative flex items-center justify-center">
                                            @if($user->AnhDaiDien)
                                                <img src="{{ asset('storage/' . $user->AnhDaiDien) }}" 
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');"
                                                     alt="Avatar" class="w-full h-full object-cover">
                                                <div class="hidden w-full h-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white items-center justify-center font-bold text-[10px]">
                                                    {{ strtoupper(substr($user->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center font-bold text-[10px]">
                                                    {{ strtoupper(substr($user->UserName ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-extrabold text-xs text-slate-800 dark:text-[var(--text-primary)] truncate max-w-[85px]">{{ $user->UserName ?? 'Ẩn danh' }}</h4>
                                        <span class="px-1.5 py-0.5 border {{ $leagueBg }} text-[7px] font-black rounded-full uppercase tracking-wider shrink-0">{{ $leagueName }}</span>
                                    </div>
                                    <span class="text-[9px] text-orange-500 dark:text-orange-400 font-bold flex items-center gap-0.5">🔥 {{ $streak }} ngày</span>
                                </div>
                            </div>
                            
                            <!-- Right: Score XP -->
                            <div class="flex items-center gap-1 bg-purple-50 dark:bg-purple-900/30 border border-purple-100/50 dark:border-purple-800/50 px-2 py-1 rounded-lg text-purple-700 dark:text-purple-300 font-black text-[10px] shrink-0">
                                <span>{{ number_format($user->TongDiem) }}</span>
                                <i class="fas fa-star text-amber-500 text-[8px]"></i>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    @else
                        <div class="text-center py-8 text-[var(--text-muted)] text-[10px] font-mono bg-slate-50/50 rounded-2xl border border-slate-200/50">
                            [ CHƯA CÓ THÀNH VIÊN ĐUA TOP ]
                        </div>
                    @endif
                </div>

                <!-- Footer Stats Info line -->
                <div class="border-t border-[var(--brand-border)] pt-4 flex items-center justify-between text-[8px] font-mono text-[var(--text-muted)] relative z-10">
                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> SYSTEM OK</span>
                    <a href="{{ route('public.rankings') }}" class="hover:text-[var(--brand-secondary)] transition-colors flex items-center gap-0.5">
                        Xem chi tiết <i class="fas fa-external-link-alt text-[7px]"></i>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Custom Interactive JS logic -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Note: Audio visualizer logic was removed because it was replaced with Top Categories widget.

        // Survey reset function
        window.resetSurvey = function() {
            if (confirm('Bạn có muốn thực hiện lại khảo sát để nhận lộ trình học tập mới?')) {
                document.getElementById('resetSurveyForm').submit();
            }
        };
    });
</script>
@endpush
@endsection