<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        return view('welcome');
    }

    public function dashboard_master()
    {
       if (Auth::user()->kd_lokasi == '01') {
        
            if (Auth::user()->type == '4') {
                return view('pos.index');
                
            }else{
                date_default_timezone_set('Asia/Jakarta');
                $date = (date('Y-m-d'));

                $total_pendapatan = DB::table('all_transactions')
                        ->select(DB::raw('SUM(all_transactions.total) as ttl_pendapatan'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->first();

                $total_pendapatan_cafe = DB::table('all_transactions')
                        ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                        ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_cafe'),
                                 DB::raw('SUM(all_transaction_items.quantity) as jml_item'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->whereIn('all_transaction_items.item_type', ['product'])
                        ->first();
                
                $total_pendapatan_barber = DB::table('all_transactions')
                        ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                        ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_barber'),
                                 DB::raw('SUM(all_transaction_items.quantity) as jml'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->whereIn('all_transaction_items.item_type', ['service'])
                        ->first();

                $total_pendapatan_senam = DB::table('all_transactions')
                        ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                        ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_senam'),
                                 DB::raw('SUM(all_transaction_items.quantity) as jml'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->whereIn('all_transaction_items.item_type', ['class'])
                        ->first();

                $data_transaksi = DB::table('all_transactions')
                        ->join('all_transaction_items', 'all_transactions.id', '=', 'all_transaction_items.transaction_id')
                        ->select('all_transactions.id',
                            'all_transactions.transaction_date',
                            'all_transactions.invoice_number',
                            'all_transactions.business_type',
                            DB::raw("GROUP_CONCAT(all_transaction_items.item_id ORDER BY all_transaction_items.item_id SEPARATOR ', ') as item_ids"),
                            DB::raw("GROUP_CONCAT(all_transaction_items.name ORDER BY all_transaction_items.item_id SEPARATOR ', ') as item_names"),
                            DB::raw("SUM(all_transaction_items.subtotal) as total_subtotal")
                        )
                        ->groupBy('all_transactions.id', 'all_transactions.transaction_date', 'all_transactions.invoice_number', 'all_transactions.business_type')
                        ->get();

                return view('master_dashboard', compact('total_pendapatan','total_pendapatan_cafe','total_pendapatan_barber','total_pendapatan_senam','data_transaksi'));
            }
        } else {
            $data_terlaris = DB::table('tr_penjualan_d')
                            ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                            ->join('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                            ->select('tr_penjualan_d.kode_produk','m_produk.nama_produk',DB::raw('SUM(tr_penjualan_d.qty_kecil) as jml_jual_terkecil'),'m_produk_unit.nama_unit')
                            ->groupBy('tr_penjualan_d.kode_produk','m_produk.nama_produk','m_produk_unit.nama_unit')
                            ->orderBy('jml_jual_terkecil', 'DESC')
                            ->get();

            $data_barang_habis = DB::table('m_produk')
                            ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.qty','m_produk.qty_min')
                            ->where('m_produk.qty', '<=', 'm_produk.qty_min')
                            ->get();
                            
            $endDate = now()->addDays(30);
            $data_barang_kadaluarsa = DB::table('m_produk')
                            ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                            ->join('m_produk_unit','m_produk.id_unit','=','m_produk_unit.id')
                            ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                            ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.kode_cabang',
                                    'm_cabang.nama_cabang','m_produk.id_supplier','m_supplier.nama_supplier',
                                    'm_produk.tgl_kadaluarsa','m_produk.qty','m_produk.id_unit','m_produk_unit.nama_unit')
                            ->where('m_produk.tgl_kadaluarsa', '<=', $endDate)
                            ->whereNotIn('m_produk.status', ['1'])
                            ->whereNotIn('m_produk.tgl_kadaluarsa', ['0000-00-00'])
                            ->whereNotIn('m_produk.qty', ['0'])
                            ->get();
                            
            $data_cabang = DB::table('m_cabang')
                            ->orderBy('kode_cabang', 'ASC')
                            ->get();

            return view('master_dashboard', compact('data_terlaris','data_barang_habis','data_barang_kadaluarsa','data_cabang'));
        }
    }

    public function dashboard_kasir()
    {
        date_default_timezone_set('Asia/Jakarta');
        // $date = (date('Y-m-d'));
        $date = '2025-09-20';
        

        $total_pendapatan = DB::table('all_transactions')
                ->select(DB::raw('SUM(all_transactions.total) as ttl_pendapatan'))
                ->whereDate('all_transactions.transaction_date',   $date)
                ->first();
       
        // $total_pendapatan = DB::table('all_transactions')
        //         ->select(DB::raw('SUM(all_transactions.total) as ttl_pendapatan'))
        //         ->whereDate('all_transactions.transaction_date',   $date)
        //         ->first();

        $total_pendapatan_cafe = DB::table('all_transactions')
                ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_cafe'),
                        DB::raw('SUM(all_transaction_items.quantity) as jml_item'))
                ->whereDate('all_transactions.transaction_date',   $date)
                ->whereIn('all_transaction_items.item_type', ['product'])
                ->first();
        
        $total_pendapatan_barber = DB::table('all_transactions')
                ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_barber'),
                        DB::raw('SUM(all_transaction_items.quantity) as jml'))
                ->whereDate('all_transactions.transaction_date',   $date)
                ->whereIn('all_transaction_items.item_type', ['service'])
                ->first();

        $total_pendapatan_senam = DB::table('all_transactions')
                ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_senam'),
                        DB::raw('SUM(all_transaction_items.quantity) as jml'))
                ->whereDate('all_transactions.transaction_date',   $date)
                ->whereIn('all_transaction_items.item_type', ['class'])
                ->first();

        $data_transaksi = DB::table('all_transactions')
                ->join('all_transaction_items', 'all_transactions.id', '=', 'all_transaction_items.transaction_id')
                ->select('all_transactions.id',
                    'all_transactions.transaction_date',
                    'all_transactions.invoice_number',
                    'all_transactions.business_type',
                    DB::raw("GROUP_CONCAT(all_transaction_items.item_id ORDER BY all_transaction_items.item_id SEPARATOR ', ') as item_ids"),
                    DB::raw("GROUP_CONCAT(all_transaction_items.name ORDER BY all_transaction_items.item_id SEPARATOR ', ') as item_names"),
                    DB::raw("SUM(all_transaction_items.subtotal) as total_subtotal")
                )
                ->groupBy('all_transactions.id', 'all_transactions.transaction_date', 'all_transactions.invoice_number', 'all_transactions.business_type')
                ->get();

        return view('dashboard_kasir', compact('total_pendapatan', 'total_pendapatan_cafe', 'total_pendapatan_barber', 'total_pendapatan_senam', 'data_transaksi' ));
        
    }

    public function index2()
    {
        if (Auth::user()->kd_lokasi == '03') {
            if (Auth::user()->type == '4') {
                return view('pos.index');
                
            }else{
                date_default_timezone_set('Asia/Jakarta');
                $date = (date('Y-m-d'));

                $total_pendapatan = DB::table('all_transactions')
                        ->select(DB::raw('SUM(all_transactions.total) as ttl_pendapatan'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->first();

                $total_pendapatan_cafe = DB::table('all_transactions')
                        ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                        ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_cafe'),
                                 DB::raw('SUM(all_transaction_items.quantity) as jml_item'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->whereIn('all_transaction_items.item_type', ['product'])
                        ->first();
                
                $total_pendapatan_barber = DB::table('all_transactions')
                        ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                        ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_barber'),
                                 DB::raw('SUM(all_transaction_items.quantity) as jml'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->whereIn('all_transaction_items.item_type', ['service'])
                        ->first();

                $total_pendapatan_senam = DB::table('all_transactions')
                        ->join('all_transaction_items','all_transactions.id','=','all_transaction_items.transaction_id')
                        ->select(DB::raw('SUM(all_transaction_items.subtotal) as ttl_pendapatan_senam'),
                                 DB::raw('SUM(all_transaction_items.quantity) as jml'))
                        ->whereDate('all_transactions.transaction_date',   $date)
                        ->whereIn('all_transaction_items.item_type', ['class'])
                        ->first();

                $data_transaksi = DB::table('all_transactions')
                        ->join('all_transaction_items', 'all_transactions.id', '=', 'all_transaction_items.transaction_id')
                        ->select('all_transactions.id',
                            'all_transactions.transaction_date',
                            'all_transactions.invoice_number',
                            'all_transactions.business_type',
                            DB::raw("GROUP_CONCAT(all_transaction_items.item_id ORDER BY all_transaction_items.item_id SEPARATOR ', ') as item_ids"),
                            DB::raw("GROUP_CONCAT(all_transaction_items.name ORDER BY all_transaction_items.item_id SEPARATOR ', ') as item_names"),
                            DB::raw("SUM(all_transaction_items.subtotal) as total_subtotal")
                        )
                        ->groupBy('all_transactions.id', 'all_transactions.transaction_date', 'all_transactions.invoice_number', 'all_transactions.business_type')
                        ->get();

                return view('home3', compact('total_pendapatan','total_pendapatan_cafe','total_pendapatan_barber','total_pendapatan_senam','data_transaksi'));
            }
        } else {
            $data_terlaris = DB::table('tr_penjualan_d')
                            ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                            ->join('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                            ->select('tr_penjualan_d.kode_produk','m_produk.nama_produk',DB::raw('SUM(tr_penjualan_d.qty_kecil) as jml_jual_terkecil'),'m_produk_unit.nama_unit')
                            //->where('tgl_kunjungan',  $date)
                            //->where('kode_cabang', Auth::user()->kd_lokasi)
                            // ->where('id_user_input', Auth::user()->id )
                            ->groupBy('tr_penjualan_d.kode_produk','m_produk.nama_produk','m_produk_unit.nama_unit')
                            ->orderBy('jml_jual_terkecil', 'DESC')
                            ->get();

            $data_barang_habis = DB::table('m_produk')
                            ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.qty','m_produk.qty_min')
                            //->where('tgl_kunjungan',  $date)
                            //->where('kode_cabang', Auth::user()->kd_lokasi)
                            // ->where('id_user_input', Auth::user()->id )
                            ->where('m_produk.qty', '<=', 'm_produk.qty_min')
                            ->get();
                            
            $endDate = now()->addDays(30);
            $data_barang_kadaluarsa = DB::table('m_produk')
                            ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                            ->join('m_produk_unit','m_produk.id_unit','=','m_produk_unit.id')
                            ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                            ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.kode_cabang',
                                    'm_cabang.nama_cabang','m_produk.id_supplier','m_supplier.nama_supplier',
                                    'm_produk.tgl_kadaluarsa','m_produk.qty','m_produk.id_unit','m_produk_unit.nama_unit')
                            ->where('m_produk.tgl_kadaluarsa', '<=', $endDate)
                            ->whereNotIn('m_produk.status', ['1'])
                            ->whereNotIn('m_produk.tgl_kadaluarsa', ['0000-00-00'])
                            ->whereNotIn('m_produk.qty', ['0'])
                            ->get();
                            
            $data_cabang = DB::table('m_cabang')
                            ->orderBy('kode_cabang', 'ASC')
                            ->get();

            return view('home', compact('data_terlaris','data_barang_habis','data_barang_kadaluarsa','data_cabang'));
        }
        
    }

    public function akses_ditolak()
    {
        return view('errors.akses-ditolak');
    }

    public function getData(Request $request)
    {
        // dd(Auth::user()->id);
        $businessType = $request->business_type; 
        $period       = $request->period;        
        $date         = '2025-09-20';          

        $query = DB::table('all_transaction_items as ati')
            ->join('all_transactions as at', 'ati.invoice_number', '=', 'at.invoice_number')
            ->join('cs_branches as b', 'at.branch_id', '=', 'b.id')
            ->where('at.user_id', Auth::user()->id)
            ->select(
                'ati.*', 'at.transaction_date', 'at.branch_id', 'at.user_id', 'at.payment_method');

        // 1. filter bisnis
        if ($businessType && $businessType !== 'all') {
            $query->where('ati.business_type', $businessType);
        }

        // 2. filter tanggal
        if ($period === 'daily' && $date) {
            $query->whereDate('at.transaction_date', $date);
        } elseif ($period === 'monthly' && $date) {
            $query->whereYear('at.transaction_date', substr($date,0,4))
                ->whereMonth('at.transaction_date', substr($date,5,2));
        } elseif ($period === 'yearly' && $date) {
            $query->whereYear('at.transaction_date', $date);
        }

        // $totalCash = DB::table('all_transaction_items as ati')
        //     ->join('all_transactions as at', 'ati.invoice_number', '=', 'at.invoice_number')
        //     ->join('cs_branches as b', 'at.branch_id', '=', 'b.id')
        //     ->where('at.user_id', Auth::user()->id)
        //     ->where('at.payment_method', 'cash')   // <-- filter cash
        //     ->sum('ati.subtotal');  

        return response()->json([
            'status' => true,
            'data'   => $query->get()
        ]);
    }

}
