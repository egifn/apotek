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
    <H2>Data Supplier</H2>

    <div>
        {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
        <br>
        <table>
            <thead>
                <tr style="background-color: skyblue">
                    <th>No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>CP</th>
                    <th>Telepon</th>  
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @forelse($data_supplier_pdf as $val)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $val->kode_supplier }}</td>
                    <td>{{ $val->nama_supplier }}</td>
                    <td>{{ $val->alamat }}</td>
                    <td>{{ $val->cp }}</td>
                    <td>{{ $val->tlp }}</td>
                    <td>{{ $val->email }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data untuk saat ini</td>
                </tr>
                 @endforelse
            </tbody>
        </table>
    </div>   
    
</body>
</html>