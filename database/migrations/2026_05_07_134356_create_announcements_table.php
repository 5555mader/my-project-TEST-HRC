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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // หัวข้อ
            $table->text('content'); // เนื้อหา
            $table->string('author_name'); // ผู้โพสต์ (เช่น HR, CSR)
            $table->string('category'); // ประเภท (success, primary, danger)
            $table->string('image')->nullable(); // รูปภาพประกอบ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};