<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPembelianController extends Controller
{
    public function index()
    {
        return view ('coffeshop.report.pembelian');
    }

    public function getDataPembelian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_pembelian = DB::table('cs_pembelian')
                    ->join('cs_pembelian_detail','cs_pembelian.kode_pembelian','=','cs_pembelian_detail.kode_pembelian')
                    ->join('m_supplier','cs_pembelian.supplier_id','=','m_supplier.id')
                    ->join('cs_ingredients','cs_pembelian_detail.ingredient_id','=','cs_ingredients.id')
                    
                    ->select('cs_pembelian.kode_pembelian','cs_pembelian.tanggal','cs_pembelian.supplier_id','m_supplier.nama_supplier',
                    'cs_pembelian.jenis',
                    'cs_pembelian.status_pembelian','cs_pembelian.status_pembayaran','cs_pembelian_detail.ingredient_id',
                    'cs_ingredients.name','cs_pembelian_detail.qty','cs_pembelian_detail.harga','cs_pembelian_detail.subtotal'
                );
                   
        if(!isset($request->value)) {
            // $data_pembelian
                            
            //                 ->whereIn('all_transaction_items.item_type', ['product']);
        }else{
            // $data_pembelian
                            
            //                 ->whereIn('all_transaction_items.item_type', ['product']);           
        }

        $data  = $data_pembelian->get();
        $count = ($data_pembelian->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }
}
