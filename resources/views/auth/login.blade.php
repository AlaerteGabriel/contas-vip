@extends('layouts.auth')

@section('title', 'Login - Carteira Financeira')

@section('content')
<div class="auth-container card border-0 shadow-lg rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-wallet2" style="font-size: 2.5rem;"></i>
            </div>
            <h4 class="fw-bold">Administração</h4>
            <p class="text-muted">Acesse sua conta para continuar</p>
        </div>

        <form action="{{ route('admin.login.logar') ?? '#' }}" method="POST">
            @csrf

            <x-alert />

            <div class="mb-3">
                <label for="email" class="form-label fw-medium">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control bg-light border-start-0 ps-0" name="login" id="email" value="{{ old('login') }}" required placeholder="seu@email.com" autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-medium">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control bg-light border-start-0 ps-0" name="pass" id="password" required placeholder="••••••••">
                </div>

                <div class="text-end mt-1">
                    <a href="{{ route('admin.password.request') ?? '#' }}" class="text-decoration-none small">Esqueceu a senha?</a>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2 fw-medium rounded-pill">Entrar na Conta</button>
            </div>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <p class="mb-0 text-muted">Não tem uma conta? <a href="#" class="text-decoration-none fw-semibold">Crie agora</a></p>
        </div>
    </div>
</div>
@endsection
