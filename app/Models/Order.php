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
}
