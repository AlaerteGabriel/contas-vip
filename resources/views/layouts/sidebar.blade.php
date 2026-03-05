<nav id="sidebar" class="sidebar">
    <div class="brand">
        <i class="fa-solid fa-gem me-2"></i> Contas VIP
    </div>
    <ul class="nav flex-column mt-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
        </li>
        <li class="nav-item title mt-3 mb-1 px-4 text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Gestão</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard.clientes.*') ? 'active' : '' }}" href="{{ route('dashboard.clientes.index') }}">
                <i class="fa-solid fa-users"></i> Clientes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard.servicos.*') ? 'active' : '' }}" href="{{ route('dashboard.servicos.index') }}">
                <i class="fa-solid fa-box-open"></i> Serviços
            </a>
        </li>
        <li class="nav-item title mt-3 mb-1 px-4 text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Administração</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard.config.*') ? '' : 'collapsed' }}" href="#configSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('dashboard.config.*') ? 'true' : 'false' }}" role="button">
                <i class="fa-solid fa-gear"></i> Configurações
                <i class="fa-solid fa-chevron-down ms-auto" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
            </a>
            <ul class="collapse submenu {{ request()->routeIs('dashboard.config.*') ? 'show' : '' }}" id="configSubmenu">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.config.email-templates') ? 'active' : '' }}" href="{{ route('dashboard.config.email-templates') }}">Templates de Email</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.config.email-marketing') ? 'active' : '' }}" href="{{ route('dashboard.config.email-marketing') }}">Email Marketing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.config.mail-server') ? 'active' : '' }}" href="{{ route('dashboard.config.mail-server') }}">Servidor Email</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.config.importarExcel') ? 'active' : '' }}" href="{{ route('dashboard.config.importarExcel') }}">Importar Excel</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
