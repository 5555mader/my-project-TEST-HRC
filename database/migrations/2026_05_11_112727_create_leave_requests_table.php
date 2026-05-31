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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับตาราง users เพื่อให้รู้ว่าใครเป็นคนส่งคำขอ
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('leave_type'); // ประเภทการลา
            $table->date('start_date');   // วันที่เริ่ม
            $table->date('end_date');     // วันที่สิ้นสุด
            $table->text('reason');       // เหตุผล
            $table->string('status')->default('pending'); // สถานะเริ่มต้นคือ pending
            $table->string('attachment')->nullable();     // ไฟล์แนบ (อนุญาตให้ว่างได้)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
