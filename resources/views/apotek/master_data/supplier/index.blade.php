@section('js')
<script type="text/javascript">
  //===Select data supplier====// 
  fetchAllDataSupplier();
  function fetchAllDataSupplier(){
    $.ajax({
      type: "GET",
      url: "{{ route('supplier/getDataSupplier.getDataSupplier') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(spl => {
          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${spl.kode_supplier}</td>`;
            tabledata += `<td>${spl.nama_supplier}</td>`;
            tabledata += `<td>${spl.alamat}</td>`;
            tabledata += `<td>${spl.cp}</td>`;
            tabledata += `<td>${spl.tlp}</td>`;
            tabledata += `<td>${spl.email}</td>`;
            tabledata += `<td hidden>${spl.id_user_input}</td>`;
            tabledata += `<td hidden>${spl.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${spl.kode_supplier}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data supplier====// 

  //=== SEARCH Select data supplier====//
  $("#cari_supplier").keyup(function() {
    let value = $("#cari_supplier").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('supplier/getDataSupplier.getDataSupplier') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(spl => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${spl.kode_supplier}</td>`;
            tabledata += `<td>${spl.nama_supplier}</td>`;
            tabledata += `<td>${spl.alamat}</td>`;
            tabledata += `<td>${spl.cp}</td>`;
            tabledata += `<td>${spl.tlp}</td>`;
            tabledata += `<td>${spl.email}</td>`;
            tabledata += `<td hidden>${spl.id_user_input}</td>`;
            tabledata += `<td hidden>${spl.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${spl.kode_supplier}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
      fetchAllDataSupplier();
    }
  });
  //=== End SEARCH Select data supplier====//

  //=== Insert data Supplier =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let nama_supplier = $("#nama").val();
    let alamat = $("#alamat").val();
    let cp = $('#cp').val();
    let tlp = $('#tlp').val();
    let email = $('#email').val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('supplier/store.store') }}",
        data: {
            nama_supplier: nama_supplier,
            alamat: alamat,
            cp: cp,
            tlp: tlp,
            email: email,
        },
        success: function(response) {
            if(response.res === true) {
                $("#nama_supplier").val('');
                $("#alamat").val('');
                $("#cp").val('');
                $("#tlp").val('');
                $("#email").val('');
                $("#modalDialogScrollable").modal('hide');
                fetchAllDataSupplier();
            }else{
                Swal.fire("Gagal!", "Data pegawai gagal disimpan.", "error");
            }
        }
    });
  });
  //=== End Insert data Supplier =================//

  //=== Edit Data Pegawai ============================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let kode_supplier = $(this).data('id');

    $.ajax({
        type: "GET",
        url: "{{ route('supplier/getDataSupplierDetail.getDataSupplierDetail') }}",
        data: {
          kode_supplier: kode_supplier
        },
        dataType: "json",
        success: function(response) {
            $("#update_kode_supplier").val(kode_supplier);
            $("#update_nama").val(response.data.nama_supplier);
            $("#update_alamat").val(response.data.alamat);
            $("#update_cp").val(response.data.cp);
            $("#update_tlp").val(response.data.tlp);
            $("#update_email").val(response.data.email);
        }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let kode_supplier = $("#update_kode_supplier").val();
    let nama_supplier = $("#update_nama").val();
    let alamat = $("#update_alamat").val();
    let cp = $("#update_cp").val();
    let tlp = $("#update_tlp").val();
    let email = $("#update_email").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('supplier/update.update') }}",
        data: {
            kode_supplier: kode_supplier,
            nama_supplier: nama_supplier,
            alamat: alamat,
            cp: cp,
            tlp: tlp,
            email: email,
        },
        success: function(response) {
            if (response.status === true) {
                $("#update_kode_supplier").val();
                $("#update_nama").val('');
                $("#update_alamat").val('');
                $("#update_cp").val('');
                $("#update_tlp").val('');
                $("#update_email").val('');
                $("#modalEdit").modal('hide');
                fetchAllDataSupplier();
            }else{
                alert('Gagal, Data tidak berhasil diubah...');
            }
        }
    });
  });

</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Supplier</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Data Supplier
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Supplier</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Supplier</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <form action="{{ route('supplier/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-4">
                        <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                        <input type="text"  class="form-control" name="cari_supplier" id="cari_supplier" placeholder="Cari supplier..."/>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode Supplier</th>
                              <th>Nama Supplier</th>
                              <th>Alamat</th>
                              <th>CP</th>
                              <th>Telepon</th>  
                              <th>Email</th>
                              <th hidden>Status</th>
                              <th hidden>Id_User Input</th>
                              <th hidden>User Input</th>
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
                <h3 class="modal-title">Tambah Data Supplier</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <br>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Supplier</label>
                      <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="col-12">
                      <label for="inputAlamat" class="form-label">Alamat</label>
                      <input type="text" class="form-control" name="alamat" id="alamat" required>
                    </div>
                    <div class="col-12">
                      <label for="inputCp" class="form-label">Contact Person</label>
                      <input type="text" class="form-control" name="cp" id="cp" required>
                    </div>
                    <div class="col-12">
                      <label for="inputTlp" class="form-label">Telepon/HP</label>
                      <input type="text" class="form-control" name="tlp" id="tlp" required>
                    </div>
                    <div class="col-12">
                      <label for="inputEmail" class="form-label">Email</label>
                      <input type="text" class="form-control" name="email" id="email" required>
                    </div>
                    <br>
                    <button type="button" class="btn btn-success" id="button_form_insert"><i class="bi bi-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                  </form>
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
                <h3 class="modal-title">Edit Data Supplier</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <br>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Supplier</label>
                      <input type="hidden" class="form-control" name="update_kode_supplier" id="update_kode_supplier" required>
                      <input type="text" class="form-control" name="update_nama" id="update_nama" required>
                    </div>
                    <div class="col-12">
                      <label for="inputAlamat" class="form-label">Alamat</label>
                      <input type="text" class="form-control" name="update_alamat" id="update_alamat" required>
                    </div>
                    <div class="col-12">
                      <label for="inputCp" class="form-label">Contact Person</label>
                      <input type="text" class="form-control" name="update_cp" id="update_cp" required>
                    </div>
                    <div class="col-12">
                      <label for="inputTlp" class="form-label">Telepon/HP</label>
                      <input type="text" class="form-control" name="update_tlp" id="update_tlp" required>
                    </div>
                    <div class="col-12">
                      <label for="inputEmail" class="form-label">Email</label>
                      <input type="text" class="form-control" name="update_email" id="update_email" required>
                    </div>
                    <br>
                    <button type="button" class="btn btn-success" id="button_form_update"><i class="bi bi-save"></i> Simpan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                  </form>
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