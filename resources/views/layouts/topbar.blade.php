<header class="topbar">
    <div class="d-flex align-items-center">
        <button id="sidebarToggle" class="navbar-toggler-btn d-lg-none me-3">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="d-none d-md-block">
            @hasSection('header_title')
                <h5 class="mb-0 fw-bold">@yield('header_title')</h5>
                <small class="text-muted">@yield('header_subtitle')</small>
            @else
                @yield('breadcrumb')
            @endif
        </div>
    </div>

    <div class="topbar-right d-flex align-items-center">
        <!-- Notifications -->
        <!-- User Profile -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->us_nome ?? 'Admin') }}&background=4361ee&color=fff" alt="User Profile" width="40" height="40" class="rounded-circle me-2 border">
                <div class="d-none d-sm-block text-start lh-1">
                    <span class="d-block fw-semibold small">{{ auth()->user()->us_nome ?? 'Admin' }}</span>
                    <span class="d-block text-muted" style="font-size: 0.75rem;">Administrador</span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item py-2" href="{{ route('dashboard.perfil.index') }}"><i class="fa-regular fa-user me-2 text-muted"></i> Meu Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <!-- Form for logout -->
                    <a class="dropdown-item py-2 text-danger" href="{{ route('admin.login.logout') }}">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sair do sistema
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
