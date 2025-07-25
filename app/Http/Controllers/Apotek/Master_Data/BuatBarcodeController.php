<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class BuatBarcodeController extends Controller
{
    public function index()
    {
        $data_barang_barcode = DB::table('m_produk')
            ->join('m_cabang', 'm_produk.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'm_produk.id',
                'm_produk.kode_produk',
                'm_produk.nama_produk',
                'm_produk.kode_cabang',
                'm_cabang.nama_cabang',
                'm_produk.barcode'
            )
            ->whereNotIn('m_produk.status', ['1'])
            ->whereNotIn('m_produk.tgl_kadaluarsa', ['0000-00-00'])
            ->get();

        return view('apotek.master_data.barcode.index', compact('data_barang_barcode'));
    }

    public function generate($id)
    {
        $qr_produk =  DB::table('m_produk')
            ->join('m_cabang', 'm_produk.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'm_produk.id',
                'm_produk.kode_produk',
                'm_produk.nama_produk',
                'm_produk.kode_cabang',
                'm_cabang.nama_cabang',
                'm_produk.barcode'
            )
            ->where('m_produk.id', $id)
            ->first();

        $qr_code = QrCode::size(100)
            ->generate($qr_produk->barcode);

        return view('apotek.master_data.barcode.view', compact('qr_code', 'qr_produk'));
    }

    public function pdf($id)
    {
        $qr_data = DB::table('m_produk')
            ->join('m_cabang', 'm_produk.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('m_produk_unit_varian', 'm_produk.kode_produk', '=', 'm_produk_unit_varian.kode_produk')
            ->select(
                'm_produk.id',
                'm_produk.kode_produk',
                'm_produk.nama_produk',
                'm_produk.kode_cabang',
                'm_cabang.nama_cabang',
                'm_produk.barcode',
                'm_produk_unit_varian.harga_jual'
            )
            ->where('m_produk.id', $id)
            ->get();

        $customPaper = array(0, 0, 567.00, 183.80);
        $pdf = PDF::loadview('master_data.barcode.pdf', compact('qr_data'))->setPaper($customPaper, 'landscape'); //landscape,portrait
        return $pdf->stream();
    }
}
