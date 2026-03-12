@extends('layouts.app')

@push('styles')
    <link href="{{asset('assets/plugins/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .dataTables_info {
            padding: 1rem;
        }
        .dataTables_paginate {
            padding: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let TOKEN = '<?=csrf_token()?>';
        var ROUTE_DATATABLES = "{{ route('dashboard.smtp.getDatatablesSmtp') }}";
        var ROUTE_DELETE = "{{ route('dashboard.smtp.ajaxDestroy') }}";
        var ROUTE_EDIT = "{{ route('dashboard.smtp.ajaxSmtpEdit') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/js/smtp/index.js') }}"></script>
@endpush

@section('title', 'Servidor de Email - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Configurações</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Servidor Email (SMTP)</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">

    <div class="col-lg-5 mb-4">
        <x-alert />
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 position-relative z-1">
                <h5 class="mb-1 text-primary fw-bold d-flex align-items-center"><i class="fa-solid fa-envelope-circle-check me-2 fs-4"></i> Credenciais de Disparo (SMTP)
                </h5>
                <p class="text-muted small mb-0">Esta configuração será usada para envio de faturas, senhas e notificações de sistema aos clientes.</p>
            </div>
            <div class="card-body p-4 position-relative z-1">
                <div class="alert alert-info border-info border-opacity-25 bg-info bg-opacity-10 d-flex align-items-center mb-4" role="alert">
                    <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
                    <div>Recomendamos usar uma conta de e-mail autêntica para garantir que suas mensagens não
                        caiam na caixa de spam de seus clientes.
                    </div>
                </div>

                <form id="add" action="{{ route('dashboard.config.mail-server.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="row g-3 mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-dark fw-semibold">Remetente (Nome de Exibição)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-address-card text-muted"></i></span>
                                <input type="text" name="mail_from_name" class="form-control border-start-0 ps-0" placeholder="Ex: Contas VIP Sistema" value="{{ old('mail_from_name') }}" required>
                            </div>
                            <div class="form-text">Como o cliente verá quem enviou.</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-dark fw-semibold">E-mail Remetente</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-at text-muted"></i></span>
                                <input type="email" name="mail_from_address" class="form-control border-start-0 ps-0" placeholder="Ex: nao-responda@suaempresa.com" value="{{ old('mail_from_address') }}" required>
                            </div>
                            <div class="form-text">Qual e-mail aparecerá como origem.</div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Servidor Host (SMTP)</label>
                            <input type="text" name="mail_host" class="form-control" placeholder="mail.seudominio.com.br" value="{{ old('mail_host') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Porta SMTP</label>
                            <input type="number" name="mail_port" class="form-control" placeholder="465 ou 587" value="{{ old('mail_port') }}" required>
                        </div>
                        <div class="col-md-12 mt-3 mb-4">
                            <label class="form-label">Tipo de Criptografia</label>
                            <select name="mail_encryption" class="form-select">
                                <option value="none" {{ setting('mail_encryption') == 'none' ? 'selected="selected"' : '' }}>Nenhuma (Não Recomendado)</option>
                                <option value="tls" {{ setting('mail_encryption') == 'tls' ? 'selected="selected"' : '' }}>TLS (Normalmente Porta 587)</option>
                                <option value="ssl" {{ setting('mail_encryption') == 'ssl' ? 'selected="selected"' : '' }}>SSL (Normalmente Porta 465)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 bg-light p-3 rounded-3 border border-light">
                        <h6 class="fw-bold mb-2 text-dark small text-uppercase">Autenticação</h6>
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-muted small">Usuário SMTP</label>
                            <input type="text" name="mail_username" class="form-control bg-white" placeholder="Seu email de login"
                                value="{{ old('mail_username') }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-muted small">Senha SMTP</label>
                            <!-- Consider not pre-filling password in real app for security, this is just for presentation layout -->
                            <input type="text" name="mail_password" class="form-control bg-white" placeholder="••••••••••••"
                                value="{{ old('mail_password') }}" required>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="card bg-danger bg-opacity-10 border-danger border-opacity-25 shadow-none mt-2">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger fw-bold mb-1"><i class="fa-solid fa-check me-2"></i>Definir como Padrão?</h6>
                                        <p class="text-danger opacity-75 small mb-0">
                                            Ao marcar como padrão, o sistema irá utilizar este servidor para o envio de email.
                                        </p>
                                    </div>
                                    <div class="form-check form-switch fs-4 mb-0">
                                        <input class="form-check-input border-danger" type="checkbox" role="switch" id="padrao" name="padrao" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm w-100">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="mb-1 text-primary fw-bold d-flex align-items-center"><i class="fa-solid fa-envelope-circle-check me-2 fs-4"></i>Servidores Cadastrados</h5>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="busca" placeholder="Buscar...">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 yajra-datatable" style="margin-top: 0 !important;" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#ID</th>
                                <th>Login</th>
                                <th>Host</th>
                                <th>Padrão</th>
                                <th>Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
