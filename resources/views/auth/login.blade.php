<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Rental Mobil</title>
    <link rel="stylesheet" href="{{ asset('css/style-admin.css') }}">
</head>
<body class="halaman-login">
    <div class="kartu-login-bagus">
        <div class="judul-login">Login Admin</div>
        <div class="sub-login">Masukkan email & password untuk masuk</div>

        @if(session('gagal'))
            <div class="alert-gagal">{{ session('gagal') }}</div>
        @endif

        <form method="POST" action="{{ route('login.proses') }}" class="form-login">
            @csrf

            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
            </div>

            <div style="position: relative;">
                <label>Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>

                <!-- ICON MATA -->
                <span onclick="togglePassword()" 
                      style="position:absolute; right:10px; top:38px; cursor:pointer;">
                    👁️
                </span>
            </div>

            <div class="baris-ingat">
                <input type="checkbox" name="ingat_saya" id="ingat_saya" value="1">
                <label for="ingat_saya" class="label-ingat">Ingat saya</label>
            </div>

            @if($errors->any())
                <div class="alert-gagal" style="margin-top:10px;">
                    @foreach($errors->all() as $e)
                        <div>- {{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <button class="btn-login-full" type="submit">Masuk</button>
        </form>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        function togglePassword() {
            const password = document.getElementById("password");

            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>
</body>
</html>