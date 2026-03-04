<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Contas VIP</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <div class="auth-wrapper position-relative overflow-hidden">
        <!-- Abstract Shapes Background -->
        <div class="position-absolute top-0 start-0 translate-middle" style="width: 400px; height: 400px; background: rgba(67, 97, 238, 0.1); border-radius: 50%; blur: 20px; filter: blur(40px);"></div>
        <div class="position-absolute bottom-0 end-0 translate-middle" style="width: 300px; height: 300px; background: rgba(76, 201, 240, 0.15); border-radius: 50%; filter: blur(50px);"></div>

        <div class="card auth-card position-relative z-1">
            <div class="auth-header pb-2">
                <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <h3>Boas-vindas</h3>
                <p class="text-muted fw-medium">Acesse o sistema Contas VIP</p>
            </div>
            <div class="auth-body">
                <!-- Laravel Login Form -->
                <form class="auth-form" method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4 rounded-3 border-danger border-opacity-25 small p-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() AS $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="email" class="form-label text-dark small">E-mail de Acesso</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i
                                    class="fa-solid fa-envelope text-primary opacity-50"></i></span>
                            <input type="email" class="form-control" name="email" id="email" placeholder="seuemail@login.com" required autocomplete="email" autofocus value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="password" class="form-label text-dark small mb-0">Senha</label>
                            @if (Route::has('admin.password.request'))
                                <a href="{{ route('admin.password.request') }}" class="text-decoration-none small text-primary fw-bold"
                                    style="font-size: 0.8rem;">Esqueceu a senha?</a>
                            @endif
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i
                                    class="fa-solid fa-lock text-primary opacity-50"></i></span>
                            <input type="password" class="form-control border-end-0" name="password" id="password" placeholder="senha" required autocomplete="current-password">
                            <button class="btn btn-outline-light border border-start-0 text-muted bg-light" type="button" id="togglePassword">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input border-primary border-opacity-50" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-muted small" for="rememberMe">
                            Manter conectado
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow-lg">Entrar no Sistema <i class="fa-solid fa-arrow-right ms-2"></i></button>

                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Protegido com criptografia SSL 256 bits <i class="fa-solid fa-shield-halved text-success ms-1"></i></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            var passwordInput = document.getElementById('password');
            var icon = this.querySelector('i');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
