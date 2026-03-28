<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
    
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('customer_name')->after('order_code')->nullable();
            $table->string('customer_phone')->after('customer_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropColumn(['customer_name', 'customer_phone']);
        });
    }
};