@extends('layouts.app')
@section('title', 'Data Kelas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Data Kelas</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-navy">Manajemen Data Kelas</h5>
        @if(auth()->user()->role == 'admin')
            <a href="/classes/create" class="btn btn-primary btn-sm">+ Tambah Kelas</a>
        @endif
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="text-center" width="15%">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($classes as $index => $class)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw">{{ $class->class_name }}</td>
                        <!-- Memanggil nama guru lewat relasi -->
                        <td>{{ $class->teacher ? $class->teacher->full_name : 'Belum Ditugaskan' }}</td>
                        @if(auth()->user()->role == 'admin')
                        <td class="text-center">
                            <a href="/classes/{{ $class->class_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/classes/{{ $class->class_id }}" method="POST" class="d-inline delete-form">
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
