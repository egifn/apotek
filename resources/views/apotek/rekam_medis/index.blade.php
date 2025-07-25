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
  //=== Select data Rekam Medis ====//
  fetchAllDataRM();
  function fetchAllDataRM() {
    $.ajax({
      type: "GET",
      url: "{{ route('rekam_medis/getDataRm.getDataRm') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(rm => {
          tabledata += `<tr>`;
          tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
          tabledata += `<td>${rm.no_rm}</td>`;
          tabledata += `<td>${rm.nama_pasien}</td>`;
          tabledata += `<td>${rm.jk}</td>`;
          tabledata += `<td>${rm.umur} Thn</td>`;
          tabledata += `<td>${rm.alamat}</td>`;
          tabledata += `<td hidden>${rm.id_user_input}</td>`;
          tabledata += `<td hidden>${rm.name}</td>`;
          tabledata += `<td hidden>${rm.kode_cabang}</td>`;
          tabledata += `<td hidden>${rm.nama_cabang}</td>`;
          tabledata += `<td align="center">
            <button type="button" 
            data-id="${rm.no_rm}"
            data-nama_pasien="${rm.nama_pasien}"
            data-jk="${rm.jk}"
            data-umur="${rm.umur}"
            data-alamat="${rm.alamat}"
            id="button_view" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button></td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //=== End Select data Rekam Medis ====//

  //===View Data RM===============//
  $(document).on("click", "#button_view", function(e) {
    e.preventDefault();
    let no_rm = $(this).data('id');
    let nama_pasien = $(this).data('nama_pasien');
    let jenis_kelamin = $(this).data('jk');
    let umur= $(this).data('umur');
    let alamat = $(this).data('alamat');

    $(".no_rm").text(no_rm);
    $(".nama_pasien").text(nama_pasien);
    $(".jk").text(jenis_kelamin);
    $(".umur").text(umur);
    $(".alamat").text(alamat);
    
    $.ajax({
      type: "GET",
      url: "{{ route('rekam_medis/getViewDataRekamMedis.getViewDataRekamMedis') }}",
      data: {
        no_rm: no_rm
      },
      dataType: "json",
      success: function(response) {
        let tbl_pemeriksaan;
        let no = 1;
        response.data.forEach(pemeriksaan => {
          tbl_pemeriksaan += `<tr>`;
            tbl_pemeriksaan += `<td style="padding-left: 13px;">${no++}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.tgl_periksa}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.keluhan_utama}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.riwayat_penyakit}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.riwayat_alergi}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.riwayat_pengobatan}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.tinggi_badan}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.berat_badan}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.tekanan_darah}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.suhu_badan}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.denyut_jantung}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.pernapasan}</td>`;
            tbl_pemeriksaan += `<td>${pemeriksaan.penglihatan}</td>`;
          tbl_pemeriksaan += `</tr>`;
        });
        $("#tbl_pemeriksaan").html(tbl_pemeriksaan);
      }
    });

    $.ajax({
      type: "GET",
      url: "{{ route('rekam_medis/getViewDataRekamMedisDiagnosa.getViewDataRekamMedisDiagnosa') }}",
      data: {
        no_rm: no_rm
      },
      dataType: "json",
      success: function(response) {
        let tbl_diagnosa;
        let no = 1;
        response.data.forEach(diagnosa => {
          tbl_diagnosa += `<tr>`;
            tbl_diagnosa += `<td style="padding-left: 13px;">${no++}</td>`;
            tbl_diagnosa += `<td>${diagnosa.tgl_periksa}</td>`;
            tbl_diagnosa += `<td>${diagnosa.id_sub_kategori}</td>`;
            tbl_diagnosa += `<td>${diagnosa.nama_subkategori_penyakit_eng}</td>`;
          tbl_diagnosa += `</tr>`;
        });
        $("#tbl_diagnosa").html(tbl_diagnosa);
      }
    });

    $.ajax({
      type: "GET",
      url: "{{ route('rekam_medis/getViewDataRekamMedisResep.getViewDataRekamMedisResep') }}",
      data: {
        no_rm: no_rm
      },
      dataType: "json",
      success: function(response) {
        let tbl_resep;
        let no = 1;
        response.data.forEach(resep => {
          tbl_resep += `<tr>`;
            tbl_resep += `<td style="padding-left: 13px;">${no++}</td>`;
            tbl_resep += `<td>${resep.tgl_periksa}</td>`;
            tbl_resep += `<td>${resep.kode_produk}</td>`;
            tbl_resep += `<td>${resep.nama_produk}</td>`;
            tbl_resep += `<td>${resep.qty}</td>`;
            tbl_resep += `<td hidden>${resep.id_produk_unit}</td>`;
            tbl_resep += `<td>${resep.nama_unit}</td>`;
            tbl_resep += `<td>${resep.aturan}</td>`;
          tbl_resep += `</tr>`;
        });
        $("#tbl_resep").html(tbl_resep);
      }
    });

    $('#modalViewRm').modal('show');
  });
  //===End View Data RM===============//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Rekam Medis</title>
@endsection

@section('content')

<main id="main" class="main">
	<div class="pagetitle">
    <h1 class="d-flex justify-content-between align-items-center">
      Rekam Medis
    </h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Rekam Medis</li>
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
              <div class="col-8"></div>
              <div class="col-4">
                <input type="text"  class="form-control" id="cari_satuan" placeholder="Cari..."/>
              </div>
            </div>
            <div class="table-responsive">
              <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>No.RM</th>
                    <th>Nama Pasien</th>
                    <th>JK</th>
                    <th>Umur</th>
                    <th>Alamat</th>  
                    <th hidden>id user input</th>
                    <th hidden>Petugas</th>
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

  <div class="modal fade" id="modalViewRm">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Rekam Medis Pasien</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-sm-6">
              <label for="inputNama" class="form-label">No RM: </label> 
              <label for="inputNama" class="form-label no_rm" style="font-weight: bold;"></label> 
            </div>
            <div class="col-sm-6">
              <label for="inputNama" class="form-label">Jenis Kelamin: </label> 
              <label for="inputNama" class="form-label jk" style="font-weight: bold;"></label>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-sm-6">
              <label for="inputNama" class="form-label">Nama Pasien: </label> 
              <label for="inputNama" class="form-label nama_pasien" style="font-weight: bold;"></label>
            </div>
            
            <div class="col-sm-6">
              <label for="inputNama" class="form-label">Umur: </label> 
              <label for="inputNama" class="form-label umur" style="font-weight: bold;"></label>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-sm-6">
              <label for="inputNama" class="form-label">Alamat Pasien: </label> 
              <label for="inputNama" class="form-label alamat" style="font-weight: bold;"></label>
            </div>
          </div>
          <hr style="border:0; height: 1px; background-color: #D3D3D3; ">

          <h6 class="card-title">Riwayat Pasien :</h6>
            <div class="col-4" hidden>
              <div class="input-group mb-3">
                  <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}" style="text-align: center;">
                  <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                </div>
            </div>
              <!-- Default Tabs -->
              <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="pemeriksaan-tab" data-bs-toggle="tab" data-bs-target="#pemeriksaan-justified" type="button" role="tab" aria-controls="pemeriksaan" aria-selected="true">Pemeriksaan Awal</button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="diagnosa-tab" data-bs-toggle="tab" data-bs-target="#diagnosa-justified" type="button" role="tab" aria-controls="diagnosa" aria-selected="false">Diagnosa</button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="obat-tab" data-bs-toggle="tab" data-bs-target="#obat-justified" type="button" role="tab" aria-controls="obat" aria-selected="false">Resep Obat</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="myTabjustifiedContent">
                <div class="tab-pane fade show active" id="pemeriksaan-justified" role="tabpanel" aria-labelledby="pemeriksaan-tab">
                  <br>
                  <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl Periksa</th>
                            <th>Keluhan</th>
                            <th>Riwayat Penyakit</th>
                            <th>Riwayat Alergi</th>
                            <th>Riwayat Pengobatan</th>
                            <th>Tinggi Badan</th>
                            <th>Berat Badan</th>
                            <th>Tekanan Darah</th>
                            <th>Suhu Badan</th>
                            <th>Denyut Jantung</th>
                            <th>Pernafasan</th>
                            <th>Pemeriksaan Fisik</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_pemeriksaan" class="tbl_pemeriksaan">
        
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade" id="diagnosa-justified" role="tabpanel" aria-labelledby="diagnosa-tab">
                  <br>
                  <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl Periksa</th>
                            <th>kode ICD</th>
                            <th>Diagnosa</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_diagnosa" class="tbl_diagnosa">
        
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade" id="obat-justified" role="tabpanel" aria-labelledby="obat-tab">
                  <br>
                  <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl Resep</th>
                            <th>kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Jml Obat</th>
                            <th hidden>Kode Unit</th>
                            <th>Satuan Obat</th>
                            <th>Aturan</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_resep" class="tbl_resep">
        
                    </tbody>
                  </table>
                </div>
              </div><!-- End Default Tabs -->
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

</main>
@endsection



@section('js')
 
    
@endsection()