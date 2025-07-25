@section('js')
<script type="text/javascript">
  // $(document).ready(function(){
  //   $('#harga').maskMoney({thousands:',', decimal:'.', precision:0});
  // });

  //===Select data Jenis====// 
  fetchAllDataJasa();
  function fetchAllDataJasa(){
    $.ajax({
      type: "GET",
      url: "{{ route('jasa/getDataJasa.getDataJasa') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 1;
        response.data.forEach(jasa => {
          let harga = jasa.harga;
          //membuat format rupiah Harga//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${jasa.id}</td>`;
            tabledata += `<td>${jasa.kode_jasa_p}</td>`;
            tabledata += `<td>${jasa.nama_jasa_p}</td>`;
            tabledata += `<td align="right">${harga_jadi}</td>`;
            tabledata += `<td hidden>${jasa.id_user_input}</td>`;
            tabledata += `<td hidden>${jasa.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${jasa.id}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select data Jenis====// 

   //=== SEARCH Select data Jenis====//
   $("#cari_jasa").keyup(function() {
    let value = $("#cari_jasa").val();
    if (this.value.length >= 2) {
      $.ajax({
        type: "GET",
        url: "{{ route('jasa/getDataJasa.getDataJasa') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata;
          let no = 1;
          response.data.forEach(jasa => {
            let harga = jasa.harga;
            //membuat format rupiah Harga//
            var reverse_harga = harga.toString().split('').reverse().join(''),
            ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
            harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td hidden>${jasa.id}</td>`;
            tabledata += `<td>${jasa.kode_jasa_p}</td>`;
            tabledata += `<td>${jasa.nama_jasa_p}</td>`;
            tabledata += `<td align="right">${harga_jadi}</td>`;
            tabledata += `<td hidden>${jasa.id_user_input}</td>`;
            tabledata += `<td hidden>${jasa.name}</td>`;
            tabledata += `<td align="center"><button type="button" data-id="${jasa.id}" id="button_edit_data" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>`;
          tabledata += `</tr>`;
          });
          $("#tabledata").html(tabledata);
        }
      });
    }else{
      fetchAllDataJasa();
    }
  });
  //=== End SEARCH Select data Jenis====//

  $('#harga').maskMoney({thousands:',', decimal:'.', precision:0});
  //=== Insert data Jenis =================//
  $("#button_form_insert").click(function(e) {
    e.preventDefault();
    let nama_jasa_p = $("#nama_jasa").val();
    let harga = $("#harga").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('jasa/store.store') }}",
        data: {
            nama_jasa_p: nama_jasa_p,
            harga: harga,
        },
        success: function(response) {
            if(response.res === true) {
                $("#nama_jasa").val('');
                $("#harga").val('');
                $("#modalDialogScrollable").modal('hide');
                fetchAllDataJasa();
            }else{
                Swal.fire("Gagal!", "Data Jasa gagal disimpan.", "error");
            }
        }
    });
  });
  //=== End Insert data Jenis =================//

  $('#update_harga').maskMoney({thousands:',', decimal:'.', precision:0});
  //=== Edit Data Pegawai ============================//
  $(document).on("click", "#button_edit_data", function(e) {
    e.preventDefault();
    let id = $(this).data('id');

    $.ajax({
        type: "GET",
        url: "{{ route('jasa/getDataJasaDetail.getDataJasaDetail') }}",
        data: {
            id: id
        },
        dataType: "json",
        success: function(response) {
            let harga = response.data.harga;
            //membuat format rupiah Harga//
            var reverse_harga = harga.toString().split('').reverse().join(''),
            ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
            harga_jadi = ribuan_harga.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            $("#update_id").val(id);
            $("#update_nama_jasa").val(response.data.nama_jasa_p);
            $("#update_harga").val(harga_jadi);
        }
    });
    $('#modalEdit').modal('show');
  });

  $("#button_form_update").click(function() {
    let id = $("#update_id").val();
    let nama_jasa = $("#update_nama_jasa").val();
    let harga = $("#update_harga").val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        type: "POST",
        url: "{{ route('jasa/update.update') }}",
        data: {
            id: id,
            nama_jasa: nama_jasa,
            harga: harga,
        },
        success: function(response) {
            if (response.status === true) {
                $("#update_id").val();
                $("#update_nama_jasa").val('');
                $("#update_harga").val('');
                $("#modalEdit").modal('hide');
                fetchAllDataJasa();
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
    <title>Pelayanan</title>
@endsection

@section('content')

<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Pelayanan
        <!-- <a href="#" class="btn btn-success btn-sm float-right">Tambah Data</a> -->
        <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Tambah Pelayanan</button>
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Master Data</li>
          <li class="breadcrumb-item active">Pelayanan</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <form action="{{ route('jasa/view.view') }}" target="_blank" method="get" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <div class="col-4">
                        <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                      </div>
                      <div class="col-4"></div>
                      <div class="col-4">
                        <input type="text"  class="form-control" name="cari_jasa" id="cari_jasa" placeholder="Cari Pelayanan..."/>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode</th>
                              <th>Nama Jasa/Pelayanan</th>
                              <th>Harga</th>
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
                <h3 class="modal-title">Tambah Data Jasa/Pelayanan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Jasa/Pelayanan</label>
                      <input type="text" class="form-control" name="nama_jasa" id="nama_jasa" required>
                    </div>
                    <div class="col-12">
                      <label for="inputHargaB" class="form-label">Harga</label>
                      <input type="text" class="form-control" name="harga" id="harga" required>
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
                <h3 class="modal-title">Edit Data Jasa/Pelayanan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="card mb-3">

                <div class="card-body">
                  <form>
                    <div class="col-12">
                      <label for="inputNama" class="form-label">Nama Jasa/Pelayanan</label>
                      <input type="hidden" class="form-control" name="update_id" id="update_id" required>
                      <input type="text" class="form-control" name="update_nama_jasa" id="update_nama_jasa" required>
                    </div>
                    <div class="col-12">
                      <label for="inputHargaB" class="form-label">Harga</label>
                      <input type="text" class="form-control" name="update_harga" id="update_harga" required>
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