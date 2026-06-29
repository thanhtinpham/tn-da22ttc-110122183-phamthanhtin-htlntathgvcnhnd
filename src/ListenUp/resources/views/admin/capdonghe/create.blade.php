@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Thêm Cấp độ nghe</h2>
    <form action="{{ route('admin.capdonghe.store') }}" method='POST'>
        @csrf
        <div class='mb-3'>
            <label>Tên cấp độ</label>
            <input type='text' name='TenCDN' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Mô tả</label>
            <input type='text' name='MoTaCDN' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-success'>Lưu</button>
    </form>
</div>
@endsection
