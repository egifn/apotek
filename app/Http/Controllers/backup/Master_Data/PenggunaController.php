<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggunaController extends Controller
{
    public function index()
    {
    	$data_cabang = DB::table('m_cabang')
    					->orderBy('kode_cabang', 'ASC')
    					->get();

    	$data_level = DB::table('users_type')
    					->orderBy('id', 'ASC')
    					->get();

    	return view ('master_data.pengguna.index', compact('data_cabang','data_level'));
    }

	public function getDataPengguna(Request $request)
    {
		$data_pengguna = DB::table('users')
    					->join('m_cabang','users.kd_lokasi','=','m_cabang.kode_cabang')
    					->join('users_type','users.type','=','users_type.id')
						->select('users.id as id_pengguna','users.name','users.username','users.email','users.kd_lokasi','m_cabang.nama_cabang','users.type','users_type.nama','users.status_user')
                        ->orderBy('users.status_user', 'ASC');

        if (!isset($request->value)) {

        }else{
            $data_pengguna->where('users.name', 'like', "%$request->value%");
        }

        $data  = $data_pengguna->get();
        $count = ($data_pengguna->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function getDataPenggunaModal(Request $request)
    {
        $data_pegawai = DB::table('m_pegawai')
                    ->select('m_pegawai.id','m_pegawai.kode_pegawai','m_pegawai.nama_pegawai','m_pegawai.jabatan');   
                    //->limit(5);
        if (!isset($request->value)) {

        }else{
            $data_pegawai->where('m_pegawai.nama_pegawai', 'like', "%$request->value%");
        }

        $data_pegawai = $data_pegawai->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_pegawai
        ];
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
		DB::table('users')->insert([
        	'name' => $request->name,
        	'username' => $request->username,
        	'email' => $request->email,
        	'email_verified_at' => '',
        	'password' => Hash::make($request->password),
        	'remember_token' => '',
        	'kd_lokasi' => $request->lokasi,
        	'type' => $request->level,
        	'status_user' => 'Aktif'
        ]);

        $output = [
            'msg'  => 'Data Unit Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

	public function getDataPenggunaDetail(Request $request)
    {
		$data = DB::table('users')
				->join('m_cabang','users.kd_lokasi','=','m_cabang.kode_cabang')
				->join('users_type','users.type','=','users_type.id')
				->where('users.id', $request->id)
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
        DB::table('users')->where('id', $request->id)->update([
            'name' =>  $request->name,
            'username' =>  $request->username,
            'email' =>  $request->email,
			'kd_lokasi' => $request->lokasi,
			'type' => $request->level,
			'status_user' => $request->status
        ]);

        $output = [
            'message'  => 'Data Unit Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

	public function view(Request $request)
    {
        $cari_pengguna = $request->input('cari_pengguna');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        if($tombol_excel == 'excel'){
            if($cari_pengguna != ''){
				$data_pengguna_excel = DB::table('users')
    					->join('m_cabang','users.kd_lokasi','=','m_cabang.kode_cabang')
    					->join('users_type','users.type','=','users_type.id')
						->select('users.id as id_pengguna','users.name','users.username','users.email','users.kd_lokasi','m_cabang.nama_cabang','users.type','users_type.nama','users.status_user')
						->where('users.name', 'like', "%$cari_pengguna%")
                        ->get();
            }else{
                $data_pengguna_excel = DB::table('users')
    					->join('m_cabang','users.kd_lokasi','=','m_cabang.kode_cabang')
    					->join('users_type','users.type','=','users_type.id')
						->select('users.id as id_pengguna','users.name','users.username','users.email','users.kd_lokasi','m_cabang.nama_cabang','users.type','users_type.nama','users.status_user')
                        ->get();
            }
            return view ('master_data.pengguna.view', compact('data_pengguna_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari_pengguna != ''){
				$data_pengguna_pdf = DB::table('users')
					->join('m_cabang','users.kd_lokasi','=','m_cabang.kode_cabang')
					->join('users_type','users.type','=','users_type.id')
					->select('users.id as id_pengguna','users.name','users.username','users.email','users.kd_lokasi','m_cabang.nama_cabang','users.type','users_type.nama','users.status_user')
					->where('users.name', 'like', "%$cari_pengguna%")
					->get();
            }else{
				$data_pengguna_pdf = DB::table('users')
					->join('m_cabang','users.kd_lokasi','=','m_cabang.kode_cabang')
					->join('users_type','users.type','=','users_type.id')
					->select('users.id as id_pengguna','users.name','users.username','users.email','users.kd_lokasi','m_cabang.nama_cabang','users.type','users_type.nama','users.status_user')
					->get();
            }
            $pdf = PDF::loadview('master_data.pengguna.pdf', compact('data_pengguna_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
    }
}
