@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
        
    $(document).ready(function() {
            //INISIASI DATERANGEPICKER
            $('#tanggal').daterangepicker({
             
            })
    })
</script>

<script type="text/javascript">
    //===Select data Pembelian====//
    fetchAllretur_k();
    function fetchAllretur_k(){
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_retur_k/getDataReturKadaluarsa') }}",
            data: {
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(retur_k => {
                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${retur_k.tgl_transaksi}</td>`;
                    tabledata += `<td hidden>${retur_k.id}</td>`;
                    tabledata += `<td>${retur_k.kode_produk}</td>`;
                    tabledata += `<td>${retur_k.nama_produk}</td>`;
                    tabledata += `<td>${retur_k.nama_jenis}</td>`;
                    tabledata += `<td>${retur_k.qty}</td>`;
                    tabledata += `<td>${retur_k.nama_unit}</td>`;
                    tabledata += `<td>${retur_k.keterangan}</td>`;
                    tabledata += `<td>${retur_k.nama_supplier}</td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    }
    //===End data Pembelian====//

    //===Pencarian berdasarkan tanggal====//
    $("#button_cari_tanggal").click(function(){
        let tgl_cari = $("#tanggal").val();
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_retur_k/cari.cari') }}",
            data: {
                tgl_cari: tgl_cari,
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(retur_k => {
                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${retur_k.tgl_transaksi}</td>`;
                    tabledata += `<td hidden>${retur_k.id}</td>`;
                    tabledata += `<td>${retur_k.kode_produk}</td>`;
                    tabledata += `<td>${retur_k.nama_produk}</td>`;
                    tabledata += `<td>${retur_k.nama_jenis}</td>`;
                    tabledata += `<td>${retur_k.qty}</td>`;
                    tabledata += `<td>${retur_k.nama_unit}</td>`;
                    tabledata += `<td>${retur_k.keterangan}</td>`;
                    tabledata += `<td>${retur_k.nama_supplier}</td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    });

    //=== SEARCH data Pembelian====//
    $("#cari").keyup(function() {
        let value = $("#cari").val();
        let tgl = $("#tanggal").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('laporan_retur_k/getDataReturKadaluarsa') }}",
                data: {
                    value: value,
                    tgl: tgl, 
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
                    let no = 1;
                    response.data.forEach(retur_k => {
                        tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${retur_k.tgl_transaksi}</td>`;
                    tabledata += `<td hidden>${retur_k.id}</td>`;
                    tabledata += `<td>${retur_k.kode_produk}</td>`;
                    tabledata += `<td>${retur_k.nama_produk}</td>`;
                    tabledata += `<td>${retur_k.nama_jenis}</td>`;
                    tabledata += `<td>${retur_k.qty}</td>`;
                    tabledata += `<td>${retur_k.nama_unit}</td>`;
                    tabledata += `<td>${retur_k.keterangan}</td>`;
                    tabledata += `<td>${retur_k.nama_supplier}</td>`;
                    tabledata += `</tr>`;
                    });
                    $("#tabledata").html(tabledata);
                }
            });
        }else{
            fetchAllretur_k();
        }
    });
    //=== End SEARCH data Pembelian====//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Laporan Retur Kadaluarsa</title>
@endsection

@section('content')

<main id="main" class="main">
    <!-- HIDE SIDEBAR -->
    <style type="text/css">
        .sidebar {
            left: -300px;
        }

        .toggle-sidebar #main,
        .toggle-sidebar #footer {
            margin-left: 0;
        }

        main,
        #footer {
            margin-left: 0px !important;
        }

    </style>
    <!-- END HIDE SIDEBAR -->

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Laporan Retur Kadaluarsa
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Retur Kadaluarsa</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_retur_k/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                            <div class="row mb-3">
                              <div class="col-4">
                                <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                                <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                              </div>
                              <div class="col-2"></div>
                              <div class="col-3">
                                <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/>
                              </div>
                              <div class="col-3">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}">
                                    <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                                  </div>
                              </div>
                              
                            </div>
                        </form>
                        <div class="row mb-3" hidden>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label">Tanggal</label>
                                <div class="col-sm-2">
                                <input type="text" name="tgl" id="tgl" class="form-control" value="" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Transaksi</th>
                                        <th hidden>Id</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Jenis</th>
                                        <th>Qty</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                        <th>Supplier</th>
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

</main>
@endsection



@section('js')
 
    
@endsection()