@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Sửa Chủ đề</h2>
    <form action="{{ route('admin.chude.update', $item->MaCD) }}" method='POST'>
        @csrf
        @method('PUT')
        <div class='mb-3'>
            <label>Tên chủ đề</label>
            <input type='text' name='TenCD' class='form-control' value="{{ $item->TenCD }}" required>
        </div>
        <div class='mb-3'>
            <label>Mô tả</label>
            <input type='text' name='MoTa' class='form-control' value="{{ $item->MoTa }}" required>
        </div>
        <button type='submit' class='btn btn-success'>Cập nhật</button>
    </form>
</div>
@endsection
