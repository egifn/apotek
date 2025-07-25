@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Stok Produk.xls");
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


<H2>Data Produk</H2>

<div>
    {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
    <br>
    <table>
        <thead>
            <tr style="background-color: skyblue">
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Komposisi</th>
                <th>Jenis</th>
                <th>Pembelian</th>
                <th>Tipe</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Margin (%)</th>
                <th>Margin (Rp)</th>
                <th>Harga Jual</th>
                <th>Tgl kadaluarsa</th>
                <th>Distributor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_obat_excel as $val)
            <tr>
                <td>{{ $val->kode_produk }}</td>
                <td>{{ $val->nama_produk }}</td>
                <td>{{ $val->komposisi }}</td>
                <td>{{ $val->nama_jenis }}</td>
                <td>{{ $val->kode_pembelian }}</td>
                <td>{{ $val->tipe }}</td>
                <td>{{ $val->qty }}</td>
                <td>{{ $val->nama_unit }}</td>
                <td>{{ $val->harga_beli }}</td>
                <td>{{ $val->margin_persen }}</td>
                <td>{{ $val->margin_rp }}</td>
                <td>{{ $val->harga_jual }}</td>
                <td>{{ $val->tgl_kadaluarsa }}</td>
                <td>{{ $val->nama_supplier }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







