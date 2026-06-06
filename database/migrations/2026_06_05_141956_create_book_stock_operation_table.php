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
        Schema::create('book_stock_operation', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('quantity');
            $table->enum('type',['add', 'destroy']);
            $table->boolean("remove_from_remaining");
            $table->foreignId("book_id")->constrained()->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_stock_operation');
    }
};
