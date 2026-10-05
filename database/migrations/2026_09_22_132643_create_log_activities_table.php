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
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Menyimpan ID admin yang sedang login
            $table->string('activity'); // Contoh: "Tambah Guru", "Edit Guru", dll
            $table->text('description'); // Contoh: "Menambahkan guru bernama Budi"
            $table->timestamps(); // Otomatis mencatat waktu kejadian (created_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};
