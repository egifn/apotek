<?php

namespace App\Http\Controllers\Apotek\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapKlinikObatController extends Controller
{
    public function index()
    {
        return view('apotek.laporan.klinik_obat_keluar.index');
    }

    public function getDataKlinikObat(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $data_klinik_obat = DB::table('tr_pembayaran_h_klinik')
            ->join('tr_pembayaran_d_klinik_obat', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_obat.no_invoice')
            ->join('tr_pelayanan_h', 'tr_pembayaran_h_klinik.kode_kunjungan', '=', 'tr_pelayanan_h.kode_kunjungan')
            ->join('tr_pelayanan_d_obat', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'tr_pelayanan_d_obat.kode_produk')
            ->join('m_produk_unit', 'tr_pelayanan_d_obat.id_produk_unit', '=', 'm_produk_unit.id')
            ->join('m_produk', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_pembayaran_h_klinik.no_invoice',
                'tr_pembayaran_h_klinik.tgl_invoice',
                'm_cabang.nama_cabang',
                'tr_pembayaran_d_klinik_obat.kode_produk',
                'm_produk.nama_produk',
                'tr_pembayaran_d_klinik_obat.jml_produk',
                'm_produk_unit.nama_unit',
                'tr_pembayaran_d_klinik_obat.harga_produk',
                'tr_pelayanan_d_obat.id_produk_unit',
                'tr_pembayaran_h_klinik.kode_cabang'
            )
            ->groupBy(
                'tr_pembayaran_h_klinik.no_invoice',
                'tr_pembayaran_h_klinik.tgl_invoice',
                'm_cabang.nama_cabang',
                'tr_pembayaran_d_klinik_obat.kode_produk',
                'm_produk.nama_produk',
                'tr_pembayaran_d_klinik_obat.jml_produk',
                'm_produk_unit.nama_unit',
                'tr_pembayaran_d_klinik_obat.harga_produk',
                'tr_pelayanan_d_obat.id_produk_unit',
                'tr_pembayaran_h_klinik.kode_cabang'
            );

        if (!isset($request->value)) {
        } else {

            $data_klinik_obat
                ->where('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('tr_pembayaran_d_klinik_obat.kode_produk', 'like', "%$request->value%");
        }

        $data  = $data_klinik_obat->get();
        $count = ($data_klinik_obat->count() == 0) ? 0 : $data->count();
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
                $data_klinik_obat_excel = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_obat', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_obat.no_invoice')
                    ->join('tr_pelayanan_h', 'tr_pembayaran_h_klinik.kode_kunjungan', '=', 'tr_pelayanan_h.kode_kunjungan')
                    ->join('tr_pelayanan_d_obat', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'tr_pelayanan_d_obat.kode_produk')
                    ->join('m_produk_unit', 'tr_pelayanan_d_obat.id_produk_unit', '=', 'm_produk_unit.id')
                    ->join('m_produk', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'm_produk.kode_produk')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->groupBy(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('tr_pembayaran_d_klinik_obat.kode_produk', 'like', "%$request->value%")
                    ->get();
            } else {
                $data_klinik_obat_excel = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_obat', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_obat.no_invoice')
                    ->join('tr_pelayanan_h', 'tr_pembayaran_h_klinik.kode_kunjungan', '=', 'tr_pelayanan_h.kode_kunjungan')
                    ->join('tr_pelayanan_d_obat', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'tr_pelayanan_d_obat.kode_produk')
                    ->join('m_produk_unit', 'tr_pelayanan_d_obat.id_produk_unit', '=', 'm_produk_unit.id')
                    ->join('m_produk', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'm_produk.kode_produk')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->groupBy(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->get();
            }
            return view('apotek.laporan.klinik_obat_keluar.view', compact('data_klinik_obat_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari != '') {
                $data_klinik_obat_pdf = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_obat', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_obat.no_invoice')
                    ->join('tr_pelayanan_h', 'tr_pembayaran_h_klinik.kode_kunjungan', '=', 'tr_pelayanan_h.kode_kunjungan')
                    ->join('tr_pelayanan_d_obat', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'tr_pelayanan_d_obat.kode_produk')
                    ->join('m_produk_unit', 'tr_pelayanan_d_obat.id_produk_unit', '=', 'm_produk_unit.id')
                    ->join('m_produk', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'm_produk.kode_produk')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->groupBy(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('tr_pembayaran_d_klinik_obat.kode_produk', 'like', "%$request->value%")
                    ->get();
            } else {
                $data_klinik_obat_pdf = DB::table('tr_pembayaran_h_klinik')
                    ->join('tr_pembayaran_d_klinik_obat', 'tr_pembayaran_h_klinik.no_invoice', '=', 'tr_pembayaran_d_klinik_obat.no_invoice')
                    ->join('tr_pelayanan_h', 'tr_pembayaran_h_klinik.kode_kunjungan', '=', 'tr_pelayanan_h.kode_kunjungan')
                    ->join('tr_pelayanan_d_obat', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'tr_pelayanan_d_obat.kode_produk')
                    ->join('m_produk_unit', 'tr_pelayanan_d_obat.id_produk_unit', '=', 'm_produk_unit.id')
                    ->join('m_produk', 'tr_pembayaran_d_klinik_obat.kode_produk', '=', 'm_produk.kode_produk')
                    ->join('m_cabang', 'tr_pembayaran_h_klinik.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->select(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->groupBy(
                        'tr_pembayaran_h_klinik.no_invoice',
                        'tr_pembayaran_h_klinik.tgl_invoice',
                        'm_cabang.nama_cabang',
                        'tr_pembayaran_d_klinik_obat.kode_produk',
                        'm_produk.nama_produk',
                        'tr_pembayaran_d_klinik_obat.jml_produk',
                        'm_produk_unit.nama_unit',
                        'tr_pembayaran_d_klinik_obat.harga_produk',
                        'tr_pelayanan_d_obat.id_produk_unit',
                        'tr_pembayaran_h_klinik.kode_cabang'
                    )
                    ->get();
            }
            $pdf = PDF::loadview('laporan.klinik_obat_keluar.pdf', compact('data_klinik_obat_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        } elseif ($tombol_tgl == 'tgl') {
        }
    }
}
