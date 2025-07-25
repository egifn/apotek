<?php

namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\carbon;
use App\StokOpname_H;
use App\StokOpname_D;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('d-m-Y'));
        
        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_obat = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
    				->join('m_produk_unit','m_produk.id_unit','=','m_produk_unit.id')
    				->join('users','m_produk.id_user_input','=','users.id')
    				->get();
        }else{ //jika Admin
            $data_obat = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
    				->join('m_produk_unit','m_produk.id_unit','=','m_produk_unit.id')
    				->join('users','m_produk.id_user_input','=','users.id')
                    ->where('m_produk.kode_cabang', Auth::user()->kd_lokasi)
    				->get();
        }

    	return view ('stok_opname.index', compact('data_obat','date'));
    }

    public function store(Request $request)
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
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount + 1);
            }
        }else{
            $kode = "SON".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        stokopname_h::create([
            'kode_opname' => $kode,
            'tgl_opname' => Carbon::now()->format('Y-m-d'),
            'waktu_opname' =>$time,
            'keterangan' => $request->get('keterangan'),
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);

        $datas = [];
        foreach ($request->input('kode_produk') as $key => $value) {
           
        }
        
        $validator = Validator::make($request->all(), $datas);
       
        foreach ($request->input("kode_produk") as $key => $value) {
            $data = new stokopname_d;

            $data->kode_opname = $kode;
            $data->kode_produk = $request->get("kode_produk")[$key];
            $data->jml_sistem = $request->get("qty_sistem")[$key];
            $data->jml_fisik = $request->get("qty_fisik")[$key];
            $data->selisih = $request->get("selisih")[$key];
            $data->save();

            $stok = DB::table('m_produk')
                ->select('m_produk.qty')
                ->where('m_produk.kode_produk', $request->get("kode_produk")[$key])->first();

            // pencatatan ke Stok_in_out (keluar masuk barang) //
            if($stok->qty < $request->get("qty_fisik")[$key]){
                DB::table('stok_in_out')->insert([
                    'id_produk' => $request->get("kode_produk")[$key],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Proses Stokopname: '.$kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => $request->get("qty_fisik")[$key] - $stok->qty,
                    'stok_keluar' => 0,
                    'stok_sisa' => $request->get("qty_fisik")[$key],
                    'type' => 'stokopname',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
            }elseif($stok->qty > $request->get("qty_fisik")[$key]){
                DB::table('stok_in_out')->insert([
                    'id_produk' => $request->get("kode_produk")[$key],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Proses Stokopname: '.$kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => 0,
                    'stok_keluar' => $stok->qty - $request->get("qty_fisik")[$key],
                    'stok_sisa' => $request->get("qty_fisik")[$key],
                    'type' => 'stokopname',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
            }
            // pencatatan ke Stok_in_out (keluar masuk barang) //

            $stock = DB::table('m_produk')
            ->select('m_produk.qty')
            ->Where('m_produk.kode_produk', $request->get("kode_produk")[$key])
            ->update([
                'qty' => $request->get("qty_fisik")[$key]
            ]);

        }
        

        // alert()->success('Success.','Update Success');
        return redirect()->route('stok_opname.index');
    }
}
