@section('js')

<script type="text/javascript">
    //==== Search autocomplete Produk =================== 
    $(document).ready(function () {
        fetch_product();

        function fetch_product(query = '') {
            $.ajax({
                type: 'GET',
                url: '{{ route("mutasi/getProduk") }}',
                dataType: 'json',
                success: function (response) {
                    // console.log(response.data);
                    let cari_produk;
                    cari_produk += `<option value="" class="cari_produk">Cari Produk/Item...</option>`;
                    response.data.forEach(element => {
                        cari_produk +=
                            `<option value="${element.kode_produk} ${element.id_produk_unit}" class="cari_produk">${element.kode_produk} | ${element.barcode} | ${element.nama_produk} | stok: ${element.qty} | Satuan: ${element.nama_unit} </option>`;
                            // `<option value="${element.kode_produk}" class="cari_produk">${element.kode_produk} | ${element.nama_produk} | stok: ${element.qty} | harga: Rp. ${harga_jual} </option>`;
                    });
                    $("#cari_produk").html(cari_produk);
                }
            })
        }
    });
    //==== End Search autocomplete =================== 

    //==== Search autocomplete dan masuk ke dalam tabel transaksi =================== 
    $("input[name='f_subtotal']").val(0);
    $("input[name='f_total_diskon']").val(0);
    $("input[name='f_total_ppn']").val(0);
    $("input[name='f_total']").val(0);
    $("input[name='f_jml_bayar']").val(0);

    var x = 1;
    $("#cari_produk").change(function(e) {
        e.preventDefault();
        //alert('asd');
        var kode = $(this).val().substring(0,9);
        $('#unit_varian').val($(this).val().substring(10));
        var unit_varian = $('#unit_varian').val();
        
        var url = '{{ url("pembelian/getProdukPilih") }}' + '/' + kode + '/' + unit_varian;
        var _this = $(this);
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: url,
            success:function(data){
                console.log(data);
                _this.val('');

                if(data.data.qty <= 0){
                  alert('Jml Stok saat ini tidak tersedia.')
                }else{
                  var isi = '';
                  isi += '<tr>';
                      isi += '<td class="kode_produk">';
                          isi += data.data.kode_produk;
                          isi += '<input type="hidden" class="form-control" name="kode_produk[]' + x +'" id="kode_produk[]' + x +'" value="'+data.data.kode_produk+'">';
                      isi += '</td>';
                      isi += '<td class="nama_produk">';
                          isi += data.data.nama_produk;
                          isi += '<input type="hidden" class="form-control" name="nama_produk[]' + x +'" id="nama_produk[]' + x +'" value="'+data.data.nama_produk+'">';
                      isi += '</td>'; 
                      isi += '<td class="barcode" hidden>';
                          isi += data.data.barcode;
                          isi += '<input type="text" class="form-control" name="barcode[]' + x +'" id="barcode[]' + x +'" value="'+data.data.barcode+'">';
                      isi += '</td>';
                      isi += '<td class="no_batch" hidden>';
                          isi += data.data.no_batch;
                          isi += '<input type="text" class="form-control" name="no_batch[]' + x +'" id="no_batch[]' + x +'" value="'+data.data.no_batch+'">';
                      isi += '</td>'; 
                      isi += '<td class="komposisi" hidden>';
                          isi += data.data.komposisi;
                          isi += '<input type="text" class="form-control" name="komposisi[]' + x +'" id="komposisi[]' + x +'" value="'+data.data.komposisi+'">';
                      isi += '</td>'; 
                      isi += '<td class="id_jenis" hidden>';
                          isi += data.data.id_jenis;
                          isi += '<input type="text" class="form-control" name="id_jenis[]' + x +'" id="id_jenis[]' + x +'" value="'+data.data.id_jenis+'">';
                      isi += '</td>';
                      isi += '<td class="tgl_kadaluarsa" hidden>';
                          isi += data.data.tgl_kadaluarsa;
                          isi += '<input type="text" class="form-control" name="tgl_kadaluarsa[]' + x +'" id="tgl_kadaluarsa[]' + x +'" value="'+data.data.tgl_kadaluarsa+'">';
                      isi += '</td>';  
                      isi += '<td class="jml_qty" id="jml_qty' + x +'">';
                          isi += data.data.qty;
                      isi += '</td>';
                      
                      isi += '<td class="tambah_jml">';
                          isi += '<input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml[]' + x +'" id="jml[]' + x +'" onclick="mirroring(' + x + ');" onkeyup="mirroring(' + x + ');" value="0">';
                      isi += '</td>';
                      isi += '<td class="tambah_jml_temp" id="tambah_jml_temp' + x +'" contenteditable="true" hidden>';
                          isi += 0;
                      isi += '</td>';

                      isi += '<td>';
                          isi += data.data.nama_unit;
                      isi += '</td>'; 
                      isi += '<td class="kode_nama_unit" hidden>';
                          isi += data.data.id_produk_unit;
                      isi += '</td>'; 
                      isi += '<td class="harga_beli" align="center">';
                          //membuat format rupiah//
                          var reverse = data.data.harga_beli.toString().split('').reverse().join(''),
                          ribuan  = reverse.match(/\d{1,3}/g);
                          hasil_harga_beli = ribuan.join(',').split('').reverse().join('');
                          //End membuat format rupiah//
                          isi += hasil_harga_beli;
                          isi += '<input type="hidden" class="form-control" name="harga_beli[]' + x +'" id="harga_beli[]' + x +'" value="'+data.data.harga_beli+'">';
                      isi += '</td>';
                      isi += '<td class="tambah_margin">';
                          isi += '<input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="margin_p[]' + x +'" id="margin_p[]' + x +'" onkeyup="cari_rp(' + x + ');" value="'+data.data.margin_persen+'">';
                      isi += '</td>';
                      isi += '<td class="tambah_margin_temp" id="tambah_margin_temp' + x +'" contenteditable="true" hidden>';
                          isi += data.data.margin_persen;
                      isi += '</td>';

                      isi += '<td class="tambah_margin_rp">';
                          isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="margin_rp[]' + x +'" id="margin_rp[]' + x +'" onkeyup="cari_persen(' + x + ')" value="'+data.data.margin_rp+'">';
                      isi += '</td>';
                      isi += '<td class="tambah_margin_rp_temp" id="tambah_margin_rp_temp' + x +'" contenteditable="true" hidden>';
                          isi += data.data.margin_rp;
                      isi += '</td>';

                      isi += '<td class="harga_jual" hidden>';
                          isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="harga_j[]' + x +'" id="harga_j[]' + x +'" value="'+data.data.harga_jual+'">';
                      isi += '</td>';
                      isi += '<td class="harga_jual_temp" id="harga_jual_temp' + x +'" contenteditable="true">';
                          isi += data.data.harga_jual;
                      isi += '</td>';
                      isi += '<td align="center">';
                          isi += '<i style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus" value=""></i>';
                      isi += '</td>';
                  isi += '</tr>';
                  
                  $('.tabledata').append(isi);
                  x++;
                }
            }
        })
    })
    //==== End Search autocomplete dan masuk ke dalam tabel transaksi =================== 
    function mirroring(x){
        var jml_stok = $('#jml_qty' + x + '').text();
        var tambah_jml = parseInt($("input[name='jml[]" +x+ "']").val());
        
        if(tambah_jml > jml_stok){
          alert('Jumlah keluar melebihi jumlah stok yang ada...!');
          $("input[name='jml[]" +x+ "']").val('0');
          $('#tambah_jml_temp' + x + '').text('0');
          return (false);
        }else{
          $('#tambah_jml_temp' + x + '').text(tambah_jml); 
        }

        var harga_j = parseInt($("input[name='harga_j[]" +x+ "']").val());
        $('#harga_jual_temp' + x + '').text(harga_j);
    }

    function cari_rp(x){
        var margin_p = parseInt($("input[name='margin_p[]" +x+ "']").val());
        $('#tambah_margin_temp' + x + '').text(margin_p); 
        var harga_beli = parseInt($("input[name='harga_beli[]" +x+ "']").val());

        var margin_rupiah = Math.round((margin_p / 100) * harga_beli);
        //membuat format rupiah//
        var reverse = margin_rupiah.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_margin_rupiah = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
        $("input[name='margin_rp[]" +x+ "']").val(hasil_margin_rupiah);
        $('#tambah_margin_rp_temp' + x + '').text(margin_rupiah); 

        var harga_jual = harga_beli + margin_rupiah;

        //membuat format rupiah//
        var reverse = harga_jual.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_harga_jual = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
        //alert(hasil_harga_jual);
        $("input[name='margin_j[]" +x+ "']").val(hasil_harga_jual);
        $('#harga_jual_temp' + x + '').text(hasil_harga_jual); 
    }

    function cari_persen(x){
        $("input[name='margin_rp[]" + x +"']").maskMoney({thousands:',', decimal:'.', precision:0});

        var margin_rp = $("input[name='margin_rp[]" +x+ "']").val();
        //menghilangka format rupiah//
        var bongkar_margin_rp = margin_rp.replace(/[.](?=.*?\.)/g, '');
        var hasil_bongkar_margin_rp = parseInt(bongkar_margin_rp.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//
     
        $('#tambah_margin_rp_temp' + x + '').text(hasil_bongkar_margin_rp); 

        var harga_b = $("input[name='harga_beli[]" +x+ "']").val();
        //menghilangka format rupiah//
        var bongkar_harga_b = harga_b.replace(/[.](?=.*?\.)/g, '');
        var hasil_bongkar_harga_b = parseInt(bongkar_harga_b.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//
     
        var margin_persen = Math.round((hasil_bongkar_margin_rp / hasil_bongkar_harga_b) * 100);
        $("input[name='margin_p[]" +x+ "']").val(margin_persen);
        $('#tambah_margin_temp' + x + '').text(margin_persen); 

        var harga_jual = hasil_bongkar_harga_b + hasil_bongkar_margin_rp;
        //membuat format rupiah//
        var reverse = harga_jual.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_harga_jual = ribuan.join(',').split('').reverse().join('');

        $("input[name='margin_j[]" +x+ "']").val(hasil_harga_jual);
        $('#harga_jual_temp' + x + '').text(hasil_harga_jual); 

    }

    //==== Hapus Per transaksi barang pada list tabel =================== 
    $('body').on('click','.hapus', function(e){
      e.preventDefault();
      $(this).closest('tr').remove(); 
    })
    //==== End Hapus Per transaksi barang pada list tabel ===============

    //=== Insert data Transaksi Penjualan =================//
    $("#button_form_insert_transaksi").click(function() {
        if($("#jenis_mutasi").val() == ""){
            alert("Pilih Jenis Mutasi. Jenis Mutasi tidak boleh kosong");
            $("#jenis_mutasi").focus();
            return (false);
        }

        if($("#apotek_tujuan").val() == ""){
            alert("Pilih Apotek tujuan. Apotek tujuan harus dipilih");
            $("#apotek_tujuan").focus();
            return (false);
        }

        let jenis_mutasi = $("#jenis_mutasi").val();
        let apotek_asal = $("#apotek_asal").val();
        let kd_apotek_asal = $("#kd_apotek_asal").val();
        let apotek_tujuan = $("#apotek_tujuan").val();

        // untuk Detail //
        let kode_produk = []
        let nama_produk = []
        let barcode = []
        let no_batch = []
        let komposisi = []
        let id_jenis = []
        let tgl_kadaluarsa = []
        let stok = []
        let jml_keluar = []
        let id_unit = []
        let harga_beli = []
        let margin_p = []
        let margin_r = []
        let harga_j = []
        
        $('.kode_produk').each(function() {
            kode_produk.push($(this).text())
        })
        $('.nama_produk').each(function(){
            nama_produk.push($(this).text())
        })
        $('.barcode').each(function(){
            barcode.push($(this).text())
        })
        $('.no_batch').each(function(){
            no_batch.push($(this).text())
        })

        $('.komposisi').each(function(){
            komposisi.push($(this).text())
        })
        $('.id_jenis').each(function(){
            id_jenis.push($(this).text())
        })
        $('.tgl_kadaluarsa').each(function(){
            tgl_kadaluarsa.push($(this).text())
        })

        $('.jml_qty').each(function() {
            stok.push($(this).text())
        })
        $('.tambah_jml_temp').each(function() {
            jml_keluar.push($(this).text())
        })
        $('.kode_nama_unit').each(function() {
            id_unit.push($(this).text())
        })
        $('.harga_beli').each(function() {
            harga_beli.push($(this).text())
        })
        $('.tambah_margin_temp').each(function() {
            margin_p.push($(this).text())
        })
        $('.tambah_margin_rp_temp').each(function() {
            margin_r.push($(this).text())
        })
        $('.harga_jual_temp').each(function() {
            harga_j.push($(this).text())
        })

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ route('mutasi/store') }}",
            data: {
                jenis_mutasi: jenis_mutasi,
                apotek_asal: apotek_asal,
                kd_apotek_asal: kd_apotek_asal,
                apotek_tujuan: apotek_tujuan,

                // untuk Detail //
                kode_produk: kode_produk,
                nama_produk: nama_produk,
                barcode: barcode,
                no_batch: no_batch,
                komposisi: komposisi,
                id_jenis: id_jenis,
                tgl_kadaluarsa: tgl_kadaluarsa,
                stok: stok,
                jml_keluar: jml_keluar,
                id_unit: id_unit,
                harga_beli: harga_beli,
                margin_p: margin_p,
                margin_r: margin_r,
                harga_j: harga_j,
            },
            success: function(response) {
                if(response.res === true) {
                    window.location.href = "{{ route('mutasi.index')}}";
                }else{
                    Swal.fire("Gagal!", "Data mutasi gagal disimpan.", "error");
                }
            }
        });
    });

</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Tambah Mutasi</title>
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
        Tambah Mutasi
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Transaksi</li>
          <li class="breadcrumb-item"><a href="{{ route('mutasi.index') }}">Mutasi</a></li>
          <li class="breadcrumb-item active">Tambah Mutasi</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
        	<div class="card">
            <div class="card-body">
              <form>
                <br>
                <div class="row mb-3" hidden>
                  <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Kode Mutasi</label>
                  <div class="col-sm-3">
                    <input type="text" name="kode_mutasi" id="kode_mutasi" class="form-control" value="{{ $kode }}" style="height: 30px; font-size: 14px; font-weight: bold;" required readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Jenis Mutasi</label>
                  <div class="col-sm-3">
                    <select name="jenis_mutasi" id="jenis_mutasi" class="form-select" style="height: 30px; font-size: 14px;" required>
                      <option value="">Pilih Mutasi...</option>
                      <option value="Keluar">Keluar</option>
                      <option value="Masuk">Masuk</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Apotek Asal</label>
                    <div class="col-sm-3">
                        <input type="text" name="apotek_asal" id="apotek_asal" class="form-control" value="{{ $data_cabang_asal->nama_cabang }}" style="height: 30px; font-size: 14px;" required readonly>
                        <input type="hidden" name="kd_apotek_asal" id="kd_apotek_asal" class="form-control" value="{{ $data_cabang_asal->kode_cabang }}" style="height: 30px; font-size: 14px;" required readonly>
                    </div>
                </div>
                <div class="row mb-3">
                  <label for="inputSupplier" class="col-sm-2 col-form-label">Apotek Tujuan</label>
                  <div class="col-sm-3">
                    <select name="apotek_tujuan" class="js-example-basic-single" id="apotek_tujuan" autofocus="autofocus"  style="width: 100%; height: 34px; font-size: 14px;">
                      <option value="">Cari apotek tujuan...</option>
                      @foreach ($data_cabang_tujuan as $row)
                          <option value="{{ $row->kode_cabang }}" {{ old('kode_cabang') == $row->id ? 'selected':'' }}>{{ $row->nama_cabang }}</option>
                      @endforeach 
                    </select>
                  </div>
                </div>

                <hr style="border:0; height: 1px; background-color: black; ">  
                
                <div class="row mb-3">
                  <div class="col-sm-12">
                      <label><i class="bi bi-upc-scan lg-6"></i> Scan barcode atau cari
                          berdasarkan produk</label>
                      <input type="hidden" name="unit_varian" id="unit_varian" class="form-control"
                          value=""
                          style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                          required readonly>
                      <br>
                      <select name="cari_produk" class="js-example-basic-single" id="cari_produk"
                          autofocus="autofocus" style="width: 100%; height: 34px; font-size: 14px;">

                      </select>
                  </div>
                  <div class="col-sm-2" hidden>
                      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#modalCari" style="margin:24px;">Cari Produk</button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table id="datatabel" class="table table-hover">
                    <thead>
                      <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Stok</th>
                        <th>Jml Keluar</th>
                        <th hidden>Id Unit</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Margin (%)</th>
                        <th>Margin (Rp)</th>
                        <th>Harga Jual</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="tabledata" class="tabledata">
                      
                    </tbody>
                    <tfoot>
                        <tr>
                          <td colspan="8"></td>
                          <td></td>
                          <td>
                            <button type="button" class="btn btn-success btn-sm" style="width: 100%;" id="button_form_insert_transaksi">Simpan Transaksi</button>
                          </td>
                          <td></td>
                        </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- <div class="row mb-3">
                  <div class="col-sm-10"></div>
                  <div class="col-sm-2" align="right">
                    <button type="submit" class="btn btn-success btn-sm float-right">Simpan Transaksi</button>
                  </div>
                </div> -->
                
              </form> 
            </div>
        	</div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="modalCari" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Data Obat</h3>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="#" method="get">
              <div class="input-group mb-3 col-md-6 right">
                <input type="text" name="search" id="search" class="form-control placeholder="Cari Produk . . .">
              </div>
            </form>
                <table id="lookup" class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Kategori</th>
                      <th>Jenis</th>
                      <th>Kemasan</th>
                      <th>Harga</th>
                      <th>Jml</th>
                    </tr>
                  </thead>
                  <tbody id="tabledataModal" data-dismiss="modal">
                    
                  </tbody>
                </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>


</main>
@endsection