<?php

namespace App\Http\Controllers\Apotek\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapNarkotikaPsikotropikaController extends Controller
{
    public function index()
    {
        return view('apotek.laporan.narkotika_psikotropika.index');
    }

    public function getData(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_item = DB::table('m_produk')
            ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
            ->join('stok_in_out', 'm_produk.kode_produk', '=', 'stok_in_out.id_produk')
            ->join('m_cabang', 'stok_in_out.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'm_produk.kode_produk',
                'm_produk.nama_produk',
                'm_produk.id_unit',
                'm_produk_unit.nama_unit',
                'stok_in_out.kode_cabang',
                'm_cabang.nama_cabang',
                DB::raw('MAX(stok_in_out.stok_awal) as stok_awal'),
                DB::raw('MIN(stok_in_out.stok_sisa) as stok_akhir'),
                DB::raw('SUM(stok_in_out.stok_masuk) as stok_masuk'),
                DB::raw('SUM(stok_in_out.stok_keluar) as stok_keluar')
            );
        if (!isset($request->value)) {
            $data_item
                ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang');
        } else {
            $data_item
                ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                ->where('stok_in_out.kode_cabang', 'like', "%$request->value%")
                ->orWhere('m_produk.kode_produk', 'like', "%$request->value%")
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang');
        }

        $data  = $data_item->get();
        $count = ($data_item->count() == 0) ? 0 : $data->count();
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
        $date = explode(' - ', $request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        $data_item = DB::table('m_produk')
            ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
            ->join('stok_in_out', 'm_produk.kode_produk', '=', 'stok_in_out.id_produk')
            ->join('m_cabang', 'stok_in_out.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'm_produk.kode_produk',
                'm_produk.nama_produk',
                'm_produk.id_unit',
                'm_produk_unit.nama_unit',
                'stok_in_out.kode_cabang',
                'm_cabang.nama_cabang',
                DB::raw('MAX(stok_in_out.stok_awal) as stok_awal'),
                DB::raw('MIN(stok_in_out.stok_sisa) as stok_akhir'),
                DB::raw('SUM(stok_in_out.stok_masuk) as stok_masuk'),
                DB::raw('SUM(stok_in_out.stok_keluar) as stok_keluar')
            );
        if (!isset($request->value)) {
            $data_item
                ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang');
        } else {
            $data_item
                ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                ->where('stok_in_out.kode_cabang', 'like', "%$request->value%")
                ->orWhere('m_produk.kode_produk', 'like', "%$request->value%")
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang');
        }

        $data  = $data_item->get();
        $count = ($data_item->count() == 0) ? 0 : $data->count();
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
        $date = explode(' - ', $request->tanggal);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');
        $cari = $request->input('cari');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');
        $tombol_tgl = $request->input('button_tgl');

        if ($tombol_excel == 'excel') {
            if ($cari != '') {
                $data_excel = DB::table('m_produk')
                    ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
                    ->join('stok_in_out', 'm_produk.kode_produk', '=', 'stok_in_out.id_produk')
                    ->join('m_cabang', 'stok_in_out.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'm_produk.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.id_unit',
                        'm_produk_unit.nama_unit',
                        'stok_in_out.kode_cabang',
                        'm_cabang.nama_cabang',
                        DB::raw('MAX(stok_in_out.stok_awal) as stok_awal'),
                        DB::raw('MIN(stok_in_out.stok_sisa) as stok_akhir'),
                        DB::raw('SUM(stok_in_out.stok_masuk) as stok_masuk'),
                        DB::raw('SUM(stok_in_out.stok_keluar) as stok_keluar')
                    )
                    ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                    ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                    ->where('stok_in_out.kode_cabang', 'like', "%$request->value%")
                    ->orWhere('m_produk.kode_produk', 'like', "%$request->value%")
                    ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                    ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang')
                    ->get();
            } else {
                $data_excel = DB::table('m_produk')
                    ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
                    ->join('stok_in_out', 'm_produk.kode_produk', '=', 'stok_in_out.id_produk')
                    ->join('m_cabang', 'stok_in_out.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'm_produk.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.id_unit',
                        'm_produk_unit.nama_unit',
                        'stok_in_out.kode_cabang',
                        'm_cabang.nama_cabang',
                        DB::raw('MAX(stok_in_out.stok_awal) as stok_awal'),
                        DB::raw('MIN(stok_in_out.stok_sisa) as stok_akhir'),
                        DB::raw('SUM(stok_in_out.stok_masuk) as stok_masuk'),
                        DB::raw('SUM(stok_in_out.stok_keluar) as stok_keluar')
                    )
                    ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                    ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                    ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang')
                    ->get();
            }
            return view('apotek.laporan.narkotika_psikotropika.view', compact('data_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari != '') {
                $data_pdf = DB::table('m_produk')
                    ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
                    ->join('stok_in_out', 'm_produk.kode_produk', '=', 'stok_in_out.id_produk')
                    ->join('m_cabang', 'stok_in_out.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'm_produk.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.id_unit',
                        'm_produk_unit.nama_unit',
                        'stok_in_out.kode_cabang',
                        'm_cabang.nama_cabang',
                        DB::raw('MAX(stok_in_out.stok_awal) as stok_awal'),
                        DB::raw('MIN(stok_in_out.stok_sisa) as stok_akhir'),
                        DB::raw('SUM(stok_in_out.stok_masuk) as stok_masuk'),
                        DB::raw('SUM(stok_in_out.stok_keluar) as stok_keluar')
                    )
                    ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                    ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                    ->where('stok_in_out.kode_cabang', 'like', "%$request->value%")
                    ->orWhere('m_produk.kode_produk', 'like', "%$request->value%")
                    ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                    ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang')
                    ->get();
            } else {
                $data_pdf = DB::table('m_produk')
                    ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
                    ->join('stok_in_out', 'm_produk.kode_produk', '=', 'stok_in_out.id_produk')
                    ->join('m_cabang', 'stok_in_out.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'm_produk.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.id_unit',
                        'm_produk_unit.nama_unit',
                        'stok_in_out.kode_cabang',
                        'm_cabang.nama_cabang',
                        DB::raw('MAX(stok_in_out.stok_awal) as stok_awal'),
                        DB::raw('MIN(stok_in_out.stok_sisa) as stok_akhir'),
                        DB::raw('SUM(stok_in_out.stok_masuk) as stok_masuk'),
                        DB::raw('SUM(stok_in_out.stok_keluar) as stok_keluar')
                    )
                    ->WhereBetween('stok_in_out.tgl_in_out', [$date_start, $date_end])
                    ->whereIn('m_produk.id_jenis', ['8', '9', '10', '11', '12', '13', '14'])
                    ->groupBy('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.id_unit', 'm_produk_unit.nama_unit', 'stok_in_out.kode_cabang', 'm_cabang.nama_cabang')
                    ->get();
            }
            $pdf = PDF::loadview('laporan.narkotika_psikotropika.pdf', compact('data_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        } elseif ($tombol_tgl == 'tgl') {
        }
    }
}
