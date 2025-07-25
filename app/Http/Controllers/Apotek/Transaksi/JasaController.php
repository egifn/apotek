<?php

namespace App\Http\Controllers\Apotek\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Tr_Jasa_H;
use App\Tr_Jasa_D;
use App\Tr_Jasa_D_Obat;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JasaController extends Controller
{
    public function index()
    {
        $poli = DB::table('m_poli')->get();

        $dokter = DB::table('m_pegawai')
            ->join('users', 'm_pegawai.nama_pegawai', '=', 'users.name')
            ->where('jabatan', 'like', '%Dokter%')
            ->where('users.kd_lokasi', Auth::user()->kd_lokasi)
            ->get();

        return view('apotek.tr_jasa_pelayanan.index', compact('dokter', 'poli'));
    }

    public function getDataAntrian(Request $request)
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
                ->orderBy('m_pendaftaran.no_rm', 'ASC');
        } else { //jika Admin
            $query = DB::table('m_kunjungan')
                ->join('m_pendaftaran', 'm_kunjungan.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('m_poli', 'm_kunjungan.id_poli', 'm_poli.id')
                ->join('users', 'm_kunjungan.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_kunjungan.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->WhereBetween('m_kunjungan.tgl_kunjungan', [$date_start, $date_end])
                ->where('m_kunjungan.kode_cabang', Auth::user()->kd_lokasi)
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
                ->orderBy('m_pendaftaran.no_rm', 'ASC');
        } else { //jika Admin
            $query = DB::table('m_kunjungan')
                ->join('m_pendaftaran', 'm_kunjungan.no_rm', '=', 'm_pendaftaran.no_rm')
                ->join('m_poli', 'm_kunjungan.id_poli', 'm_poli.id')
                ->join('users', 'm_kunjungan.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_kunjungan.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->WhereBetween('m_kunjungan.tgl_kunjungan', [$date_start, $date_end])
                ->where('m_kunjungan.kode_cabang', Auth::user()->kd_lokasi)
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

    public function actionGetPasien(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');

            if (Auth::user()->type == '1') {  //jika Super Admin
                if ($query != '') {
                    $data = DB::table('m_pendaftaran')
                        ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                        ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                        ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                        ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                        ->where('m_pendaftaran.no_rm', 'like', '%' . $query . '%')
                        ->orWhere('m_pendaftaran.nama_pasien', 'like', '%' . $query . '%')
                        ->get();
                } else {
                    $data = DB::table('m_pendaftaran')
                        ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                        ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                        ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                        ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                        ->get();
                }
            } else { //jika Admin
                if ($query != '') {
                    $data = DB::table('m_pendaftaran')
                        ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                        ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                        ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                        ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                        ->where('m_pendaftaran.no_rm', 'like', '%' . $query . '%')
                        ->orWhere('m_pendaftaran.nama_pasien', 'like', '%' . $query . '%')
                        ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi)
                        ->get();
                } else {
                    $data = DB::table('m_pendaftaran')
                        ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                        ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                        ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                        ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                        ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi)
                        ->get();
                }
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                    <tr class="pilih" data-no_rm="' . $row->no_rm . '" data-nama_pasien="' . $row->nama_pasien . '" data-tempat="' . $row->tempat_lahir . '" data-tgl="' . $row->tgl_lahir . '" data-umur="' . $row->umur . '" data-jk="' . $row->jk . '" data-alamat="' . $row->alamat . '">
                        <td>' . $row->no_rm . '</td>
                        <td>' . $row->nama_pasien . '</td>
                        <td hidden>' . $row->tempat_lahir . '</td>
                        <td>' . $row->tgl_lahir . '</td>
                        <td hidden>' . $row->umur . '</td>
                        <td hidden>' . $row->jk . '</td>
                        <td>' . $row->alamat . '</td>
                    </tr>
                    ';
                }
            } else {
                $output = '
                <tr>
                    <td align="center" colspan="7">Data Obat tidak ditemukan</td>
                </tr>
                ';
            }
            $data = array(
                'table_data'  => $output,
                'total_data'  => $total_row
            );
            echo json_encode($data);
        }
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s'));
        $date = (date('dmy'));
        $getRow = DB::table('m_kunjungan')->select(DB::raw('MAX(RIGHT(kode_kunjungan,4)) as NoUrut'))
            ->where('kode_kunjungan', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();
        $kode_cabang = Auth::user()->kd_lokasi;

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "KJ" . '' . $kode_cabang . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "KJ" . '' . $kode_cabang . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "KJ" . '' . $kode_cabang . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "KJ" . '' . $kode_cabang . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = "KJ" . '' . $kode_cabang . '' . $date . sprintf("%04s", 1);
        }
        DB::table('m_kunjungan')->insert([
            'kode_kunjungan' => $kode,
            'tgl_kunjungan' => Carbon::now()->format('Y-m-d'),
            'waktu_kunjungan' => $time,
            'no_rm' => $request->no_rm,
            'id_poli' => $request->id_poli,
            'id_dokter' => $request->id_dokter,
            'status_periksa' => '0',
            'status_kasir' => '0',
            'id_user_input' => $request->id_user_input,
            'kode_cabang' => $request->kode_cabang
        ]);

        $output = [
            'msg'  => 'Kunjungan baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
