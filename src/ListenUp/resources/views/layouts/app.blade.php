<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Immediately apply dark mode to avoid layout flashing
        (function() {
            const isGamePage = window.location.pathname.includes('/games');
            const storedTheme = localStorage.getItem('listenup-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (!isGamePage && (storedTheme === 'dark' || (!storedTheme && prefersDark))) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function setThemeGlobal(theme) {
            localStorage.setItem('listenup-theme', theme);
            const isGamePage = window.location.pathname.includes('/games');
            if (!isGamePage && theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            window.dispatchEvent(new Event('theme-changed'));
        }
    </script>
    <title>@yield('title', 'ListenUp - Elevate Your English')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0B1B3D',
                        secondary: '#7C3AED',
                        accent: '#06B6D4',
                        surface: '#FFFFFF',
                        background: '#F8FAFC',
                    },
                    boxShadow: {
                        'glass': '0 4px 30px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 20px rgba(6, 182, 212, 0.4)',
                        'card': '0 10px 40px -10px rgba(0,0,0,0.08)',
                    },
                    backgroundImage: {
                        'hero-gradient': 'linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%)',
                        'purple-gradient': 'linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%)',
                    }
                }
            }
        }
    </script>
    
    <!-- Bootstrap CSS (Dùng tạm để các trang cũ không bị vỡ giao diện ngay lập tức) -->
    <!-- Khi nào bạn chuyển hết sang Tailwind thì hãy xóa dòng này đi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --brand-hue: 205;
            --brand-primary: hsl(var(--brand-hue), 85%, 15%);
            --brand-secondary: hsl(var(--brand-hue), 90%, 50%);
            --brand-accent: hsl(190, 95%, 45%);
            --brand-bg: #f8fafc;
            --brand-card-bg: rgba(255, 255, 255, 0.7);
            --brand-border: rgba(15, 23, 42, 0.08);
            --brand-border-hover: rgba(15, 23, 42, 0.15);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --white-const: #ffffff;
        }
        .dark {
            --brand-primary: hsl(var(--brand-hue), 90%, 95%);
            --brand-secondary: hsl(var(--brand-hue), 100%, 60%);
            --brand-bg: #090d16;
            --brand-card-bg: rgba(13, 20, 35, 0.65);
            --brand-border: rgba(255, 255, 255, 0.08);
            --brand-border-hover: rgba(255, 255, 255, 0.15);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --white-const: #0f172a;
        }

        .cyber-panel {
            background: var(--brand-card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--brand-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.04);
            position: relative;
        }
        
        .corner-decor::before, .corner-decor::after {
            content: '+';
            position: absolute;
            font-family: monospace;
            font-size: 10px;
            color: var(--brand-secondary);
            opacity: 0.5;
        }
        .corner-decor::before { top: 6px; left: 8px; }
        .corner-decor::after { bottom: 6px; right: 8px; }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 50px var(--brand-card-bg) inset !important;
            -webkit-text-fill-color: var(--text-primary) !important;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .text-gradient {
            background: linear-gradient(135deg, #0B1B3D 0%, #4F46E5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .hover-lift {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
        }
        /* Thin scrollbar for dropdown menus */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
        
        /* Ghi đè Bootstrap để không hỏng giao diện Tailwind */
        body { 
            font-family: 'Inter', sans-serif !important; 
            background-color: var(--brand-bg) !important; 
            color: var(--text-primary) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        a { text-decoration: none !important; }
        
        /* Chỉnh lại padding top cho main để không bị che bởi navbar fix */
        .main-content-wrapper { padding-top: 104px !important; min-height: 80vh;}
    </style>
    @stack('styles')
</head>
<body class="text-gray-800 antialiased overflow-x-hidden relative">
    <div id="appContentWrapper" class="transition-all duration-300">

    <!-- Decorative Blobs -->
    <div class="blob bg-accent/20 w-96 h-96 rounded-full top-[-100px] left-[-100px]"></div>
    <div class="blob bg-secondary/20 w-[500px] h-[500px] rounded-full top-[20%] right-[-150px]" style="animation-delay: 2s"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 text-primary font-display font-bold text-2xl tracking-tight">
                    <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center">
                        <i class="fas fa-headphones text-sm"></i>
                    </div>
                    ListenUp
                </a>
                <div class="hidden md:flex items-center gap-6 font-medium text-gray-600 relative">
                    <!-- Dropdown Cấp độ -->
                    <div class="relative group">
                        <button class="hover:text-primary transition flex items-center gap-1 py-2">
                            <i class="fas fa-signal text-sm"></i> Cấp độ nghe <i class="fas fa-chevron-down text-xs ml-1 opacity-70 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute top-full left-0 pt-2 w-56 z-50 hidden group-hover:block transition-all duration-200">
                            <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
                                <a href="{{ route('public.levels') }}" class="block px-4 py-3 text-sm text-primary font-bold hover:bg-gray-50 border-b border-gray-50">Tất cả cấp độ</a>
                                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                    @if(isset($all_levels))
                                        @foreach($all_levels as $lvl)
                                            <a href="{{ route('public.levels.show', $lvl->MaCDN) }}" class="block px-4 py-2.5 text-sm text-gray-600 hover:text-primary hover:bg-gray-50/80 transition-colors">{{ $lvl->TenCDN }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Chủ đề -->
                    <div class="relative group">
                        <button class="hover:text-primary transition flex items-center gap-1 py-2">
                            <i class="fas fa-headphones-alt text-sm"></i> Chủ đề nghe <i class="fas fa-chevron-down text-xs ml-1 opacity-70 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute top-full left-0 pt-2 w-56 z-50 hidden group-hover:block transition-all duration-200">
                            <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
                                <a href="{{ route('public.topics') }}" class="block px-4 py-3 text-sm text-primary font-bold hover:bg-gray-50 border-b border-gray-50">Tất cả chủ đề</a>
                                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                    @if(isset($all_topics))
                                        @foreach($all_topics as $topic)
                                            <a href="{{ route('public.topics.detail', $topic->MaCD) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:text-primary hover:bg-gray-50/80 transition-colors">
                                                <i class="{{ $topic->icon_class }} text-gray-400 group-hover:text-primary transition-colors text-xs w-4"></i>
                                                <span>{{ $topic->TenCD }}</span>
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ Route::has('public.games') ? route('public.games') : '#' }}" class="hover:text-primary transition flex items-center gap-1 py-2">
                        <i class="fas fa-gamepad text-sm"></i> Trò chơi
                    </a>
                    <a href="{{ Route::has('public.rankings') ? route('public.rankings') : '#' }}" class="hover:text-primary transition flex items-center gap-1 py-2">
                        <i class="fas fa-crown text-sm text-amber-500"></i> Xếp hạng
                    </a>
                    <a href="{{ route('public.listen') }}" class="hover:text-primary transition flex items-center gap-1 py-2">
                        <i class="fas fa-volume-up text-sm"></i> Nghe
                    </a>
                    
                    <!-- Dropdown Giao diện -->
                    <div class="relative group">
                        <button class="hover:text-primary transition flex items-center gap-1 py-2 focus:outline-none">
                            <i class="fas fa-palette text-sm"></i> Giao diện <i class="fas fa-chevron-down text-xs ml-1 opacity-70 group-hover:rotate-180 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute top-full left-0 pt-2 w-36 z-50 hidden group-hover:block transition-all duration-200 animate-fade-in">
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-card border border-gray-100 dark:border-slate-700 overflow-hidden">
                                <button onclick="setThemeGlobal('light')" class="w-full text-left px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2 border-0 bg-transparent">
                                    <i class="fas fa-sun text-amber-500 w-4"></i> Sáng
                                </button>
                                <button onclick="setThemeGlobal('dark')" class="w-full text-left px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2 border-0 bg-transparent">
                                    <i class="fas fa-moon text-indigo-400 w-4"></i> Tối
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="hidden md:block text-gray-500 hover:text-primary transition">
                    <i class="fas fa-search text-lg"></i>
                </button>
                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2.5 bg-white px-4 py-2.5 rounded-full shadow-sm border border-gray-100 hover:shadow-md transition">
                            @if(Auth::user()->Vien)
                                <div class="relative w-11 h-11 flex items-center justify-center shrink-0">
                                    <img src="{{ asset('images/' . Auth::user()->Vien) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . Auth::user()->Vien) }}';" class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full">
                                    <img src="{{ Auth::user()->AnhDaiDien ? asset('storage/' . Auth::user()->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->UserName).'&background=0D8ABC&color=fff' }}" alt="Avatar" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                </div>
                            @else
                                @if(Auth::user()->AnhDaiDien)
                                    <img src="{{ asset('storage/' . Auth::user()->AnhDaiDien) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->UserName) }}&background=0D8ABC&color=fff" alt="Avatar" class="w-9 h-9 rounded-full">
                                  @endif
                            @endif
                            <span class="font-medium text-sm">{{ Auth::user()->UserName }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        <!-- Dropdown -->
                        <div class="absolute right-0 top-full pt-2 w-48 z-50 hidden group-hover:block transition-all duration-200">
                            <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-chart-line w-5"></i> Dashboard Admin</a>
                                    <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-user-edit w-5"></i> Hồ sơ Admin</a>
                                    <div class="h-px bg-gray-100"></div>
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-home w-5"></i> Tổng quan User</a>
                                    <a href="{{ route('user.profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-user-circle w-5"></i> Hồ sơ User</a>
                                    <a href="{{ route('user.lessons') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-book w-5"></i> Bài học</a>
                                @else
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-home w-5"></i> Tổng quan</a>
                                    <a href="{{ route('user.profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-user-edit w-5"></i> Chỉnh sửa hồ sơ</a>
                                    <a href="{{ route('user.lessons') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-book w-5"></i> Bài học</a>
                                @endif
                                <div class="h-px bg-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-sign-out-alt w-5"></i> Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <button onclick="openAuthModal('login')" class="font-medium text-gray-600 hover:text-primary transition px-2 focus:outline-none bg-transparent border-0">Đăng nhập</button>
                    <button onclick="openAuthModal('register')" class="bg-primary text-white px-5 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition shadow-lg shadow-primary/30 focus:outline-none border-0">Đăng ký</button>
                @endauth
            </div>
        </div>
    </nav>

    <main class="main-content-wrapper">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-2">
                    <a href="/" class="flex items-center gap-2 text-primary font-display font-bold text-2xl mb-4">
                        <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center">
                            <i class="fas fa-headphones text-sm"></i>
                        </div>
                        ListenUp
                    </a>
                    <p class="text-gray-500 text-sm max-w-sm">Nền tảng hiện đại giúp bạn luyện nghe tiếng Anh thông qua các bài học tương tác, đánh giá thời gian thực và nội dung phong phú.</p>
                </div>
                <div>
                    <h4 class="font-bold text-primary mb-4">Nền tảng</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="{{ route('public.levels') }}" class="hover:text-primary transition">Cấp độ</a></li>
                        <li><a href="{{ route('public.topics') }}" class="hover:text-primary transition">Chủ đề</a></li>
                        <li><a href="{{ Route::has('public.games') ? route('public.games') : '#' }}" class="hover:text-primary transition">Trò chơi</a></li>
                        <li><a href="{{ Route::has('public.rankings') ? route('public.rankings') : '#' }}" class="hover:text-primary transition">Bảng xếp hạng</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-primary mb-4">Pháp lý</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-primary transition">Điều khoản dịch vụ</a></li>
                        <li><a href="#" class="hover:text-primary transition">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-400 text-sm">© {{ date('Y') }} ListenUp Platform. All rights reserved.</p>
                <div class="flex gap-4 text-gray-400">
                    <a href="#" class="hover:text-primary transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-primary transition"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-primary transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
    </div> <!-- End of appContentWrapper -->

    <!-- Global Auth Modal Overlay -->
    <div id="authModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/40 dark:bg-black/60 backdrop-blur-[4px] transition-all duration-300 animate-fade-in" onclick="if(event.target === this) closeAuthModal()">
        <div class="w-full max-w-[360px] relative">
            
            <!-- Close button on top right of the modal container -->
            <button onclick="closeAuthModal()" class="absolute -top-10 right-0 text-white hover:text-[var(--brand-secondary)] transition-colors text-lg focus:outline-none flex items-center gap-1.5 font-mono text-xs border-0 bg-transparent">
                CLOSE <i class="fas fa-times"></i>
            </button>
            
            <!-- Main Form Panel -->
            <div class="cyber-panel p-5 rounded-2xl border border-[var(--brand-border)] max-h-[85vh] overflow-y-auto custom-scrollbar space-y-4">
                <div class="corner-decor"></div>
                

                <!-- Tab Segmented Control -->
                <div class="relative flex bg-[var(--brand-bg)] p-1 rounded-xl border border-[var(--brand-border)]">
                    <div id="authTabActiveIndicator" class="absolute top-1 bottom-1 left-1 w-[calc(50%-4px)] bg-[var(--white-const)] border border-[var(--brand-border)] rounded-lg shadow-sm transition-all duration-350 ease-out z-0"></div>
                    
                    <button id="tabLoginBtn" onclick="switchTab('login')" class="flex-1 py-1.5 relative z-10 text-xs font-bold font-mono flex items-center justify-center gap-1.5 transition-colors focus:outline-none text-[var(--brand-secondary)] border-0 bg-transparent">
                        LOG IN
                    </button>
                    <button id="tabRegisterBtn" onclick="switchTab('register')" class="flex-1 py-1.5 relative z-10 text-xs font-bold font-mono flex items-center justify-center gap-1.5 transition-colors focus:outline-none text-[var(--text-secondary)] border-0 bg-transparent">
                        SIGN UP
                    </button>
                </div>

                <!-- LOGIN FORM -->
                <div id="modalLoginForm" class="space-y-4">
                    <div class="text-center">
                        <h2 class="text-xl  font-extrabold text-[var(--text-primary)]">Chào mừng trở lại</h2>
                        <p class="text-[var(--text-secondary)] text-xs mt-1">Đăng nhập để tiếp tục hành trình học tập</p>
                    </div>

                    @if($errors->has('email') || $errors->has('password') || session('login_error'))
                        <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-xs font-medium">
                            {{ $errors->first('email') ?: ($errors->first('password') ?: session('login_error')) }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4" autocomplete="on">
                        @csrf

                        <div>
                            <label for="login_email" class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-1.5">EMAIL</label>
                            <input type="email" id="login_email" name="email" value="{{ old('email') }}" required placeholder="your@email.com" autocomplete="email"
                                class="w-full px-4 py-3 rounded-xl border border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--brand-secondary)] focus:ring-1 focus:ring-[var(--brand-secondary)] transition-all outline-none text-sm">
                        </div>

                        <div>
                            <label for="login_password" class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-1.5">MẬT KHẨU</label>
                            <input type="password" id="login_password" name="password" required placeholder="••••••••" autocomplete="current-password"
                                class="w-full px-4 py-3 rounded-xl border border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--brand-secondary)] focus:ring-1 focus:ring-[var(--brand-secondary)] transition-all outline-none text-sm">
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--brand-secondary)] focus:ring-[var(--brand-secondary)] cursor-pointer">
                                <span class="text-xs text-[var(--text-secondary)] font-medium">Ghi nhớ đăng nhập</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-[var(--brand-secondary)] text-white font-bold text-xs font-mono tracking-wider hover:bg-[var(--brand-secondary)]/95 hover:shadow-lg hover:shadow-[var(--brand-secondary)]/15 active:scale-[0.99] transition-all flex justify-center items-center gap-2 border-0">
                            LOG IN <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>

                        <div class="relative flex items-center justify-center py-2">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[var(--brand-border)]"></div>
                            </div>
                            <div class="relative px-3 bg-[var(--brand-card-bg)] text-[10px] font-mono text-[var(--text-muted)] uppercase tracking-wider">OR SIGN IN WITH</div>
                        </div>

                        <a href="{{ route('auth.google') }}" class="w-full py-2.5 rounded-xl bg-[var(--white-const)] text-[var(--text-primary)] font-bold text-xs font-mono tracking-wider border border-[var(--brand-border)] hover:border-[var(--brand-border-hover)] hover:bg-[var(--brand-bg)] transition-all flex justify-center items-center gap-2 shadow-sm">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4 shrink-0">
                            GOOGLE
                        </a>
                    </form>

                </div>

                <!-- REGISTER FORM -->
                <div id="modalRegisterForm" class="space-y-4 hidden">
                    <div class="text-center">
                        <h2 class="text-xl font-extrabold text-[var(--text-primary)]">Tạo tài khoản mới</h2>
                        <p class="text-[var(--text-secondary)] text-xs mt-1">Bắt đầu hành trình học tập của bạn</p>
                    </div>

                    @if($errors->has('username') || $errors->has('password_confirmation'))
                        <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-500 text-xs font-medium">
                            {{ $errors->first('username') ?: $errors->first('password_confirmation') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="register_username" class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-1.5">TÊN HIỂN THỊ</label>
                            <input type="text" id="register_username" name="username" value="{{ old('username') }}" required placeholder="John Doe" 
                                class="w-full px-4 py-3 rounded-xl border border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--brand-secondary)] focus:ring-1 focus:ring-[var(--brand-secondary)] transition-all outline-none text-sm">
                        </div>

                        <div>
                            <label for="register_email" class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-1.5">EMAIL</label>
                            <input type="email" id="register_email" name="email" value="{{ old('email') }}" required placeholder="your@email.com" 
                                class="w-full px-4 py-3 rounded-xl border border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--brand-secondary)] focus:ring-1 focus:ring-[var(--brand-secondary)] transition-all outline-none text-sm">
                        </div>

                        <div>
                            <label for="register_password" class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-1.5">MẬT KHẨU</label>
                            <input type="password" id="register_password" name="password" required placeholder="••••••••" 
                                class="w-full px-4 py-3 rounded-xl border border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--brand-secondary)] focus:ring-1 focus:ring-[var(--brand-secondary)] transition-all outline-none text-sm">
                        </div>

                        <div>
                            <label for="register_password_confirmation" class="block text-[10px] font-mono text-[var(--brand-secondary)] tracking-widest uppercase mb-1.5">XÁC NHẬN MẬT KHẨU</label>
                            <input type="password" id="register_password_confirmation" name="password_confirmation" required placeholder="••••••••" 
                                class="w-full px-4 py-3 rounded-xl border border-[var(--brand-border)] bg-[var(--brand-bg)] text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--brand-secondary)] focus:ring-1 focus:ring-[var(--brand-secondary)] transition-all outline-none text-sm">
                        </div>

                        <button type="submit" class="w-full py-3 mt-2 rounded-xl bg-[var(--brand-secondary)] text-white font-bold text-xs font-mono tracking-wider hover:bg-[var(--brand-secondary)]/95 hover:shadow-lg hover:shadow-[var(--brand-secondary)]/15 active:scale-[0.99] transition-all flex justify-center items-center gap-2 border-0">
                            SIGN UP <i class="fas fa-user-plus text-[10px]"></i>
                        </button>

                        <div class="relative flex items-center justify-center py-2">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[var(--brand-border)]"></div>
                            </div>
                            <div class="relative px-3 bg-[var(--brand-card-bg)] text-[10px] font-mono text-[var(--text-muted)] uppercase tracking-wider">OR SIGN UP WITH</div>
                        </div>

                        <a href="{{ route('auth.google') }}" class="w-full py-2.5 rounded-xl bg-[var(--white-const)] text-[var(--text-primary)] font-bold text-xs font-mono tracking-wider border border-[var(--brand-border)] hover:border-[var(--brand-border-hover)] hover:bg-[var(--brand-bg)] transition-all flex justify-center items-center gap-2 shadow-sm">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4 shrink-0">
                            GOOGLE
                        </a>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function openAuthModal(tab = 'login') {
            const modal = document.getElementById('authModal');
            const wrapper = document.getElementById('appContentWrapper');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            wrapper.classList.add('blur-[6px]', 'pointer-events-none');
            document.body.classList.add('overflow-hidden');
            
            switchTab(tab);

            // Clear login fields to prevent browser autofill on load, keeping suggestions active
            @if(!$errors->has('email') && !$errors->has('password') && !session('login_error'))
                if (tab === 'login') {
                    const clearFields = () => {
                        const emailInput = document.getElementById('login_email');
                        const passwordInput = document.getElementById('login_password');
                        if (emailInput) emailInput.value = '';
                        if (passwordInput) passwordInput.value = '';
                    };
                    clearFields();
                    setTimeout(clearFields, 50);
                    setTimeout(clearFields, 150);
                    setTimeout(clearFields, 500);
                }
            @endif
        }
        
        function closeAuthModal() {
            const modal = document.getElementById('authModal');
            const wrapper = document.getElementById('appContentWrapper');
            
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            wrapper.classList.remove('blur-[6px]', 'pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }
        
        function switchTab(tab) {
            const loginForm = document.getElementById('modalLoginForm');
            const registerForm = document.getElementById('modalRegisterForm');
            const tabLoginBtn = document.getElementById('tabLoginBtn');
            const tabRegisterBtn = document.getElementById('tabRegisterBtn');
            const activeIndicator = document.getElementById('authTabActiveIndicator');
            
            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                
                tabLoginBtn.classList.add('text-[var(--brand-secondary)]');
                tabLoginBtn.classList.remove('text-[var(--text-secondary)]');
                tabRegisterBtn.classList.add('text-[var(--text-secondary)]');
                tabRegisterBtn.classList.remove('text-[var(--brand-secondary)]');
                
                activeIndicator.style.left = '4px';
            } else {
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
                
                tabRegisterBtn.classList.add('text-[var(--brand-secondary)]');
                tabRegisterBtn.classList.remove('text-[var(--text-secondary)]');
                tabLoginBtn.classList.add('text-[var(--text-secondary)]');
                tabLoginBtn.classList.remove('text-[var(--brand-secondary)]');
                
                activeIndicator.style.left = 'calc(50% - 2px)';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');
            if (action === 'login') {
                openAuthModal('login');
                window.history.replaceState({}, document.title, window.location.pathname);
            } else if (action === 'register') {
                openAuthModal('register');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            @if($errors->has('login_error') || $errors->has('email') || $errors->has('password') || session('login_error'))
                openAuthModal('login');
            @elseif($errors->has('register_error') || $errors->has('username') || $errors->has('password_confirmation'))
                openAuthModal('register');
            @endif
        });
    </script>
    @stack('scripts')

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const preferredSpeed = {{ auth()->user()->preferred_speed ?? 1.0 }};
            
            // Function to apply playback speed to any audio elements
            const applyPlaybackSpeed = () => {
                document.querySelectorAll('audio').forEach(audio => {
                    if (audio.dataset.speedApplied) return;
                    
                    // Apply speed when metadata is loaded
                    audio.addEventListener('loadedmetadata', () => {
                        audio.playbackRate = preferredSpeed;
                    });
                    
                    // Fallback in case metadata is already loaded
                    audio.playbackRate = preferredSpeed;
                    audio.dataset.speedApplied = 'true';
                });
            };
            
            // Apply initially
            applyPlaybackSpeed();
            
            // Observe DOM changes to apply to dynamically loaded audio elements
            const observer = new MutationObserver((mutations) => {
                let hasAudio = false;
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeName === 'AUDIO' || (node.querySelector && node.querySelector('audio'))) {
                            hasAudio = true;
                        }
                    });
                });
                if (hasAudio) {
                    applyPlaybackSpeed();
                }
            });
            
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
    @if(auth()->user()->isUser())
        @include('user.components.chatbot')
    @endif
    @endauth
</body>
</html>