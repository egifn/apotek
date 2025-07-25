<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = DB::table('bs_bookings')
            ->whereDate('created_at', today())
            ->where('status', 'completed');
            // ->sum('price');

        // Penjualan Bulan Ini
        $monthlySales = DB::table('bs_bookings')
            ->whereMonth('created_at', now()->month)
            ->where('status', 'completed');
            // ->sum('price');

        // Layanan Terpopuler
        $bestSellers = DB::table('bs_bookings')
            ->join('bs_services', 'bs_bookings.service_id', '=', 'bs_services.id')
            ->select('bs_services.name', DB::raw('COUNT(*) as total_sold'))
            ->where('bs_bookings.status', 'completed')
            ->groupBy('bs_services.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Jadwal Hari Ini
        $todayBookings = DB::table('bs_bookings')
            ->join('bs_barbers', 'bs_bookings.barber_id', '=', 'bs_barbers.id')
            ->join('bs_services', 'bs_bookings.service_id', '=', 'bs_services.id')
            ->whereDate('booking_date', today())
            ->orderBy('start_time')
            ->select('bs_bookings.*', 'bs_barbers.name as barber_name', 'bs_services.name as service_name')
            ->get();

        return view('barbershop.dashboard.index', compact(
            'todaySales',
            'monthlySales',
            'bestSellers',
            'todayBookings'
        ));
        
        // return view('barbershop.dashboard.index');
    }
}
