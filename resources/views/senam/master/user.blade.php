@extends('layouts.senam.admin')
@section('page-title', 'User')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen User</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addNonMemberBtn">
                <i class="fas fa-plus me-1"></i> Tambah User
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
                        <table id="non_member_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- Modal untuk Tambah User -->
    <div class="modal fade" id="nonMemberModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="nonMemberModalLabel">Tambah User</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="nonMemberFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pegawai_select" class="form-label">Pilih Pegawai</label>
                            <div class="input-group">
                                <select class="form-control" id="pegawai_select" name="id_pegawai" required>
                                    <option value="">Pilih Pegawai</option>
                                    <!-- Options akan di-load via JavaScript -->
                                </select>
                                <button type="button" class="btn btn-secondary" id="refreshPegawai">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <input type="hidden" id="selected_nama" name="nama">
                            <input type="hidden" id="selected_email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="insert_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="insert_email" name="insert_email" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="inputAddress5" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputPassword5" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="insert_phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="insert_phone" name="insert_phone" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" value="Senam"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label for="level" class="form-label">Level User</label>
                            <select class="form-control" id="level" name="level" required>
                                <option value="">Pilih Level</option>
                                @foreach ($data_level as $row)
                                    <option value="{{ $row->id }}" {{ old('id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->nama }}
                                    </option>
                                @endforeach
                            </select>
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

    <!-- Modal untuk Hapus -->
    <div class="modal fade" id="nonMemberModalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Konfirmasi</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="nonMemberFormDelete">
                    @csrf
                    <input type="hidden" id="delete_non_member_id">
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus User ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_delete" class="btn btn-danger btn-sm">Hapus</button>
                        <button type="button" id="button_delete_send" class="btn btn-danger"
                            style="display: none;">Menghapus...</button>
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

        // Utility functions
        function showValidationErrors(errors) {
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            // Add new errors
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = errors[field][0];
                    input.parentNode.appendChild(errorDiv);
                }
            });
        }

        function createDynamicAlert(type, message) {
            // Cari container yang valid untuk alert
            const container = document.querySelector('.container-fluid') || document.querySelector('.row') || document.body;
            if (!container) return;

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            container.prepend(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>

    {{-- SET VARIABLE --}}
    <script>
        // Filter variables
        const filterSearch = document.getElementById('filter_search');
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const nonMemberTable = document.getElementById('non_member_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addNonMemberBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('nonMemberModalInput');
        const pegawaiSelect = document.getElementById('pegawai_select');
        const refreshPegawaiBtn = document.getElementById('refreshPegawai');
        const insertEmail = document.getElementById('insert_email');
        const insertPhone = document.getElementById('insert_phone');
        const selectedNama = document.getElementById('selected_nama');
        const selectedEmail = document.getElementById('selected_email');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const levelSelect = document.getElementById('level');

        // MODAL DELETE
        const nonMemberModalDelete = document.getElementById('nonMemberModalDelete');
        const deleteNonMemberId = document.getElementById('delete_non_member_id');
        const buttonDelete = document.getElementById('button_delete');
        const buttonDeleteSend = document.getElementById('button_delete_send');

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    </script>

    {{-- GET DATA USER --}}
    <script>
        async function fetchData(search, limit) {
            try {
                let url = `{{ route('senam.master.user.data') }}`;
                let params = new URLSearchParams();

                if (search && search !== '') {
                    params.append('search', search);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                nonMemberTable.innerHTML = '';
                nonMemberTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Lokasi</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || !data.data || data.data.length === 0) {
                    nonMemberTable.innerHTML += `
                        <tr>
                            <td colspan="8" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((user, index) => {
                        const statusBadge = user.status_user === 'Aktif' ?
                            '<span class="badge bg-success">Aktif</span>' :
                            '<span class="badge bg-danger">' + (user.status_user || 'Non-Aktif') + '</span>';

                        nonMemberTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${user.name || '-'}</td>
                                <td>${user.email || '-'}</td>
                                <td>${user.username || '-'}</td>
                                <td>${user.kd_lokasi || '-'}</td>
                                <td>${user.jabatan || '-'}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button type="button" data-id="${user.id}" class="btn btn-danger btn-sm btn_delete">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                createDynamicAlert('danger', 'Gagal memuat data User');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const search = filterSearch?.value || '';
            const limit = syLimit?.value || '10';
            fetchData(search, limit);
            fetchPegawaiData(); // Load data pegawai saat halaman dimuat
        });

        // Filter by search
        if (filterSearch) {
            filterSearch.addEventListener('input', debounce((event) => {
                const search = event.target.value;
                const limit = syLimit?.value || '10';
                fetchData(search, limit);
            }, 500));
        }

        // Filter by limit
        if (syLimit) {
            syLimit.addEventListener('change', (event) => {
                const search = filterSearch?.value || '';
                const limit = event.target.value;
                fetchData(search, limit);
            });
        }
    </script>

    {{-- GET DATA PEGAWAI --}}
    <script>
        async function fetchPegawaiData(search = '') {
            try {
                let url = `{{ route('senam.master.pegawai.data') }}`;
                let params = new URLSearchParams();

                if (search && search !== '') {
                    params.append('search', search);
                }
                params.append('limit', 100); // Limit besar untuk dropdown

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data;

                console.log('Pegawai response:', data); // Debug log

                // Clear existing options except the first one
                if (pegawaiSelect) {
                    while (pegawaiSelect.options.length > 1) {
                        pegawaiSelect.remove(1);
                    }

                    // Check if data exists and has the correct structure
                    if (data && data.status === true && data.data && data.data.data && data.data.data.length > 0) {
                        data.data.data.forEach(pegawai => {
                            const option = document.createElement('option');
                            option.value = pegawai.kode_pegawai || '';
                            option.textContent =
                                `${pegawai.nama_pegawai || ''} - ${pegawai.kode_pegawai || ''}`;
                            option.setAttribute('data-nama', pegawai.nama_pegawai || '');
                            option.setAttribute('data-email', pegawai.email || '');
                            option.setAttribute('data-telepon', pegawai.tlp || '');
                            pegawaiSelect.appendChild(option);
                        });
                    } else {
                        // Jika tidak ada data
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Tidak ada data pegawai';
                        option.disabled = true;
                        pegawaiSelect.appendChild(option);
                    }
                }
            } catch (error) {
                console.error('Error fetching pegawai data:', error);
                createDynamicAlert('danger', 'Gagal memuat data pegawai');
            }
        }

        // Event listener untuk perubahan select pegawai
        if (pegawaiSelect) {
            pegawaiSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                if (selectedOption.value !== '') {
                    const nama = selectedOption.getAttribute('data-nama') || '';
                    const email = selectedOption.getAttribute('data-email') || '';
                    const telepon = selectedOption.getAttribute('data-telepon') || '';

                    // Set values to hidden inputs and readonly fields
                    if (selectedNama) selectedNama.value = nama;
                    if (selectedEmail) selectedEmail.value = email;
                    if (insertEmail) insertEmail.value = email;
                    if (insertPhone) insertPhone.value = telepon;

                    // Generate username from nama (optional)
                    if (nama && usernameInput && !usernameInput.value) {
                        const username = nama.toLowerCase().replace(/\s+/g, '.');
                        usernameInput.value = username;
                    }
                } else {
                    // Reset values if no selection
                    if (selectedNama) selectedNama.value = '';
                    if (selectedEmail) selectedEmail.value = '';
                    if (insertEmail) insertEmail.value = '';
                    if (insertPhone) insertPhone.value = '';
                }
            });
        }

        // Refresh data pegawai
        if (refreshPegawaiBtn) {
            refreshPegawaiBtn.addEventListener('click', function() {
                fetchPegawaiData();
                // createDynamicAlert('info', 'Memuat ulang data pegawai...');
            });
        }
    </script>

    {{-- INSERT DATA USER --}}
    <script>
        if (buttonShowModalFormInput) {
            buttonShowModalFormInput.addEventListener('click', function() {
                // Reset form
                if (pegawaiSelect) pegawaiSelect.value = '';
                if (insertEmail) insertEmail.value = '';
                if (insertPhone) insertPhone.value = '';
                if (usernameInput) usernameInput.value = '';
                if (passwordInput) passwordInput.value = '';
                if (levelSelect) levelSelect.value = '';
                if (selectedNama) selectedNama.value = '';
                if (selectedEmail) selectedEmail.value = '';

                // Load data pegawai saat modal dibuka
                fetchPegawaiData();

                new bootstrap.Modal(componentModalFormInsert).show();
            });
        }

        if (buttonInsert) {
            buttonInsert.addEventListener('click', async function() {
                // Validasi form
                if (pegawaiSelect && !pegawaiSelect.value) {
                    createDynamicAlert('warning', 'Pilih pegawai terlebih dahulu');
                    return;
                }

                if ((usernameInput && !usernameInput.value) || (passwordInput && !passwordInput.value) || (
                        levelSelect && !levelSelect.value)) {
                    createDynamicAlert('warning', 'Harap lengkapi semua field yang required');
                    return;
                }

                buttonInsert.style.display = 'none';
                if (buttonInsertSend) buttonInsertSend.style.display = 'inline-block';

                try {
                    const formData = {
                        id_pegawai: pegawaiSelect?.value || '',
                        name: selectedNama?.value || '',
                        email: insertEmail?.value || '',
                        phone: insertPhone?.value || '',
                        username: usernameInput?.value || '',
                        password: passwordInput?.value || '',
                        lokasi: 'Senam',
                        level: levelSelect?.value || ''
                    };

                    const response = await axios.post(`{{ route('senam.master.user.store') }}`, formData);
                    const data = response.data;

                    if (data.status === true) {
                        createDynamicAlert('success', data.message || 'User berhasil ditambahkan');

                        // Refresh data
                        const search = filterSearch?.value || '';
                        const limit = syLimit?.value || '10';
                        await fetchData(search, limit);

                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(componentModalFormInsert);
                        if (modal) modal.hide();

                    } else {
                        if (data.type === 'validation') {
                            showValidationErrors(data.errors);
                        } else {
                            createDynamicAlert('danger', data.message ||
                                'Terjadi kesalahan saat menambahkan User');
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
                    if (buttonInsertSend) buttonInsertSend.style.display = 'none';
                }
            });
        }
    </script>

    {{-- DELETE DATA --}}
    <script>
        document.addEventListener('click', async function(event) {
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                event.preventDefault();
                const id = deleteBtn.dataset.id;

                if (deleteNonMemberId) deleteNonMemberId.value = id;
                new bootstrap.Modal(nonMemberModalDelete).show();
            }
        });

        if (buttonDelete) {
            buttonDelete.addEventListener('click', async function() {
                buttonDelete.style.display = 'none';
                if (buttonDeleteSend) buttonDeleteSend.style.display = 'inline-block';

                try {
                    const response = await axios.post(`{{ route('senam.master.user.destroy') }}`, {
                        id: deleteNonMemberId?.value || ''
                    });
                    const data = response.data;

                    if (data.status === true) {
                        createDynamicAlert('success', data.message);

                        const search = filterSearch?.value || '';
                        const limit = syLimit?.value || '10';
                        await fetchData(search, limit);

                        const modal = bootstrap.Modal.getInstance(nonMemberModalDelete);
                        if (modal) modal.hide();
                    } else {
                        createDynamicAlert('danger', data.message || 'Gagal menghapus User');
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
                    if (buttonDeleteSend) buttonDeleteSend.style.display = 'none';
                }
            });
        }
    </script>
@endpush
