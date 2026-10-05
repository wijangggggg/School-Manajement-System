<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = \App\Models\Teacher::all();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang diketik Admin (Sesuaikan dengan nama kolom tabelmu)
        $request->validate([
            'nip' => 'required|unique:tbl_teachers,nip',
            'full_name' => 'required',
        ]);

        // 2. BUAT AKUN LOGIN OTOMATIS
        // Email format: NIP@teacher.sch.id | Password: NIP
        $user = \App\Models\User::create([
            'name' => $request->full_name,
            'email' => $request->nip . '@teacher.sch.id',
            'password' => \Illuminate\Support\Facades\Hash::make($request->nip),
            'role' => 'teacher',
        ]);

        // 3. SIMPAN BIODATA GURU & TAUTKAN KE AKUN BARU
        \App\Models\Teacher::create([
            'user_id' => $user->id, // Ini kunci rahasianya! Menyambungkan biodata ke akun
            'nip' => $request->nip,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id, // Mengambil ID admin yang sedang login
            'activity' => 'Tambah Guru',
            'description' => 'Menambahkan guru bernama : ' . $request->full_name
        ]);

        return redirect('/teachers')->with('success', 'Data Guru dan Akun Login otomatis berhasil ditambahkan!');
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. VALIDASI: Di sini HANYA berisi aturan (seperti 'required'), bukan penangkap data
        $request->validate([
            'nip' => 'required|unique:tbl_teachers,nip,' . $id . ',teacher_id',
            'full_name' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);

        $teacher = \App\Models\Teacher::findOrFail($id);

        // 2. UPDATE AKUN LOGIN (tbl_users) AGAR SINKRON (Jika ada)
        $user = \App\Models\User::find($teacher->user_id);
        if($user) {
            $userData = ['name' => $request->full_name];

            // Jika NIP diubah, ganti email dan passwordnya
            if ($request->nip != $teacher->nip) {
                $userData['email'] = $request->nip . '@teacher.sch.id';
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->nip);
            }

            $user->update($userData);
        }

        // 3. UPDATE BIODATA GURU (tbl_teachers)
        // Nah, DI SINI BARU kita menangkap data dari form ($request->...)
        $teacher->update([
            'nip' => $request->nip,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id, // Mengambil ID admin yang sedang login
            'activity' => 'Edit Guru',
            'description' => 'Admin mengedit data guru bernama: ' . $request->full_name
        ]);

        return redirect('/teachers')->with('success', 'Data Guru berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);

        // 1. Ambil ID akun login-nya sebelum biodatanya dihapus
        $userId = $teacher->user_id;

        // 2. Catat ke Log Activity
        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id, // Mengambil ID admin yang sedang login
            'activity' => 'Hapus Guru',
            'description' => 'Menghapus guru bernama ' . $teacher->full_name
        ]);

        // 3. Hapus biodata gurunya
        $teacher->delete();

        if ($userId) {
            \App\Models\User::destroy($userId);
        }

        // Perbaikan SweetAlert: Parameter pertama harus huruf kecil 'success', bukan 'Success, ...'
        return redirect('/teachers')->with('success', 'Data Guru dan Akun Login berhasil dihapus!');
    }

    public function edit($id)
    {
        // 1. Cari data guru di database berdasarkan ID yang dipilih
        $teacher = \App\Models\Teacher::findOrFail($id);

        // 2. Arahkan ke file tampilan form edit dan kirimkan data guru tersebut
        // (Pastikan kamu sudah membuat file resources/views/teachers/edit.blade.php)
        return view('teachers.edit', compact('teacher'));
    }
}
