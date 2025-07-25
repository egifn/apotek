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
  //=== Select data Pendaftaran ====//
  fetchAllKunjungan();
  function fetchAllKunjungan() {
    $.ajax({
      type: "GET",
      url: "{{ route('kasir/getDataKasir.getDataKasir') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(antrian => {
          no = no + 1

          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${antrian.kode_kunjungan}</td>`;
          tabledata += `<td>${antrian.no_rm}</td>`;
          tabledata += `<td>${antrian.nama_pasien}</td>`;
          tabledata += `<td>${antrian.nama_poli}</td>`;
          tabledata += `<td>${antrian.tgl_kunjungan}</td>`;
            if (antrian.status_periksa == 0) {
                tabledata += `<td align="center"><span class="badge bg-secondary">Dalam Antrian</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Selesai</span></td>`;
            }

            if (antrian.status_kasir == 0) {
                tabledata += `<td align="center"><span class="badge bg-danger">Belum Bayar</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Sudah Bayar</span></td>`;
            }
          tabledata += `<td hidden>${antrian.id_user_input}</td>`;
          tabledata += `<td hidden>${antrian.name}</td>`;
          tabledata += `<td hidden>${antrian.kode_cabang}</td>`;
          tabledata += `<td hidden>${antrian.nama_cabang}</td>`;
          if (antrian.status_kasir == 0) {
            tabledata += `<td align="center">
            <button type="button" 
            data-id="${antrian.kode_kunjungan}" 
            data-tgl="${antrian.tgl_kunjungan}"
            data-rm="${antrian.no_rm}"
            data-nama_pasien="${antrian.nama_pasien}"
            data-nama_poli="${antrian.nama_poli}"
            id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>&nbsp;
            <button type="button" data-id="${antrian.kode_kunjungan}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          } else {
            tabledata += `<td align="center">
            <button type="button" 
            data-id="${antrian.kode_kunjungan}" 
            data-tgl="${antrian.tgl_kunjungan}"
            data-rm="${antrian.no_rm}"
            data-nama_pasien="${antrian.nama_pasien}"
            data-nama_poli="${antrian.nama_poli}"
            id="button_bayar" class="btn btn-warning btn-sm" disabled><i class="bi bi-cash-coin"></i></button>&nbsp;
            <button type="button" data-id="${antrian.kode_kunjungan}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          }
          tabledata += `</td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //=== End Select data Pendaftaran ====//

  //=== Pencarian berdasarkan tanggal ====//
  $("#button_cari_tanggal").click(function(){
    let tgl_cari = $("#tanggal").val();
    $.ajax({
      type: "GET",
      url: "{{ route('kasir/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(antrian => {
          no = no + 1

          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${antrian.kode_kunjungan}</td>`;
          tabledata += `<td>${antrian.no_rm}</td>`;
          tabledata += `<td>${antrian.nama_pasien}</td>`;
          tabledata += `<td>${antrian.nama_poli}</td>`;
          tabledata += `<td>${antrian.tgl_kunjungan}</td>`;
            if (antrian.status_periksa == 0) {
                tabledata += `<td align="center"><span class="badge bg-secondary">Dalam Antrian</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Selesai</span></td>`;
            }

            if (antrian.status_kasir == 0) {
                tabledata += `<td align="center"><span class="badge bg-danger">Belum Bayar</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Sudah Bayar</span></td>`;
            }
          tabledata += `<td hidden>${antrian.id_user_input}</td>`;
          tabledata += `<td hidden>${antrian.name}</td>`;
          tabledata += `<td hidden>${antrian.kode_cabang}</td>`;
          tabledata += `<td hidden>${antrian.nama_cabang}</td>`;
          if (antrian.status_kasir == 0) {
            tabledata += `<td align="center">
            <button type="button" 
            data-id="${antrian.kode_kunjungan}" 
            data-tgl="${antrian.tgl_kunjungan}"
            data-rm="${antrian.no_rm}"
            data-nama_pasien="${antrian.nama_pasien}"
            data-nama_poli="${antrian.nama_poli}"
            id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>&nbsp;
            <button type="button" data-id="${antrian.kode_kunjungan}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          } else {
            tabledata += `<td align="center">
            <button type="button" 
            data-id="${antrian.kode_kunjungan}" 
            data-tgl="${antrian.tgl_kunjungan}"
            data-rm="${antrian.no_rm}"
            data-nama_pasien="${antrian.nama_pasien}"
            data-nama_poli="${antrian.nama_poli}"
            id="button_bayar" class="btn btn-warning btn-sm" disabled><i class="bi bi-cash-coin"></i></button>&nbsp;
            <button type="button" data-id="${antrian.kode_kunjungan}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          }
          tabledata += `</td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//
  
  //===View Data Penerimaan===============//
  let total_tindakan = 0;
  $(document).on("click", "#button_bayar", function(e) {
    e.preventDefault();
    let no_kunjungan = $(this).data('id');
    let tgl_kunjungan = $(this).data('tgl');
    let norm = $(this).data('rm');
    let nama = $(this).data('nama_pasien');
    let nama_poli = $(this).data('nama_poli')
    
    $("#kode_kunjungan").val(no_kunjungan);
    $("#norm").val(norm);
    $("#nama_pasien").val(nama);
    $("#poli").val(nama_poli);
    let subtotal_all = 0;
    $.ajax({
        type: "GET",
        url: "{{ route('kasir/getDataTindakan.getDataTindakan') }}",
        data: {
            no_kunjungan: no_kunjungan
        },
        dataType: "json",
        success: function(response) {
            let data_tindakan;
            let no = 0;
            //let subtotal_tindakan = 0;
            response.data.forEach(detail => {
                let total = detail.harga;
                //membuat format rupiah total//
                var reverse_total = total.toString().split('').reverse().join(''),
                ribuan_total  = reverse_total.match(/\d{1,3}/g);
                total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                //End membuat format total//

                no = no + 1
                data_tindakan += '<tr>';
                data_tindakan += '<td></td>';    
                data_tindakan += '<td class="nama_tindakan">' +detail.nama_jasa_p+ '</td>';
                data_tindakan += '<td class="kode_tindakan" hidden>' +detail.kode_jasa_p+ '</td>';
                data_tindakan += '<td class="harga_tindakan" id="harga_tindakan' + no +'" align="right">' +total_rupiah+ '</td>';    
                data_tindakan += '</tr>';

                let temp_total = total;
                subtotal_all = subtotal_all + parseInt(temp_total);

                let temp_total_tindaan = subtotal_all;
                total_tindakan = temp_total_tindaan; //+ parseInt(temp_total);
            });
            $("#data_tindakan").html(data_tindakan);
        }
    });

    $.ajax({
        type: "GET",
        url: "{{ route('kasir/getDataObat.getDataObat') }}",
        data: {
            no_kunjungan: no_kunjungan
        },
        dataType: "json",
        success: function(response) {
            let data_obat;
            let no = 0;
            //let subtotal_obat = 0;
            response.data.forEach(detail_obat => {
                let harga = detail_obat.harga;
                //membuat format rupiah total//
                var reverse_harga = harga.toString().split('').reverse().join(''),
                ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
                total_harga = ribuan_harga.join(',').split('').reverse().join('');
                //End membuat format total//

                let tuslah = detail_obat.tuslah;
                //membuat format rupiah total//
                var reverse_tuslah = tuslah.toString().split('').reverse().join(''),
                ribuan_tuslah  = reverse_tuslah.match(/\d{1,3}/g);
                total_tuslah = ribuan_tuslah.join(',').split('').reverse().join('');
                //End membuat format total//

                let embalase = detail_obat.embalase;
                //membuat format rupiah total//
                var reverse_embalase = embalase.toString().split('').reverse().join(''),
                ribuan_embalase  = reverse_embalase.match(/\d{1,3}/g);
                total_embalase = ribuan_embalase.join(',').split('').reverse().join('');
                //End membuat format total//

                let total = detail_obat.harga*detail_obat.qty;
                //membuat format rupiah total//
                var reverse_total = total.toString().split('').reverse().join(''),
                ribuan_total  = reverse_total.match(/\d{1,3}/g);
                total_rupiah = ribuan_total.join(',').split('').reverse().join('');
                //End membuat format total//

                no = no + 1
                data_obat += '<tr>';
                data_obat += '<td></td>';    
                data_obat += '<td class="nama_produk">' +detail_obat.nama_produk+ '</td>';
                data_obat += '<td class="kode_produk" hidden>' +detail_obat.kode_produk+ '</td>';
                data_obat += '<td class="qty" id="qty' + no +'">' +detail_obat.qty+ '</td>';
                data_obat += '<td class="nama_unit">' +detail_obat.nama_unit+ '</td>';
                data_obat += '<td class="aturan">' +detail_obat.aturan+ '</td>';
                data_obat += '<td class="harga" id="harga' + no +'" align="right">' +total_harga+ '</td>';
                // data_obat += '<td class="tuslah" align="right">' +total_tuslah+ '</td>'; //tuslah
                // data_obat += '<td class="embalase" align="right">' +total_embalase+ '</td>'; //embalase
                data_obat += '<td class="tuslah">';
                  data_obat += '<input type="text" class="form-control" style="width:80px;height:27px;text-align:right;" name="tuslah[]' + no +'" id="tuslah[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="0">';
                data_obat += '</td>';
                data_obat += '<td class="temp_tuslah" align="right" id="temp_tuslah' + no +'" contenteditable="true" hidden>0</td>'; //tuslah
                
                data_obat += '<td class="embalase">';
                  data_obat += '<input type="text" class="form-control" style="width:80px;height:27px;text-align:right;" name="embalase[]' + no +'" id="embalase[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="0">';
                data_obat += '</td>';
                data_obat += '<td class="temp_embalase" align="right" id="temp_embalase' + no +'" contenteditable="true" hidden>0</td>'; //embalase
                data_obat += '<td class="total" id="total' + no +'" align="right">' +total_rupiah+ '</td>';    
                data_obat += '</tr>';

                //menghilangka format rupiah//
                var temp_total_rupiah = total_rupiah.replace(/[.](?=.*?\.)/g, '');
                var hasil_temp_total_rupiah = parseInt(temp_total_rupiah.replace(/[^0-9.]/g,''));
                //End menghilangka format rupiah//

                let temp_total = hasil_temp_total_rupiah;
                subtotal_all = subtotal_all + parseInt(temp_total);
            });
            $("#data_obat").html(data_obat);
            //membuat format rupiah total//
            var reverse_subtotal_all = subtotal_all.toString().split('').reverse().join(''),
                ribuan_reverse_subtotal_all  = reverse_subtotal_all.match(/\d{1,3}/g);
                total_ribuan_reverse_subtotal_all = ribuan_reverse_subtotal_all.join(',').split('').reverse().join('');
            //End membuat format total//
            $(".f_subtotal").text(total_ribuan_reverse_subtotal_all);
            //$(".f_total_bayar").text(total_ribuan_reverse_subtotal_all);

            var total_sum_pembulatan = Math.ceil(subtotal_all/500)*500;
            //membuat format rupiah dari total total_sum_pembulatan//
            var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
              ribuan  = reverse.match(/\d{1,3}/g);
              hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
            //end membuat format rupiah dari total total_sum_pembulatan//

            pembulatan = total_sum_pembulatan-subtotal_all;
            //membuat format rupiah dari total pembulatan//
            var reverse = pembulatan.toString().split('').reverse().join(''),
              ribuan  = reverse.match(/\d{1,3}/g);
              hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
            //end membuat format rupiah dari total pembulatan//

            $(".f_pembulatan").text(hasil_pembulatan);
            $(".f_total_bayar").text(hasil_total_sum_pembulatan);
        }
    });
    $('#modalPembayaran').modal('show');
  });

  $(document).ready(function(no){
    $("#f_jml_bayar").maskMoney({thousands:',', decimal:'.', precision:0});
    $("#f_pembulatan").maskMoney({thousands:',', decimal:'.', precision:0});
  });

  let total_sum = 0;
  let sum_subtotal_1 = 0;
  let sum_subtotal_2 = 0;
  let sum_subtotal_3 = 0;
  let sum_subtotal_4 = 0;
  let sum_subtotal_5 = 0;
  let sum_subtotal_6 = 0;
  let sum_subtotal_7 = 0;
  let sum_subtotal_8 = 0;
  let sum_subtotal_9 = 0;
  let sum_subtotal_10 = 0;
  let sum_subtotal_11 = 0;
  let sum_subtotal_12 = 0;
  let sum_subtotal_13 = 0;
  let sum_subtotal_14 = 0;
  let sum_subtotal_15 = 0;
  let sum_subtotal_16 = 0;
  let sum_subtotal_17 = 0;
  let sum_subtotal_18 = 0;
  let sum_subtotal_19 = 0;
  let sum_subtotal_20 = 0;
  function jumlah(no) {
    // $("input[name='tuslah[]" +no+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
    // $("input[name='embalase[]" +no+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
    var harga_tindakan = total_tindakan;
  
    var temp_total = $('#total' + no + '').text();
    //menghilangka format rupiah tambah_biaya//
    var temp_temp_total = temp_total.replace(/[.](?=.*?\.)/g, '');
    var temp_temp_total_jadi = parseInt(temp_temp_total.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah tambah_biaya//

    var qty = $('#qty' + no + '').text();
    var harga = $('#harga' + no + '').text();
    //menghilangka format rupiah tambah_biaya//
    var temp_harga = harga.replace(/[.](?=.*?\.)/g, '');
    var temp_harga_jadi = parseInt(temp_harga.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah tambah_biaya//

    var tuslah = ($("input[name='tuslah[]" +no+ "']").val());
    var embalase = ($("input[name='embalase[]" +no+ "']").val());

    //menghilangka format rupiah tambah_biaya//
    var temp_tuslah = tuslah.replace(/[.](?=.*?\.)/g, '');
    var temp_tuslah_jadi = parseInt(temp_tuslah.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah tambah_biaya//
   
    //menghilangka format rupiah tambah_biaya//
    var temp_embalase = embalase.replace(/[.](?=.*?\.)/g, '');
    var temp_embalase_jadi = parseInt(temp_embalase.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah tambah_biaya//

    total = temp_harga_jadi*qty+temp_tuslah_jadi+temp_embalase_jadi;

    $('#temp_tuslah' + no + '').text(temp_tuslah_jadi); 
    $('#temp_embalase' + no + '').text(temp_embalase_jadi); 


    //membuat format rupiah//
    var reverse = total.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_total = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    $('#total' + no + '').text(hasil_total);

    if(no == 1){
      sum_subtotal_1 = total;
    }else if(no == 2){
      sum_subtotal_2 = total;
    }else if(no == 3){
      sum_subtotal_3 = total;
    }else if(no == 4){
      sum_subtotal_4 = total;
    }else if(no == 5){
      sum_subtotal_5 = total;
    }else if(no == 6){
      sum_subtotal_6 = total;
    }else if(no == 7){
      sum_subtotal_7 = total;
    }else if(no == 8){
      sum_subtotal_8 = total;
    }else if(no == 9){
      sum_subtotal_9 = total;
    }else if(no == 10){
      sum_subtotal_10 = total;
    }else if(no == 11){
      sum_subtotal_11 = total;
    }else if(no == 12){
      sum_subtotal_12 = total;
    }else if(no == 13){
      sum_subtotal_13 = total;
    }else if(no == 14){
      sum_subtotal_14 = total;
    }else if(no == 15){
      sum_subtotal_15 = total;
    }else if(no == 16){
      sum_subtotal_16 = total;
    }else if(no == 17){
      sum_subtotal_17 = total;
    }else if(no == 18){
      sum_subtotal_18 = total;
    }else if(no == 19){
      sum_subtotal_19 = total;
    }else if(no == 20){
      sum_subtotal_20 = total;
    }

    total_sum = (sum_subtotal_1+sum_subtotal_2+sum_subtotal_3+sum_subtotal_4+sum_subtotal_5+sum_subtotal_6+sum_subtotal_7+sum_subtotal_8+sum_subtotal_9+sum_subtotal_10+
                sum_subtotal_11+sum_subtotal_12+sum_subtotal_13+sum_subtotal_14+sum_subtotal_15+sum_subtotal_16+sum_subtotal_17+sum_subtotal_18+sum_subtotal_19+sum_subtotal_20+harga_tindakan);
    //membuat format rupiah dari total sum//
    var reverse = total_sum.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_total_sum = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total sum//
    
    $(".f_subtotal").text(hasil_total_sum);

    var total_sum_pembulatan = Math.ceil(total_sum/500)*500;
    //membuat format rupiah dari total total_sum_pembulatan//
    var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total total_sum_pembulatan//

    pembulatan = total_sum_pembulatan-total_sum;
    //membuat format rupiah dari total pembulatan//
    var reverse = pembulatan.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
    //end membuat format rupiah dari total pembulatan//

    $(".f_pembulatan").text(hasil_pembulatan);
    $(".f_total_bayar").text(hasil_total_sum_pembulatan);
  }

   //==== Pembulatan =====================
   $("input[name='f_pembulatan']").keyup(function(e){
        var f_subtotal = ($(".f_subtotal").text());
        var f_bulat = ($(this).val());
        
        //menghilangka format rupiah tambah_diskon//
        var temp_f_subtotal = f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var temp_f_subtotal_jadi = parseInt(temp_f_subtotal.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        //menghilangka format rupiah tambah_diskon//
        var temp_f_bulat = f_bulat.replace(/[.](?=.*?\.)/g, '');
        var temp_f_bulat_jadi = parseInt(temp_f_bulat.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var f_hasil_pembulatan = temp_f_bulat_jadi + temp_f_subtotal_jadi;
        
        //membuat format rupiah//
        var reverse = f_hasil_pembulatan.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            f_hasil_pembulatan_jadi = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
       
        $(".f_total_bayar").text(f_hasil_pembulatan_jadi); 
    })
    //==== Pembulatan =====================
  
  $("input[name='f_jml_bayar']").keyup(function(e){
        var f_subtotal = ( $(".f_total_bayar").text());
        var f_jml_bayar = ($(this).val());

        //menghilangka format rupiah tambah_diskon//
        var temp_f_subtotal = f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var temp_f_subtotal_jadi = parseInt(temp_f_subtotal.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        //menghilangka format rupiah tambah_diskon//
        var temp_f_jml_bayar = f_jml_bayar.replace(/[.](?=.*?\.)/g, '');
        var temp_f_jml_bayar_jadi = parseInt(temp_f_jml_bayar.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var f_kembali = temp_f_jml_bayar_jadi - temp_f_subtotal_jadi;
        //membuat format rupiah//
        var reverse = f_kembali.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_f_kembali = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
                        
        $(".f_kembali").text(hasil_f_kembali); 
  });

  //=== Insert data Pendaftaran =================//
  $("#button_form_insert").click(function() {
    let kode_kunjungan = $("#kode_kunjungan").val();
    let no_rm = $("#norm").val();
    let poli = $("#poli").val();
    let cara_bayar = $("#c_bayar").val();
    let bank = $("#c_bayar_bank").val();
    let subtotal = $(".f_subtotal").text();
    let pembulatan = $(".f_pembulatan").text();
    let total_bayar = $(".f_total_bayar").text();
    let jml_bayar = $("#f_jml_bayar").val();
    let kembali = $(".f_kembali").text();
 
    // untuk Detail //
    let kode_jasa = []
    let harga_jasa = []

    $('.kode_tindakan').each(function() {
        kode_jasa.push($(this).text())
    })
    $('.harga_tindakan').each(function() {
        harga_jasa.push($(this).text())
    })

    let kode_produk = []
    let qty = []
    let harga_produk = []
    let tuslah = []
    let embalase = []
    let total = []

    $('.kode_produk').each(function() {
        kode_produk.push($(this).text())
    })
    $('.qty').each(function() {
        qty.push($(this).text())
    })
    $('.harga').each(function() {
        harga_produk.push($(this).text())
    })
    $('.temp_tuslah').each(function() {
        tuslah.push($(this).text())
    })
    $('.temp_embalase').each(function() {
        embalase.push($(this).text())
    })
    $('.total').each(function() {
        total.push($(this).text())
    })
    
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('kasir/store') }}",
      data: {
        kode_kunjungan: kode_kunjungan,
        no_rm: no_rm,
        poli: poli,
        cara_bayar: cara_bayar,
        bank: bank,
        subtotal: subtotal,
        pembulatan: pembulatan,
        total_bayar: total_bayar,
        jml_bayar: jml_bayar,
        kembali: kembali,

        kode_jasa: kode_jasa,
        harga_jasa: harga_jasa,

        kode_produk: kode_produk,
        qty: qty,
        harga_produk: harga_produk,
        tuslah: tuslah,
        embalase: embalase,
        total: total,
      },
      success: function(response) {
        if(response.res == true) {
            window.location.href = "{{ route('kasir.index')}}";
        }else{
          Swal.fire("Gagal!", "Pembayaran gagal disimpan.", "error");
        }
      }
    });
  });
  //=== End Insert data Pendaftaran =================//

</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Kasir</title>
@endsection

@section('content')

<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="d-flex justify-content-between align-items-center">
      Kasir
    </h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kasir</li>
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
                    <table class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>No Antrian</th>
                              <th>No RM</th>
                              <th>Nama Pasien</th>
                              <th>Poli</th>
                              <th>Tanggal</th>
                              <th>Status Periksa</th>
                              <th>Status Kasir</th>
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

    <div class="modal fade" id="modalPembayaran" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Rincian Pembayaran Pasien</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                   <br>
                   <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" align="right"><b>Detail Pasien :</b></label>
                    </div>
    
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" align="right">No RM</label>
                      <div class="col-sm-3">
                        <input type="text" name="norm" id="norm" class="form-control" value="" required readonly>
                      </div>
                      
                      <label class="col-sm-2 col-form-label" align="right">Kode Kunjungan</label>
                      <div class="col-sm-3">
                        <input type="text" name="kode_kunjungan" id="kode_kunjungan" class="form-control" required readonly>
                      </div>
                    </div>
                    
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" align="right">Nama Pasien</label>
                      <div class="col-sm-3">
                        <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" value="" required readonly>
                      </div>
    
                      <label class="col-sm-2 col-form-label" align="right">Poli</label>
                      <div class="col-sm-3">
                        <input type="text" name="poli" id="poli" class="form-control" value="" required readonly>
                      </div>
    
                      {{-- <label class="col-sm-1 col-form-label" align="right">G. Darah</label>
                      <div class="col-sm-1">
                        <input type="text" name="gol_darah" id="gol_darah" class="form-control" style="text-align: center;" value="" required readonly>
                      </div> --}}
                    </div>
    
                    <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
    
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" align="right"><b>Daftar Biaya :</b></label>
                    </div>
    
                    <div class="row mb-3">
                      <div class="col-sm-2">
                      </div>
                      <div class="col-sm-8">
                        <table id="datatabel_data_diagnosa" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                          <thead>
                            <tr>
                                <th colspan="3">Biaya Tindakan:</th>
                            </tr>
                            <tr>
                                <th style="width: 100px;"></th>
                                <th>Nama Tindakan</th>
                                <th align="right">total</th>
                            </tr>
                          </thead>
                          <tbody id="data_tindakan" class="data_tindakan">
        
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-8">
                          <table id="datatabel_data_obat" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                            <thead>
                              <tr>
                                  <th colspan="9">Biaya Obat-obatan:</th>
                              </tr>
                              <tr>
                                  <th style="width: 100px;"></th>
                                  <th>Nama Obat</th>
                                  <th>jml</th>
                                  <th>Satuan</th>
                                  <th>Aturan</th>
                                  <th>Harga</th>
                                  <th>Tuslah</th>
                                  <th>Embalase</th>
                                  <th align="right">total</th>
                              </tr>
                            </thead>
                            <tbody id="data_obat" class="data_obat">
          
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-8">
                          <table id="datatabel_rincian" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                            <tfoot>
                                <tr>
                                    <td align="right"><b>Subtotal:</b></td>
                                    <td width="130px" class="f_subtotal" id="f_subtotal" align="right" style="font-weight: bold;">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                  <td align="right"><b>Pembulatan:</b></td>
                                  <td width="130px" class="f_pembulatan" id="f_pembulatan" align="right" style="font-weight: bold;">
                                    0
                                  </td>
                                </tr>
                                <tr>
                                  <td align="right"><b>Total Bayar:</b></td>
                                  <td width="130px" class="f_total_bayar" id="f_total_bayar" align="right" style="font-weight: bold;">
                                      0
                                  </td>
                                </tr>
                                <tr>
                                    <td align="right"><b>Cara Bayar:</b></td>
                                    <td width="130px" class="f_cara_bayar" align="right" style="font-weight: bold;">
                                        <select name="c_bayar" id="c_bayar" class="form-select" style="height: 30px; font-size: 13px;" required>
                                            <option value="">Pilih...</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Debit">Debit</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><b>Bank:</b></td>
                                    <td width="130px" class="c_bayar_bank" align="right" style="font-weight: bold;">
                                        <select name="c_bayar_bank" id="c_bayar_bank" class="form-select"
                                            style="height: 30px; font-size: 13px;">
                                            <option value="">Pilih...</option>
                                            <option value="BCA">BCA</option>
                                            <option value="BNI">BNI</option>
                                            <option value="MANDIRI">MANDIRI</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><b>Jml Bayar:</b></td>
                                    <td width="130px" class="f_jml_bayar" align="right" style="font-weight: bold;">
                                        <input type="text"
                                        style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                        class="form-control" name="f_jml_bayar" id="f_jml_bayar" value="0"
                                        required />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right"><b>Kembali:</b></td>
                                    <td width="130px" class="f_kembali" id="f_kembali" align="right" style="font-weight: bold;">
                                    0
                                    </td>
                                </tr>
                                
                            </tfoot>
                          </table>
                        </div>
                      </div>

                    <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
    
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" id="button_form_insert" data-dismiss="modal"><i class="bi bi-save"></i> Bayar</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
            </div>
          </div>
        </div>
    </div>

</main>
@endsection



@section('js')
  
    
@endsection()