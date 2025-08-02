<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data statistik
        $stats = [
            'active_classes' => DB::table('s_class_types')->where('is_active', true)->count(),
            'active_instructors' => DB::table('s_instructors')->where('is_active', true)->count(),
            'upcoming_classes' => DB::table('s_class_schedule')
                ->where('recurrence_value', '>=', now())
                ->where('is_active', true)
                ->count(),
            'completed_classes' => DB::table('s_class_schedule')
                ->where('end_datetime', '<', now())
                ->where('end_datetime', '>=', now()->startOfMonth())
                ->count(),
        ];

        // Kelas hari ini
        $todayClasses = DB::table('s_class_schedule as cs')
            ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
            ->join('s_instructors as i', 'cs.instructor_id', '=', 'i.id')
            ->join('s_locations as l', 'cs.location_id', '=', 'l.id')
            ->select(
                'cs.id',
                'ct.name as class_name',
                'i.name as instructor_name',
                'l.name as location_name',
                'cs.start_datetime',
                'cs.end_datetime',
                'cs.max_participants',
                DB::raw('(SELECT COUNT(*) FROM s_class_bookings WHERE class_schedule_id = cs.id) as participants_count')
            )
            ->whereDate('cs.start_datetime', today())
            ->where('cs.is_active', true)
            ->orderBy('cs.start_datetime')
            ->get();

        // Kelas mendatang (7 hari ke depan)
        $upcomingClasses = DB::table('s_class_schedule as cs')
            ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
            ->join('s_instructors as i', 'cs.instructor_id', '=', 'i.id')
            ->select(
                'cs.id',
                'ct.name as class_name',
                'i.name as instructor_name',
                'cs.start_datetime',
                'cs.end_datetime'
            )
            ->whereBetween('cs.start_datetime', [now(), now()->addDays(7)])
            ->where('cs.is_active', true)
            ->orderBy('cs.start_datetime')
            ->get();

        // Booking terbaru
        $recentBookings = DB::table('s_class_bookings as cb')
            ->leftJoin('s_members as m', 'cb.member_id', '=', 'm.id')
            ->leftJoin('s_non_members as nm', 'cb.non_member_id', '=', 'nm.id')
            ->join('s_class_schedule as cs', 'cb.class_schedule_id', '=', 'cs.id')
            ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
            ->select(
                'cb.id',
                DB::raw('COALESCE(m.name, nm.name) as customer_name'),
                'ct.name as class_name',
                'cs.start_datetime',
                'cb.payment_status',
                'cb.created_at'
            )
            ->where('cs.start_datetime', '>=', now())
            ->orderBy('cb.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('senam.dashboard.index', compact(
            'stats',
            'todayClasses',
            'upcomingClasses',
            'recentBookings'
        ));
    }
}