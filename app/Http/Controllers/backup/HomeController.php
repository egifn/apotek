<?php

namespace App\Http\Controllers;

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

    public function getDataJmlPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));
        
        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $penjualan_jml = DB::table('tr_penjualan_h')
                        ->select(DB::raw('COUNT(kode_penjualan) as jml_penjualan'))
                        ->WhereNotIn('tr_penjualan_h.jenis_penjualan', ['Panel'])
                        ->WhereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('tgl_penjualan',  $date)
                        //->where('kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
            }else{
                $penjualan_jml = DB::table('tr_penjualan_h')
                ->select(DB::raw('COUNT(kode_penjualan) as jml_penjualan'))
                ->WhereNotIn('tr_penjualan_h.jenis_penjualan', ['Panel'])
                ->WhereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->where('tgl_penjualan',  $date)
                ->where('kode_cabang', $request->value)
                // ->where('id_user_input', Auth::user()->id )
                ->first();
            }
        }else{ //jika Admin
            $penjualan_jml = DB::table('tr_penjualan_h')
                ->select(DB::raw('COUNT(kode_penjualan) as jml_penjualan'))
                ->WhereNotIn('tr_penjualan_h.jenis_penjualan', ['Panel'])
                ->WhereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->where('tgl_penjualan',  $date)
                ->where('kode_cabang', Auth::user()->kd_lokasi)
                // ->where('id_user_input', Auth::user()->id )
                ->first();
        }
        
        return response()->json([
            'data' => $penjualan_jml
        ]);
    }

    public function getDataTtlPenjualan(Request $request)
    {   
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $penjualan_ttl = DB::table('tr_penjualan_h')
                        ->select(DB::raw('SUM(tr_penjualan_h.total_bayar) as ttl_penjualan'))
                        ->WhereNotIn('tr_penjualan_h.jenis_penjualan', ['Panel'])
                        ->WhereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('tr_penjualan_h.tgl_penjualan',  $date)
                        //->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
            }else{
                $penjualan_ttl = DB::table('tr_penjualan_h')
                        ->select(DB::raw('SUM(tr_penjualan_h.total_bayar) as ttl_penjualan'))
                        ->WhereNotIn('tr_penjualan_h.jenis_penjualan', ['Panel'])
                        ->WhereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('tr_penjualan_h.tgl_penjualan',  $date)
                        ->where('tr_penjualan_h.kode_cabang', $request->value)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
            }
        }else{ //jika Admin
            $penjualan_ttl = DB::table('tr_penjualan_h')
                        ->select(DB::raw('SUM(tr_penjualan_h.total_bayar) as ttl_penjualan'))
                        ->WhereNotIn('tr_penjualan_h.jenis_penjualan', ['Panel'])
                        ->WhereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('tr_penjualan_h.tgl_penjualan',  $date)
                        ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
        }
        
        return response()->json([
            'data' => $penjualan_ttl
        ]);
    }
    
    public function getDataTtlPenjualanPanel(Request $request)
    {   
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d 00:00:01'));
        $date_end = (date('Y-m-d 23:59:59'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $penjualan_ttl_panel = DB::table('tr_penjualan_h')
                        ->select(DB::raw('SUM(tr_penjualan_h.total_bayar) as ttl_penjualan_panel'))
                        ->Where('tr_penjualan_h.jenis_penjualan', 'Panel')
                        ->WhereBetween('tr_penjualan_h.created_at',  [$date_start,$date_end])
                        ->where('tr_penjualan_h.status_bayar', 'Sudah Bayar')
                        //->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
            }else{
                $penjualan_ttl_panel = DB::table('tr_penjualan_h')
                        ->select(DB::raw('SUM(tr_penjualan_h.total_bayar) as ttl_penjualan_panel'))
                        ->Where('tr_penjualan_h.jenis_penjualan', 'Panel')
                        ->WhereBetween('tr_penjualan_h.created_at',  [$date_start,$date_end])
                        ->where('tr_penjualan_h.status_bayar', 'Sudah Bayar')
                        ->where('tr_penjualan_h.kode_cabang', $request->value)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
            }
        }else{ //jika Admin
            $penjualan_ttl_panel = DB::table('tr_penjualan_h')
                        ->select(DB::raw('SUM(tr_penjualan_h.total_bayar) as ttl_penjualan_panel'))
                        ->Where('tr_penjualan_h.jenis_penjualan', 'Panel')
                        ->WhereBetween('tr_penjualan_h.created_at',  [$date_start,$date_end])
                        ->where('tr_penjualan_h.status_bayar', 'Sudah Bayar')
                        ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
        }

        return response()->json([
            'data' => $penjualan_ttl_panel
        ]);
    }

   public function getDataJmlReturPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $penjualan_jml = DB::table('tr_penjualan_retur_h')
                        ->select(DB::raw('COUNT(kode_retur) as jml_retur'))
                        ->where('tgl_retur',  $date)
                        //->where('kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
            }else{
                $penjualan_jml = DB::table('tr_penjualan_retur_h')
                        ->select(DB::raw('COUNT(kode_retur) as jml_retur'))
                        ->where('tgl_retur',  $date)
                        ->where('kode_cabang', $request->value)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
            }
        }else{ //jika Admin
            $penjualan_jml = DB::table('tr_penjualan_retur_h')
                        ->select(DB::raw('COUNT(kode_retur) as jml_retur'))
                        ->where('tgl_retur',  $date)
                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
        }
       
        return response()->json([
            'data' => $penjualan_jml
        ]);
    }

    public function getDataTtlReturPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $penjualan_ttl = DB::table('tr_penjualan_retur_h')
                        ->select(DB::raw('SUM(tr_penjualan_retur_h.total_bayar) as ttl_retur_penjualan'))
                        ->where('tr_penjualan_retur_h.tgl_retur',  $date)
                        //->where('tr_penjualan_retur_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_retur_h.id_user_input', Auth::user()->id )
                        ->first();
            }else{
                $penjualan_ttl = DB::table('tr_penjualan_retur_h')
                        ->select(DB::raw('SUM(tr_penjualan_retur_h.total_bayar) as ttl_retur_penjualan'))
                        ->where('tr_penjualan_retur_h.tgl_retur',  $date)
                        ->where('tr_penjualan_retur_h.kode_cabang', $request->value)
                        // ->where('tr_penjualan_retur_h.id_user_input', Auth::user()->id )
                        ->first();
            }
        }else{ //jika Admin
            $penjualan_ttl = DB::table('tr_penjualan_retur_h')
                        ->select(DB::raw('SUM(tr_penjualan_retur_h.total_bayar) as ttl_retur_penjualan'))
                        ->where('tr_penjualan_retur_h.tgl_retur',  $date)
                        ->where('tr_penjualan_retur_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_retur_h.id_user_input', Auth::user()->id )
                        ->first();
        }

        return response()->json([
            'data' => $penjualan_ttl
        ]);
    }

    public function getDataPendapatanUser(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));
        $kode_lokasi = Auth::user()->kd_lokasi;

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $data_pendapatan_user = DB::select("SELECT users.id,users.name,
                    COALESCE(SUM(tr_penjualan_h.total_bayar),0) AS ttl_penjualan,
                    COALESCE(SUM(tr_penjualan_retur_h.total_bayar),0) AS ttl_retur,
                    COALESCE(SUM(tr_penjualan_h.total_bayar),0)-COALESCE(SUM(tr_penjualan_retur_h.total_bayar),0) AS total_pendapatan 
                    FROM users
                    INNER JOIN tr_penjualan_h ON users.id = tr_penjualan_h.id_user_input 
                    LEFT JOIN tr_penjualan_retur_h ON tr_penjualan_h.kode_penjualan = tr_penjualan_retur_h.kode_penjualan
                    WHERE tr_penjualan_h.jenis_penjualan NOT IN ('Panel') 
                    AND tr_penjualan_h.tgl_penjualan = '$date'
                    GROUP BY users.id,users.name");
            }else{
                $data_pendapatan_user = DB::select("SELECT users.id,users.name,
                    COALESCE(SUM(tr_penjualan_h.total_bayar),0) AS ttl_penjualan,
                    COALESCE(SUM(tr_penjualan_retur_h.total_bayar),0) AS ttl_retur,
                    COALESCE(SUM(tr_penjualan_h.total_bayar),0)-COALESCE(SUM(tr_penjualan_retur_h.total_bayar),0) AS total_pendapatan 
                    FROM users
                    INNER JOIN tr_penjualan_h ON users.id = tr_penjualan_h.id_user_input 
                    LEFT JOIN tr_penjualan_retur_h ON tr_penjualan_h.kode_penjualan = tr_penjualan_retur_h.kode_penjualan
                    WHERE tr_penjualan_h.jenis_penjualan NOT IN ('Panel') 
                    AND tr_penjualan_h.tgl_penjualan = '$date'
                    AND tr_penjualan_h.kode_cabang = '$request->value'
                    GROUP BY users.id,users.name");
            }
        }else{ //jika Admin
            $data_pendapatan_user = DB::select("SELECT users.id,users.name,
                COALESCE(SUM(tr_penjualan_h.total_bayar),0) AS ttl_penjualan,
                COALESCE(SUM(tr_penjualan_retur_h.total_bayar),0) AS ttl_retur,
                COALESCE(SUM(tr_penjualan_h.total_bayar),0)-COALESCE(SUM(tr_penjualan_retur_h.total_bayar),0) AS total_pendapatan 
                FROM users
                INNER JOIN tr_penjualan_h ON users.id = tr_penjualan_h.id_user_input 
                LEFT JOIN tr_penjualan_retur_h ON tr_penjualan_h.kode_penjualan = tr_penjualan_retur_h.kode_penjualan
                WHERE tr_penjualan_h.jenis_penjualan NOT IN ('Panel') 
                AND tr_penjualan_h.tgl_penjualan = '$date'
                AND tr_penjualan_h.kode_cabang = '$kode_lokasi'
                GROUP BY users.id,users.name");
        }

        $output = [
            'status'  => true,
                'message' => 'success',
                'data'    => $data_pendapatan_user
            ];
                            
        return response()->json($output, 200);
    }

    public function getDataPiutangPanel(Request $request)
    {
        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $pembelian_piutang = DB::table('tr_penjualan_h')
                    ->Join('tr_penerimaan_h','tr_penjualan_h.faktur_panel','=','tr_penerimaan_h.no_faktur')
                    ->select(
                        'tr_penjualan_h.kode_penjualan',
                        'tr_penjualan_h.tgl_penjualan',
                        'tr_penjualan_h.jenis_penjualan',
                        'tr_penjualan_h.nama_pembeli',
                        'tr_penjualan_h.no_tlp',
                        'tr_penjualan_h.termin',
                        'tr_penjualan_h.tgl_jatuh_tempo',
                        DB::raw('DATEDIFF(tr_penjualan_h.tgl_jatuh_tempo,CURRENT_DATE()) AS jatuh_tempo'),
                        'tr_penjualan_h.total_bayar',
                        'tr_penerimaan_h.subtotal'
                    )
                    ->where('tr_penjualan_h.jenis_penjualan', 'Panel')
                    ->where('tr_penjualan_h.status_bayar', 'Belum Bayar')
                    //->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                    // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
                    ->orderBy('tr_penjualan_h.tgl_jatuh_tempo', 'ASC')
                    ->get();
            }else{
                $pembelian_piutang = DB::table('tr_penjualan_h')
                        ->Join('tr_penerimaan_h','tr_penjualan_h.faktur_panel','=','tr_penerimaan_h.no_faktur')
                        ->select(
                            'tr_penjualan_h.kode_penjualan',
                            'tr_penjualan_h.tgl_penjualan',
                            'tr_penjualan_h.jenis_penjualan',
                            'tr_penjualan_h.nama_pembeli',
                            'tr_penjualan_h.no_tlp',
                            'tr_penjualan_h.termin',
                            'tr_penjualan_h.tgl_jatuh_tempo',
                            DB::raw('DATEDIFF(tr_penjualan_h.tgl_jatuh_tempo,CURRENT_DATE()) AS jatuh_tempo'),
                            'tr_penjualan_h.total_bayar',
                            'tr_penerimaan_h.subtotal'
                        )
                        ->where('tr_penjualan_h.jenis_penjualan', 'Panel')
                        ->where('tr_penjualan_h.status_bayar', 'Belum Bayar')
                        ->where('tr_penjualan_h.kode_cabang', $request->value)
                        // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
                        ->orderBy('tr_penjualan_h.tgl_jatuh_tempo', 'ASC')
                        ->get();   
            }
        }else{ //jika Admin
            $pembelian_piutang = DB::table('tr_penjualan_h')
                        ->Join('tr_penerimaan_h','tr_penjualan_h.faktur_panel','=','tr_penerimaan_h.no_faktur')
                        ->select(
                            'tr_penjualan_h.kode_penjualan',
                            'tr_penjualan_h.tgl_penjualan',
                            'tr_penjualan_h.jenis_penjualan',
                            'tr_penjualan_h.nama_pembeli',
                            'tr_penjualan_h.no_tlp',
                            'tr_penjualan_h.termin',
                            'tr_penjualan_h.tgl_jatuh_tempo',
                            DB::raw('DATEDIFF(tr_penjualan_h.tgl_jatuh_tempo,CURRENT_DATE()) AS jatuh_tempo'),
                            'tr_penjualan_h.total_bayar',
                            'tr_penerimaan_h.subtotal'
                        )
                        ->where('tr_penjualan_h.jenis_penjualan', 'Panel')
                        ->where('tr_penjualan_h.status_bayar', 'Belum Bayar')
                        ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
                        ->orderBy('tr_penjualan_h.tgl_jatuh_tempo', 'ASC')
                        ->get();
        }
        
        return response()->json([
            'data' => $pembelian_piutang
        ]);
    }

    public function getDataPembayaranPanel(Request $request)
    {
        $data_pembayaran_panel = DB::table('tr_penjualan_h')
                                ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                                ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                                ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                                ->select('tr_penjualan_h.kode_penjualan',
                                    'tr_penjualan_d.kode_produk',
                                    'm_produk.nama_produk',
                                    'm_produk.qty AS stok',
                                    'tr_penjualan_d.harga',
                                    'tr_penjualan_d.qty_kecil',
                                    'tr_penjualan_d.qty',
                                    'm_produk_unit.nama_unit',
                                    'tr_penjualan_d.diskon',
                                    'tr_penjualan_d.diskon_rp',
                                    'tr_penjualan_d.ppn',
                                    'tr_penjualan_d.ppn_rp',
                                    'tr_penjualan_d.biaya_tambahan',
                                    'tr_penjualan_d.tuslah',
                                    'tr_penjualan_d.embalase',
                                    'tr_penjualan_d.total')
                                ->where('tr_penjualan_h.kode_penjualan', $request->kode_transaksi)
                                ->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_pembayaran_panel
        ];
                                                    
        return response()->json($output, 200);
    }

    public function getDataPembayaranPanel_Detail(Request $request)
    {
        $data_pembayaran_panel_detail = DB::table('tr_penjualan_h')
                                        ->where('tr_penjualan_h.kode_penjualan', $request->kode_transaksi)
                                        ->first();
        
        return response()->json([
            'data' => $data_pembayaran_panel_detail
        ]);
    }

    public function getDataJmlPembelian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        // $pembelian_jml = DB::table('tr_pembelian_h')
        //                 ->select(DB::raw('COUNT(kode_pembelian) as jml_pembelian'))
        //                 ->where('tgl_pembelian',  $date)
        //                 ->where('kode_cabang', Auth::user()->kd_lokasi)
        //                 // ->where('id_user_input', Auth::user()->id )
        //                 ->first();

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $pembelian_jml = DB::table('tr_pembayaran_supplier')
                    ->select(DB::raw('COUNT(kode_pembayaran) as jml_pembelian'))
                    ->where('tgl_pembayaran',  $date)
                    //->where('kode_cabang', Auth::user()->kd_lokasi)
                    // ->where('id_user_input', Auth::user()->id )
                    ->first();
            }else{
                $pembelian_jml = DB::table('tr_pembayaran_supplier')
                        ->select(DB::raw('COUNT(kode_pembayaran) as jml_pembelian'))
                        ->where('tgl_pembayaran',  $date)
                        ->where('kode_cabang', $request->value)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first(); 
            }
        }else{ //jika Admin
            $pembelian_jml = DB::table('tr_pembayaran_supplier')
                        ->select(DB::raw('COUNT(kode_pembayaran) as jml_pembelian'))
                        ->where('tgl_pembayaran',  $date)
                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
        }   
                    
        return response()->json([
            'data' => $pembelian_jml
        ]);
    }

    public function getDataTtlPembelian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        // $pembelian_ttl = DB::table('tr_pembelian_h')
        //                 ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
        //                 ->select(DB::raw('SUM(tr_pembelian_d.total) as ttl_pembelian'))
        //                 ->where('tr_pembelian_h.tgl_pembelian',  $date)
        //                 ->where('tr_pembelian_h.kode_cabang', Auth::user()->kd_lokasi)
        //                 // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
        //                 ->first();

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $pembelian_ttl = DB::table('tr_pembayaran_supplier')
                    ->select(DB::raw('SUM(tr_pembayaran_supplier.total_bayar) as ttl_pembelian'))
                    ->where('tr_pembayaran_supplier.tgl_pembayaran',  $date)
                    //->where('tr_pembayaran_supplier.kode_cabang', Auth::user()->kd_lokasi)
                    // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
                    ->first();
            }else{
                $pembelian_ttl = DB::table('tr_pembayaran_supplier')
                        ->select(DB::raw('SUM(tr_pembayaran_supplier.total_bayar) as ttl_pembelian'))
                        ->where('tr_pembayaran_supplier.tgl_pembayaran',  $date)
                        ->where('tr_pembayaran_supplier.kode_cabang', $request->value)
                        // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
                        ->first();  
            }
        }else{ //jika Admin
            $pembelian_ttl = DB::table('tr_pembayaran_supplier')
                        ->select(DB::raw('SUM(tr_pembayaran_supplier.total_bayar) as ttl_pembelian'))
                        ->where('tr_pembayaran_supplier.tgl_pembayaran',  $date)
                        ->where('tr_pembayaran_supplier.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_pembelian_h.id_user_input', Auth::user()->id )
                        ->first();
        }

        return response()->json([
            'data' => $pembelian_ttl
        ]);
    }

    public function getDataJmlKunjungan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $kunjungan_jml = DB::table('m_kunjungan')
                    ->select(DB::raw('COUNT(kode_kunjungan) as jml_kunjungan'))
                    ->where('tgl_kunjungan',  $date)
                    //->where('kode_cabang', Auth::user()->kd_lokasi)
                    // ->where('id_user_input', Auth::user()->id )
                    ->first();
            }else{
                $kunjungan_jml = DB::table('m_kunjungan')
                        ->select(DB::raw('COUNT(kode_kunjungan) as jml_kunjungan'))
                        ->where('tgl_kunjungan',  $date)
                        ->where('kode_cabang', $request->value)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();   
            }
        }else{ //jika Admin
            $kunjungan_jml = DB::table('m_kunjungan')
                        ->select(DB::raw('COUNT(kode_kunjungan) as jml_kunjungan'))
                        ->where('tgl_kunjungan',  $date)
                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
        }
        
        return response()->json([
            'data' => $kunjungan_jml
        ]);
    }

    public function getDataJmlKunjunganSelesai(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $kunjungan_jml = DB::table('m_kunjungan')
                    ->select(DB::raw('COUNT(kode_kunjungan) as jml_kunjungan'))
                    ->where('tgl_kunjungan',  $date)
                    ->where('status_periksa',  1)
                    //->where('kode_cabang', Auth::user()->kd_lokasi)
                    // ->where('id_user_input', Auth::user()->id )
                    ->first();
            }else{
                $kunjungan_jml = DB::table('m_kunjungan')
                        ->select(DB::raw('COUNT(kode_kunjungan) as jml_kunjungan'))
                        ->where('tgl_kunjungan',  $date)
                        ->where('status_periksa',  1)
                        ->where('kode_cabang', $request->value)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first(); 
            }
        }else{ //jika Admin
            $kunjungan_jml = DB::table('m_kunjungan')
                        ->select(DB::raw('COUNT(kode_kunjungan) as jml_kunjungan'))
                        ->where('tgl_kunjungan',  $date)
                        ->where('status_periksa',  1)
                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('id_user_input', Auth::user()->id )
                        ->first();
        }
        
        return response()->json([
            'data' => $kunjungan_jml
        ]);
    }

    public function getDataPendapatanKunjungan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $pendapatan_klinik = DB::table('tr_pembayaran_h_klinik')
                    ->select(DB::raw('SUM(tr_pembayaran_h_klinik.total_bayar) as pendapatan_klinik'))
                    ->where('tr_pembayaran_h_klinik.tgl_invoice',  $date)
                    //->where('tr_pembayaran_h_klinik.kode_cabang', Auth::user()->kd_lokasi)
                    // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                    ->first();
            }else{
                $pendapatan_klinik = DB::table('tr_pembayaran_h_klinik')
                        ->select(DB::raw('SUM(tr_pembayaran_h_klinik.total_bayar) as pendapatan_klinik'))
                        ->where('tr_pembayaran_h_klinik.tgl_invoice',  $date)
                        ->where('tr_pembayaran_h_klinik.kode_cabang', $request->value)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
            }
        }else{ //jika Admin
            $pendapatan_klinik = DB::table('tr_pembayaran_h_klinik')
                        ->select(DB::raw('SUM(tr_pembayaran_h_klinik.total_bayar) as pendapatan_klinik'))
                        ->where('tr_pembayaran_h_klinik.tgl_invoice',  $date)
                        ->where('tr_pembayaran_h_klinik.kode_cabang', Auth::user()->kd_lokasi)
                        // ->where('tr_penjualan_h.id_user_input', Auth::user()->id )
                        ->first();
        }

        return response()->json([
            'data' => $pendapatan_klinik
        ]);
    }

    public function getDataTotalPendapatan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('Y-m-d'));
        $kode_lokasi = Auth::user()->kd_lokasi;

        if (Auth::user()->type == '1') {  //jika Super Admin
            if (!isset($request->value)) {
                $data_total_pendapatan = DB::select("SELECT DISTINCT
                        (SELECT SUM(tr_penjualan_h.total_bayar)
                        FROM tr_penjualan_h
                        WHERE tr_penjualan_h.jenis_penjualan NOT IN ('Panel')
                        AND tr_penjualan_h.tgl_penjualan = '$date') AS ttl_penjualan,
                        (SELECT
                        SUM(tr_penjualan_retur_h.total_bayar)
                        FROM tr_penjualan_retur_h
                        WHERE tr_penjualan_retur_h.tgl_retur  = '$date') AS ttl_retur,
                        (SELECT
                        SUM(tr_pembayaran_supplier.total_bayar)
                        FROM tr_pembayaran_supplier 
                        WHERE tr_pembayaran_supplier.tgl_pembayaran = '$date') AS ttl_pembayaran_supplier,
                        (SELECT
                        SUM(tr_pembayaran_h_klinik.total_bayar)
                        FROM tr_pembayaran_h_klinik
                        WHERE tr_pembayaran_h_klinik.tgl_invoice = '$date') AS ttl_pendapatan_klinik");
            }else{
                $data_total_pendapatan = DB::select("SELECT DISTINCT
                        (SELECT SUM(tr_penjualan_h.total_bayar)
                        FROM tr_penjualan_h
                        WHERE tr_penjualan_h.jenis_penjualan NOT IN ('Panel')
                        AND tr_penjualan_h.tgl_penjualan = '$date'
                        AND tr_penjualan_h.kode_cabang = '$request->value') AS ttl_penjualan,
                        (SELECT
                        SUM(tr_penjualan_retur_h.total_bayar)
                        FROM tr_penjualan_retur_h
                        WHERE tr_penjualan_retur_h.tgl_retur  = '$date'
                        AND tr_penjualan_retur_h.kode_cabang = '$request->value') AS ttl_retur,
                        (SELECT
                        SUM(tr_pembayaran_supplier.total_bayar)
                        FROM tr_pembayaran_supplier 
                        WHERE tr_pembayaran_supplier.tgl_pembayaran = '$date'
                        AND tr_pembayaran_supplier.kode_cabang = '$request->value') AS ttl_pembayaran_supplier,
                        (SELECT
                        SUM(tr_pembayaran_h_klinik.total_bayar)
                        FROM tr_pembayaran_h_klinik
                        WHERE tr_pembayaran_h_klinik.tgl_invoice = '$date'
                        AND tr_pembayaran_h_klinik.kode_cabang = '$request->value') AS ttl_pendapatan_klinik");   
            }
        }else{ //jika Admin
            $data_total_pendapatan = DB::select("SELECT DISTINCT
                        (SELECT SUM(tr_penjualan_h.total_bayar)
                        FROM tr_penjualan_h
                        WHERE tr_penjualan_h.jenis_penjualan NOT IN ('Panel')
                        AND tr_penjualan_h.tgl_penjualan = '$date'
                        AND tr_penjualan_h.kode_cabang = '$kode_lokasi') AS ttl_penjualan,
                        (SELECT
                        SUM(tr_penjualan_retur_h.total_bayar)
                        FROM tr_penjualan_retur_h
                        WHERE tr_penjualan_retur_h.tgl_retur  = '$date'
                        AND tr_penjualan_retur_h.kode_cabang = '$kode_lokasi') AS ttl_retur,
                        (SELECT
                        SUM(tr_pembayaran_supplier.total_bayar)
                        FROM tr_pembayaran_supplier 
                        WHERE tr_pembayaran_supplier.tgl_pembayaran = '$date'
                        AND tr_pembayaran_supplier.kode_cabang = '$kode_lokasi') AS ttl_pembayaran_supplier,
                        (SELECT
                        SUM(tr_pembayaran_h_klinik.total_bayar)
                        FROM tr_pembayaran_h_klinik
                        WHERE tr_pembayaran_h_klinik.tgl_invoice = '$date'
                        AND tr_pembayaran_h_klinik.kode_cabang = '$kode_lokasi') AS ttl_pendapatan_klinik");
        }

        $output = [
            'status'  => true,
                'message' => 'success',
                'data'    => $data_total_pendapatan
            ];
                            
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));

        $date = (date('dmy'));
        $getRow = DB::table('tr_pembayaran_penjualan_panel')->select(DB::raw('MAX(RIGHT(kode_pembayaran_panel,4)) as NoUrut'))
                                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                                        ->where('kode_pembayaran_panel', 'like', "%".$date."%");
        $rowCount = $getRow->count();
        
        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = "PL".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = "PL".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = "PL".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = "PL".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount + 1);
            }
        }else{
            $kode = "PL".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        DB::table('tr_pembayaran_penjualan_panel')->insert([
            'kode_pembayaran_panel' => $kode,
            'tgl_pembayaran' => Carbon::now()->format('Y-m-d'),
            'waktu_pembayaran' => $time,
            'kode_penjualan' => $request->kode_transaksi_panel,
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);

        $status_update = DB::table('tr_penjualan_h')
            ->where('tr_penjualan_h.kode_penjualan', $request->kode_transaksi_panel)
            ->update([
                    'status_bayar' => 'Sudah Bayar',
                    'pembulatan' => $request->pembulatan,
                    'total_bayar' => str_replace(",", "", $request->total_bayar),
                    'cara_bayar' => $request->cara_bayar,
                    'bank' => $request->bank,
                    'jml_bayar' => str_replace(",", "", $request->jml_bayar),
                    'kembali' => str_replace(",", "", $request->kembali)
            ]);

        $output = [
                'msg'  => 'Transaksi berhasil ditambah',
                'res'  => true,
                'type' => 'success'
        ];
        return response()->json($output, 200);
    }
    
    public function retur(Request $request)
    {
        DB::table('m_produk')
        ->where('kode_produk', $request->kode_produk_update)
        ->where('kode_cabang', $request->id_cabang_update)
        ->update([
            'status' =>  1,
            'keterangan' =>  $request->keterangan
        ]);

        $output = [
            'message'  => 'Data Berhasil Diretur',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function getDataTerlaris()
    {
        // date_default_timezone_set('Asia/Jakarta');
        // $date = (date('Y-m-d'));

        // $data_terlaris = DB::table('tr_penjualan_d')
        //                 ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
        //                 ->join('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
        //                 ->select('tr_penjualan_d.kode_produk','m_produk.nama_produk',DB::raw('SUM(tr_penjualan_d.qty_kecil) as jml_jual_terkecil'),'m_produk_unit.nama_unit')
        //                 //->where('tgl_kunjungan',  $date)
        //                 //->where('kode_cabang', Auth::user()->kd_lokasi)
        //                 // ->where('id_user_input', Auth::user()->id )
        //                 ->groupBy('tr_penjualan_d.kode_produk','m_produk.nama_produk','m_produk_unit.nama_unit')
        //                 ->orderBy('jml_jual_terkecil', 'DESC')
        //                 ->get();

        
        // return response()->json([
        //     'data' => $data_terlaris
        // ]);
    }

    public function getDataHabis()
    {
        // date_default_timezone_set('Asia/Jakarta');
        // $date = (date('Y-m-d'));

        // $data_terlaris = DB::table('m_produk')
        //                 ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.qty','m_produk.qty_min')
        //                 //->where('tgl_kunjungan',  $date)
        //                 //->where('kode_cabang', Auth::user()->kd_lokasi)
        //                 // ->where('id_user_input', Auth::user()->id )
        //                 ->where('m_produk.qty', '<=', 'm_produk.qty_min')
        //                 ->get();

        
        // return response()->json([
        //     'data' => $data_terlaris
        // ]);
    }
}
