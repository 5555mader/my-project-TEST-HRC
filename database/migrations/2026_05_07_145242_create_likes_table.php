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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับ ID ของตาราง posts และลบข้อมูลอัตโนมัติหากโพสต์ถูกลบ
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); 
            // เก็บ IP Address ของผู้ใช้งานเพื่อป้องกันการกดซ้ำ
            $table->string('user_ip'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};