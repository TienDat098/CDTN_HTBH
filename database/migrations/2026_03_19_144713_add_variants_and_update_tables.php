<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TẠO BẢNG BIẾN THỂ SẢN PHẨM MỚI
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name'); // Tên: Bịch 220ml, Thùng 48 Bịch...
            $table->decimal('price', 15, 2); // Giá riêng cho biến thể
            $table->integer('stock_quantity')->default(0); // Tồn kho riêng
            $table->string('barcode')->nullable()->unique(); // Mã vạch riêng
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 2. CẤY THÊM CỘT variant_id VÀO BẢNG cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')
                  ->constrained('product_variants')->onDelete('cascade');
        });

        // 3. CẤY THÊM CỘT variant_id VÀO BẢNG order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')
                  ->constrained('product_variants')->onDelete('set null');
        });

        // 4. CẤY THÊM CỘT variant_id VÀO BẢNG inventory_logs
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')
                  ->constrained('product_variants')->onDelete('set null');
        });

        // 5. CẤY THÊM CỘT variant_id VÀO BẢNG product_stocks
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')
                  ->constrained('product_variants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Rút cột variant_id ra nếu muốn quay xe (rollback)
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });

        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });

        // Xóa bảng
        Schema::dropIfExists('product_variants');
    }
};