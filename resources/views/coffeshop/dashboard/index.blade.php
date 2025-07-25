@extends('layouts.coffeshop.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('style')
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Dashboard Kedai Kopi Tiga</h4>
        </div>
    </div>

    <div class="row">
        <!-- Sales Today -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Penjualan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($todaySales, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Penjualan Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($monthlySales, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Produk Terlaris</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bestSellers->count() }} Produk</div>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stocks -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok Hampir Habis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> Bahan</div>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Best Sellers Table -->
        <div class="col-lg-6 mb-4">
            <div class="table-card">
                <!-- Card Header -->
                <div class="table-card-header">
                    <div class="header-content">

                        <h6 class="header-title">Menu Terlaris</h6>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="table-card-body">
                    <div class="table-container">
                        <table class="table-types-table">
                            <thead>
                                <tr>
                                    <th class="column-no">No</th>
                                    <th>Nama Produk</th>
                                    <th>Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($bestSellers as $product)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Best Sellers Table -->
        <div class="col-lg-6 mb-4">
            <div class="table-card">
                <!-- Card Header -->
                <div class="table-card-header">
                    <div class="header-content">

                        <h6 class="header-title">Stok Habis</h6>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="table-card-body">
                    <div class="table-container">
                        <table class="table-types-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bahan</th>
                                    <th>Stok Tersedia</th>
                                    <th>Toko</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($lowStocks as $stock)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $stock->name }}</td>
                                        <td>{{ $stock->stock_available }}</td>
                                        <td>{{ $stock->branch_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
