@extends('layouts.admin')
@section('title', 'Quản lý Bài test')
@section('admin_content')
<div class='container mt-4'>
    <h2>Quản lý Bài test</h2>
    <a href="{{ route('admin.baitest.create') }}" class='btn btn-primary mb-3'>Thêm mới</a>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Liên kết</th>
                <th>Trạng thái</th>
                
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->TenBai }}</td>
                <td>
                    @if($row->bandophieuluu)
                        <span class="badge bg-primary d-block mb-1">Bản đồ: {{ $row->bandophieuluu->TenBanDo }}</span>
                    @endif
                    @if($row->chude)
                        <span class="badge bg-success d-block mb-1">Chủ đề: {{ $row->chude->TenCD }}</span>
                    @endif
                    @if($row->capdonghe)
                        <span class="badge bg-info text-dark d-block">Cấp độ: {{ $row->capdonghe->TenCDN }}</span>
                    @endif
                    @if(!$row->bandophieuluu && !$row->chude && !$row->capdonghe)
                        <span class="text-muted">Chưa liên kết</span>
                    @endif
                </td>
                <td>{{ $row->TrangThaiBai }}</td>

                <td>
                    <a href="{{ route('admin.baitest.content', $row->MaBai) }}" class='btn btn-sm btn-info'>Nội dung</a>
                    <a href="{{ route('admin.baitest.edit', $row->MaBai) }}" class='btn btn-sm btn-warning'>Sửa</a>
                    <form action="{{ route('admin.baitest.destroy', $row->MaBai) }}" method='POST' class='d-inline'>
                        @csrf
                        @method('DELETE')
                        <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm("Xóa?")'>Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {!! $data->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
