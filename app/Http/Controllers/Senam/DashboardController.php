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
                ->where('is_active', true)
                ->count(),
            'completed_classes' => DB::table('s_class_schedule')
                ->count(),
        ];

        // Kelas mendatang (7 hari ke depan)
        $upcomingClasses = DB::table('s_class_schedule as cs')
            ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
            ->join('s_instructors as i', 'cs.instructor_id', '=', 'i.id')
            ->select(
                'cs.id',
                'ct.name as class_name',
                'i.name as instructor_name',
            )
            ->where('cs.is_active', true)
            ->get();

        return view('senam.dashboard.index', compact(
            'stats',
            'upcomingClasses'
        ));
    }
}