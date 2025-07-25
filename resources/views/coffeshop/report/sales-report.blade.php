@extends('layouts.coffeshop.admin')
@section('page-title', 'Laporan Penjualan')
@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Laporan Penjualan</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-success btn-sm" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
            <a href="#" class="btn btn-primary btn-sm ms-2">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm" action="javascript:void(0)">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="filter_type" class="form-label">Filter Berdasarkan</label>
                                <select name="filter_type" id="filter_type" class="form-control form-control-sm">
                                    <option value="daily">Harian</option>
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly">Bulanan</option>
                                    <option value="yearly">Tahunan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="text" name="date" id="date" class="form-control form-control-sm"
                                    value="{{ now()->format('Y-m-d') }}" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-card">
                <div class="table-card-header">
                    <h5 class="card-title">Total Pendapatan: <strong id="totalRevenue">Rp 0,00</strong></h5>
                </div>
                <div class="table-card-body">
                    <div class="table-container">
                        <table id="product_table" class="table-types-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Produk</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="salesReportBody">
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
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
 
@endpush