<?php

namespace App\Http\Controllers\Apotek\Retur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class RetPenjualanController extends Controller
{
    public function index()
    {
        return view('apotek.retur_penjualan.index');
    }

    public function getDataReturPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_retur_penjualan = DB::table('tr_penjualan_retur_h')
                ->join('m_cabang', 'tr_penjualan_retur_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_retur_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_retur_h.kode_retur',
                    'tr_penjualan_retur_h.tgl_retur',
                    'tr_penjualan_retur_h.kode_penjualan',
                    'tr_penjualan_retur_h.jenis_transaksi',
                    'tr_penjualan_retur_h.subtotal_ret',
                    'tr_penjualan_retur_h.total_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->WhereBetween('tr_penjualan_retur_h.tgl_retur', [$date_start, $date_end]);
        } else { //jika Admin
            $data_retur_penjualan = DB::table('tr_penjualan_retur_h')
                ->join('m_cabang', 'tr_penjualan_retur_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_retur_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_retur_h.kode_retur',
                    'tr_penjualan_retur_h.tgl_retur',
                    'tr_penjualan_retur_h.kode_penjualan',
                    'tr_penjualan_retur_h.jenis_transaksi',
                    'tr_penjualan_retur_h.subtotal_ret',
                    'tr_penjualan_retur_h.total_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->where('tr_penjualan_retur_h.kode_cabang', Auth::user()->kd_lokasi)
                ->WhereBetween('tr_penjualan_retur_h.tgl_retur', [$date_start, $date_end]);
        }

        $data = $data_retur_penjualan->get();
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
            $query = DB::table('tr_penjualan_retur_h')
                ->join('m_cabang', 'tr_penjualan_retur_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_retur_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_retur_h.kode_retur',
                    'tr_penjualan_retur_h.tgl_retur',
                    'tr_penjualan_retur_h.kode_penjualan',
                    'tr_penjualan_retur_h.jenis_transaksi',
                    'tr_penjualan_retur_h.subtotal_ret',
                    'tr_penjualan_retur_h.total_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->WhereBetween('tr_penjualan_retur_h.tgl_retur', [$date_start, $date_end]);
        } else { //jika Admin
            $query = DB::table('tr_penjualan_retur_h')
                ->join('m_cabang', 'tr_penjualan_retur_h.kode_cabang', '=', 'm_cabang.id')
                ->join('users', 'tr_penjualan_retur_h.id_user_input', '=', 'users.id')
                ->select(
                    'tr_penjualan_retur_h.kode_retur',
                    'tr_penjualan_retur_h.tgl_retur',
                    'tr_penjualan_retur_h.kode_penjualan',
                    'tr_penjualan_retur_h.jenis_transaksi',
                    'tr_penjualan_retur_h.subtotal_ret',
                    'tr_penjualan_retur_h.total_bayar',
                    'users.name',
                    'm_cabang.nama_cabang'
                )
                ->where('tr_penjualan_retur_h.kode_cabang', Auth::user()->kd_lokasi)
                ->WhereBetween('tr_penjualan_retur_h.tgl_retur', [$date_start, $date_end]);
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

        return view('apotek.retur_penjualan.create');
    }

    public function getPenjualanModal(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_penjualan = DB::table('tr_penjualan_h')
                ->join('tr_penjualan_d', 'tr_penjualan_h.kode_penjualan', '=', 'tr_penjualan_d.kode_penjualan')
                ->select(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    DB::raw('sum(tr_penjualan_d.total) as total')
                )
                ->groupBy('tr_penjualan_h.kode_penjualan', 'tr_penjualan_h.tgl_penjualan', 'tr_penjualan_h.jenis_penjualan')
                ->orderBy('tr_penjualan_h.tgl_penjualan', 'DESC')
                ->limit(10);
            if (!isset($request->value)) {
            } else {
                $data_penjualan->where('tr_penjualan_h.kode_penjualan', 'like', "%$request->value%");
            }
        } else { //jika Admin
            $data_penjualan = DB::table('tr_penjualan_h')
                ->join('tr_penjualan_d', 'tr_penjualan_h.kode_penjualan', '=', 'tr_penjualan_d.kode_penjualan')
                ->select(
                    'tr_penjualan_h.kode_penjualan',
                    'tr_penjualan_h.tgl_penjualan',
                    'tr_penjualan_h.jenis_penjualan',
                    DB::raw('sum(tr_penjualan_d.total) as total')
                )
                ->groupBy('tr_penjualan_h.kode_penjualan', 'tr_penjualan_h.tgl_penjualan', 'tr_penjualan_h.jenis_penjualan')
                ->orderBy('tr_penjualan_h.tgl_penjualan', 'DESC')
                ->limit(10);
            if (!isset($request->value)) {
                $data_penjualan
                    ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi);
            } else {
                $data_penjualan
                    ->where('tr_penjualan_h.kode_cabang', Auth::user()->kd_lokasi)
                    ->where('tr_penjualan_h.kode_penjualan', 'like', "%$request->value%");
            }
        }

        $data_penjualan = $data_penjualan->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_penjualan
        ];
        return response()->json($output, 200);
    }

    public function getPenjualandetail(Request $request)
    {
        $data_penjualan_detail = DB::table('tr_penjualan_h')
            ->join('tr_penjualan_d', 'tr_penjualan_h.kode_penjualan', '=', 'tr_penjualan_d.kode_penjualan')
            ->join('m_produk', 'tr_penjualan_d.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_penjualan_d.id_produk_unit', '=', 'm_produk_unit.id')
            ->select(
                'tr_penjualan_d.kode_produk',
                'm_produk.nama_produk',
                'tr_penjualan_d.harga',
                'tr_penjualan_d.qty',
                'tr_penjualan_d.id_produk_unit',
                'm_produk_unit.nama_unit',
                'tr_penjualan_d.diskon',
                'tr_penjualan_d.diskon_rp',
                'tr_penjualan_d.ppn',
                'tr_penjualan_d.ppn_rp',
                'tr_penjualan_d.total'
            )
            ->where('tr_penjualan_d.kode_penjualan', $request->value);


        $data_penjualan_detail = $data_penjualan_detail->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_penjualan_detail
        ];
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));

        $date = (date('dmy'));
        $getRow = DB::table('tr_penjualan_retur_h')->select(DB::raw('MAX(RIGHT(kode_retur,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('kode_retur', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "RT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "RT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "RT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "RT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "RT" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        // Header //
        DB::table('tr_penjualan_retur_h')->insert([
            'kode_retur' => $kode,
            'tgl_retur' => Carbon::now()->format('Y-m-d'),
            'waktu_retur' => $time,
            'kode_penjualan' => $request->no_faktur,
            'tgl_penjualan' => $request->tgl_faktur,
            'jenis_transaksi' => $request->jenis,
            'subtotal_ret' =>  str_replace(",", "", $request->subtotal_ret),
            'pembulatan' => str_replace(",", "", $request->pembulatan_ret),
            'total_bayar' => str_replace(",", "", $request->total_bayar_ret),
            'jml_bayar_ret' => str_replace(",", "", $request->jml_bayar_ret),
            'kembali' => str_replace(",", "", $request->kembali),
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);
        // End Header //

        // Detail //
        $kode_produk = $request->kode_produk;
        $harga_beli = str_replace(",", "", $request->harga_beli);
        $jml_beli =  $request->jml_beli;
        $jml_retur = $request->jml_retur;
        $id_produk_unit = $request->id_produk_unit;
        $diskon_persen = $request->diskon_persen;
        $diskon_rupiah = str_replace(",", "", $request->diskon_rupiah);
        $ppn_persen = $request->ppn_persen;
        $ppn_rupiah = str_replace(",", "", $request->ppn_rupiah);
        $subtotal = str_replace(",", "", $request->subtotal);
        // End Detail //

        for ($i = 0; $i < count((array)$kode_produk); $i++) {
            $stok_varian = DB::table('m_produk_unit_varian')
                ->select('m_produk_unit_varian.qty')
                ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
                ->where('m_produk_unit_varian.id_produk_unit', $id_produk_unit[$i])->first();

            DB::table('tr_penjualan_retur_d')->insert([
                'kode_retur' => $kode,
                'kode_produk' => $kode_produk[$i],
                'harga_beli' => $harga_beli[$i],
                'jml_beli' => $jml_beli[$i],
                'jml_retur' => $jml_retur[$i],
                'id_produk_unit' => $id_produk_unit[$i],
                'diskon_persen' => $diskon_persen[$i],
                'diskon_rupiah' => $diskon_rupiah[$i],
                'ppn_persen' => $ppn_persen[$i],
                'ppn_rupiah' => $ppn_rupiah[$i],
                'subtotal' =>  $subtotal[$i]
            ]);

            $stok = DB::table('m_produk')
                ->select('m_produk.qty')
                ->where('m_produk.kode_produk', $kode_produk[$i])->first();

            if ($jml_retur[$i] != 0) {
                // pencatatan ke Stok_in_out (keluar masuk barang) //
                DB::table('stok_in_out')->insert([
                    'id_produk' => $kode_produk[$i],
                    'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                    'waktu_in_out' => $time,
                    'no_bukti' => $kode,
                    'keterangan' => 'Retur Penjualan dengan No Retur: ' . $kode,
                    'stok_awal' => $stok->qty,
                    'stok_masuk' => $stok_varian->qty * $jml_retur[$i],
                    'stok_keluar' => 0,
                    'stok_sisa' => $stok->qty + ($stok_varian->qty * $jml_retur[$i]),
                    'type' => 'Retur Penjualan',
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => Auth::user()->kd_lokasi
                ]);
                // End pencatatan ke Stok_in_out (keluar masuk barang) //

                // untuk update //
                $stok_update = DB::table('m_produk')
                    ->where('m_produk.kode_produk', $kode_produk[$i])
                    ->update([
                        'qty' => $stok->qty + ($stok_varian->qty * $jml_retur[$i])
                    ]);
                // end untuk update //
            }
        }

        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
