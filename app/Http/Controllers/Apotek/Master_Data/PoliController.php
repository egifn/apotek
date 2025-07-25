<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PoliController extends Controller
{
    public function index()
    {
        return view('apotek.master_data.poli.index');
    }

    public function getDataPoli(Request $request)
    {
        $data_poli = DB::table('m_poli')
            ->select('m_poli.id', 'm_poli.nama_poli', 'm_poli.id_user_input', 'users.name')
            ->join('users', 'm_poli.id_user_input', '=', 'users.id');

        if (!isset($request->value)) {
        } else {
            $data_poli->where('m_poli.nama_poli', 'like', "%$request->value%");
        }

        $data  = $data_poli->get();
        $count = ($data_poli->count() == 0) ? 0 : $data->count();
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
        DB::table('m_poli')->insert([
            'nama_poli' => $request->nama_poli,
            'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'msg'  => 'Data Poli Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataPoliDetail(Request $request)
    {
        $data = DB::table('m_poli')
            ->where('m_poli.id', $request->id)
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
        DB::table('m_poli')->where('id', $request->id)->update([
            'nama_poli' =>  $request->nama_poli,
        ]);

        $output = [
            'message'  => 'Data Poli Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $cari_poli = $request->input('cari_poli');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        if ($tombol_excel == 'excel') {
            if ($cari_poli != '') {
                $data_poli_excel = DB::table('m_poli')
                    ->where('m_poli.nama_poli', 'like', "%$cari_poli%")
                    ->get();
            } else {
                $data_poli_excel =  DB::table('m_poli')
                    ->get();
            }
            return view('apotek.master_data.poli.view', compact('data_poli_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari_poli != '') {
                $data_poli_pdf =  DB::table('m_poli')
                    ->where('m_poli.nama_poli', 'like', "%$cari_poli%")
                    ->get();
            } else {
                $data_poli_pdf = DB::table('m_poli')
                    ->get();
            }
            $pdf = PDF::loadview('master_data.poli.pdf', compact('data_poli_pdf'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
    }
}
