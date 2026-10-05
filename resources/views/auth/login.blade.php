<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Management System</title>
    <link rel="icon" href="{{ asset('images/Logo ex Alfatah.png') }}" type="image/png">

    <!-- Link Bootstrap agar class teks di bawah (text-center, mt-5, dll) bisa berfungsi rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- BAGIAN BACKGROUND YANG DIUBAH MENJADI GRADIEN BIRU --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #273142 0%, #3b82f6 100%); /* Ini gradiennya */
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* --- BAGIAN KOTAK LOGIN YANG DIBUAT LEBIH ELEGAN --- */
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px; /* Sudut lebih melengkung */
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); /* Bayangan lebih lembut dan luas */
            width: 100%;
            max-width: 400px; /* Sedikit dilebarkan agar teks bawah tidak terlalu sesak */
        }

        /* Pengaturan untuk logo sekolah */
        .logo-sekolah {
            width: 130px; /* Atur angka ini untuk membesarkan/mengecilkan logo */
            height: auto;
            object-fit: contain;
        }

        .login-card h2 { text-align: center; margin-bottom: 20px; color: #333; font-weight: bold; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        .form-group input:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
        .btn-login { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: 0.3s; }
        .btn-login:hover { background-color: #0056b3; }
        .error-box { background-color: #ffe6e6; color: #d9534f; padding: 10px; border-radius: 5px; font-size: 14px; margin-bottom: 20px; text-align: center; border: 1px solid #d9534f; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('images/Logo ex Alfatah.png') }}" alt="Logo sekolah" class="logo-sekolah">
        </div>
        <h2>Login Sistem</h2>

        <!-- Peringatan jika email/password salah -->
        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <!-- Tambahkan id="password" pada input ini -->
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <!-- Tombol untuk toggle mata -->
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <hr class="text-secondary my-4">
            <button type="submit" class="btn-login mt-1 mb-2">Masuk ke Dasbor</button>
        </form>
    </div>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Ubah tipe input dari 'password' ke 'text' atau sebaliknya
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ubah ikon mata terbuka / tertutup
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
