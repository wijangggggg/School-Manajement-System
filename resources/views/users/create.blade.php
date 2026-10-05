@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/users" class="text-decoration-none">Data Pengguna</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Tambah Pengguna</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-navy">Tambah Akun Pengguna</h5>
    </div>
    <div class="card-body">
        <form action="/users" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email / Username</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Hak Akses (Role)</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Guru</option>
                    <option value="student">Siswa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
            <a href="/users" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
