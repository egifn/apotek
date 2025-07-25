@extends('layouts.barbershop.admin')
@section('page-title', 'Jadwal')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Master Data Jadwal</h4>
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
                <div class="table-card-header">
                    <div class="col-lg-2">
                        <select class="form-control form-control-sm" id="filter_barber">
                            <option value="">Semua Barber</option>
                            @foreach($barbers as $barber)
                                <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select class="form-control form-control-sm" id="filter_day">
                            <option value="">Semua Hari</option>
                            <option value="monday">Senin</option>
                            <option value="tuesday">Selasa</option>
                            <option value="wednesday">Rabu</option>
                            <option value="thursday">Kamis</option>
                            <option value="friday">Jumat</option>
                            <option value="saturday">Sabtu</option>
                            <option value="sunday">Minggu</option>
                        </select>
                    </div>
                    <div class="col-lg-1">
                        <select class="form-control form-control-sm" id="short_by_limit">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="scheduleModalLabel">Tambah Jadwal Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="scheduleFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_barber_id" class="form-label">Barber</label>
                            <select class="form-control" id="insert_barber_id" name="insert_barber_id" required>
                                <option value="">Pilih Barber</option>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="insert_day" class="form-label">Hari</label>
                            <select class="form-control" id="insert_day" name="insert_day" required>
                                <option value="">Pilih Hari</option>
                                <option value="monday">Senin</option>
                                <option value="tuesday">Selasa</option>
                                <option value="wednesday">Rabu</option>
                                <option value="thursday">Kamis</option>
                                <option value="friday">Jumat</option>
                                <option value="saturday">Sabtu</option>
                                <option value="sunday">Minggu</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="insert_start_time" name="insert_start_time" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" id="insert_end_time" name="insert_end_time" required>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="scheduleModalLabel">Edit Jadwal</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="scheduleFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_scheduleId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_barber_id" class="form-label">Barber</label>
                            <select class="form-control" id="edit_barber_id" name="edit_barber_id" required>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_day" class="form-label">Hari</label>
                            <select class="form-control" id="edit_day" name="edit_day" required>
                                <option value="monday">Senin</option>
                                <option value="tuesday">Selasa</option>
                                <option value="wednesday">Rabu</option>
                                <option value="thursday">Kamis</option>
                                <option value="friday">Jumat</option>
                                <option value="saturday">Sabtu</option>
                                <option value="sunday">Minggu</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="edit_start_time" name="edit_start_time" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_end_time" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" id="edit_end_time" name="edit_end_time" required>
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    <div class="mb-3">
                        <label for="deletePassword" class="form-label">Masukkan Password:</label>
                        <input type="password" class="form-control" id="deletePassword" placeholder="Password untuk menghapus">
                    </div>
                    <input type="hidden" id="deleteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmActionBtn" disabled>
                        <span class="spinner-border spinner-border-sm d-none" id="actionSpinner"></span>
                        <span id="actionBtnText">Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    {{-- SET VARIABLE --}}
    <script>
        // Filter variables
        const filterBarber = document.getElementById('filter_barber');
        const filterDay = document.getElementById('filter_day');
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const schedulesTable = document.getElementById('schedule_table');

        // MODAL EDIT
        const componentModalFormEdit = document.getElementById('scheduleModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_scheduleId');
        const edBarberId = document.getElementById('edit_barber_id');
        const edDay = document.getElementById('edit_day');
        const edStartTime = document.getElementById('edit_start_time');
        const edEndTime = document.getElementById('edit_end_time');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addScheduleBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('scheduleModalInput');
        const inBarberId = document.getElementById('insert_barber_id');
        const inDay = document.getElementById('insert_day');
        const inStartTime = document.getElementById('insert_start_time');
        const inEndTime = document.getElementById('insert_end_time');
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(barberId, day, limit) {
            try {
                let url = `{{ route('barbershop.master.schedules.data') }}`;
                let params = new URLSearchParams();

                if (barberId && barberId !== '') {
                    params.append('barber_id', barberId);
                }
                if (day && day !== '') {
                    params.append('day', day);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                schedulesTable.innerHTML = '';
                schedulesTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Barber</th>
                            <th>Hari</th>
                            <th>Jam Kerja</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    schedulesTable.innerHTML += `
                        <tr>
                            <td colspan="5" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((schedule, index) => {
                        // Convert day to Indonesian
                        const days = {
                            'monday': 'Senin',
                            'tuesday': 'Selasa',
                            'wednesday': 'Rabu',
                            'thursday': 'Kamis',
                            'friday': 'Jumat',
                            'saturday': 'Sabtu',
                            'sunday': 'Minggu'
                        };

                        schedulesTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${schedule.barber_name}</td>
                                <td>${days[schedule.day]}</td>
                                <td>${schedule.start_time} - ${schedule.end_time}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${schedule.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${schedule.id}" 
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
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const barberId = filterBarber.value;
            const day = filterDay.value;
            const limit = syLimit.value;
            fetchData(barberId, day, limit);
        });

        // Filter by barber
        filterBarber.addEventListener('change', (event) => {
            const barberId = event.target.value;
            const day = filterDay.value;
            const limit = syLimit.value;
            fetchData(barberId, day, limit);
        });

        // Filter by day
        filterDay.addEventListener('change', (event) => {
            const barberId = filterBarber.value;
            const day = event.target.value;
            const limit = syLimit.value;
            fetchData(barberId, day, limit);
        });

        // Filter by limit
        syLimit.addEventListener('change', (event) => {
            const barberId = filterBarber.value;
            const day = filterDay.value;
            const limit = event.target.value;
            fetchData(barberId, day, limit);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inBarberId.value = '';
            inDay.value = '';
            inStartTime.value = '';
            inEndTime.value = '';
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('barbershop.master.schedules.store') }}`, {
                    barber_id: inBarberId.value,
                    day: inDay.value,
                    start_time: inStartTime.value,
                    end_time: inEndTime.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Jadwal berhasil ditambahkan');

                    // Refresh data
                    const barberId = filterBarber.value;
                    const day = filterDay.value;
                    const limit = syLimit.value;
                    await fetchData(barberId, day, limit);

                    // Close the modal
                    bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menyimpan jadwal');
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

    {{-- UPDATE DATA --}}
    <script>
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                event.preventDefault();
                const id = editBtn.dataset.id;
                let url = `{{ route('barbershop.master.schedules.data') }}?id=${id}`;

                try {
                    const response = await axios.get(url);
                    const data = response.data.data[0];

                    // Set values to form fields
                    edId.value = data.id;
                    edBarberId.value = data.barber_id;
                    edDay.value = data.day;
                    edStartTime.value = data.start_time;
                    edEndTime.value = data.end_time;
                    
                    new bootstrap.Modal(componentModalFormEdit).show();

                } catch (error) {
                    console.error('Error fetching data:', error);
                    createDynamicAlert('danger', 'Gagal memuat data jadwal');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('barbershop.master.schedules.update') }}`, {
                    id: edId.value,
                    barber_id: edBarberId.value,
                    day: edDay.value,
                    start_time: edStartTime.value,
                    end_time: edEndTime.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const barberId = filterBarber.value;
                    const day = filterDay.value;
                    const limit = syLimit.value;
                    await fetchData(barberId, day, limit);
                    
                    bootstrap.Modal.getInstance(componentModalFormEdit).hide();
                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message || 'Gagal memperbarui jadwal');
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
        document.addEventListener('DOMContentLoaded', function() {
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
                const password = document.getElementById('deletePassword').value;

                btn.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Menghapus...';

                try {
                    const response = await axios.post(
                        `{{ route('barbershop.master.schedules.destroy') }}`, {
                            id: id,
                            password: password,
                            action: 'delete'
                        });

                    if (response.data.status === true) {
                        createDynamicAlert('success', response.data.message);

                        const barberId = filterBarber.value;
                        const day = filterDay.value;
                        const limit = syLimit.value;
                        await fetchData(barberId, day, limit);

                        // Tutup modal
                        bootstrap.Modal.getInstance('#confirmDeleteModal').hide();
                        document.getElementById('deletePassword').value = '';
                    } else {
                        createDynamicAlert('danger', response.data.message || 'Gagal menghapus jadwal');
                    }
                } catch (error) {
                    console.error("Error processing data:", error);
                    if (error.response && error.response.data) {
                        const errorData = error.response.data;
                        createDynamicAlert('danger', errorData.message || 'Terjadi kesalahan saat menghapus');
                    } else {
                        createDynamicAlert('danger', 'Terjadi kesalahan jaringan');
                    }
                } finally {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Hapus';
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
                document.getElementById('deletePassword').value = '';
                document.getElementById('confirmActionBtn').disabled = true;

                // Tampilkan modal
                const confirmModal = new bootstrap.Modal('#confirmDeleteModal');
                confirmModal.show();
            }
        });
    </script>
@endpush