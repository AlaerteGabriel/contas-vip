@extends('layouts.app')

@section('title', 'Email Marketing - Sistema Contas VIP')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#"
                class="text-decoration-none text-muted">Configurações</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Disparos em Massa
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-4 fs-3 d-flex justify-content-center align-items-center"
                style="width: 70px; height: 70px;">
                <i class="fa-solid fa-bullhorn rotate-n-15"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Email Marketing</h4>
                <p class="text-muted mb-0">Comunique-se com seus clientes. Envie promoções, avisos de manutenção
                    ou cobranças em lote.</p>
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
            data-bs-target="#novaCampanhaModal">
            <i class="fa-regular fa-paper-plane"></i> Criar Nova Campanha
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
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
                                <th>Público Alvo</th>
                                <th>Status/Processo</th>
                                <th>Data/Hora</th>
                                <th class="text-end pe-4">Desempenho</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <h6 class="mb-0 fw-semibold">Promoção Black Friday 2026</h6>
                                    <span class="badge bg-light text-dark border">Ofertas</span>
                                </td>
                                <td class="text-muted small">Aproveite 50% OFF em upgrades...</td>
                                <td><span class="text-dark small"><i
                                            class="fa-solid fa-users text-muted me-1"></i> Todos os Clientes
                                        Ativos</span></td>
                                <td><span
                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3"><i
                                            class="fa-solid fa-check me-1"></i> Concluído</span></td>
                                <td class="text-muted small">01 Nov, 2026 <br> 09:30 AM</td>
                                <td class="text-end pe-4">
                                    <div class="small fw-semibold text-dark">
                                        Enviados: <span class="text-primary">1.240</span> <br>
                                        Aberturas: <span class="text-success">65%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <h6 class="mb-0 fw-semibold">Manutenção Servidor SQL 04</h6>
                                    <span class="badge bg-light text-dark border">Aviso Técnico</span>
                                </td>
                                <td class="text-muted small">IMPORTANTE: Janela de Manute...</td>
                                <td><span class="text-dark small"><i
                                            class="fa-solid fa-server text-muted me-1"></i> Clientes Servidor
                                        04</span></td>
                                <td><span
                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3"><i
                                            class="fa-solid fa-check me-1"></i> Concluído</span></td>
                                <td class="text-muted small">15 Out, 2026 <br> 14:00 PM</td>
                                <td class="text-end pe-4">
                                    <div class="small fw-semibold text-dark">
                                        Enviados: <span class="text-primary">312</span> <br>
                                        Aberturas: <span class="text-success">88%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <h6 class="mb-0 fw-semibold text-muted">Aviso de Faturas Atrasadas - Outubro
                                    </h6>
                                    <span class="badge bg-light text-dark border">CobrançaLote</span>
                                </td>
                                <td class="text-muted small">Evite a suspensão do seu serviço...</td>
                                <td><span class="text-dark small"><i
                                            class="fa-solid fa-user-clock text-muted me-1"></i> Clientes
                                        Inadimplentes</span></td>
                                <td><span
                                        class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3"><i
                                            class="fa-regular fa-pen-to-square me-1"></i> Rascunho</span></td>
                                <td class="text-muted small">-</td>
                                <td class="text-end pe-4 text-muted small fst-italic">
                                    Aguardando envio
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
