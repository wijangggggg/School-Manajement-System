@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/students" class="text-decoration-none">Data Siswa</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-warning">Edit Data Siswa</h5>
            </div>

            <div class="card-body">
                <form action="/students/{{ $student->student_id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">NIS</label>
                        <!-- Atribut autofocus juga diterapkan di sini -->
                        <input type="text" name="nis" class="form-control" value="{{ $student->nis }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="{{ $student->full_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="" disabled>-- Pilih Jenis Kelamin --</option>

                            <!-- Logika selected-nya sedikit berbeda dari form create -->
                            <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>

                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="class_id" class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $item)
                                @php
                                    $idKelas = $item->class_id ?? $item->id;
                                @endphp
                                <option value="{{ $idKelas }}" {{ $student->class_id == $idKelas ? 'selected' : '' }}>
                                    {{ $item->class_name ?? $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <!-- Value diisi agar tanggal lama muncul saat mau diedit -->
                        <input type="date" name="date_of_birth" class="form-control" value="{{ $student->date_of_birth }}" required>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="/students" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning fw-bold text-dark">Update Data Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
