@extends('admin.layouts.app')

@section('title', 'Lịch sử tư vấn AI')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-chat-left-text me-2"></i>Lịch sử tư vấn Chatbot AI</h5>
        <span class="badge bg-info text-dark">Tổng số: {{ $logs->total() }} cuộc hội thoại</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%">Thời gian</th>
                        <th style="width: 15%">Người dùng</th>
                        <th style="width: 30%">Câu hỏi của khách</th>
                        <th style="width: 35%">AI phản hồi</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td class="small text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($log->user)
                                <span class="fw-bold text-primary"><i class="bi bi-person-check me-1"></i>{{ $log->user->name }}</span>
                            @else
                                <span class="text-muted"><i class="bi bi-person-exclamation me-1"></i>Khách vãng lai</span>
                            @endif
                            <br><small class="text-muted" style="font-size: 10px;">ID: {{ substr($log->session_id, -6) }}</small>
                        </td>
                        <td><div class="p-2 bg-light rounded shadow-sm small">{{ $log->user_message }}</div></td>
                        <td><div class="p-2 border rounded small text-success">{{ $log->bot_response }}</div></td>
                       
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection