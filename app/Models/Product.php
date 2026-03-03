<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
   protected $fillable = ['category_id', 'brand_id', 'barcode', 'name', 'slug', 'import_price', 'sell_price', 'unit', 'description', 'status'];
    
   // Ép kiểu dữ liệu
    protected $casts = [
        'status' => 'boolean',
        'import_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
    ];

    //Sản phẩm thuộc một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    //Sản phẩm thuộc một thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
   // 1 Sản phẩm có 1 bản ghi Tồn kho (Quan hệ 1-1)
    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }

    // 1 Sản phẩm có nhiều Hình ảnh (Quan hệ 1-N)
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    protected $appends = ['thumbnail'];
public function getThumbnailAttribute()
{
    // Kiểm tra nếu image_url là link mạng (http) hay file local
    $image = $this->images->where('is_primary', true)->first() ?? $this->images->first();
    $path = $image?->image_url ?? 'images/no-image.png';
    
    // Nếu là link (picsum) thì giữ nguyên, nếu là file local thì dùng asset()
    return str_starts_with($path, 'http') ? $path : asset($path);
}
}
