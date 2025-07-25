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
  //===Select Transaksi Penjualan====//
  fetchAllUnit();
  function fetchAllUnit() {
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_penjualan/getDataPenjualan') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(penjualan => {
          let total = penjualan.total;

          //membuat format rupiah Harga//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${penjualan.kode_penjualan}</td>`;
          tabledata += `<td>${penjualan.tgl_penjualan}</td>`;
          tabledata += `<td>${penjualan.waktu_penjualan}</td>`;
          if(penjualan.cara_bayar == 'Debit'){
            tabledata += `<td>${penjualan.jenis_penjualan} <i class="bi bi-credit-card-2-back-fill"></i></td>`;
          }else{
            tabledata += `<td>${penjualan.jenis_penjualan}</td>`;
          }
          tabledata += `<td align="right">Rp. ${total_rupiah}</td>`; //total
          tabledata += `<td>${penjualan.status_bayar}</td>`;
          tabledata += `<td>${penjualan.name}</td>`;
          tabledata += `<td hidden>${penjualan.nama_cabang}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${penjualan.kode_penjualan}" data-tgl="${penjualan.tgl_penjualan}" data-jenis="${penjualan.jenis_penjualan}" id="button_view_unit" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button>&nbsp;
            <button type="button" data-id="${penjualan.kode_penjualan}" id="button_print" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
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
      url: "{{ route('transaksi_penjualan/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(penjualan => {
          let total = penjualan.total;

          //membuat format rupiah Harga//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${penjualan.kode_penjualan}</td>`;
          tabledata += `<td>${penjualan.tgl_penjualan}</td>`;
          tabledata += `<td>${penjualan.waktu_penjualan}</td>`;
          if(penjualan.cara_bayar == 'Debit'){
            tabledata += `<td>${penjualan.jenis_penjualan} <i class="bi bi-credit-card-2-back-fill"></i></td>`;
          }else{
            tabledata += `<td>${penjualan.jenis_penjualan}</td>`;
          }
          tabledata += `<td align="right">Rp. ${total_rupiah}</td>`; //total
          tabledata += `<td>${penjualan.status_bayar}</td>`;
          tabledata += `<td>${penjualan.name}</td>`;
          tabledata += `<td hidden>${penjualan.nama_cabang}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${penjualan.kode_penjualan}" data-tgl="${penjualan.tgl_penjualan}" data-jenis="${penjualan.jenis_penjualan}" id="button_view_unit" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button>&nbsp;
            <button type="button" data-id="${penjualan.kode_penjualan}" id="button_print" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//
  
  //===PDF Print Penjualan===============//
  $(document).on("click", "#button_print", function(e) {
    e.preventDefault();
    let kode_penjualan = $(this).data('id');
    
    //transaksi_pembelian/pdf.pdf
    $.ajax({
      type: "GET",
      url: "{{ route('penjualan/pdf') }}",
      data: {
        kode_penjualan: kode_penjualan,
        
      },
      dataType: "json",
      success: function(response) {
        
      }
    });
    let mywindow = window.open("{{ route('penjualan/pdf') }}?kode_penjualan=" + kode_penjualan + "", '_blank');
  });
  //===End PDF Print Penjualan===============//

  //===View Data Penjualan===============//
  $(document).on("click", "#button_view_unit", function(e) {
    e.preventDefault();
    let kode_penjualan = $(this).data('id');
    let tgl_penjualan = $(this).data('tgl');
    let jenis_penjualan = $(this).data('jenis');
    
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_penjualan/getViewPenjualan') }}",
      data: {
        kode_penjualan: kode_penjualan
      },
      dataType: "json",
      success: function(response) {
        $(".kode").text(kode_penjualan);
        $(".jenis").text(jenis_penjualan);
        $(".tgl_trans").text(tgl_penjualan);

        let tbl_detail;
        let no = 0;
        let subtotal = 0;
        response.data.forEach(detail => {
          let harga = detail.harga;
          let diskon_rp = detail.diskon_rp;
          let ppn_rp = detail.ppn_rp;
          let total = detail.total;
          let tambahan = detail.biaya_tambahan;
          let tuslah = detail.tuslah;
          let embalase = detail.embalase;

          //membuat format rupiah Harga//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Diskon_rp//
          var reverse_diskon_rp = diskon_rp.toString().split('').reverse().join(''),
          ribuan_diskon_rp  = reverse_diskon_rp.match(/\d{1,3}/g);
          diskon_rp_rupiah = ribuan_diskon_rp.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah ppn_rp//
          var reverse_ppn_rp = ppn_rp.toString().split('').reverse().join(''),
          ribuan_ppn_rp  = reverse_ppn_rp.match(/\d{1,3}/g);
          ppn_rp_rupiah = ribuan_ppn_rp.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Total//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Biaya Tambahan//
          var reverse_tambahan = tambahan.toString().split('').reverse().join(''),
          ribuan_tambahan  = reverse_tambahan.match(/\d{1,3}/g);
          tambahan_rupiah = ribuan_tambahan.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Biaya Tuslah//
          var reverse_tuslah = tuslah.toString().split('').reverse().join(''),
          ribuan_tuslah  = reverse_tuslah.match(/\d{1,3}/g);
          tuslah_rupiah = ribuan_tuslah.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Biaya Embalase//
          var reverse_embalase = embalase.toString().split('').reverse().join(''),
          ribuan_embalase  = reverse_embalase.match(/\d{1,3}/g);
          embalase_rupiah = ribuan_embalase.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tbl_detail += `<tr>`;
          tbl_detail += `<td>` +no+ `</td>`;
          tbl_detail += `<td>${detail.kode_produk}</td>`;
          tbl_detail += `<td>${detail.nama_produk}</td>`;
          tbl_detail += `<td align="right">${harga_rupiah}</td>`;
          tbl_detail += `<td align="right">${detail.qty}</td>`;
          tbl_detail += `<td>${detail.nama_unit}</td>`; //total
          tbl_detail += `<td align="right">${detail.diskon}%</td>`;
          tbl_detail += `<td align="right">${diskon_rp_rupiah}</td>`;
          tbl_detail += `<td align="right">${detail.ppn}%</td>`;
          tbl_detail += `<td align="right">${ppn_rp_rupiah}</td>`;
          tbl_detail += `<td align="right">${tambahan_rupiah}</td>`;
          tbl_detail += `<td align="right">${tuslah_rupiah}</td>`;
          tbl_detail += `<td align="right">${embalase_rupiah}</td>`;
          tbl_detail += `<td align="right">${total_rupiah}</td>`;
          tbl_detail += `</tr>`;
          let temp_total = `${detail.total}`;
          subtotal = subtotal + parseInt(temp_total);
        });

        //membuat format rupiah subtotal//
        var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
        ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
        subtotal_rupiah = ribuan_subtotal.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        $("#tbl_detail").html(tbl_detail);
        $(".f_subtotal").text(subtotal_rupiah);
      }
    });

    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_penjualan/getViewPenjualanFooter') }}",
      data: {
        kode_penjualan: kode_penjualan
      },
      dataType: "json",
      success: function(response) {
        let pembulatan = response.data.pembulatan;
        let total_bayar = response.data.total_bayar;
        let jml_bayar = response.data.jml_bayar;
        let kembali = response.data.kembali;

        //membuat format rupiah subtotal//
        var reverse_pembulatan = pembulatan.toString().split('').reverse().join(''),
        ribuan_pembulatan  = reverse_pembulatan.match(/\d{1,3}/g);
        pembulatan_rupiah = ribuan_pembulatan.join(',').split('').reverse().join('');

        var reverse_total_bayar = total_bayar.toString().split('').reverse().join(''),
        ribuan_total_bayar  = reverse_total_bayar.match(/\d{1,3}/g);
        total_bayar_rupiah = ribuan_total_bayar.join(',').split('').reverse().join('');

        var reverse_jml_bayar = jml_bayar.toString().split('').reverse().join(''),
        ribuan_jml_bayar  = reverse_jml_bayar.match(/\d{1,3}/g);
        jml_bayar_rupiah = ribuan_jml_bayar.join(',').split('').reverse().join('');

        var reverse_kembali = kembali.toString().split('').reverse().join(''),
        ribuan_kembali  = reverse_kembali.match(/\d{1,3}/g);
        kembali_rupiah = ribuan_kembali.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        $(".f_pembulatan").text(pembulatan_rupiah);
        $(".f_total_bayar").text(total_bayar_rupiah);
        $(".f_cara_bayar").text(response.data.cara_bayar);
        $(".f_bank").text(response.data.bank);
        $(".f_jml_bayar").text(jml_bayar_rupiah);
        $(".f_kembali").text(kembali_rupiah);
      }
    });

    $('#modalViewPenjualan').modal('show');
  });


  //===End View Data Penjualan===========//
</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Penjualan</title>
@endsection

@section('content')

<main id="main" class="main" >
	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Transaksi Penjualan
        <a href="{{ route('transaksi_penjualan.create') }}" class="btn btn-primary btn-sm float-right"><i class="bi bi-plus-square"></i>&nbsp; Tambah Transaksi</a>
        <!-- <button type="button" class="btn btn-success btn-sm right">Tambah Transaksi</button> -->
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Transaksi</li>
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
                              <th>Kode Transaksi</th>
                              <th>Tanggal</th>
                              <th>Waktu</th>
                              <th>Jenis</th>
                              <th>Total</th>
                              <th>Status Bayar</th>
                              <th hidden>Id User Input</th>
                              <th>User Input</th>  
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
                      <th>Biaya Tambahan</th>
                      <th>Tuslah</th>
                      <th>Embalase</th>
                      <th style="text-align: right;">Subtotal</th>
                  </tr>
              </thead>
              <tbody id="tbl_detail" class="tbl_detail">

              </tbody>
              <tfoot>
                  <tr>
                      <td colspan="12"></td>
                      <td><b>Subtotal:</b></td>
                      <td class="f_subtotal" align="right" style="font-weight: bold;">
                        0
                      </td>
                      
                  </tr>
                  <tr>
                    <td colspan="12"></td>
                    <td><b>Pembulatan:</b></td>
                    <td class="f_pembulatan" align="right" style="font-weight: bold;">
                      0
                    </td>
                    
                  </tr>
                  <tr>
                    <td colspan="12"></td>
                    <td><b>Total Bayar:</b></td>
                    <td class="f_total_bayar" align="right" style="font-weight: bold;">
                      0
                    </td>
                    
                  </tr>
                  <tr>
                    <td colspan="12"></td>
                    <td><b>Cara Bayar:</b></td>
                    <td class="f_cara_bayar" align="right" style="font-weight: bold;">
                      0
                    </td>
                    
                  </tr>
                  <tr>
                    <td colspan="12"></td>
                    <td><b>Bank:</b></td>
                    <td class="f_bank" align="right" style="font-weight: bold;">
                      0
                    </td>
                    
                  </tr>
                  <tr>
                    <td colspan="12"></td>
                    <td><b>Jml Bayar:</b></td>
                    <td class="f_jml_bayar" align="right" style="font-weight: bold;">
                      0
                    </td>
                    
                  </tr>
                  <tr>
                    <td colspan="12"></td>
                    <td><b>Kembali:</b></td>
                    <td class="f_kembali" align="right" style="font-weight: bold;">
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