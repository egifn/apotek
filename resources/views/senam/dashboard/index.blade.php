@extends('layouts.senam.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('style')
    <style>
        .card-stat {
            transition: all 0.3s ease;
        }

        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2rem;
            opacity: 0.7;
        }

        .table-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eaeaea;
        }

        .table-card-header .header-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Dashboard Senam</h4>
            <p class="text-muted">Ringkasan aktivitas dan statistik kelas senam</p>
        </div>
    </div>

    <div class="row">
        <!-- Kelas Aktif -->
        <div class="col-md-3 mb-4">
            <div class="card card-stat border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jenis Kelas Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_classes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumbbell stat-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instruktur Aktif -->
        <div class="col-md-3 mb-4">
            <div class="card card-stat border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Instruktur Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_instructors'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie stat-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelas Mendatang -->
        <div class="col-md-3 mb-4">
            <div class="card card-stat border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kelas Mendatang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming_classes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt stat-icon text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelas Selesai Bulan Ini -->
        <div class="col-md-3 mb-4">
            <div class="card card-stat border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Kelas Selesai (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_classes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle stat-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
