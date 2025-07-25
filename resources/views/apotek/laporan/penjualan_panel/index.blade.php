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
            url: "{{ route('laporan_penjualan_panel/getDataPenjualan.getDataPenjualan') }}",
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(stok => {
                    let harga = stok.harga;
                    //membuat format rupiah Harga//
                    var reverse_harga = harga.toString().split('').reverse().join(''),
                    ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                    harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let diskon = stok.diskon_rp;
                    //membuat format rupiah Harga//
                    var reverse_diskon = diskon.toString().split('').reverse().join(''),
                    ribuan_diskon  = reverse_diskon.match(/\d{1,3}/g);
                    diskon_jadi = ribuan_diskon.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let ppn = stok.ppn_rp;
                    //membuat format rupiah Harga//
                    var reverse_ppn = ppn.toString().split('').reverse().join(''),
                    ribuan_ppn  = reverse_ppn.match(/\d{1,3}/g);
                    ppn_jadi = ribuan_ppn.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let total = stok.total;
                    //membuat format rupiah Harga//
                    var reverse_total = total.toString().split('').reverse().join(''),
                    ribuan_total  = reverse_total.match(/\d{1,3}/g);
                    total_jadi = ribuan_total.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${stok.kode_penjualan}</td>`;
                    tabledata += `<td>${stok.tgl_penjualan}</td>`;
                    tabledata += `<td>${stok.nama_cabang}</td>`;
                    tabledata += `<td>${stok.jenis_penjualan}</td>`;
                    tabledata += `<td>${stok.tipe}</td>`;
                    tabledata += `<td>${stok.kode_produk}</td>`;
                    tabledata += `<td>${stok.nama_produk}</td>`;
                    tabledata += `<td align="right">${stok.qty}</td>`;
                    tabledata += `<td>${stok.nama_unit}</td>`;
                    tabledata += `<td align="right">${harga_jadi}</td>`;
                    // tabledata += `<td align="right">${stok.diskon}</td>`;
                    tabledata += `<td align="right">${diskon_jadi}</td>`;
                    // tabledata += `<td align="right">${stok.ppn}</td>`;
                    tabledata += `<td align="right">${ppn_jadi}</td>`;
                    tabledata += `<td align="right">${total_jadi}</td>`;
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
                url: "{{ route('laporan_penjualan_panel/getDataPenjualan.getDataPenjualan') }}",
                data: {
                    value: value,
                    tgl: tgl, 
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
                    let no = 1;
                    response.data.forEach(stok => {
                        let harga = stok.harga;
                        //membuat format rupiah Harga//
                        var reverse_harga = harga.toString().split('').reverse().join(''),
                        ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                        harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        let diskon = stok.diskon_rp;
                        //membuat format rupiah Harga//
                        var reverse_diskon = diskon.toString().split('').reverse().join(''),
                        ribuan_diskon  = reverse_diskon.match(/\d{1,3}/g);
                        diskon_jadi = ribuan_diskon.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        let ppn = stok.ppn_rp;
                        //membuat format rupiah Harga//
                        var reverse_ppn = ppn.toString().split('').reverse().join(''),
                        ribuan_ppn  = reverse_ppn.match(/\d{1,3}/g);
                        ppn_jadi = ribuan_ppn.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        let total = stok.total;
                        //membuat format rupiah Harga//
                        var reverse_total = total.toString().split('').reverse().join(''),
                        ribuan_total  = reverse_total.match(/\d{1,3}/g);
                        total_jadi = ribuan_total.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        tabledata += `<tr>`;
                        tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                        tabledata += `<td>${stok.kode_penjualan}</td>`;
                        tabledata += `<td>${stok.tgl_penjualan}</td>`;
                        tabledata += `<td>${stok.nama_cabang}</td>`;
                        tabledata += `<td>${stok.jenis_penjualan}</td>`;
                        tabledata += `<td>${stok.tipe}</td>`;
                        tabledata += `<td>${stok.kode_produk}</td>`;
                        tabledata += `<td>${stok.nama_produk}</td>`;
                        tabledata += `<td align="right">${stok.qty}</td>`;
                        tabledata += `<td align="right">${harga_jadi}</td>`;
                        tabledata += `<td>${stok.nama_unit}</td>`;
                        // tabledata += `<td align="right">${stok.diskon}</td>`;
                        tabledata += `<td align="right">${diskon_jadi}</td>`;
                        // tabledata += `<td align="right">${stok.ppn}</td>`;
                        tabledata += `<td align="right">${ppn_jadi}</td>`;
                        tabledata += `<td align="right">${total_jadi}</td>`;
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
    <title>Laporan Penjualan Panel</title>
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
        Laporan Penjualan Panel
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Transaksi Penjualan Per Obat</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_penjualan_panel/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
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
                            <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Nama Cabang</th>
                                        <th>Jenis Penjualan</th>
                                        <th>Tipe</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        {{-- <th>Diskon (%)</th> --}}
                                        <th>Diskon</th>
                                        {{-- <th>PPN (%)</th> --}}
                                        <th>PPN</th>
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

</main>
@endsection



@section('js')
 
    
@endsection()