@extends('layouts.app')

@section('title', 'Dashboard - Sistema Contas VIP')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'Bem-vindo de volta, Admin!')

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
        var ROUTE_DATATABLES = "{{ route('dashboard.ultimos.getDatatables') }}";
    </script>
    <script src="{{ asset('assets/plugins/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/countUp/countUp.umd.js') }}"></script>
    <script src="{{ asset('assets/js/home/index.js') }}"></script>
@endpush

@section('content')
<div class="row">
    <!-- Stat Card 1 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card stat-card h-100 bg-primary text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Total Clientes</p>
                        <h2 class="mb-0 fw-bold" id="total-clientes"></h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-users"></i>
                    </div>
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
                        <h2 class="mb-0 fw-bold" id="total-servicos"></h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
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
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Pedidos</p>
                        <h2 class="mb-0 fw-bold" id="total-pedidos"></h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
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
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Contas</p>
                        <h2 class="mb-0 fw-bold" id="total-contas"></h2>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Ultimos Clientes -->
    <div class="col-lg-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Últimos Clientes Registrados nas últimas 24horas</h5>
                <a href="{{ route('dashboard.clientes.index') }}" class="btn btn-sm btn-light border">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 yajra-datatable" style="margin-top: 0 !important;">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th class="ps-4">Nome Completo</th>
                                <th>Email</th>
                                <th>WhatsApp</th>
                                <th>Banido?</th>
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
