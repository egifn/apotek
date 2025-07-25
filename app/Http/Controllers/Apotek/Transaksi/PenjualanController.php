<?php

namespace App\Http\Controllers\Apotek\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Tr_Penjualan_H;
use App\Tr_Penjualan_D;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('apotek.tr_penjualan.index');
    }

    public function getDataPenjualan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_penjualan = DB::table('tr_penjualan_h')
                ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    DB::raw('SUM(tr_penjualan_h.total_bayar) as total'),
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start, $date_end])
                ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->groupBy(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                );
        } else { //jika Admin
            $data_penjualan = DB::table('tr_penjualan_h')
                ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    DB::raw('SUM(tr_penjualan_h.total_bayar) as total'),
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start, $date_end])
                ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->groupBy(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                );
        }

        $data = $data_penjualan->get();
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

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('tr_penjualan_h')
                ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    DB::raw('SUM(tr_penjualan_h.total_bayar) as total'),
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start, $date_end])
                ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->groupBy(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                );
        } else { //jika Admin
            $query = DB::table('tr_penjualan_h')
                ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    DB::raw('SUM(tr_penjualan_h.total_bayar) as total'),
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                ->WhereBetween('tr_penjualan_h.tgl_penjualan', [$date_start, $date_end])
                ->whereNotIn('tr_penjualan_h.status_bayar', ['Batal'])
                ->groupBy(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.waktu_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    'tr_penjualan_h.cara_bayar',
                    'tr_penjualan_h.status_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                );
        }

        $data  = $query->get();

        $count = ($query->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function create(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = (date('dmy'));
        $getRow = DB::table('tr_penjualan_h')->select(DB::raw('MAX(RIGHT(kode_penjualan,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_penjualan', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        return view('apotek.tr_penjualan.create', compact('kode'));
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
            ->where('m_produk.kode_cabang', Auth::user()->kd_lokasi)
            ->where('m_produk.status', 0);
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
        $getRow = DB::table('tr_penjualan_h')->select(DB::raw('MAX(RIGHT(kode_penjualan,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_penjualan', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "TJ" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        // Header //
        if ($request->jenis_penjualan == 'Panel') {
            DB::table('tr_penjualan_h')->insert([
                'kode_penjualan' => $kode,
                'tgl_penjualan' => Carbon::now()->format('Y-m-d'),
                'waktu_penjualan' => $time,
                'jenis_penjualan' => $request->jenis_penjualan,
                'faktur_panel' => $request->no_faktur,
                'nama_pembeli' => $request->nama_pembeli,
                'no_tlp' => $request->no_tlp,
                'termin' => $request->termin,
                'tgl_jatuh_tempo' => Carbon::createFromFormat('d/m/Y', $request->tgl_jatuh_tempo)->format('Y-m-d'),
                'status_bayar' => 'Belum Bayar',
                'cara_bayar' => $request->cara_bayar,
                'bank' => $request->bank,
                'subtotal' =>  str_replace(",", "", $request->subtotal),
                'pembulatan' => str_replace(",", "", $request->pembulatan),
                'total_bayar' => str_replace(",", "", $request->total_bayar),
                'jml_bayar' => str_replace(",", "", $request->jml_bayar),
                'kembali' => str_replace(",", "", $request->kembali),
                'id_user_input' => Auth::user()->id,
                'kode_cabang' => Auth::user()->kd_lokasi
            ]);
        } else {
            DB::table('tr_penjualan_h')->insert([
                'kode_penjualan' => $kode,
                'tgl_penjualan' => Carbon::now()->format('Y-m-d'),
                'waktu_penjualan' => $time,
                'jenis_penjualan' => $request->jenis_penjualan,
                'nama_pembeli' => $request->nama_pembeli,
                'no_tlp' => $request->no_tlp,
                'termin' => $request->termin,
                'tgl_jatuh_tempo' => Carbon::createFromFormat('d/m/Y', $request->tgl_jatuh_tempo)->format('Y-m-d'),
                'status_bayar' => 'Sudah Bayar',
                'cara_bayar' => $request->cara_bayar,
                'bank' => $request->bank,
                'subtotal' =>  str_replace(",", "", $request->subtotal),
                'pembulatan' => str_replace(",", "", $request->pembulatan),
                'total_bayar' => str_replace(",", "", $request->total_bayar),
                'jml_bayar' => str_replace(",", "", $request->jml_bayar),
                'kembali' => str_replace(",", "", $request->kembali),
                'id_user_input' => Auth::user()->id,
                'kode_cabang' => Auth::user()->kd_lokasi
            ]);
        }
        // End Header //

        // Detail //
        $kode_produk = $request->kode_produk;
        $jml_qty = $request->jml_qty;
        $harga_jual =  str_replace(",", "", $request->harga_jual);
        $tambah_jml = $request->tambah_jml;
        $tambah_diskon = $request->tambah_diskon;
        $tambah_diskon_rp = str_replace(",", "", $request->tambah_diskon_rp);
        $tambah_ppn = $request->tambah_ppn;
        $tambah_ppn_rp = str_replace(",", "", $request->tambah_ppn_rp);
        $kode_nama_unit = $request->kode_nama_unit;
        $biaya_tambahan = $request->biaya_tambahan;
        $tuslah = $request->tuslah;
        $embalase = $request->embalase;

        for ($i = 0; $i < count((array)$kode_produk); $i++) {
            $stok_varian = DB::table('m_produk_unit_varian')
                ->select('m_produk_unit_varian.qty')
                ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
                ->where('m_produk_unit_varian.id_produk_unit', $kode_nama_unit[$i])->first();

            DB::table('tr_penjualan_d')->insert([
                'kode_penjualan' => $kode,
                'kode_produk' => $kode_produk[$i],
                'qty_kecil' => $stok_varian->qty * $tambah_jml[$i],
                'qty' => $tambah_jml[$i],
                'id_produk_unit' => $kode_nama_unit[$i],
                'harga' => $harga_jual[$i],
                'diskon' => $tambah_diskon[$i],
                'diskon_rp' => $tambah_diskon_rp[$i],
                'ppn' => $tambah_ppn[$i],
                'ppn_rp' => $tambah_ppn_rp[$i],
                'biaya_tambahan' => $biaya_tambahan[$i],
                'tuslah' => $tuslah[$i],
                'embalase' => $embalase[$i],
                'total' =>  $tambah_jml[$i] * $harga_jual[$i] - $tambah_diskon_rp[$i] + $tambah_ppn_rp[$i] + $biaya_tambahan[$i] + $tuslah[$i] + $embalase[$i]
            ]);

            // pencatatan ke Stok_in_out (keluar masuk barang) //
            DB::table('stok_in_out')->insert([
                'id_produk' => $kode_produk[$i],
                'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                'waktu_in_out' => $time,
                'no_bukti' => $kode,
                'keterangan' => 'Penjualan dengan No Faktur: ' . $kode,
                'stok_awal' => $jml_qty[$i],
                'stok_masuk' => 0,
                'stok_keluar' => $stok_varian->qty * $tambah_jml[$i],
                'stok_sisa' => $jml_qty[$i] - ($stok_varian->qty * $tambah_jml[$i]),
                'type' => 'Jual',
                'id_user_input' => Auth::user()->id,
                'kode_cabang' => Auth::user()->kd_lokasi
            ]);
            // pencatatan ke Stok_in_out (keluar masuk barang) //

            // untuk update //
            $stok = DB::table('m_produk')
                ->select('m_produk.qty')
                ->where('m_produk.kode_produk', $kode_produk[$i])->first();
            $stok_update = DB::table('m_produk')
                ->where('m_produk.kode_produk', $kode_produk[$i])
                ->update([
                    'qty' => $stok->qty - ($stok_varian->qty * $tambah_jml[$i])
                ]);
            // end untuk update //
        }
        // End Detail //

        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getViewPenjualan(Request $request)
    {
        $dataPenjualan = DB::table('tr_penjualan_h')
            ->join('tr_penjualan_d', 'tr_penjualan_h.kode_penjualan', 'tr_penjualan_d.kode_penjualan')
            ->join('m_produk', 'tr_penjualan_d.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_penjualan_d.id_produk_unit', '=', 'm_produk_unit.id')
            ->join('users', 'tr_penjualan_h.id_user_input', '=', 'users.id')
            ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->select(
                'tr_penjualan_d.kode_produk',
                'm_produk.nama_produk',
                'tr_penjualan_d.harga',
                'tr_penjualan_d.qty',
                'm_produk_unit.nama_unit',
                'tr_penjualan_d.diskon',
                'tr_penjualan_d.diskon_rp',
                'tr_penjualan_d.ppn',
                'tr_penjualan_d.ppn',
                'tr_penjualan_d.ppn_rp',
                'tr_penjualan_d.biaya_tambahan',
                'tr_penjualan_d.tuslah',
                'tr_penjualan_d.embalase',
                'tr_penjualan_d.total',
                'tr_penjualan_h.pembulatan'
            )
            ->where('tr_penjualan_h.kode_penjualan', $request->kode_penjualan)
            ->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $dataPenjualan
        ];

        return response()->json($output, 200);
    }

    public function getViewPenjualanFooter(Request $request)
    {
        $dataPenjualanFooter = DB::table('tr_penjualan_h')
            ->select(
                'tr_penjualan_h.pembulatan',
                'tr_penjualan_h.total_bayar',
                'tr_penjualan_h.jml_bayar',
                'tr_penjualan_h.kembali',
                'tr_penjualan_h.cara_bayar',
                'tr_penjualan_h.bank'
            )
            ->where('tr_penjualan_h.kode_penjualan', $request->kode_penjualan)
            ->first();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $dataPenjualanFooter
        ];

        return response()->json($output, 200);
    }

    public function pdf(Request $request)
    {
        $data = DB::table('tr_penjualan_h')
            ->join('m_cabang', 'tr_penjualan_h.kode_cabang', '=', 'm_cabang.kode_cabang')
            ->join('users', 'tr_penjualan_h.id_user_input', '=', 'users.id')
            ->select(
                'tr_penjualan_h.kode_penjualan',
                'tr_penjualan_h.tgl_penjualan',
                'tr_penjualan_h.waktu_penjualan',
                'tr_penjualan_h.jenis_penjualan',
                'tr_penjualan_h.cara_bayar',
                'tr_penjualan_h.pembulatan',
                'tr_penjualan_h.total_bayar',
                'tr_penjualan_h.tgl_jatuh_tempo',
                'tr_penjualan_h.kembali',
                'tr_penjualan_h.id_user_input',
                'tr_penjualan_h.kode_cabang',
                'm_cabang.nama_cabang',
                'm_cabang.alamat',
                'm_cabang.tlp',
                'users.name'
            )
            ->where('tr_penjualan_h.kode_penjualan', $request->kode_penjualan)
            ->first();

        $dataPenjualan = DB::table('tr_penjualan_h')
            ->join('tr_penjualan_d', 'tr_penjualan_h.kode_penjualan', '=', 'tr_penjualan_d.kode_penjualan')
            ->join('m_produk', 'tr_penjualan_d.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_penjualan_d.id_produk_unit', '=', 'm_produk_unit.id')
            // ->join('m_produk_unit_varian', function($join)
            //         {
            //             $join->on('tr_penjualan_d.kode_produk','=','m_produk_unit_varian.kode_produk');
            //             $join->on('tr_penjualan_d.id_produk_unit','=','m_produk_unit_varian.id_produk_unit');
            //         }) 
            ->select(
                'tr_penjualan_h.kode_penjualan',
                'tr_penjualan_d.kode_produk',
                'm_produk.nama_produk',
                'tr_penjualan_d.qty',
                'm_produk_unit.nama_unit',
                'tr_penjualan_d.harga',
                'tr_penjualan_d.diskon',
                'tr_penjualan_d.diskon_rp'
            )
            ->where('tr_penjualan_h.kode_penjualan', $request->kode_penjualan)
            ->get();

        $dataPenjualanTotal = DB::table('tr_penjualan_d')
            ->select(DB::raw('SUM(tr_penjualan_d.total) as total'), DB::raw('SUM(tr_penjualan_d.qty) as total_item'))
            ->where('tr_penjualan_d.kode_penjualan', $request->kode_penjualan)
            ->first();

        $dataPenjualanTotal_h = DB::table('tr_penjualan_h')
            ->select('tr_penjualan_h.total_bayar', 'tr_penjualan_h.jml_bayar', 'tr_penjualan_h.kembali')
            ->where('tr_penjualan_h.kode_penjualan', $request->kode_penjualan)
            ->first();

        if ($data->jenis_penjualan == 'Panel') {
            $pdf = PDF::loadview('tr_penjualan.struk_panel', compact('data', 'dataPenjualan', 'dataPenjualanTotal'))->setPaper('a4', 'portrait'); //landscape,portrait
            return $pdf->stream();
        } else {
            $customPaper = array(0, 0, 567.00, 183.80);
            $pdf = PDF::loadview('tr_penjualan.struk', compact('data', 'dataPenjualan', 'dataPenjualanTotal', 'dataPenjualanTotal_h'))->setPaper($customPaper, 'landscape'); //landscape,portrait
            return $pdf->stream();
        }
    }
}
