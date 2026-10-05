@extends('layouts.app')
@section('title', 'Tambah Kelas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/classes" class="text-decoration-none">Data Kelas</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Tambah Kelas</li>
@endsection

@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4 text-navy">Input Data Kelas Baru</h5>
        <form action="/classes" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Kelas</label>
                <input type="text" name="class_name" class="form-control" placeholder="Contoh: X IPA 1" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Wali Kelas</label>
                <select name="teacher_id" class="form-select">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->teacher_id }}">{{ $teacher->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="/classes" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>
@endsection
