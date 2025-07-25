@extends('layouts.coffeshop.admin')
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
                <div class="table-card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select id="filter_branch" class="form-select form-select-sm">
                                <option value="">Pilih Cabang</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter_type" class="form-select form-select-sm">
                                <option value="">Pilih Jenis</option>
                                <option value="stock">Stok</option>
                                <option value="transaction">Transaksi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter_period" class="form-select form-select-sm">
                                <option value="">Pilih Periode</option>
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <button id="btn_filter" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-md-3" id="daily_container" style="display: none;">
                            <input type="date" id="filter_date_daily" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3" id="monthly_container" style="display: none;">
                            <input type="month" id="filter_date_monthly" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3" id="yearly_container" style="display: none;">
                            <input type="number" id="filter_date_yearly" class="form-control form-control-sm" min="2000" max="2100" placeholder="Tahun">
                        </div>
                    </div>
                </div>

                 <div class="card-body" style="padding:0">
                    <div class="table-responsive">
                        <table class="table" id="ingredientTable">
                            <thead >
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Cabang</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="report_body">
                                <tr><td colspan="5" class="text-center">Silakan lakukan filter untuk menampilkan data</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const branchSelect = document.getElementById('filter_branch');
    const filterType = document.getElementById('filter_type');
    const filterPeriod = document.getElementById('filter_period');
    const reportBody = document.getElementById('report_body');

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

    async function loadBranches() {
        try {
            const response = await axios.get("{{ route('coffeshop.reports.branches') }}");
            if (response.data.status) {
                response.data.data.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Gagal memuat cabang:', error);
        }
    }

    filterPeriod.addEventListener('change', function () {
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
        const branchId = branchSelect.value;
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

        if (!reportType || !period) {
            alert('Silakan pilih jenis laporan dan periode');
            return;
        }

        let url = '';
        if (reportType === 'stock') {
            url = "{{ route('coffeshop.reports.stock') }}";
        } else {
            url = "{{ route('coffeshop.reports.transaction') }}";
        }

        try {
            const response = await axios.get(url, {
                params: {
                    branch_id: branchId,
                    filter_type: period,
                    date: date
                }
            });

            const data = response.data.data;
            reportBody.innerHTML = '';

            if (!data.length) {
                reportBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data ditemukan</td></tr>';
                return;
            }

            data.forEach((item, index) => {
                reportBody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td>${item.stock ? item.stock : item.grand_total}</td>
                        <td>${item.branch_name}</td>
                        <td>${formatDate(item.updated_at || item.transaction_date)}</td>
                    </tr>
                `;
            });
        } catch (error) {
            console.error('Error fetching data:', error);
            reportBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>';
        }
    }

    btnFilter.addEventListener('click', fetchData);

    document.addEventListener('DOMContentLoaded', function () {
        loadBranches();
    });
</script>
@endpush