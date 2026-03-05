<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Contas VIP</title>
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
        <div class="position-absolute top-0 end-0 translate-middle"
            style="width: 400px; height: 400px; background: rgba(76, 201, 240, 0.1); border-radius: 50%; filter: blur(40px);">
        </div>
        <div class="position-absolute bottom-0 start-0 translate-middle"
            style="width: 300px; height: 300px; background: rgba(67, 97, 238, 0.15); border-radius: 50%; filter: blur(50px);">
        </div>

        <div class="card auth-card position-relative z-1">
            <div class="auth-header pb-2">
                <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle"
                    style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3>Recuperar Senha</h3>
                <p class="text-muted fw-medium px-4">Informe o e-mail cadastrado para receber as instruções de
                    recuperação de acesso.</p>
            </div>
            <div class="auth-body">
                @if (session('status'))
                    <div class="alert alert-success mt-2 mb-4 rounded-3 border-success border-opacity-25 small p-3 text-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 mb-2 d-block"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mt-2 mb-4 rounded-3 border-danger border-opacity-25 small p-3">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('admin.password.email') }}">
                    @csrf
                    @honeypot
                    <div id="formContent">
                        <div class="mb-4">
                            <label for="email" class="form-label text-dark small">E-mail Cadastrado</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="fa-solid fa-envelope text-info opacity-50"></i></span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="seu@email.com.br" required autocomplete="email" autofocus value="{{ old('email') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info text-white btn-lg w-100 mb-4 shadow"><i class="fa-regular fa-paper-plane ms-1 me-2"></i> Enviar Instruções </button>
                    </div>

                    <div class="text-center mt-3 pt-3 border-top border-opacity-10 border-dark">
                        <a href="{{ route('admin.login') }}"
                            class="text-decoration-none text-muted fw-bold d-inline-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;"><i class="fa-solid fa-arrow-left small"></i></div>
                            Voltar para o Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
