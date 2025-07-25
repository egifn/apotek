<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapPenjualanController extends Controller
{
    public function index()
    {
        return view ('laporan.penjualan.index');
    }

    public function getDataPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_penjualan = DB::table('tr_penjualan_h')
                    ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
                    'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.id_produk_unit','m_produk_unit.nama_unit','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
                    'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC');
        if(!isset($request->value)) {
            $data_penjualan->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start,$date_end])
                            ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal']);
        }else{
            $data_penjualan
                        ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                        ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                        ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%");              
        }

        $data  = $data_penjualan->get();
        $count = ($data_penjualan->count() == 0) ? 0 : $data->count();
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

        $data_penjualan = DB::table('tr_penjualan_h')
                    ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
                    'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.id_produk_unit','m_produk_unit.nama_unit','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
                    'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC');
        if(!isset($request->value)) {
            $data_penjualan->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start,$date_end])
                            ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal']);
        }else{
            $data_penjualan
                        ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                        ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                        ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%");              
        }

        $data  = $data_penjualan->get();
        $count = ($data_penjualan->count() == 0) ? 0 : $data->count();
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

        if($tombol_excel == 'excel'){
            if($cari != ''){
                $data_penjualan_excel = DB::table('tr_penjualan_h')
                    ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
                    'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.id_produk_unit','m_produk_unit.nama_unit','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
                    'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%")
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC')
                    ->get();
            }else{
                $data_penjualan_excel = DB::table('tr_penjualan_h')
                    ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
                    'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.id_produk_unit','m_produk_unit.nama_unit','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
                    'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC')
                    ->get();
            }
            return view ('laporan.penjualan.view', compact('data_penjualan_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari != ''){
                $data_penjualan_pdf = DB::table('tr_penjualan_h')
                    ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
                    'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.id_produk_unit','m_produk_unit.nama_unit','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
                    'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%")
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC')
                    ->get();
            }else{
                $data_penjualan_pdf = DB::table('tr_penjualan_h')
                    ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
                    ->join('m_produk_unit','tr_penjualan_d.id_produk_unit','=','m_produk_unit.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
                    'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.id_produk_unit','m_produk_unit.nama_unit','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
                    'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC')
                    ->get();
            }
            $pdf = PDF::loadview('laporan.penjualan.pdf', compact('data_penjualan_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }elseif($tombol_tgl == 'tgl'){
            // if(request()->tanggal != ''){
            //     // $date = explode(' - ' ,request()->tanggal);
            //     // $date_start = Carbon::parse($date[0])->format('Y-m-d');
            //     // $date_end = Carbon::parse($date[1])->format('Y-m-d');
            // }
            
            // $data_penjualan = DB::table('tr_penjualan_h')
            //         ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
            //         ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
            //         ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
            //         ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
            //         'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
            //         'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
            //         // ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start,$date_end])
            //         ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC')
            //         ->get();
            
            // return view('laporan.penjualan.index', compact('data_penjualan'));

            $data_penjualan_pdf = DB::table('tr_penjualan_h')
            ->join('tr_penjualan_d','tr_penjualan_h.kode_penjualan','=','tr_penjualan_d.kode_penjualan')
            ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
            ->join('m_produk','tr_penjualan_d.kode_produk','=','m_produk.kode_produk')
            ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.jenis_penjualan',
            'm_produk.tipe','tr_penjualan_d.kode_produk','m_produk.nama_produk','tr_penjualan_d.qty','tr_penjualan_d.harga','tr_penjualan_d.diskon','tr_penjualan_d.diskon_rp',
            'tr_penjualan_d.ppn','tr_penjualan_d.ppn_rp','tr_penjualan_d.total')
            ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
            ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
            ->orderBy('tr_penjualan_h.kode_penjualan', 'DESC')
            ->get();

            $pdf = PDF::loadview('laporan.penjualan.pdf', compact('data_penjualan_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
    }
}
