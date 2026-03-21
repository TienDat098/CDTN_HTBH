<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
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
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);
        //  Tạo thêm 10 tài khoản Khách hàng
        User::factory(10)->create(['role' => 'customer']);

        // 2. TẠO 8 DANH MỤC THẬT (Theo đúng thiết kế ảnh của bạn)
        $categories = [
            'Hóa mỹ phẩm',
            'Gia vị',
            'Bánh kẹo',
            'Thực phẩm khô',
            'Sữa & Bơ',
            'Đồ uống',
            'Đồ gia dụng',
            'Đông lạnh'
        ];
        
        $catMap = [];
        foreach ($categories as $catName) {
            $cat = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'status' => 1
            ]);
            $catMap[$catName] = $cat->id; 
        }

        // 3. TẠO THƯƠNG HIỆU THẬT
        $brands = [
            'Acecook', 'Masan', 'Suntory PepsiCo', 'Coca-Cola', 
            'Unilever', 'Orion', 'Vinamilk', 'CP', 'Khác'
        ];

        $brandMap = [];
        foreach ($brands as $brandName) {
            $brand = Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
                'status' => 1
            ]);
            $brandMap[$brandName] = $brand->id;
        }

        // 4. TẠO 50 SẢN PHẨM (Đã phân loại lại khớp 100% với 8 danh mục trên)
        $realProducts = [
            // --- HÓA MỸ PHẨM ---
            ['name' => 'Dầu Gội Clear Bạc Hà 630g', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Unilever', 'import' => 135000, 'sell' => 155000, 'unit' => 'Chai', 'barcode' => '8934868014444'],
            ['name' => 'Dầu Gội Sunsilk Mềm Mượt 640g', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Unilever', 'import' => 110000, 'sell' => 125000, 'unit' => 'Chai', 'barcode' => '8934868014555'],
            ['name' => 'Kem Đánh Răng P/S Bảo Vệ 123', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Unilever', 'import' => 30000, 'sell' => 38000, 'unit' => 'Tuýp', 'barcode' => '8934868015555'],
            ['name' => 'Nước Rửa Chén Sunlight Chanh', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Unilever', 'import' => 28000, 'sell' => 35000, 'unit' => 'Chai', 'barcode' => '8934868016666'],
            ['name' => 'Bột Giặt OMO Hệ Matic 4.5Kg', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Unilever', 'import' => 170000, 'sell' => 195000, 'unit' => 'Túi', 'barcode' => '8934868017777'],
            ['name' => 'Nước Xả Vải Downy Huyền Bí', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Khác', 'import' => 85000, 'sell' => 98000, 'unit' => 'Túi', 'barcode' => '8934868018888'],
            ['name' => 'Sữa Tắm Lifebuoy Bảo Vệ', 'cat' => 'Hóa mỹ phẩm', 'brand' => 'Unilever', 'import' => 140000, 'sell' => 165000, 'unit' => 'Chai', 'barcode' => '8934868019999'],

            // --- GIA VỊ ---
            ['name' => 'Nước Mắm Nam Ngư 500ml', 'cat' => 'Gia vị', 'brand' => 'Masan', 'import' => 38000, 'sell' => 45000, 'unit' => 'Chai', 'barcode' => '8935049001111'],
            ['name' => 'Tương Ớt Chinsu 250g', 'cat' => 'Gia vị', 'brand' => 'Masan', 'import' => 12000, 'sell' => 15000, 'unit' => 'Chai', 'barcode' => '8935049002222'],
            ['name' => 'Bột Ngọt Ajinomoto 400g', 'cat' => 'Gia vị', 'brand' => 'Khác', 'import' => 25000, 'sell' => 29000, 'unit' => 'Gói', 'barcode' => '8935049005555'],
            ['name' => 'Hạt Nêm Knorr Thịt Thăn', 'cat' => 'Gia vị', 'brand' => 'Unilever', 'import' => 32000, 'sell' => 38000, 'unit' => 'Gói', 'barcode' => '8935049006666'],
            ['name' => 'Đường Tinh Luyện Biên Hòa 1Kg', 'cat' => 'Gia vị', 'brand' => 'Khác', 'import' => 20000, 'sell' => 24000, 'unit' => 'Gói', 'barcode' => '8935049007777'],
            ['name' => 'Muối Tinh Sấy Cẩm Ngọc 500g', 'cat' => 'Gia vị', 'brand' => 'Khác', 'import' => 4000, 'sell' => 6000, 'unit' => 'Gói', 'barcode' => '8935049008888'],
            ['name' => 'Dầu Ăn Simply 1 Lít', 'cat' => 'Gia vị', 'brand' => 'Khác', 'import' => 48000, 'sell' => 55000, 'unit' => 'Chai', 'barcode' => '8935049004444'],

            // --- BÁNH KẸO ---
            ['name' => 'Bánh Chocopie Hộp 12 Cái', 'cat' => 'Bánh kẹo', 'brand' => 'Orion', 'import' => 45000, 'sell' => 55000, 'unit' => 'Hộp', 'barcode' => '8934822101111'],
            ['name' => 'Snack Khoai Tây O\'Star', 'cat' => 'Bánh kẹo', 'brand' => 'Orion', 'import' => 8000, 'sell' => 10000, 'unit' => 'Gói', 'barcode' => '8934822103333'],
            ['name' => 'Bánh Custas Hộp 6 Cái', 'cat' => 'Bánh kẹo', 'brand' => 'Orion', 'import' => 30000, 'sell' => 38000, 'unit' => 'Hộp', 'barcode' => '8934822102222'],
            ['name' => 'Kẹo Dẻo Chupa Chups 90g', 'cat' => 'Bánh kẹo', 'brand' => 'Khác', 'import' => 12000, 'sell' => 15000, 'unit' => 'Gói', 'barcode' => '8935049111222'],
            ['name' => 'Bánh Quy Cosy Marie 300g', 'cat' => 'Bánh kẹo', 'brand' => 'Khác', 'import' => 28000, 'sell' => 35000, 'unit' => 'Hộp', 'barcode' => '8935049111444'],
            ['name' => 'Bánh Gạo One One', 'cat' => 'Bánh kẹo', 'brand' => 'Khác', 'import' => 18000, 'sell' => 22000, 'unit' => 'Gói', 'barcode' => '8935049111555'],

            // --- THỰC PHẨM KHÔ ---
            ['name' => 'Mì Hảo Hảo Tôm Chua Cay', 'cat' => 'Thực phẩm khô', 'brand' => 'Acecook', 'import' => 3500, 'sell' => 4000, 'unit' => 'Gói', 'barcode' => '8934563138164'],
            ['name' => 'Mì Omachi Xốt Vang', 'cat' => 'Thực phẩm khô', 'brand' => 'Masan', 'import' => 6500, 'sell' => 8000, 'unit' => 'Gói', 'barcode' => '8935049003333'],
            ['name' => 'Phở Bò Vifon', 'cat' => 'Thực phẩm khô', 'brand' => 'Khác', 'import' => 5500, 'sell' => 7000, 'unit' => 'Gói', 'barcode' => '8935049009999'],
            ['name' => 'Miến Dong Thái Bình 500g', 'cat' => 'Thực phẩm khô', 'brand' => 'Khác', 'import' => 25000, 'sell' => 32000, 'unit' => 'Gói', 'barcode' => '8935049010000'],
            ['name' => 'Bún Tươi Sấy Khô 400g', 'cat' => 'Thực phẩm khô', 'brand' => 'Khác', 'import' => 18000, 'sell' => 23000, 'unit' => 'Gói', 'barcode' => '8935049010111'],

            // --- SỮA & BƠ ---
            ['name' => 'Lốc 4 Sữa Tươi Vinamilk', 'cat' => 'Sữa & Bơ', 'brand' => 'Vinamilk', 'import' => 28000, 'sell' => 32000, 'unit' => 'Lốc', 'barcode' => '8934666012222'],
            ['name' => 'Sữa Chua Vinamilk Lốc 4', 'cat' => 'Sữa & Bơ', 'brand' => 'Vinamilk', 'import' => 22000, 'sell' => 26000, 'unit' => 'Lốc', 'barcode' => '8934666012888'],
            ['name' => 'Sữa Đặc Ông Thọ Đỏ', 'cat' => 'Sữa & Bơ', 'brand' => 'Vinamilk', 'import' => 19000, 'sell' => 22000, 'unit' => 'Lon', 'barcode' => '8934666012999'],
            ['name' => 'Phô Mai Con Bò Cười', 'cat' => 'Sữa & Bơ', 'brand' => 'Khác', 'import' => 35000, 'sell' => 42000, 'unit' => 'Hộp', 'barcode' => '8934666013000'],
            ['name' => 'Bơ Tường An 200g', 'cat' => 'Sữa & Bơ', 'brand' => 'Khác', 'import' => 20000, 'sell' => 25000, 'unit' => 'Hộp', 'barcode' => '8934666013111'],

            // --- ĐỒ UỐNG ---
            ['name' => 'Nước Ngọt Sting Dâu 330ml', 'cat' => 'Đồ uống', 'brand' => 'Suntory PepsiCo', 'import' => 8500, 'sell' => 10000, 'unit' => 'Chai', 'barcode' => '8934588012111'],
            ['name' => 'Nước Ngọt Coca-Cola 320ml', 'cat' => 'Đồ uống', 'brand' => 'Coca-Cola', 'import' => 8500, 'sell' => 10000, 'unit' => 'Lon', 'barcode' => '8935049500111'],
            ['name' => 'Nước Suối Aquafina 500ml', 'cat' => 'Đồ uống', 'brand' => 'Suntory PepsiCo', 'import' => 4000, 'sell' => 5000, 'unit' => 'Chai', 'barcode' => '8934588012333'],
            ['name' => 'Bia Tiger Nâu 330ml', 'cat' => 'Đồ uống', 'brand' => 'Khác', 'import' => 14000, 'sell' => 16000, 'unit' => 'Lon', 'barcode' => '8934588012444'],
            ['name' => 'Bia Heineken 330ml', 'cat' => 'Đồ uống', 'brand' => 'Khác', 'import' => 17000, 'sell' => 19000, 'unit' => 'Lon', 'barcode' => '8934588012555'],
            ['name' => 'Nước Tăng Lực Redbull', 'cat' => 'Đồ uống', 'brand' => 'Khác', 'import' => 10000, 'sell' => 12000, 'unit' => 'Lon', 'barcode' => '8934588012666'],
            ['name' => 'Trà Ô Long Tea+ Plus', 'cat' => 'Đồ uống', 'brand' => 'Suntory PepsiCo', 'import' => 8000, 'sell' => 10000, 'unit' => 'Chai', 'barcode' => '8934588012777'],

            // --- ĐỒ GIA DỤNG ---
            ['name' => 'Bật Lửa Thống Nhất', 'cat' => 'Đồ gia dụng', 'brand' => 'Khác', 'import' => 3000, 'sell' => 5000, 'unit' => 'Cái', 'barcode' => '8938501110000'],
            ['name' => 'Túi Đựng Rác Cuộn Đen', 'cat' => 'Đồ gia dụng', 'brand' => 'Khác', 'import' => 25000, 'sell' => 35000, 'unit' => 'Kg', 'barcode' => '8938501112222'],
            ['name' => 'Giấy Vệ Sinh Watersilk', 'cat' => 'Đồ gia dụng', 'brand' => 'Khác', 'import' => 35000, 'sell' => 45000, 'unit' => 'Lốc', 'barcode' => '8938501113333'],
            ['name' => 'Khăn Giấy Ướt Baby', 'cat' => 'Đồ gia dụng', 'brand' => 'Khác', 'import' => 15000, 'sell' => 25000, 'unit' => 'Gói', 'barcode' => '8938501114444'],
            ['name' => 'Cây Lau Nhà 360', 'cat' => 'Đồ gia dụng', 'brand' => 'Khác', 'import' => 120000, 'sell' => 180000, 'unit' => 'Bộ', 'barcode' => '8938501115555'],
            ['name' => 'Màng Bọc Thực Phẩm', 'cat' => 'Đồ gia dụng', 'brand' => 'Khác', 'import' => 22000, 'sell' => 30000, 'unit' => 'Cuộn', 'barcode' => '8938501116666'],

            // --- ĐÔNG LẠNH ---
            ['name' => 'Xúc Xích Vườn Trường CP', 'cat' => 'Đông lạnh', 'brand' => 'CP', 'import' => 50000, 'sell' => 60000, 'unit' => 'Gói', 'barcode' => '8936001112222'],
            ['name' => 'Cá Viên CP 500g', 'cat' => 'Đông lạnh', 'brand' => 'CP', 'import' => 45000, 'sell' => 55000, 'unit' => 'Gói', 'barcode' => '8936001113333'],
            ['name' => 'Bò Viên CP 500g', 'cat' => 'Đông lạnh', 'brand' => 'CP', 'import' => 55000, 'sell' => 65000, 'unit' => 'Gói', 'barcode' => '8936001114444'],
            ['name' => 'Kem Ốc Quế Celano', 'cat' => 'Đông lạnh', 'brand' => 'Khác', 'import' => 12000, 'sell' => 15000, 'unit' => 'Cây', 'barcode' => '8936001115555'],
            ['name' => 'Kem Merino Đậu Xanh', 'cat' => 'Đông lạnh', 'brand' => 'Khác', 'import' => 7000, 'sell' => 10000, 'unit' => 'Cây', 'barcode' => '8936001116666'],
            ['name' => 'Há Cảo Tôm Thịt 500g', 'cat' => 'Đông lạnh', 'brand' => 'Khác', 'import' => 60000, 'sell' => 75000, 'unit' => 'Gói', 'barcode' => '8936001117777'],
            ['name' => 'Chả Giò Rế 500g', 'cat' => 'Đông lạnh', 'brand' => 'Khác', 'import' => 48000, 'sell' => 60000, 'unit' => 'Gói', 'barcode' => '8936001118888'],
        ];

        foreach ($realProducts as $p) {
            $product = Product::create([
                'category_id' => $catMap[$p['cat']],
                'brand_id' => $brandMap[$p['brand']],
                'barcode' => $p['barcode'],
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'import_price' => $p['import'],
                'sell_price' => $p['sell'],
                'unit' => $p['unit'],
                'description' => 'Sản phẩm chính hãng, chất lượng đảm bảo.',
                'status' => 1
            ]);

            $product->stock()->create(['quantity' => rand(20, 100)]);

            $textForImage = urlencode($p['name']); 
            $product->images()->create([
                'image_url' => "https://ui-avatars.com/api/?name={$textForImage}&background=random&color=fff&size=400",
                'is_primary' => 1
            ]);
        }
    }
}
