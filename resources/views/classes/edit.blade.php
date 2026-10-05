@extends('layouts.app')
@section('title', 'Edit Kelas')

@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4 text-warning">Edit Data Kelas</h5>

        <form action="/classes/{{ $class->class_id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Kelas</label>
                <input type="text" name="class_name" class="form-control" value="{{ $class->class_name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Wali Kelas</label>
                <select name="teacher_id" class="form-select">
                    <option value="">-- Belum Ada Wali Kelas --</option>
                    @foreach($teachers as $teacher)
                        <!-- Logika ini otomatis memilih guru yang sebelumnya sudah diset -->
                        <option value="{{ $teacher->teacher_id }}" {{ $class->teacher_id == $teacher->teacher_id ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/classes" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold text-dark">Update Kelas</button>
            </div>
        </form>
    </div>
</div>
@endsection
