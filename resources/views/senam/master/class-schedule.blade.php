@extends('layouts.senam.admin')
@section('page-title', 'Produk Jasa')
@section('style')
    <style>
        .instructor-field {
            transition: all 0.3s ease;
        }

        .form-check {
            margin-top: 8px;
        }

        .form-check-input {
            margin-top: 0.25rem;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Produk Jasa</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addScheduleBtn">
                <i class="fas fa-plus me-1"></i> Tambah Jadwal
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header g-2">
                    <div style="width: 100%; display: flex; gap: 5px;">
                        <div class="col-lg-3">
                            <select class="form-control form-control-sm" id="filter_status">
                                <option value="">Semua Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control form-control-sm" id="filter_class_type">
                                <option value="">Semua Jasa</option>
                                @foreach($classTypes as $classType)
                                    <option value="{{ $classType->id }}">{{ $classType->name }}</option>
                                @endforeach
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
                        <table id="schedule_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Jadwal -->
    <div class="modal fade" id="scheduleModalInput">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="scheduleModalLabel">Tambah Jadwal Senam</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="scheduleFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_services_name" class="form-label">Nama Servis*</label>
                                    <input type="text" class="form-control" id="insert_services_name" name="insert_services_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_class_type_id" class="form-label">Jenis Jasa*</label>
                                    <select class="form-control" id="insert_class_type_id" name="insert_class_type_id" required>
                                        <option value="">Pilih Jenis Jasa</option>
                                        @foreach($classTypes as $classType)
                                            <option value="{{ $classType->id }}">{{ $classType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_location_id" class="form-label">Lokasi*</label>
                                    <select class="form-control" id="insert_location_id" name="insert_location_id" required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_price" class="form-label">Harga*</label>
                                    <input type="number" class="form-control" id="insert_price" name="insert_price" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="need_instructor">
                                        <label class="form-check-label" for="need_instructor">
                                            Butuh Instruktur?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row instructor-field" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="insert_instructor_id" class="form-label">Instruktur*</label>
                                    <select class="form-control" id="insert_instructor_id" name="insert_instructor_id">
                                        <option value="">Pilih Instruktur</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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

    <!-- Modal untuk Edit Jadwal -->
    <div class="modal fade" id="scheduleModalEdit">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Edit Jadwal Senam</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="scheduleFormEdit">
                    @csrf
                    <input type="hidden" id="edit_schedule_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_services_name" class="form-label">Nama Servis*</label>
                                    <input type="text" class="form-control" id="edit_services_name" name="edit_services_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_class_type_id" class="form-label">Jenis Jasa*</label>
                                    <select class="form-control" id="edit_class_type_id" name="edit_class_type_id" required>
                                        <option value="">Pilih Jenis Senam</option>
                                        @foreach($classTypes as $classType)
                                            <option value="{{ $classType->id }}">{{ $classType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_price" class="form-label">Harga*</label>
                                    <input type="number" class="form-control" id="edit_price" name="edit_price" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_location_id" class="form-label">Lokasi*</label>
                                    <select class="form-control" id="edit_location_id" name="edit_location_id" required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_need_instructor">
                                    <label class="form-check-label" for="edit_need_instructor">
                                        Butuh Instruktur?
                                    </label>
                                </div>
                            </div>
                            <div class="row edit-instructor-field" style="display: none;">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_instructor_id" class="form-label">Instruktur*</label>
                                        <select class="form-control" id="edit_instructor_id" name="edit_instructor_id">
                                            <option value="">Pilih Instruktur</option>
                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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

    <!-- Modal untuk Hapus/Nonaktifkan -->
    <div class="modal fade" id="scheduleModalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Konfirmasi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="scheduleFormDelete">
                    @csrf
                    <input type="hidden" id="delete_schedule_id">
                    <input type="hidden" id="delete_action">
                    <div class="modal-body">
                        <p id="delete_message">Apakah Anda yakin ingin menghapus jadwal ini?</p>
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

@push('scripts')
    {{-- SET VARIABLE --}}
    <script>
        // Filter variables
        const filterStatus = document.getElementById('filter_status');
        const filterClassType = document.getElementById('filter_class_type');
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const scheduleTable = document.getElementById('schedule_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addScheduleBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('scheduleModalInput');
        const inServicesName = document.getElementById('insert_services_name');
        const inPrice = document.getElementById('insert_price');
        const inClassTypeId = document.getElementById('insert_class_type_id');
        const inInstructorId = document.getElementById('insert_instructor_id');
        const inLocationId = document.getElementById('insert_location_id');

        // MODAL EDIT
        const scheduleModalEdit = document.getElementById('scheduleModalEdit');
        const editServicesName = document.getElementById('edit_services_name');
        const editTypeServices = document.getElementById('edit_type_services');
        const editPrice = document.getElementById('edit_price');
        const editScheduleId = document.getElementById('edit_schedule_id');
        const editClassTypeId = document.getElementById('edit_class_type_id');
        const editInstructorId = document.getElementById('edit_instructor_id');
        const editLocationId = document.getElementById('edit_location_id');
        const editIsActive = document.getElementById('edit_is_active');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');

        // MODAL DELETE
        const scheduleModalDelete = document.getElementById('scheduleModalDelete');
        const deleteScheduleId = document.getElementById('delete_schedule_id');
        const deleteAction = document.getElementById('delete_action');
        const deleteMessage = document.getElementById('delete_message');
        const passwordContainer = document.getElementById('password_container');
        const deletePassword = document.getElementById('delete_password');
        const buttonDelete = document.getElementById('button_delete');
        const buttonDeleteSend = document.getElementById('button_delete_send');
    </script>

    {{-- KONTROL TAMPILAN INSTRUKTUR DENGAN CHECKLIST --}}
    <script>
        // Variabel
        const needInstructorCheckbox = document.getElementById('need_instructor');
        const instructorField = document.querySelector('.instructor-field');

        // Event listener untuk checkbox
        needInstructorCheckbox.addEventListener('change', function() {
            if (this.checked) {
                instructorField.style.display = 'block';
                inInstructorId.setAttribute('required', 'required');
            } else {
                instructorField.style.display = 'none';
                inInstructorId.removeAttribute('required');
                inInstructorId.value = ''; // Reset nilai
            }

        const editInstructorField = document.querySelector('.edit-instructor-field');
            // Event listener untuk checkbox EDIT
            if (editNeedInstructor && editInstructorField && editInstructorId) {
                editNeedInstructor.addEventListener('change', function() {
                    if (this.checked) {
                        editInstructorField.style.display = 'block';
                        editInstructorId.setAttribute('required', 'required');
                    } else {
                        editInstructorField.style.display = 'none';
                        editInstructorId.removeAttribute('required');
                        editInstructorId.value = '';
                    }
                });
            }

            // Reset saat modal EDIT dibuka
            if (scheduleModalEdit) {
                scheduleModalEdit.addEventListener('show.bs.modal', function() {
                    if (editNeedInstructor) {
                        editNeedInstructor.checked = false;
                    }
                    if (editInstructorField) {
                        editInstructorField.style.display = 'none';
                    }
                    if (editInstructorId) {
                        editInstructorId.removeAttribute('required');
                        editInstructorId.value = '';
                    }
                });
            }


        });

        // Reset saat modal dibuka
        document.getElementById('scheduleModalInput').addEventListener('show.bs.modal', function() {
            needInstructorCheckbox.checked = false;
            instructorField.style.display = 'none';
            inInstructorId.removeAttribute('required');
            inInstructorId.value = '';
        });


        // untuk Edit
        const editNeedInstructor = document.getElementById('edit_need_instructor');
            const editInstructorField = document.querySelector('.edit-instructor-field');

            editNeedInstructor.addEventListener('change', function() {
                if (this.checked) {
                    editInstructorField.style.display = 'block';
                    editInstructorId.setAttribute('required', 'required');
                } else {
                    editInstructorField.style.display = 'none';
                    editInstructorId.removeAttribute('required');
                    editInstructorId.value = '';
                }
        });
    </script>

    {{-- Format --}}
    <script>
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(status, classTypeId, limit) {
            try {
                let url = `{{ route('senam.master.class-schedule.data') }}`;
                let params = new URLSearchParams();

                if (status && status !== '') {
                    params.append('status', status);
                }
                if (classTypeId && classTypeId !== '') {
                    params.append('class_type_id', classTypeId);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }
                const response = await axios.get(url);
                
                if (!response.data.status) {
                    throw new Error(response.data.message || 'Gagal mengambil data');
                }

                const data = response.data.data;

                scheduleTable.innerHTML = '';
                scheduleTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Jasa</th>
                            <th>Jenis Jasa</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th class="text-center pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || !data.data || data.data.length === 0) {
                    scheduleTable.innerHTML += `
                        <tr>
                            <td colspan="8" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                    return;
                } else {
                    data.data.forEach((schedule, index) => {
                        // Format status
                        const statusBadge = schedule.is_active 
                            ? '<span class="badge bg-success">Aktif</span>' 
                            : '<span class="badge bg-danger">Nonaktif</span>';

                        scheduleTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${schedule.services_name}</td>
                                <td>${schedule.class_name}</td>
                                <td style="text-align: right;">${formatRupiah(schedule.price)}</td>
                                <td>${statusBadge}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_edit" 
                                            data-id="${schedule.id}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        ${schedule.is_active ? 
                                            `<button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                                data-id="${schedule.id}" 
                                                data-active="${schedule.is_active}"
                                                title="Nonaktifkan">
                                                <i class="fas fa-ban"></i>
                                            </button>` : 
                                            `<button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                                data-id="${schedule.id}" 
                                                data-active="${schedule.is_active}"
                                                title="Hapus Permanen">
                                                <i class="fas fa-trash"></i>
                                            </button>`
                                        }
                                    </div>
                                </td>
                            </tr>
                        `;

                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                createDynamicAlert('danger', 'Gagal memuat data jadwal');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const status = filterStatus.value;
            const classTypeId = filterClassType.value;
            const limit = syLimit.value;
            fetchData(status, classTypeId, limit);
        });

        // Filter by status
        filterStatus.addEventListener('change', (event) => {
            const status = event.target.value;
            const classTypeId = filterClassType.value;
            const limit = syLimit.value;
            fetchData(status, classTypeId, limit);
        });

        // Filter by class type
        filterClassType.addEventListener('change', (event) => {
            const status = filterStatus.value;
            const classTypeId = event.target.value;
            const limit = syLimit.value;
            fetchData(status, classTypeId, limit);
        });

        // Filter by limit
        syLimit.addEventListener('change', (event) => {
            const status = filterStatus.value;
            const classTypeId = filterClassType.value;
            const limit = event.target.value;
            fetchData(status, classTypeId, limit);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inClassTypeId.value = '';
            inInstructorId.value = '';
            inLocationId.value = '';
            inServicesName.value = '';
            inPrice.value = '';

            // Set min datetime to now
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            // Jika checkbox dicentang, pastikan instruktur dipilih
            if (needInstructorCheckbox.checked && !inInstructorId.value) {
                createDynamicAlert('warning', 'Silakan pilih instruktur');
                return;
            }

            // Lanjutkan dengan proses simpan...
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = {
                    class_type_id: inClassTypeId.value,
                    instructor_id: needInstructorCheckbox.checked ? inInstructorId.value : null,
                    location_id: inLocationId.value,
                    services_name: inServicesName.value,
                    price: inPrice.value
                };

                const response = await axios.post(`{{ route('senam.master.class-schedule.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jadwal berhasil ditambahkan');

                    // Refresh data
                    const status = filterStatus.value;
                    const classTypeId = filterClassType.value;
                    const limit = syLimit.value;
                    await fetchData(status, classTypeId, limit);

                    // Close the modal
                    bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menambahkan jadwal');
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
                    const response = await axios.get(`{{ route('senam.master.class-schedule.data') }}`, {
                        params: { id: id }
                    });
                    
                    if (!response.data.status || !response.data.data) {
                        throw new Error('Data jadwal tidak ditemukan');
                    }

                    const schedule = response.data.data;

                    // Fill the form
                    editScheduleId.value = schedule.id;
                    editServicesName.value = schedule.services_name || '';
                    editClassTypeId.value = schedule.class_type_id;
                    editInstructorId.value = schedule.instructor_id;
                    editLocationId.value = schedule.location_id;
                    editPrice.value = schedule.price || '';
                    // editTypeServices.value = schedule.type_services || '';
                    // editIsActive.value = schedule.is_active ? '1' : '0';

                    // Dalam buttonUpdate event listener
                    const formData = {
                        id: editScheduleId.value,
                        class_type_id: editClassTypeId.value,
                        instructor_id: editInstructorId.value,
                        location_id: editLocationId.value,
                        services_name: editServicesName.value,
                        price: editPrice.value,
                        // type_services: editTypeServices.value,
                        // is_active: editIsActive.value
                    };

                    // Fill the form
                    editScheduleId.value = schedule.id;
                    editClassTypeId.value = schedule.class_type_id;
                    editInstructorId.value = schedule.instructor_id;
                    editLocationId.value = schedule.location_id;
                    // editIsActive.value = schedule.is_active ? '1' : '0';

                    new bootstrap.Modal(scheduleModalEdit).show();
                } catch (error) {
                    console.error('Error:', error);
                    createDynamicAlert('danger', error.message || 'Gagal memuat data jadwal');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {

            if (editNeedInstructor && editNeedInstructor.checked && (!editInstructorId || !editInstructorId.value)) {
                createDynamicAlert('warning', 'Silakan pilih instruktur');
                return;
            }

            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: editScheduleId.value,
                    class_type_id: editClassTypeId.value,
                    instructor_id: editNeedInstructor.checked ? editInstructorId.value : null,
                    location_id: editLocationId.value,
                    services_name: editServicesName.value,
                    price: editPrice.value,
                    // is_active: editIsActive.value
                };

                const response = await axios.post(`{{ route('senam.master.class-schedule.update') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jadwal berhasil diperbarui');
                    
                    const status = filterStatus.value;
                    const classTypeId = filterClassType.value;
                    const limit = syLimit.value;
                    await fetchData(status, classTypeId, limit);
                    
                    bootstrap.Modal.getInstance(scheduleModalEdit).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal memperbarui jadwal');
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
                
                deleteScheduleId.value = id;
                
                if (isActive) {
                    deleteAction.value = 'deactivate';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menonaktifkan jasa ini?';
                    passwordContainer.style.display = 'none';
                } else {
                    deleteAction.value = 'delete';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menghapus permanen jasa ini? Tindakan ini tidak dapat dibatalkan.';
                    passwordContainer.style.display = 'block';
                }

                new bootstrap.Modal(scheduleModalDelete).show();
            }
        });

        buttonDelete.addEventListener('click', async function() {
            buttonDelete.style.display = 'none';
            buttonDeleteSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: deleteScheduleId.value,
                    action: deleteAction.value,
                    password: deletePassword.value
                };

                const response = await axios.post(`{{ route('senam.master.class-schedule.destroy') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const status = filterStatus.value;
                    const classTypeId = filterClassType.value;
                    const limit = syLimit.value;
                    await fetchData(status, classTypeId, limit);
                    
                    bootstrap.Modal.getInstance(scheduleModalDelete).hide();
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