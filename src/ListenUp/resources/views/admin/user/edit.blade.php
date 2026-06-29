@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Sửa Người dùng</h2>
    <form action="{{ route('admin.user.update', $item->UserID) }}" method='POST'>
        @csrf
        @method('PUT')
        <div class='mb-3'>
            <label>Tên</label>
            <input type='text' name='UserName' class='form-control' value="{{ $item->UserName }}" required>
        </div>
        <div class='mb-3'>
            <label>Email</label>
            <input type='text' name='Email' class='form-control' value="{{ $item->Email }}" required>
        </div>
        <div class='mb-3'>
            <label>Vai trò</label>
            <input type='text' name='Role' class='form-control' value="{{ $item->Role }}" required>
        </div>
        <button type='submit' class='btn btn-success'>Cập nhật</button>
    </form>
</div>
@endsection
