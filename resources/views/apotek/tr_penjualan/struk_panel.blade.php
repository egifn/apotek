<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kuitansi Panel</title>
</head>

<body>

    {{-- <H>Surat Pesanan</H2>
    Nomor SP    : {{ $data->kode_pembelian }} --}}
    <table>
        <tr>
            <td width="70">
                {{-- <img src="assets/img/sf.jpg" style="width: 80px; height: 70px" > --}}
                <img src="{{ ('sf.jpg') }}" style="width: 80px; height: 70px" >
            </td>
            <td width="360">
                <div>
                    <b>Apotik Sindangsari Farma</b><br>
                    {{-- <b>No. Surat Izin Apotek: </b><br> --}}
                    {{-- <b>{{ $data->alamat }}</b><br>
                    <b>{{ $data->tlp }}</b> --}}
                    <b>{{ $data->alamat }}</b><br>
                    <b>Kota Bandung</b><br>
                    <b>Telp. {{ $data->tlp }}</b>
                </div>
            </td>
            <td>
                <br>
                <br>
                <b>K U I T A N S I</b>    
            </td> 
        </tr>
        <tr>
            <td colspan="3">
                <hr>
            </td>
        </tr>
    </table>
   
    <table>
        <tr>
            <td width="90">No Resep</td>
            <td width="8">:</td>
            <td width="160"></td>

            <td width="70">Kasir</td>
            <td width="8">:</td>
            <td width="160">{{ $data->name }}</td>
        </tr>
        <tr>
            <td width="90">Nama Pelanggan</td>
            <td width="8">:</td>
            <td width="160"></td>

            <td width="70">Tanggal</td>
            <td width="8">:</td>
            <td width="160">{{ date('d/m/Y', strtotime($data->tgl_penjualan)) }}</td>
        </tr>
        <tr>
            <td width="90">No Tlp</td>
            <td width="8">:</td>
            <td width="160"></td>

            <td width="70">No Faktur</td>
            <td width="8">:</td>
            <td width="160">{{ $data->kode_penjualan }}</td>
        </tr>
        <tr>
            <td width="90">Alamat</td>
            <td width="8">:</td>
            <td width="160"></td>

            <td width="70">Pembayaran</td>
            <td width="8">:</td>
            <td width="160">{{ $data->cara_bayar }}</td>
        </tr>
        <tr>
            <td width="90">Nama Dokter</td>
            <td width="8">:</td>
            <td width="160"></td>

            <td width="70">Tgl Jatuh Tempo</td>
            <td width="8">:</td>
            <td width="160">{{ date('d-M-Y', strtotime($data->tgl_jatuh_tempo)) }}</td>
        </tr>
        <br>
    </table>
    
    <table border='1' style="border-collapse: collapse; border-color: black; width: 100%;" >
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>qty</th>
                <th>Satuan</th>
                <th>Batch & ED</th>
                <th>Harga Satuan</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($dataPenjualan as $val)
            <tr>
                <td>{{ $no++}}</td>
                <td>{{ $val->nama_produk }}</td>
                <td align="center">{{ $val->qty }}</td>
                <td>{{ $val->nama_unit }}</td>
                <td></td>
                <td align="right">{{ number_format($val->harga) }}</td>
                <td align="right">{{ $val->diskon }}%</td>
                <td align="right">{{ number_format($val->harga*$val->qty-$val->diskon_rp) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td align="center">{{ $dataPenjualanTotal->total_item }}</td>
                <td colspan="2"></td>
                <td colspan="2" align="right">Total :</td>
                <td align="right">{{ number_format($dataPenjualanTotal->total) }}</td>
            </tr>
            <tr>
                <td colspan="5"><b>Terbilang :</b> {{ terbilang($data->total_bayar) }} rupiah</td>
                <td colspan="2" align="right">Diskon :</td>
                <td align="right">0</td>
            </tr>
            <tr>
                <td colspan="5"><b>Catatan :</b> BARANG SUDAH TERMASUK PPN</td>
                <td colspan="2" align="right">Pajak :</td>
                <td align="right">0</td>
            </tr>

            <tr>
                <td colspan="5"></td>
                <td colspan="2" align="right">Pembulatan :</td>
                <td align="right">{{ number_format($data->pembulatan) }}</td>
            </tr>

            <tr>
                <td colspan="5"></td>
                <td colspan="2" align="right">Grand Total :</td>
                <td align="right">{{ number_format($data->total_bayar) }}</td>
            </tr>
        </tfoot>
    </table>
    <br>
    <br>
    <table>
        <tr>
            <td width="50"></td>
            <td align="center" width="150">Penerima/Pembeli</td>
            <td width="100"></td>
            <td align="center" width="150">Apotek Sindangsari Farma</td>
            <td width="50"></td>
        </tr>
        <br>
        <br>
        <br>
        <tr>
            <td width="50"></td>
            <td align="center" width="150">(_____________________)</td>
            <td width="100"></td>
            <td align="center" width="150"><u>{{ $data->name }}</u></td>
            <td width="50"></td>
        </tr>
        
        
    </table>   
</body>
</html>