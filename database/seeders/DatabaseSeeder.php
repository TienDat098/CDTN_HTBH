<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductStock; 
use App\Models\ProductImage; 

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin dz',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);
        //  Tạo thêm 10 tài khoản Khách hàng
        User::factory(10)->create(['role' => 'customer']);

        //Tạo ra đúng 8 Danh mục và 8 Thương hiệu (dựa theo mảng đã cấu hình ở Factory)
        Category::factory(8)->create();
        Brand::factory(8)->create();

        //  Tạo ra 50 Sản phẩm Tạp hóa. 
        // Nhờ ORM và Factory, nó sẽ tự động nối đúng category_id và brand_id
        Product::factory(50)
            ->has(ProductStock::factory()->count(1), 'stock')   
            ->has(ProductImage::factory()->count(2), 'images')  // Mỗi SP có 2 ảnh
            ->create();
    }
}
