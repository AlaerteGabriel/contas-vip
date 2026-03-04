<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm" style="background: linear-gradient(90deg, #0d6efd, #0dcaf0);">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('carteira.acc.index') ?? '#' }}">
            <i class="bi bi-wallet2 fs-3 me-2"></i> FinDash
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->us_nome ?? 'Usuário') }}&background=ffffff&color=0d6efd" class="rounded-circle me-2" alt="Avatar" width="32" height="32">
                        <span>{{ auth()->user()->us_nome ?? 'Sem Nome' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                        <li>
                            <a href="{{ route('carteira.login.logout') ?? '#' }}" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sair</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
