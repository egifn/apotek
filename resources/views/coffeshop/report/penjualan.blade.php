@extends('layouts.coffeshop.admin')
@section('page-title', 'Produk')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Laporan Penjualan</h4>
        </div>
        <div class="col-md-4 text-end">

        </div>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_penjualan/view.view') }}" target="_blank" method="get"
                            enctype="multipart/form-data">
                            <div class="row mb-3"
                                style="display: flex; justify-content: space-around; align-items: center;">
                                <div class="col-4">
                                    <button class="btn btn-success" name="button_excel" id="button_excel" value="excel"
                                        type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                                    <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf"
                                        type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                                </div>
                                <div class="col-1"></div>

                                {{-- <div class="col-1">
                                    <!-- <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/> -->
                                </div> --}}

                                <div class="col-2">
                                    <input type="date" class="form-control" name="tanggal_awal" id="tanggal_akhir"
                                        value="{{ request()->tanggal }}">
                                </div>

                                s/d

                                <div class="col-2">
                                    <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir"
                                        value="{{ request()->tanggal }}">
                                </div>

                                <div class="col-2">
                                    <button type="button" style="width: 100%;" class="btn btn-secondary"
                                        name="button_cari_tanggal" id="button_cari_tanggal" value="tgl">Cari
                                        Data</button>
                                </div>

                            </div>
                        </form>
                        <div class="row mb-3" hidden>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label">Tanggal</label>
                                <div class="col-sm-2">
                                    <input type="text" name="tgl" id="tgl" class="form-control" value=""
                                        required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th hidden>Id</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="tabledata">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            loadData();

            function loadData() {
                fetch("{{ route('coffeshop.reports.laporan_data_penjualan') }}")
                    .then(response => response.json())
                    .then(res => {
                        if (res.status) {
                            let rows = '';
                            let no = 1;
                            res.data.forEach(item => {
                                rows += `
                                <tr>
                                    <td>${no++}</td>
                                    <td hidden>${item.id}</td>
                                    <td>${item.invoice_number}</td>
                                    <td>${item.transaction_date}</td>
                                    <td>${item.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                                </tr>
                            `;
                            });
                            document.getElementById('tabledata').innerHTML = rows;
                        } else {
                            document.getElementById('tabledata').innerHTML =
                                `<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>`;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        document.getElementById('tabledata').innerHTML =
                            `<tr><td colspan="8" class="text-center">Error loading data</td></tr>`;
                    });
            }
        });
    </script>
@endpush
