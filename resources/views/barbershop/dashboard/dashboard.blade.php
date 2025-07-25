@extends('barbershop.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Today's Bookings -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Today's Bookings</h3>
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
            <p class="text-gray-500">No bookings for today.</p>
        @else
            <ul class="space-y-3">
                @foreach($todayBookings as $booking)
                    <li class="border-b pb-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $booking->customer_name }}</span>
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                        </div>
                        <div class="text-sm text-gray-600">{{ $booking->service_name }} with {{ $booking->barber_name }}</div>
                        <span class="inline-block px-2 py-1 text-xs rounded 
                            @if($booking->status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($booking->status == 'completed') bg-green-100 text-green-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Upcoming Bookings -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Upcoming Bookings</h3>
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
            <p class="text-gray-500">No upcoming bookings.</p>
        @else
            <ul class="space-y-3">
                @foreach($upcomingBookings as $booking)
                    <li class="border-b pb-2">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $booking->customer_name }}</span>
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d') }} at {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>
                        </div>
                        <div class="text-sm text-gray-600">{{ $booking->service_name }} with {{ $booking->barber_name }}</div>
                        <span class="inline-block px-2 py-1 text-xs rounded 
                            @if($booking->status == 'confirmed') bg-blue-100 text-blue-800
                            @elseif($booking->status == 'completed') bg-green-100 text-green-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Statistics -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Statistics</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="text-blue-800 font-bold text-2xl">
                    {{ DB::table('bs_barbers')->where('is_active', true)->count() }}
                </div>
                <div class="text-blue-600">Active Barbers</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="text-green-800 font-bold text-2xl">
                    {{ DB::table('bs_services')->where('is_active', true)->count() }}
                </div>
                <div class="text-green-600">Services</div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="text-purple-800 font-bold text-2xl">
                    {{ DB::table('bs_bookings')->where('status', 'confirmed')->where('booking_date', '>=', today())->count() }}
                </div>
                <div class="text-purple-600">Upcoming Appointments</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-yellow-800 font-bold text-2xl">
                    {{ DB::table('bs_bookings')->where('status', 'completed')->whereMonth('booking_date', now()->month)->count() }}
                </div>
                <div class="text-yellow-600">Completed This Month</div>
            </div>
        </div>
    </div>
</div>
@endsection