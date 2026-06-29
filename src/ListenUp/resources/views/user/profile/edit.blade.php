@extends('layouts.app')
@section('title', 'Cập nhật Thông tin Cá nhân')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
            <i class="fas fa-user-edit text-primary"></i> Chỉnh sửa Hồ sơ
        </h2>
        <p class="text-gray-500 mt-2">Cập nhật thông tin cá nhân và ảnh đại diện của bạn.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-r-lg" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-lg" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mt-0.5 mr-2"></i>
                <div>
                    <p class="font-bold">Đã có lỗi xảy ra!</p>
                    <ul class="list-disc ml-5 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="flex flex-col md:flex-row gap-10">
                <!-- Avatar Upload Section -->
                <div class="w-full md:w-1/3 flex flex-col items-center">
                    <!-- Avatar Preview Container -->
                    <div id="avatar-container" class="relative group cursor-pointer mb-4 w-48 h-48 flex items-center justify-center">
                        <!-- Frame Image -->
                        <img id="frame-preview" src="{{ $user->Vien ? asset('images/' . $user->Vien) : '' }}" 
                             onerror="this.onerror=null; this.src='{{ $user->Vien ? asset('storage/' . $user->Vien) : '' }}';" 
                             class="absolute inset-0 w-full h-full object-cover z-10 pointer-events-none rounded-full drop-shadow-md {{ $user->Vien ? '' : 'hidden' }}">
                        
                        <!-- Avatar Image Wrapper -->
                        <div id="avatar-wrapper" class="rounded-full overflow-hidden bg-gray-50 flex items-center justify-center z-0 relative transition-all duration-300 {{ $user->Vien ? 'w-[72%] h-[72%]' : 'w-full h-full border-4 border-gray-100 shadow-md' }}">
                            <img id="avatar-preview" src="{{ $user->AnhDaiDien ? asset('storage/' . $user->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($user->UserName).'&background=0D8ABC&color=fff&size=160' }}" alt="Avatar" class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20">
                                <i class="fas fa-camera text-white text-2xl"></i>
                            </div>
                        </div>
                        <input type="file" name="AnhDaiDien" id="AnhDaiDien" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <label for="AnhDaiDien" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium cursor-pointer transition-colors border border-gray-200">
                        Thay đổi ảnh đại diện
                    </label>
                    <p class="text-xs text-gray-400 mt-2 text-center mb-6">JPG, PNG, GIF (Tối đa 2MB)</p>

                    <!-- Frame selection grid -->
                    <div class="w-full">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 text-center md:text-left">
                            <i class="fas fa-certificate text-yellow-500 mr-1"></i> Khung Viền Avatar
                        </label>
                        <input type="hidden" name="Vien" id="Vien" value="{{ $user->Vien }}">
                        
                        <div class="grid grid-cols-4 gap-2.5 max-h-[220px] overflow-y-auto p-1.5 border border-gray-100 rounded-xl bg-gray-50/50">
                            <!-- No frame option -->
                            <div onclick="selectFrame('')" class="frame-option cursor-pointer relative aspect-square rounded-lg border-2 flex items-center justify-center bg-white transition-all hover:scale-105 {{ empty($user->Vien) ? 'border-primary shadow-sm ring-2 ring-primary/20' : 'border-gray-200' }}" data-frame="">
                                <div class="w-10 h-10 rounded-full border border-dashed border-gray-300 flex items-center justify-center text-gray-400 text-xs font-bold bg-white">
                                    <i class="fas fa-times text-xs"></i>
                                </div>
                            </div>
                            
                            @foreach($frames as $frame)
                                @if($frame['unlocked'])
                                    <!-- Unlocked frame -->
                                    <div onclick="selectFrame('{{ $frame['filename'] }}')" class="frame-option cursor-pointer relative aspect-square rounded-lg border-2 flex items-center justify-center bg-white transition-all hover:scale-105 {{ $user->Vien === $frame['filename'] ? 'border-primary shadow-sm ring-2 ring-primary/20' : 'border-gray-200' }}" data-frame="{{ $frame['filename'] }}">
                                        <div class="relative w-11 h-11 flex items-center justify-center">
                                            <img src="{{ asset('images/' . $frame['filename']) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $frame['filename']) }}';" class="absolute inset-0 w-full h-full object-cover rounded-full z-10 pointer-events-none">
                                            <img src="{{ $user->AnhDaiDien ? asset('storage/' . $user->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($user->UserName).'&background=random&color=fff' }}" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                    </div>
                                @else
                                    <!-- Locked frame -->
                                    <div class="relative aspect-square rounded-lg border-2 border-gray-100 flex items-center justify-center bg-gray-100/50 cursor-not-allowed group" title="Hoàn thành Map tương ứng để mở khóa!">
                                        <div class="relative w-11 h-11 flex items-center justify-center filter grayscale opacity-40">
                                            <img src="{{ asset('images/' . $frame['filename']) }}" onerror="this.onerror=null; this.src='{{ asset('storage/' . $frame['filename']) }}';" class="absolute inset-0 w-full h-full object-cover rounded-full z-10 pointer-events-none">
                                            <img src="{{ $user->AnhDaiDien ? asset('storage/' . $user->AnhDaiDien) : 'https://ui-avatars.com/api/?name='.urlencode($user->UserName).'&background=random&color=fff' }}" class="w-[72%] h-[72%] rounded-full object-cover z-0">
                                        </div>
                                        <div class="absolute inset-0 bg-black/10 flex items-center justify-center rounded-lg">
                                            <i class="fas fa-lock text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- User Info Form -->
                <div class="w-full md:w-2/3 space-y-5">
                    <div>
                        <label for="UserName" class="block text-sm font-semibold text-gray-700 mb-1">Tên người dùng (UserName) <span class="text-red-500">*</span></label>
                        <input type="text" name="UserName" id="UserName" value="{{ old('UserName', $user->UserName) }}" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors bg-gray-50 focus:bg-white text-gray-900">
                        <p class="text-xs text-gray-500 mt-1">Tên này sẽ hiển thị trên Bảng xếp hạng.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="SDT" class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" name="SDT" id="SDT" value="{{ old('SDT', $user->SDT) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label for="NgaySinh" class="block text-sm font-semibold text-gray-700 mb-1">Ngày sinh</label>
                            <input type="date" name="NgaySinh" id="NgaySinh" value="{{ old('NgaySinh', $user->NgaySinh ? \Carbon\Carbon::parse($user->NgaySinh)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giới tính</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="GioiTinh" value="Nam" class="w-4 h-4 text-primary focus:ring-primary" {{ old('GioiTinh', $user->GioiTinh) == 'Nam' ? 'checked' : '' }}>
                                <span class="text-gray-700">Nam</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="GioiTinh" value="Nữ" class="w-4 h-4 text-primary focus:ring-primary" {{ old('GioiTinh', $user->GioiTinh) == 'Nữ' ? 'checked' : '' }}>
                                <span class="text-gray-700">Nữ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="GioiTinh" value="Khác" class="w-4 h-4 text-primary focus:ring-primary" {{ (!in_array(old('GioiTinh', $user->GioiTinh), ['Nam', 'Nữ']) && $user->GioiTinh != null) ? 'checked' : '' }}>
                                <span class="text-gray-700">Khác</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 mt-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-4"><i class="fas fa-sliders-h text-primary mr-1"></i> Tùy chỉnh Giọng đọc & Tốc độ (Cá nhân hóa)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="preferred_accent" class="block text-sm font-semibold text-gray-700 mb-1">Giọng đọc ưu tiên (Accent)</label>
                                <select name="preferred_accent" id="preferred_accent"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors bg-white text-gray-900">
                                    <option value="en-US" {{ old('preferred_accent', $user->preferred_accent) == 'en-US' ? 'selected' : '' }}>Giọng Mỹ (en-US)</option>
                                    <option value="en-GB" {{ old('preferred_accent', $user->preferred_accent) == 'en-GB' ? 'selected' : '' }}>Giọng Anh (en-GB)</option>
                                    <option value="en-AU" {{ old('preferred_accent', $user->preferred_accent) == 'en-AU' ? 'selected' : '' }}>Giọng Úc (en-AU)</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Áp dụng trực tiếp khi luyện nghe trong AI Voice Lab.</p>
                            </div>

                            <div>
                                <label for="preferred_speed" class="block text-sm font-semibold text-gray-700 mb-1">Tốc độ phát mặc định</label>
                                <select name="preferred_speed" id="preferred_speed"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors bg-white text-gray-900">
                                    <option value="0.75" {{ old('preferred_speed', $user->preferred_speed) == 0.75 ? 'selected' : '' }}>Chậm (0.75x)</option>
                                    <option value="1.0" {{ old('preferred_speed', $user->preferred_speed) == 1.0 ? 'selected' : '' }}>Bình thường (1.0x)</option>
                                    <option value="1.25" {{ old('preferred_speed', $user->preferred_speed) == 1.25 ? 'selected' : '' }}>Nhanh (1.25x)</option>
                                    <option value="1.5" {{ old('preferred_speed', $user->preferred_speed) == 1.5 ? 'selected' : '' }}>Rất nhanh (1.5x)</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Tốc độ mặc định của trình phát âm thanh bài học và game.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 mt-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-4">Đổi mật khẩu (Tùy chọn)</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                                <input type="password" name="password" id="password" placeholder="Bỏ trống nếu không muốn đổi"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Nhập lại mật khẩu mới</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 flex justify-end gap-3 mt-6 border-t border-gray-100">
                        <a href="{{ route('user.dashboard') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">Hủy</a>
                        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-bold shadow-md shadow-primary/20 flex items-center gap-2">
                            <i class="fas fa-save"></i> Lưu Thay Đổi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
            wrapper.className = "rounded-full overflow-hidden bg-gray-50 flex items-center justify-center z-0 relative transition-all duration-300 w-[72%] h-[72%]";
        } else {
            frameImg.src = "";
            frameImg.classList.add('hidden');
            wrapper.className = "rounded-full overflow-hidden bg-gray-50 flex items-center justify-center z-0 relative transition-all duration-300 w-full h-full border-4 border-gray-100 shadow-md";
        }
        
        document.querySelectorAll('.frame-option').forEach(el => {
            if (el.getAttribute('data-frame') === filename) {
                el.classList.add('border-primary', 'shadow-sm', 'ring-2', 'ring-primary/20');
                el.classList.remove('border-gray-200');
            } else {
                el.classList.remove('border-primary', 'shadow-sm', 'ring-2', 'ring-primary/20');
                el.classList.add('border-gray-200');
            }
        });
    }
</script>
@endsection
