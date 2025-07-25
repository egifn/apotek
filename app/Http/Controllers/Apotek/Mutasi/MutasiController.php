<?php

namespace App\Http\Controllers\Apotek\Mutasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MutasiController extends Controller
{
    public function index()
    {
        return view('apotek.mutasi.index');
    }

    public function getDataMutasi()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_mutasi = DB::table('tr_mutasi_h')
                ->join('m_cabang as cabang_asal', 'tr_mutasi_h.kode_cabang_asal', '=', 'cabang_asal.kode_cabang')
                ->join('m_cabang as cabang_tujuan', 'tr_mutasi_h.kode_cabang_tujuan', '=', 'cabang_tujuan.kode_cabang')
                ->join('users', 'tr_mutasi_h.id_user_input', 'users.id')
                ->select(
                    'tr_mutasi_h.kode_mutasi',
                    'tr_mutasi_h.tgl_mutasi',
                    'tr_mutasi_h.waktu_mutasi',
                    'tr_mutasi_h.kode_cabang_asal',
                    'cabang_asal.nama_cabang as nama_cabang_asal',
                    'tr_mutasi_h.kode_cabang_tujuan',
                    'cabang_tujuan.nama_cabang as nama_cabang_tujuan',
                    'tr_mutasi_h.id_user_input',
                    'users.name'
                )
                ->WhereBetween('tr_mutasi_h.tgl_mutasi', [$date_start, $date_end]);
        } else { //jika Admin
            $data_mutasi = DB::table('tr_mutasi_h')
                ->join('m_cabang as cabang_asal', 'tr_mutasi_h.kode_cabang_asal', '=', 'cabang_asal.kode_cabang')
                ->join('m_cabang as cabang_tujuan', 'tr_mutasi_h.kode_cabang_tujuan', '=', 'cabang_tujuan.kode_cabang')
                ->join('users', 'tr_mutasi_h.id_user_input', 'users.id')
                ->select(
                    'tr_mutasi_h.kode_mutasi',
                    'tr_mutasi_h.tgl_mutasi',
                    'tr_mutasi_h.waktu_mutasi',
                    'tr_mutasi_h.kode_cabang_asal',
                    'cabang_asal.nama_cabang as nama_cabang_asal',
                    'tr_mutasi_h.kode_cabang_tujuan',
                    'cabang_tujuan.nama_cabang as nama_cabang_tujuan',
                    'tr_mutasi_h.id_user_input',
                    'users.name'
                )
                ->WhereBetween('tr_mutasi_h.tgl_mutasi', [$date_start, $date_end])
                ->where('tr_mutasi_h.kode_cabang_asal', Auth::user()->kd_lokasi);
        }

        $data = $data_mutasi->get();
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
            $data_mutasi = DB::table('tr_mutasi_h')
                ->join('m_cabang as cabang_asal', 'tr_mutasi_h.kode_cabang_asal', '=', 'cabang_asal.kode_cabang')
                ->join('m_cabang as cabang_tujuan', 'tr_mutasi_h.kode_cabang_tujuan', '=', 'cabang_tujuan.kode_cabang')
                ->join('users', 'tr_mutasi_h.id_user_input', 'users.id')
                ->select(
                    'tr_mutasi_h.kode_mutasi',
                    'tr_mutasi_h.tgl_mutasi',
                    'tr_mutasi_h.waktu_mutasi',
                    'tr_mutasi_h.kode_cabang_asal',
                    'cabang_asal.nama_cabang as nama_cabang_asal',
                    'tr_mutasi_h.kode_cabang_tujuan',
                    'cabang_tujuan.nama_cabang as nama_cabang_tujuan',
                    'tr_mutasi_h.id_user_input',
                    'users.name'
                )
                ->WhereBetween('tr_mutasi_h.tgl_mutasi', [$date_start, $date_end]);
        } else { //jika Admin  
            $data_mutasi = DB::table('tr_mutasi_h')
                ->join('m_cabang as cabang_asal', 'tr_mutasi_h.kode_cabang_asal', '=', 'cabang_asal.kode_cabang')
                ->join('m_cabang as cabang_tujuan', 'tr_mutasi_h.kode_cabang_tujuan', '=', 'cabang_tujuan.kode_cabang')
                ->join('users', 'tr_mutasi_h.id_user_input', 'users.id')
                ->select(
                    'tr_mutasi_h.kode_mutasi',
                    'tr_mutasi_h.tgl_mutasi',
                    'tr_mutasi_h.waktu_mutasi',
                    'tr_mutasi_h.kode_cabang_asal',
                    'cabang_asal.nama_cabang as nama_cabang_asal',
                    'tr_mutasi_h.kode_cabang_tujuan',
                    'cabang_tujuan.nama_cabang as nama_cabang_tujuan',
                    'tr_mutasi_h.id_user_input',
                    'users.name'
                )
                ->WhereBetween('tr_mutasi_h.tgl_mutasi', [$date_start, $date_end])
                ->where('tr_mutasi_h.kode_cabang_asal', Auth::user()->kd_lokasi);
        }

        $data  = $data_mutasi->get();

        $count = ($data_mutasi->count() == 0) ? 0 : $data->count();
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
        $date = now()->format('dmy');

        $getRow = DB::table('tr_mutasi_h')->select(DB::raw('MAX(RIGHT(kode_mutasi,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_mutasi', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "MS" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "MS" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "MS" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "MS" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "MS" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        $data_cabang_asal = DB::table('m_cabang')->select('m_cabang.kode_cabang', 'm_cabang.nama_cabang')->where('kode_cabang', Auth::user()->kd_lokasi)->first();
        $data_cabang_tujuan = DB::table('m_cabang')->orderBy('id', 'ASC')->get();

        return view('apotek.mutasi.create', compact('kode', 'data_cabang_asal', 'data_cabang_tujuan'));
    }

    public function getProduk(Request $request)
    {
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
            );

        //->limit(5);

        $data = $data_produk->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));

        $date = (date('dmy'));
        $getRow = DB::table('tr_mutasi_h')->select(DB::raw('MAX(RIGHT(kode_mutasi,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_mutasi', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "MT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "MT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "MT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "MT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "MT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        // Header //
        DB::table('tr_mutasi_h')->insert([
            'kode_mutasi' => $kode,
            'tgl_mutasi' => Carbon::now()->format('Y-m-d'),
            'waktu_mutasi' => $time,
            'kode_cabang_asal' => $request->kd_apotek_asal,
            'kode_cabang_tujuan' => $request->apotek_tujuan,
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);
        // End Header //

        // Detail //
        $kode_barang = $request->kode_produk;
        $nama_produk = $request->nama_produk;
        $barcode = $request->barcode;
        $no_batch = $request->no_batch;

        $komposisi = $request->komposisi;
        $id_jenis = $request->id_jenis;
        $tgl_kadaluarsa = $request->tgl_kadaluarsa;

        $qty_kecil = $request->stok;
        $qty_mutasi = $request->jml_keluar;
        $id_unit = $request->id_unit;
        $harga_beli = str_replace(",", "", $request->harga_beli);
        $margin_persen = $request->margin_p;
        $margin_rp = str_replace(",", "", $request->margin_r);
        $harga_jual = str_replace(",", "", $request->harga_j);

        for ($i = 0; $i < count((array)$kode_barang); $i++) {
            //cek barang sudah ada atau belum
            $count_barang = DB::table('m_produk')
                ->select(DB::raw('count(m_produk.nama_produk) as jml_produk'))
                ->where('m_produk.nama_produk', $nama_produk[$i])
                ->where('m_produk.kode_cabang', $request->apotek_tujuan)
                ->groupBy('m_produk.kode_produk', 'm_produk.qty');
            $rowCount_barang = $count_barang->count();
            //end cek barang

            if ($rowCount_barang > 0) {
                $cek_barang = DB::table('m_produk')
                    ->select(DB::raw('max(m_produk.nama_produk) as jml_produk'), 'm_produk.kode_produk', 'm_produk.qty')
                    ->where('m_produk.nama_produk', $nama_produk[$i])
                    ->where('m_produk.kode_cabang', $request->apotek_tujuan)
                    ->groupBy('m_produk.kode_produk', 'm_produk.qty')
                    ->first();

                $stok_varian = DB::table('m_produk_unit_varian')
                    ->select('m_produk_unit_varian.qty')
                    ->where('m_produk_unit_varian.kode_produk', $kode_barang[$i])
                    ->where('m_produk_unit_varian.id_produk_unit', $id_unit[$i])->first();

                DB::table('tr_mutasi_d')->insert([
                    'kode_mutasi' => $kode,
                    'kode_barang' => $kode_barang[$i],
                    'kode_barang_setelah_mutasi' => $cek_barang->kode_produk,
                    'qty_kecil' => $stok_varian->qty * $qty_mutasi[$i],
                    'qty_mutasi' => $qty_mutasi[$i],
                    'id_unit' => $id_unit[$i],
                    'harga_beli' => $harga_beli[$i],
                    'margin_rp' => $margin_rp[$i],
                    'margin_persen' => $margin_persen[$i],
                    'harga_jual' => $harga_jual[$i]
                ]);

                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $kode_barang[$i],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Mutasi Keluar dengan No: ' . $kode,
                    'stok_awal' => $qty_kecil[$i],
                    'stok_masuk' => 0,
                    'stok_keluar' => $stok_varian->qty * $qty_mutasi[$i],
                    'stok_sisa' => $qty_kecil[$i] - ($stok_varian->qty * $qty_mutasi[$i]),
                    'type' => 'Mutasi Keluar',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
                // pencatatan ke Stok_in_out (keluar masuk barang) //

                // untuk update //
                $stok = DB::table('m_produk')
                    ->select('m_produk.qty')
                    ->where('m_produk.kode_produk', $kode_barang[$i])->first();
                $stok_update = DB::table('m_produk')
                    ->where('m_produk.kode_produk', $kode_barang[$i])
                    ->update([
                        'qty' => $stok->qty - ($stok_varian->qty * $qty_mutasi[$i])
                    ]);
                // end untuk update //


                ///======================================================================================//



                // update Header //
                $stok_baru = DB::table('m_produk')
                    ->select('m_produk.qty')
                    ->where('m_produk.kode_produk', $cek_barang->kode_produk)->first();
                $stok_update_baru = DB::table('m_produk')
                    ->where('m_produk.kode_produk', $cek_barang->kode_produk)
                    ->update([
                        'qty' => $stok_baru->qty + ($stok_varian->qty * $qty_mutasi[$i])
                    ]);
                // End Header //


                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $cek_barang->kode_produk,
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Mutasi Masuk dengan No: ' . $kode,
                    'stok_awal' => $cek_barang->qty,
                    'stok_masuk' => $stok_varian->qty * $qty_mutasi[$i],
                    'stok_keluar' => 0,
                    'stok_sisa' => $cek_barang->qty + ($stok_varian->qty * $qty_mutasi[$i]),
                    'type' => 'Mutasi Masuk',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => $request->apotek_tujuan
                ]);
                // pencatatan ke Stok_in_out (keluar masuk barang) //

                // End Detail //

                //End Mutasi Masuk ////
            } else {
                $stok_varian = DB::table('m_produk_unit_varian')
                    ->select('m_produk_unit_varian.qty')
                    ->where('m_produk_unit_varian.kode_produk', $kode_barang[$i])
                    ->where('m_produk_unit_varian.id_produk_unit', $id_unit[$i])->first();

                // Mutasi Masuk////
                $getRow = DB::table('m_produk')
                    ->select(DB::raw('MAX(id) as urut_no'))
                    ->where('kode_cabang', $request->apotek_tujuan)
                    ->first();

                $rowCount = $getRow->urut_no + 1;

                if ($rowCount > 0) {
                    if ($rowCount < 9) {
                        $kode_setelah_mutasi = $request->apotek_tujuan . '-' . "00000" . '' . ($rowCount + 1);
                    } else if ($rowCount < 99) {
                        $kode_setelah_mutasi = $request->apotek_tujuan . '-' . "0000" . '' . ($rowCount + 1);
                    } else if ($rowCount < 999) {
                        $kode_setelah_mutasi = $request->apotek_tujuan . '-' . "000" . '' . ($rowCount + 1);
                    } else if ($rowCount < 9999) {
                        $kode_setelah_mutasi = $request->apotek_tujuan . '-' . "00" . '' . ($rowCount + 1);
                    } else if ($rowCount < 99999) {
                        $kode_setelah_mutasi = $request->apotek_tujuan . '-' . "0" . '' . ($rowCount + 1);
                    } else {
                        $kode_setelah_mutasi = $request->apotek_tujuan . '-' . ($rowCount + 1);
                    }
                }


                DB::table('tr_mutasi_d')->insert([
                    'kode_mutasi' => $kode,
                    'kode_barang' => $kode_barang[$i],
                    'kode_barang_setelah_mutasi' => $kode_setelah_mutasi,
                    'qty_kecil' => $stok_varian->qty * $qty_mutasi[$i],
                    'qty_mutasi' => $qty_mutasi[$i],
                    'id_unit' => $id_unit[$i],
                    'harga_beli' => $harga_beli[$i],
                    'margin_rp' => $margin_rp[$i],
                    'margin_persen' => $margin_persen[$i],
                    'harga_jual' => $harga_jual[$i]
                ]);

                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $kode_barang[$i],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Mutasi Keluar dengan No: ' . $kode,
                    'stok_awal' => $qty_kecil[$i],
                    'stok_masuk' => 0,
                    'stok_keluar' => $stok_varian->qty * $qty_mutasi[$i],
                    'stok_sisa' => $qty_kecil[$i] - ($stok_varian->qty * $qty_mutasi[$i]),
                    'type' => 'Mutasi Keluar',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
                // pencatatan ke Stok_in_out (keluar masuk barang) //

                // untuk update //
                $stok = DB::table('m_produk')
                    ->select('m_produk.qty')
                    ->where('m_produk.kode_produk', $kode_barang[$i])->first();
                $stok_update = DB::table('m_produk')
                    ->where('m_produk.kode_produk', $kode_barang[$i])
                    ->update([
                        'qty' => $stok->qty - ($stok_varian->qty * $qty_mutasi[$i])
                    ]);
                // end untuk update //


                ///======================================================================================//



                // insert Header //
                DB::table('m_produk')->insert([
                    'kode_produk' => $kode_setelah_mutasi,
                    'kode_cabang' => $request->apotek_tujuan,
                    'barcode' => $barcode[$i],
                    'no_batch' => $no_batch[$i],
                    'nama_produk' => $nama_produk[$i],
                    'komposisi' => $komposisi[$i],
                    'id_jenis' => $id_jenis[$i],
                    'tipe' => 'Apotek',
                    'tgl_kadaluarsa' => $tgl_kadaluarsa[$i],
                    'qty' => $stok_varian->qty * $qty_mutasi[$i],
                    'qty_min' => '5',
                    'id_unit' => $id_unit[$i],
                    'id_user_input' => Auth::user()->id,
                ]);
                // End Header //



                DB::table('m_produk_unit_varian')->insert([
                    'kode_produk' => $kode_setelah_mutasi,
                    'id_produk_unit' => $id_unit[$i],
                    'qty' => $stok_varian->qty,
                    'harga_beli' => $harga_beli[$i],
                    'margin_rp' => $margin_rp[$i],
                    'margin_persen' => $margin_persen[$i],
                    'harga_jual' => $harga_jual[$i],
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => $request->apotek_tujuan
                ]);


                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $kode_setelah_mutasi,
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Mutasi Masuk dengan No: ' . $kode,
                    'stok_awal' => 0,
                    'stok_masuk' => $stok_varian->qty * $qty_mutasi[$i],
                    'stok_keluar' => 0,
                    'stok_sisa' => ($stok_varian->qty * $qty_mutasi[$i]),
                    'type' => 'Mutasi Masuk',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => $request->apotek_tujuan
                ]);
                // pencatatan ke Stok_in_out (keluar masuk barang) //

                // End Detail //

                //End Mutasi Masuk ////
            }
        }
        // End Detail //


        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function pdf(Request $request)
    {
        $data = DB::table('tr_mutasi_h')
            ->join('m_cabang as cabang_asal', 'tr_mutasi_h.kode_cabang_asal', '=', 'cabang_asal.kode_cabang')
            ->join('m_cabang as cabang_tujuan', 'tr_mutasi_h.kode_cabang_tujuan', '=', 'cabang_tujuan.kode_cabang')
            ->join('users', 'tr_mutasi_h.id_user_input', 'users.id')
            ->select(
                'tr_mutasi_h.kode_mutasi',
                'tr_mutasi_h.tgl_mutasi',
                'tr_mutasi_h.waktu_mutasi',
                'tr_mutasi_h.kode_cabang_asal',
                'cabang_asal.nama_cabang as nama_cabang_asal',
                'tr_mutasi_h.kode_cabang_tujuan',
                'cabang_tujuan.nama_cabang as nama_cabang_tujuan',
                'tr_mutasi_h.id_user_input',
                'users.name'
            )
            ->Where('tr_mutasi_h.kode_mutasi', $request->kode_mutasi)
            ->first();

        $data_mutasi = DB::table('tr_mutasi_h')
            ->join('tr_mutasi_d', 'tr_mutasi_h.kode_mutasi', 'tr_mutasi_d.kode_mutasi')
            ->join('m_produk', 'tr_mutasi_d.kode_barang', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_mutasi_d.id_unit', '=', 'm_produk_unit.id')
            ->join('m_cabang AS cabang_asal', 'tr_mutasi_h.kode_cabang_asal', '=', 'cabang_asal.kode_cabang')
            ->join('m_cabang AS cabang_tujuan', 'tr_mutasi_h.kode_cabang_tujuan', '=', 'cabang_tujuan.kode_cabang')
            ->select(
                'tr_mutasi_h.kode_mutasi',
                'tr_mutasi_h.tgl_mutasi',
                'tr_mutasi_d.kode_barang',
                'tr_mutasi_d.kode_barang_setelah_mutasi',
                'm_produk.nama_produk',
                'tr_mutasi_d.qty_mutasi',
                'm_produk_unit.nama_unit',
                'cabang_asal.nama_cabang AS asal',
                'cabang_tujuan.nama_cabang AS tujuan'
            )
            ->Where('tr_mutasi_h.kode_mutasi', $request->kode_mutasi)
            ->get();

        $pdf = PDF::loadview('mutasi.pdf', compact('data_mutasi', 'data'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
}
