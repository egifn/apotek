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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
            font-size: 1.1rem;
            font-weight: 600;
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

        .table-responsive-custom {
            width: 100%;
            overflow-x: auto;
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
            max-height: 70vh;
            overflow-y: auto;
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

        .form-control-qty {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            transition: border-color 0.2s;
            width: 50px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(26, 86, 219, 0.1);
        }

        /* Product and Service Cards */
        .product-card,
        .service-card {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .product-card:hover,
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Exercise Class Table */
        .select-class-btn {
            min-width: 80px;
        }

        /* Participant Card */
        .participant-card {
            border-left: 4px solid var(--primary-color);
        }

        /* Modal Backdrop Fix */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 100vw;
            height: 100vh;
            background-color: #000;
            opacity: 0.5;
        }

        /* Modal Fix untuk mencegah kelap-kelip */
        .modal {
            z-index: 1050;
            overflow: hidden;
            outline: 0;
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-content {
                border-radius: 0;
            }

            .table-types-table th,
            .table-types-table td {
                padding: 0.5rem;
            }

            .header-title {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-content flex-grow-1" id="mainContent">
        <!-- Top Navbar -->
        <nav class="top-navbar navbar navbar-expand-lg navbar-light bg-white">
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>

            <span class="navbar-brand">
                @yield('page-title')
            </span>
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
        <!-- Page Content -->
        <div class="content-area">
            <div id="alert-container" class="position-fixed top-0 end-0 p-3"
                style="z-index: 9999; width: 100%; max-width: 400px;">
                <!-- Alert akan muncul di sini -->
            </div>
            <div class="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="posTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="products-tab" data-bs-toggle="tab"
                                            data-bs-target="#products" type="button" role="tab">Kopi Tiga</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="services-tab" data-bs-toggle="tab"
                                            data-bs-target="#services" type="button" role="tab">Barbershop</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="exercise-tab" data-bs-toggle="tab"
                                            data-bs-target="#exercise" type="button" role="tab">Senam</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="posTabsContent">

                                    <!-- Coffee Products Tab -->
                                    <div class="tab-pane fade show active" id="products" role="tabpanel">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="productSearch"
                                                placeholder="Search coffee products...">
                                        </div>
                                        <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
                                        </ul>
                                        <div class="tab-content mt-3" id="categoryTabContent">
                                            <!-- Products will be loaded here by category -->
                                        </div>
                                    </div>

                                    <!-- Barbershop Services Tab -->
                                    <div class="tab-pane fade" id="services" role="tabpanel">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="serviceSearch"
                                                placeholder="Search barbershop services...">
                                        </div>
                                        <div class="row" id="serviceList">
                                            <div class="col-12 text-center py-3">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Exercise Classes Tab -->
                                    <div class="tab-pane fade" id="exercise" role="tabpanel">
                                        <div class="row mb-3" style="display: flex;align-items: center;">
                                            <div class="col-md-4">
                                                <button class="btn btn-primary btn-sm" id="addMemberBtn"
                                                    style="width: 100%;">
                                                    <i class="fas fa-plus me-1"></i> Tambah Member
                                                </button>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="classSearch"
                                                    placeholder="Search exercise classes...">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table-types-table" id="classTable">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Kategori</th>
                                                        <th>Harga</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="classList">
                                                    <tr>
                                                        <td colspan="3" class="text-center py-3">
                                                            <div class="spinner-border text-primary" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Section -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"
                                style="display: flex;align-items: center;justify-content: space-between;">
                                <div>
                                    <h5 class="card-title mb-0">Order Summary</h5>
                                </div>
                                <div>
                                    <a href="{{ route('dashboard_kasir') }}" class="btn btn-primary btn-sm"
                                        id="addMemberBtn" style="width: 100%;">
                                        Rekap
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table-types-table" id="orderTable">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="emptyOrderMessage">
                                                <td colspan="5" class="text-center text-muted py-3">No items added
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <h5>Total:</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 id="totalAmount">Rp 0</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="mb-3">
                                    <label for="paymentAmount" class="form-label">Jumlah Pembayaran</label>
                                    <input type="number" class="form-control" id="paymentAmount"
                                        placeholder="Masukkan jumlah pembayaran">
                                </div>
                                <div class="mb-3">
                                    <label for="changeAmount" class="form-label">Kembalian</label>
                                    <input type="text" class="form-control" id="changeAmount" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                                    <select class="form-control" id="paymentMethod">
                                        <option value="cash">Cash</option>
                                        <option value="debit">Debit Card</option>
                                        <option value="credit">Credit Card</option>
                                        <option value="e-wallet">E-Wallet</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-100" id="processTransaction">Process
                                    Transaction</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Input Peserta Kelas -->
    <div class="modal fade" id="exerciseModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exerciseModalLabel">Daftar Peserta Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-outline-primary" id="addParticipantBtn">
                            <i class="fas fa-plus"></i> Tambah Peserta
                        </button>
                    </div>
                    <div id="participantsContainer">
                        <!-- Participant forms will be added here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmParticipants">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template untuk form peserta (hidden) -->
    <template id="participantTemplate">
        <div class="card participant-card mb-3" data-participant-id="{id}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title mb-0">Peserta #<span class="participant-number">{participantNumber}</span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-danger remove-participant">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input participant-type" type="radio"
                                name="participantType_{id}" id="memberType_{id}" value="member" checked>
                            <label class="form-check-label" for="memberType_{id}">Member</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input participant-type" type="radio"
                                name="participantType_{id}" id="nonMemberType_{id}" value="non_member">
                            <label class="form-check-label" for="nonMemberType_{id}">Non-Member</label>
                        </div>
                    </div>
                </div>

                <div class="member-fields">
                    <div class="mb-2 position-relative">
                        <label class="form-label">Cari Member</label>
                        <input type="text" class="form-control member-search-input"
                            placeholder="Ketik nama member..." data-participant-id="{id}">
                        <ul class="list-group position-absolute w-100 mt-1 member-dropdown"
                            style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></ul>
                    </div>
                    <div class="member-details" id="memberDetails_{id}" style="display: none;">
                        <small class="text-muted">
                            Sisa Kuota: <span class="quota-remaining">0</span>/<span class="quota-total">0</span>
                            (Berlaku hingga: <span class="quota-end">-</span>)
                        </small>
                    </div>
                </div>

                <div class="non-member-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Nama Non-Member</label>
                            <input type="text" class="form-control non-member-name" placeholder="Nama lengkap">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control non-member-phone" placeholder="No. telepon">
                        </div>
                    </div>
                </div>

                <div class="price-info mt-2">
                    <strong>Harga: <span class="participant-price">Rp 0</span></strong>
                </div>
            </div>
        </div>
    </template>

    {{-- modal tambah member --}}
    <div class="modal fade" id="memberModalInput">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="memberModalLabel">Tambah Member</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="memberFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_name" class="form-label">Nama Member</label>
                                <input type="text" class="form-control" id="insert_name" name="insert_name"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="insert_phone" name="insert_phone"
                                    required>
                            </div>
                        </div>
                        <input type="date" class="form-control" id="insert_join_date" name="insert_join_date"
                            required hidden>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="insert_membership_type" class="form-label">Jenis Membership</label>
                                <select class="form-select" id="insert_membership_type" name="insert_membership_type"
                                    required>
                                    <option value="">Pilih Jenis Membership</option>
                                    <option value="Senam">Senam</option>
                                    <option value="Sewa">Sewa</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="insert_start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="insert_start_date"
                                    name="insert_start_date" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="insert_end_date" class="form-label">Tanggal Berakhir</label>
                                <input type="date" class="form-control" id="insert_end_date"
                                    name="insert_end_date" required>
                            </div>
                            <div class="col-md-4 mb-3" hidden>
                                <label for="insert_total_quota" class="form-label">Kuota</label>
                                <input type="number" class="form-control" id="insert_total_quota"
                                    name="insert_total_quota" min="1" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_insert" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_insert_send" class="btn btn-primary"
                            style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let orderItems = [];
            let currentMember = null;
            let selectedClassData = null;

            // DOM Elements - Member Modal
            const buttonShowModalFormInput = document.getElementById('addMemberBtn');
            const buttonInsert = document.getElementById('button_insert');
            const buttonInsertSend = document.getElementById('button_insert_send');
            const componentModalFormInsert = document.getElementById('memberModalInput');
            const inName = document.getElementById('insert_name');
            const inPhone = document.getElementById('insert_phone');
            const inJoinDate = document.getElementById('insert_join_date');
            const inMembershipType = document.getElementById('insert_membership_type');
            const inTotalQuota = document.getElementById('insert_total_quota');
            const inStartDate = document.getElementById('insert_start_date');
            const inEndDate = document.getElementById('insert_end_date');

            // DOM Elements - Coffee Products
            const productSearch = document.getElementById('productSearch');

            // DOM Elements - Barbershop Services
            const serviceSearch = document.getElementById('serviceSearch');
            const serviceList = document.getElementById('serviceList');

            // DOM Elements - Exercise Classes
            const classSearch = document.getElementById('classSearch');
            const classList = document.getElementById('classList');

            // DOM Elements - Order Summary
            const orderTable = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
            const emptyOrderMessage = document.getElementById('emptyOrderMessage');
            const totalAmount = document.getElementById('totalAmount');
            const paymentAmount = document.getElementById('paymentAmount');
            const changeAmount = document.getElementById('changeAmount');
            const processBtn = document.getElementById('processTransaction');
            // Variabel untuk modal
            let exerciseModal = null;

            // ===================================================================================
            // COFFEE PRODUCTS SECTION
            // ===================================================================================
            // 1. Variabel global di paling atas
            let categories = [];
            let selectedCategory = 'all';
            // 2. Function selectCategory harus didefinisikan sebelum dipanggil
            function selectCategory(categoryId) {
                selectedCategory = categoryId;
                const searchTerm = document.getElementById('productSearch').value;
                loadProducts(searchTerm);
            }
            // 3. Function lainnya
            async function loadCategories() {
                try {
                    const response = await fetch(`{{ route('pos.categories') }}`);
                    const data = await response.json();

                    if (data.status) {
                        categories = data.data;
                        renderCategoryTabs();
                        loadProducts();
                    }
                } catch (error) {
                    console.error('Error loading categories:', error);
                }
            }

            function renderCategoryTabs() {
                const categoryTabs = document.getElementById('categoryTabs');
                if (!categoryTabs) return;

                let tabsHtml = `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" 
                                data-category="all" 
                                type="button">
                            All Products
                        </button>
                    </li>
                `;

                categories.forEach(category => {
                    tabsHtml += `
                        <li class="nav-item" role="presentation">
                            <button class="nav-link category-tab" 
                                    data-category="${category.id}" 
                                    type="button">
                                ${category.name}
                            </button>
                        </li>
                    `;
                });

                categoryTabs.innerHTML = tabsHtml;
            }

            async function loadProducts(search = '') {
                const categoryTabContent = document.getElementById('categoryTabContent');
                if (!categoryTabContent) return;

                if (selectedCategory === 'all') {
                    categoryTabContent.innerHTML = `
                        <div class="tab-pane fade show active" id="all-products" role="tabpanel">
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    categoryTabContent.innerHTML = `
                        <div class="tab-pane fade show active" id="category-${selectedCategory}" role="tabpanel">
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary, role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    `;
                }

                try {
                    let url = `{{ route('pos.products') }}?search=${encodeURIComponent(search)}`;
                    if (selectedCategory !== 'all') {
                        url += `&category_id=${selectedCategory}`;
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.status) {
                        renderProductsByCategory(data.data);
                    }
                } catch (error) {
                    console.error('Error loading products:', error);
                }
            }

            function renderProductsByCategory(products) {
                const categoryTabContent = document.getElementById('categoryTabContent');
                if (!categoryTabContent) return;

                let html = '';
                if (selectedCategory === 'all') {
                    html = '<div class="tab-pane fade show active" id="all-products" role="tabpanel">';
                } else {
                    html =
                        `<div class="tab-pane fade show active" id="category-${selectedCategory}" role="tabpanel">`;
                }

                const category = categories.find(c => c.id == selectedCategory);
                const categoryName = selectedCategory === 'all' ? 'All Products' : (category ? category.name :
                    'Products');

                html += `
                    <h5 class="mb-3">${categoryName}</h5>
                    <div class="row">
                        ${renderProductCards(products)}
                    </div>
                </div>`;

                categoryTabContent.innerHTML = html;
            }

            function renderProductCards(products) {
                if (!products || products.length === 0) {
                    return '<div class="col-12 text-center py-3 text-muted">No products found</div>';
                }

                return products.map(product => `
                    <div class="col-md-4 mb-3">
                        <div class="card product-card h-100" data-id="${product.id}" 
                            data-name="${product.name}" 
                            data-price="${product.selling_price}" 
                            data-type="product">
                            ${product.image ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <img src="${product.image}" class="card-img-top" alt="${product.name}" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                style="height: 150px; object-fit: cover;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ` : `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                style="height: 150px;">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <i class="fas fa-coffee fa-3x text-muted"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `}
                            <div class="card-body">
                                <h6 class="card-title">${product.name}</h6>
                                <p class="card-text">${formatRupiah(product.selling_price)}</p>
                                ${product.code ? `<small class="text-muted">Code: ${product.code}</small>` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // 4. Initialize saat DOM ready
            loadCategories();
            // Event listener untuk search
            if (productSearch) {
                productSearch.addEventListener('input', (e) => {
                    loadProducts(e.target.value);
                });
            }
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('category-tab')) {
                    e.preventDefault();

                    // Update active class
                    document.querySelectorAll('.category-tab').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    e.target.classList.add('active');

                    // Panggil selectCategory
                    const categoryId = e.target.dataset.category;
                    selectedCategory = categoryId;
                    const searchInput = document.getElementById('productSearch');
                    loadProducts(searchInput ? searchInput.value : '');
                }
            });

            // ======================
            // BARBERSHOP SERVICES SECTION
            // ======================
            async function loadServices(search = '') {
                if (!serviceList) return;

                serviceList.innerHTML = `
                    <div class="col-12 text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                try {
                    const response = await fetch(
                        `{{ route('pos.services') }}?search=${encodeURIComponent(search)}`);
                    const data = await response.json();

                    if (data.status) {
                        let html = '';
                        data.data.forEach(service => {
                            html += `
                                <div class="col-md-4 mb-3">
                                    <div class="card service-card" data-id="${service.id}" 
                                        data-name="${service.name}" 
                                        data-price="${service.price}" 
                                        data-type="service">
                                        <div class="card-body">
                                            <h6 class="card-title">${service.name}</h6>
                                            <p class="card-text">${formatRupiah(service.price)}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        serviceList.innerHTML = html ||
                            '<div class="col-12 text-center py-3 text-muted">No services found</div>';
                    }
                } catch (error) {
                    console.error('Error loading services:', error);
                    serviceList.innerHTML =
                        '<div class="col-12 text-center py-3 text-danger">Error loading services</div>';
                }
            }

            // ======================
            // EXERCISE CLASSES SECTION
            // ======================
            // Variabel global tambahan
            let participants = [];
            let participantCount = 0;

            // Modifikasi fungsi loadClasses
            async function loadClasses(date = '', search = '') {
                if (!classList) return;

                classList.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </td>
                    </tr>
                `;

                try {
                    const params = new URLSearchParams();
                    if (date) params.append('date', date);
                    if (search) params.append('search', search);

                    const response = await fetch(`{{ route('pos.exercise-classes') }}?${params.toString()}`);
                    const data = await response.json();

                    if (data.status) {
                        let html = '';

                        if (data.data.length === 0) {
                            html = `
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">
                                        Tidak ada kelas yang tersedia
                                    </td>
                                </tr>
                            `;
                        } else {
                            data.data.forEach(cls => {
                                // console.log(cls.id);

                                html += `
                                    <tr>
                                        <td>${cls.services_name}</td>
                                        <td>${cls.class_name}</td>
                                        <td>${formatRupiah(cls.price)}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary select-class-btn" 
                                                    data-id="${cls.id}"
                                                    data-name="${cls.services_name}"
                                                    data-jenis="${cls.class_name}"
                                                    data-price="${cls.price}">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        }

                        classList.innerHTML = html;
                    }
                } catch (error) {
                    console.error('Error loading classes:', error);
                    classList.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center py-3 text-danger">
                                Error loading classes
                            </td>
                        </tr>
                    `;
                }
            }

            // Modifikasi event handler untuk tombol pilih kelas
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.select-class-btn');
                if (btn) {
                    selectedClassData = {
                        id: btn.dataset.id,
                        name: btn.dataset.name,
                        jenis: btn.dataset.jenis,
                        price: parseInt(btn.dataset.price)
                    };
                    console.log(selectedClassData);


                    // Tampilkan modal untuk input peserta
                    showExerciseModal();
                }
            });

            // Fungsi untuk menampilkan modal
            function showExerciseModal() {
                const modalClassName = document.getElementById('modalClassName');
                if (modalClassName) {
                    modalClassName.textContent = selectedClassData.name;
                }

                const participantsContainer = document.getElementById('participantsContainer');
                if (participantsContainer) {
                    participantsContainer.innerHTML = '';
                }

                participants = [];
                participantCount = 0;

                // Tambahkan peserta pertama
                addParticipant();

                const modalElement = document.getElementById('exerciseModal');
                if (modalElement) {
                    // Inisialisasi modal hanya sekali
                    if (!exerciseModal) {
                        exerciseModal = new bootstrap.Modal(modalElement, {
                            backdrop: 'static',
                            keyboard: false
                        });
                    }

                    // Tambahkan event listener untuk menutup modal
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        // Reset state modal jika diperlukan
                        participants = [];
                        participantCount = 0;
                    });

                    exerciseModal.show();
                }
            }

            // Fungsi untuk menambah form peserta
            function addParticipant() {
                participantCount++;
                const template = document.getElementById('participantTemplate');
                if (!template) return;

                const templateContent = template.innerHTML;
                const participantHtml = templateContent
                    .replace(/{id}/g, participantCount)
                    .replace(/{participantNumber}/g, participantCount);

                const participantDiv = document.createElement('div');
                participantDiv.innerHTML = participantHtml;
                const card = participantDiv.firstElementChild;

                const participantsContainer = document.getElementById('participantsContainer');
                if (participantsContainer) {
                    participantsContainer.appendChild(card);

                    // Event listener untuk tombol hapus
                    const removeBtn = card.querySelector('.remove-participant');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            card.remove();
                            participants = participants.filter(p => p.participantId !== participantCount);
                            updateParticipantNumbers();
                        });
                    }

                    // Event listener untuk radio button toggle
                    initParticipantEvents(card, participantCount);
                    updateParticipantNumbers();
                }
            }

            // Fungsi untuk update nomor peserta setelah tambah/hapus
            function updateParticipantNumbers() {
                const cards = document.querySelectorAll('#participantsContainer .participant-card');
                cards.forEach((card, idx) => {
                    const numberSpan = card.querySelector('.participant-number');
                    if (numberSpan) numberSpan.textContent = idx + 1;
                });
            }

            // Inisialisasi select2 untuk pencarian member
            function initMemberSelect(selectElement, participantId) {
                if (!selectElement) return;

                $(selectElement).select2({
                    placeholder: "Pilih Member...",
                    ajax: {
                        url: "{{ route('pos.search-members') }}",
                        type: "POST",
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: function(params) {
                            // Kirimkan juga jenis class untuk filter di backend
                            return {
                                search: params.term,
                                jenis: selectedClassData && selectedClassData.jenis ? selectedClassData
                                    .jenis : ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.data.map(member => ({
                                    id: member.id,
                                    text: `${member.name} (${member.member_id})`
                                }))
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 2,
                    dropdownParent: $('#exerciseModal') // penting agar tidak tertutup modal
                });

                $(selectElement).on('select2:select', function(e) {
                    const memberId = e.params.data.id;
                    // PATCH: panggil loadMemberDetails dan updateParticipant agar data quota sinkron
                    loadMemberDetails(memberId, participantId);
                    // updateParticipant akan dipanggil di dalam loadMemberDetails setelah quota dicek
                });
            }

            // Fungsi untuk mencari member
            async function searchMembers(query, dropdownEl, participantId) {
                if (query.length < 2) {
                    dropdownEl.style.display = 'none';
                    return;
                }

                try {
                    // Kirimkan juga jenis jika ada
                    const response = await fetch("{{ route('pos.search-members') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            search: query,
                            jenis: selectedClassData && selectedClassData.jenis ?
                                selectedClassData.jenis : ''
                        })
                    });

                    const data = await response.json();
                    dropdownEl.innerHTML = '';

                    if (data.status && data.data.length > 0) {
                        data.data.forEach(member => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action';
                            li.innerHTML = `
                                <div>
                                    <strong>${member.name}</strong>
                                </div>
                            `;
                            li.dataset.id = member.id;
                            li.dataset.name = member.name;
                            li.addEventListener('click', () => {
                                const input = dropdownEl.previousElementSibling;
                                input.value = member.name;
                                dropdownEl.style.display = 'none';
                                loadMemberDetails(member.id, participantId);
                            });
                            dropdownEl.appendChild(li);
                        });
                        dropdownEl.style.display = 'block';
                    } else {
                        dropdownEl.style.display = 'none';
                        dropdownEl.innerHTML = '<li class="list-group-item text-muted">No members found</li>';
                    }
                } catch (err) {
                    console.error('Error searching member:', err);
                    dropdownEl.style.display = 'none';
                }
            }

            // Event listener untuk input pencarian member
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('member-search-input')) {
                    const dropdown = e.target.nextElementSibling;
                    const participantId = e.target.dataset.participantId;
                    const debouncedSearch = debounce(searchMembers, 300);
                    debouncedSearch(e.target.value, dropdown, participantId);
                }
            });

            // Fungsi debounce
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.member-fields')) {
                    document.querySelectorAll('.member-dropdown').forEach(dd => dd.style.display = 'none');
                }
            });

            // Load detail member dan cek kuota
            async function loadMemberDetails(memberId, participantId) {
                try {
                    const response = await fetch("{{ route('pos.check-member') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            member_id: memberId
                        })
                    });

                    const data = await response.json();

                    if (data.status) {
                        const member = data.member;
                        const memberDetails = document.getElementById(`memberDetails_${participantId}`);
                        const quotaRemaining = document.querySelector(
                            `#memberDetails_${participantId} .quota-remaining`);
                        const quotaTotal = document.querySelector(
                            `#memberDetails_${participantId} .quota-total`);
                        const quotaEnd = document.querySelector(`#memberDetails_${participantId} .quota-end`);
                        const priceElement = document.querySelector(
                            `.participant-card:nth-child(${participantId}) .participant-price`);

                        if (data.quota && data.quota.remaining_quota > 0) {
                            // Member memiliki kuota
                            if (quotaRemaining) quotaRemaining.textContent = data.quota.remaining_quota;
                            if (quotaTotal) quotaTotal.textContent = data.quota.total_quota;
                            if (quotaEnd) quotaEnd.textContent = new Date(data.quota.end_date)
                                .toLocaleDateString('id-ID');
                            if (priceElement) priceElement.textContent = 'Rp 0 (Pakai Kuota)';

                            // Simpan data peserta
                            updateParticipant(participantId, {
                                type: 'member',
                                member_id: member.id,
                                member_name: member.name,
                                use_quota: true,
                                price: 0
                            });
                        } else {
                            // Member tidak memiliki kuota
                            if (quotaRemaining) quotaRemaining.textContent = '0';
                            if (quotaTotal) quotaTotal.textContent = '0';
                            if (quotaEnd) quotaEnd.textContent = 'Tidak ada kuota aktif';
                            if (priceElement) priceElement.textContent = formatRupiah(selectedClassData.price);

                            // Simpan data peserta
                            updateParticipant(participantId, {
                                type: 'member',
                                member_id: member.id,
                                member_name: member.name,
                                use_quota: false,
                                price: selectedClassData.price
                            });
                        }

                        // Set data-member-id pada input member-search
                        const memberInput = document.querySelector(
                            `.participant-card[data-participant-id="${participantId}"] .member-search-input`
                        );
                        if (memberInput) {
                            memberInput.dataset.memberId = member.id;
                        }

                        if (memberDetails) {
                            memberDetails.style.display = 'block';
                        }
                    } else {
                        createDynamicAlert('danger', 'Member tidak ditemukan');
                    }
                } catch (error) {
                    console.error('Error loading member details:', error);
                    createDynamicAlert('danger', 'Gagal memuat detail member');
                }
            }

            // Inisialisasi event untuk form peserta
            function initParticipantEvents(participantCard, participantId) {
                if (!participantCard) return;

                // Toggle antara member dan non-member
                const typeRadios = participantCard.querySelectorAll('.participant-type');
                typeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        const memberFields = participantCard.querySelector('.member-fields');
                        const nonMemberFields = participantCard.querySelector('.non-member-fields');
                        const priceElement = participantCard.querySelector('.participant-price');
                        const memberInput = participantCard.querySelector('.member-search-input');

                        if (this.value === 'member') {
                            if (memberFields) memberFields.style.display = 'block';
                            if (nonMemberFields) nonMemberFields.style.display = 'none';
                            if (priceElement) priceElement.textContent = 'Rp 0';

                            // Reset data peserta
                            updateParticipant(participantId, {
                                type: 'member',
                                non_member_name: '',
                                non_member_phone: '',
                                price: 0
                            });

                            // PATCH: jika input member sudah terisi, panggil ulang loadMemberDetails
                            if (memberInput && memberInput.value && memberInput.dataset.memberId) {
                                loadMemberDetails(memberInput.dataset.memberId, participantId);
                            }
                        } else {
                            if (memberFields) memberFields.style.display = 'none';
                            if (nonMemberFields) nonMemberFields.style.display = 'block';
                            if (priceElement) priceElement.textContent = formatRupiah(
                                selectedClassData.price);

                            // Reset data peserta
                            updateParticipant(participantId, {
                                type: 'non_member',
                                member_id: null,
                                member_name: '',
                                use_quota: false,
                                price: selectedClassData.price
                            });
                        }
                    });
                });

                // Input nama non-member
                const nonMemberName = participantCard.querySelector('.non-member-name');
                if (nonMemberName) {
                    nonMemberName.addEventListener('input', function() {
                        updateParticipant(participantId, {
                            non_member_name: this.value
                        });
                    });
                }

                // Input telepon non-member
                const nonMemberPhone = participantCard.querySelector('.non-member-phone');
                if (nonMemberPhone) {
                    nonMemberPhone.addEventListener('input', function() {
                        updateParticipant(participantId, {
                            non_member_phone: this.value
                        });
                    });
                }

                // Hapus peserta
                const removeBtn = participantCard.querySelector('.remove-participant');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        participantCard.remove();
                        // Hapus dari array participants
                        participants = participants.filter(p => p.participantId !== participantId);
                    });
                }
            }

            // Update data peserta
            function updateParticipant(participantId, data) {
                const existingIndex = participants.findIndex(p => p.participantId === participantId);

                if (existingIndex >= 0) {
                    participants[existingIndex] = {
                        ...participants[existingIndex],
                        ...data
                    };
                } else {
                    participants.push({
                        participantId,
                        ...data
                    });
                }
            }

            // Event listener untuk tombol tambah peserta
            const addParticipantBtn = document.getElementById('addParticipantBtn');
            if (addParticipantBtn) {
                addParticipantBtn.addEventListener('click', function() {
                    addParticipant();
                });
            }

            // Event listener untuk konfirmasi peserta
            const confirmParticipants = document.getElementById('confirmParticipants');
            if (confirmParticipants) {
                confirmParticipants.addEventListener('click', function() {
                    if (participants.length === 0) {
                        createDynamicAlert('warning', 'Tambahkan setidaknya satu peserta');
                        return;
                    }

                    let isValid = true;
                    let errorMessage = '';

                    for (const participant of participants) {
                        if (participant.type === 'member' && !participant.member_id) {
                            isValid = false;
                            errorMessage = 'Harap pilih member untuk semua peserta yang bertipe member';
                            break;
                        }

                        if (participant.type === 'non_member') {
                            if (!participant.non_member_name) {
                                isValid = false;
                                errorMessage = 'Harap isi nama untuk semua peserta non-member';
                                break;
                            }

                            if (!participant.non_member_phone) {
                                isValid = false;
                                errorMessage = 'Harap isi telepon untuk semua peserta non-member';
                                break;
                            }
                        }
                    }
                    // Tambahkan ke order summary
                    addParticipantsToOrder();

                    // Tutup modal
                    if (exerciseModal) {
                        exerciseModal.hide();
                    }
                });
            }

            // Fungsi addParticipantsToOrder
            function addParticipantsToOrder() {
                const cards = document.querySelectorAll('#participantsContainer .participant-card');
                cards.forEach(card => {
                    // Ambil id peserta dari data attribute
                    const participantId = Number(card.getAttribute('data-participant-id'));

                    // Ambil tipe peserta
                    const radioMember = card.querySelector(
                        `input[name="participantType_${participantId}"]:checked`);
                    const type = radioMember ? radioMember.value : 'member';

                    let id_member = 0;
                    let memberName = '';
                    let price = selectedClassData ? selectedClassData.price : 0;
                    let priceLabel = card.querySelector('.participant-price');
                    let priceText = priceLabel ? priceLabel.textContent : '';
                    if (priceText.includes('Pakai Kuota') || priceText.trim() === 'Rp 0') {
                        price = 0;
                    }

                    if (type === 'member') {
                        const memberInput = card.querySelector('.member-search-input');
                        memberName = memberInput ? memberInput.value : '';
                        let participantData = participants.find(p => p.participantId === participantId);
                        id_member = participantData && participantData.member_id ? participantData
                            .member_id : 0;
                        if (!id_member && memberInput && memberInput.dataset.memberId) {
                            id_member = memberInput.dataset.memberId;
                        }
                        addToOrder(
                            `${selectedClassData.jenis} ${selectedClassData.name} - ${memberName}${price === 0 ? '' : ''}`,
                            price,
                            'class',
                            selectedClassData.id,
                            id_member
                        );
                    } else if (type === 'non_member') {
                        const nonMemberName = card.querySelector('.non-member-name')?.value || '';
                        const nonMemberPhone = card.querySelector('.non-member-phone')?.value || '';
                        addToOrder(
                            `${selectedClassData.jenis} ${selectedClassData.name} - ${nonMemberName} (Non-Member)`,
                            price,
                            'class',
                            selectedClassData.id,
                            0
                        );
                    }
                });
            }

            // Event listener untuk radio button
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('participant-type')) {
                    const memberFields = document.querySelector('.member-fields');
                    const nonMemberFields = document.querySelector('.non-member-fields');
                    const searchInput = document.querySelector('.member-search-input');

                    if (e.target.value === 'member') {
                        memberFields.style.display = 'block';
                        nonMemberFields.style.display = 'none';
                        searchInput.value = ''; // Kosongkan kolom pencarian member
                    } else {
                        memberFields.style.display = 'none';
                        nonMemberFields.style.display = 'block';
                    }
                }
            });

            // js tambah member
            buttonShowModalFormInput.addEventListener('click', function() {
                // Reset form
                inName.value = '';
                inPhone.value = '';
                inJoinDate.value = '';
                inMembershipType.value = '';
                inTotalQuota.value = '0';
                inStartDate.value = '';
                inEndDate.value = '';

                // Set default dates
                const today = new Date().toISOString().split('T')[0];
                inJoinDate.value = today;
                inStartDate.value = today;

                // Set end date to 1 month from today
                const endDate = new Date();
                endDate.setMonth(endDate.getMonth() + 1);
                inEndDate.value = endDate.toISOString().split('T')[0];

                new bootstrap.Modal(componentModalFormInsert).show();
            });

            buttonInsert.addEventListener('click', async function() {
                buttonInsert.style.display = 'none';
                buttonInsertSend.style.display = 'inline-block';

                try {
                    const formData = {
                        name: inName.value,
                        phone: inPhone.value,
                        join_date: inJoinDate.value,
                        membership_type: inMembershipType.value,
                        total_quota: inTotalQuota.value,
                        start_date: inStartDate.value,
                        end_date: inEndDate.value
                    };

                    const response = await axios.post(`{{ route('senam.master.members.store') }}`,
                        formData);
                    const data = response.data;

                    if (data.status === true) {
                        createDynamicAlert('success', data.message || 'Member berhasil ditambahkan');

                        // Close the modal
                        bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                    } else {
                        if (data.type === 'validation') {
                            showValidationErrors(data.errors);
                        } else {
                            createDynamicAlert('danger', data.message ||
                                'Terjadi kesalahan saat menambahkan member');
                        }
                    }

                } catch (error) {
                    console.error('Error:', error);
                    if (error.response && error.response.data) {
                        const errorData = error.response.data;
                        if (errorData.type === 'validation') {
                            showValidationErrors(errorData.errors);
                        } else {
                            createDynamicAlert('danger', errorData.message || 'Terjadi kesalahan');
                        }
                    } else {
                        createDynamicAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                    }
                } finally {
                    buttonInsert.style.display = 'inline-block';
                    buttonInsertSend.style.display = 'none';
                }
            });

            // ======================
            // ORDER SUMMARY SECTION
            // ======================
            // Add item to order (for coffee and barbershop)
            function handleItemClick(event) {
                const card = event.target.closest('.product-card, .service-card');
                if (!card) return;

                const id = card.dataset.id;
                const name = card.dataset.name;
                const price = parseFloat(card.dataset.price);
                const type = card.dataset.type;

                // Check if item exists
                const existingItem = orderItems.find(item => item.id === id && item.type === type);

                if (existingItem) {
                    existingItem.quantity += 1;
                    existingItem.subtotal = existingItem.quantity * existingItem.price;
                } else {
                    orderItems.push({
                        id: id,
                        name: name,
                        price: price,
                        quantity: 1,
                        subtotal: price,
                        type: type
                    });
                }

                updateOrderTable();
            }

            // Fungsi addToOrder
            function addToOrder(name, price, type, id = null, member_id = null) {
                // Generate ID jika tidak provided
                // Untuk class, id adalah id kelas
                const itemId = type === 'class' ? id : (id ||
                    `${type}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`);

                // Untuk class, tambahkan detail peserta
                let participantType = null;
                let className = null;
                let memberId = member_id ? Number(member_id) : 0;
                let nonMemberName = null;
                let nonMemberPhone = null;

                if (type === 'class') {
                    // Parse tipe peserta dari name string
                    if (name.includes('(Non-Member)')) {
                        participantType = 'non_member';
                        memberId = 0;
                        // Ambil nama non-member
                        const match = name.match(/- (.*?) \(Non-Member\)/);
                        nonMemberName = match ? match[1] : '';
                    } else {
                        participantType = 'member';
                        // Ambil nama member
                        const match = name.match(/- (.*?)( \(Pakai Kuota\))?$/);
                        nonMemberName = '';
                    }
                    // Ambil nama class dari name string
                    const classMatch = name.match(/^Kelas (.*?) -/);
                    className = classMatch ? classMatch[1] : '';
                }

                // Check if item exists
                const existingItem = orderItems.find(item =>
                    item.name === name && item.type === type && item.member_id === member_id
                );

                if (existingItem) {
                    existingItem.quantity += 1;
                    existingItem.subtotal = existingItem.quantity * existingItem.price;
                } else {
                    let itemObj = {
                        id: itemId,
                        name: name,
                        price: price,
                        quantity: 1,
                        subtotal: price,
                        type: type,
                        member_id: memberId
                    };
                    // Tambahkan detail peserta untuk class
                    if (type === 'class') {
                        itemObj.tipe_peserta = participantType;
                        itemObj.class_name = className;
                        itemObj.non_member_name = nonMemberName;
                        itemObj.non_member_phone = nonMemberPhone;
                        itemObj.id_member = memberId;
                    }
                    orderItems.push(itemObj);
                }

                updateOrderTable();
            }

            // Update order table
            function updateOrderTable() {
                if (!orderTable) return;

                let html = '';
                let total = 0;

                orderItems.forEach((item, index) => {
                    total += item.subtotal;
                    const rowClass = item.type === 'quota_topup' ? 'quota-item' : '';

                    html += `
                        <tr class="${rowClass}">
                            <td>${item.name}${item.type === 'class' && item.member_id ? ' (Member)' : ''}</td>
                            <td>
                                ${item.type === 'quota_topup' ? '1' : `<input type="number" class="form-control-qty form-control-sm quantity-input" data-index="${index}" value="${item.quantity}" min="1">`}
                            </td>
                            <td>${formatRupiah(item.price)}</td>
                            <td>${formatRupiah(item.subtotal)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                orderTable.innerHTML = html;

                if (emptyOrderMessage) {
                    if (orderItems.length > 0) {
                        emptyOrderMessage.style.display = 'none';
                    } else {
                        emptyOrderMessage.style.display = '';
                    }
                }

                if (totalAmount) {
                    totalAmount.textContent = formatRupiah(total);
                }

                calculateChange();
            }

            // Calculate change
            function calculateChange() {
                const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
                const payment = parseFloat(paymentAmount.value) || 0;
                const change = payment - total;

                if (changeAmount) {
                    changeAmount.value = change >= 0 ? formatRupiah(change) : 'Pembayaran tidak cukup';
                }
            }

            // Remove item
            function handleRemoveItem(event) {
                if (!event.target.closest('.remove-item')) return;

                const index = event.target.closest('.remove-item').dataset.index;
                orderItems.splice(index, 1);
                updateOrderTable();
            }

            // Update quantity
            function handleQuantityChange(event) {
                if (!event.target.classList.contains('quantity-input')) return;

                const index = event.target.dataset.index;
                const quantity = parseInt(event.target.value);

                if (quantity > 0) {
                    orderItems[index].quantity = quantity;
                    orderItems[index].subtotal = quantity * orderItems[index].price;
                    updateOrderTable();
                } else {
                    event.target.value = orderItems[index].quantity;
                }
            }

            // Process transaction (for coffee and barbershop)
            async function processTransaction() {
                const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
                const payment = parseFloat(paymentAmount.value) || 0;
                const amountString = changeAmount.value;
                const change = parseInt(amountString.replace(/[^\d]/g, ''), 10);

                if (!Array.isArray(orderItems) || orderItems.length === 0) {
                    createDynamicAlert('danger', 'Belum ada item di order');
                    return;
                }

                if (isNaN(payment) || payment < total) {
                    createDynamicAlert('danger', 'Pembayaran tidak mencukupi');
                    return;
                }

                // Tentukan business type
                const activeTab = document.querySelector('#posTabs .nav-link.active');
                let businessType = 'cafe';
                if (activeTab && activeTab.id === 'services-tab') {
                    businessType = 'barbershop';
                } else if (activeTab && activeTab.id === 'exercise-tab') {
                    businessType = 'exercise';
                }

                // Siapkan request data
                const requestData = {
                    business_type: businessType,
                    items: orderItems.map(item => {
                        let itemId = item.id;
                        if (item.type === 'product' || item.type === 'service') {
                            itemId = parseInt(item.id) || 0;
                        }
                        if (item.type === 'class' && !isNaN(item.id)) {
                            itemId = parseInt(item.id);
                        }
                        const itemData = {
                            type: item.type,
                            id: itemId,
                            name: item.name,
                            price: Number(item.price),
                            quantity: Number(item.quantity || 1),
                            subtotal: Number(item.subtotal)
                        };
                        if (item.type === 'class') {
                            itemData.member_id = item.member_id ? Number(item.member_id) : 0;
                            if (item.tipe_peserta === 'member') {
                                if (item.quota && item.quota > 0) {
                                    itemData.available_quota = item.quota;
                                } else {
                                    itemData.quota_topup = true;
                                }
                            }
                        }
                        if (item.type === 'quota_topup' && item.member_id) {
                            itemData.member_id = Number(item.member_id);
                        }
                        return itemData;
                    }),
                    payment_method: document.getElementById('paymentMethod').value,
                    payment_amount: payment,
                    payment_change: change
                    // notes: document.getElementById('transactionNotes').value,
                };
                if (currentMember) {
                    requestData.customer_id = Number(currentMember.id);
                    requestData.customer_name = currentMember.name;
                }

                if (processBtn) {
                    processBtn.disabled = true;
                    processBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                }

                try {
                    // Debug: tampilkan data yang dikirim
                    console.log('Sending data:', requestData);

                    const response = await fetch("{{ route('pos.process') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(requestData)
                    });

                    let data = null;
                    try {
                        data = await response.json();
                    } catch (jsonErr) {
                        throw new Error('Gagal membaca response dari server');
                    }

                    if (!response.ok || !data || data.status === false) {
                        let errorMsg = 'Gagal memproses transaksi';
                        if (data && data.errors) {
                            let errorMessages = [];
                            for (const [field, messages] of Object.entries(data.errors)) {
                                errorMessages.push(...messages);
                            }
                            errorMsg = 'Validasi gagal:\n' + errorMessages.join('\n');
                        } else if (data && data.message) {
                            errorMsg = data.message;
                        }
                        createDynamicAlert('danger', errorMsg);
                        return;
                    }

                    createDynamicAlert('success', `Transaksi berhasil. Invoice: ${data.data.invoice_number}`);
                    // Reset order
                    orderItems = [];
                    if (paymentAmount) paymentAmount.value = '';
                    if (changeAmount) changeAmount.value = '';
                    updateOrderTable();

                } catch (error) {
                    console.error('Transaction error:', error);
                    createDynamicAlert('danger', error.message || 'Terjadi kesalahan saat memproses transaksi');
                } finally {
                    if (processBtn) {
                        processBtn.disabled = false;
                        processBtn.textContent = 'Process Transaction';
                    }
                }
            }

            // Initialize time picker with business hours (e.g., 9AM-5PM)
            const bookingTimeInput = document.getElementById('booking_time');
            if (bookingTimeInput) {
                bookingTimeInput.min = '09:00';
                bookingTimeInput.max = '17:00';
            }

            // Event listeners for all sections
            if (productSearch) {
                productSearch.addEventListener('input', () => loadProducts(productSearch.value));
            }

            if (serviceSearch) {
                serviceSearch.addEventListener('input', () => loadServices(serviceSearch.value));
            }

            if (classSearch) {
                classSearch.addEventListener('input', function() {
                    loadClasses('', this.value);
                });
            }

            // Order summary event listeners
            document.addEventListener('click', handleItemClick);

            if (orderTable) {
                orderTable.addEventListener('click', handleRemoveItem);
                orderTable.addEventListener('change', handleQuantityChange);
            }

            if (paymentAmount) {
                paymentAmount.addEventListener('input', calculateChange);
            }

            if (processBtn) {
                processBtn.addEventListener('click', processTransaction);
            }

            // Initial load
            loadProducts();
            loadServices();
            loadClasses();
        });
    </script>

    {{-- // --------------------------Lain-lain-------------------------- --}}
    <script>
        // Fungsi formatRupiah yang diperbaiki
        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

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

        // Fungsi untuk menampilkan error validasi dalam formatRupiah list
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

        function toggleMasterMenu() {
            const submenu = document.getElementById('masterSubmenu');
            const icon = document.querySelector('.menu-header .dropdown-icon');

            if (submenu && icon) {
                submenu.classList.toggle('show');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');

                // Toggle active class on header
                document.querySelector('.menu-header').classList.toggle('active');
            }
        }

        // Pertahankan dropdown terbuka jika submenu aktif
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.submenu .nav-link.active');
            if (activeSubmenuItem) {
                const submenu = document.getElementById('masterSubmenu');
                const icon = document.querySelector('.menu-header .dropdown-icon');
                const header = document.querySelector('.menu-header');

                if (submenu && icon && header) {
                    submenu.classList.add('show');
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                    header.classList.add('active');
                }
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
