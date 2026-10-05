@extends('layouts.app')
@section('title', 'Edit Guru')

@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4 text-warning">Edit Data Guru</h5>

        <form action="/teachers/{{ $teacher->teacher_id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">NIP</label>
                <input type="text" name="nip" class="form-control" value="{{ $teacher->nip }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" value="{{ $teacher->full_name }}" required>
            </div>
            
            <div class="form-group mb-3">
                <label>Jenis Kelamin</label>
                <select name="gender" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ $teacher->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $teacher->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">No HP</label>
                <input type="text" name="phone_number" class="form-control" value="{{ $teacher->phone_number }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Alamat</label>
                <textarea name="address" class="form-control" rows="3">{{ $teacher->address }}</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/teachers" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold text-dark">Update Data Guru</button>
            </div>
        </form>
    </div>
</div>
@endsection
