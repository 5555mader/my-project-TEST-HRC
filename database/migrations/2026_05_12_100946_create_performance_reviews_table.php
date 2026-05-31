<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('performance_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ประเมินใคร
        $table->integer('quality_score');     // คะแนนคุณภาพงาน (1-5)
        $table->integer('punctuality_score'); // คะแนนตรงต่อเวลา (1-5)
        $table->text('comments')->nullable(); // ความคิดเห็นเพิ่มเติม
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
