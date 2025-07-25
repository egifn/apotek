<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProdukImport;
use RealRashid\SweetAlert\Facades\Alert;
use App\Obat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;

class ObatController extends Controller
{
    public function index()
    {
        $data_cabang = DB::table('m_cabang')
                        ->get();

        $data_kategori = DB::table('m_kategori_obat')
                    ->get();

        $data_jenis = DB::table('m_jenis_obat')
                    ->get();

    	$data_unit = DB::table('m_produk_unit')
    				->get();

        $data_vendor = DB::table('m_supplier')
                    ->get();

    	return view ('master_data.obat.index', compact('data_cabang','data_kategori','data_jenis','data_unit','data_vendor'));
    }

    public function getDataProduk(Request $request)
    {
        $data_obat = DB::table('m_produk')
                ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
    		    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                ->join('m_produk_unit as produk_unit_terkecil','m_produk.id_unit','=','produk_unit_terkecil.id')
                ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                ->leftJoin('m_produk_unit_varian', function($join)
                        {
                            $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                            $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                        }) 
                ->leftJoin('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
    		    ->join('users','m_produk.id_user_input','=','users.id')
                ->select('m_produk.kode_produk','m_produk.barcode','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                        'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','m_supplier.nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit','m_produk.id_unit','produk_unit_terkecil.nama_unit as nama_unit_terkecil',
                        'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                    'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name');
        
        if (!isset($request->value)) {
                $data_obat->where('m_produk.status', 0);
        }else{
                $data_obat->where('m_produk.status', 0)
                            ->where('m_produk.nama_produk','like', "%$request->value%")
                            ->orWhere('m_produk.kode_produk','like', "%$request->value%")
                            ->orWhere('m_produk.komposisi','like', "%$request->value%")
                            ->orWhere('m_jenis_obat.nama_jenis','like', "%$request->value%")
                            ->orWhere('m_produk.tipe', 'like', "%$request->value%");
        }

        $data  = $data_obat->get();
        $count = ($data_obat->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        // $getRow = Obat::where('kode_cabang', $request->kode_cabang)->get();
        // $rowCount = $getRow->count();

        $getRow = DB::table('m_produk')
                    ->select(DB::raw('MAX(id) as urut_no'))
                    ->where('kode_cabang', $request->kode_cabang)
                    ->first();

        //$kode = $request->kode_cabang.'-'."000001";

        $rowCount = $getRow->urut_no + 1;

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                    $kode = $request->kode_cabang.'-'."00000".''.($rowCount + 1);
            } else if ($rowCount < 99) {
                    $kode = $request->kode_cabang.'-'."0000".''.($rowCount + 1);
            } else if ($rowCount < 999) {
                    $kode = $request->kode_cabang.'-'."000".''.($rowCount + 1);
            } else if ($rowCount < 9999) {
                    $kode = $request->kode_cabang.'-'."00".''.($rowCount + 1);
            } else if ($rowCount < 99999) {
                    $kode = $request->kode_cabang.'-'."0".''.($rowCount + 1);
            } else {
                    $kode = $request->kode_cabang.'-'.($rowCount + 1);
            }
        } 
        // insert Header //
        DB::table('m_produk')->insert([
                'kode_produk' => $kode,
                'kode_cabang' => $request->kode_cabang,
                'barcode' => $request->barcode,
                'no_batch' => $request->no_batch,
                'nama_produk' => $request->nama_produk,
                'komposisi' => $request->komposisi,
                'id_supplier' => $request->vendor, 
                'id_jenis' => $request->id_jenis,
                'tipe' => $request->tipe,
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
                'qty' => $request->qty,
                'qty_min' => $request->qty_min,
                'id_unit' => $request->unit_terkecil,
                'id_user_input' => Auth::user()->id,
        ]);
        // End Header //
        
        // Insert Detail //
        $kode_unit = $request->kode_unit;
        $stok_unit = $request->stok_unit;
        $harga_beli_unit = str_replace(",", "", $request->harga_beli_unit);
        $margin_persen_unit = $request->margin_persen_unit;
        $margin_rp_unit = str_replace(",", "", $request->margin_rp_unit);
        $harga_jual_unit = str_replace(",", "", $request->harga_jual_unit);

        for ($i=0; $i < count((array)$kode_unit); $i++) {
            DB::table('m_produk_unit_varian')->insert([
                'kode_produk' => $kode,
                'id_produk_unit' => $kode_unit[$i],
                'qty' => $stok_unit[$i],
                'harga_beli' => $harga_beli_unit[$i],
                'margin_rp' => $margin_rp_unit[$i],
                'margin_persen' => $margin_persen_unit[$i],
                'harga_jual' => $harga_jual_unit[$i],
                'id_user_input' => Auth::user()->id,
                'kode_cabang' => $request->kode_cabang
            ]);
        } 
        // End Detail //

        $output = [
            'msg'  => 'Data Unit Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDetailData(Request $request)
    {
        //$data = DB::table('m_produk')->where('kode_produk', $request->kode_produk)->first();

        $dataDetailProduk = DB::table('m_produk')
                ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
    		    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                ->join('m_produk_unit_varian','m_produk.kode_produk','=','m_produk_unit_varian.kode_produk')
                ->join('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
    		    ->join('users','m_produk.id_user_input','=','users.id')
                ->select('m_produk.kode_produk','m_produk.barcode','m_produk.id_unit','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                        'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit',
                        'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                    'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name')
                ->where('m_produk.kode_produk', $request->kode_produk)->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $dataDetailProduk
        ];

        return response()->json($output, 200);
    }

    public function update(Request $request)
    {
        DB::table('m_produk')->where('kode_produk', $request->kode_produk)->update([
                'kode_cabang' => $request->kode_cabang,
                'barcode' => $request->barcode,
                'no_batch' => $request->no_batch,
                'nama_produk' => $request->nama_produk,
                'komposisi' => $request->komposisi,
                'id_jenis' => $request->id_jenis,
                'id_unit' => $request->id_unit,
                'tipe' => $request->tipe,
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
                'qty' => $request->qty,
                'qty_min' => $request->qty_min,
                // 'harga_beli' => str_replace(",", "", $request->harga_beli), 
                // 'margin_rp' => str_replace(",", "", $request->margin_rp), 
                // 'margin_persen' => $request->margin_persen,
                // 'harga_jual' => str_replace(",", "",$request->harga_jual), 
                'id_user_input' => Auth::user()->id
        ]);

        // update Detail //
        $kode_unit = $request->kode_unit;
        $stok_unit = $request->stok_unit;
        $harga_beli_unit = str_replace(",", "", $request->harga_beli_unit);
        $margin_persen_unit = $request->margin_persen_unit;
        $margin_rp_unit = str_replace(",", "", $request->margin_rp_unit);
        $harga_jual_unit = str_replace(",", "", $request->harga_jual_unit);
        
        for ($i=0; $i < count((array)$kode_unit); $i++) {
            $getRow = DB::table('m_produk_unit_varian')->select(DB::raw('COUNT(m_produk_unit_varian.id_produk_unit) as data_unit'))
                        ->where('m_produk_unit_varian.kode_produk', $request->kode_produk)
                        ->where('m_produk_unit_varian.id_produk_unit', $kode_unit[$i]);
            $rowCount = $getRow->count();

            // $caridata = DB::table('m_produk_unit_varian')->select('m_produk_unit_varian.id_produk_unit')
            //             ->where('m_produk_unit_varian.kode_produk', $request->kode_produk)
            //             ->where('m_produk_unit_varian.id_produk_unit', $kode_unit[$i])->first();
            
            if ($rowCount > 0) {
                $data = DB::table('m_produk_unit_varian')
                ->where('kode_produk', $request->kode_produk)
                ->where('id_produk_unit', $kode_unit[$i])
                ->update([
                    'id_produk_unit' => $kode_unit[$i],
                    'qty' => $stok_unit[$i],
                    'harga_beli' => $harga_beli_unit[$i],
                    'margin_rp' => $margin_rp_unit[$i],
                    'margin_persen' => $margin_persen_unit[$i],
                    'harga_jual' => $harga_jual_unit[$i],
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => $request->kode_cabang
                ]);
            }else{
                DB::table('m_produk_unit_varian')->insert([
                    'kode_produk' => $request->kode_produk,
                    'id_produk_unit' => $kode_unit[$i],
                    'qty' => $stok_unit[$i],
                    'harga_beli' => $harga_beli_unit[$i],
                    'margin_rp' => $margin_rp_unit[$i],
                    'margin_persen' => $margin_persen_unit[$i],
                    'harga_jual' => $harga_jual_unit[$i],
                    'id_user_input' => Auth::user()->id,
                    'kode_cabang' => $request->kode_cabang
                ]);
            }
        } 
        // End Detail //

        $output = [
            'message'  => 'Data Unit Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $cari_produk = $request->input('cari_produk');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');
        
        if($tombol_excel == 'excel'){
            if($cari_produk != ''){
                $data_obat_excel = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->leftJoin('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                    ->leftJoin('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
                    ->join('users','m_produk.id_user_input','=','users.id')
                    ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                            'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit',
                            'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                        'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name')
                    ->where('m_produk.status', 0)
                    ->where('m_produk.nama_produk', 'like', "%$cari_produk%")
                    ->get();
            }else{
                $data_obat_excel = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->leftJoin('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                    ->leftJoin('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
                    ->join('users','m_produk.id_user_input','=','users.id')
                    ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                            'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit',
                            'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                        'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name')
                    ->where('m_produk.status', 0)
                    ->get();
            }
            return view ('master_data.obat.view', compact('data_obat_excel'));
        }elseif($tombol_pdf == 'pdf'){
            if($cari_produk != ''){
                $data_obat_pdf = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->leftJoin('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                    ->leftJoin('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
                    ->join('users','m_produk.id_user_input','=','users.id')
                    ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                            'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit',
                            'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                        'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name')
                    ->where('m_produk.status', 0)
                    ->where('m_produk.nama_produk', 'like', "%$cari_produk%")
                    ->get();
            }else{
                $data_obat_pdf = DB::table('m_produk')
                    ->join('m_jenis_obat','m_produk.id_jenis','=','m_jenis_obat.id')
                    ->join('m_cabang','m_produk.kode_cabang','=','m_cabang.kode_cabang')
                    ->leftJoin('m_supplier','m_produk.id_supplier','=','m_supplier.id')
                    ->leftJoin('m_produk_unit_varian', function($join)
                            {
                                $join->on('m_produk.kode_produk','=','m_produk_unit_varian.kode_produk');
                                $join->on('m_produk.id_unit','=','m_produk_unit_varian.id_produk_unit');
                            }) 
                    ->leftJoin('m_produk_unit','m_produk_unit_varian.id_produk_unit','=','m_produk_unit.id')
                    ->join('users','m_produk.id_user_input','=','users.id')
                    ->select('m_produk.kode_produk','m_produk.nama_produk','m_produk.komposisi','m_produk.id_jenis','m_jenis_obat.nama_jenis',
                            'm_produk.kode_pembelian','m_produk.tipe','m_produk.tgl_kadaluarsa','m_produk.no_batch','m_produk.id_supplier','nama_supplier','m_produk.qty','m_produk_unit_varian.qty AS qty_unit',
                            'm_produk_unit_varian.id_produk_unit','m_produk_unit.nama_unit','m_produk_unit_varian.harga_beli','m_produk_unit_varian.margin_persen','m_produk_unit_varian.margin_rp','m_produk_unit_varian.harga_jual',
                        'm_produk.qty_min','m_produk.kode_cabang','m_cabang.nama_cabang','m_produk.id_user_input','users.name')
                    ->where('m_produk.status', 0)
                    ->get();
            }
            $pdf = PDF::loadview('master_data.obat.pdf', compact('data_obat_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();

            
            
        }
    }

}
