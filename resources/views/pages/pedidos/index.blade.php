@extends('layouts.app')

@section('title', 'Serviços - Sistema Contas VIP')
@section('header_title', 'Serviços Contratados')
@section('header_subtitle', 'Gestão de contas vinculadas')

@push('styles')
<link href="{{asset('assets/plugins/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
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
        var ROUTE_DATATABLES = "{{ route('dashboard.pedidos.getDatatables') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/js/pedidos/index.js') }}"></script>
@endpush

@section('content')

<x-alert />
<!-- Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Pedidos/Vendas</h5>
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="busca" id="busca" class="form-control border-start-0 ps-0" placeholder="Buscar">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 yajra-datatable" style="margin-top: 0 !important;">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Data Venda</th>
                        <th>SKU</th>
                        <th>Detalhe</th>
                        <th class="ps-4">Serviço</th>
                        <th>Tipo</th>
                        <th>Data Início</th>
                        <th>Período</th>
                        <th>Data Término</th>
                        <th>Obs.</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
