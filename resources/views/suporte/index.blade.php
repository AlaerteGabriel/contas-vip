<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suprote - Contas VIP</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-suporte.css') }}">
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
                <p class="text-muted fw-medium">Mudar cliente de conta</p>
            </div>
            <div class="auth-body">
                <!-- Laravel Login Form -->
                <form class="auth-form" method="POST" action="{{ route('suporte.altConta') }}">
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
                    <x-alert />
                    <div class="mb-4">
                        <label for="email" class="form-label text-dark small">E-mail do Cliente</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="fa-solid fa-envelope text-primary opacity-50"></i></span>
                            <input type="email" class="form-control" name="email" id="email" placeholder="seuemail@login.com" required autocomplete="email" autofocus value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="conta" class="form-label text-dark small mb-0">Código do Serviço</label>
                        <input type="text" class="form-control" name="conta" id="conta" required value="{{ old('conta') }}">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow-lg">Atualizar <i class="fa-solid fa-arrow-right ms-2"></i></button>

                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Protegido com criptografia SSL 256 bits <i class="fa-solid fa-shield-halved text-success ms-1"></i></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
