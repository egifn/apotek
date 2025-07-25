<?php
namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('coffeshop.report.index');
    }

    public function getBranches()
    {
        try {
            $branches = DB::table('cs_branches')->where('is_active', 1)->get();
            return response()->json([
                'status' => true,
                'data' => $branches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data cabang: ' . $e->getMessage()
            ]);
        }
    }

    public function getStockReport(Request $request)
    {
        try {
            $branchId = $request->input('branch_id');
            $period = $request->input('filter_type');
            $date = $request->input('date');

            $query = DB::table('cs_branch_stocks')
                ->select(
                    'cs_branch_stocks.id',
                    'cs_ingredients.name',
                    'cs_branch_stocks.stock',
                    'cs_branches.name as branch_name',
                    'cs_branch_stocks.updated_at'
                )
                ->join('cs_ingredients', 'cs_branch_stocks.ingredient_id', '=', 'cs_ingredients.id')
                ->join('cs_branches', 'cs_branch_stocks.branch_id', '=', 'cs_branches.id');

            if ($branchId) {
                $query->where('cs_branch_stocks.branch_id', $branchId);
            }

            if ($period === 'daily' && $date) {
                $query->whereDate('cs_branch_stocks.updated_at', $date);
            }

            if ($period === 'monthly' && $date) {
                list($year, $month) = explode('-', $date);
                $query->whereYear('cs_branch_stocks.updated_at', $year)
                    ->whereMonth('cs_branch_stocks.updated_at', $month);
            }

            if ($period === 'yearly' && $date) {
                $query->whereYear('cs_branch_stocks.updated_at', $date);
            }

            $data = $query->get();

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

    public function getTransactionReport(Request $request)
    {
        try {
            $branchId = $request->input('branch_id');
            $period = $request->input('filter_type');
            $date = $request->input('date');

            $query = DB::table('cs_transactions')
                ->select(
                    'cs_transactions.invoice',
                    'cs_branches.name as branch_name',
                    'cs_transactions.transaction_date',
                    'cs_transactions.grand_total'
                )
                ->join('cs_branches', 'cs_transactions.branch_id', '=', 'cs_branches.id');

            if ($branchId) {
                $query->where('cs_transactions.branch_id', $branchId);
            }

            if ($period === 'daily' && $date) {
                $query->whereDate('cs_transactions.transaction_date', $date);
            }

            if ($period === 'monthly' && $date) {
                list($year, $month) = explode('-', $date);
                $query->whereYear('cs_transactions.transaction_date', $year)
                    ->whereMonth('cs_transactions.transaction_date', $month);
            }

            if ($period === 'yearly' && $date) {
                $query->whereYear('cs_transactions.transaction_date', $date);
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
}