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
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->string('session_id'); // Mã phiên chat để biết tin nhắn nào thuộc cùng 1 cuộc hội thoại
            $table->text('user_message'); // Khách hỏi gì
            $table->text('bot_response')->nullable(); // Bot trả lời gì
            $table->string('intent_name')->nullable(); // Bot hiểu ý định gì (VD: Tra_Cuu_Ton_Kho)
            $table->decimal('confidence_score', 3, 2)->nullable(); // Độ tự tin của Bot (VD: 0.95 = 95%)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
    }
};
