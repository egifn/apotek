@extends('layouts.barbershop.admin')
@section('page-title', 'Layanan')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Layanan</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addServiceBtn">
                <i class="fas fa-plus me-1"></i> Tambah Layanan
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
                        <input type="text" class="form-control form-control-sm" id="short_by_search" placeholder="search..">
                    </div>
                </div>

                <div class="table-card-body">
                    <div class="table-container">
                        <table id="service_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Layanan -->
    <div class="modal fade" id="serviceModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="serviceModalLabel">Tambah Layanan Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="serviceFormInput" method="POST">
                    @csrf
                    <input type="hidden" id="insert_serviceId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="insert_description" name="insert_description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_duration" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="insert_duration" name="insert_duration" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="insert_price" name="insert_price" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_insert" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_insert_send" class="btn btn-primary" style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Layanan -->
    <div class="modal fade" id="serviceModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="serviceModalLabel">Edit Layanan</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="serviceFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_serviceId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_duration" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="edit_duration" name="edit_duration" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="edit_price" name="edit_price" min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_update" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_update_send" class="btn btn-primary" style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" aria-labelledby="confirmDeleteModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Nonaktifkan Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="actionDescription">Data akan dinonaktifkan</span>
                        </p>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="deleteToggle">
                            <label class="form-check-label" for="deleteToggle">
                                <span id="toggleLabel">Hapus Data</span>
                            </label>
                        </div>
                    </div>

                    <div id="passwordField" class="mb-3 d-none">
                        <label for="deletePassword" class="form-label">Masukkan Password:</label>
                        <input type="password" class="form-control" id="deletePassword" placeholder="Password untuk menghapus">
                    </div>

                    <input type="hidden" id="deleteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmActionBtn" disabled>
                        <span class="spinner-border spinner-border-sm d-none" id="actionSpinner"></span>
                        <span id="actionBtnText">Konfirmasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    {{-- SET VARIABLE --}}
    <script>
        // Variabel untuk filter/search
        const sySearch = document.getElementById('short_by_search');
        const syLimit = document.getElementById('short_by_limit');
        const syStatus = document.getElementById('short_by_status');

        // data table
        const servicesTable = document.getElementById('service_table');

        // MODAL EDIT
        const componentModalFormEdit = document.getElementById('serviceModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_serviceId');
        const edName = document.getElementById('edit_name');
        const edDescription = document.getElementById('edit_description');
        const edDuration = document.getElementById('edit_duration');
        const edPrice = document.getElementById('edit_price');

        // TAMBAH DATA
        const buttonShowModalFormInput = document.getElementById('addServiceBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('serviceModalInput');
        const inName = document.getElementById('insert_name');
        const inDescription = document.getElementById('insert_description');
        const inDuration = document.getElementById('insert_duration');
        const inPrice = document.getElementById('insert_price');
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(syVSearch, syVLimit, syVStatus) {
            try {
                let url = `{{ route('barbershop.master.services.data') }}`;
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

                servicesTable.innerHTML = '';
                servicesTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Layanan</th>
                            <th>Deskripsi</th>
                            <th>Durasi</th>
                            <th>Harga</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    servicesTable.innerHTML += `
                        <tr>
                            <td colspan="6" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((service, index) => {
                        servicesTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${service.name}</td>
                                <td class="text-muted">${service.description || '-'}</td>
                                <td>${service.duration} menit</td>
                                <td>Rp ${Number(service.price).toLocaleString('id-ID')}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${service.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${service.id}" 
                                            data-bs-toggle="tooltip" 
                                            title="Non-aktifkan">
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
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const syVSearch = sySearch.value;
            const syVLimit = syLimit.value;
            const syVStatus = syStatus.value;
            fetchData(syVSearch, syVLimit, syVStatus);
        });

        sySearch.addEventListener('keyup', (event) => {
            const syVSearch = event.target.value.trim();
            const syVLimit = syLimit.value;
            const syVStatus = syStatus.value;
            fetchData(syVSearch, syVLimit, syVStatus);
        });

        syLimit.addEventListener('change', (event) => {
            const syVSearch = sySearch.value;
            const syVLimit = event.target.value.trim();
            const syVStatus = syStatus.value;
            fetchData(syVSearch, syVLimit, syVStatus);
        });

        syStatus.addEventListener('change', (event) => {
            const syVSearch = sySearch.value;
            const syVLimit = syLimit.value;
            const syVStatus = event.target.value.trim();
            fetchData(syVSearch, syVLimit, syVStatus);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('barbershop.master.services.store') }}`, {
                    name: inName.value,
                    description: inDescription.value,
                    duration: inDuration.value,
                    price: inPrice.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Data berhasil disimpan');

                    // Refresh data
                    const syVSearch = sySearch.value;
                    const syVLimit = syLimit.value;
                    const syVStatus = syStatus.value;
                    await fetchData(syVSearch, syVLimit, syVStatus);

                    // Clear the form fields
                    inName.value = '';
                    inDescription.value = '';
                    inDuration.value = '';
                    inPrice.value = '';

                    // Close the modal
                    bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
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

    {{-- UPDATE DATA --}}
    <script>
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;
                let url = `{{ route('barbershop.master.services.data') }}`;
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
                    edDuration.value = data.duration;
                    edPrice.value = data.price;
                    new bootstrap.Modal(componentModalFormEdit).show();

                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('barbershop.master.services.update') }}`, {
                    id: edId.value,
                    name: edName.value,
                    description: edDescription.value,
                    duration: edDuration.value,
                    price: edPrice.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const syVSearch = sySearch.value;
                    const syVLimit = syLimit.value;
                    const syVStatus = syStatus.value;
                    await fetchData(syVSearch, syVLimit, syVStatus);
                    
                    bootstrap.Modal.getInstance(componentModalFormEdit).hide();
                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message || 'An error occurred');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                createDynamicAlert('danger', 'Network error occurred');
            } finally {
                buttonUpdate.style.display = 'inline-block';
                buttonUpdateSend.style.display = 'none';
            }
        });
    </script>

    {{-- DELETE DATA --}}
    <script>
        let currentAction = 'deactivate';

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('deleteToggle').addEventListener('change', function(e) {
                const passwordField = document.getElementById('passwordField');
                const actionDescription = document.getElementById('actionDescription');
                const toggleLabel = document.getElementById('toggleLabel');
                const confirmBtn = document.getElementById('confirmActionBtn');

                if (e.target.checked) {
                    passwordField.classList.remove('d-none');
                    actionDescription.textContent = 'Data akan dihapus permanen dari sistem';
                    toggleLabel.textContent = 'Hapus Data';
                    currentAction = 'delete';
                    confirmBtn.disabled = true;
                } else {
                    passwordField.classList.add('d-none');
                    actionDescription.textContent = 'Data akan dinonaktifkan';
                    toggleLabel.textContent = 'Hapus Data';
                    currentAction = 'deactivate';
                    confirmBtn.disabled = false;
                }
            });

            document.getElementById('deletePassword')?.addEventListener('input', function(e) {
                document.getElementById('confirmActionBtn').disabled = e.target.value.length < 3;
            });

            document.getElementById('confirmActionBtn').addEventListener('click', async function() {
                const btn = this;
                const spinner = document.getElementById('actionSpinner');
                const btnText = document.getElementById('actionBtnText');
                const id = document.getElementById('deleteId').value;
                const password = document.getElementById('deletePassword')?.value || null;

                btn.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Memproses...';

                try {
                    if (currentAction === 'delete') {
                        response = await axios.post(
                            `{{ route('barbershop.master.services.destroy') }}`, {
                                id: id,
                                password: password,
                                action: 'delete'
                            });
                    } else {
                        response = await axios.post(
                            `{{ route('barbershop.master.services.destroy') }}`, {
                                id: id,
                                action: 'deactivate'
                            });
                    }

                    if (response.data.status === true) {
                        createDynamicAlert('success', response.data.message);

                        const syVSearch = sySearch.value;
                        const syVLimit = syLimit.value;
                        const syVStatus = syStatus.value;
                        await fetchData(syVSearch, syVLimit, syVStatus);

                        bootstrap.Modal.getInstance('#confirmDeleteModal').hide();
                    } else {
                        createDynamicAlert('danger', response.data.message || 'Gagal memproses data');
                    }
                } catch (error) {
                    console.error("Error processing data:", error);
                    if (error.response && error.response.data) {
                        const errorData = error.response.data;
                        if (errorData.type === 'validation') {
                            showValidationErrors(errorData.errors);
                        } else {
                            createDynamicAlert('danger', errorData.message || 'Terjadi kesalahan saat memproses');
                        }
                    } else {
                        createDynamicAlert('danger', 'Terjadi kesalahan jaringan');
                    }
                } finally {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Konfirmasi';
                }
            });
        });

        document.addEventListener('click', async function(event) {
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                event.preventDefault();
                const id = deleteBtn.dataset.id;

                document.getElementById('deleteId').value = id;
                document.getElementById('deleteToggle').checked = false;
                document.getElementById('passwordField').classList.add('d-none');
                document.getElementById('deletePassword').value = '';
                document.getElementById('confirmActionBtn').disabled = false;

                const confirmModal = new bootstrap.Modal('#confirmDeleteModal', {
                    focus: true
                });
                confirmModal.show();
            }
        });
    </script>
@endpush