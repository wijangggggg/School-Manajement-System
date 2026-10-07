<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student; // Import Model Student
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung data untuk 4 Kotak di Atas
        $total_teachers = Teacher::count();
        $total_classes  = Classroom::count();
        $total_subjects = Subject::count();

        // Menggunakan Model Student agar otomatis memfilter data yang dihapus (SoftDeletes / Hard Delete)
        if (class_exists('\App\Models\Student')) {
            $total_students = Student::count();
        } else {
            $total_students = Schema::hasTable('tbl_students')
                ? DB::table('tbl_students')->whereNull('deleted_at')->count()
                : 0;
        }

        // 2. Hitung data untuk Grafik Donat
        $total_admins = User::whereIn('role', ['admin', 'Admin'])->count();

        $total_users = $total_students + $total_teachers + $total_admins;

        $persen_siswa = $total_users > 0 ? round(($total_students / $total_users) * 100) : 0;
        $persen_guru  = $total_users > 0 ? round(($total_teachers / $total_users) * 100) : 0;
        $persen_admin = $total_users > 0 ? round(($total_admins / $total_users) * 100) : 0;

        // 3. Hitung data untuk Grafik Batang Login (7 Hari Terakhir)
        $siswaIds = User::whereIn('role', ['student', 'siswa', 'Siswa'])->pluck('id');
        $guruIds  = User::whereIn('role', ['teacher', 'guru', 'Guru'])->pluck('id');
        $adminIds = User::whereIn('role', ['admin', 'Admin'])->pluck('id');

        $chartLabels = [];
        $chartDataSiswa = [];
        $chartDataGuru = [];
        $chartDataAdmin = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $chartLabels[] = Carbon::parse($date)->translatedFormat('d M');

            $chartDataSiswa[] = LogActivity::where('activity', 'Login')
                ->whereDate('created_at', $date)
                ->whereIn('user_id', $siswaIds)
                ->count();

            $chartDataGuru[] = LogActivity::where('activity', 'Login')
                ->whereDate('created_at', $date)
                ->whereIn('user_id', $guruIds)
                ->count();

            $chartDataAdmin[] = LogActivity::where('activity', 'Login')
                ->whereDate('created_at', $date)
                ->whereIn('user_id', $adminIds)
                ->count();
        }

        return view('dashboard', compact(
            'total_teachers',
            'total_classes',
            'total_subjects',
            'total_students',
            'total_admins',
            'persen_siswa',
            'persen_guru',
            'persen_admin',
            'chartLabels',
            'chartDataSiswa',
            'chartDataGuru',
            'chartDataAdmin'
        ));
    }
}
