@extends('layouts.app')

@section('title', 'Email Marketing - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-muted">Configurações</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            Disparos em Massa
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-4 fs-3 d-flex justify-content-center align-items-center" style="width: 70px; height: 70px;">
                <i class="fa-solid fa-bullhorn rotate-n-15"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Email Marketing</h4>
                <p class="text-muted mb-0">Comunique-se com seus clientes. Envie promoções, avisos de manutenção ou cobranças em lote.</p>
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#novaCampanhaModal">
            <i class="fa-regular fa-paper-plane"></i> Criar Nova Campanha
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <x-alert />

        <div class="card h-100">
            <div class="card-header bg-white pt-4 pb-3 border-bottom-0 d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">Campanhas Recentes</h5>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary active">Todas</button>
                    <button type="button" class="btn btn-outline-secondary">Enviadas</button>
                    <button type="button" class="btn btn-outline-secondary">Rascunhos</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nome da Campanha</th>
                                <th>Assunto do Email</th>
                                <th>Status/Processo</th>
                                <th>Data/Hora</th>
                                <th class="text-end pe-4">Desempenho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campanhas AS $campanha)
                            <tr>
                                <td class="ps-4">
                                    <h6 class="mb-0 fw-semibold">
                                        {{ $campanha->em_titulo }}
                                    </h6>
                                    <span class="badge bg-light text-dark border mt-1">Campanha</span>
                                </td>

                                <td class="text-muted small">
                                    {{ Str::limit($campanha->template->te_assunto ?? 'Sem assunto definido', 40) }}
                                </td>

                                <td>
                                    @if($campanha->em_status == 'finalizada')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">
                                            <i class="fa-solid fa-check me-1"></i> Concluído
                                        </span>
                                    @elseif($campanha->em_status == 'executando')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">
                                            <i class="fa-solid fa-spinner fa-spin me-1"></i> Enviando...
                                        </span>
                                    @elseif($campanha->em_status == 'pausada')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3">
                                            <i class="fa-solid fa-pause me-1"></i> Pausada
                                        </span>
                                    @elseif($campanha->em_status == 'erro')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Erro
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">
                                            <i class="fa-regular fa-clock me-1"></i> {{ ucfirst($campanha->em_status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    @if($campanha->em_status == 'pendente')
                                        -
                                    @else
                                        {{ \Carbon\Carbon::parse($campanha->created_at)->format('d M, Y') }} <br>
                                        {{ \Carbon\Carbon::parse($campanha->created_at)->format('H:i') }}
                                    @endif
                                </td>

                                <td class="text-end pe-4">
                                    @if($campanha->em_status == 'pendente')
                                        <div class="text-muted small fst-italic">
                                            Aguardando envio
                                        </div>
                                    @else
                                        <div class="small fw-semibold text-dark">
                                            Enviados: <span class="text-primary">{{ number_format($campanha->qtd_enviados, 0, ',', '.') }}</span>

                                            @if($campanha->em_status == 'pausada')
                                                <br><span class="text-muted" style="font-size: 0.75rem;">(Lote diário atingido)</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Nenhuma campanha criada ainda.
                                </td>
                            </tr>
                           @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        {{ $campanhas->links() }}
    </div>
</div>


<div class="modal fade" id="novaCampanhaModal" tabindex="-1" aria-labelledby="novaCampanhaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 pt-4 pb-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold" id="novaCampanhaModalLabel">Criar Nova Campanha</h5>
                    <p class="text-muted small mb-0">Defina os detalhes do envio e filtre seu público-alvo.</p>
                </div>
                <button type="button" class="btn-close mb-4" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('dashboard.config.email-marketing.getContatos') }}" method="GET">
                @csrf
                <div class="modal-body px-4 py-4">

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-users-viewfinder me-2"></i>1. Filtros de Audiência para envio</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status do Cliente</label>
                            <select name="status_filtro" class="form-select">
                                <option value="">Todos os status</option>
                                <option value="ativo">Ativo</option>
                                <option value="expirado">Expirado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Serviço</label>
                            <select name="servico_id" class="form-select">
                                <option value="">Todos os serviços</option>
                                @foreach($servicos AS $servico)
                                    <option value="{{ $servico->se_id }}">{{ $servico->se_nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-top-0 px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-buscar-contatos" class="btn btn-info text-white d-flex align-items-center gap-2">
                        <i class="fa-solid fa-search"></i> Buscar Contatos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
