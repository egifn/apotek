@php
    header("Content-type: application/vnd-ms-excel"); 
    header("Content-Disposition: attachment; filename=Laporan Data Pengguna.xls");
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


<H2>Data Pengguna</H2>

<div>
    {{-- <b>Periode: {{ request()->tanggal_ex }} </b> --}}
    <br>
    <table>
        <thead>
            <tr style="background-color: skyblue">
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Username</th>
                <th>Lokasi</th>  
                <th>Tipe</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @forelse($data_pengguna_excel as $val)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $val->name }}</td>
                <td>{{ $val->email }}</td>
                <td>{{ $val->username }}</td>
                <td>{{ $val->nama_cabang }}</td>
                <td>{{ $val->nama }}</td>
                <td>{{ $val->status_user }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data untuk saat ini</td>
            </tr>
             @endforelse
        </tbody>
    </table>
</div>                  







