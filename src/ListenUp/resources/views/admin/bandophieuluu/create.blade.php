@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Thêm Bản đồ</h2>
    <form action="{{ route('admin.bandophieuluu.store') }}" method='POST'>
        @csrf
        <div class='mb-3'>
            <label>Tên bản đồ</label>
            <input type='text' name='TenBanDo' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Yêu cầu</label>
            <input type='text' name='YeuCauBanDo' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Trạng thái</label>
            <input type='text' name='TrangThaiBanDo' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-success'>Lưu</button>
    </form>
</div>
@endsection
