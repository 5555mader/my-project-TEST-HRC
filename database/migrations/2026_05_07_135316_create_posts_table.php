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
        Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');        // หัวข้อประกาศ
    $table->text('content');       // เนื้อหา
    $table->string('category');    // ประเภท: success (ข่าวทั่วไป), danger (ประกาศด่วน), primary (กิจกรรม)
    $table->string('author');      // ชื่อผู้โพสต์ (เช่น HR Admin)
    $table->string('image')->nullable(); // พาธรูปภาพประกอบ
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
