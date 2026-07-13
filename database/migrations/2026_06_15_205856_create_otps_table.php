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
        Schema::dropIfExists('otps');
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->string('otp_hash');
           $table->integer('attempts')->default(0);
           $table->timestamp("expires_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
