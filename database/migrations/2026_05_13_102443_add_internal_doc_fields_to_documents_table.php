<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // เพิ่มคอลัมน์ใหม่ที่จำเป็นสำหรับระบบบันทึกข้อความ
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('doc_number')->nullable()->after('file_extension');
            $table->string('department')->nullable()->after('doc_number');
            $table->string('to_position')->nullable()->after('department');
            $table->text('content')->nullable()->after('to_position');
            $table->unsignedBigInteger('approver_id')->nullable()->after('content');
            $table->string('status')->default('pending')->after('approver_id');

            // (Optional) ผูก Foreign Key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // เอาไว้ลบคอลัมน์ออกเวลาสั่ง rollback
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'doc_number', 'department', 'to_position', 
                'content', 'approver_id', 'status'
            ]);
        });
    }
};