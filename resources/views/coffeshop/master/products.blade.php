@extends('layouts.coffeshop.admin')
@section('page-title', 'Produk')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Produk</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addProductBtn">
                <i class="fas fa-plus me-1"></i> Tambah Produk
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
                        <table id="product_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Produk -->
    <div class="modal fade" id="productModalInput">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="productModalLabel">Tambah Produk Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productFormInput" method="POST">
                    @csrf
                    <input type="hidden" id="insert_productId" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                {{-- <div class="mb-3">
                                    <label for="insert_code" class="form-label">Kode Produk*</label>
                                    <input type="text" class="form-control" id="insert_code" name="insert_code" required>
                                </div> --}}
                                <div class="mb-3">
                                    <label for="insert_name" class="form-label">Nama Produk*</label>
                                    <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="insert_category_id" class="form-label">Kategori*</label>
                                    <select class="form-control" id="insert_category_id" name="insert_category_id" required>
                                        <option value="">Pilih Kategori</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="insert_description" name="insert_description" rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card" style="border: 1px solid #d9d9d9;">
                                    <div class="card-header" style="display: flex;justify-content: space-between;align-items: center;">
                                        <h6 class="card-title">Komposisi Bahan Baku</h6>
                                        <button type="button" class="btn btn-sm btn-primary" id="addIngredientBtn">
                                            <i class="fas fa-plus me-1"></i> Tambah Bahan
                                        </button>
                                    </div>
                                    <div class="card-body" style="padding:0">
                                        <div class="table-responsive">
                                            <table class="table" id="ingredientTable">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Bahan Baku</th>
                                                        <th width="30%">Jumlah</th>
                                                        <th width="20%">Satuan</th>
                                                        <th width="10%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="ingredientList" style="border-top: 0px">
                                                    <!-- Daftar bahan baku akan ditambahkan di sini -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="insert_selling_price" class="form-label">Harga Jual*</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="insert_selling_price"
                                        name="insert_selling_price" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="insert_hpp" class="form-label">Harga Produksi (HPP)*</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="insert_hpp" name="insert_hpp"
                                        readonly>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="button" id="button_insert" class="btn btn-primary btn-sm">Simpan</button>
                                <button type="button" id="button_insert_send" class="btn btn-primary"
                                    style="display: none;">Menyimpan...</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Produk -->
    <div class="modal fade" id="productModalEdit">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="productModalLabel">Edit Produk</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_productId" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_code" class="form-label">Kode Produk*</label>
                                    <input type="text" class="form-control" id="edit_code" name="edit_code" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Nama Produk*</label>
                                    <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_category_id" class="form-label">Kategori*</label>
                                    <select class="form-control" id="edit_category_id" name="edit_category_id" required>
                                        <option value="">Pilih Kategori</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="edit_description" name="edit_description" rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card" style="border: 1px solid #d9d9d9;">
                                    <div class="card-header" style="display: flex;justify-content: space-between;align-items: center;">
                                        <h6 class="card-title">Komposisi Bahan Baku</h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-warning" id="resetEditCompositionBtn">
                                                    <i class="fas fa-redo me-1"></i> Buat Ulang 
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" id="addEditIngredientBtn">
                                                <i class="fas fa-plus me-1"></i> Tambah Bahan
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body" style="padding:0">
                                        <div class="table-responsive">
                                            <table class="table" id="editIngredientTable">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Bahan Baku</th>
                                                        <th width="30%">Jumlah</th>
                                                        <th width="20%">Satuan</th>
                                                        <th width="10%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="editIngredientList" style="border-top: 0px;">
                                                    <!-- Daftar bahan baku akan ditambahkan di sini -->
                                                </tbody>
                                            </table>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="edit_selling_price" class="form-label">Harga Jual*</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit_selling_price"
                                        name="edit_selling_price" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_hpp" class="form-label">Harga Produksi (HPP)*</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit_hpp" name="edit_hpp"
                                        readonly>
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
                    <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk ini?</p>
                    <input type="hidden" id="deleteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="deleteSpinner"></span>
                        <span id="deleteBtnText">Hapus</span>
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
        const productsTable = document.getElementById('product_table');

        // MODAL EDIT
        const productModalFormEdit = document.getElementById('productModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_productId');
        const edCode = document.getElementById('edit_code');
        const edName = document.getElementById('edit_name');
        const edCategoryId = document.getElementById('edit_category_id');
        const edDescription = document.getElementById('edit_description');
        const edSellingPrice = document.getElementById('edit_selling_price');
        const edHpp = document.getElementById('edit_hpp');

        // TAMBAH DATA
        const buttonShowModalFormInput = document.getElementById('addProductBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const productModalFormInsert = document.getElementById('productModalInput');
        // const inCode = document.getElementById('insert_code');
        const inName = document.getElementById('insert_name');
        const inCategoryId = document.getElementById('insert_category_id');
        const inDescription = document.getElementById('insert_description');
        const inSellingPrice = document.getElementById('insert_selling_price');
        const inHpp = document.getElementById('insert_hpp');

        // DELETE
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteSpinner = document.getElementById('deleteSpinner');
        const deleteBtnText = document.getElementById('deleteBtnText');

        // Variabel untuk komposisi bahan baku
        let ingredients = [];
        let editIngredients = [];
        let ingredientOptions = [];
        let categoryOptions = [];   
        

        // Fungsi untuk format mata uang
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(amount);
        }

        // Fungsi untuk memuat data kategori
        async function loadCategories(selectElement) {
            try {
                const response = await axios.get(`{{ route('coffeshop.master.products.categories') }}`);
                categoryOptions = response.data.data;

                // Kosongkan select
                selectElement.innerHTML = '<option value="">Pilih Kategori</option>';

                // Tambahkan opsi kategori
                categoryOptions.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    selectElement.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading categories:', error);
                createDynamicAlert('danger', 'Gagal memuat data kategori');
            }
        }

        // Fungsi untuk memuat data bahan baku
        async function loadIngredients() {
            try {
                const response = await axios.get(`{{ route('coffeshop.master.products.ingredients') }}`);
                ingredientOptions = response.data.data;
            } catch (error) {
                console.error('Error loading ingredients:', error);
                createDynamicAlert('danger', 'Gagal memuat data bahan baku');
            }
        }

        // Fungsi untuk menambahkan baris bahan baku
        function addIngredientRow(ingredient = null, isEdit = false, isReadOnly = false) {
            const ingredientList = isEdit ? document.getElementById('editIngredientList') : document.getElementById('ingredientList');
            const rowId = isEdit ? `edit_${Date.now()}` : `new_${Date.now()}`;
            const row = document.createElement('tr');
            row.id = rowId;

            // Kolom bahan baku
            const ingredientCell = document.createElement('td');
            const ingredientSelect = document.createElement('select');
            ingredientSelect.className = 'form-control ingredient-select';
            ingredientSelect.name = 'ingredient_id[]';
            ingredientSelect.required = !isReadOnly;
            if (isReadOnly) ingredientSelect.disabled = true;

            // Tambahkan opsi bahan baku
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Pilih Bahan Baku';
            ingredientSelect.appendChild(defaultOption);
            ingredientOptions.forEach(ing => {
                const option = document.createElement('option');
                option.value = ing.id;
                option.textContent = `${ing.name} (${ing.unit_symbol})`;
                option.dataset.pricePerUnit = ing.price_per_unit;
                option.dataset.unitSymbol = ing.unit_symbol;
                if (isEdit && ingredient && ingredient.ingredient_id == ing.id) {
                    option.selected = true;
                }
                ingredientSelect.appendChild(option);
            });
            ingredientCell.appendChild(ingredientSelect);

            // Kolom quantity
            const quantityCell = document.createElement('td');
            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.className = 'form-control quantity-input';
            quantityInput.name = 'quantity[]';
            quantityInput.step = '0.1';
            quantityInput.min = '0.1';
            quantityInput.required = !isReadOnly;
            if (isReadOnly) quantityInput.disabled = true;
            quantityInput.value = isEdit && ingredient ? ingredient.quantity : '';
            quantityCell.appendChild(quantityInput);

            // Kolom satuan
            const unitCell = document.createElement('td');
            const unitSpan = document.createElement('p');
            unitSpan.className = 'unit-symbol';
            if (isEdit && ingredient) {
                const selectedIngredient = ingredientOptions.find(ing => ing.id == ingredient.ingredient_id);
                if (selectedIngredient) {
                    unitSpan.textContent = selectedIngredient.unit_symbol;
                }
            }
            unitCell.appendChild(unitSpan);

            // Kolom aksi
            const actionCell = document.createElement('td');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'btn btn-sm btn-danger';
            deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
            deleteButton.onclick = function () {
                if (isEdit) {
                    editIngredients = editIngredients.filter(item => item.rowId !== rowId);
                } else {
                    ingredients = ingredients.filter(item => item.rowId !== rowId);
                }
                row.remove();
                calculateHpp(isEdit);
            };
            if (isReadOnly) deleteButton.style.display = 'none';
            actionCell.appendChild(deleteButton);

            // Gabungkan semua kolom
            row.appendChild(ingredientCell);
            row.appendChild(quantityCell);
            row.appendChild(unitCell);
            row.appendChild(actionCell);
            ingredientList.appendChild(row);

            const newIngredient = {
                rowId,
                ingredient_id: isEdit && ingredient ? ingredient.ingredient_id : '',
                quantity: isEdit && ingredient ? parseFloat(ingredient.quantity) : 0,
                price_per_unit: isEdit && ingredient ? parseFloat(ingredient.price_per_unit) : 0
            };

            if (isEdit) {
                editIngredients.push(newIngredient);
            } else {
                ingredients.push(newIngredient);
            }

            // Hanya tambahkan event listener jika bukan readonly
            if (!isReadOnly) {
                ingredientSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const unitSymbol = selectedOption.dataset.unitSymbol || '';
                    const pricePerUnit = parseFloat(selectedOption.dataset.pricePerUnit) || 0;
                    this.closest('tr').querySelector('.unit-symbol').textContent = unitSymbol;
                    const row = this.closest('tr');
                    const rowId = row.id;
                    if (isEdit) {
                        const index = editIngredients.findIndex(item => item.rowId === rowId);
                        if (index !== -1) {
                            editIngredients[index].ingredient_id = this.value;
                            editIngredients[index].price_per_unit = pricePerUnit;
                        }
                    } else {
                        const index = ingredients.findIndex(item => item.rowId === rowId);
                        if (index !== -1) {
                            ingredients[index].ingredient_id = this.value;
                            ingredients[index].price_per_unit = pricePerUnit;
                        }
                    }
                    calculateHpp(isEdit);
                });

                quantityInput.addEventListener('input', function () {
                    const row = this.closest('tr');
                    const rowId = row.id;
                    const quantity = parseFloat(this.value) || 0;
                    if (isEdit) {
                        const index = editIngredients.findIndex(item => item.rowId === rowId);
                        if (index !== -1) {
                            editIngredients[index].quantity = quantity;
                        }
                    } else {
                        const index = ingredients.findIndex(item => item.rowId === rowId);
                        if (index !== -1) {
                            ingredients[index].quantity = quantity;
                        }
                    }
                    calculateHpp(isEdit);
                });
            }
        }

        document.getElementById('resetEditCompositionBtn').addEventListener('click', function () {
            if (confirm("Apakah Anda yakin ingin mereset komposisi? Data lama akan hilang.")) {
                editIngredients = [];
                document.getElementById('editIngredientList').innerHTML = '';
                addIngredientRow(null, true, false); // Tambahkan baris kosong yang bisa diedit
            }
        });
        // Perbaikan fungsi calculateHpp
        function calculateHpp(isEdit = false) {
            let totalHpp = 0;
            const items = isEdit ? editIngredients : ingredients;
            const hppField = isEdit ? edHpp : inHpp;

            items.forEach(item => {
                if (item.ingredient_id && item.quantity > 0 && item.price_per_unit > 0) {
                    totalHpp += parseFloat(item.quantity) * parseFloat(item.price_per_unit);
                }
            });

            hppField.value = totalHpp.toFixed(2);
        }

        // Perbaikan pada bagian edit produk
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;
                
                try {
                    // Reset form edit
                    editIngredients = [];
                    document.getElementById('editIngredientList').innerHTML = '';

                    // Load data produk
                    const productResponse = await axios.get(`{{ route('coffeshop.master.products.data') }}?id=${id}`);
                    const product = productResponse.data.data[0];
                    
                    // Set values to form fields
                    edId.value = product.id;
                    edCode.value = product.code;
                    edName.value = product.name;
                    edCategoryId.value = product.category_id || '';
                    edDescription.value = product.description || '';
                    edSellingPrice.value = product.selling_price;
                    edHpp.value = product.hpp || '0';

                    // Load komposisi bahan baku
                    const compResponse = await axios.get(`{{ route('coffeshop.master.products.compositions', ['product_id' => '']) }}${product.id}`);
                    const compositions = compResponse.data.data;

                    // Pastikan kita memiliki data bahan baku terbaru
                    await loadIngredients();

                    // Tambahkan bahan baku ke tabel
                   if (compositions && compositions.length > 0) {
                        compositions.forEach(comp => {
                            const ingredientInfo = ingredientOptions.find(ing => ing.id == comp.ingredient_id);
                            if (ingredientInfo) {
                                addIngredientRow({
                                    ingredient_id: comp.ingredient_id,
                                    quantity: comp.quantity,
                                    price_per_unit: ingredientInfo.price_per_unit
                                }, true, true); // true untuk isEdit, true untuk isReadOnly
                            }
                        });
                        
                        // Hitung ulang HPP setelah semua bahan dimuat
                        setTimeout(() => {
                            calculateHpp(true);
                        }, 100);
                    } else {
                        // Tambahkan satu baris kosong jika tidak ada komposisi
                        addIngredientRow(null, true, true); 
                    }

                    new bootstrap.Modal(productModalFormEdit).show();

                } catch (error) {
                    console.error('Error fetching data:', error);
                    createDynamicAlert('danger', 'Gagal memuat data produk');
                }
            }
        });


        // Fungsi untuk fetch data produk
        async function fetchData(syVSearch, syVLimit, syVStatus) {
            try {
                let url = `{{ route('coffeshop.master.products.data') }}`;
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

                productsTable.innerHTML = '';
                productsTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th>Harga Produksi</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    productsTable.innerHTML += `
                        <tr>
                            <td colspan="8" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((product, index) => {
                        productsTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${product.name}</td>
                                <td>
                                    <div class="price-wrapper">
                                        <span class="currency">Rp</span>
                                        <span class="amount">${formatCurrency(product.selling_price)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-wrapper">
                                        <span class="currency">Rp</span>
                                        <span class="amount">${formatCurrency(product.hpp)}</span>
                                    </div>
                                </td>
                                <td>${product.category_name || '-'}</td>
                                <td>
                                    <span class="badge ${product.is_active ? 'bg-success' : 'bg-secondary'}">
                                        ${product.is_active ? 'Active' : 'Non Active'}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${product.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${product.id}" 
                                            data-bs-toggle="tooltip" 
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                createDynamicAlert('danger', 'Gagal memuat data produk');
            }
        }

        // Event listener untuk load data awal
        document.addEventListener('DOMContentLoaded', async () => {
            const syVSearch = sySearch.value;
            const syVLimit = syLimit.value;
            const syVStatus = syStatus.value;

            // Load data kategori dan bahan baku
            await loadCategories(inCategoryId);
            await loadCategories(edCategoryId);
            await loadIngredients();

            // Load data produk
            await fetchData(syVSearch, syVLimit, syVStatus);
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

        // Event listener untuk tombol tambah produk
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            ingredients = [];
            document.getElementById('ingredientList').innerHTML = '';
            // inCode.value = '';
            inName.value = '';
            inCategoryId.value = '';
            inDescription.value = '';
            inSellingPrice.value = '';
            inHpp.value = '0';

            // Tambahkan satu baris bahan baku kosong
            addIngredientRow();

            new bootstrap.Modal(productModalFormInsert).show();
        });

        // Event listener untuk tombol tambah bahan baku (tambah produk)
        document.getElementById('addIngredientBtn').addEventListener('click', function() {
            addIngredientRow();
        });

        // Event listener untuk tombol tambah bahan baku (edit produk)
        document.getElementById('addEditIngredientBtn').addEventListener('click', function() {
            addIngredientRow(null, true);
        });

        // Event listener untuk simpan produk baru
        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                // Validasi minimal satu bahan baku
                if (ingredients.length === 0 || ingredients.every(item => !item.ingredient_id || item
                        .quantity <= 0)) {
                    createDynamicAlert('danger', 'Minimal harus ada satu bahan baku dengan jumlah yang valid');
                    buttonInsert.style.display = 'inline-block';
                    buttonInsertSend.style.display = 'none';
                    return;
                }

                const response = await axios.post(`{{ route('coffeshop.master.products.store') }}`, {
                    // code: inCode.value,
                    name: inName.value,
                    category_id: inCategoryId.value,
                    description: inDescription.value,
                    selling_price: inSellingPrice.value,
                    hpp: inHpp.value,
                    compositions: ingredients.filter(item => item.ingredient_id && item.quantity > 0)
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
                    bootstrap.Modal.getInstance(productModalFormInsert).hide();
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


        // Event listener untuk simpan edit produk
        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                // Validasi minimal satu bahan baku
                if (editIngredients.length === 0 || editIngredients.every(item => !item.ingredient_id || item
                        .quantity <= 0)) {
                    createDynamicAlert('danger', 'Minimal harus ada satu bahan baku dengan jumlah yang valid');
                    buttonUpdate.style.display = 'inline-block';
                    buttonUpdateSend.style.display = 'none';
                    return;
                }

                const response = await axios.post(`{{ route('coffeshop.master.products.update') }}`, {
                    id: edId.value,
                    // code: edCode.value,
                    name: edName.value,
                    category_id: edCategoryId.value,
                    description: edDescription.value,
                    selling_price: edSellingPrice.value,
                    hpp: edHpp.value,
                    compositions: editIngredients.filter(item => item.ingredient_id && item.quantity >
                        0)
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
                    bootstrap.Modal.getInstance(productModalFormEdit).hide();
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

        document.addEventListener('click', function(event) {
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                const id = deleteBtn.dataset.id;
                document.getElementById('deleteId').value = id;
                new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
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
                const response = await axios.post(`{{ route('coffeshop.master.products.delete') }}`, {
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