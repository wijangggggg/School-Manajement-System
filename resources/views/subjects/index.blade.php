@extends('layouts.app')
@section('title', 'Data Mata Pelajaran & Jadwal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Mata Pelajaran</li>
@endsection

@section('content')

<!-- KARTU 1: MANAJEMEN MATA PELAJARAN (TABEL ATAS) -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-navy">Manajemen Mata Pelajaran</h5>
        @if(auth()->user()->role == 'admin')
            <a href="/subjects/create" class="btn btn-primary btn-sm">+ Tambah Mapel</a>
        @endif
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Mapel</th>
                    <th>Nama Mata Pelajaran</th>
                    @if(auth()->user()->role == 'admin')
                        <th class="text-center" width="15%">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $index => $subject)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-secondary">{{ $subject->subject_code }}</td>
                        <td>{{ $subject->subject_name }}</td>
                        @if(auth()->user()->role == 'admin')
                        <td class="text-center">
                            <a href="/subjects/{{ $subject->subject_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/subjects/{{ $subject->subject_id }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role == 'admin' ? 4 : 3 }}" class="text-center py-3 text-muted">Belum ada data mata pelajaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<!-- KARTU 2: JADWAL PELAJARAN (TABEL BAWAH) -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-navy">
            @if(auth()->user()->role == 'admin')
                Jadwal Pelajaran (Seluruh Kelas)
            @elseif(auth()->user()->role == 'guru' || auth()->user()->role == 'teacher')
                Jadwal Pelajaran Kelas {{ $namaKelasAmpuan ? $namaKelasAmpuan : 'Ampuan' }}
            @else
                Jadwal Pelajaran Kelas Saya
            @endif
        </h5>

        @if(auth()->user()->role == 'admin')
            <!-- Route ini nanti bisa Anda buat belakangan jika ingin admin bisa input jadwal via web -->
            <a href="/schedules/create" class="btn btn-primary btn-sm">+ Tambah Jadwal</a>
        @endif
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Hari</th>
                    <th>Jam Pelajaran</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengajar</th>
                    @if(auth()->user()->role == 'admin')
                        <th>Kelas</th>
                        <th class="text-center" width="15%">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $index => $schedule)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-bold text-secondary">{{ $schedule->day }}</td>
                        <td>
                            {{ date('H:i', strtotime($schedule->start_time)) }} -
                            {{ date('H:i', strtotime($schedule->end_time)) }}
                        </td>
                        <td>{{ $schedule->subject_name }}</td>
                        <td>{{ $schedule->teacher ? $schedule->teacher->full_name : '-' }}</td>

                        @if(auth()->user()->role == 'admin')
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $schedule->classroom ? $schedule->classroom->class_name : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="/schedules/{{ $schedule->schedule_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                <form action="/schedules/{{ $schedule->schedule_id }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role == 'admin' ? 7 : 5 }}" class="text-center py-3 text-muted">
                            Belum ada jadwal pelajaran yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- CDN SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. Toast Notifikasi CRUD (Tetap Pakai Animasi Opsi 3)
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight animate__faster'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight animate__faster'
                }
            });
        @endif

        // 2. Pop-up Konfirmasi Hapus (Kembali Standar Seperti Sebelumnya)
        document.addEventListener('submit', function (e) {
            if (e.target && e.target.classList.contains('delete-form')) {
                e.preventDefault();
                const form = e.target;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    heightAuto: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

    });
</script>
@endsection
