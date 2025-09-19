<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ClassParticipationExport;
use App\Exports\QuotaUsageExport;
use App\Exports\InstructorSessionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $classTypes = DB::table('s_class_types')->where('is_active', true)->get();
        $instructors = DB::table('s_instructors')->where('is_active', true)->get();
        
        return view('senam.master.reports', compact('classTypes', 'instructors'));
    }

    public function getClassParticipation(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $classTypeId = $request->input('class_type_id');
            $limit = $request->input('limit', 10);

            $query = DB::table('s_class_schedule as cs')
                ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
                ->leftJoin('s_class_bookings as cb', 'cs.id', '=', 'cb.class_schedule_id')
                ->select(
                    'cs.id',
                    'ct.name as class_name',
                    'cs.start_datetime',
                    'cs.max_participants',
                    DB::raw('COUNT(cb.id) as total_participants'),
                    DB::raw('CONCAT(ROUND(COUNT(cb.id) / cs.max_participants * 100, 2), "%") as participation_rate')
                )
                ->groupBy('cs.id', 'ct.name', 'cs.start_datetime', 'cs.max_participants');

            if ($startDate && $endDate) {
                $query->whereBetween('cs.start_datetime', [$startDate, $endDate]);
            }

            if ($classTypeId) {
                $query->where('cs.class_type_id', $classTypeId);
            }

            $data = $query->orderBy('cs.start_datetime', 'desc')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data partisipasi kelas berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data partisipasi kelas',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getQuotaUsage(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $membershipType = $request->input('membership_type');
            $limit = $request->input('limit', 10);

            $query = DB::table('s_members as m')
                ->join('s_member_quotas as mq', 'm.id', '=', 'mq.member_id')
                ->leftJoin('s_quota_history as qh', 'mq.id', '=', 'qh.quota_id')
                ->select(
                    'm.id',
                    'm.name',
                    'm.membership_type',
                    DB::raw('SUM(mq.total_quota) as total_quota'),
                    DB::raw('SUM(mq.remaining_quota) as remaining_quota'),
                    DB::raw('SUM(mq.total_quota - mq.remaining_quota) as used_quota'),
                    DB::raw('CONCAT(ROUND(SUM(mq.total_quota - mq.remaining_quota) / SUM(mq.total_quota) * 100, 2), "%") as usage_rate')
                )
                ->groupBy('m.id', 'm.name', 'm.membership_type');

            if ($startDate && $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('mq.start_date', [$startDate, $endDate])
                      ->orWhereBetween('mq.end_date', [$startDate, $endDate]);
                });
            }

            if ($membershipType) {
                $query->where('m.membership_type', $membershipType);
            }

            $data = $query->orderBy('used_quota', 'desc')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data penggunaan kuota berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data penggunaan kuota',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getInstructorSessions(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $instructorId = $request->input('instructor_id');
            $limit = $request->input('limit', 10);

            $query = DB::table('s_instructors as i')
                ->join('s_class_schedule as cs', 'i.id', '=', 'cs.instructor_id')
                ->leftJoin('s_class_bookings as cb', 'cs.id', '=', 'cb.class_schedule_id')
                ->select(
                    'i.id',
                    'i.name',
                    DB::raw('COUNT(DISTINCT cs.id) as total_sessions'),
                    DB::raw('COUNT(cb.id) as total_participants'),
                    DB::raw('ROUND(COUNT(cb.id) / COUNT(DISTINCT cs.id), 2) as avg_participants')
                )
                ->groupBy('i.id', 'i.name');

            if ($startDate && $endDate) {
                $query->whereBetween('cs.start_datetime', [$startDate, $endDate]);
            }

            if ($instructorId) {
                $query->where('i.id', $instructorId);
            }

            $data = $query->orderBy('total_sessions', 'desc')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data sesi instruktur berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data sesi instruktur',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getQuotaReport(Request $request)
    {
        // dd($request->all());
        try {
            $branchId = $request->input('branch_id');
            $period = $request->input('filter_type');
            $date = $request->input('date');

            $data = DB::table('s_quota_history')
                ->select(
                    's_quota_history.*',
                    's_members.name',
                )
                ->join('s_members', 's_quota_history.member_id', '=', 's_members.id')
                ->get();

                // dd($data);

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan stok: ' . $e->getMessage()
            ]);
        }
    }

    public function getSalesReport(Request $request)
    {
        // dd($request->all());
        try {
            // $branchId = $request->input('branch_id');
            $period = $request->input('filter_type');
            $date = $request->input('date');

            $query = DB::table('all_transaction_items')
                ->select(
                    'all_transaction_items.*',
                    'all_transactions.*',
                    'cs_branches.name as branch_name'
                )
                ->join('all_transactions', 'all_transaction_items.transaction_id', '=', 'all_transactions.id')
                ->join('cs_branches', 'all_transactions.branch_id', '=', 'cs_branches.id')
                ->where('all_transaction_items.item_type', 'class');

                // dd($query->get());
            
            // if ($branchId) {
            //     $query->where('all_transactions.branch_id', $branchId);
            // }
            
            if ($period === 'daily' && $date) {
                $query->whereDate('all_transactions.transaction_date', $date);
            }
            
            if ($period === 'monthly' && $date) {
                list($year, $month) = explode('-', $date);
                $query->whereYear('all_transactions.transaction_date', $year)
                    ->whereMonth('all_transactions.transaction_date', $month);
            }

            if ($period === 'yearly' && $date) {
                $query->whereYear('all_transactions.transaction_date', $date);
            }

            $data = $query->get();

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan transaksi: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getInstrukturReport(Request $request)
    {
        try {
            $period = $request->input('filter_type');
            $date = $request->input('date');

            $query = DB::table('all_transaction_items')
                ->select(
                    'all_transaction_items.*',
                    'all_transactions.*',
                    'cs_branches.name as branch_name',
                    's_instructors.name as instructor_name',
                    's_class_types.name as class_name'
                )
                ->join('all_transactions', 'all_transaction_items.transaction_id', '=', 'all_transactions.id')
                ->join('cs_branches', 'all_transactions.branch_id', '=', 'cs_branches.id')
                ->join('s_class_schedule', 'all_transaction_items.item_id', '=', 's_class_schedule.id')
                ->join('s_class_types', 's_class_schedule.class_type_id', '=', 's_class_types.id')
                ->join('s_instructors', 's_class_schedule.instructor_id', '=', 's_instructors.id')
                ->where('all_transaction_items.item_type', 'class');

                // dd($query->get());
            
            // if ($branchId) {
            //     $query->where('all_transactions.branch_id', $branchId);
            // }
            
            if ($period === 'daily' && $date) {
                $query->whereDate('all_transactions.transaction_date', $date);
            }
            
            if ($period === 'monthly' && $date) {
                list($year, $month) = explode('-', $date);
                $query->whereYear('all_transactions.transaction_date', $year)
                    ->whereMonth('all_transactions.transaction_date', $month);
            }

            if ($period === 'yearly' && $date) {
                $query->whereYear('all_transactions.transaction_date', $date);
            }

            $data = $query->get();

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan transaksi: ' . $e->getMessage()
            ]);
        }
    }
   
    public function getPurchaseReport(Request $request)
    {
        try {
            $branchId = $request->input('branch_id');
            $period = $request->input('filter_type');
            $date = $request->input('date');

            $query = DB::table('cs_pembelian_detail')
                ->select(
                    'cs_pembelian_detail.*',
                    'cs_pembelian.tanggal',
                    'cs_pembelian.jenis',
                    'm_supplier.nama_supplier',
                    'cs_ingredients.name as ingredient_name',
                    'cs_branches.name as branch_name'
                )
                ->join('cs_pembelian', 'cs_pembelian.kode_pembelian', '=', 'cs_pembelian_detail.kode_pembelian')
                ->join('cs_branches', 'cs_pembelian.kode_cabang', '=', 'cs_branches.id')
                ->join('m_supplier', 'cs_pembelian.supplier_id', '=', 'm_supplier.id')
                ->join('cs_ingredients', 'cs_pembelian_detail.ingredient_id', '=', 'cs_ingredients.code_ingredient');

            if ($branchId) {
                $query->where('cs_pembelian.kode_cabang', $branchId);
            }

            if ($period === 'daily' && $date) {
                $formattedDate = date('Y-m-d', strtotime($date));
                $query->where('cs_pembelian.tanggal', $formattedDate);
            }

            if ($period === 'monthly' && $date) {
                list($year, $month) = explode('-', $date);
                $query->whereYear('cs_pembelian.tanggal', $year)
                    ->whereMonth('cs_pembelian.tanggal', $month);
            }

            if ($period === 'yearly' && $date) {
                $year = preg_replace('/[^0-9]/', '', $date);
                $query->whereYear('cs_pembelian.tanggal', $year);
            }
            
            $data = $query->get();

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan transaksi: ' . $e->getMessage()
            ]);
        }
    }

    // public function exportParticipation(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $classTypeId = $request->input('class_type_id');

    //     return Excel::download(new ClassParticipationExport($startDate, $endDate, $classTypeId), 'partisipasi-kelas.xlsx');
    // }

    // public function exportQuota(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $membershipType = $request->input('membership_type');

    //     return Excel::download(new QuotaUsageExport($startDate, $endDate, $membershipType), 'penggunaan-kuota.xlsx');
    // }

    // public function exportInstructor(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $instructorId = $request->input('instructor_id');

    //     return Excel::download(new InstructorSessionsExport($startDate, $endDate, $instructorId), 'sesi-instruktur.xlsx');
    // }
}