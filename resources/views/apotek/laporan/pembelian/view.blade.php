@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Pembelian.xls");
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
    <b>LAPORAN PEMBELIAN</b>
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
                    <th>No SP</th>
                    <th>Tgl SP</th>
                    <th>No Faktur</th>
                    <th>Nama Cabang</th>
                    <th>Jenis SP</th>
                    <th>Pembelian</th>
                    <th>Transaksi</th>
                    <th>Tgl JT</th>
                    <th>Supplier</th>
                    <th>Diskon All</th>
                    <th>Status bayar</th>
                    <th>Tipe</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    {{-- <th>Diskon (%)</th> --}}
                    <th>Diskon</th>
                    {{-- <th>PPN (%)</th> --}}
                    <th>PPN</th>
                    <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_pembelian_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->kode_pembelian }}</td>
                <td>{{ $val->tgl_pembelian }}</td>
                <td>{{ $val->no_faktur }}</td>
                <td>{{ $val->nama_cabang }}</td>
                <td>{{ $val->jenis_surat_pesanan }}</td>
                <td>{{ $val->pembelian }}</td>
                <td>{{ $val->jenis_transaksi }}</td>
                <td>{{ $val->tgl_jatuh_tempo }}</td>
                <td>{{ $val->nama_supplier }}</td>
                <td>{{ $val->diskon }}</td>
                @if($val->status_pembayaran == 0)
                    <td>Hutang</td>
                @else
                    <td>Lunas</td>
                @endif
                <td>{{ $val->tipe }}</td>
                <td>{{ $val->kode_produk }}</td>
                <td>{{ $val->nama_produk }}</td>
                <td align="right">{{ $val->qty_beli }}</td>
                <td align="right">Rp. {{ number_format($val->harga) }}</td>
                {{-- <td align="right">{{ $val->diskon }}</td> --}}
                <td align="right">Rp. {{ number_format($val->diskon_item_rp) }}</td>
                {{-- <td align="right">{{ $val->ppn }}</td> --}}
                <td align="right">Rp. {{ number_format($val->ppn_item_rp) }}</td>
                <td align="right">Rp. {{ number_format($val->total) }}</td>
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







