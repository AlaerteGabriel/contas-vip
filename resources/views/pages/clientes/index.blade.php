@extends('layouts.app')

@section('title', 'Clientes - Sistema Contas VIP')
@section('header_title', 'Clientes')
@section('header_subtitle', 'Gestão de contatos e cadastros')

@push('styles')
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
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Lista de Clientes</h5>
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i
                        class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Buscar cliente...">
            </div>
            <!-- Redirecionando para a nova tela de cadastro -->
            <a href="{{ route('clientes.create') }}"
                class="btn btn-primary d-flex align-items-center gap-2 text-nowrap shadow-sm">
                <i class="fa-solid fa-plus"></i> Novo Cliente
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nome Completo</th>
                        <th>Usuário (Nick)</th>
                        <th>Email</th>
                        <th>Email de Envio</th>
                        <th>WhatsApp</th>
                        <th>Banido?</th>
                        <th>Observações</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 fw-bold d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">JA</div>
                                <h6 class="mb-0 fw-semibold">João Almeida</h6>
                            </div>
                        </td>
                        <td class="text-muted"><code>joaoalmeida</code></td>
                        <td class="small"><i class="fa-regular fa-envelope text-muted me-1"></i> joao@email.com
                        </td>
                        <td class="small"><i class="fa-regular fa-paper-plane text-muted me-1"></i>
                            faturas@empresa.com.br</td>
                        <td class="small fw-medium"><i class="fa-brands fa-whatsapp text-success me-1"></i>(11)
                            98765-4321</td>
                        <td><span
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Não</span>
                        </td>
                        <td class="text-muted small fst-italic"
                            style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            Cliente VIP Server 1</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border text-primary" title="Editar"><i
                                    class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light border text-danger" title="Excluir"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 fw-bold d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">TE</div>
                                <h6 class="mb-0 fw-semibold">TechCorp Soluções</h6>
                            </div>
                        </td>
                        <td class="text-muted"><code>tech_corp</code></td>
                        <td class="small"><i class="fa-regular fa-envelope text-muted me-1"></i>
                            contato@techcorp.com</td>
                        <td class="small"><i class="fa-regular fa-paper-plane text-muted me-1"></i>
                            financeiro@techcorp.com</td>
                        <td class="small fw-medium"><i class="fa-brands fa-whatsapp text-success me-1"></i>(11)
                            3456-7890</td>
                        <td><span
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Não</span>
                        </td>
                        <td class="text-muted small fst-italic"
                            style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            Revendedor preferencial</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border text-primary" title="Editar"><i
                                    class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light border text-danger" title="Excluir"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 me-3 fw-bold d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">MC</div>
                                <h6 class="mb-0 fw-semibold text-danger">Maria Costa</h6>
                            </div>
                        </td>
                        <td class="text-muted"><code>maria.costa89</code></td>
                        <td class="small"><i class="fa-regular fa-envelope text-muted me-1"></i>
                            maria.costa@email.com</td>
                        <td class="small text-muted">-</td>
                        <td class="small fw-medium"><i class="fa-brands fa-whatsapp text-success me-1"></i>(21)
                            99999-8888</td>
                        <td><span
                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3"><i
                                    class="fa-solid fa-ban me-1"></i> Sim</span></td>
                        <td class="text-muted small fst-italic text-danger"
                            style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            Banida por fraude financeira</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border text-primary" title="Editar"><i
                                    class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light border text-danger" title="Excluir"><i
                                    class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Pagination -->
    <div class="card-footer bg-white border-top py-3">
        <nav aria-label="Navegação da lista de clientes">
            <ul class="pagination pagination-sm justify-content-between mb-0 align-items-center">
                <li class="page-item disabled text-muted small">Mostrando 1 a 3 de 2,451</li>
                <div class="d-flex">
                    <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                    <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                </div>
            </ul>
        </nav>
    </div>
</div>
@endsection
