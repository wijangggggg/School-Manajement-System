<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_classes';
    protected $primaryKey = 'class_id';
    protected $guarded = [];

    // Relasi ke tabel Guru (Wali Kelas)
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }
}
