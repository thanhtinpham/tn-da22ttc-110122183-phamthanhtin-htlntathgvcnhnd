@extends('layouts.app')

@section('title', 'Chi tiết kết quả: ' . ($result->baitest->TenBai ?? 'Không xác định'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-check-circle text-success me-2"></i> Kết quả làm bài</h2>
                <a href="{{ route('user.results') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại lịch sử</a>
            </div>

            <!-- Tổng quan kết quả -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body p-4 bg-light text-center">
                    <h3 class="fw-bold text-dark mb-3">{{ $result->baitest->TenBai ?? 'Không xác định' }}</h3>
                    <div class="row justify-content-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-white rounded shadow-sm border">
                                <h1 class="display-5 fw-bold text-primary mb-0">{{ $result->SoCauDung ?? 0 }} / {{ $result->TongSoCau ?? 0 }}</h1>
                                <span class="text-muted">Câu trả lời đúng</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-white rounded shadow-sm border h-100 d-flex flex-column justify-content-center">
                                @php
                                    $duration = $result->ThoiGianLam ?? 0;
                                    $minutes = floor($duration / 60);
                                    $seconds = $duration % 60;
                                @endphp
                                <h3 class="fw-bold text-dark mb-0"><i class="fas fa-clock text-warning me-2"></i>{{ sprintf("%02d:%02d", $minutes, $seconds) }}</h3>
                                <span class="text-muted">Thời gian làm bài</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết từng câu hỏi -->
            <h4 class="mb-4">Xem lại bài làm</h4>
            
            @if($result->baitest && $result->baitest->phan)
                @php $globalQuestionIndex = 1; @endphp
                @foreach($result->baitest->phan as $phan)
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-header bg-white p-4 border-bottom-0">
                            <h5 class="fw-bold text-primary mb-1">{{ $phan->TenPhan }}</h5>
                        </div>
                        <div class="card-body p-4 pt-0">
                            @foreach($phan->cauhoi as $question)
                                @php
                                    $userChoice = null;
                                    foreach($userAnswers as $qId => $ans) {
                                        if (trim($qId) == trim($question->MaCauHoi)) {
                                            $userChoice = $ans;
                                            break;
                                        }
                                    }
                                @endphp
                                <div class="mb-4 p-3 border rounded {{ $userChoice ? '' : 'bg-light' }}">
                                    <h6 class="fw-bold mb-3">
                                        <span class="text-dark me-2">Câu {{ $globalQuestionIndex++ }}:</span>
                                        {{ $question->NDCauHoi }}
                                    </h6>
                                    
                                    <div class="options-list d-flex flex-column gap-2">
                                        @foreach($question->phuongancauhoi->sortBy('NDPA') as $option)
                                            @php
                                                $isCorrectOption = (mb_strtolower(trim($option->DapAn), 'UTF-8') === 'dung' || mb_strtolower(trim($option->DapAn), 'UTF-8') === 'đúng' || $option->DapAn == '1');
                                                $isSelected = (trim($userChoice) == trim($option->MaPA));
                                                
                                                $bgClass = '';
                                                $icon = '';
                                                
                                                if ($isSelected && $isCorrectOption) {
                                                    $bgClass = 'bg-success text-white border-success';
                                                    $icon = '<i class="fas fa-check-circle float-end mt-1"></i>';
                                                } elseif ($isSelected && !$isCorrectOption) {
                                                    $bgClass = 'bg-danger text-white border-danger';
                                                    $icon = '<i class="fas fa-times-circle float-end mt-1"></i> <span class="badge bg-white text-danger float-end me-2 mt-1">Sai</span>';
                                                } elseif (!$isSelected && $isCorrectOption) {
                                                    $bgClass = 'bg-success bg-opacity-25 border-success fw-bold';
                                                    $icon = '<span class="badge bg-success float-end mt-1">Đúng</span>';
                                                }
                                            @endphp
                                            <div class="p-2 border rounded {{ $bgClass }}">
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="radio" disabled {{ $isSelected ? 'checked' : '' }}>
                                                    <label class="form-check-label d-block w-100">
                                                        {{ $option->NDPA }}
                                                        {!! $icon !!}
                                                        
                                                        @if($option->HinhAnh)
                                                            <div class="mt-2">
                                                                <img src="{{ asset('storage/' . $option->HinhAnh) }}" alt="Option Image" style="max-height: 100px; object-fit: contain;" class="border rounded bg-white">
                                                            </div>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(!$userChoice)
                                        <div class="mt-2 text-warning"><i class="fas fa-exclamation-triangle"></i> Bạn đã bỏ trống câu này.</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
            
            <div class="text-center mt-4">
                <a href="{{ route('user.lessons') }}" class="btn btn-primary btn-lg rounded-pill px-5"><i class="fas fa-book-open me-2"></i> Luyện tập bài khác</a>
            </div>
        </div>
    </div>
</div>
@endsection
