<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'WebTapHoa') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS bong bóng chat */
        .chat-bubble { position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background-color: #0d6efd; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 28px; cursor: pointer; z-index: 1000; transition: transform 0.3s ease; }
        .chat-bubble:hover { transform: scale(1.1); }
        .chat-unread-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 24px;
        height: 24px;
        padding: 0 7px;
        border-radius: 999px;
        background-color: #dc3545;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        line-height: 1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
        .chat-window { position: fixed; bottom: 100px; right: 30px; width: 350px; max-width: 90vw; background-color: white; z-index: 1000; display: flex; flex-direction: column; transition: all 0.3s ease; }
        .chat-body { height: 350px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
        .chat-body::-webkit-scrollbar { width: 6px; }
        .chat-body::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        .message-row { display: flex; width: 100%; }
        .message-row.sender { justify-content: flex-end; }
        .message-row.receiver { justify-content: flex-start; }
        .message-bubble { max-width: 80%; padding: 10px 14px; border-radius: 15px; font-size: 0.9rem; line-height: 1.4; }
        .sender .message-bubble { background-color: #0d6efd; color: white; border-bottom-right-radius: 2px; }
        .receiver .message-bubble { background-color: #ffffff; color: #333; border-bottom-left-radius: 2px; }
        #chat-input:focus { box-shadow: none; }
    </style>
</head>

<body>

@include('layouts.navigation')

<div class="container mt-4">
    @yield('content')
</div>

@include('layouts.footer')

<div id="chat-bubble" class="chat-bubble shadow" onclick="toggleChat()">
    <i class="bi bi-chat-dots-fill"></i>
    <span id="unread-badge" class="chat-unread-badge d-none">0</span>
</div>

<div id="chat-window" class="chat-window shadow rounded d-none">
    <div class="chat-header bg-primary text-white p-3 d-flex justify-content-between align-items-center rounded-top">
        <h6 class="mb-0 fw-bold"><i class="bi bi-headset me-2"></i>Hỗ trợ trực tuyến</h6>
        <button type="button" class="btn-close btn-close-white" onclick="toggleChat()"></button>
        
    </div>
    
    <div id="chat-body" class="chat-body p-3 bg-light">
        <div class="message-row receiver">
            <div class="message-bubble bg-white border">Xin chào! WebTapHoa có thể giúp gì cho bạn?</div>
        </div>
    </div>
    
    <div class="chat-footer p-2 bg-white border-top d-flex rounded-bottom">
        <input type="text" id="chat-input" class="form-control me-2 border-0" placeholder="Nhập tin nhắn..." onkeypress="handleEnter(event)">
        
        <button class="btn btn-primary rounded-circle me-1" onclick="sendMessage()" title="Gửi cho Admin">
            <i class="bi bi-send-fill"></i>
        </button>
        
        <button class="btn btn-success rounded-circle" onclick="askAI()" title="Hỏi Trợ lý AI">
            <i class="bi bi-robot"></i>
        </button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script type="module">
    // Lấy ID định danh ngay từ lúc load trang (Laravel truyền xuống)
    let mySessionId = "{{ session('guest_chat_id', '') }}";
    const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
    
    window.toggleChat = function() {
    const chatWindow = document.getElementById('chat-window');
    const badge = document.getElementById('unread-badge');

    chatWindow.classList.toggle('d-none');
    document.getElementById('chat-bubble').classList.remove('bg-danger');

    if (!chatWindow.classList.contains('d-none')) {
        if (badge) {
            badge.innerText = 0;
            badge.classList.add('d-none');
        }

        scrollToBottom();
        document.getElementById('chat-input').focus();
    }
};

    window.scrollToBottom = function() {
        const chatBody = document.getElementById('chat-body');
        chatBody.scrollTop = chatBody.scrollHeight;
    };

    window.handleEnter = function(e) {
        if (e.key === 'Enter') sendMessage();
    };

    // HÀM GỬI TIN NHẮN (ĐÃ FIX LỖI DỘI TIN)
    window.sendMessage = function() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (text === '') return;

        input.value = '';
        appendMessage(text, 'sender'); // Hiện bong bóng xanh

        fetch('/chat/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Socket-ID': window.Echo.socketId()
            },
            body: JSON.stringify({ 
                message: text
                // KHÔNG TRUYỀN conversation_id ở đây để Controller tự hiểu đây là Khách
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message && data.message.session_id && !mySessionId) {
                mySessionId = data.message.session_id;
            }
        });
    };

    // ==========================================
    // HÀM GỌI TRỢ LÝ ẢO AI (GEMINI)
    // ==========================================
    window.askAI = function() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (text === '') {
            alert('Vui lòng nhập câu hỏi để Trợ lý AI tư vấn nhé!');
            input.focus();
            return;
        }

        // 1. Xóa ô nhập và in câu hỏi của khách lên màn hình
        input.value = '';
        appendMessage(text, 'sender');

        // 2. Tạo hiệu ứng "AI đang gõ chữ..." để khách chờ
        const chatBody = document.getElementById('chat-body');
        const loadingId = 'loading-' + Date.now();
        const loadingHtml = `
            <div id="${loadingId}" class="message-row receiver mb-2 d-flex flex-column">
                <div class="small text-muted mb-1" style="font-size: 11px; margin-left: 5px;">Trợ lý AI 🤖</div>
                <div class="message-bubble shadow-sm bg-light border text-muted">
                    <em>Đang suy nghĩ... <span class="spinner-grow spinner-grow-sm text-success" role="status"></span></em>
                </div>
            </div>`;
        chatBody.insertAdjacentHTML('beforeend', loadingHtml);
        scrollToBottom();

        // 3. Gửi câu hỏi lên ChatbotController
        fetch('{{ route('chatbot.ask') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: text })
        })
        .then(response => response.json())
        .then(data => {
            // Xóa hiệu ứng "Đang suy nghĩ"
            document.getElementById(loadingId).remove();

            if (data.success) {
                // In câu trả lời của AI ra
                appendMessage(data.bot_response, 'receiver', 'Trợ lý AI 🤖');
            } else {
                appendMessage("Xin lỗi, AI đang bảo trì: " + data.message, 'receiver', 'Hệ thống');
            }
        })
        .catch(error => {
            document.getElementById(loadingId).remove();
            appendMessage("Lỗi kết nối mạng khi gọi AI.", 'receiver', 'Hệ thống');
            console.error('Lỗi AI:', error);
        });
    };

    window.appendMessage = function(text, type, name = null) {
        const chatBody = document.getElementById('chat-body');
        const row = document.createElement('div');
        row.className = `message-row ${type} mb-2 d-flex flex-column`; 
        // 1. Chuyển đổi dấu ** thành thẻ in đậm HTML, và \n thành thẻ <br> xuống dòng
        let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formattedText = formattedText.replace(/\n/g, '<br>');
        let nameHtml = name ? `<div class="small text-muted mb-1" style="font-size: 11px; margin-${type === 'sender' ? 'right' : 'left'}: 5px;">${name}</div>` : '';
        // 2. Thay biến ${text} cũ thành ${formattedText}
        row.innerHTML = `${nameHtml}<div class="message-bubble shadow-sm">${formattedText}</div>`;
        chatBody.appendChild(row);
        scrollToBottom();
    };

    // LẮNG NGHE PUSHER
    window.Echo.channel('chat-channel')
    .listen('.message.sent', (e) => {
        let myRoom = currentUserId ? currentUserId : mySessionId;

        if (myRoom && e.message.conversation_id == myRoom) {

            let isMyMessage = false;

            if (!currentUserId && e.message.sender_id === null && e.message.session_id === mySessionId) {
                isMyMessage = true;
            }

            if (currentUserId && e.message.sender_id === currentUserId) {
                isMyMessage = true;
            }

            if (!isMyMessage) {
                appendMessage(e.message.message, 'receiver', e.message.sender_name);

                const chatWindow = document.getElementById('chat-window');
                if (chatWindow.classList.contains('d-none')) {
                    const bubble = document.getElementById('chat-bubble');
                    bubble.classList.add('bg-danger');
                    bubble.style.animation = "shake 0.5s";
                    setTimeout(() => { bubble.style.animation = ""; }, 500);
                }
            }
        }
    });
</script>


</body>
</html>