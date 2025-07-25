@section('js')
<script type="text/javascript">
  var no_update = 1;

  //===Select data Produk====//
  fetchAllProduk();
  function fetchAllProduk(){
    $.ajax({
      type: "GET",
      url: "{{ route('produk/getDataProduk.getDataProduk') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(pdk => {
            // let harga_beli = pdk.harga_beli;
            // //membuat format rupiah Harga//
            // var reverse_harga_beli = harga_beli.toString().split('').reverse().join(''),
            // ribuan_harga_beli  = reverse_harga_beli.match(/\d{1,3}/g);
            // harga_beli_jadi = ribuan_harga_beli.join(',').split('').reverse().join('');
            // //End membuat format rupiah//
           
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${pdk.kode_cabang}</td>`;
            tabledata += `<td hidden>${pdk.nama_cabang}</td>`;
            tabledata += `<td>${pdk.kode_produk}</td>`;
            tabledata += `<td>${pdk.nama_produk}</td>`;
            tabledata += `<td>${pdk.komposisi}</td>`;
            tabledata += `<td hidden>${pdk.barcode}</td>`;
            tabledata += `<td hidden>${pdk.no_batch}</td>`;
            tabledata += `<td hidden>${pdk.id_jenis}</td>`;
            tabledata += `<td>${pdk.nama_jenis}</td>`;
            tabledata += `<td>${pdk.kode_pembelian}</td>`;
            tabledata += `<td>${pdk.tipe}</td>`;
            tabledata += `<td>${pdk.qty}</td>`;
            tabledata += `<td hidden>${pdk.id_unit}</td>`;
            tabledata += `<td>${pdk.nama_unit_terkecil}</td>`;
            tabledata += `<td> ${pdk.harga_beli} </td>`;
            tabledata += `<td>${pdk.margin_persen}</td>`;
            tabledata += `<td>${pdk.margin_rp}</td>`;
            tabledata += `<td>${pdk.harga_jual}</td>`;
            tabledata += `<td hidden>${pdk.qty_min}</td>`;
            tabledata += `<td>${pdk.tgl_kadaluarsa}</td>`;
            tabledata += `<td>${pdk.nama_supplier}</td>`;
            tabledata += `<td hidden>${pdk.id_user_input}</td>`;
            tabledata += `<td hidden>${pdk.name}</td>`;
            tabledata += `<td align="center">
              <button type="button" 
              data-id="${pdk.kode_produk}"
              data-nama_produk="${pdk.nama_produk}"
              data-komposisi="${pdk.komposisi}"
              data-kode_cabang="${pdk.kode_cabang}"
              data-barcode="${pdk.barcode}"
              data-batch="${pdk.no_batch}"
              data-tgl_kadaluarsa="${pdk.tgl_kadaluarsa}"
              data-qty_terkecil="${pdk.qty}"
              data-qty_min="${pdk.qty_min}"
              data-id_unit="${pdk.id_unit}"
              data-id_jenis="${pdk.id_jenis}"
              data-tipe="${pdk.tipe}"
              id="button_edit_produk" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Produk====//

  //=== Edit Data Produk =================================================//
  $(document).on("click", "#button_edit_produk", function(e) {
    e.preventDefault();
    let kode_produk = $(this).data('id');
    let nama_produk = $(this).data('nama_produk');
    let komposisi = $(this).data('komposisi');
    let kode_cabang = $(this).data('kode_cabang');
    let barcode = $(this).data('barcode');
    let no_batch = $(this).data('batch');
    let tgl_kadaluarsa = $(this).data('tgl_kadaluarsa');
    let qty_terkecil = $(this).data('qty_terkecil');
    let qty_min = $(this).data('qty_min');
    let id_unit = $(this).data('id_unit');
    let id_jenis = $(this).data('id_jenis');
    let tipe = $(this).data('tipe');
    $.ajax({
      type: "GET",
      url: "{{ route('produk/getDetailData.getDetailData') }}",
      data: {
        kode_produk: kode_produk
      },
      dataType: "json",
      success: function(response) {
        $("#update_kode_produk").val(kode_produk);
        $("#update_komposisi").val(komposisi);
        $("#update_apotek").val(kode_cabang);
        $("#update_barcode").val(barcode);
        $("#update_no_batch").val(no_batch);
        $("#update_nama_obat").val(nama_produk);
        $("#update_jenis").val(id_jenis);
        $("#update_tipe").val(tipe);
        $("#update_tgl_k").val(tgl_kadaluarsa);
        $("#update_stok").val(qty_terkecil);
        $("#update_stok_min").val(qty_min);
        $("#update_unit_terkecil").val(id_unit);
        let update_tabledata;
        response.data.forEach(detail => {
          update_tabledata += '<tr>';
            update_tabledata += '<td hidden>' +no_update+ '</td>';

            update_tabledata += '<td>';
              update_tabledata += '<select name="update_kode_unit' + no_update +'" id="update_kode_unit' + no_update +'" class="form-select" onchange="update_tampilkan(' + no_update + ')"><option value="'+detail.id_produk_unit+'">'+detail.nama_unit+'</option>@foreach ($data_unit as $row)<option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_unit }}</option>@endforeach</select>';
            update_tabledata += '</td>';
            update_tabledata += '<td class="update_kode_unit_temp" id="update_kode_unit_temp' + no_update + '" contenteditable="true" hidden>' +detail.id_produk_unit+ '</td>';

            update_tabledata += '<td class="update_jml_qty">';
              update_tabledata += '<input type="number" class="form-control" name="update_jml_qty[]' + no_update +'" id="update_jml_qty[]' + no_update +'" onkeyup="update_tampilkan(' + no_update + ');" value="' +detail.qty_unit+ '">';
            update_tabledata += '</td>';
            update_tabledata += '<td class="update_jml_qty_temp" id="update_jml_qty_temp' + no_update + '" contenteditable="true" hidden>' +detail.qty_unit+ '</td>';

            update_tabledata += '<td class="update_harga_b">';
              update_tabledata += '<input type="text" class="form-control" name="update_harga_b[]' + no_update +'" id="update_harga_b[]' + no_update +'" onkeyup="update_cari_margin_persen(' + no_update + ');" value="' +detail.harga_beli+ '">';
            update_tabledata += '</td>';
            update_tabledata += '<td class="update_harga_b_temp" id="update_harga_b_temp' + no_update + '" contenteditable="true" hidden>' +detail.harga_beli+ '</td>';

            update_tabledata += '<td class="update_margin_p">';
              update_tabledata += '<input type="text" class="form-control" name="update_margin_p[]' + no_update +'" id="update_margin_p[]' + no_update +'" onkeyup="update_cari_margin_rp(' + no_update + ');" value="' +detail.margin_persen+ '">';
            update_tabledata += '</td>';
            update_tabledata += '<td class="update_margin_p_temp" id="update_margin_p_temp' + no_update + '" contenteditable="true" hidden>' +detail.margin_persen+ '</td>';
            
            update_tabledata += '<td class="update_margin_r">';
              update_tabledata += '<input type="text" class="form-control" name="update_margin_r[]' + no_update +'" id="update_margin_r[]' + no_update +'" onkeyup="update_cari_margin_persen(' + no_update + ');" value="' +detail.margin_rp+ '">';
            update_tabledata += '</td>';
            update_tabledata += '<td class="update_margin_r_temp" id="update_margin_r_temp' + no_update + '" contenteditable="true" hidden>' +detail.margin_rp+ '</td>';

            update_tabledata += '<td class="update_harga_j">';
              update_tabledata += '<input type="text" class="form-control" name="update_harga_j[]' + no_update +'" id="update_harga_j[]' + no_update +'" value="' +detail.harga_jual+ '" readonly>';
            update_tabledata += '</td>';
            update_tabledata += '<td class="update_harga_j_temp" id="update_harga_j_temp' + no_update + '" contenteditable="true" hidden>' +detail.harga_jual+ '</td>';

            update_tabledata += '<td align="center">';
              update_tabledata += '<i style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus" value=""></i>';
            update_tabledata += '</td>';
          update_tabledata += '</tr>';
          no_update++;
        });
        $("#update_tabledata").html(update_tabledata);
      }
    });
    $('#modalEditProduk').modal('show');
  });

  //=== SEARCH Select data unit/kategori====//
  $("#cari_produk").keyup(function() {
    let value = $("#cari_produk").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('produk/getDataProduk.getDataProduk') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(pdk => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${pdk.kode_produk}</td>`;
            tabledata += `<td>${pdk.nama_produk}</td>`;
            tabledata += `<td>${pdk.komposisi}</td>`;
            tabledata += `<td hidden>${pdk.id_jenis}</td>`;
            tabledata += `<td>${pdk.nama_jenis}</td>`;
            tabledata += `<td>${pdk.kode_pembelian}</td>`;
            tabledata += `<td>${pdk.tipe}</td>`;
            tabledata += `<td>${pdk.qty}</td>`;
            tabledata += `<td hidden>${pdk.id_unit}</td>`;
            tabledata += `<td>${pdk.nama_unit}</td>`;
            tabledata += `<td>${pdk.harga_beli}</td>`;
            tabledata += `<td>${pdk.margin_persen}</td>`;
            tabledata += `<td>${pdk.margin_rp}</td>`;
            tabledata += `<td>${pdk.harga_jual}</td>`;
            tabledata += `<td hidden>${pdk.qty_min}</td>`;
            tabledata += `<td>${pdk.tgl_kadaluarsa}</td>`;
            tabledata += `<td>${pdk.nama_supplier}</td>`;
            tabledata += `<td hidden>${pdk.id_user_input}</td>`;
            tabledata += `<td hidden>${pdk.name}</td>`;
            tabledata += `<td align="center">
              <button type="button" 
              data-id="${pdk.kode_produk}"
              data-nama_produk="${pdk.nama_produk}"
              data-komposisi="${pdk.komposisi}"
              data-kode_cabang="${pdk.kode_cabang}"
              data-barcode="${pdk.barcode}"
              data-batch="${pdk.no_batch}"
              data-tgl_kadaluarsa="${pdk.tgl_kadaluarsa}"
              data-qty_terkecil="${pdk.qty}"
              data-qty_min="${pdk.qty_min}"
              data-id_unit="${pdk.id_produk_unit}"
              data-id_jenis="${pdk.id_jenis}"
              data-tipe="${pdk.tipe}"
              id="button_edit_produk" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
      fetchAllProduk();
    }
  });
  //=== End SEARCH Select data unit/kategori====//

  //=== Tambah Satuan ke tabel ===//
  var x = 1;
  $("#button_tambah_satuan").click(function(e) {
    e.preventDefault();
    var isi = '';

    isi += '<tr>';
      isi += '<td hidden>' +x+ '</td>';

      isi += '<td>';
        isi += '<select name="kode_unit' + x +'" id="kode_unit' + x +'" class="form-select" onchange="tampilkan(' + x + ')"><option value="">Pilih Satuan</option>@foreach ($data_unit as $row)<option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_unit }}</option>@endforeach</select>';
      isi += '</td>';

      isi += '<td class="kode_unit_temp" id="kode_unit_temp' + x +'" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="jml_qty">';
        isi += '<input type="number" class="form-control" name="jml_qty[]' + x +'" id="jml_qty[]' + x +'" onkeyup="tampilkan(' + x + ');" value="0">';
      isi += '</td>';
      isi += '<td class="jml_qty_temp" id="jml_qty_temp' + x +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="harga_b">';
        isi += '<input type="text" class="form-control" name="harga_b[]' + x +'" id="harga_b[]' + x +'" onkeyup="cari_margin_persen(' + x + ');" value="0">';
      isi += '</td>';
      isi += '<td class="harga_b_temp" id="harga_b_temp' + x +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="margin_p">';
        isi += '<input type="text" class="form-control" name="margin_p[]' + x +'" id="margin_p[]' + x +'" onkeyup="cari_margin_rp(' + x + ');" value="0">';
      isi += '</td>';
      isi += '<td class="margin_p_temp" id="margin_p_temp' + x +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';
      
      isi += '<td class="margin_r">';
        isi += '<input type="text" class="form-control" name="margin_r[]' + x +'" id="margin_r[]' + x +'" onkeyup="cari_margin_persen(' + x + ');" value="0">';
      isi += '</td>';
      isi += '<td class="margin_r_temp" id="margin_r_temp' + x +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="harga_j">';
        isi += '<input type="text" class="form-control" name="harga_j[]' + x +'" id="harga_j[]' + x +'" readonly>';
      isi += '</td>';
      isi += '<td class="harga_j_temp" id="harga_j_temp' + x +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td align="center">';
        isi += '<i style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus" value=""></i>';
      isi += '</td>';
    isi += '</tr>';

    $('.tabledata_satuan').append(isi);
    x++;
  })
  //=== End Tambah Satuan ke tabel ===//

  //=== Tambah update Satuan ke tabel ===//
  $("#update_button_tambah_satuan").click(function(e) {
    e.preventDefault();
    var isi = '';
    isi += '<tr>';
      isi += '<td hidden>' +no_update+ '</td>';

      isi += '<td>';
        isi += '<select name="update_kode_unit' + no_update +'" id="update_kode_unit' + no_update +'" class="form-select" onchange="update_tampilkan(' + no_update + ')"><option value="">Pilih Satuan</option>@foreach ($data_unit as $row)<option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_unit }}</option>@endforeach</select>';
      isi += '</td>';

      isi += '<td class="update_kode_unit_temp" id="update_kode_unit_temp' + no_update +'" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="update_jml_qty">';
        isi += '<input type="number" class="form-control" name="update_jml_qty[]' + no_update +'" id="update_jml_qty[]' + no_update +'" onkeyup="update_tampilkan(' + no_update + ');" value="0">';
      isi += '</td>';
      isi += '<td class="update_jml_qty_temp" id="update_jml_qty_temp' + no_update +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="update_harga_b">';
        isi += '<input type="text" class="form-control" name="update_harga_b[]' + no_update +'" id="update_harga_b[]' + x +'" onkeyup="update_cari_margin_persen(' + no_update + ');" value="0">';
      isi += '</td>';
      isi += '<td class="update_harga_b_temp" id="update_harga_b_temp' + no_update +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="update_margin_p">';
        isi += '<input type="text" class="form-control" name="update_margin_p[]' + no_update +'" id="update_margin_p[]' + no_update +'" onkeyup="update_cari_margin_rp(' + no_update + ');" value="0">';
      isi += '</td>';
      isi += '<td class="update_margin_p_temp" id="update_margin_p_temp' + no_update +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';
      
      isi += '<td class="update_margin_r">';
        isi += '<input type="text" class="form-control" name="update_margin_r[]' + no_update +'" id="update_margin_r[]' + no_update +'" onkeyup="update_cari_margin_persen(' + no_update + ');" value="0">';
      isi += '</td>';
      isi += '<td class="update_margin_r_temp" id="update_margin_r_temp' + no_update +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td class="update_harga_j">';
        isi += '<input type="text" class="form-control" name="update_harga_j[]' + no_update +'" id="update_harga_j[]' + no_update +'" readonly>';
      isi += '</td>';
      isi += '<td class="update_harga_j_temp" id="update_harga_j_temp' + no_update +'" contenteditable="true" align="right" hidden>';
        isi += 0;
      isi += '</td>';

      isi += '<td align="center">';
        isi += '<i style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus" value=""></i>';
      isi += '</td>';
    isi += '</tr>';

    $('.update_tabledata').append(isi);
    no_update++;
  })
  //=== End Tambah Satuan ke tabel ===//
  
  function tampilkan(x){
    $("input[name='harga_b[]" + x +"']").maskMoney({thousands:',', decimal:'.', precision:0});

    var id_unit = $('#kode_unit' + x + '').val();
    $('#kode_unit_temp' + x + '').text(id_unit); 

    var jml_qty = $("input[name='jml_qty[]" +x+ "']").val();
    $('#jml_qty_temp' +x+'').text(jml_qty);

    var harga_b = $("input[name='harga_b[]" +x+ "']").val();
    $('#harga_b_temp' +x+'').text(harga_b);
    $("input[name='harga_j[]" +x+ "']").val(harga_b);
    $('#harga_j_temp' +x+'').text(harga_b);
  }

  function update_tampilkan(no_update){
    $("input[name='update_harga_b[]" + no_update +"']").maskMoney({thousands:',', decimal:'.', precision:0});

    var id_unit = $('#update_kode_unit' + no_update + '').val();
    $('#update_kode_unit_temp' + no_update + '').text(id_unit); 

    var jml_qty = $("input[name='update_jml_qty[]" + no_update + "']").val();
    $('#update_jml_qty_temp' + no_update + '').text(jml_qty);

    var harga_b = $("input[name='update_harga_b[]" + no_update + "']").val();
    $('#update_harga_b_temp' + no_update +'').text(harga_b);
    $("input[name='update_harga_j[]" + no_update + "']").val(harga_b);
    $('#update_harga_j_temp' + no_update +'').text(harga_b);
  }

  function cari_margin_rp(x){
    $("input[name='margin_p[]" + x +"']").maskMoney({thousands:',', decimal:'.', precision:0});
    var margin_p = $("input[name='margin_p[]" +x+ "']").val();
    $('#margin_p_temp' + x + '').text(margin_p); 

    var harga_b = $("input[name='harga_b[]" +x+ "']").val();
    //menghilangka format rupiah//
    var bongkar_harga_b = harga_b.replace(/[.](?=.*?\.)/g, '');
        var hasil_bongkar_harga_b = parseInt(bongkar_harga_b.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var margin_rupiah = Math.round((margin_p / 100) * hasil_bongkar_harga_b);
  
    //membuat format rupiah//
    var reverse = margin_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_margin_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='margin_r[]" +x+ "']").val(hasil_margin_rupiah);
    $('#margin_r_temp' + x + '').text(hasil_margin_rupiah);

    var margin_harga_jual = hasil_bongkar_harga_b + margin_rupiah;
    //membuat format rupiah//
    var reverse = margin_harga_jual.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_margin_harga_jual = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='harga_j[]" +x+ "']").val(hasil_margin_harga_jual);
    $('#harga_j_temp' +x+'').text(hasil_margin_harga_jual);
  }

  function update_cari_margin_rp(no_update){
    $("input[name='update_margin_p[]" + no_update +"']").maskMoney({thousands:',', decimal:'.', precision:0});
    var margin_p = $("input[name='update_margin_p[]" + no_update + "']").val();
    $('#update_margin_p_temp' + no_update + '').text(margin_p); 

    var harga_b = $("input[name='update_harga_b[]" + no_update + "']").val();
    //menghilangka format rupiah//
    var bongkar_harga_b = harga_b.replace(/[.](?=.*?\.)/g, '');
        var hasil_bongkar_harga_b = parseInt(bongkar_harga_b.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var margin_rupiah = Math.round((margin_p / 100) * hasil_bongkar_harga_b);
  
    //membuat format rupiah//
    var reverse = margin_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_margin_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='update_margin_r[]" + no_update + "']").val(hasil_margin_rupiah);
    $('#update_margin_r_temp' + no_update + '').text(hasil_margin_rupiah);

    var margin_harga_jual = hasil_bongkar_harga_b + margin_rupiah;
    //membuat format rupiah//
    var reverse = margin_harga_jual.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_margin_harga_jual = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='update_harga_j[]" + no_update + "']").val(hasil_margin_harga_jual);
    $('#update_harga_j_temp' + no_update +'').text(hasil_margin_harga_jual);
  }

  function cari_margin_persen(x){
    $("input[name='margin_r[]" + x +"']").maskMoney({thousands:',', decimal:'.', precision:0});

    var margin_r = $("input[name='margin_r[]" +x+ "']").val();
    $('#margin_r_temp' + x + '').text(margin_r); 
    //menghilangka format rupiah//
      var bongkar_margin_r = margin_r.replace(/[.](?=.*?\.)/g, '');
      var hasil_bongkar_margin_r = parseInt(bongkar_margin_r.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var harga_b = $("input[name='harga_b[]" +x+ "']").val();
    //menghilangka format rupiah//
      var bongkar_harga_b = harga_b.replace(/[.](?=.*?\.)/g, '');
      var hasil_bongkar_harga_b = parseInt(bongkar_harga_b.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var margin_persen = Math.round((hasil_bongkar_margin_r / hasil_bongkar_harga_b) * 100);
    $("input[name='margin_p[]" +x+ "']").val(margin_persen);
    $('#margin_p_temp' + x + '').text(margin_persen); 

    var margin_harga_jual = hasil_bongkar_harga_b + hasil_bongkar_margin_r;
    //membuat format rupiah//
    var reverse = margin_harga_jual.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_margin_harga_jual = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='harga_j[]" +x+ "']").val(hasil_margin_harga_jual);
    $('#harga_j_temp' +x+'').text(hasil_margin_harga_jual);
  }

  function update_cari_margin_persen(no_update){
    $("input[name='update_margin_r[]" + no_update +"']").maskMoney({thousands:',', decimal:'.', precision:0});

    var margin_r = $("input[name='update_margin_r[]" + no_update + "']").val();
    $('#update_margin_r_temp' + no_update + '').text(margin_r); 
    //menghilangka format rupiah//
      var bongkar_margin_r = margin_r.replace(/[.](?=.*?\.)/g, '');
      var hasil_bongkar_margin_r = parseInt(bongkar_margin_r.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var harga_b = $("input[name='update_harga_b[]" + no_update + "']").val();
    //menghilangka format rupiah//
      var bongkar_harga_b = harga_b.replace(/[.](?=.*?\.)/g, '');
      var hasil_bongkar_harga_b = parseInt(bongkar_harga_b.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var margin_persen = Math.round((hasil_bongkar_margin_r / hasil_bongkar_harga_b) * 100);
    $("input[name='update_margin_p[]" + no_update + "']").val(margin_persen);
    $('#update_margin_p_temp' + no_update + '').text(margin_persen); 

    var margin_harga_jual = hasil_bongkar_harga_b + hasil_bongkar_margin_r;
    //membuat format rupiah//
    var reverse = margin_harga_jual.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_margin_harga_jual = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='update_harga_j[]" + no_update + "']").val(hasil_margin_harga_jual);
    $('#update_harga_j_temp' + no_update +'').text(hasil_margin_harga_jual);
  }

  //==== Hapus Per transaksi barang pada list tabel =================== 
  $('body').on('click','.hapus', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
  })
  //==== End Hapus Per transaksi barang pada list tabel ===============

  //=== Insert data Produk =================//
  $("#button_form_insert_produk").click(function(e) {
    e.preventDefault();

    if ($("#apotek").val() == ""){
      alert("Pilih Nama Apotek. Nama Apotek tidak boleh kosong");
      $("#apotek").focus();
      return (false);
    }

    if ($("#nama_obat").val() == ""){
      alert("Nama Produk harus diisi");
      $("#nama_obat").focus();
      return (false);
    }

    if ($("#stok").val() == ""){
      alert("Jml Stok Terkecil harus diisi");
      $("#stok").focus();
      return (false);
    }

    if ($("#stok_min").val() == ""){
      alert("Jml Stok Min harus diisi");
      $("#stok_min").focus();
      return (false);
    }

    if ($("#unit_terkecil").val() == ""){
      alert("Pilih Satuan Terkecil, Satuan Terkecil tidak boleh kosong");
      $("#unit_terkecil").focus();
      return (false);
    }
    if ($("#jenis").val() == ""){
      alert("Pilih Jenis, Jenis tidak boleh kosong");
      $("#jenis").focus();
      return (false);
    }

    if ($("#tipe").val() == ""){
      alert("Pilih Tipe, Tipe tidak boleh kosong");
      $("#tipe").focus();
      return (false);
    }

    let kode_cabang = $("#apotek").val();
    let barcode = $("#barcode").val();
    let no_batch = $("#no_batch").val();
    let nama_produk = $("#nama_obat").val();
    let komposisi = $("#komposisi").val();
    let vendor = $("#vendor").val();
    let id_jenis = $("#jenis").val();
    let tipe = $("#tipe").val();
    let tgl_kadaluarsa = $("#tgl_k").val();
    let qty = $("#stok").val();
    let qty_min = $("#stok_min").val();
    let unit_terkecil = $("#unit_terkecil").val();
   
    // untuk Detail //
    let kode_unit = []
    let stok_unit = []
    let harga_beli_unit = []
    let margin_persen_unit = []
    let margin_rp_unit = []
    let harga_jual_unit = []
    
    $('.kode_unit_temp').each(function() {
      kode_unit.push($(this).text())
    })
    $('.jml_qty_temp').each(function() {
      stok_unit.push($(this).text())
    })
    $('.harga_b_temp').each(function() {
      harga_beli_unit.push($(this).text())
    })
    $('.margin_p_temp').each(function() {
      margin_persen_unit.push($(this).text())
    })
    $('.margin_r_temp').each(function() {
      margin_rp_unit.push($(this).text())
    })
    $('.harga_j_temp').each(function() {
      harga_jual_unit.push($(this).text())
    })
    // End untuk Detail //
   
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('produk/store.store') }}",
      data: {
        kode_cabang: kode_cabang,
        barcode: barcode,
        no_batch: no_batch,
        nama_produk: nama_produk,
        komposisi: komposisi,
        vendor: vendor,
        id_jenis: id_jenis,
        tipe: tipe,
        tgl_kadaluarsa: tgl_kadaluarsa,
        qty: qty,
        qty_min: qty_min,
        unit_terkecil: unit_terkecil,
        // detail //
        kode_unit: kode_unit,
        stok_unit: stok_unit,
        harga_beli_unit: harga_beli_unit,
        margin_persen_unit: margin_persen_unit,
        margin_rp_unit: margin_rp_unit,
        harga_jual_unit: harga_jual_unit,
        // end Detail //
      },
      success: function(response) {
        if(response.res === true) {
          $("#apotek").val('');
          $("#barcode").val('');
          $("#no_batch").val('');
          $("#nama_obat").val('');
          $("#komposisi").val('');
          $("#vendor").val('');
          $("#unit_terkecil").val('');
          $("#jenis").val('');
          $("#tipe").val('');
          $("#tgl_k").val('');
          $("#stok").val('');
          $("#stok_min").val('');

          //$("#tabledata_satuan").closest('tr').remove();
          $('.hapus').closest('tr').remove();
          $("#modalTambahProduk").modal('hide');
          fetchAllProduk();
        }else{
          Swal.fire("Gagal!", "Data unit gagal disimpan.", "error");
        }
      }
    });
  });
  //=== End Insert data Produk =================//

  $("#button_form_update_produk").click(function() {
    let kode_produk = $("#update_kode_produk").val();
    let kode_cabang = $("#update_apotek").val();
    let barcode = $("#update_barcode").val();
    let no_batch = $("#update_no_batch").val();
    let nama_produk = $("#update_nama_obat").val();
    let komposisi = $("#update_komposisi").val();
    let id_jenis = $("#update_jenis").val();
    let id_unit = $("#update_unit_terkecil").val();
    let tipe = $("#update_tipe").val();
    let tgl_kadaluarsa = $("#update_tgl_k").val();
    let qty = $("#update_stok").val();
    let qty_min = $("#update_stok_min").val();
    // let harga_beli = $("#update_harga_b").val();
    // let margin_rp = $("#update_margin_r").val();
    // let margin_persen = $("#update_margin_p").val();
    // let harga_jual = $("#update_harga_j").val();

    // untuk Detail //
    let kode_unit = []
    let stok_unit = []
    let harga_beli_unit = []
    let margin_persen_unit = []
    let margin_rp_unit = []
    let harga_jual_unit = []

    $('.update_kode_unit_temp').each(function() {
      kode_unit.push($(this).text())
    })
    $('.update_jml_qty_temp').each(function() {
      stok_unit.push($(this).text())
    })
    $('.update_harga_b_temp').each(function() {
      harga_beli_unit.push($(this).text())
    })
    $('.update_margin_p_temp').each(function() {
      margin_persen_unit.push($(this).text())
    })
    $('.update_margin_r_temp').each(function() {
      margin_rp_unit.push($(this).text())
    })
    $('.update_harga_j_temp').each(function() {
      harga_jual_unit.push($(this).text())
    })
    // End untuk Detail //
    
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "POST",
      url: "{{ route('produk/update.update') }}",
      data: {
        kode_produk: kode_produk,
        kode_cabang: kode_cabang,
        barcode: barcode,
        no_batch: no_batch,
        nama_produk: nama_produk,
        komposisi: komposisi,
        id_jenis: id_jenis,
        id_unit: id_unit,
        tipe: tipe,
        tgl_kadaluarsa: tgl_kadaluarsa,
        qty: qty,
        qty_min: qty_min,

        // detail //
        kode_unit: kode_unit,
        stok_unit: stok_unit,
        harga_beli_unit: harga_beli_unit,
        margin_persen_unit: margin_persen_unit,
        margin_rp_unit: margin_rp_unit,
        harga_jual_unit: harga_jual_unit,
        // end Detail //
        
      },
      success: function(response) {
        if (response.status === true) {
          $('#modalEditProduk').modal('hide');
          alert('Sukses, Data Berhasil diubah...');
          fetchAllProduk()
        }else{
          alert('Gagal, Data tidak berhasil diubah...');
        }
      }
    });
  });
  //=== End Edit Data Produk =================================================//

  $("#button_modal_update_tutup").click(function() {
    $('.hapus').closest('tr').remove();
  });

  $("#button_modal_update_tutup_bawah").click(function() { 
    $('.hapus').closest('tr').remove();
  });

</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Produk</title>
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
        Data Produk
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalTambahProduk"><i class="bi bi-plus"></i> Tambah Data</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Produk</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row" id="data_produk">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <form action="{{ route('produk/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-4">
                        <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                        <input type="text"  class="form-control" name="cari_produk" id="cari_produk" placeholder="Cari Produk..."/>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%; font-size: 14px;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode Produk</th>
                              <th>Nama Produk</th>
                              <th>Komposisi</th>
                              <th hidden>Id Jenis</th>
                              <th>Jenis</th>
                              <th>pembelian</th>
                              <th>Tipe</th>
                              <th>Stok</th>
                              <th hidden>Id Unit</th>
                              <th>Satuan Terkecil</th>
                              <th>Harga Beli</th>
                              <th>Margin (%)</th>
                              <th>Margin (Rp)</th>
                              <th>Harga Jual</th>
                              <th hidden>Stok Min</th>
                              <th>Tgl kadaluarsa</th>
                              <th>Distributor</th>
                              <th hidden>Id User Input</th>
                              <th hidden>User Input</th>  
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

        <!-- Modal Tambah Data -->
        <div class="modal fade" id="modalTambahProduk" tabindex="-1">
          <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Tambah Produk</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="card mb-1">
                  <div class="card-body">
                    <br>
                    <div class="row mb-3">
                      <div class="col-12">
                        <label for="inputApotek" class="form-label">Nama Apotek</label>
                        <select name="apotek" id="apotek" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_cabang as $row)
                            <option value="{{ $row->kode_cabang }}" {{ old('kode_cabang') == $row->kode_cabang ? 'selected':'' }}>{{ $row->nama_cabang }}</option>
                          @endforeach 
                        </select>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-6">
                        <label for="inputNama" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_obat" id="nama_obat" required>
                      </div>
                      <div class="col-2">
                        <label for="inputBarcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control" name="barcode" id="barcode" required>
                      </div>
                      <div class="col-2">
                        <label for="inputNoBatch" class="form-label">No Batch</label>
                        <input type="text" class="form-control" name="no_batch" id="no_batch" required>
                      </div>
                      <div class="col-2">
                        <label for="inputTglKadaluarsa" class="form-label">Tgl Kadaluarsa</label>
                        <input type="date" class="form-control" name="tgl_k" id="tgl_k" required>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-8">
                        <label for="inputNama" class="form-label">Komposisi</label>
                        <input type="text" class="form-control" name="komposisi" id="komposisi">
                      </div>
                      <div class="col-4">
                        <label for="inputUnit" class="form-label">Vendor</label>
                        <select name="vendor" id="vendor" class="form-select" required>
                          <option value="">Pilih Vendor...</option>
                          @foreach ($data_vendor as $row)
                            <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_supplier }}</option>
                          @endforeach 
                        </select>
                      </div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-2">
                        <label for="inputJml" class="form-label">Stok Terkecil</label>
                        <input type="number" class="form-control" name="stok" id="stok" style="text-align: center;" required>
                      </div>
                      <div class="col-1">
                        <label for="inputJml" class="form-label">Stok Min</label>
                        <input type="number" class="form-control" name="stok_min" id="stok_min" style="text-align: center;" required>
                      </div>
                      <div class="col-2">
                        <label for="inputUnit" class="form-label">Satuan Terkecil</label>
                        <select name="unit_terkecil" id="unit_terkecil" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_unit as $row)
                            <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_unit }}</option>
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-4">
                        <label for="inputJenis" class="form-label">Jenis</label>
                        <select name="jenis" id="jenis" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_jenis as $row)
                            <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_jenis }}</option>
                           @endforeach 
                        </select>
                      </div>
                      <div class="col-3">
                        <label for="inputTipe" class="form-label">Tipe</label>
                        <select name="tipe" id="tipe" class="form-select" required>
                          <option value="">Pilih...</option>
                          <option value="Apotek">Apotek</option>
                          <option value="Konsinyasi">Konsinyasi</option>
                        </select>
                      </div>
                    </div>
                    <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
                    <div class="row mb-3">
                      <div class="col-sm-2">
                        <button type="button" class="btn btn-primary" id="button_tambah_satuan"><i class="bi bi-save"></i> Tambah Satuan</button>
                        <input type="hidden" class="form-control" name="txt_btn" id="txt_btn" required>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="table-responsive">
                        <table id="tabledata_satuan" class="table table-striped table-bordered" style="width: 100%;">
                          <thead>
                            <tr>
                              <th hidden>No</th>
                              <th>Satuan</th>
                              <th>Jml</th>
                              <th>Harga Beli</th>
                              <th>Margin (%)</th>
                              <th>Margin (Rp)</th>
                              <th>Harga Jual</th>  
                              <th></th>
                            </tr>
                          </thead>
                          <tbody id="tabledata_satuan" class="tabledata_satuan">
                                
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" id="button_form_insert_produk"><i class="bi bi-save"></i> Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Modal Tambah Data -->

        <!-- Modal Update Data -->
        <div class="modal fade" id="modalEditProduk" tabindex="-1">
          <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Edit Produk</h3>
                  <button type="button" class="btn-close" id="button_modal_update_tutup" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="card mb-1">
                    <div class="card-body">
                      <br>
                      <div class="row mb-3">
                        <div class="col-12">
                          <label for="inputApotek" class="form-label">Nama Apotek</label>
                          <select name="update_apotek" id="update_apotek" class="form-control" required>
                            <option value="">Pilih...</option>
                            @foreach ($data_cabang as $row)
                              <option value="{{ $row->kode_cabang }}" {{ old('kode_cabang') == $row->kode_cabang ? 'selected':'' }}>{{ $row->nama_cabang }}</option>
                            @endforeach 
                          </select>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-6">
                          <label for="inputNama" class="form-label">Nama Produk</label>
                          <input type="text" class="form-control" name="update_nama_obat" id="update_nama_obat" required>
                          <input type="hidden" class="form-control" name="update_kode_produk" id="update_kode_produk" required>
                        </div>
                        <div class="col-2">
                          <label for="inputBarcode" class="form-label">Barcode</label>
                          <input type="text" class="form-control" name="update_barcode" id="update_barcode" required>
                        </div>
                        <div class="col-2">
                          <label for="inputNoBatch" class="form-label">No Batch</label>
                          <input type="text" class="form-control" name="update_no_batch" id="update_no_batch" required>
                        </div>
                        <div class="col-2">
                          <label for="inputTglKadaluarsa" class="form-label">Tgl Kadaluarsa</label>
                          <input type="date" class="form-control" name="update_tgl_k" id="update_tgl_k" required>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-12">
                          <label for="inputNama" class="form-label">Komposisi</label>
                          <input type="text" class="form-control" name="update_komposisi" id="update_komposisi">
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-2">
                          <label for="inputJml" class="form-label">Stok Terkecil</label>
                          <input type="number" class="form-control" name="update_stok" id="update_stok" style="text-align: center;" required>
                        </div>
                        <div class="col-1">
                          <label for="inputJml" class="form-label">Stok Min</label>
                          <input type="number" class="form-control" name="update_stok_min" id="update_stok_min" style="text-align: center;" required>
                        </div>
                        <div class="col-2">
                          <label for="inputUnit" class="form-label">Satuan Terkecil</label>
                          <select name="update_unit_terkecil" id="update_unit_terkecil" class="form-control" required>
                            <option value="">Pilih...</option>
                            @foreach ($data_unit as $row)
                              <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_unit }}</option>
                            @endforeach 
                          </select>
                        </div>
                        <div class="col-4">
                          <label for="inputJenis" class="form-label">Jenis</label>
                          <select name="update_jenis" id="update_jenis" class="form-control" required>
                            <option value="">Pilih...</option>
                            @foreach ($data_jenis as $row)
                              <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_jenis }}</option>
                            @endforeach 
                          </select>
                        </div>
                        <div class="col-3">
                          <label for="inputTipe" class="form-label">Tipe</label>
                          <select name="update_tipe" id="update_tipe" class="form-control" required>
                            <option value="">Pilih...</option>
                            <option value="Apotek">Apotek</option>
                            <option value="Konsinyasi">Konsinyasi</option>
                          </select>
                        </div>
                      </div>
                      
                      <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
                      <div class="row mb-3">
                        
                        <div class="col-sm-2">
                          <button type="button" class="btn btn-primary" id="update_button_tambah_satuan"><i class="bi bi-save"></i> Tambah Satuan</button>
                          <input type="hidden" class="form-control" name="txt_btn" id="txt_btn" required>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="table-responsive">
                          <table id="update_tabledata_satuan" class="table table-striped table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th hidden>No</th>
                                    <th>Satuan</th>
                                    <th>Jml</th>
                                    <th>Harga Beli</th>
                                    <th>Margin (%)</th>
                                    <th>Margin (Rp)</th>
                                    <th>Harga Jual</th>  
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="update_tabledata" class="update_tabledata">
                                
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_update_produk"><i class="bi bi-save"></i> Simpan</button>
                  <button type="button" class="btn btn-danger" id="button_modal_update_tutup_bawah" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
            </div>
          </div>
        </div>
        <!-- End Modal Update Data -->

      </div>
    </section>

</main>
@endsection



@section('js')
 
    
@endsection()