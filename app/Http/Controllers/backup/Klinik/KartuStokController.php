<?php

namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KartuStokController extends Controller
{
    public function index()
    {
        return view ('kartu_stok.index');
    }

    public function getDatakartuStok(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_kartu_stok= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk');

            if (!isset($request->value)) {
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end]);
            }else{
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                            ->orWhere('m_produk.nama_produk', 'like', "%$request->value%");
            }
        }else{ //jika Admin
            $data_kartu_stok= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk');

            if (!isset($request->value)) {
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                            ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi);
            }else{
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                            ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi)
                            ->orWhere('m_produk.nama_produk', 'like', "%$request->value%");
            }
        }
        
        $data  = $data_kartu_stok->get();
        $count = ($data_kartu_stok->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function cari(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = explode(' - ' ,$request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_kartu_stok= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk');

            if (!isset($request->value)) {
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end]);
            }else{
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                            ->orWhere('m_produk.nama_produk', 'like', "%$request->value%");
            }
        }else{ //jika Admin
            $data_kartu_stok= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk');

            if (!isset($request->value)) {
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                            ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi);
            }else{
                $data_kartu_stok
                            ->whereNotIn('stok_in_out.type', ['Batal'])
                            ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                            ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi)
                            ->orWhere('m_produk.nama_produk', 'like', "%$request->value%");
            }
        }

        $data  = $data_kartu_stok->get();
        $count = ($data_kartu_stok->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = explode(' - ' ,$request->tanggal);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');
        $cari = $request->input('cari');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');
        $tombol_tgl = $request->input('button_tgl');

        if (Auth::user()->type == '1') {  //jika Super Admin

            if($tombol_excel == 'excel'){
                if($cari != ''){
                    $kartu_stok_excel= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->where('m_produk.nama_produk', 'like', "%$cari%")
                        ->get();
                   
                }else{
                    $kartu_stok_excel= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->get();
                }
                return view ('kartu_stok.view', compact('kartu_stok_excel'));
            }elseif($tombol_pdf == 'pdf'){
                if($cari != ''){
                    $kartu_stok_pdf= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->where('m_produk.nama_produk', 'like', "%$cari%")
                        ->get();
                }else{
                    $kartu_stok_pdf= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->get();
                }
                $pdf = PDF::loadview('kartu_stok.pdf', compact('kartu_stok_pdf'))->setPaper('a4', 'landscape');
                return $pdf->stream();
            }

        }else{ //jika Admin
            
            if($tombol_excel == 'excel'){
                if($cari != ''){
                    $kartu_stok_excel= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi)
                        ->where('m_produk.nama_produk', 'like', "%$cari%")
                        ->get();
                }else{
                    $kartu_stok_excel= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi)
                        ->get();
                }
                return view ('kartu_stok.view', compact('kartu_stok_excel'));
            }elseif($tombol_pdf == 'pdf'){
                if($cari != ''){
                    $kartu_stok_pdf= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi)
                        ->where('m_produk.nama_produk', 'like', "%$cari%")
                        ->get();
                }else{
                    $kartu_stok_pdf= DB::table('stok_in_out')
                        ->join('m_produk','stok_in_out.id_produk','=','m_produk.kode_produk')
                        ->WhereBetween('stok_in_out.tgl_in_out',[$date_start,$date_end])
                        ->whereNotIn('stok_in_out.type', ['Batal'])
                        ->where('stok_in_out.kode_cabang', Auth::user()->kd_lokasi)
                        ->get();
                }
                $pdf = PDF::loadview('kartu_stok.pdf', compact('kartu_stok_pdf'))->setPaper('a4', 'landscape');
                return $pdf->stream();
            }

        }
    }
}
