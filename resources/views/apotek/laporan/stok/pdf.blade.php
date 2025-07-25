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
    <b>LAPORAN STOK BARANG</b>
    </div>
    <div class="col-lg-12" style="text-align: center;">
    Per Tanggal: {{ $date }}
    </div>
    <hr>
    <div>
        {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
        <br>
        @if(Auth::user()->type == '1') <!-- Super Admin dan Admin -->
            <table>
                <thead>
                    <tr style="background-color: rgb(195, 199, 200)">
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Stok</th>
                        <th>Harga Pokok</th>
                        <th>Nilai Total</th>
                        <th>Distributor</th>
                        <th>Nama Cabang</th>
                        <th>Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @forelse($data_stok_pdf as $val)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $val->kode_produk }}</td>
                        <td>{{ $val->nama_produk }}</td>
                        <td align="right">{{ $val->qty }}</td>
                        <td align="right">Rp. {{ number_format($val->harga_beli) }}</td>
                        <td align="right">Rp. {{ number_format($val->qty * $val->harga_beli) }}</td>
                        <td>{{ $val->nama_supplier }}</td>
                        <td align="center">{{ $val->nama_cabang }}</td>
                        <td>{{ $val->tgl_kadaluarsa }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
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
                </tfoot>
            </table>
        @elseif(Auth::user()->type == '2') <!-- Admin -->
            <table>
                <thead>
                    <tr style="background-color: rgb(195, 199, 200)">
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Stok</th>
                        {{-- <th hidden>Harga Pokok</th>
                        <th hidden>Nilai Total</th> --}}
                        <th>Distributor</th>
                        <th>Nama Cabang</th>
                        <th>Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    @forelse($data_stok_pdf as $val)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $val->kode_produk }}</td>
                        <td>{{ $val->nama_produk }}</td>
                        <td align="right">{{ $val->qty }}</td>
                        {{-- <td align="right" hidden>Rp. {{ number_format($val->harga_beli) }}</td>
                        <td align="right" hidden>Rp. {{ number_format($val->qty * $val->harga_beli) }}</td> --}}
                        <td>{{ $val->nama_supplier }}</td>
                        <td align="center">{{ $val->nama_cabang }}</td>
                        <td>{{ $val->tgl_kadaluarsa }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
                    </tr>
                    @endforelse
                </tbody>
                {{-- <tfoot hidden>
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
        @endif
        
    </div>   
    
</body>
</html>