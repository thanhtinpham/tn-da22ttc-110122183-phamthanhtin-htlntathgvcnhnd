<!-- CROSSWORD INTERACTIVE 3-COLUMN STRUCTURE -->
<div id="game-workspace" class="flex flex-col lg:flex-row gap-6 w-full items-stretch relative z-10 transition-all duration-500">
    
    <!-- COLUMN 2 (MIDDLE): Suggestion Clue Transmitter (Initially hidden/collapsed, reveals dynamically) -->
    <div id="hint-column" class="bg-white/90 backdrop-blur rounded-3xl p-0 shadow-sm flex flex-col justify-between transition-all duration-500 overflow-hidden relative border border-indigo-100">
        <!-- Watermark decal -->
        <div class="absolute -right-4 -bottom-6 text-pink-500/5 pointer-events-none select-none">
            <i class="fas fa-satellite-dish text-8xl"></i>
        </div>

        <div class="space-y-6 p-6 relative z-10">
            <div class="flex items-center gap-3 border-b border-pink-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-500 to-rose-600 text-white flex items-center justify-center shadow-md shadow-pink-500/10">
                    <i class="fas fa-headphones text-xs"></i>
                </div>
                <h5 class="font-extrabold text-pink-900 mb-0 tracking-wide text-sm uppercase">Bộ Phát Gợi Ý</h5>
            </div>
            
            <!-- Question Text -->
            <div class="text-base text-slate-800 font-semibold bg-gradient-to-r from-cyan-500/5 to-blue-500/5 border-l-4 border-cyan-400 p-4 rounded-r-2xl shadow-sm leading-relaxed">
                <div class="text-[9px] font-black text-cyan-500 uppercase tracking-widest mb-1">Gợi ý câu hỏi</div>
                <div id="hint-text-display" class="text-sm">Hãy nhấn nút "Nghe đáp án" để tải gợi ý.</div>
            </div>
            
            <!-- Audio Player -->
            <div id="hint-audio-container" class="p-3 rounded-2xl border border-pink-100 bg-white shadow-inner flex flex-col gap-2 hidden">
                <div class="flex items-center gap-2">
                    <div class="flex items-end gap-0.5 h-4 w-6">
                        <span class="w-1 bg-pink-500 rounded-full animate-sound-bar-1" style="height: 100%"></span>
                        <span class="w-1 bg-pink-400 rounded-full animate-sound-bar-2" style="height: 60%"></span>
                        <span class="w-1 bg-pink-600 rounded-full animate-sound-bar-3" style="height: 80%"></span>
                        <span class="w-1 bg-pink-300 rounded-full animate-sound-bar-4" style="height: 40%"></span>
                    </div>
                    <span class="text-[10px] font-black text-pink-600 uppercase tracking-wider">Hệ thống âm thanh</span>
                </div>
                <audio id="hint-audio-element" class="w-full custom-audio" controls></audio>
            </div>
        </div>
    </div>

    <!-- COLUMN 1 (LEFT SIDE): Active Decryption Chamber (Solver Box) -->
    <div id="solver-column" class="bg-white/90 backdrop-blur border border-indigo-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between transition-all duration-500 relative overflow-hidden">
        <!-- Watermark decal -->
        <div class="absolute -right-4 -bottom-6 text-indigo-500/5 pointer-events-none select-none">
            <i class="fas fa-keyboard text-9xl"></i>
        </div>

        <form action="{{ route('user.games.submit', $map->MaBanDo) }}" method="POST" id="crossword-form" class="space-y-6 h-full flex flex-col justify-between relative z-10">
            @csrf
            <input type="hidden" name="test_id" value="{{ $test->MaBai }}">
            <input type="hidden" name="start_time" value="{{ time() }}">

            <!-- Hidden Inputs for form submit -->
            @foreach($test->phan as $index => $phan)
                @php $cauhoi = $phan->cauhoi->first(); @endphp
                @if($cauhoi)
                    <input type="hidden" name="answers[{{ $cauhoi->MaCauHoi }}]" id="ans-{{ $cauhoi->MaCauHoi }}" value="">
                @endif
            @endforeach

            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-indigo-100 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/10">
                        <i class="fas fa-terminal text-xs"></i>
                    </div>
                    <h5 class="font-extrabold text-indigo-900 mb-0 tracking-wide text-sm uppercase">Hộp Giải Mã Hoạt Động</h5>
                </div>

                <!-- Cute AI Mascot Assistant Bubble -->
                <div class="flex items-center gap-3 bg-gradient-to-r from-indigo-500/5 to-purple-500/5 border border-indigo-500/10 p-3.5 rounded-2xl relative overflow-hidden select-none">
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 opacity-15 pointer-events-none">
                        <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-24 h-24 object-contain rotate-12">
                    </div>
                    <img src="{{ asset('images/cyber_mascot_helper.png') }}" class="w-12 h-12 object-contain animate-bounce shrink-0" style="animation-duration: 4s;">
                    <div>
                        <div class="text-[9px] font-black text-indigo-600 uppercase tracking-widest leading-none mb-1">AI Decryptor assistant</div>
                        <div class="text-xs font-bold text-slate-500 leading-normal">Gõ ký tự vào ô, nhấn "Nghe đáp án" để mở máy phát gợi ý câu đố tương ứng.</div>
                    </div>
                </div>

                <div class="middle-solver-deck">
                    @foreach($test->phan as $index => $phan)
                        @php
                            $cauhoi = $phan->cauhoi->first();
                            if (!$cauhoi) continue;
                            $correctOpt = $cauhoi->phuongancauhoi->firstWhere('DapAn', 'Dung');
                            $word = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                            $word = strtoupper(trim($word));
                            $letters = str_split($word);
                        @endphp
                        
                        <div class="row-solver-box space-y-5" id="solver-box-{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <div class="flex items-center justify-between border-b border-indigo-50 pb-2 select-none">
                                <span class="text-xs font-black text-indigo-600 uppercase tracking-wider">Giải câu: Hàng ngang {{ $index + 1 }}</span>
                                <span class="text-xs font-black px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100">{{ count($letters) }} chữ cái</span>
                            </div>

                            <!-- Row Cells & Listen Button side by side -->
                            <div class="flex flex-col items-center gap-3 py-2">
                                <div class="flex items-center gap-3 py-1 justify-center flex-wrap">
                                    <!-- Inputs grid -->
                                    <div class="flex items-center gap-1 active-cells-group" data-row-idx="{{ $index }}">
                                        @foreach($letters as $charIdx => $char)
                                            <div class="crossword-cell flex items-center justify-center rounded-xl border-2 border-b-4 border-slate-200 transition-all duration-150"
                                                 style="width: 38px; height: 38px;">
                                                <input type="text" 
                                                       maxlength="1" 
                                                       autocomplete="off"
                                                       spellcheck="false"
                                                       class="active-char-input w-full h-full border-0 text-center font-black text-lg uppercase focus:ring-0 focus:outline-none" 
                                                       style="background: transparent; color: #1e3a8a; caret-color: #2563eb;"
                                                       data-row="{{ $index }}"
                                                       data-col="{{ $charIdx }}"
                                                       data-question-id="{{ $cauhoi->MaCauHoi }}"
                                                       oninput="handleActiveCharInput(event, {{ $index }}, {{ $charIdx }}, {{ count($letters) }})"
                                                       onkeydown="handleActiveBackspace(event, {{ $index }}, {{ $charIdx }})">
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Listening command button with text -->
                                    <button type="button" 
                                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-xs shadow-md active:scale-95 transition-all cursor-pointer shrink-0 tracking-wider flex items-center gap-1.5 border border-blue-400/20"
                                            onclick="toggleClueTransmitter({{ $index }})">
                                        <i class="fas fa-volume-up animate-pulse"></i> Nghe đáp án
                                    </button>
                                </div>
                            </div>

                            <!-- Check Answer Action Button -->
                            <div class="flex justify-center select-none pt-2">
                                <button type="button" 
                                        onclick="checkActiveRowAnswer({{ $index }}, '{{ $word }}')"
                                        class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 text-white font-extrabold px-6 py-2.5 border-b-4 border-indigo-700 rounded-xl active:translate-y-[2px] active:border-b-2 transition-all shadow-md shadow-indigo-500/10">
                                    Kiểm tra đáp án
                                </button>
                            </div>

                            <!-- Row feedback message alert -->
                            <div id="row-feedback-{{ $index }}" class="hidden p-3.5 rounded-xl text-center font-bold text-sm transition-all duration-300"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    <!-- COLUMN 3 (RIGHT SIDE): Decrypted Board (Reveals correct answers on success, initially blank) -->
    <div id="board-column" class="bg-white/90 backdrop-blur border border-indigo-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between transition-all duration-500 relative overflow-hidden">
        <!-- Watermark decal -->
        <div class="absolute -right-4 -bottom-6 text-emerald-500/5 pointer-events-none select-none">
            <i class="fas fa-gamepad text-9xl"></i>
        </div>

        <div class="space-y-6 relative z-10">
            <div class="flex items-center gap-3 border-b border-emerald-100 pb-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shadow-md shadow-emerald-500/10">
                    <i class="fas fa-grid-horizontal text-xs"></i>
                </div>
                <h5 class="font-extrabold text-emerald-950 mb-0 tracking-wide text-sm uppercase">Bảng Giải Mã Đáp Án</h5>
            </div>
            
            <!-- Blueprint Crossword Deck -->
            <div class="w-full overflow-x-auto custom-scrollbar py-6 px-3 bg-blue-50/10 border-2 border-indigo-100/30 rounded-2xl shadow-inner relative flex justify-start">
                <div class="flex flex-col gap-4 min-w-[760px] py-2 mx-auto">
                    
                    @php
                        // Compute alignment columns
                        $maxViTriGiao = 0;
                        foreach($test->phan as $phan) {
                            $cauhoi = $phan->cauhoi->first();
                            if ($cauhoi && (int)$cauhoi->ViTriGiao > $maxViTriGiao) {
                                $maxViTriGiao = (int)$cauhoi->ViTriGiao;
                            }
                        }
                        $keyColIndex = $maxViTriGiao + 1;

                        // Rainbow row colors array for beautiful game styling
                        $rowColors = [
                            'from-pink-500 to-rose-500 shadow-rose-500/20 text-white border-rose-300',
                            'from-amber-400 to-orange-500 shadow-orange-500/20 text-white border-orange-300',
                            'from-emerald-400 to-teal-500 shadow-teal-500/20 text-white border-teal-300',
                            'from-cyan-400 to-blue-500 shadow-blue-500/20 text-white border-blue-300',
                            'from-indigo-500 to-purple-600 shadow-purple-500/20 text-white border-purple-300',
                            'from-violet-500 to-fuchsia-600 shadow-fuchsia-500/20 text-white border-fuchsia-300',
                        ];
                    @endphp

                    @foreach($test->phan as $index => $phan)
                        @php
                            $cauhoi = $phan->cauhoi->first();
                            if (!$cauhoi) continue;
                            $correctOpt = $cauhoi->phuongancauhoi->firstWhere('DapAn', 'Dung');
                            $word = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                            $word = strtoupper(trim($word));
                            $letters = str_split($word);
                            $viTriGiao = (int)$cauhoi->ViTriGiao;
                            $startCol = $keyColIndex - $viTriGiao;
                            $colorClass = $rowColors[$index % count($rowColors)];
                        @endphp

                        <div class="crossword-row flex items-center gap-3" data-row-idx="{{ $index }}" id="left-row-{{ $index }}">
                            <!-- Row number button (Triggers row change) -->
                            <button type="button" 
                                    id="left-pencil-{{ $index }}"
                                    class="pencil-btn flex items-center justify-center font-black shadow-md w-9 h-9 rounded-xl border bg-gradient-to-br {{ $colorClass }} hover:scale-110 active:scale-95 transition-all duration-200 shrink-0 cursor-pointer"
                                    onclick="jumpToRow({{ $index }})">
                                {{ $index + 1 }}
                            </button>

                            <!-- Cells (Initially empty placeholders) -->
                            <div class="cells-wrapper grid gap-1 shrink-0" style="grid-template-columns: repeat(20, 38px); width: calc(20 * 39px);">
                                @foreach($letters as $charIdx => $char)
                                    @php
                                        $colIndex = $startCol + $charIdx;
                                        $isKey = ($charIdx == $viTriGiao);
                                    @endphp
                                    <div id="left-cell-{{ $index }}-{{ $charIdx }}"
                                         class="crossword-cell flex items-center justify-center rounded-xl border-2 border-b-4 {{ $isKey ? 'key-cell border-amber-400 bg-gradient-to-br from-amber-50 to-yellow-100 shadow-[0_3px_0_#d97706]' : 'border-slate-200 bg-white' }} font-black text-lg uppercase transition-all duration-300 select-none cursor-pointer"
                                         style="grid-column: {{ $colIndex }}; height: 38px; color: #1e3a8a;"
                                         onclick="jumpToRow({{ $index }})">
                                         <!-- Blank placeholder -->
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit Challenge -->
        <div class="flex justify-center pt-6 border-t border-indigo-50 relative z-10">
            <button type="submit" 
                    form="crossword-form"
                    class="group relative flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-base tracking-wider px-8 py-4 rounded-2xl shadow-lg shadow-emerald-500/25 hover:scale-105 active:scale-95 transition-all duration-300 border border-emerald-400/20"
                    id="btn-submit-test">
                <i class="fas fa-trophy text-amber-200 group-hover:animate-bounce"></i>
                HOÀN THÀNH THỬ THÁCH
            </button>
        </div>
    </div>

</div>

<style>
    /* 3-Column Width transitions & slide animations */
    #solver-column {
        width: 100%;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #board-column {
        width: 100%;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #hint-column {
        width: 0px;
        opacity: 0;
        padding: 0px !important;
        border-width: 0px !important;
        overflow: hidden;
        pointer-events: none;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (min-width: 1024px) {
        #solver-column { width: 40%; }
        #board-column { width: 60%; }
        #hint-column { width: 0%; }
        
        /* When active workspace has clue shown */
        .clue-shown #solver-column { width: 30%; }
        .clue-shown #hint-column { 
            width: 32%; 
            opacity: 1; 
            padding: 1.5rem !important; 
            border-width: 1px !important;
            pointer-events: auto;
        }
        .clue-shown #board-column { width: 38%; }
    }
    
    @media (max-width: 1023px) {
        #hint-column.active {
            width: 100% !important;
            opacity: 1 !important;
            padding: 1.5rem !important;
            border-width: 1px !important;
            pointer-events: auto;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    }

    /* Crossword Cells keyboard keycap tactile design */
    .crossword-cell {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 3px 0 rgba(148, 163, 184, 0.3); /* Slate-400 depth */
        border-color: #e2e8f0;
    }
    .crossword-cell:hover {
        border-color: #818cf8 !important; /* indigo-400 */
        transform: translateY(-1px);
    }
    .crossword-cell:focus-within {
        border-color: #a855f7 !important; /* purple-500 */
        border-bottom-width: 2px !important;
        transform: translateY(2px);
        box-shadow: 0 1px 0 rgba(168, 85, 247, 0.2), 0 0 12px rgba(168, 85, 247, 0.25) !important;
        background-color: #faf5ff !important; /* purple-50 */
    }
    
    /* Vertical Keyword column keycaps */
    .key-cell {
        background: linear-gradient(135deg, #fef08a, #fde047) !important; /* yellow-200 to yellow-300 */
        box-shadow: 0 3px 0 #ca8a04 !important;
        border-color: #fbbf24 !important;
    }
    .key-cell input {
        color: #ca8a04 !important; /* yellow-700 */
    }

    /* Active Row Highlights */
    .active-row .crossword-cell {
        border-color: #c084fc; /* purple-400 */
        background-color: #faf5ff;
    }
    .active-row .crossword-cell.key-cell {
        border-color: #fbbf24;
        background: linear-gradient(135deg, #fef9c3, #fde047) !important;
    }

    /* Solved cell bright style overrides */
    .crossword-cell.solved-cell {
        background: linear-gradient(135deg, #10b981, #059669) !important; /* emerald-500 to emerald-600 */
        color: #ffffff !important;
        border-color: #047857 !important; /* emerald-700 */
        box-shadow: 0 3px 0 #065f46 !important;
    }
    
    /* Pulsing row index pointer button */
    .active-row .pencil-btn {
        animation: pulse-active-blue 1.5s infinite alternate;
        background: linear-gradient(135deg, #a855f7, #6366f1) !important; /* purple to indigo */
        box-shadow: 0 0 14px rgba(168, 85, 247, 0.4) !important;
        border-color: #d8b4fe !important;
        color: #ffffff !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Active Row Track for Crossword Game Loop
        window.activeRowIndex = 0;

        // Clues Data Array loaded from PHP Blade Variables
        const cluesData = [
            @if(!empty($test->TuKhoaHangDoc))
                @foreach($test->phan as $index => $phan)
                    @php
                        $cauhoi = $phan->cauhoi->first();
                    @endphp
                    {
                        index: {{ $index }},
                        question: "{!! addslashes($cauhoi ? $cauhoi->NDCauHoi : '') !!}",
                        audioUrl: "{{ $phan->tepamthanh ? asset('storage/' . $phan->tepamthanh->DuongDan) : '' }}"
                    },
                @endforeach
            @endif
        ];

        window.activeHintIndex = null;

        // Toggle suggestion/audio transmitter panel in the middle column
        window.toggleClueTransmitter = function(index) {
            const workspace = document.getElementById('game-workspace');
            const hintColumn = document.getElementById('hint-column');
            const audioElement = document.getElementById('hint-audio-element');
            
            // Pause any playing audio
            if (audioElement) {
                audioElement.pause();
            }

            // Toggle off if clicking the currently open suggestion card
            if (workspace.classList.contains('clue-shown') && window.activeHintIndex === index) {
                workspace.classList.remove('clue-shown');
                hintColumn.classList.remove('active');
                return;
            }
            
            // Update panel contents with correct question prompt and audio stream
            const clue = cluesData[index];
            if (clue) {
                document.getElementById('hint-text-display').innerText = clue.question;
                const audioContainer = document.getElementById('hint-audio-container');
                
                if (clue.audioUrl) {
                    audioContainer.classList.remove('hidden');
                    audioElement.src = clue.audioUrl;
                    audioElement.load();
                    audioElement.play().catch(err => console.log('Autoplay blocked'));
                } else {
                    audioContainer.classList.add('hidden');
                    audioElement.src = '';
                }
                
                window.activeHintIndex = index;
                workspace.classList.add('clue-shown');
                hintColumn.classList.add('active');
            }
        };

        // Jump to row function (switches active solver box in left column)
        window.jumpToRow = function(index) {
            window.activeRowIndex = index;

            // Pause all running audio streams
            document.querySelectorAll('audio').forEach(audio => {
                audio.pause();
            });

            // Toggle active classes on board rows
            document.querySelectorAll('.crossword-row').forEach(row => {
                row.classList.remove('active-row');
            });
            const activeRow = document.getElementById('left-row-' + index);
            if (activeRow) {
                activeRow.classList.add('active-row');
            }

            // Toggle active solver boxes in the left deck
            document.querySelectorAll('.row-solver-box').forEach(box => {
                box.style.display = 'none';
            });
            const selectedSolver = document.getElementById('solver-box-' + index);
            if (selectedSolver) {
                selectedSolver.style.display = 'block';
                // Focus first input of active solver
                const firstInput = selectedSolver.querySelector('.active-char-input');
                if (firstInput) firstInput.focus();
            }

            // If suggestion transmitter is currently open, switch the clue content instantly
            const workspace = document.getElementById('game-workspace');
            if (workspace && workspace.classList.contains('clue-shown')) {
                const clue = cluesData[index];
                if (clue) {
                    document.getElementById('hint-text-display').innerText = clue.question;
                    const audioContainer = document.getElementById('hint-audio-container');
                    const audioElement = document.getElementById('hint-audio-element');
                    
                    if (clue.audioUrl) {
                        audioContainer.classList.remove('hidden');
                        audioElement.src = clue.audioUrl;
                        audioElement.load();
                        audioElement.play().catch(err => console.log('Autoplay blocked'));
                    } else {
                        audioContainer.classList.add('hidden');
                        audioElement.src = '';
                    }
                    window.activeHintIndex = index;
                }
            }
        };

        // Handle typing inputs inside active solver
        window.handleActiveCharInput = function(event, rowIdx, colIdx, wordLength) {
            const input = event.target;
            
            // Normalize Vietnamese letters to uppercase English
            let val = input.value;
            val = val.replace(/à|á|ạ|ả|ã|â|ần|ấn|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a")
                     .replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e")
                     .replace(/ì|í|ị|ỉ|ĩ/g, "i")
                     .replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o")
                     .replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u")
                     .replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y")
                     .replace(/đ/g, "d")
                     .replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A")
                     .replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E")
                     .replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I")
                     .replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O")
                     .replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U")
                     .replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y")
                     .replace(/Đ/g, "D");
            val = val.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
            val = val.toUpperCase().trim().replace(/[^A-Z]/g, '');
            
            if (val.length > 0) {
                val = val.charAt(val.length - 1);
            }
            input.value = val;

            updateRowHiddenAnswer(rowIdx);

            // Move focus to next input box
            if (val.length > 0 && colIdx < wordLength - 1) {
                setTimeout(() => {
                    const solverBox = input.closest('.row-solver-box');
                    if (solverBox) {
                        const inputs = Array.from(solverBox.querySelectorAll('.active-char-input'));
                        const nextInput = inputs[colIdx + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                }, 20);
            }
        };

        // Handle Backspace and Enter key navigations
        window.handleActiveBackspace = function(event, rowIdx, colIdx) {
            const input = event.target;
            if (event.key === 'Backspace') {
                if (input.value.length === 0 && colIdx > 0) {
                    setTimeout(() => {
                        const solverBox = input.closest('.row-solver-box');
                        if (solverBox) {
                            const inputs = Array.from(solverBox.querySelectorAll('.active-char-input'));
                            const prevInput = inputs[colIdx - 1];
                            if (prevInput) {
                                prevInput.focus();
                                prevInput.value = '';
                                updateRowHiddenAnswer(rowIdx);
                            }
                        }
                    }, 10);
                } else {
                    setTimeout(() => {
                        updateRowHiddenAnswer(rowIdx);
                    }, 10);
                }
            } else if (event.key === 'Enter') {
                event.preventDefault();
                const solverBox = input.closest('.row-solver-box');
                if (solverBox) {
                    const btn = solverBox.querySelector('button[onclick^="checkActiveRowAnswer"]');
                    if (btn) btn.click();
                }
            }
        };

        // Save typed response to hidden fields
        function updateRowHiddenAnswer(rowIdx) {
            const inputs = document.querySelectorAll(`.active-char-input[data-row="${rowIdx}"]`);
            let answer = '';
            let questionId = '';
            inputs.forEach(inp => {
                answer += inp.value;
                questionId = inp.getAttribute('data-question-id');
            });
            
            if (questionId) {
                const hiddenInp = document.getElementById('ans-' + questionId);
                if (hiddenInp) {
                    hiddenInp.value = answer;
                }
            }
        }

        // Real-time verification of active row answer input
        window.checkActiveRowAnswer = function(index, correctWord) {
            const inputs = document.querySelectorAll(`.active-char-input[data-row="${index}"]`);
            let userAns = '';
            inputs.forEach(inp => {
                userAns += inp.value;
            });
            userAns = userAns.toUpperCase().trim();
            correctWord = correctWord.toUpperCase().trim();

            const feedbackDiv = document.getElementById('row-feedback-' + index);
            
            if (userAns === correctWord) {
                // Correct answer toast alert
                feedbackDiv.className = "p-3.5 rounded-xl text-center font-bold text-sm bg-emerald-50 text-emerald-800 border border-emerald-200 mt-4 block animate-fade-in";
                feedbackDiv.innerHTML = "🎉 CHÍNH XÁC!";
                
                // Save correct value in form submit fields
                const questionId = inputs[0].getAttribute('data-question-id');
                const hiddenInp = document.getElementById('ans-' + questionId);
                if (hiddenInp) {
                    hiddenInp.value = userAns;
                }

                // Render letters inside Board cells dynamically
                const letters = correctWord.split('');
                letters.forEach((char, charIdx) => {
                    const leftCell = document.getElementById(`left-cell-${index}-${charIdx}`);
                    if (leftCell) {
                        leftCell.innerHTML = char;
                        leftCell.classList.add('solved-cell');
                        leftCell.classList.remove('bg-white', 'border-slate-200', 'key-cell', 'border-amber-400', 'bg-gradient-to-br', 'from-amber-50', 'to-yellow-100');
                    }
                });

                // Color index pencil button as successful green
                const pencilBtn = document.getElementById(`left-pencil-${index}`);
                if (pencilBtn) {
                    pencilBtn.classList.remove('border-blue-200', 'text-blue-600');
                    pencilBtn.classList.add('border-emerald-500', 'from-emerald-500', 'to-teal-600', 'text-white', 'shadow-emerald-500/20');
                }

                // Advance to next row solver container automatically
                setTimeout(() => {
                    const nextSolver = document.getElementById('solver-box-' + (index + 1));
                    if (nextSolver) {
                        // If clue was shown, update the clue to the next row!
                        const workspace = document.getElementById('game-workspace');
                        if (workspace.classList.contains('clue-shown')) {
                            toggleClueTransmitter(index + 1);
                        }
                        jumpToRow(index + 1);
                    } else {
                        feedbackDiv.innerHTML = "🎉 CHÍNH XÁC! Bạn đã giải được tất cả hàng ngang!";
                    }
                }, 1200);
            } else {
                // Incorrect answer warning toast alert
                feedbackDiv.className = "p-3.5 rounded-xl text-center font-bold text-sm bg-red-50 text-red-800 border border-red-200 mt-4 block animate-fade-in";
                feedbackDiv.innerHTML = "❌ SAI RỒI! Hãy thử lại.";
                
                setTimeout(() => {
                    if (inputs[0]) inputs[0].focus();
                }, 800);
            }
        };

        // Initialize board by selecting Row 1 (index 0) on launch
        if (document.querySelector('.crossword-row')) {
            jumpToRow(0);
        }
    });
</script>
