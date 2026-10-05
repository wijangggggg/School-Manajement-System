@extends('layouts.app')
@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-navy">Tambah Jadwal Pelajaran</h5>
    </div>
    <div class="card-body">
        <form action="/schedules" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" name="subject_name" class="form-control" required placeholder="Misal: Matematika Dasar">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pilih Kelas</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Guru Pengajar (Opsional)</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">-- Kosong / Belum Ada --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->teacher_id }}">{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hari</label>
                    <select name="day" class="form-select" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            <a href="/subjects" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
