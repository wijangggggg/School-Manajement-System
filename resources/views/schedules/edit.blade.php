@extends('layouts.app')

@section('title', 'Edit Jadwal Pelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/subjects" class="text-decoration-none">Mata Pelajaran & Jadwal</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Edit Jadwal</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-primary">Edit Jadwal Pelajaran</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('schedules.update', $schedule->schedule_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Pilihan Kelas -->
            <div class="mb-3">
                <label for="class_id" class="form-label">Kelas</label>
                <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->class_id }}" {{ (old('class_id', $schedule->class_id) == $class->class_id) ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Pilihan Mata Pelajaran -->
            <div class="mb-3">
                <label for="subject_name" class="form-label">Mata Pelajaran</label>
                <select name="subject_name" id="subject_name" class="form-select @error('subject_name') is-invalid @enderror" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->subject_name }}" {{ (old('subject_name', $schedule->subject_name) == $subject->subject_name) ? 'selected' : '' }}>
                            {{ $subject->subject_name }} ({{ $subject->subject_code }})
                        </option>
                    @endforeach
                </select>
                @error('subject_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Pilihan Guru Pengampu -->
            <div class="mb-3">
                <label for="teacher_id" class="form-label">Guru Pengampu</label>
                <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->teacher_id }}" {{ (old('teacher_id', $schedule->teacher_id) == $teacher->teacher_id) ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Pilihan Hari -->
            <div class="mb-3">
                <label for="day" class="form-label">Hari</label>
                <select name="day" id="day" class="form-select @error('day') is-invalid @enderror" required>
                    <option value="">-- Pilih Hari --</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                        <option value="{{ $hari }}" {{ (old('day', $schedule->day) == $hari) ? 'selected' : '' }}>
                            {{ $hari }}
                        </option>
                    @endforeach
                </select>
                @error('day')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Jam Mulai & Jam Selesai -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $schedule->start_time) }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }};</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $schedule->end_time) }}" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between mt-4">
                <a href="/subjects" class="btn btn-secondary px-4">Kembali</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
