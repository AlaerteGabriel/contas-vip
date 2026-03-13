@extends('layouts.app')

@section('title', 'Serviços - Sistema Contas VIP')
@section('header_title', 'Serviços Contratados')
@section('header_subtitle', 'Gestão de contas vinculadas')

@push('styles')
<link href="{{asset('assets/plugins/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/plugins/select2/select2-bootstrap-5-theme.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/plugins/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css"/>
<style>
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background-color: rgba(67, 97, 238, 0.2);
        border-radius: 10px;
    }
    .table th {
        white-space: nowrap;
    }
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
        var ROUTE_DATATABLES = "{{ route('dashboard.servicos.getDatatables') }}";
        var ROUTE_DELETE = "{{ route('dashboard.servicos.ajaxDestroy') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/countUp/countUp.umd.js') }}"></script>
    <script src="{{ asset('assets/js/servicos/index.js') }}"></script>
@endpush

@section('content')

<!-- Stats Overview -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100 border-start border-primary border-4 shadow-sm mb-0">
            <div class="card-body">
                <p class="text-muted fw-semibold text-uppercase small mb-1">Total de Serviços Vinculados</p>
                <h3 class="mb-0 fw-bold text-dark" id="total-servicos"></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-start border-success border-4 shadow-sm mb-0">
            <div class="card-body">
                <p class="text-muted fw-semibold text-uppercase small mb-1">Serviços Ativos</p>
                <h3 class="mb-0 fw-bold text-dark" id="total-servicos-ativos"></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-start border-danger border-4 shadow-sm mb-0">
            <div class="card-body">
                <p class="text-muted fw-semibold text-uppercase small mb-1">Para Renovar (Próx. 7 Dias)</p>
                <h3 class="mb-0 fw-bold text-dark text-danger" id="total-renovar"></h3>
            </div>
        </div>
    </div>
</div>

<x-alert />
<!-- Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Listagem de Serviços</h5>
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="busca" id="busca" class="form-control border-start-0 ps-0" placeholder="Serviço, email ou código">
            </div>
            <button class="btn btn-primary d-flex align-items-center gap-2 text-nowrap shadow-sm" data-bs-toggle="modal" data-bs-target="#novoServicoModal">
                <i class="fa-solid fa-plus"></i> Novo Serviço
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 yajra-datatable" style="margin-top: 0 !important;">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th class="ps-4">CodServ.</th>
                        <th>Serviço</th>
                        <th>Email Vinc.</th>
                        <th>Username</th>
                        <th>Senha</th>
                        <th>Renovação</th>
                        <th>Tipo</th>
                        <th>Ult. AS</th>
                        <th>Qtd Ass.</th>
                        <th>Limite</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Vincular Novo Servico -->
<div class="modal fade" id="novoServicoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h1 class="modal-title fs-5 fw-bold"><i class="fa-solid fa-link text-primary me-2"></i>Novo Serviço</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dashboard.servicos.store') }}" method="POST" name="add" id="add">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-medium"><i class="fa-regular fa-user text-muted me-2"></i>Selecione a Conta</label>
                            <select name="se_contas_id" class="form-select form-control bg-light select2" required>
                                <option value="">Selecione uma conta</option>
                                @foreach($contas AS $conta)
                                    <option value="{{ $conta->co_id }}"
                                        {{ old('se_contas_id') == $conta->co_id ? 'selected' : '' }}>
                                        {{ $conta->co_nome.' | '.$conta->co_codigo.' | Limite: '.$conta->co_limite }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card border border-primary border-opacity-25 bg-primary bg-opacity-10 shadow-none mb-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">Dados de Acesso da Conta/Serviço</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-dark fw-medium mb-1">Email Vinculado</label>
                                    <input type="email" name="se_email_vinculado" class="form-control"
                                           value="{{ old('se_email_vinculado') }}"
                                           placeholder="login@servico.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-dark fw-medium mb-1">Username</label>
                                    <input type="text" name="se_username" class="form-control"
                                           value="{{ old('se_username') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-dark fw-medium mb-1">Senha Atual</label>
                                    <input type="text" name="se_senha_atual" class="form-control"
                                           value="{{ old('se_senha_atual') }}"
                                           placeholder="senha" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Próxima Renovação</label>
                            <input type="date" name="se_data_renovacao" class="form-control"
                                   value="{{ old('se_data_renovacao') }}">
                            <small class="text-muted">Deixar em branco para Vitalício</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Qtd Assinantes</label>
                            <input type="number" name="se_qtd_assinantes" class="form-control"
                                   value="{{ old('se_qtd_assinantes', 0) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Limite Ocupação</label>
                            <input type="number" name="se_limite" class="form-control"
                                   value="{{ old('se_limite') }}">
                            <small class="text-muted">Sem limite, deixe em branco</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="se_tipo" class="form-select form-select-lg form-control bg-light select2" required>
                                <option value="Free" {{ old('se_tipo') == 'Free' ? 'selected' : '' }}>Free</option>
                                <option value="Premium" {{ old('se_tipo') == 'Premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                    </div>

                    <div class="card border border-warning border-opacity-25 bg-warning bg-opacity-10 shadow-none mb-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">Situação do serviço/conta</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <select name="se_status" class="form-select form-control bg-light select2" required>
                                        <option value="ativa" {{ old('se_status') == 'ativa' ? 'selected' : '' }}>Ativo</option>
                                        <option value="davez" {{ old('se_status') == 'davez' ? 'selected' : '' }}>Prioridade (Da Vez)</option>
                                        <option value="fechada" {{ old('se_status') == 'fechada' ? 'selected' : '' }}>Fechada</option>
                                        <option value="desligada" {{ old('se_status') == 'desligada' ? 'selected' : '' }}>Desligada</option>
                                    </select>
                                    <small class="text-muted">Se marcada como <code>Prioridade (Da Vez)</code> o sistema irá utilizar esse serviço independente de seu limite.</small><br>
                                    <small class="text-muted">Se marcada como <code>Fechada</code> o sistema irá manter os clientes que nela estão, e não receberá novos clientes.</small><br>
                                    <small class="text-muted">Se marcada como <code>Desligada</code> o sistema irá realocar todos os clientes a um serviço equivalente, e não será mais utilizada.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top-0 pt-4 px-4 pb-2 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Salvar Serviço</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal alterar senha -->
<div class="modal fade" id="altsenhaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h1 class="modal-title fs-5 fw-bold"><i class="fa-solid fa-link text-primary me-2"></i>Alterar Senha/Atualizar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dashboard.servicos.altSenha') }}" method="POST" name="add3" id="add3">
                    @csrf
                    <input type="hidden" name="se_id" id="idSe">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label small text-dark fw-medium mb-1">Senha Atual:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-key"></i></span>
                                <input type="text" name="se_senha_atual" id="senha" class="form-control border-start-0" placeholder="senha" required>
                            </div>
                            <small class="text-muted">ATENÇÃO: Ao atualizar a senha, se o status da conta for "ativa" iniciará o processo de realocação de contas</small>
                        </div>
                    </div>

                    <div class="card border border-warning border-opacity-25 bg-warning bg-opacity-10 shadow-none mb-4 mt-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">Situação do serviço/conta</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <select name="se_status" id="status" class="form-select form-control bg-light select2" required>
                                        <option value="ativa">Ativo</option>
                                        <option value="davez">Prioridade (Da Vez)</option>
                                        <option value="fechada">Fechada</option>
                                        <option value="desligada">Desligada</option>
                                    </select>
                                    <small class="text-muted">Se marcada como <code>Prioridade (Da Vez)</code> o sistema irá utilizar esse serviço independente de seu limite.</small><br>
                                    <small class="text-muted">Se marcada como <code>Fechada</code> o sistema irá manter os clientes que nela estão, e não receberá novos clientes.</small><br>
                                    <small class="text-muted">Se marcada como <code>Desligada</code> o sistema irá realocar todos os clientes a um serviço equivalente, e não será mais utilizada.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top-0 pt-4 px-4 pb-2 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Alterar e atualizar</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
