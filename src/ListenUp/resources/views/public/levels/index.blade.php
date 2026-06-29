@extends('layouts.app')

@section('title', 'Cấp độ nghe - English Listening')

@push('styles')
<style>
    .level-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-[var(--text-primary)]">Chọn cấp độ của bạn</h1>
        <p class="text-[var(--text-secondary)]">Chúng tôi có các bài nghe từ cơ bản đến nâng cao phù hợp với mọi trình độ</p>
    </div>

    <div class="row">
        @foreach($levels as $index => $level)
        @php
            $colors = ['info', 'warning', 'danger', 'success', 'primary'];
            $color = $colors[$index % count($colors)];
        @endphp
        <div class="col-md-4 mb-4">
            <a href="{{ route('public.levels.show', $level->MaCDN) }}" class="text-decoration-none">
                <div class="card bg-[var(--brand-card-bg)] h-100 border border-{{ $color }} shadow-sm level-card" style="border-radius: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-width: 2px !important; backdrop-filter: blur(12px);">
                    <div class="card-body p-4 text-center">
                        <div class="level-icon mx-auto mb-3 text-{{ $color }}" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; background-color: rgba(var(--bs-{{ $color }}-rgb), 0.1);">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h3 class="fw-bold text-[var(--text-primary)]">{{ $level->TenCDN }}</h3>
                        <p class="text-[var(--text-secondary)] small mt-2">{{ $level->MoTaCDN }}</p>
                        <span class="btn btn-outline-{{ $color }} btn-sm mt-3 px-4 rounded-pill fw-bold">Khám phá</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
