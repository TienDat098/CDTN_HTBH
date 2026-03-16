<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
        // Thêm cột qr_code (cho phép rỗng với các đơn hàng cũ đã có sẵn)
        $table->string('qr_code')->nullable()->after('order_code'); 
        
        // Xóa cột payment_status (vì đã có bảng payments lo)
        $table->dropColumn('payment_status'); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('qr_code');
        $table->string('payment_status')->nullable();
    });

    Schema::table('products', function (Blueprint $table) {
        $table->string('qr_code')->nullable();
    });
    }
};
