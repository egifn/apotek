<?php

namespace App\Http\Controllers\Apotek\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $data_tuslah_apotek = DB::table('m_tuslah')
            ->select('m_tuslah.id', 'm_tuslah.nama_tuslah', 'm_tuslah.harga_tuslah')
            ->where('m_tuslah.id', '1')
            ->first();

        return view('apotek.kasir.index', compact('data_tuslah_apotek'));
    }

    public function getDataKasir(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('m_kunjungan')
                ->join('m_pendaftaran', 'm_kunjungan.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('m_poli', 'm_kunjungan.id_poli', 'm_poli.id')
                ->join('users', 'm_kunjungan.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_kunjungan.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->WhereBetween('m_kunjungan.tgl_kunjungan', [$date_start, $date_end])
                ->where('m_kunjungan.status_periksa', 1)
                ->orderBy('m_pendaftaran.no_rm', 'ASC');
        } else { //jika Admin
            $query = DB::table('m_kunjungan')
                ->join('m_pendaftaran', 'm_kunjungan.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('m_poli', 'm_kunjungan.id_poli', 'm_poli.id')
                ->join('users', 'm_kunjungan.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_kunjungan.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->WhereBetween('m_kunjungan.tgl_kunjungan', [$date_start, $date_end])
                ->where('m_kunjungan.kode_cabang', Auth::user()->kd_lokasi)
                ->where('m_kunjungan.status_periksa', 1)
                ->orderBy('m_pendaftaran.no_rm', 'ASC');
        }

        $data  = $query->get();

        $count = ($query->count() == 0) ? 0 : $data->count();
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
            $query = DB::table('m_kunjungan')
                ->join('m_pendaftaran', 'm_kunjungan.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('m_poli', 'm_kunjungan.id_poli', 'm_poli.id')
                ->join('users', 'm_kunjungan.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_kunjungan.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->WhereBetween('m_kunjungan.tgl_kunjungan', [$date_start, $date_end])
                ->where('m_kunjungan.status_periksa', 1)
                ->orderBy('m_pendaftaran.no_rm', 'ASC');
        } else { //jika Admin
            $query = DB::table('m_kunjungan')
                ->join('m_pendaftaran', 'm_kunjungan.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('m_poli', 'm_kunjungan.id_poli', 'm_poli.id')
                ->join('users', 'm_kunjungan.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_kunjungan.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->WhereBetween('m_kunjungan.tgl_kunjungan', [$date_start, $date_end])
                ->where('m_kunjungan.kode_cabang', Auth::user()->kd_lokasi)
                ->where('m_kunjungan.status_periksa', 1)
                ->orderBy('m_pendaftaran.no_rm', 'ASC');
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

    public function getDataTindakan(Request $request)
    {
        $data_tindakan = DB::table('tr_pelayanan_h')
            ->join('tr_pelayanan_d', 'tr_pelayanan_h.id_pemeriksaan', 'tr_pelayanan_d.id_pemeriksaan')
            ->join('m_jasa_pelayanan', 'tr_pelayanan_d.kode_jasa_p', '=', 'm_jasa_pelayanan.kode_jasa_p')
            ->where('tr_pelayanan_h.kode_kunjungan', $request->no_kunjungan)
            ->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_tindakan
        ];

        return response()->json($output, 200);
    }

    public function getDataObat(Request $request)
    {
        // $data_obat = DB::table('tr_pelayanan_h')
        //                 ->join('tr_pelayanan_d_obat','tr_pelayanan_h.id_pemeriksaan','tr_pelayanan_d_obat.id_pemeriksaan')
        //                 ->join('m_produk','tr_pelayanan_d_obat.kode_produk','=','m_produk.kode_produk')
        //                 ->join('m_produk_unit','tr_pelayanan_d_obat.id_produk_unit','=','m_produk_unit.id')
        //                 ->select('tr_pelayanan_h.kode_kunjungan','tr_pelayanan_d_obat.kode_produk','m_produk.nama_produk','tr_pelayanan_d_obat.qty','tr_pelayanan_d_obat.id_produk_unit','m_produk_unit.nama_unit','tr_pelayanan_d_obat.aturan','tr_pelayanan_d_obat.harga')
        //                 ->where('tr_pelayanan_h.kode_kunjungan', $request->no_kunjungan)
        //                 ->get();

        $data_obat = DB::select("SELECT tr_pelayanan_h.kode_kunjungan,tr_pelayanan_d_obat.kode_produk,m_produk.nama_produk,tr_pelayanan_d_obat.qty,tr_pelayanan_d_obat.id_produk_unit,
                    m_produk_unit.nama_unit,tr_pelayanan_d_obat.aturan,tr_pelayanan_d_obat.harga,
                    (SELECT m_tuslah.harga_tuslah FROM m_tuslah WHERE m_tuslah.id = '2') AS tuslah,
                    (SELECT m_tuslah.harga_tuslah FROM m_tuslah WHERE m_tuslah.id = '1') AS embalase
                    FROM tr_pelayanan_h
                    INNER JOIN tr_pelayanan_d_obat ON tr_pelayanan_h.id_pemeriksaan = tr_pelayanan_d_obat.id_pemeriksaan
                    INNER JOIN m_produk ON tr_pelayanan_d_obat.kode_produk = m_produk.kode_produk
                    INNER JOIN m_produk_unit ON tr_pelayanan_d_obat.id_produk_unit = m_produk_unit.id
                    WHERE tr_pelayanan_h.kode_kunjungan = '$request->no_kunjungan'
                    GROUP BY tr_pelayanan_h.kode_kunjungan,tr_pelayanan_d_obat.kode_produk,m_produk.nama_produk,tr_pelayanan_d_obat.qty,tr_pelayanan_d_obat.id_produk_unit,
                    m_produk_unit.nama_unit,tr_pelayanan_d_obat.aturan,tr_pelayanan_d_obat.harga");

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_obat
        ];

        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));

        $date = (date('dmy'));
        $getRow = DB::table('tr_pembayaran_h_klinik')->select(DB::raw('MAX(RIGHT(no_invoice,4)) as NoUrut'))
            ->where('kode_cabang', Auth::user()->kd_lokasi)
            ->where('no_invoice', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "IV" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "IV" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "IV" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "IV" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "IV" . '' . Auth::user()->kd_lokasi . "-" . '' . $date . sprintf("%04s", 1);
        }

        DB::table('tr_pembayaran_h_klinik')->insert([
            'no_invoice' => $kode,
            'tgl_invoice' => Carbon::now()->format('Y-m-d'),
            'waktu_invoice' => $time,
            'kode_kunjungan' => $request->kode_kunjungan,
            'no_rm' => $request->no_rm,
            'poli' => $request->poli,
            'cara_bayar' => $request->cara_bayar,
            'bank' => $request->bank,
            'subtotal' => str_replace(",", "", $request->subtotal),
            'pembulatan' => str_replace(",", "", $request->pembulatan),
            'total_bayar' => str_replace(",", "", $request->total_bayar),
            'jml_bayar' => str_replace(",", "", $request->jml_bayar),
            'kembali' => str_replace(",", "", $request->kembali),
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);

        // untuk Detail //
        $kode_jasa = $request->kode_jasa;
        $harga_jasa = $request->harga_jasa;

        $kode_produk = $request->kode_produk;
        $qty = $request->qty;
        $harga_produk = $request->harga_produk;
        $tuslah = $request->tuslah;
        $embalase = $request->embalase;
        $total = $request->total;

        for ($i = 0; $i < count((array)$kode_jasa); $i++) {
            DB::table('tr_pembayaran_d_klinik_tindakan')->insert([
                'no_invoice' => $kode,
                'kode_jasa_p' => $kode_jasa[$i],
                'jml_jasa_p' => 1,
                'harga_jasa_p' => str_replace(",", "",  $harga_jasa[$i]),
            ]);
        }

        for ($i = 0; $i < count((array)$kode_produk); $i++) {
            DB::table('tr_pembayaran_d_klinik_obat')->insert([
                'no_invoice' => $kode,
                'kode_produk' => $kode_produk[$i],
                'jml_produk' => $qty[$i],
                'harga_produk' => str_replace(",", "",  $harga_produk[$i]),
                'tuslah' => str_replace(",", "",  $tuslah[$i]),
                'embalase' => str_replace(",", "",  $embalase[$i]),
                'total' => str_replace(",", "",  $total[$i])
            ]);
        }

        $status_update = DB::table('m_kunjungan')
            ->where('m_kunjungan.kode_kunjungan', $request->kode_kunjungan)
            ->update([
                'status_kasir' => 1
            ]);

        $output = [
            'msg'  => 'Transaksi berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
