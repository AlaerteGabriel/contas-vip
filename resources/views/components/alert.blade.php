@if (session('success'))
    <div class="alert alert-success mb-4 rounded-3 border-0 bg-success bg-opacity-10 text-success" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <!-- Danger alert -->
    <div class="alert d-flex alert-danger" role="alert">
        <i class="ci-banned fs-lg pe-1 mt-1 me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert d-flex alert-danger" role="alert">
            <i class="ci-banned fs-lg pe-1 mt-1 me-2"></i>
            <div><span class="">{{ $error }}</span></div>
        </div>
    @endforeach
@endif
