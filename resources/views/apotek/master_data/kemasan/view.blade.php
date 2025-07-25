@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Data Satuan Unit Produk.xls");
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


<H2>Data Satuan Unit Produk</H2>

<div>
    {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
    <br>
    <table>
        <thead>
            <tr style="background-color: skyblue">
                <th>No</th>
                <th>Nama Satuan Unit</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_unit_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->nama_unit }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







