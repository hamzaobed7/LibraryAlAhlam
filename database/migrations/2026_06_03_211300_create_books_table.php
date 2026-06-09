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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->char('ISBN', 13)->unique();
            $table->string('title', 150)->index();
            $table->decimal('rental_price', 6, 2)->default(0);
            $table->decimal('deposit', 8, 2)->default(0);
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('default_borrow_days')->nullable();       
            $table->unsignedInteger('total_copies')->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->date('published_at')->nullable();
            $table->string('cover')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
