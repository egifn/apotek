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
    //=== Select data Resep ====//
    fetchAllResep();
    function fetchAllResep() {
        $.ajax({
        type: "GET",
        url: "{{ route('farmasi/getDataResepObat.getDataResepObat') }}",
        dataType: "json",
        success: function(response) {
            let tabledata;
            let no = 1;
            response.data.forEach(resep => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${resep.kode_resep}</td>`;
            tabledata += `<td>${resep.tgl_resep}</td>`;
            tabledata += `<td hidden>${resep.id_pemeriksaan}</td>`;
            tabledata += `<td>${resep.no_rm}</td>`;
            tabledata += `<td>${resep.nama_pasien}</td>`;
            tabledata += `<td>${resep.jk}</td>`;
            tabledata += `<td hidden>${resep.umur}</td>`;
                if (resep.status_resep == 0) {
                    tabledata += `<td align="center"><span class="badge bg-warning">Baru</span></td>`;
                } else {
                    tabledata += `<td align="center"><span class="badge bg-success">Selesai</span></td>`;
                }
            tabledata += `<td hidden>${resep.id_dokter}</td>`;
            tabledata += `<td>${resep.nama_dokter}</td>`;
            tabledata += `<td hidden>${resep.id_user_input}</td>`;
            tabledata += `<td hidden>${resep.nama_user_input}</td>`;
            tabledata += `<td hidden>${resep.kode_cabang}</td>`;
            tabledata += `<td hidden>${resep.nama_cabang}</td>`;
            if (resep.status_resep == 0) {
                tabledata += `<td align="center">
                <button type="button" 
                data-id="${resep.kode_resep}" 
                data-tgl="${resep.tgl_resep}"
                data-id_pemeriksaan="${resep.id_pemeriksaan}"
                data-rm="${resep.no_rm}"
                data-nama_pasien="${resep.nama_pasien}"
                data-jk="${resep.jk}"
                data-jk="${resep.umur}"
                id="button_proses" class="btn btn-warning btn-sm"><i class="bi bi-eyedropper"></i></button>&nbsp;
                <button type="button" data-id="${resep.kode_resep}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
            } else {
                tabledata += `<td align="center">
                    <button type="button" 
                data-id="${resep.kode_resep}" 
                data-tgl="${resep.tgl_resep}"
                data-id_pemeriksaan="${resep.id_pemeriksaan}"
                data-rm="${resep.no_rm}"
                data-nama_pasien="${resep.nama_pasien}"
                data-jk="${resep.jk}"
                data-jk="${resep.umur}"
                id="button_proses" class="btn btn-warning btn-sm" disabled><i class="bi bi-eyedropper"></i></button>&nbsp;
                <button type="button" data-id="${resep.kode_resep}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
            }
            tabledata += `</td>`;
            tabledata += `</tr>`;
            });
            $("#tabledata").html(tabledata);
        }
        });
    }
    //=== End Select data Pendaftaran ====//

    //=== SEARCH data Rresep====//
    $("#button_cari_tanggal").click(function() {
        let value = $("#cari").val();
        let tgl = $("#tanggal").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('farmasi/cari.cari') }}",
                data: {
                    value: value,
                    tgl: tgl, 
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
            let no = 1;
            response.data.forEach(resep => {
            tabledata += `<tr>`;
            tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
            tabledata += `<td>${resep.kode_resep}</td>`;
            tabledata += `<td>${resep.tgl_resep}</td>`;
            tabledata += `<td hidden>${resep.id_pemeriksaan}</td>`;
            tabledata += `<td>${resep.no_rm}</td>`;
            tabledata += `<td>${resep.nama_pasien}</td>`;
            tabledata += `<td>${resep.jk}</td>`;
            tabledata += `<td hidden>${resep.umur}</td>`;
                if (resep.status_resep == 0) {
                    tabledata += `<td align="center"><span class="badge bg-warning">Baru</span></td>`;
                } else {
                    tabledata += `<td align="center"><span class="badge bg-success">Selesai</span></td>`;
                }
            tabledata += `<td hidden>${resep.id_dokter}</td>`;
            tabledata += `<td>${resep.nama_dokter}</td>`;
            tabledata += `<td hidden>${resep.id_user_input}</td>`;
            tabledata += `<td hidden>${resep.nama_user_input}</td>`;
            tabledata += `<td hidden>${resep.kode_cabang}</td>`;
            tabledata += `<td hidden>${resep.nama_cabang}</td>`;
            if (resep.status_resep == 0) {
                tabledata += `<td align="center">
                <button type="button" 
                data-id="${resep.kode_resep}" 
                data-tgl="${resep.tgl_resep}"
                data-id_pemeriksaan="${resep.id_pemeriksaan}"
                data-rm="${resep.no_rm}"
                data-nama_pasien="${resep.nama_pasien}"
                data-jk="${resep.jk}"
                data-jk="${resep.umur}"
                id="button_proses" class="btn btn-warning btn-sm"><i class="bi bi-eyedropper"></i></button>&nbsp;
                <button type="button" data-id="${resep.kode_resep}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
            } else {
                tabledata += `<td align="center">
                    <button type="button" 
                data-id="${resep.kode_resep}" 
                data-tgl="${resep.tgl_resep}"
                data-id_pemeriksaan="${resep.id_pemeriksaan}"
                data-rm="${resep.no_rm}"
                data-nama_pasien="${resep.nama_pasien}"
                data-jk="${resep.jk}"
                data-jk="${resep.umur}"
                id="button_proses" class="btn btn-warning btn-sm" disabled><i class="bi bi-eyedropper"></i></button>&nbsp;
                <button type="button" data-id="${resep.kode_resep}" id="button_cetak" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button></td>`;
            }
            tabledata += `</td>`;
            tabledata += `</tr>`;
            });
            $("#tabledata").html(tabledata);
                }
            });
        }else{
            fetchAllResep();
        }
    });
    //=== End SEARCH data Resep====//

    //===View Data Resep===============//
    $(document).on("click", "#button_proses", function(e) {
        e.preventDefault();
        let kode_resep = $(this).data('id');
        let norm = $(this).data('rm');
        let nama_pasien = $(this).data('nama_pasien');

        $("#kode_resep").val(kode_resep);
        $("#norm").val(norm);
        $("#nama_pasien").val(nama_pasien);

        $.ajax({
            type: "GET",
            url: "{{ route('farmasi/getDataResepDetail.getDataResepDetail') }}",
            data: {
                kode_resep: kode_resep
            },
            dataType: "json",
            success: function(response) {
                let datatabelResep;
                let no = 0;
                response.data.forEach(detail => {
                    no = no + 1

                    datatabelResep += '<tr>';
                    datatabelResep += '<td>' +no+ '</td>';    
                    datatabelResep += '<td class="kode_produk" id="kode_produk' + no + '">' +detail.kode_produk+ '</td>';
                    datatabelResep += '<td class="nama_produk" id="nama_produk' + no + '">' +detail.nama_produk+ '</td>';
                    datatabelResep += '<td class="stok" id="stok' + no + '">' +detail.stok+ '</td>';
                    datatabelResep += '<td class="jml_kecil" id="jml_kecil' + no + '" hidden>' +detail.qty_kecil+ '</td>';
                    datatabelResep += '<td class="jml" id="jml' + no + '">' +detail.qty+ '</td>';
                    datatabelResep += '<td class="kode_satuan" id="kode_satuan' + no + '" hidden>' +detail.id_produk_unit+ '</td>';
                    datatabelResep += '<td class="satuan" id="satuan' + no + '">' +detail.nama_unit+ '</td>';
                    datatabelResep += '<td class="aturan" id="aturan' + no + '">' +detail.aturan+ '</td>';    
                    datatabelResep += '</tr>';
                });
                $("#datatabelResep").html(datatabelResep);
            }
        });
        $('#fullscreenModal').modal('show');
    });
    //=== End View Data Resep===============//

    $('#button_form_insert').click(function(e) {
      e.preventDefault();
      let kode_resep = $("#kode_resep").val();

      // untuk Detail //
      let kode_produk = []
      let stok = []
      let jml_kecil = []
      let jml = []
      let kode_satuan = []

      $('.kode_produk').each(function() {
         kode_produk.push($(this).text())
      })
      $('.stok').each(function() {
        stok.push($(this).text())
      })
      $('.jml_kecil').each(function() {
        jml_kecil.push($(this).text())
      })
      $('.jml').each(function() {
        jml.push($(this).text())
      })
      $('.kode_satuan').each(function() {
        kode_satuan.push($(this).text())
      })

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "{{ route('farmasi/store.store') }}",
        data: { 
          kode_resep: kode_resep,

          kode_produk: kode_produk,
          stok: stok,
          jml_kecil: jml_kecil,
          jml: jml,
          kode_satuan: kode_satuan,

        },
        success: function(response) {
          if(response.res === true) {
            window.location.href = "{{ route('farmasi.index')}}";
          }else{
            alert("Gagal!", "Data gagal disimpan.", "error");
          }
        }
      });
    });
</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Farmasi</title>
@endsection

@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1 class="d-flex justify-content-between align-items-center">
      Farmasi
    </h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Farmasi</li>
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
                            <th>No</th>
                            <th>Kode Resep</th>
                            <th>Tanggal</th>
                            <th hidden>Kode Pemeriksaan</th>   
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Jk</th>
                            <th hidden>Umur</th>
                            <th>Status</th>
                            <th>Dokter</th>
                            <th hidden>Id User Input</th>
                            <th hidden>User Input</th>  
                            <th hidden>Id Apotek</th>
                            <th hidden>Nama Apotek</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody id="tabledata">
                        
                    </tbody>
                  </table>
                </div>

                <div class="modal fade" id="fullscreenModal" tabindex="-1">
                  <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Data Pemeriksaan Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="col-lg-12">
                          <div class="card">
                            <div class="card-body">
                             <br>
                             <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" align="right"><b>Detail Pasien :</b></label>
                              </div>
              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" align="right">Kode Resep</label>
                                <div class="col-sm-2">
                                  <input type="text" name="kode_resep" id="kode_resep" class="form-control" value="" required readonly>
                                </div>
                              </div>
                              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" align="right">No RM</label>
                                <div class="col-sm-2">
                                  <input type="text" name="norm" id="norm" class="form-control" value="" required readonly>
                                </div>

                                <label class="col-sm-2 col-form-label" align="right">Nama Pasien</label>
                                <div class="col-sm-4">
                                  <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" value="" required readonly>
                                </div>
              
                                {{-- <label class="col-sm-1 col-form-label" align="right">Jenis Kelamin</label>
                                <div class="col-sm-1">
                                  <input type="text" name="jk" id="jk" class="form-control" style="text-align: center;" value="" required readonly>
                                </div> --}}
              
                                {{-- <label class="col-sm-1 col-form-label" align="right">G. Darah</label>
                                <div class="col-sm-1">
                                  <input type="text" name="gol_darah" id="gol_darah" class="form-control" style="text-align: center;" value="" required readonly>
                                </div> --}}
                              </div>
              
                              <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
              
                              <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" align="right"><b>Resep Obat :</b></label>
                              </div>
              
                              <div class="row mb-3">
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-8">
                                  <table id="datatabel" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Obat</th>
                                            <th>Nama Obat</th>
                                            <th>Stok</th>
                                            <th>Jml</th>
                                            <th>Satuan</th>
                                            <th>Aturan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="datatabelResep" class="datatabelResep">
                  
                                    </tbody>
                                  </table>
                                </div>
                              </div>
              
                              <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
              
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="button_form_insert" data-dismiss="modal"><i class="bi bi-save"></i> Simpan</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                      </div>
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
