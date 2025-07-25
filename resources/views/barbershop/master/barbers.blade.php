@extends('layouts.barbershop.admin')
@section('page-title', 'Barber')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Barber</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addBarberBtn">
                <i class="fas fa-plus me-1"></i> Tambah Barber
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
                        <table id="barber_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Barber -->
    <div class="modal fade" id="barberModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="barberModalLabel">Tambah Barber Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="barberFormInput" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="insert_barberId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Barber</label>
                            <input type="text" class="form-control" id="insert_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="insert_photo" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="insert_photo" name="photo">
                        </div>
                        <div class="mb-3">
                            <label for="insert_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="insert_description" name="description" rows="3"></textarea>
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

    <!-- Modal untuk Edit Barber -->
    <div class="modal fade" id="barberModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="barberModalLabel">Edit Barber</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="barberFormEdit" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_barberId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Barber</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_photo" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="edit_photo" name="photo">
                            <div id="currentPhotoContainer" class="mt-2">
                                <small class="text-muted">Foto saat ini:</small>
                                <img id="currentPhoto" src="" class="img-thumbnail mt-1" style="max-height: 100px; display: none;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
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

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true"
        aria-labelledby="confirmDeleteModalLabel">
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
                        <input type="password" class="form-control" id="deletePassword"
                            placeholder="Password untuk menghapus">
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
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- SET VARIABLE --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        // Variabel untuk filter/search
        const sySearch = document.getElementById('short_by_search');
        const syLimit = document.getElementById('short_by_limit');
        const syStatus = document.getElementById('short_by_status');

        // data table
        const barbersTable = document.getElementById('barber_table');

        // MODAL EDIT
        const componentModalFormEdit = document.getElementById('barberModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_barberId');
        const edName = document.getElementById('edit_name');
        const edPhoto = document.getElementById('edit_photo');
        const edDescription = document.getElementById('edit_description');
        const currentPhoto = document.getElementById('currentPhoto');

        // TAMBAH DATA
        const buttonShowModalFormInput = document.getElementById('addBarberBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('barberModalInput');
        const inName = document.getElementById('insert_name');
        const inPhoto = document.getElementById('insert_photo');
        const inDescription = document.getElementById('insert_description');
    </script>
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- GET DATA --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        async function fetchData(syVSearch, syVLimit, syVStatus) {
            try {
                let url = `{{ route('barbershop.master.barbers.data') }}`;
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

                barbersTable.innerHTML = '';
                barbersTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Foto</th>
                            <th>Nama Barber</th>
                            <th>Deskripsi</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    barbersTable.innerHTML += `
                        <tr>
                            <td colspan="5" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((barber, index) => {
                        const photoUrl = barber.photo ? `/storage/${barber.photo}` : 'https://via.placeholder.com/50';
                        barbersTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td>
                                    <img src="${photoUrl}" alt="${barber.name}" class="rounded-circle" width="40" height="40">
                                </td>
                                <td class="fw-medium">${barber.name}</td>
                                <td class="text-muted">${barber.description ? barber.description.substring(0, 50) + (barber.description.length > 50 ? '...' : '') : '-'}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${barber.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${barber.id}" 
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
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- INSERT DATA --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = new FormData();
                formData.append('name', inName.value);
                if (inPhoto.files[0]) {
                    formData.append('photo', inPhoto.files[0]);
                }
                formData.append('description', inDescription.value);

                const response = await axios.post(`{{ route('barbershop.master.barbers.store') }}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
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
                    inPhoto.value = '';
                    inDescription.value = '';

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
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- UPDATE DATA --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;
                let url = `{{ route('barbershop.master.barbers.data') }}`;
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
                    edDescription.value = data.description;
                    
                    // Show current photo if exists
                    if (data.photo) {
                        currentPhoto.src = `/storage/${data.photo}`;
                        currentPhoto.style.display = 'block';
                    } else {
                        currentPhoto.style.display = 'none';
                    }

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
                const formData = new FormData();
                formData.append('id', edId.value);
                formData.append('name', edName.value);
                if (edPhoto.files[0]) {
                    formData.append('photo', edPhoto.files[0]);
                }
                formData.append('description', edDescription.value);

                const response = await axios.post(`{{ route('barbershop.master.barbers.update') }}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    // Refresh data
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
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    {{-- DELETE DATA --}}
    {{-- -------------------------------------------------------------------------------------------------------------------------------------------------------- --}}
    <script>
        let currentAction = 'deactivate'; // 'deactivate' atau 'delete'

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle switch handler
            document.getElementById('deleteToggle').addEventListener('change', function(e) {
                const passwordField = document.getElementById('passwordField');
                const actionDescription = document.getElementById('actionDescription');
                const toggleLabel = document.getElementById('toggleLabel');
                const confirmBtn = document.getElementById('confirmActionBtn');

                if (e.target.checked) {
                    // Mode hapus data
                    passwordField.classList.remove('d-none');
                    actionDescription.textContent = 'Data akan dihapus permanen dari sistem';
                    toggleLabel.textContent = 'Hapus Data';
                    currentAction = 'delete';
                    confirmBtn.disabled = true; // Disable sampai password diisi
                } else {
                    // Mode nonaktifkan data
                    passwordField.classList.add('d-none');
                    actionDescription.textContent = 'Data akan dinonaktifkan';
                    toggleLabel.textContent = 'Hapus Data';
                    currentAction = 'deactivate';
                    confirmBtn.disabled = false;
                }
            });

            // Validasi password field
            document.getElementById('deletePassword')?.addEventListener('input', function(e) {
                document.getElementById('confirmActionBtn').disabled = e.target.value.length < 3;
            });

            // Tombol konfirmasi
            document.getElementById('confirmActionBtn').addEventListener('click', async function() {
                const btn = this;
                const spinner = document.getElementById('actionSpinner');
                const btnText = document.getElementById('actionBtnText');
                const id = document.getElementById('deleteId').value;
                const password = document.getElementById('deletePassword')?.value || null;

                // Tampilkan loading state
                btn.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Memproses...';

                try {
                    const response = await axios.post(
                        `{{ route('barbershop.master.barbers.destroy') }}`, {
                            id: id,
                            password: password,
                            action: currentAction
                        });

                    if (response.data.status === true) {
                        createDynamicAlert('success', response.data.message);

                        // Refresh data
                        const syVSearch = sySearch.value;
                        const syVLimit = syLimit.value;
                        const syVStatus = syStatus.value;
                        await fetchData(syVSearch, syVLimit, syVStatus);

                        // Tutup modal
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
                            createDynamicAlert('danger', errorData.message ||
                                'Terjadi kesalahan saat memproses');
                        }
                    } else {
                        createDynamicAlert('danger', 'Terjadi kesalahan jaringan');
                    }
                } finally {
                    // Reset button state
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Konfirmasi';
                }
            });
        });

        // Handler tombol delete
        document.addEventListener('click', async function(event) {
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                event.preventDefault();
                const id = deleteBtn.dataset.id;

                // Set ID dan reset form
                document.getElementById('deleteId').value = id;
                document.getElementById('deleteToggle').checked = false;
                document.getElementById('passwordField').classList.add('d-none');
                document.getElementById('deletePassword').value = '';
                document.getElementById('confirmActionBtn').disabled = false;

                // Tampilkan modal
                const confirmModal = new bootstrap.Modal('#confirmDeleteModal', {
                    focus: true
                });
                confirmModal.show();
            }
        });
    </script>
@endpush