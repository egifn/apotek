@section('js')
<script type="text/javascript">
  

</script>

@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Barcode</title>  
@endsection

@section('content')

  

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="d-flex justify-content-between align-items-center">
            Data Barcode
            {{-- <button type="button" class="btn btn-success btn-sm right" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable"><i class="bi bi-plus"></i> Buat Barcode</button> --}}
          </h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Dasboard</a></li>
              <li class="breadcrumb-item">Master Data</li>
              <li class="breadcrumb-item active">Barcode</li>
            </ol>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">       
                    <div class="col-xxl-12 col-md-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title"></h5>
                                
                                <div class="table-responsive">
                                  <table id="datatables_paging_1" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th hidden>Id</th>
                                            <th hidden>id_cabang</th>
                                            <th>Apotek</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>barcode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @forelse($data_barang_barcode as $val)
                                            <tr>
                                                <td>
                                                    {{ $no }}
                                                </td>
                                                <td hidden>
                                                    {{ $val->id }}
                                                </td>
                                                <td class="id_cabang" hidden>
                                                    <input class="form-control" type="text" name="id_cabang[]" id="id_cabang{{ $no }}" value="{{ $val->kode_cabang }}" hidden/>
                                                    {{ $val->kode_cabang }}
                                                </td>
                                                <td class="nama_cabang">
                                                    <input class="form-control" type="text" name="nama_cabang[]" id="nama_cabang{{ $no }}" value="{{ $val->nama_cabang }}" hidden/>
                                                    {{ $val->nama_cabang }}
                                                </td>
                                                <td class="kode_produk">
                                                    <input class="form-control" type="text" name="kode_produk[]" id="kode_produk{{ $no }}" value="{{ $val->kode_produk }}" hidden/>
                                                    {{ $val->kode_produk }}
                                                </td>
                                                <td class="nama_produk">
                                                    <input class="form-control" type="text" name="nama_produk[]" id="nama_produk{{ $no }}" value="{{ $val->nama_produk }}" hidden/>
                                                    {{ $val->nama_produk }}
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" name="barcode[]" id="barcode{{ $no }}" value="{{ $val->barcode }}" hidden/>
                                                    {{ $val->barcode }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('qr_code.pdf', $val->id) }}" target="_blank" class="btn btn-success btn-sm">Cetak</a>
                                                    {{-- <a href="{{ route('generate', $val->id) }}" target="_blank" class="btn btn-success btn-sm">Buat</a> --}}
                                                </td>
                                            </tr>
                                            <?php $no++; ?>
                                            @empty
                                            <tr>
                                            <td colspan="6" class="text-center">Tidak ada data untuk saat ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

@endsection


