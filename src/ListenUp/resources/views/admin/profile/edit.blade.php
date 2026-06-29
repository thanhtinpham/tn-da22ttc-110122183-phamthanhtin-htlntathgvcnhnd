@extends('layouts.admin')
@section('title', 'Thông tin Cá nhân - Admin')

@section('admin_content')
<div class="p-3">
    <div class="mb-4">
        <h3 class="fw-bold text-dark flex items-center gap-2">
            <i class="fas fa-user-edit text-primary"></i> Hồ sơ Cá nhân Admin
        </h3>
        <p class="text-muted">Cập nhật thông tin chi tiết và ảnh đại diện của tài khoản quản trị.</p>
        <hr>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Đã xảy ra lỗi!</h5>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            <!-- Cột trái: Ảnh đại diện -->
            <div class="col-md-4 text-center">
                <div class="d-flex flex-column align-items-center">
                    <!-- Avatar Preview Container -->
                    <div id="avatar-container" class="position-relative group cursor-pointer mb-3 w-48 h-48 flex items-center justify-center">
                        <!-- Frame Image -->
                        <img id="frame-preview" src="{{ $user->Vien ? asset('images/' . $user->Vien) : '' }}" 
                             onerror="this.onerror=null; this.src='{{ $user->Vien ? asset('storage/' . $user->Vien) : '' }}';" 
                             class="position-absolute top-0 start-0 w-100 h-100 object-cover z-3 pointer-events-none rounded-circle shadow-sm {{ $user->Vien ? '' : 'hidden' }}">
                        
                        <!-- Avatar Image Wrapper -->
                        <div id="avatar-wrapper" class="rounded-circle overflow-hidden bg-light z-0 position-relative transition-all duration-300 {{ $user->Vien ? 'w-[72%] h-[72%]' : 'w-100 h-100 border border-4 border-light shadow-sm' }}">
                            <img id="avatar-preview" src="{{ $user->AnhDaiDien ? asset('storage/' . $user->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($user->UserName).'&background=0D8ABC&color=fff&size=160' }}" alt="Avatar" class="w-100 h-100 object-cover">
                        </div>
                        <input type="file" name="AnhDaiDien" id="AnhDaiDien" class="d-none" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <label for="AnhDaiDien" class="btn btn-outline-secondary btn-sm mb-2">
                        <i class="fas fa-camera me-1"></i> Thay đổi ảnh
                    </label>
                    <small class="text-muted d-block mb-4">Định dạng: JPG, PNG, GIF (Tối đa 2MB)</small>

                    <!-- Frame selection grid -->
                    <div class="w-100 mt-2">
                        <label class="form-label d-block fw-bold text-start mb-2">
                            <i class="fas fa-certificate text-warning me-1"></i> Khung Viền Avatar
                        </label>
                        <input type="hidden" name="Vien" id="Vien" value="{{ $user->Vien }}">
                        
                        <div class="row g-2 border rounded p-2 bg-light align-items-center" style="max-h: 220px; overflow-y: auto;">
                            <!-- No frame option -->
                            <div class="col-3">
                                <div onclick="selectFrame('')" class="frame-option cursor-pointer border rounded bg-white p-1 d-flex align-items-center justify-center ratio ratio-1x1 {{ empty($user->Vien) ? 'border-primary shadow-sm' : '' }}" style="{{ empty($user->Vien) ? 'border-width: 2px !important;' : '' }}" data-frame="">
                                    <div class="rounded-circle border border-dashed d-flex align-items-center justify-center text-muted" style="width: 32px; height: 32px;">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                            
                            @foreach($frames as $frame)
                                <div class="col-3">
                                    <div onclick="selectFrame('{{ $frame['filename'] }}')" class="frame-option cursor-pointer border rounded bg-white p-1 d-flex align-items-center justify-center ratio ratio-1x1 {{ $user->Vien === $frame['filename'] ? 'border-primary shadow-sm' : '' }}" style="{{ $user->Vien === $frame['filename'] ? 'border-width: 2px !important;' : '' }}" data-frame="{{ $frame['filename'] }}">
                                        <div class="position-relative w-100 h-100 d-flex align-items-center justify-center">
                                            <img src="{{ asset('images/' . $frame['filename']) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $frame['filename']) }}';" class="position-absolute top-0 start-0 w-100 h-100 object-cover rounded-circle z-3 pointer-events-none">
                                            <img src="{{ $user->AnhDaiDien ? asset('storage/' . $user->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($user->UserName).'&background=random&color=fff' }}" class="w-[72%] h-[72%] rounded-circle object-cover z-0">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Thông tin tài khoản -->
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="UserName" class="form-label fw-bold">Tên hiển thị <span class="text-danger">*</span></label>
                        <input type="text" name="UserName" id="UserName" value="{{ old('UserName', $user->UserName) }}" required class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="SDT" class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="SDT" id="SDT" value="{{ old('SDT', $user->SDT) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="NgaySinh" class="form-label fw-bold">Ngày sinh</label>
                        <input type="date" name="NgaySinh" id="NgaySinh" value="{{ old('NgaySinh', $user->NgaySinh ? \Carbon\Carbon::parse($user->NgaySinh)->format('Y-m-d') : '') }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label d-block fw-bold">Giới tính</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="GioiTinh" id="genderNam" value="Nam" {{ old('GioiTinh', $user->GioiTinh) == 'Nam' ? 'checked' : '' }}>
                            <label class="form-check-label" for="genderNam">Nam</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="GioiTinh" id="genderNu" value="Nữ" {{ old('GioiTinh', $user->GioiTinh) == 'Nữ' ? 'checked' : '' }}>
                            <label class="form-check-label" for="genderNu">Nữ</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="GioiTinh" id="genderKhac" value="Khác" {{ (!in_array(old('GioiTinh', $user->GioiTinh), ['Nam', 'Nữ']) && $user->GioiTinh != null) ? 'checked' : '' }}>
                            <label class="form-check-label" for="genderKhac">Khác</label>
                        </div>
                    </div>

                    <!-- Phần Đổi mật khẩu -->
                    <div class="col-12 mt-4">
                        <div class="bg-light p-3 rounded">
                            <h5 class="fw-bold mb-3"><i class="fas fa-key me-1"></i> Đổi mật khẩu (Tùy chọn)</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="password" id="password" placeholder="Bỏ trống nếu không đổi" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4 me-2">Hủy</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                // Update main preview
                document.getElementById('avatar-preview').src = e.target.result;
                
                // Update small previews inside frame options list
                document.querySelectorAll('.frame-option img.z-0').forEach(img => {
                    img.src = e.target.result;
                });
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function selectFrame(filename) {
        document.getElementById('Vien').value = filename;
        
        const frameImg = document.getElementById('frame-preview');
        const wrapper = document.getElementById('avatar-wrapper');
        
        if (filename) {
            frameImg.src = "{{ asset('images') }}/" + filename;
            frameImg.classList.remove('hidden');
            wrapper.className = "rounded-circle overflow-hidden bg-light z-0 position-relative transition-all duration-300 w-[72%] h-[72%]";
        } else {
            frameImg.src = "";
            frameImg.classList.add('hidden');
            wrapper.className = "rounded-circle overflow-hidden bg-light z-0 position-relative transition-all duration-300 w-100 h-100 border border-4 border-light shadow-sm";
        }
        
        document.querySelectorAll('.frame-option').forEach(el => {
            if (el.getAttribute('data-frame') === filename) {
                el.classList.add('border-primary', 'shadow-sm');
                el.style.borderWidth = '2px';
            } else {
                el.classList.remove('border-primary', 'shadow-sm');
                el.style.borderWidth = '1px';
            }
        });
    }
</script>
@endsection
