<!DOCTYPE html>
<html lang="en" style="margin: 30px">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Struk Penjualan</title>
</head>

<body>
    <table >
        <tr>
            <td width="0">
                <div align="center">
                    <font size="4">Apotek</font><br>
                    <u><font size="4">Sindangsari Farma</font></u><br>
                    {{ $data->alamat }} <br>
                    {{-- Nomor 21 --}}
                    {{-- =============================== --}}
                </div>
            </td> 
        </tr>
        <tr>
            <td>======================</td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="width: 10px;">Tanggal</td>
            <td style="width: 230px;" colspan="3">: {{ date('d/m/Y', strtotime($data->tgl_penjualan)) }}</td>
        </tr>
        <tr>
            <td style="width: 10px;">Nomor</td>
            <td style="width: 230px;" colspan="3">: {{  $data->kode_penjualan }}</td>
        </tr>
        <tr>
            <td colspan="4">--------------------------------------</td>
        </tr>
        @forelse($dataPenjualan as $val)
            <tr>
                <td colspan="5" style="font-size: 15px;">{{ $val->nama_produk }}</td>
            </tr>
            <tr>
                <td style="width: 280px;" align="right" colspan="2">{{ $val->qty }} x {{ number_format($val->harga) }}</td>
                <td style="width: 70px;" align="right">{{ number_format($val->qty * $val->harga) }}</td>
                <td style="width: 20px;"></td>   
            </tr>
        @empty

        @endforelse
        
        <tr>
            <td colspan="4">-----------------------------------  (+)</td>
        </tr>
        <tr>
            <td colspan="2" align="right">Sub Total</td>
            <td align="right">{{ number_format($dataPenjualanTotal->total) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" align="right">Pembulatan</td>
            <td align="right">{{ number_format($data->pembulatan) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">-----------------------------------  (+)</td>
        </tr>
        <tr>
            <td>Qty {{ $dataPenjualanTotal->total_item }}</td>
            <td align="right">Total</td>
            <td align="right">{{ number_format($dataPenjualanTotal_h->total_bayar) }}</td>
            <td></td>
        </tr>
        @if($data->cara_bayar == 'Tunai')
            <tr>
                <td></td>
                <td align="right">Cash</td>
                <td align="right">{{ number_format($dataPenjualanTotal_h->jml_bayar) }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td align="right">Debit</td>
                <td align="right">0</td>
                <td></td>
            </tr>
        @else
            <tr>
                <td></td>
                <td align="right">Cash</td>
                <td align="right">0</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td align="right">Debit</td>
                <td align="right">{{ number_format($dataPenjualanTotal->total) }}</td>
                <td></td>
            </tr>
        @endif
        <tr>
            <td colspan="4">------------------------------------  (-)</td>
        </tr>
        <tr>
            <td></td>
            <td align="right">Kembalian</td>
            <td align="right">{{ number_format($dataPenjualanTotal_h->kembali) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">======================</td>
        </tr>
        <tr>
            <td colspan="5">{{ date('d/m/Y', strtotime($data->tgl_penjualan)) }} {{ $data->waktu_penjualan }}</td>
        </tr>
        <tr>
            <td colspan="5">{{ $data->name }}</td> 
        </tr>
        
        <br>
        <tr>
            <td colspan="5" align="center">Obat/Barang yang sudah dibeli,</td> 
        </tr>
        <tr>
            <td colspan="5" align="center">tidak dapat dikembalikan.</td> 
        </tr>
        <tr>
            <td colspan="5" align="center">.:Terima Kasih:.</td> 
        </tr>

       
    </table>
    
   
  

    
    

       
</body>
</html>