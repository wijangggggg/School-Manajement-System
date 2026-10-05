@extends('layouts.app')
@section('title', 'Data Pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item text-secondary">Master Data</li>
    <li class="breadcrumb-item active fw-bold text-navy" aria-current="page">Data Pembayaran</li>
@endsection

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Pembayaran</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-success">Manajemen Pembayaran SPP</h5>
        <a href="/payments/create" class="btn btn-success btn-sm">+ Tambah Pembayaran</a>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Siswa</th>
                    <th>Bulan Pembayaran</th>
                    <!-- CLASS TEXT-END DARI BOOTSTRAP UNTUK RATA KANAN -->
                    <th class="text-end" width="20%">Nominal Bayar (Rp)</th>
                    <th class="text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $index => $payment)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $payment->student->full_name ?? 'Siswa Tidak Ditemukan' }}</td>
                        <td>{{ $payment->payment_month }}</td>
                        <!-- NUMBER_FORMAT PHP UNTUK FORMAT RUPIAH -->
                        <td class="text-end fw-bold">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <a href="/payments/{{ $payment->payment_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/payments/{{ $payment->payment_id }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
