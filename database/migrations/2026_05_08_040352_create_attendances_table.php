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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->date('date'); // วันที่ทำงาน
        $table->time('check_in')->nullable(); // เวลาเข้า
        $table->time('check_out')->nullable(); // เวลาออก
        $table->string('status')->default('ปกติ'); // สถานะ เช่น ปกติ, มาสาย
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
