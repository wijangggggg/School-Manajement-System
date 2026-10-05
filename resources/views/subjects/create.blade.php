@extends('layouts.app')
@section('content')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/subjects" class="text-decoration-none">Mata Pelajaran</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Tambah Data</li>
@endsection

<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4 text-navy">Input Mata Pelajaran Baru</h5>
        <form action="/subjects" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Kode Mapel</label>
                <input type="text" name="subject_code" class="form-control" placeholder="Contoh: MAT-01" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Mata Pelajaran</label>
                <input type="text" name="subject_name" class="form-control" placeholder="Contoh: Matematika" required>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="/subjects" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Mapel</button>
            </div>
        </form>
    </div>
</div>
@endsection
