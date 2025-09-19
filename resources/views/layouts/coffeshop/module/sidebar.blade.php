<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand">
            <span class="logo-text">Coffe Tiga</span>
        </div>
        <button class="btn btn-link p-0 text-muted" id="sidebarCollapse">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu-container">
        <div class="sidebar-menu">
            <div class="menu-title">Menu</div>

            <!-- Dashboard (always visible) -->
            <a class="nav-link {{ request()->routeIs('coffeshop.dashboard') ? 'active' : '' }}"
                href="{{ route('coffeshop.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
            {{-- <a class="nav-link {{ request()->routeIs('coffeshop.master.analisa') ? 'active' : '' }}" href="#">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Analisa</span>
            </a> --}}

            <div class="menu-title">POS</div>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.products.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.products.index') }}">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Produk</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.stocks.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.stocks.index') }}">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Stok</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.pembelian.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.pembelian.index') }}">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Pembelian</span>
            </a>

            <div class="menu-title">Master</div>

            <a class="nav-link {{ request()->routeIs('coffeshop.master.branches.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.branches.index') }}">
                <i class="fas fa-building"></i>
                <span class="nav-link-text">Cabang</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.categories.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.categories.index') }}">
                <i class="fas fa-tags"></i>
                <span class="nav-link-text">Kategori</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.units.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.units.index') }}">
                <i class="fas fa-balance-scale"></i>
                <span class="nav-link-text">Satuan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.ingredients.index') ? 'active' : '' }}"
                href="{{ route('coffeshop.master.ingredients.index') }}">
                <i class="fas fa-leaf"></i>
                <span class="nav-link-text">Bahan Baku</span>
            </a>

            <div class="menu-title">Laporan</div>
            <a class="nav-link {{ request()->routeIs('coffeshop.reports.reports') ? 'active' : '' }}"
                href="{{ route('coffeshop.reports.reports') }}">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Laporan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.reports.laporan_penjualan') ? 'active' : '' }}"
                href="{{ route('coffeshop.reports.laporan_penjualan') }}">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Penjualan</span>
            </a>

            <a class="nav-link {{ request()->routeIs('coffeshop.reports.laporan_pembelian') ? 'active' : '' }}"
                href="{{ route('coffeshop.reports.laporan_pembelian') }}">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Pembelian</span>
            </a>
            {{-- <a class="nav-link {{ request()->routeIs('coffeshop.master.analisa') ? 'active' : '' }}" href="#">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Laporan Bulanan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('coffeshop.master.analisa') ? 'active' : '' }}" href="#">
                <i class="fas fa-coffee"></i>
                <span class="nav-link-text">Laporan Karyawan</span> --}}
            </a>
            <!-- Master Dropdown -->
            {{-- <div class="menu-group">
              <div class="menu-header" onclick="toggleMasterMenu()">
                  <i class="fas fa-cubes"></i>
                  <span class="nav-link-text">Master Data</span>
                  <i class="dropdown-icon fas fa-chevron-down"></i>
              </div>

              <div class="submenu" id="masterSubmenu">
                  <a class="nav-link {{ request()->routeIs('coffeshop.master.branches.*') ? 'active' : '' }}"
                      href="{{ route('coffeshop.master.branches.index') }}">
                      <i class="fas fa-building"></i>
                      <span class="nav-link-text">Cabang</span>
                  </a>
                  <a class="nav-link {{ request()->routeIs('coffeshop.master.categories.*') ? 'active' : '' }}"
                      href="{{ route('coffeshop.master.categories.index') }}">
                      <i class="fas fa-tags"></i>
                      <span class="nav-link-text">Kategori</span>
                  </a>
                  <a class="nav-link {{ request()->routeIs('coffeshop.master.units.*') ? 'active' : '' }}"
                      href="{{ route('coffeshop.master.units.index') }}">
                      <i class="fas fa-balance-scale"></i>
                      <span class="nav-link-text">Satuan</span>
                  </a>
                  <a class="nav-link {{ request()->routeIs('coffeshop.master.ingredients.*') ? 'active' : '' }}"
                      href="{{ route('coffeshop.master.ingredients.index') }}">
                      <i class="fas fa-leaf"></i>
                      <span class="nav-link-text">Bahan Baku</span>
                  </a>
              </div>
          </div> --}}
        </div>
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarCollapse = document.getElementById('sidebarCollapse');

            // Toggle sidebar collapse
            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });

            // For mobile, you might want to add a button to show/hide the sidebar
            const mobileMenuToggle = document.createElement('button');
            mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            mobileMenuToggle.classList.add('btn', 'btn-link', 'mobile-menu-toggle');
            document.body.appendChild(mobileMenuToggle);

            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 &&
                    !sidebar.contains(e.target) &&
                    e.target !== mobileMenuToggle &&
                    !mobileMenuToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Optional: Highlight active menu item
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <script>
        function toggleMasterMenu() {
            const submenu = document.getElementById('masterSubmenu');
            const icon = document.querySelector('.menu-header .dropdown-icon');

            submenu.classList.toggle('show');
            icon.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
        }

        // Optional: Collapse/expand based on current route
        document.addEventListener('DOMContentLoaded', function() {
            const masterRoutes = [
                'coffeshop.master.branches.*',
                'coffeshop.master.categories.*',
                'coffeshop.master.units.*',
                'coffeshop.master.ingredients.*',
                'coffeshop.master.products.*'
            ];

            const isMasterActive = masterRoutes.some(route => {
                return window.location.pathname.startsWith(route.replace('.*', ''));
            });

            if (isMasterActive) {
                document.getElementById('masterSubmenu').classList.add('show');
                document.querySelector('.menu-header .dropdown-icon').style.transform = 'rotate(180deg)';
            }
        });
    </script>
@endpush
