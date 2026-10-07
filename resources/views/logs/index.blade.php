@extends('layouts.app')
@section('title', 'Riwayat Data')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Riwayat Data</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-history me-2"></i> Log Aktivitas Sistem</h5>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="20%">Time</th>
                    <th class="text-center" width="13%">Role</th>
                    <th class="text-center" width="13%">Activity</th>
                    <th class="text-center">Description</th>    
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $index => $log)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <!-- Format waktu menjadi lebih mudah dibaca -->
                        <td class="text-center">{{ $log->created_at->format('d M Y - H:i:s') }} WIB</td>

                        <!-- Cek jika user ada, tampilkan namanya. Jika tidak, tampilkan 'Sistem' -->
                        <td class="text-center">{{ $log->user ? $log->user->name : 'Sistem' }}</td>

                        <td class="text-center">
                            @php
                                $badgeClass = 'bg-secondary-subtle border border-secondary text-secondary-emphasis';
                                $activityText = strtolower($log->activity);

                                if (str_contains($activityText, 'tambah')) {
                                    // Biru transparan, border biru, teks biru gelap
                                    $badgeClass = 'bg-primary-subtle text-primary-emphasis';
                                } elseif (str_contains($activityText, 'edit')) {
                                    // Kuning transparan, border kuning, teks hitam semu kuning
                                    $badgeClass = 'bg-warning-subtle text-warning-emphasis';
                                } elseif (str_contains($activityText, 'hapus')) {
                                    // Merah transparan, border merah, teks merah gelap
                                    $badgeClass = 'bg-danger-subtle text-danger-emphasis';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }} py-1" style="min-width: 140px; font-size: 12px; font-weight: 600;">
                                {{ $log->activity }}
                            </span>
                        </td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
