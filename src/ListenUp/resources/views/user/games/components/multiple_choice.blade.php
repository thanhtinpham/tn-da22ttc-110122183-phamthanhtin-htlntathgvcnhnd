<!-- MULTIPLE CHOICE (TRẮC NGHIỆM) STRUCTURE -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full items-stretch relative z-10">
    
    <!-- LEFT SIDE: Multiple Choice Audio Control Panel -->
    <div class="lg:col-span-5 bg-white/60 backdrop-blur border border-white rounded-3xl p-6 shadow-sm flex flex-col justify-between">
        <div class="space-y-6 flex flex-col h-full justify-between">
            <div class="space-y-6">
                <div class="flex items-center gap-2 border-b border-blue-100 pb-3">
                    <i class="fas fa-volume-up text-blue-600 text-lg"></i>
                    <h5 class="font-extrabold text-blue-800 mb-0 tracking-wide text-sm uppercase">Trạm phát đề bài</h5>
                </div>
                
                @foreach($test->phan as $index => $phan)
                    @if($phan->tepamthanh)
                        <div class="mc-audio-panel" id="mc-audio-{{ $phan->MaPhan ?? $phan->id }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <div class="p-4 rounded-2xl border border-blue-100 bg-white/80 shadow-inner">
                                <h6 class="font-bold text-xs text-blue-600 flex items-center gap-2 mb-3 select-none">
                                    <i class="fas fa-volume-up text-blue-500 animate-pulse"></i> Trình phát âm thanh cho {{ $phan->TenPhan }}:
                                </h6>
                                <audio id="audio-player-{{ $phan->tepamthanh->MaTep ?? $phan->tepamthanh->id }}" class="w-full custom-audio" controls>
                                    <source src="{{ asset('storage/' . $phan->tepamthanh->DuongDan) }}" type="audio/mpeg">
                                    Trình duyệt không hỗ trợ phát âm thanh.
                                </audio>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Progress Parts Navigation Steps -->
            <div class="space-y-3 pt-6 border-t border-blue-100/50">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest select-none">Bản đồ phần thi</span>
                <div class="nav flex-column nav-pills gap-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    @foreach($test->phan as $index => $phan)
                        <button class="nav-link text-start custom-tab-btn flex items-center justify-between p-3.5 rounded-2xl border border-slate-200 bg-white hover:border-blue-300 hover:bg-blue-50/30 transition-all duration-300 w-full" 
                                id="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-part-{{ $phan->MaPhan ?? $phan->id }}" 
                                type="button" 
                                role="tab" 
                                aria-controls="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}" 
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                onclick="switchMCAudio('{{ $phan->MaPhan ?? $phan->id }}', {{ $index }})">
                            <div class="flex flex-col gap-0.5 min-w-0">
                                <span class="font-bold text-slate-600 text-sm tracking-wide custom-tab-btn-title truncate">{{ $phan->TenPhan }}</span>
                                <span class="text-[11px] text-slate-400 font-semibold">{{ $phan->cauhoi->count() }} câu hỏi</span>
                            </div>
                            <div class="w-6 h-6 rounded-full border border-slate-300 flex items-center justify-center text-xs font-bold text-slate-400 custom-tab-btn-badge shrink-0">
                                {{ $index + 1 }}
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE: Multiple Choice Answering Area -->
    <div class="lg:col-span-7 bg-white/60 backdrop-blur border border-white rounded-3xl p-6 shadow-sm flex flex-col justify-between">
        <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="mc-form" class="space-y-6 h-full flex flex-col justify-between">
            @csrf
            <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
            <input type="hidden" name="start_time" value="{{ time() }}">

            <div class="flex-grow flex flex-col justify-between">
                <div class="tab-content" id="v-pills-tabContent">
                    @php $globalQuestionIndex = 1; @endphp
                    @foreach($test->phan as $index => $phan)
                    
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                             id="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}" 
                             role="tabpanel" 
                             aria-labelledby="v-pills-part-{{ $phan->MaPhan ?? $phan->id }}-tab">
                             
                            <div class="space-y-6">
                                <!-- Part Header Title -->
                                <div class="flex items-center justify-between border-b border-blue-100 pb-3">
                                    <h4 class="font-extrabold text-blue-800 text-base flex items-center gap-2 mb-0 font-display">
                                        <i class="fas fa-folder-open text-blue-500"></i> {{ $phan->TenPhan }}
                                    </h4>
                                </div>
                                
                                <!-- Questions List -->
                                <div class="space-y-6 max-h-[500px] overflow-y-auto custom-scrollbar pr-2">
                                    @foreach($phan->cauhoi as $question)
                                        <div class="p-5 rounded-2xl border-2 border-b-4 border-blue-100 bg-white shadow-sm relative pt-8">
                                            <!-- Question index badge -->
                                            <div class="absolute -top-3.5 left-4 px-4 py-1.5 rounded-xl bg-blue-600 border border-blue-500 text-xs font-bold text-white tracking-wide shadow-md">
                                                CÂU HỎI {{ $globalQuestionIndex++ }}
                                            </div>
                                            
                                            <h5 class="font-bold text-slate-800 text-base mb-5 mt-1 select-none leading-relaxed">
                                                {{ $question->NDCauHoi }}
                                            </h5>
                                            
                                            <!-- Options grid (Tactile Option Cards) -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($question->phuongancauhoi as $option)
                                                    <div class="form-check custom-option flex items-center p-4 bg-white border-2 border-b-4 border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 active:translate-y-[2px] active:border-b-2 transition-all duration-150 relative select-none">
                                                        <input class="form-check-input hidden-radio" 
                                                               type="radio" 
                                                               name="answers[{{ $question->MaCauHoi ?? $question->id }}]" 
                                                               id="option_{{ $option->MaPA ?? $option->id }}" 
                                                               value="{{ $option->MaPA ?? $option->id }}" 
                                                               required>
                                                        
                                                        <!-- Custom radio ring -->
                                                        <div class="custom-radio-circle w-5 h-5 rounded-full border border-slate-300 flex items-center justify-center mr-3 transition-all shrink-0">
                                                            <div class="w-2.5 h-2.5 rounded-full bg-blue-600 scale-0 transition-all"></div>
                                                        </div>

                                                        <label class="form-check-label text-slate-600 font-medium cursor-pointer w-full text-sm mb-0 leading-normal" 
                                                               for="option_{{ $option->MaPA ?? $option->id }}">
                                                            {{ $option->NDPA }}
                                                            @if($option->HinhAnh)
                                                                <div class="mt-3 text-center">
                                                                    <img src="{{ asset('storage/' . $option->HinhAnh) }}" alt="Option Image" class="max-h-32 object-contain border border-slate-200 rounded-lg mx-auto bg-slate-50 p-1">
                                                                </div>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Controls Navigation -->
                <div class="flex items-center justify-center gap-4 pt-6 flex-wrap border-t border-blue-100/50 mt-6">
                    <button type="button" 
                            class="flex items-center gap-2 border-2 border-b-4 border-slate-300 bg-white hover:border-blue-400 hover:bg-blue-50 text-slate-700 font-bold px-6 py-2.5 rounded-xl active:translate-y-[2px] active:border-b-2 transition-all shadow-sm" 
                            id="btn-prev-part" 
                            style="display: none;">
                        <i class="fas fa-arrow-left"></i> Trở lại
                    </button>
                    
                    <button type="button" 
                            class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold px-6 py-2.5 border-b-4 border-indigo-700 rounded-xl active:translate-y-[2px] active:border-b-2 transition-all shadow-md shadow-blue-500/10" 
                            id="btn-next-part">
                        Tiếp tục <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <button type="submit" 
                            class="flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold px-8 py-3.5 border-b-4 border-teal-700 rounded-xl active:translate-y-[2px] active:border-b-2 transition-all shadow-lg shadow-emerald-500/25 border border-emerald-400/20" 
                            id="btn-submit-test">
                        <i class="fas fa-trophy text-amber-200"></i> Hoàn thành thử thách
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Option selection hover and check status styling */
    .custom-option {
        transition: all 0.15s ease-out;
    }
    .custom-option:hover {
        border-color: #3b82f6; /* blue-500 */
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.05);
    }
    
    /* Custom hidden radio and radio ring */
    .hidden-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .custom-option:hover .custom-radio-circle {
        border-color: #3b82f6; /* blue-500 */
    }
    .custom-option:has(.hidden-radio:checked) {
        background-color: #eff6ff !important; /* blue-50 */
        border-color: #3b82f6 !important; /* blue-500 */
        border-bottom-width: 2px !important;
        transform: translateY(2px);
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.15) !important;
    }
    .custom-option:has(.hidden-radio:checked) .custom-radio-circle {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
    }
    .custom-option:has(.hidden-radio:checked) .custom-radio-circle div {
        transform: scale(1);
    }
    .custom-option:has(.hidden-radio:checked) label {
        color: #1d4ed8 !important; /* blue-700 */
        font-weight: 700;
    }
    
    /* Custom part navigation step tab button (Bootstrap tab override) */
    .custom-tab-btn {
        border-bottom-width: 4px !important;
        transition: all 0.15s ease-out;
    }
    .custom-tab-btn:hover {
        border-color: #93c5fd;
    }
    .custom-tab-btn.active {
        border-color: #3b82f6 !important; /* blue-500 */
        border-bottom-width: 2px !important;
        transform: translateY(2px);
        background: #eff6ff !important; /* blue-50 */
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.08);
    }
    .custom-tab-btn.active .custom-tab-btn-title {
        color: #1d4ed8 !important; /* blue-700 */
    }
    .custom-tab-btn.active .custom-tab-btn-badge {
        border-color: #3b82f6 !important;
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom Option Click event binder (Trắc nghiệm)
        document.querySelectorAll('.custom-option').forEach(item => {
            item.addEventListener('click', function(e) {
                const radio = this.querySelector('input[type="radio"]');
                if (radio && e.target !== radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });

        // Part Navigation Logic (Trắc nghiệm tabs progression)
        const tabBtns = Array.from(document.querySelectorAll('.custom-tab-btn'));
        const btnPrev = document.getElementById('btn-prev-part');
        const btnNext = document.getElementById('btn-next-part');
        const btnSubmit = document.getElementById('btn-submit-test');
        
        let currentIndex = 0;

        function updateButtons() {
            if (!btnPrev || !btnNext || !btnSubmit) return;
            if (tabBtns.length <= 1) {
                btnPrev.style.display = 'none';
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-block';
                return;
            }

            if (currentIndex === 0) {
                btnPrev.style.display = 'none';
                btnNext.style.display = 'inline-block';
                btnSubmit.style.display = 'none';
            } else if (currentIndex === tabBtns.length - 1) {
                btnPrev.style.display = 'inline-block';
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-block';
            } else {
                btnPrev.style.display = 'inline-block';
                btnNext.style.display = 'inline-block';
                btnSubmit.style.display = 'none';
            }
        }

        tabBtns.forEach((btn, index) => {
            btn.addEventListener('shown.bs.tab', function () {
                currentIndex = index;
                updateButtons();
            });
        });

        if (btnNext) {
            btnNext.addEventListener('click', function() {
                if (currentIndex < tabBtns.length - 1) {
                    let nextTab = new bootstrap.Tab(tabBtns[currentIndex + 1]);
                    nextTab.show();
                }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function() {
                if (currentIndex > 0) {
                    let prevTab = new bootstrap.Tab(tabBtns[currentIndex - 1]);
                    prevTab.show();
                }
            });
        }

        updateButtons();

        // Multiple choice audio toggle helper
        window.switchMCAudio = function(partId, idx) {
            document.querySelectorAll('.mc-audio-panel').forEach(panel => {
                panel.style.display = 'none';
            });
            const activePanel = document.getElementById('mc-audio-' + partId);
            if (activePanel) {
                activePanel.style.display = 'block';
            }
            
            // Pause all running audio streams
            document.querySelectorAll('audio').forEach(audio => {
                audio.pause();
            });
        };
    });
</script>
