@extends('layouts.coffeshop.admin')
@section('page-title', 'Kategori')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Kategori</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addCategoryBtn">
                <i class="fas fa-plus me-1"></i> Tambah Kategori
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
                        <table id="category_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Kategori -->
    <div class="modal fade" id="categoryModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="categoryModalLabel">Tambah Kategori Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryFormInput" method="POST">
                    @csrf
                    <input type="hidden" id="insert_categoryId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="insert_description" name="insert_description" rows="3"></textarea>
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

    <!-- Modal untuk Edit Kategori -->
    <div class="modal fade" id="categoryModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="categoryModalLabel">Edit Kategori</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_categoryId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" rows="3"></textarea>
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
                    <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
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
        const categoriesTable = document.getElementById('category_table');

        // MODAL EDIT
        const categoryModalFormEdit = document.getElementById('categoryModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_categoryId');
        const edName = document.getElementById('edit_name');
        const edDescription = document.getElementById('edit_description');

        // TAMBAH DATA
        const buttonShowModalFormInput = document.getElementById('addCategoryBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const categoryModalFormInsert = document.getElementById('categoryModalInput');
        const inName = document.getElementById('insert_name');
        const inDescription = document.getElementById('insert_description');

        // DELETE
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteSpinner = document.getElementById('deleteSpinner');
        const deleteBtnText = document.getElementById('deleteBtnText');

        // Fungsi untuk menampilkan alert


        // Fungsi untuk fetch data kategori
        async function fetchData(syVSearch, syVLimit, syVStatus) {
            try {
                let url = `{{ route('coffeshop.master.categories.data') }}`;
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

                categoriesTable.innerHTML = '';
                categoriesTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    categoriesTable.innerHTML += `
                        <tr>
                            <td colspan="4" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((category, index) => {
                        categoriesTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${category.name}</td>
                                <td class="text-muted">${category.description || '-'}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${category.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${category.id}" 
                                            data-bs-toggle="tooltip" 
                                            title="Nonaktifkan">
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
                // createDynamicAlert('danger', 'Gagal memuat data kategori');
                createDynamicAlert('danger' || 'Gagal memuat data kategori');
            }
        }

        // Event listener untuk load data awal
        document.addEventListener('DOMContentLoaded', () => {
            const syVSearch = sySearch.value;
            const syVLimit = syLimit.value;
            const syVStatus = syStatus.value;
            fetchData(syVSearch, syVLimit, syVStatus);
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

        // Event listener untuk tombol tambah kategori
        buttonShowModalFormInput.addEventListener('click', function() {
            new bootstrap.Modal(categoryModalFormInsert).show();
        });
    </script>
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- INSERT DATA --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        // Event listener untuk simpan kategori baru
        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('coffeshop.master.categories.store') }}`, {
                    name: inName.value,
                    description: inDescription.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Data berhasil disimpan');

                    // Refresh data
                    const syVSearch = sySearch.value;
                    const syVLimit = syLimit.value;
                    const syVStatus = syStatus.value;
                    await fetchData(syVSearch, syVLimit, syVStatus);

                    // Clear form
                    inName.value = '';
                    inDescription.value = '';

                    // Close modal
                    bootstrap.Modal.getInstance(categoryModalFormInsert).hide();
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
    </script>
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- UPDATE DATA --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        // Event listener untuk tombol edit
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;
                let url = `{{ route('coffeshop.master.categories.data') }}`;
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
                    edDescription.value = data.description || '';
                    new bootstrap.Modal(categoryModalFormEdit).show();

                } catch (error) {
                    console.error('Error fetching data:', error);
                    createDynamicAlert('danger', 'Gagal memuat data kategori');
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

        // Event listener untuk simpan edit kategori
        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('coffeshop.master.categories.update') }}`, {
                    id: edId.value,
                    name: edName.value,
                    description: edDescription.value,
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
                    bootstrap.Modal.getInstance(categoryModalFormEdit).hide();
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
                const response = await axios.post(`{{ route('coffeshop.master.categories.delete') }}`, {
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
                deleteBtnText.textContent = 'Nonaktifkan';
                deleteSpinner.classList.add('d-none');
                confirmDeleteBtn.disabled = false;
            }
        });
    </script>
@endpush
