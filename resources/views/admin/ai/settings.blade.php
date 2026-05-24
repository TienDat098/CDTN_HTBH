@extends('admin.layouts.app')

@section('title', 'Huấn luyện Trợ lý AI')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-success"><i class="bi bi-robot me-2"></i>Đào tạo kiến thức cho Chatbot AI</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ai.update') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nội dung huấn luyện (System Prompt)</label>
                        <p class="text-muted small">Hãy mô tả vai trò, kiến thức về sản phẩm và chính sách của WebTapHoa để AI học theo.</p>
                        <textarea name="system_prompt" class="form-control" rows="12" style="font-size: 15px; line-height: 1.6;">{{ $setting->system_prompt }}</textarea>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="aiStatus" {{ $setting->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="aiStatus">Kích hoạt trợ lý AI ngoài trang chủ</label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Lưu & Cập nhật kiến thức AI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="fw-bold"><i class="bi bi-lightbulb me-2 text-warning"></i>Cách viết Prompt hiệu quả:</h6>
                <hr>
                <div class="small">
                    <p><strong>1. Định nghĩa vai trò:</strong><br> "Bạn là nhân viên tư vấn của WebTapHoa, chuyên bán thực phẩm và đồ gia dụng."</p>
                    <p><strong>2. Cung cấp dữ liệu:</strong><br> "Cửa hàng mở cửa từ 7h - 21h. Phí ship nội thành là 15k. Đơn trên 200k freeship."</p>
                    <p><strong>3. Quy định thái độ:</strong><br> "Trả lời lễ phép, dùng từ 'Dạ, vâng'. Luôn cảm ơn khách khi kết thúc."</p>
                    <p><strong>4. Giới hạn trả lời:</strong><br> "Không trả lời các vấn đề chính trị, tôn giáo. Chỉ tập trung vào sản phẩm của shop."</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection