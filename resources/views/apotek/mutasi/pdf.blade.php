<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Serah Terima Barang</title>
</head>

<body>

    {{-- <H>Surat Pesanan</H2>
    Nomor SP    : {{ $data->kode_mutasi }} --}}
    <table>
        <tr>
            <td width="530">
                <div align="center">
                    <u><font size="4">SURAT SERAH TERIMA BARANG</font></u><br>
                    Nomor : {{ $data->kode_mutasi }}<br>
            </div>
            </td> 
        </tr>
    </table>
    <br>
    <br>
    
    <table border='1' style="border-collapse: collapse; border-color: black; width: 100%; font-size: 15px;" >
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Apotek Asal</th>
                <th>Apotek Tujuan</th>
            </tr>
        </thead>
        <tbody>
            {{ $no = 1 }}
            @forelse($data_mutasi as $val)
            <tr>
                <td>{{ $no }}</td>
                <td>{{ $val->kode_barang_setelah_mutasi }}</td>
                <td>{{ $val->nama_produk }}</td>
                <td>{{ $val->qty_mutasi }}</td>
                <td>{{ $val->nama_unit }} </td>
                <td>{{ $val->asal }} </td>
                <td>{{ $val->tujuan }} </td>
            </tr>
            {{ $no++ }}
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <br>
 

    <br>

    <table>    
        <tr>
            <td width="320">
            <td width="190" align="center">Bandung, {{  date('d-M-Y', strtotime($data->tgl_mutasi)) }}</td>
        </tr>
        <tr>
            <td width="100" align="center">Penerima</td>
            
            <td width="190" align="center">Pengirim</td>
        </tr>
        <br>
        <br>
        <br>
        <tr>
            <td width="100" align="center">(_____________________)</td>
            <td width="190" align="center"><u>({{ $data->name }})</u></td>
        </tr>
        <tr>
            <td width="320">
            <td width="190" align="center"></td>
        </tr>
    </table>
    <br>
    <br>
    

       
</body>
</html>