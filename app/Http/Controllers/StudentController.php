<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\Student::with('classroom');

    $userRole = Auth::user()->role;

    if ($userRole === 'siswa' || $userRole === 'student') {
        // Jika SISWA: Hanya tampilkan teman sekelas
        $loggedInStudent = \App\Models\Student::where('user_id', Auth::user()->id)->first();

        if ($loggedInStudent) {
            $query->where('class_id', $loggedInStudent->class_id);
        } else {
            $query->where('student_id', 0); // Kosongkan jika profil belum ada
        }

    } elseif ($userRole === 'guru' || $userRole === 'teacher') {

        // --- BAGIAN YANG DIUBAH (2 LANGKAH) ---
        // 1. Cari dulu profil gurunya berdasarkan user_id (akun login)
        $profilGuru = \App\Models\Teacher::where('user_id', Auth::user()->id)->first();

        if ($profilGuru) {
            // 2. Jika ketemu, cari kelas berdasarkan teacher_id profil tersebut
            $kelasAmpuan = \App\Models\Classroom::where('teacher_id', $profilGuru->teacher_id)->pluck('class_id');

            if ($kelasAmpuan->isNotEmpty()) {
                $query->whereIn('class_id', $kelasAmpuan);
            } else {
                $query->where('student_id', 0); // Guru ada, tapi tidak punya kelas
            }
        } else {
            $query->where('student_id', 0); // Akun login belum ditautkan ke profil guru mana pun
        }
        // -------------------------------------

    }

    // 2. Jika Admin mengetik di kotak pencarian (Search)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('full_name', 'like', '%' . $search . '%')
              ->orWhere('nis', 'like', '%' . $search . '%');
        });
    }

    // 3. Jika Admin memilih dropdown Jenis Kelamin (Filter)
    if ($request->has('gender') && $request->gender != '') {
        $query->where('gender', $request->gender);
    }

    // 4. Ambil data dengan Pagination
    $students = $query->paginate(20)->withQueryString();

    return view('students.index', compact('students'));
}

    public function create()
    {
        // Mengambil semua data kelas dari database
        $classes = Classroom::all();

        // Melempar variabel $classes ke halaman form
        return view('students.create', compact('classes'));
    }

    // 2. Memproses data dari form ke database
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak! Hanya Admin yang berhak melakukan ini.');
        }
        $tahun_prefix = substr($request->nis, 0, 2);
        $angkatan_otomatis = '20' . $tahun_prefix;

        $request->validate([
            'nis' => 'required|unique:tbl_students,nis',
            'full_name' => 'required',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6',
            'gender' => 'required|in:L,P',
        ]);

        // Langkah A: Buat akun login siswa di tbl_users dulu
        $user = User::create([
            'name' => $request->full_name,
            'username' => strtolower(str_replace(' ', '', $request->full_name)), // username otomatis
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        // Langkah B: Simpan data siswa ke tbl_students dengan membawa user_id dari Langkah A
        Student::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'angkatan' => $angkatan_otomatis,
            'class_id'      => $request->class_id,
        ]);

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Tambah Siswa',
            'description' => 'Admin menambahkan data siswa bernama: ' . $request->full_name
        ]);

        // Kembali ke halaman daftar siswa
        return redirect('/students');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak! Hanya Admin yang berhak melakukan ini.');
        }
        $student = \App\Models\Student::findOrFail($id);
        $classes = \App\Models\Classroom::all();
        return view('students.edit', compact('student','classes'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak! Hanya Admin yang berhak melakukan ini.');
        }
        $tahun_prefix = substr($request->nis, 0, 2);
        $angkatan_otomatis = '20' . $tahun_prefix;

        $student = Student::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:tbl_students,nis,' . $student->nis . ',nis',
            'full_name' => 'required',
            'gender' => 'required|in:L,P',
            'class_id'      => 'required',
        ]);

        // Update nama di akun login (tbl_users) agar sinkron
        $user = User::find($student->user_id);
        if($user) {
            // Siapkan data nama yang pasti akan di-update
            $userData = ['name' => $request->full_name];

            // Cek apakah Admin mengubah NIS-nya
            if ($request->nis != $student->nis) {
                // Jika NIS diubah, sekalian ganti email dan passwordnya!
                $userData['email'] = $request->nis . '@student.sch.id';
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->nis);
            }

            // Lakukan proses update ke database
            $user->update($userData);
        }

        // Update data di tbl_students
        $student->update([
            'nis' => $request->nis,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'class_id'      => $request->class_id,
            'angkatan'      => $angkatan_otomatis,
        ]);

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Edit Siswa',
            'description' => 'Admin mengedit data siswa bernama: ' . $request->full_name
        ]);

        return redirect('/students')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('http://127.0.0.1:9999');
        }

        $student = Student::findOrFail($id);
        $userId = $student->user_id;

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Hapus Siswa',
            'description' => 'Admin menghapus data siswa bernama: ' . $student->full_name
        ]);

        // 1. Lakukan Soft Delete pada profil Siswa
        $student->delete();

        // 2. MATIKAN SEMENTARA baris di bawah ini dengan memberikan komentar (//)
        User::destroy($userId);

        return redirect('/students')->with('success', 'Data Siswa berhasil diarsipkan!');
    }

    public function importData(Request $request)
    {
        // Validasi: pastikan yang diunggah benar-benar file dan berekstensi excel/csv
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // 1. Tampung class StudentsImport ke dalam variabel $import
            $import = new StudentsImport;
            Excel::import($import, $request->file('file_excel'));

            // 2. Kondisi A: Jika TIDAK ADA data yang masuk karena duplikat semua
            if ($import->berhasil == 0 && $import->duplikat > 0) {
                return redirect('/students')->with('error', 'Gagal! Semua data siswa (' . $import->duplikat . ' data) ditolak karena NIS sudah terdaftar.');
            }

            // 3. Kondisi B: Jika file Excel kosong atau tidak ada baris yang valid
            if ($import->berhasil == 0 && $import->duplikat == 0) {
                return redirect('/students')->with('error', 'Gagal! Tidak ada data siswa valid yang ditemukan di dalam file.');
            }

            // 4. Catat ke Log Activity (Hanya jika ada data yang berhasil masuk)
            \App\Models\LogActivity::create([
                'user_id'     => auth()->user()->id,
                'activity'    => 'Import Data Siswa',
                'description' => 'Admin mengimpor ' . $import->berhasil . ' data siswa dari file Excel: ' . $request->file('file_excel')->getClientOriginalName()
            ]);

            // 5. Kondisi C: Jika sebagian berhasil masuk, tapi ada sebagian yang duplikat
            if ($import->berhasil > 0 && $import->duplikat > 0) {
                return redirect('/students')->with('success', $import->berhasil . ' siswa berhasil di-import! (' . $import->duplikat . ' data dilewati karena NIS sudah ada).');
            }

            // 6. Kondisi D: Jika murni berhasil masuk semua tanpa duplikat
            return redirect('/students')->with('success', $import->berhasil . ' data siswa berhasil di-import dari Excel!');

        } catch (\Exception $e) {
            // Jika ada error sistem/format, tangkap dan tampilkan pesannya
            return redirect('/students')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
