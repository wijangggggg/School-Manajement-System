<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Sekolah')</title>
    <link rel="icon" href="{{ asset('images/Logo ex Alfatah.png') }}" type="image/png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f4f5f7;
            overflow-x: hidden;
        }

        /* Animasi Transisi Halus */
        .sidebar, .main-content {
            transition: all 0.3s ease-in-out;
        }

        /* Gaya Sidebar */
        .sidebar {
            background-color: #0a192f;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        /* State ketika Sidebar disembunyikan */
        .sidebar.collapsed {
            left: -250px;
        }

        .sidebar .nav-link {
            color: #aeb2b7;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 4px 15px;
            font-size: 15px;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #313d4f;
            color: #ffffff;
        }

        .sidebar .nav-link.active {
            border-left: 4px solid #3b82f6;
            border-radius: 0 5px 5px 0;
        }

        /* Area Konten Utama */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        /* State ketika Konten Utama melebar (karena sidebar disembunyikan) */
        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }

        /* Header Atas (Tempat Tombol Toggle) */
        .top-header {
            background: linear-gradient(135deg, #273142 0%, #3b82f6 100%);
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            margin-bottom: 30px;
            border-top: 4px solid #e74c3c;
        }
    </style>
</head>
<body class="d-flex">

    <!-- BAGIAN SIDEBAR (KIRI) -->
    <!-- Tambahkan ID 'sidebar' agar bisa dipanggil oleh JavaScript -->
    <div class="sidebar d-flex flex-column py-4" id="sidebar">
        <!-- BAGIAN LOGO & NAMA APLIKASI -->
        <div class="d-flex align-items-center mb-4 px-4 text-white text-decoration-none border-bottom border-secondary pb-4 mx-2" style="border-color: #313d4f !important;">
            <!-- Menampilkan logo ex Alfatah dengan latar putih agar jelas -->
            <img src="{{ asset('images/Logo ex Alfatah.png') }}" alt="Logo Sekolah" width="70" height="70" class="rounded bg-white p-1 me-3 shadow-sm">

            <!-- Teks Nama Aplikasi -->
            <div class="d-flex flex-column">
                <span class="fs-6" style="letter-spacing: 0.5px;">AL - FATAH Connect</span>
                <span class="text-info" style="font-size: 13px; font-weight: 500;">Sekolah</span>
            </div>
        </div>

        <ul class="nav flex-column mb-auto mt-3">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link d-flex align-items-center {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt fa-fw me-3"></i> Dashboard
                </a>
            </li>

            <li class="nav-item mt-3 mb-1 px-4">
                <small class="text-secondary fw-bold" style="font-size: 11px; letter-spacing: 1px;">MASTER DATA</small>
            </li>

            @if(auth()->user()->role == 'admin')
            <li class="nav-item">
                <a href="/users" class="nav-link d-flex align-items-center {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fas fa-users fa-fw me-3"></i> Data Pengguna
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="/students" class="nav-link d-flex align-items-center {{ request()->is('students*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate fa-fw me-3"></i> Data Siswa
                </a>
            </li>
            <li class="nav-item">
                <a href="/teachers" class="nav-link d-flex align-items-center {{ request()->is('teachers*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher fa-fw me-3"></i> Data Guru
                </a>
            </li>
            <li class="nav-item">
                <a href="/classes" class="nav-link d-flex align-items-center {{ request()->is('classes*') ? 'active' : '' }}">
                    <i class="fas fa-school fa-fw me-3"></i> Data Kelas
                </a>
            </li>

            <li class="nav-item">
                <a href="/subjects" class="nav-link d-flex align-items-center {{ request()->is('subjects*') ? 'active' : '' }}">
                    <i class="fas fa-book fa-fw me-3"></i> Mata Pelajaran
                </a>
            </li>
            @if (auth()->check() && strtolower(trim(auth()->user()->role)) == 'admin')
                <li class="nav-item mt-3 mb-1 px-4">
                    <small class="text-secondary fw-bold" style="font-size: 11px; letter-spacing: 1px;">PENGATURAN</small>
                </li>
                <li class="nav-item">
                    <a href="/riwayat-data" class="nav-link d-flex align-items-center {{ request()->is('riwayat-data*') ? 'active' : '' }}">
                        <i class="fas fa-history fa-fw me-3"></i> Riwayat Data
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- BAGIAN KONTEN UTAMA (KANAN) -->
    <div class="main-content" id="mainContent">

        <!-- Top Header (Baru) -->
        <div class="top-header d-flex justify-content-between align-items-center sticky-top z-3" style="top: 0;">
            <!-- Tombol Hamburger -->
            <button class="btn border-0 shadow-none" id="toggleBtn">
                <i class="fas fa-bars text-white fs-5"></i>
            </button>

            <!-- Identitas User (Opsional, agar header tidak kosong) -->
            <!-- Identitas User Dropdown -->
            <button class="btn btn-transparent text-white border-0 shadow-none d-flex align-items-center" type="button" data-bs-toggle="modal" data-bs-target="#profileModal">
                <i class="fas fa-user-circle fs-3 me-2"></i>
                {{ Auth::check() ? Auth::user()->name : 'Admin' }}
            </button>
        </div>

        <!-- Area khusus untuk isi konten (Tabel dll) -->
        <div class="px-4 px-md-5 pb-5">
            <!-- Wadah Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb" style="font-size: 14px;">
                    <!-- Teks breadcrumb akan di-inject dari halaman masing-masing -->
                    @yield('breadcrumb')
                </ol>
            </nav>
            @yield('content')
        </div>
    </div>

    <!-- Script Logika Animasi Toggle & SweetAlert -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // 1. Logika Toggle Sidebar
            const toggleBtn = document.getElementById('toggleBtn');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });

            // 2. Logika Global SweetAlert
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus Data!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Modal Profil & Logout -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" style="margin-top: 70px; margin-right: 25px; margin-left: auto;">
            <div class="modal-content border-0 shadow-lg">

                <!-- Bagian atas modal dengan warna tema -->
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #273142 0%, #3b82f6 100%);">
                    <h5 class="modal-title fs-6" id="profileModalLabel">Profil Pengguna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Bagian tengah: Info Biodata -->
                <div class="modal-body text-center py-4">
                    <i class="fas fa-user-circle text-secondary mb-3" style="font-size: 5rem;"></i>
                    <h5 class="mb-1 text-dark">{{ Auth::check() ? Auth::user()->name : 'Admin User' }}</h5>
                    <p class="text-muted mb-2 small">{{ Auth::check() ? Auth::user()->email : 'admin@admin.sch.id' }}</p>

                    <!-- Label Role -->
                    <span class="badge bg-primary px-3 py-2 text-uppercase rounded-pill" style="font-size: 0.75rem;">
                        {{ Auth::check() ? Auth::user()->role : 'Admin' }}
                    </span>
                </div>

                <!-- Bagian bawah: Tombol Tutup dan Logout -->
                <div class="modal-footer bg-light border-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>

                    <!-- Form Logout -->
                    <form action="/logout" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm px-3">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
