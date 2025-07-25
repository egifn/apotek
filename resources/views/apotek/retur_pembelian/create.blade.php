@section('js')

<script type="text/javascript">
    let subtotal = 0;
    $("input[name='f_jml_bayar']").maskMoney({thousands:',', decimal:'.', precision:0});

    $(document).on('click', '.pilih', function (e) {
        $("#no_batch").val($(this).attr('data-batch'))
        $("#kode_transaksi").val($(this).attr('data-kode_pembelian'))
        $("#kode_transaksi_sp").val($(this).attr('data-faktur'))
        $("#tgl_sp").val($(this).attr('data-tgl_pembelian'))
        $("#jenis_sp").val($(this).attr('data-jenis-sp'))
        $("#nama_supplier").val($(this).attr('data-supplier'))
        $("#pembelian").val($(this).attr('data-pembelian'))
        $("#jenis_transaksi").val($(this).attr('data-jenis-transaksi'))
        $('#modalCariTransaksi').modal('hide');
        $("#kode_transaksi").focus();
    });

    $(document).on('click', '.pilih_batch', function (e) {
        $("#no_batch").val($(this).attr('data-batch'))
        $("#kode_transaksi").val($(this).attr('data-kode_pembelian'))
        $("#kode_transaksi_sp").val($(this).attr('data-faktur'))
        $("#tgl_sp").val($(this).attr('data-tgl_pembelian'))
        $("#jenis_sp").val($(this).attr('data-jenis-sp'))
        $("#nama_supplier").val($(this).attr('data-supplier'))
        $("#pembelian").val($(this).attr('data-pembelian'))
        $("#jenis_transaksi").val($(this).attr('data-jenis-transaksi'))
        $('#modalCariTransaksiBatch').modal('hide');
        $("#kode_transaksi").focus();
    });

    //===Menampilkan Modal data Transaksi====//
    fetch_data_transaksi_pembelian();
    function fetch_data_transaksi_pembelian() {
        $.ajax({
            type: "GET",
            url: "{{ route('transaksi_retur_pembelian/getPembelianModal.getPembelianModal') }}",
            dataType: "json",
            success: function(response) {
                let tabledataModalPembelian;
                response.data.forEach(pembelian => {
                    tabledataModalPembelian += `<tr class="pilih" data-batch="${pembelian.no_batch}" data-kode_pembelian="${pembelian.kode_pembelian}" data-faktur="${pembelian.no_faktur}" data-tgl_pembelian="${pembelian.tgl_pembelian}" data-jenis-sp="${pembelian.jenis_surat_pesanan}" data-pembelian="${pembelian.pembelian}" data-supplier="${pembelian.nama_supplier}" data-jenis-transaksi="${pembelian.jenis_transaksi}">`;
                    tabledataModalPembelian += `<td>${pembelian.no_batch}</td>`
                    tabledataModalPembelian += `<td>${pembelian.no_faktur}</td>`;
                    tabledataModalPembelian += `<td>${pembelian.kode_pembelian}</td>`;
                    tabledataModalPembelian += `<td>${pembelian.tgl_pembelian}</td>`;
                    tabledataModalPembelian += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                    tabledataModalPembelian += `<td>${pembelian.pembelian}</td>`;
                    tabledataModalPembelian += `<td>${pembelian.nama_supplier}</td>`;
                    tabledataModalPembelian += `<td hidden>${pembelian.jenis_transaksi}</td>`;
                    tabledataModalPembelian += `</tr>`;
                });
                $("#tabledataModalPembelian").html(tabledataModalPembelian);
            }
        });
    }
    //===End Menampilkan Modal data Transaksi====//

    //===Pencarian Modal data Transaksi====//
    $("#search").keyup(function() {
        let value = $("#search").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('transaksi_retur_pembelian/getPembelianModal.getPembelianModal') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModalPembelian;
                    response.data.forEach(pembelian => {
                        tabledataModalPembelian += `<tr class="pilih" data-batch="${pembelian.no_batch}" data-kode_pembelian="${pembelian.kode_pembelian}" data-faktur="${pembelian.no_faktur}" data-tgl_pembelian="${pembelian.tgl_pembelian}" data-jenis-sp="${pembelian.jenis_surat_pesanan}" data-pembelian="${pembelian.pembelian}" data-supplier="${pembelian.nama_supplier}" data-jenis-transaksi="${pembelian.jenis_transaksi}">`;
                        tabledataModalPembelian += `<td>${pembelian.no_batch}</td>`
                        tabledataModalPembelian += `<td>${pembelian.no_faktur}</td>`;
                        tabledataModalPembelian += `<td>${pembelian.kode_pembelian}</td>`;
                        tabledataModalPembelian += `<td>${pembelian.tgl_pembelian}</td>`;
                        tabledataModalPembelian += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                        tabledataModalPembelian += `<td>${pembelian.pembelian}</td>`;
                        tabledataModalPembelian += `<td>${pembelian.nama_supplier}</td>`;
                        tabledataModalPembelian += `<td hidden>${pembelian.jenis_transaksi}</td>`;
                        tabledataModalPembelian += `</tr>`;
                    });
                    $("#tabledataModalPembelian").html(tabledataModalPembelian);
                }
            });
        }else{
            fetch_data_transaksi_pembelian();
        }
    });

    fetch_data_transaksi_pembelian_batch();
    function fetch_data_transaksi_pembelian_batch() {
        $.ajax({
            type: "GET",
            url: "{{ route('transaksi_retur_pembelian/getPembelianModal.getPembelianModalBatch') }}",
            dataType: "json",
            success: function(response) {
                let tabledataModalPembelianBatch;
                response.data.forEach(pembelian => {
                    tabledataModalPembelianBatch += `<tr class="pilih_batch" data-batch="${pembelian.no_batch}" data-kode_pembelian="${pembelian.kode_pembelian}" data-faktur="${pembelian.no_faktur}" data-tgl_pembelian="${pembelian.tgl_pembelian}" data-jenis-sp="${pembelian.jenis_surat_pesanan}" data-pembelian="${pembelian.pembelian}" data-supplier="${pembelian.nama_supplier}" data-jenis-transaksi="${pembelian.jenis_transaksi}">`;
                    tabledataModalPembelianBatch += `<td>${pembelian.no_batch}</td>`
                    tabledataModalPembelianBatch += `<td>${pembelian.no_faktur}</td>`;
                    tabledataModalPembelianBatch += `<td>${pembelian.kode_pembelian}</td>`;
                    tabledataModalPembelianBatch += `<td>${pembelian.tgl_pembelian}</td>`;
                    tabledataModalPembelianBatch += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                    tabledataModalPembelianBatch += `<td>${pembelian.pembelian}</td>`;
                    tabledataModalPembelianBatch += `<td>${pembelian.nama_supplier}</td>`;
                    tabledataModalPembelianBatch += `<td hidden>${pembelian.jenis_transaksi}</td>`;
                    tabledataModalPembelianBatch += `</tr>`;
                });
                $("#tabledataModalPembelianBatch").html(tabledataModalPembelianBatch);
            }
        });
    }

    $("#searchBatch").keyup(function() {
        let value = $("#searchBatch").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('transaksi_retur_pembelian/getPembelianModal.getPembelianModalBatch') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModalPembelianBatch;
                    response.data.forEach(pembelian => {
                        tabledataModalPembelianBatch += `<tr class="pilih_batch" data-batch="${pembelian.no_batch}" data-kode_pembelian="${pembelian.kode_pembelian}" data-faktur="${pembelian.no_faktur}" data-tgl_pembelian="${pembelian.tgl_pembelian}" data-jenis-sp="${pembelian.jenis_surat_pesanan}" data-pembelian="${pembelian.pembelian}" data-supplier="${pembelian.nama_supplier}" data-jenis-transaksi="${pembelian.jenis_transaksi}">`;
                        tabledataModalPembelianBatch += `<td>${pembelian.no_batch}</td>`;
                        tabledataModalPembelianBatch += `<td>${pembelian.no_faktur}</td>`;
                        tabledataModalPembelianBatch += `<td>${pembelian.kode_pembelian}</td>`;
                        tabledataModalPembelianBatch += `<td>${pembelian.tgl_pembelian}</td>`;
                        tabledataModalPembelianBatch += `<td>${pembelian.jenis_surat_pesanan}</td>`;
                        tabledataModalPembelianBatch += `<td>${pembelian.pembelian}</td>`;
                        tabledataModalPembelianBatch += `<td>${pembelian.nama_supplier}</td>`;
                        tabledataModalPembelianBatch += `<td hidden>${pembelian.jenis_transaksi}</td>`;
                        tabledataModalPembelianBatch += `</tr>`;
                    });
                    $("#tabledataModalPembelianBatch").html(tabledataModalPembelianBatch);
                }
            });
        }else{
            fetch_data_transaksi_pembelian_batch();
        }
    });
    //===End Pencarian Modal data Transaksi====//

    //===Select data unit/kategori====//
    $("#kode_transaksi_sp").keyup(function() {
        let value = $("#kode_transaksi_sp").val();
        $.ajax({
        type: "GET",
        url: "{{ route('transaksi_retur_pembelian/getPembeliandetail.getPembeliandetail') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
            let tabledata;
            let no = 0;
            let subtotal = 0;
            response.data.forEach(el => {
                let harga = el.harga;
                //membuat format rupiah Harga//
                var reverse_harga = harga.toString().split('').reverse().join(''),
                ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
                //End membuat format rupiah//

                no = no + 1
                tabledata += '<tr>';
                tabledata += '<td>' +no+ '</td>';
                tabledata += '<td class="kode_produk" id="kode_produk' + no + '">' +el.kode_produk+ '</td>';
                tabledata += '<td>' +el.nama_produk+ '</td>';
                tabledata += '<td class="harga_satuan" id="harga_satuan' + no + '" align="right">' +harga_rupiah+ '</td>';
                tabledata += '<td class="jml_beli" id="jml_beli' + no + '" align="right">' +el.qty_beli+ '</td>';
                tabledata += '<td align="right"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml_ret[]' + no +'" id="jml_ret[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="0"></td>';
                tabledata += '<td class="jml_ret" id="jml_ret' + no + '" align="right" contenteditable="true" hidden>0</td>';
                tabledata += '<td>' +el.nama_unit+ '</td>';
                tabledata += '<td class="id_produk_unit" id="id_produk_unit' + no + '" hidden>' +el.id_unit+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_persen[]' + no +'" id="diskon_persen[]' + no +'" onclick="diskon_persen(' + no + ');" onkeyup="diskon_persen(' + no + ');" value="' +el.diskon_item+ '"></td>';
                tabledata += '<td class="diskon_persen" id="diskon_persen' + no + '" align="right" contenteditable="true" hidden>' +el.diskon_item+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_rp[]' + no +'" id="diskon_rp[]' + no +'" onclick="diskon_rupiah(' + no + ');" onkeyup="diskon_rupiah(' + no + ');" value="' +el.diskon_item_rp+ '"></td>';
                tabledata += '<td class="diskon_rp" id="diskon_rp' + no + '" align="right" contenteditable="true" hidden>' +el.diskon_item_rp+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn[]' + no +'" id="ppn[]' + no +'" onclick="ppn_persen(' + no + ');" onkeyup="ppn_persen(' + no + ');" value="' +el.ppn_item+ '"></td>';
                tabledata += '<td class="ppn" id="ppn' + no + '" align="right" contenteditable="true" hidden>' +el.ppn_item+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn_rp[]' + no +'" id="ppn_rp[]' + no +'" onclick="ppn_rupiah(' + no + ');" onkeyup="ppn_rupiah(' + no + ');" value="' +el.ppn_item_rp+ '"></td>';
                tabledata += '<td class="ppn_rp" id="ppn_rp' + no + '" align="right" contenteditable="true" hidden>' +el.ppn_item_rp+ '</td>';
                tabledata += '<td class="subtotal" id="subtotal' + no + '" align="right">0</td>';
                tabledata += `</tr>`;
                let temp_total = $('#subtotal' + no +'').text();
                subtotal = subtotal + parseInt(temp_total);
            });
            $("#tabledata").html(tabledata);
            $(".f_subtotal").text(subtotal);
        }
        });
    });

    $("#no_batch").keyup(function() {
        let value = $("#kode_transaksi_sp").val();
        $.ajax({
        type: "GET",
        url: "{{ route('transaksi_retur_pembelian/getPembeliandetail.getPembeliandetail') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
            let tabledata;
            let no = 0;
            let subtotal = 0;
            response.data.forEach(el => {
                let harga = el.harga;
                //membuat format rupiah Harga//
                var reverse_harga = harga.toString().split('').reverse().join(''),
                ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
                //End membuat format rupiah//

                no = no + 1
                tabledata += '<tr>';
                tabledata += '<td>' +no+ '</td>';
                tabledata += '<td class="kode_produk" id="kode_produk' + no + '">' +el.kode_produk+ '</td>';
                tabledata += '<td>' +el.nama_produk+ '</td>';
                tabledata += '<td class="harga_satuan" id="harga_satuan' + no + '" align="right">' +harga_rupiah+ '</td>';
                tabledata += '<td class="jml_beli" id="jml_beli' + no + '" align="right">' +el.qty_beli+ '</td>';
                tabledata += '<td align="right"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml_ret[]' + no +'" id="jml_ret[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="0"></td>';
                tabledata += '<td class="jml_ret" id="jml_ret' + no + '" align="right" contenteditable="true" hidden>0</td>';
                tabledata += '<td>' +el.nama_unit+ '</td>';
                tabledata += '<td class="id_produk_unit" id="id_produk_unit' + no + '" hidden>' +el.id_unit+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_persen[]' + no +'" id="diskon_persen[]' + no +'"  onkeyup="diskon_persen(' + no + ');" value="' +el.diskon_item+ '"></td>';
                tabledata += '<td class="diskon_persen" id="diskon_persen' + no + '" align="right" contenteditable="true" hidden>' +el.diskon_item+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_rp[]' + no +'" id="diskon_rp[]' + no +'" value="' +el.diskon_item_rp+ '"></td>';
                tabledata += '<td class="diskon_rp" id="diskon_rp' + no + '" align="right" contenteditable="true" hidden>' +el.diskon_item_rp+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn[]' + no +'" id="ppn[]' + no +'" onkeyup="ppn_persen(' + no + ');" value="' +el.ppn_item+ '"></td>';
                tabledata += '<td class="ppn" id="ppn' + no + '" align="right" contenteditable="true" hidden>' +el.ppn_item+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn_rp[]' + no +'" id="ppn_rp[]' + no +'"  value="' +el.ppn_item_rp+ '"></td>';
                tabledata += '<td class="ppn_rp" id="ppn_rp' + no + '" align="right" contenteditable="true" hidden>' +el.ppn_item_rp+ '</td>';
                tabledata += '<td class="subtotal" id="subtotal' + no + '" align="right">0</td>';
                tabledata += `</tr>`;
                let temp_total = $('#subtotal' + no +'').text();
                subtotal = subtotal + parseInt(temp_total);
            });
            $("#tabledata").html(tabledata);
            $(".f_subtotal").text(subtotal);
        }
        });
    });
  //=== End Select data unit/kategori ====//

  var total_ret_1 = 0;
  var total_ret_2 = 0;
  var total_ret_3 = 0;
  var total_ret_4 = 0;
  var total_ret_5 = 0;
  var total_ret_6 = 0;
  var total_ret_7 = 0;
  var total_ret_8 = 0;
  var total_ret_9 = 0;
  var total_ret_10 = 0;
  function jumlah(no) {
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();
    $('#jml_ret' + no +'').text(jml_ret);

    var harga_satuan = $('#harga_satuan' +no+ '').text();
    //menghilangka format rupiah//
        var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var total_ret = jml_ret * hasil_temp_harga_satuan;

    var diskon_persen = $("input[name='diskon_persen[]" +no+ "']").val(); 
    var diskon_rupiah = Math.round((diskon_persen / 100) * total_ret);
    //membuat format rupiah//
    var reverse = diskon_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_diskon_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    var ppn_persen = $("input[name='ppn[]" +no+ "']").val(); 
    var ppn_rupiah = Math.round((ppn_persen / 100) * total_ret);
    //membuat format rupiah//
    var reverse = ppn_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_ppn_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    var subtotal_setelah_diskon = (hasil_temp_harga_satuan * jml_ret) - diskon_rupiah + ppn_rupiah;
    var reverse = subtotal_setelah_diskon.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal_setelah_diskon = ribuan.join(',').split('').reverse().join('');

    $("input[name='diskon_rp[]" +no+ "']").val(hasil_diskon_rupiah);
    $("input[name='ppn_rp[]" +no+ "']").val(hasil_ppn_rupiah);
    $('#subtotal' + no +'').text(hasil_subtotal_setelah_diskon);
     
    if(no == 1){
        total_ret_1 = total_ret;
    }else if(no == 2){
        total_ret_2 = total_ret;
    }else if(no == 3){
        total_ret_3 = total_ret;
    }else if(no == 4){
        total_ret_4 = total_ret;
    }else if(no == 5){
        total_ret_5 = total_ret;
    }else if(no == 6){
        total_ret_6 = total_ret;
    }else if(no == 7){
        total_ret_7 = total_ret;
    }else if(no == 8){
        total_ret_8 = total_ret;
    }else if(no == 9){
        total_ret_9 = total_ret;
    }else if(no == 10){
        total_ret_10 = total_ret;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10 - diskon_rupiah + ppn_rupiah);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);
  }

  function diskon_persen(no) {
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();

    var diskon_persen = $("input[name='diskon_persen[]" +no+ "']").val(); 
    var harga_satuan = $('#harga_satuan' +no+ '').text();
    //menghilangka format rupiah//
        var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//
    
    var diskon_rupiah = Math.round((diskon_persen / 100) * (hasil_temp_harga_satuan * jml_ret));
    //membuat format rupiah//
    var reverse = diskon_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_diskon_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    var ppn_persen = $("input[name='ppn[]" +no+ "']").val(); 
    var ppn_rupiah = Math.round((ppn_persen / 100) * (hasil_temp_harga_satuan * jml_ret));

    var subtotal_setelah_diskon = (hasil_temp_harga_satuan * jml_ret) - diskon_rupiah + ppn_rupiah;
    var reverse = subtotal_setelah_diskon.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal_setelah_diskon = ribuan.join(',').split('').reverse().join('');

    $("input[name='diskon_rp[]" +no+ "']").val(hasil_diskon_rupiah);
    $('#subtotal' + no +'').text(hasil_subtotal_setelah_diskon);

    if(no == 1){
        total_ret_1 = subtotal_setelah_diskon;
    }else if(no == 2){
        total_ret_2 = subtotal_setelah_diskon;
    }else if(no == 3){
        total_ret_3 = subtotal_setelah_diskon;
    }else if(no == 4){
        total_ret_4 = subtotal_setelah_diskon;
    }else if(no == 5){
        total_ret_5 = subtotal_setelah_diskon;
    }else if(no == 6){
        total_ret_6 = subtotal_setelah_diskon;
    }else if(no == 7){
        total_ret_7 = subtotal_setelah_diskon;
    }else if(no == 8){
        total_ret_8 = subtotal_setelah_diskon;
    }else if(no == 9){
        total_ret_9 = subtotal_setelah_diskon;
    }else if(no == 10){
        total_ret_10 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10 - diskon_rupiah + ppn_rupiah);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);
  }

  function diskon_rupiah(no) {
    $("input[name='diskon_rp[]" +no+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();
   
    var diskon_rupiah = $("input[name='diskon_rp[]" +no+ "']").val(); 
    var harga_satuan = $('#harga_satuan' +no+ '').text();

    //menghilangka format rupiah//
    var temp_diskon_rupiah = diskon_rupiah.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_diskon_rupiah = parseInt(temp_diskon_rupiah.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    //menghilangka format rupiah//
    var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var diskon_persen = Math.round((hasil_temp_diskon_rupiah / hasil_temp_harga_satuan) * 100);

    var subtotal_setelah_diskon = (hasil_temp_harga_satuan * jml_ret) - hasil_temp_diskon_rupiah;
    var reverse = subtotal_setelah_diskon.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal_setelah_diskon = ribuan.join(',').split('').reverse().join('');

    $("input[name='diskon_persen[]" +no+ "']").val(diskon_persen);
    $('#subtotal' + no +'').text(hasil_subtotal_setelah_diskon)

    if(no == 1){
        total_ret_1 = subtotal_setelah_diskon;
    }else if(no == 2){
        total_ret_2 = subtotal_setelah_diskon;
    }else if(no == 3){
        total_ret_3 = subtotal_setelah_diskon;
    }else if(no == 4){
        total_ret_4 = subtotal_setelah_diskon;
    }else if(no == 5){
        total_ret_5 = subtotal_setelah_diskon;
    }else if(no == 6){
        total_ret_6 = subtotal_setelah_diskon;
    }else if(no == 7){
        total_ret_7 = subtotal_setelah_diskon;
    }else if(no == 8){
        total_ret_8 = subtotal_setelah_diskon;
    }else if(no == 9){
        total_ret_9 = subtotal_setelah_diskon;
    }else if(no == 10){
        total_ret_10 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);
  }

  function ppn_persen(no) {
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();
    var jml_diskon_rp = $("input[name='diskon_rp[]" +no+ "']").val();
    //menghilangka format rupiah//
    var temp_jml_diskon_rp = jml_diskon_rp.replace(/[.](?=.*?\.)/g, '');
        var hasil_jml_diskon_rp = parseInt(temp_jml_diskon_rp.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var ppn_persen = $("input[name='ppn[]" +no+ "']").val(); 
    var harga_satuan = $('#harga_satuan' +no+ '').text();
    //menghilangka format rupiah//
        var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//
    
    var ppn_rupiah = Math.round((ppn_persen / 100) * hasil_temp_harga_satuan*jml_ret);
    //membuat format rupiah//
    var reverse = ppn_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_ppn_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    var subtotal_setelah_diskon = (hasil_temp_harga_satuan * jml_ret) - hasil_jml_diskon_rp + ppn_rupiah;
    var reverse = subtotal_setelah_diskon.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal_setelah_diskon = ribuan.join(',').split('').reverse().join('');

    $("input[name='ppn_rp[]" +no+ "']").val(hasil_ppn_rupiah);
    $('#subtotal' + no +'').text(hasil_subtotal_setelah_diskon)

    if(no == 1){
        total_ret_1 = subtotal_setelah_diskon;
    }else if(no == 2){
        total_ret_2 = subtotal_setelah_diskon;
    }else if(no == 3){
        total_ret_3 = subtotal_setelah_diskon;
    }else if(no == 4){
        total_ret_4 = subtotal_setelah_diskon;
    }else if(no == 5){
        total_ret_5 = subtotal_setelah_diskon;
    }else if(no == 6){
        total_ret_6 = subtotal_setelah_diskon;
    }else if(no == 7){
        total_ret_7 = subtotal_setelah_diskon;
    }else if(no == 8){
        total_ret_8 = subtotal_setelah_diskon;
    }else if(no == 9){
        total_ret_9 = subtotal_setelah_diskon;
    }else if(no == 10){
        total_ret_10 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10 - hasil_jml_diskon_rp + ppn_rupiah);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);
  }

  function ppn_rupiah(no) {
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();
    var jml_diskon_rp = $("input[name='diskon_rp[]" +no+ "']").val();
    //menghilangka format rupiah//
    var temp_jml_diskon_rp = jml_diskon_rp.replace(/[.](?=.*?\.)/g, '');
        var hasil_jml_diskon_rp = parseInt(temp_jml_diskon_rp.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    $("input[name='ppn_rp[]" +no+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
    var ppn_rupiah = $("input[name='ppn_rp[]" +no+ "']").val(); 
    var harga_satuan = $('#harga_satuan' +no+ '').text();

    //menghilangka format rupiah//
    var temp_ppn_rupiah = ppn_rupiah.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_ppn_rupiah = parseInt(temp_ppn_rupiah.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    //menghilangka format rupiah//
    var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var ppn_persen = Math.round((hasil_temp_ppn_rupiah / (hasil_temp_harga_satuan*jml_ret)) * 100);

    var subtotal_setelah_diskon = (hasil_temp_harga_satuan * jml_ret) - hasil_jml_diskon_rp + hasil_temp_ppn_rupiah;
    var reverse = subtotal_setelah_diskon.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal_setelah_diskon = ribuan.join(',').split('').reverse().join('');

    $("input[name='ppn[]" +no+ "']").val(ppn_persen);
    $('#subtotal' + no +'').text(hasil_subtotal_setelah_diskon)

    if(no == 1){
        total_ret_1 = subtotal_setelah_diskon;
    }else if(no == 2){
        total_ret_2 = subtotal_setelah_diskon;
    }else if(no == 3){
        total_ret_3 = subtotal_setelah_diskon;
    }else if(no == 4){
        total_ret_4 = subtotal_setelah_diskon;
    }else if(no == 5){
        total_ret_5 = subtotal_setelah_diskon;
    }else if(no == 6){
        total_ret_6 = subtotal_setelah_diskon;
    }else if(no == 7){
        total_ret_7 = subtotal_setelah_diskon;
    }else if(no == 8){
        total_ret_8 = subtotal_setelah_diskon;
    }else if(no == 9){
        total_ret_9 = subtotal_setelah_diskon;
    }else if(no == 10){
        total_ret_10 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    $('#f_subtotal').val(hasil_subtotal);
  }

  $('#button_form_insert_transaksi').click(function(e) {
    if ($("#kode_transaksi").val() == ""){
        alert("No Surat Pesanan harus diisi.No Surat Pesanan tidak boleh kosong");
        $("#kode_transaksi").focus();
        return (false);
    }
   
    if ($("#f_jml_bayar").val() == 0){
        alert("Isi Jml Uang Kembali");
        $("#f_jml_bayar").focus();
        return (false);
    }

    e.preventDefault();
    let no_sp = $("#kode_transaksi").val();
    let no_transaksi_sp = $("#kode_transaksi_sp").val(); //no_faktur
    let tgl_sp = $("#tgl_sp").val();
    let jenis_sp = $("#jenis_sp").val();
    let pembelian = $("#jenis_transaksi").val();
    let subtotal_ret = $("#f_subtotal").val();
    let jml_bayar_ret = $("#f_jml_bayar").val();

    // untuk Detail //
    let kode_produk = []
    let harga_beli = []
    let jml_beli = []
    let jml_retur = []
    let id_produk_unit = []
    let diskon_persen = []
    let diskon_rupiah = []
    let ppn_persen = []
    let ppn_rupiah = []
    let subtotal = []

    $('.kode_produk').each(function() {
         kode_produk.push($(this).text())
    })
    $('.harga_satuan').each(function() {
        harga_beli.push($(this).text())
    })
    $('.jml_beli').each(function() {
        jml_beli.push($(this).text())
    })
    $('.jml_ret').each(function() {
        jml_retur.push($(this).text())
    })
    $('.id_produk_unit').each(function() {
        id_produk_unit.push($(this).text())
    })
    $('.diskon_persen').each(function() {
        diskon_persen.push($(this).text())
    })
    $('.diskon_rp').each(function() {
        diskon_rupiah.push($(this).text())
    })
    $('.ppn').each(function() {
        ppn_persen.push($(this).text())
    })
    $('.ppn_rp').each(function() {
        ppn_rupiah.push($(this).text())
    })
    $('.subtotal').each(function() {
        subtotal.push($(this).text())
    })
    // End untuk Detail //

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('transaksi_retur_pembelian/store') }}",
        data: {
            no_sp: no_sp,
            no_transaksi_sp: no_transaksi_sp,
            tgl_sp: tgl_sp,
            jenis_sp: jenis_sp,
            pembelian: pembelian,
            subtotal_ret: subtotal_ret,
            jml_bayar_ret: jml_bayar_ret,

            kode_produk: kode_produk,
            harga_beli: harga_beli,
            jml_beli: jml_beli,
            jml_retur: jml_retur,
            id_produk_unit: id_produk_unit,
            diskon_persen: diskon_persen,
            diskon_rupiah: diskon_rupiah,
            ppn_persen: ppn_persen,
            ppn_rupiah: ppn_rupiah,
            subtotal: subtotal,
        },
        success: function(response) {
            if(response.res === true) {
                window.location.href = "{{ route('retur_pembelian.index')}}";
            }else{
                Swal.fire("Gagal!", "Data unit gagal disimpan.", "error");
            }
        }
    });
  });

</script>



@stop

@extends('layouts.apotek.admin')

@section('title')
<title>Tambah Retur Pembelian</title>
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
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Retur</li>
                <li class="breadcrumb-item"><a href="{{ route('retur_pembelian.index') }}">Pembelian</a></li>
                <li class="breadcrumb-item active">Tambah Retur Pembelian</li>
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
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">No Batch</label>
                                <div class="col-sm-2">
                                    <div class="input-group mb-3">
                                        <input type="text" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" class="form-control" name="no_batch" id="no_batch" value="">
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCariTransaksiBatch" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-3"></div> --}}
                            </div>
                            <div class="row mb-3">
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">No Faktur</label>
                                <div class="col-sm-2">
                                    {{-- <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly> --}}
                                    
                                        <div class="input-group mb-3">
                                            <input type="text" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" class="form-control" name="kode_transaksi_sp" id="kode_transaksi_sp" value="">
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCariTransaksi" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"><i class="bi bi-search"></i></button>
                                        </div>
                                </div>
                                {{-- <div class="col-sm-3"></div> --}}
                            </div>

                            <div class="row mb-3">
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">No Surat Pesanan</label>
                                <div class="col-sm-2">
                                    <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                </div>

                                <div class="col-sm-3"></div>
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Tgl Surat Pesanan</label>
                                <div class="col-sm-2">
                                    <input type="text" name="tgl_sp" id="tgl_sp" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Jenis Surat Pesanan</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="jenis_sp" id="jenis_sp" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                    </div>
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 col-form-label">Nama Supplier</label>
                                <div class="col-sm-2">
                                    <input type="text" name="nama_supplier" id="nama_supplier" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Kode Pembelian</label>
                                  <div class="col-sm-2">
                                    <input type="text" name="pembelian" id="pembelian" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                  </div>
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                                <div class="col-sm-2">
                                    <input type="text" name="jenis_transaksi" id="jenis_transaksi" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                </div>
                            </div>
                            
                            <hr style="border:0; height: 1px; background-color: black; ">

                            <table id="datatabel" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Harga Satuan</th>
                                        <th>Jml Beli</th>
                                        <th>jml Retur</th>
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
                                <tbody id="tabledata" class="tabledata">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="10"></td>
                                        <td>Subtotal:</td>
                                        <td>
                                            <input type="text"
                                                style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                class="form-control" name="f_subtotal" id="f_subtotal" value="0"
                                                readonly />
                                        </td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="10"></td>
                                        <td>Jml Uang Kembali:</td>
                                        <td>
                                            <input type="text"
                                                style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                class="form-control" name="f_jml_bayar" id="f_jml_bayar" value="0" />
                                        </td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="10"></td>
                                        <td></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" style="width: 100%;" id="button_form_insert_transaksi">R e t u r</button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCariTransaksi" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Data Transaksi Pembelian</h3>
                  <button type="button" class="btn-close tutupModalObat" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="#" method="get">
                    <div class="input-group mb-3 col-md-6 right">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Cari Transaksi...">
                    </div>
                  </form>
                  <table id="lookup" class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th>No Batch</th>
                        <th>No Faktur</th>
                        <th>No Surat Pesanan</th>
                        <th>Tgl Surat Pesanan</th>
                        <th>Jenis Surat Pesanan</th>
                        <th>Kode Pembelian</th>
                        <th>Nama Supplier</th>
                      </tr>
                    </thead>
                    <tbody id="tabledataModalPembelian" data-dismiss="modal">
                          
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger tutupModalObat" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="modalCariTransaksiBatch" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Data Transaksi Pembelian</h3>
                  <button type="button" class="btn-close tutupModalObat" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="#" method="get">
                    <div class="input-group mb-3 col-md-6 right">
                      <input type="text" name="searchBatch" id="searchBatch" class="form-control" placeholder="Cari Transaksi...">
                    </div>
                  </form>
                  <table id="lookupBatch" class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th>No Batch</th>
                        <th>No Faktur</th>
                        <th>No Surat Pesanan</th>
                        <th>Tgl Surat Pesanan</th>
                        <th>Jenis Surat Pesanan</th>
                        <th>Kode Pembelian</th>
                        <th>Nama Supplier</th>
                      </tr>
                    </thead>
                    <tbody id="tabledataModalPembelianBatch" data-dismiss="modal">
                          
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger tutupModalObat" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
              </div>
            </div>
        </div>
    </section>

    


</main>
@endsection
