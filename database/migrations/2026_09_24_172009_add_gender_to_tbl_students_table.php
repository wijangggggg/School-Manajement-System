<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_students', function (Blueprint $table) {
            // Menambah kolom gender setelah full_name.
            // nullable() agar data lama (seperti Haris) tidak error
            $table->enum('gender', ['L', 'P'])->nullable()->after('full_name');
        });
    }

    public function down()
    {
        Schema::table('tbl_students', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
