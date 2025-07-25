<?php

namespace App\Http\Controllers\Apotek\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapKlinikPelayananController extends Controller
{
    public function index()
    {
        return view('apotek.laporan.klinik_pelayanan.index');
    }

    public function getDataKlinikPelayanan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $data_klinik_pelayanan = DB::table('tr_pembayaran_h_klinik')
            ->join('tr_pembayaran_d_klinik_tindakan', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_tindakan.no_invoice')
            ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('m_jasa_pelayanan', 'tr_pembayaran_d_klinik_tindakan.kode_jasa_p', '=', 'm_jasa_pelayanan.kode_jasa_p')
            ->select(
                'tr_pembayaran_h_klinik.no_invoice',
                'tr_pembayaran_h_klinik.tgl_invoice',
                'm_cabang.nama_cabang',
                'tr_pembayaran_d_klinik_tindakan.kode_jasa_p',
                'm_jasa_pelayanan.nama_jasa_p',
                'tr_pembayaran_d_klinik_tindakan.jml_jasa_p',
                'tr_pembayaran_d_klinik_tindakan.harga_jasa_p',
                'tr_pembayaran_h_klinik.kode_cabang'
            );

        if (!isset($request->value)) {
        } else {

            $data_klinik_pelayanan
                ->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$request->value%")
                ->orWhere('tr_pembayaran_d_klinik_tindakan.kode_jasa_p', 'like', "%$request->value%");
        }

        $data  = $data_klinik_pelayanan->get();
        $count = ($data_klinik_pelayanan->count() == 0) ? 0 : $data->count();
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
        $cari = $request->input('cari');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');
        $tombol_tgl = $request->input('button_tgl');

        if ($tombol_excel == 'excel') {
            if ($cari != '') {
                $data_klinik_pelayanan_excel = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_tindakan', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_tindakan.no_invoice')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_jasa_pelayanan', 'tr_pembayaran_d_klinik_tindakan.kode_jasa_p', '=', 'm_jasa_pelayanan.kode_jasa_p')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_tindakan.kode_jasa_p',
                        'm_jasa_pelayanan.nama_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.jml_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.harga_jasa_p',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$request->value%")
                    ->orWhere('tr_pembayaran_d_klinik_tindakan.kode_jasa_p', 'like', "%$request->value%")
                    ->get();
            } else {
                $data_klinik_pelayanan_excel = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_tindakan', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_tindakan.no_invoice')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_jasa_pelayanan', 'tr_pembayaran_d_klinik_tindakan.kode_jasa_p', '=', 'm_jasa_pelayanan.kode_jasa_p')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_tindakan.kode_jasa_p',
                        'm_jasa_pelayanan.nama_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.jml_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.harga_jasa_p',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->get();
            }
            return view('apotek.laporan.klinik_pelayanan.view', compact('data_klinik_pelayanan_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari != '') {
                $data_klinik_pelayanan_pdf = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_tindakan', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_tindakan.no_invoice')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_jasa_pelayanan', 'tr_pembayaran_d_klinik_tindakan.kode_jasa_p', '=', 'm_jasa_pelayanan.kode_jasa_p')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_tindakan.kode_jasa_p',
                        'm_jasa_pelayanan.nama_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.jml_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.harga_jasa_p',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->where('m_jasa_pelayanan.nama_jasa_p', 'like', "%$request->value%")
                    ->orWhere('tr_pembayaran_d_klinik_tindakan.kode_jasa_p', 'like', "%$request->value%")
                    ->get();
            } else {
                $data_klinik_pelayanan_pdf = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_tindakan', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_tindakan.no_invoice')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_jasa_pelayanan', 'tr_pembayaran_d_klinik_tindakan.kode_jasa_p', '=', 'm_jasa_pelayanan.kode_jasa_p')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_tindakan.kode_jasa_p',
                        'm_jasa_pelayanan.nama_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.jml_jasa_p',
                        'tr_pembayaran_d_klinik_tindakan.harga_jasa_p',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->get();
            }
            $pdf = PDF::loadview('laporan.klinik_pelayanan.pdf', compact('data_klinik_pelayanan_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        } elseif ($tombol_tgl == 'tgl') {
        }
    }
}
