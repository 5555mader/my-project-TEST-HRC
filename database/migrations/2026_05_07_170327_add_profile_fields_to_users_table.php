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
    Schema::table('users', function (Blueprint $table) {
        // วางโค้ดที่นี่ครับ
        $table->string('phone')->nullable()->after('email');
        $table->text('address')->nullable()->after('phone');
        $table->string('department')->nullable()->after('address');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // ส่วนนี้สำหรับยกเลิกการเปลี่ยนแปลง (เผื่อสั่ง rollback)
        $table->dropColumn(['phone', 'address', 'department']);
    });
}
};
