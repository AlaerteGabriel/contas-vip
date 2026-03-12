@extends('layouts.app')

@section('title', 'Cadastro de Cliente - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contas.create') }}" class="text-decoration-none text-muted">Contas de Serviços</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Novo Cadastro</li>
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
        var ROUTE_DATATABLES = "{{ route('dashboard.contas.getDatatables') }}";
        var ROUTE_DELETE = "{{ route('dashboard.contas.ajaxDestroy') }}";
        var ROUTE_EDIT = "{{ route('dashboard.contas.ajaxEdit') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/js/contas/index.js') }}"></script>
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('dashboard.index') }}"
        class="btn btn-light rounded-circle shadow-sm p-2 me-3 d-flex justify-content-center align-items-center"
        style="width: 40px; height: 40px; transition: all 0.2s;" onmouseover="this.classList.add('bg-white')"
        onmouseout="this.classList.remove('bg-white')">
        <i class="fa-solid fa-arrow-left text-dark"></i>
    </a>
    <div>
        <h4 class="mb-0 fw-bold">Cadastro de Contas</h4>
        <p class="text-muted small mb-0">Preencha os dados abaixo para registrar uma nova conta no sistema.</p>
    </div>
</div>

<div class="row">

    <div class="col-lg-6">
        <x-alert />
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <!-- Change action to route in Laravel -->
                <form action="{{ route('dashboard.contas.store') }}" method="POST" id="add" name="add">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <h6 class="fw-bold text-primary mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;"><i class="fa-solid fa-building me-2"></i>Informações Da conta</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-medium">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="co_nome" class="form-control" placeholder="Ex: Google" required value="{{ old('co_nome') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-medium">URL <span class="text-danger">*</span></label>
                            <input type="text" name="co_url" class="form-control" placeholder="EX: https://meusitedeservico.com.br" required value="{{ old('co_url') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-medium">Código (Referência) <span class="text-danger">*</span></label>
                            <input type="text" name="co_codigo" class="form-control" placeholder="EX: GG" required value="{{ old('co_codigo') }}">
                            <div class="form-text">Código de referência da conta/serviço</div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-10 my-4">
                    <h6 class="fw-bold text-primary mb-4 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;"><i class="fa-solid fa-building me-2"></i>Outras informações</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-medium">URL de Anúncio</label>
                            <input type="text" name="co_url_anuncio" class="form-control" value="{{ old('co_url_anuncio') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-medium">Limite de Usuários</label>
                            <input type="number" name="co_limite" class="form-control" value="{{ old('co_limite', 1) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Selecione um template</label>
                            <select class="form-select form-control select2" name="co_template_email_id">
                                <option value="" selected>Selecione</option>
                                @foreach($modelo AS $template)
                                    <option value="{{ $template->te_id}}" @php ($template->te_id) @endphp>{{ $template->te_codigo}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Modelo de template para este tipo de conta</small>
                        </div>
                        <div class="col-md-12">
                            <div
                                class="card bg-danger bg-opacity-10 border-danger border-opacity-25 shadow-none mt-2">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger fw-bold mb-1"><i class="fa-solid fa-ban me-2"></i>Envio Manual ?</h6>
                                        <p class="text-danger opacity-75 small mb-0">
                                            Ao marcar como envio manual, deverá ser enviado manualmente.
                                        </p>
                                    </div>
                                    <div class="form-check form-switch fs-4 mb-0">
                                        <input class="form-check-input border-danger" type="checkbox" role="switch" id="banidoSwitch" name="co_envio_manual" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                        <a href="{{ route('dashboard.clientes.index') }}" class="btn btn-light fw-medium px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-medium px-5 shadow-sm"><i
                                class="fa-solid fa-check me-2"></i>Salvar Informações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="mb-1 text-primary fw-bold d-flex align-items-center"><i class="fa-solid fa-building me-2 fs-4"></i>Contas Cadastradas</h5>
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
                            <th>Nome</th>
                            <th>Cod</th>
                            <th>Envio Manual</th>
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
