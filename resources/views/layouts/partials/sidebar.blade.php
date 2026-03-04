<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <h6 class="text-muted fw-bold text-uppercase small">Menu Principal</h6>
        </div>
        <div class="nav flex-column nav-pills gap-2">
            <a href="{{ route('carteira.acc.index') ?? '#' }}" class="nav-link {{ request()->routeIs('carteira.acc.index') ? 'active' : 'text-dark' }} rounded-pill d-flex align-items-center px-3 py-2"><i class="bi bi-grid-1x2-fill me-3"></i> Dashboard</a>
            <a href="#" class="nav-link text-dark rounded-pill d-flex align-items-center px-3 py-2" data-bs-toggle="modal" data-bs-target="#depositModal"><i class="bi bi-box-arrow-in-down me-3"></i> Depositar</a>
            <a href="#" class="nav-link text-dark rounded-pill d-flex align-items-center px-3 py-2" data-bs-toggle="modal" data-bs-target="#transferModal"><i class="bi bi-send me-3"></i> Transferir</a>
            <a href="#" class="nav-link text-dark rounded-pill d-flex align-items-center px-3 py-2" data-bs-toggle="modal" data-bs-target="#receiveModal"><i class="bi bi-qr-code-scan me-3"></i> Receber</a>
            <a href="#" class="nav-link text-dark rounded-pill d-flex align-items-center px-3 py-2"><i class="bi bi-shield-check me-3"></i> Segurança</a>
            <a href="#" class="nav-link text-dark rounded-pill d-flex align-items-center px-3 py-2"><i class="bi bi-question-circle me-3"></i> Ajuda</a>
        </div>
    </div>
</div>
