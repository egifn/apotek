<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapPenjualanPerTransaksiController extends Controller
{
    public function index()
    {
        return view ('laporan.penjualan_pertransaksi.index');
    }

    public function getDataPenjualanPertransaksi(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_penjualan_pertransaksi = DB::table('tr_penjualan_h')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('users','tr_penjualan_h.id_user_input','=','users.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.waktu_penjualan','tr_penjualan_h.jenis_penjualan'
                    ,'tr_penjualan_h.cara_bayar','tr_penjualan_h.bank','tr_penjualan_h.subtotal','tr_penjualan_h.pembulatan','tr_penjualan_h.total_bayar',
                    'tr_penjualan_h.jml_bayar','tr_penjualan_h.kembali','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.id_user_input','users.name')
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'ASC');
        if(!isset($request->value)) {
            $data_penjualan_pertransaksi
                ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal']);
        }else{
            $data_penjualan_pertransaksi
                        ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                        ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->where('tr_penjualan_h.kode_penjualan', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.cara_bayar', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.bank', 'like', "%$request->value%");              
        }

        $data  = $data_penjualan_pertransaksi->get();
        $count = ($data_penjualan_pertransaksi->count() == 0) ? 0 : $data->count();
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
        if (strpos($request->tgl_cari, ' - ') !== false) {
            $date = explode(' - ', $request->tgl_cari);
            $date_start = Carbon::parse($date[0])->format('Y-m-d');
            $date_end = Carbon::parse($date[1])->format('Y-m-d');
        } else {
            $date_start = Carbon::now()->format('Y-m-d');
            $date_end = Carbon::now()->format('Y-m-d');
        }

        $data_penjualan_pertransaksi = DB::table('tr_penjualan_h')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('users','tr_penjualan_h.id_user_input','=','users.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.waktu_penjualan','tr_penjualan_h.jenis_penjualan'
                    ,'tr_penjualan_h.cara_bayar','tr_penjualan_h.bank','tr_penjualan_h.subtotal','tr_penjualan_h.pembulatan','tr_penjualan_h.total_bayar',
                    'tr_penjualan_h.jml_bayar','tr_penjualan_h.kembali','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.id_user_input','users.name')
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'ASC');
        if(!isset($request->value)) {
            $data_penjualan_pertransaksi
                ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end]);
        }else{
            $data_penjualan_pertransaksi
                        ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                        ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                        ->orWhere('tr_penjualan_h.kode_penjualan', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.cara_bayar', 'like', "%$request->value%")
                        ->orWhere('tr_penjualan_h.bank', 'like', "%$request->value%");              
        }

        $data  = $data_penjualan_pertransaksi->get();
        $count = ($data_penjualan_pertransaksi->count() == 0) ? 0 : $data->count();
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
                $data_penjualan_pertransaksi_excel = DB::table('tr_penjualan_h')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('users','tr_penjualan_h.id_user_input','=','users.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.waktu_penjualan','tr_penjualan_h.jenis_penjualan'
                    ,'tr_penjualan_h.cara_bayar','tr_penjualan_h.bank','tr_penjualan_h.subtotal','tr_penjualan_h.pembulatan','tr_penjualan_h.total_bayar',
                    'tr_penjualan_h.jml_bayar','tr_penjualan_h.kembali','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.id_user_input','users.name')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->where('tr_penjualan_h.kode_penjualan', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.cara_bayar', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.bank', 'like', "%$request->value%")   
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'ASC')
                    ->get();
            }else{
                $data_penjualan_pertransaksi_excel = DB::table('tr_penjualan_h')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('users','tr_penjualan_h.id_user_input','=','users.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.waktu_penjualan','tr_penjualan_h.jenis_penjualan'
                    ,'tr_penjualan_h.cara_bayar','tr_penjualan_h.bank','tr_penjualan_h.subtotal','tr_penjualan_h.pembulatan','tr_penjualan_h.total_bayar',
                    'tr_penjualan_h.jml_bayar','tr_penjualan_h.kembali','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.id_user_input','users.name')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'ASC')
                    ->get();
            }
            return view ('laporan.penjualan_pertransaksi.view', compact('data_penjualan_pertransaksi_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari != ''){
                $data_penjualan_pertransaksi_pdf = DB::table('tr_penjualan_h')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('users','tr_penjualan_h.id_user_input','=','users.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.waktu_penjualan','tr_penjualan_h.jenis_penjualan'
                    ,'tr_penjualan_h.cara_bayar','tr_penjualan_h.bank','tr_penjualan_h.subtotal','tr_penjualan_h.pembulatan','tr_penjualan_h.total_bayar',
                    'tr_penjualan_h.jml_bayar','tr_penjualan_h.kembali','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.id_user_input','users.name')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->where('tr_penjualan_h.kode_penjualan', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.jenis_penjualan', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.cara_bayar', 'like', "%$request->value%")
                    ->orWhere('tr_penjualan_h.bank', 'like', "%$request->value%")   
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'ASC')
                    ->get();
            }else{
                $data_penjualan_pertransaksi_pdf = DB::table('tr_penjualan_h')
                    ->join('m_cabang','tr_penjualan_h.kode_cabang','=','m_cabang.kode_cabang')
                    ->join('users','tr_penjualan_h.id_user_input','=','users.id')
                    ->select('tr_penjualan_h.kode_penjualan','tr_penjualan_h.tgl_penjualan','tr_penjualan_h.waktu_penjualan','tr_penjualan_h.jenis_penjualan'
                    ,'tr_penjualan_h.cara_bayar','tr_penjualan_h.bank','tr_penjualan_h.subtotal','tr_penjualan_h.pembulatan','tr_penjualan_h.total_bayar',
                    'tr_penjualan_h.jml_bayar','tr_penjualan_h.kembali','tr_penjualan_h.kode_cabang','m_cabang.nama_cabang','tr_penjualan_h.id_user_input','users.name')
                    ->WhereBetween('tr_penjualan_h.tgl_penjualan',[$date_start,$date_end])
                    ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                    ->orderBy('tr_penjualan_h.kode_penjualan', 'ASC')
                    ->get();
            }
            $pdf = PDF::loadview('laporan.penjualan_pertransaksi.pdf', compact('data_penjualan_pertransaksi_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }elseif($tombol_tgl == 'tgl'){
            
        }
    }
}
