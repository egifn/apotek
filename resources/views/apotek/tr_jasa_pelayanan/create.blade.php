
@extends('layouts.apotek.admin')

@section('title')
    <title>Tambah Transaksi Pelayanan/Jasa</title>
@endsection

@section('content')
<main id="main" class="main">

	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Tambah Transaksi Pelayanan/Jasa
      </h1>
      <nav hidden>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Transaksi</li>
          <li class="breadcrumb-item">Pelayanan/Jasa</li>
          <li class="breadcrumb-item active">Tambah Transaksi Pelayanan/Jasa</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
        	<div class="card">
            	<div class="card-body">
                	<form>
		                <br>
		                <div class="row mb-3">
		                  <label for="inputTgl" class="col-sm-2 col-form-label">Tgl Transaksi</label>
		                  <div class="col-sm-4">
		                    <input type="text" name="tgl_transaksi" id="tgl_transaksi" class="form-control" required>
		                  </div>
		                </div>
		                <div class="row mb-3">
		                  <label for="inputKodeTransaksi" class="col-sm-2 col-form-label">Kode Transaksi</label>
		                  <div class="col-sm-4">
		                    <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" required>
		                  </div>
		                </div>
		                
	            	</form>  
                 
            	</div>
        	</div>
        </div>
      </div>
    </section>

</main>
@endsection