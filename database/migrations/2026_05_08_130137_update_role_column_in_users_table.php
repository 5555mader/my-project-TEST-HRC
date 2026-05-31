<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // ลบคอลัมน์ is_admin เดิมทิ้ง
            $table->dropColumn('is_admin');
            // เพิ่มคอลัมน์ role เข้ามาแทน และให้ค่าเริ่มต้นเป็นพนักงานทั่วไป
            $table->string('role')->default('General Employee')->after('password');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->boolean('is_admin')->default(false);
        });
    }
};
