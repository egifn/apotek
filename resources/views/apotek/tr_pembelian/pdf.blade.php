<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pesanan</title>
</head>

<body>

    {{-- <H>Surat Pesanan</H2>
    Nomor SP    : {{ $data->kode_pembelian }} --}}
    <table>
        <tr>
            <td width="530">
                <div align="center">
                    <u><font size="4">SURAT PESANAN NARKOTIKA</font></u><br>
                    Nomor : {{ $data->kode_pembelian }}<br>
            </div>
            </td> 
        </tr>
    </table>
    <br>
    <br>
    <table>
        <tr>
            <td colspan="3"> Yang bertanda tangan di bawah ini :</td>
        </tr>
        <tr>
            <td width="100">Nama</td>
            <td width="8">:</td>
            <td width="400">{{ $data->name }}</td>
        </tr>
        <tr>
            <td width="100">Jabatan</td>
            <td width="8">:</td>
            <td width="400">{{ $data->jabatan }}</td>
        </tr>
        <br>
        <tr>
            <td colspan="3">Mengajukan pesanan Narkotika kepada :</td>
        </tr>
        <tr>
            <td width="100">Nama Distributor</td>
            <td width="8">:</td>
            <td width="400">{{ $data->nama_supplier }}</td>
        </tr>
        <tr>
            <td width="100">Alamat</td>
            <td width="8">:</td>
            <td width="400">{{ $data->alamat }}</td>
        </tr>
        <tr>
            <td width="100">Telp</td>
            <td width="8">:</td>
            <td width="400">{{ $data->tlp }}</td>
        </tr>
        <br>
        <tr>
            <td colspan="3"> dengan Narkotika yang dipesan adalah :</td>
        </tr>
    </table>
    
    <table border='1' style="border-collapse: collapse; border-color: black; width: 100%;" >
        <thead>
            <tr>
                {{-- <th>No</th> --}}
                <th width="200">Nama Obat</th>
                <th width="100">Bentuk Sediaan</th>
                <th width="150">Kekuatan/Potensi</th>
                <th width="50">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataPembelian as $val)
            <?php $angka = $val->qty_kecil ?>
            <tr>
                <td>{{ $val->nama_produk }}</td>
                <td></td>
                <td>{{ $val->nama_unit }}</td>
                <td>{{ $val->qty_kecil }} ({{ terbilang($angka) }})</td>
                    
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <br>
    <table >
        <tr>
            <td colspan="3">Narkotika tersebut akan dipergunakan untuk :</td>
        </tr>
    
        <tr>
            <td width="100">Nama Sarana</td>
            <td width="8">:</td>
            <td width="400">Apotek</td>
        </tr>
        
        <tr>
            <td width="100">Alamat Sarana</td>
            <td width="8">:</td>
            <td width="400">{{ $data->alamat_cabang }}</td>
        </tr>

    </table>

    <br>
    <br>
    <br>

    <table>    
        <tr>
            <td width="320">
            <td width="190" align="center">Bandung, 11 Desember 2022</td>
        </tr>
        <br>
        <br>
        <br>
        <br>
        <br>
        <tr>
            <td width="320">
            <td width="190" align="center"><u>{{ $data->name }}</u></td>
        </tr>
        <tr>
            <td width="320">
            <td width="190" align="center">No. SIKA/SIPA/NIP</td>
        </tr>
    </table>
    <br>
    <br>
    <table> 
        <tr>
            <td>Catt:</td>
        </tr>
        <tr>
            <td>- Satu surat pesanan hanya berlaku untuk satu jenis Narkotika.</td>
        </tr>
        <tr>
            <td>- Surat Pesanan dibuat sekurang-kurangnya 3 (tiga) rangkap.</td>
        </tr>
    </table>

       
</body>
</html>