<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_teachers';
    protected $primaryKey = 'teacher_id';
    protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'gender',
        'phone_number',
        'gender',
        'address',
    ];
}
