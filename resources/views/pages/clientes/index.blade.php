@extends('layouts.app')

@section('title', 'Clientes - Sistema Contas VIP')
@section('header_title', 'Clientes')
@section('header_subtitle', 'Gestão de contatos e cadastros')

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
        var ROUTE_DATATABLES = "{{ route('dashboard.clientes.getDatatables') }}";
        var ROUTE_DELETE = "{{ route('dashboard.clientes.ajaxDestroy') }}";
    </script>
    <script src="{{asset('assets/plugins/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset('assets/js/clientes/index.js') }}"></script>
@endpush

@section('content')
<x-alert />
<div class="card shadow-sm border-0">
    <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Lista de Clientes</h5>
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="busca" id="busca" class="form-control border-start-0 ps-0" placeholder="Buscar cliente...">
            </div>
            <!-- Redirecionando para a nova tela de cadastro -->
            <a href="{{ route('dashboard.clientes.create') }}"
                class="btn btn-primary d-flex align-items-center gap-2 text-nowrap shadow-sm">
                <i class="fa-solid fa-plus"></i> Novo Cliente
            </a>
        </div>
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
                        <th>Obs</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
