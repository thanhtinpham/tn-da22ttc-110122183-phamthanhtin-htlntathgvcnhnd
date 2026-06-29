@extends('layouts.admin')
@section('title', 'Quản lý Cấp độ nghe')
@section('admin_content')
<div class='container mt-4'>
    <h2>Quản lý Cấp độ nghe</h2>
    <a href="{{ route('admin.capdonghe.create') }}" class='btn btn-primary mb-3'>Thêm mới</a>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>Tên cấp độ</th>
<th>Mô tả</th>

                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->TenCDN }}</td>
<td>{{ $row->MoTaCDN }}</td>

                <td>
                    <a href="{{ route('admin.capdonghe.edit', $row->MaCDN) }}" class='btn btn-sm btn-warning'>Sửa</a>
                    <form action="{{ route('admin.capdonghe.destroy', $row->MaCDN) }}" method='POST' class='d-inline'>
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
