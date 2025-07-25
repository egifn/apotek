<?php

namespace App\Http\Controllers\Klinik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class PemeriksaanController extends Controller
{
    public function index()
    {
        return view('pemeriksaan.index');
    }

    public function getDataAntrianPeriksa(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('m_kunjungan')
                    ->join('m_pendaftaran','m_kunjungan.no_rm','=','m_pendaftaran.no_rm')
                    ->join('users','m_kunjungan.id_user_input','=','users.id')
                    ->join('m_cabang','m_kunjungan.kode_cabang','=','m_cabang.kode_cabang')
                    ->WhereBetween('m_kunjungan.tgl_kunjungan',[$date_start,$date_end]);
                    // ->where('m_kunjungan.id_dokter', Auth::user()->id);
        }else{ //jika Admin
            $query = DB::table('m_kunjungan')
                    ->join('m_pendaftaran','m_kunjungan.no_rm','=','m_pendaftaran.no_rm')
                    ->join('users','m_kunjungan.id_user_input','=','users.id')
                    ->join('m_cabang','m_kunjungan.kode_cabang','=','m_cabang.kode_cabang')
                    ->WhereBetween('m_kunjungan.tgl_kunjungan',[$date_start,$date_end])
                    ->where('m_kunjungan.kode_cabang', Auth::user()->kd_lokasi);
                    // ->where('m_kunjungan.id_dokter', Auth::user()->id);
        }

        $data  = $query->get();

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
        $date = explode(' - ' ,$request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        if (Auth::user()->type == '1') {  //jika Super Admin
            $query = DB::table('m_kunjungan')
                    ->join('m_pendaftaran','m_kunjungan.no_rm','=','m_pendaftaran.no_rm')
                    ->join('users','m_kunjungan.id_user_input','=','users.id')
                    ->join('m_cabang','m_kunjungan.kode_cabang','=','m_cabang.kode_cabang')
                    ->WhereBetween('m_kunjungan.tgl_kunjungan',[$date_start,$date_end]);
                    // ->where('m_kunjungan.id_dokter', Auth::user()->id);
        }else{ //jika Admin
            $query = DB::table('m_kunjungan')
                    ->join('m_pendaftaran','m_kunjungan.no_rm','=','m_pendaftaran.no_rm')
                    ->join('users','m_kunjungan.id_user_input','=','users.id')
                    ->join('m_cabang','m_kunjungan.kode_cabang','=','m_cabang.kode_cabang')
                    ->WhereBetween('m_kunjungan.tgl_kunjungan',[$date_start,$date_end])
                    ->where('m_kunjungan.kode_cabang', Auth::user()->kd_lokasi);
                    // ->where('m_kunjungan.id_dokter', Auth::user()->id);
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


    public function getDataAntrianPeriksaDetail(Request $request)
    {
        $data = DB::table('m_kunjungan')
                ->join('m_pendaftaran','m_kunjungan.no_rm','=','m_pendaftaran.no_rm')
                ->where('kode_kunjungan', $request->kode_kunjungan)->first();
        
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];

        return response()->json($output, 200);
        
    }

    public function getSubKatDiagnosaModal(Request $request)
    {
        $data_subKatDiagnosa = DB::table('m_penyakit_list_subkategori')->limit(5000);

        if (!isset($request->value)) {
            
        }else{
            $data_subKatDiagnosa->where('id_sub_kategori', 'like', "%$request->value%")
                                    ->orWhere('nama_subkategori_penyakit_eng', 'like', "%$request->value%")
                                    ->orWhere('nama_subkategori_penyakit_ind', 'like', "%$request->value%");
        }

        $data_subKatDiagnosa = $data_subKatDiagnosa->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_subKatDiagnosa
        ];
        return response()->json($output, 200);
    }

    public function getProdukModal(Request $request)
    {

        $data_produk = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->join('m_produk_unit_varian','m_produk.kode_produk','=','m_produk_unit_varian.kode_produk')
                    ->join('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
                    ->join('users','m_produk.id_user_input','=','users.id')
                    ->select('m_produk.kode_produk','m_produk.barcode','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                            'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit',
                            'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                        'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name');         
                    //->limit(5);
        if (!isset($request->value)) {
            $data_produk->where('m_produk.kode_cabang', Auth::user()->kd_lokasi);
        }else{
            $data_produk->where('m_produk.kode_cabang', Auth::user()->kd_lokasi)
                        ->where('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_produk.komposisi','like', "%$request->value%");
        }

        $data_produk = $data_produk->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_produk
        ];
        return response()->json($output, 200);
    }

    public function getTindakanModal(Request $request)
    {
        $data_tindakan = DB::table('m_jasa_pelayanan');
        if (!isset($request->value)) {

        }else{
            $data_tindakan->where('nama_jasa_p', 'like', "%$request->value%");
        }

        $data_tindakan = $data_tindakan->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data_tindakan
        ];
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s')); 

        $date = (date('dmy'));
        $getRow = DB::table('tr_pelayanan_h')->select(DB::raw('MAX(RIGHT(id_pemeriksaan,4)) as NoUrut'))
                                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                                        ->where('id_pemeriksaan', 'like', "%".$date."%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = "KL".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = "KL".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = "KL".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = "KL".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount + 1);
            }
        }else{
            $kode = "KL".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        DB::table('tr_pelayanan_h')->insert([
            'id_pemeriksaan' => $kode,
            'tgl_periksa' => Carbon::now()->format('Y-m-d'),
            'waktu_periksa' => $time,
            'kode_kunjungan' => $request->kode_kunjungan,
            'no_rm' => $request->no_rm,
            'id_dokter' => Auth::user()->id,
            'status_periksa' => 1,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);

        DB::table('tr_pelayanan_d_pemeriksaan')->insert([
            'id_pemeriksaan' => $kode,
            'keluhan_utama' => $request->keluhan_utama,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'riwayat_alergi' => $request->riwayat_alergi,
            'riwayat_pengobatan' => $request->riwayat_pengobatan,
            'tinggi_badan' => $request->t_badan,
            'berat_badan' => $request->b_badan,
            'tekanan_darah' => $request->t_darah,
            'suhu_badan' => $request->suhu,
            'denyut_jantung' => $request->denyut_jantung,
            'pernapasan' => $request->pernapasan,
            'penglihatan' => $request->penglihatan,
            'catatan' => $request->catatan
        ]);

        $kode_diagnosa = $request->kode_diagnosa;
        for ($i=0; $i < count((array)$kode_diagnosa); $i++) {
            DB::table('tr_pelayanan_d_diagnosa')->insert([
                'id_pemeriksaan' => $kode,
                'id_sub_kategori' => $kode_diagnosa[$i]
            ]);
        }

        $kode_tindakan = $request->kode_tindakan;
        $harga = str_replace(",", "", $request->harga);
        for ($i=0; $i < count((array)$kode_tindakan); $i++) {
            DB::table('tr_pelayanan_d')->insert([
                'id_pemeriksaan' => $kode,
                'kode_jasa_p' => $kode_tindakan[$i],
                'harga_jasa_p' => $harga[$i]
            ]);
        }

        $kode_produk = $request->kode_produk;
        $qty = $request->qty;
        $harga_jual = $request->harga_jual;
        $aturan = $request->aturan;
        $id_produk_unit = $request->id_produk_unit;
        for ($i=0; $i < count((array)$kode_produk); $i++) {
            DB::table('tr_pelayanan_d_obat')->insert([
                'id_pemeriksaan' => $kode,
                'kode_produk' => $kode_produk[$i],
                'qty' => $qty[$i],
                'id_produk_unit' => $id_produk_unit[$i],
                'harga' => $harga_jual[$i],
                'total' => $harga_jual[$i] * $qty[$i],
                'aturan' => $aturan[$i]
            ]);
        }

        $status_update = DB::table('m_kunjungan')
                ->where('m_kunjungan.kode_kunjungan', $request->kode_kunjungan)
                ->update([
                    'm_kunjungan.status_periksa' => 1
                ]);

        //===== Untuk resep=====================================================================================//
        $getRow_resep = DB::table('tr_resep_h')->select(DB::raw('MAX(RIGHT(kode_resep,4)) as NoUrut'))
                                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                                        ->where('kode_resep', 'like', "%".$date."%");
        $rowCount_resep = $getRow_resep->count();

        if ($rowCount_resep > 0) {
            if ($rowCount_resep < 9) {
                    $kode_resep = "RP".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount_resep + 1);
            } else if ($rowCount_resep < 99) {
                    $kode_resep = "RP".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount_resep + 1);
            } else if ($rowCount_resep < 999) {
                    $kode_resep = "RP".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount_resep + 1);
            } else if ($rowCount_resep < 9999) {
                    $kode_resep = "RP".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount_resep + 1);
            }
        }else{
            $kode_resep = "RP".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        DB::table('tr_resep_h')->insert([
            'kode_resep' => $kode_resep,
            'tgl_resep' => Carbon::now()->format('Y-m-d'),
            'kode_kunjungan' => $request->kode_kunjungan,
            'id_pemeriksaan' =>$kode,
            'no_rm' => $request->no_rm,
            'id_dokter' => Auth::user()->id,
            'status_resep' => 0,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);

        for ($i=0; $i < count((array)$kode_produk); $i++) {
            $stok_varian = DB::table('m_produk_unit_varian')
                ->select('m_produk_unit_varian.qty')
                ->where('m_produk_unit_varian.kode_produk', $kode_produk[$i])
                ->where ('m_produk_unit_varian.id_produk_unit', $id_produk_unit[$i])->first();

            DB::table('tr_resep_d')->insert([
                'kode_resep' => $kode_resep,
                'kode_produk' => $kode_produk[$i],
                'qty_kecil' => $stok_varian->qty * $qty[$i],
                'qty' => $qty[$i],
                'id_produk_unit' => $id_produk_unit[$i],
                'aturan' => $aturan[$i]
            ]);
        }

        //====== end resep====================================================================================//


        $output = [
            'msg'  => 'Transaksi baru berhasil ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);

    }

    
}
