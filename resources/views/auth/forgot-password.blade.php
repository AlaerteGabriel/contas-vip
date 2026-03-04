@extends('layouts.auth')

@section('title', 'Recuperar Senha - Carteira Financeira')

@section('content')
<div class="auth-container card border-0 shadow-lg rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-shield-lock" style="font-size: 2.5rem;"></i>
            </div>
            <h4 class="fw-bold">Recuperar Senha</h4>
            <p class="text-muted">Informe seu e-mail para receber um link de redefinição de senha</p>
        </div>

        <x-alert />

        <form action="{{ route('admin.password.email') ?? '#' }}" method="POST">
            @csrf
            @honeypot
            <div class="mb-4">
                <label for="email" class="form-label fw-medium">E-mail Cadastrado</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control bg-light border-start-0 ps-0" name="email" id="email" value="{{ old('email') }}" required placeholder="seu@email.com" autofocus>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning py-2 fw-medium rounded-pill text-dark border-0" style="background: linear-gradient(135deg, #ffc107, #ffca2c);">Enviar Link de Recuperação</button>
            </div>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <p class="mb-0 text-muted">Lembrou sua senha? <a href="{{ route('admin.login') ?? '#' }}" class="text-decoration-none fw-semibold text-warning">Voltar ao login</a></p>
        </div>
    </div>
</div>
@endsection
