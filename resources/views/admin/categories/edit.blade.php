@extends('admin.layouts.app')

@section('title', 'Sửa danh mục')

@section('content')

<h3 class="mb-4">Sửa danh mục</h3>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $category->name) }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

        <div class="mb-3">
                <label class="form-label">Ảnh danh mục</label>

                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ asset('images/categories/'.$category->image) }}" 
                            width="120" 
                            style="border-radius:8px;">
                    </div>
                @endif

                <img id="preview" class="mb-2" style="display:none; max-width:120px; border-radius:8px;">

                <input type="file" name="image" class="form-control" onchange="previewImage(event)">

                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
        </div>


    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-control">
            <option value="1" {{ $category->status ? 'selected' : '' }}>
                Hiển thị
            </option>
            <option value="0" {{ !$category->status ? 'selected' : '' }}>
                Ẩn
            </option>
        </select>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>

</form>
<script>
function previewImage(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = 'block';
}
</script>
@endsection