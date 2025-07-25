<style>
    @page { margin: 20px 1px 20px 45px; }
    body { margin: 0px; }

    .barcode-container {
        width: 100%;
    }

    .barcode-item {
        display: flex;
        width: 48%; /* Atur lebar sesuai kebutuhan Anda */
        box-sizing: border-box;
        margin-bottom: 20px;
    }

    .barcode-image {
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
        margin-left: 60px;
    }

    .barcode-info {
        margin-bottom: 53px;
        display: inline-block;
        vertical-align: top;
        margin-left: 58px;
        text-align: center;
        font-size: 10px;
    }
</style>

<div class="barcode-container">
    @php
    $no=1;
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    @endphp
    @foreach ($qr_data as $data)
        <div class="barcode-item">
            <br><br>
            <img class="barcode-image" src="data:image/png;base64, {!! base64_encode(QrCode::format('svg')->size(60)->generate($data->barcode)) !!} ">
            <div class="barcode-info">
                
                    {{$data->barcode}}<br>
                    {{$data->kode_produk}}<br>
                    {{$data->nama_produk}}<br>
                    Rp. {{number_format($data->harga_jual)}}
                
            </div>
        </div>
    @endforeach
</div>