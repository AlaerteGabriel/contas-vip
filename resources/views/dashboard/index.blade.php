@extends('layouts.app')

@section('title', 'Dashboard - Sistema Contas VIP')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'Bem-vindo de volta, Admin!')

@section('content')
<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card stat-card h-100 bg-primary text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Total Clientes</p>
                        <h2 class="mb-0 fw-bold">2,451</h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-white text-primary rounded-pill">+12%</span>
                    <span class="small text-white-50 ms-2">desde o último mês</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card stat-card h-100 text-white" style="background: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Serviços Ativos</p>
                        <h2 class="mb-0 fw-bold">842</h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-white text-info rounded-pill">+5%</span>
                    <span class="small text-white-50 ms-2">desde o último mês</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card stat-card h-100 text-white" style="background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Faturas Pendentes</p>
                        <h2 class="mb-0 fw-bold">124</h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-white text-danger rounded-pill">-2%</span>
                    <span class="small text-white-50 ms-2">desde o último mês</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card stat-card h-100 text-white" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Receita Mensal</p>
                        <h2 class="mb-0 fw-bold">R$ 45k</h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-brazilian-real-sign"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-white text-success rounded-pill">+18%</span>
                    <span class="small text-white-50 ms-2">desde o último mês</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Ultimos Clientes -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Últimos Clientes Registrados</h5>
                <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-light border">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Cliente</th>
                                <th>Status</th>
                                <th>Plano/Serviço</th>
                                <th>Data Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary fw-bold" style="width: 40px; height: 40px; text-align: center;">JA</div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">João Almeida</h6>
                                            <small class="text-muted">joao.almeida@email.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Ativo</span></td>
                                <td>Hospedagem VIP</td>
                                <td class="text-muted small">04 Mar, 2026</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary fw-bold" style="width: 40px; height: 40px; text-align: center;">MC</div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Maria Costa</h6>
                                            <small class="text-muted">maria.costa@email.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3">Pendente</span></td>
                                <td>Suporte Técnico</td>
                                <td class="text-muted small">03 Mar, 2026</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary fw-bold" style="width: 40px; height: 40px; text-align: center;">RS</div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Roberto Silva</h6>
                                            <small class="text-muted">roberto@empresa.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Ativo</span></td>
                                <td>Servidor Dedicado</td>
                                <td class="text-muted small">01 Mar, 2026</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle p-2 me-3 text-primary fw-bold" style="width: 40px; height: 40px; text-align: center;">AL</div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Amanda Lima</h6>
                                            <small class="text-muted">amanda@email.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">Inativo</span></td>
                                <td>Hospedagem Padrão</td>
                                <td class="text-muted small">28 Fev, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Avisos -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Atividades Recentes</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-4">
                    <div class="mt-1">
                        <i class="fa-solid fa-circle text-primary" style="font-size: 0.6rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 fw-semibold">Backup Realizado</h6>
                        <p class="mb-0 text-muted small">Backup completo do banco de dados concluído com sucesso.</p>
                        <small class="text-muted" style="font-size: 0.7rem;">Hoje, 02:00 AM</small>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="mt-1">
                        <i class="fa-solid fa-circle text-success" style="font-size: 0.6rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 fw-semibold">Novo Pagamento</h6>
                        <p class="mb-0 text-muted small">Fatura #834 de R$ 150,00 foi paga por Cliente XYZ.</p>
                        <small class="text-muted" style="font-size: 0.7rem;">Ontem, 16:30 PM</small>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mt-1">
                        <i class="fa-solid fa-circle text-warning" style="font-size: 0.6rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 fw-semibold">Alerta de Espaço</h6>
                        <p class="mb-0 text-muted small">Servidor 01 atingiu 85% da capacidade de armazenamento.</p>
                        <small class="text-muted" style="font-size: 0.7rem;">Ontem, 09:15 AM</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
