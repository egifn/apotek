@section('js')
<script type="text/javascript">
  //===Select data Jenis====// 
  fetchAllDataPoli();
  function fetchAllDataPoli(){
    $.ajax({
      type: "GET",
      url: "{{ route('poli/getDataPoli.getDataPoli') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(poli => {
          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${poli.id}</td>`;
            tabledata += `<td>${poli.nama_poli}</td>`;
            tabledata += `<td hidden>${poli.id_user_input}</td>`;
            tabledata += `<td hidden>${poli.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${poli.id}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Jenis====// 

  //=== SEARCH Select data Jenis====//
  $("#cari_poli").keyup(function() {
    let value = $("#cari_poli").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('poli/getDataPoli.getDataPoli') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(poli => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${poli.id}</td>`;
            tabledata += `<td>${poli.nama_poli}</td>`;
            tabledata += `<td hidden>${poli.id_user_input}</td>`;
            tabledata += `<td hidden>${poli.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${poli.id}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
        fetchAllDataPoli();
    }
  });
  //=== End SEARCH Select data Jenis====//

  //=== Insert data Jenis =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let nama_poli = $("#nama_poli").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('poli/store.store') }}",
        data: {
            nama_poli: nama_poli,
        },
        success: function(response) {
            if(response.res === true) {
                $("#nama_poli").val('');
                $("#modalDialogScrollable").modal('hide');
                fetchAllDataPoli();
            }else{
                Swal.fire("Gagal!", "Data Poli gagal disimpan.", "error");
            }
        }
    });
  });
  //=== End Insert data Jenis =================//

  //=== Edit Data Pegawai ============================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let id = $(this).data('id');

    $.ajax({
        type: "GET",
        url: "{{ route('poli/getDataPoliDetail.getDataPoliDetail') }}",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            $("#update_id").val(id);
            $("#update_nama_poli").val(response.data.nama_poli);
        }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let id = $("#update_id").val();
    let nama_poli = $("#update_nama_poli").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('poli/update.update') }}",
        data: {
            id: id,
            nama_poli: nama_poli,
        },
        success: function(response) {
            if (response.status === true) {
                $("#update_id").val('');
                $("#update_nama_poli").val('');
                $("#modalEdit").modal('hide');
                fetchAllDataPoli();
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
    <title>Poli</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Poli
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Poli</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Poli</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row" id="unit">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <br>
              <form action="{{ route('poli/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                <div class="row mb-3">
                  <div class="col-4">
                    <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                    <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                  </div>
                  <div class="col-4"></div>
                  <div class="col-4">
                    <input type="text"  class="form-control" name="cari_poli" id="cari_poli" placeholder="Cari Poli..."/>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th hidden>Kode</th>
                      <th>Nama Poli</th>
                      <th hidden>Id User Input</th>
                      <th hidden>User Input</th>  
                      <th align="center">Opsi</th>
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
                <h3 class="modal-title">Tambah Poli</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <form>
                            <br>
                            <div class="col-12">
                            <label for="inputNama" class="form-label">Nama Poli</label>
                            <input type="text" class="form-control" name="nama_poli" id="nama_poli" required>
                            </div>
                            <br>
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
                  <h3 class="modal-title">Edit Poli</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="card mb-3">
                      <div class="card-body">
                          <form>
                              <br>
                              <div class="col-12">
                              <label for="inputNama" class="form-label">Nama Poli</label>
                              <input type="hidden" class="form-control" name="update_id" id="update_id" required>
                              <input type="text" class="form-control" name="update_nama_poli" id="update_nama_poli" required>
                              </div>
                              <br>
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