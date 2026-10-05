@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-navy">Edit Akun Pengguna</h5>
    </div>
    <div class="card-body">
        <form action="/users/{{ $user->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email / Username</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password <span class="text-muted" style="font-size: 12px;">(Kosongkan jika tidak ingin mereset password)</span></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-4">
                <label class="form-label">Hak Akses (Role)</label>
                <select name="role" class="form-select" required>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Guru</option>
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Update Pengguna</button>
            <a href="/users" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
