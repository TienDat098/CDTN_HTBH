@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="contact-wrap shadow rounded overflow-hidden mb-5">
        <div class="row g-0">
            <div class="col-lg-4 bg-primary text-white p-5 d-flex flex-column justify-content-center">
                <h3 class="fw-bold mb-3">Thông tin liên hệ</h3>
                <p class="mb-4" style="font-size: 0.95rem; opacity: 0.9;">
                    Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại tin nhắn cho chúng tôi.
                </p>

                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-geo-alt-fill fs-4 me-3"></i>
                    <span>Nha Trang, Khánh Hòa</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-telephone-fill fs-4 me-3"></i>
                    <span>090.123.4567</span>
                </div>
                <div class="d-flex align-items-center mb-5">
                    <i class="bi bi-envelope-fill fs-4 me-3"></i>
                    <span>cskh@webtaphoa.com</span>
                </div>

                <h4 class="fw-bold mb-3">Giờ làm việc</h4>
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-clock-fill me-3"></i>
                    <span>Thứ 2 - Thứ 7: 8:00 - 21:00</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock me-3" style="opacity: 0;"></i> <span>Chủ Nhật: 9:00 - 18:00</span>
                </div>
            </div>

            <div class="col-lg-8 bg-white p-5">
                <h3 class="fw-bold text-primary mb-4">Gửi tin nhắn cho chúng tôi</h3>
                
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nhập họ tên" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Nhập SĐT" value="{{ old('phone') }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Nội dung liên hệ <span class="text-danger">*</span></label>
                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Bạn cần hỗ trợ vấn đề gì?" required>{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-uppercase">
                        Gửi liên hệ
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="map-container shadow-sm rounded overflow-hidden">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3903.2877902405253!2d109.19131607574345!3d12.24702438743845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3170677811cc886f%3A0x5c4bbc0aa81edcb9!2zTmhhIFRyYW5nLCBLaMOhbmggSMOyYQ!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

</div>

<style>
    /* Chỉnh màu xanh của Form giống hệt thiết kế */
    .contact-wrap .bg-primary {
        background-color: #1a73e8 !important; 
    }
    .contact-wrap .text-primary {
        color: #1a73e8 !important;
    }
    .contact-wrap .btn-primary {
        background-color: #1a73e8;
        border-color: #1a73e8;
    }
    .contact-wrap .btn-primary:hover {
        background-color: #155cb0;
    }
    
    /* Input style */
    .contact-wrap .form-control {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.6rem 1rem;
    }
    .contact-wrap .form-control:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 0 0.25rem rgba(26, 115, 232, 0.25);
        background-color: #fff;
    }
</style>
@endsection