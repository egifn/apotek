<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Apotek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ApotekController extends Controller
{
    public function index()
    {
    	return view ('master_data.apotek.index');
    }

    public function getDataApotek(Request $request)
    {
        $data_apotek = DB::table('m_cabang')
    					->join('users','m_cabang.id_user_input','=','users.id');

        if (!isset($request->value)) {

        }else{
            $data_apotek->where('m_cabang.nama_cabang', 'like', "%$request->value%");
        }

        $data  = $data_apotek->get();
        $count = ($data_apotek->count() == 0) ? 0 : $data->count();
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
        $getRow = Apotek::orderBy('kode_cabang', 'ASC')->get();
        $rowCount = $getRow->count();

        $kode = "01";

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "0".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = ($rowCount + 1);
            }
        } 

        DB::table('m_cabang')->insert([
            'kode_cabang' => $kode,
            'nama_cabang' => $request->nama_apotek,
            'alamat' => $request->alamat,
            'tlp' => $request->tlp,
            'id_user_input' => Auth::user()->id,
        ]);

        $output = [
            'msg'  => 'Data Unit Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataApotekDetail(Request $request)
    {
        $data = DB::table('m_cabang')
    			->join('users','m_cabang.id_user_input','=','users.id')
                ->where('m_cabang.kode_cabang', $request->kode_cabang)
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
        DB::table('m_cabang')->where('kode_cabang', $request->kode_cabang)->update([
            'nama_cabang' =>  $request->nama_cabang,
            'alamat' =>  $request->alamat,
            'tlp' =>  $request->tlp,
            'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'message'  => 'Data Unit Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $cari_apotek = $request->input('cari_apotek');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        if($tombol_excel == 'excel'){
            if($cari_apotek != ''){
                $data_apotek_excel = DB::table('m_cabang')
    					->join('users','m_cabang.id_user_input','=','users.id')
                        ->where('m_cabang.nama_cabang', 'like', "%$cari_apotek%")
                        ->get();
            }else{
                $data_apotek_excel = DB::table('m_cabang')
    					->join('users','m_cabang.id_user_input','=','users.id')
                        ->get();
            }
            return view ('master_data.apotek.view', compact('data_apotek_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari_apotek != ''){
                $data_apotek_pdf = DB::table('m_cabang')
    					->join('users','m_cabang.id_user_input','=','users.id')
                        ->where('m_cabang.nama_cabang', 'like', "%$cari_apotek%")
                        ->get();
            }else{
                $data_apotek_pdf = DB::table('m_cabang')
    					->join('users','m_cabang.id_user_input','=','users.id')
                        ->get();
            }
            $pdf = PDF::loadview('master_data.apotek.pdf', compact('data_apotek_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
    }
}
