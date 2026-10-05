<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan halaman form login
    public function index()
    {
        return view('auth.login');
    }

    // 2. Memvalidasi email & password, lalu proses login
    public function authenticate(Request $request)
    {
        // Validasi input (Sesuai tugas To-Do)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek ke database tbl_users
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();


            \App\Models\LogActivity::create([
                'user_id' => auth()->user()->id,
                'activity' => 'Login',
                'description' => auth()->user()->name . ' baru saja login ke sistem'
            ]);
            // Jika sukses, arahkan ke dashboard
            return redirect()->intended('/dashboard');
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Kembali ke halaman awal/login
    }
}
