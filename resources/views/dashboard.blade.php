@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active text-navy" aria-current="page">Dashboard</li>
@endsection

@section('content')
<!-- 1. BAGIAN ATAS: 4 KOTAK RINGKASAN -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card text-center border-primary shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">TOTAL SISWA</h5>
                <h1 class="display-4 fw-bold text-primary">{{ $total_students ?? 0 }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-center border-success shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">TOTAL GURU</h5>
                <h1 class="display-4 fw-bold text-success">{{ $total_teachers ?? 0 }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-center border-warning shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">TOTAL KELAS</h5>
                <h1 class="display-4 fw-bold text-warning">{{ $total_classes ?? 0 }}</h1>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-center border-info shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-muted">MATA PELAJARAN</h5>
                <h1 class="display-4 fw-bold text-info">{{ $total_subjects ?? 0 }}</h1>
            </div>
        </div>
    </div>
</div>

<!-- 2. BAGIAN BAWAH: 2 KOLOM GRAFIK -->
<div class="row mb-4">
    <!-- Kolom Kiri: Grafik Garis (Lebar 8/12) -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-muted">Statistik Login (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <!-- Kanvas Grafik Kiri dibungkus div dengan tinggi pasti -->
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="loginChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Grafik Donat (Lebar 4/12) -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-muted">Persentase Pengguna</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <!-- Kanvas Grafik Kanan dibungkus div dengan tinggi pasti -->
                <div style="position: relative; height: 220px; width: 100%;">
                    <canvas id="donutChart"></canvas>
                </div>

                <!-- Keterangan Angka di Bawah Grafik -->
                <div class="mt-4 text-center d-flex justify-content-around w-100 fw-bold">
                    <div>
                        <span style="color: #4e73df;">{{ $persen_siswa ?? 0 }}%</span><br>
                        <small class="text-muted fw-normal">Siswa</small>
                    </div>
                    <div>
                        <span style="color: #1cc88a;">{{ $persen_guru ?? 0 }}%</span><br>
                        <small class="text-muted fw-normal">Guru</small>
                    </div>
                    <div>
                        <span style="color: #f6c23e;">{{ $persen_admin ?? 0 }}%</span><br>
                        <small class="text-muted fw-normal">Admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. SCRIPT CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Siapkan data asli dari Laravel
        const labelsLogin = {!! json_encode($chartLabels ?? []) !!};
        const dataSiswa   = {!! json_encode($chartDataSiswa ?? []) !!};
        const dataGuru    = {!! json_encode($chartDataGuru ?? []) !!};
        const dataAdmin   = {!! json_encode($chartDataAdmin ?? []) !!};
        const dataDonut   = [
            {{ $total_students ?? 0 }},
            {{ $total_teachers ?? 0 }},
            {{ $total_admins ?? 0 }}
        ];

        // --- 1. BUAT GRAFIK KIRI (AWALNYA DIISI ANGKA 0 SEMUA) ---
        const ctxLogin = document.getElementById('loginChart').getContext('2d');
        const loginChart = new Chart(ctxLogin, {
            type: 'bar',
            data: {
                labels: labelsLogin,
                datasets: [
                    {
                        label: 'Siswa',
                        data: dataSiswa.map(() => 0), // Mulai dari 0
                        backgroundColor: '#1e3a8a',
                        borderWidth: 1
                    },
                    {
                        label: 'Guru',
                        data: dataGuru.map(() => 0), // Mulai dari 0
                        backgroundColor: '#881337',
                        borderWidth: 1
                    },
                    {
                        label: 'Admin',
                        data: dataAdmin.map(() => 0), // Mulai dari 0
                        backgroundColor: '#0f766e',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                devicePixelRatio: 3,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#e5e7eb' }
                    }
                }
            }
        });

        // --- 2. BUAT GRAFIK KANAN (AWALNYA DIISI ANGKA 0 SEMUA) ---
        const ctxDonut = document.getElementById('donutChart').getContext('2d');
        const donutChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ["Siswa", "Guru", "Admin"],
                datasets: [{
                    data: [0, 0, 0], // Mulai dari 0 agar berputar dari awal
                    backgroundColor: ['#1e3a8a', '#1cc88a', '#f6c23e'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#dda20a'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                devicePixelRatio: 3,
                cutout: '75%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // --- 3. PICU EFEK ANIMASI SETELAH LAYAR TAMPIL PENUH ---
        setTimeout(function() {
            // Masukkan data asli ke Bar Chart lalu gerakkan ke atas
            loginChart.data.datasets[0].data = dataSiswa;
            loginChart.data.datasets[1].data = dataGuru;
            loginChart.data.datasets[2].data = dataAdmin;
            loginChart.update();

            // Masukkan data asli ke Donut Chart lalu putar
            donutChart.data.datasets[0].data = dataDonut;
            donutChart.update();
        }, 200);
    });
</script>
@endsection
