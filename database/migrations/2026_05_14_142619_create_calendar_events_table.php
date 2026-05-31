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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');          // หัวข้อกิจกรรม/วันหยุด
            $table->date('start');            // วันที่เริ่มต้น
            $table->date('end')->nullable();  // ถึงวันที่ (ใส่ nullable เผื่อกรณีเป็นกิจกรรมวันเดียว)
            $table->string('color')->default('#0d6efd'); // รหัสสี (เช่น #dc3545)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};