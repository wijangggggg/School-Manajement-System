<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar
    protected $table = 'tbl_schedules';

    // Tentukan primary key
    protected $primaryKey = 'schedule_id';

    // Izinkan semua kolom diisi massal
    protected $guarded = [];

    // Relasi ke tabel Kelas
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'class_id');
    }

    // Relasi ke tabel Guru yang mengajar mapel ini
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
