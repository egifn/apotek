<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanner</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    #reader {
        width: 600px;
    }
    #result {
        text-align: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    #scanResultTextbox {
        width: 250px; /* Sesuaikan lebar sesuai kebutuhan */
        margin-top: 1px; /* Tambahkan margin di atas textbox */
    }
    #kodeProdukTextbox {
        width: 250px; /* Sesuaikan lebar sesuai kebutuhan */
        margin-top: 1px; /* Tambahkan margin di atas textbox */
    }
    #namaProdukTextbox {
        width: 250px; /* Sesuaikan lebar sesuai kebutuhan */
        margin-top: 1px; /* Tambahkan margin di atas textbox */
    }
    #jumlahTextbox {
        width: 250px; /* Sesuaikan lebar sesuai kebutuhan */
        margin-top: 1px; /* Tambahkan margin di atas textbox */
    }
    #jumlahTextboxFisik {
        width: 250px; /* Sesuaikan lebar sesuai kebutuhan */
        margin-top: 1px; /* Tambahkan margin di atas textbox */
    }
    #lblTextbox { 
        margin-top: 10px; /* Tambahkan margin di atas textbox */
    }
    </style>
</head>
<body>
<main>
    <div id="reader"></div>
    <div id="result"></div> 
    <input type="text" id="scanResultTextbox" placeholder="Hasil Scan Barcode" readonly hidden>
    <label>Kode Produk</label> <input type="text" id="kodeProdukTextbox" placeholder="Kode Produk" readonly>
    <label>Nama Produk</label> <input type="text" id="namaProdukTextbox" placeholder="Nama Paroduk" readonly>
    <label>Jumlah Sistem</label> <input type="text" id="jumlahTextbox" placeholder="Jumlah Sistem" readonly>
    <label>Jumlah Fisik</label> <input type="text" id="jumlahTextboxFisik" placeholder="Jumlah Fisik" required>
    <br>
    <button type="button" class="btn btn-primary" id="button_simpan" onclick="simpan()"><i class="bi bi-save"></i> Simpan</button>
</main>


  
</body>
</html>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const scanner = new Html5QrcodeScanner('reader', {
        qrbox: {
            width: 250,
            height: 250,
        },
        fps: 20,
    });


    scanner.render(success, error);

    function success(result) {

        document.getElementById('result').innerHTML = `
        <h2>Barcode</h2>
        <p>${result}</p>
        `;

        // // Set hasil scan ke nilai textbox
        // document.getElementById('scanResultTextbox').value = result;

        // Set hasil scan ke nilai textbox
        const barcodeValue = result;
        document.getElementById('scanResultTextbox').value = barcodeValue;

        // Lakukan permintaan Ajax untuk mendapatkan detail produk berdasarkan barcode
        var url = '{{ url("barcode_scan/get_product_details") }}' + '/' + barcodeValue;
        var _this = $(this);
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: url,
            success: function(response) {
                console.log(response);
                _this.val('');
                // Isi nilai textbox lain dengan data dari response
                $('#kodeProdukTextbox').val(response.kode_produk);
                $('#namaProdukTextbox').val(response.nama_produk);
                $('#jumlahTextbox').val(response.qty);
            },
            error: function(error) {
                console.error(error);
            }
        });
        // ===================================================================================================

        scanner.clear();
        document.getElementById('reader').remove();
        document.getElementById('jumlahTextboxFisik').focus();
    }

    function error(err) {
        console.error(err);
    }

    function simpan(){
        if (document.getElementById('jumlahTextboxFisik').value == ""){
            alert("Jumlah Fisik harus disi...");
            document.getElementById('jumlahTextboxFisik').focus();
            return (false);
        }

        let kode_barcode = document.getElementById('scanResultTextbox').value;
        let kode_produk = document.getElementById('kodeProdukTextbox').value;
        let jml_sistem = document.getElementById('jumlahTextbox').value;
        let jml_fisik = document.getElementById('jumlahTextboxFisik').value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('barcode_scan/update.update') }}",
            data: {
                kode_barcode: kode_barcode,
                kode_produk: kode_produk,
                jml_sistem: jml_sistem,
                jml_fisik: jml_fisik,
            },
            success: function(response) {
                if (response.status === true) {
                    document.getElementById('kodeProdukTextbox').value = '';
                    document.getElementById('namaProdukTextbox').value = '';
                    document.getElementById('jumlahTextbox').value = '';
                    document.getElementById('jumlahTextboxFisik').value = '';
                    alert('Sukses, Data Berhasil disimpan...');
                }else{
                    alert('Gagal, Data tidak berhasil disimpan...');
                }
            }
        });
    }

</script>