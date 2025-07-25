<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapStokOpnameController extends Controller
{
    public function index()
    {
        return view ('laporan.stok_opname.index');
    }

    public function getDataStokOpname(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d'));
        $date_end = (date('Y-m-d'));

        $data_stok_opname = DB::table('stokopname_h');
        if(!isset($request->value)){
            $data_stok_opname
                ->WhereBetween('stokopname_h.tgl_opname',[$date_start,$date_end]);
        }else{
            $data_stok_opname 
            ->WhereBetween('stokopname_h.tgl_opname',[$date_start,$date_end]);
        }

        $data  = $data_stok_opname->get();
        $count = ($data_stok_opname->count() == 0) ? 0 : $data->count();
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
        $date = explode(' - ' ,$request->tgl_cari);
        $date_start = Carbon::parse($date[0])->format('Y-m-d');
        $date_end = Carbon::parse($date[1])->format('Y-m-d');

        $data_stok_opname = DB::table('stokopname_h');
        if(!isset($request->value)){
            $data_stok_opname
                ->WhereBetween('stokopname_h.tgl_opname',[$date_start,$date_end]);
        }else{
            $data_stok_opname 
            ->WhereBetween('stokopname_h.tgl_opname',[$date_start,$date_end]);
        }

        $data  = $data_stok_opname->get();
        $count = ($data_stok_opname->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function getViewOpname(Request $request)
    {
        $dataPenjualan = DB::table('stokopname_h')
                ->join('stokopname_d','stokopname_h.kode_opname','stokopname_d.kode_opname')
                ->join('m_produk','stokopname_d.kode_produk','=','m_produk.kode_produk')
                ->select('stokopname_h.kode_opname','stokopname_h.tgl_opname','stokopname_d.kode_produk','m_produk.nama_produk',
                'stokopname_d.jml_sistem','stokopname_d.jml_fisik','stokopname_d.selisih')
                ->where('stokopname_h.kode_opname', $request->kode_opname)
                ->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $dataPenjualan
        ];

        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $tombol_excel = $request->input('button_excel');
        $kode_stok_opname =$request->kode_stok_opname;
        if($tombol_excel == 'excel'){
            $dataOpname = DB::table('stokopname_h')
            ->join('stokopname_d','stokopname_h.kode_opname','stokopname_d.kode_opname')
            ->join('m_produk','stokopname_d.kode_produk','=','m_produk.kode_produk')
            ->select('stokopname_h.kode_opname','stokopname_h.tgl_opname','stokopname_d.kode_produk','m_produk.nama_produk',
            'stokopname_d.jml_sistem','stokopname_d.jml_fisik','stokopname_d.selisih')
            ->where('stokopname_h.kode_opname', $kode_stok_opname)
            ->get();
        }
        return view ('laporan.stok_opname.view', compact('dataOpname'));
    }
}
