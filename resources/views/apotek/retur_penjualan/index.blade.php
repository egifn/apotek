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
  //===Select Transaksi retur====//
  fetchAllDataRetur();
  function fetchAllDataRetur() {
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_retur_penjualan/getDataReturPenjualan') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(retur => {
          let subtotal_ret = retur.total_bayar;

          //membuat format rupiah Harga//
          var reverse_subtotal_ret = subtotal_ret.toString().split('').reverse().join(''),
          ribuan_reverse_subtotal_ret  = reverse_subtotal_ret.match(/\d{1,3}/g);
          total_ribuan_reverse_subtotal_ret = ribuan_reverse_subtotal_ret.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${retur.kode_retur}</td>`;
          tabledata += `<td>${retur.tgl_retur}</td>`;
          tabledata += `<td>${retur.kode_penjualan}</td>`;
          tabledata += `<td>${retur.jenis_transaksi}</td>`;
          tabledata += `<td align="right">Rp. ${total_ribuan_reverse_subtotal_ret}</td>`;
          
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }

    });
  }
  //===End Select Transaksi retur====//

  //=== Pencarian berdasarkan tanggal ====//
  $("#button_cari_tanggal").click(function(){
    let tgl_cari = $("#tanggal").val();
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_retur_penjualan/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(retur => {
          let subtotal_ret = retur.total_bayar;

          //membuat format rupiah Harga//
          var reverse_subtotal_ret = subtotal_ret.toString().split('').reverse().join(''),
          ribuan_reverse_subtotal_ret  = reverse_subtotal_ret.match(/\d{1,3}/g);
          total_ribuan_reverse_subtotal_ret = ribuan_reverse_subtotal_ret.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${retur.kode_retur}</td>`;
          tabledata += `<td>${retur.tgl_retur}</td>`;
          tabledata += `<td>${retur.kode_penjualan}</td>`;
          tabledata += `<td>${retur.jenis_transaksi}</td>`;
          tabledata += `<td align="right">Rp. ${total_ribuan_reverse_subtotal_ret}</td>`;
          
          tabledata += `</tr>`;
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
    <title>Retur Penjualan</title>
@endsection

@section('content')

<main id="main" class="main" >
	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Retur Penjualan
        <a href="{{ route('transaksi_retur_penjualan.create') }}" class="btn btn-primary btn-sm float-right"><i class="bi bi-plus-square"></i>&nbsp; Tambah Retur</a>
        <!-- <button type="button" class="btn btn-success btn-sm right">Tambah Transaksi</button> -->
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Retur</li>
          <li class="breadcrumb-item active">Penjualan</li>
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
                              <th>Kode Retur</th>
                              <th>Tgl Retur</th>
                              <th>Kode_pembelian</th>
                              <th>Jenis Transaksi</th>
                              <th>Total Retur</th>
                              <th hidden>Id User Input</th>
                              <th hidden>User Input</th>  
                              <th hidden>Id Apotek</th>
                              <th hidden>Nama Apotek</th>
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

    <div class="modal fade" id="modalViewPenjualan">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Transaksi Penjualan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Kode Penjualan: </label> 
                <label for="inputNama" class="form-label kode" style="font-weight: bold;"></label> 
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Transaksi: </label> 
                <label for="inputNama" class="form-label jenis" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Tgl. Penjualan: </label> 
                <label for="inputNama" class="form-label tgl_trans" style="font-weight: bold;"></label>
              </div>
            </div>

            <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Harga Satuan</th>
                      <th>Jml Beli</th>
                      <th hidden>jml_temp</th>
                      <th>Satuan</th>
                      <th>Diskon (%)</th>
                      <th hidden>diskon_temp</th>
                      <th>Diskon (Rp)</th>
                      <th hidden>diskon_rp_temp</th>
                      <th>PPN (%)</th>
                      <th hidden>ppn_temp</th>
                      <th>PPN (Rp)</th>
                      <th hidden>ppn_rp_temp</th>
                      <th style="text-align: right;">Subtotal</th>
                  </tr>
              </thead>
              <tbody id="tbl_detail" class="tbl_detail">

              </tbody>
              <tfoot>
                  <tr>
                      <td colspan="9"></td>
                      <td><b>Subtotal:</b></td>
                      <td class="f_subtotal" align="right" style="font-weight: bold;">
                        0
                      </td>
                      
                  </tr>
              </tfoot>
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