@extends('layouts.senam.admin')
@section('page-title', 'Member')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Member</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addMemberBtn">
                <i class="fas fa-plus me-1"></i> Tambah Member
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
                        <table id="member_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah Member -->
    <div class="modal fade" id="memberModalInput">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="memberModalLabel">Tambah Member</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="memberFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_name" class="form-label">Nama Member</label>
                                <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="insert_phone" name="insert_phone" required>
                            </div>
                        </div>
                        <input type="date" class="form-control" id="insert_join_date" name="insert_join_date" required
                            hidden>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="insert_start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="insert_start_date" name="insert_start_date"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="insert_end_date" class="form-label">Tanggal Berakhir</label>
                                <input type="date" class="form-control" id="insert_end_date" name="insert_end_date"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="insert_total_quota" class="form-label">Kuota</label>
                                <input type="number" class="form-control" id="insert_total_quota" name="insert_total_quota"
                                    min="1" required readonly>
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

    <!-- Modal untuk Edit Member -->
    <div class="modal fade" id="memberModalEdit">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Edit Member</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="memberFormEdit">
                    @csrf
                    <input type="hidden" id="edit_member_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label">Nama Member</label>
                                <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="edit_phone" name="edit_phone" required>
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

    <!-- Modal untuk Tambah Kuota -->
    <div class="modal fade" id="quotaModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Tambah Kuota Member</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="quotaFormInput">
                    @csrf
                    <input type="hidden" id="quota_member_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_additional_quota" class="form-label">Jumlah Kuota Tambahan</label>
                            <input type="number" class="form-control" id="insert_additional_quota"
                                name="insert_additional_quota" min="1" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_quota_start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="insert_quota_start_date"
                                    name="insert_quota_start_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_quota_end_date" class="form-label">Tanggal Berakhir</label>
                                <input type="date" class="form-control" id="insert_quota_end_date"
                                    name="insert_quota_end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_insert_quota" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_insert_quota_send" class="btn btn-primary"
                            style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk History Kuota -->
    <div class="modal fade" id="quotaHistoryModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">History Kuota Member</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-container">
                        <table id="quota_history_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Hapus/Nonaktifkan -->
    <div class="modal fade" id="memberModalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Konfirmasi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="memberFormDelete">
                    @csrf
                    <input type="hidden" id="delete_member_id">
                    <input type="hidden" id="delete_action">
                    <div class="modal-body">
                        <p id="delete_message">Apakah Anda yakin ingin menghapus member ini?</p>
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
        const memberTable = document.getElementById('member_table');
        const quotaHistoryTable = document.getElementById('quota_history_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addMemberBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('memberModalInput');
        const inName = document.getElementById('insert_name');
        const inPhone = document.getElementById('insert_phone');
        const inJoinDate = document.getElementById('insert_join_date');
        const inTotalQuota = document.getElementById('insert_total_quota');
        const inStartDate = document.getElementById('insert_start_date');
        const inEndDate = document.getElementById('insert_end_date');

        // MODAL EDIT
        const memberModalEdit = document.getElementById('memberModalEdit');
        const editMemberId = document.getElementById('edit_member_id');
        const editName = document.getElementById('edit_name');
        const editPhone = document.getElementById('edit_phone');
        const editIsActive = document.getElementById('edit_is_active');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');

        // MODAL QUOTA
        const quotaModalInput = document.getElementById('quotaModalInput');
        const quotaMemberId = document.getElementById('quota_member_id');
        const inAdditionalQuota = document.getElementById('insert_additional_quota');
        const inQuotaStartDate = document.getElementById('insert_quota_start_date');
        const inQuotaEndDate = document.getElementById('insert_quota_end_date');
        const buttonInsertQuota = document.getElementById('button_insert_quota');
        const buttonInsertQuotaSend = document.getElementById('button_insert_quota_send');

        // MODAL QUOTA HISTORY
        const quotaHistoryModal = document.getElementById('quotaHistoryModal');

        // MODAL DELETE
        const memberModalDelete = document.getElementById('memberModalDelete');
        const deleteMemberId = document.getElementById('delete_member_id');
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
                let url = `{{ route('senam.master.members.data') }}`;
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


                memberTable.innerHTML = '';
                memberTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th class="text-center pe-4" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    memberTable.innerHTML += `
                        <tr>
                            <td colspan="7" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((member, index) => {
                        // Format status
                        console.log(member);

                        const statusBadge = member.is_active ?
                            '<span class="badge bg-success">Aktif</span>' :
                            '<span class="badge bg-danger">Nonaktif</span>';

                        memberTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>
                                    <div class="fw-medium">${member.name}</div>
                                    <div class="small text-muted">Bergabung: ${new Date(member.join_date).toLocaleDateString('id-ID')}</div>
                                </td>
                                <td>${member.phone}</td>
                                <td>${member.remaining_quota}</td>
                                <td>${statusBadge}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-primary btn_edit" 
                                            data-id="${member.id}" 
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-success btn_add_quota" 
                                            data-id="${member.id}" 
                                            title="Tambah Kuota">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-info btn_quota_history" 
                                            data-id="${member.id}" 
                                            title="History Kuota">
                                            <i class="fas fa-history"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-danger btn_delete" 
                                            data-id="${member.id}" 
                                            data-active="${member.is_active}"
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
                createDynamicAlert('danger', 'Gagal memuat data member');
            }
        }

        async function fetchQuotaHistory(memberId) {
            try {
                const response = await axios.get(`{{ route('senam.master.members.quota-history') }}`, {
                    params: {
                        member_id: memberId
                    }
                });

                const data = response.data.data;

                quotaHistoryTable.innerHTML = '';
                quotaHistoryTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Total Kuota</th>
                            <th>Sisa Kuota</th>
                            <th>Periode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    quotaHistoryTable.innerHTML += `
                        <tr>
                            <td colspan="5" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((quota, index) => {
                        // Format status
                        const statusBadge = quota.is_active ?
                            '<span class="badge bg-success">Aktif</span>' :
                            '<span class="badge bg-secondary">Tidak Aktif</span>';

                        // Format dates
                        const startDate = new Date(quota.start_date).toLocaleDateString('id-ID');
                        const endDate = new Date(quota.end_date).toLocaleDateString('id-ID');

                        quotaHistoryTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${quota.total_quota}</td>
                                <td>${quota.remaining_quota}</td>
                                <td>${startDate} - ${endDate}</td>
                                <td>${statusBadge}</td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching quota history:', error);
                createDynamicAlert('danger', 'Gagal memuat history kuota');
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
            inPhone.value = '';
            inJoinDate.value = '';
            inTotalQuota.value = '4';
            inStartDate.value = '';
            inEndDate.value = '';

            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            inJoinDate.value = today;
            inStartDate.value = today;

            // Set end date to 1 month from today
            const endDate = new Date();
            endDate.setMonth(endDate.getMonth() + 1);
            inEndDate.value = endDate.toISOString().split('T')[0];

            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = {
                    name: inName.value,
                    phone: inPhone.value,
                    join_date: inJoinDate.value,
                    total_quota: inTotalQuota.value,
                    start_date: inStartDate.value,
                    end_date: inEndDate.value
                };

                const response = await axios.post(`{{ route('senam.master.members.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Member berhasil ditambahkan');

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
                            'Terjadi kesalahan saat menambahkan member');
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
                    editMemberId.value = '';
                    editName.value = '';
                    editPhone.value = '';
                    editIsActive.value = '1';

                    const response = await axios.get(`{{ route('senam.master.members.data') }}`, {
                        params: {
                            id: id
                        }
                    });

                    // Ambil data member dari response (selalu ambil dari data.member)
                    let member = null;
                    if (response.data && response.data.data && response.data.data.member) {
                        member = response.data.data.member;
                    }

                    if (!member || typeof member !== 'object') {
                        createDynamicAlert('danger', 'Data member tidak ditemukan');
                        return;
                    }

                    // Fill the form
                    editMemberId.value = member.id !== undefined && member.id !== null ? member.id : '';
                    editName.value = member.name !== undefined && member.name !== null ? member.name : '';
                    editPhone.value = member.phone !== undefined && member.phone !== null ? member.phone : '';
                    editIsActive.value = member.is_active == 1 ? '1' : '0';

                    new bootstrap.Modal(memberModalEdit).show();
                } catch (error) {
                    console.error('Error:', error);
                    createDynamicAlert('danger', 'Gagal memuat data member');
                }
            }
        });

        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: editMemberId.value,
                    name: editName.value,
                    phone: editPhone.value,
                    is_active: editIsActive.value
                };

                const response = await axios.post(`{{ route('senam.master.members.update') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Member berhasil diperbarui');

                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, membershipType, limit);

                    bootstrap.Modal.getInstance(memberModalEdit).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal memperbarui member');
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

    {{-- QUOTA MANAGEMENT --}}
    <script>
        document.addEventListener('click', async function(event) {
            const addQuotaBtn = event.target.closest('.btn_add_quota');
            if (addQuotaBtn) {
                event.preventDefault();
                const id = addQuotaBtn.dataset.id;

                quotaMemberId.value = id;

                // Set default dates
                const today = new Date().toISOString().split('T')[0];
                inQuotaStartDate.value = today;

                // Set end date to 1 month from today
                const endDate = new Date();
                endDate.setMonth(endDate.getMonth() + 1);
                inQuotaEndDate.value = endDate.toISOString().split('T')[0];

                new bootstrap.Modal(quotaModalInput).show();
            }

            const quotaHistoryBtn = event.target.closest('.btn_quota_history');
            if (quotaHistoryBtn) {
                event.preventDefault();
                const id = quotaHistoryBtn.dataset.id;

                await fetchQuotaHistory(id);
                new bootstrap.Modal(quotaHistoryModal).show();
            }
        });

        buttonInsertQuota.addEventListener('click', async function() {
            buttonInsertQuota.style.display = 'none';
            buttonInsertQuotaSend.style.display = 'inline-block';

            try {
                const formData = {
                    member_id: quotaMemberId.value,
                    additional_quota: inAdditionalQuota.value,
                    start_date: inQuotaStartDate.value,
                    end_date: inQuotaEndDate.value
                };

                const response = await axios.post(`{{ route('senam.master.members.add-quota') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Kuota berhasil ditambahkan');

                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, membershipType, limit);

                    bootstrap.Modal.getInstance(quotaModalInput).hide();
                    inAdditionalQuota.value = '';
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal menambahkan kuota');
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
                buttonInsertQuota.style.display = 'inline-block';
                buttonInsertQuotaSend.style.display = 'none';
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

                deleteMemberId.value = id;

                if (isActive) {
                    deleteAction.value = 'deactivate';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menonaktifkan member ini?';
                    passwordContainer.style.display = 'none';
                } else {
                    deleteAction.value = 'delete';
                    deleteMessage.textContent = 'Apakah Anda yakin ingin menghapus permanen member ini?';
                    passwordContainer.style.display = 'block';
                }

                new bootstrap.Modal(memberModalDelete).show();
            }
        });

        buttonDelete.addEventListener('click', async function() {
            buttonDelete.style.display = 'none';
            buttonDeleteSend.style.display = 'inline-block';

            try {
                const formData = {
                    id: deleteMemberId.value,
                    action: deleteAction.value,
                    password: deletePassword.value
                };

                const response = await axios.post(`{{ route('senam.master.members.destroy') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);

                    const search = filterSearch.value;
                    const status = filterStatus.value;
                    const limit = syLimit.value;
                    await fetchData(search, status, membershipType, limit);

                    bootstrap.Modal.getInstance(memberModalDelete).hide();
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
