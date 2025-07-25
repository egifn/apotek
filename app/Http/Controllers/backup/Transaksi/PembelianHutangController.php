<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianHutangController extends Controller
{
    public function index()
    {
        return view ('tr_pembelian_hutang.index');
    }

    public function getDataHutangPembelian()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_hutang_pembelian = DB::table('tr_pembelian_h')
            ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
            ->Join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
            ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
            ->join('m_cabang','tr_pembelian_h.kode_cabang','=','m_cabang.kode_cabang')
            ->join('users','tr_pembelian_h.id_user_input','=','users.id')
            ->select('tr_penerimaan_h.no_faktur','tr_penerimaan_h.tgl_penerimaan','tr_pembelian_h.termin','tr_pembelian_h.tgl_jatuh_tempo','tr_pembelian_h.kode_pembelian',
            'tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_transaksi','tr_pembelian_h.kode_supplier','m_supplier.nama_supplier','tr_penerimaan_h.diskon_rupiah','tr_penerimaan_h.diskon_persen','tr_penerimaan_h.subtotal',
            DB::raw('SUM(tr_pembelian_d.total) as total'),'tr_pembelian_h.status_pembayaran')
            ->WhereBetween('tr_pembelian_h.tgl_pembelian',[$date_start,$date_end])
            ->groupBy('tr_penerimaan_h.no_faktur','tr_penerimaan_h.tgl_penerimaan','tr_pembelian_h.termin','tr_pembelian_h.tgl_jatuh_tempo','tr_pembelian_h.kode_pembelian',
            'tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_transaksi','tr_pembelian_h.kode_supplier','m_supplier.nama_supplier','tr_penerimaan_h.diskon_rupiah','tr_penerimaan_h.diskon_persen','tr_penerimaan_h.subtotal','tr_pembelian_h.status_pembayaran');

        $data = $data_hutang_pembelian->get();
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

        $query = DB::table('tr_pembelian_h')
            ->join('tr_pembelian_d','tr_pembelian_h.kode_pembelian','=','tr_pembelian_d.kode_pembelian')
            ->Join('tr_penerimaan_h','tr_pembelian_h.kode_pembelian','=','tr_penerimaan_h.kode_pembelian')
            ->join('m_supplier','tr_pembelian_h.kode_supplier','=','m_supplier.id')
            ->join('m_cabang','tr_pembelian_h.kode_cabang','=','m_cabang.kode_cabang')
            ->join('users','tr_pembelian_h.id_user_input','=','users.id')
            ->select('tr_penerimaan_h.no_faktur','tr_penerimaan_h.tgl_penerimaan','tr_pembelian_h.termin','tr_pembelian_h.tgl_jatuh_tempo','tr_pembelian_h.kode_pembelian',
            'tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_transaksi','tr_pembelian_h.kode_supplier','m_supplier.nama_supplier','tr_penerimaan_h.diskon_rupiah','tr_penerimaan_h.diskon_persen','tr_penerimaan_h.subtotal',
            DB::raw('SUM(tr_pembelian_d.total) as total'),'tr_pembelian_h.status_pembayaran')
            ->WhereBetween('tr_pembelian_h.tgl_pembelian',[$date_start,$date_end])
            ->groupBy('tr_penerimaan_h.no_faktur','tr_penerimaan_h.tgl_penerimaan','tr_pembelian_h.termin','tr_pembelian_h.tgl_jatuh_tempo','tr_pembelian_h.kode_pembelian',
            'tr_pembelian_h.tgl_pembelian','tr_pembelian_h.jenis_transaksi','tr_pembelian_h.kode_supplier','m_supplier.nama_supplier','tr_penerimaan_h.diskon_rupiah','tr_penerimaan_h.diskon_persen','tr_penerimaan_h.subtotal','tr_pembelian_h.status_pembayaran');

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

    public function getViewPembelian(Request $request)
    {
        $dataPembelian = DB::table('tr_penerimaan_h')
                ->join('tr_penerimaan_d','tr_penerimaan_h.kode_penerimaan','=','tr_penerimaan_d.kode_penerimaan')
                ->join('m_produk','tr_penerimaan_d.kode_produk','=','m_produk.kode_produk')
                ->join('m_produk_unit','tr_penerimaan_d.id_produk_unit','=','m_produk_unit.id')
                ->join('m_produk_unit_varian', function($join)
                        {
                            $join->on('tr_penerimaan_d.kode_produk','=','m_produk_unit_varian.kode_produk');
                            $join->on('tr_penerimaan_d.id_produk_unit','=','m_produk_unit_varian.id_produk_unit');
                        }) 
                ->join('users','tr_penerimaan_h.id_user_input','=','users.id')
                ->join('m_cabang','tr_penerimaan_h.kode_cabang','=','m_cabang.kode_cabang')
                ->select('tr_penerimaan_h.kode_penerimaan','tr_penerimaan_d.kode_produk','m_produk.nama_produk','tr_penerimaan_d.no_batch','tr_penerimaan_d.tgl_kadaluarsa',
                        'tr_penerimaan_d.harga_beli','tr_penerimaan_d.jml_beli','tr_penerimaan_d.jml_terima','tr_penerimaan_d.id_produk_unit','m_produk_unit.nama_unit',
                        'tr_penerimaan_d.diskon_persen','tr_penerimaan_d.diskon_rp','tr_penerimaan_d.ppn_persen','tr_penerimaan_d.ppn_rp','tr_penerimaan_d.subtotal')
                ->where('tr_penerimaan_h.no_faktur', $request->no_faktur)
                ->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $dataPembelian
        ];
        
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $time = (now()->format('H:i:s')); 

        $date = (date('dmy'));
        $getRow = DB::table('tr_pembayaran_supplier')->select(DB::raw('MAX(RIGHT(kode_pembayaran,4)) as NoUrut'))
                                        ->where('kode_cabang', Auth::user()->kd_lokasi)
                                        ->where('kode_pembayaran', 'like', "%".$date."%");
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = "PB".''.Auth::user()->kd_lokasi."-".''.$date."000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = "PB".''.Auth::user()->kd_lokasi."-".''.$date."00".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = "PB".''.Auth::user()->kd_lokasi."-".''.$date."0".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = "PB".''.Auth::user()->kd_lokasi."-".''.$date.($rowCount + 1);
            }
        }else{
            $kode = "PB".''.Auth::user()->kd_lokasi."-".''.$date.sprintf("%04s", 1);
        } 

        DB::table('tr_pembayaran_supplier')->insert([
            'kode_pembayaran' => $kode,
            'tgl_pembayaran' => Carbon::now()->format('Y-m-d'),
            'waktu_pembayaran' => $time,
            'no_faktur' => $request->no_faktur,
            'kode_supplier' => $request->kode_supplier,
            'subtotal' => str_replace(",", "", $request->subtotal),
            'pembayaran' => $request->pembayaran,
            'bank' => $request->bank,
            'total_bayar' => str_replace(",", "", $request->total_bayar),
            'kembali' => str_replace(",", "", $request->kembali),
            'id_user_input' => Auth::user()->id,
            'kode_cabang' => Auth::user()->kd_lokasi
        ]);

        $status_update = DB::table('tr_pembelian_h')
                ->where('tr_pembelian_h.kode_pembelian', $request->kode_pembelian)
                ->update([
                    'status_pembayaran' => 1
                ]);

        $output = [
            'msg'  => 'Pembayaran berhasil',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }
}
