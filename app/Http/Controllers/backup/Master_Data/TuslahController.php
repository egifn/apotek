<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Tuslah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TuslahController extends Controller
{
    public function index()
    {
    	$data_tuslah = DB::table('m_tuslah')
    				->join('users','m_tuslah.id_user_input','=','users.id')
    				->select('m_tuslah.id as id_tuslah','m_tuslah.nama_tuslah','m_tuslah.harga_tuslah','m_tuslah.id_user_input','users.name')
    				->get();

    	return view ('master_data.tuslah.index', compact('data_tuslah'));
    }

    public function getDataTuslah(Request $request)
    {
        $data_tuslah = DB::table('m_tuslah')
                        ->select('m_tuslah.id','m_tuslah.nama_tuslah','m_tuslah.harga_tuslah','m_tuslah.id_user_input','users.name')
                        ->join('users','m_tuslah.id_user_input','=','users.id');

        if (!isset($request->value)) {

        }else{
            $data_tuslah->where('m_tuslah.nama_tuslah', 'like', "%$request->value%");
        }

        $data  = $data_tuslah->get();
        $count = ($data_tuslah->count() == 0) ? 0 : $data->count();
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
    	DB::table('m_tuslah')->insert([
        	'nama_tuslah' => $request->nama_tuslah,
            'harga_tuslah' => str_replace(",", "", $request->harga_tuslah),
        	'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'msg'  => 'Data Tuslah Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataTuslahDetail(Request $request)
    {
		$data = DB::table('m_tuslah')
				->where('m_tuslah.id', $request->id)
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
        DB::table('m_tuslah')->where('id', $request->id)->update([
            'nama_tuslah' => $request->nama_tuslah,
            'harga_tuslah' => str_replace(",", "", $request->harga_tuslah),
        ]);

        $output = [
            'message'  => 'Data Tuslah Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
	{
		$cari_tuslah = $request->input('cari_tuslah');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

		if($tombol_excel == 'excel'){
            if($cari_tuslah != ''){
				$data_tuslah_excel = DB::table('m_tuslah')
						->where('m_tuslah.nama_tuslah', 'like', "%$cari_tuslah%")
                        ->get();
            }else{
                $data_tuslah_excel =  DB::table('m_tuslah')
                        ->get();
            }
            return view ('master_data.tuslah.view', compact('data_tuslah_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari_tuslah != ''){
				$data_tuslah_pdf =  DB::table('m_tuslah')
					->where('m_tuslah.nama_tuslah', 'like', "%$cari_tuslah%")
					->get();
            }else{
				$data_tuslah_pdf = DB::table('m_tuslah')
					->get();
            }
            $pdf = PDF::loadview('master_data.tuslah.pdf', compact('data_tuslah_pdf'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
	}
}
