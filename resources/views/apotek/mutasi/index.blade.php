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
 
  fetchAllUnit();
  function fetchAllUnit() {
    $.ajax({
      type: "GET",
      url: "{{ route('mutasi/getDataMutasi') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(mutasi => {
          let total = mutasi.total;

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${mutasi.kode_mutasi}</td>`;
          tabledata += `<td>${mutasi.tgl_mutasi}</td>`;
          tabledata += `<td>${mutasi.nama_cabang_asal}</td>`;
          tabledata += `<td>${mutasi.nama_cabang_tujuan}</td>`;
          //tabledata += `<td>${mutasi.name}</td>`;
          //tabledata += `<td hidden>${mutasi.nama_cabang}</td>`;
          tabledata += `<td align="center">
              <button type="button" data-id="${mutasi.kode_mutasi}" id="button_print_mutasi" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }

  $("#button_cari_tanggal").click(function(){
    let tgl_cari = $("#tanggal").val();
    $.ajax({
      type: "GET",
      url: "{{ route('mutasi/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(mutasi => {
          let total = mutasi.total;

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${mutasi.kode_mutasi}</td>`;
          tabledata += `<td>${mutasi.tgl_mutasi}</td>`;
          tabledata += `<td>${mutasi.nama_cabang_asal}</td>`;
          tabledata += `<td>${mutasi.nama_cabang_tujuan}</td>`;
          //tabledata += `<td>${mutasi.name}</td>`;
          //tabledata += `<td hidden>${mutasi.nama_cabang}</td>`;
          tabledata += `<td align="center">
              <button type="button" data-id="${mutasi.kode_mutasi}" id="button_print_mutasi" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  
  //===PDF Data Pembelian===============//
  $(document).on("click", "#button_print_mutasi", function(e) {
    e.preventDefault();
    let kode_mutasi = $(this).data('id');
    
    //transaksi_pembelian/pdf.pdf
    $.ajax({
      type: "GET",
      url: "{{ route('mutasi/pdf.pdf') }}",
      data: {
        kode_mutasi: kode_mutasi,
        
      },
      dataType: "json",
      success: function(response) {
        
      }
    });
    let mywindow = window.open("{{ route('mutasi/pdf.pdf') }}?kode_mutasi=" + kode_mutasi + "", '_blank');
  });
  //===End PDF Data Pembelian===============//

</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Mutasi</title>
@endsection

@section('content')

<main id="main" class="main">
	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Mutasi
        <a href="{{ route('mutasi_create.create') }}" class="btn btn-success btn-sm float-right"><i class="bi bi-plus-square"></i>&nbsp; Tambah Mutasi</a>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Transaksi</li>
          <li class="breadcrumb-item active">Mutasi</li>
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
                      {{-- <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/> --}}
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
                              <th>Kode Transaksi</th>
                              <th>Tanggal</th>
                              <th>Apotek Asal</th>
                              <th>Apotek Tujuan</th>
                              <th hidden>Id User Input</th>
                              <th hidden>User Input</th>  
                              <th hidden>Id Apotek</th>
                              <th hidden>Nama Apotek</th>
                              <th>Opsi</th>
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