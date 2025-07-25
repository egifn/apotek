@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Data Jasa/Pelayanan.xls");
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


<H2>Data Jasa/Pelayanan</H2>

<div>
    {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
    <br>
    <table>
        <thead>
            <tr style="background-color: skyblue">
                <th>No</th>
                <th>Kode</th>
                <th>Nama Jasa/Pelayanan</th>
                <th>Nama Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_jasa_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->kode_jasa_p }}</td>
                <td>{{ $val->nama_jasa_p }}</td>
                <td align="right">{{ $val->harga }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







