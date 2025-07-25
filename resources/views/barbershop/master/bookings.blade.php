@extends('layouts.barbershop.admin')
@section('page-title', 'Booking')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Booking</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addBookingBtn">
                <i class="fas fa-plus me-1"></i> Buat Booking
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="filter_barber">
                                <option value="">Semua Barber</option>
                                @foreach($barbers as $barber)
                                    <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control form-control-sm" id="filter_status">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" id="filter_date">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control form-control-sm" id="short_by_limit">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-card-body">
                    <div class="table-container">
                        <table id="booking_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Booking -->
    <div class="modal fade" id="bookingModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="bookingModalLabel">Buat Booking Baru</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bookingFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_barber_id" class="form-label">Barber</label>
                                <select class="form-control" id="insert_barber_id" name="insert_barber_id" required>
                                    <option value="">Pilih Barber</option>
                                    @foreach($barbers as $barber)
                                        <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_service_id" class="form-label">Layanan</label>
                                <select class="form-control" id="insert_service_id" name="insert_service_id" required>
                                    <option value="">Pilih Layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-duration="{{ $service->duration }}">
                                            {{ $service->name }} ({{ $service->duration }} menit)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_customer_name" class="form-label">Nama Customer</label>
                                <input type="text" class="form-control" id="insert_customer_name" name="insert_customer_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_customer_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="insert_customer_phone" name="insert_customer_phone" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="insert_customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="insert_customer_email" name="insert_customer_email" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_booking_date" class="form-label">Tanggal Booking</label>
                                <input type="date" class="form-control" id="insert_booking_date" name="insert_booking_date" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_start_time" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="insert_start_time" name="insert_start_time" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="insert_notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="insert_notes" name="insert_notes" rows="2"></textarea>
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

    <!-- Modal untuk Update Status -->
    <div class="modal fade" id="statusModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Update Status Booking</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="status_booking_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="update_status" class="form-label">Status</label>
                            <select class="form-control" id="update_status" name="update_status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_update_status" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_update_status_send" class="btn btn-primary" style="display: none;">Menyimpan...</button>
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
        const filterBarber = document.getElementById('filter_barber');
        const filterStatus = document.getElementById('filter_status');
        const filterDate = document.getElementById('filter_date');
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const bookingsTable = document.getElementById('booking_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addBookingBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('bookingModalInput');
        const inBarberId = document.getElementById('insert_barber_id');
        const inServiceId = document.getElementById('insert_service_id');
        const inCustomerName = document.getElementById('insert_customer_name');
        const inCustomerEmail = document.getElementById('insert_customer_email');
        const inCustomerPhone = document.getElementById('insert_customer_phone');
        const inBookingDate = document.getElementById('insert_booking_date');
        const inStartTime = document.getElementById('insert_start_time');
        const inNotes = document.getElementById('insert_notes');

        // MODAL STATUS
        const statusModal = document.getElementById('statusModal');
        const statusBookingId = document.getElementById('status_booking_id');
        const updateStatus = document.getElementById('update_status');
        const buttonUpdateStatus = document.getElementById('button_update_status');
        const buttonUpdateStatusSend = document.getElementById('button_update_status_send');
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(barberId, status, date, limit) {
            try {
                let url = `{{ route('barbershop.master.bookings.data') }}`;
                let params = new URLSearchParams();

                if (barberId && barberId !== '') {
                    params.append('barber_id', barberId);
                }
                if (status && status !== '') {
                    params.append('status', status);
                }
                if (date && date !== '') {
                    params.append('date', date);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                bookingsTable.innerHTML = '';
                bookingsTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Customer</th>
                            <th>Barber</th>
                            <th>Layanan</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.length === 0) {
                    bookingsTable.innerHTML += `
                        <tr>
                            <td colspan="7" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.forEach((booking, index) => {
                        // Format status badge
                        let statusBadge = '';
                        switch(booking.status) {
                            case 'pending':
                                statusBadge = '<span class="badge bg-warning">Pending</span>';
                                break;
                            case 'confirmed':
                                statusBadge = '<span class="badge bg-primary">Confirmed</span>';
                                break;
                            case 'completed':
                                statusBadge = '<span class="badge bg-success">Completed</span>';
                                break;
                            case 'cancelled':
                                statusBadge = '<span class="badge bg-danger">Cancelled</span>';
                                break;
                        }

                        // Format date
                        const bookingDate = new Date(booking.booking_date);
                        const formattedDate = bookingDate.toLocaleDateString('id-ID', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });

                        bookingsTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td>
                                    <div class="fw-medium">${booking.customer_name}</div>
                                    <div class="small text-muted">${booking.customer_phone}</div>
                                </td>
                                <td>${booking.barber_name}</td>
                                <td>${booking.service_name}</td>
                                <td>
                                    <div>${formattedDate}</div>
                                    <div class="small text-muted">${booking.start_time} - ${booking.end_time}</div>
                                </td>
                                <td>${statusBadge}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_update_status" 
                                            data-id="${booking.id}" 
                                            data-status="${booking.status}"
                                            title="Update Status">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                createDynamicAlert('danger', 'Gagal memuat data booking');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const barberId = filterBarber.value;
            const status = filterStatus.value;
            const date = filterDate.value;
            const limit = syLimit.value;
            fetchData(barberId, status, date, limit);
        });

        // Filter by barber
        filterBarber.addEventListener('change', (event) => {
            const barberId = event.target.value;
            const status = filterStatus.value;
            const date = filterDate.value;
            const limit = syLimit.value;
            fetchData(barberId, status, date, limit);
        });

        // Filter by status
        filterStatus.addEventListener('change', (event) => {
            const barberId = filterBarber.value;
            const status = event.target.value;
            const date = filterDate.value;
            const limit = syLimit.value;
            fetchData(barberId, status, date, limit);
        });

        // Filter by date
        filterDate.addEventListener('change', (event) => {
            const barberId = filterBarber.value;
            const status = filterStatus.value;
            const date = event.target.value;
            const limit = syLimit.value;
            fetchData(barberId, status, date, limit);
        });

        // Filter by limit
        syLimit.addEventListener('change', (event) => {
            const barberId = filterBarber.value;
            const status = filterStatus.value;
            const date = filterDate.value;
            const limit = event.target.value;
            fetchData(barberId, status, date, limit);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inBarberId.value = '';
            inServiceId.value = '';
            inCustomerName.value = '';
            inCustomerEmail.value = '';
            inCustomerPhone.value = '';
            inBookingDate.value = '';
            inStartTime.value = '';
            inNotes.value = '';
            
            // Set min date to today
            inBookingDate.min = new Date().toISOString().split('T')[0];
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('barbershop.master.bookings.store') }}`, {
                    barber_id: inBarberId.value,
                    service_id: inServiceId.value,
                    customer_name: inCustomerName.value,
                    customer_email: inCustomerEmail.value,
                    customer_phone: inCustomerPhone.value,
                    booking_date: inBookingDate.value,
                    start_time: inStartTime.value,
                    notes: inNotes.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Booking berhasil dibuat');

                    // Refresh data
                    const barberId = filterBarber.value;
                    const status = filterStatus.value;
                    const date = filterDate.value;
                    const limit = syLimit.value;
                    await fetchData(barberId, status, date, limit);

                    // Close the modal
                    bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat membuat booking');
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

    {{-- UPDATE STATUS --}}
    <script>
        document.addEventListener('click', async function(event) {
            const statusBtn = event.target.closest('.btn_update_status');
            if (statusBtn) {
                event.preventDefault();
                const id = statusBtn.dataset.id;
                const currentStatus = statusBtn.dataset.status;

                statusBookingId.value = id;
                updateStatus.value = currentStatus;
                
                new bootstrap.Modal(statusModal).show();
            }
        });

        buttonUpdateStatus.addEventListener('click', async function() {
            buttonUpdateStatus.style.display = 'none';
            buttonUpdateStatusSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('barbershop.master.bookings.update') }}`, {
                    id: statusBookingId.value,
                    status: updateStatus.value,
                });

                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const barberId = filterBarber.value;
                    const status = filterStatus.value;
                    const date = filterDate.value;
                    const limit = syLimit.value;
                    await fetchData(barberId, status, date, limit);
                    
                    bootstrap.Modal.getInstance(statusModal).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal mengupdate status');
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
                buttonUpdateStatus.style.display = 'inline-block';
                buttonUpdateStatusSend.style.display = 'none';
            }
        });
    </script>
@endpush