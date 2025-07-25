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
    //===Select data penjualan====//
    fetchAllStok();
    function fetchAllStok(){
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_klinik_pelayanan/getDataKlinikPelayanan.getDataKlinikPelayanan') }}",
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(klk_pelayanan => {
                    let harga_produk = klk_pelayanan.harga_jasa_p;
                    //membuat format rupiah Harga//
                    var reverse_harga = harga_produk.toString().split('').reverse().join(''),
                    ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                    harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${klk_pelayanan.no_invoice}</td>`;
                    tabledata += `<td>${klk_pelayanan.tgl_invoice}</td>`;
                    tabledata += `<td>${klk_pelayanan.nama_cabang}</td>`;
                    tabledata += `<td>${klk_pelayanan.kode_jasa_p}</td>`;
                    tabledata += `<td>${klk_pelayanan.nama_jasa_p}</td>`;
                    tabledata += `<td align="right" hidden>${klk_pelayanan.jml_jasa_p}</td>`;
                    tabledata += `<td align="right">${harga_jadi}</td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    }
    //===End data Penjualan====//

    //=== SEARCH data penjualan====//
    $("#cari").keyup(function() {
        let value = $("#cari").val();
        let tgl = $("#tanggal").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('laporan_klinik_pelayanan/getDataKlinikPelayanan.getDataKlinikPelayanan') }}",
                data: {
                    value: value,
                    tgl: tgl, 
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
                    let no = 1;
                    response.data.forEach(klk_pelayanan => {
                        let harga_produk = klk_pelayanan.harga_jasa_p;
                        //membuat format rupiah Harga//
                        var reverse_harga = harga_produk.toString().split('').reverse().join(''),
                        ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                        harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        tabledata += `<tr>`;
                        tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                        tabledata += `<td>${klk_pelayanan.no_invoice}</td>`;
                        tabledata += `<td>${klk_pelayanan.tgl_invoice}</td>`;
                        tabledata += `<td>${klk_pelayanan.nama_cabang}</td>`;
                        tabledata += `<td>${klk_pelayanan.kode_jasa_p}</td>`;
                        tabledata += `<td>${klk_pelayanan.nama_jasa_p}</td>`;
                        tabledata += `<td align="right" hidden>${klk_pelayanan.jml_jasa_p}</td>`;
                        tabledata += `<td align="right">${harga_jadi}</td>`;
                        tabledata += `</tr>`;
                    });
                    $("#tabledata").html(tabledata);
                }
            });
        }else{
            fetchAllStok();
        }
    });
    //=== End SEARCH data penjualan====//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Laporan Klinik Pelayanan</title>
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
        Laporan Klinik Pelayanan
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Klinik Pelayanan</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_klinik_pelayanan/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                            <div class="row mb-3">
                              <div class="col-4">
                                <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                                <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                              </div>
                              <div class="col-2"></div>
                              <div class="col-3"></div>
                              <div class="col-3">
                                <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/>
                              </div>
                              <div class="col-3" hidden>
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
                            <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pembayaran</th>
                                        <th>Tgl Pembayaran</th>
                                        <th>Nama Cabang</th>
                                        <th>Kode Pelayanan</th>
                                        <th>Nama Pelayanan</th>
                                        <th hidden>Jumlah</th>
                                        <th>Harga</th>
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