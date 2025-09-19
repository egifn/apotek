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

            $query = DB::table('cs_stocks')
                ->select(
                    'cs_stocks.*',
                    'cs_ingredients.name',
                    'cs_branches.name as branch_name',
                )
                ->join('cs_ingredients', 'cs_stocks.id_ingredients', '=', 'cs_ingredients.code_ingredient')
                ->join('cs_branches', 'cs_stocks.id_branch', '=', 'cs_branches.id');
                

            if ($branchId) {
                $query->where('cs_stocks.id_branch', $branchId);
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

    public function getSalesReport(Request $request)
    {
        try {
            $branchId = $request->input('branch_id');
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
                ->where('all_transaction_items.item_type', 'product');
            
            if ($branchId) {
                $query->where('all_transactions.branch_id', $branchId);
            }
            
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
}