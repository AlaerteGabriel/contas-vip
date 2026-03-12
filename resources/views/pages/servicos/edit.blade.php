@extends('layouts.app')

@section('title', 'Cadastro de Cliente - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.servicos.index') }}" class="text-decoration-none text-muted">Serviços</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Editar Serviço</li>
    </ol>
</nav>
@endsection

@push('styles')
    <link href="{{asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/plugins/select2/select2-bootstrap-5-theme.min.css')}}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/servicos/edit.js') }}"></script>
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('dashboard.servicos.index') }}"
        class="btn btn-light rounded-circle shadow-sm p-2 me-3 d-flex justify-content-center align-items-center"
        style="width: 40px; height: 40px; transition: all 0.2s;" onmouseover="this.classList.add('bg-white')"
        onmouseout="this.classList.remove('bg-white')">
        <i class="fa-solid fa-arrow-left text-dark"></i>
    </a>
    <div>
        <h4 class="mb-0 fw-bold">Editar Serviço</h4>
        <p class="text-muted small mb-0">ATENÇÃO: Leia atentamente todas observações contidas no formulário.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <x-alert />
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('dashboard.servicos.update') }}" method="POST" name="add" id="add">
                    @csrf
                    <input type="hidden" name="se_id" value="{{ $servico->se_id }}">
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-medium"><i class="fa-regular fa-user text-muted me-2"></i>Selecione a Conta</label>
                            <select disabled class="form-select form-control bg-light select2">
                                <option value="">{{ $servico->se_nome.' | '.$servico->se_cod}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="card border border-primary border-opacity-25 bg-primary bg-opacity-10 shadow-none mb-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">Dados de Acesso da Conta/Serviço</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-dark fw-medium mb-1">Email Vinculado</label>
                                    <input type="email" name="se_email_vinculado" class="form-control" value="{{ old('se_email_vinculado', $servico->se_email_vinculado ?? '') }}" placeholder="login@servico.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-dark fw-medium mb-1">Username</label>
                                    <input type="text" name="se_username" class="form-control"
                                           value="{{ old('se_username', $servico->se_username ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-dark fw-medium mb-1">Senha Atual</label>
                                    <input type="text" name="se_senha_atual" class="form-control"
                                           value="{{ old('se_senha_atual', $servico->se_senha_atual ?? '') }}"
                                           placeholder="senha" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Próxima Renovação</label>
                            <input type="date" name="se_data_renovacao" class="form-control"
                                   value="{{ old('se_data_renovacao', $servico->se_data_renovacao ?? '') }}">
                            <small class="text-muted">Deixar em branco para Vitalício</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Qtd Assinantes</label>
                            <input type="number" name="se_qtd_assinantes" class="form-control"
                                   value="{{ old('se_qtd_assinantes', $servico->se_qtd_assinantes ?? 0) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Limite Ocupação</label>
                            <input type="number" name="se_limite" class="form-control" value="{{ old('se_limite', $servico->se_limite ?? '') }}">
                            <small class="text-muted">Sem limite, deixe em branco</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipo</label>
                            <select name="se_tipo" class="form-select form-select-lg form-control bg-light select2" required>
                                <option value="Free" {{ old('se_tipo', $servico->se_tipo ?? '') == 'Free' ? 'selected' : '' }}>Free</option>
                                <option value="Premium" {{ old('se_tipo', $servico->se_tipo ?? '') == 'Premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                    </div>

                    <div class="card border border-warning border-opacity-25 bg-warning bg-opacity-10 shadow-none mb-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">Situação do serviço/conta</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <select name="se_status" class="form-select form-control bg-light select2" required>
                                        <option value="ativa" {{ old('se_status', $servico->se_status ?? '') == 'ativa' ? 'selected' : '' }}>Ativo</option>
                                        <option value="davez" {{ old('se_status', $servico->se_status ?? '') == 'davez' ? 'selected' : '' }}>Prioridade (Da Vez)</option>
                                        <option value="fechada" {{ old('se_status', $servico->se_status ?? '') == 'fechada' ? 'selected' : '' }}>Fechada</option>
                                        <option value="desligada" {{ old('se_status', $servico->se_status ?? '') == 'desligada' ? 'selected' : '' }}>Desligada</option>
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
@endsection
