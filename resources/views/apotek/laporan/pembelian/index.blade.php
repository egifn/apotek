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
    fetchAllPembelian();
    function fetchAllPembelian(){
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_pembelian/getDataPembelian.getDataPembelian') }}",
            data: {
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(pembelian => {
                    let temp_harga = pembelian.harga;
                    //membuat format rupiah Harga//
                    var reverse_harga = temp_harga.toString().split('').reverse().join(''),
                    ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                    harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let temp_diskon = pembelian.diskon_item_rp;
                    //membuat format rupiah Harga//
                    var reverse_diskon = temp_diskon.toString().split('').reverse().join(''),
                    ribuan_diskon  = reverse_diskon.match(/\d{1,3}/g);
                    diskon_rupiah = ribuan_diskon.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let temp_ppn = pembelian.ppn_item_rp;
                    //membuat format rupiah Harga//
                    var reverse_ppn = temp_ppn.toString().split('').reverse().join(''),
                    ribuan_ppn  = reverse_ppn.match(/\d{1,3}/g);
                    ppn_rupiah = ribuan_ppn.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let temp_total = pembelian.total;
                    //membuat format rupiah Harga//
                    var reverse_total = temp_total.toString().split('').reverse().join(''),
                    ribuan_total  = reverse_total.match(/\d{1,3}/g);
                    total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${pembelian.kode_pembelian}</td>`;
                    tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
                    if(pembelian.no_faktur == null) {
                        tabledata += `<td align="center"> - </td>`;
                    }else{
                        tabledata += `<td>${pembelian.no_faktur}</td>`;
                    }
                    tabledata += `<td>${pembelian.nama_cabang}</td>`;
                    tabledata += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                    tabledata += `<td>${pembelian.pembelian}</td>`;
                    tabledata += `<td>${pembelian.jenis_transaksi}</td>`;
                    tabledata += `<td>${pembelian.tgl_jatuh_tempo}</td>`;
                    tabledata += `<td>${pembelian.nama_supplier}</td>`;
                    tabledata += `<td>${pembelian.diskon}</td>`;
                    if(pembelian.status_pembayaran == 0) {
                        tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
                    }else{
                        tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
                    }
                    tabledata += `<td>${pembelian.tipe}</td>`;
                    tabledata += `<td>${pembelian.kode_produk}</td>`;
                    tabledata += `<td>${pembelian.nama_produk}</td>`;
                    tabledata += `<td align="right">${pembelian.qty_beli}</td>`;
                    tabledata += `<td align="right">${harga_rupiah}</td>`;
                    // tabledata += `<td align="right">${pembelian.diskon}</td>`;
                    tabledata += `<td align="right">${diskon_rupiah}</td>`;
                    // tabledata += `<td align="right">${pembelian.ppn}</td>`;
                    tabledata += `<td align="right">${ppn_rupiah}</td>`;
                    tabledata += `<td align="right">${total_rupiah}</td>`;
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
            url: "{{ route('laporan_pembelian/cari.cari') }}",
            data: {
                tgl_cari: tgl_cari,
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(pembelian => {
                    let temp_harga = pembelian.harga;
                    //membuat format rupiah Harga//
                    var reverse_harga = temp_harga.toString().split('').reverse().join(''),
                    ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                    harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let temp_diskon = pembelian.diskon_item_rp;
                    //membuat format rupiah Harga//
                    var reverse_diskon = temp_diskon.toString().split('').reverse().join(''),
                    ribuan_diskon  = reverse_diskon.match(/\d{1,3}/g);
                    diskon_rupiah = ribuan_diskon.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let temp_ppn = pembelian.ppn_item_rp;
                    //membuat format rupiah Harga//
                    var reverse_ppn = temp_ppn.toString().split('').reverse().join(''),
                    ribuan_ppn  = reverse_ppn.match(/\d{1,3}/g);
                    ppn_rupiah = ribuan_ppn.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let temp_total = pembelian.total;
                    //membuat format rupiah Harga//
                    var reverse_total = temp_total.toString().split('').reverse().join(''),
                    ribuan_total  = reverse_total.match(/\d{1,3}/g);
                    total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${pembelian.kode_pembelian}</td>`;
                    tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
                    if(pembelian.no_faktur == null) {
                        tabledata += `<td align="center"> - </td>`;
                    }else{
                        tabledata += `<td>${pembelian.no_faktur}</td>`;
                    }
                    tabledata += `<td>${pembelian.nama_cabang}</td>`;
                    tabledata += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                    tabledata += `<td>${pembelian.pembelian}</td>`;
                    tabledata += `<td>${pembelian.jenis_transaksi}</td>`;
                    tabledata += `<td>${pembelian.tgl_jatuh_tempo}</td>`;
                    tabledata += `<td>${pembelian.nama_supplier}</td>`;
                    tabledata += `<td>${pembelian.diskon}</td>`;
                    if(pembelian.status_pembayaran == 0) {
                        tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
                    }else{
                        tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
                    }
                    tabledata += `<td>${pembelian.tipe}</td>`;
                    tabledata += `<td>${pembelian.kode_produk}</td>`;
                    tabledata += `<td>${pembelian.nama_produk}</td>`;
                    tabledata += `<td align="right">${pembelian.qty_beli}</td>`;
                    tabledata += `<td align="right">${harga_rupiah}</td>`;
                    // tabledata += `<td align="right">${pembelian.diskon}</td>`;
                    tabledata += `<td align="right">${diskon_rupiah}</td>`;
                    // tabledata += `<td align="right">${pembelian.ppn}</td>`;
                    tabledata += `<td align="right">${ppn_rupiah}</td>`;
                    tabledata += `<td align="right">${total_rupiah}</td>`;
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
                url: "{{ route('laporan_pembelian/getDataPembelian.getDataPembelian') }}",
                data: {
                    value: value,
                    tgl: tgl, 
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
                    let no = 1;
                    response.data.forEach(pembelian => {
                        let temp_harga = pembelian.harga;
                        //membuat format rupiah Harga//
                        var reverse_harga = temp_harga.toString().split('').reverse().join(''),
                        ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                        harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        let temp_diskon = pembelian.diskon_item_rp;
                        //membuat format rupiah Harga//
                        var reverse_diskon = temp_diskon.toString().split('').reverse().join(''),
                        ribuan_diskon  = reverse_diskon.match(/\d{1,3}/g);
                        diskon_rupiah = ribuan_diskon.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        let temp_ppn = pembelian.ppn_item_rp;
                        //membuat format rupiah Harga//
                        var reverse_ppn = temp_ppn.toString().split('').reverse().join(''),
                        ribuan_ppn  = reverse_ppn.match(/\d{1,3}/g);
                        ppn_rupiah = ribuan_ppn.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        let temp_total = pembelian.total;
                        //membuat format rupiah Harga//
                        var reverse_total = temp_total.toString().split('').reverse().join(''),
                        ribuan_total  = reverse_total.match(/\d{1,3}/g);
                        total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        tabledata += `<tr>`;
                        tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                        tabledata += `<td>${pembelian.kode_pembelian}</td>`;
                        tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
                        if(pembelian.no_faktur == null) {
                            tabledata += `<td align="center"> - </td>`;
                        }else{
                            tabledata += `<td>${pembelian.no_faktur}</td>`;
                        }
                        tabledata += `<td>${pembelian.nama_cabang}</td>`;
                        tabledata += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                        tabledata += `<td>${pembelian.pembelian}</td>`;
                        tabledata += `<td>${pembelian.jenis_transaksi}</td>`;
                        tabledata += `<td>${pembelian.tgl_jatuh_tempo}</td>`;
                        tabledata += `<td>${pembelian.nama_supplier}</td>`;
                        tabledata += `<td>${pembelian.diskon}</td>`;
                        if(pembelian.status_pembayaran == 0) {
                            tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
                        }else{
                            tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
                        }
                        tabledata += `<td>${pembelian.tipe}</td>`;
                        tabledata += `<td>${pembelian.kode_produk}</td>`;
                        tabledata += `<td>${pembelian.nama_produk}</td>`;
                        tabledata += `<td align="right">${pembelian.qty_beli}</td>`;
                        tabledata += `<td align="right">${harga_rupiah}</td>`;
                        // tabledata += `<td align="right">${pembelian.diskon}</td>`;
                        tabledata += `<td align="right">${diskon_rupiah}</td>`;
                        // tabledata += `<td align="right">${pembelian.ppn}</td>`;
                        tabledata += `<td align="right">${ppn_rupiah}</td>`;
                        tabledata += `<td align="right">${total_rupiah}</td>`;
                        tabledata += `</tr>`;
                    });
                    $("#tabledata").html(tabledata);
                }
            });
        }else{
            fetchAllPembelian();
        }
    });
    //=== End SEARCH data Pembelian====//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Laporan Pembelian</title>
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
        Laporan Pembelian
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Pembelian</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_pembelian/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
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
                            <table id="example" class="table table-striped table-bordered" style="width: 150%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SP</th>
                                        <th>Tgl SP</th>
                                        <th>No Faktur</th>
                                        <th>Nama Cabang</th>
                                        <th>Jenis SP</th>
                                        <th>Pembelian</th>
                                        <th>Transaksi</th>
                                        <th>Tgl JT</th>
                                        <th>Supplier</th>
                                        <th>Diskon All</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tipe</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
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