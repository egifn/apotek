@extends('layouts.senam.admin')
@section('page-title', 'Laporan')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">Laporan Manajemen Senam</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="participation-tab" data-bs-toggle="tab" 
                                data-bs-target="#participation" type="button" role="tab">
                                Partisipasi Kelas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="quota-tab" data-bs-toggle="tab" 
                                data-bs-target="#quota" type="button" role="tab">
                                Penggunaan Kuota
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="instructor-tab" data-bs-toggle="tab" 
                                data-bs-target="#instructor" type="button" role="tab">
                                Sesi Instruktur
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content p-3 border border-top-0" id="reportTabsContent">
                        <!-- Partisipasi Kelas Tab -->
                        <div class="tab-pane fade show active" id="participation" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="participation_start_date" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="participation_start_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="participation_end_date" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="participation_end_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="participation_class_type" class="form-label">Jenis Kelas</label>
                                    <select class="form-control" id="participation_class_type">
                                        <option value="">Semua Jenis</option>
                                        @foreach($classTypes as $classType)
                                            <option value="{{ $classType->id }}">{{ $classType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="participation_limit" class="form-label">Tampilkan</label>
                                    <select class="form-control" id="participation_limit">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 text-end mb-3">
                                    <button class="btn btn-success btn-sm" id="export_participation_btn">
                                        <i class="fas fa-file-excel me-1"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="participation_table">
                                    <!-- Data will be loaded via JavaScript -->
                                </table>
                            </div>
                        </div>
                        
                        <!-- Penggunaan Kuota Tab -->
                        <div class="tab-pane fade" id="quota" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="quota_start_date" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="quota_start_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="quota_end_date" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="quota_end_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="quota_membership_type" class="form-label">Tipe Member</label>
                                    <select class="form-control" id="quota_membership_type">
                                        <option value="">Semua Tipe</option>
                                        <option value="regular">Regular</option>
                                        <option value="premium">Premium</option>
                                        <option value="vip">VIP</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="quota_limit" class="form-label">Tampilkan</label>
                                    <select class="form-control" id="quota_limit">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 text-end mb-3">
                                    <button class="btn btn-success btn-sm" id="export_quota_btn">
                                        <i class="fas fa-file-excel me-1"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="quota_table">
                                    <!-- Data will be loaded via JavaScript -->
                                </table>
                            </div>
                        </div>
                        
                        <!-- Sesi Instruktur Tab -->
                        <div class="tab-pane fade" id="instructor" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="instructor_start_date" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="instructor_start_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="instructor_end_date" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="instructor_end_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="instructor_id" class="form-label">Instruktur</label>
                                    <select class="form-control" id="instructor_id">
                                        <option value="">Semua Instruktur</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="instructor_limit" class="form-label">Tampilkan</label>
                                    <select class="form-control" id="instructor_limit">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 text-end mb-3">
                                    <button class="btn btn-success btn-sm" id="export_instructor_btn">
                                        <i class="fas fa-file-excel me-1"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="instructor_table">
                                    <!-- Data will be loaded via JavaScript -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Partisipasi Kelas
        const participationStartDate = document.getElementById('participation_start_date');
        const participationEndDate = document.getElementById('participation_end_date');
        const participationClassType = document.getElementById('participation_class_type');
        const participationLimit = document.getElementById('participation_limit');
        const participationTable = document.getElementById('participation_table');
        const exportParticipationBtn = document.getElementById('export_participation_btn');

        // Penggunaan Kuota
        const quotaStartDate = document.getElementById('quota_start_date');
        const quotaEndDate = document.getElementById('quota_end_date');
        const quotaMembershipType = document.getElementById('quota_membership_type');
        const quotaLimit = document.getElementById('quota_limit');
        const quotaTable = document.getElementById('quota_table');
        const exportQuotaBtn = document.getElementById('export_quota_btn');

        // Sesi Instruktur
        const instructorStartDate = document.getElementById('instructor_start_date');
        const instructorEndDate = document.getElementById('instructor_end_date');
        const instructorId = document.getElementById('instructor_id');
        const instructorLimit = document.getElementById('instructor_limit');
        const instructorTable = document.getElementById('instructor_table');
        const exportInstructorBtn = document.getElementById('export_instructor_btn');

        // Fungsi untuk mengambil data partisipasi kelas
        async function fetchParticipationData(startDate, endDate, classTypeId, limit) {
            try {
                let url = `{{ route('senam.master.reports.class-participation') }}`;
                let params = new URLSearchParams();

                if (startDate && endDate) {
                    params.append('start_date', startDate);
                    params.append('end_date', endDate);
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
                const data = response.data.data;

                participationTable.innerHTML = '';
                participationTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Kelas</th>
                            <th>Tanggal & Waktu</th>
                            <th>Peserta</th>
                            <th>Kapasitas</th>
                            <th>Tingkat Partisipasi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    participationTable.innerHTML += `
                        <tr>
                            <td colspan="6" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((classItem, index) => {
                        const startDate = new Date(classItem.start_datetime);
                        const formattedDate = startDate.toLocaleDateString('id-ID', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        participationTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${classItem.class_name}</td>
                                <td>${formattedDate}</td>
                                <td>${classItem.total_participants}</td>
                                <td>${classItem.max_participants}</td>
                                <td>${classItem.participation_rate}</td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching participation data:', error);
                createDynamicAlert('danger', 'Gagal memuat data partisipasi kelas');
            }
        }

        // Fungsi untuk mengambil data penggunaan kuota
        async function fetchQuotaData(startDate, endDate, membershipType, limit) {
            try {
                let url = `{{ route('senam.master.reports.quota-usage') }}`;
                let params = new URLSearchParams();

                if (startDate && endDate) {
                    params.append('start_date', startDate);
                    params.append('end_date', endDate);
                }
                if (membershipType && membershipType !== '') {
                    params.append('membership_type', membershipType);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                quotaTable.innerHTML = '';
                quotaTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Member</th>
                            <th>Tipe Member</th>
                            <th>Total Kuota</th>
                            <th>Kuota Terpakai</th>
                            <th>Sisa Kuota</th>
                            <th>Tingkat Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    quotaTable.innerHTML += `
                        <tr>
                            <td colspan="7" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((quota, index) => {
                        quotaTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${quota.name}</td>
                                <td>${quota.membership_type}</td>
                                <td>${quota.total_quota}</td>
                                <td>${quota.used_quota}</td>
                                <td>${quota.remaining_quota}</td>
                                <td>${quota.usage_rate}</td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching quota data:', error);
                createDynamicAlert('danger', 'Gagal memuat data penggunaan kuota');
            }
        }

        // Fungsi untuk mengambil data sesi instruktur
        async function fetchInstructorData(startDate, endDate, instructorId, limit) {
            try {
                let url = `{{ route('senam.master.reports.instructor-sessions') }}`;
                let params = new URLSearchParams();

                if (startDate && endDate) {
                    params.append('start_date', startDate);
                    params.append('end_date', endDate);
                }
                if (instructorId && instructorId !== '') {
                    params.append('instructor_id', instructorId);
                }
                if (limit && limit !== '') {
                    params.append('limit', limit);
                }

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await axios.get(url);
                const data = response.data.data;

                instructorTable.innerHTML = '';
                instructorTable.innerHTML += `
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama Instruktur</th>
                            <th>Total Sesi</th>
                            <th>Total Peserta</th>
                            <th>Rata-rata Peserta/Sesi</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                if (!data || data.data.length === 0) {
                    instructorTable.innerHTML += `
                        <tr>
                            <td colspan="5" class="ps-4 text-center">Tidak ada Data</td>
                        </tr>
                    `;
                } else {
                    data.data.forEach((instructor, index) => {
                        instructorTable.innerHTML += `
                            <tr>
                                <td class="ps-4">${data.from + index}</td>
                                <td>${instructor.name}</td>
                                <td>${instructor.total_sessions}</td>
                                <td>${instructor.total_participants}</td>
                                <td>${instructor.avg_participants}</td>
                            </tr>
                        `;
                    });
                }
            } catch (error) {
                console.error('Error fetching instructor data:', error);
                createDynamicAlert('danger', 'Gagal memuat data sesi instruktur');
            }
        }

        // Inisialisasi data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            // Set default dates (current month)
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
            
            // Partisipasi Kelas
            participationStartDate.value = firstDay;
            participationEndDate.value = lastDay;
            fetchParticipationData(
                participationStartDate.value,
                participationEndDate.value,
                participationClassType.value,
                participationLimit.value
            );
            
            // Penggunaan Kuota
            quotaStartDate.value = firstDay;
            quotaEndDate.value = lastDay;
            fetchQuotaData(
                quotaStartDate.value,
                quotaEndDate.value,
                quotaMembershipType.value,
                quotaLimit.value
            );
            
            // Sesi Instruktur
            instructorStartDate.value = firstDay;
            instructorEndDate.value = lastDay;
            fetchInstructorData(
                instructorStartDate.value,
                instructorEndDate.value,
                instructorId.value,
                instructorLimit.value
            );
        });

        // Event listeners untuk Partisipasi Kelas
        participationStartDate.addEventListener('change', (event) => {
            fetchParticipationData(
                event.target.value,
                participationEndDate.value,
                participationClassType.value,
                participationLimit.value
            );
        });

        participationEndDate.addEventListener('change', (event) => {
            fetchParticipationData(
                participationStartDate.value,
                event.target.value,
                participationClassType.value,
                participationLimit.value
            );
        });

        participationClassType.addEventListener('change', (event) => {
            fetchParticipationData(
                participationStartDate.value,
                participationEndDate.value,
                event.target.value,
                participationLimit.value
            );
        });

        participationLimit.addEventListener('change', (event) => {
            fetchParticipationData(
                participationStartDate.value,
                participationEndDate.value,
                participationClassType.value,
                event.target.value
            );
        });

        // Event listeners untuk Penggunaan Kuota
        quotaStartDate.addEventListener('change', (event) => {
            fetchQuotaData(
                event.target.value,
                quotaEndDate.value,
                quotaMembershipType.value,
                quotaLimit.value
            );
        });

        quotaEndDate.addEventListener('change', (event) => {
            fetchQuotaData(
                quotaStartDate.value,
                event.target.value,
                quotaMembershipType.value,
                quotaLimit.value
            );
        });

        quotaMembershipType.addEventListener('change', (event) => {
            fetchQuotaData(
                quotaStartDate.value,
                quotaEndDate.value,
                event.target.value,
                quotaLimit.value
            );
        });

        quotaLimit.addEventListener('change', (event) => {
            fetchQuotaData(
                quotaStartDate.value,
                quotaEndDate.value,
                quotaMembershipType.value,
                event.target.value
            );
        });

        // Event listeners untuk Sesi Instruktur
        instructorStartDate.addEventListener('change', (event) => {
            fetchInstructorData(
                event.target.value,
                instructorEndDate.value,
                instructorId.value,
                instructorLimit.value
            );
        });

        instructorEndDate.addEventListener('change', (event) => {
            fetchInstructorData(
                instructorStartDate.value,
                event.target.value,
                instructorId.value,
                instructorLimit.value
            );
        });

        instructorId.addEventListener('change', (event) => {
            fetchInstructorData(
                instructorStartDate.value,
                instructorEndDate.value,
                event.target.value,
                instructorLimit.value
            );
        });

        instructorLimit.addEventListener('change', (event) => {
            fetchInstructorData(
                instructorStartDate.value,
                instructorEndDate.value,
                instructorId.value,
                event.target.value
            );
        });

        // Export buttons
        exportParticipationBtn.addEventListener('click', function() {
            const startDate = participationStartDate.value;
            const endDate = participationEndDate.value;
            const classTypeId = participationClassType.value;
            
            window.location.href = `{{ route('senam.master.reports.export-participation') }}?start_date=${startDate}&end_date=${endDate}&class_type_id=${classTypeId}`;
        });

        exportQuotaBtn.addEventListener('click', function() {
            const startDate = quotaStartDate.value;
            const endDate = quotaEndDate.value;
            const membershipType = quotaMembershipType.value;
            
            window.location.href = `{{ route('senam.master.reports.export-quota') }}?start_date=${startDate}&end_date=${endDate}&membership_type=${membershipType}`;
        });

        exportInstructorBtn.addEventListener('click', function() {
            const startDate = instructorStartDate.value;
            const endDate = instructorEndDate.value;
            const instructorIdValue = instructorId.value;
            
            window.location.href = `{{ route('senam.master.reports.export-instructor') }}?start_date=${startDate}&end_date=${endDate}&instructor_id=${instructorIdValue}`;
        });
    </script>
@endpush