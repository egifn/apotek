@section('js')
<script type="text/javascript">
  //===Select data Apotek====//
  fetchAllDataApotek();
  function fetchAllDataApotek(){
    $.ajax({
      type: "GET",
      url: "{{ route('apotek/getDataApotek.getDataApotek') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(apotek => {
          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${apotek.kode_cabang}</td>`;
            tabledata += `<td>${apotek.nama_cabang}</td>`;
            tabledata += `<td>${apotek.alamat}</td>`;
            tabledata += `<td>${apotek.tlp}</td>`;
            tabledata += `<td>${apotek.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${apotek.kode_cabang}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Apotek====//

  //=== SEARCH Select data Apotek====//
  $("#cari_apotek").keyup(function() {
    let value = $("#cari_apotek").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('apotek/getDataApotek.getDataApotek') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(apotek => {
            tabledata += `<tr>`;
              tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
              tabledata += `<td>${apotek.kode_cabang}</td>`;
              tabledata += `<td>${apotek.nama_cabang}</td>`;
              tabledata += `<td>${apotek.alamat}</td>`;
              tabledata += `<td>${apotek.tlp}</td>`;
              tabledata += `<td>${apotek.name}</td>`;
              tabledata += `<td align="center"><button type="button" data-id="${apotek.kode_cabang}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
            tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
      fetchAllDataApotek();
    }
  });
  //=== End SEARCH Select data Apotek====//

  //=== Insert data Apotek =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let nama_apotek = $("#nama").val();
    let alamat = $("#alamat").val();
    let tlp = $("#tlp").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('apotek/store.store') }}",
      data: {
        nama_apotek: nama_apotek,
        alamat:alamat,
        tlp: tlp,
      },
      success: function(response) {
        if(response.res === true) {
          $("#nama").val('');
          $("#alamat").val('');
          $("#tlp").val('');
          $("#modalDialogScrollable").modal('hide');
          fetchAllDataApotek();
          //alert('Sukses, Data Berhasil diubah...');
        }else{
          Swal.fire("Gagal!", "Data unit gagal disimpan.", "error");
        }
      }
    });

  });
  //=== End Insert data Produk =================//

  //=== Edit Data Produk =================================================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let kode_cabang = $(this).data('id');

    $.ajax({
      type: "GET",
      url: "{{ route('apotek/getDataApotekDetail.getDataApotekDetail') }}",
      data: {
        kode_cabang: kode_cabang
      },
      dataType: "json",
      success: function(response) {
        $("#update_kode_apotek").val(kode_cabang);
        $("#update_nama").val(response.data.nama_cabang);
        $("#update_alamat").val(response.data.alamat);
        $("#update_tlp").val(response.data.tlp);
      }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let kode_cabang = $("#update_kode_apotek").val();
    let nama_cabang = $("#update_nama").val();
    let alamat = $("#update_alamat").val();
    let tlp = $("#update_tlp").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('apotek/update.update') }}",
      data: {
        kode_cabang: kode_cabang,
        nama_cabang: nama_cabang,
        alamat: alamat,
        tlp: tlp,
      },
      success: function(response) {
        if (response.status === true) {
          $("#update_kode_apotek").val('');
          $("#update_nama").val('');
          $("#update_alamat").val('');
          $("#update_tlp").val('');
          $("#modalEdit").modal('hide');
          fetchAllDataApotek();
          //alert('Sukses, Data Berhasil diubah...');
        }else{
          alert('Gagal, Data tidak berhasil diubah...');
        }
      }
    });
  });
  //=== End Edit Data Produk =================================================//

</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Apotek</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Data Apotek
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Apotek</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dasboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Apotek</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <form action="{{ route('apotek/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-4">
                        <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                        <input type="text"  class="form-control" name="cari_apotek" id="cari_apotek" placeholder="Cari Apotek..."/>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode Apotek</th>
                              <th>Nama Apotek</th>
                              <th>Alamat</th>
                              <th>Telepon</th>
                              <th hidden>Id User Input</th>
                              <th>User Input</th>
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
                <h3 class="modal-title">Tambah Data Apotek</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="card-body">
                <div class="col-9">
                  <label for="inputNama" class="form-label">Nama Apotek</label>
                  <input type="text" class="form-control" name="nama" id="nama" required>
                </div>
                <div class="col-12">
                  <label for="inputAlamat" class="form-label">Alamat</label>
                  <input type="text" class="form-control" name="alamat" id="alamat" required>
                </div>
                <div class="col-12">
                  <label for="inputTlp" class="form-label">Telepon/HP</label>
                  <input type="text" class="form-control" name="tlp" id="tlp" required>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_insert"><i class="bi bi-save"></i> Simpan</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalEdit" tabindex="-1">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Edit Data Apotek</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="card-body">
                <div class="col-9">
                  <label for="inputNama" class="form-label">Nama Apotek</label>
                  <input type="hidden" class="form-control" name="update_kode_apotek" id="update_kode_apotek" required>
                  <input type="text" class="form-control" name="update_nama" id="update_nama" required>
                </div>
                <div class="col-12">
                  <label for="inputAlamat" class="form-label">Alamat</label>
                  <input type="text" class="form-control" name="update_alamat" id="update_alamat" required>
                </div>
                <div class="col-12">
                  <label for="inputTlp" class="form-label">Telepon/HP</label>
                  <input type="text" class="form-control" name="update_tlp" id="update_tlp" required>
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