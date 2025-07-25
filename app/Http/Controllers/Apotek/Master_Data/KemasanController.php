<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Kemasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KemasanController extends Controller
{
    public function index()
    {

        return view('apotek.master_data.kemasan.index');
    }

    public function getDataUnit(Request $request)
    {
        $query = DB::table('m_produk_unit')
            ->join('users', 'm_produk_unit.id_user_input', '=', 'users.id')
            ->select('m_produk_unit.id as id_unit', 'm_produk_unit.nama_unit', 'm_produk_unit.id_user_input', 'users.name')
            ->orderBy('m_produk_unit.id', 'ASC');
        if (!isset($request->value)) {
            // $query->paginate(8);
        } else {
            $query->where('nama_unit', 'like', "%$request->value%");
        }

        $data  = $query->get();
        $count = ($query->count() == 0) ? 0 : $data->count();
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
        DB::table('m_produk_unit')->insert([
            'nama_unit' => $request->nama_unit,
            'id_user_input' => $request->id_user_input
        ]);

        $output = [
            'msg'  => 'Data Unit Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataKemasanDetail(Request $request)
    {
        $data = DB::table('m_produk_unit')
            ->where('m_produk_unit.id', $request->id)
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
        DB::table('m_produk_unit')->where('id', $request->id)->update([
            'nama_unit' =>  $request->nama_unit,
        ]);

        $output = [
            'message'  => 'Data Jenis Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $cari_satuan = $request->input('cari_satuan');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        if ($tombol_excel == 'excel') {
            if ($cari_satuan != '') {
                $data_unit_excel = DB::table('m_produk_unit')
                    ->where('m_produk_unit.nama_jenis', 'like', "%$cari_satuan%")
                    ->get();
            } else {
                $data_unit_excel =  DB::table('m_produk_unit')
                    ->get();
            }
            return view('apotek.master_data.kemasan.view', compact('data_unit_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari_satuan != '') {
                $data_unit_pdf =  DB::table('m_produk_unit')
                    ->where('m_produk_unit.nama_jenis', 'like', "%$cari_satuan%")
                    ->get();
            } else {
                $data_unit_pdf = DB::table('m_produk_unit')
                    ->get();
            }
            $pdf = PDF::loadview('master_data.kemasan.pdf', compact('data_unit_pdf'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
    }
}
