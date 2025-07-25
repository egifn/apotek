@section('js')

<script type="text/javascript">
  var y = 0;
  var x = 1;
  var hasil = 0;
  
  //===Menampilkan Modal data produk====//
  fetch_product_data();
    function fetch_product_data() {
        $.ajax({
            type: "GET",
            url: "{{ route('pembelian/getProdukModal') }}",
            dataType: "json",
            success: function(response) {
                let tabledataModal;
                response.data.forEach(daftar => {
                    tabledataModal += `<tr class="pilih" data-kode_produk="${daftar.kode_produk}" data-nama_produk="${daftar.nama_produk}" data-nama_kategori="${daftar.nama_kategori}" data-nama_jenis="${daftar.nama_jenis}" data-kemasan="${daftar.nama_unit}" data-harga="${daftar.harga_jual}" data-qty="${daftar.qty}">`;
                    tabledataModal += `<td>${daftar.kode_produk}</td>`;
                    tabledataModal += `<td>${daftar.nama_produk}</td>`;
                    tabledataModal += `<td>${daftar.nama_kategori}</td>`;
                    tabledataModal += `<td>${daftar.nama_jenis}</td>`;
                    tabledataModal += `<td>${daftar.nama_unit}</td>`;
                    tabledataModal += `<td>${daftar.harga_beli}</td>`;
                    tabledataModal += `<td>${daftar.qty}</td>`;
                    tabledataModal += `</tr>`;
                });
                $("#tabledataModal").html(tabledataModal);
            }
        });
    }
    //=== End Menampilkan Modal data produk====//

    //===Pencarian Modal data produk====//
    $("#search").keyup(function() {
        let value = $("#search").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('pembelian/getProdukModal') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModal;
                    response.data.forEach(daftar => {
                        tabledataModal += `<tr>`;
                        tabledataModal += `<td>${daftar.kode_produk}</td>`;
                        tabledataModal += `<td>${daftar.nama_produk}</td>`;
                        tabledataModal += `<td>${daftar.nama_kategori}</td>`;
                        tabledataModal += `<td>${daftar.nama_jenis}</td>`;
                        tabledataModal += `<td>${daftar.nama_unit}</td>`;
                        tabledataModal += `<td>${daftar.harga_beli}</td>`;
                        tabledataModal += `<td>${daftar.qty}</td>`;
                        tabledataModal += `</tr>`;
                    });
                    $("#tabledataModal").html(tabledataModal);
                }
            });
        }else{
            fetch_product_data();
        }
    });
    //===End Pencarian Modal data produk====//

    //==== Search autocomplete Supplier =================== 
    $(document).ready(function () {
        fetch_supplier();

        function fetch_supplier(query = '') {
            $.ajax({
                type: 'GET',
                url: '{{ route("pembelian/getSupplier") }}',
                dataType: 'json',
                success: function (response) {
                    // console.log(response.data);
                    let cari_supplier;
                    cari_supplier += `<option value="" class="cari_supplier">Cari Supplier...</option>`;
                    response.data.forEach(element => {
                        cari_supplier +=
                            `<option value="${element.id}" class="cari_supplier">${element.kode_supplier} | ${element.nama_supplier}</option>`;
                    });
                    $("#cari_supplier").html(cari_supplier);
                }
            })
        }
    });

    //==== Search autocomplete Produk =================== 
    $(document).ready(function () {
        fetch_product();

        function fetch_product(query = '') {
            $.ajax({
                type: 'GET',
                url: '{{ route("pembelian/getProduk") }}',
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

                var isi = '';

                isi += '<tr>';
                    isi += '<td class="kode_produk">';
                        isi += data.data.kode_produk;
                        isi += '<input type="hidden" class="form-control" name="kode_produk[]' + x +'" id="kode_produk[]' + x +'" value="'+data.data.kode_produk+'">';
                    isi += '</td>';
                    isi += '<td>';
                        isi += data.data.nama_produk;
                    isi += '</td>'; 
                    isi += '<td class="jml_qty" id="jml_qty' + x +'">';
                        isi += data.data.qty;
                    isi += '</td>';
                    isi += '<td class="harga_beli" align="center">';
                        //membuat format rupiah//
                        var reverse = data.data.harga_beli.toString().split('').reverse().join(''),
                        ribuan  = reverse.match(/\d{1,3}/g);
                        hasil_harga_beli = ribuan.join(',').split('').reverse().join('');
                        //End membuat format rupiah//
                        isi += hasil_harga_beli;
                        isi += '<input type="hidden" class="form-control" name="harga_beli[]' + x +'" id="harga_beli[]' + x +'" onclick="jumlah(' + x + ');" onkeyup="jumlah(' + x + ');" value="'+data.data.harga_beli+'">';
                    isi += '</td>';
                    // margin dan Harga jual lama//
                    isi += '<td class="margin_rp_lama" hidden>';
                        isi += data.data.margin_rp;
                        isi += '<input type="hidden" class="form-control" name="margin_rp_lama[]' + x +'" id="margin_rp_lama[]' + x +'" value="'+data.data.margin_rp+'">';
                    isi += '</td>';
                    isi += '<td class="margin_persen_lama" hidden>';
                        isi += data.data.margin_persen;
                        isi += '<input type="hidden" class="form-control" name="margin_persen_lama[]' + x +'" id="margin_persen_lama[]' + x +'" value="'+data.data.margin_persen+'">';
                    isi += '</td>';
                    isi += '<td class="harga_jual_lama" hidden>';
                        isi += data.data.harga_jual;
                        isi += '<input type="hidden" class="form-control" name="harga_jual_lama[]' + x +'" id="harga_jual_lama[]' + x +'" value="'+data.data.harga_jual+'">';
                    isi += '</td>';
                    //=================
                    isi += '<td class="tambah_jml">';
                        isi += '<input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml[]' + x +'" id="jml[]' + x +'" onclick="jumlah(' + x + ');" onkeyup="jumlah(' + x + ');" value="0">';
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

                    isi += '<td class="tambah_diskon">';
                        isi += '<input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon[]' + x +'" id="diskon[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                    isi += '</td>';
                    isi += '<td class="tambah_diskon_temp" id="tambah_diskon_temp' + x +'" contenteditable="true" hidden>';
                        isi += 0;
                    isi += '</td>';

                    isi += '<td class="tambah_diskon_rp">';
                        isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="diskon_rp[]' + x +'" id="diskon_rp[]' + x +'" onkeyup="jumlah_rp(' + x + ')" value="0">';
                    isi += '</td>';
                    isi += '<td class="tambah_diskon_rp_temp" id="tambah_diskon_rp_temp' + x +'" contenteditable="true" hidden>';
                        isi += 0;
                    isi += '</td>';

                    isi += '<td class="tambah_ppn">';
                        isi += '<input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn[]' + x +'" id="ppn[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                    isi += '</td>';
                    isi += '<td class="tambah_ppn_temp" id="tambah_ppn_temp' + x +'" contenteditable="true" hidden>';
                        isi += 0;
                    isi += '</td>';

                    isi += '<td class="tambah_ppn_rp">';
                        isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="ppn_rp[]' + x +'" id="ppn_rp[]' + x +'" onkeyup="jumlah_rp(' + x + ');" value="0">';
                    isi += '</td>';
                    isi += '<td class="tambah_ppn_rp_temp" id="tambah_ppn_rp_temp' + x +'" contenteditable="true" hidden>';
                        isi += 0;
                    isi += '</td>';

                    isi += '<td class="subtotal' + x +'" align="right">';
                        isi += 0;
                    isi += '</td>';
                    isi += '<td align="center">';
                        isi += '<i style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus" value=""></i>';
                    isi += '</td>';
                isi += '</tr>';
                
                $('.tabledata').append(isi);

                //y = $('#f_subtotal').val();

                var f_subtotal_tanpa_rupiah = $('#f_subtotal').val();
                //menghilangka format rupiah//
                var temp_f_subtotal_tanpa_rupiah = f_subtotal_tanpa_rupiah.replace(/[.](?=.*?\.)/g, '');
                var temp_f_subtotal_tanpa_rupiah_jadi = (temp_f_subtotal_tanpa_rupiah.replace(/[^0-9.]/g,''));
                //End menghilangka format rupiah//
                y = temp_f_subtotal_tanpa_rupiah_jadi;
                
                //kalkulasi_total_invoice(x);
                x++;
            }
        })
    })
    //==== End Search autocomplete dan masuk ke dalam tabel transaksi =================== 

    //==== Penjumlahan subtotal detail =================== 
    //var total = 0;
    function jumlah(x) {
      $("input[name='diskon_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
      $("input[name='ppn_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
      var harga_beli = parseInt($("input[name='harga_beli[]" +x+ "']").val());
      var tambah_jml = parseInt($("input[name='jml[]" +x+ "']").val());
      
      var tambah_diskon = parseInt($("input[name='diskon[]" +x+ "']").val());
      var temp_tambah_diskon_rp = Math.round((tambah_diskon / 100)*(harga_beli*tambah_jml));
      //membuat format rupiah//
      var reverse = temp_tambah_diskon_rp.toString().split('').reverse().join(''),
          ribuan  = reverse.match(/\d{1,3}/g);
          hasil_temp_tambah_diskon_rp = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//

      var tambah_ppn = parseInt($("input[name='ppn[]" +x+ "']").val());
      var temp_tambah_ppn_rp = Math.round((tambah_ppn / 100)*(harga_beli*tambah_jml));
      //membuat format rupiah//
      var reverse = temp_tambah_ppn_rp.toString().split('').reverse().join(''),
          ribuan  = reverse.match(/\d{1,3}/g);
          hasil_temp_tambah_ppn_rp = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//

      var subtotal = parseInt(harga_beli * tambah_jml );
      //membuat format rupiah//
      var reverse = subtotal.toString().split('').reverse().join(''),
          ribuan  = reverse.match(/\d{1,3}/g);
          hasil_subtotal = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//

      $('#tambah_jml_temp' + x + '').text(tambah_jml); 
      $('#tambah_diskon_temp' + x + '').text(tambah_diskon); 
      $("input[name='diskon_rp[]" +x+ "']").val(hasil_temp_tambah_diskon_rp);
      $('#tambah_diskon_rp_temp' + x + '').text(hasil_temp_tambah_diskon_rp); 
      $('#tambah_ppn_temp' + x + '').text(tambah_ppn); 
      $("input[name='ppn_rp[]" +x+ "']").val(hasil_temp_tambah_ppn_rp);
      $('#tambah_ppn_rp_temp' + x + '').text(hasil_temp_tambah_ppn_rp); 

      $('.subtotal' + x + '').text(hasil_subtotal); 
        
      kalkulasi_total_invoice(x);
    }

    function jumlah_rp(x) {
        $("input[name='diskon_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
        $("input[name='ppn_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
        var harga_beli = parseInt($("input[name='harga_beli[]" +x+ "']").val());
        var tambah_jml = parseInt($("input[name='jml[]" +x+ "']").val());
        
        var tambah_diskon_rp = ($("input[name='diskon_rp[]" +x+ "']").val());
        //menghilangka format rupiah//
        var temp_tambah_diskon_rp = tambah_diskon_rp.replace(/[.](?=.*?\.)/g, '');
        var temp_tambah_diskon_rp_jadi = parseInt(temp_tambah_diskon_rp.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//
        
        var temp_tambah_diskon_rp =  Math.round((temp_tambah_diskon_rp_jadi / (harga_beli*tambah_jml))*100);
        //membuat format rupiah//
        var reverse = temp_tambah_diskon_rp.toString().split('').reverse().join(''),
                        ribuan  = reverse.match(/\d{1,3}/g);
                        hasil_temp_tambah_diskon_rp = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        var tambah_ppn_rp = ($("input[name='ppn_rp[]" +x+ "']").val());
        //menghilangka format rupiah//
        var temp_tambah_ppn_rp = tambah_ppn_rp.replace(/[.](?=.*?\.)/g, '');
        var temp_tambah_ppn_rp_jadi = parseInt(temp_tambah_ppn_rp.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var temp_tambah_ppn_rp = Math.round((temp_tambah_ppn_rp_jadi / (harga_beli*tambah_jml))*100);
        //membuat format rupiah//
        var reverse = temp_tambah_ppn_rp.toString().split('').reverse().join(''),
                        ribuan  = reverse.match(/\d{1,3}/g);
                        hasil_temp_tambah_ppn_rp = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        var subtotal = parseInt(harga_beli * tambah_jml );
        //membuat format rupiah//
        var reverse = subtotal.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_subtotal = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        $('#tambah_jml_temp' + x + '').text(tambah_jml); 
        $('#tambah_diskon_rp_temp' + x + '').text(tambah_diskon_rp); 
        $("input[name='diskon[]" +x+ "']").val(temp_tambah_diskon_rp);
        $('#tambah_diskon_temp' + x + '').text(temp_tambah_diskon_rp);
        $('#tambah_ppn_rp_temp' + x + '').text(tambah_ppn_rp); 
        $("input[name='ppn[]" +x+ "']").val(temp_tambah_ppn_rp);
        $('#tambah_ppn_temp' + x + '').text(temp_tambah_ppn_rp);
        
        $('.subtotal' + x + '').text(hasil_subtotal); 
        
        kalkulasi_total_invoice(x);
    }

    //==== Penjumlahan Tanggal dengan Input =====//
    $("input[name='termin']").keyup(function(e){
      var input_termin = ($("input[name='termin']").val());
      var jatuh_tempo = new Date(new Date().getTime()+(input_termin*24*60*60*1000)); // 1000 ini buat pengkalian milisecondnya date object
        
      var format_jt = new Intl.DateTimeFormat('id').format(jatuh_tempo);

      $("#jt").val(format_jt); 
    })
    //==== End Penjumlahan Tanggal dengan Input =====//
    
    function kalkulasi_total_invoice(x){
      var tempSubtotal = parseInt(y);
      var harga_beli = parseInt($("input[name='harga_beli[]" +x+ "']").val());
      var tambah_jml = parseInt($("input[name='jml[]" +x+ "']").val());
      var tambah_diskon = ($("input[name='diskon_rp[]" +x+ "']").val());
      var tambah_ppn = ($("input[name='ppn_rp[]" +x+ "']").val());

      //menghilangka format rupiah tambah_diskon//
      var temp_tambah_diskon_nonrupiah = tambah_diskon.replace(/[.](?=.*?\.)/g, '');
      var temp_tambah_diskon_nonrupiah_jadi = parseInt(temp_tambah_diskon_nonrupiah.replace(/[^0-9.]/g,''));
      //End menghilangka format rupiah//

      // //menghilangka format rupiah tambah_ppn//
      var temp_tambah_ppn_nonrupiah = tambah_ppn.replace(/[.](?=.*?\.)/g, '');
      var temp_tambah_ppn_nonrupiah_jadi = parseInt(temp_tambah_ppn_nonrupiah.replace(/[^0-9.]/g,''));
      // //End menghilangka format rupiah//
      
      var sum_f_subtotal = parseInt(harga_beli * tambah_jml - temp_tambah_diskon_nonrupiah_jadi + temp_tambah_ppn_nonrupiah_jadi);
        
      c = tempSubtotal+sum_f_subtotal;
      //membuat format rupiah//
      var reverse = c.toString().split('').reverse().join(''),
          ribuan  = reverse.match(/\d{1,3}/g);
          hasil_c = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//
        
      $('#f_subtotal').val(hasil_c);
      $('#f_total').val(hasil_c);
        
    }
    //==== end Penjumlahan subtotal detail =================== 

    //==== Hapus Per transaksi barang pada list tabel =================== 
    $('body').on('click','.hapus', function(e){
      e.preventDefault();
      $(this).closest('tr').remove(); 
    })
    //==== End Hapus Per transaksi barang pada list tabel ===============

    //=== Insert data Transaksi Penjualan =================//
    $("#button_form_insert_transaksi").click(function() {
      if ($("#jenis_sp").val() == ""){
        alert("Pilih Jenis Surat Pesanan. Jenis Surat Pesanan tidak boleh kosong");
        $("#jenis_sp").focus();
        return (false);
      }

      if ($("#pembelian").val() == ""){
        alert("Pilih Kode Pembelian. Kode Pembelian tidak boleh kosong");
        $("#pembelian").focus();
        return (false);
      }

      if ($("#cari_supplier").val() == ""){
        alert("Pilih Supplier. Supplier tidak boleh kosong");
        $("#cari_supplier").focus();
        return (false);
      }

      if ($("#jenis").val() == ""){
        alert("Pilih Jenis Transaksi. Jenis Transaksi tidak boleh kosong");
        $("#jenis").focus();
        return (false);
      }

      let kode_pembelian = $("#kode_transaksi").val();
      let jenis_surat_pesanan = $("#jenis_sp").val();
      let pembelian = $("#pembelian").val();
      let kode_supplier = $("#cari_supplier").val();
      let jenis_pembelian = $("#jenis").val();
      let termin = $("#termin").val();
      let jt = $("#jt").val();
      let diskon = $("#f_diskon").val();

      // untuk Detail //
      let kode_produk = []
      let jml_qty = []
      let harga_beli = []
      let margin_rp_lama = []
      let margin_persen_lama = []
      let harga_jual_lama = []
      let tambah_jml = []
      let tambah_diskon = []
      let tambah_diskon_rp = []
      let tambah_ppn = []
      let tambah_ppn_rp = []
      let kode_nama_unit = []

      $('.kode_produk').each(function() {
        kode_produk.push($(this).text())
      })
      $('.jml_qty').each(function() {
          jml_qty.push($(this).text())
      })
      $('.harga_beli').each(function() {
        harga_beli.push($(this).text())
      })

      $('.margin_rp_lama').each(function() {
        margin_rp_lama.push($(this).text())
      })
      $('.margin_persen_lama').each(function() {
        margin_persen_lama.push($(this).text())
      })
      $('.harga_jual_lama').each(function() {
        harga_jual_lama.push($(this).text())
      })

      $('.tambah_jml_temp').each(function() {
        tambah_jml.push($(this).text())
      })
      $('.tambah_diskon_temp').each(function() {
        tambah_diskon.push($(this).text())
      })
      $('.tambah_diskon_rp_temp').each(function() {
        tambah_diskon_rp.push($(this).text())
      })
      $('.tambah_ppn_temp').each(function() {
        tambah_ppn.push($(this).text())
      })
      $('.tambah_ppn_rp_temp').each(function() {
        tambah_ppn_rp.push($(this).text())
      })
      $('.kode_nama_unit').each(function() {
            kode_nama_unit.push($(this).text())
      })
      // end Detail // 
        
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "{{ route('pembelian/store') }}",
        data: {
          kode_pembelian: kode_pembelian,
          jenis_surat_pesanan: jenis_surat_pesanan,
          pembelian: pembelian,
          kode_supplier: kode_supplier,
          jenis_pembelian: jenis_pembelian,
          termin: termin,
          jt: jt,
          diskon: diskon,

          // untuk Detail //
          kode_produk: kode_produk,
          jml_qty: jml_qty,
          harga_beli: harga_beli,
          margin_rp_lama: margin_rp_lama,
          margin_persen_lama: margin_persen_lama,
          harga_jual_lama: harga_jual_lama,
          tambah_jml: tambah_jml,
          tambah_diskon: tambah_diskon,
          tambah_diskon_rp: tambah_diskon_rp,
          tambah_ppn: tambah_ppn,
          tambah_ppn_rp: tambah_ppn_rp,
          kode_nama_unit: kode_nama_unit,
          // end Detail //
        },
        success: function(response) {
          if(response.res === true) {
            $("#kode_transaksi").val('');
            $("#jenis_sp").val('');
            $("#kode_pembelian").val('');
            $("#tgl_transaksi").val('');
            $("#jenis").val('');
            $("#termin").val('0');

            window.location.href = "{{ route('pembelian.index')}}";
          }else{
            Swal.fire("Gagal!", "Data transaksi pembelian gagal disimpan.", "error");
          }
        }
      });
    });
    //=== End Insert data Transaksi Penjualan =================//

</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Tambah Transaksi Penjualan</title>
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
        Tambah Transaksi Pembelian
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Transaksi</li>
          <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
          <li class="breadcrumb-item active">Tambah Transaksi Pembelian</li>
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
                <div class="row mb-3">
                  <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">No Surat Pesanan</label>
                  <div class="col-sm-2">
                    <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" value="{{ $kode }}" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" required readonly>
                  </div>

                  <div class="col-sm-3"></div>
                  
                  <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                  <div class="col-sm-3">
                    <select name="jenis" id="jenis" class="form-select" style="height: 30px; font-size: 14px;" required>
                      <option value="">Pilih...</option>
                      <option value="Tunai">Tunai</option>
                      <option value="Kredit">Kredit</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Jenis Surat Pesanan</label>
                  <div class="col-sm-2">
                    <select name="jenis_sp" id="jenis_sp" class="form-select" style="height: 30px; font-size: 14px;" required>
                      <option value="">Pilih Surat Pesanan...</option>
                      <option value="Umum/Regular">Umum/Regular</option>
                      <option value="Narkotika">Narkotika</option>
                      <option value="Psikotropika">Psikotropika</option>
                      <option value="Prekursor">Prekursor</option>
                      <option value="OOT">OOT</option>
                    </select>
                  </div>

                  <div class="col-sm-3"></div>

                  <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Termin</label>
                  <div class="col-sm-2">
                    <div class="input-group">
                      <input type="text" name="termin" id="termin" class="form-control" value="0" style="height: 30px; font-size: 14px; text-align: center;">
                      <span class="input-group-text" id="inputGroupPrepend" style="height: 30px;">Hari</span>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Kode Pembelian</label>
                    <div class="col-sm-2">
                      <select name="pembelian" id="pembelian" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih Kode Pembelian...</option>
                        <option value="Regular">Regular</option>
                        <option value="Panel 1">Panel 1</option>
                        <option value="Panel 2">Panel 2</option>
                      </select>
                    </div>

                  <div class="col-sm-3"></div>
                  <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Tgl. Jatuh Tempo</label>
                  <div class="col-sm-2">
                    <input type="text" name="jt" id="jt" class="form-control" value="{{ date('d/m/Y', strtotime(Carbon\Carbon::today()->toDateString())) }}" style="height: 30px; font-size: 14px; text-align: center;" required readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputSupplier" class="col-sm-2 col-form-label">Supplier</label>
                  <div class="col-sm-4">
                    <select name="cari_supplier" class="js-example-basic-single" id="cari_supplier" autofocus="autofocus"  style="width: 100%; height: 34px; font-size: 14px;">
                      <option value="">Cari Supplier...</option>
                      
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
                        <th>Harga Satuan</th>
                        <th hidden>Margin Rp Lama</th>
                        <th hidden>Margin persen Lama</th>
                        <th hidden>Harga Jual Lama</th>
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
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="tabledata" class="tabledata">
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="9"></td>
                        <td>Subtotal:</td>
                        <td>
                          <input type="text" style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;" class="form-control" name="f_subtotal" id="f_subtotal" value="0" readonly />
                        </td>
                        <td></td>
                      </tr>
                      <tr hidden>
                        <td colspan="9"></td>
                        <td>Ttl Disk:</td>
                        <td>
                          <input type="text" style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;" class="form-control" name="f_total_diskon" id="f_total_diskon" value="0" readonly />
                        </td>
                        <td></td>
                      </tr>
                      <tr hidden>
                        <td colspan="9"></td>
                        <td>Total PPN:</td>
                        <td>
                          <input type="text" style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;" class="form-control" name="f_total_ppn" id="f_total_ppn" value="0" readonly />
                        </td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="9"></td>
                        <td>Total:</td>
                        <td>
                          <input type="text" style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;" class="form-control" name="f_total" id="f_total" value="0" readonly />
                        </td>
                        <td></td>
                      </tr>
                      <tr hidden>
                        <td colspan="9"></td>
                        <td>Jml Bayar:</td>
                        <td>
                          <input type="text" style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;" class="form-control" name="f_jml_bayar" id="f_jml_bayar" value="0" required />
                        </td>
                        <td></td>
                      </tr>
                      <tr hidden>
                        <td colspan="9"></td>
                        <td>Kembali:</td>
                        <td>
                          <input type="text" style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;" class="form-control" name="f_kembali" id="f_kembali" value="0" readonly />
                        </td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="9"></td>
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