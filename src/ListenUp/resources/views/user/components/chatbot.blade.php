<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(99, 102, 241, 0.03);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.15);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.3);
    }
</style>

<div id="ai-chatbot-widget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <!-- Chat Drawer Panel (Glassmorphic & Hidden by default) -->
    <div id="ai-chat-panel" class="hidden w-[380px] sm:w-[420px] h-[550px] bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/60 dark:border-slate-800/80 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] flex flex-col overflow-hidden transition-all duration-300 mb-4 transform scale-95 opacity-0 origin-bottom-right">
        <!-- Panel Header -->
        <div class="p-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white flex justify-between items-center shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
                    <i class="fas fa-robot text-base text-yellow-300"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-sm tracking-tight">Trợ Lý Ảo ListenUp AI</h3>
                    <p class="text-[10px] text-indigo-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Gemini 2.5 Flash Sẵn Sàng
                    </p>
                </div>
            </div>
            <button onclick="toggleAiChatPanel()" class="text-white/80 hover:text-white transition cursor-pointer border-0 bg-transparent">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Tab Controls -->
        <div class="flex bg-slate-50 dark:bg-slate-800/60 p-1 border-b border-slate-100 dark:border-slate-850 shrink-0">
            <button onclick="switchAiTab('chat')" id="ai-tab-chat" class="flex-1 py-2 text-xs font-bold font-mono tracking-tight text-center rounded-xl bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm border-0 transition-all cursor-pointer">
                HỎI ĐÁP
            </button>
            <button onclick="switchAiTab('personalize')" id="ai-tab-personalize" class="flex-1 py-2 text-xs font-bold font-mono tracking-tight text-center rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 border-0 transition-all cursor-pointer">
                LỘ TRÌNH
            </button>
        </div>

        <!-- Content Area (Flex container, no direct scrolling to avoid layout breakage) -->
        <div class="flex-1 flex flex-col overflow-hidden p-4 bg-slate-50/30 dark:bg-slate-950/20 text-slate-800 dark:text-slate-100">
            
            <!-- TAB 1: CHATBOT HỎI ĐÁP -->
            <div id="ai-panel-chat" class="flex-1 flex flex-col overflow-hidden justify-between space-y-3">
                <!-- Chat Message History -->
                <div id="ai-chat-messages" class="flex-1 space-y-3 overflow-y-auto custom-scrollbar pr-1">
                    <!-- Welcome Msg -->
                    <div class="flex gap-2.5 items-start">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center shrink-0">
                            <i class="fas fa-robot text-xs text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800/80 rounded-2xl rounded-tl-none p-3 text-xs max-w-[80%] shadow-sm leading-relaxed">
                            Xin chào! Tôi là trợ lý học tập ListenUp. Bạn cần tôi giải thích từ vựng, ngữ pháp hay dịch câu tiếng Anh nào hôm nay?
                        </div>
                    </div>
                </div>

                <!-- Input box -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex gap-2 items-center shrink-0">
                    <input type="text" id="ai-chat-input" placeholder="Hỏi tôi bất cứ điều gì..." class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 text-slate-800 dark:text-slate-100">
                    <button onclick="sendAiChatMessage()" class="w-9 h-9 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center transition cursor-pointer border-0">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- TAB 3: CÁ NHÂN HÓA LỘ TRÌNH -->
            <div id="ai-panel-personalize" class="hidden flex-1 overflow-y-auto custom-scrollbar space-y-4 pr-1">
                <div class="bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/50 dark:border-indigo-900/30 rounded-2xl p-4 text-xs space-y-2">
                    <div class="font-bold text-indigo-700 dark:text-indigo-400">Trợ Lý Hành Trình</div>
                    <p class="text-slate-600 dark:text-slate-350 leading-relaxed">AI sẽ đọc điểm số bài nghe và tiến trình mở khóa các hòn đảo phiêu lưu của bạn để đưa ra đánh giá, đề xuất lộ trình và kế hoạch học 7 ngày tiếp theo.</p>
                    <button onclick="generateAiRoute()" id="ai-route-btn" class="w-full py-2.5 mt-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs tracking-wider transition cursor-pointer border-0">
                        PHÂN TÍCH TIẾN ĐỘ & LẬP LỘ TRÌNH
                    </button>
                </div>

                <!-- Personalization Result Display (Hidden initially) -->
                <div id="ai-route-result" class="hidden bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 space-y-4 text-xs">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Trình độ hiện tại</span>
                        <div id="ai-res-level" class="font-bold text-slate-700 dark:text-slate-200 mt-0.5"></div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Điểm mạnh</span>
                        <div id="ai-res-strengths" class="mt-1 space-y-1"></div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Điểm yếu cần cải thiện</span>
                        <div id="ai-res-weaknesses" class="mt-1 space-y-1"></div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Đề xuất bài học tiếp theo</span>
                        <div id="ai-res-rec-lessons" class="mt-1 space-y-1"></div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Kế hoạch học tập 7 ngày</span>
                        <div id="ai-res-weekly-plan" class="mt-1 space-y-1"></div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Lời khuyên</span>
                        <div id="ai-res-suggestions" class="mt-1 space-y-1 text-slate-600 dark:text-slate-350"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Floating Trigger Button -->
    <button onclick="toggleAiChatPanel()" id="ai-chatbot-btn" class="h-14 w-14 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 shadow-xl border-2 border-white dark:border-slate-800 hover:scale-105 transition-transform flex items-center justify-center text-white text-xl relative cursor-pointer">
        <i class="fas fa-robot" id="ai-robot-icon"></i>
    </button>
</div>

<script>
    // Toggle Chatbox Display
    function toggleAiChatPanel() {
        const panel = document.getElementById('ai-chat-panel');
        const robotIcon = document.getElementById('ai-robot-icon');
        
        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            // Force reflow
            panel.offsetHeight;
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
            robotIcon.classList.remove('fa-robot');
            robotIcon.classList.add('fa-chevron-down');
        } else {
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            robotIcon.classList.remove('fa-chevron-down');
            robotIcon.classList.add('fa-robot');
            setTimeout(() => {
                panel.classList.add('hidden');
            }, 300);
        }
    }

    // Switch Tabs
    function switchAiTab(tab) {
        const panels = ['chat', 'personalize'];
        panels.forEach(p => {
            const btn = document.getElementById(`ai-tab-${p}`);
            const pan = document.getElementById(`ai-panel-${p}`);
            if (p === tab) {
                btn.classList.add('bg-white', 'dark:bg-slate-800', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-sm');
                btn.classList.remove('text-slate-500', 'dark:text-slate-400');
                pan.classList.remove('hidden');
            } else {
                btn.classList.remove('bg-white', 'dark:bg-slate-800', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-sm');
                btn.classList.add('text-slate-500', 'dark:text-slate-400');
                pan.classList.add('hidden');
            }
        });
    }

    // CSRF Utility
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // CHỨC NĂNG 1: SEND CHAT MESSAGE
    async function sendAiChatMessage() {
        const input = document.getElementById('ai-chat-input');
        const messageText = input.value.trim();
        if (!messageText) return;

        const container = document.getElementById('ai-chat-messages');

        // Render User Message
        const userMsg = document.createElement('div');
        userMsg.className = 'flex gap-2.5 items-start justify-end';
        userMsg.innerHTML = `
            <div class="bg-indigo-600 text-white rounded-2xl rounded-tr-none p-3 text-xs max-w-[80%] shadow-sm leading-relaxed">
                ${escapeHtml(messageText)}
            </div>
        `;
        container.appendChild(userMsg);
        input.value = '';
        container.scrollTop = container.scrollHeight;

        // Render Loading message from AI
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'flex gap-2.5 items-start temp-loading';
        loadingMsg.innerHTML = `
            <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center shrink-0">
                <i class="fas fa-spinner animate-spin text-xs text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800/80 rounded-2xl rounded-tl-none p-3 text-xs max-w-[80%] shadow-sm text-slate-400">
                AI đang suy nghĩ...
            </div>
        `;
        container.appendChild(loadingMsg);
        container.scrollTop = container.scrollHeight;

        try {
            const response = await fetch('/user/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ user_message: messageText })
            });

            const data = await response.json();
            container.removeChild(loadingMsg);

            // Render Response
            const aiMsg = document.createElement('div');
            aiMsg.className = 'flex gap-2.5 items-start';
            aiMsg.innerHTML = `
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center shrink-0">
                    <i class="fas fa-robot text-xs text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800/80 rounded-2xl rounded-tl-none p-3 text-xs max-w-[80%] shadow-sm leading-relaxed whitespace-pre-line">
                    ${data.reply || data.error || 'Đã có lỗi xảy ra.'}
                </div>
            `;
            container.appendChild(aiMsg);
            container.scrollTop = container.scrollHeight;

        } catch (error) {
            console.error(error);
            container.removeChild(loadingMsg);
            alert("Không thể kết nối máy chủ.");
        }
    }

    // ENTER KEY FOR CHAT
    document.getElementById('ai-chat-input').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            sendAiChatMessage();
        }
    });

    // CHỨC NĂNG 2: Đã lược bỏ (Tóm tắt bài nghe)

    // CHỨC NĂNG 3: GENERATE LỘ TRÌNH (PERSONALIZE)
    async function generateAiRoute() {
        const btn = document.getElementById('ai-route-btn');
        const originalText = btn.innerText;
        btn.innerText = 'ĐANG PHÂN TÍCH DỮ LIỆU...';
        btn.disabled = true;

        try {
            const response = await fetch('/user/ai/personalize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (data.error) {
                alert(data.error);
                return;
            }

            // Bind values
            document.getElementById('ai-res-level').innerText = data.currentLevel;

            // Render Strengths
            const strengthsContainer = document.getElementById('ai-res-strengths');
            strengthsContainer.innerHTML = '';
            data.strengths.forEach(s => {
                const el = document.createElement('div');
                el.className = 'flex gap-1.5 items-center text-emerald-600 dark:text-emerald-400';
                el.innerHTML = `<i class="fas fa-check-circle text-[10px]"></i> <span>${escapeHtml(s)}</span>`;
                strengthsContainer.appendChild(el);
            });

            // Render Weaknesses
            const weaknessesContainer = document.getElementById('ai-res-weaknesses');
            weaknessesContainer.innerHTML = '';
            data.weaknesses.forEach(w => {
                const el = document.createElement('div');
                el.className = 'flex gap-1.5 items-center text-rose-500';
                el.innerHTML = `<i class="fas fa-exclamation-circle text-[10px]"></i> <span>${escapeHtml(w)}</span>`;
                weaknessesContainer.appendChild(el);
            });

            // Render Recommended Lessons
            const recLessonsContainer = document.getElementById('ai-res-rec-lessons');
            recLessonsContainer.innerHTML = '';
            data.recommendedLessons.forEach(l => {
                const el = document.createElement('div');
                el.className = 'p-1.5 bg-sky-50 dark:bg-sky-950/20 border border-sky-100/50 dark:border-sky-900/30 text-sky-850 dark:text-sky-350 rounded-lg';
                el.innerHTML = `<i class="fas fa-book-open mr-1"></i> ${escapeHtml(l)}`;
                recLessonsContainer.appendChild(el);
            });

            // Render Weekly Plan
            const weeklyPlanContainer = document.getElementById('ai-res-weekly-plan');
            weeklyPlanContainer.innerHTML = '';
            data.weeklyPlan.forEach(p => {
                const el = document.createElement('div');
                el.className = 'p-2 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-lg';
                el.innerHTML = `${escapeHtml(p)}`;
                weeklyPlanContainer.appendChild(el);
            });

            // Render Suggestions
            const suggestionsContainer = document.getElementById('ai-res-suggestions');
            suggestionsContainer.innerHTML = '';
            data.suggestions.forEach(s => {
                const el = document.createElement('div');
                el.className = 'mb-1 leading-relaxed';
                el.innerHTML = `• ${escapeHtml(s)}`;
                suggestionsContainer.appendChild(el);
            });

            document.getElementById('ai-route-result').classList.remove('hidden');

        } catch (error) {
            console.error(error);
            alert("Lỗi kết nối.");
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    // HELPER: ESCAPE HTML TO PREVENT XSS
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
</script>
