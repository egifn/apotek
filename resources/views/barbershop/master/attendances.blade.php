@extends('layouts.barbershop.admin')
@section('page-title', 'Absensi Barber')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Absensi Barber</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addAttendanceBtn">
                <i class="fas fa-plus me-1"></i> Tambah Absensi
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="col-lg-2">
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="attendanceDate" value="{{ date('Y-m-d') }}">
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="refreshBtn">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="barberFilter">
                            <option value="">Semua Barber</option>
                        </select>
                    </div>
                </div>

    
                <div class="table-card-body">
                    <div class="table-container">
                        <table id="attendance_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Absensi -->
    <div class="modal fade" id="attendanceModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="attendanceModalLabel">Tambah Absensi Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="attendanceFormInput" method="POST">
                    @csrf
                    <input type="hidden" id="attendance_date" name="attendance_date" value="{{ date('Y-m-d') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="barber_id" class="form-label">Barber <span class="text-danger">*</span></label>
                            <select class="form-control" id="barber_id" name="barber_id" required>
                                <option value="">Pilih Barber</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_in" class="form-label">Jam Masuk</label>
                                    <input type="time" class="form-control" id="check_in" name="check_in">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_out" class="form-label">Jam Pulang</label>
                                    <input type="time" class="form-control" id="check_out" name="check_out">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="customer_count" class="form-label">Jumlah Pelanggan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="customer_count" name="customer_count" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
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

    <!-- Modal untuk Edit Absensi -->
    <div class="modal fade" id="attendanceModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="attendanceModalLabel">Edit Absensi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="attendanceFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_attendance_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Barber</label>
                            <input type="text" class="form-control" id="edit_barber_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="edit_attendance_date" readonly>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_check_in" class="form-label">Jam Masuk</label>
                                    <input type="time" class="form-control" id="edit_check_in" name="check_in">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_check_out" class="form-label">Jam Pulang</label>
                                    <input type="time" class="form-control" id="edit_check_out" name="check_out">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_customer_count" class="form-label">Jumlah Pelanggan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_customer_count" name="customer_count" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
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
@endpush

@push('scripts')
    <script>
        // Variabel global
        const attendanceDate = document.getElementById('attendanceDate');
        const barberFilter = document.getElementById('barberFilter');
        const refreshBtn = document.getElementById('refreshBtn');
        const attendanceTable = document.getElementById('attendance_table');
        
        // Modal Input
        const addAttendanceBtn = document.getElementById('addAttendanceBtn');
        const attendanceModalInput = document.getElementById('attendanceModalInput');
        const attendanceFormInput = document.getElementById('attendanceFormInput');
        const barberIdInput = document.getElementById('barber_id');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        
        // Modal Edit
        const attendanceModalEdit = document.getElementById('attendanceModalEdit');
        const editAttendanceId = document.getElementById('edit_attendance_id');
        const editBarberName = document.getElementById('edit_barber_name');
        const editAttendanceDate = document.getElementById('edit_attendance_date');
        const editCheckIn = document.getElementById('edit_check_in');
        const editCheckOut = document.getElementById('edit_check_out');
        const editCustomerCount = document.getElementById('edit_customer_count');
        const editNotes = document.getElementById('edit_notes');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');

        // Fungsi untuk memuat data absensi
        async function fetchAttendanceData(date, barberId = null) {
            try {
                const params = new URLSearchParams();
                params.append('date', date);
                if (barberId) params.append('barber_id', barberId);

                const response = await axios.get(`{{ route('barbershop.master.attendances.data') }}?${params.toString()}`);
                const data = response.data.data;
                const barbers = response.data.barbers || [];

                // Update dropdown barber
                barberIdInput.innerHTML = '<option value="">Pilih Barber</option>';
                barbers.forEach(barber => {
                    barberIdInput.innerHTML += `<option value="${barber.id}">${barber.name}</option>`;
                });

                // Update filter barber
                barberFilter.innerHTML = '<option value="">Semua Barber</option>';
                barbers.forEach(barber => {
                    barberFilter.innerHTML += `<option value="${barber.id}">${barber.name}</option>`;
                });

                // Update tabel
                attendanceTable.innerHTML = `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Barber</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Jumlah Pelanggan</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (data.length === 0) {
                    attendanceTable.innerHTML += `
                        <tr>
                            <td colspan="6" class="ps-4 text-center">Tidak ada Data Absensi</td>
                        </tr>
                    `;
                } else {
                    data.forEach((attendance, index) => {
                        attendanceTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${attendance.barber_name}</td>
                                <td>${attendance.check_in || '-'}</td>
                                <td>${attendance.check_out || '-'}</td>
                                <td>${attendance.customer_count}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_show_modal_form_edit" 
                                            data-id="${attendance.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching attendance data:', error);
                createDynamicAlert('danger', 'Gagal memuat data absensi');
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            fetchAttendanceData(attendanceDate.value);
        });

        attendanceDate.addEventListener('change', () => {
            fetchAttendanceData(attendanceDate.value, barberFilter.value);
        });

        barberFilter.addEventListener('change', () => {
            fetchAttendanceData(attendanceDate.value, barberFilter.value);
        });

        refreshBtn.addEventListener('click', () => {
            fetchAttendanceData(attendanceDate.value, barberFilter.value);
        });

        // Tambah Absensi
        addAttendanceBtn.addEventListener('click', () => {
            document.getElementById('attendance_date').value = attendanceDate.value;
            new bootstrap.Modal(attendanceModalInput).show();
        });

        buttonInsert.addEventListener('click', async () => {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = new FormData(attendanceFormInput);
                const response = await axios.post(`{{ route('barbershop.master.attendances.store') }}`, formData);

                if (response.data.status) {
                    createDynamicAlert('success', response.data.message);
                    fetchAttendanceData(attendanceDate.value, barberFilter.value);
                    bootstrap.Modal.getInstance(attendanceModalInput).hide();
                    attendanceFormInput.reset();
                } else {
                    createDynamicAlert('danger', response.data.message || 'Gagal menyimpan absensi');
                }
            } catch (error) {
                console.error('Error:', error);
                if (error.response && error.response.data.type === 'validation') {
                    showValidationErrors(error.response.data.errors);
                } else {
                    createDynamicAlert('danger', 'Terjadi kesalahan saat menyimpan absensi');
                }
            } finally {
                buttonInsert.style.display = 'inline-block';
                buttonInsertSend.style.display = 'none';
            }
        });

        // Edit Absensi
        document.addEventListener('click', async (event) => {
            const editBtn = event.target.closest('.btn_show_modal_form_edit');
            if (editBtn) {
                const attendanceId = editBtn.dataset.id;
                try {
                    const response = await axios.get(`{{ route('barbershop.master.attendances.data') }}?id=${attendanceId}`);
                    const attendance = response.data.data[0];

                    editAttendanceId.value = attendance.id;
                    editBarberName.value = attendance.barber_name;
                    editAttendanceDate.value = attendance.attendance_date;
                    editCheckIn.value = attendance.check_in || '';
                    editCheckOut.value = attendance.check_out || '';
                    editCustomerCount.value = attendance.customer_count;
                    editNotes.value = attendance.notes || '';

                    new bootstrap.Modal(attendanceModalEdit).show();
                } catch (error) {
                    console.error('Error fetching attendance data:', error);
                    createDynamicAlert('danger', 'Gagal memuat data absensi');
                }
            }
        });

        buttonUpdate.addEventListener('click', async () => {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const formData = new FormData(document.getElementById('attendanceFormEdit'));
                const response = await axios.post(`{{ route('barbershop.master.attendances.update') }}`, formData);

                if (response.data.status) {
                    createDynamicAlert('success', response.data.message);
                    fetchAttendanceData(attendanceDate.value, barberFilter.value);
                    bootstrap.Modal.getInstance(attendanceModalEdit).hide();
                } else {
                    createDynamicAlert('danger', response.data.message || 'Gagal memperbarui absensi');
                }
            } catch (error) {
                console.error('Error:', error);
                if (error.response && error.response.data.type === 'validation') {
                    showValidationErrors(error.response.data.errors);
                } else {
                    createDynamicAlert('danger', 'Terjadi kesalahan saat memperbarui absensi');
                }
            } finally {
                buttonUpdate.style.display = 'inline-block';
                buttonUpdateSend.style.display = 'none';
            }
        });
    </script>
@endpush