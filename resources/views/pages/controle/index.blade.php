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
        var ROUTE_DATATABLES = "{{ route('dashboard.controle.getDatatables') }}";
        var ROUTE_BAN = "{{ route('dashboard.controle.ajaxBanCliente') }}";
        var ROUTE_BAN_SERV = "{{ route('dashboard.controle.ajaxBanClienteServico') }}";
        var ROUTE_DELETE = "{{ route('dashboard.controle.ajaxDestroy') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/controle/index.js') }}"></script>
@endpush

@section('content')

<x-alert />
<!-- Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Controle geral</h5>
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="busca" id="busca" class="form-control border-start-0 ps-0" placeholder="Serviço, email ou código">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 yajra-datatable" style="margin-top: 0 !important;">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th class="ps-4">Serviço</th>
                        <th>Cod.Serv</th>
                        <th>Email Envio</th>
                        <th>Username</th>
                        <th>Senha</th>
                        <th>Renovação</th>
                        <th>Qtd AS</th>
                        <th>Status</th>
                        <th>Email Ad.</th>
                        <th>Obs.</th>
                        <th class="text-end pe-4"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Vincular Novo Servico -->
<div class="modal fade" id="trocarClienteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h1 class="modal-title fs-5 fw-bold"><i class="fa-solid fa-link text-primary me-2"></i>Novo Serviço</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dashboard.controle.trocarConta') }}" method="POST" name="add" id="add">
                    @csrf
                    <input type="hidden" name="cs_id" id="idCs">
                    <input type="hidden" name="cs_cliente_id" id="idCliente">
                    <h6 class="nomeCliente mb-5"></h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-medium"><i class="fa-solid fa-box-open"></i> Selecione o novo Serviço</label>
                            <select name="cs_servico_id" class="form-select form-control bg-light select2" required>
                                <option value="">Selecione o novo Serviço</option>
                                @foreach($servicos AS $conta)
                                    <option value="{{ $conta->se_id }}"
                                        {{ old('se_id') == $conta->se_id ? 'selected' : '' }}>
                                        {{ $conta->se_nome.' | '.$conta->se_cod.' | Limite: '.$conta->se_limite }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="border-top-0 pt-4 px-4 pb-2 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Trocar de Serviço</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal Vincular email adicional -->
<div class="modal fade" id="addEmailAdicionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h1 class="modal-title fs-5 fw-bold"><i class="fa-solid fa-link text-primary me-2"></i>Registrar envio de email adicional</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dashboard.controle.addEmailAdicional') }}" method="POST" name="add2" id="add2">
                    @csrf
                    <input type="hidden" name="cs_id" id="idCsMail">
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-medium"><i class="fa-solid fa-box-open"></i> Selecione o template de email</label>
                            <select name="cs_template_email_id" class="form-select form-control select2" required>
                                <option value="">Selecione o template de email</option>
                                @foreach($template AS $item)
                                    <option value="{{ $item->te_id }}" {{ old('te_id') == $item->te_id ? 'selected' : '' }}>
                                        {{ $item->te_codigo.' | '.$item->te_assunto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="border-top-0 pt-4 px-4 pb-2 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">Registrar</button>
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
