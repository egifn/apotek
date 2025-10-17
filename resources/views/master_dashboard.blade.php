<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee 3 - Dashboard</title>
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        <div class="" style="padding: 0px 25px">
            <div class="flex items-center justify-between">
                <div class="logo">
                    <div class="logo-icon">3</div>
                    <div class="logo-text">Coffee 3</div>
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
    <main class="space-y-6" style="padding: 15px 25px">
        <!-- Summary Card -->
        <div class="card summary-card">
            <div class="card-content">
                <div class="flex items-center justify-between">
                    <div>
                        <div style="color: rgba(0, 171, 6, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL PENDAPATAN
                        </div>
                        <div class="summary-value">Rp.
                            {{ number_format($total_pendapatan->ttl_pendapatan, 0, ',', '.') }}</div>
                        <div class="summary-change">
                            <!-- <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i> -->
                            <!-- 8% dari kemarin -->
                        </div>
                    </div>
                    <div>
                        <div style="color: rgba(255, 7, 7, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL PENGELUARAN
                        </div>
                        <div class="summary-value">Rp 0</div>
                        <div class="summary-change">
                            <!-- <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i> -->
                            <!-- 8% dari kemarin -->
                        </div>
                    </div>
                    <div>
                        <!-- <div style="color: rgba(255, 7, 7, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL GROS
                        </div>
                        <div class="summary-value">Rp 12,450,000</div>
                        <div class="summary-change">
                            <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                            8% dari kemarin
                        </div> -->
                    </div>
                    <div>
                        <!-- <div style="color: rgba(0, 171, 6, 0.8); font-size: 0.875rem; font-weight: 600;">
                            TOTAL LABA
                        </div>
                        <div class="summary-value">Rp 12,450,000</div>
                        <div class="summary-change">
                            <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                            8% dari kemarin
                        </div> -->
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
                            <div class="metric-value">RP.</div>
                            {{-- <div class="metric-change up">
                                <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                                +12%
                            </div> --}}
                        </div>
                        <div class="activity-icon icon-blue"><a href="{{ route('home') }}"><i
                                    class="bi bi-arrow-up-right"></i></a>
                        </div>
                    </div>

                    <div class="metric-stats">
                        <div>
                            {{-- <div class="stat-value">42</div> --}}
                            <div class="stat-label">Transaksi</div>
                        </div>
                        {{-- <div>
                            <div class="stat-value" style="color: #dc2626;">8</div>
                            <div class="stat-label">Expired</div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- Cafe -->
            <div class="card metric-card">
                <div class="card-content">
                    <div class="metric-header">
                        <div>
                            <div class="metric-label">Cafe</div>
                            <div class="metric-value">Rp.
                                {{ number_format($total_pendapatan_cafe->ttl_pendapatan_cafe, 0, ',', '.') }}</div>
                            <div class="metric-change up">
                                <!-- <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i>
                                +8% -->
                            </div>
                        </div>
                        <a class="activity-icon icon-blue" href="{{ route('coffeshop.dashboard') }}"><i
                                class="bi bi-arrow-up-right"></i></a>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">{{ $total_pendapatan_cafe->jml_item }}</div>
                            <div class="stat-label">Order</div>
                        </div>
                        <div>
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
                            <div class="metric-value">Rp.
                                {{ number_format($total_pendapatan_barber->ttl_pendapatan_barber, 0, ',', '.') }}</div>
                            <div class="metric-change down">
                                <!-- <i class="bi bi-graph-down-arrow" style="margin-right: 10px;"></i> -->
                                <!-- -5% -->
                            </div>
                        </div>
                        <a class="activity-icon icon-blue" href="{{ route('barbershop.dashboard') }}"><i
                                class="bi bi-arrow-up-right"></i></a>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">{{ $total_pendapatan_barber->jml }}</div>
                            <div class="stat-label">Booking</div>
                        </div>
                        <div>
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
                            <div class="metric-value">Rp.
                                {{ number_format($total_pendapatan_senam->ttl_pendapatan_senam, 0, ',', '.') }}</div>
                            <div class="metric-change up">
                                <!-- <i class="bi bi-graph-up-arrow" style="margin-right: 10px;"></i> -->
                                <!-- +22% -->
                            </div>
                        </div>
                        <a class="activity-icon icon-blue" href="{{ route('senam.dashboard') }}"><i
                                class="bi bi-arrow-up-right"></i></a>
                    </div>

                    <div class="metric-stats">
                        <div>
                            <div class="stat-value">{{ $total_pendapatan_senam->jml }}</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row" id="kasir" @if (Auth::user()->type == 4) style="display:none;" @endif></div>

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
                    <table class="table" id="ingredientTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kasir</th>
                                <th>Pembayaran</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th style="text-align: right; width: 120px;">Total</th>
                            </tr>
                        </thead>
                        <tbody id="report_body">
                            <tr>
                                <td colspan="9" class="text-center">Mengambil Data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js"></script>

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

    {{-- Transaksi Hari ini --}}
    <script>
        const dtFilterBisnis = document.getElementById('filter_bisnis');
        const btnFilter = document.getElementById('btn_filter');
        const reportBody = document.getElementById('report_body');
        const cashierName = document.getElementById('cashier_name');
        const kasirContainer = document.getElementById('kasir');

        function exportFile(route) {
            const params = new URLSearchParams({

                date: '{{ now()->toDateString() }}'
            });
            window.open(route + '?' + params.toString(), '_blank');
        }
        /* ------------------------- */

        // btnFilter.addEventListener('click', fetchData);
        window.addEventListener('DOMContentLoaded', () => fetchData());

        async function fetchData() {
            try {
                const {
                    data: res
                } = await axios.get("{{ route('dashboard_data') }}", {
                    params: {
                        date: '{{ now()->toDateString() }}'
                    }
                });


                const rows = res.dt_all_transaksi;
                const dt_transaksi_kasir = res.dt_transaksi_kasir;
                console.log(dt_transaksi_kasir);

                const total_uang_fisik = res.dt_all_transaksi_cash;
                reportBody.innerHTML = '';

                if (!rows.length) {
                    reportBody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data</td></tr>';
                    return;
                }
                const capital = str => {
                    if (!str) return '';
                    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
                };
                const grandTotal = rows.reduce((sum, r) => sum + Number(r.subtotal), 0);

                let html = '';
                rows.forEach((r, i) => {
                    html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${r.transaction_date || '-'}</td>
                            <td>${r.invoice_number || '-'}</td>
                            <td>${r.name || '-'}</td>
                            <td>${r.nama_kasir || '-'}</td>
                            <td>${capital(r.payment_method || '-')}</td>
                            <td style="text-align:right;">
                                <div style="display:flex; justify-content:space-between; white-space:nowrap;">
                                    <span>Rp.</span>
                                    <span>${formatRupiah(r.price)}</span>
                                </div>
                            </td>
                            <td style="text-align:center;">${r.quantity ?? 0}</td>
                            <td style="text-align:right;">
                                <div style="display:flex; justify-content:space-between; white-space:nowrap;">
                                    <span>Rp.</span>
                                    <span>${formatRupiah(r.subtotal)}</span>
                                </div>
                            </td>
                        </tr>
                    `;
                })

                html += `
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Total Uang Fisik</b></td>
                            <td colspan="2"><b>Cash</b></td>
                            <td></td>
                            <td style="text-align:right;">
                                <div style="display:flex; justify-content:space-between; white-space:nowrap;">
                                    <span><b>Rp.</b></span>
                                    <b>${formatRupiah(total_uang_fisik)}</b>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="2"><b>Total</b></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right;">
                                <div style="display:flex; justify-content:space-between; white-space:nowrap;">
                                    <span><b>Rp.</b></span>
                                    <b>${formatRupiah(grandTotal)}</b>
                                </div>
                            </td>
                        </tr>
                    `;

                reportBody.innerHTML = html;

                const shiftCount = 2;

                // Isi data berdasarkan jumlah shift
                const kasirCards = Array.from({
                    length: shiftCount
                }, (_, i) => {
                    const dataKasir = dt_transaksi_kasir[i]; // ambil data sesuai urutan kalau ada
                    return dataKasir ? {
                        shift: `Shift ${i + 1}`,
                        user_id: dataKasir.user_id,
                        user_name: dataKasir.user_name,
                        total_cash: dataKasir.total_cash,
                        total_qris: dataKasir.total_qris,
                        total_lainnya: dataKasir.total_lainnya,
                        total_semua: dataKasir.total_semua
                    } : {
                        shift: `Shift ${i + 1}`,
                        user_id: "-",
                        user_name: `-`,
                        total_cash: 0,
                        total_qris: 0,
                        total_lainnya: 0,
                        total_semua: 0
                    };
                });

                // --- Generate HTML ---
                let htmlKasir = kasirCards
                    .map(
                        (kasir) => `
                        <div class="col-md-6 col-sm-6 mb-3">
                            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                            <div class="card-body" style="padding: 20px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0 fw-bold text-primary">${kasir.user_name}</h5>
                                <span class="badge bg-light text-secondary border">${kasir.shift}</span>
                                </div>

                                <div class="mt-2">
                                <p class="mb-1 d-flex justify-content-between">
                                    <span><b>Cash:</b></span>
                                    <span>Rp ${formatRupiah(kasir.total_cash)}</span>
                                </p>
                                <p class="mb-1 d-flex justify-content-between">
                                    <span><b>QRIS:</b></span>
                                    <span>Rp ${formatRupiah(kasir.total_qris)}</span>
                                </p>
                                <p class="mb-1 d-flex justify-content-between">
                                    <span><b>Lainnya:</b></span>
                                    <span>Rp ${formatRupiah(kasir.total_lainnya)}</span>
                                </p>
                                </div>

                                <hr class="my-2">

                                <p class="mb-0 fw-bold d-flex justify-content-between align-items-center fs-6">
                                <span>Total:</span>
                                <span class="text-success">Rp ${formatRupiah(kasir.total_semua)}</span>
                                </p>
                            </div>
                            </div>
                        </div>
                        `
                    )
                    .join("");

                // Masukkan ke HTML
                document.getElementById("kasir").innerHTML = htmlKasir;

            } catch (e) {
                console.error(e);
                reportBody.innerHTML = '<tr><td colspan="9" class="text-center">Gagal memuat data</td></tr>';
            }
        }

        function formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    currencyDisplay: 'code',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                })
                .format(num)
                .replace('IDR', '')
                .trim(); // hasil: "1.234.567"
        }

        function escapeHTML(str) {
            return str.replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [m]));
        }
    </script>
</body>

</html>
