<?php

namespace App\Http\Controllers\Retur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class RetPembelianController extends Controller
{
    public function index()
    {
        return view ('retur_pembelian.index');
    }

    public function getDataReturPembelian(Request $request)
    {
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_retur_penjualan = DB::table('tr_pembelian_retur_h')
                    ->join('tr_pembelian_h','tr_pembelian_retur_h.kode_pembelian','=','tr_pembelian_h.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_retur_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
    	 			->join('m_cabang','tr_pembelian_retur_h.kode_cabang','=','m_cabang.id')
    	 			->join('users','tr_pembelian_retur_h.id_user_input','=','users.id')
                    ->select(
                        'tr_pembelian_retur_h.kode_retur_pembelian',
                        'tr_pembelian_retur_h.tgl_retur_pembelian',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_retur_h.kode_pembelian',
                        'tr_pembelian_retur_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_retur_h.subtotal_ret',
                        'users.name',
                        'm_cabang.nama_cabang'
                    )
                    ->WhereBetween('tr_pembelian_retur_h.tgl_retur_pembelian',[$date_start,$date_end]);
        }else{ //jika Admin
            $data_retur_penjualan = DB::table('tr_pembelian_retur_h')
                    ->join('tr_pembelian_h','tr_pembelian_retur_h.kode_pembelian','=','tr_pembelian_h.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_retur_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
    	 			->join('m_cabang','tr_pembelian_retur_h.kode_cabang','=','m_cabang.id')
    	 			->join('users','tr_pembelian_retur_h.id_user_input','=','users.id')
                    ->select(
                        'tr_pembelian_retur_h.kode_retur_pembelian',
                        'tr_pembelian_retur_h.tgl_retur_pembelian',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_retur_h.kode_pembelian',
                        'tr_pembelian_retur_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_retur_h.subtotal_ret',
                        'users.name',
                        'm_cabang.nama_cabang'
                    )
                    ->where('tr_pembelian_retur_h.kode_cabang', Auth::user()->kd_lokasi)
                    ->WhereBetween('tr_pembelian_retur_h.tgl_retur_pembelian',[$date_start,$date_end]);
        }

        $data = $data_retur_penjualan->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function cari(Request $request)
    {
        $date = explode(' - ' ,$request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('tr_pembelian_retur_h')
                    ->join('tr_pembelian_h','tr_pembelian_retur_h.kode_pembelian','=','tr_pembelian_h.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_retur_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
    	 			->join('m_cabang','tr_pembelian_retur_h.kode_cabang','=','m_cabang.id')
    	 			->join('users','tr_pembelian_retur_h.id_user_input','=','users.id')
                    ->select(
                        'tr_pembelian_retur_h.kode_retur_pembelian',
                        'tr_pembelian_retur_h.tgl_retur_pembelian',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_retur_h.kode_pembelian',
                        'tr_pembelian_retur_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_retur_h.subtotal_ret',
                        'users.name',
                        'm_cabang.nama_cabang'
                    )
                    ->WhereBetween('tr_pembelian_retur_h.tgl_retur_pembelian',[$date_start,$date_end]);
        }else{ //jika Admin
            $query = DB::table('tr_pembelian_retur_h')
                    ->join('tr_pembelian_h','tr_pembelian_retur_h.kode_pembelian','=','tr_pembelian_h.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_retur_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
    	 			->join('m_cabang','tr_pembelian_retur_h.kode_cabang','=','m_cabang.id')
    	 			->join('users','tr_pembelian_retur_h.id_user_input','=','users.id')
                    ->select(
                        'tr_pembelian_retur_h.kode_retur_pembelian',
                        'tr_pembelian_retur_h.tgl_retur_pembelian',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_retur_h.kode_pembelian',
                        'tr_pembelian_retur_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_retur_h.subtotal_ret',
                        'users.name',
                        'm_cabang.nama_cabang'
                    )
                    ->where('tr_pembelian_retur_h.kode_cabang', Auth::user()->kd_lokasi)
                    ->WhereBetween('tr_pembelian_retur_h.tgl_retur_pembelian',[$date_start,$date_end]);
        }

        $data  = $query->get();

        $count = ($query->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function create(Request $request){

        return view ('retur_pembelian.create');
    }

    public function getPembelianModal(Request $request)
    {   
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));
        
        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_pembelian = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('tr_penerimaan_d','tr_penerimaan_h.kode_penerimaan','=','tr_penerimaan_d.kode_penerimaan')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','m_supplier.id')
                    ->select('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier',
                    DB::raw('sum(tr_pembelian_d.total) as total'))
                    ->groupBy('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier')    
                    ->orderBy('tr_pembelian_h.tgl_pembelian', 'DESC')
                    ->limit(10);
            if (!isset($request->value)) {

            }else{
                $data_pembelian->where('tr_penerimaan_h.no_faktur', 'like', "%$request->value%");
            }
        }else{ //jika Admin
            $data_pembelian = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('tr_penerimaan_d','tr_penerimaan_h.kode_penerimaan','=','tr_penerimaan_d.kode_penerimaan')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','m_supplier.id')
                    ->select('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier',
                    DB::raw('sum(tr_pembelian_d.total) as total'))
                    ->groupBy('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier')    
                    ->orderBy('tr_pembelian_h.tgl_pembelian', 'DESC')
                    ->limit(10);
            if (!isset($request->value)) {
                $data_pembelian
                        ->where('tr_pembelian_h.kode_cabang', Auth::user()->kd_lokasi);
            }else{
                $data_pembelian
                        ->where('tr_pembelian_h.kode_cabang', Auth::user()->kd_lokasi)
                        ->where('tr_penerimaan_h.no_faktur', 'like', "%$request->value%");
            }
        }

        $data_pembelian = $data_pembelian->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_pembelian
        ];
        return response()->json($output, 200);
    }

    public function getPembelianModalBatch(Request $request)
    {   
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));
        
        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_pembelian = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('tr_penerimaan_d','tr_penerimaan_h.kode_penerimaan','=','tr_penerimaan_d.kode_penerimaan')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','m_supplier.id')
                    ->select('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier',
                    DB::raw('sum(tr_pembelian_d.total) as total'))
                    ->groupBy('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier')    
                    ->orderBy('tr_pembelian_h.tgl_pembelian', 'DESC')
                    ->limit(10);
            if (!isset($request->value)) {

            }else{
                $data_pembelian->where('tr_penerimaan_h.no_faktur', 'like', "%$request->value%");
            }
        }else{ //jika Admin
            $data_pembelian = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
                    ->join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
                    ->join('tr_penerimaan_d','tr_penerimaan_h.kode_penerimaan','=','tr_penerimaan_d.kode_penerimaan')
                    ->join('m_supplier','tr_pembelian_h.kode_supplier','m_supplier.id')
                    ->select('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier',
                    DB::raw('sum(tr_pembelian_d.total) as total'))
                    ->groupBy('tr_penerimaan_d.no_batch','tr_penerimaan_h.no_faktur','tr_pembelian_h.kode_pembelian','tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_surat_pesanan','tr_pembelian_h.pembelian','tr_pembelian_h.jenis_transaksi','m_supplier.nama_supplier')    
                    ->orderBy('tr_pembelian_h.tgl_pembelian', 'DESC')
                    ->limit(10);
            if (!isset($request->value)) {
                $data_pembelian
                        ->where('tr_pembelian_h.kode_cabang', Auth::user()->kd_lokasi);
            }else{
                $data_pembelian
                        ->where('tr_pembelian_h.kode_cabang', Auth::user()->kd_lokasi)
                        ->where('tr_penerimaan_d.no_batch', 'like', "%$request->value%");
            }
        }

        $data_pembelian = $data_pembelian->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_pembelian
        ];
        return response()->json($output, 200);
    }

    public function getPembeliandetail(Request $request)
    {
        // $data_pembelian_detail = DB::table('tr_pembelian_h')
        //             ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
        //             ->join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
        //             ->join('m_produk','tr_pembelian_d.kode_produk','=','m_produk.kode_produk')
        //             ->join('m_produk_unit','tr_pembelian_d.id_unit','=','m_produk_unit.id')
        //             ->select('tr_pembelian_d.kode_produk','m_produk.nama_produk','tr_pembelian_d.harga','tr_pembelian_d.qty_beli','tr_pembelian_d.id_unit','m_produk_unit.nama_unit','tr_pembelian_d.diskon_item','tr_pembelian_d.diskon_item_rp',
        //                     'tr_pembelian_d.ppn_item','tr_pembelian_d.ppn_item_rp','tr_pembelian_d.total')
        //             ->where('tr_penerimaan_h.no_faktur', $request->value); 
        
        $data_pembelian_detail = DB::table('tr_penerimaan_h')
                    ->join('tr_penerimaan_d','tr_penerimaan_h.kode_penerimaan','=','tr_penerimaan_d.kode_penerimaan')
                    ->join('m_produk','tr_penerimaan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penerimaan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penerimaan_d.kode_produk','m_produk.nama_produk','tr_penerimaan_d.harga_beli as harga','tr_penerimaan_d.jml_beli','tr_penerimaan_d.jml_terima as qty_beli','tr_penerimaan_d.id_produk_unit as id_unit',
                            'm_produk_unit.nama_unit','tr_penerimaan_d.diskon_persen as diskon_item','tr_penerimaan_d.diskon_rp as diskon_item_rp',
                            'tr_penerimaan_d.ppn_persen as ppn_item','tr_penerimaan_d.ppn_rp as ppn_item_rp','tr_penerimaan_d.subtotal as total')
                    ->where('tr_penerimaan_h.no_faktur', $request->value); 
                    

        $data_pembelian_detail = $data_pembelian_detail->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_pembelian_detail
        ];
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s')); 

        $date = (date('dmy'));
        $getRow = DB::table('tr_pembelian_retur_h')->select(DB::raw('MAX(RIGHT(kode_retur_pembelian,4)) as NoUrut'))
                                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                                        ->where('kode_retur_pembelian', 'like', "%".$date."%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = "RB".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = "RB".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = "RB".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = "RB".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount + 1);
            }
        }else{
            $kode = "RB".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        // Header //
        DB::table('tr_pembelian_retur_h')->insert([
            'kode_retur_pembelian' => $kode,
            'tgl_retur_pembelian' => Carbon::now()->format('Y-m-d'),
            'waktu_retur_pembelian' => $time,
            'kode_pembelian' => $request->no_sp,
            'tgl_pembelian' => $request->tgl_sp,
            'subtotal_ret' =>  str_replace(",", "", $request->subtotal_ret),
            'jml_bayar_ret' => str_replace(",", "", $request->jml_bayar_ret),
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);
        // End Header //

        // Detail //
        $kode_produk = $request->kode_produk;
        $harga_beli = str_replace(",", "", $request->harga_beli);
        $jml_beli =  $request->jml_beli;
        $jml_retur = $request->jml_retur;
        $id_produk_unit = $request->id_produk_unit;
        $diskon_persen = $request->diskon_persen;
        $diskon_rupiah = str_replace(",", "", $request->diskon_rupiah);
        $ppn_persen = $request->ppn_persen;
        $ppn_rupiah = str_replace(",", "", $request->ppn_rupiah);
        $subtotal = str_replace(",", "", $request->subtotal);
        // End Detail //

        for ($i=0; $i < count((array)$kode_produk); $i++) { 
            $stok_varian = DB::table('m_produk_unit_varian')
            ->select('m_produk_unit_varian.qty')
            ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
            ->where ('m_produk_unit_varian.id_produk_unit', $id_produk_unit[$i])->first();

            DB::table('tr_pembelian_retur_d')->insert([
                'kode_retur_pembelian' => $kode,
                'kode_produk' => $kode_produk[$i],
                'harga_beli' => $harga_beli[$i],
                'jml_beli' => $jml_beli[$i],
                'jml_retur' => $jml_retur[$i],
                'id_produk_unit' => $id_produk_unit[$i],
                'diskon_persen' => $diskon_persen[$i],
                'diskon_rupiah' => $diskon_rupiah[$i],
                'ppn_persen' => $ppn_persen[$i],
                'ppn_rupiah' => $ppn_rupiah[$i],
                'subtotal' =>  $subtotal[$i]
            ]);

            $stok = DB::table('m_produk')
                ->select('m_produk.qty')
                ->where('m_produk.kode_produk', $kode_produk[$i])->first();

            if($jml_retur[$i] != 0){
                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $kode_produk[$i],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Retur Pembelian dengan No Retur: '.$kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => 0,
                    'stok_keluar' => $stok_varian->qty * $jml_retur[$i],
                    'stok_sisa' => $stok->qty - ($stok_varian->qty * $jml_retur[$i]),
                    'type' => 'Retur Pembelian',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
                // End pencatatan ke Stok_in_out (keluar masuk barang) //

                // untuk update //
                $stok_update = DB::table('m_produk')
                    ->where('m_produk.kode_produk', $kode_produk[$i])
                    ->update([
                        'qty' => $stok->qty - ($stok_varian->qty * $jml_retur[$i])
                    ]);
                // end untuk update //
            }
        }

        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
