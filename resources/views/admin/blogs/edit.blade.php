@extends('admin.layouts.app') 

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chỉnh Sửa Bài Viết</h2>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <!-- Chú ý URL trỏ đến update và truyền ID -->
            <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Bắt buộc phải có khi update trong Laravel -->

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                            <textarea name="content" id="editor" class="form-control @error('content') is-invalid @enderror">{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ảnh đại diện mới (Bỏ trống nếu giữ nguyên)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="imageInput">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Hiển thị ảnh cũ của bài viết -->
                            <div class="mt-2 text-center">
                                <img id="imagePreview" src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/no-image.png') }}" class="img-thumbnail" style="width: 100%; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $blog->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('status', $blog->status) == '0' ? 'selected' : '' }}>Ẩn </option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg text-dark fw-bold">
                                <i class="bi bi-save"></i> Cập Nhật
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Tích hợp CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });

    // Preview ảnh
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
    .ck-editor__editable_inline {
        min-height: 300px;
    }
</style>
@endsection