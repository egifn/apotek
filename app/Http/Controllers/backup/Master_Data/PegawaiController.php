<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiController extends Controller
{
    public function index()
    {
    	return view ('master_data.pegawai.index');
    }

	public function getDataPegawai(Request $request)
	{
		$data_pegawai = DB::table('m_pegawai');

		if (!isset($request->value)) {

        }else{
            $data_pegawai->where('m_pegawai.nama_pegawai', 'like', "%$request->value%");
        }

        $data  = $data_pegawai->get();
        $count = ($data_pegawai->count() == 0) ? 0 : $data->count();
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

        $getRow = DB::table('m_pegawai')->select(DB::raw('COUNT(left(kode_pegawai,1)) as urut'))
                                        ->where('jk', $request->jk)
                                        ->first();

        $kode_pegawai = $request->jk.''."0001";

        if($getRow->urut > 0) {
        	if($getRow->urut < 9){
        		$kode_pegawai = $request->jk.''."000".($getRow->urut + 1);
        	}elseif($getRow->urut < 99){
        		$kode_pegawai = $request->jk.''."00".($getRow->urut + 1);
        	}elseif($getRow->urut < 999){
        		$kode_pegawai = $request->jk.''."0".($getRow->urut + 1);
        	}elseif($getRow->urut < 9999){
        		$kode_pegawai = $request->jk.''.($getRow->urut + 1);
        	}
        }

        DB::table('m_pegawai')->insert([
        	'kode_pegawai' => $kode_pegawai,
			'nik_pegawai' => $request->nik,
        	'nama_pegawai' => $request->nama,
        	'jk' => $request->jk,
        	'alamat' => $request->alamat,
        	'tlp' => $request->tlp,
        	'email' => $request->email,
        	'jabatan' => $request->jabatan,
        	'status_pegawai' => 'Aktif',
        	'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'msg'  => 'Data Unit Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

	public function getDataPegawaiDetail(Request $request)
    {
		$data = DB::table('m_pegawai')
				->where('m_pegawai.kode_pegawai', $request->id)
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
        DB::table('m_pegawai')->where('kode_pegawai', $request->id)->update([
            'nik_pegawai' =>  $request->nik,
            'nama_pegawai' =>  $request->nama,
            'jk' =>  $request->jk,
			'alamat' => $request->alamat,
			'tlp' => $request->tlp,
			'email' => $request->email,
			'jabatan' => $request->jabatan,
			'status_pegawai' => $request->status
        ]);

        $output = [
            'message'  => 'Data Unit Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

	public function view(Request $request)
	{
		$cari_pegawai = $request->input('cari_pegawai');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

		if($tombol_excel == 'excel'){
            if($cari_pegawai != ''){
				$data_pegawai_excel = DB::table('m_pegawai')
						->where('m_pegawai.nama_pegawai', 'like', "%$cari_pegawai%")
                        ->get();
            }else{
                $data_pegawai_excel =  DB::table('m_pegawai')
                        ->get();
            }
            return view ('master_data.pegawai.view', compact('data_pegawai_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari_pegawai != ''){
				$data_pegawai_pdf =  DB::table('m_pegawai')
					->where('m_pegawai.nama_pegawai', 'like', "%$cari_pegawai%")
					->get();
            }else{
				$data_pegawai_pdf = DB::table('m_pegawai')
					->get();
            }
            $pdf = PDF::loadview('master_data.pegawai.pdf', compact('data_pegawai_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
	}
}
