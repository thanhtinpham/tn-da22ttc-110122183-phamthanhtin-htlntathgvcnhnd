@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-tags"></i> Quản lý danh mục</h2>
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
                                <th>Mã CD</th>
                                <th>Tên chủ đề</th>
                                <th>Mô tả</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->MaCD }}</td>
                                <td>{{ $category->TenCD }}</td>
                                <td>{{ $category->MoTa }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

