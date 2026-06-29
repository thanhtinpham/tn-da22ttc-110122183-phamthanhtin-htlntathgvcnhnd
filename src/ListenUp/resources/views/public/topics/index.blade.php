@extends('layouts.app')
@section('title', 'Tất cả chủ đề nghe')
@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold text-[var(--text-primary)]">Khám phá các chủ đề nghe</h2>
            <p class="text-[var(--text-secondary)]">Chọn một chủ đề yêu thích để bắt đầu luyện tập</p>
        </div>
    </div>
    
    <div class="row">
        @foreach($topics as $index => $topic)
        @php
            $colors = ['primary', 'success', 'danger', 'warning', 'info', 'secondary'];
            $color = $colors[$index % count($colors)];
        @endphp
        <div class="col-md-4 mb-4">
            <a href="{{ route('public.topics.detail', $topic->MaCD) }}" class="text-decoration-none">
                <div class="card bg-[var(--brand-card-bg)] h-100 border border-{{ $color }} shadow-sm topic-card" style="border-radius: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-width: 2px !important; backdrop-filter: blur(12px);">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mx-auto mb-3 text-{{ $color }}" style="width: 60px; height: 60px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; background-color: rgba(var(--bs-{{ $color }}-rgb), 0.1);">
                            <i class="{{ $topic->icon_class }}"></i>
                        </div>
                        <h4 class="fw-bold text-[var(--text-primary)]">{{ $topic->TenCD }}</h4>
                        <p class="text-[var(--text-secondary)] small">{{ Str::limit($topic->MoTa, 80) }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
<style>
    .topic-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
</div>
@endsection
