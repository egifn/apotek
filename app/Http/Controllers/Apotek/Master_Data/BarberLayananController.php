<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BarberLayananController extends Controller
{
    public function index()
    {
        // $data_jasa = DB::table('m_barbershop_list')
        // 			->join('users','m_barbershop_list.id_user_input','=','users.id')
        // 			->get();

        return view('apotek.master_data.barber.index');
    }

    public function getDataJasa(Request $request)
    {
        $data_jasa = DB::table('m_barbershop_list')
            ->select('m_barbershop_list.id', 'm_barbershop_list.nama_pelayanan', 'm_barbershop_list.harga', 'm_barbershop_list.keterangan', 'm_barbershop_list.id_user_input', 'users.name')
            ->join('users', 'm_barbershop_list.id_user_input', '=', 'users.id');

        if (!isset($request->value)) {
        } else {
            $data_jasa->where('m_barbershop_list.nama_pelayanan', 'like', "%$request->value%");
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
        DB::table('m_barbershop_list')->insert([
            'nama_pelayanan' => $request->nama_jasa_p,
            'harga' => str_replace(",", "", $request->harga),
            'keterangan' => $request->keterangan,
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
        $data = DB::table('m_barbershop_list')
            ->where('m_barbershop_list.id', $request->id)
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
        DB::table('m_barbershop_list')->where('id', $request->id)->update([
            'nama_pelayanan' => $request->nama_jasa,
            'harga' => str_replace(",", "", $request->harga),
            'keterangan' => $request->keterangan,
        ]);

        $output = [
            'message'  => 'Data Jasa Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    // public function view(Request $request)
    // {       
    //     $cari_jasa = $request->input('cari_jasa');
    //     $tombol_excel = $request->input('button_excel');
    //     $tombol_pdf = $request->input('button_pdf');

    //     if($tombol_excel == 'excel'){
    //        if($cari_jasa != ''){
    // 		$data_jasa_excel = DB::table('m_jasa_pelayanan')
    // 			->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$cari_jasa%")
    //                             ->get();
    //        }else{
    //                     $data_jasa_excel =  DB::table('m_jasa_pelayanan')
    //                     ->get();
    //        }
    //        return view ('master_data.jasa.view', compact('data_jasa_excel'));
    //     }elseif($tombol_pdf == 'pdf'){
    //       if($cari_jasa != ''){
    // 		$data_jasa_pdf =  DB::table('m_jasa_pelayanan')
    // 				->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$cari_jasa%")
    // 				->get();
    //        }else{
    // 		$data_jasa_pdf = DB::table('m_jasa_pelayanan')
    // 				->get();
    //        }
    //        $pdf = PDF::loadview('master_data.jasa.pdf', compact('data_jasa_pdf'))->setPaper('a4', 'portrait');
    //        return $pdf->stream();
    //     }
    // }

}
