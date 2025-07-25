@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Stok Opname.xls");
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
    <b>LAPORAN STOK OPNAME</b>
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
                <th>Kode</th>
                <th>Tgl Stok Opname</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Jml Sistem</th>
                <th>Jml Fisik</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($dataOpname as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->kode_opname }}</td>
                <td>{{ $val->tgl_opname }}</td>
                <td>{{ $val->kode_produk }}</td>
                <td>{{ $val->nama_produk }}</td>
                <td>{{ $val->jml_sistem }}</td>
                <td>{{ $val->jml_fisik }}</td>
                <td>{{ $val->selisih }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







