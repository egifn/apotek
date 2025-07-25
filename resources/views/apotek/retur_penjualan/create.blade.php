@section('js')

<script type="text/javascript">
    let subtotal = 0;
    $("input[name='f_jml_bayar']").maskMoney({thousands:',', decimal:'.', precision:0});

    $(document).on('click', '.pilih', function (e) {
        $("#kode_transaksi").val($(this).attr('data-kode_penjualan'))
        $("#tgl_transaksi").val($(this).attr('data-tgl_penjualan'))
        $("#jenis_transaksi").val($(this).attr('data-jenis_penjualan'))
        $('#modalCariTransaksi').modal('hide');
        $("#kode_transaksi").focus();
    });

    //===Menampilkan Modal data Transaksi====//
    fetch_data_transaksi_penjualan();
    function fetch_data_transaksi_penjualan() {
        $.ajax({
            type: "GET",
            url: "{{ route('transaksi_retur_penjualan/getPenjualanModal.getPenjualanModal') }}",
            dataType: "json",
            success: function(response) {
                let tabledataModalPenjualan;
                response.data.forEach(penjualan => {
                    let total_penjualan = penjualan.total;
                    //membuat format rupiah Harga//
                    var reverse_penjualan = total_penjualan.toString().split('').reverse().join(''),
                    ribuan_reverse_penjualan  = reverse_penjualan.match(/\d{1,3}/g);
                    total_ribuan_reverse_penjualan = ribuan_reverse_penjualan.join(',').split('').reverse().join('');
                    //End membuat format rupiah//
                    
                    tabledataModalPenjualan += `<tr class="pilih" data-kode_penjualan="${penjualan.kode_penjualan}" data-tgl_penjualan="${penjualan.tgl_penjualan}" data-jenis_penjualan="${penjualan.jenis_penjualan}" data-total="${penjualan.total}">`;
                    tabledataModalPenjualan += `<td>${penjualan.kode_penjualan}</td>`;
                    tabledataModalPenjualan += `<td>${penjualan.tgl_penjualan}</td>`;
                    tabledataModalPenjualan += `<td>${penjualan.jenis_penjualan}</td>`;
                    tabledataModalPenjualan += `<td align="right">${total_ribuan_reverse_penjualan}</td>`;
                    tabledataModalPenjualan += `</tr>`;
                });
                $("#tabledataModalPenjualan").html(tabledataModalPenjualan);
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
                url: "{{ route('transaksi_retur_penjualan/getPenjualanModal.getPenjualanModal') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModalPenjualan;
                    response.data.forEach(penjualan => {
                        let total_penjualan = penjualan.total;
                        //membuat format rupiah Harga//
                        var reverse_penjualan = total_penjualan.toString().split('').reverse().join(''),
                        ribuan_reverse_penjualan  = reverse_penjualan.match(/\d{1,3}/g);
                        total_ribuan_reverse_penjualan = ribuan_reverse_penjualan.join(',').split('').reverse().join('');
                        //End membuat format rupiah//

                        tabledataModalPenjualan += `<tr class="pilih" data-kode_penjualan="${penjualan.kode_penjualan}" data-tgl_penjualan="${penjualan.tgl_penjualan}" data-jenis_penjualan="${penjualan.jenis_penjualan}" data-total="${penjualan.total}">`;
                        tabledataModalPenjualan += `<td>${penjualan.kode_penjualan}</td>`;
                        tabledataModalPenjualan += `<td>${penjualan.tgl_penjualan}</td>`;
                        tabledataModalPenjualan += `<td>${penjualan.jenis_penjualan}</td>`;
                        tabledataModalPenjualan += `<td align="right">${total_ribuan_reverse_penjualan}</td>`;
                        tabledataModalPenjualan += `</tr>`;
                    });
                    $("#tabledataModalPenjualan").html(tabledataModalPenjualan);
                }
            });
        }else{
            fetch_data_transaksi_penjualan();
        }
    });
    //===End Pencarian Modal data Transaksi====//

    //===Select data unit/kategori====//
    $("#kode_transaksi").keyup(function() {
        let value = $("#kode_transaksi").val();
        $.ajax({
        type: "GET",
        url: "{{ route('transaksi_retur_penjualan/getPenjualandetail.getPenjualandetail') }}",
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
                tabledata += '<td class="jml_beli" id="jml_beli' + no + '" align="right">' +el.qty+ '</td>';
                tabledata += '<td align="right"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml_ret[]' + no +'" id="jml_ret[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="0"></td>';
                tabledata += '<td class="jml_ret" id="jml_ret' + no + '" align="right" contenteditable="true" hidden>0</td>';
                tabledata += '<td>' +el.nama_unit+ '</td>';
                tabledata += '<td class="id_produk_unit" id="id_produk_unit' + no + '" hidden>' +el.id_produk_unit+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_persen[]' + no +'" id="diskon_persen[]' + no +'" onclick="diskon_persen(' + no + ');" onkeyup="diskon_persen(' + no + ');" value="' +el.diskon+ '"></td>';
                tabledata += '<td class="diskon_persen" id="diskon_persen' + no + '" align="right" contenteditable="true" hidden>' +el.diskon+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_rp[]' + no +'" id="diskon_rp[]' + no +'" onclick="diskon_rupiah(' + no + ');" onkeyup="diskon_rupiah(' + no + ');" value="' +el.diskon_rp+ '"></td>';
                tabledata += '<td class="diskon_rp" id="diskon_rp' + no + '" align="right" contenteditable="true" hidden>' +el.diskon_rp+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn[]' + no +'" id="ppn[]' + no +'" onclick="ppn_persen(' + no + ');" onkeyup="ppn_persen(' + no + ');" value="' +el.ppn+ '"></td>';
                tabledata += '<td class="ppn" id="ppn' + no + '" align="right" contenteditable="true" hidden>' +el.ppn+ '</td>';
                tabledata += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn_rp[]' + no +'" id="ppn_rp[]' + no +'" onclick="ppn_rupiah(' + no + ');" onkeyup="ppn_rupiah(' + no + ');" value="' +el.ppn_rp+ '"></td>';
                tabledata += '<td class="ppn_rp" id="ppn_rp' + no + '" align="right" contenteditable="true" hidden>' +el.ppn_rp+ '</td>';
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
  var total_ret_11 = 0;
  var total_ret_12 = 0;
  var total_ret_13 = 0;
  var total_ret_14 = 0;
  var total_ret_15 = 0;
  var total_ret_16 = 0;
  var total_ret_17 = 0;
  var total_ret_18 = 0;
  var total_ret_19 = 0;
  var total_ret_20 = 0;
  var total_ret_21 = 0;
  var total_ret_22 = 0;
  var total_ret_23 = 0;
  var total_ret_24 = 0;
  var total_ret_25 = 0;
  function jumlah(no) {
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();
    $('#jml_ret' + no +'').text(jml_ret);

    var harga_satuan = $('#harga_satuan' +no+ '').text();
    //menghilangka format rupiah//
        var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//
    
    var total_ret = jml_ret * hasil_temp_harga_satuan;
    var reverse = total_ret.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_total_ret = ribuan.join(',').split('').reverse().join('');
    $('#subtotal' + no +'').text(hasil_total_ret);

    
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
        
    }else if(no == 11){
        total_ret_11 = total_ret;
    }else if(no == 12){
        total_ret_12 = total_ret;
    }else if(no == 13){
        total_ret_13 = total_ret;
    }else if(no == 14){
        total_ret_14 = total_ret;
    }else if(no == 15){
        total_ret_15 = total_ret;
    }else if(no == 16){
        total_ret_16 = total_ret;
    }else if(no == 17){
        total_ret_17 = total_ret;
    }else if(no == 18){
        total_ret_18 = total_ret;
    }else if(no == 19){
        total_ret_19 = total_ret;
    }else if(no == 20){
        total_ret_10 = total_ret;
    }else if(no == 21){
        total_ret_10 = total_ret;
    }else if(no == 22){
        total_ret_22 = total_ret;
    }else if(no == 23){
        total_ret_23 = total_ret;
    }else if(no == 24){
        total_ret_24 = total_ret;
    }else if(no == 25){
        total_ret_25 = total_ret;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10
                + total_ret_11 + total_ret_12 + total_ret_13 + total_ret_14 + total_ret_15 + total_ret_16 + total_ret_17 + total_ret_18 + total_ret_19 + total_ret_20
                + total_ret_21 + total_ret_22 + total_ret_23 + total_ret_24 + total_ret_25);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);

    var total_sum_pembulatan = Math.ceil(subtotal/500)*500;
    //membuat format rupiah dari total total_sum_pembulatan//
    var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total total_sum_pembulatan//

    pembulatan = total_sum_pembulatan-subtotal;
    //membuat format rupiah dari total pembulatan//
    var reverse = pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total pembulatan//

    $('#f_pembulatan').val(hasil_pembulatan);
    $('#f_total_bayar').val(hasil_total_sum_pembulatan);

  }

  function diskon_persen(no) {
    var jml_ret = $("input[name='jml_ret[]" +no+ "']").val();

    var diskon_persen = $("input[name='diskon_persen[]" +no+ "']").val(); 
    var harga_satuan = $('#harga_satuan' +no+ '').text();
    //menghilangka format rupiah//
        var temp_harga_satuan = harga_satuan.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_harga_satuan = parseInt(temp_harga_satuan.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//
    
    var diskon_rupiah = (diskon_persen / 100) * hasil_temp_harga_satuan;
    //membuat format rupiah//
    var reverse = diskon_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_diskon_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    var subtotal_setelah_diskon = (hasil_temp_harga_satuan * jml_ret) - diskon_rupiah;
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
        
    }else if(no == 11){
        total_ret_11 = subtotal_setelah_diskon;
    }else if(no == 12){
        total_ret_12 = subtotal_setelah_diskon;
    }else if(no == 13){
        total_ret_13 = subtotal_setelah_diskon;
    }else if(no == 14){
        total_ret_14 = subtotal_setelah_diskon;
    }else if(no == 15){
        total_ret_15 = subtotal_setelah_diskon;
    }else if(no == 16){
        total_ret_16 = subtotal_setelah_diskon;
    }else if(no == 17){
        total_ret_17 = subtotal_setelah_diskon;
    }else if(no == 18){
        total_ret_18 = subtotal_setelah_diskon;
    }else if(no == 19){
        total_ret_19 = subtotal_setelah_diskon;
    }else if(no == 20){
        total_ret_20 = subtotal_setelah_diskon;
    }else if(no == 21){
        total_ret_21 = subtotal_setelah_diskon;
    }else if(no == 22){
        total_ret_22 = subtotal_setelah_diskon;
    }else if(no == 23){
        total_ret_23 = subtotal_setelah_diskon;
    }else if(no == 24){
        total_ret_24 = subtotal_setelah_diskon;
    }else if(no == 25){
        total_ret_25 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10
                + total_ret_11 + total_ret_12 + total_ret_13 + total_ret_14 + total_ret_15 + total_ret_16 + total_ret_17 + total_ret_18 + total_ret_19 + total_ret_20
                + total_ret_21 + total_ret_22 + total_ret_23 + total_ret_24 + total_ret_25);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);

    var total_sum_pembulatan = Math.ceil(subtotal/500)*500;
    //membuat format rupiah dari total total_sum_pembulatan//
    var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total total_sum_pembulatan//

    pembulatan = total_sum_pembulatan-subtotal;
    //membuat format rupiah dari total pembulatan//
    var reverse = pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total pembulatan//

    $('#f_pembulatan').val(hasil_pembulatan);
    $('#f_total_bayar').val(hasil_total_sum_pembulatan);

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

    var diskon_persen = (hasil_temp_diskon_rupiah / hasil_temp_harga_satuan) * 100;

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
        
    }else if(no == 11){
        total_ret_11 = subtotal_setelah_diskon;
    }else if(no == 12){
        total_ret_12 = subtotal_setelah_diskon;
    }else if(no == 13){
        total_ret_13 = subtotal_setelah_diskon;
    }else if(no == 14){
        total_ret_14 = subtotal_setelah_diskon;
    }else if(no == 15){
        total_ret_15 = subtotal_setelah_diskon;
    }else if(no == 16){
        total_ret_16 = subtotal_setelah_diskon;
    }else if(no == 17){
        total_ret_17 = subtotal_setelah_diskon;
    }else if(no == 18){
        total_ret_18 = subtotal_setelah_diskon;
    }else if(no == 19){
        total_ret_19 = subtotal_setelah_diskon;
    }else if(no == 20){
        total_ret_20 = subtotal_setelah_diskon;
    }else if(no == 21){
        total_ret_21 = subtotal_setelah_diskon;
    }else if(no == 22){
        total_ret_22 = subtotal_setelah_diskon;
    }else if(no == 23){
        total_ret_23 = subtotal_setelah_diskon;
    }else if(no == 24){
        total_ret_24 = subtotal_setelah_diskon;
    }else if(no == 25){
        total_ret_25 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10
                + total_ret_11 + total_ret_12 + total_ret_13 + total_ret_14 + total_ret_15 + total_ret_16 + total_ret_17 + total_ret_18 + total_ret_19 + total_ret_20
                + total_ret_21 + total_ret_22 + total_ret_23 + total_ret_24 + total_ret_25);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);

    var total_sum_pembulatan = Math.ceil(subtotal/500)*500;
    //membuat format rupiah dari total total_sum_pembulatan//
    var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total total_sum_pembulatan//

    pembulatan = total_sum_pembulatan-subtotal;
    //membuat format rupiah dari total pembulatan//
    var reverse = pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total pembulatan//

    $('#f_pembulatan').val(hasil_pembulatan);
    $('#f_total_bayar').val(hasil_total_sum_pembulatan);
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
    
    var ppn_rupiah = (ppn_persen / 100) * hasil_temp_harga_satuan*jml_ret;
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
        
    }else if(no == 11){
        total_ret_11 = subtotal_setelah_diskon;
    }else if(no == 12){
        total_ret_12 = subtotal_setelah_diskon;
    }else if(no == 13){
        total_ret_13 = subtotal_setelah_diskon;
    }else if(no == 14){
        total_ret_14 = subtotal_setelah_diskon;
    }else if(no == 15){
        total_ret_15 = subtotal_setelah_diskon;
    }else if(no == 16){
        total_ret_16 = subtotal_setelah_diskon;
    }else if(no == 17){
        total_ret_17 = subtotal_setelah_diskon;
    }else if(no == 18){
        total_ret_18 = subtotal_setelah_diskon;
    }else if(no == 19){
        total_ret_19 = subtotal_setelah_diskon;
    }else if(no == 20){
        total_ret_20 = subtotal_setelah_diskon;
    }else if(no == 21){
        total_ret_21 = subtotal_setelah_diskon;
    }else if(no == 22){
        total_ret_22 = subtotal_setelah_diskon;
    }else if(no == 23){
        total_ret_23 = subtotal_setelah_diskon;
    }else if(no == 24){
        total_ret_24 = subtotal_setelah_diskon;
    }else if(no == 25){
        total_ret_25 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10
                + total_ret_11 + total_ret_12 + total_ret_13 + total_ret_14 + total_ret_15 + total_ret_16 + total_ret_17 + total_ret_18 + total_ret_19 + total_ret_20
                + total_ret_21 + total_ret_22 + total_ret_23 + total_ret_24 + total_ret_25);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#f_subtotal').val(hasil_subtotal);

    var total_sum_pembulatan = Math.ceil(subtotal/500)*500;
    //membuat format rupiah dari total total_sum_pembulatan//
    var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total total_sum_pembulatan//

    pembulatan = total_sum_pembulatan-subtotal;
    //membuat format rupiah dari total pembulatan//
    var reverse = pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total pembulatan//

    $('#f_pembulatan').val(hasil_pembulatan);
    $('#f_total_bayar').val(hasil_total_sum_pembulatan);
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

    var ppn_persen = (hasil_temp_ppn_rupiah / (hasil_temp_harga_satuan*jml_ret)) * 100;

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
        
    }else if(no == 11){
        total_ret_11 = subtotal_setelah_diskon;
    }else if(no == 12){
        total_ret_12 = subtotal_setelah_diskon;
    }else if(no == 13){
        total_ret_13 = subtotal_setelah_diskon;
    }else if(no == 14){
        total_ret_14 = subtotal_setelah_diskon;
    }else if(no == 15){
        total_ret_15 = subtotal_setelah_diskon;
    }else if(no == 16){
        total_ret_16 = subtotal_setelah_diskon;
    }else if(no == 17){
        total_ret_17 = subtotal_setelah_diskon;
    }else if(no == 18){
        total_ret_18 = subtotal_setelah_diskon;
    }else if(no == 19){
        total_ret_19 = subtotal_setelah_diskon;
    }else if(no == 20){
        total_ret_20 = subtotal_setelah_diskon;
    }else if(no == 21){
        total_ret_21 = subtotal_setelah_diskon;
    }else if(no == 22){
        total_ret_22 = subtotal_setelah_diskon;
    }else if(no == 23){
        total_ret_23 = subtotal_setelah_diskon;
    }else if(no == 24){
        total_ret_24 = subtotal_setelah_diskon;
    }else if(no == 25){
        total_ret_25 = subtotal_setelah_diskon;
    }
    
    subtotal = (total_ret_1 + total_ret_2 + total_ret_3 + total_ret_4 + total_ret_5 + total_ret_6 + total_ret_7 + total_ret_8 + total_ret_9 + total_ret_10
                + total_ret_11 + total_ret_12 + total_ret_13 + total_ret_14 + total_ret_15 + total_ret_16 + total_ret_17 + total_ret_18 + total_ret_19 + total_ret_20
                + total_ret_21 + total_ret_22 + total_ret_23 + total_ret_24 + total_ret_25);
    var reverse = subtotal.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    $('#f_subtotal').val(hasil_subtotal);

    var total_sum_pembulatan = Math.ceil(subtotal/500)*500;
    //membuat format rupiah dari total total_sum_pembulatan//
    var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total total_sum_pembulatan//

    pembulatan = total_sum_pembulatan-subtotal;
    //membuat format rupiah dari total pembulatan//
    var reverse = pembulatan.toString().split('').reverse().join(''),
    ribuan  = reverse.match(/\d{1,3}/g);
    hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total pembulatan//

    $('#f_pembulatan').val(hasil_pembulatan);
    $('#f_total_bayar').val(hasil_total_sum_pembulatan);
  }

    //==== Jumlah Bayar =================== 
    $("input[name='f_jml_bayar']").keyup(function(e){
        //var f_subtotal = ($("input[name='f_subtotal']").val());
        var f_total_bayar = ($("input[name='f_total_bayar']").val());
        var f_jml_bayar = ($(this).val());

        //menghilangka format rupiah tambah_diskon//
        var temp_f_total_bayar = f_total_bayar.replace(/[.](?=.*?\.)/g, '');
        var temp_f_total_bayar_jadi = parseInt(temp_f_total_bayar.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        //menghilangka format rupiah tambah_diskon//
        var temp_f_jml_bayar = f_jml_bayar.replace(/[.](?=.*?\.)/g, '');
        var temp_f_jml_bayar_jadi = parseInt(temp_f_jml_bayar.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var f_kembali = temp_f_jml_bayar_jadi - temp_f_total_bayar_jadi;
        //membuat format rupiah//
        var reverse = f_kembali.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_f_kembali = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
                        
        $(".f_kembali").text(hasil_f_kembali); 
    })
    //==== End Jumlah Bayar =================== 

  $('#button_form_insert_transaksi').click(function(e) {
    if ($("#kode_transaksi").val() == ""){
        alert("No Faktur harus diisi. No Faktur tidak boleh kosong");
        $("#kode_transaksi").focus();
        return (false);
    }
   
    if ($("#f_jml_bayar").val() == 0){
        alert("Isi Jml Bayar");
        $("#f_jml_bayar").focus();
        return (false);
    }

    e.preventDefault();
    let no_faktur = $("#kode_transaksi").val();
    let tgl_faktur = $("#tgl_transaksi").val();
    let jenis = $("#jenis_transaksi").val();
    let subtotal_ret = $("#f_subtotal").val();
    let pembulatan_ret = $("#f_pembulatan").val();
    let total_bayar_ret = $("#f_total_bayar").val();
    let jml_bayar_ret = $("#f_jml_bayar").val();
    let kembali = $(".f_kembali").text();

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
        url: "{{ route('transaksi_retur_penjualan/store') }}",
        data: {
            no_faktur: no_faktur,
            tgl_faktur: tgl_faktur,
            jenis: jenis,
            subtotal_ret: subtotal_ret,
            pembulatan_ret: pembulatan_ret,
            total_bayar_ret: total_bayar_ret,
            jml_bayar_ret: jml_bayar_ret,
            kembali: kembali,

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
                window.location.href = "{{ route('retur_penjualan.index')}}";
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
<title>Tambah Retur Penjualan</title>
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
                <li class="breadcrumb-item"><a href="{{ route('retur_penjualan.index') }}">Penjualan</a></li>
                <li class="breadcrumb-item active">Tambah Retur Penjualan</li>
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
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">No Faktur</label>
                                <div class="col-sm-2">
                                    {{-- <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly> --}}
                                    
                                    <div class="input-group mb-3">
                                        <input type="text" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" class="form-control" name="kode_transaksi" id="kode_transaksi" value="">
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCariTransaksi" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>

                                <div class="col-sm-3"></div>

                                <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                                <div class="col-sm-3">
                                    <input type="text" name="jenis_transaksi" id="jenis_transaksi" class="form-control"
                                        value=""
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                </div>

                                {{-- <div class="col-sm-3"></div> --}}
                            </div>
                            <div class="row mb-3">
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Tgl Faktur</label>
                                <div class="col-sm-2">
                                    <input type="text" name="tgl_transaksi" id="tgl_transaksi" class="form-control"
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
                                        <td>Pembulatan:</td>
                                        <td>
                                            <input type="text"
                                                style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                class="form-control" name="f_pembulatan" id="f_pembulatan" value="0"
                                                readonly />
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"></td>
                                        <td>Total Bayar:</td>
                                        <td>
                                            <input type="text"
                                                style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                class="form-control" name="f_total_bayar" id="f_total_bayar" value="0"
                                                readonly />
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"></td>
                                        <td>Jml Bayar:</td>
                                        <td>
                                            <input type="text"
                                                style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                class="form-control" name="f_jml_bayar" id="f_jml_bayar" value="0"
                                                required />
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"></td>
                                        <td>Kembali:</td>
                                        <td class="f_kembali" align="right">
                                            0
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"></td>
                                        <td></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" style="width: 100%;" id="button_form_insert_transaksi">Bayar</button>
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
                  <h3 class="modal-title">Data Transaksi Penjualan</h3>
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
                        <th>No Faktur</th>
                        <th>Tgl Faktur</th>
                        <th>Jenis</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody id="tabledataModalPenjualan" data-dismiss="modal">
                          
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
