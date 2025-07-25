<?php

namespace App\Http\Controllers\Apotek\Pendapatan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PendapatanController extends Controller
{
    public function index()
    {
        return view('apotek.pendapatan.index');
    }

    public function getDataPendapatan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_pendapatan = DB::table('tr_penjualan_h')
            ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_penjualan_h.tgl_penjualan',
                'tr_penjualan_h.kode_cabang',
                'm_cabang.nama_cabang',
                DB::raw('COUNT(tr_penjualan_h.kode_penjualan) as jml_transaksi'),
                DB::raw('SUM(tr_penjualan_h.total_bayar) as total')
            )
            ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start, $date_end])
            ->whereNotIn('tr_penjualan_h.cara_bayar', ['Kredit'])
            ->groupBy('tr_penjualan_h.tgl_penjualan', 'tr_penjualan_h.kode_cabang', 'm_cabang.nama_cabang');

        $data = $data_pendapatan->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function cari(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = explode(' - ', $request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        $data_pendapatan = DB::table('tr_penjualan_h')
            ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_penjualan_h.tgl_penjualan',
                'tr_penjualan_h.kode_cabang',
                'm_cabang.nama_cabang',
                DB::raw('COUNT(tr_penjualan_h.kode_penjualan) as jml_transaksi'),
                DB::raw('SUM(tr_penjualan_h.total_bayar) as total')
            )
            ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start, $date_end])
            ->whereNotIn('tr_penjualan_h.cara_bayar', ['Kredit'])
            ->groupBy('tr_penjualan_h.tgl_penjualan', 'tr_penjualan_h.kode_cabang', 'm_cabang.nama_cabang');

        $data = $data_pendapatan->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }
}
