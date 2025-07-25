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
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_penjualan_Pertransaksi/getDataPenjualanPertransaksi.getDataPenjualanPertransaksi') }}",
            data: {
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(transaksi => {
                    let subtotal = transaksi.subtotal;
                    //membuat format rupiah Harga//
                    var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
                    ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
                    subtotal_jadi = ribuan_subtotal.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let pembulatan = transaksi.pembulatan;
                    //membuat format rupiah Harga//
                    var reverse_pembulatan = pembulatan.toString().split('').reverse().join(''),
                    ribuan_pembulatan  = reverse_pembulatan.match(/\d{1,3}/g);
                    pembulatan_jadi = ribuan_pembulatan.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let total_bayar = transaksi.total_bayar;
                    //membuat format rupiah Harga//
                    var reverse_total_bayar = total_bayar.toString().split('').reverse().join(''),
                    ribuan_total_bayar  = reverse_total_bayar.match(/\d{1,3}/g);
                    total_bayar_jadi = ribuan_total_bayar.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let jml_bayar = transaksi.jml_bayar;
                    //membuat format rupiah Harga//
                    var reverse_jml_bayar = jml_bayar.toString().split('').reverse().join(''),
                    ribuan_jml_bayar  = reverse_jml_bayar.match(/\d{1,3}/g);
                    jml_bayar_jadi = ribuan_jml_bayar.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let kembali = transaksi.kembali;
                    //membuat format rupiah Harga//
                    var reverse_kembali = kembali.toString().split('').reverse().join(''),
                    ribuan_kembali  = reverse_kembali.match(/\d{1,3}/g);
                    kembali_jadi = ribuan_kembali.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${transaksi.kode_penjualan}</td>`;
                    tabledata += `<td>${transaksi.tgl_penjualan}</td>`;
                    tabledata += `<td>${transaksi.waktu_penjualan}</td>`;
                    tabledata += `<td>${transaksi.jenis_penjualan}</td>`;
                    tabledata += `<td>${transaksi.cara_bayar}</td>`;
                    tabledata += `<td>${transaksi.bank}</td>`;
                    tabledata += `<td align="right">${subtotal_jadi}</td>`;
                    tabledata += `<td align="right">${pembulatan_jadi}</td>`;
                    tabledata += `<td align="right">${total_bayar_jadi}</td>`;
                    tabledata += `<td align="right">${jml_bayar_jadi}</td>`;
                    tabledata += `<td align="right">${kembali_jadi}</td>`;
                    tabledata += `<td hidden>${transaksi.id_user_input}</td>`;
                    tabledata += `<td hidden>${transaksi.name}</td>`;
                    tabledata += `<td hidden>${transaksi.kode_cabang}</td>`;
                    tabledata += `<td hidden>${transaksi.nama_cabang}</td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    }
    //===End data Penjualan====//

    //===Pencarian berdasarkan tanggal====//
    $("#button_cari_tanggal").click(function(){
        let tgl_cari = $("#tanggal").val();
        let value = $("#cari").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_penjualan_Pertransaksi/cari.cari') }}",
            data: {
                tgl_cari: tgl_cari,
                value: value
            },
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                response.data.forEach(transaksi => {
                    let subtotal = transaksi.subtotal;
                    //membuat format rupiah Harga//
                    var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
                    ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
                    subtotal_jadi = ribuan_subtotal.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let pembulatan = transaksi.pembulatan;
                    //membuat format rupiah Harga//
                    var reverse_pembulatan = pembulatan.toString().split('').reverse().join(''),
                    ribuan_pembulatan  = reverse_pembulatan.match(/\d{1,3}/g);
                    pembulatan_jadi = ribuan_pembulatan.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let total_bayar = transaksi.total_bayar;
                    //membuat format rupiah Harga//
                    var reverse_total_bayar = total_bayar.toString().split('').reverse().join(''),
                    ribuan_total_bayar  = reverse_total_bayar.match(/\d{1,3}/g);
                    total_bayar_jadi = ribuan_total_bayar.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let jml_bayar = transaksi.jml_bayar;
                    //membuat format rupiah Harga//
                    var reverse_jml_bayar = jml_bayar.toString().split('').reverse().join(''),
                    ribuan_jml_bayar  = reverse_jml_bayar.match(/\d{1,3}/g);
                    jml_bayar_jadi = ribuan_jml_bayar.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let kembali = transaksi.kembali;
                    //membuat format rupiah Harga//
                    var reverse_kembali = kembali.toString().split('').reverse().join(''),
                    ribuan_kembali  = reverse_kembali.match(/\d{1,3}/g);
                    kembali_jadi = ribuan_kembali.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${transaksi.kode_penjualan}</td>`;
                    tabledata += `<td>${transaksi.tgl_penjualan}</td>`;
                    tabledata += `<td>${transaksi.waktu_penjualan}</td>`;
                    tabledata += `<td>${transaksi.jenis_penjualan}</td>`;
                    tabledata += `<td>${transaksi.cara_bayar}</td>`;
                    tabledata += `<td>${transaksi.bank}</td>`;
                    tabledata += `<td align="right">${subtotal_jadi}</td>`;
                    tabledata += `<td align="right">${pembulatan_jadi}</td>`;
                    tabledata += `<td align="right">${total_bayar_jadi}</td>`;
                    tabledata += `<td align="right">${jml_bayar_jadi}</td>`;
                    tabledata += `<td align="right">${kembali_jadi}</td>`;
                    tabledata += `<td hidden>${transaksi.id_user_input}</td>`;
                    tabledata += `<td hidden>${transaksi.name}</td>`;
                    tabledata += `<td hidden>${transaksi.kode_cabang}</td>`;
                    tabledata += `<td hidden>${transaksi.nama_cabang}</td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    });
    //===End Pencarian berdasarkan tanggal====//

    //=== SEARCH data penjualan====//
    $("#cari").keyup(function() {
        let value = $("#cari").val();
        let tgl = $("#tanggal").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('laporan_penjualan_Pertransaksi/cari.cari') }}",
                data: {
                    value: value,
                    tgl: tgl, 
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
                    let no = 1;
                    response.data.forEach(transaksi => {
                        let subtotal = transaksi.subtotal;
                    //membuat format rupiah Harga//
                    var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
                    ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
                    subtotal_jadi = ribuan_subtotal.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let pembulatan = transaksi.pembulatan;
                    //membuat format rupiah Harga//
                    var reverse_pembulatan = pembulatan.toString().split('').reverse().join(''),
                    ribuan_pembulatan  = reverse_pembulatan.match(/\d{1,3}/g);
                    pembulatan_jadi = ribuan_pembulatan.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let total_bayar = transaksi.total_bayar;
                    //membuat format rupiah Harga//
                    var reverse_total_bayar = total_bayar.toString().split('').reverse().join(''),
                    ribuan_total_bayar  = reverse_total_bayar.match(/\d{1,3}/g);
                    total_bayar_jadi = ribuan_total_bayar.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let jml_bayar = transaksi.jml_bayar;
                    //membuat format rupiah Harga//
                    var reverse_jml_bayar = jml_bayar.toString().split('').reverse().join(''),
                    ribuan_jml_bayar  = reverse_jml_bayar.match(/\d{1,3}/g);
                    jml_bayar_jadi = ribuan_jml_bayar.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    let kembali = transaksi.kembali;
                    //membuat format rupiah Harga//
                    var reverse_kembali = kembali.toString().split('').reverse().join(''),
                    ribuan_kembali  = reverse_kembali.match(/\d{1,3}/g);
                    kembali_jadi = ribuan_kembali.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                        tabledata += `<tr>`;
                        tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                        tabledata += `<td>${transaksi.kode_penjualan}</td>`;
                        tabledata += `<td>${transaksi.tgl_penjualan}</td>`;
                        tabledata += `<td>${transaksi.waktu_penjualan}</td>`;
                        tabledata += `<td>${transaksi.jenis_penjualan}</td>`;
                        tabledata += `<td>${transaksi.cara_bayar}</td>`;
                        tabledata += `<td>${transaksi.bank}</td>`;
                        tabledata += `<td align="right">${subtotal_jadi}</td>`;
                        tabledata += `<td align="right">${pembulatan_jadi}</td>`;
                        tabledata += `<td align="right">${total_bayar_jadi}</td>`;
                        tabledata += `<td align="right">${jml_bayar_jadi}</td>`;
                        tabledata += `<td align="right">${kembali_jadi}</td>`;
                        tabledata += `<td hidden>${transaksi.id_user_input}</td>`;
                        tabledata += `<td hidden>${transaksi.name}</td>`;
                        tabledata += `<td hidden>${transaksi.kode_cabang}</td>`;
                        tabledata += `<td hidden>${transaksi.nama_cabang}</td>`;
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
    <title>Laporan Penjualan Per Transaksi</title>
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
        Laporan Penjualan Per Transaksi
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Penjualan Per Transaksi</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_penjualan_Pertransaksi/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
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
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Jenis</th>
                                        <th>cara bayar</th>
                                        <th>Bank</th>
                                        <th>Subtotal</th>
                                        <th>Pembulatan</th>
                                        <th>Total Bayar</th>
                                        <th>jml_bayar</th>
                                        <th>Kembali</th>
                                        <th hidden>Id User Input</th>
                                        <th hidden>User Input</th>  
                                        <th hidden>Id Apotek</th>
                                        <th hidden>Nama Apotek</th>
                                        <th hidden>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabledata">
    
                                </tbody>
                                <tfoot id="tabledata_foot">
                                    
                                </tfoot>
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