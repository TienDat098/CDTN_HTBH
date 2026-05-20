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
    Schema::table('messages', function (Blueprint $table) {
        // Cho phép sender_id nullable (vì khách vãng lai không có ID)
        $table->foreignId('sender_id')->nullable()->change();
        
        // Thêm session_id để phân biệt các khách vãng lai khác nhau
        $table->string('session_id')->nullable()->after('conversation_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
