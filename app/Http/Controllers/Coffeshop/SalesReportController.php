<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SalesReportController extends Controller
{
     public function index()
    {
        return view('coffeshop.report.sales-report');
    }

    public function getData(Request $request)
    {
        $filterType = $request->input('filter_type', 'daily');
        $date = $request->input('date', now()->format('Y-m-d'));

        $query = DB::table('cs_transaction_details')
            ->join('cs_transactions', 'cs_transaction_details.transaction_id', '=', 'cs_transactions.id')
            ->join('cs_products', 'cs_transaction_details.product_id', '=', 'cs_products.id')
            ->where('cs_transactions.status', 'completed');

        switch ($filterType) {
            case 'daily':
                $query->whereDate('cs_transactions.transaction_date', $date);
                break;
            case 'weekly':
                $startOfWeek = \Carbon\Carbon::parse($date)->startOfWeek();
                $endOfWeek = \Carbon\Carbon::parse($date)->endOfWeek();
                $query->whereBetween('cs_transactions.transaction_date', [$startOfWeek, $endOfWeek]);
                break;
            case 'monthly':
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));
                $query->whereYear('cs_transactions.transaction_date', $year)
                    ->whereMonth('cs_transactions.transaction_date', $month);
                break;
            case 'yearly':
                $year = date('Y', strtotime($date));
                $query->whereYear('cs_transactions.transaction_date', $year);
                break;
        }

        $transactions = $query
            ->select(
                'cs_transaction_details.*',
                'cs_products.name as product_name',
                'cs_transactions.transaction_date'
            )
            ->get();

        $totalRevenue = $transactions->sum('subtotal');

        return response()->json([
            'status' => true,
            'data' => $transactions,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}
