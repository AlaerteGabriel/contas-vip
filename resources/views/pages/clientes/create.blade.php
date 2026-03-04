@extends('layouts.app')

@section('title', 'Cadastro de Cliente - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" class="text-decoration-none text-muted">Clientes</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Novo Cadastro</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('clientes.index') }}"
        class="btn btn-light rounded-circle shadow-sm p-2 me-3 d-flex justify-content-center align-items-center"
        style="width: 40px; height: 40px; transition: all 0.2s;" onmouseover="this.classList.add('bg-white')"
        onmouseout="this.classList.remove('bg-white')">
        <i class="fa-solid fa-arrow-left text-dark"></i>
    </a>
    <div>
        <h4 class="mb-0 fw-bold">Cadastro de Cliente</h4>
        <p class="text-muted small mb-0">Preencha os dados abaixo para registrar um novo usuário no sistema.</p>
    </div>
</div>

<div class="row">
    <div class="col-xl-9 col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <!-- Change action to route in Laravel -->
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="fw-bold text-primary mb-4 text-uppercase"
                        style="font-size: 0.8rem; letter-spacing: 1px;"><i
                            class="fa-regular fa-id-card me-2"></i>Informações Pessoais</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-7">
                            <label class="form-label text-dark fw-medium">Nome Completo <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ex: João da Silva" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-dark fw-medium">Usuário (Nick) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">@</span>
                                <input type="text" name="username" class="form-control border-start-0 ps-0"
                                    placeholder="joaosilva" required>
                            </div>
                            <div class="form-text">Como o cliente prefere ser chamado ou login do sistema.</div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-10 my-4">
                    <h6 class="fw-bold text-primary mb-4 text-uppercase"
                        style="font-size: 0.8rem; letter-spacing: 1px;"><i
                            class="fa-solid fa-address-book me-2"></i>Contato & Comunicação</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-medium">Email <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0"
                                    placeholder="joao@exemplo.com" required>
                            </div>
                            <div class="form-text">Email principal do cliente.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-medium">Email de Envio</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="fa-regular fa-paper-plane"></i></span>
                                <input type="email" name="billing_email" class="form-control border-start-0 ps-0"
                                    placeholder="cobranca@exemplo.com">
                            </div>
                            <div class="form-text">Usado para receber faturas ou alertas (opcional).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-medium">WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="fa-brands fa-whatsapp"></i></span>
                                <input type="text" name="whatsapp" class="form-control border-start-0 ps-0"
                                    placeholder="(11) 90000-0000">
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-10 my-4">
                    <h6 class="fw-bold text-primary mb-4 text-uppercase"
                        style="font-size: 0.8rem; letter-spacing: 1px;"><i
                            class="fa-solid fa-sliders me-2"></i>Status & Detalhes</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label text-dark fw-medium">Observações</label>
                            <textarea name="notes" class="form-control" rows="4"
                                placeholder="Adicione notas, preferências ou histórico rápido do cliente aqui..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <div
                                class="card bg-danger bg-opacity-10 border-danger border-opacity-25 shadow-none mt-2">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger fw-bold mb-1"><i
                                                class="fa-solid fa-ban me-2"></i>Status: Banido?</h6>
                                        <p class="text-danger opacity-75 small mb-0">Ao marcar como banido, o
                                            cliente perde acesso aos serviços e não recebe novos emails do
                                            sistema.</p>
                                    </div>
                                    <div class="form-check form-switch fs-4 mb-0">
                                        <input class="form-check-input border-danger" type="checkbox"
                                            role="switch" id="banidoSwitch" name="is_banned" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light fw-medium px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-medium px-5 shadow-sm"><i
                                class="fa-solid fa-check me-2"></i>Salvar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
