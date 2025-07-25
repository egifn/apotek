<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        return view('welcome');
    }

    public function dashboard_master()
    {
        // $data_terlaris = DB::table('tr_penjualan_d')
        //     ->join('m_produk', 'tr_penjualan_d.kode_produk', '=', 'm_produk.kode_produk')
        //     ->join('m_produk_unit', 'm_produk.id_unit', 'm_produk_unit.id')
        //     ->select('tr_penjualan_d.kode_produk', 'm_produk.nama_produk', DB::raw('SUM(tr_penjualan_d.qty_kecil) as jml_jual_terkecil'), 'm_produk_unit.nama_unit')
        //     //->where('tgl_kunjungan',  $date)
        //     //->where('kode_cabang', Auth::user()->kd_lokasi)
        //     // ->where('id_user_input', Auth::user()->id )
        //     ->groupBy('tr_penjualan_d.kode_produk', 'm_produk.nama_produk', 'm_produk_unit.nama_unit')
        //     ->orderBy('jml_jual_terkecil', 'DESC')
        //     ->get();

        // $data_barang_habis = DB::table('m_produk')
        //     ->select('m_produk.kode_produk', 'm_produk.nama_produk', 'm_produk.qty', 'm_produk.qty_min')
        //     //->where('tgl_kunjungan',  $date)
        //     //->where('kode_cabang', Auth::user()->kd_lokasi)
        //     // ->where('id_user_input', Auth::user()->id )
        //     ->where('m_produk.qty', '<=', 'm_produk.qty_min')
        //     ->get();

        // $endDate = now()->addDays(30);
        // $data_barang_kadaluarsa = DB::table('m_produk')
        //     ->join('m_cabang', 'm_produk.kode_cabang', '=', 'm_cabang.kode_cabang')
        //     ->join('m_produk_unit', 'm_produk.id_unit', '=', 'm_produk_unit.id')
        //     ->leftJoin('m_supplier', 'm_produk.id_supplier', '=', 'm_supplier.id')
        //     ->select(
        //         'm_produk.kode_produk',
        //         'm_produk.nama_produk',
        //         'm_produk.kode_cabang',
        //         'm_cabang.nama_cabang',
        //         'm_produk.id_supplier',
        //         'm_supplier.nama_supplier',
        //         'm_produk.tgl_kadaluarsa',
        //         'm_produk.qty',
        //         'm_produk.id_unit',
        //         'm_produk_unit.nama_unit'
        //     )
        //     ->where('m_produk.tgl_kadaluarsa', '<=', $endDate)
        //     ->whereNotIn('m_produk.status', ['1'])
        //     ->whereNotIn('m_produk.tgl_kadaluarsa', ['0000-00-00'])
        //     ->whereNotIn('m_produk.qty', ['0'])
        //     ->get();

        // $data_cabang = DB::table('m_cabang')
        //     ->orderBy('kode_cabang', 'ASC')
        //     ->get();

        return view('master_dashboard');
    }
}
