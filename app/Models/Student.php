<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    // 1. Beritahu Laravel nama tabel yang benar
    protected $table = 'tbl_students';

    // 2. Beritahu Laravel nama Primary Key yang benar
    protected $primaryKey = 'student_id';

    // 3. Daftarkan kolom apa saja yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'full_name',
        'nis',
        'class_id',
        'date_of_birth',
        'gender',
        'angkatan',
    ];

    // 4. Relasi ke tabel tbl_users (One-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function classroom()
    {
        // Menghubungkan ke Model Classroom, kolom foreign key, kolom primary key
        return $this->belongsTo(Classroom::class, 'class_id', 'class_id');
    }
}
