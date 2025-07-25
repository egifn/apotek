<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Jasa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class JasaController extends Controller
{
    public function index()
    {
    	$data_jasa = DB::table('m_jasa_pelayanan')
    				->join('users','m_jasa_pelayanan.id_user_input','=','users.id')
    				->get();

    	return view ('master_data.jasa.index', compact('data_jasa'));
    }

    public function getDataJasa(Request $request)
    {
        $data_jasa = DB::table('m_jasa_pelayanan')
                        ->select('m_jasa_pelayanan.id','m_jasa_pelayanan.kode_jasa_p','m_jasa_pelayanan.nama_jasa_p','m_jasa_pelayanan.harga','m_jasa_pelayanan.id_user_input','users.name')
                        ->join('users','m_jasa_pelayanan.id_user_input','=','users.id');

        if (!isset($request->value)) {

        }else{
            $data_jasa->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$request->value%");
        }

        $data  = $data_jasa->get();
        $count = ($data_jasa->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);

    }



    public function store(Request $request)
    {
        $getRow = Jasa::orderBy('kode_jasa_p', 'ASC')->get();
        $rowCount = $getRow->count();

        $kode = "JP00000001";

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = "JP0000000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = "JP000000".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = "JP00000".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = "JP0000".''.($rowCount + 1);
            } else if ($rowCount < 99999) {
                    $kode = "JP000".''.($rowCount + 1);
            } else if ($rowCount < 999999){
                    $kode = 'JP00'.($rowCount + 1);
            } else if ($rowCount < 9999999){
                    $kode = 'JP0'.($rowCount + 1);
            } else if ($rowCount < 99999999){
                    $kode = 'JP'.($rowCount + 1);
            }
        } 

        DB::table('m_jasa_pelayanan')->insert([
                'kode_jasa_p' => $kode,
        	'nama_jasa_p' => $request->nama_jasa_p,
                'harga' =>str_replace(",", "", $request->harga),
        	'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'msg'  => 'Data Jasa Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataJasaDetail(Request $request)
    {
		$data = DB::table('m_jasa_pelayanan')
				->where('m_jasa_pelayanan.id', $request->id)
                ->first();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        
        return response()->json($output, 200);
    }

    public function update(Request $request)
    {
        DB::table('m_jasa_pelayanan')->where('id', $request->id)->update([
            'nama_jasa_p' => $request->nama_jasa,
            'harga' => str_replace(",", "", $request->harga),
        ]);

        $output = [
            'message'  => 'Data Jasa Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {       
	$cari_jasa = $request->input('cari_jasa');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

	if($tombol_excel == 'excel'){
           if($cari_jasa != ''){
			$data_jasa_excel = DB::table('m_jasa_pelayanan')
				->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$cari_jasa%")
                                ->get();
           }else{
                        $data_jasa_excel =  DB::table('m_jasa_pelayanan')
                        ->get();
           }
           return view ('master_data.jasa.view', compact('data_jasa_excel'));
        }elseif($tombol_pdf == 'pdf'){
          if($cari_jasa != ''){
			$data_jasa_pdf =  DB::table('m_jasa_pelayanan')
					->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$cari_jasa%")
					->get();
           }else{
			$data_jasa_pdf = DB::table('m_jasa_pelayanan')
					->get();
           }
           $pdf = PDF::loadview('master_data.jasa.pdf', compact('data_jasa_pdf'))->setPaper('a4', 'portrait');
           return $pdf->stream();
        }
    }
}
