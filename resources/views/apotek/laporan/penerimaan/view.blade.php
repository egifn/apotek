@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Penerimaan Supplier.xls");
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
    <b>LAPORAN PENERIMAAN SUPPLIER</b>
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
                <th>Kode Penerimaan</th>
                <th>Tgl Penerimaan</th>
                <th>Nama Cabang</th>
                <th>No SP</th>
                <th>Jenis SP</th>
                <th>Pembelian</th>
                <th>Supplier</th>
                <th>No Faktur</th>
                <th>Tgl Jatuh Tempo</th>
                <th>Diskon All</th>
                <th>Harga Lama</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Jml Pesan</th>
                <th>Jml Terima</th>
                <th>Harga Baru</th>
                {{-- <th>Diskon (%)</th> --}}
                <th>Diskon</th>
                <th>Ppn</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_penerimaan_excel as $val)
            <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $val->kode_penerimaan }}</td>
                    <td>{{ $val->tgl_penerimaan }}</td>
                    <td>{{ $val->nama_cabang }}</td>
                    <td>{{ $val->kode_pembelian }}</td>
                    <td>{{ $val->jenis_surat_pesanan }}</td>
                    <td>{{ $val->pembelian }}</td>
                    <td>{{ $val->nama_supplier }}</td>
                    <td>{{ $val->no_faktur }}</td>
                    <td>{{ $val->tgl_jatuh_tempo }}</td>
                    <td>{{ $val->diskon_rupiah }}</td>
                    <td align="right">{{ $val->harga_beli_lama }}</td>
                    <td>{{ $val->kode_produk }}</td>
                    <td>{{ $val->nama_produk }}</td>
                    <td align="right">{{ $val->jml_beli }}</td>
                    <td align="right">{{ $val->jml_terima }}</td>
                    <td align="right">{{ $val->harga_beli }}</td>
                    {{-- <td align="right">{{ $val->diskon }}</td> --}}
                    <td align="right">{{ $val->diskon_rp }}</td>
                    <td align="right">{{ $val->ppn_rp }}</td>
                    <td align="right">{{ $val->subtotal }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="21" class="text-center">Tidak ada data untuk saat ini</td>
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







