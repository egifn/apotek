@section('js')
<script type="text/javascript">
  //===Select data Jenis====// 
  fetchAllDataTuslah();
  function fetchAllDataTuslah(){
    $.ajax({
      type: "GET",
      url: "{{ route('tuslah/getDataTuslah.getDataTuslah') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(tuslah => {
          let harga = tuslah.harga_tuslah;
          //membuat format rupiah Harga//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${tuslah.id}</td>`;
            tabledata += `<td>${tuslah.nama_tuslah}</td>`;
            tabledata += `<td align="right">${harga_jadi}</td>`;
            tabledata += `<td hidden>${tuslah.id_user_input}</td>`;
            tabledata += `<td hidden>${tuslah.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${tuslah.id}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Jenis====// 

  //=== SEARCH Select data Jenis====//
  $("#cari_tuslah").keyup(function() {
    let value = $("#cari_tuslah").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('tuslah/getDataTuslah.getDataTuslah') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(tuslah => {
            let harga = tuslah.harga_tuslah;
            //membuat format rupiah Harga//
            var reverse_harga = harga.toString().split('').reverse().join(''),
            ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
            harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${tuslah.id}</td>`;
            tabledata += `<td>${tuslah.nama_tuslah}</td>`;
            tabledata += `<td align="right">${harga_jadi}</td>`;
            tabledata += `<td hidden>${tuslah.id_user_input}</td>`;
            tabledata += `<td hidden>${tuslah.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${tuslah.id}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
        fetchAllDataTuslah();
    }
  });
  //=== End SEARCH Select data Jenis====//

  $('#harga_tuslah').maskMoney({thousands:',', decimal:'.', precision:0});
  //=== Insert data Jenis =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let nama_tuslah = $("#nama_tuslah").val();
    let harga_tuslah = $("#harga_tuslah").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('tuslah/store.store') }}",
        data: {
            nama_tuslah: nama_tuslah,
            harga_tuslah: harga_tuslah,
        },
        success: function(response) {
            if(response.res === true) {
                $("#nama_tuslah").val('');
                $("#harga_tuslah").val('');
                $("#modalDialogScrollable").modal('hide');
                fetchAllDataTuslah();
            }else{
                Swal.fire("Gagal!", "Data Tuslah gagal disimpan.", "error");
            }
        }
    });
  });
  //=== End Insert data Jenis =================//

  $('#update_harga_tuslah').maskMoney({thousands:',', decimal:'.', precision:0});
  //=== Edit Data Pegawai ============================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let id = $(this).data('id');

    $.ajax({
        type: "GET",
        url: "{{ route('tuslah/getDataTuslahDetail.getDataTuslahDetail') }}",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            let harga = response.data.harga_tuslah;
            //membuat format rupiah Harga//
            var reverse_harga = harga.toString().split('').reverse().join(''),
            ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
            harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            $("#update_id").val(id);
            $("#update_nama_tuslah").val(response.data.nama_tuslah);
            $("#update_harga_tuslah").val(harga_jadi);
        }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let id = $("#update_id").val();
    let nama_tuslah = $("#update_nama_tuslah").val();
    let harga_tuslah = $("#update_harga_tuslah").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('tuslah/update.update') }}",
        data: {
            id: id,
            nama_tuslah: nama_tuslah,
            harga_tuslah: harga_tuslah,
        },
        success: function(response) {
            if (response.status === true) {
                $("#update_id").val();
                $("#update_nama_tuslah").val('');
                $("#update_harga_tuslah").val('');
                $("#modalEdit").modal('hide');
                fetchAllDataTuslah();
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
    <title>Tuslah</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Data Tuslah
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Tuslah</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Tuslah</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <form action="{{ route('tuslah/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-4">
                        <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                        <input type="text"  class="form-control" name="cari_tuslah" id="cari_tuslah" placeholder="Cari Tuslah..."/>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Nama Tuslah</th>
                              <th>Harga Tuslah</th>
                              <th hidden>Id User Input</th>
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
                <h3 class="modal-title">Tambah Data Tuslah</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <br>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Tuslah</label>
                      <input type="text" class="form-control" name="nama_tuslah" id="nama_tuslah" required>
                    </div>
                    <div class="col-12">
                      <label for="inputHargaB" class="form-label">Harga Tuslah</label>
                      <input type="text" class="form-control" name="harga_tuslah" id="harga_tuslah" required>
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
                <h3 class="modal-title">Edit Data Tuslah</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <br>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Tuslah</label>
                      <input type="hidden" class="form-control" name="update_id" id="update_id" required>
                      <input type="text" class="form-control" name="update_nama_tuslah" id="update_nama_tuslah" required>
                    </div>
                    <div class="col-12">
                      <label for="inputHargaB" class="form-label">Harga Tuslah</label>
                      <input type="text" class="form-control" name="update_harga_tuslah" id="update_harga_tuslah" required>
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