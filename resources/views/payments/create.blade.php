@extends('layouts.app')
@section('title', 'Tambah Pembayaran')
@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Input Pembayaran SPP</h5>
        <form action="/payments" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Siswa</label>
                <select name="student_id" class="form-select" required autofocus>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->student_id }}">{{ $student->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Bulan Pembayaran</label>
                <input type="text" name="payment_month" class="form-control" placeholder="Contoh: September 2026" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nominal Uang</label>
                <input type="number" name="amount" class="form-control" placeholder="Contoh: 150000 (tanpa titik)" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Simpan Pembayaran</button>
        </form>
    </div>
</div>
@endsection
