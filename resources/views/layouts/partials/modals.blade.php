<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Adicionar Saldo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('carteira.deposito.store') ?? '#' }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Quanto você quer depositar?</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0">R$</span>
                            <input type="number" name="valor" class="form-control bg-light border-0 fw-bold fs-4" placeholder="0,00" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted">Forma de depósito</label>
                        <div class="d-grid gap-2">
                            <input type="radio" class="btn-check" name="depositMethod" id="methodPix" value="pix" autocomplete="off" checked>
                            <label class="btn btn-outline-success text-start px-3 py-2 d-flex align-items-center" for="methodPix">
                                <i class="bi bi-lightning-charge fs-4 me-3"></i>
                                <div><strong class="d-block">PIX</strong><small class="opacity-75">Instantâneo</small></div>
                            </label>

                            <input type="radio" class="btn-check" name="depositMethod" id="methodBoleto" value="boleto" autocomplete="off">
                            <label class="btn btn-outline-primary text-start px-3 py-2 d-flex align-items-center" for="methodBoleto">
                                <i class="bi bi-upc-scan fs-4 me-3"></i>
                                <div><strong class="d-block">Boleto Bancário</strong><small class="opacity-75">1 a 2 dias úteis</small></div>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold">Gerar Pagamento</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Transferir Dinheiro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('carteira.transferir.store') ?? '#' }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Valor da transferência</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0">R$</span>
                            <input type="number" name="valor" class="form-control bg-light border-0 fw-bold fs-4 text-primary" placeholder="0,00" step="0.01" required>
                        </div>
                        <div class="text-end mt-1"><small class="text-muted">Saldo: R$ {{ number_format(auth()->user()->us_balanco, 2, ',', '.') }}</small></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Para quem?</label>
                        <input type="email" name="beneficiario" class="form-control bg-light border-0 py-2" placeholder="Chave PIX, E-mail" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">Continuar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Receive Modal -->
<div class="modal fade" id="receiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-center">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold w-100">Receber via PIX</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <p class="text-muted mb-4">Mostre este QR Code ou compartilhe sua chave.</p>

                <div class="bg-light p-3 rounded-4 d-inline-block mb-4 border shadow-sm">
                    <!-- Placeholder for QR Code -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ auth()->user()->us_email }}" alt="QR Code PIX" class="img-fluid rounded">
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-medium text-muted">Sua Chave PIX (Email)</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 py-2 fw-semibold" value="{{ auth()->user()->us_email }}" readonly>
                        <button class="btn btn-dark px-3" type="button"><i class="bi bi-files"></i> Copiar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
