<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    public function index()
    {
        return view('coffeshop.pembelian.index');
    }

    public function getTransaksiData(Request $request)
    {
        // dd($request->all());
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);

            $query = DB::table('cs_pembelian as p')
                ->select(
                    'p.kode_pembelian',
                    'p.tanggal',
                    'p.supplier_id',
                    's.nama_supplier',
                    'p.jenis',
                    'p.termin',
                    'p.tgl_jatuh_tempo',
                    'p.total',
                    'p.keterangan',
                    'p.status_pembelian',
                    'p.status_pembayaran',
                    'p.id_user_input',
                    'u.name',
                    'p.kode_cabang',
                    'c.name as branch_name'
                )
                ->join('m_supplier as s', 'p.supplier_id', '=', 's.id')
                ->join('users as u','p.id_user_input', '=', 'u.id')
                ->join('cs_branches as c', 'c.id', '=', 'p.kode_cabang');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('p.kode_pembelian', 'like', "%$search%")
                      ->orWhere('p.nama_supplier', 'like', "%$search%");
                });
            }

            $data = $query->orderBy('p.id')->paginate($limit);

            // $pembelian = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar produk berhasil diambil',
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data produk',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = now()->format('dmy');

        return view ('coffeshop.pembelian.create');
    }

    public function getSupplier(Request $request)
    {
        $data_supplier = DB::table('m_supplier')
                        ->where('kode_produk','coffeshop')
                        ->where('nama_supplier','like','%'.$request->q.'%');

        $data = $data_supplier->get();
        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);

    }

    public function getProduk(Request $request)
    {
        $data_produk = DB::table('cs_ingredients')
                    ->join('cs_units','cs_ingredients.unit_id','=','cs_units.id')
                    ->join('cs_stocks','cs_ingredients.code_ingredient','=','cs_stocks.id_ingredients')
                    ->select('cs_ingredients.id','cs_ingredients.name','cs_ingredients.unit_id', 'cs_stocks.stock_available','cs_units.name AS nama_unit', 'code_ingredient', 'cs_units.symbol')
                    ->where('cs_ingredients.name','like','%'.$request->q.'%');
                    
        $data = $data_produk->get();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];
        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            /* ---------- VALIDASI ---------- */
            if (!$request->filled(['supplier_id', 'jenis'])) {
                return response()->json(['success' => false, 'message' => 'Supplier & jenis transaksi wajib'], 422);
            }
            if (empty($request->produk)) {
                return response()->json(['success' => false, 'message' => 'Tambahkan minimal satu produk'], 422);
            }

            /* ---------- HEADER ---------- */
            $kode = 'PO-' . date('ymd') . '-' . rand(1000, 9999);
            $tglJt = $request->jt_formatted
                ? Carbon::createFromFormat('Y-m-d', $request->jt_formatted)
                : Carbon::today();

            /* ---------- TOTAL & DETAIL ---------- */
            $totalPembelian = 0;
            foreach ($request->produk as $id => $item) {
                $harga      = (float) ($item['harga']       ?? 0);
                $jumlah     = (int)   ($item['jumlah']      ?? 1);
                $jumlahBeli = (int)   ($item['jumlah_beli'] ?? 0);
                $diskonPct  = (float) ($item['diskon_persen'] ?? 0);
                $diskonRp   = (float) ($item['diskon_rp']    ?? 0);
                $ppnPct     = (float) ($item['ppn_persen']   ?? 0);
                $ppnRp      = (float) ($item['ppn_rp']       ?? 0);

                $bruto   = $harga * $jumlahBeli;
                $diskon  = ($bruto * $diskonPct / 100) + $diskonRp;
                $ppn     = ($bruto * $ppnPct    / 100) + $ppnRp;
                $sub     = max(0, $bruto - $diskon + $ppn);

                $totalPembelian += $sub;

                /* ---- SIMPAN DETAIL ---- */
                DB::table('cs_pembelian_detail')->insert([
                    'kode_pembelian' => $kode,
                    'ingredient_id'  => $id,
                    'qty_unit'       => $jumlah,
                    'harga'          => $harga,
                    'qty'            => $jumlahBeli,
                    'diskon_persen'  => $diskonPct,
                    'diskon_rp'      => $diskonRp,
                    'ppn_persen'     => $ppnPct,
                    'ppn_rp'         => $ppnRp,
                    'subtotal'       => $sub,
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now(),
                ]);

                /* ---- UPDATE STOCK ---- */
                // $unitQty = $jumlah * $jumlahBeli;          // jumlah satuan kecil
                // $pricePerUnit = $jumlah > 0 ? $harga / $jumlah : $harga;

                // DB::table('cs_stocks')
                //     ->where('id_ingredients', $id)          // INT = INT (tanpa kutip)
                //     ->increment('stock_available', $unitQty);


                // DB::table('cs_ingredients')
                //     ->where('code_ingredient', $id)
                //     ->update([
                //         'purchase_price'   => $harga,
                //         'quantity_purchase'=> $unitQty,
                //         'price_per_unit'   => $pricePerUnit,
                //         'updated_at'       => Carbon::now(),
                //     ]);
            }

            /* ---- INSERT HEADER ---- */
            DB::table('cs_pembelian')->insert([
                'kode_pembelian' => $kode,
                'tanggal'        => Carbon::today(),
                'supplier_id'    => $request->supplier_id,
                'jenis'          => $request->jenis,
                'termin'         => $request->termin ?? 0,
                'tgl_jatuh_tempo'=> $tglJt->format('Y-m-d'),
                'total'          => $totalPembelian,
                'keterangan'     => 'Transaksi Pembelian',
                'id_user_input'  => Auth::id(),
                'kode_cabang'    => Auth::user()->kd_lokasi,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            DB::commit();
            return response()->json([
                'success'  => true,
                'message'  => 'Pembelian berhasil dibuat',
                'redirect' => route('coffeshop.master.pembelian.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan pembelian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pembelian: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getTransaksiDataDetail($kode)
    {
        try {
            $data_header = DB::table('cs_pembelian as p')
                ->select(
                    'p.*',
                    'u.name',
                    's.nama_supplier',
                    'c.name'
                )
                ->join('m_supplier as s', 'p.supplier_id', '=', 's.id')
                ->join('users as u', 'p.id_user_input', '=', 'u.id')
                ->join('cs_branches as c', 'p.kode_cabang', '=', 'c.id')
                ->where('p.kode_pembelian', $kode)
                ->first();

            $data_detail = DB::table('cs_pembelian_detail as d')
                ->select(
                    'd.*',
                    'i.name as ingredient',
                    'u.name as satuan'
                )
                ->join('cs_ingredients as i', 'd.ingredient_id', '=', 'i.code_ingredient')
                ->join('cs_units as u', 'i.unit_id', '=', 'u.id')
                ->where('d.kode_pembelian', $kode)
                ->get();


            return response()->json([
                'success' => true,
                'header' => $data_header,
                'detail' => $data_detail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pembelian: ' . $e->getMessage()
            ]);
        }
    }

    public function terimaBarang(Request $request, $kode)
    {
        dd($request->all());
        DB::beginTransaction();
        try {
            // Hitung total dari detail
            $totalPembelian = 0;
            foreach ($request->detail as $d) {
                $totalPembelian += $d['subtotal'];
            }

            // Update status pembelian dan total
            DB::table('cs_pembelian')
                ->where('kode_pembelian', $kode)
                ->update([
                    'no_faktur' => $request->no_faktur, 
                    'status_pembelian' => 1, // Diterima
                    'total' => $totalPembelian,
                    'updated_at' => Carbon::now()
                ]);

            // Update detail jika sudah ada, insert jika belum
            foreach ($request->detail as $d) {
                $existing = DB::table('cs_pembelian_detail')
                    ->where('kode_pembelian', $kode)
                    ->where('ingredient_id', $d['ingredient_id'])
                    ->first();

                $detailData = [
                    'harga'          => $d['harga'],
                    'qty_unit'       => $d['satuan'],
                    'qty'            => $d['qty'],
                    'diskon_persen'  => $d['diskon_persen'],
                    'diskon_rp'      => $d['diskon_rp'],
                    'ppn_persen'     => $d['ppn_persen'],
                    'ppn_rp'         => $d['ppn_rp'],
                    'subtotal'       => $d['subtotal'],
                    'updated_at'     => Carbon::now(),
                ];
                if ($existing) {
                    DB::table('cs_pembelian_detail')
                        ->where('kode_pembelian', $kode)
                        ->where('ingredient_id', $d['ingredient_id'])
                        ->update($detailData);
                } else {
                    $detailData['kode_pembelian'] = $kode;
                    $detailData['ingredient_id'] = $d['ingredient_id'];
                    $detailData['created_at'] = Carbon::now();
                    DB::table('cs_pembelian_detail')->insert($detailData);
                }

                // Update stock
                $jumlah = is_numeric($d['satuan']) ? (float)$d['satuan'] : 1;
                $jumlahBeli = (int)$d['qty'];
                $unitQty = $jumlah * $jumlahBeli;
                $harga = (float)$d['harga'];
                $pricePerUnit = $jumlah > 0 ? $harga / $jumlah : $harga;

                DB::table('cs_stocks')
                    ->where('id_ingredients', $d['ingredient_id'])
                    ->increment('stock_available', $unitQty);

                DB::table('cs_ingredients')
                    ->where('code_ingredient', $d['ingredient_id'])
                    ->update([
                        'purchase_price'   => $harga,
                        'quantity_purchase'=> $unitQty,
                        'price_per_unit'   => $pricePerUnit,
                        'updated_at'       => Carbon::now(),
                    ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diterima'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menerima barang: ' . $e->getMessage()
            ], 500);
        }
    }

}


