@extends('layouts.app')

@section('title', 'AI Voice & Phiên âm IPA - ListenUp')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header Lab Title -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-display font-black tracking-tight text-slate-800 mb-2">
            AI Voice <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">& Phiên Âm IPA</span>
        </h1>
        <p class="text-slate-500 font-medium text-base">Chuyển đổi văn bản tiếng Anh thành giọng đọc tự nhiên và phiên âm quốc tế IPA</p>
    </div>

    <!-- Main Generator Card (Premium Light Glassmorphic Card) -->
    <div class="bg-white/90 backdrop-blur-xl border-4 border-indigo-100/80 rounded-[2.5rem] p-8 md:p-10 shadow-[0_20px_50px_rgba(148,163,184,0.12)] relative overflow-hidden text-slate-800">
        <!-- Floating background glowing spots (Soft Pastels) -->
        <div class="absolute -top-12 -left-12 w-64 h-64 bg-indigo-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-purple-200/30 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Input Section -->
        <div class="space-y-6 relative z-10">
            <div>
                <label for="text-input" class="block text-xs font-black text-indigo-600 uppercase tracking-widest mb-3">Văn bản tiếng Anh</label>
                <textarea id="text-input" 
                          placeholder="Nhập câu hoặc từ tiếng Anh tại đây... (Ví dụ: Hello, welcome to ListenUp!)"
                          class="w-full h-36 bg-slate-50/50 border border-slate-200/80 focus:border-indigo-500 focus:bg-white rounded-2xl p-5 text-slate-800 font-medium placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 resize-none transition-all duration-300 text-lg leading-relaxed shadow-inner"></textarea>
            </div>

            <!-- Options & Submit Action -->
            <div class="flex flex-col sm:flex-row gap-6 justify-between items-start sm:items-center border-t border-slate-100/60 pt-6">
                <!-- Gender selection pills -->
                <div class="flex flex-col gap-2">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Giọng đọc (Voice Gender)</span>
                    <div class="flex gap-2">
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="gender" value="female" checked class="sr-only peer">
                            <div class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600 bg-white peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:border-slate-300 transition-all flex items-center gap-2 shadow-sm">
                                <i class="fas fa-venus"></i> Giọng Nữ
                            </div>
                        </label>
                        <label class="cursor-pointer select-none">
                            <input type="radio" name="gender" value="male" class="sr-only peer">
                            <div class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-600 bg-white peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:border-slate-300 transition-all flex items-center gap-2 shadow-sm">
                                <i class="fas fa-mars"></i> Giọng Nam
                            </div>
                        </label>
                    </div>
                </div>

                <button id="generate-btn" 
                        class="w-full sm:w-auto flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-extrabold text-base tracking-wider px-10 py-4 rounded-xl shadow-lg shadow-indigo-500/20 active:translate-y-[2px] transition-all duration-200 cursor-pointer min-w-[180px]">
                    <span class="btn-text flex items-center gap-2"><i class="fas fa-magic text-amber-200"></i> Tạo giọng đọc</span>
                    <div class="hidden loader w-5 h-5 border-3 border-white/30 border-t-white rounded-full animate-spin" id="loader"></div>
                </button>
            </div>
        </div>

        <!-- Result Section (Initially Hidden) -->
        <div id="result-section" class="hidden mt-10 pt-10 border-t border-slate-200/80 space-y-8 relative z-10 animate-fade-in">
            
            <!-- IPA output -->
            <div class="space-y-3">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Phát âm quốc tế (IPA)</h3>
                <div class="bg-indigo-50/50 border border-indigo-100/60 rounded-2xl p-5 flex items-center justify-between shadow-inner relative">
                    <span id="ipa-output" class="text-2xl font-serif text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-600 tracking-wide font-black select-all whitespace-pre-line"></span>
                    <button id="copy-ipa" 
                            title="Sao chép phiên âm"
                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 hover:border-indigo-400 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition-all active:scale-95 cursor-pointer shadow-sm">
                        <i class="far fa-copy"></i>
                    </button>
                </div>
            </div>

            <!-- Audio Player Output -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Trình phát âm thanh</h3>
                <div class="bg-indigo-50/50 border border-indigo-100/60 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6 justify-between shadow-inner">
                    <div class="w-full md:flex-grow">
                        <audio id="audio-player" class="w-full custom-light-audio" controls></audio>
                    </div>
                    <div class="shrink-0 w-full md:w-auto">
                        <a id="download-btn" 
                           href="#" 
                           download 
                           class="w-full md:w-auto flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-600 font-bold px-6 py-3.5 rounded-xl transition-all cursor-pointer text-sm shadow-sm">
                            <i class="fas fa-download text-indigo-500"></i> Tải file MP3
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom light theme audio tweaks if needed */
    .custom-light-audio {
        outline: none;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const textInput = document.getElementById('text-input');
    const generateBtn = document.getElementById('generate-btn');
    const btnText = generateBtn.querySelector('.btn-text');
    const loader = document.getElementById('loader');
    const resultSection = document.getElementById('result-section');
    const ipaOutput = document.getElementById('ipa-output');
    const audioPlayer = document.getElementById('audio-player');
    const downloadBtn = document.getElementById('download-btn');
    const copyIpaBtn = document.getElementById('copy-ipa');

    const generate = async () => {
        const text = textInput.value.trim();
        if (!text) {
            alert('Vui lòng nhập văn bản tiếng Anh.');
            return;
        }

        // Get selected gender value
        const genderInput = document.querySelector('input[name="gender"]:checked');
        const gender = genderInput ? genderInput.value : 'female';

        // Show loading state
        btnText.classList.add('invisible');
        loader.classList.remove('hidden');
        generateBtn.disabled = true;

        try {
            const response = await fetch('{{ route("public.listen.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ text, gender }),
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.error || 'Yêu cầu chuyển đổi thất bại.');
            }

            const data = await response.json();

            const preferredSpeed = {{ auth()->check() ? (auth()->user()->preferred_speed ?? 1.0) : 1.0 }};

            // Update UI
            ipaOutput.textContent = data.ipa;
            audioPlayer.src = data.audio_url;
            downloadBtn.href = data.audio_url;

            // Apply speed on loaded metadata
            audioPlayer.onloadedmetadata = () => {
                audioPlayer.playbackRate = preferredSpeed;
            };

            resultSection.classList.remove('hidden');
            
            // Auto play audio
            audioPlayer.play();

        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Có lỗi xảy ra trong quá trình xử lý. Hãy thử lại.');
        } finally {
            // Restore button state
            btnText.classList.remove('invisible');
            loader.classList.add('hidden');
            generateBtn.disabled = false;
        }
    };

    generateBtn.addEventListener('click', generate);

    // Enter key to submit (Shift+Enter for new line)
    textInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            generate();
        }
    });

    // Copy IPA to clipboard
    copyIpaBtn.addEventListener('click', () => {
        const ipaText = ipaOutput.textContent;
        navigator.clipboard.writeText(ipaText).then(() => {
            const icon = copyIpaBtn.querySelector('i');
            icon.classList.remove('far', 'fa-copy');
            icon.classList.add('fas', 'fa-check', 'text-emerald-500');
            
            setTimeout(() => {
                icon.classList.remove('fas', 'fa-check', 'text-emerald-500');
                icon.classList.add('far', 'fa-copy');
            }, 2000);
        });
    });
});
</script>
@endsection
