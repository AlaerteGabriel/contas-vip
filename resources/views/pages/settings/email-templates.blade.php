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
        var ROUTE_DATATABLES = "{{ route('dashboard.config.email-templates.getDatatablesEmailTemplates') }}";
        var ROUTE_DELETE = "{{ route('dashboard.config.email-templates.ajaxDestroy') }}";
        var ROUTE_EDIT = "{{ route('dashboard.config.email-templates.ajaxEdit') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/js/templates/index.js') }}"></script>
@endpush

@section('content')
<div class="row">

    <!-- Tags Table -->
    <div class="col-lg-3 mb-4">
        <div class="card border-primary border-opacity-25" style="box-shadow: 0 5px 20px rgba(67, 97, 238, 0.05);">
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
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                        <tr>
                            <th class="ps-4">Campo (Referência)</th>
                            <th class="pe-4 text-end">Tag (Código)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-regular fa-user me-2"></i>Nome do Cliente</td>
                            <td class="pe-4 text-end"><code>{nome}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-regular fa-id-card me-2"></i>Username</td>
                            <td class="pe-4 text-end"><code>{username}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-regular fa-id-card me-2"></i>Saudação</td>
                            <td class="pe-4 text-end"><code>{saudacao}</code></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-light py-2 px-4 small fw-bold text-uppercase text-muted">Dados do Pedido / Serviço</td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-solid fa-calendar-day me-2"></i>Dias Contratados
                            </td>
                            <td class="pe-4 text-end"><code>{periodo}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-solid fa-box me-2"></i>Serviço Contratado
                            </td>
                            <td class="pe-4 text-end"><code>{servico}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-solid fa-key me-2"></i>Senha Atual</td>
                            <td class="pe-4 text-end"><code>{senhaatual}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-solid fa-calendar-day me-2"></i>Data de Vencimento</td>
                            <td class="pe-4 text-end"><code>{vencimento}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-solid fa-link me-2"></i>Link Renovação
                            </td>
                            <td class="pe-4 text-end"><code>{linkrenovPT}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted"><i class="fa-solid fa-link me-2"></i>URL do Serviço</td>
                            <td class="pe-4 text-end"><code>{linkserver}</code></td>
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

    <!-- Form Template -->
    <div class="col-lg-5">

        <x-alert />

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-envelope text-primary fs-4 me-3"></i> Cadastro de Template de E-mail</h5>
            </div>
            <div class="card-body">
                <form id="add" action="{{ route('dashboard.config.email-templates.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="id" name="id" value="">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="codigo" class="form-label">Código do Template</label>
                            <input type="text" name="te_codigo" class="form-control" id="codigo" placeholder="ex: EMAIL_BOAS_VINDAS" value="{{ old('te_codigo') }}" required>
                            <div class="form-text">Identificador único no sistema</div>
                        </div>
                        <div class="col-md-8">
                            <label for="assunto" class="form-label">Assunto do E-mail</label>
                            <input type="text" name="te_assunto" class="form-control" id="assunto" placeholder="Assunto" value="{{ old('te_assunto') }}" required>
                            <div class="form-text">Você pode usar tags no assunto</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label for="editor" class="form-label mb-0">Modelo (HTML)</label>
                        </div>
                        <textarea class="form-control font-monospace text-muted" name="te_modelo" id="editor" rows="18" placeholder="">{{ old('te_modelo') }}</textarea>
                        <div class="form-text mt-2 text-end">Código fonte HTML puro. Suporta CSS inline.</div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#previewModal"><i class="fa-regular fa-eye me-2"></i>Pré-visualizar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-2"></i>Salvar Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="mb-1 text-primary fw-bold d-flex align-items-center"><i class="fa-solid fa-envelope-circle-check me-2 fs-4"></i>Templates cadastrados</h5>
                <div class="d-flex">
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
                            <th>Código</th>
                            <th>Assunto</th>
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

<!-- Modal Pré-visualização -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light border-bottom border-muted border-opacity-25 pb-3">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="previewModalLabel">
                    <i class="fa-solid fa-envelope-open-text text-info fs-4 me-3"></i> Pré-visualização do Modelo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-secondary bg-opacity-10 d-flex justify-content-center">
                <!-- Email container wrapper -->
                <div class="w-100 preview-container bg-white shadow-sm border mt-4 mb-4" style="max-width: 800px; min-height: 500px; border-radius: 8px; overflow: hidden;">
                    <iframe id="previewIframe" style="width: 100%; height: 100%; min-height: 500px; border: none; display: block;"></iframe>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 pt-3">
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection
