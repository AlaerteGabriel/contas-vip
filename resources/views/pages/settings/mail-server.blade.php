@extends('layouts.app')

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
<div class="row justify-content-center">
    <div class="col-lg-8 mb-4">
        <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 position-relative z-1">
                <h5 class="mb-1 text-primary fw-bold d-flex align-items-center"><i
                        class="fa-solid fa-envelope-circle-check me-2 fs-4"></i> Credenciais de Disparo (SMTP)
                </h5>
                <p class="text-muted small mb-0">Esta configuração será usada para envio de faturas, senhas e
                    notificações de sistema aos clientes.</p>
            </div>
            <div class="card-body p-4 position-relative z-1">
                <div class="alert alert-info border-info border-opacity-25 bg-info bg-opacity-10 d-flex align-items-center mb-4"
                    role="alert">
                    <i class="fa-solid fa-circle-info fs-4 me-3 text-info"></i>
                    <div>Recomendamos usar uma conta de e-mail autêntica para garantir que suas mensagens não
                        caiam na caixa de spam de seus clientes.</div>
                </div>

                <form action="{{ route('settings.mail-server.store') ?? '#' }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">Remetente (Nome de Exibição)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fa-solid fa-address-card text-muted"></i></span>
                                <input type="text" name="mail_from_name" class="form-control border-start-0 ps-0"
                                    placeholder="Ex: Contas VIP Sistema" value="{{ config('mail.from.name', 'Sistema Contas VIP') }}" required>
                            </div>
                            <div class="form-text">Como o cliente verá quem enviou.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-semibold">E-mail Remetente</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fa-solid fa-at text-muted"></i></span>
                                <input type="email" name="mail_from_address" class="form-control border-start-0 ps-0"
                                    placeholder="Ex: nao-responda@suaempresa.com"
                                    value="{{ config('mail.from.address', 'contato@contasvip.com.br') }}" required>
                            </div>
                            <div class="form-text">Qual e-mail aparecerá como origem.</div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-4">
                    <h6 class="fw-bold mb-3 text-dark">Configurações de Conexão</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Servidor Host (SMTP)</label>
                            <input type="text" name="mail_host" class="form-control" placeholder="mail.seudominio.com.br"
                                value="{{ config('mail.mailers.smtp.host', 'smtp.hostinger.com.br') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Porta SMTP</label>
                            <input type="number" name="mail_port" class="form-control" placeholder="465 ou 587" value="{{ config('mail.mailers.smtp.port', '465') }}"
                                required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Tipo de Criptografia</label>
                            <select name="mail_encryption" class="form-select">
                                <option value="none" {{ config('mail.mailers.smtp.encryption') == 'none' ? 'selected' : '' }}>Nenhuma (Não Recomendado)</option>
                                <option value="tls" {{ config('mail.mailers.smtp.encryption') == 'tls' ? 'selected' : '' }}>TLS (Normalmente Porta 587)</option>
                                <option value="ssl" {{ config('mail.mailers.smtp.encryption', 'ssl') == 'ssl' ? 'selected' : '' }}>SSL (Normalmente Porta 465)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 bg-light p-3 rounded-3 border border-light">
                        <h6 class="fw-bold mb-2 text-dark small text-uppercase">Autenticação</h6>
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-muted small">Usuário SMTP</label>
                            <input type="text" name="mail_username" class="form-control bg-white" placeholder="Seu email de login"
                                value="{{ config('mail.mailers.smtp.username', 'contato@contasvip.com.br') }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label text-muted small">Senha SMTP</label>
                            <!-- Consider not pre-filling password in real app for security, this is just for presentation layout -->
                            <input type="password" name="mail_password" class="form-control bg-white" placeholder="••••••••••••"
                                value="{{ config('mail.mailers.smtp.password') ? '********' : '' }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <button type="button"
                            class="btn btn-outline-info bg-info bg-opacity-10 border-info border-opacity-25"
                            id="btnTestEmail">
                            <i class="fa-regular fa-paper-plane me-2"></i> Enviar E-mail de Teste
                        </button>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card bg-dark text-white shadow" style="border-radius: 20px;">
            <div class="card-body p-4 text-center">
                <div class="mx-auto bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;">
                    <i class="fa-brands fa-aws fs-1"></i>
                </div>
                <h5 class="fw-bold mb-3">Recomendação</h5>
                <p class="text-white-50 small mb-4">Para disparo de faturas e recibos nós recomendamos utilizar
                    o <strong>Amazon SES</strong> pelo baixo custo e alta taxa de entrega na caixa de entrada
                    dos provedores (Gmail, Outlook, etc).</p>
                <a href="https://aws.amazon.com/ses/" target="_blank"
                    class="btn btn-light btn-sm text-dark px-4 fw-bold rounded-pill">Saber Mais</a>
            </div>
        </div>
    </div>
</div>
@endsection
