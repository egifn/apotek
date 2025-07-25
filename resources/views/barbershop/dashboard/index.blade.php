@extends('layouts.barbershop.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('style')
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h4>Dashboard BarberShop</h4>
        </div>
    </div>

    <div class="row">
        <!-- Active Barbers -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Barbers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ DB::table('bs_barbers')->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Services</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ DB::table('bs_services')->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-scissors fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Upcoming Appointments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ DB::table('bs_bookings')->where('status', 'confirmed')->where('booking_date', '>=', today())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed This Month -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Completed This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ DB::table('bs_bookings')->where('status', 'completed')->whereMonth('booking_date', now()->month)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="header-content">
                        <h6 class="header-title">Today's Bookings</h6>
                    </div>
                </div>
                <div class="table-card-body">
                    <div class="table-container">
                        @php
                            $todayBookings = DB::table('bs_bookings')
                                ->join('bs_barbers', 'bs_bookings.barber_id', '=', 'bs_barbers.id')
                                ->join('bs_services', 'bs_bookings.service_id', '=', 'bs_services.id')
                                ->where('booking_date', today())
                                ->orderBy('start_time')
                                ->select('bs_bookings.*', 'bs_barbers.name as barber_name', 'bs_services.name as service_name')
                                ->get();
                        @endphp

                        @if($todayBookings->isEmpty())
                            <p class="text-gray-500 p-4">No bookings for today.</p>
                        @else
                            <table class="table-types-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Time</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->customer_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                                            <td>{{ $booking->service_name }} ({{ $booking->barber_name }})</td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status == 'confirmed') badge-primary
                                                    @elseif($booking->status == 'completed') badge-success
                                                    @elseif($booking->status == 'cancelled') badge-danger
                                                    @else badge-warning @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="table-card">
                <div class="table-card-header">
                    <div class="header-content">
                        <h6 class="header-title">Upcoming Bookings</h6>
                    </div>
                </div>
                <div class="table-card-body">
                    <div class="table-container">
                        @php
                            $upcomingBookings = DB::table('bs_bookings')
                                ->join('bs_barbers', 'bs_bookings.barber_id', '=', 'bs_barbers.id')
                                ->join('bs_services', 'bs_bookings.service_id', '=', 'bs_services.id')
                                ->where('booking_date', '>', today())
                                ->orderBy('booking_date')
                                ->orderBy('start_time')
                                ->select('bs_bookings.*', 'bs_barbers.name as barber_name', 'bs_services.name as service_name')
                                ->limit(5)
                                ->get();
                        @endphp

                        @if($upcomingBookings->isEmpty())
                            <p class="text-gray-500 p-4">No upcoming bookings.</p>
                        @else
                            <table class="table-types-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Date & Time</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->customer_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d') }} at {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                                            <td>{{ $booking->service_name }} ({{ $booking->barber_name }})</td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status == 'confirmed') badge-primary
                                                    @elseif($booking->status == 'completed') badge-success
                                                    @elseif($booking->status == 'cancelled') badge-danger
                                                    @else badge-warning @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection