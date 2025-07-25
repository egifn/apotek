@section('js')
<script type="text/javascript">
  //===Select data Pengguna====//
  fetchAllDataPengguna();
  function fetchAllDataPengguna(){
    $.ajax({
      type: "GET",
      url: "{{ route('pengguna/getDataPengguna.getDataPengguna') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(pengguna => {
          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${pengguna.name}</td>`;
            tabledata += `<td>${pengguna.email}</td>`;
            tabledata += `<td>${pengguna.username}</td>`;
            tabledata += `<td>${pengguna.nama_cabang}</td>`;
            tabledata += `<td>${pengguna.nama}</td>`;
            if (pengguna.status_user == 'Aktif') {
              tabledata += `<td align="center"><span class="badge bg-success">${pengguna.status_user}</span></td>`;
            } else {
              tabledata += `<td align="center"><span class="badge bg-danger">${pengguna.status_user}</span></td>`;
            }
            tabledata += `<td align="center"><button type="button" data-id="${pengguna.id_pengguna}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Pengguna====//

  //=== SEARCH Select data Pengguna====//
  $("#cari_pengguna").keyup(function() {
    let value = $("#cari_pengguna").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('pengguna/getDataPengguna.getDataPengguna') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(pengguna => {
            tabledata += `<tr>`;
              tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
              tabledata += `<td>${pengguna.name}</td>`;
              tabledata += `<td>${pengguna.email}</td>`;
              tabledata += `<td>${pengguna.username}</td>`;
              tabledata += `<td>${pengguna.nama_cabang}</td>`;
              tabledata += `<td>${pengguna.nama}</td>`;
                if (pengguna.status_user == 'Aktif') {
                  tabledata += `<td align="center"><span class="badge bg-success">${pengguna.status_user}</span></td>`;
                } else {
                  tabledata += `<td align="center"><span class="badge bg-danger">${pengguna.status_user}</span></td>`;
                }
              tabledata += `<td align="center"><button type="button" data-id="${pengguna.id_pengguna}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
      fetchAllDataPengguna();
    }
  });
  //=== End SEARCH Select data Pengguna====//

  //=== Menampilkan Modal Data Pegawai ========//
  fetch_data_pegawai();
  function fetch_data_pegawai() {
    $.ajax({
      type: "GET",
      url: "{{ route('pengguna/getDataPenggunaModal.getDataPenggunaModal') }}",
      dataType: "json",
      success: function(response) {
        let tabledatapegawai;
        response.data.forEach(pegawai => {
          tabledatapegawai += `<tr class="pilih" data-kode_pegawai="${pegawai.kode_pegawai}" data-nama="${pegawai.nama_pegawai}" data-jabatan="${pegawai.jabatan}">`;
          tabledatapegawai += `<td>${pegawai.kode_pegawai}</td>`;
          tabledatapegawai += `<td>${pegawai.nama_pegawai}</td>`;
          tabledatapegawai += `<td>${pegawai.jabatan}</td>`;
          tabledatapegawai += `</tr>`;
        });
        $("#tabledatapegawai").html(tabledatapegawai);
      }
    });
  }

  $(document).on('click', '.pilih', function (e) {
    $("#id_pegawai").val($(this).attr('data-kode_pegawai'))
    $("#nama").val($(this).attr('data-nama'))
    $('#modalDialogScrollable').modal('show');
    $('#modalCariPegawai').modal('hide');
    $("#email").focus();
  });
  //=== End Menampilkan Modal Data Pegawai ========//

  //===Pencarian Modal data Pegawai====//
  $("#search").keyup(function() {
    let value = $("#search").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('pengguna/getDataPenggunaModal.getDataPenggunaModal') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledatapegawai;
          response.data.forEach(pegawai => {
            tabledatapegawai += `<tr class="pilih" data-kode_pegawai="${pegawai.kode_pegawai}" data-nama="${pegawai.nama_pegawai}" data-jabatan="${pegawai.jabatan}">`;
            tabledatapegawai += `<td>${pegawai.kode_pegawai}</td>`;
            tabledatapegawai += `<td>${pegawai.nama_pegawai}</td>`;
            tabledatapegawai += `<td>${pegawai.jabatan}</td>`;
            tabledatapegawai += `</tr>`;
          });
          $("#tabledatapegawai").html(tabledatapegawai);
        }
      });
    }else{
      fetch_data_pegawai();
    }
  });
  //===End Pencarian Modal data Pegawai====//

  //=== Insert data Pengguna =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let name = $("#nama").val();
    let username = $("#username").val();
    let email = $("#email").val();
    let password = $("#password").val();
    let lokasi = $("#lokasi").val();
    let level = $("#level").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('pengguna/store.store') }}",
      data: {
        name: name,
        username: username,
        email: email,
        password: password,
        lokasi: lokasi,
        level: level,
      },
      success: function(response) {
        if(response.res === true) {
          $("#id_pegawai").val('');
          $("#nama").val('');
          $("#username").val('');
          $("#email").val('');
          $("#password").val('');
          $("#lokasi").val('');
          $("#level").val('');
          $("#modalDialogScrollable").modal('hide');
          fetchAllDataPengguna();
          //alert('Sukses, Data Berhasil diubah...');
        }else{
          Swal.fire("Gagal!", "Data pegawai gagal disimpan.", "error");
        }
      }
    });
  });
  //=== End Insert data Pengguna =================//

  //=== Edit Data Pengguna =================================================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    
    $.ajax({
      type: "GET",
      url: "{{ route('pengguna/getDataPenggunaDetail.getDataPenggunaDetail') }}",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        $("#update_id").val(id);
        $("#update_nama").val(response.data.name);
        $("#update_email").val(response.data.email);
        $("#update_username").val(response.data.username);
        $("#update_lokasi").val(response.data.kd_lokasi);
        $("#update_level").val(response.data.type);
        $("#update_status").val(response.data.status_user);
      }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let id = $("#update_id").val();
    let name = $("#update_nama").val();
    let email = $("#update_email").val();
    let username = $("#update_username").val();
    let lokasi = $("#update_lokasi").val();
    let level = $("#update_level").val();
    let status = $("#update_status").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('pengguna/update.update') }}",
      data: {
        id: id,
        name: name,
        email: email,
        username: username,
        lokasi: lokasi,
        level: level,
        status: status,
      },
      success: function(response) {
        if (response.status === true) {
          $("#update_name").val('');
          $("#update_username").val('');
          $("#update_email").val('');
          $("#update_lokasi").val('');
          $("#update_level").val('');
          $("#update_status").val('');
          $("#modalEdit").modal('hide');
          fetchAllDataPengguna();
          //alert('Sukses, Data Berhasil diubah...');
        }else{
          alert('Gagal, Data tidak berhasil diubah...');
        }
      }
    });
  });
  //=== End Edit Data Pengguna =================================================//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Pengguna</title>
@endsection

@section('content')

<main id="main" class="main">
	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Data Pengguna
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Pengguna</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Pengguna</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <br>
              <form action="{{ route('pengguna/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                <div class="row mb-3">
                  <div class="col-4">
                    <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                    <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                  </div>
                  <div class="col-4"></div>
                  <div class="col-4">
                    <input type="text"  class="form-control" name="cari_pengguna" id="cari_pengguna" placeholder="Cari Pengguna..."/>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th hidden>Kode_lokasi</th>
                      <th>Lokasi</th>  
                      <th hidden>Id Tipe</th>
                      <th>Tipe</th>
                      <th>Status</th>
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
                  <h3 class="modal-title">Tambah Data Pengguna</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                  <div class="card mb-2">
                    <div class="card-body">
                      {{-- <div class="col-md-12">
                        <label for="inputName5" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                      </div> --}}
                      <label for="inputName5" class="form-label">Nama</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" name="id_pegawai" id="id_pegawai" value="" hidden>
                        <input type="text" class="form-control" name="nama" id="nama" value="" readonly>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCariPegawai"><i class="bi bi-search"></i></button>
                      </div>

                      <div class="col-md-12">
                        <label for="inputEmail5" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                      </div>
                      <div class="col-md-12">
                        <label for="inputAddress5" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                      </div>
                      <div class="col-md-12">
                        <label for="inputPassword5" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                      </div>
                      <div class="col-12">
                        <label for="inputState" class="form-label">Cabang/Lokasi</label>
                        <select name="lokasi" id="lokasi" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_cabang as $row)
                            <option value="{{ $row->kode_cabang }}" {{ old('kode_cabang') == $row->kode_cabang ? 'selected':'' }}>{{ $row->nama_cabang }}</option>
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-12">
                        <label for="inputState" class="form-label">Level Pengguna</label>
                        <select name="level" id="level" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_level as $row)
                            <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama }}</option>
                          @endforeach 
                        </select>
                      </div>
                    </div>
                  </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_insert"><i class="bi bi-save"></i> Simpan</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
             
            </div>
          </div>
        </div>

          <div class="modal fade" id="modalCariPegawai" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Data Pegawai</h3>
                  <button type="button" class="btn-close tutupModalObat" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="#" method="get">
                    <div class="input-group mb-3 col-md-6 right">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Cari Pegawai...">
                    </div>
                  </form>
                  <table id="lookup" class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                      </tr>
                    </thead>
                    <tbody id="tabledatapegawai" data-dismiss="modal">
                          
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger tutupModalObat" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Edit Data Pengguna</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                  <div class="card mb-2">
                    <div class="card-body">
                      <div class="col-md-12">
                        <label for="inputName5" class="form-label">Nama</label>
                        <input type="hidden" class="form-control" name="update_id" id="update_id" required>
                        <input type="text" class="form-control" name="update_nama" id="update_nama" required>
                      </div>
                      <div class="col-md-12">
                        <label for="inputEmail5" class="form-label">Email</label>
                        <input type="email" class="form-control" name="update_email" id="update_email" required>
                      </div>
                      <div class="col-md-12">
                        <label for="inputAddress5" class="form-label">Username</label>
                        <input type="text" class="form-control" name="update_username" id="update_username" required>
                      </div>
                      
                      <div class="col-12">
                        <label for="inputState" class="form-label">Cabang/Lokasi</label>
                        <select name="update_okasi" id="update_lokasi" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_cabang as $row)
                            <option value="{{ $row->kode_cabang }}" {{ old('kode_cabang') == $row->kode_cabang ? 'selected':'' }}>{{ $row->nama_cabang }}</option>
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-12">
                        <label for="inputState" class="form-label">Level Pengguna</label>
                        <select name="update_level" id="update_level" class="form-select" required>
                          <option value="">Pilih...</option>
                          @foreach ($data_level as $row)
                            <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama }}</option>
                          @endforeach 
                        </select>
                      </div>
                      <div class="col-12">
                        <label for="inputState" class="form-label">Status Pengguna</label>
                        <select name="update_status" id="update_status" class="form-select" required>
                          <option value="">Pilih...</option>
                          <option value="Aktif">Aktif</option>
                          <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                      </div>
                    </div>
                  </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_update"><i class="bi bi-save"></i> Simpan</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
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