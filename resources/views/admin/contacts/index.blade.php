@extends('admin.layouts.app') 

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="container-fluid mt-4 mb-5">
    <div class="mb-4">
        <h3 class="fw-bold"><i class="bi bi-envelope-paper me-2"></i> Quản lý Liên Hệ</h3>
        <p class="text-muted">Tiếp nhận và phản hồi ý kiến khách hàng</p>
    </div>

    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="row gx-2 gy-2 align-items-center">
                
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 ps-0" placeholder="Tìm tên, email hoặc SĐT..." value="{{ request('keyword') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Chưa xem</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã xem</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 fw-bold"><i class="bi bi-funnel-fill me-1"></i> Lọc</button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary" title="Làm mới"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
            <span class="text-primary fw-medium">Danh sách tin nhắn</span>
            <span class="badge border text-dark bg-light px-2 py-1">Tổng: {{ $contacts->total() }}</span>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="25%" class="py-3 px-4">KHÁCH HÀNG</th>
                        <th width="35%" class="py-3">NỘI DUNG TIN NHẮN</th>
                        <th width="15%" class="py-3">THỜI GIAN</th>
                        <th width="12%" class="text-center py-3">TRẠNG THÁI</th>
                        <th width="13%" class="text-center py-3">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="px-4 align-middle">
                                <div class="fw-bold text-dark mb-1">{{ $contact->name }}</div>
                                <div class="text-muted small mb-1"><i class="bi bi-envelope text-primary me-2"></i>{{ $contact->email }}</div>
                                <div class="text-muted small"><i class="bi bi-telephone text-success me-2"></i>{{ $contact->phone }}</div>
                            </td>

                            <td class="align-middle">
                                <div class="text-truncate-2 text-muted mb-1">{{ $contact->message }}</div>
                                <a href="#" class="text-decoration-none fw-bold small text-primary" data-bs-toggle="modal" data-bs-target="#viewMessageModal{{ $contact->id }}">
                                    <i class="bi bi-eye-fill me-1"></i> Xem toàn bộ
                                </a>
                            </td>

                            <td class="align-middle">
                                <div class="text-muted small mb-1"><i class="bi bi-calendar3 me-2"></i>{{ $contact->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted small"><i class="bi bi-clock me-2"></i>{{ $contact->created_at->format('H:i') }}</div>
                            </td>

                            <td class="text-center align-middle">
                               @if($contact->status == 2)
                                        <span class="badge bg-success rounded-pill px-3 py-1 fw-bold">Đã trả lời</span>
                                    @elseif($contact->status == 1)
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">Đã xem</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">Chưa xem</span>
                                @endif
                            </td>

                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('admin.contacts.update_status', $contact->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-primary btn-action" title="Đánh dấu {{ $contact->status == 1 ? 'Chưa xem' : 'Đã xem' }}">
                                            <i class="bi bi-reply-fill"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-action" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="viewMessageModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-white border-bottom-0 pb-0 mt-2">
                                        <h5 class="modal-title fw-bold text-primary">
                                            <i class="bi bi-chat-dots-fill me-2"></i> Chi tiết nội dung
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-3">
                                        <div class="mb-3 border-bottom pb-3">
                                            <div class="text-dark fw-bold small mb-1">Người gửi:</div>
                                            <div class="fw-bold text-dark">{{ $contact->name }}</div>
                                            <a href="javascript:void(0)" class="text-decoration-none text-primary">{{ $contact->email }}</a>
                                        </div>
                                        <div>
                                            <div class="text-dark fw-bold small mb-2">Nội dung tin nhắn:</div>
                                            <div class="p-3 bg-light rounded border-0 text-dark" style="white-space: pre-wrap; font-size: 15px;">{{ $contact->message }}</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between border-0 bg-white pt-0 mb-2">
                                        <button type="button" class="btn btn-secondary px-4 text-white" data-bs-dismiss="modal">Đóng</button>
                                        
                                        <button type="button" class="btn btn-primary px-4 fw-medium" data-bs-toggle="modal" data-bs-target="#replyModal{{ $contact->id }}" data-bs-dismiss="modal">
                                            <i class="bi bi-reply-fill me-1"></i> Trả lời ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="replyModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                               <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                                @csrf
                                
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-primary text-white border-0">
                                        <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                                            <i class="bi bi-send-fill me-2"></i> Phản hồi khách hàng (Email)
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <div class="modal-body p-4 bg-light">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Người nhận:</label>
                                                <input type="text" class="form-control bg-white" value="{{ $contact->name }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small fw-bold">Email nhận:</label>
                                                <input type="email" name="email" class="form-control bg-white" value="{{ $contact->email }}" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Tiêu đề Email <span class="text-danger">*</span></label>
                                            <input type="text" name="subject" class="form-control" value="Phản hồi từ WebTapHoa về liên hệ của bạn" required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fw-bold small">Nội dung phản hồi (HTML) <span class="text-danger">*</span></label>
                                            <textarea name="reply_content" class="form-control" rows="8" required>Chào {{ $contact->name }},

                    Cảm ơn quý khách đã liên hệ với WebTapHoa. Về vấn đề quý khách thắc mắc, chúng tôi xin phản hồi như sau:



                    Trân trọng,</textarea>
                                            <div class="form-text mt-1 text-muted small">Hỗ trợ mã HTML cơ bản như &lt;b&gt; in đậm, &lt;br&gt; xuống dòng.</div>
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer border-0 bg-light pt-0 pb-3">
                                        <button type="button" class="btn btn-secondary px-4 text-white fw-medium" data-bs-dismiss="modal">Hủy bỏ</button>
                                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                                            <i class="bi bi-send me-1"></i> GỬI PHẢN HỒI
                                        </button>
                                    </div>
                                </div>
                            </form> 


                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-envelope-x fs-1 d-block mb-2"></i>
                                Chưa có tin nhắn liên hệ nào!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white py-3">
            <p class="text-muted small mb-0 mt-2 text-center">Hiển thị kết quả liên hệ mới nhất.</p>
            <div class="d-flex justify-content-center mt-3">
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS cho các nút vuông vức */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 4px;
    }

    /* Giới hạn nội dung 2 dòng để bảng không bị phình to */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection