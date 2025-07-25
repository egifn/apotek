<?php

namespace App\Http\Controllers\Apotek\Farmasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class FarmasiController extends Controller
{
    public function index()
    {
        return view('apotek.farmasi.index');
    }

    public function getDataResepObat(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_resep = DB::table('tr_resep_h')
                ->join('m_pendaftaran', 'tr_resep_h.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('users', 'tr_resep_h.id_dokter', '=', 'users.id')
                ->join('m_cabang', 'tr_resep_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->leftJoin('users AS users_input', 'tr_resep_h.id_user_input', '=', 'users_input.id')
                ->select(
                    'tr_resep_h.kode_resep',
                    'tr_resep_h.tgl_resep',
                    'tr_resep_h.id_pemeriksaan',
                    'tr_resep_h.no_rm',
                    'm_pendaftaran.nama_pasien',
                    'm_pendaftaran.jk',
                    'm_pendaftaran.umur',
                    'tr_resep_h.status_resep',
                    'tr_resep_h.id_dokter',
                    'users.name AS nama_dokter',
                    'tr_resep_h.kode_cabang',
                    'm_cabang.nama_cabang',
                    'tr_resep_h.id_user_input',
                    'users_input.name AS nama_user_input'
                )
                ->WhereBetween('tr_resep_h.tgl_resep', [$date_start, $date_end]);
        } else { //jika Admin
            $data_resep = DB::table('tr_resep_h')
                ->join('m_pendaftaran', 'tr_resep_h.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('users', 'tr_resep_h.id_dokter', '=', 'users.id')
                ->join('m_cabang', 'tr_resep_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->leftJoin('users AS users_input', 'tr_resep_h.id_user_input', '=', 'users_input.id')
                ->select(
                    'tr_resep_h.kode_resep',
                    'tr_resep_h.tgl_resep',
                    'tr_resep_h.id_pemeriksaan',
                    'tr_resep_h.no_rm',
                    'm_pendaftaran.nama_pasien',
                    'm_pendaftaran.jk',
                    'm_pendaftaran.umur',
                    'tr_resep_h.status_resep',
                    'tr_resep_h.id_dokter',
                    'users.name AS nama_dokter',
                    'tr_resep_h.kode_cabang',
                    'm_cabang.nama_cabang',
                    'tr_resep_h.id_user_input',
                    'users_input.name AS nama_user_input'
                )
                ->WhereBetween('tr_resep_h.tgl_resep', [$date_start, $date_end])
                ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi);
        }

        $data  = $data_resep->get();

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
        $date = explode(' - ', $request->tgl);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_resep = DB::table('tr_resep_h')
                ->join('m_pendaftaran', 'tr_resep_h.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('users', 'tr_resep_h.id_dokter', '=', 'users.id')
                ->join('m_cabang', 'tr_resep_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->leftJoin('users AS users_input', 'tr_resep_h.id_user_input', '=', 'users_input.id')
                ->select(
                    'tr_resep_h.kode_resep',
                    'tr_resep_h.tgl_resep',
                    'tr_resep_h.id_pemeriksaan',
                    'tr_resep_h.no_rm',
                    'm_pendaftaran.nama_pasien',
                    'm_pendaftaran.jk',
                    'm_pendaftaran.umur',
                    'tr_resep_h.status_resep',
                    'tr_resep_h.id_dokter',
                    'users.name AS nama_dokter',
                    'tr_resep_h.kode_cabang',
                    'm_cabang.nama_cabang',
                    'tr_resep_h.id_user_input',
                    'users_input.name AS nama_user_input'
                )
                ->WhereBetween('tr_resep_h.tgl_resep', [$date_start, $date_end]);
        } else { //jika Admin
            $data_resep = DB::table('tr_resep_h')
                ->join('m_pendaftaran', 'tr_resep_h.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('users', 'tr_resep_h.id_dokter', '=', 'users.id')
                ->join('m_cabang', 'tr_resep_h.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->leftJoin('users AS users_input', 'tr_resep_h.id_user_input', '=', 'users_input.id')
                ->select(
                    'tr_resep_h.kode_resep',
                    'tr_resep_h.tgl_resep',
                    'tr_resep_h.id_pemeriksaan',
                    'tr_resep_h.no_rm',
                    'm_pendaftaran.nama_pasien',
                    'm_pendaftaran.jk',
                    'm_pendaftaran.umur',
                    'tr_resep_h.status_resep',
                    'tr_resep_h.id_dokter',
                    'users.name AS nama_dokter',
                    'tr_resep_h.kode_cabang',
                    'm_cabang.nama_cabang',
                    'tr_resep_h.id_user_input',
                    'users_input.name AS nama_user_input'
                )
                ->WhereBetween('tr_resep_h.tgl_resep', [$date_start, $date_end])
                ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi);
        }

        $data  = $data_resep->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function getDataResepDetail(Request $request)
    {
        $data_obat = DB::table('tr_resep_h')
            ->join('tr_resep_d', 'tr_resep_h.kode_resep', 'tr_resep_d.kode_resep')
            ->join('m_produk', 'tr_resep_d.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_resep_d.id_produk_unit', '=', 'm_produk_unit.id')
            ->select('tr_resep_h.kode_resep', 'tr_resep_d.kode_produk', 'm_produk.nama_produk', 'm_produk.qty as stok', 'tr_resep_d.qty_kecil', 'tr_resep_d.qty', 'tr_resep_d.id_produk_unit', 'm_produk_unit.nama_unit', 'tr_resep_d.aturan')
            ->where('tr_resep_h.kode_resep', $request->kode_resep)
            ->get();

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

        $kode_produk = $request->kode_produk;
        $stok_detail = $request->stok;
        //$jml_kecil = $request->jml_kecil;
        $jml = $request->jml;
        $kode_satuan = $request->kode_satuan;

        for ($i = 0; $i < count((array)$kode_produk); $i++) {
            $stok_varian = DB::table('m_produk_unit_varian')
                ->select('m_produk_unit_varian.qty')
                ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
                ->where('m_produk_unit_varian.id_produk_unit', $kode_satuan[$i])->first();


            // pencatatan ke Stok_in_out (keluar masuk barang) //
            DB::table('stok_in_out')->insert([
                'id_produk' => $kode_produk[$i],
                'tgl_in_out' => Carbon::now()->format('Y-m-d'),
                'waktu_in_out' => $time,
                'no_bukti' => $request->kode_resep,
                'keterangan' => 'Resep Dokter: ' . $request->kode_resep,
                'stok_awal' => $stok_detail[$i],
                'stok_masuk' => 0,
                'stok_keluar' => $stok_varian->qty * $jml[$i],
                'stok_sisa' => $stok_detail[$i] - ($stok_varian->qty * $jml[$i]),
                'type' => 'Resep',
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
                    'qty' => $stok->qty - ($stok_varian->qty * $jml[$i])
                ]);
            // end untuk update //
        }

        $status_update = DB::table('tr_resep_h')
            ->where('tr_resep_h.kode_resep', $request->kode_resep)
            ->update([
                'tr_resep_h.status_resep' => 1
            ]);

        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
