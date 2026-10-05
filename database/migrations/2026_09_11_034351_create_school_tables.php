<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Mata Pelajaran (Berdiri sendiri)
        Schema::create('tbl_subjects', function (Blueprint $table) {
            $table->id('subject_id');
            $table->string('subject_name');
            $table->string('subject_code')->unique();
            $table->integer('credits');
            $table->timestamps();
        });

        // 2. Tabel Guru (Butuh tbl_users dan tbl_subjects)
        Schema::create('tbl_teachers', function (Blueprint $table) {
            $table->id('teacher_id');
            $table->foreignId('user_id')->constrained('tbl_users')->onDelete('cascade');
            $table->string('full_name', 100);
            $table->string('nip', 20)->unique();
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('subject_id')->on('tbl_subjects')->onDelete('restrict');
            $table->timestamps();
        });

        // 3. Tabel Kelas (Butuh tbl_teachers sbg Wali Kelas)
        Schema::create('tbl_classes', function (Blueprint $table) {
            $table->id('class_id');
            $table->string('class_name');
            $table->unsignedBigInteger('homeroom_teacher_id')->nullable();
            $table->foreign('homeroom_teacher_id')->references('teacher_id')->on('tbl_teachers')->onDelete('set null');
            $table->string('academic_year');
            $table->timestamps();
        });

        // 4. Tabel Siswa (Butuh tbl_users dan tbl_classes)
        Schema::create('tbl_students', function (Blueprint $table) {
            $table->id('student_id');
            $table->foreignId('user_id')->constrained('tbl_users')->onDelete('cascade');
            $table->string('full_name', 100);
            $table->enum('gender', ['L', 'P']);
            $table->string('nis', 20)->unique();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreign('class_id')->references('class_id')->on('tbl_classes')->onDelete('restrict');
            $table->date('date_of_birth')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Urutan drop harus dari bawah ke atas agar tidak bentrok foreign key
        Schema::dropIfExists('tbl_students');
        Schema::dropIfExists('tbl_classes');
        Schema::dropIfExists('tbl_teachers');
        Schema::dropIfExists('tbl_subjects');
    }
};
