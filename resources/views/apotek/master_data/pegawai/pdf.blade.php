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
    <H2>Data Pegawai</H2>

    <div>
        {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
        <br>
        <table>
            <thead>
                <tr style="background-color: skyblue">
                    <th>No</th>
                    <th>Kode Pegawai</th>
                    <th>NIK</th>
                    <th>Nama Pegawai</th>
                    <th>JK</th>
                    <th>Alamat</th>
                    <th>Telepon</th>  
                    <th>Email</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @forelse($data_pegawai_pdf as $val)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $val->kode_pegawai }}</td>
                    <td>{{ $val->nik_pegawai }}</td>
                    <td>{{ $val->nama_pegawai }}</td>
                    <td>{{ $val->jk }}</td>
                    <td>{{ $val->alamat }}</td>
                    <td>{{ $val->tlp }}</td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->jabatan }}</td>
                    <td>{{ $val->status_pegawai }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data untuk saat ini</td>
                </tr>
                 @endforelse
            </tbody>
        </table>
    </div>   
    
</body>
</html>