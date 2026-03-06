@extends('layouts.app')

@section('title', 'Email Marketing - Sistema Contas VIP')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#form-campanha').on('submit', function(e) {
                // Pega todos os values dos checkboxes marcados e junta com vírgula
                let idsSelecionados = $('.check-usuario:checked').map(function() {
                    return $(this).val();
                }).get().join(',');

                // Se o cara desmarcou todo mundo, trava o envio
                if (!idsSelecionados) {
                    e.preventDefault();
                    alert('Você precisa selecionar pelo menos um usuário na tabela!');
                    return false;
                }

                // Joga a string gigante no input hidden
                $('#usuarios_ids').val(idsSelecionados);

                // O formulário segue o envio normalmente a partir daqui
            });
        });
    </script>
@endpush

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="#" class="text-decoration-none text-muted">Configurações</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">
            Disparos em Massa - Criar campanha
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-4 fs-3 d-flex justify-content-center align-items-center" style="width: 70px; height: 70px;">
                <i class="fa-solid fa-bullhorn rotate-n-15"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Email Marketing</h4>
                <p class="text-muted mb-0">Comunique-se com seus clientes. Envie promoções, avisos de manutenção ou cobranças em lote.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">

        <x-alert />

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">

                <form action="{{ route('dashboard.config.email-marketing.store') }}" method="POST" id="form-campanha">
                    @csrf
                    <input type="hidden" name="usuarios_ids" id="usuarios_ids" value="">
                    @foreach($validated AS $key => $value)
                        @if(is_array($value))
                            @foreach($value AS $subValue)
                                <input type="hidden" name="filtros[{{ $key }}][]" value="{{ $subValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="filtros[{{ $key }}]" value="{{ $value }}">
                        @endif
                    @endforeach
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-envelope-open-text me-2"></i>1. Detalhes do Envio</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Nome da Campanha (Uso Interno)</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex: Minha campanha 2026" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Modelo de Template</label>
                            <select name="template_id" class="form-select form-control" required>
                                <option value="" selected disabled>Selecione o template...</option>
                                @foreach($templates AS $template)
                                    <option value="{{ $template->te_id }}">{{ $template->te_assunto }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Limite de Envios</label>
                            <input type="number" name="quantidade_envios" class="form-control" value="500" min="1" required>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info">
                                ATENÇÃO: Ao selecionar o modelo, o assunto do email será o que está salvo no modelo selecionado
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-3 border-top">
                        <a href="{{route('dashboard.config.email-marketing')}}" class="btn btn-outline-secondary">Cancelar</a>

                        <button type="submit" id="btn-confirmar-envio" class="btn btn-primary align-items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Confirmar e Criar Campanha
                        </button>

                    </div>
                </form>

            </div>
        </div>

    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white pt-4 pb-3 border-bottom-0 d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">Clientes encontrados para envio</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#ID</th>
                                <th class="ps-4">Nome</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contatosFiltrados AS $cli)
                            <tr>
                                <td>
                                    <input type="checkbox" class="check-usuario form-check-input" value="{{ $cli->cl_id }}" checked>
                                </td>
                                <td>{{ $cli->cl_nome }}</td>
                                <td>{{ $cli->cl_email_envio ?? $cli->cl_email}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
