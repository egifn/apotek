@extends('layouts.senam.admin')
@section('page-title', 'Non-Member')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Manajemen Non-Member</h4>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary btn-sm" id="addNonMemberBtn">
                <i class="fas fa-plus me-1"></i> Tambah Non-Member
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
    <!-- Modal untuk Tambah Non-Member -->
    <div class="modal fade" id="nonMemberModalInput">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="nonMemberModalLabel">Tambah Non-Member</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="nonMemberFormInput" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="insert_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="insert_name" name="insert_name" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="insert_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="insert_email" name="insert_email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="insert_phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="insert_phone" name="insert_phone">
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
                        <p>Apakah Anda yakin ingin menghapus non-member ini?</p>
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
        const syLimit = document.getElementById('short_by_limit');

        // Table element
        const nonMemberTable = document.getElementById('non_member_table');

        // MODAL INSERT
        const buttonShowModalFormInput = document.getElementById('addNonMemberBtn');
        const buttonInsert = document.getElementById('button_insert');
        const buttonInsertSend = document.getElementById('button_insert_send');
        const componentModalFormInsert = document.getElementById('nonMemberModalInput');
        const inName = document.getElementById('insert_name');
        const inEmail = document.getElementById('insert_email');
        const inPhone = document.getElementById('insert_phone');

        // MODAL DELETE
        const nonMemberModalDelete = document.getElementById('nonMemberModalDelete');
        const deleteNonMemberId = document.getElementById('delete_non_member_id');
        const buttonDelete = document.getElementById('button_delete');
        const buttonDeleteSend = document.getElementById('button_delete_send');
    </script>

    {{-- GET DATA --}}
    <script>
        async function fetchData(search, limit) {
            try {
                let url = `{{ route('senam.master.non-members.data') }}`;
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
                            <th>Kontak</th>
                            <th>Tanggal Daftar</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    nonMemberTable.innerHTML += `
                        <tr>
                            <td colspan="5" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((nonMember, index) => {
                        // Format contact
                        const contact = `
                            <div>${nonMember.phone || '-'}</div>
                            <div class="small text-muted">${nonMember.email || '-'}</div>
                        `;

                        // Format date
                        const createdAt = new Date(nonMember.created_at);
                        const formattedDate = createdAt.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });

                        nonMemberTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${nonMember.name}</td>
                                <td>${contact}</td>
                                <td>${formattedDate}</td>
                                
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                createDynamicAlert('danger', 'Gagal memuat data non-member');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const search = filterSearch.value;
            const limit = syLimit.value;
            fetchData(search, limit);
        });

        // Filter by search
        filterSearch.addEventListener('input', debounce((event) => {
            const search = event.target.value;
            const limit = syLimit.value;
            fetchData(search, limit);
        }, 500));

        // Filter by limit
        syLimit.addEventListener('change', (event) => {
            const search = filterSearch.value;
            const limit = event.target.value;
            fetchData(search, limit);
        });
    </script>

    {{-- INSERT DATA --}}
    <script>
        buttonShowModalFormInput.addEventListener('click', function() {
            // Reset form
            inName.value = '';
            inEmail.value = '';
            inPhone.value = '';
            
            new bootstrap.Modal(componentModalFormInsert).show();
        });

        buttonInsert.addEventListener('click', async function() {
            buttonInsert.style.display = 'none';
            buttonInsertSend.style.display = 'inline-block';

            try {
                const formData = {
                    name: inName.value,
                    email: inEmail.value,
                    phone: inPhone.value
                };

                const response = await axios.post(`{{ route('senam.master.non-members.store') }}`, formData);
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message || 'Non-member berhasil ditambahkan');

                    // Refresh data
                    const search = filterSearch.value;
                    const limit = syLimit.value;
                    await fetchData(search, limit);

                    // Close the modal
                    bootstrap.Modal.getInstance(componentModalFormInsert).hide();

                } else {
                    if (data.type === 'validation') {
                        showValidationErrors(data.errors);
                    } else {
                        createDynamicAlert('danger', data.message || 'Terjadi kesalahan saat menambahkan non-member');
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

    {{-- DELETE DATA --}}
    <script>
        document.addEventListener('click', async function(event) {
            const deleteBtn = event.target.closest('.btn_delete');
            if (deleteBtn) {
                event.preventDefault();
                const id = deleteBtn.dataset.id;
                
                deleteNonMemberId.value = id;
                new bootstrap.Modal(nonMemberModalDelete).show();
            }
        });

        buttonDelete.addEventListener('click', async function() {
            buttonDelete.style.display = 'none';
            buttonDeleteSend.style.display = 'inline-block';

            try {
                const response = await axios.post(`{{ route('senam.master.non-members.destroy') }}`, {
                    id: deleteNonMemberId.value
                });
                const data = response.data;

                if (data.status === true) {
                    createDynamicAlert('success', data.message);
                    
                    const search = filterSearch.value;
                    const limit = syLimit.value;
                    await fetchData(search, limit);
                    
                    bootstrap.Modal.getInstance(nonMemberModalDelete).hide();
                } else {
                    createDynamicAlert('danger', data.message || 'Gagal menghapus non-member');
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