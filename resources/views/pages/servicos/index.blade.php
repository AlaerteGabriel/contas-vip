@extends('layouts.app')

@section('title', 'Serviços - Sistema Contas VIP')
@section('header_title', 'Serviços Contratados')
@section('header_subtitle', 'Gestão de contas vinculadas')

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
<!-- Stats Overview -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100 border-start border-primary border-4 shadow-sm mb-0">
            <div class="card-body">
                <p class="text-muted fw-semibold text-uppercase small mb-1">Total de Serviços Vinculados</p>
                <h3 class="mb-0 fw-bold text-dark">857</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-start border-success border-4 shadow-sm mb-0">
            <div class="card-body">
                <p class="text-muted fw-semibold text-uppercase small mb-1">Contas Ativas Sem Pendências</p>
                <h3 class="mb-0 fw-bold text-dark">820</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-start border-danger border-4 shadow-sm mb-0">
            <div class="card-body">
                <p class="text-muted fw-semibold text-uppercase small mb-1">Para Renovar (Próx. 7 Dias)</p>
                <h3 class="mb-0 fw-bold text-dark text-danger">37</h3>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
    <div
        class="card-header bg-white pt-4 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold">Listagem de Serviços</h5>
        <div class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i
                        class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0"
                    placeholder="Buscar serviço ou email...">
            </div>
            <button class="btn btn-primary d-flex align-items-center gap-2 text-nowrap shadow-sm"
                data-bs-toggle="modal" data-bs-target="#novoServicoModal">
                <i class="fa-solid fa-plus"></i> Vincular Novo
            </button>
            <button class="btn btn-outline-secondary d-flex align-items-center shadow-sm">
                <i class="fa-solid fa-filter"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">CodServ.</th>
                        <th>Nome do Serviço</th>
                        <th>Email Vinc. (Cliente)</th>
                        <th>Senha Atual</th>
                        <th>Senha Ant.</th>
                        <th>Data Renovação</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4"><span
                                class="badge bg-light text-dark border font-monospace">10245</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded px-2 py-2 me-3 fs-5"
                                    style="width: 40px; text-align: center;"><i class="fa-brands fa-aws"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold text-dark">Hospedagem Cloud PME</h6>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-primary mb-1"><i class="fa-regular fa-envelope me-1"></i>
                                joao@email.com</div>
                            <div class="small text-muted"><i class="fa-regular fa-user text-muted me-1"></i>
                                João Almeida <a href="#" class="text-decoration-none ms-1"><i
                                        class="fa-solid fa-arrow-up-right-from-square small"></i></a></div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="password" class="form-control border-end-0 bg-white"
                                    value="SenhaForte123!" readonly>
                                <button class="btn btn-light border border-start-0 text-muted" type="button"
                                    title="Copiar"><i class="fa-regular fa-copy"></i></button>
                            </div>
                        </td>
                        <td class="text-muted small fst-italic"><code>SenhaAntiga456_</code></td>
                        <td>
                            <div class="fw-semibold text-dark mb-1"><i
                                    class="fa-regular fa-calendar text-muted me-1"></i> 10/Abr/2026</div>
                            <div class="progress" style="height: 5px; width: 100px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td><span
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Ativo</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border text-primary"
                                title="Editar / Mudar Senha"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light border text-danger"
                                title="Cancelar / Suspender"><i class="fa-solid fa-ban"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4"><span
                                class="badge bg-light text-dark border font-monospace">10246</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded px-2 py-2 me-3 fs-5"
                                    style="width: 40px; text-align: center;"><i
                                        class="fa-solid fa-shield-halved"></i></div>
                                <h6 class="mb-0 fw-semibold text-dark">Firewall + VPN Pro</h6>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-primary mb-1"><i class="fa-regular fa-envelope me-1"></i>
                                contato@techcorp.com</div>
                            <div class="small text-muted"><i class="fa-regular fa-user text-muted me-1"></i>
                                TechCorp Soluções <a href="#" class="text-decoration-none ms-1"><i
                                        class="fa-solid fa-arrow-up-right-from-square small"></i></a></div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="password" class="form-control border-end-0 bg-white"
                                    value="vpn_Super" readonly>
                                <button class="btn btn-light border border-start-0 text-muted" type="button"
                                    title="Copiar"><i class="fa-regular fa-copy"></i></button>
                            </div>
                        </td>
                        <td class="text-muted small">-</td>
                        <td>
                            <div class="fw-semibold text-danger mb-1"><i
                                    class="fa-regular fa-calendar text-danger me-1"></i> 05/Mar/2026</div>
                            <div class="progress" style="height: 5px; width: 100px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 95%"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-danger fw-bold d-block mt-1">Vence Amanhã</small>
                        </td>
                        <td><span
                                class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 text-dark">Pagto
                                Pendente</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border text-primary"
                                title="Editar / Mudar Senha"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light border text-danger"
                                title="Cancelar / Suspender"><i class="fa-solid fa-ban"></i></button>
                        </td>
                    </tr>
                    <tr class="opacity-75 bg-light">
                        <td class="ps-4"><span
                                class="badge bg-light text-dark border font-monospace">10189</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded px-2 py-2 me-3 fs-5"
                                    style="width: 40px; text-align: center;"><i
                                        class="fa-brands fa-microsoft"></i></div>
                                <h6 class="mb-0 fw-semibold text-secondary">Licença Office 365</h6>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-secondary mb-1"><i
                                    class="fa-regular fa-envelope me-1"></i> maria.costa@email.com</div>
                            <div class="small text-muted"><i class="fa-regular fa-user text-muted me-1"></i>
                                Maria Costa</div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="password" class="form-control border-end-0 bg-light"
                                    value="********" readonly disabled>
                            </div>
                        </td>
                        <td class="text-muted small">-</td>
                        <td>
                            <div class="fw-semibold text-secondary mb-1"><i
                                    class="fa-regular fa-calendar text-muted me-1"></i> 20/Fev/2026</div>
                        </td>
                        <td><span
                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Suspenso</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border text-primary" title="Editar"><i
                                    class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light border text-success" title="Reativar"><i
                                    class="fa-solid fa-rotate-left"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="card-footer bg-white border-top py-3">
        <nav aria-label="Navegação">
            <ul class="pagination pagination-sm justify-content-between mb-0 align-items-center">
                <li class="page-item disabled text-muted small">Mostrando 1 a 3 de 857 vínculos</li>
                <div class="d-flex">
                    <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                    <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><span class="page-link text-muted">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                </div>
            </ul>
        </nav>
    </div>
</div>

<!-- Modal Vincular Novo Servico -->
<div class="modal fade" id="novoServicoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h1 class="modal-title fs-5 fw-bold"><i class="fa-solid fa-link text-primary me-2"></i>Vincular
                    Serviço ao Cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dashboard.servicos.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i
                                    class="fa-regular fa-user text-muted me-2"></i>Selecione o Cliente</label>
                            <select name="cliente_id" class="form-select bg-light" required>
                                <option selected disabled value="">Buscar por nome ou email...</option>
                                <option value="1">João Almeida (joao@email.com)</option>
                                <option value="2">TechCorp Soluções (contato@techcorp.com)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class="fa-solid fa-box text-muted me-2"></i>Plano
                                / Serviço a Contratar</label>
                            <select name="plano_id" class="form-select bg-light" required>
                                <option selected disabled value="">Selecione um plano...</option>
                                <option value="1">Hospedagem Cloud PME - R$ 149,90/mês</option>
                                <option value="2">Firewall + VPN Pro - R$ 99,90/mês</option>
                            </select>
                        </div>
                    </div>

                    <div
                        class="card border border-primary border-opacity-25 bg-primary bg-opacity-10 shadow-none mb-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">Dados de Acesso da Conta (A ser enviada)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-dark fw-medium mb-1">Email / Login
                                        Vinculado</label>
                                    <input type="text" name="login_vinculado" class="form-control" placeholder="login@servico.com"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-dark fw-medium mb-1">Senha Inicial</label>
                                    <div class="input-group">
                                        <input type="text" name="senha_inicial" class="form-control" placeholder="SenhaApenas123!"
                                            required>
                                        <button class="btn btn-outline-secondary bg-white" type="button"
                                            title="Gerar Senha Aleatória"><i class="fa-solid fa-dice"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Competência / Ciclo</label>
                            <select name="ciclo" class="form-select">
                                <option value="mensal">Mensal</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Primeira Renovação</label>
                            <input type="date" name="data_renovacao" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Valor Diferenciado</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" name="valor_diferenciado" class="form-control" placeholder="Padrão do plano">
                            </div>
                            <div class="form-text mt-1" style="font-size: 0.7rem;">Deixe vazio caso não haja
                                desconto</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4 d-flex justify-content-between">
                <div class="form-check pt-1">
                    <input class="form-check-input" type="checkbox" id="sendEmailToggle" name="send_email" value="1" checked form="novoServicoModal form">
                    <label class="form-check-label text-muted small" for="sendEmailToggle">
                        Enviar email de boas-vindas com dados de acesso
                    </label>
                </div>
                <div>
                    <button type="button" class="btn btn-light border me-2"
                        data-bs-dismiss="modal">Cancelar</button>
                    <!-- using a little JS to submit the form from outside or we can just change button type inside the form, but let's keep html logic -->
                    <button type="button" onclick="document.querySelector('#novoServicoModal form').submit();" class="btn btn-primary px-4 shadow-sm">Vincular Serviço</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Custom JS Just for copy passwords inside inputs
    document.querySelectorAll('button[title="Copiar"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.previousElementSibling;
            // temporarely change type to copy if needed, but select works on text/password
            if (input) {
                const type = input.type;
                input.type = "text";
                input.select();
                document.execCommand("copy");
                input.type = type;

                // visual feedback
                const originalIcon = this.innerHTML;
                this.innerHTML = '<i class="fa-solid fa-check text-success"></i>';
                setTimeout(() => { this.innerHTML = originalIcon; }, 1500);
            }
        });
    });
</script>
@endpush
