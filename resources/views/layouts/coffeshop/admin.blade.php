<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ========== ROOT VARIABLES ========== */
        :root {
            --primary-color: #1a56db;
            --secondary-color: #1e429f;
            --light-gray: #f3f4f6;
            --medium-gray: #e5e7eb;
            --dark-gray: #6b7280;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0284c7;
        }

        /* ========== BASE STYLES ========== */
        body {
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #111827;
            background-color: var(--light-gray);
        }

        /* ========== LAYOUT STYLES ========== */
        .d-flex {
            display: flex;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        .mt-auto {
            margin-top: auto;
        }

        /* ========== SIDEBAR STYLES ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 225px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            height: 60px;
            font-size: 17px;
            font-weight: 800;
        }

        .sidebar-menu-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu {
            padding: 1px 0;
        }

        .menu-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6c757d;
            font-weight: 600;
            margin-top: 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #495057;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .nav-link.active {
            background-color: #e9f5ff;
            color: #007bff;
            border-left: 3px solid #007bff;
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed {
            width: 70px;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .nav-link-text,
        .sidebar.collapsed .menu-title {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Responsive behavior */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 250px;
            }

            .sidebar.collapsed .logo-text,
            .sidebar.collapsed .nav-link-text,
            .sidebar.collapsed .menu-title {
                display: inline;
            }

            .sidebar.collapsed .nav-link {
                justify-content: flex-start;
                padding: 0.75rem 1.5rem;
            }

            .sidebar.collapsed .nav-link i {
                margin-right: 0.75rem;
            }
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 225px;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: 70px;
        }

        .content-area {
            padding: 20px;
        }

        /* ========== TOP NAVBAR ========== */
        .top-navbar {
            background-color: white;
            border-bottom: 1px solid #d8d8d8;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            background: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        .navbar-toggler-icon {
            width: 1.2rem;
            height: 1.2rem;
        }

        /* Navbar Brand */
        .navbar-brand {
            font-size: 1.125rem;
            font-weight: 500;
            color: #1e293b;
            margin-left: 0.75rem;
        }

        /* User Avatar */
        .user-avatar {
            width: 2rem;
            height: 2rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-right: 0.5rem;
        }

        /* Dropdown Toggle */
        .dropdown-toggle {
            color: #475569 !important;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 6px;
            border: none;
            background: none;
        }

        .dropdown-toggle:hover {
            background-color: #f8fafc;
            color: #1e293b !important;
        }

        .dropdown-toggle:focus {
            box-shadow: none;
            outline: none;
        }

        .dropdown-toggle::after {
            margin-left: 0.5rem;
            border-top: 0.25em solid;
            border-right: 0.25em solid transparent;
            border-left: 0.25em solid transparent;
            vertical-align: middle;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 5px 0;
            margin-top: 0.25rem;
            margin-right: 5px;
            min-width: 180px;
        }

        /* Dropdown Header */
        .dropdown-header {
            padding: 0.3rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            background: none;
            border: none;
        }

        /* Dropdown Divider */
        .dropdown-divider {
            border-color: #f1f5f9;
        }

        /* Dropdown Item */
        .dropdown-item {
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            color: #475569;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .dropdown-item:focus {
            background-color: #f8fafc;
            color: #1e293b;
            outline: none;
        }

        .dropdown-item i {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-navbar {
                padding: 0.75rem 1rem;
            }

            .navbar-brand {
                font-size: 1rem;
                margin-left: 0.5rem;
            }

            .user-avatar {
                width: 1.75rem;
                height: 1.75rem;
                font-size: 0.7rem;
            }
        }

        @media (max-width: 576px) {
            .dropdown-toggle span {
                display: none;
            }

            .user-avatar {
                margin-right: 0;
            }
        }

        /* ========== USER AVATAR ========== */
        .user-avatar {
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 0.75rem;
            margin-right: 8px;
        }

        /* ========== CARD STYLES ========== */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: white;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--medium-gray);
            padding: 0.75rem 1.25rem;
        }

        .card-title {
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* ========== STAT CARD VARIATIONS ========== */
        .stat-card {
            border-left: 4px solid var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.danger {
            border-left-color: var(--danger-color);
        }

        .stat-card.info {
            border-left-color: var(--info-color);
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* ========== ALERT STYLES ========== */
        .alert {
            font-size: 0.8125rem;
            border-radius: 6px;
            border: none;
            margin-bottom: 1rem;
        }

        /* ========== BUTTON STYLES ========== */
        .btn {
            font-size: 0.8125rem;
            border-radius: 6px;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 0.875rem;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1a56db;
            border-color: #1a56db;
        }

        .btn-outline-danger:hover {
            color: white;
            background-color: var(--danger-color);
        }


        /* ========== TABLE STYLES ========== */
        .table {
            font-size: 0.8125rem;
        }

        .table th {
            font-weight: 600;
            color: var(--dark-gray);
            background-color: var(--light-gray);
        }

        .table-card {
            background-color: #ffffff;
            border: 0;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
            border-radius: 5px;
            margin-bottom: 20px;

        }

        .table-card-header {
            border: 0;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-card-body {
            padding: 0;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .header-icon {
            background-color: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
            padding: 0.5rem;
            margin-right: 0.75rem;
        }

        .header-title {
            margin-bottom: 0;
            color: #212529;
        }

        .table-container {
            overflow-x: auto;
        }

        .table-types-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-types-table thead {
            background-color: #dfdfdf;
        }

        .table-types-table th {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 400;
        }

        .table-types-table td {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            color: #474747;
            font-weight: 400;
        }

        /* .table-types-table tbody:hover {
            opacity: 10%;
        } */

        .table-responsive-custom {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            border-collapse: collapse;
        }

        .table-responsive-custom thead {
            background-color: #f8f9fa;
        }

        .table-responsive-custom th {
            white-space: nowrap;
            padding: 12px 16px;
            font-weight: 600;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .table-responsive-custom td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table-responsive-custom tr:last-child td {
            border-bottom: none;
        }

        .table-responsive-custom .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .table>:not(:first-child) {
            border-top: 0px solid currentColor;
        }

        /* ========== DROPDOWN STYLES ========== */
        /* Minimalist White Navbar */
        /* .top-navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        } */

        /* Navbar Toggler */


        /* ========== RESPONSIVE STYLES ========== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
            }
        }

        @media (max-width: 767.98px) {
            .content-area {
                padding: 15px;
            }
        }
    </style>

    <style>
        .menu-group {
            margin-bottom: 2px;
        }

        .menu-header {
            color: var(--dark-gray);
            padding: 0.5rem 0;
            padding-left: 1rem;
            margin: 2px 1px;
            border-radius: 3px;
            transition: all 0.2s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .menu-header i {
            width: 20px;
            margin-right: 10px;
            color: var(--dark-gray);
            text-align: center;
        }

        .menu-header.active i,
        .menu-header.active span,
        .menu-header:hover i,
        .menu-header:hover span {
            color: var(--primary-color);
        }

        .sidebar.collapsed .menu-header {
            display: flex;
            justify-content: space-evenly;
        }

        .sidebar.collapsed .dropdown-icon {
            display: none;
        }

        .menu-header {
            transition: opacity 0.3s ease;
        }

        .menu-header:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .menu-header .dropdown-icon {
            margin-left: auto;
            transition: transform 0.3s;
        }

        .submenu {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease-out;
        }

        .submenu.show {
            max-height: 500px;
            /* Adjust based on your content */
        }

        .submenu .nav-link {
            padding: 0.5rem 1rem 0.5rem 2rem;
        }

        .price-wrapper {
            white-space: nowrap;
            min-width: 140px;
        }

        .price-wrapper .currency {
            display: inline-block;
            width: 30px;
            text-align: left;
        }

        .price-wrapper .amount {
            display: inline-block;
            width: 100px;
            text-align: right;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #eee;
            padding: 10px 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #000;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #eee;
            /* padding: 1rem 1.5rem; */
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark-gray);
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(26, 86, 219, 0.1);
        }


        /* Responsive adjustments */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-content {
                border-radius: 0;
            }
        }
    </style>

    @yield('style')
</head>


<body>
    <div class="d-flex">

        @include('layouts.coffeshop.module.sidebar')



        <!-- Main Content -->
        <div class="main-content flex-grow-1" id="mainContent">
            <!-- Top Navbar -->
            @include('layouts.coffeshop.module.header')
            <!-- Page Content -->
            <div class="content-area">
                <div id="alert-container" class="position-fixed top-0 end-0 p-3"
                    style="z-index: 9999; width: 100%; max-width: 400px;">
                    <!-- Alert akan muncul di sini -->
                </div>

                @yield('content')
            </div>
        </div>
    </div>
    @stack('modal')


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js"></script>
    <script>
        // Toggle sidebar collapse
        document.getElementById('sidebarCollapse').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('collapsed');

            // Store state in localStorage
            localStorage.setItem('sidebarCollapsed',
                document.getElementById('sidebar').classList.contains('collapsed'));
        });

        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (window.innerWidth < 992 &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('collapsed');
            }

            // Prevent menu links from collapsing sidebar
            const menuLinks = document.querySelectorAll('.sidebar .nav-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (window.innerWidth < 992) {
                        document.getElementById('sidebar').classList.remove('show');
                    }
                    // Don't prevent default to allow normal navigation
                });
            });
        });
    </script>

    <script>
        function toggleMasterMenu() {
            const submenu = document.getElementById('masterSubmenu');
            const icon = document.querySelector('.menu-header .dropdown-icon');

            submenu.classList.toggle('show');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');

            // Toggle active class on header
            document.querySelector('.menu-header').classList.toggle('active');
        }

        // Pertahankan dropdown terbuka jika submenu aktif
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.submenu .nav-link.active');
            if (activeSubmenuItem) {
                const submenu = document.getElementById('masterSubmenu');
                const icon = document.querySelector('.menu-header .dropdown-icon');
                const header = document.querySelector('.menu-header');

                submenu.classList.add('show');
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                header.classList.add('active');
            }
        });
    </script>

    {{-- allert --}}
    <script>
        // Buat fungsi global
        window.createDynamicAlert = function(type, message) {
            // Hapus alert sebelumnya jika ada
            const existingAlerts = document.querySelectorAll('.alert-dismissible');
            existingAlerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });

            // Buat alert baru
            const alertContainer = document.createElement('div');
            alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
            alertContainer.setAttribute('role', 'alert');

            const iconClass = type === 'success' ? 'fa-check-circle' :
                type === 'danger' ? 'fa-exclamation-circle' : 'fa-info-circle';

            alertContainer.innerHTML = `
            <i class="fas ${iconClass} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            // Tempatkan alert di container khusus atau di bagian tertentu
            const targetElement = document.getElementById('alert-container') || document.body;
            targetElement.prepend(alertContainer);

            // Auto dismiss setelah beberapa detik
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertContainer);
                bsAlert.close();
            }, 5000);
        };

        // Fungsi untuk menampilkan error validasi dalam format list
        window.showValidationErrors = function(errors) {
            let errorMessages = '<ul class="mb-0">';
            for (const [key, messages] of Object.entries(errors)) {
                messages.forEach(message => {
                    errorMessages += `<li>${message}</li>`;
                });
            }
            errorMessages += '</ul>';

            createDynamicAlert('danger', `
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Validation Error:</strong>
            ${errorMessages}
        `);
        };
    </script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
