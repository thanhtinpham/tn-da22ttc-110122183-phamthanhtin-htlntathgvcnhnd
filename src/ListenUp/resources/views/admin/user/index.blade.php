@extends('layouts.admin')
@section('title', 'Quản lý Người dùng')
@section('admin_content')
<div class='container mt-4'>
    <h2>Quản lý Người dùng</h2>
    <a href="{{ route('admin.user.create') }}" class='btn btn-primary mb-3'>Thêm mới</a>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->UserName }}</td>
                <td>{{ $row->Email }}</td>
                <td>{{ $row->Role }}</td>
                <td>
                    <span class="badge {{ $row->Status == 'Chặn' ? 'bg-danger' : 'bg-success' }}">
                        {{ $row->Status ?? 'action' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.user.show', $row->UserID) }}" class='btn btn-sm btn-info text-white'>Chi tiết</a>
                    <a href="{{ route('admin.user.edit', $row->UserID) }}" class='btn btn-sm btn-warning'>Sửa</a>
                    <form action="{{ route('admin.user.destroy', $row->UserID) }}" method='POST' class='d-inline'>
                        @csrf
                        @method('DELETE')
                        @if($row->Status == 'Chặn')
                            <button type='submit' class='btn btn-sm btn-success' onclick='return confirm("Bỏ chặn người dùng này?")'>Bỏ chặn</button>
                        @else
                            <button type='submit' class='btn btn-sm btn-danger' onclick='return confirm("Chặn người dùng này?")'>Chặn</button>
                        @endif
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
