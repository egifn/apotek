<?php

namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
    {
        return view('coffeshop.master.stocks');
    }

    public function getStocksData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $branch = $request->input('status', 1);

            $query = DB::table('cs_stocks')
                ->select(
                    'cs_stocks.id',
                    'cs_stocks.stock_available',
                    'cs_stocks.min_stock',
                    'cs_ingredients.name',
                    'cs_branches.name as branch_name'
                )
                ->join('cs_branches', 'cs_stocks.id_branch', '=', 'cs_branches.id')
                ->join('cs_ingredients', 'cs_stocks.id_ingredients', '=', 'cs_ingredients.code_ingredient');
                // dd($query->get());  

            if ($id) {
                $query->where('cs_stocks.id', $id);
            }

            if ($branch) {
                $query->where('cs_branches.id', $branch);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('cs_ingredients.name', 'like', "%$search%")
                      ->orWhere('cs_branches.name', 'like', "%$search%");
                });
            }

            $stocks = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar stok berhasil diambil.',
                'data'    => $stocks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data stok.',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|integer|exists:cs_stocks,id',
            'min_stock' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'type'    => 'validation',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('cs_stocks')
                ->where('id', $request->id)
                ->update([
                    'min_stock' => $request->min_stock,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Minimal stok berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
            ]);
        }
    }

    public function tambahStok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'         => 'required|integer|exists:cs_stocks,id',
            'tambah_stok' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'type'    => 'validation',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            // Ambil data stok saat ini
            $stock = DB::table('cs_stocks')
                ->where('id', $request->id)
                ->first();

            if (!$stock) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Stok tidak ditemukan'
                ]);
            }

            // Tambahkan stok yang tersedia
            $newStockAvailable = $stock->stock_available + $request->tambah_stok;

            // Update stok
            DB::table('cs_stocks')
                ->where('id', $request->id)
                ->update([
                    'stock_available' => $newStockAvailable,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Stok berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan stok: ' . $e->getMessage()
            ]);
        }
    }
}