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
    url: "{{ route('getDataAntrianPeriksa') }}",
    dataType: "json",
    success: function(response) {
      let tabledata;
      response.data.forEach(antrian => {
        tabledata += `<tr>`;
        tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
        tabledata += `<td>${antrian.kode_kunjungan}</td>`;
        tabledata += `<td>${antrian.no_rm}</td>`;
        tabledata += `<td>${antrian.nama_pasien}</td>`;
        tabledata += `<td>${antrian.jk}</td>`;
        tabledata += `<td>${antrian.umur} Thn</td>`;
          if (antrian.status_periksa == 0) {
              tabledata += `<td align="center"><span class="badge bg-secondary"><i class="bi bi-collection me-1"></i> Baru</span></td>`;
          } else {
              tabledata += `<td align="center"><span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Selesai</span></td>`;
          }
        tabledata += `<td hidden>${antrian.id_user_input}</td>`;
        tabledata += `<td hidden>${antrian.name}</td>`;
        tabledata += `<td hidden>${antrian.kode_cabang}</td>`;
        tabledata += `<td hidden>${antrian.nama_cabang}</td>`;
        //tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_panggil" class="btn btn-primary btn-sm">Panggil</button></td>`;
        if (antrian.status_periksa == 0) {
          tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_panggil" class="btn btn-primary btn-sm">Panggil</button></td>`;
        } else {
          tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_panggil" class="btn btn-primary btn-sm" disabled>Panggil</button></td>`;
        }
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
      url: "{{ route('pemeriksaan/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        response.data.forEach(antrian => {
          tabledata += `<tr>`;
          tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
          tabledata += `<td>${antrian.kode_kunjungan}</td>`;
          tabledata += `<td>${antrian.no_rm}</td>`;
          tabledata += `<td>${antrian.nama_pasien}</td>`;
          tabledata += `<td>${antrian.jk}</td>`;
          tabledata += `<td>${antrian.umur} Thn</td>`;
            if (antrian.status_periksa == 0) {
                tabledata += `<td align="center"><span class="badge bg-secondary"><i class="bi bi-collection me-1"></i> Baru</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Selesai</span></td>`;
            }
          tabledata += `<td hidden>${antrian.id_user_input}</td>`;
          tabledata += `<td hidden>${antrian.name}</td>`;
          tabledata += `<td hidden>${antrian.kode_cabang}</td>`;
          tabledata += `<td hidden>${antrian.nama_cabang}</td>`;
          //tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_panggil" class="btn btn-primary btn-sm">Panggil</button></td>`;
          if (antrian.status_periksa == 0) {
            tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_panggil" class="btn btn-primary btn-sm">Panggil</button></td>`;
          } else {
            tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_panggil" class="btn btn-primary btn-sm" disabled>Panggil</button></td>`;
          }
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//


$(document).on("click", "#button_panggil", function(e) {
  e.preventDefault();
        let kode_kunjungan = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "{{ route('getDataAntrianPeriksaDetail') }}",
            data: {
                kode_kunjungan: kode_kunjungan
            },
            dataType: "json",
            success: function(response) {
                $('#kode_kunjungan').val(kode_kunjungan);
                $('#norm').val(response.data.no_rm);
                $('#nama_pasien').val(response.data.nama_pasien);
                $('#umur').val(response.data.umur + ' thn')
                // $('#update_atas_nama').val(response.data.atas_nama);
                // $('#update_bank').val(response.data.bank);
            }
        });
        $('#fullscreenModal').modal('show');
});

    //===Menampilkan Modal Diagnosa==========//
    fetch_diagnosa_data();
    function fetch_diagnosa_data(){
      $.ajax({
        type: "GET",
        url: "{{ route('pemeriksaan/getSubKatDiagnosaModal.getSubKatDiagnosaModal') }}",
        dataType: "json",
        success: function(response) {
          let tabledataModalDiagnosa;
          response.data.forEach(daftarDiagnosa => {
            tabledataModalDiagnosa += `<tr>`;
              tabledataModalDiagnosa += `<tr class="pilihDiagnosa" data-kode_diagnosa="${daftarDiagnosa.id_sub_kategori}" data-nama_diagnosa="${daftarDiagnosa.nama_subkategori_penyakit_eng}">`;
              tabledataModalDiagnosa += `<td>${daftarDiagnosa.id_sub_kategori}</td>`;
              tabledataModalDiagnosa += `<td>${daftarDiagnosa.nama_subkategori_penyakit_eng}</td>`;
              tabledataModalDiagnosa += `<td hidden>${daftarDiagnosa.nama_subkategori_penyakit_ind}</td>`;
            tabledataModalDiagnosa += `</tr>`;
          });
          $("#tabledataModalDiagnosa").html(tabledataModalDiagnosa);
        }
      });
    }
    //===End Menampilkan Modal Diagnosa==========//

    //===Pencarian Modal data Diagnosa====//
    $("#searchDiagnosa").keyup(function() {
        let value = $("#searchDiagnosa").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('pemeriksaan/getSubKatDiagnosaModal.getSubKatDiagnosaModal') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModalDiagnosa;
                    response.data.forEach(daftarDiagnosa => {
                        tabledataModalDiagnosa += `<tr>`;
                        tabledataModalDiagnosa += `<tr class="pilihDiagnosa" data-kode_diagnosa="${daftarDiagnosa.id_sub_kategori}" data-nama_diagnosa="${daftarDiagnosa.nama_subkategori_penyakit_eng}">`;
                        tabledataModalDiagnosa += `<td>${daftarDiagnosa.id_sub_kategori}</td>`;
                        tabledataModalDiagnosa += `<td>${daftarDiagnosa.nama_subkategori_penyakit_eng}</td>`;
                        tabledataModalDiagnosa += `<td>${daftarDiagnosa.nama_subkategori_penyakit_ind}</td>`;
                        tabledataModalDiagnosa += `</tr>`;
                    });
                    $("#tabledataModalDiagnosa").html(tabledataModalDiagnosa);
                }
            });
        }else{
          fetch_diagnosa_data();
        }
    });
    //===End Pencarian Modal data Diagnosa====//

    var x = 1;
    $(document).on('click', '.pilihDiagnosa', function (e) {
      e.preventDefault();
      var isi = '';

      var kode_diagnosa = $(this).attr('data-kode_diagnosa');
      var nama_diagnosa = $(this).attr('data-nama_diagnosa');

      isi += '<tr>';
        isi += '<td class="kode_diagnosa" id="kode_diagnosa' + x + '">' +kode_diagnosa+ '</td>';
        isi += '<td class="nama_diagnosa" id="nama_diagnosa' + x + '">' +nama_diagnosa+ '</td>';
        isi += '<td align="center">';
          isi += '<i onclick="delete_item(this)" align="center" style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus_tindakan"></i>';
        isi += '</td>';
      isi += '<tr>';
      
      $('#modalCariDiagnosa').modal('hide');
      $('#fullscreenModal').modal('show');

      $('.datatabel_data_diagnosa').append(isi);
      x++;
    });

    //===Menampilkan Modal data Tindakan====//
    fetch_tindakan_data();
    function fetch_tindakan_data() {
      $.ajax({
            type: "GET",
            url: "{{ route('pemeriksaan/getTindakanModal.getTindakanModal') }}",
            dataType: "json",
            success: function(response) {
                let tabledataModalTindakan;
                response.data.forEach(daftarTindakan => {
                  tabledataModalTindakan += `<tr>`;
                  tabledataModalTindakan += `<tr class="pilihTindakan" data-kode_tindakan="${daftarTindakan.kode_jasa_p}" data-nama_tindakan="${daftarTindakan.nama_jasa_p}" data-harga="${daftarTindakan.harga}">`;
                  tabledataModalTindakan += `<td>${daftarTindakan.kode_jasa_p}</td>`;
                  tabledataModalTindakan += `<td>${daftarTindakan.nama_jasa_p}</td>`;
                  tabledataModalTindakan += `<td hidden>${daftarTindakan.harga}</td>`;
                  tabledataModalTindakan += `</tr>`;
                });
                $("#tabledataModalTindakan").html(tabledataModalTindakan);
            }
        });
    }
    //===End Menampilkan Modal data Tindakan====//

    //===Pencarian Modal data tindakank====//
    $("#searchTindakan").keyup(function() {
        let value = $("#searchTindakan").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('pemeriksaan/getTindakanModal.getTindakanModal') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModalTindakan;
                    response.data.forEach(daftarTindakan => {
                        tabledataModalTindakan += `<tr>`;
                        tabledataModalTindakan += `<tr class="pilihTindakan" data-kode_tindakan="${daftarTindakan.kode_jasa_p}" data-nama_tindakan="${daftarTindakan.nama_jasa_p}" data-harga="${daftarTindakan.harga}">`;
                        tabledataModalTindakan += `<td>${daftarTindakan.kode_jasa_p}</td>`;
                        tabledataModalTindakan += `<td>${daftarTindakan.nama_jasa_p}</td>`;
                        tabledataModalTindakan += `<td hidden>${daftarTindakan.harga}</td>`;
                        tabledataModalTindakan += `</tr>`;
                    });
                    $("#tabledataModalTindakan").html(tabledataModalTindakan);
                }
            });
        }else{
          fetch_tindakan_data();
        }
    });
    //===End Pencarian Modal data tindakan====//
    
    var x = 1;
    $(document).on('click', '.pilihTindakan', function (e) {
      e.preventDefault();
      var isi = '';

      var kode_tindakan = $(this).attr('data-kode_tindakan');
      var nama_tindakan = $(this).attr('data-nama_tindakan');;
      var harga = $(this).attr('data-harga');

      //membuat format rupiah//
      var reverse = harga.toString().split('').reverse().join(''),
        ribuan  = reverse.match(/\d{1,3}/g);
        hasil_harga = ribuan.join(',').split('').reverse().join('');
      //End membuat format rupiah//

      isi += '<tr>';
        isi += '<td class="kode_tindakan" id="kode_tindakan' + x + '">' +kode_tindakan+ '</td>';
        isi += '<td class="nama_tindakan" id="nama_tindakan' + x + '">' +nama_tindakan+ '</td>';
        isi += '<td class="harga" id="harga' + x + '">' +hasil_harga+ '</td>';
        isi += '<td align="center">';
          isi += '<i onclick="delete_item(this)" align="center" style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus_tindakan"></i>';
        isi += '</td>';
      isi += '<tr>';
      
      $('#modalCariTindakan').modal('hide');
      $('#fullscreenModal').modal('show');

      $('.datatabel_tindakan').append(isi);
      x++;
    });

    //===Menampilkan Modal data produk====//
    fetch_product_data();
    function fetch_product_data() {
        $.ajax({
            type: "GET",
            url: "{{ route('pemeriksaan/getProdukModal.getProdukModal') }}",
            dataType: "json",
            success: function(response) {
                let tabledataModalObat;
                response.data.forEach(daftar => {
                    tabledataModalObat += `<tr class="pilih" data-kode_produk="${daftar.kode_produk}" data-nama_produk="${daftar.nama_produk}" data-nama_kategori="${daftar.nama_kategori}" data-nama_jenis="${daftar.nama_jenis}" data-kemasan="${daftar.nama_unit}" data-harga="${daftar.harga_jual}" data-qty="${daftar.qty}" data-harga_jual="${daftar.harga_jual}" data-id_produk_unit="${daftar.id_produk_unit}">`;
                    tabledataModalObat += `<td>${daftar.kode_produk}</td>`;
                    tabledataModalObat += `<td>${daftar.nama_produk}</td>`;
                    tabledataModalObat += `<td>${daftar.komposisi}</td>`;
                    tabledataModalObat += `<td>${daftar.nama_jenis}</td>`;
                    //membuat format rupiah dari total total_sum_pembulatan//
                    var harga_jual = daftar.harga_jual;
                    var reverse = harga_jual.toString().split('').reverse().join(''),
                    ribuan  = reverse.match(/\d{1,3}/g);
                    hasil_harga_jual = ribuan.join(',').split('').reverse().join('');
                    //end membuat format rupiah dari total total_sum_pembulatan//
                    tabledataModalObat += `<td align="right">${hasil_harga_jual}</td>`;
                    tabledataModalObat += `<td>${daftar.nama_unit}</td>`;
                    tabledataModalObat += `<td>${daftar.qty}</td>`;
                    tabledataModalObat += `<td hidden>${daftar.id_produk_unit}</td>`;
                    tabledataModalObat += `</tr>`;
                });
                $("#tabledataModalObat").html(tabledataModalObat);
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
                url: "{{ route('pemeriksaan/getProdukModal.getProdukModal') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledataModalObat;
                    response.data.forEach(daftar => {
                      tabledataModalObat += `<tr class="pilih" data-kode_produk="${daftar.kode_produk}" data-nama_produk="${daftar.nama_produk}" data-nama_kategori="${daftar.nama_kategori}" data-nama_jenis="${daftar.nama_jenis}" data-kemasan="${daftar.nama_unit}" data-harga="${daftar.harga_jual}" data-qty="${daftar.qty}" data-harga_jual="${daftar.harga_jual}" data-id_produk_unit="${daftar.id_produk_unit}">`;
                        tabledataModalObat += `<td>${daftar.kode_produk}</td>`;
                        tabledataModalObat += `<td>${daftar.nama_produk}</td>`;
                        tabledataModalObat += `<td>${daftar.komposisi}</td>`;
                        tabledataModalObat += `<td>${daftar.nama_jenis}</td>`;
                        //membuat format rupiah dari total total_sum_pembulatan//
                        var harga_jual = daftar.harga_jual;
                        var reverse = harga_jual.toString().split('').reverse().join(''),
                        ribuan  = reverse.match(/\d{1,3}/g);
                        hasil_harga_jual = ribuan.join(',').split('').reverse().join('');
                        //end membuat format rupiah dari total total_sum_pembulatan//
                        tabledataModalObat += `<td align="right">${hasil_harga_jual}</td>`;
                        tabledataModalObat += `<td>${daftar.nama_unit}</td>`;
                        tabledataModalObat += `<td>${daftar.qty}</td>`;
                        tabledataModalObat += `<td hidden>${daftar.id_produk_unit}</td>`;
                        tabledataModalObat += `</tr>`;
                    });
                    $("#tabledataModalObat").html(tabledataModalObat);
                }
            });
        }else{
            fetch_product_data();
        }
    });
    //===End Pencarian Modal data produk====//

    var x = 1;
    $(document).on('click', '.pilih', function (e) {
      e.preventDefault();
      var isi = '';

      var kode_produk = $(this).attr('data-kode_produk');
      var nama_produk = $(this).attr('data-nama_produk');
      var qty = $(this).attr('data-qty');
      var nama_unit = $(this).attr('data-kemasan');
      var harga_jual = $(this).attr('data-harga_jual');
      var id_produk_unit = $(this).attr('data-id_produk_unit');

      isi += '<tr>';
        isi += '<td class="kode_produk" id="kode_produk' + x + '">' +kode_produk+ '</td>';
        isi += '<td class="nama_produk" id="nama_produk' + x + '">' +nama_produk+ '</td>';
        isi += '<td class="stok" id="stok' + x + '">' +qty+ '</td>';
        isi += '<td class="nama_unit" id="nama_unit' + x + '">' +nama_unit+ '</td>';
        isi += '<td class="id_produk_unit" id="id_produk_unit' + x + '" hidden>' +id_produk_unit+ '</td>';
        isi += '<td class="tambah_jml">';
          isi += '<input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml[]' + x +'" id="jml[]' + x +'" onclick="mirror(' + x + ');" onkeyup="mirror(' + x + ');" value="0">';
        isi += '</td>';
        isi += '<td class="tambah_jml_temp" id="tambah_jml_temp' + x +'" contenteditable="true" hidden>';
          isi += 0;
        isi += '</td>';
        isi += '<td class="harga_jual" id="harga_jual' + x + '" hidden>' +harga_jual+ '</td>';
        isi += '<td class="aturan">';
          isi += '<input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="aturan[]' + x +'" id="aturan[]' + x +'" onclick="mirror(' + x + ');" onkeyup="mirror(' + x + ');" value="-">';
        isi += '</td>';
        isi += '<td class="aturan_temp" id="aturan_temp' + x +'" contenteditable="true" hidden>';
          isi += "-";
        isi += '</td>';
        isi += '<td align="center">';
          isi += '<i onclick="delete_item(this)" align="center" style="color:#c00;" class="bi bi-x-square-fill bi-2x hapus_tindakan"></i>';
        isi += '</td>';
      isi += '<tr>';
      
      $('#modalCariObat').modal('hide');
      $('#fullscreenModal').modal('show');

      $('.datatabel').append(isi);
      x++;
    });

    function mirror(x){
      var jml = $("input[name='jml[]" +x+ "']").val();
      $('#tambah_jml_temp' + x + '').text(jml);

      var aturan = $("input[name='aturan[]" +x+ "']").val();
      $('#aturan_temp' + x + '').text(aturan);
    }

    $(document).on('click', '.tutupModalObat', function (e){
      $('#fullscreenModal').modal('show');
    });

    $(document).on('click', '.tutupModalTindakan', function (e){
      $('#fullscreenModal').modal('show');
    });

    $('body').on('click','.hapus_obat', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    })

    $('body').on('click','.hapus_tindakan', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    })


$("#button_form_insert").click(function(e) {
  e.preventDefault();

  // if ($("#jenis").val() == ""){
  //   alert("Pilih Jenis Transaksi. Jenis Transaksi harus dipilih");
  //   $("#jenis").focus();
  //   return (false);
  // }

  let kode_kunjungan = $("#kode_kunjungan").val();
  let no_rm = $("#norm").val();

  let keluhan_utama = $("#keluhan").val();
  let riwayat_penyakit = $("#riwayat_penyakit").val();
  let riwayat_alergi = $("#riwayat_alergi").val();
  let riwayat_pengobatan = $("#riwayat_pengobatan").val();
  let t_badan = $("#tb").val();
  let b_badan = $("#bb").val();
  let t_darah = $("#td").val();
  let suhu = $("#suhu").val();
  let denyut_jantung = $("#jantung").val();
  let pernapasan = $("#pernapasan").val();
  let penglihatan = $("#penglihatan").val();
  let catatan = $("#catatan").val();

  let kode_diagnosa = []

  $('.kode_diagnosa').each(function() {
    kode_diagnosa.push($(this).text())
  })
 
  let kode_tindakan = []
  let harga = []

  $('.kode_tindakan').each(function() {
    kode_tindakan.push($(this).text())
  })
  $('.harga').each(function() {
    harga.push($(this).text())
  })

  let kode_produk = []
  let qty = []
  let harga_jual = []
  let aturan = []
  let id_produk_unit = []

  $('.kode_produk').each(function() {
    kode_produk.push($(this).text())
  })
  $('.tambah_jml_temp').each(function() {
    qty.push($(this).text())
  })
  $('.harga_jual').each(function() {
    harga_jual.push($(this).text())
  })
  $('.aturan_temp').each(function() {
    aturan.push($(this).text())
  })
  $('.id_produk_unit').each(function() {
    id_produk_unit.push($(this).text())
  })

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    type: "POST",
    url: "{{ route('pemeriksaan/store') }}",
    data: {
      kode_kunjungan: kode_kunjungan,
      no_rm: no_rm,

      keluhan_utama: keluhan_utama,
      riwayat_penyakit: riwayat_penyakit,
      riwayat_alergi: riwayat_alergi,
      riwayat_pengobatan: riwayat_pengobatan,
      t_badan: t_badan,
      b_badan: b_badan,
      t_darah: t_darah,
      suhu: suhu,
      denyut_jantung: denyut_jantung,
      pernapasan: pernapasan,
      penglihatan: penglihatan,
      catatan: catatan,

      kode_diagnosa: kode_diagnosa,

      kode_tindakan: kode_tindakan,
      harga: harga,

      kode_produk: kode_produk,
      qty:qty,
      harga_jual: harga_jual,
      aturan: aturan,
      id_produk_unit: id_produk_unit,
    },
    success: function(response) {
      if(response.res === true) {
        window.location.href = "{{ route('pemeriksaan.index')}}";
      }else{
        Swal.fire("Gagal!", "Data pemeriksaan gagal disimpan.", "error");
      }
    }
  });

});
//=== End Insert dan Update data Pemeriksaan =================//
</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Pemeriksaan</title>
@endsection

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="d-flex justify-content-between align-items-center">
      Pemeriksaan
    </h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pemeriksaan</li>
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
                            <th>Kode Antrian</th>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Jk</th>
                            <th>Umur</th>
                            <th>Status Periksa</th>
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

                <div class="modal fade" id="fullscreenModal" tabindex="-1">
                  <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Data Pemeriksaan Pasien</h5>
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
                                <div class="col-sm-4">
                                  <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" value="" required readonly>
                                </div>
              
                                <label class="col-sm-1 col-form-label" align="right">Umur</label>
                                <div class="col-sm-1">
                                  <input type="text" name="umur" id="umur" class="form-control" style="text-align: center;" value="" required readonly>
                                </div>
              
                                {{-- <label class="col-sm-1 col-form-label" align="right">G. Darah</label>
                                <div class="col-sm-1">
                                  <input type="text" name="gol_darah" id="gol_darah" class="form-control" style="text-align: center;" value="" required readonly>
                                </div> --}}
                              </div>
              
                              <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" align="right"><b>Pemeriksaan :</b></label>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">Keluhan Utama</label>
                                <div class="col-sm-8">
                                  <input type="text" name="keluhan" id="keluhan" class="form-control" style="width: 100%; height: 28px; font-size: 14px;" value="" required>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">Riwayat Penyakit</label>
                                <div class="col-sm-8">
                                  <input type="text" name="riwayat_penyakit" id="riwayat_penyakit" class="form-control" style="width: 100%; height: 28px; font-size: 14px;" value="" required>
                                </div>
                              </div>
                              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">Riwayat Alergi</label>
                                <div class="col-sm-8">
                                  <input type="text" name="riwayat_alergi" id="riwayat_alergi" class="form-control" style="width: 100%; height: 28px; font-size: 14px;" value="" required>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">Riwayat Pengobatan</label>
                                <div class="col-sm-8">
                                  <input type="text" name="riwayat_pengobatan" id="riwayat_pengobatan" class="form-control" style="width: 100%; height: 28px; font-size: 14px;" value="" required>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">T. Badan</label>
                                <div class="col-sm-1">
                                  <input type="number" name="tb" id="tb" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required>
                                </div>
              
                                <label class="col-sm-1 col-form-label" style="margin-top: -6px;" align="right">B. Badan</label>
                                <div class="col-sm-1">
                                  <input type="number" name="bb" id="bb" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required>
                                </div>
                                
                                <label class="col-sm-1 col-form-label" style="margin-top: -6px;" align="right">T. Darah</label>
                                <div class="col-sm-1">
                                  <input type="text" name="td" id="td" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required>
                                </div>
              
                                <label class="col-sm-1 col-form-label" style="margin-top: -6px;" align="right">Suhu</label>
                                <div class="col-sm-1">
                                  <input type="text" name="suhu" id="suhu" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">Denyut Jantung</label>
                                <div class="col-sm-3">
                                  <input type="text" name="jantung" id="jantung" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required>
                                </div>
                                
                                <label class="col-sm-1 col-form-label" style="margin-top: -6px;" align="right">Pernapasan</label>
                                <div class="col-sm-3">
                                  <input type="text" name="pernapasan" id="pernapasan" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required>
                                </div>
                              </div>

                              <div class="row mb-3">
                                <div class="col-sm-1">
                                </div>
                                <label class="col-sm-1 col-form-label" style="margin-top: -6px;" align="right">Pemeriksaan Fisik</label>
                                <div class="col-sm-8">
                                  {{-- <input type="text" name="penglihatan" id="penglihatan" class="form-control" style="width: 100%; height: 28px; font-size: 14px; text-align: center;" value="" required> --}}
                                  <textarea class="form-control" style="height: 100px" name="penglihatan" id="penglihatan"></textarea>
                                </div>
                              </div>
              
                              <div class="row mb-3">
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-8">
                                  <table id="datatabel_data_diagnosa" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th>Kode ICD</th>
                                            <th>Nama Diagnosa</th>
                                            <td align="center">
                                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCariDiagnosa" style="height: 28px; font-size: 14px; align: center;"><i class="bi bi-plus"></i></button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody id="datatabel_data_diagnosa" class="datatabel_data_diagnosa">
                  
                                    </tbody>
                                  </table>
                                </div>
                              </div>
              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" style="margin-top: -6px;" align="right">Catatan</label>
                                <div class="col-sm-8">
                                  <input type="text" name="catatan" id="catatan" class="form-control" style="width: 100%; height: 28px; font-size: 14px;" value="" required>
                                </div>
                              </div>

                              <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" align="right"><b>Tindakan :</b></label>
                              </div>

                              <div class="row mb-3">
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-8">
                                  <table id="datatabel_tindakan" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th>Kode Tindakan</th>
                                            <th>Nama Tindakan</th>
                                            <th>Harga</th>
                                            <td align="center">
                                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCariTindakan" style="height: 28px; font-size: 14px; align: center;"><i class="bi bi-plus"></i></button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody id="datatabel_tindakan" class="datatabel_tindakan">
                  
                                    </tbody>
                                  </table>
                                </div>
                              </div>
              
                              <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" align="right"><b>Resep Obat :</b></label>
                              </div>
              
                              <div class="row mb-3">
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-8">
                                  <table id="datatabel" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th>Kode Obat</th>
                                            <th>Nama Obat</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Jml</th>
                                            <th>Aturan</th>
                                            <td align="center">
                                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCariObat" style="height: 28px; font-size: 14px; align: center;"><i class="bi bi-plus"></i></button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody id="datatabel" class="datatabel">
                  
                                    </tbody>
                                  </table>
                                </div>
                              </div>
              
                              <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
              
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="button_form_insert" data-dismiss="modal"><i class="bi bi-save"></i> Simpan</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="modalCariDiagnosa" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h3 class="modal-title">Daftar Diagnosa ICD 10</h3>
                        <button type="button"  class="btn-close tutupModalTindakan" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="#" method="get">
                          <div class="input-group mb-3 col-md-6 right">
                            <input type="text" name="searchDiagnosa" id="searchDiagnosa" class="form-control" placeholder="Cari Diagnosa . . .">
                          </div>
                        </form>
                        <table id="lookup" class="table table-bordered table-hover table-striped">
                          <thead>
                            <tr>
                              <th>Kode ICD</th>
                              <th>Nama Diagnosa</th>
                              <th hidden>Nama Diagnosa (Ind)</th>
                            </tr>
                          </thead>
                          <tbody id="tabledataModalDiagnosa" data-dismiss="modal">
                                
                          </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger tutupModalTindakan" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="modalCariTindakan" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h3 class="modal-title">Data Tindakan</h3>
                        <button type="button"  class="btn-close tutupModalTindakan" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="#" method="get">
                          <div class="input-group mb-3 col-md-6 right">
                            <input type="text" name="searchTindakan" id="searchTindakan" class="form-control" placeholder="Cari tindakan . . .">
                          </div>
                        </form>
                        <table id="lookup" class="table table-bordered table-hover table-striped">
                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Tindakan</th>
                              <th hidden>Harga</th>
                            </tr>
                          </thead>
                          <tbody id="tabledataModalTindakan" data-dismiss="modal">
                                
                          </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger tutupModalTindakan" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="modalCariObat" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h3 class="modal-title">Data Obat</h3>
                        <button type="button" class="btn-close tutupModalObat" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="#" method="get">
                          <div class="input-group mb-3 col-md-6 right">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Cari Produk...">
                          </div>
                        </form>
                        <table id="lookup" class="table table-bordered table-hover table-striped">
                          <thead>
                            <tr>
                              <th>Kode Produk</th>
                              <th>Nama Produk</th>
                              <th>komposisi</th>
                              <th>Jenis</th>
                              <th>Harga</th>
                              <th>Kemasan</th>
                              <th>Jml</th>
                            </tr>
                          </thead>
                          <tbody id="tabledataModalObat" data-dismiss="modal">
                                
                          </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger tutupModalObat" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
      </div>
    </div>
  </section>

</main>

@endsection


@section('js')


    
@endsection()
