@extends('layouts.senam.admin')
@section('page-title', 'Jenis Senam')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Jenis Senam</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addClassTypeBtn">
                <i class="fas fa-plus me-1"></i> Tambah Jenis
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header g-2">
                    <div style="width: 100%; display: flex; gap: 5px;">
                        <div class="col-lg-4">
                            <input type="text" class="form-control form-control-sm" id="filter_search" placeholder="Cari...">
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
                        <table id="class_type_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Jenis Senam -->
    <div class="modal fade" id="classTypeModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="classTypeModalLabel">Tambah Jenis Senam</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="classTypeFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Jenis Senam</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="insert_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="insert_description" name="insert_description" rows="2"></textarea>
                        </div>
                        
                        {{-- <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_duration_minutes" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="insert_duration_minutes" name="insert_duration_minutes" min="15" max="180" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Alat dari Daftar</label>
                            <div class="equipment-checkbox-container">
                                @foreach($equipment as $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="insert_equipment_ids[]" value="{{ $item->id }}" id="equipment_{{ $item->id }}">
                                        <label class="form-check-label" for="equipment_{{ $item->id }}">
                                            {{ $item->name }} (Tersedia: {{ $item->available_quantity }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div> --}}
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

    <!-- Modal untuk Edit Jenis Senam -->
    <div class="modal fade" id="classTypeModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Edit Jenis Senam</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="classTypeFormEdit">
                    @csrf
                    <input type="hidden" id="edit_class_type_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Jenis Senam</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" rows="2"></textarea>
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
                        <button type="button" id="button_update_send" class="btn btn-primary" style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Hapus/Nonaktifkan -->
    <div class="modal fade" id="classTypeModalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Konfirmasi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="classTypeFormDelete">
                    @csrf
                    <input type="hidden" id="delete_class_type_id">
                    <input type="hidden" id="delete_action">
                    <div class="modal-body">
                        <p id="delete_message">Apakah Anda yakin ingin menghapus jenis senam ini?</p>
                        <div id="password_container" style="display: none;">
                            <label for="delete_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="delete_password" name="delete_password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_delete" class="btn btn-danger btn-sm">Konfirmasi</button>
                        <button type="button" id="button_delete_send" class="btn btn-danger" style="display: none;">Memproses...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('styles')
    <style>
        .equipment-checkbox-container {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
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
        const classTypeTable = document.getElementById('class_type_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addClassTypeBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('classTypeModalInput');
        const inName = document.getElementById('insert_name');
        const inDescription = document.getElementById('insert_description');
        // const inEquipmentIds = document.querySelectorAll('input[name="insert_equipment_ids[]"]');
        // const inDurationMinutes = document.getElementById('insert_duration_minutes');

        // MODAL EDIT
        const classTypeModalEdit = document.getElementById('classTypeModalEdit');
        const editClassTypeId = document.getElementById('edit_class_type_id');
        const editName = document.getElementById('edit_name');
        const editDescription = document.getElementById('edit_description');
        const editIsActive = document.getElementById('edit_is_active');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');

        // MODAL DELETE
        const classTypeModalDelete = document.getElementById('classTypeModalDelete');
        const deleteClassTypeId = document.getElementById('delete_class_type_id');
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
                let url = `{{ route('senam.master.class-types.data') }}`;
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

                classTypeTable.innerHTML = '';
                classTypeTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Jenis</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    classTypeTable.innerHTML += `
                        <tr>
                            <td colspan="6" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((classType, index) => {
                        // Format status
                        const statusBadge = classType.is_active 
                            ? '<span class="badge bg-success">Aktif</span>' 
                            : '<span class="badge bg-danger">Nonaktif</span>';

                        // Format duration
                        const duration = classType.duration_minutes + ' menit';

                        // Format equipment
                        const equipment = classType.required_equipment 
                            ? classType.required_equipment.substring(0, 30) + (classType.required_equipment.length > 30 ? '...' : '')
                            : '-';

                        classTypeTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>
                                    <div class="fw-medium">${classType.name}</div>
                                </td>
                                <td><div class="small text-muted">${classType.description ? classType.description.substring(0, 50) + (classType.description.length > 50 ? '...' : '') : '-'}</div></td>
                                <td>${statusBadge}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_edit" 
                                            data-id="${classType.id}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${classType.id}" 
                                            data-active="${classType.is_active}"
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
                createDynamicAlert('danger', 'Gagal memuat data jenis senam');
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
            inDescription.value = '';
            // inDurationMinutes.value = '60';
            // inEquipmentIds.forEach(checkbox => {
            //     checkbox.checked = false;
            // });
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const selectedEquipment = Array.from(document.querySelectorAll('input[name="insert_equipment_ids[]"]:checked'))
                    .map(el => el.value);

                const formData = {
                    name: inName.value,
                    description: inDescription.value,
                    // duration_minutes: inDurationMinutes.value,
                    equipment_ids: selectedEquipment
                };

                const response = await axios.post(`{{ route('senam.master.class-types.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jenis senam berhasil ditambahkan');

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
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menambahkan jenis senam');
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
                    editClassTypeId.value = '';
                    editName.value = '';
                    editDescription.value = '';
                    editIsActive.value = '1';

                    const response = await axios.get(`{{ route('senam.master.class-types.data') }}`, {
                        params: { id: id }
                    });

                    if (!response.data.status || !response.data.data) {
                        throw new Error('Data jenis senam tidak ditemukan');
                    }

                    const classType = response.data.data.class_type;
                    const equipmentRequirements = response.data.data.equipment_requirements || [];

                    // Fill the form
                    editClassTypeId.value = classType.id;
                    editName.value = classType.name || '';
                    editDescription.value = classType.description || '';
                    editIsActive.value = classType.is_active ? '1' : '0';
                    
                    new bootstrap.Modal(classTypeModalEdit).show();
                } catch (error) {
                    console.error('Error:', error);
                    createDynamicAlert('danger', error.message || 'Gagal memuat data jenis senam');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
            const formData = {
                    id: editClassTypeId.value,
                    name: editName.value,
                    description: editDescription.value,
                    is_active: editIsActive.value
                };

                const response = await axios.post(`{{ route('senam.master.class-types.update') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jenis senam berhasil diperbarui');
                    
                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);
                    
                    bootstrap.Modal.getInstance(classTypeModalEdit).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal memperbarui jenis senam');
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
                
                deleteClassTypeId.value = id;
                
                if (isActive) {
                    deleteAction.value = 'deactivate';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menonaktifkan jenis senam ini?';
                    passwordContainer.style.display = 'none';
                } else {
                    deleteAction.value = 'delete';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menghapus permanen jenis senam ini?';
                    passwordContainer.style.display = 'block';
                }
                
                new bootstrap.Modal(classTypeModalDelete).show();
            }
        });

        buttonDelete.addEventListener('click', async function() {
            buttonDelete.style.display = 'none';
            buttonDeleteSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: deleteClassTypeId.value,
                    action: deleteAction.value,
                    password: deletePassword.value
                };

                const response = await axios.post(`{{ route('senam.master.class-types.destroy') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);
                    
                    bootstrap.Modal.getInstance(classTypeModalDelete).hide();
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