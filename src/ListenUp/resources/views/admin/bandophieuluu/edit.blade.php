@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Sửa Bản đồ</h2>
    <form action="{{ route('admin.bandophieuluu.update', $item->MaBanDo) }}" method='POST'>
        @csrf
        @method('PUT')
        <div class='mb-3'>
            <label>Tên bản đồ</label>
            <input type='text' name='TenBanDo' class='form-control' value="{{ $item->TenBanDo }}" required>
        </div>
        <div class='mb-3'>
            <label>Yêu cầu</label>
            <input type='text' name='YeuCauBanDo' class='form-control' value="{{ $item->YeuCauBanDo }}" required>
        </div>
        <div class='mb-3'>
            <label>Trạng thái</label>
            <input type='text' name='TrangThaiBanDo' class='form-control' value="{{ $item->TrangThaiBanDo }}" required>
        </div>
        <button type='submit' class='btn btn-success'>Cập nhật</button>
    </form>
</div>
@endsection
