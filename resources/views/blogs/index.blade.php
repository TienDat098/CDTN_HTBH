@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Tin tức & Blog</h3>
    <div class="row">
        @foreach($blogs as $blog)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/no-image.png') }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="text-muted small">Đăng bởi: {{ $blog->author->name ?? 'Admin' }} | {{ $blog->created_at->format('d/m/Y') }}</p>
                       <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-outline-primary btn-sm">Đọc tiếp</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $blogs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection