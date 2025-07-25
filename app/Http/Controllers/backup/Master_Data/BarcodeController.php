<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\carbon;
use App\StokOpname_H;
use App\StokOpname_D;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
{
    public function index()
    {
        return view ('master_data.barcode_scan.index');
    }

    public function getProdukDetail($barcode)
    {
        $product = DB::table('m_produk')
				->where('m_produk.barcode', $barcode)
                ->first();

        if ($product) {
            return response()->json($product);
        } else {
            return response()->json([], 404);
        }
    }
    

    public function update(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s')); 

        $date = (date('dmy'));
        $getRow = DB::table('stokopname_h')->select(DB::raw('MAX(RIGHT(kode_opname,4)) as NoUrut'))
                                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                                        ->where('kode_opname', 'like', "%".$date."%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount);
            } else if ($rowCount < 99) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount);
            } else if ($rowCount < 999) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount);
            } else if ($rowCount < 9999) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount);
            }
        }else{
            $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        $getRowDate = DB::table('stokopname_h')->select('kode_opname')
                                        ->where('tgl_opname', Carbon::now()->format('Y-m-d'));
        $rowCountDate = $getRowDate->count();
        if ($rowCount > 0) {
            
        }else{
            stokopname_h::create([
                'kode_opname' => $kode,
                'tgl_opname' => Carbon::now()->format('Y-m-d'),
                'waktu_opname' =>$time,
                'keterangan' => 'Stokopname: '.Carbon::now()->format('Y-m-d'),
                'id_user_input' => Auth::user()->id,
                'kode_cabang' => Auth::user()->kd_lokasi
            ]);
        }

        stokopname_d::create([
            'kode_opname' => $kode,
            'kode_produk' => $request->kode_produk,
            'jml_sistem' =>$request->jml_sistem,
            'jml_fisik' => $request->jml_fisik,
            'selisih' => ($request->jml_sistem) - ($request->jml_fisik),
        ]);

           
        $stok = DB::table('m_produk')
                ->select('m_produk.qty')
                ->where('m_produk.kode_produk', $request->kode_produk)->first();

        // pencatatan ke Stok_in_out (keluar masuk barang) //
            if($stok->qty < $request->jml_fisik){
                DB::table('stok_in_out')->insert([
                    'id_produk' => $request->kode_produk,
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Proses Stokopname: '.$kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => $request->jml_fisik - $stok->qty,
                    'stok_keluar' => 0,
                    'stok_sisa' => $request->jml_fisik,
                    'type' => 'stokopname',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
            }elseif($stok->qty > $request->jml_fisik){
                DB::table('stok_in_out')->insert([
                    'id_produk' => $request->kode_produk,
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Proses Stokopname: '.$kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => 0,
                    'stok_keluar' => $stok->qty - $request->jml_fisik,
                    'stok_sisa' => $request->jml_fisik,
                    'type' => 'stokopname',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
            }
            // pencatatan ke Stok_in_out (keluar masuk barang) //

            $stock = DB::table('m_produk')
            ->select('m_produk.qty')
            ->Where('m_produk.kode_produk', $request->jml_fisik)
            ->update([
                'qty' => $request->jml_fisik
            ]);

        $output = [
            'message'  => 'Data Unit Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }
}
