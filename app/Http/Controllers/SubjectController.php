<?php
namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Schedule; // Tambahan Model Schedule
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahan Facades Auth

class SubjectController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Mata Pelajaran (Tabel Atas)
        $subjects = Subject::latest()->get();

        // 2. Ambil Data Jadwal Pelajaran Berdasarkan Role (Tabel Bawah)
        $query = Schedule::with(['classroom', 'teacher']);
        $userRole = Auth::user()->role;
        $namaKelasAmpuan = null;

        if ($userRole === 'siswa' || $userRole === 'student') {

            $loggedInStudent = \App\Models\Student::where('user_id', Auth::id())->first();
            if ($loggedInStudent) {
                $query->where('class_id', $loggedInStudent->class_id);
            } else {
                $query->where('schedule_id', 0);
            }

        } elseif ($userRole === 'guru' || $userRole === 'teacher') {

            $profilGuru = \App\Models\Teacher::where('user_id', Auth::id())->first();
            if ($profilGuru) {
                $kelasAmpuan = \App\Models\Classroom::where('teacher_id', $profilGuru->teacher_id)->pluck('class_id');

                if ($kelasAmpuan->isNotEmpty()) {
                    $query->whereIn('class_id', $kelasAmpuan);
                    $namaKelasAmpuan = \App\Models\Classroom::whereIn('class_id', $kelasAmpuan)->pluck('class_name')->implode(', ');
                } else {
                    $query->where('schedule_id', 0);
                }
            } else {
                $query->where('schedule_id', 0);
            }
        }

        // Urutkan hari secara logis (Senin - Sabtu) lalu berdasarkan jam mulai
        $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
              ->orderBy('start_time');

        // Gunakan get() bukan paginate() agar navigasi halamannya tidak bentrok
        $schedules = $query->get();

        return view('subjects.index', compact('subjects', 'schedules', 'namaKelasAmpuan'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|unique:tbl_subjects,subject_code',
            'subject_name' => 'required'
        ]);
        Subject::create($request->all());
        return redirect('/subjects');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_code' => 'required|unique:tbl_subjects,subject_code,' . $id . ',subject_id',
            'subject_name' => 'required'
        ]);
        $subject = Subject::findOrFail($id);
        $subject->update($request->all());
        return redirect('/subjects');
    }

    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();
        return redirect('/subjects');
    }
}
