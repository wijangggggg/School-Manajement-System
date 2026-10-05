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
        Schema::table('tbl_students', function (Blueprint $table) {
            // Menambah kolom angkatan (panjang 4 digit)
            $table->string('angkatan', 4)->nullable()->after('date_of_birth');
        });
    }

    public function down()
    {
        Schema::table('tbl_students', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });
    }
};
