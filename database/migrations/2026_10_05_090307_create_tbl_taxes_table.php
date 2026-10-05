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
        Schema::create('tbl_taxes', function (Blueprint $table) {
            $table->id();

            // Kolom inti data pajak
            $table->string('name');
            $table->float('rate');
            $table->integer('status')->default(1);
            $table->integer('id_user')->nullable();

            // Kolom sistem (bawaan Laravel)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_taxes');
    }
};
