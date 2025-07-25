<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LapReturKadaluarsaController extends Controller
{
    public function index()
    {
        return view ('laporan.retur_kadaluarsa.index');
    }

    public function getDataReturKadaluarsa(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_start = (date('Y-m-d 00:00:01'));
        $date_end = (date('Y-m-d 23:59:59'));

        $data_kadaluarsa = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->leftJoin('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->select('m_produk.created_at AS tgl_transaksi',
                        'm_produk.id',
                        'm_produk.kode_produk',
                        'm_produk.nama_produk', 
                        'm_jenis_obat.nama_jenis',
                        'm_produk.qty',
                        'm_produk_unit.nama_unit',
                        'm_produk.keterangan',
                        'm_supplier.nama_supplier'
                    );
        if(!isset($request->value)){
            $data_kadaluarsa
                ->where('m_produk.status', 1)
                ->WhereBetween('m_produk.created_at',[$date_start,$date_end]);
        }else{
            $data_kadaluarsa 
                ->where('m_produk.status', 1)
                ->WhereBetween('m_produk.created_at',[$date_start,$date_end])
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_kadaluarsa->get();
        $count = ($data_kadaluarsa->count() == 0) ? 0 : $data->count();
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
        $date_start = Carbon::parse($date[0])->format('Y-m-d 00:00:01');
        $date_end = Carbon::parse($date[1])->format('Y-m-d 23:59:59');

        $data_kadaluarsa = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->leftJoin('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->select('m_produk.created_at AS tgl_transaksi',
                        'm_produk.id',
                        'm_produk.kode_produk',
                        'm_produk.nama_produk', 
                        'm_jenis_obat.nama_jenis',
                        'm_produk.qty',
                        'm_produk_unit.nama_unit',
                        'm_produk.keterangan',
                        'm_supplier.nama_supplier'
                    );
        if(!isset($request->value)){
            $data_kadaluarsa
                ->where('m_produk.status', 1)
                ->WhereBetween('m_produk.created_at',[$date_start,$date_end]);
        }else{
            $data_kadaluarsa 
                ->where('m_produk.status', 1)
                ->WhereBetween('m_produk.created_at',[$date_start,$date_end])
                ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_kadaluarsa->get();
        $count = ($data_kadaluarsa->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = explode(' - ' ,$request->tanggal);
        $date_start = Carbon::parse($date[0])->format('Y-m-d 00:00:01');
        $date_end = Carbon::parse($date[1])->format('Y-m-d 23:59:59');
        $cari = $request->input('cari');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');
        $tombol_tgl = $request->input('button_tgl');
        $date = (date('d M Y'));

        if($tombol_excel == 'excel'){
            if($cari != ''){
                $data_kadaluarsa_excel = DB::table('m_produk')
                        ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                        ->leftJoin('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                        ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                        ->select('m_produk.created_at AS tgl_transaksi',
                            'm_produk.id',
                            'm_produk.kode_produk',
                            'm_produk.nama_produk', 
                            'm_jenis_obat.nama_jenis',
                            'm_produk.qty',
                            'm_produk_unit.nama_unit',
                            'm_produk.keterangan',
                            'm_supplier.nama_supplier'
                        )
                        ->where('m_produk.status', 1)
                        ->WhereBetween('m_produk.created_at',[$date_start,$date_end])
                        ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%")
                        ->get();
            }else{
                $data_kadaluarsa_excel = DB::table('m_produk')
                        ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                        ->leftJoin('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                        ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                        ->select('m_produk.created_at AS tgl_transaksi',
                            'm_produk.id',
                            'm_produk.kode_produk',
                            'm_produk.nama_produk', 
                            'm_jenis_obat.nama_jenis',
                            'm_produk.qty',
                            'm_produk_unit.nama_unit',
                            'm_produk.keterangan',
                            'm_supplier.nama_supplier'
                        )
                        ->where('m_produk.status', 1)
                        ->WhereBetween('m_produk.created_at',[$date_start,$date_end])
                        ->get();
            }
            return view ('laporan.retur_kadaluarsa.view', compact('data_kadaluarsa_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari != ''){
                $data_kadaluarsa_pdf = DB::table('m_produk')
                        ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                        ->leftJoin('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                        ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                        ->select('m_produk.created_at AS tgl_transaksi',
                            'm_produk.id',
                            'm_produk.kode_produk',
                            'm_produk.nama_produk', 
                            'm_jenis_obat.nama_jenis',
                            'm_produk.qty',
                            'm_produk_unit.nama_unit',
                            'm_produk.keterangan',
                            'm_supplier.nama_supplier'
                        )
                        ->where('m_produk.status', 1)
                        ->WhereBetween('m_produk.created_at',[$date_start,$date_end])
                        ->orWhere('m_produk.nama_produk', 'like', "%$request->value%")
                        ->orWhere('m_supplier.nama_supplier', 'like', "%$request->value%")
                        ->get();
            }else{
                $data_kadaluarsa_pdf = DB::table('m_produk')
                        ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                        ->leftJoin('m_produk_unit','m_produk.id_unit','m_produk_unit.id')
                        ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                        ->select('m_produk.created_at AS tgl_transaksi',
                            'm_produk.id',
                            'm_produk.kode_produk',
                            'm_produk.nama_produk', 
                            'm_jenis_obat.nama_jenis',
                            'm_produk.qty',
                            'm_produk_unit.nama_unit',
                            'm_produk.keterangan',
                            'm_supplier.nama_supplier'
                        )
                        ->where('m_produk.status', 1)
                        ->WhereBetween('m_produk.created_at',[$date_start,$date_end])
                        ->get();
            }
            $pdf = PDF::loadview('laporan.retur_kadaluarsa.pdf', compact('data_kadaluarsa_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }elseif($tombol_tgl == 'tgl'){

        }
    }
}
