@extends('layouts.senam.admin')
@section('page-title', 'Jadwal Senam')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Jadwal Senam</h4>
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
                            <select class="form-control form-control-sm" id="recurrence">
                                <option value="">Semua Tipe Jadwal</option>
                                <option value="one-time">Sekali</option>
                                <option value="Weekly">Mingguan</option>
                                <option value="monthly ">Bulanan</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control form-control-sm" id="filter_class_type">
                                <option value="">Semua Jenis Senam</option>
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
                            <div class="col-md-6 mb-3">
                                <label for="insert_class_type_id" class="form-label">Jenis Senam</label>
                                <select class="form-control" id="insert_class_type_id" name="insert_class_type_id" required>
                                    <option value="">Pilih Jenis Senam</option>
                                    @foreach($classTypes as $classType)
                                        <option value="{{ $classType->id }}">{{ $classType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_instructor_id" class="form-label">Instruktur</label>
                                <select class="form-control" id="insert_instructor_id" name="insert_instructor_id" required>
                                    <option value="">Pilih Instruktur</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        
                        {{-- <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_recurrence_type" class="form-label">Tipe Jadwal</label>
                                <select class="form-control" id="insert_recurrence_type" name="insert_recurrence_type" required>
                                    <option value="" selected>Pilih Tipe Jadwal</option>
                                    <option value="one-time">Sekali</option>
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly">Bulanan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="recurrence_value_container" style="display: none;">
                                <label for="insert_recurrence_value" class="form-label" id="recurrence_value_label">Hari (Senin-Sabtu)</label>
                                <select class="form-control" id="insert_recurrence_value" name="insert_recurrence_value">
                                    <option value="Monday">Senin</option>
                                    <option value="Tuesday">Selasa</option>
                                    <option value="Wednesday">Rabu</option>
                                    <option value="Thursday">Kamis</option>
                                    <option value="Friday">Jumat</option>
                                    <option value="Saturday">Sabtu</option>
                                    <option value="Sunday">Minggu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="recurrence_value_onetime_container" style="display: none;">
                                <label for="insert_recurrence_value_onetime" class="form-label">Tanggal Sekali</label>
                                <input type="date" class="form-control" id="insert_recurrence_value_onetime" name="insert_recurrence_value_onetime">
                            </div>
                        </div>
                        
                        <div class="row" id="end_recurrence_container" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="insert_end_recurrence_date" class="form-label">Berakhir Pada</label>
                                <input type="date" class="form-control" id="insert_end_recurrence_date" name="insert_end_recurrence_date">
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
                            <div class="col-md-6 mb-3">
                                <label for="edit_class_type_id" class="form-label">Jenis Senam</label>
                                <select class="form-control" id="edit_class_type_id" name="edit_class_type_id" required>
                                    <option value="">Pilih Jenis Senam</option>
                                    @foreach($classTypes as $classType)
                                        <option value="{{ $classType->id }}">{{ $classType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_instructor_id" class="form-label">Instruktur</label>
                                <select class="form-control" id="edit_instructor_id" name="edit_instructor_id" required>
                                    <option value="">Pilih Instruktur</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_location_id" class="form-label">Lokasi</label>
                                <select class="form-control" id="edit_location_id" name="edit_location_id" required>
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_max_participants" class="form-label">Maksimal Peserta</label>
                                <input type="number" class="form-control" id="edit_max_participants" name="edit_max_participants" min="1" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_start_datetime" class="form-label">Tanggal & Waktu Mulai</label>
                                <input type="time" class="form-control" id="edit_start_datetime" name="edit_start_datetime" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_end_datetime" class="form-label">Tanggal & Waktu Selesai</label>
                                <input type="time" class="form-control" id="edit_end_datetime" name="edit_end_datetime" required>
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
        const filterRecurrence = document.getElementById('recurrence');
        const filterClassType = document.getElementById('filter_class_type');
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const scheduleTable = document.getElementById('schedule_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addScheduleBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('scheduleModalInput');
        const inClassTypeId = document.getElementById('insert_class_type_id');
        const inInstructorId = document.getElementById('insert_instructor_id');
        const inRecurrenceType = document.getElementById('insert_recurrence_type');
        const inRecurrenceValue = document.getElementById('insert_recurrence_value');
        const inEndRecurrenceDate = document.getElementById('insert_end_recurrence_date');
        const recurrenceValueContainer = document.getElementById('recurrence_value_container');
        const recurrenceValueLabel = document.getElementById('recurrence_value_label');
        const endRecurrenceContainer = document.getElementById('end_recurrence_container');

        // MODAL EDIT
        const scheduleModalEdit = document.getElementById('scheduleModalEdit');
        const editScheduleId = document.getElementById('edit_schedule_id');
        const editClassTypeId = document.getElementById('edit_class_type_id');
        const editInstructorId = document.getElementById('edit_instructor_id');
        const editLocationId = document.getElementById('edit_location_id');
        const editMaxParticipants = document.getElementById('edit_max_participants');
        const editStartDatetime = document.getElementById('edit_start_datetime');
        const editEndDatetime = document.getElementById('edit_end_datetime');
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

    {{-- RECURRENCE TYPE HANDLER --}}
    <script>
        const recurrenceValueOnetimeContainer = document.getElementById('recurrence_value_onetime_container');
        const inRecurrenceValueOnetime = document.getElementById('insert_recurrence_value_onetime');

        inRecurrenceType.addEventListener('change', function() {
            const value = this.value;
            if (value === 'one-time') {
                recurrenceValueContainer.style.display = 'none';
                endRecurrenceContainer.style.display = 'none';
                recurrenceValueOnetimeContainer.style.display = 'block';
            } else {
                recurrenceValueContainer.style.display = 'block';
                endRecurrenceContainer.style.display = 'block';
                recurrenceValueOnetimeContainer.style.display = 'none';
                if (value === 'weekly') {
                    recurrenceValueLabel.textContent = 'Hari (Senin-Sabtu)';
                    inRecurrenceValue.innerHTML = `
                        <option value="Monday">Senin</option>
                        <option value="Tuesday">Selasa</option>
                        <option value="Wednesday">Rabu</option>
                        <option value="Thursday">Kamis</option>
                        <option value="Friday">Jumat</option>
                        <option value="Saturday">Sabtu</option>
                        <option value="Sunday">Minggu</option>
                    `;
                } else if (value === 'monthly') {
                    recurrenceValueLabel.textContent = 'Tanggal (1-31)';
                    inRecurrenceValue.innerHTML = '';
                    for (let i = 1; i <= 31; i++) {
                        inRecurrenceValue.innerHTML += `<option value="${i}">${i}</option>`;
                    }
                }
            }
        });
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(status, date, classTypeId, limit) {
            try {
                let url = `{{ route('senam.master.class-schedule.data') }}`;
                let params = new URLSearchParams();

                if (status && status !== '') {
                    params.append('status', status);
                }
                // Ganti date menjadi recurrence_type
                if (date && date !== '') {
                    params.append('recurrence_type', date);
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
                            <th>Jenis Senam</th>
                            <th>Lokasi</th>
                            <th>Tanggal & Waktu</th>
                            <th>Peserta</th>
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
                        // Format date and time
                        const startDate = new Date(schedule.start_datetime);
                        const endDate = new Date(schedule.end_datetime);
                        
                        const formattedDate = startDate.toLocaleDateString('id-ID', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                        
                        const startTime = startDate.toLocaleTimeString('id-ID', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        });
                        
                        const endTime = endDate.toLocaleTimeString('id-ID', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        });

                        // Format status
                        const statusBadge = schedule.is_active 
                            ? '<span class="badge bg-success">Aktif</span>' 
                            : '<span class="badge bg-danger">Nonaktif</span>';

                        // Format recurrence
                        let recurrenceText = '';
                        if (schedule.recurrence_type === 'weekly') {
                            const days = {
                                'Monday': 'Senin',
                                'Tuesday': 'Selasa',
                                'Wednesday': 'Rabu',
                                'Thursday': 'Kamis',
                                'Friday': 'Jumat',
                                'Saturday': 'Sabtu',
                                'Sunday': 'Minggu'
                            };
                            recurrenceText = `Setiap ${days[schedule.recurrence_value]}`;
                        } else if (schedule.recurrence_type === 'monthly') {
                            recurrenceText = `Setiap tanggal ${schedule.recurrence_value}`;
                        } else {
                            recurrenceText = `Tanggal  ${schedule.recurrence_value}`;
                        }

                        scheduleTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>
                                    <div class="fw-medium">${schedule.class_name}</div>
                                    <div class="small text-muted">instruktur: ${schedule.instructor_name}</div>
                                </td>
                                <td>${schedule.location_name}</td>
                                <td>
                                    <div class="fw-medium">${recurrenceText}</div>
                                    <div class="small text-muted">${schedule.start_datetime} - ${schedule.end_datetime}</div>
                                </td>
                                <td>0/${schedule.max_participants}</td>
                                <td>${statusBadge}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_edit" 
                                            data-id="${schedule.id}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${schedule.id}" 
                                            data-active="${schedule.is_active}"
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
                createDynamicAlert('danger', 'Gagal memuat data jadwal');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const status = filterStatus.value;
            const recurrence_type = filterRecurrence.value;
            const classTypeId = filterClassType.value;
            const limit = syLimit.value;
            fetchData(status, recurrence_type, classTypeId, limit);
        });

        // Filter by status
        filterStatus.addEventListener('change', (event) => {
            const status = event.target.value;
            const recurrence_type = filterRecurrence.value;
            const classTypeId = filterClassType.value;
            const limit = syLimit.value;
            fetchData(status, recurrence_type, classTypeId, limit);
        });

        // Filter by recurrence type
        filterRecurrence.addEventListener('change', (event) => {
            const status = filterStatus.value;
            const recurrence_type = event.target.value;
            const classTypeId = filterClassType.value;
            const limit = syLimit.value;
            fetchData(status, recurrence_type, classTypeId, limit);
        });

        // Filter by class type
        filterClassType.addEventListener('change', (event) => {
            const status = filterStatus.value;
            const recurrence_type = filterRecurrence.value;
            const classTypeId = event.target.value;
            const limit = syLimit.value;
            fetchData(status, recurrence_type, classTypeId, limit);
        });

        // Filter by limit
        syLimit.addEventListener('change', (event) => {
            const status = filterStatus.value;
            const recurrence_type = filterRecurrence.value;
            const classTypeId = filterClassType.value;
            const limit = event.target.value;
            fetchData(status, recurrence_type, classTypeId, limit);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inClassTypeId.value = '';
            inInstructorId.value = '';
            inRecurrenceType.value = '';
            inRecurrenceValue.value = '';
            inRecurrenceValueOnetime.value = '';
            inEndRecurrenceDate.value = '';
            recurrenceValueContainer.style.display = 'none';
            endRecurrenceContainer.style.display = 'none';
            
            // Set min datetime to now
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - timezoneOffset)).toISOString().slice(0, 16);
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                let recurrence_value = null;
                if (inRecurrenceType.value === 'one-time') {
                    recurrence_value = inRecurrenceValueOnetime.value;
                } else if (inRecurrenceType.value === 'weekly' || inRecurrenceType.value === 'monthly') {
                    recurrence_value = inRecurrenceValue.value;
                }
                const formData = {
                    class_type_id: inClassTypeId.value,
                    instructor_id: inInstructorId.value,
                    recurrence_type: inRecurrenceType.value,
                    recurrence_value: recurrence_value,
                    end_recurrence_date: inRecurrenceType.value !== 'one-time' ? inEndRecurrenceDate.value : null
                };

                const response = await axios.post(`{{ route('senam.master.class-schedule.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jadwal berhasil ditambahkan');

                    // Refresh data
                    const status = filterStatus.value;
                    const date = filterRecurrence.value;
                    const classTypeId = filterClassType.value;
                    const limit = syLimit.value;
                    await fetchData(status, date, classTypeId, limit);

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
                    
                    // Pastikan response.data.data ada dan bukan array
                    if (!response.data.status || !response.data.data) {
                        throw new Error('Data jadwal tidak ditemukan');
                    }

                    const schedule = response.data.data;

                    // Format time for input fields (type="time" expects HH:mm)
                    function toTimeString(val) {
                        if (!val) return '';
                        // If already in HH:mm:ss or HH:mm format
                        if (typeof val === 'string') {
                            // If format is HH:mm:ss
                            if (/^\d{2}:\d{2}:\d{2}$/.test(val)) {
                                return val.slice(0,5);
                            }
                            // If format is HH:mm
                            if (/^\d{2}:\d{2}$/.test(val)) {
                                return val;
                            }
                        }
                        // Try to parse as Date
                        const d = new Date(val);
                        if (!isNaN(d.getTime())) {
                            return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                        }
                        return '';
                    }

                    let startTime = toTimeString(schedule.start_datetime);
                    let endTime = toTimeString(schedule.end_datetime);

                    // Fill the form
                    editScheduleId.value = schedule.id;
                    editClassTypeId.value = schedule.class_type_id;
                    editInstructorId.value = schedule.instructor_id;
                    editLocationId.value = schedule.location_id;
                    editMaxParticipants.value = schedule.max_participants;
                    editStartDatetime.value = startTime;
                    editEndDatetime.value = endTime;
                    editIsActive.value = schedule.is_active ? '1' : '0';

                    new bootstrap.Modal(scheduleModalEdit).show();
                } catch (error) {
                    console.error('Error:', error);
                    createDynamicAlert('danger', error.message || 'Gagal memuat data jadwal');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                // Convert HH:mm to HH:mm:ss for backend
                function toHHMMSS(val) {
                    if (!val) return '';
                    if (/^\d{2}:\d{2}$/.test(val)) {
                        return val + ':00';
                    }
                    if (/^\d{2}:\d{2}:\d{2}$/.test(val)) {
                        return val;
                    }
                    return val;
                }

                const formData = {
                    id: editScheduleId.value,
                    class_type_id: editClassTypeId.value,
                    instructor_id: editInstructorId.value,
                    location_id: editLocationId.value,
                    start_datetime: toHHMMSS(editStartDatetime.value),
                    end_datetime: toHHMMSS(editEndDatetime.value),
                    max_participants: editMaxParticipants.value,
                    is_active: editIsActive.value
                };

                const response = await axios.post(`{{ route('senam.master.class-schedule.update') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jadwal berhasil diperbarui');
                    
                    const status = filterStatus.value;
                    const date = filterRecurrence.value;
                    const classTypeId = filterClassType.value;
                    const limit = syLimit.value;
                    await fetchData(status, date, classTypeId, limit);
                    
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
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menonaktifkan jadwal ini?';
                    passwordContainer.style.display = 'none';
                } else {
                    deleteAction.value = 'delete';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menghapus permanen jadwal ini?';
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
                    const date = filterRecurrence.value;
                    const classTypeId = filterClassType.value;
                    const limit = syLimit.value;
                    await fetchData(status, date, classTypeId, limit);
                    
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