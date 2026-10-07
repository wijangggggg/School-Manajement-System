@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item active text-navy" aria-current="page">Data Pengguna</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-navy">Manajemen Akun Pengguna</h5>
        <a href="/users/create" class="btn btn-primary btn-sm">+ Tambah Pengguna</a>
    </div>

    <!-- FORM PENCARIAN -->
    <form action="{{ route('users.index') }}" method="GET" class="px-4 pt-3 mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <div style="min-width: 280px;">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama, Email, atau Role..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Cari
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-sync-alt me-1"></i> Reset
            </a>
        </div>
    </form>

    <!-- WRAPPER TABEL DENGAN ANIMASI TRANSISI -->
    <div id="tabel-user-wrapper" style="transition: opacity 0.25s ease, transform 0.25s ease;">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="30%">Nama Pengguna</th>
                        <th class="text-center">Email</th>
                        <th class="text-center" width="30%">Role / Hak Akses</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $users->firstItem() + $index }}</td>
                            <td class="fw">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @if($user->role == 'admin')
                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger">Admin</span>
                                @elseif($user->role == 'teacher')
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success">Guru</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary-emphasis border border-primary">Siswa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/users/{{ $user->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                <form action="/users/{{ $user->id }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 px-4 mb-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- SCRIPT ANIMASI SMOOTH AJAX PAGINATION -->
<script>
    document.addEventListener('click', function (e) {
        // Cari elemen tombol pagination
        const pageLink = e.target.closest('.pagination a');

        if (!pageLink || !pageLink.href) return;

        e.preventDefault();
        const url = pageLink.href;
        const wrapper = document.getElementById('tabel-user-wrapper');

        if (!wrapper) return;

        // 1. Mulai Animasi Meredup & Turun (Fade-Out)
        wrapper.style.opacity = '0.2';
        wrapper.style.transform = 'translateY(15px)';

        // 2. Ambil data halaman berikutnya via Fetch AJAX
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('tabel-user-wrapper');

                setTimeout(() => {
                    if (newContent) {
                        wrapper.innerHTML = newContent.innerHTML;
                    }

                    // Force repaint browser
                    void wrapper.offsetWidth;

                    // 3. Mulai Animasi Muncul Kembali (Fade-In)
                    wrapper.style.opacity = '1';
                    wrapper.style.transform = 'translateY(0)';

                    // Update URL di address bar
                    window.history.pushState({}, '', url);
                }, 250);
            })
            .catch(error => {
                window.location.href = url;
            });
    });
</script>
@endsection
