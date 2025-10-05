@extends('layouts.kasir')
@section('page-title', 'Transaksi hari ini')
@section('content')
    {{-- <div class="row mb-4">
        <div class="col-md-8">
            <h4>Transaksi hari ini</h4>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="table-card">
                <div class="table-card-header" style="display: flex; flex-wrap: wrap;gap: 5px;">
                    <div class="row" hidden>
                        <div class="col-md-12 text-end" style="margin-bottom: 5px">
                            <button id="btn_export_pdf" class="btn btn-danger btn-sm">Export PDF</button>
                            <button id="btn_export_excel" class="btn btn-success btn-sm" style="margin-right: 5px">Export
                                Excel</button>
                        </div>
                    </div>
                    <div class="row g-2" style="width: 100%; align-items: center;" hidden>
                        <div class="col-md-3">
                            <select id="filter_bisnis" class="form-select form-select-sm">
                                <option value="">Pilih Jenis</option>
                                <option value="all" selected>Semua</option>
                                <option value="coffeshop">Coffeshop</option>
                                <option value="senam">Senam</option>
                                <option value="barbershop">barber</option>
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button id="btn_filter" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>
                    <p style="margin: 0">Nama Kasir : &nbsp; {{ Auth::user()->username }}</p>
                </div>

                <div class="card-body" style="padding:0">
                    <div class="table-responsive">
                        <table class="table" id="ingredientTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Waktu</th>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Pembayaran</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th style="text-align: right; width: 120px;">Total</th>
                                </tr>
                            </thead>
                            <tbody id="report_body">
                                <tr>
                                    <td colspan="8" class="text-center">Mengambil Data
                                    </td>
                                </tr>
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
        const dtFilterBisnis = document.getElementById('filter_bisnis');
        const btnFilter = document.getElementById('btn_filter');
        const reportBody = document.getElementById('report_body');
        const cashierName = document.getElementById('cashier_name');

        function exportFile(route) {
            const params = new URLSearchParams({
                business_type: dtFilterBisnis.value,
                period: 'daily', // sesuaikan jika nanti ada select period
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
                        business_type: dtFilterBisnis.value,
                        period: 'daily',
                        date: '{{ now()->toDateString() }}'
                    }
                });

                console.log(res);


                const rows = res.data;
                reportBody.innerHTML = '';

                if (!rows.length) {
                    reportBody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>';
                    return;
                }
                const capital = str => {
                    if (!str) return '';
                    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
                };
                const grandTotal = rows.reduce((sum, r) => sum + Number(r.subtotal), 0);

                let html = '';
                rows.forEach((r, i) => {
                    html += `<tr>
                            <td>${i+1}</td>
                            <td>${r.transaction_date}</td>
                            <td>${r.invoice_number ?? '-'}</td>
                            <td>${r.name}</td>
                            <td>${capital(r.payment_method)}</td>
                            <td style="text-align:right; display: flex; justify-content: space-between;"><span>Rp.</span>${formatRupiah(r.price)}</td>
                            <td>${r.quantity}</td>
                            <td style="text-align:right; display: flex; justify-content: space-between;"><span>Rp.</span>${formatRupiah(r.subtotal)}</td>
                         </tr>`;
                });
                html += `
                <tr style="border-top: 2px solid;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Total Uang Fisik</b></td>
                            <td colspan='2'><b>Cash</b></td>
                            <td></td>
                            <td style="text-align:right; display: flex; justify-content: space-between;"><span><b>Rp.</b></span><b>${formatRupiah(grandTotal)}</b></td>
                </tr>
                  <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan='2'><b>Total</b></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right; display: flex; justify-content: space-between;"><span><b>Rp.</b></span><b>${formatRupiah(grandTotal)}</b></td>
                </tr>`;

                reportBody.innerHTML = html;

            } catch (e) {
                console.error(e);
                reportBody.innerHTML = '<tr><td colspan="7" class="text-center">Gagal memuat data</td></tr>';
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
@endpush
