@extends('layouts.kasir')
@section('page-title', 'Transaksi hari ini')
@section('content')
    {{-- <div class="row mb-4">
        <div class="col-md-8">
            <h4>Transaksi hari ini</h4>
        </div>
    </div> --}}

    <div class="row" id="kasir" @if (Auth::user()->type == 4) style="display:none;" @endif></div>

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
                <h5>Rekap Transaksi Hari Ini</h5>
                {{-- <p style="margin: 0">Nama Kasir : &nbsp; {{ Auth::user()->username }}</p> --}}
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
    </div>
@endsection

@push('scripts')
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
@endpush
