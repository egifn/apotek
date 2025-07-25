<nav class="top-navbar navbar navbar-expand-lg navbar-light bg-white">
    <button class="navbar-toggler" type="button" id="sidebarToggle">
        <span class="navbar-toggler-icon"></span>
    </button>

    <span class="navbar-brand">
        @yield('page-title')
    </span>

    <a href="{{ route('dashboard_master') }}" class="btn btn-primary btn-sm me-3">
        Dashboard Utama
    </a>
    <div class="navbar-nav ms-auto">
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                role="button" data-bs-toggle="dropdown" style="margin-right: 5px;">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span>{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <h6 class="dropdown-header">{{ Auth::user()->name }}</h6>
                    <div class="dropdown-header" style="font-size: 0.75rem; color: #6b7280;">
                        {{ Auth::user()->username }}</div>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
