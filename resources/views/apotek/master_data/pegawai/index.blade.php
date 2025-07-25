@section('js')
<script type="text/javascript">
  //===Select data Pegawai====// 
  fetchAllDataPegawai();
  function fetchAllDataPegawai(){
    $.ajax({
      type: "GET",
      url: "{{ route('pegawai/getDataPegawai.getDataPegawai') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(pegawai => {
          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${pegawai.kode_pegawai}</td>`;
            tabledata += `<td>${pegawai.nik_pegawai}</td>`;
            tabledata += `<td>${pegawai.nama_pegawai}</td>`;
            tabledata += `<td>${pegawai.jk}</td>`;
            tabledata += `<td>${pegawai.alamat}</td>`;
            tabledata += `<td>${pegawai.tlp}</td>`;
            tabledata += `<td>${pegawai.email}</td>`;
            tabledata += `<td>${pegawai.jabatan}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${pegawai.kode_pegawai}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Pegawai====// 

  //=== SEARCH Select data Pegawai====//
  $("#cari_pegawai").keyup(function() {
    let value = $("#cari_pegawai").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('pegawai/getDataPegawai.getDataPegawai') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(pegawai => {
            tabledata += `<tr>`;
              tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
              tabledata += `<td>${pegawai.kode_pegawai}</td>`;
              tabledata += `<td>${pegawai.nik_pegawai}</td>`;
              tabledata += `<td>${pegawai.nama_pegawai}</td>`;
              tabledata += `<td>${pegawai.jk}</td>`;
              tabledata += `<td>${pegawai.alamat}</td>`;
              tabledata += `<td>${pegawai.tlp}</td>`;
              tabledata += `<td>${pegawai.email}</td>`;
              tabledata += `<td>${pegawai.jabatan}</td>`;
              tabledata += `<td align="center"><button type="button" data-id="${pegawai.kode_pegawai}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
      fetchAllDataPegawai();
    }
  });
  //=== End SEARCH Select data Pegawai====//

  //=== Insert data Pegawai =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let nama = $("#nama").val();
    let nik = $("#nik").val();
    let jk = $("#jk").val();
    let alamat = $("#alamat").val();
    let tlp = $("#tlp").val();
    let email = $("#email").val();
    let jabatan = $("#jabatan").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('pegawai/store.store') }}",
      data: {
        nama: nama,
        nik: nik,
        jk: jk,
        alamat: alamat,
        tlp: tlp,
        email: email,
        jabatan: jabatan,
      },
      success: function(response) {
        if(response.res === true) {
          $("#nama").val('');
          $("#nik").val('');
          $("#jk").val('');
          $("#alamat").val('');
          $("#tlp").val('');
          $("#email").val('');
          $("#jabatan").val('');
          $("#modalDialogScrollable").modal('hide');
          fetchAllDataPegawai();
        }else{
          Swal.fire("Gagal!", "Data pegawai gagal disimpan.", "error");
        }
      }
    });

  });
  //=== End Insert data Pegawai =================//

  //=== Edit Data Pegawai ============================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let id = $(this).data('id');

    $.ajax({
      type: "GET",
      url: "{{ route('pegawai/getDataPegawaiDetail.getDataPegawaiDetail') }}",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        $("#update_id").val(id);
        $("#update_nik").val(response.data.nik_pegawai);
        $("#update_nama").val(response.data.nama_pegawai);
        $("#update_jk").val(response.data.jk);
        $("#update_alamat").val(response.data.alamat);
        $("#update_tlp").val(response.data.tlp);
        $("#update_email").val(response.data.email);
        $("#update_jabatan").val(response.data.jabatan);
        $("#update_status").val(response.data.status_pegawai);
      }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let id = $("#update_id").val();
    let nama = $("#update_nama").val();
    let nik = $("#update_nik").val();
    let jk = $("#update_jk").val();
    let alamat = $("#update_alamat").val();
    let tlp = $("#update_tlp").val();
    let email = $("#update_email").val();
    let jabatan = $("#update_jabatan").val();
    let status = $("#update_status").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('pegawai/update.update') }}",
      data: {
        id: id,
        nama: nama,
        nik: nik,
        jk: jk,
        alamat: alamat,
        tlp: tlp,
        email: email,
        jabatan: jabatan,
        status: status,
      },
      success: function(response) {
        if (response.status === true) {
          $("#update_nama").val('');
          $("#update_nik").val('');
          $("#update_jk").val('');
          $("#update_alamat").val('');
          $("#update_tlp").val('');
          $("#update_email").val('');
          $("#update_jabatan").val('');
          $("#update_status").val('');
          $("#modalEdit").modal('hide');
          fetchAllDataPegawai();
          //alert('Sukses, Data Berhasil diubah...');
        }else{
          alert('Gagal, Data tidak berhasil diubah...');
        }
      }
    });
  });
  //=== End Edit Data Pegawai ============================//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Pegawai</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Data Pegawai
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Pegawai</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Pegawai</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <form action="{{ route('pegawai/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-4">
                        <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                        <input type="text"  class="form-control" name="cari_pegawai" id="cari_pegawai" placeholder="Cari Pegawai..."/>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode Pegawai</th>
                              <th>NIK</th>
                              <th>Nama Pegawai</th>
                              <th>JK</th>
                              <th>Alamat</th>
                              <th>Telepon</th>  
                              <th>Email</th>
                              <th>Jabatan</th>
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

        <div class="modal fade" id="modalDialogScrollable" tabindex="-1">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Tambah Data Pegawai</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="card mb-3">

                  <div class="card-body">
                    <form action="" class="row g-3 needs-validation" novalidate>
                      <div class="col-12">
                        <label for="inputNama" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                      </div>
                      <div class="col-12">
                        <label for="inputNik" class="form-label">NIK</label>
                        <input type="text" class="form-control" name="nik" id="nik" required>
                      </div>
                      <div class="col-12">
                        <label for="inputJK" class="form-label">Jenis Kelamin</label>
                        <select name="jk" id="jk" class="form-control" required>
                          <option value="">Pilih...</option>
                          <option value="L">Laki-laki</option>
                          <option value="P">Perempuan</option>
                        </select>
                      </div>
                      <div class="col-12">
                        <label for="inputAlamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="alamat" id="alamat" required>
                      </div>
                      <div class="col-12">
                        <label for="inputTlp" class="form-label">Telepon/HP</label>
                        <input type="text" class="form-control" name="tlp" id="tlp" required>
                      </div>
                      <div class="col-12">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" id="email" required>
                      </div>
                      <div class="col-12">
                        <label for="inputJabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" id="jabatan" required>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="button_form_insert"><i class="bi bi-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                  </div>

                </div>
              </div>
              
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Edit Data Pegawai</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form action="" class="row g-3 needs-validation" novalidate>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Pegawai</label>
                      <input type="hidden" class="form-control" name="update_id" id="update_id" required>
                      <input type="text" class="form-control" name="update_nama" id="update_nama" required>
                    </div>
                    <div class="col-12">
                      <label for="inputNik" class="form-label">NIK</label>
                      <input type="text" class="form-control" name="update_nik" id="update_nik" required>
                    </div>
                    <div class="col-12">
                      <label for="inputJK" class="form-label">Jenis Kelamin</label>
                      <select name="update_jk" id="update_jk" class="form-control" required>
                        <option value="">Pilih...</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <label for="inputAlamat" class="form-label">Alamat</label>
                      <input type="text" class="form-control" name="update_alamat" id="update_alamat" required>
                    </div>
                    <div class="col-12">
                      <label for="inputTlp" class="form-label">Telepon/HP</label>
                      <input type="text" class="form-control" name="update_tlp" id="update_tlp" required>
                    </div>
                    <div class="col-12">
                      <label for="inputEmail" class="form-label">Email</label>
                      <input type="text" class="form-control" name="update_email" id="update_email" required>
                    </div>
                    <div class="col-12">
                      <label for="inputJabatan" class="form-label">Jabatan</label>
                      <input type="text" class="form-control" name="update_jabatan" id="update_jabatan" required>
                    </div>
                    <div class="col-12">
                      <label for="inputState" class="form-label">Status Pegawai</label>
                      <select name="update_status" id="update_status" class="form-select" required>
                        <option value="">Pilih...</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                      </select>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_update"><i class="bi bi-save"></i> Simpan</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
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