@extends('layouts.admin')
@section('title', 'Nội dung Bản đồ: ' . $map->TenBanDo)
@section('admin_content')
<div class='container mt-4'>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2>Nội dung Bản đồ: <span class="text-primary">{{ $map->TenBanDo }}</span></h2>
        <a href="{{ route('admin.bandophieuluu.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thêm Bài test vào Bản đồ</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bandophieuluu.assign', $map->MaBanDo) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Chọn Bài test</label>
                            <select name="MaBai" class="form-control" required>
                                <option value="">-- Chọn bài test --</option>
                                @foreach($allTests as $test)
                                    @if($test->MaBanDo != $map->MaBanDo)
                                        <option value="{{ $test->MaBai }}">{{ $test->TenBai }} ({{ $test->MaBai }}) - Đang thuộc: {{ $test->MaBanDo ?? 'Chưa có' }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Thêm vào Bản đồ</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Các Bài test đang có trong Bản đồ này</h5>
                </div>
                <div class="card-body p-0">
                    @if($map->baitests->count() > 0)
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã Bài</th>
                                    <th>Tên Bài Test</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($map->baitests as $test)
                                    <tr>
                                        <td>{{ $test->MaBai }}</td>
                                        <td>
                                            <strong>{{ $test->TenBai }}</strong>
                                            <div class="text-muted small">Chủ đề: {{ $test->MaCD }}</div>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('admin.bandophieuluu.unassign', ['id' => $map->MaBanDo, 'test_id' => $test->MaBai]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn gỡ bài test này khỏi bản đồ?')"><i class="fas fa-times"></i> Gỡ</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-4 text-center text-muted">
                            Chưa có bài test nào được gán vào bản đồ này.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
