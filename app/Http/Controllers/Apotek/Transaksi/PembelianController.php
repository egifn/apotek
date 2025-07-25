<?php

namespace App\Http\Controllers\Apotek\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    public function index()
    {
        return view('apotek.tr_pembelian.index');
    }

    public function getDataPembelian()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));
        $kode_cabang = Auth::user()->kd_lokasi;

        if (Auth::user()->type == '1') {  //jika Super Admin
            // $data_pembelian = DB::table('tr_pembelian_h')
            // 			->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
            // 			->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
            //             ->join('m_cabang','tr_pembelian_h.kode_cabang','=','m_cabang.kode_cabang')
            // 			->join('users','tr_pembelian_h.id_user_input','=','users.id')
            //             ->select(
            //                 'tr_pembelian_h.kode_pembelian',
            //                 'tr_pembelian_h.jenis_surat_pesanan',
            //                 'tr_pembelian_h.tgl_pembelian',
            //                 'tr_pembelian_h.pembelian',
            //                 'tr_pembelian_h.waktu_pembelian',
            //                 'tr_pembelian_h.kode_supplier',
            //                 'm_supplier.nama_supplier',
            //                 DB::raw('SUM(tr_pembelian_d.total) as total'),
            //                 'tr_pembelian_h.jenis_transaksi',
            //                 'tr_pembelian_h.termin',
            //                 'tr_pembelian_h.tgl_jatuh_tempo',
            //                 'tr_pembelian_h.status_pembelian',
            //                 'tr_pembelian_h.status_pembayaran',
            //                 'tr_pembelian_h.id_user_input',
            //                 'users.name',
            //                 'tr_pembelian_h.kode_cabang',
            //                 'm_cabang.nama_cabang'   
            //             )
            //             ->WhereBetween('tr_pembelian_h.tgl_pembelian',[$date_start,$date_end])
            //             ->groupBy(
            //                 'tr_pembelian_h.kode_pembelian',
            //                 'tr_pembelian_h.jenis_surat_pesanan',
            //                 'tr_pembelian_h.tgl_pembelian',
            //                 'tr_pembelian_h.pembelian',
            //                 'tr_pembelian_h.waktu_pembelian',
            //                 'tr_pembelian_h.kode_supplier',
            //                 'm_supplier.nama_supplier', 
            //                 'tr_pembelian_h.status_pembelian',
            //                 'tr_pembelian_h.jenis_transaksi',
            //                 'tr_pembelian_h.termin',
            //                 'tr_pembelian_h.tgl_jatuh_tempo',
            //                 'tr_pembelian_h.status_pembayaran',
            //                 'tr_pembelian_h.id_user_input',
            //                 'users.name',
            //                 'tr_pembelian_h.kode_cabang',
            //                 'm_cabang.nama_cabang'
            //             );

            $data_pembelian = DB::select("SELECT
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier,
            COALESCE(SUM(tr_penerimaan_d.subtotal),0) AS total,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang   
            FROM tr_pembelian_h
            LEFT JOIN tr_penerimaan_h ON tr_pembelian_h.kode_pembelian = tr_penerimaan_h.kode_pembelian
            LEFT JOIN tr_penerimaan_d ON tr_penerimaan_h.kode_penerimaan = tr_penerimaan_d.kode_penerimaan
            INNER JOIN m_supplier ON tr_pembelian_h.kode_supplier = m_supplier.id
            INNER JOIN m_cabang ON tr_pembelian_h.kode_cabang = m_cabang.kode_cabang
            INNER JOIN users ON tr_pembelian_h.id_user_input = users.id
            WHERE tr_pembelian_h.tgl_pembelian BETWEEN '$date_start' AND '$date_end'
            GROUP BY
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier, 
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang");
        } else { //jika Admin
            // $data_pembelian = DB::table('tr_pembelian_h')
            // 			->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
            // 			->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
            //             ->join('m_cabang','tr_pembelian_h.kode_cabang','=','m_cabang.kode_cabang')
            // 			->join('users','tr_pembelian_h.id_user_input','=','users.id')
            //             ->select(
            //                 'tr_pembelian_h.kode_pembelian',
            //                 'tr_pembelian_h.jenis_surat_pesanan',
            //                 'tr_pembelian_h.tgl_pembelian',
            //                 'tr_pembelian_h.pembelian',
            //                 'tr_pembelian_h.waktu_pembelian',
            //                 'tr_pembelian_h.kode_supplier',
            //                 'm_supplier.nama_supplier',
            //                 DB::raw('SUM(tr_pembelian_d.total) as total'),
            //                 'tr_pembelian_h.jenis_transaksi',
            //                 'tr_pembelian_h.termin',
            //                 'tr_pembelian_h.tgl_jatuh_tempo',
            //                 'tr_pembelian_h.status_pembelian',
            //                 'tr_pembelian_h.status_pembayaran',
            //                 'tr_pembelian_h.id_user_input',
            //                 'users.name',
            //                 'tr_pembelian_h.kode_cabang',
            //                 'm_cabang.nama_cabang'   
            //             )
            //             ->WhereBetween('tr_pembelian_h.tgl_pembelian',[$date_start,$date_end])
            //             ->groupBy(
            //                 'tr_pembelian_h.kode_pembelian',
            //                 'tr_pembelian_h.jenis_surat_pesanan',
            //                 'tr_pembelian_h.tgl_pembelian',
            //                 'tr_pembelian_h.pembelian',
            //                 'tr_pembelian_h.waktu_pembelian',
            //                 'tr_pembelian_h.kode_supplier',
            //                 'm_supplier.nama_supplier', 
            //                 'tr_pembelian_h.status_pembelian',
            //                 'tr_pembelian_h.jenis_transaksi',
            //                 'tr_pembelian_h.termin',
            //                 'tr_pembelian_h.tgl_jatuh_tempo',
            //                 'tr_pembelian_h.status_pembayaran',
            //                 'tr_pembelian_h.id_user_input',
            //                 'users.name',
            //                 'tr_pembelian_h.kode_cabang',
            //                 'm_cabang.nama_cabang'
            //             );

            $data_pembelian = DB::select("SELECT
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier,
            COALESCE(SUM(tr_penerimaan_d.subtotal),0) AS total,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang   
            FROM tr_pembelian_h
            LEFT JOIN tr_penerimaan_h ON tr_pembelian_h.kode_pembelian = tr_penerimaan_h.kode_pembelian
            LEFT JOIN tr_penerimaan_d ON tr_penerimaan_h.kode_penerimaan = tr_penerimaan_d.kode_penerimaan
            INNER JOIN m_supplier ON tr_pembelian_h.kode_supplier = m_supplier.id
            INNER JOIN m_cabang ON tr_pembelian_h.kode_cabang = m_cabang.kode_cabang
            INNER JOIN users ON tr_pembelian_h.id_user_input = users.id
            WHERE tr_pembelian_h.tgl_pembelian BETWEEN '$date_start' AND '$date_end'
            AND tr_pembelian_h.kode_cabang = '$kode_cabang'
            GROUP BY
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier, 
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang");
        }

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_pembelian
        ];

        return response()->json($output, 200);
    }

    public function cari(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');
        $date = explode(' - ', $request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');
        $kode_cabang = Auth::user()->kd_lokasi;

        if (Auth::user()->type == '1') {  //jika Super Admin
            // $query = DB::table('tr_pembelian_h')
            // ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
            // ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
            // ->join('m_cabang','tr_pembelian_h.kode_cabang','=','m_cabang.kode_cabang')
            // ->join('users','tr_pembelian_h.id_user_input','=','users.id')
            // ->select(
            //     'tr_pembelian_h.kode_pembelian',
            //     'tr_pembelian_h.jenis_surat_pesanan',
            //     'tr_pembelian_h.tgl_pembelian',
            //     'tr_pembelian_h.pembelian',
            //     'tr_pembelian_h.waktu_pembelian',
            //     'tr_pembelian_h.kode_supplier',
            //     'm_supplier.nama_supplier',
            //     DB::raw('SUM(tr_pembelian_d.total) as total'),
            //     'tr_pembelian_h.jenis_transaksi',
            //     'tr_pembelian_h.termin',
            //     'tr_pembelian_h.tgl_jatuh_tempo',
            //     'tr_pembelian_h.status_pembelian',
            //     'tr_pembelian_h.status_pembayaran',
            //     'tr_pembelian_h.id_user_input',
            //     'users.name',
            //     'tr_pembelian_h.kode_cabang',
            //     'm_cabang.nama_cabang'   
            // )
            // ->WhereBetween('tr_pembelian_h.tgl_pembelian',[$date_start,$date_end])
            // ->groupBy(
            //     'tr_pembelian_h.kode_pembelian',
            //     'tr_pembelian_h.jenis_surat_pesanan',
            //     'tr_pembelian_h.tgl_pembelian',
            //     'tr_pembelian_h.pembelian',
            //     'tr_pembelian_h.waktu_pembelian',
            //     'tr_pembelian_h.kode_supplier',
            //     'm_supplier.nama_supplier', 
            //     'tr_pembelian_h.status_pembelian',
            //     'tr_pembelian_h.jenis_transaksi',
            //     'tr_pembelian_h.termin',
            //     'tr_pembelian_h.tgl_jatuh_tempo',
            //     'tr_pembelian_h.status_pembayaran',
            //     'tr_pembelian_h.id_user_input',
            //     'users.name',
            //     'tr_pembelian_h.kode_cabang',
            //     'm_cabang.nama_cabang'
            // );

            $query = DB::select("SELECT
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier,
            COALESCE(SUM(tr_penerimaan_d.subtotal),0) AS total,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang   
            FROM tr_pembelian_h
            LEFT JOIN tr_penerimaan_h ON tr_pembelian_h.kode_pembelian = tr_penerimaan_h.kode_pembelian
            LEFT JOIN tr_penerimaan_d ON tr_penerimaan_h.kode_penerimaan = tr_penerimaan_d.kode_penerimaan
            INNER JOIN m_supplier ON tr_pembelian_h.kode_supplier = m_supplier.id
            INNER JOIN m_cabang ON tr_pembelian_h.kode_cabang = m_cabang.kode_cabang
            INNER JOIN users ON tr_pembelian_h.id_user_input = users.id
            WHERE tr_pembelian_h.tgl_pembelian BETWEEN '$date_start' AND '$date_end'
            GROUP BY
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier, 
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang");
        } else { //jika Admin
            // $query = DB::table('tr_pembelian_h')
            // ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
            // ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
            // ->join('m_cabang','tr_pembelian_h.kode_cabang','=','m_cabang.kode_cabang')
            // ->join('users','tr_pembelian_h.id_user_input','=','users.id')
            // ->select(
            //     'tr_pembelian_h.kode_pembelian',
            //     'tr_pembelian_h.jenis_surat_pesanan',
            //     'tr_pembelian_h.tgl_pembelian',
            //     'tr_pembelian_h.pembelian',
            //     'tr_pembelian_h.waktu_pembelian',
            //     'tr_pembelian_h.kode_supplier',
            //     'm_supplier.nama_supplier',
            //     DB::raw('SUM(tr_pembelian_d.total) as total'),
            //     'tr_pembelian_h.jenis_transaksi',
            //     'tr_pembelian_h.termin',
            //     'tr_pembelian_h.tgl_jatuh_tempo',
            //     'tr_pembelian_h.status_pembelian',
            //     'tr_pembelian_h.status_pembayaran',
            //     'tr_pembelian_h.id_user_input',
            //     'users.name',
            //     'tr_pembelian_h.kode_cabang',
            //     'm_cabang.nama_cabang'   
            // )
            // ->WhereBetween('tr_pembelian_h.tgl_pembelian',[$date_start,$date_end])
            // ->groupBy(
            //     'tr_pembelian_h.kode_pembelian',
            //     'tr_pembelian_h.jenis_surat_pesanan',
            //     'tr_pembelian_h.tgl_pembelian',
            //     'tr_pembelian_h.pembelian',
            //     'tr_pembelian_h.waktu_pembelian',
            //     'tr_pembelian_h.kode_supplier',
            //     'm_supplier.nama_supplier', 
            //     'tr_pembelian_h.status_pembelian',
            //     'tr_pembelian_h.jenis_transaksi',
            //     'tr_pembelian_h.termin',
            //     'tr_pembelian_h.tgl_jatuh_tempo',
            //     'tr_pembelian_h.status_pembayaran',
            //     'tr_pembelian_h.id_user_input',
            //     'users.name',
            //     'tr_pembelian_h.kode_cabang',
            //     'm_cabang.nama_cabang'
            // );

            $query = DB::select("SELECT
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier,
            COALESCE(SUM(tr_penerimaan_d.subtotal),0) AS total,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang   
            FROM tr_pembelian_h
            LEFT JOIN tr_penerimaan_h ON tr_pembelian_h.kode_pembelian = tr_penerimaan_h.kode_pembelian
            LEFT JOIN tr_penerimaan_d ON tr_penerimaan_h.kode_penerimaan = tr_penerimaan_d.kode_penerimaan
            INNER JOIN m_supplier ON tr_pembelian_h.kode_supplier = m_supplier.id
            INNER JOIN m_cabang ON tr_pembelian_h.kode_cabang = m_cabang.kode_cabang
            INNER JOIN users ON tr_pembelian_h.id_user_input = users.id
            WHERE tr_pembelian_h.tgl_pembelian BETWEEN '$date_start' AND '$date_end'
            AND tr_pembelian_h.kode_cabang = '$kode_cabang'
            GROUP BY
            tr_pembelian_h.kode_pembelian,
            tr_pembelian_h.jenis_surat_pesanan,
            tr_pembelian_h.tgl_pembelian,
            tr_pembelian_h.pembelian,
            tr_pembelian_h.waktu_pembelian,
            tr_pembelian_h.kode_supplier,
            m_supplier.nama_supplier, 
            tr_pembelian_h.status_pembelian,
            tr_pembelian_h.jenis_transaksi,
            tr_pembelian_h.termin,
            tr_pembelian_h.tgl_jatuh_tempo,
            tr_pembelian_h.status_pembayaran,
            tr_pembelian_h.id_user_input,
            users.name,
            tr_pembelian_h.kode_cabang,
            m_cabang.nama_cabang");
        }

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $query
        ];

        return response()->json($output, 200);
    }

    public function create(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $date = now()->format('dmy');

        $getRow = DB::table('tr_pembelian_h')->select(DB::raw('MAX(RIGHT(kode_pembelian,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_pembelian', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        $cart = session("cart");

        return view('apotek.tr_pembelian.create', compact('kode', 'cart'));
    }

    public function getSupplier(Request $request)
    {
        $data_supplier = DB::table('m_supplier');

        $data = $data_supplier->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function getProduk(Request $request)
    {
        // $data_produk = DB::table('m_produk')
        //             ->join('m_kategori_obat','m_produk.id_kategori','=','m_kategori_obat.id')
        //             ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
        //             ->join('m_produk_unit','m_produk.id_unit','=','m_produk_unit.id');

        $data_produk = DB::table('m_produk')
            ->join('m_jenis_obat', 'm_produk.id_jenis', '=', 'm_jenis_obat.id')
            ->join('m_cabang', 'm_produk.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->leftJoin('m_supplier', 'm_produk.id_supplier', '=', 'm_supplier.id')
            ->join('m_produk_unit_varian', 'm_produk.kode_produk', '=', 'm_produk_unit_varian.kode_produk')
            ->join('m_produk_unit', 'm_produk_unit_varian.id_produk_unit', '=', 'm_produk_unit.id')
            ->join('users', 'm_produk.id_user_input', '=', 'users.id')
            ->select(
                'm_produk.kode_produk',
                'm_produk.barcode',
                'm_produk.nama_produk',
                'm_produk.id_jenis',
                'm_jenis_obat.nama_jenis',
                'm_produk.kode_pembelian',
                'm_produk.tipe',
                'm_produk.tgl_kadaluarsa',
                'm_produk.no_batch',
                'm_produk.id_supplier',
                'nama_supplier',
                'm_produk.qty',
                'm_produk_unit_varian.qty AS qty_unit',
                'm_produk_unit_varian.id_produk_unit',
                'm_produk_unit.nama_unit',
                'm_produk_unit_varian.harga_beli',
                'm_produk_unit_varian.margin_persen',
                'm_produk_unit_varian.margin_rp',
                'm_produk_unit_varian.harga_jual',
                'm_produk.qty_min',
                'm_produk.kode_cabang',
                'm_cabang.nama_cabang',
                'm_produk.id_user_input',
                'users.name'
            )
            ->where('m_produk.kode_cabang', Auth::user()->kd_lokasi);
        //->limit(5);

        $data = $data_produk->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function getProdukModal(Request $request)
    {
        $data_produk = DB::table('m_produk')
            ->join('m_jenis_obat', 'm_produk.id_jenis', '=', 'm_jenis_obat.id')
            ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id');
        //->limit(5);
        if (!isset($request->value)) {
        } else {
            $data_produk->where('nama_produk', 'like', "%$request->value%");
        }

        $data = $data_produk->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function getProdukPilih($kode, $unit_varian)
    {
        // $data_barang = DB::table('m_produk')
        //                 ->join('m_kategori_obat','m_produk.id_kategori','=','m_kategori_obat.id')
        //                 ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
        //                 ->join('m_produk_unit','m_produk.id_unit','=','m_produk_unit.id')
        //                 ->where('m_produk.kode_produk', $kode)
        //                 ->first();

        $data_barang = DB::table('m_produk')
            ->join('m_jenis_obat', 'm_produk.id_jenis', '=', 'm_jenis_obat.id')
            ->join('m_cabang', 'm_produk.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->leftJoin('m_supplier', 'm_produk.id_supplier', '=', 'm_supplier.id')
            ->join('m_produk_unit_varian', 'm_produk.kode_produk', '=', 'm_produk_unit_varian.kode_produk')
            ->join('m_produk_unit', 'm_produk_unit_varian.id_produk_unit', '=', 'm_produk_unit.id')
            ->join('users', 'm_produk.id_user_input', '=', 'users.id')
            ->select(
                'm_produk.kode_produk',
                'm_produk.barcode',
                'm_produk.nama_produk',
                'm_produk.komposisi',
                'm_produk.id_jenis',
                'm_jenis_obat.nama_jenis',
                'm_produk.kode_pembelian',
                'm_produk.tipe',
                'm_produk.tgl_kadaluarsa',
                'm_produk.no_batch',
                'm_produk.id_supplier',
                'nama_supplier',
                'm_produk.qty',
                'm_produk_unit_varian.qty AS qty_unit',
                'm_produk_unit_varian.id_produk_unit',
                'm_produk_unit.nama_unit',
                'm_produk_unit_varian.harga_beli',
                'm_produk_unit_varian.margin_persen',
                'm_produk_unit_varian.margin_rp',
                'm_produk_unit_varian.harga_jual',
                'm_produk.qty_min',
                'm_produk.kode_cabang',
                'm_cabang.nama_cabang',
                'm_produk.id_user_input',
                'users.name'
            )
            ->where('m_produk.kode_produk', $kode)
            ->where('m_produk_unit_varian.id_produk_unit', $unit_varian)
            ->first();

        return response()->json([
            'data' => $data_barang
        ]);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));

        $date = (date('dmy'));
        $getRow = DB::table('tr_pembelian_h')->select(DB::raw('MAX(RIGHT(kode_pembelian,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_pembelian', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "SP" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        // Header //
        DB::table('tr_pembelian_h')->insert([
            'kode_pembelian' => $kode,
            'tgl_pembelian' => Carbon::now()->format('Y-m-d'),
            'waktu_pembelian' => $time,
            'jenis_surat_pesanan' => $request->jenis_surat_pesanan,
            'pembelian' => $request->pembelian,
            'jenis_transaksi' => $request->jenis_pembelian,
            'termin' => $request->termin,
            'tgl_jatuh_tempo' => Carbon::createFromFormat('d/m/Y', $request->jt)->format('Y-m-d'), //$request->jt,
            'kode_supplier' => $request->kode_supplier,
            'diskon' => $request->diskon,
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);
        // End Header //

        // Detail //
        $kode_produk = $request->kode_produk;
        $jml_qty = $request->jml_qty;
        $harga_beli = str_replace(",", "", $request->harga_beli);

        $margin_rp_lama = $request->margin_rp_lama;
        $margin_persen_lama = $request->margin_persen_lama;
        $harga_jual_lama = $request->harga_jual_lama;

        $tambah_jml = $request->tambah_jml;
        $tambah_diskon = $request->tambah_diskon;
        $tambah_diskon_rp = str_replace(",", "", $request->tambah_diskon_rp);
        $tambah_ppn = $request->tambah_ppn;
        $tambah_ppn_rp = str_replace(",", "", $request->tambah_ppn_rp);
        $kode_nama_unit = $request->kode_nama_unit;

        for ($i = 0; $i < count((array)$kode_produk); $i++) {
            $stok_varian = DB::table('m_produk_unit_varian')
                ->select('m_produk_unit_varian.qty')
                ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
                ->where('m_produk_unit_varian.id_produk_unit', $kode_nama_unit[$i])->first();

            DB::table('tr_pembelian_d')->insert([
                'kode_pembelian' => $kode,
                'kode_produk' => $kode_produk[$i],
                'qty_kecil' => $stok_varian->qty * $tambah_jml[$i],
                'qty_beli' => $tambah_jml[$i],
                'id_unit' => $kode_nama_unit[$i],
                'harga' => $harga_beli[$i],

                'margin_rp_lama' => $margin_rp_lama[$i],
                'margin_persen_lama' => $margin_persen_lama[$i],
                'harga_jual_lama' => $harga_jual_lama[$i],

                'diskon_item' => $tambah_diskon[$i],
                'diskon_item_rp' => $tambah_diskon_rp[$i],
                'ppn_item' => $tambah_ppn[$i],
                'ppn_item_rp' => $tambah_ppn_rp[$i],
                'total' => $tambah_jml[$i] * $harga_beli[$i] - $tambah_diskon_rp[$i] + $tambah_ppn_rp[$i]
            ]);
        }
        // End Detail //

        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function terima(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));

        $date = (date('dmy'));
        $getRow = DB::table('tr_penerimaan_h')->select(DB::raw('MAX(RIGHT(kode_penerimaan,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_penerimaan', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "TB" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "TB" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "TB" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "TB" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "TB" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        DB::beginTransaction();
        try {
            // Header //
            DB::table('tr_penerimaan_h')->insert([
                'kode_penerimaan' => $kode,
                'tgl_penerimaan' => Carbon::now()->format('Y-m-d'),
                'waktu_penerimaan' => $time,
                'kode_pembelian' => $request->kode_pembelian,
                'no_faktur' => $request->no_faktur,
                'diskon_rupiah' => str_replace(",", "", $request->diskon_all_rupiah),
                'diskon_persen' => $request->diskon_all_persen,
                'subtotal' => str_replace(",", "", $request->subtotal),
                'id_user_input' => Auth::user()->id,
                'kode_cabang' => Auth::user()->kd_lokasi
            ]);
            // End Header //

            // untuk Detail //
            $kode_produk = $request->kode_produk;
            $harga_beli_lama = $request->harga_beli_lama;
            $margin_persen_lama = $request->margin_persen_lama;
            $margin_rp_lama = $request->margin_rp_lama;
            $harga_jual_lama = $request->harga_jual_lama;
            $no_batch = $request->no_batch;
            $tgl_kadaluarsa = $request->tgl_kadaluarsa;
            $harga_beli = $request->harga_beli;
            $harga_margin = $request->harga_margin;
            $margin_persen = $request->margin_persen;
            $margin_rupiah = $request->margin_rupiah;
            $jml_pesan = $request->jml_pesan;
            $jml_terima = $request->jml_terima;
            $id_produk_unit = $request->id_produk_unit;
            $diskon_persen = $request->diskon_persen;
            $diskon_rupiah = $request->diskon_rupiah;
            $ppn_persen = $request->ppn_persen;
            $ppn_rupiah = $request->ppn_rupiah;
            $total = $request->total;

            for ($i = 0; $i < count((array)$kode_produk); $i++) {
                //dd($harga_margin[$i]);
                DB::table('tr_penerimaan_d')->insert([

                    'kode_penerimaan' => $kode,
                    'kode_produk' => $kode_produk[$i],
                    'harga_beli_lama' => str_replace(",", "", $harga_beli_lama[$i]),
                    'margin_persen_lama' => $margin_persen_lama[$i],
                    'margin_rp_lama' => $margin_rp_lama[$i],
                    'harga_jual_lama' => str_replace(",", "", $harga_jual_lama[$i]),
                    'no_batch' => $no_batch[$i],
                    'tgl_kadaluarsa' => $tgl_kadaluarsa[$i],
                    'harga_beli' => str_replace(",", "", $harga_beli[$i]),
                    'margin_persen' => str_replace(",", "", $margin_persen[$i]),
                    'margin_rupiah' => str_replace(",", "", $margin_rupiah[$i]),
                    'jml_beli' => $jml_pesan[$i],
                    'jml_terima' => $jml_terima[$i],
                    'id_produk_unit' => $id_produk_unit[$i],
                    'diskon_persen' => $diskon_persen[$i],
                    'diskon_rp' => str_replace(",", "", $diskon_rupiah[$i]),
                    'ppn_persen' => $ppn_persen[$i],
                    'ppn_rp' => str_replace(",", "", $ppn_rupiah[$i]),
                    'subtotal' => str_replace(",", "", $total[$i])
                ]);

                $stok = DB::table('m_produk')
                    ->select('m_produk.qty')
                    ->where('m_produk.kode_produk', $kode_produk[$i])->first();

                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $kode_produk[$i],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Pembelian dengan No Invoice: ' . $kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => $jml_terima[$i],
                    'stok_keluar' => 0,
                    'stok_sisa' => $stok->qty + $jml_terima[$i],
                    'type' => 'Beli',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
                // pencatatan ke Stok_in_out (keluar masuk barang) //

                // untuk update //
                $stok_update = DB::table('m_produk')
                    ->where('m_produk.kode_produk', $kode_produk[$i])
                    ->update([
                        'qty' => $stok->qty + $jml_terima[$i],
                        //'harga_beli' => $harga_beli[$i],
                        'no_batch' => $no_batch[$i],
                        'tgl_kadaluarsa' => $tgl_kadaluarsa[$i],
                        'id_supplier' => $request->kd_supplier
                    ]);
                // end untuk update //


                // untuk update //
                if ($jml_terima[$i] != 0) {
                    $stok_update_varian = DB::table('m_produk_unit_varian')
                        ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
                        ->where('m_produk_unit_varian.id_produk_unit', $id_produk_unit[$i])
                        ->update([
                            // 'harga_beli' =>  str_replace(",", "", $total[$i])/$jml_terima[$i],
                            'harga_beli' =>  str_replace(",", "", $total[$i]) / $jml_pesan[$i],
                            'margin_rp' => str_replace(",", "", $harga_margin[$i]),
                            'margin_persen' => str_replace(",", "",  $margin_persen[$i]),
                            // 'harga_jual' => (str_replace(",", "", $total[$i])/$jml_terima[$i]) + str_replace(",", "", $harga_margin[$i]) 
                            'harga_jual' => (str_replace(",", "", $total[$i]) / $jml_pesan[$i]) + str_replace(",", "", $harga_margin[$i])
                        ]);
                }

                // end untuk update //
            }

            $status_update = DB::table('tr_pembelian_h')
                ->where('tr_pembelian_h.kode_pembelian', $request->kode_pembelian)
                ->update([
                    'status_pembelian' => 1
                ]);

            DB::commit();

            $output = [
                'msg'  => 'Transaksi baru berhasil ditambah',
                'res'  => true,
                'type' => 'success'
            ];
            return response()->json($output, 200);
        } catch (\Exception $e) {
            DB::rollback();

            $output = [
                'msg'  => 'Terjadi adanya kesalahan, mohon untuk melakukan pengecekan kembali: ' . $e->getMessage(),
                'res'  => false,
                'type' => 'error'
            ];
            return response()->json($output, 200);
        }
    }

    public function getViewPembelian(Request $request)
    {
        $dataPembelian = DB::table('tr_pembelian_h')
            ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
            ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_pembelian_d.id_unit', '=', 'm_produk_unit.id')
            //->join('m_produk_unit_varian', 'tr_pembelian_d.kode_produk', '=', 'm_produk_unit_varian.kode_produk')
            ->join('m_produk_unit_varian', function ($join) {
                $join->on('tr_pembelian_d.kode_produk', '=', 'm_produk_unit_varian.kode_produk');
                $join->on('tr_pembelian_d.id_unit', '=', 'm_produk_unit_varian.id_produk_unit');
            })
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('users', 'tr_pembelian_h.id_user_input', '=', 'users.id')
            ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'm_produk_unit_varian.qty AS qty_unit_kecil',
                'm_produk_unit_varian.margin_persen',
                'm_produk_unit_varian.margin_rp',
                'm_produk_unit.nama_unit',
                'm_produk_unit.id',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total',
                'tr_pembelian_d.margin_rp_lama',
                'tr_pembelian_d.margin_persen_lama',
                'tr_pembelian_d.harga_jual_lama'
            )
            ->where('tr_pembelian_h.kode_pembelian', $request->kode_pembelian)
            ->groupBy(
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'm_produk_unit_varian.qty',
                'm_produk_unit_varian.margin_persen',
                'm_produk_unit_varian.margin_rp',
                'm_produk_unit.nama_unit',
                'm_produk_unit.id',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total',
                'tr_pembelian_d.margin_rp_lama',
                'tr_pembelian_d.margin_persen_lama',
                'tr_pembelian_d.harga_jual_lama'
            )
            ->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $dataPembelian
        ];

        return response()->json($output, 200);
    }

    public function getViewPenerimanPembelian(Request $request)
    {
        $query_penerimaan = DB::select("SELECT tr_penerimaan_d.kode_produk,m_produk.nama_produk,tr_penerimaan_d.harga_beli,tr_penerimaan_d.jml_beli,tr_penerimaan_d.jml_terima,m_produk_unit_varian.qty AS qty_unit_kecil,m_produk_unit_varian.margin_persen,m_produk_unit_varian.margin_rp,m_produk_unit.nama_unit,m_produk_unit.id,
                    tr_penerimaan_d.diskon_persen,tr_penerimaan_d.diskon_rp,tr_penerimaan_d.ppn_persen,tr_penerimaan_d.ppn_rp,tr_penerimaan_d.subtotal
                    FROM tr_pembelian_h 
                    LEFT JOIN tr_penerimaan_h ON tr_pembelian_h.kode_pembelian = tr_penerimaan_h.kode_pembelian
                    LEFT JOIN tr_penerimaan_d ON tr_penerimaan_h.kode_penerimaan = tr_penerimaan_d.kode_penerimaan
                    INNER JOIN m_produk ON tr_penerimaan_d.kode_produk = m_produk.kode_produk
                    INNER JOIN m_produk_unit ON tr_penerimaan_d.id_produk_unit = m_produk_unit.id
                    INNER JOIN m_produk_unit_varian ON tr_penerimaan_d.kode_produk = m_produk_unit_varian.kode_produk
                    AND tr_penerimaan_d.id_produk_unit = m_produk_unit_varian.id_produk_unit 
                    INNER JOIN m_supplier ON tr_pembelian_h.kode_supplier = m_supplier.id
                    INNER JOIN users ON tr_pembelian_h.id_user_input = users.id
                    INNER JOIN m_cabang ON tr_pembelian_h.kode_cabang = m_cabang.kode_cabang 
                        
                    WHERE tr_pembelian_h.kode_pembelian = '$request->kode_pembelian'
                    GROUP BY tr_penerimaan_d.kode_produk,m_produk.nama_produk,tr_penerimaan_d.harga_beli,tr_penerimaan_d.jml_beli,tr_penerimaan_d.jml_terima,m_produk_unit_varian.qty,m_produk_unit_varian.margin_persen,m_produk_unit_varian.margin_rp,m_produk_unit.nama_unit,m_produk_unit.id,
                    tr_penerimaan_d.diskon_persen,tr_penerimaan_d.diskon_rp,tr_penerimaan_d.ppn_persen,tr_penerimaan_d.ppn_rp,tr_penerimaan_d.subtotal");

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $query_penerimaan
        ];

        return response()->json($output, 200);
    }

    public function pdf(Request $request)
    {
        $data = DB::table('tr_pembelian_h')
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('users', 'tr_pembelian_h.id_user_input', '=', 'users.id')
            // ->join('m_pegawai','users.type','m_pegawai.id')
            ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_pembelian_h.kode_pembelian',
                'tr_pembelian_h.tgl_pembelian',
                'tr_pembelian_h.jenis_surat_pesanan',
                'users.name',
                'm_supplier.nama_supplier',
                'm_supplier.alamat',
                'm_supplier.tlp',
                'm_cabang.alamat as alamat_cabang',
                'm_cabang.tlp as tlp_cabang'
            )
            ->where('tr_pembelian_h.kode_pembelian', $request->kode_pembelian)
            ->first();

        $dataPembelian = DB::table('tr_pembelian_h')
            ->join('tr_pembelian_d', 'tr_pembelian_h.kode_pembelian', '=', 'tr_pembelian_d.kode_pembelian')
            ->join('m_produk', 'tr_pembelian_d.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_pembelian_d.id_unit', '=', 'm_produk_unit.id')
            ->join('m_supplier', 'tr_pembelian_h.kode_supplier', '=', 'm_supplier.id')
            ->join('users', 'tr_pembelian_h.id_user_input', '=', 'users.id')
            ->join('m_cabang', 'tr_pembelian_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_pembelian_d.kode_produk',
                'm_produk.nama_produk',
                'tr_pembelian_d.harga',
                'tr_pembelian_d.qty_kecil',
                'tr_pembelian_d.qty_beli',
                'm_produk_unit.nama_unit',
                'tr_pembelian_d.diskon_item',
                'tr_pembelian_d.diskon_item_rp',
                'tr_pembelian_d.ppn_item',
                'tr_pembelian_d.ppn_item_rp',
                'tr_pembelian_d.total'
            )
            ->where('tr_pembelian_h.kode_pembelian', $request->kode_pembelian)
            ->get();

        if ($data->jenis_surat_pesanan == 'Narkotika') {
            $pdf = PDF::loadview('tr_pembelian.pdf', compact('dataPembelian', 'data'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        } elseif ($data->jenis_surat_pesanan == 'Psikotropika') {
            $pdf = PDF::loadview('tr_pembelian.pdf_psikoto', compact('dataPembelian', 'data'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        } elseif ($data->jenis_surat_pesanan == 'Prekursor') {
            $pdf = PDF::loadview('tr_pembelian.pdf_prekusor', compact('dataPembelian', 'data'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        } elseif ($data->jenis_surat_pesanan == 'Umum/Regular') {
            $pdf = PDF::loadview('tr_pembelian.pdf_umum', compact('dataPembelian', 'data'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        } else {
            $pdf = PDF::loadview('tr_pembelian.pdf', compact('dataPembelian', 'data'))->setPaper('a4', 'portrait');
            return $pdf->stream();
        }
    }

    public function getTambahCart($kode_produk)
    {
        $cart = session("cart");

        $data_barang = DB::table('m_produk')
            ->join('m_kategori_obat', 'm_produk.id_kategori', '=', 'm_kategori_obat.id')
            ->join('m_jenis_obat', 'm_produk.id_jenis', '=', 'm_jenis_obat.id')
            ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
            ->where('m_produk.kode_produk', $kode_produk)
            ->first();

        $cart["kode_produk"] = [
            "kode_produk" => $data_barang->kode_produk,
            "nama_produk" => $data_barang->nama_produk,
            "satuan" => $data_barang->nama_unit,
            "stok" => $data_barang->qty,
            "harga_satuan" => $data_barang->harga_jual,
            "jumlah_beli" => 1
        ];

        session(["cart" => $cart]);
    }
}
