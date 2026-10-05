@extends('layouts.app') <!-- Sesuaikan dengan nama layout utama Anda jika berbeda, misal: layouts.main -->

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">

        <!-- HEADER KARTU -->
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                @if(Auth::user()->role === 'admin')
                    Manajemen Jadwal Pelajaran
                @elseif(Auth::user()->role === 'guru' || Auth::user()->role === 'teacher')
                    Jadwal Pelajaran Kelas {{ $namaKelasAmpuan ? $namaKelasAmpuan : 'Ampuan' }}
                @else
                    Jadwal Pelajaran Kelas Saya
                @endif
            </h5>

            <!-- Tombol Tambah Hanya untuk Admin -->
            @if(Auth::user()->role === 'admin')
                <a href="#" class="btn btn-primary btn-sm">
                    + Tambah Jadwal
                </a>
            @endif
        </div>

        <!-- ISI KARTU / TABEL -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Jam Pelajaran</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengajar</th>

                            <!-- Kolom Tambahan Hanya untuk Admin -->
                            @if(Auth::user()->role === 'admin')
                                <th>Kelas</th>
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $schedule)
                            <tr>
                                <td class="text-center">{{ $schedules->firstItem() + $index }}</td>
                                <td class="text-center fw-bold">{{ $schedule->day }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </td>
                                <td>{{ $schedule->subject_name }}</td>
                                <td>{{ $schedule->teacher ? $schedule->teacher->full_name : '-' }}</td>

                                <!-- Data Tambahan Hanya untuk Admin -->
                                @if(Auth::user()->role === 'admin')
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            {{ $schedule->classroom ? $schedule->classroom->class_name : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Edit & Hapus (Nanti bisa diaktifkan routenya) -->
                                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <!-- Jika data masih kosong -->
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'admin' ? 7 : 5 }}" class="text-center text-muted py-4">
                                    Belum ada jadwal pelajaran untuk kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Menampilkan Navigasi Halaman (Pagination) -->
            <div class="d-flex justify-content-end mt-3">
                {{ $schedules->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
