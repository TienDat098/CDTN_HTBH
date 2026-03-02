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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 50)->unique();
            $table->string('ghn_tracking_code')->nullable(); // Mã vận đơn GHN
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('staff_id')->nullable()->constrained('users');
            $table->decimal('total_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('final_total', 15, 2);
            $table->string('order_type', 20); // pos, web
            $table->string('payment_status', 50)->default('pending'); // pending, paid
            $table->text('shipping_address')->nullable();
            $table->text('note')->nullable();
            $table->string('status', 50)->default('new'); // new, processing, shipped, completed, canceled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
