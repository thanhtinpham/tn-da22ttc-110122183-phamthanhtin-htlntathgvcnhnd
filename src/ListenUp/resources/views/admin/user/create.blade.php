@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Thêm Người dùng</h2>
    <form action="{{ route('admin.user.store') }}" method='POST'>
        @csrf
        <div class='mb-3'>
            <label>Tên</label>
            <input type='text' name='UserName' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Email</label>
            <input type='text' name='Email' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>Vai trò</label>
            <input type='text' name='Role' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-success'>Lưu</button>
    </form>
</div>
@endsection
