<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>
    table, td, th {
      border: 1px outset gray;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    </style>

<body>
    <hr>
    <div class="col-lg-12" style="text-align: center;">
    <b>LAPORAN PENJUALAN PANEL (Per Obat)</b>
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
                    <th>Tgl Transaksi</th>
                    <th>Nama Cabang</th>
                    <th>Jenis Penjualan</th>
                    <th>Tipe</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Satuan</th>
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
                @forelse($data_penjualan_pdf as $val)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $val->kode_penjualan }}</td>
                    <td>{{ $val->tgl_penjualan }}</td>
                    <td>{{ $val->nama_cabang }}</td>
                    <td>{{ $val->jenis_penjualan }}</td>
                    <td>{{ $val->tipe }}</td>
                    <td>{{ $val->kode_produk }}</td>
                    <td>{{ $val->nama_produk }}</td>
                    <td align="right">{{ $val->qty }}</td>
                    <td>{{ $val->nama_unit }}</td>
                    <td align="right">{{ number_format($val->harga) }}</td>
                    {{-- <td align="right">{{ $val->diskon }}</td> --}}
                    <td align="right">{{ number_format($val->diskon_rp) }}</td>
                    {{-- <td align="right">{{ $val->ppn }}</td> --}}
                    <td align="right">{{ number_format($val->ppn_rp) }}</td>
                    <td align="right">{{ number_format($val->total) }}</td>
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
                    <td align="right"><b>Rp. {{ number_format($data_stok_total_pdf->jumlah) }}</b></td>
                    <td></td>
                </tr>
                <tr style="background-color: rgb(195, 199, 200)">
                    <td colspan="5" align="right"><b>Grand Total: Rp.</b></td>
                    <td align="right"><b>Rp. {{ number_format($data_stok_total_pdf->jumlah) }}</b></td>
                    <td></td>
                </tr>
            </tfoot> --}}
        </table>
    </div>   
    
</body>
</html>