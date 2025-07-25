<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    public function index()
    {
        return view ('laporan.stok.index');
    }

    public function getDataStok(Request $request)
    {
        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_stok = DB::table('m_produk')
                ->join('m_produk_unit_varian', function($join)
                {
                    $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                    $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                }) 
                ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                ->select('m_produk.id','m_produk.kode_produk','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.nama_produk','m_produk.qty','m_produk_unit_varian.harga_beli','m_supplier.nama_supplier','m_produk.tgl_kadaluarsa');

            if(!isset($request->value)) {

            }else{
                $data_stok->where('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%");
            }
        }else{ //jika Admin
            $data_stok = DB::table('m_produk')
                ->join('m_produk_unit_varian', function($join)
                {
                    $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                    $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                }) 
                ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                ->select('m_produk.id','m_produk.kode_produk','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.nama_produk','m_produk.qty','m_produk_unit_varian.harga_beli','m_supplier.nama_supplier','m_produk.tgl_kadaluarsa');

            if(!isset($request->value)) {
                $data_stok->where('m_produk_unit_varian.kode_cabang', Auth::user()->kd_lokasi);
            }else{
                $data_stok->where('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                        ->where('m_produk_unit_varian.kode_cabang', Auth::user()->kd_lokasi);
            }
        }
        
        $data  = $data_stok->get();
        $count = ($data_stok->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function view(Request $request){
        $cari = $request->input('cari');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        $date = (date('d M Y'));

        if($tombol_excel == 'excel'){
            if($cari != ''){
                $data_stok_excel =  DB::table('m_produk')
                            ->join('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                            ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                            ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                            ->select('m_produk.id','m_produk.kode_produk','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.nama_produk','m_produk.qty','m_produk_unit_varian.harga_beli','m_supplier.nama_supplier','m_produk.tgl_kadaluarsa')
                            ->where('m_produk.nama_produk', 'like', "%$cari%")
                            ->orWhere('m_cabang.nama_cabang', 'like', "%$cari%")
                            ->get();

                $data_stok_total_excel =  DB::table('m_produk')
                            ->join('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                            ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                            ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                            ->select(DB::raw('sum(m_produk.qty*m_produk_unit_varian.harga_beli) as jumlah'))
                            ->where('m_produk.nama_produk', 'like', "%$cari%")
                            ->orWhere('m_cabang.nama_cabang', 'like', "%$cari%")
                            ->first();
            }else{
                $data_stok_excel =  DB::table('m_produk')
                            ->join('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                            ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                            ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                            ->select('m_produk.id','m_produk.kode_produk','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.nama_produk','m_produk.qty','m_produk_unit_varian.harga_beli','m_supplier.nama_supplier','m_produk.tgl_kadaluarsa')
                            ->get();

                $data_stok_total_excel =  DB::table('m_produk')
                            ->join('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                            ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                            ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                            ->select(DB::raw('sum(m_produk.qty*m_produk_unit_varian.harga_beli) as jumlah'))
                            ->first();
            }
            return view ('laporan.stok.view', compact('data_stok_excel','date','data_stok_total_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari != ''){
                $data_stok_pdf =  DB::table('m_produk')
                    ->join('m_produk_unit_varian', function($join)
                    {
                        $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                        $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                    }) 
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->select('m_produk.id','m_produk.kode_produk','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.nama_produk','m_produk.qty','m_produk_unit_varian.harga_beli','m_supplier.nama_supplier','m_produk.tgl_kadaluarsa')
                    ->where('m_produk.nama_produk', 'like', "%$cari%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$cari%")
                    ->get();
                
                $data_stok_total_pdf =  DB::table('m_produk')
                    ->join('m_produk_unit_varian', function($join)
                    {
                        $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                        $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                    }) 
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->select(DB::raw('sum(m_produk.qty*m_produk_unit_varian.harga_beli) as jumlah'))
                    ->where('m_produk.nama_produk', 'like', "%$cari%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$cari%")
                    ->first();
            }else{
                $data_stok_pdf =  DB::table('m_produk')
                    ->join('m_produk_unit_varian', function($join)
                    {
                        $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                        $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                    }) 
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->select('m_produk.id','m_produk.kode_produk','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.nama_produk','m_produk.qty','m_produk_unit_varian.harga_beli','m_supplier.nama_supplier','m_produk.tgl_kadaluarsa')
                    ->get();

                $data_stok_total_pdf =  DB::table('m_produk')
                    ->join('m_produk_unit_varian', function($join)
                    {
                        $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                        $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                    }) 
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->select(DB::raw('sum(m_produk.qty*m_produk_unit_varian.harga_beli) as jumlah'))
                    ->first();
            }
            $pdf = PDF::loadview('laporan.stok.pdf', compact('data_stok_pdf','date','data_stok_total_pdf'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
    }
}
