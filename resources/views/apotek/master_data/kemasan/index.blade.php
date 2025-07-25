@section('js')
<script type="text/javascript">
  //===Select data unit/kategori====//
  fetchAllUnit();
  function fetchAllUnit() {
    $.ajax({
      type: "GET",
      url: "{{ route('getDataUnit') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(el => {
          tabledata += `<tr>`;
          tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
          tabledata += `<td hidden>${el.id_unit}</td>`;
          tabledata += `<td>${el.nama_unit}</td>`;
          tabledata += `<td hidden>${el.id_user_input}</td>`;
          tabledata += `<td hidden>${el.name}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${el.id_unit}" id="button_edit_unit" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //=== End Select data unit/kategori ====//

  //=== SEARCH Select data unit/kategori====//
  $("#cari_satuan").keyup(function() {
    let value = $("#cari_satuan").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('getDataUnit') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(el => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${el.id_unit}</td>`;
            tabledata += `<td>${el.nama_unit}</td>`;
            tabledata += `<td hidden>${el.id_user_input}</td>`;
            tabledata += `<td hidden>${el.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${el.id_unit}" id="button_edit_unit" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    } else {
      fetchAllUnit();
    }
  });
  //=== SEARCH end Select data unit/kategori====//

  //=== Insert data Unit =================//
  $("#button_form_insert_unit").click(function() {
    let nama_unit = $("#nama_unit").val();
    let id_user_input = $("#id_user_input").val();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('kemasan/store') }}",
      data: {
        nama_unit: nama_unit,
        id_user_input: id_user_input,
      },
      success: function(response) {
        if(response.res === true) {
          $('#nama_unit').val('');
          $('#id_user_input').val('');
          $("#modalDialogScrollable").modal('hide');
          fetchAllUnit();
        }else{
          Swal.fire("Gagal!", "Data unit gagal disimpan.", "error");
        }
      }
    });
  });
  //=== End Insert data Unit =================//

  //=== Edit Data Pegawai ============================//
  $(document).on("click", "#button_edit_unit", function(e) {
    e.preventDefault();
    let id = $(this).data('id');

    $.ajax({
        type: "GET",
        url: "{{ route('kemasan/getDataKemasanDetail.getDataKemasanDetail') }}",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            $("#update_id").val(id);
            $("#update_nama_unit").val(response.data.nama_unit);
        }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let id = $("#update_id").val();
    let nama_unit = $("#update_nama_unit").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('kemasan/update.update') }}",
        data: {
            id: id,
            nama_unit: nama_unit,
        },
        success: function(response) {
            if (response.status === true) {
                $("#update_id").val();
                $("#update_nama_unit").val('');
                $("#modalEdit").modal('hide');
                fetchAllUnit();
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
    <title>Satuan</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Data Satuan
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Satuan</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Satuan</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row" id="unit">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <br>
              <form action="{{ route('kemasan/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                <div class="row mb-3">
                  <div class="col-4">
                    <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                    <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                  </div>
                  <div class="col-4"></div>
                  <div class="col-4">
                    <input type="text"  class="form-control" name="cari_satuan" id="cari_satuan" placeholder="Cari Satuan..."/>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th hidden>Kode</th>
                      <th>Nama Satuan</th>
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
                <h3 class="modal-title">Tambah Data Satuan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <br>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Satuan</label>
                      <input type="text" class="form-control" name="nama_unit" id="nama_unit" required>
                    </div>
                    <div class="col-12" hidden>
                      <label for="inputNama" class="form-label">Id User</label>
                      <input type="text" class="form-control" name="id_user_input" id="id_user_input" value="{{Auth::user()->id}}" required>
                    </div>
                    <br>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_insert_unit"><i class="bi bi-save"></i> Simpan</button>
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
                <h3 class="modal-title">Edit Data Satuan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <br>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Satuan</label>
                      <input type="hidden" class="form-control" name="update_id" id="update_id" required>
                      <input type="text" class="form-control" name="update_nama_unit" id="update_nama_unit" required>
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