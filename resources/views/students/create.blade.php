@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/students" class="text-decoration-none">Data Siswa</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Tambah Siswa</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary">Tambah Data Siswa</h5>
            </div>

            <div class="card-body">
                <div class="alert alert-info py-2 small">
                    Data ini otomatis akan membuatkan akun login untuk siswa.
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Oops! Ada yang salah:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/students" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">NIS (Nomor Induk Siswa)</label>
                        <!-- Perhatikan atribut 'autofocus' di bawah ini -->
                        <input type="text" name="nis" class="form-control" required placeholder="Contoh: 10102023" autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Kelas</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">-- Silakan Pilih Kelas --</option>
                            @foreach ($classes as $kelas)
                                <option value="{{ $kelas->class_id }}">{{ $kelas->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" required placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>

                        <!-- Pesan Error jika lupa diisi -->
                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Siswa</label>
                        <input type="email" name="email" class="form-control" required placeholder="nis@student.sch.id">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Akun</label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="/students" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Data Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
