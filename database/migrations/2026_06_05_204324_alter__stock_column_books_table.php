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
        Schema::table('books', function (Blueprint $table) {
            // نقوم هنا بالتعديل على العمود الموجود مسبقاً بدلاً من إضافته من جديد
            $table->unsignedInteger('stock')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // هنا نرجع العمود لخصائصه الأصلية السابقة (مثال: لو كان لا يقبل NULL)
            $table->unsignedInteger('stock')->change(); 
        });
    }
};