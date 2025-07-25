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
  //=== Select data Pendaftaran ====//
  fetchAllKunjungan();
  function fetchAllKunjungan() {
    $.ajax({
      type: "GET",
      url: "{{ route('getDataAntrian') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        response.data.forEach(antrian => {
          tabledata += `<tr>`;
          tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
          tabledata += `<td>${antrian.kode_kunjungan}</td>`;
          tabledata += `<td>${antrian.no_rm}</td>`;
          tabledata += `<td>${antrian.nama_pasien}</td>`;
          tabledata += `<td>${antrian.nama_poli}</td>`;
          tabledata += `<td>${antrian.tgl_kunjungan}</td>`;
            if (antrian.status_periksa == 0) {
                tabledata += `<td align="center"><span class="badge bg-secondary">Dalam Antrian</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Selesai</span></td>`;
            }

            if (antrian.status_kasir == 0) {
                tabledata += `<td align="center"><span class="badge bg-danger">Belum Bayar</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Sudah Bayar</span></td>`;
            }
          tabledata += `<td hidden>${antrian.id_user_input}</td>`;
          tabledata += `<td>${antrian.name}</td>`;
          tabledata += `<td hidden>${antrian.kode_cabang}</td>`;
          tabledata += `<td hidden>${antrian.nama_cabang}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_delete" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          tabledata += `</td>`;
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
    $.ajax({
      type: "GET",
      url: "{{ route('pelayanan/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        response.data.forEach(antrian => {
          tabledata += `<tr>`;
          tabledata += `<td hidden style="padding-left: 13px;">#</td>`;
          tabledata += `<td>${antrian.kode_kunjungan}</td>`;
          tabledata += `<td>${antrian.no_rm}</td>`;
          tabledata += `<td>${antrian.nama_pasien}</td>`;
          tabledata += `<td>${antrian.nama_poli}</td>`;
          tabledata += `<td>${antrian.tgl_kunjungan}</td>`;
            if (antrian.status_periksa == 0) {
                tabledata += `<td align="center"><span class="badge bg-secondary">Dalam Antrian</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Selesai</span></td>`;
            }

            if (antrian.status_kasir == 0) {
                tabledata += `<td align="center"><span class="badge bg-danger">Belum Bayar</span></td>`;
            } else {
                tabledata += `<td align="center"><span class="badge bg-success">Sudah Bayar</span></td>`;
            }
          tabledata += `<td hidden>${antrian.id_user_input}</td>`;
          tabledata += `<td>${antrian.name}</td>`;
          tabledata += `<td hidden>${antrian.kode_cabang}</td>`;
          tabledata += `<td hidden>${antrian.nama_cabang}</td>`;
          tabledata += `<td align="center"><button type="button" data-id="${antrian.kode_kunjungan}" id="button_delete" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
          tabledata += `</td>`;
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//

  //=== Insert data Pendaftaran =================//
  $("#button_form_insert_kunjungan").click(function() {
    let no_rm = $("#kode_transaksi").val();
    let id_poli = $("#poli").val();
    let id_dokter = $("#dokter").val();
    let id_user_input = $("#id_user_input").val();
    let kode_cabang = $("#kode_cabang").val();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('store') }}",
      data: {
        no_rm: no_rm,
        id_poli: id_poli,
        id_dokter: id_dokter,
        id_user_input: id_user_input,
        kode_cabang: kode_cabang,
      },
      success: function(response) {
        if(response.res == true) {
          $('#fullscreenModal').modal('hide');
          $("#kode_transaksi").val('');
          $('#nama_pasien').val('');
          $('#tempat').val('');
          $('#tgl_lahir').val('');
          $('#umur').val('');
          $('#jk').val('');
          $('#alamat').val('');
          $("#poli").val('');
          $("#dokter").val('');
          $("#id_user_input").val('');
          $("#kode_cabang").val('');
          fetchAllKunjungan();
        }else{
          Swal.fire("Gagal!", "Data Kunjungan gagal disimpan.", "error");
        }
      }
    });
  });
  //=== End Insert data Pendaftaran =================//

  $(document).ready(function () {
        fetch_pasien_data();
        function fetch_pasien_data(query = '') {
            $.ajax({
                url: '{{ route("actionGetPasien") }}',
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                success: function (data) {
                    $('#lookup tbody').html(data.table_data);
                }
            })
        }

        $(document).on('keyup', '#search', function () {
            var query = $(this).val();
            fetch_pasien_data(query);
        });
  });

  $(document).on('click', '.pilih', function (e) {
    var norm = $(this).attr('data-no_rm');
    var nama = $(this).attr('data-nama_pasien');
    var gabung = norm + ' / ' + nama;
    document.getElementById("kode_transaksi").value = $(this).attr('data-no_rm');
    document.getElementById("nama_pasien").value = gabung;
    document.getElementById("tempat").value = $(this).attr('data-tempat');
    document.getElementById("tgl_lahir").value = $(this).attr('data-tgl');
    document.getElementById("umur").value = $(this).attr('data-umur')+' Thn ';
    document.getElementById("jk").value = $(this).attr('data-jk');
    document.getElementById("alamat").value = $(this).attr('data-alamat');
    $('#fullscreenModal').modal('show');
    $('#modalCari').modal('hide');
  });
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Kunjungan</title>
@endsection

@section('content')

<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="d-flex justify-content-between align-items-center">
      Kunjungan
      <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#fullscreenModal"><i class="bi bi-plus-square"></i>&nbsp; Tambah Kunjungan</button> 
    </h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kunjungan</li>
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
                    <div class="col-4">
                      {{-- <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit" ><i class="bi bi-file-earmark-excel"></i> Excel</button>
                      <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button> --}}
                    </div>
                    <div class="col-2"></div>
                    <div class="col-3">
                      {{-- <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/> --}}
                    </div>
                    <div class="col-3">
                      <div class="input-group mb-3">
                          <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}">
                          <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>Kode Antrian</th>
                              <th>No RM</th>
                              <th>Nama Pasien</th>
                              <th>Poli</th>
                              <th>Tanggal</th>
                              <th>Status Periksa</th>
                              <th>Status Kasir</th>
                              <th hidden>Id User Input</th>
                              <th>User Input</th>  
                              <th hidden>Id Apotek</th>
                              <th hidden>Nama Apotek</th>
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
                  <h5 class="modal-title">Tambah Kunjungan Pasien</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <br>
                <br>
                <div class="modal-body">
                  
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <br>
                        <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
                       
                        <div class="row mb-3">
                          <div class="col-sm-8" hidden>
                            <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" required readonly>
                          </div>
                          <label class="col-sm-2 col-form-label" align="right">No. RM / Nama Pasien</label>
                          <div class="col-sm-8">
                            <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" required readonly>
                          </div>
                          <div class="col-sm-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCari"><i class="bi bi-search"></i> Cari</button>
                          </div>
                          <div class="col-sm-2" hidden>
                            <input type="text" name="id_user_input" id="id_user_input" class="form-control" value="{{ Auth::user()->id }}" required readonly>
                          </div>
    
                          <div class="col-sm-2" hidden>
                            <input type="text" name="kode_cabang" id="kode_cabang" class="form-control" value="{{ Auth::user()->kd_lokasi }}" required readonly>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" align="right">Tempat Lahir</label>
                          <div class="col-sm-2">
                            <input type="text" name="tempat" id="tempat" class="form-control" value="" required readonly>
                          </div>
    
                          <label class="col-sm-1 col-form-label" align="right">Tgl Lahir</label>
                          <div class="col-sm-2">
                            <input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control" value="" required readonly>
                          </div>
                          
                          <label class="col-sm-1 col-form-label" align="right">Umur</label>
                          <div class="col-sm-2">
                            <input type="text" name="umur" id="umur" class="form-control" value="" required readonly>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" align="right">Jenis Kelamin</label>
                          <div class="col-sm-8">
                            <input type="text" name="jk" id="jk" class="form-control" value="" required readonly>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" align="right">Alamat</label>
                          <div class="col-sm-8">
                            <input type="text" name="alamat" id="alamat" class="form-control" value="" required readonly>
                          </div>
                        </div>
                        <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" align="right">Poli</label>
                          <div class="col-sm-3">
                            <select name="poli" id="poli" class="form-select" required>
                              <option value="">Pilih...</option>
                              @foreach ($poli as $row)
                                <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_poli }}</option>
                              @endforeach 
                            </select>
                          </div>

                          <label class="col-sm-1 col-form-label" align="right">Dokter</label>
                          <div class="col-sm-4">
                            <select name="dokter" id="dokter" class="form-select" required>
                              <option value="">Pilih...</option>
                              @foreach ($dokter as $row)
                                <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected':'' }}>{{ $row->nama_pegawai }}</option>
                              @endforeach 
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                          
                        </div>
                        <hr style="border:0; height: 1px; background-color: #D3D3D3; ">

                      </div>
                    </div>
                  </div>
                  <div class="col-lg-1">
                  </div>
                </div>
                <br>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" id="button_form_insert_kunjungan" data-dismiss="modal"><i class="bi bi-save"></i> Simpan</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
              </div>
          </div>
        </div>

        <div class="modal fade" id="modalCari" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Data Pasien</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="#" method="get">
                  <div class="input-group mb-3 col-md-6 right">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Cari No RM / Nama Pasien . . .">
                  </div>
                </form>
                    <table id="lookup" class="table table-bordered table-hover table-striped">
                      <thead>
                        <tr>
                          <th>No RM</th>
                          <th>Nama Pasien</th>
                          <th>Tgl. Lahir</th>
                          <th>Alamat</th>
                        </tr>
                      </thead>
                      <tbody data-dismiss="modal">
                        
                      </tbody>
                    </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
              </div>
            </div>
          </div>
        </div>

</main>
@endsection



@section('js')
  
    
@endsection()