<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view ('coffeshop.report.penjualan');
    }

    public function getDataPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_penjualan = DB::table('all_transactions')
                    ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                    
                    ->select('all_transactions.id','all_transactions.invoice_number','all_transactions.transaction_date','all_transaction_items.item_type','all_transaction_items.name',
                    'all_transaction_items.quantity','all_transaction_items.price','all_transaction_items.subtotal');
                   
        if(!isset($request->value)) {
            $data_penjualan
                            
                            ->whereIn('all_transaction_items.item_type', ['product']);
        }else{
            $data_penjualan
                            
                            ->whereIn('all_transaction_items.item_type', ['product']);           
        }

        $data  = $data_penjualan->get();
        $count = ($data_penjualan->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }
}
