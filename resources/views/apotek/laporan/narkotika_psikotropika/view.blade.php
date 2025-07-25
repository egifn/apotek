@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Narkotika dan Psikotropika.xls");
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
    <b>Laporan Narkotika dan Psikotropika</b>
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
                <th>Kode Obat</th>
                <th>Nama Obat</th>
                <th>Bentuk Sediaan</th>
                <th>Persediaan Awal</th>
                <th>Persediaan Akhir</th>
                <th>Jumlah Terima</th>
                <th>Jumlah diserahkan</th>
                <th>Nama Cabang</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->kode_produk }}</td>
                <td>{{ $val->nama_produk }}</td>
                <td>{{ $val->nama_unit }}</td>
                <td align="right">{{ $val->stok_awal }}</td>
                <td align="right">{{ $val->stok_akhir }}</td>
                <td align="right">{{ $val->stok_masuk }}</td>
                <td align="right">{{ $val->stok_keluar }}</td>
                <td>{{ $val->nama_cabang }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
        {{-- <tfoot>
            <tr style="background-color: rgb(195, 199, 200)">
                <td colspan="5" align="right"><b>Total: Rp.</b></td>
                <td align="right"><b>{{ ($data_stok_total_excel->jumlah) }}</b></td>
                <td></td>
            </tr>
            <tr style="background-color: rgb(195, 199, 200)">
                <td colspan="5" align="right"><b>Grand Total: Rp.</b></td>
                <td align="right"><b>{{ ($data_stok_total_excel->jumlah) }}</b></td>
                <td></td>
            </tr>
        </tfoot> --}}
    </table>
</div>                  







