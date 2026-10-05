@extends('layouts.app')
@section('title', 'Data Guru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Data Guru</li>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
        <div class="mb-0">{{ session('success') }}</div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="background: transparent; border: none; font-size: 1.5rem; cursor: pointer;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary">Manajemen Data Guru</h5>
        @if(auth()->user()->role == 'admin')
            <a href="/teachers/create" class="btn btn-primary btn-sm">+ Tambah Guru</a>
        @endif
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    @if(Auth::user()->role === 'admin')
                        <th class="text-center" width="15%">NIP</th>
                    @endif
                    <th class="text-center" width="20%">Nama Lengkap</th>
                    <th class="text-center" width="15%">Jenis Kelamin</th>
                    <th class="text-center" width="15%">No HP</th>
                    <th class="text-center" width="20%">Alamat</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="text-center" width="15%">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($teachers as $index => $teacher)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        @if(Auth::user()->role === 'admin')
                            <td class="text-center">{{ $teacher->nip }}</td>
                        @endif
                        <td>{{ $teacher->full_name }}</td>
                        <td class="text-center">{{ $teacher->gender }}</td>
                        <td class="text-center">{{ $teacher->phone_number }}</td>
                        <td>{{ $teacher->address }}</td>
                        @if(auth()->user()->role == 'admin')
                        <td class="text-center">
                            <a href="/teachers/{{ $teacher->teacher_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/teachers/{{ $teacher->teacher_id }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
