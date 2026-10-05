<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\Teacher;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // with('teacher') akan menarik nama Wali Kelas sekaligus secara efisien
        $classes = Classroom::with('teacher')->latest()->get();
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::all(); // Mengambil data guru untuk dropdown Wali Kelas
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['class_name' => 'required']);
        Classroom::create($request->all());

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Tambah Kelas', 
            'description' => 'Admin menambahkan data kelas: ' . $request->class_name
        ]);
        return redirect('/classes');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $class = Classroom::findOrFail($id);
        $teachers = Teacher::all(); // Ambil data guru untuk dropdown
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
        'class_name' => 'required'
    ]);

    $class = Classroom::findOrFail($id);
    $class->update($request->all());

        \App\Models\LogActivity::create([
        'user_id' => auth()->user()->id,
        'activity' => 'Edit Kelas', // Kata "Edit" akan otomatis membuatnya kuning
        'description' => 'Admin mengedit data kelas: ' . $request->class_name
    ]);

    return redirect('/classes')->with('success', 'Data Kelas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);

        // Pastikan kamu mencari data kelasnya dulu, misal: $kelas = \App\Models\ClassModel::findOrFail($id);
        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Hapus Kelas', // Kata "Hapus" akan otomatis membuatnya merah
            'description' => 'Admin menghapus data kelas: ' . $classroom->class_name
        ]);
        $classroom->delete();
        return redirect('/classes')->with('Success', 'Data berhasil dihapus!');
    }
}
