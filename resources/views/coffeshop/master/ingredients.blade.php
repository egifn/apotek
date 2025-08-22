@extends('layouts.coffeshop.admin')
@section('page-title', 'Bahan Baku')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Bahan Baku</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addIngredientBtn">
                <i class="fas fa-plus me-1"></i> Tambah Bahan Baku
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="col-lg-1">
                        <select class="form-control form-control-sm" id="short_by_limit" style="width: 50px;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                    <div class="col-4" style="display: flex; gap:5px;">
                        <select class="form-control form-control-sm" id="short_by_status" style="width: 150px;">
                            <option value="1" selected>Active</option>
                            <option value="0">Non Active</option>
                        </select>
                        <input type="text" class="form-control form-control-sm" id="short_by_search"
                            placeholder="search..">
                    </div>
                </div>

                <div class="table-card-body">
                    <div class="table-container">
                        <table id="ingredient_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Bahan Baku -->
    <div class="modal fade" id="ingredientModalInput">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="ingredientModalLabel">Tambah Bahan Baku Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ingredientFormInput" method="POST">
                    @csrf
                    <input type="hidden" id="insert_ingredientId" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_name" class="form-label">Nama Bahan Baku*</label>
                                    <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="insert_unit_id" class="form-label">Satuan*</label>
                                    <select class="form-control" id="insert_unit_id" name="insert_unit_id" required>
                                        <option value="">Pilih Satuan</option>
                                    </select>
                                    <small class="text-muted" style="margin-left: 2px;">Satuan dikonversi ke satuan baku
                                        yang sudah ditetapkan</small>
                                </div>
                                <div class="mb-3">
                                    <label for="insert_purchase_price" class="form-label">Harga Beli*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="insert_purchase_price"
                                            name="insert_purchase_price" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_quantity" class="form-label">Quantity dalam Satuan*</label>
                                    <input type="number" class="form-control" id="insert_quantity" name="insert_quantity"
                                        step="0.01" required>
                                    {{-- <small class="text-muted">Satuan dikonversi ke satuan baku yang sudah
                                        ditetapkan</small> --}}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga per Satuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" id="insert_price_per_unit" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_insert" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_insert_send" class="btn btn-primary"
                            style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Bahan Baku -->
    <div class="modal fade" id="ingredientModalEdit">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="ingredientModalLabel">Edit Bahan Baku</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ingredientFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_ingredientId" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Nama Bahan Baku*</label>
                                    <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_unit_id" class="form-label">Satuan*</label>
                                    <select class="form-control" id="edit_unit_id" name="edit_unit_id" required>
                                        <option value="">Pilih Satuan</option>
                                    </select>
                                    <small class="text-muted" style="margin-left: 2px;">Satuan dikonversi ke satuan baku
                                        yang sudah ditetapkan</small>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_purchase_price" class="form-label">Harga Beli*</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="edit_purchase_price"
                                            name="edit_purchase_price" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_quantity" class="form-label">Quantity dalam Satuan*</label>
                                    <input type="number" class="form-control" id="edit_quantity" name="edit_quantity"
                                        step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Harga per Unit</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" id="edit_price_per_unit" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_update" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_update_send" class="btn btn-primary"
                            style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Nonaktifkan Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus bahan baku ini?</p>
                    <input type="hidden" id="deleteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="deleteSpinner"></span>
                        <span id="deleteBtnText">Nonaktifkan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        // Variabel untuk filter/search
        const sySearch = document.getElementById('short_by_search');
        const syLimit = document.getElementById('short_by_limit');
        const syStatus = document.getElementById('short_by_status');

        // data table
        const ingredientsTable = document.getElementById('ingredient_table');

        // MODAL EDIT
        const ingredientModalFormEdit = document.getElementById('ingredientModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_ingredientId');
        const edName = document.getElementById('edit_name');
        const edUnitId = document.getElementById('edit_unit_id');
        const edPurchasePrice = document.getElementById('edit_purchase_price');
        const edQuantity = document.getElementById('edit_quantity');
        const edPricePerUnit = document.getElementById('edit_price_per_unit');

        // TAMBAH DATA
        const buttonShowModalFormInput = document.getElementById('addIngredientBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const ingredientModalFormInsert = document.getElementById('ingredientModalInput');
        const inName = document.getElementById('insert_name');
        const inUnitId = document.getElementById('insert_unit_id');
        const inPurchasePrice = document.getElementById('insert_purchase_price');
        const inQuantity = document.getElementById('insert_quantity');
        const inPricePerUnit = document.getElementById('insert_price_per_unit');

        // DELETE
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteSpinner = document.getElementById('deleteSpinner');
        const deleteBtnText = document.getElementById('deleteBtnText');

        // Fungsi untuk menampilkan alert


        // Fungsi untuk format mata uang
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        // Fungsi untuk menghitung harga per unit
        function calculatePricePerUnit(purchasePrice, quantity) {
            if (quantity <= 0) return 0;
            return purchasePrice / quantity;
        }

        // Event listener untuk perhitungan harga per unit (Tambah)
        inPurchasePrice.addEventListener('input', updatePricePerUnit);
        inQuantity.addEventListener('input', updatePricePerUnit);

        function updatePricePerUnit() {
            const purchasePrice = parseFloat(inPurchasePrice.value) || 0;
            const quantity = parseFloat(inQuantity.value) || 0;
            const pricePerUnit = calculatePricePerUnit(purchasePrice, quantity);
            inPricePerUnit.value = formatCurrency(pricePerUnit);
        }

        // Event listener untuk perhitungan harga per unit (Edit)
        edPurchasePrice.addEventListener('input', updateEditPricePerUnit);
        edQuantity.addEventListener('input', updateEditPricePerUnit);

        function updateEditPricePerUnit() {
            const purchasePrice = parseFloat(edPurchasePrice.value) || 0;
            const quantity = parseFloat(edQuantity.value) || 0;
            const pricePerUnit = calculatePricePerUnit(purchasePrice, quantity);
            edPricePerUnit.value = formatCurrency(pricePerUnit);
        }

        // Fungsi untuk memuat data satuan
        async function loadUnits(selectElement) {
            try {
                const response = await axios.get(`{{ route('coffeshop.master.units.data') }}`);
                const units = response.data.data;

                // Kosongkan select
                selectElement.innerHTML = '<option value="">Pilih Satuan</option>';

                // Tambahkan opsi satuan
                units.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = `${unit.name} (${unit.symbol})`;
                    selectElement.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading units:', error);
                createDynamicAlert('danger', 'Gagal memuat data satuan');
            }
        }

        // Fungsi untuk fetch data bahan baku
        async function fetchData(syVSearch, syVLimit, syVStatus) {
            try {
                let url = `{{ route('coffeshop.master.ingredients.data') }}`;
                let params = new URLSearchParams();

                if (syVSearch && syVSearch.trim() !== '') {
                    params.append('search', syVSearch.trim());
                }
                if (syVLimit != undefined && syVLimit != null && syVLimit != '') {
                    params.append('limit', syVLimit);
                }
                if (syVStatus && syVStatus.trim() !== '') {
                    params.append('status', syVStatus.trim());
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                ingredientsTable.innerHTML = '';
                ingredientsTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Bahan Baku</th>
                            <th>Harga Beli</th>
                            <th>Harga Beli per Satuan</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    ingredientsTable.innerHTML += `
                        <tr>
                            <td colspan="7" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((ingredient, index) => {
                        ingredientsTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${ingredient.name}</td>
                                <td>
                                    <div class="price-wrapper">
                                        <span class="currency">Rp</span>
                                        <span class="amount">${formatCurrency(ingredient.purchase_price)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-wrapper">
                                        <span class="currency">Rp</span>
                                        <span class="amount">${formatCurrency(ingredient.price_per_unit)}</span>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${ingredient.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${ingredient.id}" 
                                            data-bs-toggle="tooltip" 
                                            title="Hapus">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                createDynamicAlert('danger', 'Gagal memuat data bahan baku');
            }
        }

        // Event listener untuk load data awal
        document.addEventListener('DOMContentLoaded', async () => {
            const syVSearch = sySearch.value;
            const syVLimit = syLimit.value;
            const syVStatus = syStatus.value;

            // Load data bahan baku
            await fetchData(syVSearch, syVLimit, syVStatus);

            // Load data satuan untuk form
            await loadUnits(inUnitId);
            await loadUnits(edUnitId);

        });

        // Event listener untuk search
        sySearch.addEventListener('keyup', (event) => {
            const syVSearch = event.target.value.trim();
            const syVLimit = syLimit.value;
            const syVStatus = syStatus.value;
            fetchData(syVSearch, syVLimit, syVStatus);
        });

        // Event listener untuk limit
        syLimit.addEventListener('change', (event) => {
            const syVSearch = sySearch.value;
            const syVLimit = event.target.value.trim();
            const syVStatus = syStatus.value;
            fetchData(syVSearch, syVLimit, syVStatus);
        });

        // Event listener untuk status
        syStatus.addEventListener('change', (event) => {
            const syVSearch = sySearch.value;
            const syVLimit = syLimit.value;
            const syVStatus = event.target.value.trim();
            fetchData(syVSearch, syVLimit, syVStatus);
        });

        // Event listener untuk tombol tambah bahan baku
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inName.value = '';
            inUnitId.value = '';
            inPurchasePrice.value = '';
            inQuantity.value = '';
            inPricePerUnit.value = '';

            new bootstrap.Modal(ingredientModalFormInsert).show();
        });

        // Event listener untuk simpan bahan baku baru
        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('coffeshop.master.ingredients.store') }}`, {
                    name: inName.value,
                    unit_id: inUnitId.value,
                    purchase_price: inPurchasePrice.value,
                    quantity_purchase: inQuantity.value,
                    price_per_unit: calculatePricePerUnit(
                        parseFloat(inPurchasePrice.value) || 0,
                        parseFloat(inQuantity.value) || 1
                    )
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Data berhasil disimpan');

                    // Refresh data
                    const syVSearch = sySearch.value;
                    const syVLimit = syLimit.value;
                    const syVStatus = syStatus.value;
                    await fetchData(syVSearch, syVLimit, syVStatus);

                    // Close modal
                    bootstrap.Modal.getInstance(ingredientModalFormInsert).hide();
                } else {
                    if (data.type === 'validation') {
                        // Tampilkan error validasi
                        let errorMessages = '';
                        for (const field in data.errors) {
                            errorMessages += data.errors[field].join('<br>') + '<br>';
                        }
                        createDynamicAlert('danger', errorMessages);
                    } else {
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menyimpan data');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                createDynamicAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            } finally {
                buttonInsert.style.display = 'inline-block';
                buttonInsertSend.style.display = 'none';
            }
        });

        // Event listener untuk tombol edit
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;
                let url = `{{ route('coffeshop.master.ingredients.data') }}`;
                let params = new URLSearchParams();
                params.append('id', id);

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                try {
                    const response = await axios.get(url);
                    const data = response.data.data[0];

                    // Set values to form fields
                    edId.value = data.id;
                    edName.value = data.name;
                    edUnitId.value = data.unit_id;
                    edPurchasePrice.value = data.purchase_price;
                    edQuantity.value = data.quantity_purchase;
                    edPricePerUnit.value = formatCurrency(data.price_per_unit);

                    new bootstrap.Modal(ingredientModalFormEdit).show();

                } catch (error) {
                    console.error('Error fetching data:', error);
                    createDynamicAlert('danger', 'Gagal memuat data bahan baku');
                }
            }

            // Tombol delete
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                event.preventDefault();
                const id = deleteBtn.dataset.id;
                document.getElementById('deleteId').value = id;
                new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
            }
        });

        // Event listener untuk simpan edit bahan baku
        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('coffeshop.master.ingredients.update') }}`, {
                    id: edId.value,
                    name: edName.value,
                    unit_id: edUnitId.value,
                    purchase_price: edPurchasePrice.value,
                    quantity_purchase: edQuantity.value,
                    price_per_unit: calculatePricePerUnit(
                        parseFloat(edPurchasePrice.value) || 0,
                        parseFloat(edQuantity.value) || 1
                    )
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Data berhasil diperbarui');

                    // Refresh data
                    const syVSearch = sySearch.value;
                    const syVLimit = syLimit.value;
                    const syVStatus = syStatus.value;
                    await fetchData(syVSearch, syVLimit, syVStatus);

                    // Close modal
                    bootstrap.Modal.getInstance(ingredientModalFormEdit).hide();
                } else {
                    if (data.type === 'validation') {
                        // Tampilkan error validasi
                        let errorMessages = '';
                        for (const field in data.errors) {
                            errorMessages += data.errors[field].join('<br>') + '<br>';
                        }
                        createDynamicAlert('danger', errorMessages);
                    } else {
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat memperbarui data');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                createDynamicAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            } finally {
                buttonUpdate.style.display = 'inline-block';
                buttonUpdateSend.style.display = 'none';
            }
        });

        // Event listener untuk konfirmasi delete
        confirmDeleteBtn.addEventListener('click', async function() {
            const id = document.getElementById('deleteId').value;

            // Tampilkan loading
            deleteBtnText.textContent = 'Menghapus...';
            deleteSpinner.classList.remove('d-none');
            confirmDeleteBtn.disabled = true;

            try {
                const response = await axios.post(`{{ route('coffeshop.master.ingredients.delete') }}`, {
                    id: id
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Data berhasil dihapus');

                    // Refresh data
                    const syVSearch = sySearch.value;
                    const syVLimit = syLimit.value;
                    const syVStatus = syStatus.value;
                    await fetchData(syVSearch, syVLimit, syVStatus);

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menghapus data');
                }
            } catch (error) {
                console.error('Error:', error);
                createDynamicAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            } finally {
                // Reset button state
                deleteBtnText.textContent = 'Hapus';
                deleteSpinner.classList.add('d-none');
                confirmDeleteBtn.disabled = false;
            }
        });
    </script>
@endpush
