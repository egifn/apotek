@section('js')
<script type="text/javascript">
    $('#savedatas').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("stok_opname_store.store") }}',
            type: 'post',
            data: $(this).serializeArray(),
            success: function(data){
                console.log(data);
            }
        });
    });
</script>
@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Stok Opname</title>
@endsection

@section('content')

<main id="main" class="main">
    <!-- HIDE SIDEBAR -->
    <style type="text/css">
        .sidebar {
            left: -300px;
        }

        .toggle-sidebar #main,
        .toggle-sidebar #footer {
            margin-left: 0;
        }

        main,
        #footer {
            margin-left: 0px !important;
        }

    </style>
    <!-- END HIDE SIDEBAR -->

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Stok Opname
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Stok Opname</li>
        </ol>
      </nav>
    </div>

    <section class="section">
        <div class="row" id="data_produk">
            <div class="col-lg-12">
                <form action="{{ route('stok_opname_store.store') }}" method="post" onkeypress="return event.keyCode != 13" enctype="multipart/form-data">
                @csrf  
                    <div class="card">
                        <div class="card-body">
                            <br>
                            <div class="row mb-3">
                                <div class="row mb-3">
                                    <label class="col-sm-1 col-form-label">Tanggal</label>
                                    <div class="col-sm-2">
                                    <input type="text" name="tgl" id="tgl" class="form-control" value="{{ $date }}" required readonly>
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
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>No Batch</th>
                                            <th>Tgl Exp</th>
                                            <th>Satuan</th>
                                            <th>Jml Sistem</th>
                                            <th>Jml Fisik</th>
                                            <th hidden>Selisih</th>
                                            <th hidden>Id User Input</th>
                                            <th hidden>User Input</th>  
                                            <th hidden>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no=1 ?>
                                        @forelse ($data_obat as $val)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>
                                                <input type="hidden" class="form-control" name="kode_produk[]" id="kode_produk" style="font-size: 13px; text-align: right; width: 100%;" value="{{ $val->kode_produk }}">
                                                {{ $val->kode_produk }}
                                            </td>
                                            <td>{{ $val->nama_produk }}</td>
                                            <td>{{ $val->no_batch }}</td>
                                            <td>{{ $val->tgl_kadaluarsa }}</td>
                                            <td>{{ $val->nama_unit }}</td>
                                            <td>
                                                <input type="hidden" class="form-control" name="qty_sistem[]" id="qty_sistem" style="font-size: 13px; text-align: right; width: 100%;" value="{{ $val->qty }}">
                                                {{ $val->qty }}
                                            </td>
                                            <td align="center">
                                                <input type="number" class="form-control" name="qty_fisik[]" id="qty_fisik" style="height: 20px; width: 100px; text-align: right;" value="{{ $val->qty }}">
                                            </td>
                                            <td align="center" hidden>
                                                <input type="text" class="form-control" name="selisih[]" id="selisih" style="height: 20px; width: 100px; text-align: right;" value="0" readonly>
                                            </td>
                                            <td hidden>{{ $val->id_user_input }}</td>
                                            <td hidden>{{ $val->name }}</td>
                                            <td align="center" hidden>
                                            <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                                            </td>
                                        </tr>
                                        <?php $no++ ?>
                                        @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

</main>
@endsection



@section('js')
 
    
@endsection()