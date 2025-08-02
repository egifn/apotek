@extends('layouts.senam.admin')
@section('page-title', 'Alat Senam')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Alat Senam</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addEquipmentBtn">
                <i class="fas fa-plus me-1"></i> Tambah Alat
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
                        <table id="equipment_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Alat -->
    <div class="modal fade" id="equipmentModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="equipmentModalLabel">Tambah Alat Senam</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="equipmentFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="insert_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="insert_description" name="insert_description" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_total_quantity" class="form-label">Jumlah Total</label>
                                <input type="number" class="form-control" id="insert_total_quantity" name="insert_total_quantity" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_maintenance_schedule" class="form-label">Jadwal Maintenance</label>
                                <input type="date" class="form-control" id="insert_maintenance_schedule" name="insert_maintenance_schedule">
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

    <!-- Modal untuk Edit Alat -->
    <div class="modal fade" id="equipmentModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Edit Alat Senam</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="equipmentFormEdit">
                    @csrf
                    <input type="hidden" id="edit_equipment_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_total_quantity" class="form-label">Jumlah Total</label>
                                <input type="number" class="form-control" id="edit_total_quantity" name="edit_total_quantity" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_maintenance_schedule" class="form-label">Jadwal Maintenance</label>
                                <input type="date" class="form-control" id="edit_maintenance_schedule" name="edit_maintenance_schedule">
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

    <!-- Modal untuk Hapus -->
    <div class="modal fade" id="equipmentModalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Konfirmasi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="equipmentFormDelete">
                    @csrf
                    <input type="hidden" id="delete_equipment_id">
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus alat ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_delete" class="btn btn-danger btn-sm">Hapus</button>
                        <button type="button" id="button_delete_send" class="btn btn-danger" style="display: none;">Menghapus...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    {{-- GLOBAL DEBOUNCE FUNCTION --}}
    <script>
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
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
        const equipmentTable = document.getElementById('equipment_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addEquipmentBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('equipmentModalInput');
        const inName = document.getElementById('insert_name');
        const inDescription = document.getElementById('insert_description');
        const inTotalQuantity = document.getElementById('insert_total_quantity');
        const inMaintenanceSchedule = document.getElementById('insert_maintenance_schedule');

        // MODAL EDIT
        const equipmentModalEdit = document.getElementById('equipmentModalEdit');
        const editEquipmentId = document.getElementById('edit_equipment_id');
        const editName = document.getElementById('edit_name');
        const editDescription = document.getElementById('edit_description');
        const editTotalQuantity = document.getElementById('edit_total_quantity');
        const editMaintenanceSchedule = document.getElementById('edit_maintenance_schedule');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');

        // MODAL DELETE
        const equipmentModalDelete = document.getElementById('equipmentModalDelete');
        const deleteEquipmentId = document.getElementById('delete_equipment_id');
        const buttonDelete = document.getElementById('button_delete');
        const buttonDeleteSend = document.getElementById('button_delete_send');
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(search, status, limit) {
            try {
                let url = `{{ route('senam.master.equipment.data') }}`;
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

                equipmentTable.innerHTML = '';
                equipmentTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Maintenance</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    equipmentTable.innerHTML += `
                        <tr>
                            <td colspan="5" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((equipment, index) => {
                        // Format quantity
                        const quantity = `
                            <div>Total: ${equipment.total_quantity}</div>
                            <div class="small ${equipment.available_quantity > 0 ? 'text-success' : 'text-danger'}">
                                Tersedia: ${equipment.available_quantity}
                            </div>
                        `;

                        // Format maintenance
                        const maintenance = equipment.maintenance_schedule 
                            ? new Date(equipment.maintenance_schedule).toLocaleDateString('id-ID')
                            : '-';

                        equipmentTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>
                                    <div class="fw-medium">${equipment.name}</div>
                                    <div class="small text-muted">${equipment.description ? equipment.description.substring(0, 50) + (equipment.description.length > 50 ? '...' : '') : '-'}</div>
                                </td>
                                <td>${quantity}</td>
                                <td>${maintenance}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_edit" 
                                            data-id="${equipment.id}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${equipment.id}" 
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
                createDynamicAlert('danger', 'Gagal memuat data alat');
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
            inTotalQuantity.value = '1';
            inMaintenanceSchedule.value = '';
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = {
                    name: inName.value,
                    description: inDescription.value,
                    total_quantity: inTotalQuantity.value,
                    maintenance_schedule: inMaintenanceSchedule.value
                };

                const response = await axios.post(`{{ route('senam.master.equipment.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Alat berhasil ditambahkan');

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
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menambahkan alat');
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
                    const response = await axios.get(`{{ route('senam.master.equipment.data') }}`, {
                        params: { id: id }
                    });
                    
                    const equipment = response.data.data[0];
                    
                    // Fill the form
                    editEquipmentId.value = equipment.id;
                    editName.value = equipment.name;
                    editDescription.value = equipment.description;
                    editTotalQuantity.value = equipment.total_quantity;
                    editMaintenanceSchedule.value = equipment.maintenance_schedule ? equipment.maintenance_schedule.split(' ')[0] : '';
                    
                    new bootstrap.Modal(equipmentModalEdit).show();
                } catch (error) {
                    console.error('Error:', error);
                    createDynamicAlert('danger', 'Gagal memuat data alat');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: editEquipmentId.value,
                    name: editName.value,
                    description: editDescription.value,
                    total_quantity: editTotalQuantity.value,
                    maintenance_schedule: editMaintenanceSchedule.value
                };

                const response = await axios.post(`{{ route('senam.master.equipment.update') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Alat berhasil diperbarui');
                    
                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);
                    
                    bootstrap.Modal.getInstance(equipmentModalEdit).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal memperbarui alat');
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
                
                deleteEquipmentId.value = id;
                new bootstrap.Modal(equipmentModalDelete).show();
            }
        });

        buttonDelete.addEventListener('click', async function() {
            buttonDelete.style.display = 'none';
            buttonDeleteSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('senam.master.equipment.destroy') }}`, {
                    id: deleteEquipmentId.value
                });
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, limit);
                    
                    bootstrap.Modal.getInstance(equipmentModalDelete).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal menghapus alat');
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