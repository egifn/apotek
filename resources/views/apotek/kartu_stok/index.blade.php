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
  //===Select data Kartu Stok====//
  fetchAllKartuStok();
  function fetchAllKartuStok(){
    let value = $("#cari").val();
    $.ajax({
      type: "GET",
      url: "{{ route('kartu_stok/getDatakartuStok.getDatakartuStok') }}",
      data: {
          value: value
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(stok => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${stok.kode_produk }</td>`;
            tabledata += `<td>${stok.nama_produk }</td>`;
            tabledata += `<td>${stok.tgl_in_out}</td>`;
            tabledata += `<td>${stok.no_bukti}</td>`;
            tabledata += `<td>${stok.keterangan }</td>`;
            tabledata += `<td>${stok.stok_awal}</td>`;
            tabledata += `<td>${stok.stok_masuk}</td>`;
            tabledata += `<td>${stok.stok_keluar}</td>`;
            tabledata += `<td>${stok.stok_sisa}</td>`;
            tabledata += `<td>${stok.type}</td>`;
            tabledata += `<td hidden>${stok.id_user_input}</td>`;
            tabledata += `<td hidden>${stok.name}</td>`;
            tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Produk====//

  //===Pencarian berdasarkan tanggal====//
    $("#button_cari_tanggal").click(function(){
        let tgl_cari = $("#tanggal").val();
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('kartu_stok/cari.cari') }}",
            data: {
                tgl_cari: tgl_cari,
                value: value
            },
            dataType: "json",
            success: function(response) {
              let tabledata;
              let no = 1;
              response.data.forEach(stok => {
                  tabledata += `<tr>`;
                  tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                  tabledata += `<td>${stok.kode_produk }</td>`;
                  tabledata += `<td>${stok.nama_produk }</td>`;
                  tabledata += `<td>${stok.tgl_in_out}</td>`;
                  tabledata += `<td>${stok.no_bukti}</td>`;
                  tabledata += `<td>${stok.keterangan }</td>`;
                  tabledata += `<td>${stok.stok_awal}</td>`;
                  tabledata += `<td>${stok.stok_masuk}</td>`;
                  tabledata += `<td>${stok.stok_keluar}</td>`;
                  tabledata += `<td>${stok.stok_sisa}</td>`;
                  tabledata += `<td>${stok.type}</td>`;
                  tabledata += `<td hidden>${stok.id_user_input}</td>`;
                  tabledata += `<td hidden>${stok.name}</td>`;
                  tabledata += `</tr>`;
              });
              $("#tabledata").html(tabledata);
            }
        });
    });
    //===End Pencarian berdasarkan tanggal====//

    //=== SEARCH Select data unit/kategori====//
    $("#cari").keyup(function() {
      let tgl_cari = $("#tanggal").val();
      let value = $("#cari").val();
      if (this.value.length >= 2) {
        $.ajax({
          type: "GET",
          url: "{{ route('kartu_stok/cari.cari') }}",
          data: {
            tgl_cari: tgl_cari,
            value: value
          },
          dataType: "json",
          success: function(response) {
            let tabledata;
            let no = 1;
            response.data.forEach(stok => {
              tabledata += `<tr>`;
              tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
              tabledata += `<td>${stok.kode_produk }</td>`;
              tabledata += `<td>${stok.nama_produk }</td>`;
              tabledata += `<td>${stok.tgl_in_out}</td>`;
              tabledata += `<td>${stok.no_bukti}</td>`;
              tabledata += `<td>${stok.keterangan }</td>`;
              tabledata += `<td>${stok.stok_awal}</td>`;
              tabledata += `<td>${stok.stok_masuk}</td>`;
              tabledata += `<td>${stok.stok_keluar}</td>`;
              tabledata += `<td>${stok.stok_sisa}</td>`;
              tabledata += `<td>${stok.type}</td>`;
              tabledata += `<td hidden>${stok.id_user_input}</td>`;
              tabledata += `<td hidden>${stok.name}</td>`;
              tabledata += `</tr>`;
            });
            $("#tabledata").html(tabledata);
          }
        });
      }else{
          fetchAllKartuStok();
      }
    });
    //=== End SEARCH Select data unit/kategori====//
    
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Kartu Stok</title>
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
        Kartu Stok
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Kartu Stok</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_produk">
            <div class="col-lg-12">
                
                    <div class="card">
                      <div class="card-body">
                        <br>
                        <form action="{{ route('kartu_stok/view') }}" target="_blank" method="get" enctype="multipart/form-data">
                          <div class="row mb-3">
                            <div class="col-4">
                              <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                              <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-3">
                              <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari Nama Produk..."/>
                            </div>
                            <div class="col-3">
                              <div class="input-group mb-3">
                                  <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}">
                                  <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                          </div>
                        </form>
                        <div class="table-responsive">
                          <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Tgl transaksi</th>
                                    <th>No Bukti</th>
                                    <th>Keterangan</th>
                                    <th>Stok Awal</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Keluar</th>
                                    <th>Sisa Stok</th>
                                    <th>tipe</th>
                                    {{-- <th hidden>Id User Input</th>
                                    <th hidden>User Input</th>  
                                    <th hidden>Opsi</th> --}}
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