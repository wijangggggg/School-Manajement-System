@extends('layouts.app')
@section('title', 'Edit Pembayaran')
@section('content')
<div class="card shadow-sm border-0 col-md-6 mx-auto">
    <div class="card-body">
        <h5 class="fw-bold mb-4 text-warning">Edit Pembayaran SPP</h5>

        <form action="/payments/{{ $payment->payment_id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Siswa</label>
                <select name="student_id" class="form-select" required autofocus>
                    @foreach($students as $student)
                        <!-- Logika ini otomatis memilih siswa yang benar -->
                        <option value="{{ $student->student_id }}" {{ $payment->student_id == $student->student_id ? 'selected' : '' }}>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Bulan Pembayaran</label>
                <input type="text" name="payment_month" class="form-control" value="{{ $payment->payment_month }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nominal Uang</label>
                <input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="/payments" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold text-dark">Update Pembayaran</button>
            </div>
        </form>
    </div>
</div>
@endsection
