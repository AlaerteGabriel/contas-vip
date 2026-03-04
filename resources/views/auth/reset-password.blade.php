@extends('layouts.auth')

@section('title', 'Redefinir Senha - Carteira Financeira')

@section('content')
<div class="auth-container card border-0 shadow-lg rounded-4">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-key" style="font-size: 2.5rem;"></i>
            </div>
            <h4 class="fw-bold">Redefinir Senha</h4>
            <p class="text-muted">Crie uma nova senha para sua conta</p>
        </div>

        <form action="{{ route('carteira.password.store') ?? '#' }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $tokenPass }}">
            <input type="hidden" name="us_email" value="{{ $email }}">

            <div class="mb-3">
                <label for="password" class="form-label fw-medium">Nova Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control bg-light border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" id="password" required placeholder="••••••••" autofocus>
                </div>
                @error('password')
                    <div class="small text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-medium">Confirmar Nova Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-check-circle text-muted"></i></span>
                    <input type="password" class="form-control bg-light border-start-0 ps-0" name="password_confirmation" id="password_confirmation" required placeholder="••••••••">
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2 fw-medium rounded-pill">Salvar Nova Senha</button>
            </div>
        </form>
    </div>
</div>
@endsection
