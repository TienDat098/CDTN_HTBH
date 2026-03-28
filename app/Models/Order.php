<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'ghn_tracking_code', 'user_id', 'staff_id', 
        'total_price', 'discount_amount', 'final_total', 'order_type', 
        'payment_status', 'shipping_address', 'note', 'status'
    ];
   public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    //1 Đơn hàng có 1 Giao dịch thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
}
