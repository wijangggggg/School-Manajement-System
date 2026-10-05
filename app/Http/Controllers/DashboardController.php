<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung data untuk 4 Kotak di Atas
        $total_teachers = Teacher::count();
        $total_classes = Classroom::count();
        $total_subjects = Subject::count();
        $total_students = Schema::hasTable('tbl_students') ? DB::table('tbl_students')->count() : 0;

        // 2. Hitung data untuk Grafik Donat
        // Menggunakan whereIn agar tetap terbaca meskipun di database tertulis 'admin' atau 'Admin'
        $total_admins = \App\Models\User::whereIn('role', ['admin', 'Admin'])->count();

        $total_users = $total_students + $total_teachers + $total_admins;

        // Gunakan 1 angka desimal (round(..., 1)) atau bulat agar totalnya akurat 100%
        $persen_siswa = $total_users > 0 ? round(($total_students / $total_users) * 100) : 0;
        $persen_guru  = $total_users > 0 ? round(($total_teachers / $total_users) * 100) : 0;
        $persen_admin = $total_users > 0 ? round(($total_admins / $total_users) * 100) : 0;

        // 3. Hitung data untuk Grafik Batang Login (3 Bar)
        $siswaIds = \App\Models\User::whereIn('role', ['student', 'siswa', 'Siswa'])->pluck('id');
        $guruIds  = \App\Models\User::whereIn('role', ['teacher', 'guru', 'Guru'])->pluck('id');
        $adminIds = \App\Models\User::whereIn('role', ['admin', 'Admin'])->pluck('id');

        $dates = LogActivity::selectRaw('DATE(created_at) as date')
            ->where('activity', 'Login')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(7)
            ->pluck('date');

        $chartLabels = [];
        $chartDataSiswa = [];
        $chartDataGuru = [];
        $chartDataAdmin = [];

        foreach ($dates as $date) {
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

        // PASTIKAN SEMUA VARIABEL INI ADA DI DALAM COMPACT:
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
