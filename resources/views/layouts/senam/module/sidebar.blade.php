<!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand">
                <span class="logo-text">Senam</span>
            </div>
            <button class="btn btn-link p-0 text-muted" id="sidebarCollapse">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
    <div class="sidebar-menu-container">
        <div class="sidebar-menu">
            <div class="menu-title">Menu</div>

            <!-- Dashboard -->
            <a class="nav-link {{ request()->routeIs('senam.dashboard') ? 'active' : '' }}"
                href="{{ route('senam.dashboard') }}">
                <i class="fas fa-home"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>

            <div class="menu-title">Master</div>

            <a class="nav-link {{ request()->routeIs('senam.master.class-schedule.index') ? 'active' : '' }}"
                href="{{ route('senam.master.class-schedule.index') }}">
                <i class="fas fa-calendar-alt"></i>
                <span class="nav-link-text">Jadwal Senam</span>
            </a>

            <a class="nav-link {{ request()->routeIs('senam.master.class-types.index') ? 'active' : '' }}"
                href="{{ route('senam.master.class-types.index') }}">
                <i class="fas fa-layer-group"></i>
                <span class="nav-link-text">Tipe Senam</span>
            </a>

            <a class="nav-link {{ request()->routeIs('senam.master.instructors.index') ? 'active' : '' }}"
                href="{{ route('senam.master.instructors.index') }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span class="nav-link-text">Instruktur</span>
            </a>

            <a class="nav-link {{ request()->routeIs('senam.master.members.index') ? 'active' : '' }}"
                href="{{ route('senam.master.members.index') }}">
                <i class="fas fa-users"></i>
                <span class="nav-link-text">Member</span>
            </a>

            <a class="nav-link {{ request()->routeIs('senam.master.non-members.index') ? 'active' : '' }}"
                href="{{ route('senam.master.non-members.index') }}">
                <i class="fas fa-user-times"></i>
                <span class="nav-link-text">Non-Member</span>
            </a>

            <a class="nav-link {{ request()->routeIs('senam.master.equipment.index') ? 'active' : '' }}"
                href="{{ route('senam.master.equipment.index') }}">
                <i class="fas fa-dumbbell"></i>
                <span class="nav-link-text">Alat</span>
            </a>

            <div class="menu-title">Laporan</div>

            <a class="nav-link {{ request()->routeIs('senam.master.reports.index') ? 'active' : '' }}" href="{{ route('senam.master.reports.index') }}">
                <i class="fas fa-file-alt"></i>
                <span class="nav-link-text">Laporan</span>
            </a>
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
