@extends('layouts.coffeshop.admin')
@section('page-title', 'Satuan')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Satuan</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addUnitBtn">
                <i class="fas fa-plus me-1"></i> Tambah Satuan
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
                        <table id="unit_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Satuan -->
    <div class="modal fade" id="unitModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="unitModalLabel">Tambah Satuan Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="unitFormInput" method="POST">
                    @csrf
                    <input type="hidden" id="insert_unitId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Satuan*</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_symbol" class="form-label">Simbol*</label>
                            <input type="text" class="form-control" id="insert_symbol" name="insert_symbol" required>
                            <small class="text-muted">Contoh: kg, g, ml, ltr, pcs</small>
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

    <!-- Modal untuk Edit Satuan -->
    <div class="modal fade" id="unitModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="unitModalLabel">Edit Satuan</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="unitFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_unitId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Satuan*</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_symbol" class="form-label">Simbol*</label>
                            <input type="text" class="form-control" id="edit_symbol" name="edit_symbol" required>
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
                    <p>Apakah Anda yakin ingin menghapus satuan ini?</p>
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
        const unitsTable = document.getElementById('unit_table');

        // MODAL EDIT
        const unitModalFormEdit = document.getElementById('unitModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_unitId');
        const edName = document.getElementById('edit_name');
        const edSymbol = document.getElementById('edit_symbol');

        // TAMBAH DATA
        const buttonShowModalFormInput = document.getElementById('addUnitBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const unitModalFormInsert = document.getElementById('unitModalInput');
        const inName = document.getElementById('insert_name');
        const inSymbol = document.getElementById('insert_symbol');

        // DELETE
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteSpinner = document.getElementById('deleteSpinner');
        const deleteBtnText = document.getElementById('deleteBtnText');

        // Fungsi untuk fetch data satuan
        async function fetchData(syVSearch, syVLimit, syVStatus) {
            try {
                let url = `{{ route('coffeshop.master.units.data') }}`;
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

                unitsTable.innerHTML = '';
                unitsTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Satuan</th>
                            <th>Simbol</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    unitsTable.innerHTML += `
                        <tr>
                            <td colspan="4" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((unit, index) => {
                        unitsTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${unit.name}</td>
                                <td>${unit.symbol}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${unit.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${unit.id}" 
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
                createDynamicAlert('danger', 'Gagal memuat data satuan');
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

        // Event listener untuk tombol tambah satuan
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inName.value = '';
            inSymbol.value = '';
            new bootstrap.Modal(unitModalFormInsert).show();
        });

        // Event listener untuk simpan satuan baru
        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('coffeshop.master.units.store') }}`, {
                    name: inName.value,
                    symbol: inSymbol.value,
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
                    bootstrap.Modal.getInstance(unitModalFormInsert).hide();
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
                let url = `{{ route('coffeshop.master.units.data') }}`;
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
                    edSymbol.value = data.symbol;
                    new bootstrap.Modal(unitModalFormEdit).show();

                } catch (error) {
                    console.error('Error fetching data:', error);
                    createDynamicAlert('danger', 'Gagal memuat data satuan');
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

        // Event listener untuk simpan edit satuan
        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('coffeshop.master.units.update') }}`, {
                    id: edId.value,
                    name: edName.value,
                    symbol: edSymbol.value,
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
                    bootstrap.Modal.getInstance(unitModalFormEdit).hide();
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
                const response = await axios.post(`{{ route('coffeshop.master.units.delete') }}`, {
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
