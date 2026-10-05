@extends('layouts.app')

@section('title', 'Data Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Data Siswa</li>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i> <strong>Upload Gagal!</strong> Pastikan file yang diunggah berformat Excel (.xlsx).
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <!-- 1. PERUBAHAN: Judul Dinamis -->
        <h5 class="mb-0 fw-bold text-primary">
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'guru' || Auth::user()->role === 'teacher')
                Manajemen Data Siswa
            @else
                Daftar Teman Sekelas
            @endif
        </h5>

        @if(Auth::user()->role === 'admin')
            <div>
                <a href="/students/create" class="btn btn-primary btn-sm">+ Tambah Siswa</a>
                <button type="button" class="btn btn-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-excel"></i> Import Excel
            </button>
            </div>
        @endif
    </div>

    <!-- Buka Pembungkus Animasi -->
    <div id="tabel-siswa-wrapper" style="transition: opacity 0.25s ease, transform 0.25s ease;">
        <div class="card-body p-0 table-responsive">

            <!-- FORM PENCARIAN TETAP ADA -->
            <form action="/students" method="GET" class="mb-4">
                <div class="d-flex flex-wrap justify-content align-items-center gap-2 ms-4 mt-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIS..." value="{{ request('search') }}" style="width: 600px;">
                    <select name="gender" class="form-select" style="width: 200px;">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="/students" class="btn btn-secondary px-4">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </form>

            <!-- 2. PERUBAHAN: PEMISAHAN TABEL VS KARTU -->
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'guru' || Auth::user()->role === 'teacher')

                <!-- TAMPILAN TABEL ASLI MILIKMU (UNTUK ADMIN) -->
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center fw-normal" width="5%">No</th>
                            <th class="fw-normal">NIS</th>
                            <th class="fw-normal">Nama Lengkap</th>
                            <th class="fw-normal text-center">Jenis Kelamin</th>
                            <th class="fw-normal text-center">Kelas</th>
                            <th class="fw-normal text-center">Angkatan</th>
                            <th class="fw-normal text-center">Tanggal Lahir</th>
                            @if(auth()->user()->role == 'admin')
                                <th class="fw-normal text-center" width="15%">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $index => $student)
                            <tr>
                                <td class="text-center">{{ $students->firstItem() + $loop->index }}</td>
                                <td>{{ $student->nis }}</td>
                                <td>{{ $student->full_name }}</td>

                                <td class="text-center">
                                    @if($student->gender == 'L')
                                        Laki-laki
                                    @elseif($student->gender == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-center">
                                    {{ $student->classRoom->class_name ?? $student->classRoom->name ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $student->angkatan ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="mx-auto" style="display: flex; justify-content: space-between; width: 150px;">
                                        <span>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d') }}</span>
                                        <span style="text-align: center; width: 85px;">{{ \Carbon\Carbon::parse($student->date_of_birth)->translatedFormat('F') }}</span>
                                        <span>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('Y') }}</span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="/students/{{ $student->student_id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="/students/{{ $student->student_id }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @else

                <!-- TAMPILAN KARTU/GRID BARU (UNTUK SISWA) -->
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 m-0 p-4 pt-0">
                    @forelse($students as $student)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 {{ Auth::user()->id == $student->user_id ? 'border-primary border-bottom border-3' : '' }}" style="background-color: #f8f9fc;">
                                <div class="card-body text-center d-flex flex-column align-items-center pt-4">

                                    <!-- Foto Profil Otomatis (Inisial Nama) -->
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&background=random&color=fff&size=90&rounded=true&bold=true"
                                        alt="Avatar {{ $student->full_name }}"
                                        class="mb-3 shadow-sm">

                                    <!-- Nama Siswa -->
                                    <h6 class="card-title fw-bold mb-1 text-dark">{{ $student->full_name }}</h6>

                                    <!-- Jenis Kelamin -->
                                    <small class="text-muted mb-3 d-block">
                                        @if($student->gender == 'L' || $student->gender == 'Laki-laki')
                                            <i class="fas fa-mars text-primary me-1"></i> Laki-laki
                                        @else
                                            <i class="fas fa-venus text-danger me-1"></i> Perempuan
                                        @endif
                                    </small>

                                    <!-- Badge "Ini Anda" untuk diri sendiri -->
                                    @if(Auth::user()->id == $student->user_id)
                                        <span class="badge bg-primary px-3 py-2 mt-auto rounded-pill">
                                            <i class="fas fa-user-check me-1"></i> Ini Anda
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 w-100">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data teman sekelas.</p>
                        </div>
                    @endforelse
                </div>

            @endif

            <!-- Pagination ikut dibungkus di dalam wrapper -->
            <div class="mt-3 px-4 py-2 border-top">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div> <!-- Penutup Tabel Wrapper yang benar -->
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Siswa dari Excel</h5>
                <button type="button" class="btn-close close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <!-- Penting: enctype="multipart/form-data" wajib ada untuk upload file -->
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">Pilih File Excel (.xlsx, .xls, .csv)</label>
                        <input class="form-control" type="file" id="file_excel" name="file_excel" required accept=".xlsx, .xls, .csv">
                    </div>
                    <div class="alert alert-info py-2 mb-0">
                        <small>
                            <strong>Format Kolom Excel:</strong><br>
                            Baris 1: Judul Kolom (diabaikan)<br>
                            Kolom A: NIS<br>
                            Kolom B: Nama Lengkap<br>
                            Kolom C: ID Kelas (Angka)<br>
                            Kolom D: Jenis Kelamin (Laki-laki / Perempuan)<br>
                            Kolom E: Tanggal Lahir (Format: DD-MM-YYYY)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- 3. PERUBAHAN: TAMBAHAN CDN SWEETALERT AGAR POP-UP HAPUS BISA MUNCUL -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('click', function (e) {
        // 1. Cari elemen link pagination yang diklik (menggunakan .pagination a agar lebih akurat)
        const pageLink = e.target.closest('.pagination a');

        // Jika yang diklik bukan tombol pagination (atau tombol yang tidak ada linknya), abaikan
        if (!pageLink || !pageLink.href) return;

        e.preventDefault(); // Mencegah browser loading ulang layar hingga putih
        const url = pageLink.href;
        const wrapper = document.getElementById('tabel-siswa-wrapper');

        // 2. Mulai Animasi Meredup & Turun (Fade-Out)
        wrapper.style.opacity = '0.2';
        wrapper.style.transform = 'translateY(15px)'; // Turun 15px

        // 3. Ambil data halaman 2 / halaman berikutnya
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('tabel-siswa-wrapper');

                // Tunggu 250 milidetik (sesuai durasi transisi di CSS) agar layar redup dulu
                setTimeout(() => {
                    if (newContent) {
                        // Ganti isi tabel dengan data yang baru
                        wrapper.innerHTML = newContent.innerHTML;
                    }

                    // --- KODE PENTING: Paksa browser membaca ulang perubahan DOM ---
                    void wrapper.offsetWidth;

                    // 4. Mulai Animasi Muncul Kembali (Fade-In)
                    wrapper.style.opacity = '1';
                    wrapper.style.transform = 'translateY(0)';

                    // Update URL di address bar (jadi ?page=2 dst)
                    window.history.pushState({}, '', url);
                }, 250);
            })
            .catch(error => {
                // Jika terjadi error pada Javascript, kembalikan ke sistem klik biasa
                window.location.href = url;
            });
    });
</script>
<script>
    // Event listener dipasang ke 'document' agar mengenali tombol baru walau halaman pindah via AJAX
    document.addEventListener('submit', function(e) {

        // Cek apakah yang disubmit adalah form dengan class 'delete-form'
        if (e.target && e.target.classList.contains('delete-form')) {
            e.preventDefault(); // Tahan dulu form agar tidak langsung terhapus

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data siswa ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik Ya, baru formnya benar-benar disubmit
                    e.target.submit();
                }
            });
        }

    });
</script>
@endsection
