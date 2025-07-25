@section('js')
<script type="text/javascript">
    //===Select data Stok====//
    fetchAllStok();
    function fetchAllStok(){
        let id_type_user = $("#id_type").val();
        $.ajax({
            type: "GET",
            url: "{{ route('laporan_stok/getDataStok.getDataStok') }}",
            dataType: "json",
            success: function(response) {
                let tabledata;
                let no = 1;
                let supplier;
                response.data.forEach(stok => {
                    if(stok.nama_supplier != null){
                        supplier = stok.nama_supplier;
                    }else{
                        supplier = '-';
                    }
                    tabledata += `<tr>`;
                    tabledata += `<td style="padding-left: 13px;">${no++}</td>`;
                    tabledata += `<td>${stok.kode_produk}</td>`;
                    tabledata += `<td>${stok.nama_produk}</td>`;
                    tabledata += `<td align="right">${stok.qty}</td>`;
                    if (id_type_user == '1') {
                        tabledata += `<td align="right">${stok.harga_beli}</td>`;
                        tabledata += `<td align="right">${stok.qty*stok.harga_beli}</td>`;
                    }else if(id_type_user == '2'){
                        tabledata += `<td align="right" hidden>${stok.harga_beli}</td>`;
                        tabledata += `<td align="right" hidden>${stok.qty*stok.harga_beli}</td>`;
                    }
                    tabledata += `<td>${supplier}</td>`;
                    tabledata += `<td hidden>${stok.kode_cabang}</td>`;
                    tabledata += `<td>${stok.nama_cabang}</td>`;
                    tabledata += `<td>${stok.tgl_kadaluarsa}</td>`;
                    tabledata += `</tr>`;
                });
                $("#tabledata").html(tabledata);
            }
        });
    }
    //===End data Produk====//

    //=== SEARCH data Stok====//
    $("#cari").keyup(function() {
        let value = $("#cari").val();
        let id_type_user = $("#id_type").val();
        if (this.value.length >= 2) {
            $.ajax({
                type: "GET",
                url: "{{ route('laporan_stok/getDataStok.getDataStok') }}",
                data: {
                    value: value
                },
                dataType: "json",
                success: function(response) {
                    let tabledata;
                    let no = 1;
                    let supplier;
                    response.data.forEach(stok => {
                        if(stok.nama_supplier != null){
                            supplier = stok.nama_supplier;
                        }else{
                            supplier = '-';
                        }
                        tabledata += `<tr>`;
                        tabledata += `<td style="padding-left: 13px;">#</td>`;
                        tabledata += `<td>${stok.kode_produk}</td>`;
                        tabledata += `<td>${stok.nama_produk}</td>`;
                        tabledata += `<td align="right">${stok.qty}</td>`;
                        if (id_type_user == '1') {
                            tabledata += `<td align="right">${stok.harga_beli}</td>`;
                            tabledata += `<td align="right">${stok.qty*stok.harga_beli}</td>`;
                        }else if(id_type_user == '2'){
                            tabledata += `<td align="right" hidden>${stok.harga_beli}</td>`;
                            tabledata += `<td align="right" hidden>${stok.qty*stok.harga_beli}</td>`;
                        }
                        tabledata += `<td>${supplier}</td>`;
                        tabledata += `<td hidden>${stok.kode_cabang}</td>`;
                        tabledata += `<td>${stok.nama_cabang}</td>`;
                        tabledata += `<td>${stok.tgl_kadaluarsa}</td>`;
                        tabledata += `</tr>`;
                    });
                    $("#tabledata").html(tabledata);
                }
            });
        }else{
            fetchAllStok();
        }
    });
    //=== End SEARCH data Stok====//
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Laporan Stok</title>
@endsection

@section('content')

<main id="main" class="main">
    

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Laporan Stok
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Laporan</a></li>
          <li class="breadcrumb-item active">Stok</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_stok">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <form action="{{ route('laporan_stok/view') }}" target="_blank" method="get" enctype="multipart/form-data">
                            <div class="row mb-3">
                              <div class="col-4">
                                <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                                <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button>
                              </div>
                              <div class="col-4"></div>
                              <div class="col-4">
                                <input type="text"  class="form-control" name="cari" id="cari" placeholder="Cari..."/>
                              </div>
                            </div>
                        </form>
                        <div class="row mb-3" hidden>
                            <div class="row mb-3">
                                <label class="col-sm-1 col-form-label">Tanggal</label>
                                <div class="col-sm-2">
                                <input type="text" name="tgl" id="tgl" class="form-control" value="" required readonly>
                                </div>
                                
                                <label class="col-sm-1 col-form-label">Keterangan</label>
                                <div class="col-sm-5">
                                <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" id="savedatas" name="savedatas" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id_type" id="id_type" class="form-control" value="{{ Auth::user()->type }}" required readonly>
                        @if(Auth::user()->type == '1') <!-- Super Admin dan Admin -->
                            <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th>Harga Pokok</th>
                                        <th>Nilai Total</th>
                                        <th>Distributor</th>
                                        <th hidden>Kode Cabang</th>
                                        <th>Nama Cabang</th>
                                        <th>Kadaluarsa</th>
                                    </tr>
                                </thead>
                                <tbody id="tabledata">

                                </tbody>
                            </table>
                        @elseif(Auth::user()->type == '2') <!-- Admin -->
                            <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th hidden>Harga Pokok</th>
                                        <th hidden>Nilai Total</th>
                                        <th hidden>Kode Cabang</th>
                                        <th>Distributor</th>
                                        <th>Nama Cabang</th>
                                        <th>Kadaluarsa</th>
                                    </tr>
                                </thead>
                                <tbody id="tabledata">

                                </tbody>
                            </table>
                        @endif
                        
                    </div>
                </div>
                
            </div>
        </div>
    </section>

</main>
@endsection



@section('js')
 
    
@endsection()