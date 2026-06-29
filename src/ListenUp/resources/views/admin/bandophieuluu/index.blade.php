@extends('layouts.admin')
@section('title', 'Quản lý Bản đồ')
@section('admin_content')
<div class='container mt-4'>
    <h2>Quản lý Bản đồ</h2>
    <a href="{{ route('admin.bandophieuluu.create') }}" class='btn btn-primary mb-3'>Thêm mới</a>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>Tên bản đồ</th>
<th>Yêu cầu</th>
<th>Trạng thái</th>

                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->TenBanDo }}</td>
<td>{{ $row->YeuCauBanDo }}</td>
<td>{{ $row->TrangThaiBanDo }}</td>

                <td>
                    <a href="{{ route('admin.bandophieuluu.content', $row->MaBanDo) }}" class='btn btn-sm btn-info'>Nội dung</a>
                    <a href="{{ route('admin.bandophieuluu.edit', $row->MaBanDo) }}" class='btn btn-sm btn-warning'>Sửa</a>
                    <form action="{{ route('admin.bandophieuluu.destroy', $row->MaBanDo) }}" method='POST' class='d-inline'>
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
