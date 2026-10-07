<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Subject;

class ScheduleController extends Controller
{
    // Method create untuk menampilkan form tambah jadwal
    public function create()
    {
        // Ambil data kelas dan guru untuk diletakkan di dropdown (pilihan)
        $classes = Classroom::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        return view('schedules.create', compact('classes', 'teachers', 'subjects'));
    }

    // Method store untuk menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'class_id'      => 'required',
            'subject_name'  => 'required',
            'teacher_id'     => 'required',
            'day'           => 'required',
            'start_time'    => 'required',
            'end_time'      => 'required',
        ]);

        Schedule::create($request->all());

        // Arahkan kembali ke halaman subjects karena tabelnya ada di sana
        return redirect('/subjects')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Method edit untuk menampilkan form edit
    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $classes = Classroom::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        return view('schedules.edit', compact('schedule', 'classes', 'teachers', 'subjects'));
    }

    // Method update untuk menyimpan perubahan data
    public function update(Request $request, $id)
    {
        //dd($request->all(), $id);

        $request->validate([
            'class_id'      => 'required',
            'subject_name'  => 'required',
            'day'           => 'required',
            'start_time'    => 'required',
            'end_time'      => 'required',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect('/subjects')->with('success', 'Jadwal berhasil diperbarui!');
    }

    // Method destroy untuk menghapus jadwal
    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();
        return redirect('/subjects')->with('success', 'Jadwal berhasil dihapus!');
    }
}
