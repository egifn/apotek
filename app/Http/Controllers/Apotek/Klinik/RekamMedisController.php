<?php

namespace App\Http\Controllers\Apotek\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index()
    {
        return view('apotek.rekam_medis.index');
    }

    public function getDataRm(Request $request)
    {
        if (Auth::user()->type == '1') {  //jika Super Admin
            $data_rm = DB::table('m_pendaftaran')
                ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_pendaftaran.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->orderBy('m_pendaftaran.no_rm', 'ASC');

            if (!isset($request->value)) {
            } else {
                $data_rm
                    // ->where('tr_penjualan_h.tgl_penjualan',Carbon::now()->format('Y-m-d'))
                    ->where('m_pendaftaran.no_rm', 'like', "%$request->value%")
                    ->orWhere('m_pendaftaran.nama_pasien', 'like', "%$request->value%")
                    ->orWhere('m_pendaftaran.jk', 'like', "%$request->value%");
            }
        } else { //jika Admin
            $data_rm = DB::table('m_pendaftaran')
                ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_pendaftaran.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->orderBy('m_pendaftaran.no_rm', 'ASC');

            if (!isset($request->value)) {
                $data_rm->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi);
            } else {
                $data_rm
                    // ->where('tr_penjualan_h.tgl_penjualan',Carbon::now()->format('Y-m-d'))
                    ->where('m_pendaftaran.no_rm', 'like', "%$request->value%")
                    ->orWhere('m_pendaftaran.nama_pasien', 'like', "%$request->value%")
                    ->orWhere('m_pendaftaran.jk', 'like', "%$request->value%")
                    ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi);
            }
        }

        $data  = $data_rm->get();
        $count = ($data_rm->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function getViewDataRekamMedis(Request $request)
    {
        $data_pemeriksaan = DB::table('m_pendaftaran')
            ->join('tr_pelayanan_h', 'm_pendaftaran.no_rm', '=', 'tr_pelayanan_h.no_rm')
            ->join('tr_pelayanan_d_pemeriksaan', 'tr_pelayanan_h.id_pemeriksaan', '=', 'tr_pelayanan_d_pemeriksaan.id_pemeriksaan')
            ->select(
                'm_pendaftaran.no_rm',
                'tr_pelayanan_h.id_pemeriksaan',
                'tr_pelayanan_h.tgl_periksa',
                'tr_pelayanan_d_pemeriksaan.keluhan_utama',
                'tr_pelayanan_d_pemeriksaan.riwayat_penyakit',
                'tr_pelayanan_d_pemeriksaan.riwayat_alergi',
                'tr_pelayanan_d_pemeriksaan.riwayat_pengobatan',
                'tr_pelayanan_d_pemeriksaan.tinggi_badan',
                'tr_pelayanan_d_pemeriksaan.berat_badan',
                'tr_pelayanan_d_pemeriksaan.tekanan_darah',
                'tr_pelayanan_d_pemeriksaan.suhu_badan',
                'tr_pelayanan_d_pemeriksaan.denyut_jantung',
                'tr_pelayanan_d_pemeriksaan.pernapasan',
                'tr_pelayanan_d_pemeriksaan.penglihatan',
                'tr_pelayanan_d_pemeriksaan.catatan'
            );

        if (!isset($request->value)) {
            $data_pemeriksaan->where('m_pendaftaran.no_rm', $request->no_rm);
        } else {
            $data_pemeriksaan->where('m_pendaftaran.no_rm', $request->no_rm);
            // $data_pemeriksaan->where('m_produk.nama_produk','like', "%$request->value%")
            //             ->orWhere('m_produk.komposisi','like', "%$request->value%")
            //             ->orWhere('m_jenis_obat.nama_jenis','like', "%$request->value%")
            //             ->orWhere('m_produk.tipe', 'like', "%$request->value%");
        }

        $data  = $data_pemeriksaan->get();
        $count = ($data_pemeriksaan->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function getViewDataRekamMedisDiagnosa(Request $request)
    {
        $data_diagnosa = DB::table('m_pendaftaran')
            ->join('tr_pelayanan_h', 'm_pendaftaran.no_rm', '=', 'tr_pelayanan_h.no_rm')
            ->join('tr_pelayanan_d_diagnosa', 'tr_pelayanan_h.id_pemeriksaan', '=', 'tr_pelayanan_d_diagnosa.id_pemeriksaan')
            ->join('m_penyakit_list_subkategori', 'tr_pelayanan_d_diagnosa.id_sub_kategori', '=', 'm_penyakit_list_subkategori.id_sub_kategori')
            ->select(
                'm_pendaftaran.no_rm',
                'tr_pelayanan_h.id_pemeriksaan',
                'tr_pelayanan_h.tgl_periksa',
                'tr_pelayanan_d_diagnosa.id_sub_kategori',
                'm_penyakit_list_subkategori.nama_subkategori_penyakit_eng'
            );

        if (!isset($request->value)) {
            $data_diagnosa->where('m_pendaftaran.no_rm', $request->no_rm);
        } else {
            $data_diagnosa->where('m_pendaftaran.no_rm', $request->no_rm);
            // $data_diagnosa->where('m_produk.nama_produk','like', "%$request->value%")
            //             ->orWhere('m_produk.komposisi','like', "%$request->value%")
            //             ->orWhere('m_jenis_obat.nama_jenis','like', "%$request->value%")
            //             ->orWhere('m_produk.tipe', 'like', "%$request->value%");
        }

        $data  = $data_diagnosa->get();
        $count = ($data_diagnosa->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function getViewDataRekamMedisResep(Request $request)
    {
        $data_resep = DB::table('m_pendaftaran')
            ->join('tr_pelayanan_h', 'm_pendaftaran.no_rm', '=', 'tr_pelayanan_h.no_rm')
            ->join('tr_pelayanan_d_obat', 'tr_pelayanan_h.id_pemeriksaan', '=', 'tr_pelayanan_d_obat.id_pemeriksaan')
            ->join('m_produk', 'tr_pelayanan_d_obat.kode_produk', '=', 'm_produk.kode_produk')
            ->join('m_produk_unit', 'tr_pelayanan_d_obat.id_produk_unit', 'm_produk_unit.id')
            ->select(
                'm_pendaftaran.no_rm',
                'tr_pelayanan_h.id_pemeriksaan',
                'tr_pelayanan_h.tgl_periksa',
                'tr_pelayanan_d_obat.kode_produk',
                'm_produk.nama_produk',
                'tr_pelayanan_d_obat.qty',
                'm_produk_unit.nama_unit',
                'tr_pelayanan_d_obat.aturan'
            );

        if (!isset($request->value)) {
            $data_resep->where('m_pendaftaran.no_rm', $request->no_rm);
        } else {
            $data_resep->where('m_pendaftaran.no_rm', $request->no_rm);
            // $data_resep->where('m_produk.nama_produk','like', "%$request->value%")
            //             ->orWhere('m_produk.komposisi','like', "%$request->value%")
            //             ->orWhere('m_jenis_obat.nama_jenis','like', "%$request->value%")
            //             ->orWhere('m_produk.tipe', 'like', "%$request->value%");
        }

        $data  = $data_resep->get();
        $count = ($data_resep->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }
}
