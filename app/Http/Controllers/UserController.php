<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan hanya admin yang bisa melihat data pengguna
        if (Auth::user()->role !== 'admin') {
            // Disarankan redirect ke route internal/dashboard alih-alih URL port hardcoded
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // 2. Baris $users = User::all() sudah dihapus dari sini

        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(30)
        ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::findOrFail($id);

        // Mencegah Admin menghapus akunnya sendiri yang sedang dipakai login
        if ($user->id === Auth::id()) {
            return redirect('/users')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        // 1. Catat ke Log Activity
        LogActivity::create([
            'user_id' => Auth::id(),
            'activity' => 'Hapus Pengguna',
            'description' => 'Admin menghapus akun pengguna: ' . $user->name
        ]);

        // 2. Hapus akun
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') return redirect('http://127.0.0.1:9999');
        return view('users.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') return redirect('http://127.0.0.1:9999');

        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
            'role' => 'required',
            'email'    => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) use ($request) {
                    $role = strtolower($request->role);
                    $email = strtolower($value);

                    if (in_array($role, ['siswa', 'student']) && !str_ends_with($email, '@student.sch.id')) {
                        $fail('Format email untuk Siswa wajib menggunakan akhiran @student.sch.id!');
                    }

                    if (in_array($role, ['guru', 'teacher']) && !str_ends_with($email, '@teacher.sch.id')) {
                        $fail('Format email untuk Guru wajib menggunakan akhiran @teacher.sch.id!');
                    }

                    if ($role === 'admin' && !str_ends_with($email, '@admin.sch.id')) {
                        $fail('Format email untuk Admin wajib menggunakan akhiran @admin.sch.id!');
                    }
                },
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => $request->role,
        ]);

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Tambah Pengguna',
            'description' => 'Admin menambahkan akun pengguna: ' . $request->name
        ]);

        return redirect()->route('users.index')->with('success', 'Akun Pengguna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') return redirect('http://127.0.0.1:9999');
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') return redirect('http://127.0.0.1:9999');

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:tbl_users,email,'.$id,
            'role' => 'required'
        ]);

        // Siapkan data yang akan diupdate
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Jika form password diisi, berarti admin ingin mereset password-nya
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        \App\Models\LogActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Edit Pengguna',
            'description' => 'Admin mengubah akun pengguna: ' . $request->name
        ]);

        return redirect()->route('users.index')->with('success', 'Akun Pengguna berhasil diperbarui!');
    }
}
