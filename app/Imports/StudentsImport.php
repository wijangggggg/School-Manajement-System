<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public $berhasil = 0;
    public $duplikat = 0;

    public function model(array $row)
    {
        if (!isset($row[0]) || !is_numeric($row[0])) {
            return null;
        }

        if (Student::where('nis', $row[0])->exists()) {
            $this->duplikat++;
            return null;
        }

        $user = User::firstOrCreate(
            ['email' => $row[0] . '@student.sch.id'],
            [
                'name'     => $row[1],
                'password' => Hash::make($row[0]),
                'role'     => 'student',
            ]
        );

        $jkInput = isset($row[3]) ? strtolower(trim($row[3])) : '';
        if ($jkInput === 'l' || $jkInput === 'laki-laki' || $jkInput === 'laki laki') {
            $gender = 'L';
        } elseif ($jkInput === 'p' || $jkInput === 'perempuan') {
            $gender = 'P';
        } else {
            $gender = $row[3] ?? null;
        }

        $nis = $row[0]; // Sesuaikan index/nama kolom NIS-mu
        $angkatan_otomatis = '20' . substr($nis, 0, 2);

        $this->berhasil++;

        return new Student([
            'nis'           => $row[0],
            'full_name'     => $row[1],
            'class_id'      => $row[2],
            'date_of_birth' => is_numeric($row[4])
                                ? \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]))->format('Y-m-d'): \Carbon\Carbon::parse($row[4])->format('Y-m-d'),
            'gender'        => $gender,
            'user_id'       => $user->id,
            'angkatan'      => $angkatan_otomatis,
        ]);
    }
}
