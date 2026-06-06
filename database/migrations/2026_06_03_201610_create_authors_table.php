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
        Schema::create('authors', function (Blueprint $table) {
            $table->id()->index();
            $table->string('first_name',50)->index();
            $table->string("last_name",50)->index();
            $table->string("email");
            $table->enum("gender",["Male","Female"]);
            $table->date("birth-date")->nullable();
            $table->text("bio")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
