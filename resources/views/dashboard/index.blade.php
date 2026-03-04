@extends('layouts.app')

@section('title', 'Dashboard - Carteira Financeira')

<script>
    function confirmarEstorno(id) {
        if (confirm('Deseja realmente estornar esta transação? O valor será devolvido ao pagador e esta operação não pode ser desfeita.')) {
            document.getElementById('form-estorno-' + id).submit();
        }
    }
</script>

@section('content')
<!-- Balance & Summary -->
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 gradient-card text-white h-100 overflow-hidden position-relative">
            <!-- Background shapes -->
            <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 150px; height: 150px; top: -50px; right: -20px;"></div>
            <div class="position-absolute rounded-circle bg-white opacity-10" style="width: 100px; height: 100px; bottom: -30px; right: 80px;"></div>

            <div class="card-body p-4 d-flex flex-column justify-content-between position-relative z-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75 fw-medium">Saldo Disponível</p>
                        <h1 class="display-5 fw-bold mb-0">R$ {{ number_format($user->us_balanco, 2, ',', '.') }}</h1>
                    </div>
                    <div class="bg-white bg-opacity-25 p-2 rounded-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-top border-light border-opacity-25 d-flex gap-4">
                    <div>
                        <p class="small mb-0 opacity-75">Agência</p>
                        <p class="fw-semibold mb-0">0001</p>
                    </div>
                    <div>
                        <p class="small mb-0 opacity-75">Conta</p>
                        <p class="fw-semibold mb-0">{{ rand(1000, 1999).'-'.$user->us_id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Stats -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-arrow-up-right fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Entradas (Mês)</p>
                        <h5 class="fw-bold mb-0">+ R$ {{ number_format($summary['entradas'], 2, ',', '.') }}</h5>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                        <i class="bi bi-arrow-down-left fs-4"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Saídas (Mês)</p>
                        <h5 class="fw-bold mb-0">- R$ {{ number_format($summary['saidas'], 2, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Mobile (Visible mainly on mobile/tablet) -->
<div class="row g-3 mb-4 d-lg-none">
    <div class="col-4">
        <div class="card border-0 shadow-sm text-center h-100 p-2 action-btn" role="button" data-bs-toggle="modal" data-bs-target="#depositModal">
            <div class="card-body p-2">
                <i class="bi bi-box-arrow-in-down fs-3 text-success mb-2 d-inline-block"></i>
                <p class="mb-0 fw-medium small">Depositar</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm text-center h-100 p-2 action-btn" role="button" data-bs-toggle="modal" data-bs-target="#transferModal">
            <div class="card-body p-2">
                <i class="bi bi-send fs-3 text-primary mb-2 d-inline-block"></i>
                <p class="mb-0 fw-medium small">Transferir</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm text-center h-100 p-2 action-btn" role="button" data-bs-toggle="modal" data-bs-target="#receiveModal">
            <div class="card-body p-2">
                <i class="bi bi-qr-code fs-3 text-dark mb-2 d-inline-block"></i>
                <p class="mb-0 fw-medium small">Receber</p>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Action Buttons -->
<div class="d-none d-lg-flex gap-3 mb-4">
    <button class="btn btn-success rounded-pill px-4 py-2 shadow-sm d-flex align-items-center fw-medium" data-bs-toggle="modal" data-bs-target="#depositModal">
        <i class="bi bi-plus-circle me-2"></i> Depositar
    </button>
    <button class="btn btn-primary rounded-pill px-4 py-2 shadow-sm d-flex align-items-center fw-medium" data-bs-toggle="modal" data-bs-target="#transferModal">
        <i class="bi bi-send me-2"></i> Transferir Dinheiro
    </button>
    <button class="btn btn-dark rounded-pill px-4 py-2 shadow-sm d-flex align-items-center fw-medium" data-bs-toggle="modal" data-bs-target="#receiveModal">
        <i class="bi bi-qr-code me-2"></i> Receber PIX
    </button>
</div>

<!-- Recent Transactions -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Extrato Recente</h5>
        <a href="#" class="text-decoration-none text-primary small fw-semibold">Ver todos</a>
    </div>
    <div class="card-body px-4 pt-3 pb-4">
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <tbody>
                    @foreach($transacoes AS $tr)
                        @php
                            // 1. Identifica se a transação FOI estornada ou se ela É um estorno
                            $isReversed = $tr->tr_estornado; // A original marcada como estornada
                            $isAReversal = !is_null($tr->tr_estorno_id); // O lançamento da devolução

                            // Lógica de Entrada ou Saída
                            $isIncome = ($tr->tr_tipo === 'deposito') || ($tr->tr_beneficiario_id === auth()->id());

                            // Cores e Ícones base
                            $colorClass = $isIncome ? 'success' : 'dark';
                            $icon = $isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right';
                            $prefix = $isIncome ? '+' : '-';

                            // Título e Descrição
                            if ($tr->tr_tipo === 'deposito') {
                                $title = 'Depósito';
                                $description = $tr->tr_descricao ?? 'Adição de saldo';
                                $icon = 'bi-cash-stack';
                            } elseif ($tr->tr_tipo === 'transferencia') {
                                if ($isIncome) {
                                    $title = $isAReversal ? 'Estorno Recebido' : 'Transferência Recebida';
                                    $description = "De: " . ($tr->pagador->us_nome ?? 'Usuário Externo');
                                } else {
                                    $title = $isAReversal ? 'Estorno Enviado' : 'Transferência Enviada';
                                    $description = "Para: " . ($tr->beneficiario->us_nome ?? 'Desconhecido');
                                    $colorClass = 'primary';
                                }
                            }

                            // Ajustes visuais para itens estornados
                            if ($isReversed) {
                                $colorClass = 'secondary';
                                $icon = 'bi-x-circle';
                            }
                        @endphp

                        <tr class="transaction-row border-bottom border-light {{ $isReversed ? 'opacity-50' : '' }}">
                            <td class="py-3 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-{{ $colorClass }} bg-opacity-10 text-{{ $colorClass }} me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <i class="bi {{ $icon }} fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">
                                            {{ $title }}
                                            @if($isReversed) <span class="badge bg-light text-secondary ms-1 small" style="font-size: 0.7rem;">CANCELADA</span> @endif
                                            @if($isAReversal) <span class="badge bg-info bg-opacity-10 text-info ms-1 small" style="font-size: 0.7rem;">ESTORNO</span> @endif
                                        </h6>
                                        <p class="text-muted small mb-0">{{ $description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end py-3 px-0">
                                <h6 class="text-{{ $isIncome ? 'success' : 'dark' }} fw-bold mb-1 {{ $isReversed ? 'text-decoration-line-through' : '' }}">
                                    {{ $prefix }} R$ {{ number_format($tr->tr_valor, 2, ',', '.') }}
                                </h6>
                                <span class="badge bg-light text-secondary">
                                    {{ $tr->created_at->calendar() }}
                                </span>

                                {{-- Botão de Estorno --}}
                                @if(!$isReversed && !$isAReversal && $tr->tr_tipo !== 'deposito' && $tr->tr_us_id === auth()->id())
                                    <button type="button"
                                            onclick="confirmarEstorno({{ $tr->tr_id }})"
                                            class="btn btn-sm btn-outline-danger border-0 p-0 shadow-none"
                                            title="Estornar esta transação">
                                        <i class="bi bi-arrow-counterclockwise fs-6"></i>
                                    </button>
                                @endif

                                {{-- Formulário Único Oculto (Fora do loop para performance, ou um para cada se preferir) --}}
                                <form id="form-estorno-{{ $tr->tr_id }}" action="{{ route('carteira.transacoes.estorno', $tr->tr_id) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $transacoes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
