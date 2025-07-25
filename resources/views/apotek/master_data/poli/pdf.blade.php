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
    <H2>Data Poli</H2>

    <div>
        {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
        <br>
        <table>
            <thead>
                <tr style="background-color: skyblue">
                    <th>No</th>
                    <th>Nama Poli</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                @forelse($data_poli_pdf as $val)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $val->nama_poli }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Tidak ada data untuk saat ini</td>
                </tr>
                 @endforelse
            </tbody>
        </table>
    </div>   
    
</body>
</html>