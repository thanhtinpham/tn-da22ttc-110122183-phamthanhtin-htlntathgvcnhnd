@extends('layouts.app')

@section('title', 'Kết quả luyện tập')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-chart-line"></i> Kết quả luyện tập của tôi</h2>
            <hr>
        </div>
    </div>

    @if($results->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Bài test</th>
                                    <th>Thời gian nộp</th>
                                    <th>Kết quả</th>
                                    <th>Làm bài</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $index => $result)
                                @php
                                    $testName = $result->baitest ? $result->baitest->TenBai : 'Test ID: ' . $result->MaBai;
                                    $duration = $result->ThoiGianLam ?? 0;
                                    $minutes = floor($duration / 60);
                                    $seconds = $duration % 60;
                                    $submitDate = $result->CreatedAt ? \Carbon\Carbon::parse($result->CreatedAt)->format('d/m/Y H:i') : 'N/A';
                                @endphp
                                <tr>
                                    <td>{{ $results->firstItem() + $index }}</td>
                                    <td class="fw-bold text-primary">{{ $testName }}</td>
                                    <td>{{ $submitDate }}</td>
                                    <td><span class="badge bg-success">{{ $result->SoCauDung ?? 0 }} / {{ $result->TongSoCau ?? 0 }}</span></td>
                                    <td>{{ sprintf("%02d:%02d", $minutes, $seconds) }}</td>
                                    <td>
                                        <a href="{{ route('user.results.show', $result->MaCTLB) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye me-1"></i> Xem chi tiết</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            {{ $results->links() }}
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Bạn chưa có kết quả luyện tập nào. 
                <a href="{{ route('user.lessons') }}" class="alert-link">Bắt đầu học ngay</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection