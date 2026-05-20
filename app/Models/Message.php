<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'session_id', // ID dành cho khách vãng lai
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    // 1. THÊM VÀO MẢNG APPENDS
    // Giúp thuộc tính "sender_name" luôn xuất hiện khi chuyển dữ liệu sang JSON (gửi lên Pusher)
    protected $appends = ['sender_name'];

    /**
     * 2. ĐỊNH NGHĨA ATTRIBUTE ẢO (ACCESSOR)
     * Tên hàm phải viết theo chuẩn: get + TênThuộcTính + Attribute
     */
    public function getSenderNameAttribute()
    {
        // Nếu có sender_id (người dùng đã đăng nhập) -> trả về tên của họ
        if ($this->sender) {
            return $this->sender->name;
        }

        // Nếu không có sender (khách vãng lai) -> trả về nhãn kèm 4 số cuối của session_id để phân biệt
        return 'Khách vãng lai (' . substr($this->session_id, -4) . ')';
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}