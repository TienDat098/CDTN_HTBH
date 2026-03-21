<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'product_id', 
        'name', 
        'price', 
        'stock_quantity', 
        'barcode', 
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }
}
