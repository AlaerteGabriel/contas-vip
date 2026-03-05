@if (session('success'))
    <div class="alert alert-success border-success border-opacity-25 bg-success bg-opacity-10 d-flex align-items-center rounded-3" role="alert">
        <i class="fa-solid fa-check-circle fs-4 me-3 text-success"></i>
        <div>{{ session('success') }}</div>
    </div>
    @push('scripts')
        <script>notificar("{{ session('success') }}")</script>
    @endpush
@endif

@if (session('error'))
    <!-- Danger alert -->
    <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 d-flex align-items-center rounded-3" role="alert">
        <i class="fa-solid fa-times-circle fs-5 me-3 text-danger"></i>
        <div>{{ session('error') }}</div>
    </div>
    @push('scripts')
        <script>notificar("{{ session('error') }}", "error")</script>
    @endpush
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger border-danger border-opacity-25 bg-danger bg-opacity-10 d-flex align-items-center small p-3 rounded-3" role="alert">
            <i class="fa-solid fa-times-circle fs-5 me-2 text-danger"></i>
            <div><span class="">{{ $error }}</span></div>
        </div>
    @endforeach
@endif
