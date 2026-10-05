    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - School Management System</title>

    <!-- Link Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #273142 0%, #3b82f6 100%);
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Menggunakan min-height agar aman jika form panjang */
            margin: 0;
            padding: 40px 0; /* Memberi ruang atas bawah di layar kecil */
        }

        .register-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px; /* Sedikit lebih lebar dari form login */
        }

        .register-card h2 { text-align: center; margin-bottom: 20px; color: #333; font-weight: bold; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        .form-group input:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }

        .btn-register {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-register:hover { background-color: #0056b3; }
        .error-box { background-color: #ffe6e6; color: #d9534f; padding: 10px; border-radius: 5px; font-size: 14px; margin-bottom: 20px; text-align: center; border: 1px solid #d9534f; }
    </style>
</head>
<body>

    <div class="register-card">
        <h2>Buat Akun Baru</h2>

        <!-- Peringatan jika ada error validasi -->
        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email resmi" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Buat password (minimal 8 karakter)" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password" required>
            </div>

            <button type="submit" class="btn-register mb-2">Daftar Sekarang</button>

            <!-- Tautan kembali ke Login yang profesional -->
            <div class="text-center mt-4 pt-4 border-top">
                <span class="text-secondary small">Sudah memiliki hak akses sistem? </span>
                <br>
                <a href="/login" class="text-primary fw-bold text-decoration-none small">Kembali ke Halaman Login</a>
            </div>
        </form>
    </div>

</body>
</html>
