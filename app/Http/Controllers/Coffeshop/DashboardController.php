<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total transaksi hari ini
        $todaySales = DB::table('cs_transactions')
            ->whereDate('transaction_date', today())
            ->sum('grand_total');

        // Hitung total transaksi bulan ini
        $monthlySales = DB::table('cs_transactions')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('grand_total');

        // Produk terlaris bulan ini
        $bestSellers = DB::table('cs_transaction_details as td')
            ->join('cs_products as p', 'td.product_id', '=', 'p.id')
            ->select('p.name', DB::raw('SUM(td.quantity) as total_sold'))
            ->whereMonth('td.created_at', now()->month)
            ->whereYear('td.created_at', now()->year)
            ->groupBy('p.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Stok hampir habis
        $lowStocks = DB::table('cs_stocks')
            ->select('cs_stocks.id', 'cs_stocks.stock_available', 'cs_stocks.min_stock', 'cs_ingredients.name', 'cs_branches.name as branch_name')
            ->join('cs_branches', 'cs_stocks.id_branch', '=', 'cs_branches.id')
            ->join('cs_ingredients', 'cs_stocks.id_ingredients', '=', 'cs_ingredients.id')
            ->where('cs_stocks.stock_available', '>=', 'cs_stocks.min_stock')
            ->get();

        // dd($lowStocks);
        return view('coffeshop.dashboard.index', compact(
            'todaySales',
            'monthlySales',
            'bestSellers',
            'lowStocks'
        ));
    }
}
