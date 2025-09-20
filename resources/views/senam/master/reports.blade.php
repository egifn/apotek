@extends('layouts.senam.admin')
@section('page-title', 'Laporan')
@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h4>Laporan</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-card">
                <div class="table-card-header" style="display: flex; flex-wrap: wrap;gap: 5px;">
                    <div class="row">
                        <div class="col-md-12 text-end" style="margin-bottom: 5px">
                            <button id="btn_export_pdf" class="btn btn-danger btn-sm">Export PDF</button>
                            <button id="btn_export_excel" class="btn btn-success btn-sm" style="margin-right: 5px">Export
                                Excel</button>
                        </div>
                    </div>
                    <div class="row g-2" style="width: 100%; align-items: center;">
                        {{-- <div class="col-md-3">
                            <select id="filter_branch" class="form-select form-select-sm">
                                <option value="">Pilih Cabang</option>
                            </select>
                        </div> --}}
                        <div class="col-md-3">
                            <select id="filter_type" class="form-select form-select-sm">
                                <option value="">Pilih Jenis</option>
                                <option value="quota">Quota</option>
                                <option value="sales">Transaksi</option>
                                <option value="instruktur">Instruktur</option>
                                <option value="rent" hidden>Sewa</option>

                            </select>
                        </div>
                        <div class="col-md-3" id="filter_period_container" style="display: none">
                            <select id="filter_period" class="form-select form-select-sm">
                                <option value="">Pilih Periode</option>
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="daily_container" style="display: none;">
                            <input type="date" id="filter_date_daily" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3" id="monthly_container" style="display: none;">
                            <input type="month" id="filter_date_monthly" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3" id="yearly_container" style="display: none;">
                            <input type="number" id="filter_date_yearly" class="form-control form-control-sm"
                                min="2000" max="2100" placeholder="Tahun">
                        </div>
                        <div class="col-md-1 text-end">
                            <button id="btn_filter" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="padding:0">
                    <div class="table-responsive">
                        <table class="table" id="ingredientTable">
                            {{-- <thead >
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Cabang</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="report_body">
                                <tr>
                                    <td colspan="5" class="text-center">Silakan lakukan filter untuk menampilkan data
                                    </td>
                                </tr>
                            </tbody> --}}
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // const branchSelect = document.getElementById('filter_branch');
        const filterType = document.getElementById('filter_type');
        const filterPeriod = document.getElementById('filter_period');
        const reportBody = document.getElementById('ingredientTable');

        const filterPeriodContainer = document.getElementById('filter_period_container');
        const dailyContainer = document.getElementById('daily_container');
        const monthlyContainer = document.getElementById('monthly_container');
        const yearlyContainer = document.getElementById('yearly_container');
        const dailyInput = document.getElementById('filter_date_daily');
        const monthlyInput = document.getElementById('filter_date_monthly');
        const yearlyInput = document.getElementById('filter_date_yearly');

        const btnFilter = document.getElementById('btn_filter');

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID');
        }

        // async function loadBranches() {
        //     try {
        //         const response = await axios.get("{{ route('coffeshop.reports.branches') }}");
        //         if (response.data.status) {
        //             response.data.data.forEach(branch => {
        //                 const option = document.createElement('option');
        //                 option.value = branch.id;
        //                 option.textContent = branch.name;
        //                 branchSelect.appendChild(option);
        //             });
        //         }
        //     } catch (error) {
        //         console.error('Gagal memuat cabang:', error);
        //     }
        // }

        filterType.addEventListener('change', function() {
            const type = this.value;

            filterPeriod.value = '';

            filterPeriodContainer.style.display = 'none';
            dailyContainer.style.display = 'none';
            monthlyContainer.style.display = 'none';
            yearlyContainer.style.display = 'none';

            if (type !== 'stock') {
                filterPeriodContainer.style.display = 'block';
            }
        });

        filterPeriod.addEventListener('change', function() {
            const period = this.value;

            dailyContainer.style.display = 'none';
            monthlyContainer.style.display = 'none';
            yearlyContainer.style.display = 'none';

            if (period === 'daily') {
                dailyContainer.style.display = 'block';
            } else if (period === 'monthly') {
                monthlyContainer.style.display = 'block';
            } else if (period === 'yearly') {
                yearlyContainer.style.display = 'block';
            }
        });

        async function fetchData() {
            // const branchId = branchSelect.value;
            const reportType = filterType.value;
            const period = filterPeriod.value;

            let date = '';
            if (period === 'daily') {
                date = dailyInput.value;
            } else if (period === 'monthly') {
                date = monthlyInput.value;
            } else if (period === 'yearly') {
                date = yearlyInput.value;
            }

            if (!reportType) {
                alert('Silakan pilih jenis laporan dan periode');
                return;
            }

            let url = '';
            if (reportType === 'quota') {
                url = "{{ route('senam.reports.quota') }}";
            } else if (reportType === 'sales') {
                url = "{{ route('senam.reports.sales') }}";
            } else if (reportType === 'instruktur') {
                url = "{{ route('senam.reports.instruktur') }}";
            } else if (reportType === 'rent') {
                url = "{{ route('senam.reports.rent') }}";
            }

            try {
                const response = await axios.get(url, {
                    params: {
                        // branch_id: branchId,
                        filter_type: period,
                        date: date
                    }
                });

                const data = response.data.data;
                console.log(data);
                reportBody.innerHTML = '';

                if (!data.length) {
                    reportBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data ditemukan</td></tr>';
                    return;
                }
                if (reportType === 'quota') {
                    let tableHTML = `<thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>`;

                    // Build table rows safely
                    data.forEach((item, index) => {
                        tableHTML += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.name}</td>
                                        <td>${item.notes}</td>
                                      </tr>`;
                    });

                    tableHTML += `</tbody>`;

                    reportBody.innerHTML = tableHTML;
                } else if (reportType === 'purchase') {
                    let tableHTML = ` <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pembelian</th>
                                        <th>Tgl Pembelian</th>
                                        <th>Nama Supplier</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Build table rows safely
                    data.forEach((item, index) => {
                        tableHTML += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.kode_pembelian}</td>
                                        <td>${item.tanggal}</td>
                                        <td>${item.nama_supplier}</td>
                                        <td>${item.jenis}</td>
                                        <td>${item.ingredient_name}</td>
                                        <td>${item.qty}</td>
                                        <td style="text-align:right;">${formatRupiah(item.harga)}</td>
                                        <td style="text-align:right;">${formatRupiah(item.subtotal)}</td>
                                      </tr>`;
                    });

                    const totalSales = data.reduce((total, item) => {
                        const price = parseFloat(item.subtotal);
                        return total + (isNaN(price) ? 0 : price);
                    }, 0);

                    tableHTML += `<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total :</b></td>
                                        <td></td>
                                        <td style="text-align:right;"><b>${formatRupiah(totalSales)}</b></td>
                                    </tr>
                                </tbody>`;

                    reportBody.innerHTML = tableHTML;

                } else if (reportType === 'sales') {

                    let tableHTML = ` <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Invoice</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Nama Produk</th>
                                        <th style="text-align:right;">Qty</th>
                                        <th style="text-align:right;">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Build table rows safely
                    data.forEach((item, index) => {
                        tableHTML += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.invoice_number}</td>
                                        <td>${formatDate(item.transaction_date)}</td>
                                        <td>${item.name}</td>
                                        <td style="text-align:right;">${item.quantity}</td>
                                        <td style="text-align:right;">${formatRupiah(parseFloat(item.price) || 0)}</td>
                                      </tr>`;
                    });

                    const totalSales = data.reduce((total, item) => {
                        const price = parseFloat(item.price);
                        return total + (isNaN(price) ? 0 : price);
                    }, 0);

                    tableHTML += `<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total :</b></td>
                                        <td></td>
                                        <td style="text-align:right;"><b>${formatRupiah(totalSales)}</b></td>
                                    </tr>
                                </tbody>`;

                    reportBody.innerHTML = tableHTML;
                }
                if (reportType === 'quota') {
                    let tableHTML = `<thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>`;

                    // Build table rows safely
                    data.forEach((item, index) => {
                        tableHTML += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.name}</td>
                                        <td>${item.notes}</td>
                                      </tr>`;
                    });

                    tableHTML += `</tbody>`;

                    reportBody.innerHTML = tableHTML;
                } else if (reportType === 'instruktur') {
                    let tableHTML = ` <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Instruktur</th>
                                        <th>Jenis</th>
                                        <th>Member</th>
                                        <th>Non Member</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Build table rows safely
                    data.forEach((item, index) => {
                        tableHTML += `<tr>
                                        <td>${item.transaction_date}</td>
                                        <td>${item.instructor_name}</td>
                                        <td>${item.class_name}</td>
                                        <td>${item.total_member}</td>
                                        <td>${item.total_nonmember}</td>
                                      </tr>`;
                    });


                    tableHTML += `</tbody>`;

                    reportBody.innerHTML = tableHTML;

                }
            } catch (error) {
                console.error('Error fetching data:', error);
                reportBody.innerHTML =
                    '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>';
            }
        }

        btnFilter.addEventListener('click', fetchData);

        // document.addEventListener('DOMContentLoaded', function() {
        //     loadBranches();
        // });

        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }

        function escapeHTML(str) {
            return str.replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [m];
            });
        }
    </script>
@endpush
