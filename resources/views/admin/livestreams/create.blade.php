@extends('admin.layouts.app')

@section('title', 'Thêm Livestream')

@section('content')
<h4 class="fw-bold mb-4">
    <i class="bi bi-plus-circle me-2 text-primary"></i>Thêm Livestream
</h4>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.livestreams.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề livestream</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Link YouTube livestream</label>
                <input type="text"
                       name="youtube_url"
                       class="form-control"
                       value="{{ old('youtube_url') }}"
                       placeholder="VD: https://www.youtube.com/watch?v=xxxxxxxxxxx"
                       required>
                <small class="text-muted">
                    Hỗ trợ link dạng watch, youtu.be, embed hoặc live.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="description"
                          class="form-control"
                          rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Sản phẩm liên quan</label>
                <select name="product_ids[]"
                        class="form-select"
                        multiple
                        size="8">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} - {{ number_format($product->sell_price) }}đ
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">
                    Giữ Ctrl để chọn nhiều sản phẩm.
                </small>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1">
                <label class="form-check-label" for="is_active">
                    Bật livestream này
                </label>
            </div>

            <button class="btn btn-primary">
                Lưu livestream
            </button>

            <a href="{{ route('admin.livestreams.index') }}" class="btn btn-secondary">
                Quay lại
            </a>
        </form>
    </div>
</div>
@endsection