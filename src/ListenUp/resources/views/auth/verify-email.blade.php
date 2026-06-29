<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email - ListenUp</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
                        background: '#F8FAFC',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-background min-h-screen flex items-center justify-center relative overflow-hidden py-12">
    
    <div class="absolute w-96 h-96 bg-accent/20 rounded-full blur-[80px] top-[-10%] left-[-10%] z-0"></div>
    <div class="absolute w-96 h-96 bg-secondary/20 rounded-full blur-[80px] bottom-[-10%] right-[-10%] z-0"></div>

    <div class="w-full max-w-md p-8 relative z-10">
        <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)]">
            
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2 text-primary font-display font-bold text-2xl mb-6">
                    <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    Xác thực Email
                </a>
                <h2 class="text-xl font-display font-bold text-gray-800">Kiểm tra hộp thư của bạn</h2>
                <p class="text-gray-500 text-sm mt-2">
                    Chúng tôi đã gửi một liên kết xác nhận đến địa chỉ email của bạn. 
                    Vui lòng nhấn vào liên kết đó để kích hoạt tài khoản trước khi tiếp tục.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-white font-medium hover:bg-primary/90 hover:shadow-lg transition-all flex justify-center items-center gap-2">
                    <i class="fas fa-paper-plane text-sm"></i> Gửi lại link xác thực
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-all flex justify-center items-center gap-2">
                    <i class="fas fa-sign-out-alt text-sm"></i> Đăng xuất
                </button>
            </form>
        </div>
    </div>
</body>
</html>
