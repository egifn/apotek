<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class JenisController extends Controller
{
    public function index()
    {
        return view('apotek.master_data.jenis_produk.index');
    }

    public function getDataJenis(Request $request)
    {
        $data_jenis = DB::table('m_jenis_obat')
            ->select('m_jenis_obat.id', 'm_jenis_obat.nama_jenis', 'm_jenis_obat.id_user_input', 'users.name')
            ->join('users', 'm_jenis_obat.id_user_input', '=', 'users.id');

        if (!isset($request->value)) {
        } else {
            $data_jenis->where('m_jenis_obat.nama_jenis', 'like', "%$request->value%");
        }

        $data  = $data_jenis->get();
        $count = ($data_jenis->count() == 0) ? 0 : $data->count();
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
        DB::table('m_jenis_obat')->insert([
            'nama_jenis' => $request->nama_jenis,
            'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'msg'  => 'Data Jenis Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataJenisDetail(Request $request)
    {
        $data = DB::table('m_jenis_obat')
            ->where('m_jenis_obat.id', $request->id)
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
        DB::table('m_jenis_obat')->where('id', $request->id)->update([
            'nama_jenis' =>  $request->nama_jenis,
        ]);

        $output = [
            'message'  => 'Data Jenis Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $cari_jenis = $request->input('cari_jenis');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        if ($tombol_excel == 'excel') {
            if ($cari_jenis != '') {
                $data_jenis_excel = DB::table('m_jenis_obat')
                    ->where('m_jenis_obat.nama_jenis', 'like', "%$cari_jenis%")
                    ->get();
            } else {
                $data_jenis_excel =  DB::table('m_jenis_obat')
                    ->get();
            }
            return view('apotek.master_data.jenis_produk.view', compact('data_jenis_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari_jenis != '') {
                $data_jenis_pdf =  DB::table('m_jenis_obat')
                    ->where('m_jenis_obat.nama_jenis', 'like', "%$cari_jenis%")
                    ->get();
            } else {
                $data_jenis_pdf = DB::table('m_jenis_obat')
                    ->get();
            }
            $pdf = PDF::loadview('master_data.jenis_produk.pdf', compact('data_jenis_pdf'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
    }
}
