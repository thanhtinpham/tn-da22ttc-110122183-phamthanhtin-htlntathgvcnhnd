@extends('layouts.admin')
@section('title', 'Quản lý nội dung bài test: ' . $baitest->TenBai)
@section('admin_content')
@php
    $mapNumber = 0;
    if ($baitest->MaBanDo) {
        $mapNumber = (int) str_replace('BD', '', $baitest->MaBanDo);
    }
@endphp
<style>
    /* Fix xung đột giữa Tailwind và Bootstrap cho class collapse */
    .collapse:not(.show) {
        display: none;
    }
    .collapse.show {
        visibility: visible !important;
        display: block;
    }
    .collapsing {
        visibility: visible !important;
    }
</style>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Nội dung: {{ $baitest->TenBai }}</h2>
        <div>
            <a href="{{ route('admin.baitest.index') }}" class="btn btn-secondary">Quay lại</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPhanModal">Thêm Phần Mới</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($mapNumber === 1)
        <div class="alert alert-info border-info d-flex align-items-center gap-3 mb-4 shadow-sm">
            <div class="fs-2 text-info"><i class="fas fa-heading"></i></div>
            <div class="w-100">
                <h5 class="alert-heading mb-2 fw-bold text-info"><i class="fas fa-key me-2"></i>Cấu hình Trò chơi Ô chữ (Map 1)</h5>
                <form action="{{ route('admin.baitest.updateKeyword', $baitest->MaBai) }}" method="POST" class="row align-items-end g-2">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label mb-1">Từ khóa hàng dọc (Viết liền không dấu, VD: CODE)</label>
                        <input type="text" name="TuKhoaHangDoc" class="form-control text-uppercase" value="{{ old('TuKhoaHangDoc', $baitest->TuKhoaHangDoc) }}" placeholder="Nhập từ khóa dọc..." required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info text-white fw-bold w-100">Cập nhật từ khóa</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($mapNumber === 8)
        <div class="alert alert-info border-info d-flex align-items-center gap-3 mb-4 shadow-sm">
            <div class="fs-2 text-info"><i class="fas fa-puzzle-piece"></i></div>
            <div>
                <h5 class="alert-heading mb-1 fw-bold text-info"><i class="fas fa-puzzle-piece me-2"></i>Cấu hình Trò chơi Mở mảnh ghép (Map 8)</h5>
                <div class="mb-1">
                    Ảnh nền trò chơi: 
                    @if($baitest->AnhTroChoi)
                        <a href="{{ asset('images/' . $baitest->AnhTroChoi) }}" target="_blank" class="fw-bold text-info text-decoration-underline">{{ $baitest->AnhTroChoi }}</a>
                    @else
                        <span class="text-danger fw-bold">Chưa tải ảnh nền trò chơi! Vui lòng sửa thông tin bài test để cập nhật ảnh.</span>
                    @endif
                </div>
                <div class="mb-0">
                    Số mảnh ghép: <strong class="badge bg-primary fs-6">{{ $baitest->SoManhGhep ?? 4 }}</strong> mảnh.
                    Số phần đã tạo: <strong class="badge bg-secondary fs-6">{{ $baitest->phan->count() }}</strong> / {{ $baitest->SoManhGhep ?? 4 }} phần.
                    @if($baitest->phan->count() !== ($baitest->SoManhGhep ?? 4))
                        <span class="text-warning fw-bold ms-2"><i class="fas fa-exclamation-triangle"></i> Cảnh báo: Hãy tạo đủ {{ $baitest->SoManhGhep ?? 4 }} phần tương ứng với số mảnh ghép!</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="accordion" id="testContentAccordion">
        @forelse($baitest->phan as $phan)
        <div class="accordion-item mb-3 border">
            <h2 class="accordion-header position-relative" id="heading-{{ $phan->MaPhan }}">
                <button class="accordion-button collapsed pe-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $phan->MaPhan }}">
                    <strong>{{ $phan->TenPhan }}</strong> (Thứ tự: {{ $phan->ThuTuPhan }})
                </button>
                <div class="position-absolute end-0 top-50 translate-middle-y me-5" style="z-index: 10;">
                    <form action="{{ route('admin.phan.destroy', $phan->MaPhan) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa phần này không? Hành động này sẽ xóa toàn bộ câu hỏi và tệp âm thanh liên quan!');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm bg-white" onclick="event.stopPropagation();">
                            <i class="fas fa-trash-alt me-1"></i> Xóa phần
                        </button>
                    </form>
                </div>
            </h2>
            <div id="collapse-{{ $phan->MaPhan }}" class="accordion-collapse collapse" data-bs-parent="#testContentAccordion">
                <div class="accordion-body">
                    
                    <div class="row">
                        <div class="col-md-5">
                            <h5>Âm thanh</h5>
                            @if($phan->tepamthanh)
                                <div class="p-3 bg-light rounded mb-2">
                                    <audio controls class="w-100 mb-2">
                                        <source src="{{ asset('storage/' . $phan->tepamthanh->DuongDan) }}" type="audio/mpeg">
                                    </audio>
                                    <small>Tên tệp: {{ $phan->tepamthanh->TenTep }}</small><br>
                                    <small>Giới hạn: {{ $phan->tepamthanh->GioiHanPhat }}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAudioModal-{{ $phan->tepamthanh->MaTep }}"><i class="fas fa-edit"></i> Sửa</button>
                                    <form action="{{ route('admin.audio.destroy', $phan->tepamthanh->MaTep) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa audio này không?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Xóa</button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-warning py-2">Chưa có âm thanh</div>
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addAudioModal-{{ $phan->MaPhan }}">Tải Audio lên</button>
                            @endif
                        </div>
                        
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5>Danh sách câu hỏi ({{ $phan->cauhoi->count() }})</h5>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addCauhoiModal-{{ $phan->MaPhan }}">Thêm câu hỏi</button>
                            </div>
                            
                            @forelse($phan->cauhoi as $question)
                                <div class="card mb-2 border border-info">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>Câu hỏi:</strong> {{ $question->NDCauHoi }}
                                                @if($mapNumber === 1)
                                                    @php
                                                        $dapAnOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung');
                                                        $answerWord = $dapAnOption ? preg_replace('/^[A-D]\.\s*/', '', $dapAnOption->NDPA) : '';
                                                        $intersectChar = ($answerWord && isset($answerWord[$question->ViTriGiao])) ? $answerWord[$question->ViTriGiao] : '';
                                                    @endphp
                                                    <div class="mt-2">
                                                        <span class="badge bg-warning text-dark p-2" style="font-size: 0.9rem;">
                                                            <i class="fas fa-key me-1"></i> Đáp án: <strong>{{ $answerWord }}</strong>
                                                        </span>
                                                        <span class="badge bg-info text-dark p-2 ms-2" style="font-size: 0.9rem;">
                                                            <i class="fas fa-link me-1"></i> Giao nhau: <strong>{{ $intersectChar }}</strong> (Vị trí {{ $question->ViTriGiao + 1 }})
                                                        </span>
                                                    </div>
                                                @elseif($mapNumber === 2 || $mapNumber === 3 || $mapNumber === 4 || $mapNumber === 6)
                                                    @php
                                                        $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                                        $englishVal = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                                                    @endphp
                                                    <div class="mt-2">
                                                        <span class="badge bg-success p-2" style="font-size: 0.9rem;">
                                                            <i class="fas fa-check-circle me-1"></i> Tiếng Anh: <strong>{{ $englishVal }}</strong>
                                                        </span>
                                                    </div>
                                                @elseif($mapNumber === 7)
                                                    @php
                                                        $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                                        $englishVal = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                                                        $words = $englishVal !== '' ? explode('|', $englishVal) : [];
                                                    @endphp
                                                    <div class="mt-2 flex-wrap gap-1">
                                                        <span class="badge bg-primary p-2 mb-1" style="font-size: 0.9rem;">
                                                            <i class="fas fa-list-ol me-1"></i> Thứ tự từ đúng:
                                                        </span>
                                                        @foreach($words as $index => $wVal)
                                                            <span class="badge text-white p-2 mb-1" style="font-size: 0.9rem; background-color: #6366f1;">
                                                                {{ $index + 1 }}. {{ $wVal }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @elseif($mapNumber === 5)
                                                    @php
                                                        $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                                        $correctWord = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                                                        $incorrectOpts = $question->phuongancauhoi->where('DapAn', '!=', 'Dung')->values();
                                                    @endphp
                                                    <div class="mt-2">
                                                        <span class="badge bg-success p-2" style="font-size: 0.9rem;">
                                                            <i class="fas fa-check-circle me-1"></i> Từ đúng: <strong>{{ $correctWord }}</strong>
                                                        </span>
                                                        @foreach($incorrectOpts as $idx => $incorrectOpt)
                                                            <span class="badge bg-danger p-2 ms-1" style="font-size: 0.9rem;">
                                                                <i class="fas fa-times-circle me-1"></i> Gây nhiễu {{ $idx+1 }}: <strong>{{ preg_replace('/^[A-D]\.\s*/', '', $incorrectOpt->NDPA) }}</strong>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="d-flex flex-wrap gap-3 mt-2 text-muted">
                                                        @foreach($question->phuongancauhoi as $opt)
                                                            <div class="border rounded p-2 bg-light" style="flex: 1 1 calc(50% - 1rem); min-width: 150px;">
                                                                <span class="{{ $opt->DapAn == 'Dung' ? 'text-success fw-bold' : '' }}">
                                                                    {{ $opt->NDPA }}
                                                                </span>
                                                                @if($opt->HinhAnh)
                                                                    <br><img src="{{ asset('storage/' . $opt->HinhAnh) }}" alt="Hình ảnh đáp án" style="max-height: 80px; object-fit: contain;" class="mt-2 border rounded shadow-sm">
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCauhoiModal-{{ $question->MaCauHoi }}"><i class="fas fa-edit"></i> Sửa</button>
                                                <form action="{{ route('admin.cauhoi.destroy', $question->MaCauHoi) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Chưa có câu hỏi nào.</div>
                            @endforelse
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        @empty
            <div class="alert alert-info">Bài test này chưa có phần nào. Vui lòng thêm phần mới.</div>
        @endforelse
    </div>
</div>

<!-- Modal Add Phan -->
<div class="modal fade" id="addPhanModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.phan.store', $baitest->MaBai) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Phần mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tên Phần (Ví dụ: Part 1 - Picture Description)</label>
                        <input type="text" name="TenPhan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Thứ tự</label>
                        <input type="number" name="ThuTuPhan" class="form-control" value="{{ $baitest->phan->count() + 1 }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Mô tả (Tùy chọn)</label>
                        <textarea name="MoTaPhan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tạo phần</button>
                </div>
            </div>
        </form>
    </div>
</div>

</div>

<!-- Modals for each Phan -->
@foreach($baitest->phan as $phan)
    @if($phan->tepamthanh)
    <!-- Modal Edit Audio -->
    <div class="modal fade" id="editAudioModal-{{ $phan->tepamthanh->MaTep }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.audio.update', $phan->tepamthanh->MaTep) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Audio cho: {{ $phan->TenPhan }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nguồn Audio mới (Không bắt buộc)</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="audio_source" id="sourceUploadEdit-{{ $phan->tepamthanh->MaTep }}" value="upload" checked onchange="toggleAudioSourceEdit('{{ $phan->tepamthanh->MaTep }}')">
                                    <label class="form-check-label" for="sourceUploadEdit-{{ $phan->tepamthanh->MaTep }}">
                                        Tải tệp lên (Upload)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="audio_source" id="sourceGenerateEdit-{{ $phan->tepamthanh->MaTep }}" value="generate" onchange="toggleAudioSourceEdit('{{ $phan->tepamthanh->MaTep }}')">
                                    <label class="form-check-label" for="sourceGenerateEdit-{{ $phan->tepamthanh->MaTep }}">
                                        Tạo tự động (TTS)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="uploadGroupEdit-{{ $phan->tepamthanh->MaTep }}">
                            <label class="form-label fw-bold">Tải tệp MP3 mới</label>
                            <input type="file" name="audio_file" class="form-control" accept=".mp3,.wav">
                            <small class="text-muted">Chọn tệp khác nếu bạn muốn thay thế tệp cũ</small>
                        </div>

                        <div class="mb-3 d-none" id="generateGroupEdit-{{ $phan->tepamthanh->MaTep }}">
                            <div class="mb-2">
                                <label class="form-label fw-bold">Nội dung văn bản mới (Tiếng Anh)</label>
                                <textarea name="audio_text" id="audioTextInputEdit-{{ $phan->tepamthanh->MaTep }}" class="form-control" rows="3" placeholder="Nhập câu tiếng Anh cần phát âm..."></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Giọng phát âm</label>
                                    <select name="audio_accent" class="form-control">
                                        <option value="en-US">Mỹ (en-US)</option>
                                        <option value="en-GB">Anh (en-GB)</option>
                                        <option value="en-AU">Úc (en-AU)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Tốc độ</label>
                                    <select name="audio_speed" class="form-control">
                                        <option value="1.0">Bình thường (1.0x)</option>
                                        <option value="0.75">Chậm (0.75x)</option>
                                        <option value="1.25">Nhanh (1.25x)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời lượng (ví dụ: 30s)</label>
                            <input type="text" name="TGTep" class="form-control" value="{{ $phan->tepamthanh->TGTep }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giới hạn phát</label>
                            <input type="text" name="GioiHanPhat" class="form-control" value="{{ $phan->tepamthanh->GioiHanPhat }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Modal Add Audio -->
    <div class="modal fade" id="addAudioModal-{{ $phan->MaPhan }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.phan.audio.store', $phan->MaPhan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cung cấp Audio cho: {{ $phan->TenPhan }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nguồn Audio</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="audio_source" id="sourceUpload-{{ $phan->MaPhan }}" value="upload" checked onchange="toggleAudioSource('{{ $phan->MaPhan }}')">
                                    <label class="form-check-label" for="sourceUpload-{{ $phan->MaPhan }}">
                                        Tải tệp lên (Upload)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="audio_source" id="sourceGenerate-{{ $phan->MaPhan }}" value="generate" onchange="toggleAudioSource('{{ $phan->MaPhan }}')">
                                    <label class="form-check-label" for="sourceGenerate-{{ $phan->MaPhan }}">
                                        Tạo tự động (TTS)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="uploadGroup-{{ $phan->MaPhan }}">
                            <label class="form-label fw-bold">Tải tệp MP3</label>
                            <input type="file" name="audio_file" id="audioFileInput-{{ $phan->MaPhan }}" class="form-control" accept=".mp3,.wav" required>
                        </div>

                        <div class="mb-3 d-none" id="generateGroup-{{ $phan->MaPhan }}">
                            <div class="mb-2">
                                <label class="form-label fw-bold">Nội dung văn bản (Tiếng Anh)</label>
                                <textarea name="audio_text" id="audioTextInput-{{ $phan->MaPhan }}" class="form-control" rows="3" placeholder="Nhập câu tiếng Anh cần phát âm..."></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Giọng phát âm</label>
                                    <select name="audio_accent" class="form-control">
                                        <option value="en-US">Mỹ (en-US)</option>
                                        <option value="en-GB">Anh (en-GB)</option>
                                        <option value="en-AU">Úc (en-AU)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-bold">Tốc độ</label>
                                    <select name="audio_speed" class="form-control">
                                        <option value="1.0">Bình thường (1.0x)</option>
                                        <option value="0.75">Chậm (0.75x)</option>
                                        <option value="1.25">Nhanh (1.25x)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thời lượng (ví dụ: 30s hoặc tự động điền)</label>
                            <input type="text" name="TGTep" class="form-control" placeholder="ví dụ: 10s" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giới hạn phát</label>
                            <input type="text" name="GioiHanPhat" class="form-control" value="3 lần" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add Question -->
    <div class="modal fade" id="addCauhoiModal-{{ $phan->MaPhan }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.phan.cauhoi.store', $phan->MaPhan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm câu hỏi cho: {{ $phan->TenPhan }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($mapNumber === 1)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gợi ý câu hỏi</label>
                                <input type="text" name="NDCauHoi" class="form-control" placeholder="Nhập gợi ý câu hỏi..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đáp án hàng ngang (Viết liền không dấu, VD: FLOWER)</label>
                                <input type="text" name="DapAnOChu" id="dap_an_ochu_add_{{ $phan->MaPhan }}" class="form-control text-uppercase" placeholder="Nhập đáp án..." required onkeyup="generateCrosswordPreview('add-{{ $phan->MaPhan }}')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn chữ cái giao nhau với từ khóa dọc (Chữ: <span class="text-danger fw-bold" id="vertical-char-add-{{ $phan->MaPhan }}">?</span>)</label>
                                <div class="d-flex gap-2 flex-wrap" id="crossword-preview-add-{{ $phan->MaPhan }}">
                                    <!-- Dynamic letter boxes will be rendered here -->
                                </div>
                                <input type="hidden" name="ViTriGiao" id="vi_tri_giao_add_{{ $phan->MaPhan }}" value="0">
                            </div>
                        @elseif($mapNumber === 3 || $mapNumber === 7)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung dịch Tiếng Việt (Gợi ý)</label>
                                <input type="text" name="NDCauHoi" class="form-control" placeholder="VD: Tôi đi học mỗi ngày / Tôi thích học Tiếng Anh" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số lượng từ trong câu</label>
                                <input type="number" id="word_count_add_{{ $phan->MaPhan }}" class="form-control" min="1" max="30" placeholder="Nhập số từ (VD: 6)" required oninput="generateWordInputs('add-{{ $phan->MaPhan }}')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhập thứ tự các từ trong câu đúng</label>
                                <div id="word_inputs_container_add_{{ $phan->MaPhan }}" class="d-flex flex-column gap-2">
                                    <!-- Dynamic word inputs will be generated here -->
                                </div>
                            </div>
                        @elseif($mapNumber === 2 || $mapNumber === 4)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gợi ý nghĩa từ / Định nghĩa</label>
                                <input type="text" name="NDCauHoi" class="form-control" placeholder="VD: Lời chào thân thiện khi gặp mặt" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Từ/Cụm từ Tiếng Anh</label>
                                <input type="text" name="OptionA" class="form-control" placeholder="VD: hello" required>
                            </div>
                        @elseif($mapNumber === 5)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gợi ý / Mô tả cặp từ</label>
                                <input type="text" name="NDCauHoi" class="form-control" placeholder="VD: Cặp nguyên âm dài/ngắn" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">Từ phát âm đúng (Đáp án đúng)</label>
                                <input type="text" name="OptionA" class="form-control" placeholder="VD: sheep" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Từ gây nhiễu 1 (Đáp án sai)</label>
                                <input type="text" name="OptionB" class="form-control" placeholder="VD: ship" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Từ gây nhiễu 2 (Đáp án sai - Tùy chọn)</label>
                                <input type="text" name="OptionC" class="form-control" placeholder="VD: shape">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Từ gây nhiễu 3 (Đáp án sai - Tùy chọn)</label>
                                <input type="text" name="OptionD" class="form-control" placeholder="VD: shop">
                            </div>
                        @elseif($mapNumber === 6)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nghĩa Tiếng Việt (Thẻ Tiếng Việt)</label>
                                <input type="text" name="NDCauHoi" class="form-control" placeholder="VD: con cừu" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Từ Tiếng Anh (Thẻ Audio phát âm)</label>
                                <input type="text" name="OptionA" class="form-control" placeholder="VD: sheep" required>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung câu hỏi / Gợi ý</label>
                                <input type="text" name="NDCauHoi" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án A</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="A" required onchange="toggleScoreInput(this, 'add-{{$phan->MaPhan}}')">
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreA" id="score-A-add-{{$phan->MaPhan}}" class="form-control mb-1 score-input-add-{{$phan->MaPhan}}" style="display:none;" placeholder="Nhập điểm...">
                                    <input type="text" name="OptionA" class="form-control mb-1">
                                    <input type="file" name="ImageA" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án B</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="B" required onchange="toggleScoreInput(this, 'add-{{$phan->MaPhan}}')">
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreB" id="score-B-add-{{$phan->MaPhan}}" class="form-control mb-1 score-input-add-{{$phan->MaPhan}}" style="display:none;" placeholder="Nhập điểm...">
                                    <input type="text" name="OptionB" class="form-control mb-1">
                                    <input type="file" name="ImageB" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án C</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="C" required onchange="toggleScoreInput(this, 'add-{{$phan->MaPhan}}')">
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreC" id="score-C-add-{{$phan->MaPhan}}" class="form-control mb-1 score-input-add-{{$phan->MaPhan}}" style="display:none;" placeholder="Nhập điểm...">
                                    <input type="text" name="OptionC" class="form-control mb-1">
                                    <input type="file" name="ImageC" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án D</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="D" required onchange="toggleScoreInput(this, 'add-{{$phan->MaPhan}}')">
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreD" id="score-D-add-{{$phan->MaPhan}}" class="form-control mb-1 score-input-add-{{$phan->MaPhan}}" style="display:none;" placeholder="Nhập điểm...">
                                    <input type="text" name="OptionD" class="form-control mb-1">
                                    <input type="file" name="ImageD" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu câu hỏi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach($phan->cauhoi as $question)
    <!-- Modal Edit Question -->
    <div class="modal fade" id="editCauhoiModal-{{ $question->MaCauHoi }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.cauhoi.update', $question->MaCauHoi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa câu hỏi: {{ $phan->TenPhan }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($mapNumber === 1)
                            @php
                                $dapAnOption = $question->phuongancauhoi->firstWhere('DapAn', 'Dung');
                                $wordValue = $dapAnOption ? preg_replace('/^[A-D]\.\s*/', '', $dapAnOption->NDPA) : '';
                            @endphp
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gợi ý câu hỏi</label>
                                <input type="text" name="NDCauHoi" class="form-control" value="{{ $question->NDCauHoi }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đáp án hàng ngang (Viết liền không dấu, VD: FLOWER)</label>
                                <input type="text" name="DapAnOChu" id="dap_an_ochu_edit_{{ $question->MaCauHoi }}" class="form-control text-uppercase" value="{{ $wordValue }}" placeholder="Nhập đáp án..." required onkeyup="generateCrosswordPreview('edit-{{ $question->MaCauHoi }}')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn chữ cái giao nhau với từ khóa dọc (Chữ: <span class="text-danger fw-bold" id="vertical-char-edit-{{ $question->MaCauHoi }}">?</span>)</label>
                                <div class="d-flex gap-2 flex-wrap" id="crossword-preview-edit-{{ $question->MaCauHoi }}">
                                    <!-- Dynamic letter boxes will be rendered here -->
                                </div>
                                <input type="hidden" name="ViTriGiao" id="vi_tri_giao_edit_{{ $question->MaCauHoi }}" value="{{ $question->ViTriGiao }}">
                            </div>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    setTimeout(() => {
                                        generateCrosswordPreview('edit-{{ $question->MaCauHoi }}');
                                    }, 100);
                                });
                            </script>
                        @elseif($mapNumber === 3 || $mapNumber === 7)
                            @php
                                $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                $sentence = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                                $delimiter = ($mapNumber === 7) ? '|' : ' ';
                                $words = $sentence !== '' ? explode($delimiter, $sentence) : [];
                                $wordCount = count($words);
                            @endphp
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung dịch Tiếng Việt (Gợi ý)</label>
                                <input type="text" name="NDCauHoi" class="form-control" value="{{ $question->NDCauHoi }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số lượng từ trong câu</label>
                                <input type="number" id="word_count_edit_{{ $question->MaCauHoi }}" class="form-control" min="1" max="30" value="{{ $wordCount }}" placeholder="Nhập số từ (VD: 6)" required oninput="generateWordInputs('edit-{{ $question->MaCauHoi }}')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nhập thứ tự các từ trong câu đúng</label>
                                <div id="word_inputs_container_edit_{{ $question->MaCauHoi }}" class="d-flex flex-column gap-2">
                                    @foreach($words as $index => $wVal)
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-secondary font-monospace" style="width: 80px;">Từ {{ $index + 1 }}</span>
                                            <input type="text" name="words[]" class="form-control" value="{{ $wVal }}" placeholder="Nhập từ..." required>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($mapNumber === 2 || $mapNumber === 4)
                            @php
                                $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                $word = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                            @endphp
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gợi ý nghĩa từ / Định nghĩa</label>
                                <input type="text" name="NDCauHoi" class="form-control" value="{{ $question->NDCauHoi }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Từ/Cụm từ Tiếng Anh</label>
                                <input type="text" name="OptionA" class="form-control" value="{{ $word }}" required>
                            </div>
                        @elseif($mapNumber === 5)
                            @php
                                $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                $correctWord = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                                
                                $incorrectOpts = $question->phuongancauhoi->where('DapAn', '!=', 'Dung')->values();
                                $wrongWord1 = isset($incorrectOpts[0]) ? preg_replace('/^[A-D]\.\s*/', '', $incorrectOpts[0]->NDPA) : '';
                                $wrongWord2 = isset($incorrectOpts[1]) ? preg_replace('/^[A-D]\.\s*/', '', $incorrectOpts[1]->NDPA) : '';
                                $wrongWord3 = isset($incorrectOpts[2]) ? preg_replace('/^[A-D]\.\s*/', '', $incorrectOpts[2]->NDPA) : '';
                            @endphp
                            <div class="mb-3">
                                <label class="form-label fw-bold">Gợi ý / Mô tả cặp từ</label>
                                <input type="text" name="NDCauHoi" class="form-control" value="{{ $question->NDCauHoi }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">Từ phát âm đúng (Đáp án đúng)</label>
                                <input type="text" name="OptionA" class="form-control" value="{{ $correctWord }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Từ gây nhiễu 1 (Đáp án sai)</label>
                                <input type="text" name="OptionB" class="form-control" value="{{ $wrongWord1 }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Từ gây nhiễu 2 (Đáp án sai - Tùy chọn)</label>
                                <input type="text" name="OptionC" class="form-control" value="{{ $wrongWord2 }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Từ gây nhiễu 3 (Đáp án sai - Tùy chọn)</label>
                                <input type="text" name="OptionD" class="form-control" value="{{ $wrongWord3 }}">
                            </div>
                        @elseif($mapNumber === 6)
                            @php
                                $correctOpt = $question->phuongancauhoi->firstWhere('DapAn', 'Dung') ?? $question->phuongancauhoi->first();
                                $word = $correctOpt ? preg_replace('/^[A-D]\.\s*/', '', $correctOpt->NDPA) : '';
                            @endphp
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nghĩa Tiếng Việt (Thẻ Tiếng Việt)</label>
                                <input type="text" name="NDCauHoi" class="form-control" value="{{ $question->NDCauHoi }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Từ Tiếng Anh (Thẻ Audio phát âm)</label>
                                <input type="text" name="OptionA" class="form-control" value="{{ $word }}" required>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nội dung câu hỏi / Gợi ý</label>
                                <input type="text" name="NDCauHoi" class="form-control" value="{{ $question->NDCauHoi }}" required>
                            </div>
                            <div class="row">
                                @php
                                    $opts = $question->phuongancauhoi;
                                    $optData = ['A' => null, 'B' => null, 'C' => null, 'D' => null];
                                    foreach($opts as $opt) {
                                        $prefix = strtoupper(substr($opt->NDPA, 0, 1));
                                        if(array_key_exists($prefix, $optData)) {
                                            $optData[$prefix] = $opt;
                                        }
                                    }
                                    $textA = $optData['A'] ? preg_replace('/^[A-D]\.\s*/', '', $optData['A']->NDPA) : '';
                                    $textB = $optData['B'] ? preg_replace('/^[A-D]\.\s*/', '', $optData['B']->NDPA) : '';
                                    $textC = $optData['C'] ? preg_replace('/^[A-D]\.\s*/', '', $optData['C']->NDPA) : '';
                                    $textD = $optData['D'] ? preg_replace('/^[A-D]\.\s*/', '', $optData['D']->NDPA) : '';
                                    
                                    $isCorrectA = $optData['A'] && $optData['A']->DapAn == 'Dung';
                                    $isCorrectB = $optData['B'] && $optData['B']->DapAn == 'Dung';
                                    $isCorrectC = $optData['C'] && $optData['C']->DapAn == 'Dung';
                                    $isCorrectD = $optData['D'] && $optData['D']->DapAn == 'Dung';
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án A</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="A" required onchange="toggleScoreInput(this, 'edit-{{$question->MaCauHoi}}')" {{ $isCorrectA ? 'checked' : '' }}>
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreA" id="score-A-edit-{{$question->MaCauHoi}}" class="form-control mb-1 score-input-edit-{{$question->MaCauHoi}}" style="display:{{ $isCorrectA ? 'block' : 'none' }};" placeholder="Nhập điểm..." value="{{ $optData['A'] ? $optData['A']->Diem : 0 }}">
                                    <input type="text" name="OptionA" class="form-control mb-1" value="{{ $textA }}">
                                    @if($optData['A'] && $optData['A']->HinhAnh)
                                        <div class="mb-1"><img src="{{ asset('storage/' . $optData['A']->HinhAnh) }}" alt="A" style="max-height:50px;" class="border rounded"></div>
                                    @endif
                                    <input type="file" name="ImageA" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án B</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="B" required onchange="toggleScoreInput(this, 'edit-{{$question->MaCauHoi}}')" {{ $isCorrectB ? 'checked' : '' }}>
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreB" id="score-B-edit-{{$question->MaCauHoi}}" class="form-control mb-1 score-input-edit-{{$question->MaCauHoi}}" style="display:{{ $isCorrectB ? 'block' : 'none' }};" placeholder="Nhập điểm..." value="{{ $optData['B'] ? $optData['B']->Diem : 0 }}">
                                    <input type="text" name="OptionB" class="form-control mb-1" value="{{ $textB }}">
                                    @if($optData['B'] && $optData['B']->HinhAnh)
                                        <div class="mb-1"><img src="{{ asset('storage/' . $optData['B']->HinhAnh) }}" alt="B" style="max-height:50px;" class="border rounded"></div>
                                    @endif
                                    <input type="file" name="ImageB" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án C</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="C" required onchange="toggleScoreInput(this, 'edit-{{$question->MaCauHoi}}')" {{ $isCorrectC ? 'checked' : '' }}>
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreC" id="score-C-edit-{{$question->MaCauHoi}}" class="form-control mb-1 score-input-edit-{{$question->MaCauHoi}}" style="display:{{ $isCorrectC ? 'block' : 'none' }};" placeholder="Nhập điểm..." value="{{ $optData['C'] ? $optData['C']->Diem : 0 }}">
                                    <input type="text" name="OptionC" class="form-control mb-1" value="{{ $textC }}">
                                    @if($optData['C'] && $optData['C']->HinhAnh)
                                        <div class="mb-1"><img src="{{ asset('storage/' . $optData['C']->HinhAnh) }}" alt="C" style="max-height:50px;" class="border rounded"></div>
                                    @endif
                                    <input type="file" name="ImageC" class="form-control form-control-sm" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="mb-0">Phương án D</label>
                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="CorrectAnswer" value="D" required onchange="toggleScoreInput(this, 'edit-{{$question->MaCauHoi}}')" {{ $isCorrectD ? 'checked' : '' }}>
                                            <label class="form-check-label text-success">Đúng</label>
                                        </div>
                                    </div>
                                    <input type="number" name="ScoreD" id="score-D-edit-{{$question->MaCauHoi}}" class="form-control mb-1 score-input-edit-{{$question->MaCauHoi}}" style="display:{{ $isCorrectD ? 'block' : 'none' }};" placeholder="Nhập điểm..." value="{{ $optData['D'] ? $optData['D']->Diem : 0 }}">
                                    <input type="text" name="OptionD" class="form-control mb-1" value="{{ $textD }}">
                                    @if($optData['D'] && $optData['D']->HinhAnh)
                                        <div class="mb-1"><img src="{{ asset('storage/' . $optData['D']->HinhAnh) }}" alt="D" style="max-height:50px;" class="border rounded"></div>
                                    @endif
                                    <input type="file" name="ImageD" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endforeach

@endforeach

@endsection

@push('scripts')
<script>
    const composingStates = {};

    function generateCrosswordPreview(id) {
        const isAdd = id.startsWith('add-');
        const actualId = id.replace('add-', '').replace('edit-', '');
        const inputEl = document.getElementById(isAdd ? 'dap_an_ochu_add_' + actualId : 'dap_an_ochu_edit_' + actualId);
        const containerEl = document.getElementById('crossword-preview-' + id);
        const hiddenEl = document.getElementById(isAdd ? 'vi_tri_giao_add_' + actualId : 'vi_tri_giao_edit_' + actualId);
        const charIndicator = document.getElementById('vertical-char-' + id);

        if (!inputEl || !containerEl || !hiddenEl) return;

        // Set up composition event listeners once
        if (!inputEl.dataset.compositionBound) {
            inputEl.dataset.compositionBound = 'true';
            inputEl.addEventListener('compositionstart', () => {
                composingStates[id] = true;
            });
            inputEl.addEventListener('compositionend', () => {
                composingStates[id] = false;
                generateCrosswordPreview(id);
            });
            inputEl.addEventListener('input', () => {
                generateCrosswordPreview(id);
            });
        }

        // If user is currently composing IME, do not modify input value yet
        if (composingStates[id]) {
            return;
        }

        let originalVal = inputEl.value;
        let val = originalVal;
        
        val = val.replace(/à|á|ạ|ả|ã|â|ần|ấn|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        val = val.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        val = val.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        val = val.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        val = val.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        val = val.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        val = val.replace(/đ/g, "d");
        val = val.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
        val = val.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
        val = val.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
        val = val.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
        val = val.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
        val = val.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
        val = val.replace(/Đ/g, "D");
        
        val = val.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
        val = val.toUpperCase().trim().replace(/[^A-Z]/g, '');

        if (originalVal !== val) {
            const start = inputEl.selectionStart;
            const end = inputEl.selectionEnd;
            inputEl.value = val;
            if (document.activeElement === inputEl) {
                try {
                    inputEl.setSelectionRange(start, end);
                } catch (e) {}
            }
        }

        let selectedIdx = parseInt(hiddenEl.value) || 0;
        if (selectedIdx >= val.length) {
            selectedIdx = 0;
            hiddenEl.value = 0;
        }

        containerEl.innerHTML = '';

        if (val.length === 0) {
            if(charIndicator) charIndicator.textContent = '?';
            return;
        }

        for (let i = 0; i < val.length; i++) {
            const char = val[i];
            const box = document.createElement('div');
            box.className = 'border rounded text-center fw-bold d-flex align-items-center justify-content-center cursor-pointer transition-all';
            box.style.width = '40px';
            box.style.height = '40px';
            box.style.fontSize = '1.2rem';
            box.textContent = char;

            if (i === selectedIdx) {
                box.classList.add('bg-warning', 'text-dark', 'border-warning');
                if(charIndicator) charIndicator.textContent = char;
            } else {
                box.classList.add('bg-white', 'text-dark');
            }

            box.addEventListener('click', () => {
                hiddenEl.value = i;
                generateCrosswordPreview(id);
            });

            containerEl.appendChild(box);
        }
    }

    function toggleScoreInput(radio, modalId) {
        // Hide all score inputs in this modal group
        document.querySelectorAll('.score-input-' + modalId).forEach(el => {
            el.style.display = 'none';
        });
        
        // Show the score input for the selected correct answer
        let val = radio.value;
        let scoreInput = document.getElementById('score-' + val + '-' + modalId);
        if(scoreInput) {
            scoreInput.style.display = 'block';
            if(!scoreInput.value || scoreInput.value == 0) {
                scoreInput.value = 10; // Default score when checked
            }
        }
    }

    function generateWordInputs(id) {
        const isAdd = id.startsWith('add-');
        const actualId = id.replace('add-', '').replace('edit-', '');
        
        const countInput = document.getElementById(isAdd ? 'word_count_add_' + actualId : 'word_count_edit_' + actualId);
        const container = document.getElementById(isAdd ? 'word_inputs_container_add_' + actualId : 'word_inputs_container_edit_' + actualId);
        
        if (!countInput || !container) return;
        
        const count = parseInt(countInput.value) || 0;
        
        // Save current values to restore them if count increases
        const currentInputs = container.querySelectorAll('input[name="words[]"]');
        const savedValues = [];
        currentInputs.forEach(inp => savedValues.push(inp.value));
        
        container.innerHTML = '';
        
        for (let i = 0; i < count; i++) {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group';
            
            const labelSpan = document.createElement('span');
            labelSpan.className = 'input-group-text bg-light text-secondary font-monospace';
            labelSpan.style.width = '80px';
            labelSpan.textContent = 'Từ ' + (i + 1);
            
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'words[]';
            input.className = 'form-control';
            input.placeholder = 'Nhập từ...';
            input.required = true;
            input.value = savedValues[i] || '';
            
            wrapper.appendChild(labelSpan);
            wrapper.appendChild(input);
            container.appendChild(wrapper);
        }
    }

    function toggleAudioSource(phanId) {
        const isUpload = document.getElementById('sourceUpload-' + phanId).checked;
        const uploadGroup = document.getElementById('uploadGroup-' + phanId);
        const generateGroup = document.getElementById('generateGroup-' + phanId);
        const audioFileInput = document.getElementById('audioFileInput-' + phanId);
        const audioTextInput = document.getElementById('audioTextInput-' + phanId);

        if (isUpload) {
            uploadGroup.classList.remove('d-none');
            generateGroup.classList.add('d-none');
            if (audioFileInput) audioFileInput.required = true;
            if (audioTextInput) audioTextInput.required = false;
        } else {
            uploadGroup.classList.add('d-none');
            generateGroup.classList.remove('d-none');
            if (audioFileInput) audioFileInput.required = false;
            if (audioTextInput) audioTextInput.required = true;
        }
    }

    function toggleAudioSourceEdit(tepFileId) {
        const isUpload = document.getElementById('sourceUploadEdit-' + tepFileId).checked;
        const uploadGroup = document.getElementById('uploadGroupEdit-' + tepFileId);
        const generateGroup = document.getElementById('generateGroupEdit-' + tepFileId);
        const audioTextInput = document.getElementById('audioTextInputEdit-' + tepFileId);

        if (isUpload) {
            uploadGroup.classList.remove('d-none');
            generateGroup.classList.add('d-none');
            if (audioTextInput) audioTextInput.required = false;
        } else {
            uploadGroup.classList.add('d-none');
            generateGroup.classList.remove('d-none');
            if (audioTextInput) audioTextInput.required = true;
        }
    }
</script>
@endpush
