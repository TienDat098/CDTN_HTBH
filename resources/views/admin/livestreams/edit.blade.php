@extends('admin.layouts.app')

@section('title', 'Sửa Livestream')

@section('content')
<h4 class="fw-bold mb-4">
    <i class="bi bi-pencil-square me-2 text-warning"></i>Sửa Livestream
</h4>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.livestreams.update', $livestream) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề livestream</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ old('title', $livestream->title) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Link YouTube livestream</label>
                <input type="text"
                       name="youtube_url"
                       class="form-control"
                       value="{{ old('youtube_url', $livestream->youtube_url) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea name="description"
                          class="form-control"
                          rows="4">{{ old('description', $livestream->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Sản phẩm liên quan</label>

                @php
                    $selectedProducts = old(
                        'product_ids',
                        $livestream->products->pluck('id')->toArray()
                    );
                @endphp

                <select name="product_ids[]"
                        class="form-select"
                        multiple
                        size="8">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                            {{ $product->name }} - {{ number_format($product->sell_price) }}đ
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       id="is_active"
                       value="1"
                       {{ $livestream->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Bật livestream này
                </label>
            </div>

            <button class="btn btn-primary">
                Cập nhật livestream
            </button>

            <a href="{{ route('admin.livestreams.index') }}" class="btn btn-secondary">
                Quay lại
            </a>
        </form>
    </div>
</div>
@endsection