@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Data Pengguna</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-navy">Manajemen Akun Pengguna</h5>
        <a href="/users/create" class="btn btn-primary btn-sm">+ Tambah Pengguna</a>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="30%">Nama Pengguna</th>
                    <th class="text-center">Email</th>
                    <th class="text-center" width="30%">Role / Hak Akses</th>
                    <th class="text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            @if($user->role == 'admin')
                                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger">Admin</span>
                            @elseif($user->role == 'teacher')
                                <span class="badge bg-success-subtle text-success-emphasis border border-success">Guru</span>
                            @else
                                <span class="badge bg-primary-subtle text-primary-emphasis border border-primary">Siswa</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/users/{{ $user->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/users/{{ $user->id }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
