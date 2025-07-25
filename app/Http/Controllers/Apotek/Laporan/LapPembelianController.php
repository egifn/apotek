<?php

namespace App\Http\Controllers\Apotek\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapPembelianController extends Controller
{
    public function index()
    {
        return view('apotek.laporan.pembelian.index');
    }

    public function getDataPembelian(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_pembelian = DB::table('tr_pembelian_h')
            ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
            ->leftJoin('tr_penerimaan_h', 'tr_pembelian_h.kode_pembelian', '=', 'tr_penerimaan_h.kode_pembelian')
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
            ->select(
                'tr_pembelian_h.kode_pembelian',
                'tr_pembelian_h.tgl_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'tr_pembelian_h.pembelian',
                'tr_pembelian_h.jenis_transaksi',
                'tr_pembelian_h.tgl_jatuh_tempo',
                'tr_pembelian_h.kode_supplier',
                'm_supplier.nama_supplier',
                'tr_pembelian_h.diskon',
                'tr_pembelian_h.status_pembelian',
                'tr_pembelian_h.status_pembayaran',
                'tr_penerimaan_h.no_faktur',
                'tr_pembelian_h.kode_cabang',
                'm_cabang.nama_cabang',
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'm_produk.tipe',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total'
            )
            ->groupBy(
                'tr_pembelian_h.kode_pembelian',
                'tr_pembelian_h.tgl_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'tr_pembelian_h.pembelian',
                'tr_pembelian_h.jenis_transaksi',
                'tr_pembelian_h.tgl_jatuh_tempo',
                'tr_pembelian_h.kode_supplier',
                'm_supplier.nama_supplier',
                'tr_pembelian_h.diskon',
                'tr_pembelian_h.status_pembelian',
                'tr_pembelian_h.status_pembayaran',
                'tr_penerimaan_h.no_faktur',
                'tr_pembelian_h.kode_cabang',
                'm_cabang.nama_cabang',
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'm_produk.tipe',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total'
            )
            ->orderBy('tr_pembelian_h.kode_pembelian', 'DESC');

        if (!isset($request->value)) {
            $data_pembelian
                ->WhereBetween('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end]);
        } else {
            $data_pembelian
                ->where('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end])
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.jenis_transaksi', 'like', "%$request->value%")
                ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_pembelian->get();
        $count = ($data_pembelian->count() == 0) ? 0 : $data->count();
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

        $data_pembelian = DB::table('tr_pembelian_h')
            ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
            ->leftJoin('tr_penerimaan_h', 'tr_pembelian_h.kode_pembelian', '=', 'tr_penerimaan_h.kode_pembelian')
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
            ->select(
                'tr_pembelian_h.kode_pembelian',
                'tr_pembelian_h.tgl_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'tr_pembelian_h.pembelian',
                'tr_pembelian_h.jenis_transaksi',
                'tr_pembelian_h.tgl_jatuh_tempo',
                'tr_pembelian_h.kode_supplier',
                'm_supplier.nama_supplier',
                'tr_pembelian_h.diskon',
                'tr_pembelian_h.status_pembelian',
                'tr_pembelian_h.status_pembayaran',
                'tr_penerimaan_h.no_faktur',
                'tr_pembelian_h.kode_cabang',
                'm_cabang.nama_cabang',
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'm_produk.tipe',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total'
            )
            ->groupBy(
                'tr_pembelian_h.kode_pembelian',
                'tr_pembelian_h.tgl_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'tr_pembelian_h.pembelian',
                'tr_pembelian_h.jenis_transaksi',
                'tr_pembelian_h.tgl_jatuh_tempo',
                'tr_pembelian_h.kode_supplier',
                'm_supplier.nama_supplier',
                'tr_pembelian_h.diskon',
                'tr_pembelian_h.status_pembelian',
                'tr_pembelian_h.status_pembayaran',
                'tr_penerimaan_h.no_faktur',
                'tr_pembelian_h.kode_cabang',
                'm_cabang.nama_cabang',
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'm_produk.tipe',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total'
            )
            ->orderBy('tr_pembelian_h.kode_pembelian', 'DESC');

        if (!isset($request->value)) {
            $data_pembelian
                ->WhereBetween('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end]);
        } else {
            $data_pembelian
                ->where('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end])
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                ->orWhere('tr_pembelian_h.jenis_transaksi', 'like', "%$request->value%")
                ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_pembelian->get();
        $count = ($data_pembelian->count() == 0) ? 0 : $data->count();
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
                $data_pembelian_excel = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
                    ->leftJoin('tr_penerimaan_h', 'tr_pembelian_h.kode_pembelian', '=', 'tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
                    ->select(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->WhereBetween('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end])
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.jenis_transaksi', 'like', "%$request->value%")
                    ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%")
                    ->groupBy(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->orderBy('tr_pembelian_h.kode_pembelian', 'DESC')
                    ->get();
            } else {
                $data_pembelian_excel = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
                    ->leftJoin('tr_penerimaan_h', 'tr_pembelian_h.kode_pembelian', '=', 'tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
                    ->select(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->WhereBetween('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end])
                    ->groupBy(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->orderBy('tr_pembelian_h.kode_pembelian', 'DESC')
                    ->get();
            }
            return view('apotek.laporan.pembelian.view', compact('data_pembelian_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari != '') {
                $data_pembelian_pdf = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
                    ->leftJoin('tr_penerimaan_h', 'tr_pembelian_h.kode_pembelian', '=', 'tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
                    ->select(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->WhereBetween('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end])
                    ->where('m_produk.nama_produk', 'like', "%$request->value%")
                    ->orWhere('m_produk.tipe', 'like', "%$request->value%")
                    ->orWhere('m_cabang.nama_cabang', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.jenis_surat_pesanan', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.pembelian', 'like', "%$request->value%")
                    ->orWhere('tr_pembelian_h.jenis_transaksi', 'like', "%$request->value%")
                    ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%")
                    ->groupBy(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->orderBy('tr_pembelian_h.kode_pembelian', 'DESC')
                    ->get();
            } else {
                $data_pembelian_pdf = DB::table('tr_pembelian_h')
                    ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
                    ->leftJoin('tr_penerimaan_h', 'tr_pembelian_h.kode_pembelian', '=', 'tr_penerimaan_h.kode_pembelian')
                    ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
                    ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                    ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
                    ->select(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->WhereBetween('tr_pembelian_h.tgl_pembelian', [$date_start, $date_end])
                    ->groupBy(
                        'tr_pembelian_h.kode_pembelian',
                        'tr_pembelian_h.tgl_pembelian',
                        'tr_pembelian_h.jenis_surat_pesanan',
                        'tr_pembelian_h.pembelian',
                        'tr_pembelian_h.jenis_transaksi',
                        'tr_pembelian_h.tgl_jatuh_tempo',
                        'tr_pembelian_h.kode_supplier',
                        'm_supplier.nama_supplier',
                        'tr_pembelian_h.diskon',
                        'tr_pembelian_h.status_pembelian',
                        'tr_pembelian_h.status_pembayaran',
                        'tr_penerimaan_h.no_faktur',
                        'tr_pembelian_h.kode_cabang',
                        'm_cabang.nama_cabang',
                        'tr_pembelian_d.kode_produk',
                        'm_produk.nama_produk',
                        'm_produk.tipe',
                        'tr_pembelian_d.qty_kecil',
                        'tr_pembelian_d.qty_beli',
                        'tr_pembelian_d.harga',
                        'tr_pembelian_d.diskon_item',
                        'tr_pembelian_d.diskon_item_rp',
                        'tr_pembelian_d.ppn_item',
                        'tr_pembelian_d.ppn_item_rp',
                        'tr_pembelian_d.total'
                    )
                    ->orderBy('tr_pembelian_h.kode_pembelian', 'DESC')
                    ->get();
            }
            $pdf = PDF::loadview('laporan.pembelian.pdf', compact('data_pembelian_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        } elseif ($tombol_tgl == 'tgl') {
        }
    }
}
