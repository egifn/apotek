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
    //===Select Pembayaran====//
    fetchAllDataPembayaran();
    function fetchAllDataPembayaran() {
        $.ajax({
            type: "GET",
            url: "{{ route('hutang_pembelian/getDataHutangPembelian') }}",
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 0;
                response.data.forEach(pembelian => {
                    let total = pembelian.subtotal - pembelian.diskon_rupiah;

                    //membuat format rupiah Harga//
                    var reverse_total = total.toString().split('').reverse().join(''),
                    ribuan_total  = reverse_total.match(/\d{1,3}/g);
                    total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    no = no + 1
                    tabledata += `<tr>`;
                        tabledata += `<td>` +no+ `</td>`;
                        tabledata += `<td>${pembelian.no_faktur}</td>`;
                        tabledata += `<td>${pembelian.tgl_penerimaan}</td>`;
                        tabledata += `<td>${pembelian.tgl_jatuh_tempo}</td>`;
                        tabledata += `<td>${pembelian.kode_pembelian}</td>`;
                        tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
                        tabledata += `<td>${pembelian.nama_supplier}</td>`;
                        tabledata += `<td align="right">Rp. ${total_rupiah}</td>`;
                        tabledata += `<td align="right">0</td>`;
                        tabledata += `<td align="right">Rp. ${total_rupiah}</td>`;
                        if(pembelian.status_pembayaran == 0) {
                            tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
                        }else{
                            tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
                        }

                        if(pembelian.status_pembayaran == 0) {
                          tabledata += `<td align="center">
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_view" class="btn btn-success btn-sm"><i class="bi  bi-eye-fill"></i></button>&nbsp;
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>`
                        }else{
                          tabledata += `<td align="center">
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_view" class="btn btn-success btn-sm"><i class="bi  bi-eye-fill"></i></button>&nbsp;
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_bayar" class="btn btn-warning btn-sm" disabled><i class="bi bi-cash-coin"></i></button>`

                        }
                        
                        tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    }
    //===End Select Pembayaran====//

    //=== Pencarian berdasarkan tanggal ====//
  $("#button_cari_tanggal").click(function(){
    let tgl_cari = $("#tanggal").val();
    $.ajax({
      type: "GET",
      url: "{{ route('hutang_pembelian/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(pembelian => {
          let total = pembelian.subtotal - pembelian.diskon_rupiah;

                    //membuat format rupiah Harga//
                    var reverse_total = total.toString().split('').reverse().join(''),
                    ribuan_total  = reverse_total.match(/\d{1,3}/g);
                    total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                    //End membuat format rupiah//

                    no = no + 1
                    tabledata += `<tr>`;
                        tabledata += `<td>` +no+ `</td>`;
                        tabledata += `<td>${pembelian.no_faktur}</td>`;
                        tabledata += `<td>${pembelian.tgl_penerimaan}</td>`;
                        tabledata += `<td>${pembelian.tgl_jatuh_tempo}</td>`;
                        tabledata += `<td>${pembelian.kode_pembelian}</td>`;
                        tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
                        tabledata += `<td>${pembelian.nama_supplier}</td>`;
                        tabledata += `<td align="right">Rp. ${total_rupiah}</td>`;
                        tabledata += `<td align="right">0</td>`;
                        tabledata += `<td align="right">Rp. ${total_rupiah}</td>`;
                        if(pembelian.status_pembayaran == 0) {
                            tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
                        }else{
                            tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
                        }

                        if(pembelian.status_pembayaran == 0) {
                          tabledata += `<td align="center">
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_view" class="btn btn-success btn-sm"><i class="bi  bi-eye-fill"></i></button>&nbsp;
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>`
                        }else{
                          tabledata += `<td align="center">
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_view" class="btn btn-success btn-sm"><i class="bi  bi-eye-fill"></i></button>&nbsp;
                          <button type="button" 
                            data-id="${pembelian.no_faktur}"
                            data-tgl="${pembelian.tgl_penerimaan}"
                            data-no_sp="${pembelian.kode_pembelian}"
                            data-supplier="${pembelian.nama_supplier}"
                            data-kd_supplier="${pembelian.kode_supplier}"
                            data-jenis_transaksi="${pembelian.jenis_transaksi}"  
                            data-termin="${pembelian.termin}"
                            data-jt="${pembelian.tgl_jatuh_tempo}"
                            data-diskon_rp="${pembelian.diskon_rupiah}"
                            data-diskon_persen="${pembelian.diskon_persen}"
                            data-subtotal="${pembelian.subtotal}"
                            id="button_bayar" class="btn btn-warning btn-sm" disabled><i class="bi bi-cash-coin"></i></button>`

                        }
                        
                        tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//

    //===View Data Pembayaran=====//
    $(document).on("click", "#button_view", function(e) {
      e.preventDefault();
      let no_faktur = $(this).data('id');
      let tgl_faktur = $(this).data('tgl');
      let no_sp = $(this).data('no_sp');
      let supplier = $(this).data('supplier');
      let kd_supplier = $(this).data('kd_supplier');
      let jenis_transaksi = $(this).data('jenis_transaksi');
      let termin = $(this).data('termin');
      let jt = $(this).data('jt');
      let diskon_rp = $(this).data('diskon_rp');
      let diskon_persen = $(this).data('diskon_persen');
      let subtotal_bawah = $(this).data('subtotal');
      $.ajax({
        type: "GET",
        url: "{{ route('hutang_pembelian/getViewPembelian') }}",
        data: {
          no_faktur: no_faktur
        },
        dataType: "json",
        success: function(response) {
          $(".no_faktur").text(no_faktur);
          $(".tgl_faktur").text(tgl_faktur);
          $(".no_sp").text(no_sp);
          $(".supplier").text(supplier);
          $(".kd_supplier").text(kd_supplier);
          $(".jenis_transaksi").text(jenis_transaksi);
          $(".termin").text(termin);
          $(".tgl_jt").text(jt);

          //membuat format rupiah total//
          var reverse_diskon_rp = diskon_rp.toString().split('').reverse().join(''),
            ribuan_diskon_rp  = reverse_diskon_rp.match(/\d{1,3}/g);
            diskon_rp_rupiah = ribuan_diskon_rp.join(',').split('').reverse().join('');

          $("#f_diskon_terima_rupiah").val(diskon_rp_rupiah);
          $("#f_diskon_terima_persen").val(diskon_persen);
          
          let tbl_detail;
          let no = 0;
          let subtotal = 0;
          response.data.forEach(detail => {
            let harga = detail.harga_beli;
            //membuat format rupiah Harga//
            var reverse_harga = harga.toString().split('').reverse().join(''),
            ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
            harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            let diskon_rp = detail.diskon_rp;
            //membuat format rupiah total//
            var reverse_diskon_rp = diskon_rp.toString().split('').reverse().join(''),
            ribuan_diskon_rp  = reverse_diskon_rp.match(/\d{1,3}/g);
            diskon_rp_rupiah = ribuan_diskon_rp.join(',').split('').reverse().join('');

            let ppn_rp = detail.ppn_rp;
            //membuat format rupiah total//
            var reverse_ppn_rp = ppn_rp.toString().split('').reverse().join(''),
            ribuan_ppn_rp  = reverse_ppn_rp.match(/\d{1,3}/g);
            ppn_rp_rupiah = ribuan_ppn_rp.join(',').split('').reverse().join('');

            let total = detail.subtotal;
            //membuat format rupiah total//
            var reverse_total = total.toString().split('').reverse().join(''),
            ribuan_total  = reverse_total.match(/\d{1,3}/g);
            total_rupiah = ribuan_total.join(',').split('').reverse().join('');
            //End membuat format total//
              no = no + 1
              tbl_detail += '<tr>';
              tbl_detail += '<td>' +no+ '</td>';
              tbl_detail += '<td>' +detail.kode_produk+ '</td>';
              tbl_detail += '<td>' +detail.nama_produk+ '</td>';
              tbl_detail += '<td align = "right">' +harga_rupiah+ '</td>';
              tbl_detail += '<td align = "right">' +detail.jml_beli+ '</td>';
              tbl_detail += '<td align = "right">' +detail.jml_terima+ '</td>';
              tbl_detail += '<td>' +detail.nama_unit+ '</td>';
              tbl_detail += '<td align = "right">' +detail.diskon_persen+ '</td>';
              tbl_detail += '<td align = "right">' +diskon_rp_rupiah+ '</td>';
              tbl_detail += '<td align = "right">' +detail.ppn_persen+ '</td>';
              tbl_detail += '<td align = "right">' +ppn_rp_rupiah+ '</td>';
              tbl_detail += '<td align = "right">' +total_rupiah+ '</td>';
              tbl_detail += '</tr>';
              
          });
          $("#tbl_detail").html(tbl_detail);

          var diskon_rp_bawah = $("#f_diskon_terima_rupiah").val();
          //menghilangka format rupiah//
          var temp_diskon_rp_bawah = diskon_rp_bawah.replace(/[.](?=.*?\.)/g, '');
          var temp_diskon_rp_bawah_jadi = parseInt(temp_diskon_rp_bawah.replace(/[^0-9.]/g,''));
          //End menghilangka format rupiah//
          
          var hasil_total_bawah = subtotal_bawah - temp_diskon_rp_bawah_jadi

          //membuat format rupiah total//
          var reverse_subtotal = hasil_total_bawah.toString().split('').reverse().join(''),
            ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
            subtotal_rupiah = ribuan_subtotal.join(',').split('').reverse().join('');
          //End membuat format total//

          $(".f_subtotal_terima").text(subtotal_rupiah);
        }
      });

      $('#modalViewPenerimaan').modal('show');
    });
    //===End View Data Pembayaran=======//

    //===View Data Pembayaran=====//
    $("#subtotal").maskMoney({thousands:',', decimal:'.', precision:0});
    $("#total_bayar").maskMoney({thousands:',', decimal:'.', precision:0});
    $("#kembali").maskMoney({thousands:',', decimal:'.', precision:0});
    
    $(document).on("click", "#button_bayar", function(e) {
      e.preventDefault();
      let no_faktur = $(this).data('id');
      let jt = $(this).data('jt');
      let kode_pembelian = $(this).data('no_sp');
      let nama_supplier = $(this).data('supplier');
      let kode_supplier = $(this).data('kd_supplier');
      let diskon_rupiah = $(this).data('diskon_rp');
      let subtotal = $(this).data('subtotal');

      var total_bayar = subtotal - diskon_rupiah;

      //membuat format rupiah//
      var reverse = total_bayar.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_total_bayar = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//

      $("#no_faktur").val(no_faktur);
      $("#jt").val(jt);
      $("#kode_pembelian").val(kode_pembelian);
      $("#nama_supplier").val(nama_supplier);
      $("#kode_supplier").val(kode_supplier);
      $("#subtotal").val(hasil_total_bayar);
     
      $('#modalPembayaran').modal('show');
    });
    //===End View Data Pembayaran=======//

    $("input[name='total_bayar']").keyup(function(e){
      var subtotal = ($("input[name='subtotal']").val());
      var total_bayar = ($(this).val());

      //menghilangka format rupiah tambah_diskon//
      var temp_subtotal = subtotal.replace(/[.](?=.*?\.)/g, '');
      var temp_f_subtotal_jadi = parseInt(temp_subtotal.replace(/[^0-9.]/g,''));
      //End menghilangka format rupiah//

      //menghilangka format rupiah tambah_diskon//
      var temp_total_bayar = total_bayar.replace(/[.](?=.*?\.)/g, '');
      var temp_f_total_bayar = parseInt(temp_total_bayar.replace(/[^0-9.]/g,''));
      //End menghilangka format rupiah//

      var temp_kembali = temp_f_total_bayar - temp_f_subtotal_jadi;
      
      //membuat format rupiah//
      var reverse = temp_kembali.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_f_kembali = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//

      $("#kembali").val(hasil_f_kembali); 
    });

    //=====Simpan Bayar==================//
    $('#button_form_bayar').click(function(e) {
      if ($("#pembayaran").val() == ""){
        alert("Pembayaran harus diisi. Pilih Pembayaran");
        $("#pembayaran").focus();
        return (false);
      }
      if ($("#bank").val() == ""){
        alert("Bank harus diisi. Pilih Bank");
        $("#bank").focus();
        return (false);
      }
      if ($("#total_bayar").val() == ""){
        alert("Total Bayar tidak boleh kosong. Isi Total Bayar");
        $("#total_bayar").focus();
        return (false);
      }

      let no_faktur = $("#no_faktur").val();
      let jt = $("#jt").val();
      let kode_pembelian = $("#kode_pembelian").val();
      let nama_supplier = $("#nama_supplier").val();
      let kode_supplier = $("#kode_supplier").val();
      let subtotal = $("#subtotal").val();
      let pembayaran = $("#pembayaran").val();
      let bank = $("#bank").val();
      let total_bayar = $("#total_bayar").val();
      let kembali = $("#kembali").val();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "{{ route('hutang_pembelian/store') }}",
        data: {
          no_faktur: no_faktur,
          jt: jt,
          kode_pembelian: kode_pembelian,
          nama_supplier: nama_supplier,
          kode_supplier: kode_supplier,
          subtotal: subtotal,
          pembayaran: pembayaran,
          bank: bank,
          total_bayar: total_bayar,
          kembali: kembali,
        },
        success: function(response) {
          if(response.res === true) {
            $("#modalPembayaran").modal('hide');
            window.location.href = "{{ route('hutang_pembelian.index')}}";
          }else{
            Swal.fire("Gagal!", "Data unit gagal disimpan.", "error");
          }
        }
      });
    });
    //-----End Simpan Bayar=============//
</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Bayar Pembelian</title>
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
        Bayar Pembelian 
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Transaksi</li>
          <li class="breadcrumb-item active">Bayar Pembelian</li>
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
                              <th>No Faktur</th>
                              <th>Tgl Faktur</th>
                              <th>Jatuh Tempo</th>
                              <th>No SP</th>
                              <th>Tgl SP</th>
                              <th>Supplier</th>
                              <th>Jml Bayar</th>
                              <th>Jml Retur</th>
                              <th>Total Bayar</th>
                              <th>status bayar</th>
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

    <div class="modal fade" id="modalViewPenerimaan">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">No Faktur: </label> 
                <label for="inputNama" class="form-label no_faktur" style="font-weight: bold;"></label> 
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Transaksi: </label> 
                <label for="inputNama" class="form-label jenis_transaksi" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Tgl Faktur: </label> 
                <label for="inputNama" class="form-label tgl_faktur" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Termin: </label> 
                <label for="inputNama" class="form-label termin" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">No Surat Pesanan: </label> 
                <label for="inputNama" class="form-label no_sp" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Tgl. Jatuh Tempo: </label> 
                <label for="inputNama" class="form-label tgl_jt" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Supplier: </label> 
                <label for="inputNama" class="form-label supplier" style="font-weight: bold;"></label>
                <label for="inputNama" class="form-label kd_supplier" style="font-weight: bold;" hidden></label>
              </div>
            </div>

            <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Harga Satuan</th>
                      <th>Jml Pesan</th>
                      <th>Jml Terima</th>
                      <th>Satuan</th>
                      <th>Diskon (%)</th>
                      <th>Diskon (Rp)</th>
                      <th>PPN (%)</th>
                      <th>PPN (Rp)</th>
                      <th style="text-align: right;">Total</th>
                  </tr>
              </thead>
              <tbody id="tbl_detail" class="tbl_detail">

              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3"></td>
                  <td></td>
                  <td></td>
                  
              </tr>
              <tr>
                <td></td>
                <td><b>Diskon(Rp):</b></td>
                <td class="f_diskon_rp" align="right" style="font-weight: bold;">
                  <input type="text"
                        style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                        class="form-control" name="f_diskon_terima_rupiah" id="f_diskon_terima_rupiah" value="0" readonly/>
                </td>
                <td></td>
                <td><b>Diskon(%):</b></td>
                <td class="f_diskon_rp" align="right" style="font-weight: bold;">
                  <input type="text"
                        style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                        class="form-control" name="f_diskon_terima_persen" id="f_diskon_terima_persen" value="0" readonly/>
                </td>
                
                <td colspan="4"></td>
                <td><b>Subtotal:</b></td>
                <td class="f_subtotal_terima" align="right" style="font-weight: bold;">
                  0
                </td>
                      
              </tr>
              </tfoot>
            </table>
            
          </div>>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="button_tampil_bayar" hidden><i class="bi bi-save"></i> Bayar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalPembayaran" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Form Pembayaran</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <br>
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body"> 
                    <br>
                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">No. Faktur</label>
                      <div class="col-sm-6">
                        <input type="text" name="no_faktur" id="no_faktur" class="form-control" value="" style="height: 30px; font-size: 14px;" required readonly>
                        <input type="hidden" name="kode_pembelian" id="kode_pembelian" class="form-control" value="" style="height: 30px; font-size: 14px;" required readonly>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Tgl. Jatuh Tempo</label>
                      <div class="col-sm-6">
                        <input type="text" name="jt" id="jt" class="form-control" value="" style="height: 30px; font-size: 14px;" required readonly>
                      </div>
                    </div>
  
                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Nama Supplier</label>
                      <div class="col-sm-6">
                        <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" value="" style="height: 30px; font-size: 14px;" required readonly>
                        <input type="hidden" name="kode_supplier" id="kode_supplier" class="form-control" value="" style="height: 30px; font-size: 14px;" required readonly>
                      </div>
                    </div>
  
                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Subtotal</label>
                      <div class="col-sm-6">
                        <input type="text" name="subtotal" id="subtotal" class="form-control" value="" style="height: 30px; font-size: 14px;" required readonly>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Pembayaran</label>
                      <div class="col-sm-6">
                        <select name="pembayaran" id="pembayaran" class="form-select" style="height: 30px; font-size: 14px;" required>
                          <option value="">Pilih...</option>
                          <option value="Tunai">Tunai</option>
                          <option value="Debit">Debit</option>
                        </select>
                      </div>
                    </div> 

                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Bank</label>
                      <div class="col-sm-6">
                        <select name="bank" id="bank" class="form-select" style="height: 30px; font-size: 14px;" required>
                          <option value="">Pilih...</option>
                          <option value="Kas Umum">Kas Umum (Non Bank)</option>
                          <option value="BCA">BCA</option>
                          <option value="BNI">BNI</option>
                          <option value="BRI">BRI</option>
                          <option value="MANDIRI">MANDIRI</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Total Bayar</label>
                      <div class="col-sm-6">
                        <input type="text" name="total_bayar" id="total_bayar" class="form-control" value="" style="height: 30px; font-size: 14px;"  value="0" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-sm-1"></div>
                      <label class="col-sm-2 col-form-label" align="right">Kembali</label>
                      <div class="col-sm-6">
                        <input type="text" name="kembali" id="kembali" class="form-control" value="" style="height: 30px; font-size: 14px;" value="0" required readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" id="button_form_bayar" data-dismiss="modal"><i class="bi bi-save"></i> Bayar</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
            </div>
          </div>
      </div>
    </div>

</main>
@endsection



@section('js')
  
    
@endsection()