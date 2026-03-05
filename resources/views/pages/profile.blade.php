@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Meu Perfil</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">

    <div class="col-lg-12 mb-4">
        <x-alert />
    </div>

    <!-- Informações do Perfil -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-4 pb-3 border-bottom-0">
                <h5 class="mb-1 fw-bold text-dark d-flex align-items-center">
                    <i class="fa-regular fa-id-badge text-primary fs-4 me-2"></i> Informações do Perfil
                </h5>
                <p class="text-muted small mb-0">Atualize as informações da sua conta e endereço de e-mail.</p>
            </div>
            <div class="card-body p-4 pt-2">

                <form method="POST" action="{{ route('dashboard.perfil.update') }}">
                    @csrf
                    <div class="mb-4 text-center">
                        <div class="position-relative d-inline-block">
                            <!-- Avatar dinâmico via name -->
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->us_nome ?? 'Admin') }}&background=4361ee&color=fff&size=120"
                                alt="Foto do Perfil" class="rounded-circle border border-4 border-white shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm border d-flex justify-content-center align-items-center" style="width: 30px; height: 30px; transform: translate(10%, 10%); cursor: help;" title="Imagem gerada automaticamente usando suas iniciais.">
                                <i class="fa-solid fa-camera small text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label text-dark fw-medium small">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" name="us_nome" id="name" required autofocus value="{{ old('us_nome', auth()->user()->us_nome ?? 'Administrador') }}">
                        </div>
                        @error('us_nome') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label text-dark fw-medium small">E-mail de Acesso</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-start-0 ps-0" name="us_email" id="email" required value="{{ old('us_email', auth()->user()->us_email ?? 'admin@contasvip.com.br') }}">
                        </div>
                        @error('us_email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-end border-top pt-4">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alterar Senha -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-4 pb-3 border-bottom-0">
                <h5 class="mb-1 fw-bold text-dark d-flex align-items-center">
                    <i class="fa-solid fa-shield-halved text-danger fs-4 me-2"></i> Alterar Senha
                </h5>
                <p class="text-muted small mb-0">Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter seguro.</p>
            </div>
            <div class="card-body p-4 pt-2">

                <form method="POST" action="{{ route('dashboard.password.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label text-dark fw-medium small">Senha Atual</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key opacity-50 text-dark"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" name="current_password" id="current_password" required autocomplete="current-password" placeholder="Digite a senha atual">
                        </div>
                        @error('current_password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-dark fw-medium small">Nova Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock opacity-50 text-danger"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" name="password" id="password" required autocomplete="new-password" placeholder="Nova senha segura">
                        </div>
                        @error('password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label text-dark fw-medium small">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock opacity-50 text-danger"></i></span>
                            <input type="password" class="form-control border-start-0 ps-0" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="Digite a senha novamente">
                        </div>
                    </div>

                    <div class="text-end border-top pt-4">
                        <button type="submit" class="btn btn-dark px-4 shadow-sm">
                            <i class="fa-solid fa-lock me-1"></i> Atualizar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
