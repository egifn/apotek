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
  $(function(){
    $('#provinsi').change(function(){
        var provinsi = $(this).val();
        if(provinsi){
            $.ajax({
                type:"GET",
                url:"city?provinsi="+provinsi,
                dataType:'JSON',
                success: function(res){
                    if(res){
                        $("#kab_kota").empty();
                        $("#kab_kota").append('<option value="">Pilih...</option>');
                        $.each(res,function(kode,nama){
                            $("#kab_kota").append('<option value="'+kode+'">'+nama+'</option>');
                        });
                    }else{
                        $("#kab_kota").empty();
                    }
                }
            });
        }else{
            $("#kab_kota").empty();
        }
    });
  });

  $(function(){
    $('#kab_kota').change(function(){
        var kab_kota = $(this).val();
        if(kab_kota){
            $.ajax({
                type:"GET",
                url:"district?kab_kota="+kab_kota,
                dataType:'JSON',
                success: function(res){
                    if(res){
                        $("#kecamatan").empty();
                        $("#kecamatan").append('<option value="">Pilih...</option>');
                        $.each(res,function(nama,kode){
                            $("#kecamatan").append('<option value="'+kode+'">'+nama+'</option>');
                        });
                    }else{
                        $("#kecamatan").empty();
                    }
                }
            });
        }else{
            $("#kecamatan").empty();
        }
    });
  });

  //=== Select data Pendaftaran ====//
  fetchAllPendaftaran();
  function fetchAllPendaftaran() {
    let value = $("#cari").val();
    $.ajax({
      type: "GET",
      url: "{{ route('getDataPendaftaran') }}",
      data: {
          value: value
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        response.data.forEach(daftar => {
          tabledata += `<tr>`;
          tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
          tabledata += `<td>${daftar.no_rm}</td>`;
          tabledata += `<td>${daftar.nama_pasien}</td>`;
          tabledata += `<td>${daftar.jk}</td>`;
          tabledata += `<td>${daftar.umur} Thn</td>`;
          tabledata += `<td>${daftar.tlp}</td>`;
          tabledata += `<td>${daftar.jenis_pasien}</td>`;
          tabledata += `<td>${daftar.tgl_daftar}</td>`;
          tabledata += `<td hidden>${daftar.id_user_input}</td>`;
          tabledata += `<td>${daftar.name}</td>`;
          tabledata += `<td hidden>${daftar.kode_cabang}</td>`;
          tabledata += `<td hidden>${daftar.nama_cabang}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${daftar.no_rm}" id="button_edit" class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></button>`;
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
    let value = $("#cari").val();
    $.ajax({
      type: "GET",
      url: "{{ route('pendaftaran/cari.cari') }}",
      data: {
          tgl_cari: tgl_cari,
          value: value
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        response.data.forEach(daftar => {
          tabledata += `<tr>`;
          tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
          tabledata += `<td>${daftar.no_rm}</td>`;
          tabledata += `<td>${daftar.nama_pasien}</td>`;
          tabledata += `<td>${daftar.jk}</td>`;
          tabledata += `<td>${daftar.umur} Thn</td>`;
          tabledata += `<td>${daftar.tlp}</td>`;
          tabledata += `<td>${daftar.jenis_pasien}</td>`;
          tabledata += `<td>${daftar.tgl_daftar}</td>`;
          tabledata += `<td hidden>${daftar.id_user_input}</td>`;
          tabledata += `<td>${daftar.name}</td>`;
          tabledata += `<td hidden>${daftar.kode_cabang}</td>`;
          tabledata += `<td hidden>${daftar.nama_cabang}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${daftar.no_rm}" id="button_edit" class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//

  //=== SEARCH Select data unit/kategori====//
  $("#cari").keyup(function() {
      let tgl_cari = $("#tanggal").val();
      let value = $("#cari").val();
      if (this.value.length >= 2) {
        $.ajax({
          type: "GET",
          url: "{{ route('pendaftaran/cari.cari') }}",
          data: {
              tgl_cari: tgl_cari,
              value: value
          },
          dataType: "json",
          success: function(response) {
            let tabledata;
            response.data.forEach(daftar => {
              tabledata += `<tr>`;
              tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
              tabledata += `<td>${daftar.no_rm}</td>`;
              tabledata += `<td>${daftar.nama_pasien}</td>`;
              tabledata += `<td>${daftar.jk}</td>`;
              tabledata += `<td>${daftar.umur} Thn</td>`;
              tabledata += `<td>${daftar.tlp}</td>`;
              tabledata += `<td>${daftar.jenis_pasien}</td>`;
              tabledata += `<td>${daftar.tgl_daftar}</td>`;
              tabledata += `<td hidden>${daftar.id_user_input}</td>`;
              tabledata += `<td>${daftar.name}</td>`;
              tabledata += `<td hidden>${daftar.kode_cabang}</td>`;
              tabledata += `<td hidden>${daftar.nama_cabang}</td>`;
              tabledata += `<td align="center"><button type="button" data-id="${daftar.no_rm}" id="button_edit" class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></button></td>`;
              tabledata += `</tr>`;
            });
            $("#tabledata").html(tabledata);
          }
        });
      }else{
        fetchAllPendaftaran();
      }
    });
    //=== End SEARCH Select data unit/kategori====//

  //=== Insert data Pendaftaran =================//
  $("#button_form_insert_pendaftaran").click(function() {
    // let no_rm = $("#kode_transaksi").val();
    let tgl_daftar = $("#tgl_daftar").val();
    // let waktu = $("#jam").val();
    let nik = $("#nik").val();
    let nama_pasien = $("#nama_pasien").val();
    let tempat_lahir = $("#tempat").val();
    let tgl_lahir = $("#tgl_lahir").val();
    let umur = $("#umur").val();
    let jk = $("#jenis_kelamin").val();
    let alamat = $("#alamat").val();
    let id_provinsi = $("#provinsi").val();
    let id_kab_kota = $("#kab_kota").val();
    let id_kecamatan = $("#kecamatan").val();
    let status_perkawinan = $("#status_perkawinan").val();
    let pekerjaan = $("#pekerjaan").val();
    let tlp = $("#tlp").val();
    let jenis_pasien = $("#jenis_pasien").val();
    let nama_asuransi = $("#nama_asuransi").val();
    let no_asuransi = $("#no_asuransi").val();
    let nama_ortu = $("#nama_ortu").val();
    let agama = $("#agama").val();
    let suku = $("#suku").val();
    let id_user_input = $("#id_user_input").val();
    let kode_cabang = $("#kode_cabang").val();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('pendaftaran/store') }}",
      data: {
        //no_rm: no_rm,
        tgl_daftar: tgl_daftar,
        //waktu: waktu,
        nik: nik,
        nama_pasien: nama_pasien,
        tempat_lahir: tempat_lahir,
        tgl_lahir: tgl_lahir,
        umur: umur,
        jk: jk,
        alamat: alamat,
        id_provinsi: id_provinsi,
        id_kab_kota: id_kab_kota,
        id_kecamatan: id_kecamatan,
        status_perkawinan: status_perkawinan,
        pekerjaan: pekerjaan,
        tlp: tlp,
        jenis_pasien: jenis_pasien,
        nama_asuransi: nama_asuransi,
        no_asuransi: no_asuransi,
        nama_ortu: nama_ortu,
        agama: agama,
        suku: suku,
        id_user_input: id_user_input,
        kode_cabang: kode_cabang,
      },
      success: function(response) {
        if(response.res === true) {
          $('#fullscreenModal').modal('hide');
          // $("#kode_transaksi").val('');
          $("#tgl_daftar").val('');
          // $("#jam").val('');
          $("#nik").val('');
          $("#nama_pasien").val('');
          $("#tempat").val('');
          $("#tgl_lahir").val('');
          $("#umur").val('');
          $("#jenis_kelamin").val('');
          $("#alamat").val('');
          $("#provinsi").val('');
          $("#kab_kota").val('');
          $("#kecamatan").val('');
          $("#status_perkawinan").val('');
          $("#pekerjaan").val('');
          $("#tlp").val('');
          $("#jenis_pasien").val('');
          $("#nama_asuransi").val('');
          $("#no_asuransi").val('');
          $("#nama_ortu").val('');
          $("#agama").val('');
          $("#suku").val('');
          $("#id_user_input").val('');
          $("#kode_cabang").val('');
          fetchAllPendaftaran();
        }else{
          Swal.fire("Gagal!", "Data pendaftaran gagal disimpan.", "error");
        }
      }
    });
  });

  //=== End Insert data Pendaftaran =============//



</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Pendaftaran Klinik</title>
@endsection

@section('content')

<main id="main" class="main">
	<div class="pagetitle">
    <h1 class="d-flex justify-content-between align-items-center">
      Pendaftaran
      <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
      <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#fullscreenModal"><i class="bi bi-plus-square"></i>&nbsp; Tambah Pendaftaran</button>
    </h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pendaftaran Klinik</li>
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
              {{-- <div class="col-8"></div>
              <div class="col-4">
                <input type="text"  class="form-control" id="cari_satuan" placeholder="Cari..."/>
              </div> --}}
              
                <div class="col-4">
                  
                </div>
                <div class="col-2"></div>
                <div class="col-3">
                  <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari Nama Pasien..."/>
                </div>
                <div class="col-3">
                  <div class="input-group mb-3">
                      <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}">
                      <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                
            </div>
            <div class="table-responsive">
              <table id="" class="table table-striped table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <th hidden>No</th>
                    <th>No.RM</th>
                    <th>Nama Pasien</th>
                    <th>JK</th>
                    <th>Umur</th>
                    <th>Telepon/HP</th>
                    <th>Jenis Pasien</th>
                    <th>Tgl.Daftar</th>  
                    <th hidden>id user input</th>
                    <th>Petugas</th>
                    <th hidden>id cabang</th>
                    <th hidden>Cabang</th>
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

  <div class="modal fade" id="fullscreenModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Pendaftaran Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <br>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body"> 
                  <br>
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Tgl. Daftar</label>
                    <div class="col-sm-2">
                      <input type="text" name="tgl_daftar" id="tgl_daftar" class="form-control" value="{{ date('d-M-Y', strtotime(Carbon\Carbon::today()->toDateString())) }}" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" required readonly>
                    </div>

                    <div class="col-sm-2" hidden>
                      <input type="text" name="id_user_input" id="id_user_input" class="form-control" value="{{ Auth::user()->id }}" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" required readonly>
                    </div>

                    <div class="col-sm-2" hidden>
                      <input type="text" name="kode_cabang" id="kode_cabang" class="form-control" value="{{ Auth::user()->kd_lokasi }}" style="height: 30px; font-size: 14px; font-weight: bold; text-align: center;" required readonly>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Nama Pasien</label>
                    <div class="col-sm-5">
                      <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">NIK</label>
                    <div class="col-sm-3">
                      <input type="text" name="nik" id="nik" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Nama Orang Tua </label>
                    <div class="col-sm-5">
                      <input type="text" name="nama_ortu" id="nama_ortu" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                  </div>  

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                    <div class="col-sm-2">
                      <input type="text" name="tempat" id="tempat" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Tgl. Lahir</label>
                    <div class="col-sm-2">
                      <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                    
                    <label class="col-sm-2 col-form-label" align="right">Umur</label>
                    <div class="col-sm-2">
                      <input type="text" name="umur" id="umur" class="form-control" value="" style="height: 30px; font-size: 14px; text-align: center;" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-2">
                      <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Agama</label>
                    <div class="col-sm-2">
                      <select name="agama" id="agama" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen (Protestan)">Kristen (Protestan)</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Budha">Budha</option>
                        <option value="Konghucu">Konghucu</option>
                        <option value="Penghayat">Penghayat</option>
                        <option value="Lain-lain">Lain-lain</option>
                      </select>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Suku</label>
                    <div class="col-sm-2">
                      <input type="text" name="suku" id="suku" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                      <input type="text" name="alamat" id="alamat" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Provinsi</label>
                    <div class="col-sm-2">
                      <select name="provinsi" id="provinsi" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                        @foreach ($provinsi as $row)
                          <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->name }}</option>
                        @endforeach 
                      </select>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Kab/Kota</label>
                    <div class="col-sm-2">
                      <select name="kab_kota" id="kab_kota" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                      </select>
                    </div>
                    
                    <label class="col-sm-2 col-form-label" align="right">Kecamatan</label>
                    <div class="col-sm-2">
                      <select name="kecamatan" id="kecamatan" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Status Perkawinan</label>
                    <div class="col-sm-2">
                      <select name="status_perkawinan" id="status_perkawinan" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                      </select>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Pekerjaan</label>
                    <div class="col-sm-2">
                      <input type="text" name="pekerjaan" id="pekerjaan" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Telepon</label>
                    <div class="col-sm-2">
                      <input type="text" name="tlp" id="tlp" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Jenis Pasien</label>
                    <div class="col-sm-2">
                      <select name="jenis_pasien" id="jenis_pasien" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                        <option value="Asuransi">Asuransi</option>
                        <option value="Non Asuransi">Umum</option>
                      </select>
                    </div>

                    <label class="col-sm-2 col-form-label" align="right">Nama Asuransi</label>
                    <div class="col-sm-2">
                      <select name="nama_asuransi" id="nama_asuransi" class="form-select" style="height: 30px; font-size: 14px;" required>
                        <option value="">Pilih...</option>
                        <option value="BPJS">BPJS</option>
                      </select>
                    </div>
                    
                    <label for="inputKodeTransaksi" class="col-sm-2 col-form-label" align="right">No. Asuransi</label>
                    <div class="col-sm-2">
                      <input type="text" name="no_asuransi" id="no_asuransi" class="form-control" value="" style="height: 30px; font-size: 14px;" required>
                    </div>
                  </div>  
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="button_form_insert_pendaftaran" data-dismiss="modal"><i class="bi bi-save"></i> Simpan</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
    </div>
  </div>

</main>
@endsection



@section('js')
 
    
@endsection()