@extends('layouts.admin')

@section('title', 'Quản lý học viên')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-users"></i> Quản lý học viên</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mã User</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->UserID }}</td>
                                <td>{{ $user->UserName }}</td>
                                <td>{{ $user->Email }}</td>
                                <td>{{ $user->Role }}</td>
                                <td>{{ $user->Status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

