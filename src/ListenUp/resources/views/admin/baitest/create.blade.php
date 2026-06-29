@extends('layouts.admin')
@section('admin_content')
<div class='container mt-4'>
    <h2>Thêm Bài test</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.baitest.store') }}" method='POST' enctype="multipart/form-data">
        @csrf
        <div class='mb-3'>
            <label>Tiêu đề</label>
            <input type='text' name='TenBai' class='form-control' value="{{ old('TenBai') }}" required>
        </div>
        <div class='mb-3'>
            <label>Bản đồ phiêu lưu</label>
            <select name='MaBanDo' id="MaBanDoSelect" class='form-control'>
                <option value="">-- Chọn Bản đồ (Tùy chọn) --</option>
                @foreach($bandos as $b)
                    <option value="{{ $b->MaBanDo }}" {{ old('MaBanDo') == $b->MaBanDo ? 'selected' : '' }}>{{ $b->TenBanDo }} ({{ $b->MaBanDo }})</option>
                @endforeach
            </select>
        </div>
        <div class='mb-3'>
            <label>Chủ đề</label>
            <select name='MaCD' class='form-control'>
                <option value="">-- Chọn Chủ đề (Tùy chọn) --</option>
                @foreach($chudes as $cd)
                    <option value="{{ $cd->MaCD }}" {{ old('MaCD') == $cd->MaCD ? 'selected' : '' }}>{{ $cd->TenCD }} ({{ $cd->MaCD }})</option>
                @endforeach
            </select>
        </div>
        <div class='mb-3'>
            <label>Cấp độ nghe</label>
            <select name='MaCDN' class='form-control'>
                <option value="">-- Chọn Cấp độ nghe (Tùy chọn) --</option>
                @foreach($capdonghes as $cdn)
                    <option value="{{ $cdn->MaCDN }}" {{ old('MaCDN') == $cdn->MaCDN ? 'selected' : '' }}>{{ $cdn->TenCDN }} ({{ $cdn->MaCDN }})</option>
                @endforeach
            </select>
            <small class="text-muted d-block mt-1">Lưu ý: Bài test phải liên kết với ít nhất 1 loại: Bản đồ, Chủ đề hoặc Cấp độ nghe.</small>
        </div>
        <div class='mb-3 border p-3 bg-light rounded' id="puzzle-setup-container">
            <h5 class="text-secondary mb-3"><i class="fas fa-puzzle-piece me-2"></i>Thiết lập Trò chơi Mở mảnh ghép (Map 8)</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ảnh nền mảnh ghép</label>
                    <input type="file" name="AnhTroChoi" class="form-control" accept="image/*">
                    <small class="text-muted">Tải ảnh nền sẽ bị che và cắt thành các mảnh ghép cho trò chơi.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số mảnh ghép sẽ cắt</label>
                    <input type="number" name="SoManhGhep" class="form-control" value="{{ old('SoManhGhep') }}" min="4" placeholder="VD: 4, 9, 16...">
                    <small class="text-muted">Phải là số chính phương (4, 9, 16...) để tạo thành lưới vuông. Tương ứng với số phần test phải tạo.</small>
                </div>
            </div>
        </div>
        <div class='mb-3'>
            <label>Trạng thái</label>
            <select name='TrangThaiBai' class='form-control' required>
                <option value="Mo" {{ old('TrangThaiBai', 'Mo') == 'Mo' ? 'selected' : '' }}>Mo</option>
                <option value="Dong" {{ old('TrangThaiBai') == 'Dong' ? 'selected' : '' }}>Dong</option>
            </select>
        </div>
        <button type='submit' class='btn btn-success'>Lưu</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maBanDoSelect = document.getElementById('MaBanDoSelect');
    const puzzleContainer = document.getElementById('puzzle-setup-container');

    function toggleContainers() {
        const val = maBanDoSelect.value;
        if (val === 'BD08') {
            puzzleContainer.style.display = 'block';
        } else {
            puzzleContainer.style.display = 'none';
        }
        
        // Clear value when hidden
        if (val !== 'BD08') {
            const input = puzzleContainer.querySelector('input[name="SoManhGhep"]');
            if (input) input.value = '';
        }
    }

    if (maBanDoSelect) {
        maBanDoSelect.addEventListener('change', toggleContainers);
        toggleContainers(); // Run initially
    }
});
</script>
@endpush
@endsection
