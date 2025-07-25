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
    fetchAllOpname();
    function fetchAllOpname(){
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_stok_opname/getDataStokOpname') }}",
            data: {
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(opname => {
                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${opname.kode_opname}</td>`;
                    tabledata += `<td>${opname.tgl_opname}</td>`;
                    tabledata += `<td>${opname.waktu_opname}</td>`;
                    tabledata += `<td>${opname.keterangan}</td>`;
                    tabledata += `<td align="center"><button type="button" data-id="${opname.kode_opname}" id="button_view" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button></td>`;
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
            url: "{{ route('laporan_stok_opname/cari.cari') }}",
            data: {
                tgl_cari: tgl_cari,
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(opname => {
                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${opname.kode_opname}</td>`;
                    tabledata += `<td>${opname.tgl_opname}</td>`;
                    tabledata += `<td>${opname.waktu_opname}</td>`;
                    tabledata += `<td>${opname.keterangan}</td>`;
                    tabledata += `<td align="center"><button type="button" data-id="${opname.kode_opname}" id="button_view" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button></td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    });

    $(document).on("click", "#button_view", function(e) {
        let kode_opname = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_stok_opname/getViewOpname') }}",
            data: {
                kode_opname: kode_opname
            },
            dataType: "json",
            success: function(response) {
                $("#kode_stok_opname").val(kode_opname);
                let tbl_detail;
                let no = 0;
                response.data.forEach(data_opname => {
                    no = no + 1
                    tbl_detail += `<tr>`;
                    tbl_detail += `<td>` +no+ `</td>`;
                    tbl_detail += `<td>${data_opname.kode_opname}</td>`;
                    tbl_detail += `<td>${data_opname.tgl_opname}</td>`;
                    tbl_detail += `<td>${data_opname.kode_produk}</td>`;
                    tbl_detail += `<td>${data_opname.nama_produk}</td>`;
                    tbl_detail += `<td align="right">${data_opname.jml_sistem}</td>`;
                    tbl_detail += `<td align="right">${data_opname.jml_fisik}</td>`;
                    tbl_detail += `<td align="right">${data_opname.selisih}</td>`;
                    tbl_detail += `</tr>`;
                });
                $("#tbl_detail").html(tbl_detail);
            }
        });
        $('#modalView').modal('show');
    });

</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Laporan Stok Opname</title>
@endsection

@section('content')

<main id="main" class="main">
    <!-- HIDE SIDEBAR -->
    {{-- <style type="text/css">
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

    </style> --}}
    <!-- END HIDE SIDEBAR -->

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Laporan Stok Opname
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Stok Opname</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="#" target="_blank" method="get" enctype="multipart/form-data">
                            <div class="row mb-3">
                              <div class="col-4" >
                                {{-- <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                                <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button> --}}
                              </div>
                              <div class="col-2"></div>
                              <div class="col-3" >
                                {{-- <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/> --}}
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
                                        <th>Kode Stok Opname</th>
                                        <th>Tgl Stok Opname</th>
                                        <th>Waktu</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
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

    <div class="modal fade" id="modalView">
        <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Laporan Stok Opname</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('laporan_stok_opname/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-4">
                            <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                            <input type="hidden" name="kode_stok_opname" id="kode_stok_opname" class="form-control" style="height: 30px; font-size: 14px;" required>
                        </div>
                    </div>
                </form>
                <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Tgl Stok Opname</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Jml Sistem</th>
                            <th>Jml Fisik</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_detail" class="tbl_detail">
    
                    </tbody>
                </table>
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>

</main>
@endsection



@section('js')
 
    
@endsection()