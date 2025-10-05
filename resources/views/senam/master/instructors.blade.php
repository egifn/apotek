@extends('layouts.senam.admin')
@section('page-title', 'Instruktur')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Instruktur</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addInstructorBtn">
                <i class="fas fa-plus me-1"></i> Tambah Instruktur
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header g-2">
                    <div style="width: 100%; display: flex; gap: 5px;">
                        <div class="col-lg-4">
                            <input type="text" class="form-control form-control-sm" id="filter_search"
                                placeholder="Cari...">
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control form-control-sm" id="filter_status">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <select class="form-control form-control-sm" id="short_by_limit">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="table-card-body">
                    <div class="table-container">
                        <table id="instructor_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Instruktur -->
    <div class="modal fade" id="instructorModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="instructorModalLabel">Tambah Instruktur</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="instructorFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Instruktur</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="insert_specialization" class="form-label">Spesialisasi</label>
                            <input type="text" class="form-control" id="insert_specialization"
                                name="insert_specialization" required>
                        </div>

                        <div class="mb-3">
                            <label for="insert_bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="insert_bio" name="insert_bio" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="insert_contact_phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="insert_contact_phone" name="insert_contact_phone"
                                required>
                        </div>

                        <div class="row" hidden>
                            <div class="col-md-6 mb-3">
                                <label for="insert_contact_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="insert_contact_phone"
                                    name="insert_contact_phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_contact_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="insert_contact_email"
                                    name="insert_contact_email" required>
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

    <!-- Modal untuk Edit Instruktur -->
    <div class="modal fade" id="instructorModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Edit Instruktur</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="instructorFormEdit">
                    @csrf
                    <input type="hidden" id="edit_instructor_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Instruktur</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_specialization" class="form-label">Spesialisasi</label>
                            <input type="text" class="form-control" id="edit_specialization"
                                name="edit_specialization" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="edit_bio" name="edit_bio" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_contact_phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="edit_contact_phone" name="edit_contact_phone"
                                required>
                        </div>

                        <div class="row" hidden>
                            <div class="col-md-6 mb-3">
                                <label for="edit_contact_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="edit_contact_phone"
                                    name="edit_contact_phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_contact_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_contact_email"
                                    name="edit_contact_email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_is_active" class="form-label">Status</label>
                            <select class="form-control" id="edit_is_active" name="edit_is_active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
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

    <!-- Modal untuk Hapus/Nonaktifkan -->
    <div class="modal fade" id="instructorModalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Konfirmasi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="instructorFormDelete">
                    @csrf
                    <input type="hidden" id="delete_instructor_id">
                    <input type="hidden" id="delete_action">
                    <div class="modal-body">
                        <p id="delete_message">Apakah Anda yakin ingin menghapus instruktur ini?</p>
                        <div id="password_container" style="display: none;">
                            <label for="delete_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="delete_password" name="delete_password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_delete" class="btn btn-danger btn-sm">Konfirmasi</button>
                        <button type="button" id="button_delete_send" class="btn btn-danger"
                            style="display: none;">Memproses...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    {{-- GLOBAL DEBOUNCE FUNCTION --}}
    <script>
        // Simple debounce implementation
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    </script>
    {{-- SET VARIABLE --}}
    <script>
        // Filter variables
        const filterSearch = document.getElementById('filter_search');
        const filterStatus = document.getElementById('filter_status');
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const instructorTable = document.getElementById('instructor_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addInstructorBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('instructorModalInput');
        const inName = document.getElementById('insert_name');
        const inSpecialization = document.getElementById('insert_specialization');
        const inBio = document.getElementById('insert_bio');
        const inContactPhone = document.getElementById('insert_contact_phone');
        const inContactEmail = document.getElementById('insert_contact_email');

        // MODAL EDIT
        const instructorModalEdit = document.getElementById('instructorModalEdit');
        const editInstructorId = document.getElementById('edit_instructor_id');
        const editName = document.getElementById('edit_name');
        const editSpecialization = document.getElementById('edit_specialization');
        const editBio = document.getElementById('edit_bio');
        const editContactPhone = document.getElementById('edit_contact_phone');
        const editContactEmail = document.getElementById('edit_contact_email');
        const editIsActive = document.getElementById('edit_is_active');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');

        // MODAL DELETE
        const instructorModalDelete = document.getElementById('instructorModalDelete');
        const deleteInstructorId = document.getElementById('delete_instructor_id');
        const deleteAction = document.getElementById('delete_action');
        const deleteMessage = document.getElementById('delete_message');
        const passwordContainer = document.getElementById('password_container');
        const deletePassword = document.getElementById('delete_password');
        const buttonDelete = document.getElementById('button_delete');
        const buttonDeleteSend = document.getElementById('button_delete_send');
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(search, status, limit) {
            try {
                let url = `{{ route('senam.master.instructors.data') }}`;
                let params = new URLSearchParams();

                if (search && search !== '') {
                    params.append('search', search);
                }
                if (status && status !== '') {
                    params.append('status', status);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                instructorTable.innerHTML = '';
                instructorTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama</th>
                            <th>Spesialisasi</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    instructorTable.innerHTML += `
                        <tr>
                            <td colspan="6" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((instructor, index) => {
                        // Format status
                        const statusBadge = instructor.is_active ?
                            '<span class="badge bg-success">Aktif</span>' :
                            '<span class="badge bg-danger">Nonaktif</span>';

                        // Format contact
                        const contact = `
                            <div>${instructor.contact_phone}</div>
                            <div class="small text-muted">${instructor.contact_email}</div>
                        `;

                        instructorTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>
                                    <div class="fw-medium">${instructor.name}</div>
                                    <div class="small text-muted">${instructor.bio ? instructor.bio.substring(0, 50) + (instructor.bio.length > 50 ? '...' : '') : '-'}</div>
                                </td>
                                <td>${instructor.specialization}</td>
                                <td>${contact}</td>
                                <td>${statusBadge}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_edit" 
                                            data-id="${instructor.id}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${instructor.id}" 
                                            data-active="${instructor.is_active}"
                                            title="Hapus/Nonaktifkan">
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
                createDynamicAlert('danger', 'Gagal memuat data instruktur');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const search = filterSearch.value;
            const status = filterStatus.value;
            const limit = syLimit.value;
            fetchData(search, status, limit);
        });

        // Filter by search
        filterSearch.addEventListener('input', debounce((event) => {
            const search = event.target.value;
            const status = filterStatus.value;
            const limit = syLimit.value;
            fetchData(search, status, limit);
        }, 500));

        // Filter by status
        filterStatus.addEventListener('change', (event) => {
            const search = filterSearch.value;
            const status = event.target.value;
            const limit = syLimit.value;
            fetchData(search, status, limit);
        });

        // Filter by limit
        syLimit.addEventListener('change', (event) => {
            const search = filterSearch.value;
            const status = filterStatus.value;
            const limit = event.target.value;
            fetchData(search, status, limit);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inName.value = '';
            inSpecialization.value = '';
            inBio.value = '';
            inContactPhone.value = '';
            inContactEmail.value = '';

            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = {
                    name: inName.value,
                    specialization: inSpecialization.value,
                    bio: inBio.value,
                    contact_phone: inContactPhone.value,
                    contact_email: inContactEmail.value
                };

                const response = await axios.post(`{{ route('senam.master.instructors.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Instruktur berhasil ditambahkan');

                    // Refresh data
                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);

                    // Close the modal
                    bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message ||
                            'Terjadi kesalahan saat menambahkan instruktur');
                    }
                }

            } catch (error) {
                console.error('Error:', error);
                if (error.response && error.response.data) {
                    const errorData = error.response.data;
                    if (errorData.type === 'validation') {
                        showValidationErrors(errorData.errors);
                    } else {
                        createDynamicAlert('danger', errorData.message || 'Terjadi kesalahan');
                    }
                } else {
                    createDynamicAlert('danger', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                }
            } finally {
                buttonInsert.style.display = 'inline-block';
                buttonInsertSend.style.display = 'none';
            }
        });
    </script>

    {{-- EDIT DATA --}}
    <script>
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;

                try {
                    // Reset all fields before filling
                    editInstructorId.value = '';
                    editName.value = '';
                    editSpecialization.value = '';
                    editBio.value = '';
                    editContactPhone.value = '';
                    editContactEmail.value = '';
                    editIsActive.value = '1';

                    const response = await axios.get(`{{ route('senam.master.instructors.data') }}`, {
                        params: {
                            id: id
                        }
                    });

                    // Ambil data instruktur dari response (object/class)
                    let instructor = null;
                    if (response.data && response.data.data) {
                        if (response.data.data.instructor) {
                            instructor = response.data.data.instructor;
                        } else {
                            instructor = response.data.data;
                        }
                    }

                    if (!instructor || typeof instructor !== 'object') {
                        createDynamicAlert('danger', 'Data instruktur tidak ditemukan');
                        return;
                    }

                    // Fill the form
                    editInstructorId.value = instructor.id !== undefined && instructor.id !== null ? instructor
                        .id : '';
                    editName.value = instructor.name !== undefined && instructor.name !== null ? instructor
                        .name : '';
                    editSpecialization.value = instructor.specialization !== undefined && instructor
                        .specialization !== null ? instructor.specialization : '';
                    editBio.value = instructor.bio !== undefined && instructor.bio !== null ? instructor.bio :
                        '';
                    editContactPhone.value = instructor.contact_phone !== undefined && instructor
                        .contact_phone !== null ? instructor.contact_phone : '';
                    editContactEmail.value = instructor.contact_email !== undefined && instructor
                        .contact_email !== null ? instructor.contact_email : '';
                    editIsActive.value = instructor.is_active == 1 ? '1' : '0';

                    new bootstrap.Modal(instructorModalEdit).show();
                } catch (error) {
                    console.error('Error:', error);
                    createDynamicAlert('danger', 'Gagal memuat data instruktur');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: editInstructorId.value,
                    name: editName.value,
                    specialization: editSpecialization.value,
                    bio: editBio.value,
                    contact_phone: editContactPhone.value,
                    contact_email: editContactEmail.value,
                    is_active: editIsActive.value
                };

                const response = await axios.post(`{{ route('senam.master.instructors.update') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Instruktur berhasil diperbarui');

                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);

                    bootstrap.Modal.getInstance(instructorModalEdit).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal memperbarui instruktur');
                }
            } catch (error) {
                console.error('Error:', error);
                if (error.response && error.response.data) {
                    const errorData = error.response.data;
                    createDynamicAlert('danger', errorData.message || 'Terjadi kesalahan');
                } else {
                    createDynamicAlert('danger', 'Terjadi kesalahan jaringan');
                }
            } finally {
                buttonUpdate.style.display = 'inline-block';
                buttonUpdateSend.style.display = 'none';
            }
        });
    </script>

    {{-- DELETE DATA --}}
    <script>
        document.addEventListener('click', async function(event) {
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                event.preventDefault();
                const id = deleteBtn.dataset.id;
                const isActive = deleteBtn.dataset.active === '1';

                deleteInstructorId.value = id;

                if (isActive) {
                    deleteAction.value = 'deactivate';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menonaktifkan instruktur ini?';
                    passwordContainer.style.display = 'none';
                } else {
                    deleteAction.value = 'delete';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menghapus permanen instruktur ini?';
                    passwordContainer.style.display = 'block';
                }

                new bootstrap.Modal(instructorModalDelete).show();
            }
        });

        buttonDelete.addEventListener('click', async function() {
            buttonDelete.style.display = 'none';
            buttonDeleteSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: deleteInstructorId.value,
                    action: deleteAction.value,
                    password: deletePassword.value
                };

                const response = await axios.post(`{{ route('senam.master.instructors.destroy') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);

                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);

                    bootstrap.Modal.getInstance(instructorModalDelete).hide();
                    deletePassword.value = '';
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal memproses permintaan');
                }
            } catch (error) {
                console.error('Error:', error);
                if (error.response && error.response.data) {
                    const errorData = error.response.data;
                    createDynamicAlert('danger', errorData.message || 'Terjadi kesalahan');
                } else {
                    createDynamicAlert('danger', 'Terjadi kesalahan jaringan');
                }
            } finally {
                buttonDelete.style.display = 'inline-block';
                buttonDeleteSend.style.display = 'none';
            }
        });
    </script>
@endpush
