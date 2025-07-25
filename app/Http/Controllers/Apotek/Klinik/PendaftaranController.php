<?php

namespace App\Http\Controllers\Apotek\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\carbon;
use App\Province;
use App\City;
use App\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {


        $provinsi = DB::table('provinces')->get();

        return view('apotek.pendaftaran_klinik.index', compact('provinsi'));
    }

    public function getDataPendaftaran(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('m_pendaftaran')
                ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_pendaftaran.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->select('m_pendaftaran.no_rm', 'm_pendaftaran.nama_pasien', 'm_pendaftaran.jk', 'm_pendaftaran.umur', 'm_pendaftaran.tlp', 'm_pendaftaran.jenis_pasien', 'm_pendaftaran.tgl_daftar', 'm_pendaftaran.id_user_input', 'users.name', 'm_pendaftaran.kode_cabang', 'm_cabang.kode_cabang');

            if (!isset($request->value)) {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            } else {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->where('m_pendaftaran.nama_pasien', 'like', "%$request->value%")
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            }
        } else { //jika Admin
            $query = DB::table('m_pendaftaran')
                ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_pendaftaran.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->select('m_pendaftaran.no_rm', 'm_pendaftaran.nama_pasien', 'm_pendaftaran.jk', 'm_pendaftaran.umur', 'm_pendaftaran.tlp', 'm_pendaftaran.jenis_pasien', 'm_pendaftaran.tgl_daftar', 'm_pendaftaran.id_user_input', 'users.name', 'm_pendaftaran.kode_cabang', 'm_cabang.kode_cabang');

            if (!isset($request->value)) {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi)
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            } else {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi)
                    ->where('m_pendaftaran.nama_pasien', 'like', "%$request->value%")
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            }
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

    public function cari(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = explode(' - ', $request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('m_pendaftaran')
                ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_pendaftaran.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->select('m_pendaftaran.no_rm', 'm_pendaftaran.nama_pasien', 'm_pendaftaran.jk', 'm_pendaftaran.umur', 'm_pendaftaran.tlp', 'm_pendaftaran.jenis_pasien', 'm_pendaftaran.tgl_daftar', 'm_pendaftaran.id_user_input', 'users.name', 'm_pendaftaran.kode_cabang', 'm_cabang.kode_cabang');

            if (!isset($request->value)) {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            } else {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->where('m_pendaftaran.nama_pasien', 'like', "%$request->value%")
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            }
        } else { //jika Admin
            $query = DB::table('m_pendaftaran')
                ->join('provinces', 'm_pendaftaran.id_provinsi', '=', 'provinces.id')
                ->join('cities', 'm_pendaftaran.id_kab_kota', '=', 'cities.id')
                ->join('districts', 'm_pendaftaran.id_kecamatan', '=', 'districts.id')
                ->join('users', 'm_pendaftaran.id_user_input', '=', 'users.id')
                ->join('m_cabang', 'm_pendaftaran.kode_cabang', '=', 'm_cabang.kode_cabang')
                ->select('m_pendaftaran.no_rm', 'm_pendaftaran.nama_pasien', 'm_pendaftaran.jk', 'm_pendaftaran.umur', 'm_pendaftaran.tlp', 'm_pendaftaran.jenis_pasien', 'm_pendaftaran.tgl_daftar', 'm_pendaftaran.id_user_input', 'users.name', 'm_pendaftaran.kode_cabang', 'm_cabang.kode_cabang');

            if (!isset($request->value)) {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi)
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            } else {
                $query
                    ->WhereBetween('m_pendaftaran.tgl_daftar', [$date_start, $date_end])
                    ->where('m_pendaftaran.kode_cabang', Auth::user()->kd_lokasi)
                    ->where('m_pendaftaran.nama_pasien', 'like', "%$request->value%")
                    ->orderBy('m_pendaftaran.no_rm', 'ASC');
            }
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

    public function getKota(Request $request)
    {
        $kota = City::where('province_id', $request->provinsi)->pluck('name', 'id');
        return response()->json($kota);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = District::where("city_id", $request->kab_kota)->pluck('id', 'name');
        return response()->json($kecamatan);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $time = (now()->format('H:i:s'));
        $date = (date('dmy'));
        $getRow = DB::table('m_pendaftaran')->select(DB::raw('MAX(RIGHT(no_rm,4)) as NoUrut'))
            ->where('no_rm', 'like', "%" . $date . "%");
        $rowCount = $getRow->count();
        $kode_cabang = Auth::user()->kd_lokasi;
        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = $kode_cabang . '' . $date . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = $kode_cabang . '' . $date . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = $kode_cabang . '' . $date . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = $kode_cabang . '' . $date . ($rowCount + 1);
            }
        } else {
            $kode = $kode_cabang . '' . $date . sprintf("%04s", 1);
        }

        DB::table('m_pendaftaran')->insert([
            'no_rm' => $kode,
            'tgl_daftar' => Carbon::now()->format('Y-m-d'),
            'waktu' => $time,
            'nik_ktp' => $request->nik,
            'nama_pasien' => $request->nama_pasien,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'umur' => $request->umur,
            'jk' => $request->jk,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kab_kota' => $request->id_kab_kota,
            'id_kecamatan' => $request->id_kecamatan,
            'status_perkawinan' => $request->status_perkawinan,
            'pekerjaan' => $request->pekerjaan,
            'tlp' => $request->tlp,
            'nama_ortu' => $request->nama_ortu,
            'agama' => $request->agama,
            'suku' => $request->suku,
            'jenis_pasien' => $request->jenis_pasien,
            'nama_asuransi' => $request->nama_asuransi,
            'no_asuransi' => $request->no_asuransi,
            'id_user_input' => $request->id_user_input,
            'kode_cabang' => $request->kode_cabang
        ]);

        $output = [
            'msg'  => 'Pendaftaran baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
