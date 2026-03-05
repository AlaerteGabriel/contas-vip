<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha - Contas VIP</title>
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
        <div class="position-absolute top-0 start-0 translate-middle"
            style="width: 400px; height: 400px; background: rgba(67, 97, 238, 0.1); border-radius: 50%; blur: 20px; filter: blur(40px);">
        </div>
        <div class="position-absolute bottom-0 end-0 translate-middle"
            style="width: 300px; height: 300px; background: rgba(76, 201, 240, 0.15); border-radius: 50%; filter: blur(50px);">
        </div>

        <div class="card auth-card position-relative z-1">
            <div class="auth-header pb-2">
                <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle"
                    style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </div>
                <h3>Redefinir Senha</h3>
                <p class="text-muted fw-medium px-4">Crie uma nova senha segura para acessar sua conta no sistema Contas VIP.</p>
            </div>
            <div class="auth-body">
                @if ($errors->any())
                    <div class="alert alert-danger mt-2 mb-4 rounded-3 border-danger border-opacity-25 small p-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('admin.password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $tokenPass }}">
                    <input type="hidden" name="us_email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="email" class="form-label text-dark small">E-mail Cadastrado</label>
                        <div class="input-group">
                            <span class="input-group-text"><i
                                    class="fa-solid fa-envelope text-success opacity-50"></i></span>
                            <input type="email" class="form-control" name="email" id="email" placeholder="seu@email.com.br" required readonly value="{{ old('email', $request->email ?? '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-dark small">Nova Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock text-success opacity-50"></i></span>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mínimo de 8 caracteres" required autocomplete="new-password" autofocus>
                        </div>
                        <div class="form-text small opacity-75">Use letras, números e símbolos especiais.</div>
                        @error('password')
                            <div class="small text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-dark small">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i
                                    class="fa-solid fa-lock text-success opacity-50"></i></span>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                                placeholder="Digite a senha novamente" required autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success text-white btn-lg w-100 shadow-sm"><i
                            class="fa-solid fa-floppy-disk ms-1 me-2"></i> Salvar Nova Senha</button>

                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Protegido com criptografia SSL 256 bits <i
                                class="fa-solid fa-shield-halved text-success ms-1"></i></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
