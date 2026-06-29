@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Thêm Chủ đề</h2>
    <form action="{{ route('admin.chude.store') }}" method='POST'>
        @csrf
        <div class='mb-3'>
            <label>Tên chủ đề</label>
            <input type='text' name='TenCD' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Mô tả</label>
            <input type='text' name='MoTa' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-success'>Lưu</button>
    </form>
</div>
@endsection
