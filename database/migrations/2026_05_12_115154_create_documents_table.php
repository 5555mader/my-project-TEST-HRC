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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // ชื่อเอกสาร
        $table->string('category'); // หมวดหมู่
        $table->string('file_name'); // ชื่อไฟล์ที่ระบบบันทึก
        $table->string('file_size'); // ขนาดไฟล์
        $table->string('file_extension'); // นามสกุลไฟล์ (pdf, docx)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
