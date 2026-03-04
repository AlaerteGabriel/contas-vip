@extends('layouts.app')

@section('title', 'Templates de Email - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#"
                class="text-decoration-none text-muted">Configurações</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Templates de Email
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <!-- Form Template -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Cadastro de Template de E-mail</h5>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-list me-1"></i> Ver
                    Salvos</button>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.email-templates.store') ?? '#' }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="codigo" class="form-label">Código do Template</label>
                            <input type="text" name="codigo" class="form-control" id="codigo"
                                placeholder="ex: EMAIL_BOAS_VINDAS" required>
                            <div class="form-text">Identificador único no sistema</div>
                        </div>
                        <div class="col-md-8">
                            <label for="assunto" class="form-label">Assunto do E-mail</label>
                            <input type="text" name="assunto" class="form-control" id="assunto"
                                placeholder="Bem-vindo ao sistema, {cliente_nome}!" required>
                            <div class="form-text">Você pode usar tags no assunto</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label for="editor" class="form-label mb-0">Modelo (HTML)</label>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border py-1"
                                    title="Negrito"><i class="fa-solid fa-bold"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1"
                                    title="Itálico"><i class="fa-solid fa-italic"></i></button>
                                <button type="button" class="btn btn-sm btn-light border py-1"
                                    title="Inserir Link"><i class="fa-solid fa-link"></i></button>
                            </div>
                        </div>
                        <textarea class="form-control font-monospace text-muted" name="modelo_html" id="editor" rows="18"
                            placeholder="<!DOCTYPE html>&#10;<html>&#10;<head>&#10;...&#10;</head>&#10;<body>&#10;    <h1>Olá {cliente_nome},</h1>&#10;    <p>Sua conta foi criada com sucesso.</p>&#10;    <p>Acesse com o login: <strong>{login_acesso}</strong></p>&#10;</body>&#10;</html>"></textarea>
                        <div class="form-text mt-2 text-end">Código fonte HTML puro. Suporta CSS inline.</div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border">Cancelar</button>
                        <button type="button" class="btn btn-info text-white"><i
                                class="fa-regular fa-eye me-2"></i>Pré-visualizar</button>
                        <button type="submit" class="btn btn-primary"><i
                                class="fa-solid fa-save me-2"></i>Salvar Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tags Table -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100 border-primary border-opacity-25"
            style="box-shadow: 0 5px 20px rgba(67, 97, 238, 0.05);">
            <div class="card-header bg-primary bg-opacity-10 border-bottom-0 pb-0">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-code text-primary fs-4 me-3"></i>
                    <div>
                        <h5 class="mb-1 text-primary fw-bold">Tags Variáveis</h5>
                        <p class="mb-0 text-muted small">Clique na tag para copiá-la</p>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-3">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Campo (Referência)</th>
                                <th class="pe-4 text-end">Tag (Código)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-regular fa-user me-2"></i>Nome do
                                    Cliente</td>
                                <td class="pe-4 text-end"><code>{cliente_nome}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-regular fa-envelope me-2"></i>Email do
                                    Cliente</td>
                                <td class="pe-4 text-end"><code>{cliente_email}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i
                                        class="fa-solid fa-mobile-screen-button me-2"></i>Telefone/Whats</td>
                                <td class="pe-4 text-end"><code>{cliente_telefone}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-regular fa-id-card me-2"></i>Login de
                                    Acesso</td>
                                <td class="pe-4 text-end"><code>{login_acesso}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-key me-2"></i>Senha
                                    (Textoplano)</td>
                                <td class="pe-4 text-end"><code>{senha_temporaria}</code></td>
                            </tr>
                            <tr>
                                <td colspan="2"
                                    class="bg-light py-2 px-4 small fw-bold text-uppercase text-muted">Dados do
                                    Pedido / Serviço</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-box me-2"></i>Nome do Serviço
                                </td>
                                <td class="pe-4 text-end"><code>{servico_nome}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-barcode me-2"></i>Código do
                                    Pedido</td>
                                <td class="pe-4 text-end"><code>{pedido_id}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i
                                        class="fa-solid fa-money-bill-wave me-2"></i>Valor Fatura</td>
                                <td class="pe-4 text-end"><code>{fatura_valor}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i
                                        class="fa-solid fa-calendar-day me-2"></i>Vencimento</td>
                                <td class="pe-4 text-end"><code>{fatura_vencimento}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-link me-2"></i>Link Pagamento
                                </td>
                                <td class="pe-4 text-end"><code>{link_pagamento}</code></td>
                            </tr>
                            <tr>
                                <td colspan="2"
                                    class="bg-light py-2 px-4 small fw-bold text-uppercase text-muted">Sistema
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-globe me-2"></i>Nome da
                                    Empresa</td>
                                <td class="pe-4 text-end"><code>{empresa_nome}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-link me-2"></i>URL Painel
                                    Cliente</td>
                                <td class="pe-4 text-end"><code>{url_sistema}</code></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted"><i class="fa-solid fa-headset me-2"></i>Email
                                    Suporte</td>
                                <td class="pe-4 text-end"><code>{email_suporte}</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 pt-0 pb-3 text-center">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Substituições ocorrem
                    automaticamente no envio.</small>
            </div>
        </div>
    </div>
</div>
@endsection
