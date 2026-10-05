@extends('layouts.app')
@section('title', 'Tambah Guru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/teachers" class="text-decoration-none">Data Guru</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Tambah Data</li>
@endsection

@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Input Data Guru Baru</h5>
        <form action="/teachers" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">NIP</label>
                <input type="text" name="nip" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">No HP</label>
                <input type="text" name="phone_number" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label>Jenis Kelamin</label>
                <select name="gender" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label fw-bold">Alamat Lengkap</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap guru..." required>{{ old('address') }}</textarea>

                <!-- Menampilkan pesan error jika alamat kosong saat disubmit -->
                @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Data Guru</button>
        </form>
    </div>
</div>
@endsection
