@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Penjualan Panel Per Transaksi.xls");
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
    <b>LAPORAN PENJUALAN PANEL PER TRANSAKSI</b>
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
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Jenis</th>
                                        <th>cara bayar</th>
                                        <th>Bank</th>
                                        <th>Subtotal</th>
                                        <th>Pembulatan</th>
                                        <th>Total Bayar</th>
                                        <th>jml_bayar</th>
                                        <th>Kembali</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_penjualan_pertransaksi_excel as $val)
            <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $val->kode_penjualan }}</td>
                    <td>{{ $val->tgl_penjualan }}</td>
                    <td>{{ $val->waktu_penjualan }}</td>
                    <td>{{ $val->jenis_penjualan }}</td>
                    <td>{{ $val->cara_bayar }}</td>
                    <td>{{ $val->bank }}</td>
                    <td align="right">{{ ($val->subtotal) }}</td>
                    <td align="right">{{ ($val->pembulatan) }}</td>
                    <td align="right">{{ ($val->total_bayar) }}</td>
                    <td align="right">{{ ($val->jml_bayar) }}</td>
                    <td align="right">{{ ($val->kembali) }}</td>
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







