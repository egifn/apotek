@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Data Tuslah.xls");
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


<H2>Data Tuslah</H2>

<div>
    {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
    <br>
    <table>
        <thead>
            <tr style="background-color: skyblue">
                <th>No</th>
                <th>Nama Tuslah</th>
                <th>Harga Tuslah</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_tuslah_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->nama_jenis }}</td>
                <td align="right">{{ number_format($val->harga_jenis) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







