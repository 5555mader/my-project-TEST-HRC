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
        Schema::table('calendar_events', function (Blueprint $table) {
            // เพิ่มคอลัมน์ description เป็นชนิด text และให้ว่างได้ (nullable)
            $table->text('description')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            // เอาคอลัมน์ออกกรณีที่ต้องการย้อนกลับ (Rollback)
            $table->dropColumn('description');
        });
    }
};