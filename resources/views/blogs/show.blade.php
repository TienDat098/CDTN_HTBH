@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}" class="text-decoration-none">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h1 class="fw-bold mb-3">{{ $blog->title }}</h1>
            
            <div class="d-flex align-items-center text-muted mb-4 pb-3 border-bottom">
                <i class="bi bi-person-circle me-2 fs-5"></i>
                <span class="me-4 fw-medium">{{ $blog->author->name ?? 'Admin' }}</span>
                <i class="bi bi-calendar3 me-2"></i>
                <span>{{ $blog->created_at->format('d/m/Y H:i') }}</span>
            </div>

            @if($blog->image)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $blog->title }}" style="max-height: 500px; width: 100%; object-fit: cover;">
                </div>
            @endif

            <div class="blog-content" style="font-size: 1.1rem; line-height: 1.8;">
                {{-- Lưu ý: Dùng {!! !!} để render HTML từ CKEditor thay vì {{ }} --}}
                {!! $blog->content !!}
            </div>
            
            <div class="mt-5 pt-4 border-top">
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách bài viết
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS làm đẹp cho nội dung bài viết sinh ra từ CKEditor */
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 15px 0;
    }
    .blog-content h2, .blog-content h3 {
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: bold;
    }
    .blog-content ul, .blog-content ol {
        margin-bottom: 20px;
    }
</style>
@endsection