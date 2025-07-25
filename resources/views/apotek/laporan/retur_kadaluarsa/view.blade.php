@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Retur Kadaluarsa.xls");
@endphp

<style>
table, td, th {
  border: 1px outset gray;
}

table {
  width: 100%;
  border-collapse: collapse;
}
</style>


<hr>
<div class="col-lg-12" style="text-align: center;">
    <b>LAPORAN RETUR KADALUARSA</b>
</div>
{{-- <div class="col-lg-12" style="text-align: center;">
    Per Tanggal: {{ $date }}
</div> --}}
<hr>

<div>
    {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
    <br>
    <table>
        <thead>
            <tr style="background-color: rgb(195, 199, 200)">
                <th>No</th>
                <th>Tgl Transaksi</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Jenis</th>
                <th>Qty</th>
                <th>Keterangan</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_kadaluarsa_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->tgl_transaksi }}</td>
                <td>{{ $val->kode_produk }}</td>
                <td>{{ $val->nama_produk }}</td>
                <td>{{ $val->nama_jenis }}</td>
                <td>{{ $val->qty }} {{ $val->nama_unit }}</td>
                <td>{{ $val->keterangan }}</td>
                <td>{{ $val->nama_supplier }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







