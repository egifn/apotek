<?php

namespace App\Http\Controllers\Apotek\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapPenerimaanController extends Controller
{
    public function index()
    {
        return view('apotek.laporan.penerimaan.index');
    }

    public function getDataPenerimaan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_penerimaan = DB::table('tr_penerimaan_h')
            ->join('m_cabang', 'tr_penerimaan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('tr_pembelian_h', 'tr_penerimaan_h.kode_pembelian', '=', 'tr_pembelian_h.kode_pembelian')
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('tr_penerimaan_d', 'tr_penerimaan_h.kode_penerimaan', '=', 'tr_penerimaan_d.kode_penerimaan')
            ->join('m_produk', 'tr_penerimaan_d.kode_produk', 'm_produk.kode_produk')
            ->select(
                'tr_penerimaan_h.kode_penerimaan',
                'tr_penerimaan_h.tgl_penerimaan',
                'tr_penerimaan_h.kode_cabang',
                'm_cabang.nama_cabang',
                'tr_penerimaan_h.kode_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'tr_pembelian_h.pembelian',
                'tr_pembelian_h.kode_supplier',
                'tr_pembelian_h.tgl_jatuh_tempo',
                'm_supplier.nama_supplier',
                'tr_penerimaan_h.no_faktur',
                'tr_penerimaan_h.diskon_rupiah',
                'tr_penerimaan_d.harga_beli_lama',
                'tr_penerimaan_d.kode_produk',
                'm_produk.nama_produk',
                'tr_penerimaan_d.jml_beli',
                'tr_penerimaan_d.jml_terima',
                'tr_penerimaan_d.harga_beli',
                'tr_penerimaan_d.diskon_rp',
                'tr_penerimaan_d.ppn_rp',
                'tr_penerimaan_d.subtotal'
            );

        if (!isset($request->value)) {
            $data_penerimaan
                ->whereNotIn('tr_penerimaan_h.no_faktur', ['-'])
                ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end]);
        } else {
            $data_penerimaan
                ->whereNotIn('tr_penerimaan_h.no_faktur', ['-'])
                ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end])
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_penerimaan->get();
        $count = ($data_penerimaan->count() == 0) ? 0 : $data->count();
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

        $data_penerimaan = DB::table('tr_penerimaan_h')
            ->join('m_cabang', 'tr_penerimaan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('tr_pembelian_h', 'tr_penerimaan_h.kode_pembelian', '=', 'tr_pembelian_h.kode_pembelian')
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('tr_penerimaan_d', 'tr_penerimaan_h.kode_penerimaan', '=', 'tr_penerimaan_d.kode_penerimaan')
            ->join('m_produk', 'tr_penerimaan_d.kode_produk', 'm_produk.kode_produk')
            ->select(
                'tr_penerimaan_h.kode_penerimaan',
                'tr_penerimaan_h.tgl_penerimaan',
                'tr_penerimaan_h.kode_cabang',
                'm_cabang.nama_cabang',
                'tr_penerimaan_h.kode_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'tr_pembelian_h.pembelian',
                'tr_pembelian_h.kode_supplier',
                'tr_pembelian_h.tgl_jatuh_tempo',
                'm_supplier.nama_supplier',
                'tr_penerimaan_h.no_faktur',
                'tr_penerimaan_h.diskon_rupiah',
                'tr_penerimaan_d.harga_beli_lama',
                'tr_penerimaan_d.kode_produk',
                'm_produk.nama_produk',
                'tr_penerimaan_d.jml_beli',
                'tr_penerimaan_d.jml_terima',
                'tr_penerimaan_d.harga_beli',
                'tr_penerimaan_d.diskon_rp',
                'tr_penerimaan_d.ppn_rp',
                'tr_penerimaan_d.subtotal'
            );

        if (!isset($request->value)) {
            $data_penerimaan
                ->whereNotIn('tr_penerimaan_h.no_faktur', ['-'])
                ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end]);
        } else {
            $data_penerimaan
                ->whereNotIn('tr_penerimaan_h.no_faktur', ['-'])
                ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end])
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_penerimaan->get();
        $count = ($data_penerimaan->count() == 0) ? 0 : $data->count();
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
        $date = (date('d M Y'));

        if ($tombol_excel == 'excel') {
            if ($cari != '') {
                $data_penerimaan_excel = DB::table('tr_penerimaan_h')
                    ->join('m_cabang', 'tr_penerimaan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('tr_pembelian_h', 'tr_penerimaan_h.kode_pembelian', '=', 'tr_pembelian_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('tr_penerimaan_d', 'tr_penerimaan_h.kode_penerimaan', '=', 'tr_penerimaan_d.kode_penerimaan')
                    ->join('m_produk', 'tr_penerimaan_d.kode_produk', 'm_produk.kode_produk')
                    ->select(
                        'tr_penerimaan_h.kode_penerimaan',
                        'tr_penerimaan_h.tgl_penerimaan',
                        'tr_penerimaan_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_penerimaan_h.kode_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.kode_supplier',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'm_supplier.nama_supplier',
                        'tr_penerimaan_h.no_faktur',
                        'tr_penerimaan_h.diskon_rupiah',
                        'tr_penerimaan_d.harga_beli_lama',
                        'tr_penerimaan_d.kode_produk',
                        'm_produk.nama_produk',
                        'tr_penerimaan_d.jml_beli',
                        'tr_penerimaan_d.jml_terima',
                        'tr_penerimaan_d.harga_beli',
                        'tr_penerimaan_d.diskon_rp',
                        'tr_penerimaan_d.ppn_rp',
                        'tr_penerimaan_d.subtotal'
                    )
                    ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end])
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                    ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%")
                    ->get();
            } else {
                $data_penerimaan_excel = DB::table('tr_penerimaan_h')
                    ->join('m_cabang', 'tr_penerimaan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('tr_pembelian_h', 'tr_penerimaan_h.kode_pembelian', '=', 'tr_pembelian_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('tr_penerimaan_d', 'tr_penerimaan_h.kode_penerimaan', '=', 'tr_penerimaan_d.kode_penerimaan')
                    ->join('m_produk', 'tr_penerimaan_d.kode_produk', 'm_produk.kode_produk')
                    ->select(
                        'tr_penerimaan_h.kode_penerimaan',
                        'tr_penerimaan_h.tgl_penerimaan',
                        'tr_penerimaan_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_penerimaan_h.kode_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.kode_supplier',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'm_supplier.nama_supplier',
                        'tr_penerimaan_h.no_faktur',
                        'tr_penerimaan_h.diskon_rupiah',
                        'tr_penerimaan_d.harga_beli_lama',
                        'tr_penerimaan_d.kode_produk',
                        'm_produk.nama_produk',
                        'tr_penerimaan_d.jml_beli',
                        'tr_penerimaan_d.jml_terima',
                        'tr_penerimaan_d.harga_beli',
                        'tr_penerimaan_d.diskon_rp',
                        'tr_penerimaan_d.ppn_rp',
                        'tr_penerimaan_d.subtotal'
                    )
                    ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end])
                    ->get();
            }
            return view('apotek.laporan.penerimaan.view', compact('data_penerimaan_excel', 'date'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari != '') {
                $data_penerimaan_pdf = DB::table('tr_penerimaan_h')
                    ->join('m_cabang', 'tr_penerimaan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('tr_pembelian_h', 'tr_penerimaan_h.kode_pembelian', '=', 'tr_pembelian_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('tr_penerimaan_d', 'tr_penerimaan_h.kode_penerimaan', '=', 'tr_penerimaan_d.kode_penerimaan')
                    ->join('m_produk', 'tr_penerimaan_d.kode_produk', 'm_produk.kode_produk')
                    ->select(
                        'tr_penerimaan_h.kode_penerimaan',
                        'tr_penerimaan_h.tgl_penerimaan',
                        'tr_penerimaan_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_penerimaan_h.kode_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.kode_supplier',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'm_supplier.nama_supplier',
                        'tr_penerimaan_h.no_faktur',
                        'tr_penerimaan_h.diskon_rupiah',
                        'tr_penerimaan_d.harga_beli_lama',
                        'tr_penerimaan_d.kode_produk',
                        'm_produk.nama_produk',
                        'tr_penerimaan_d.jml_beli',
                        'tr_penerimaan_d.jml_terima',
                        'tr_penerimaan_d.harga_beli',
                        'tr_penerimaan_d.diskon_rp',
                        'tr_penerimaan_d.ppn_rp',
                        'tr_penerimaan_d.subtotal'
                    )
                    ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end])
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                    ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%")
                    ->get();
            } else {
                $data_penerimaan_pdf = DB::table('tr_penerimaan_h')
                    ->join('m_cabang', 'tr_penerimaan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('tr_pembelian_h', 'tr_penerimaan_h.kode_pembelian', '=', 'tr_pembelian_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('tr_penerimaan_d', 'tr_penerimaan_h.kode_penerimaan', '=', 'tr_penerimaan_d.kode_penerimaan')
                    ->join('m_produk', 'tr_penerimaan_d.kode_produk', 'm_produk.kode_produk')
                    ->select(
                        'tr_penerimaan_h.kode_penerimaan',
                        'tr_penerimaan_h.tgl_penerimaan',
                        'tr_penerimaan_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_penerimaan_h.kode_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.kode_supplier',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'm_supplier.nama_supplier',
                        'tr_penerimaan_h.no_faktur',
                        'tr_penerimaan_h.diskon_rupiah',
                        'tr_penerimaan_d.harga_beli_lama',
                        'tr_penerimaan_d.kode_produk',
                        'm_produk.nama_produk',
                        'tr_penerimaan_d.jml_beli',
                        'tr_penerimaan_d.jml_terima',
                        'tr_penerimaan_d.harga_beli',
                        'tr_penerimaan_d.diskon_rp',
                        'tr_penerimaan_d.ppn_rp',
                        'tr_penerimaan_d.subtotal'
                    )
                    ->WhereBetween('tr_penerimaan_h.tgl_penerimaan', [$date_start, $date_end])
                    ->get();
            }
            $pdf = PDF::loadview('laporan.penerimaan.pdf', compact('data_penerimaan_pdf', 'date'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        } elseif ($tombol_tgl == 'tgl') {
        }
    }
}
