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
    fetchAllPendapatan();
    function fetchAllPendapatan() {
        $.ajax({
        type: "GET",
        url: "{{ route('pendapatan/getDataPendapatan') }}",
        dataType: "json",
        success: function(response) {
            let tabledata;
            let no = 0;
            response.data.forEach(pendapatan => {
            let total = pendapatan.total;

            //membuat format rupiah Harga//
            var reverse_total = total.toString().split('').reverse().join(''),
            ribuan_total  = reverse_total.match(/\d{1,3}/g);
            total_rupiah = ribuan_total.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            no = no + 1
            tabledata += `<tr>`;
            tabledata += `<td>` +no+ `</td>`;
            tabledata += `<td>${pendapatan.tgl_penjualan}</td>`;
            tabledata += `<td hidden>${pendapatan.kode_cabang}</td>`;
            tabledata += `<td>${pendapatan.nama_cabang}</td>`;
            tabledata += `<td align="right">${pendapatan.jml_transaksi} transaksi</td>`;
            tabledata += `<td align="right">Rp. ${total_rupiah}</td>`; //total
            // tabledata += `<td align="center">
            //                 <button type="button" data-id="${pendapatan.tgl_penjualan}" id="button_print" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button>
            //               </td>`;
            tabledata += `</tr>`;
            });
            $("#tabledata").html(tabledata);
        }
        });
    }
    //===End Select Transaksi Penjualan====//

    //=== Pencarian berdasarkan tanggal ====//
    $("#button_cari_tanggal").click(function(){
        let tgl_cari = $("#tanggal").val();
        $.ajax({
        type: "GET",
        url: "{{ route('pendapatan/cari.cari') }}",
        data: {
            tgl_cari: tgl_cari
        },
        dataType: "json",
        success: function(response) {
            let tabledata;
            let no = 0;
            response.data.forEach(pendapatan => {
            let total = pendapatan.total;

            //membuat format rupiah Harga//
            var reverse_total = total.toString().split('').reverse().join(''),
            ribuan_total  = reverse_total.match(/\d{1,3}/g);
            total_rupiah = ribuan_total.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            no = no + 1
            tabledata += `<tr>`;
            tabledata += `<td>` +no+ `</td>`;
            tabledata += `<td>${pendapatan.tgl_penjualan}</td>`;
            tabledata += `<td hidden>${pendapatan.kode_cabang}</td>`;
            tabledata += `<td>${pendapatan.nama_cabang}</td>`;
            tabledata += `<td align="right">${pendapatan.jml_transaksi} transaksi</td>`;
            tabledata += `<td align="right">Rp. ${total_rupiah}</td>`; //total
            // tabledata += `<td align="center">
            //                 <button type="button" data-id="${pendapatan.tgl_penjualan}" id="button_print" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button>
            //               </td>`;
            // tabledata += `</tr>`;
            });
            $("#tabledata").html(tabledata);
        }
        });
    });
    //=== End Pencarian berdasarkan tanggal ====//

</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Pendapatan Apotek</title>
@endsection

@section('content')

<main id="main" class="main" >
	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Pendapatan Apotek
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Pendapatan Apotek</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <div class="row mb-3">
                    <div class="col-4">
                      {{-- <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit" ><i class="bi bi-file-earmark-excel"></i> Excel</button>
                      <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button> --}}
                    </div>
                    <div class="col-2"></div>
                    <div class="col-3">
                      {{-- <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari Nama Apotek..."/> --}}
                    </div>
                    <div class="col-3">
                      <div class="input-group mb-3">
                          <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}">
                          <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Tanggal</th>
                              <th hidden>Kode Cabang</th>
                              <th>Nama Apotek</th>
                              <th>Jml Transaksi</th>
                              <th>Total Pendapatan</th>
                              <th hidden>Opsi</th>
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