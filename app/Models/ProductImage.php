<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProductImage extends Model
{
    use HasFactory; 

    // Khai báo các cột được phép thêm dữ liệu
    protected $fillable = ['product_id', 'image_url', 'is_primary'];

    // Ảnh này THUỘC VỀ 1 Sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
