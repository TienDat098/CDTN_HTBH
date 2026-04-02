@extends('admin.layouts.app') 

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thêm Bài Viết Mới</h2>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Cột trái: Tiêu đề và Nội dung -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Nhập tiêu đề..." required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                            <!-- ID 'editor' dùng để gọi CKEditor -->
                            <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Cột phải: Ảnh đại diện và Trạng thái -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="imageInput">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Khung xem trước ảnh -->
                            <div class="mt-2 text-center">
                                <img id="imagePreview" src="{{ asset('images/no-image.png') }}" class="img-thumbnail" style="width: 100%; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị (Public)</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Lưu nháp (Draft)</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Lưu Bài Viết
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Tích hợp CKEditor 5 (Classic) -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Khởi tạo CKEditor
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });

    // Code JS để hiển thị ảnh xem trước khi người dùng chọn file
    document.getElementById('imageInput').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function(){
            let output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>

<style>
    /* CSS làm cho khung nhập liệu của CKEditor cao hơn chút cho dễ gõ */
    .ck-editor__editable_inline {
        min-height: 300px;
    }
</style>
@endsection