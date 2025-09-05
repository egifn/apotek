@extends('layouts.coffeshop.admin')

@section('title')
    <title>Tambah Transaksi Pembelian</title>
@endsection

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h4 class="mb-0">
            Tambah Transaksi Pembelian
        </h4>
    </div>
    <br>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('coffeshop.master.pembelian.store') }}" method="POST" id="pembelianForm">
                            @csrf
                            <br>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Supplier</label>
                                <div class="col-sm-4">
                                    <select name="supplier_id" id="cari_supplier" class="form-control" style="width: 100%" required>
                                    
                                    </select>
                                </div>
                                
                                <div class="col-sm-2"></div>
                                <label class="col-sm-2 col-form-label">Jenis Transaksi</label>
                                <div class="col-sm-2">
                                    <select name="jenis" id="jenis" class="form-select" style="height: 30px; font-size: 14px;" required>
                                    <option value="">Pilih...</option>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Kredit">Kredit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-8"></div>

                                <label class="col-sm-2 col-form-label">Termin</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                    <input type="number" name="termin" id="termin" class="form-control" value="0" style="height: 30px; font-size: 14px; text-align: center;" min="0">
                                    <span class="input-group-text" style="height: 30px;">Hari</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-8"></div>
                                <label class="col-sm-2 col-form-label">Tgl. Jatuh Tempo</label>
                                <div class="col-sm-2">
                                    <input type="text" name="jt" id="jt" class="form-control" 
                                    value="{{ date('d/m/Y', strtotime(Carbon\Carbon::today()->toDateString())) }}" 
                                    style="height: 30px; font-size: 14px; text-align: center;" required readonly>
                                    <input type="hidden" name="jt_formatted" id="jt_formatted" value="{{ date('Y-m-d', strtotime(Carbon\Carbon::today()->toDateString())) }}">
                                </div>
                            </div>

                            <hr style="border:0; height: 1px; background-color: black;">  

                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label><i class="bi bi-upc-scan lg-6"></i> Cari produk</label>
                                    <select name="produk_id" id="cari_produk" class="form-control" style="width: 100%">
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="datatabel" class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Stok Gudang</th>
                                        <th>Harga Beli</th>
                                        <th>Jml</th>
                                        <th>Satuan</th>
                                        <th>Jml Beli</th>
                                        <th>Diskon (%)</th>
                                        <th>Diskon (Rp)</th>
                                        <th>PPN (%)</th>
                                        <th>PPN (Rp)</th>
                                        <th style="text-align: right;">Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tabledata" class="tabledata">
                                    
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="10" style="text-align: right;">Total:</th>
                                        <th></th>
                                        <th style="text-align: right;" id="grand_total">0</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td ></td>
                                        <td colspan="4">
                                            <button type="submit" class="btn btn-success btn-sm" style="width: 100%;" id="submitBtn">
                                                <span id="submitSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                                <span id="submitText">Simpan Transaksi</span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Alert Container -->
<div id="alertContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyimpan transaksi pembelian ini?</p>
                <div class="transaction-summary">
                    <p><strong>Supplier:</strong> <span id="confirmSupplier"></span></p>
                    <p><strong>Jenis Transaksi:</strong> <span id="confirmJenis"></span></p>
                    <p><strong>Total Pembelian:</strong> Rp <span id="confirmTotal"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmSave">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- jQuery (wajib untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Fungsi untuk menampilkan alert dinamis
        function createDynamicAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            
            const alertDiv = document.createElement('div');
            alertDiv.id = alertId;
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            alertContainer.appendChild(alertDiv);
            
            // Otomatis hilang setelah 5 detik
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    const bsAlert = new bootstrap.Alert(alertElement);
                    bsAlert.close();
                }
            }, 5000);
        }

        $(document).ready(function() {
            // aktifkan select2 untuk supplier
            $('#cari_supplier').select2({
                placeholder: "Cari Supplier...",
                allowClear: true,
                ajax: {
                    url: '{{ route("coffeshop.master.pembelian.getSupplier") }}', 
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term } 
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.kode_supplier + ' | ' + item.nama_supplier,
                                    data: item
                                }
                            })
                        };
                    }
                }
            });

            $('#cari_produk').select2({
                placeholder: "Cari Produk...",
                allowClear: true,
                ajax: {
                    url: '{{ route("coffeshop.master.pembelian.getProduk") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term }
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.id + ' | ' + item.name,
                                    data: item
                                }
                            })
                        };
                    }
                }
            });
        });

        // Event listener untuk termin
        $("#termin").on('input', function(e){
            var input_termin = parseInt($(this).val()) || 0;
            if (input_termin < 0) {
                $(this).val(0);
                input_termin = 0;
            }
            
            var jatuh_tempo = new Date();
            jatuh_tempo.setDate(jatuh_tempo.getDate() + input_termin);
            
            // Format tanggal Indonesia (dd/mm/yyyy)
            var day = jatuh_tempo.getDate().toString().padStart(2, '0');
            var month = (jatuh_tempo.getMonth() + 1).toString().padStart(2, '0');
            var year = jatuh_tempo.getFullYear();
            
            $("#jt").val(day + '/' + month + '/' + year); 
            // Format untuk backend (YYYY-MM-DD)
            $("#jt_formatted").val(year + '-' + month + '-' + day);
        });

        // Event listener untuk pemilihan produk
        $('#cari_produk').on('select2:select', function (e) {
            var data = e.params.data.data;
            
            var stock_available = data.stock_available || 0;
            var produk_id = data.code_ingredient;
            var nama_produk = data.name;
            var unit = data.nama_unit || 'Unit';

            // Cek apakah produk sudah ada di tabel
            if ($("#row_"+produk_id).length > 0) {
                createDynamicAlert('warning', "Produk sudah ditambahkan!");
                // Reset select2
                $('#cari_produk').val(null).trigger('change');
                return;
            }

            // Tambahkan baris baru
            var row = `
                <tr id="row_${produk_id}">
                    <td>${produk_id}</td>
                    <td>${nama_produk}</td>
                    <td>${stock_available} ${unit}</td>
                    <td>
                        <input type="hidden" name="produk[${produk_id}][id]" value="${produk_id}">
                        <input type="number" name="produk[${produk_id}][harga]" placeholder="0" 
                               class="form-control form-control-sm harga" style="text-align:right; width:100px" 
                               min="0" step="0.01" required oninput="validateNumber(this)">
                    </td>
                    <td>
                        <input type="number" name="produk[${produk_id}][jumlah]" placeholder="1" 
                               class="form-control form-control-sm jumlah" step="1" min="1" required oninput="validateNumber(this)">
                    </td>
                    <td>${unit}</td>
                    <td>
                        <input type="number" name="produk[${produk_id}][jumlah_beli]" placeholder="1" 
                               class="form-control form-control-sm jumlah_beli" step="1" min="1" required oninput="validateNumber(this)">
                    </td>
                    <td>
                        <input type="number" name="produk[${produk_id}][diskon_persen]" placeholder="0" 
                               class="form-control form-control-sm diskon_persen" min="0" max="100" step="0.01" oninput="validateNumber(this)">
                    </td>
                    <td>
                        <input type="number" name="produk[${produk_id}][diskon_rp]" placeholder="0" 
                               class="form-control form-control-sm diskon_rp" min="0" step="0.01" oninput="validateNumber(this)">
                    </td>
                    <td>
                        <input type="number" name="produk[${produk_id}][ppn_persen]" placeholder="0" 
                               class="form-control form-control-sm ppn_persen" min="0" max="100" step="0.01" oninput="validateNumber(this)">
                    </td>
                    <td>
                        <input type="number" name="produk[${produk_id}][ppn_rp]" placeholder="0" 
                               class="form-control form-control-sm ppn_rp" min="0" step="0.01" oninput="validateNumber(this)">
                    </td>
                    <td style="text-align:right;" class="subtotal">0</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                    </td>
                </tr>
            `;

            $('#tabledata').append(row);
            hitungGrandTotal();
            
            // Reset select2
            $('#cari_produk').val(null).trigger('change');
        });

        // Validasi input angka untuk mencegah karakter tidak valid
        function validateNumber(input) {
            // Hanya memperbolehkan angka, titik desimal, dan minus (jika diperlukan)
            input.value = input.value.replace(/[^0-9.]/g, '');
            
            // Pastikan hanya ada satu titik desimal
            if ((input.value.match(/\./g) || []).length > 1) {
                input.value = input.value.substring(0, input.value.lastIndexOf('.'));
            }
        }

        // Format mata uang untuk tampilan
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        // Parse format mata uang
        function parseCurrency(value) {
            if (typeof value === 'string') {
                return parseFloat(value.replace(/\./g, '').replace(',', '.'));
            }
            return value;
        }

        // Hitung subtotal untuk satu baris
        function hitungSubtotal(row) {
            var harga = parseCurrency(row.find('.harga').val()) || 0;
            var jumlah = parseCurrency(row.find('.jumlah').val()) || 0;
            var jumlahBeli = parseCurrency(row.find('.jumlah_beli').val()) || 0;
            var diskonPersen = parseCurrency(row.find('.diskon_persen').val()) || 0;
            var diskonRp = parseCurrency(row.find('.diskon_rp').val()) || 0;
            var ppnPersen = parseCurrency(row.find('.ppn_persen').val()) || 0;
            var ppnRp = parseCurrency(row.find('.ppn_rp').val()) || 0;

            // Hitung total sebelum diskon dan ppn
            var total = harga * jumlahBeli;

            // Hitung diskon
            var diskonValue = (total * diskonPersen / 100) + diskonRp;
            
            // Hitung ppn
            var ppnValue = (total * ppnPersen / 100) + ppnRp;

            // Hitung subtotal akhir
            var subtotal = total - diskonValue + ppnValue;
            
            // Pastikan tidak negatif
            if (subtotal < 0) subtotal = 0;

            row.find('.subtotal').text(formatCurrency(subtotal));
            return subtotal;
        }   

        // Hitung grand total
        function hitungGrandTotal() {
            var grandTotal = 0;
            $('#tabledata tr').each(function() {
                grandTotal += hitungSubtotal($(this));
            });
            $('#grand_total').text(formatCurrency(grandTotal));
            return grandTotal;
        }

        // Event listener untuk perubahan input
        $(document).on('input', '.harga, .jumlah_beli, .jumlah, .diskon_persen, .diskon_rp, .ppn_persen, .ppn_rp', function(){
            hitungGrandTotal();
        });

        // Event listener untuk hapus baris
        $(document).on('click', '.remove-row', function(){
            $(this).closest('tr').remove();
            hitungGrandTotal();
        });

        // Event listener untuk submit form
        $('#pembelianForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validasi form
            if ($('#cari_supplier').val() === null || $('#cari_supplier').val() === '') {
                createDynamicAlert('danger', 'Pilih supplier terlebih dahulu!');
                return;
            }
            
            if ($('#jenis').val() === '') {
                createDynamicAlert('danger', 'Pilih jenis transaksi terlebih dahulu!');
                return;
            }
            
            if ($('#tabledata tr').length === 0) {
                createDynamicAlert('danger', 'Tambahkan minimal satu produk!');
                return;
            }
            
            // Validasi setiap produk
            var valid = true;
            $('#tabledata tr').each(function() {
                var harga = parseCurrency($(this).find('.harga').val()) || 0;
                var jumlah = parseCurrency($(this).find('.jumlah').val()) || 0;
                var jumlahBeli = parseCurrency($(this).find('.jumlah_beli').val()) || 0;
                
                if (harga <= 0) {
                    createDynamicAlert('danger', 'Harga harus lebih dari 0 untuk semua produk');
                    valid = false;
                    return false;
                }
                
                if (jumlah <= 0 || jumlahBeli <= 0) {
                    createDynamicAlert('danger', 'Jumlah dan Jumlah Beli harus lebih dari 0 untuk semua produk');
                    valid = false;
                    return false;
                }
            });
            
            if (!valid) return;
            
            // Tampilkan modal konfirmasi
            var supplierText = $('#cari_supplier').select2('data')[0].text;
            var jenisText = $('#jenis').find('option:selected').text();
            var totalText = $('#grand_total').text();
            
            $('#confirmSupplier').text(supplierText);
            $('#confirmJenis').text(jenisText);
            $('#confirmTotal').text(totalText);
            
            $('#confirmModal').modal('show');
        });
        
        // Konfirmasi penyimpanan
        $('#confirmSave').on('click', function() {
            $('#confirmModal').modal('hide');
            
            // Tampilkan loading
            $('#submitText').addClass('d-none');
            $('#submitSpinner').removeClass('d-none');
            $('#submitBtn').prop('disabled', true);
            
            // Kirim form langsung tanpa memproses format angka
            // Controller sudah menangani parsing angka dengan benar
            $.ajax({
                url: $('#pembelianForm').attr('action'),
                method: 'POST',
                data: $('#pembelianForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        createDynamicAlert('success', response.message || 'Transaksi berhasil disimpan');
                        
                        // Reset form
                        $('#pembelianForm')[0].reset();
                        $('#cari_supplier').val(null).trigger('change');
                        $('#jenis').val('');
                        $('#tabledata').empty();
                        $('#grand_total').text('0');
                        
                        // Redirect setelah 2 detik
                        setTimeout(() => {
                            window.location.href = response.redirect || '{{ route("coffeshop.master.pembelian.index") }}';
                        }, 2000);
                    } else {
                        if (response.errors) {
                            let errorMessages = '';
                            for (const field in response.errors) {
                                errorMessages += response.errors[field].join('<br>') + '<br>';
                            }
                            createDynamicAlert('danger', errorMessages);
                        } else {
                            createDynamicAlert('danger', response.message || 'Terjadi kesalahan saat menyimpan transaksi');
                        }
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan jaringan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422) {
                        // Validasi error
                        const errors = xhr.responseJSON.errors;
                        errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field].join('<br>') + '<br>';
                        }
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
                    }
                    createDynamicAlert('danger', errorMessage);
                },
                complete: function() {
                    // Sembunyikan loading
                    $('#submitText').removeClass('d-none');
                    $('#submitSpinner').addClass('d-none');
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });
    </script>
@endsection