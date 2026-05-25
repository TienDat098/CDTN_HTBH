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
        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            // Link YouTube đầy đủ admin nhập
            $table->string('youtube_url')->nullable();

            // ID video YouTube, ví dụ: dQw4w9WgXcQ
            $table->string('youtube_video_id')->nullable();

            // Trạng thái livestream
            $table->boolean('is_active')->default(false);

            // Thời gian bắt đầu / kết thúc nếu muốn hiển thị lịch
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();

            // Người tạo livestream
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestreams');
    }
};
