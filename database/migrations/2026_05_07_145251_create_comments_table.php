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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับ ID ของตาราง posts
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); 
            // ชื่อผู้เขียนคอมเมนต์
            $table->string('author_name'); 
            // เนื้อหาข้อความคอมเมนต์
            $table->text('comment_text'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};