@extends('layouts.coffeshop.admin')
@section('page-title', 'Stok')
@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Stok</h4>
        </div>
        <div class="col-md-4 text-end">
            {{-- <button class="btn btn-primary btn-sm" id="btnTambahStok">
                <i class="fas fa-plus me-1"></i> Tambah Stok
            </button> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="col-lg-1">
                        <select class="form-control form-control-sm" id="short_by_limit" style="width: 50px;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                    <div class="col-4" style="display: flex; gap:5px;">
                        <select class="form-control form-control-sm" id="short_by_status" style="width: 150px;">
                            <option value="0" selected>All</option>
                            <option value="1">Cafe Tilu 1</option>
                            <option value="2">Cafe Tilu 2</option>
                            <option value="3">Cafe Tilu 3</option>
                        </select>
                        <input type="text" class="form-control form-control-sm" id="short_by_search"
                            placeholder="search..">
                    </div>
                </div>
                <div class="table-card-body">
                    <div class="table-container">
                        <table id="stock_table" class="table-types-table">
                            <!-- Table content loaded via JavaScript -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Edit Minimal Stok -->
    <div class="modal fade" id="unitModalEdit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="unitModalLabel">Edit Minimal Stok</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="unitFormEdit" method="POST">
                    @csrf
                    <input type="hidden" id="edit_unitId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control" id="edit_name" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="min_stock" class="form-label">Minimal Stok*</label>
                            <input type="number" class="form-control" id="min_stock" name="min_stock" required>
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

    <!-- Modal untuk Tambah Stok -->
    <div class="modal fade" id="tambahStokModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Tambah Stok</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="tambahStokForm" method="POST">
                    @csrf
                    <input type="hidden" id="stok_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="stok_name" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control" id="stok_name" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="tambah_stok" class="form-label">Jumlah Tambahan Stok*</label>
                            <input type="number" class="form-control" id="tambah_stok" name="tambah_stok" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="button_tambah_stok" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" id="button_tambah_stok_send" class="btn btn-primary"
                            style="display: none;">Menyimpan...</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const sySearch = document.getElementById('short_by_search');
        const syLimit = document.getElementById('short_by_limit');
        const syStatus = document.getElementById('short_by_status');
        const stockTable = document.getElementById('stock_table');

        // Modal Edit
        const unitModalFormEdit = document.getElementById('unitModalEdit');
        const buttonUpdate = document.getElementById('button_update');
        const buttonUpdateSend = document.getElementById('button_update_send');
        const edId = document.getElementById('edit_unitId');
        const edName = document.getElementById('edit_name');
        const edMinStock = document.getElementById('min_stock');

        // Modal Tambah Stok
        const btnTambahStok = document.getElementById('btnTambahStok');
        const tambahStokModal = document.getElementById('tambahStokModal');
        const stokId = document.getElementById('stok_id');
        const stokName = document.getElementById('stok_name');
        const tambahStokInput = document.getElementById('tambah_stok');
        const buttonTambahStok = document.getElementById('button_tambah_stok');
        const buttonTambahStokSend = document.getElementById('button_tambah_stok_send');

        async function fetchData(syVSearch = '', syVLimit = 10, syVStatus = 1) {
            try {
                let url = "{{ route('coffeshop.master.stocks.data') }}";
                let params = new URLSearchParams();
                if (syVSearch) params.append('search', syVSearch);
                if (syVLimit) params.append('limit', syVLimit);
                if (syVStatus) params.append('status', syVStatus);
                url += '?' + params.toString();

                const response = await axios.get(url);
                const data = response.data.data;

                stockTable.innerHTML = `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Bahan Baku</th>
                            <th>Stok Tersedia</th>
                            <th>Stok Minimum</th>
                            <th>Cabang</th>
                            <th class="text-center pe-4" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.length ? data.map((item, index) => `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td class="fw-medium">${item.name}</td>
                                <td>${item.stock_available}</td>
                                <td>${item.min_stock}</td>
                                <td>${item.branch_name}</td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-icon btn-sm btn-outline-secondary btn_edit_min_stock" data-id="${item.id}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-icon btn-sm btn-outline-success btn_add_stock" data-id="${item.id}" title="Tambah Stok">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `).join('') : `
                            <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                        `}
                    </tbody>
                `;
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Event listener tombol edit minimal stok
        document.addEventListener('click', async function(event) {
            const editBtn = event.target.closest('.btn_edit_min_stock');
            if (editBtn) {
                const id = editBtn.dataset.id;
                const url = "{{ route('coffeshop.master.stocks.data') }}?id=" + id;
                try {
                    const response = await axios.get(url);
                    const data = response.data.data[0];
                    edId.value = data.id;
                    edName.value = data.name;
                    edMinStock.value = data.min_stock;
                    new bootstrap.Modal(unitModalFormEdit).show();
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

            // Tombol tambah stok
            const addStockBtn = event.target.closest('.btn_add_stock');
            if (addStockBtn) {
                const id = addStockBtn.dataset.id;
                const url = "{{ route('coffeshop.master.stocks.data') }}?id=" + id;
                try {
                    const response = await axios.get(url);
                    const data = response.data.data[0];
                    stokId.value = data.id;
                    stokName.value = data.name;
                    tambahStokInput.value = '';
                    new bootstrap.Modal(tambahStokModal).show();
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        });

        // Submit edit minimal stok
        buttonUpdate.addEventListener('click', async function() {
            buttonUpdate.style.display = 'none';
            buttonUpdateSend.style.display = 'inline-block';
            try {
                const response = await axios.post("{{ route('coffeshop.master.stocks.update') }}", {
                    id: edId.value,
                    min_stock: edMinStock.value
                });
                const data = response.data;
                if (data.status) {
                    createDynamicAlert('success', data.message);
                    fetchData(sySearch.value, syLimit.value, syStatus.value);
                    bootstrap.Modal.getInstance(unitModalFormEdit).hide();
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                buttonUpdate.style.display = 'inline-block';
                buttonUpdateSend.style.display = 'none';
            }
        });

        // Submit tambah stok
        buttonTambahStok.addEventListener('click', async function() {
            buttonTambahStok.style.display = 'none';
            buttonTambahStokSend.style.display = 'inline-block';
            try {
                const response = await axios.post("{{ route('coffeshop.master.stocks.tambahStok') }}", {
                    id: stokId.value,
                    tambah_stok: tambahStokInput.value
                });
                const data = response.data;
                if (data.status) {
                    createDynamicAlert('success', data.message);
                    fetchData(sySearch.value, syLimit.value, syStatus.value);
                    bootstrap.Modal.getInstance(tambahStokModal).hide();
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                buttonTambahStok.style.display = 'inline-block';
                buttonTambahStokSend.style.display = 'none';
            }
        });

        // Event listener filter
        document.addEventListener('DOMContentLoaded', () => fetchData(sySearch.value, syLimit.value, syStatus.value));
        sySearch.addEventListener('keyup', () => fetchData(sySearch.value, syLimit.value, syStatus.value));
        syLimit.addEventListener('change', () => fetchData(sySearch.value, syLimit.value, syStatus.value));
        syStatus.addEventListener('change', () => fetchData(sySearch.value, syLimit.value, syStatus.value));
    </script>
@endpush