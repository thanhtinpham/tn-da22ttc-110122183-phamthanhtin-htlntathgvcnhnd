@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Sửa Cấp độ nghe</h2>
    <form action="{{ route('admin.capdonghe.update', $item->MaCDN) }}" method='POST'>
        @csrf
        @method('PUT')
        <div class='mb-3'>
            <label>Tên cấp độ</label>
            <input type='text' name='TenCDN' class='form-control' value="{{ $item->TenCDN }}" required>
        </div>
        <div class='mb-3'>
            <label>Mô tả</label>
            <input type='text' name='MoTaCDN' class='form-control' value="{{ $item->MoTaCDN }}" required>
        </div>
        <button type='submit' class='btn btn-success'>Cập nhật</button>
    </form>
</div>
@endsection
