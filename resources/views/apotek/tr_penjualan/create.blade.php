@section('js')

<script type="text/javascript">
    var y = 0;
    var x = 1;
    var hasil = 0;
    $(document).on('click', '.pilih', function (e) {
        // var tabel = document.getElementById("datatabel");
        // var row = tabel.insertRow(1);

        // var cell1 = row.insertCell(0);
        // var cell2 = row.insertCell(1);
        // var cell3 = row.insertCell(2);
        // var cell4 = row.insertCell(3);
        // var cell5 = row.insertCell(4);
        // var cell6 = row.insertCell(5);
        // var cell7 = row.insertCell(6);
        // var cell8 = row.insertCell(7);
        // var cell9 = row.insertCell(8);
        // var cell10 = row.insertCell(9);

        // cell5.setAttribute('style', 'text-align:right;');
        // cell10.setAttribute('style', 'text-align:center;');

        // var kode_produk = $(this).attr('data-kode_produk');
        // var nama_produk = $(this).attr('data-nama_produk');
        // var nama_kategori = $(this).attr('data-nama_kategori');
        // var nama_jenis = $(this).attr('data-nama_jenis');
        // var nama_unit = $(this).attr('data-kemasan');
        // var qty = $(this).attr('data-qty');
        // var harga = $(this).attr('data-harga');
        // //membuat format rupiah untuk harga//
        // var reverse_harga = harga.toString().split('').reverse().join(''),
        //     ribuan_harga = reverse_harga.match(/\d{1,3}/g);
        // hasil_harga = ribuan_harga.join(',').split('').reverse().join('');
        // //End membuat format rupiah//

        // cell1.innerHTML = '<input type="text" class="form-control" name="kode_produk[]" id="kode_produk' + x +
        //     '" style="font-size: 13px;" value="' + kode_produk + '" hidden>' + kode_produk + '';
        // cell2.innerHTML = '<input type="text" class="form-control" name="nama_produk[]" id="nama_produk' + x +
        //     '" style="font-size: 13px;" value="' + nama_produk + '" hidden>' + nama_produk + '';
        // cell3.innerHTML = '<input type="text" class="form-control" name="kemasan[]" id="kemasan' + x +
        //     '" style="font-size: 13px;" value="' + nama_unit + '" hidden>' + nama_unit + '';
        // cell4.innerHTML = '<input type="text" class="form-control" name="stok[]" id="stok' + x +
        //     '" style="font-size: 13px;" value="' + qty + '" hidden>' + qty + '';
        // cell5.innerHTML = '<input type="text" class="form-control" name="harga[]" id="harga' + x +
        //     '" style="font-size: 13px; text-align: right;" value="' + harga + '" hidden>' + hasil_harga + '';

        // cell6.innerHTML =
        //     '<input type="text" style="height: 20px; width: 70px; text-align: right;" class="form-control" name="jml_beli[]" id="jml_beli' +
        //     x + '" style="font-size: 13px;" value="0" onkeyup="jumlah(' + x + ');" required />';
        // cell7.innerHTML =
        //     '<input type="text" style="height: 20px; width: 70px; text-align: right;" class="form-control" name="diskon[]" id="diskon' +
        //     x + '" style="font-size: 13px;" value="0" onkeyup="jumlah(' + x + ');" required />';
        // cell8.innerHTML =
        //     '<input type="text" style="height: 20px; width: 70px; text-align: right;" class="form-control" name="ppn[]" id="ppn' +
        //     x + '" style="font-size: 13px;" value="0" onkeyup="jumlah(' + x + ');" required />';
        // cell9.innerHTML =
        //     '<input type="text" style="height: 20px; text-align: right;" class="form-control" name="subtotal[]" id="subtotal' +
        //     x + '" style="font-size: 13px;" value="0" required />';
        // cell10.innerHTML =
        //     '<i onclick="delete_item(this)" style="color:#c00;" class="bi bi-x-square-fill bi-2x"></i>';
        $('#modalCari').modal('hide');
        x++;
    });

    //===Menampilkan Modal data produk====//
    fetch_product_data();
    function fetch_product_data() {
        $.ajax({
            type: "GET",
            url: "{{ route('getProdukModal') }}",
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
                    tabledataModal += `<td>${daftar.harga_jual}</td>`;
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
                url: "{{ route('getProdukModal') }}",
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
                        tabledataModal += `<td>${daftar.harga_jual}</td>`;
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

    //==== Search autocomplete =================== 
    $(document).ready(function () {
        fetch_product();

        function fetch_product(query = '') {
            $.ajax({
                type: 'GET',
                url: '{{ route("getProduk") }}',
                dataType: 'json',
                success: function (response) {
                    // console.log(response.data);
                    let cari_produk;
                    cari_produk += `<option value="" class="cari_produk">Cari Produk/Item...</option>`;
                    response.data.forEach(element => {
                        //membuat format rupiah//
                        var reverse = element.harga_jual.toString().split('').reverse().join(''),
                        ribuan  = reverse.match(/\d{1,3}/g);
                        harga_jual = ribuan.join(',').split('').reverse().join('');
                        //End membuat format rupiah//
                        
                        cari_produk +=
                            `<option value="${element.kode_produk} ${element.id_produk_unit}" class="cari_produk">${element.kode_produk} | ${element.barcode} | ${element.nama_produk} | stok: ${element.qty} | Satuan: ${element.nama_unit} | harga: Rp. ${harga_jual} </option>`; //| Satuan: ${element.nama_unit} | harga: Rp. ${harga_jual} 
                    });
                    $("#cari_produk").html(cari_produk);
                }
            })
        }
    });
    //==== End Search autocomplete =================== 
    
    $(document).ready(function () {
        if($("#jenis").val() == 'Online'){
            $(".pembeli").hide();
            $(".telepon").hide();
            $(".tambah_biaya_tambahan").show();
            $(".tambah_biaya_tambahan").show();
            $(".head_biaya_tambahan").show();
            $(".tambah_tuslah").hide();
            $(".head_tambah_tuslah").hide();
            $(".tambah_embalase").hide();
            $(".head_tambah_embalase").hide();
            $(".footer_termin").hide();
            $(".footer_jatuh_tempo").hide();
            $(".bayar").show();
            $(".sisa").show();
            $(".f_footer_bayar").show();
            $(".f_kembali").show();
            $(".1").show();
            $(".2").show();
            $(".3").show();
            $(".4").show();
            $(".5").show();
            $(".6").show();
            $(".7").show();
            $(".8").show();
            $(".9").show();

        }else if($("#jenis").val() == 'Panel'){
            $(".pembeli").show();
            $(".telepon").show();
            $(".tambah_biaya_tambahan").hide();
            $(".head_biaya_tambahan").hide();
            $(".tambah_tuslah").hide();
            $(".head_tambah_tuslah").hide();
            $(".tambah_embalase").hide();
            $(".head_tambah_embalase").hide();
            $(".footer_termin").show();
            $(".footer_jatuh_tempo").show();
            $(".bayar").hide();
            $(".sisa").hide();
            $(".f_footer_bayar").hide();
            $(".f_kembali").hide();
            $(".1").hide();
            $(".2").hide();
            $(".3").hide();
            $(".4").hide();
            $(".5").hide();
            $(".6").hide();
            $(".7").hide();
            $(".8").hide();
            $(".9").hide();
        }else if($("#jenis").val() == 'Langsung'){
            $(".pembeli").hide();
            $(".telepon").hide();
            $(".tambah_biaya_tambahan").hide();
            $(".head_biaya_tambahan").hide();
            $(".tambah_tuslah").hide();
            $(".head_tambah_tuslah").hide();
            $(".tambah_embalase").hide();
            $(".head_tambah_embalase").hide();
            $(".footer_termin").hide();
            $(".footer_jatuh_tempo").hide();
            $(".bayar").show();
            $(".sisa").show();
            $(".f_footer_bayar").show();
            $(".f_kembali").show();
            $(".1").show();
            $(".2").show();
            $(".3").show();
            $(".4").show();
            $(".5").show();
            $(".6").show();
            $(".7").show();
            $(".8").show();
            $(".9").show();
        }else if($("#jenis").val() == 'Resep Dokter'){
            $(".pembeli").hide();
            $(".telepon").hide();
            $(".tambah_biaya_tambahan").hide();
            $(".head_biaya_tambahan").hide();
            $(".tambah_tuslah").show();
            $(".head_tambah_tuslah").show();
            $(".tambah_embalase").show();
            $(".head_tambah_embalase").show();
            $(".footer_termin").hide();
            $(".footer_jatuh_tempo").hide();
            $(".bayar").show();
            $(".sisa").show();
            $(".f_footer_bayar").show();
            $(".f_kembali").show();
            $(".1").show();
            $(".2").show();
            $(".3").show();
            $(".4").show();
            $(".5").show();
            $(".6").show();
            $(".7").show();
            $(".8").show();
            $(".9").show();
        }
    });

    $("#jenis").change(function(e) {
        e.preventDefault();
        var jenis_transaksi = $(this).val();
    
        // if(jenis_transaksi == 'Panel'){
        //     $(".bayar").text("Uang Muka:");
        //     $(".sisa").text("Sisa Tagihan:");
        // }else{
        //     $(".bayar").text("Jml Bayar:")
        //     $(".sisa").text("Kembali:");
        // }

        if(jenis_transaksi == 'Online'){
            $(".pembeli").hide();
            $(".telepon").hide();
            $(".tambah_biaya_tambahan").show();
            $(".head_biaya_tambahan").show();
            $(".tambah_tuslah").hide();
            $(".head_tambah_tuslah").hide();
            $(".tambah_embalase").hide();
            $(".head_tambah_embalase").hide();
            $(".footer_termin").hide();
            $(".footer_jatuh_tempo").hide();
            $(".bayar").show();
            $(".sisa").show();
            $(".f_footer_bayar").show();
            $(".f_kembali").show();
            $(".1").show();
            $(".2").show();
            $(".3").show();
            $(".4").show();
            $(".5").show();
            $(".6").show();
            $(".7").show();
            $(".8").show();
            $(".9").show();
        }else if(jenis_transaksi == 'Panel'){
            $(".pembeli").show();
            $(".telepon").show();
            $(".tambah_biaya_tambahan").hide();
            $(".head_biaya_tambahan").hide();
            $(".tambah_tuslah").hide();
            $(".head_tambah_tuslah").hide();
            $(".tambah_embalase").hide();
            $(".head_tambah_embalase").hide();
            $(".footer_termin").show();
            $(".footer_jatuh_tempo").show();
            $(".bayar").hide();
            $(".sisa").hide();
            $(".f_footer_bayar").hide();
            $(".f_kembali").hide();
            $(".1").hide();
            $(".2").hide();
            $(".3").hide();
            $(".4").hide();
            $(".5").hide();
            $(".6").hide();
            $(".7").hide();
            $(".8").hide();
            $(".9").hide();
        }else if(jenis_transaksi == 'Langsung'){
            $(".pembeli").hide();
            $(".telepon").hide();
            $(".tambah_biaya_tambahan").hide();
            $(".head_biaya_tambahan").hide();
            $(".tambah_tuslah").hide();
            $(".head_tambah_tuslah").hide();
            $(".tambah_embalase").hide();
            $(".head_tambah_embalase").hide();
            $(".footer_termin").hide();
            $(".footer_jatuh_tempo").hide();
            $(".bayar").show();
            $(".f_footer_bayar").show();
            $(".sisa").show();
            $(".f_kembali").show();
            $(".1").show();
            $(".2").show();
            $(".3").show();
            $(".4").show();
            $(".5").show();
            $(".6").show();
            $(".7").show();
            $(".8").show();
            $(".9").show();
        }else if(jenis_transaksi == 'Resep Dokter'){
            $(".pembeli").hide();
            $(".telepon").hide();
            $(".tambah_biaya_tambahan").hide();
            $(".head_biaya_tambahan").hide();
            $(".tambah_tuslah").show();
            $(".head_tambah_tuslah").show();
            $(".tambah_embalase").show();
            $(".head_tambah_embalase").show();
            $(".footer_termin").hide();
            $(".footer_jatuh_tempo").hide();
            $(".bayar").show();
            $(".sisa").show();
            $(".f_footer_bayar").show();
            $(".f_kembali").show();
            $(".1").show();
            $(".2").show();
            $(".3").show();
            $(".4").show();
            $(".5").show();
            $(".6").show();
            $(".7").show();
            $(".8").show();
            $(".9").show();
        }
    })

    //==== Penjumlahan Tanggal dengan Input =====//
    $("input[name='termin']").keyup(function(e){
      var input_termin = ($("input[name='termin']").val());
      var jatuh_tempo = new Date(new Date().getTime()+(input_termin*24*60*60*1000)); // 1000 ini buat pengkalian milisecondnya date object
        
      var format_jt = new Intl.DateTimeFormat('id').format(jatuh_tempo);

      $("#jt").val(format_jt); 
    })
    //==== End Penjumlahan Tanggal dengan Input =====//

    //==== Search autocomplete dan masuk ke dalam tabel transaksi =================== 
    $("input[name='f_subtotal']").val(0);
    $("input[name='f_total_diskon']").val(0);
    $("input[name='f_total_ppn']").val(0);
    $("input[name='f_total']").val(0);
    $("input[name='f_jml_bayar']").val(0);

    var x = 1;
    $("#cari_produk").change(function(e) {
        e.preventDefault();
       
        var kode = $(this).val().substring(0,9);
        $('#unit_varian').val($(this).val().substring(10));
        var unit_varian = $('#unit_varian').val();
        
        var url = '{{ url("penjualan/getProdukPilih") }}' + '/' + kode + '/' + unit_varian;
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
                        isi += '<td>';
                            isi += data.data.nama_produk;
                        isi += '</td>'; 
                        isi += '<td class="jml_qty" id="jml_qty' + x +'">';
                            isi += data.data.qty;
                        isi += '</td>';
                        isi += '<td class="harga_jual" align="center">';
                            //membuat format rupiah//
                            var reverse = data.data.harga_jual.toString().split('').reverse().join(''),
                            ribuan  = reverse.match(/\d{1,3}/g);
                            hasil_harga_jual = ribuan.join(',').split('').reverse().join('');
                            //End membuat format rupiah//
                            isi += hasil_harga_jual;
                            isi += '<input type="hidden" class="form-control" name="harga_jual[]' + x +'" id="harga_jual[]' + x +'" onclick="jumlah(' + x + ');" onkeyup="jumlah(' + x + ');" value="'+data.data.harga_jual+'">';
                        isi += '</td>';
                        
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
                        
                        if($("#jenis").val() == 'Online'){
                            isi += '<td class="tambah_biaya_tambahan">';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="biaya_tambahan[]' + x +'" id="Biaya_tambahan[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_biaya_tambahan_temp" id="tambah_biaya_tambahan_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';

                            isi += '<td class="tambah_tuslah" hidden>';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="tuslah[]' + x +'" id="tuslah[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_tuslah_temp" id="tambah_tuslah_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';

                            isi += '<td class="tambah_embalase" hidden>';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="embalase[]' + x +'" id="embalase[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_embalase_temp" id="tambah_embalase_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';
                        }else if($("#jenis").val() == 'Resep Dokter'){
                            isi += '<td class="tambah_biaya_tambahan" hidden>';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="biaya_tambahan[]' + x +'" id="Biaya_tambahan[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_biaya_tambahan_temp" id="tambah_biaya_tambahan_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';

                            isi += '<td class="tambah_tuslah">';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="tuslah[]' + x +'" id="tuslah[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_tuslah_temp" id="tambah_tuslah_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';

                            isi += '<td class="tambah_embalase">';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="embalase[]' + x +'" id="embalase[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_embalase_temp" id="tambah_embalase_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';
                        }else{
                            isi += '<td class="tambah_biaya_tambahan" hidden>';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="biaya_tambahan[]' + x +'" id="Biaya_tambahan[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_biaya_tambahan_temp" id="tambah_biaya_tambahan_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';

                            isi += '<td class="tambah_tuslah" hidden>';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="tuslah[]' + x +'" id="tuslah[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_tuslah_temp" id="tambah_tuslah_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';

                            isi += '<td class="tambah_embalase" hidden>';
                                isi += '<input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="embalase[]' + x +'" id="embalase[]' + x +'" onkeyup="jumlah(' + x + ');" value="0">';
                            isi += '</td>';
                            isi += '<td class="tambah_embalase_temp" id="tambah_embalase_temp' + x +'" contenteditable="true" hidden>';
                                isi += 0;
                            isi += '</td>';
                        }
                        

                        isi += '<td class="subtotal' + x +'" align="right">';
                            isi += 0;
                        isi += '</td>';
                        
                        isi += '<td align="center">';
                            isi += '<i style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus" onclick="hapus_data(' + x + ')" value=""></i>';
                        isi += '</td>';
                    isi += '</tr>';
                    
                    $('.tabledata').append(isi);
                    x++;
                }
                
            }
        })
    })
    //==== End Search autocomplete dan masuk ke dalam tabel transaksi =================== 

    $(document).ready(function(x){
        // $("#f_bulat").maskMoney({thousands:',', decimal:'.', precision:0});
        $("#f_jml_bayar").maskMoney({thousands:',', decimal:'.', precision:0});
    });

    //==== Penjumlahan subtotal detail =================== 
    //var total = 0;
    function jumlah(x) {
        var harga_jual = parseInt($("input[name='harga_jual[]" +x+ "']").val());
        var tambah_jml = parseInt($("input[name='jml[]" +x+ "']").val());
        var jml_stok = $('#jml_qty' + x + '').text(); 
        if(tambah_jml > jml_stok){
            alert('Jumlah beli melebihi jumlah stok yang ada...!');
            $("input[name='jml[]" +x+ "']").val('0');
            $('#tambah_jml_temp' + x + '').text('0');
            $('.subtotal' + x + '').text('0'); 
            $('.a_subtotal' + x + '').text('0'); 
            // $('#f_subtotal').val('0');
            // $('#f_bulat').val('0');
            // $('#f_total').val('0');
            return (false);
        }else{
            $("input[name='diskon_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
            $("input[name='ppn_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
            $("input[name='biaya_tambahan[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
            $("input[name='tuslah[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
            $("input[name='embalase[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
            

            var tambah_diskon = parseInt($("input[name='diskon[]" +x+ "']").val());
            var temp_tambah_diskon_rp = Math.round((tambah_diskon / 100)*(harga_jual*tambah_jml));
            //membuat format rupiah//
            var reverse = temp_tambah_diskon_rp.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_temp_tambah_diskon_rp = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            
            var tambah_ppn = parseInt($("input[name='ppn[]" +x+ "']").val());
            var temp_tambah_ppn_rp = Math.round((tambah_ppn / 100)*(harga_jual*tambah_jml));
            //membuat format rupiah//
            var reverse = temp_tambah_ppn_rp.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_temp_tambah_ppn_rp = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            var tambah_biaya = ($("input[name='biaya_tambahan[]" +x+ "']").val());
            //menghilangka format rupiah tambah_biaya//
            var temp_tambah_biaya = tambah_biaya.replace(/[.](?=.*?\.)/g, '');
            var temp_tambah_biaya_jadi = parseInt(temp_tambah_biaya.replace(/[^0-9.]/g,''));
            //End menghilangka format rupiah tambah_biaya//

            var tambah_tuslah = ($("input[name='tuslah[]" +x+ "']").val());
            //menghilangka format rupiah tambah_biaya//
            var temp_tambah_tuslah = tambah_tuslah.replace(/[.](?=.*?\.)/g, '');
            var temp_tambah_tuslah_jadi = parseInt(temp_tambah_tuslah.replace(/[^0-9.]/g,''));
            //End menghilangka format rupiah tambah_biaya//

            var tambah_embalase = ($("input[name='embalase[]" +x+ "']").val());
            //menghilangka format rupiah tambah_biaya//
            var temp_tambah_embalase = tambah_embalase.replace(/[.](?=.*?\.)/g, '');
            var temp_tambah_embalase_jadi = parseInt(temp_tambah_embalase.replace(/[^0-9.]/g,''));
            //End menghilangka format rupiah tambah_biaya//
            
            var subtotal = parseInt(harga_jual * tambah_jml - temp_tambah_diskon_rp + temp_tambah_ppn_rp + temp_tambah_biaya_jadi+temp_tambah_tuslah_jadi+temp_tambah_embalase_jadi);
            //membuat format rupiah//
            var reverse = subtotal.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_subtotal = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            
            $('#tambah_jml_temp' + x + '').text(tambah_jml); 
            $('#tambah_diskon_temp' + x + '').text(tambah_diskon); 
            $("input[name='diskon_rp[]" +x+ "']").val(hasil_temp_tambah_diskon_rp);
            $('#tambah_ppn_temp' + x + '').text(tambah_ppn); 
            $("input[name='ppn_rp[]" +x+ "']").val(hasil_temp_tambah_ppn_rp);
            $('#tambah_ppn_rp_temp' + x + '').text(temp_tambah_ppn_rp);
            $('#tambah_diskon_rp_temp' + x + '').text(temp_tambah_diskon_rp);
            $('#tambah_biaya_tambahan_temp' + x + '').text(temp_tambah_biaya_jadi);
            $('#tambah_tuslah_temp' + x + '').text(temp_tambah_tuslah_jadi);
            $('#tambah_embalase_temp' + x + '').text(temp_tambah_embalase_jadi);
            
            $('.subtotal' + x + '').text(hasil_subtotal); 
            $('.a_subtotal' + x + '').text(hasil_subtotal); 
            
            ////PERULANGAN UNTUK MENJUMLAH SUM TOTAL////
            var table = document.getElementById("datatabel"), sumHsl = 0;
            for(var t = 1; t < table.rows.length; t++)
            {
                var sub_total = table.rows[t].cells[22].innerHTML;
                // //menghilangka format rupiah harga//
                var sub_total_non_format = sub_total.replace(/[.](?=.*?\.)/g, '');
                var sub_total_hasil = parseInt(sub_total_non_format.replace(/[^0-9.]/g,''));
                // //End menghilangka format rupiah harga//

                sumHsl = sumHsl + parseInt(sub_total_hasil);
                //membuat format rupiah total//
                var format_sumHsl = sumHsl.toString().split('').reverse().join(''),
                        ribuan  = format_sumHsl.match(/\d{1,3}/g);
                        hasil_format_sumHsl = ribuan.join(',').split('').reverse().join('');
                //End membuat format rupiah total//
                $('#f_subtotal').val(hasil_format_sumHsl);

                var total_sum_pembulatan = Math.ceil(sumHsl/500)*500;
                pembulatan = total_sum_pembulatan-sumHsl;
                //membuat format rupiah dari total pembulatan//
                var reverse = pembulatan.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
                //end membuat format rupiah dari total pembulatan//
                $('#f_bulat').val(hasil_pembulatan);
                
                //membuat format rupiah dari total total_sum_pembulatan//
                var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
                //end membuat format rupiah dari total total_sum_pembulatan//
                $('#f_total').val(hasil_total_sum_pembulatan);
            }
            ////END PERULANGAN UNTUK MENJUMLAH SUM TOTAL////
        }
    }

    function jumlah_rp(x) {
        $("input[name='diskon_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
        $("input[name='ppn_rp[]" +x+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
        var harga_jual = parseInt($("input[name='harga_jual[]" +x+ "']").val());
        var tambah_jml = parseInt($("input[name='jml[]" +x+ "']").val());
        
        var tambah_diskon_rp = ($("input[name='diskon_rp[]" +x+ "']").val());
        //menghilangka format rupiah//
        var temp_tambah_diskon_rp = tambah_diskon_rp.replace(/[.](?=.*?\.)/g, '');
        var temp_tambah_diskon_rp_jadi = parseInt(temp_tambah_diskon_rp.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//
        
        var temp_tambah_diskon_rp = Math.round((temp_tambah_diskon_rp_jadi / (harga_jual*tambah_jml))*100);
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

        var temp_tambah_ppn_rp = Math.round((temp_tambah_ppn_rp_jadi / (harga_jual*tambah_jml))*100);
        //membuat format rupiah//
        var reverse = temp_tambah_ppn_rp.toString().split('').reverse().join(''),
                        ribuan  = reverse.match(/\d{1,3}/g);
                        hasil_temp_tambah_ppn_rp = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        var tambah_biaya = ($("input[name='biaya_tambahan[]" +x+ "']").val());
        //menghilangka format rupiah tambah_biaya//
        var temp_tambah_biaya = tambah_biaya.replace(/[.](?=.*?\.)/g, '');
        var temp_tambah_biaya_jadi = parseInt(temp_tambah_biaya.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah tambah_biaya//

        var tambah_tuslah = ($("input[name='tuslah[]" +x+ "']").val());
        //menghilangka format rupiah tambah_biaya//
        var temp_tambah_tuslah = tambah_tuslah.replace(/[.](?=.*?\.)/g, '');
        var temp_tambah_tuslah_jadi = parseInt(temp_tambah_tuslah.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah tambah_biaya//

        var tambah_embalase = ($("input[name='embalase[]" +x+ "']").val());
        //menghilangka format rupiah tambah_biaya//
        var temp_tambah_embalase = tambah_embalase.replace(/[.](?=.*?\.)/g, '');
        var temp_tambah_embalase_jadi = parseInt(temp_tambah_embalase.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah tambah_biaya//

        var subtotal = parseInt(harga_jual * tambah_jml - temp_tambah_diskon_rp_jadi + temp_tambah_ppn_rp_jadi + temp_tambah_biaya_jadi+temp_tambah_tuslah_jadi+temp_tambah_embalase_jadi);
        //membuat format rupiah//
        var reverse = subtotal.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_subtotal = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        
        $('#tambah_jml_temp' + x + '').text(tambah_jml); 
        $('#tambah_diskon_rp_temp' + x + '').text(tambah_diskon_rp); 
        $("input[name='diskon[]" + x + "']").val(temp_tambah_diskon_rp);
        $('#tambah_diskon_temp' + x + '').text(temp_tambah_diskon_rp)
        $('#tambah_ppn_rp_temp' + x + '').text(temp_tambah_ppn_rp_jadi); 
        $("input[name='ppn[]" + x + "']").val(temp_tambah_ppn_rp);
        $('#tambah_ppn_temp' + x + '').text(temp_tambah_ppn_rp);
        
        $('.subtotal' + x + '').text(hasil_subtotal); 
        $('.a_subtotal' + x + '').text(hasil_subtotal); 
        
        ////PERULANGAN UNTUK MENJUMLAH SUM TOTAL////
        var table = document.getElementById("datatabel"), sumHsl = 0;
            for(var t = 1; t < table.rows.length; t++)
            {
                var sub_total = table.rows[t].cells[22].innerHTML;
                // //menghilangkan format rupiah harga//
                var sub_total_non_format = sub_total.replace(/[.](?=.*?\.)/g, '');
                var sub_total_hasil = parseInt(sub_total_non_format.replace(/[^0-9.]/g,''));
                // //End menghilangka format rupiah harga//

                sumHsl = sumHsl + parseInt(sub_total_hasil);
                //membuat format rupiah total//
                var format_sumHsl = sumHsl.toString().split('').reverse().join(''),
                        ribuan  = format_sumHsl.match(/\d{1,3}/g);
                        hasil_format_sumHsl = ribuan.join(',').split('').reverse().join('');
                //End membuat format rupiah total//
                $('#f_subtotal').val(hasil_format_sumHsl);
                
                var total_sum_pembulatan = Math.ceil(sumHsl/500)*500;
                pembulatan = total_sum_pembulatan-sumHsl;
                //membuat format rupiah dari total pembulatan//
                var reverse = pembulatan.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
                //end membuat format rupiah dari total pembulatan//
                $('#f_bulat').val(hasil_pembulatan);
                
                //membuat format rupiah dari total total_sum_pembulatan//
                var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
                //end membuat format rupiah dari total total_sum_pembulatan//
                $('#f_total').val(hasil_total_sum_pembulatan);
                
            }
    }
    
    //==== Hapus Per transaksi barang pada list tabel =================== 
    $('body').on('click','.hapus', function(x){
        //var a_subtotal = $('.subtotal' + x + '').text(); 
        // var a_subtotal = ($("input[name='ppn_rp[]" +x+ "']").text());
        // alert(a_subtotal);
        $(this).closest('tr').remove();
    })

    function hapus_data(x){
        var a_subtotal = $('.subtotal' + x + '').text(); 
        //menghilangka format rupiah tambah_diskon//
        var temp_a_subtotal = a_subtotal.replace(/[.](?=.*?\.)/g, '');
        var temp_a_subtotal_jadi = parseInt(temp_a_subtotal.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var a_f_subtotal = $('#f_subtotal').val(); 
        //menghilangka format rupiah tambah_diskon//
        var temp_a_f_subtotal = a_f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var temp_a_f_subtotal_jadi = parseInt(temp_a_f_subtotal.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var hasil_a_f_subtotal = temp_a_f_subtotal_jadi - temp_a_subtotal_jadi;

        //membuat format rupiah//
        var reverse_a_f_subtotal = hasil_a_f_subtotal.toString().split('').reverse().join(''),
            ribuan_a_f_subtotal  = reverse_a_f_subtotal.match(/\d{1,3}/g);
            hasil_a_f_subtotal_hasil = ribuan_a_f_subtotal.join(',').split('').reverse().join('');
        //End membuat format rupiah//
        
        $('#f_subtotal').val(hasil_a_f_subtotal_hasil);

        var total_sum_pembulatan = Math.ceil(hasil_a_f_subtotal/500)*500;
        //membuat format rupiah dari total total_sum_pembulatan//
        var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
        //end membuat format rupiah dari total total_sum_pembulatan//

        pembulatan = total_sum_pembulatan-hasil_a_f_subtotal;
        //membuat format rupiah dari total pembulatan//
        var reverse = pembulatan.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
        //end membuat format rupiah dari total pembulatan//

        $('#f_bulat').val(hasil_pembulatan);
        $('#f_total').val(hasil_total_sum_pembulatan);
    }
    //==== End Hapus Per transaksi barang pada list tabel ===============

    //==== Pembulatan =====================
    $("input[name='f_bulat']").keyup(function(e){
        var f_subtotal = ($("input[name='f_subtotal']").val());
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
       
        $("#f_total").val(f_hasil_pembulatan_jadi); 
    })
    //==== Pembulatan =====================

    //==== Jumlah Bayar =================== 
    $("input[name='f_jml_bayar']").keyup(function(e){
        //var f_subtotal = ($("input[name='f_subtotal']").val());
        var f_subtotal = ($("input[name='f_total']").val());
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
    })
    //==== End Jumlah Bayar =================== 

    //=== Insert data Transaksi Penjualan =================//
    $("#button_form_insert_transaksi").click(function() {
        if ($("#jenis").val() == ""){
            alert("Pilih Jenis Transaksi. Jenis Transaksi harus dipilih");
            $("#jenis").focus();
            return (false);
        }

        if ($("#jenis").val() == "Panel"){
            if ($("#no_faktur").val() == ""){
                alert("No Faktur harus diisi...");
                $("#no_faktur").focus();
                return (false);
            }
            if ($("#nama_pembeli").val() == ""){
                alert("Nama Pembeli harus diisi...");
                $("#nama_pembeli").focus();
                return (false);
            }
            if ($("#no_tlp").val() == ""){
                alert("No Telepon harus diisi...");
                $("#no_tlp").focus();
                return (false);
            }
        }

        //let kode_penjualan = $("#kode_transaksi").val();
        let tgl_penjualan = $("#tgl_transaksi").val();
        let no_faktur = $("#no_faktur").val();
        let jenis_penjualan = $("#jenis").val();
        let nama_pembeli = $("#nama_pembeli").val();
        let no_tlp = $("#no_tlp").val();
        let termin = $("#termin").val();
        let tgl_jatuh_tempo = $("#jt").val();
        let cara_bayar = $("#c_bayar").val();
        let bank = $("#c_bayar_bank").val();
        let subtotal = $("#f_subtotal").val();
        let pembulatan = $("#f_bulat").val();
        let total_bayar = $("#f_total").val();
        let jml_bayar = $("#f_jml_bayar").val();
        let kembali = $(".f_kembali").text();

        // untuk Detail //
        let kode_produk = []
        let jml_qty = []
        let harga_jual = []
        let tambah_jml = []
        let tambah_diskon = []
        let tambah_diskon_rp = []
        let tambah_ppn = []
        let tambah_ppn_rp = []
        let kode_nama_unit = []
        let biaya_tambahan = []
        let tuslah = []
        let embalase = []

        $('.kode_produk').each(function() {
            kode_produk.push($(this).text())
        })
        $('.jml_qty').each(function() {
            jml_qty.push($(this).text())
        })
        $('.harga_jual').each(function() {
            harga_jual.push($(this).text())
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
        $('.tambah_biaya_tambahan_temp').each(function() {
            biaya_tambahan.push($(this).text())
        })
        $('.tambah_tuslah_temp').each(function() {
            tuslah.push($(this).text())
        })
        $('.tambah_embalase_temp').each(function() {
            embalase.push($(this).text())
        })
        // end Detail // 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('penjualan/store') }}",
            data: {
                //kode_penjualan: kode_penjualan,
                tgl_penjualan: tgl_penjualan,
                no_faktur: no_faktur,
                jenis_penjualan: jenis_penjualan,
                nama_pembeli: nama_pembeli,
                no_tlp: no_tlp,
                termin: termin,
                tgl_jatuh_tempo: tgl_jatuh_tempo, 
                cara_bayar: cara_bayar,
                bank: bank,
                subtotal: subtotal,
                pembulatan: pembulatan,
                total_bayar: total_bayar,
                jml_bayar: jml_bayar,
                kembali: kembali,
                 // untuk Detail //
                kode_produk: kode_produk,
                jml_qty: jml_qty,
                harga_jual: harga_jual,
                tambah_jml: tambah_jml,
                tambah_diskon: tambah_diskon,
                tambah_diskon_rp: tambah_diskon_rp,
                tambah_ppn: tambah_ppn,
                tambah_ppn_rp: tambah_ppn_rp,
                kode_nama_unit: kode_nama_unit,
                biaya_tambahan: biaya_tambahan,
                tuslah: tuslah,
                embalase: embalase,
                // end Detail //
            },
            success: function(response) {
                if(response.res === true) {
                    $("#kode_transaksi").val('');
                    $("#tgl_transaksi").val('');
                    $("#jenis").val('');

                    $('.tabledata').remove('');
                    $('.cari_produk').val('');
                    $('#f_subtotal').val(0);
                    $('#f_total').val(0);
                    $('#f_jml_bayar').val(0);
                    $(".f_kembali").text(0);

                    window.location.href = "{{ route('transaksi_penjualan.create')}}";
                }else{
                    Swal.fire("Gagal!", "Data transaksi penjualan gagal disimpan.", "error");
                }
            }
        });
    });
    //=== End Insert data Transaksi Penjualan =================//

    //=== Insert data Transaksi Penjualan =================//
    $("#button_form_insert_transaksi_cetak").click(function() {
        if ($("#jenis").val() == ""){
            alert("Pilih Jenis Transaksi. Jenis Transaksi harus dipilih");
            $("#jenis").focus();
            return (false);
        }

        if ($("#jenis").val() == "Panel"){
            if ($("#no_faktur").val() == ""){
                alert("No Faktur harus diisi...");
                $("#no_faktur").focus();
                return (false);
            }
            if ($("#nama_pembeli").val() == ""){
                alert("Nama Pembeli harus diisi...");
                $("#nama_pembeli").focus();
                return (false);
            }
            if ($("#no_tlp").val() == ""){
                alert("No Telepon harus diisi...");
                $("#no_tlp").focus();
                return (false);
            }
        }

        //let kode_penjualan = $("#kode_transaksi").val();
        let tgl_penjualan = $("#tgl_transaksi").val();
        let no_faktur = $("#no_faktur").val();
        let jenis_penjualan = $("#jenis").val();
        let nama_pembeli = $("#nama_pembeli").val();
        let no_tlp = $("#no_tlp").val();
        let termin = $("#termin").val();
        let tgl_jatuh_tempo = $("#jt").val();
        let cara_bayar = $("#c_bayar").val();
        let bank = $("#c_bayar_bank").val();
        let subtotal = $("#f_subtotal").val();
        let pembulatan = $("#f_bulat").val();
        let total_bayar = $("#f_total").val();
        let jml_bayar = $("#f_jml_bayar").val();
        let kembali = $(".f_kembali").text();

        // untuk Detail //
        let kode_produk = []
        let jml_qty = []
        let harga_jual = []
        let tambah_jml = []
        let tambah_diskon = []
        let tambah_diskon_rp = []
        let tambah_ppn = []
        let tambah_ppn_rp = []
        let kode_nama_unit = []
        let biaya_tambahan = []
        let tuslah = []
        let embalase = []

        $('.kode_produk').each(function() {
            kode_produk.push($(this).text())
        })
        $('.jml_qty').each(function() {
            jml_qty.push($(this).text())
        })
        $('.harga_jual').each(function() {
            harga_jual.push($(this).text())
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
        $('.tambah_biaya_tambahan_temp').each(function() {
            biaya_tambahan.push($(this).text())
        })
        $('.tambah_tuslah_temp').each(function() {
            tuslah.push($(this).text())
        })
        $('.tambah_embalase_temp').each(function() {
            embalase.push($(this).text())
        })
        // end Detail // 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('penjualan/store') }}",
            data: {
                //kode_penjualan: kode_penjualan,
                tgl_penjualan: tgl_penjualan,
                no_faktur: no_faktur,
                jenis_penjualan: jenis_penjualan,
                nama_pembeli: nama_pembeli,
                no_tlp: no_tlp,
                termin: termin,
                tgl_jatuh_tempo: tgl_jatuh_tempo, 
                cara_bayar: cara_bayar,
                bank: bank,
                subtotal: subtotal,
                pembulatan: pembulatan,
                total_bayar: total_bayar,
                jml_bayar: jml_bayar,
                kembali: kembali,
                 // untuk Detail //
                kode_produk: kode_produk,
                jml_qty: jml_qty,
                harga_jual: harga_jual,
                tambah_jml: tambah_jml,
                tambah_diskon: tambah_diskon,
                tambah_diskon_rp: tambah_diskon_rp,
                tambah_ppn: tambah_ppn,
                tambah_ppn_rp: tambah_ppn_rp,
                kode_nama_unit: kode_nama_unit,
                biaya_tambahan: biaya_tambahan,
                tuslah: tuslah,
                embalase: embalase,
                // end Detail //
            },
            success: function(response) {
                if(response.res === true) {
                    $("#kode_transaksi").val('');
                    $("#tgl_transaksi").val('');
                    $("#jenis").val('');

                    $('.tabledata').remove('');
                    $('.cari_produk').val('');
                    $('#f_subtotal').val(0);
                    $('#f_total').val(0);
                    $('#f_jml_bayar').val(0);
                    $(".f_kembali").text(0);

                    window.location.href = "{{ route('transaksi_penjualan.create')}}";
                }else{
                    Swal.fire("Gagal!", "Data transaksi penjualan gagal disimpan.", "error");
                }
            }
        });
    });
    //=== End Insert data Transaksi Penjualan =================//

    function show_my_pdf() {
        if ($("#jenis").val() == ""){
            $("#jenis").focus();
            return (false);
        }

        kode_penjualan = $("#kode_transaksi").val();
        $.ajax({
            type: "GET",
            url: "{{ route('penjualan/pdf') }}",
            data: {
                kode_penjualan: kode_penjualan
            },
            dataType: "json",
            success: function(response) {
                
            }
        });
        window.open("{{ route('penjualan/pdf') }}?kode_penjualan=" + kode_penjualan + "", '_blank'); 
        //window.print("{{ route('penjualan/pdf') }}?kode_penjualan=" + kode_penjualan + ""); 
    }

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
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Transaksi</li>
                <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                <li class="breadcrumb-item active">Tambah Transaksi Penjualan</li>
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
                                <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Kode Transaksi</label>
                                <div class="col-sm-2">
                                    <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control"
                                        value="{{ $kode }}"
                                        style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;"
                                        required readonly>
                                </div>

                                <div class="col-sm-3"></div>

                                <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                                <div class="col-sm-3">
                                    <select name="jenis" id="jenis" class="form-select"
                                        style="height: 30px; font-size: 14px;" required>
                                        <option value="Langsung">Langsung</option>
                                        <option value="Online">Online</option>
                                        <option value="Resep Dokter">Resep Dokter</option>
                                        <option value="Panel">Panel</option>
                                    </select>
                                </div>

                                {{-- <div class="col-sm-3"></div> --}}
                            </div>
                            <div class="row mb-3 pembeli">
                                <label class="col-sm-2 col-form-label">No Faktur</label>
                                <div class="col-sm-2">
                                    <input type="text" name="no_faktur" id="no_faktur" class="form-control"
                                        style="height: 30px; font-size: 14px;">
                                </div>

                                <div class="col-sm-3"></div>

                                <label class="col-sm-2 col-form-label">Nama Pembeli</label>
                                <div class="col-sm-3">
                                    <input type="text" name="nama_pembeli" id="nama_pembeli" class="form-control"
                                        style="height: 30px; font-size: 14px;">
                                </div>
                            </div>
                            <div class="row mb-3 telepon">
                                
                                <div class="col-sm-4"></div>

                                <div class="col-sm-3"></div>

                                <label class="col-sm-2 col-form-label">No Tlp</label>
                                <div class="col-sm-3">
                                    <input type="text" name="no_tlp" id="no_tlp" class="form-control"
                                        style="height: 30px; font-size: 14px;">
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
                                            <th>Harga</th>
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
                                            <th class="head_biaya_tambahan">Biaya Tambahan</th>
                                            <th class="head_tambah_tuslah">Tuslah</th>
                                            <th class="head_tambah_embalase">Embalase</th>
                                            <th style="text-align: right;">Subtotal</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabledata" class="tabledata">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="10"></td>
                                            <td>Subtotal:</td>
                                            <td colspan="2">
                                                <input type="text"
                                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                    class="form-control" name="f_subtotal" id="f_subtotal" value="0"
                                                    readonly />
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="10"></td>
                                            <td class="bulat">Pembulatan:</td>
                                            <td colspan="2">
                                                <input type="text"
                                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                    class="form-control" name="f_bulat" id="f_bulat" value="0"
                                                    />
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr hidden>
                                            <td colspan="10"></td>
                                            <td>Total Diskon:</td>
                                            <td colspan="2">
                                                <input type="text"
                                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                    class="form-control" name="f_total_diskon" id="f_total_diskon" value="0"
                                                    readonly />
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr hidden>
                                            <td colspan="10"></td>
                                            <td>Total PPN:</td>
                                            <td colspan="2">
                                                <input type="text"
                                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                    class="form-control" name="f_total_ppn" id="f_total_ppn" value="0"
                                                    readonly />
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="10"></td>
                                            <td>Total Bayar:</td>
                                            <td colspan="2">
                                                <input type="text"
                                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                    class="form-control" name="f_total" id="f_total" value="0" readonly />
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="10"></td>
                                            <td>Cara Bayar:</td>
                                            <td colspan="2">
                                                <select name="c_bayar" id="c_bayar" class="form-select"
                                                    style="height: 30px; font-size: 13px;" required>
                                                    <option value="">Pilih...</option>
                                                    <option value="Tunai">Tunai</option>
                                                    <option value="Debit">Debit</option>
                                                    <option value="Kredit">Kredit</option>
                                                </select>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="footer_termin">
                                            <td colspan="10"></td>
                                            <td>Termin:</td>
                                            <td colspan="2">
                                                <div class="input-group">
                                                    <input type="text" name="termin" id="termin" class="form-control" value="0" style="height: 30px; font-size: 14px; text-align: center;">
                                                    <span class="input-group-text" id="inputGroupPrepend" style="height: 30px;">Hari</span>
                                                  </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="footer_jatuh_tempo">
                                            <td colspan="10"></td>
                                            <td>Jatuh Tempo:</td>
                                            <td colspan="2">
                                                <input type="text" name="jt" id="jt" class="form-control" value="{{ date('d/m/Y', strtotime(Carbon\Carbon::today()->toDateString())) }}" style="height: 30px; font-size: 14px; text-align: center;" required readonly>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="footer_bank">
                                            <td colspan="10" class="1"></td>
                                            <td class="2">Bank:</td>
                                            <td colspan="2" class="3">
                                                <select name="c_bayar_bank" id="c_bayar_bank" class="form-select f_footer_bank"
                                                    style="height: 30px; font-size: 13px;">
                                                    <option value="">Pilih...</option>
                                                    <option value="BCA">BCA</option>
                                                    <option value="BNI">BNI</option>
                                                    <option value="MANDIRI">MANDIRI</option>
                                                </select>
                                            </td>
                                            <td class="4"></td>
                                        </tr>
                                        <tr class="footer_bayar">
                                            <td colspan="10" class="5"></td>
                                            <td class="bayar">Jml Bayar:</td>
                                            <td colspan="2" class="6">
                                                <input type="text"
                                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                                    class="form-control f_footer_bayar" name="f_jml_bayar" id="f_jml_bayar" value="0"
                                                    required />
                                            </td>
                                            <td class="7"></td>
                                        </tr>
                                        <tr class="footer_kembali">
                                            <td colspan="10" class="8"></td>
                                            <td class="sisa">Kembali:</td>
                                            <td colspan="2" class="f_kembali" align="right">
                                                0
                                            </td>
                                            <td class="9"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="10"></td>
                                            
                                            <td colspan="3">
                                                <div class="row mb-3">
                                                    <div class="col-sm-5">
                                                        <button type="button" class="btn btn-success btn-sm" style="width: 100%;" id="button_form_insert_transaksi">Simpan</button>
                                                    </div>
                                                    <div class="col-sm-1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <button type="button" class="btn btn-success btn-sm" style="width: 100%;" id="button_form_insert_transaksi_cetak" onclick="show_my_pdf()">Simpan & Cetak</button>
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="get">
                        <div class="input-group mb-3 col-md-6 right">
                            <input type="text" name="search" id="search" class="form-control" placeholder=" Cari Produk...">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


</main>
@endsection
