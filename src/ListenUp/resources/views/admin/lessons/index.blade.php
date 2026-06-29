@extends('layouts.admin')

@section('title', 'Quản lý bài học')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-book"></i> Quản lý bài học</h2>
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
                                <th>Mã Bài</th>
                                <th>Tiêu đề</th>
                                <th>Số câu hỏi</th>
                                <th>Chủ đề</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->MaBai }}</td>
                                <td>{{ $lesson->TenBai }}</td>
                                <td>{{ $lesson->SoCauHoi }}</td>
                                <td>{{ $lesson->chude->TenCD }}</td>
                                <td>{{ $lesson->TrangThaiBai }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $lessons->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

