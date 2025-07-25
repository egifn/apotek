@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Klinik Pelayanan.xls");
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
    <b>LAPORAN KLINIK PELAYANAN</b>
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
                <th>Kode Pembayaran</th>
                <th>Tgl Pembayaran</th>
                <th>Nama Cabang</th>
                <th>Kode Pelayanan</th>
                <th>Nama Pelayanan</th>
                <th hidden>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_klinik_pelayanan_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->no_invoice }}</td>
                <td>{{ $val->tgl_invoice }}</td>
                <td>{{ $val->nama_cabang }}</td>
                <td>{{ $val->kode_jasa_p }}</td>
                <td>{{ $val->nama_jasa_p }}</td>
                <td align="right">{{ $val->jml_jasa_p }}</td>
                <td align="right">{{ ($val->harga_jasa_p) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk saat ini</td>
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







