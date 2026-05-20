@extends('admin.layouts.app')

@section('title', 'Quản lý tin nhắn')

@section('content')
<div class="row h-100 bg-white rounded shadow-sm border">
    <div class="col-md-4 border-end p-0">
        <div class="p-3 bg-light border-bottom">
            <h5 class="mb-0 fw-bold">Danh sách Chat</h5>
        </div>
        <div class="list-group list-group-flush" style="height: 70vh; overflow-y: auto;">
            @foreach($conversations as $conv)
                <button class="list-group-item list-group-item-action p-3" onclick="loadChat('{{ $conv->conversation_id }}', '{{ $conv->sender_name }}')">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <h6 class="mb-1 fw-bold text-primary">
                            <i class="bi bi-person-circle me-1"></i> {{ $conv->sender_name }}
                        </h6>
                    </div>
                    <small class="text-muted">Mã phòng: {{ substr($conv->conversation_id, -6) }}</small>
                </button>
            @endforeach
        </div>
    </div>

    <div class="col-md-8 p-0 d-flex flex-column">
        <div class="p-3 bg-primary text-white border-bottom">
            <h5 class="mb-0" id="chat-title">Chọn một cuộc hội thoại để bắt đầu</h5>
        </div>
        
        <div id="admin-chat-body" class="p-4" style="flex-grow: 1; height: 60vh; overflow-y: auto; background-color: #f8f9fa;">
            <div class="text-center text-muted mt-5">
                <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                <p>Chưa có tin nhắn nào được chọn</p>
            </div>
        </div>

        <div class="p-3 bg-white border-top d-flex">
            <input type="hidden" id="current-conversation-id">
            <input type="text" id="admin-chat-input" class="form-control me-2" placeholder="Nhập câu trả lời..." disabled>
            <button class="btn btn-primary" id="btn-send-admin" onclick="sendAdminMessage()" disabled>
                <i class="bi bi-send-fill"></i> Gửi
            </button>
        </div>
    </div>
</div>

<script type="module">
    // Biến lưu ID phòng chat đang mở
    let activeConversation = null;

    // 1. Hàm load lịch sử khi bấm vào 1 khách hàng bên trái
    window.loadChat = function(conversation_id, name) {
        activeConversation = conversation_id;
        document.getElementById('current-conversation-id').value = conversation_id;
        document.getElementById('chat-title').innerText = "Đang hỗ trợ: " + name;
        
        document.getElementById('admin-chat-input').disabled = false;
        document.getElementById('btn-send-admin').disabled = false;
        
        const chatBody = document.getElementById('admin-chat-body');
        chatBody.innerHTML = '<div class="text-center text-muted">Bắt đầu trả lời tin nhắn của ' + name + '</div>';

        // Gọi API lấy lịch sử
        fetch('/chat/messages/' + conversation_id)
            .then(res => res.json())
            .then(messages => {
                chatBody.innerHTML = ''; 
                messages.forEach(msg => {
                    // Nếu admin gửi (sender_id == auth id) thì bong bóng nằm bên phải
                    const isAdmin = msg.sender_id == {{ auth()->id() }};
                    appendAdminMsg(msg.message, isAdmin ? 'right' : 'left', isAdmin ? 'Bạn' : msg.sender_name);
                });
            });
    };

    // 2. Hàm gửi tin nhắn từ Admin
    window.sendAdminMessage = function() {
    const input = document.getElementById('admin-chat-input');
    const text = input.value.trim();
    const conversationId = document.getElementById('current-conversation-id').value;

    if (text === '' || !conversationId) return;

    input.value = '';

    fetch('/chat/messages', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Socket-ID': window.Echo ? window.Echo.socketId() : ''
        },
        body: JSON.stringify({
            message: text,
            conversation_id: conversationId
        })
    })
    .then(async response => {
        const data = await response.json();

        if (!response.ok) {
            console.error('Lỗi gửi tin nhắn admin:', data);
            alert('Gửi thất bại. Mở F12 > Console để xem lỗi.');
            return;
        }

        appendAdminMsg(data.message.message, 'right', 'Bạn');
    })
    .catch(error => {
        console.error('Lỗi fetch:', error);
        alert('Không gửi được tin nhắn admin.');
    });
};

    // 3. Hàm in HTML bong bóng chat trong trang Admin
    window.appendAdminMsg = function(text, align, name) {
        const chatBody = document.getElementById('admin-chat-body');
        const row = document.createElement('div');
        row.className = `d-flex flex-column mb-3 align-items-${align === 'right' ? 'end' : 'start'}`;
        
        const bgColor = align === 'right' ? 'bg-primary text-white' : 'bg-white border text-dark';
        
        row.innerHTML = `
            <small class="text-muted mb-1" style="font-size: 11px;">${name}</small>
            <div class="p-2 rounded shadow-sm ${bgColor}" style="max-width: 75%;">${text}</div>
        `;
        chatBody.appendChild(row);
        chatBody.scrollTop = chatBody.scrollHeight; // Cuộn xuống dưới cùng
    };

    // 4. Lắng nghe Pusher (Real-time) ngay trong màn hình Chat
    window.Echo.channel('chat-channel')
        .listen('MessageSent', (e) => {
            // Nếu admin đang mở đúng phòng chat của người vừa nhắn tới
            if (activeConversation === e.message.conversation_id) {
                // Kiểm tra xem tin nhắn đó KHÔNG PHẢI do chính Admin vừa gõ
                if (e.message.sender_id !== {{ auth()->id() }}) {
                    appendAdminMsg(e.message.message, 'left', e.message.sender_name);
                }
            }
        });
</script>
@endsection