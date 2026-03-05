@extends('layouts.app')

@push('scripts')
    <script>let token = '<?=csrf_token()?>';</script>
    <script src="{{ asset('assets/plugins/uppy/uppy.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/settings/import.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/uppy/uppy.bundle.css') }}">
@endpush

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Configurações</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Importar Planilha Excel
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <x-alert />

    <div class="row">
        <div class="col-12">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        Importar dados em massa via CSV ou Excell
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add user-->
                            <a href="{{route('dashboard.index')}}" class="btn btn-primary">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M9.60001 11H21C21.6 11 22 11.4 22 12C22 12.6 21.6 13 21 13H9.60001V11Z" fill="currentColor"/>
                                        <path opacity="0.3" d="M9.6 20V4L2.3 11.3C1.9 11.7 1.9 12.3 2.3 12.7L9.6 20Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                Voltar
                            </a>
                            <!--end::Add user-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4" id="store_form">
                    <!--begin::Input group-->
                    <div class="row mb-10 validar">
                        <!-- icone -->
                        <div class="col-12 mb-10 mb-sm-0">
                            <div class="uppy" id="uppy">
                                <div class="uppy-drag"></div>
                                <div class="uppy-informer"></div>
                                <div class="uppy-progress"></div>
                                <div class="uppy-thumbnails"></div>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>

@endsection('content')
