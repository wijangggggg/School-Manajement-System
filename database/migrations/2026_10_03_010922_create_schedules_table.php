<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tbl_schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('class_id'); // Relasi ke kelas mana jadwal ini
            $table->unsignedBigInteger('teacher_id')->nullable(); // Relasi ke guru siapa yang mengajar
            $table->string('subject_name'); // Nama Mata Pelajaran
            $table->string('day'); // Hari (Senin, Selasa, dll)
            $table->time('start_time'); // Jam Mulai
            $table->time('end_time'); // Jam Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
