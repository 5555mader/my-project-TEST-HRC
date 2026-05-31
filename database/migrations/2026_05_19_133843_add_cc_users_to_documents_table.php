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
        Schema::table('documents', function (Blueprint $table) {
            // สร้างคอลัมน์ cc_users เป็นแบบ text และให้ว่างได้ (nullable)
            $table->text('cc_users')->nullable()->after('status'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // เอาคอลัมน์ออกกรณีที่เราสั่ง Rollback
            $table->dropColumn('cc_users');
        });
    }
};