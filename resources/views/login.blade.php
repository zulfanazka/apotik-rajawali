<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LOGIN</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}" />
</head>

<body>
    <div class="wrapper">
        <div class="title">Login Form</div>
        <!-- Menambahkan route ke Laravel -->
        <form action="{{ route('loginaction') }}" method="POST">
            @csrf
            <div class="field">
                <input type="text" name="username" required />
                <label>Username</label>
            </div>
            <div class="field">
                <input type="password" name="password" required />
                <label>Password</label>
            </div>
            <!-- Menampilkan pesan error jika login gagal -->
            @if ($errors->has('login'))
                <div class="error-message">
                    {{ $errors->first('login') }}
                </div>
            @endif
            <div class="field">
                <input type="submit" value="Login" />
            </div>
        </form>
    </div>
</body>

</html>
