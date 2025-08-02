<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sindangsari Farma - Dashboard</title> 
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            background-color: #e5e5e5;
            color: #111827;
            line-height: 1.5;
        }

        /* Utility Classes */
        .container {
            /* max-width: 1280px; */
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .grid {
            display: grid;
            gap: 1rem;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, 1fr);
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .space-x-2>*+* {
            margin-left: 0.5rem;
        }

        .space-x-3>*+* {
            margin-left: 0.75rem;
        }

        .space-y-6>*+* {
            margin-top: 1.5rem;
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 10px 0;
            box-shadow: 0 5px 5px 0px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-icon {
            width: 25px;
            height: 25px;
            background: #2563eb;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.875rem;
            margin-right: 0.75rem;
        }

        .logo-text {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-button {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
            transition: background-color 0.15s;
        }

        .profile-button:hover {
            background-color: #f9fafb;
        }

        .avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #e5e7eb;
            margin-right: 0.5rem;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            min-width: 12rem;
            z-index: 100;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-header {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            text-decoration: none;
            color: #374151;
            transition: background-color 0.15s;
        }

        .dropdown-item:hover {
            background-color: #f9fafb;
        }

        .dropdown-item.danger {
            color: #dc2626;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: box-shadow 0.15s;
        }

        .card:hover {
            box-shadow: 0 10px 6px -1px rgba(0, 0, 0, 0.1);
            transform: rotateX();
        }

        .card-header {
            padding: 1.5rem 1.5rem 0;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        /* Summary Card */
        .summary-card {
            color: black;
            border: none;
        }

        .summary-value {
            font-size: 1.875rem;
            font-weight: bold;
            margin: 0.25rem 0;
        }

        .summary-change {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-outline {
            background: white;
            color: #374151;
            border-color: #d1d5db;
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        .btn-ghost {
            background: transparent;
            color: white;
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        /* Metrics */
        .metric-card {
            position: relative;
        }

        .metric-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #111827;
            margin: 0.25rem 0;
        }

        .metric-change {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .metric-change.up {
            color: #059669;
        }

        .metric-change.down {
            color: #dc2626;
        }

        .metric-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-blue {
            background: #dbeafe;
            color: #2563eb;
        }

        .icon-emerald {
            background: #d1fae5;
            color: #059669;
        }

        .icon-red {
            background: #fee2e2;
            color: #dc2626;
        }

        .icon-purple {
            background: #e9d5ff;
            color: #7c3aed;
        }

        .metric-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .stat-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Activity */
        .activity-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        .activity-icon:hover {
            opacity: 50%;
        }

        .activity-content {
            flex: 1;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .activity-desc {
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
        }

        .activity-amount {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f9fafb;
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-emerald {
            background: #d1fae5;
            color: #047857;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-purple {
            background: #e9d5ff;
            color: #6b21a8;
        }

        /* Chart Placeholder */
        .chart-placeholder {
            height: 16rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: between;
            padding: 1rem 1.5rem;
            border-top: 1px solid #f3f4f6;
            justify-content: space-between;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .pagination-buttons {
            display: flex;
            gap: 0.25rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid-cols-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .grid-cols-4,
            .grid-cols-3,
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .summary-card .flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .btn-group {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .metric-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .card-header .flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="flex items-center justify-between">
                <div class="logo">
                    <div class="logo-icon">SF</div>
                    <div class="logo-text">Sindangsari Farma</div>
                </div>

                <div class="profile-dropdown">
                    <button class="profile-button" onclick="toggleDropdown()">
                        <img src="assets/img/user.png" alt="Profile" class="avatar">
                        <span style="font-size: 0.875rem; font-weight: 500;">{{ Auth::user()->name }}</span>
                    </button>

                    <div class="dropdown-menu" id="profileDropdown">
                        <div class="dropdown-header">
                            <div style="font-size: 0.875rem; font-weight: 500;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ Auth::user()->username }}</div>
                        </div>
                        <a href="{{ route('logout') }}" class="dropdown-item danger"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right" style="margin-right: 5px"></i>
                            Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container space-y-6" style="padding-top: 1.5rem; padding-bottom: 1.5rem;">
        <!-- Summary Card -->
        <div class="card summary-card">
            <div class="card-content">
                <div class="flex items-center justify-between">
                    <div>
                        <div style="color: rgba(0, 171, 6, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL PENDAPATAN
                        </div>
                        <div class="summary-value">Rp 12,450,000</div>
                        <div class="summary-change">
                            <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                            8% dari kemarin
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 7, 7, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL PENGELUARAN
                        </div>
                        <div class="summary-value">Rp 12,450,000</div>
                        <div class="summary-change">
                            <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                            8% dari kemarin
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 7, 7, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL GROS
                        </div>
                        <div class="summary-value">Rp 12,450,000</div>
                        <div class="summary-change">
                            <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                            8% dari kemarin
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(0, 171, 6, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL LABA
                        </div>
                        <div class="summary-value">Rp 12,450,000</div>
                        <div class="summary-change">
                            <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                            8% dari kemarin
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-4">
            <!-- Apotek -->
            <div class="card metric-card">
                <div class="card-content">
                    <div class="metric-header">
                        <div>
                            <div class="metric-label">Apotek</div>
                            <div class="metric-value">Rp 5,250,000</div>
                            <div class="metric-change up">
                                <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                                +12%
                            </div>
                        </div>
                        <div class="activity-icon icon-blue"><a href="{{ route('home') }}"><i
                                    class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">42</div>
                            <div class="stat-label">Transaksi</div>
                        </div>
                        <div>
                            <div class="stat-value" style="color: #dc2626;">8</div>
                            <div class="stat-label">Expired</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cafe -->
            <div class="card metric-card">
                <div class="card-content">
                    <div class="metric-header">
                        <div>
                            <div class="metric-label">Cafe</div>
                            <div class="metric-value">Rp 3,800,000</div>
                            <div class="metric-change up">
                                <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                                +8%
                            </div>
                        </div>
                        <div class="activity-icon icon-blue"><a href="{{ route('coffeshop.dashboard') }}"><i
                                    class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">35</div>
                            <div class="stat-label">Order</div>
                        </div>
                        <div>
                            <div class="stat-value">Kopi Susu</div>
                            <div class="stat-label">Favorit</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barbershop -->
            <div class="card metric-card">
                <div class="card-content">
                    <div class="metric-header">
                        <div>
                            <div class="metric-label">Barbershop</div>
                            <div class="metric-value">Rp 2,100,000</div>
                            <div class="metric-change down">
                                <i class="bi bi-graph-down-arrow" style="margin-right: 10px;"></i>
                                -5%
                            </div>
                        </div>
                        <div class="activity-icon icon-blue"><a href="{{ route('barbershop.dashboard') }}"><i
                                    class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">18</div>
                            <div class="stat-label">Booking</div>
                        </div>
                        <div>
                            <div class="stat-value">Haircut</div>
                            <div class="stat-label">Favorit</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Senam -->
            <div class="card metric-card">
                <div class="card-content">
                    <div class="metric-header">
                        <div>
                            <div class="metric-label">Senam</div>
                            <div class="metric-value">Rp 1,350,000</div>
                            <div class="metric-change up">
                                <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                                +22%
                            </div>
                        </div>
                        <div class="activity-icon icon-blue"><a href="{{ route('senam.dashboard') }}"><i
                                    class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">27</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div>
                            <div class="stat-value">Zumba</div>
                            <div class="stat-label">Favorit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Activity -->
        <div class="grid grid-cols-3">
            <!-- Chart -->
            <div class="card" style="grid-column: span 3;">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div class="card-title">Trend Pendapatan 7 Hari</div>
                        <button class="btn btn-outline btn-sm">Semua Bisnis</button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="chart-placeholder">
                        Chart akan ditampilkan di sini
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card" style="grid-column: span 3;">
                <div class="card-header">
                    <div class="card-title">Aktivitas Terkini</div>
                </div>
                <div style="padding: 0;">
                    <div class="activity-item">
                        <div class="activity-icon icon-blue"><i class="bi bi-hospital"></i></div>
                        <div class="activity-content">
                            <div class="activity-time">2 menit lalu</div>
                            <div class="activity-desc">Transaksi #TRX-1280</div>
                            <div class="activity-amount">Rp 185,000</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon icon-blue"><i class="bi bi-cup-hot"></i></div>
                        <div class="activity-content">
                            <div class="activity-time">15 menit lalu</div>
                            <div class="activity-desc">Order: Kopi Susu x2</div>
                            <div class="activity-amount">Rp 67,000</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon icon-blue"><i class="bi bi-person-workspace"></i></div>
                        <div class="activity-content">
                            <div class="activity-time">32 menit lalu</div>
                            <div class="activity-desc">Booking: Haircut</div>
                            <div class="activity-amount">Rp 85,000</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon icon-blue"><i class="bi bi-droplet"></i></div>
                        <div class="activity-content">
                            <div class="activity-time">1 jam lalu</div>
                            <div class="activity-desc">Pendaftaran Zumba</div>
                            <div class="activity-amount">Rp 75,000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="card-title">Transaksi Hari Ini</div>
                    <div class="flex space-x-2">
                        <button class="btn btn-outline btn-sm">
                            <span style="margin-right: 0.5rem;"></span>
                            Export
                        </button>
                    </div>
                </div>
            </div>

            <div style="padding: 0;">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>Bisnis</th>
                                <th>Kode</th>
                                <th>Detail</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>10:15</td>
                                <td><span class="badge badge-blue">Apotek</span></td>
                                <td style="font-family: monospace;">#TRX-1280</td>
                                <td>Paracetamol, Vitamin C</td>
                                <td style="text-align: right; font-weight: 500;">Rp 185,000</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>09:30</td>
                                <td><span class="badge badge-emerald">Cafe</span></td>
                                <td style="font-family: monospace;">#CAFE-892</td>
                                <td>Kopi Susu x2, Croissant</td>
                                <td style="text-align: right; font-weight: 500;">Rp 67,000</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>09:15</td>
                                <td><span class="badge badge-red">Barber</span></td>
                                <td style="font-family: monospace;">#BAR-421</td>
                                <td>Haircut + Styling</td>
                                <td style="text-align: right; font-weight: 500;">Rp 85,000</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>08:45</td>
                                <td><span class="badge badge-purple">Senam</span></td>
                                <td style="font-family: monospace;">#GYM-056</td>
                                <td>Zumba Class</td>
                                <td style="text-align: right; font-weight: 500;">Rp 75,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <div class="pagination-info">Menampilkan 4 dari 42 transaksi</div>
                    <div class="pagination-buttons">
                        <button class="btn btn-outline btn-sm">←</button>
                        <button class="btn btn-primary btn-sm">1</button>
                        <button class="btn btn-outline btn-sm">2</button>
                        <button class="btn btn-outline btn-sm">3</button>
                        <button class="btn btn-outline btn-sm">→</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Dropdown functionality
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const button = document.querySelector('.profile-button');

            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Close dropdown when pressing Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('profileDropdown').classList.remove('show');
            }
        });

        // Business filter buttons
        document.querySelectorAll('.btn-ghost').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.btn-ghost, .btn-primary').forEach(btn => {
                    btn.className = btn.className.replace('btn-primary', 'btn-ghost');
                });

                // Add active class to clicked button
                this.className = this.className.replace('btn-ghost', 'btn-primary');
            });
        });

        // Table row hover effect (already handled by CSS)

        // Pagination buttons
        document.querySelectorAll('.pagination-buttons .btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.textContent.includes('←') && !this.textContent.includes('→')) {
                    // Remove active class from all page buttons
                    document.querySelectorAll('.pagination-buttons .btn').forEach(btn => {
                        if (!btn.textContent.includes('←') && !btn.textContent.includes('→')) {
                            btn.className = btn.className.replace('btn-primary', 'btn-outline');
                        }
                    });

                    // Add active class to clicked button
                    this.className = this.className.replace('btn-outline', 'btn-primary');
                }
            });
        });

        // Smooth scroll for any anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add loading states for buttons (optional)
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function() {
                if (this.textContent.includes('Export') || this.textContent.includes('Tambah')) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span style="margin-right: 0.5rem;">⏳</span>Loading...';
                    this.disabled = true;

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 1500);
                }
            });
        });
    </script>
</body>

</html>
