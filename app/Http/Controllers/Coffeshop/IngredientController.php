<?php

namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    public function index()
    {
        return view('coffeshop.master.ingredients');
    }

    public function getIngredientsData(Request $request)
    {
        // dd($request->all());
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 10);
            $status = $request->input('status', 1);

            $query = DB::table('cs_ingredients as i')
                ->select(
                    'i.id',
                    'i.name',
                    'i.purchase_price',
                    'i.quantity_purchase',
                    'i.price_per_unit',
                    'i.created_at',
                    'i.updated_at',
                    'i.is_active',
                    'u.symbol as unit_symbol',
                    'u.name as unit_name',
                    'u.id as unit_id'
                )
                ->leftJoin('cs_units as u', 'i.unit_id', '=', 'u.id');

            if ($id) {
                $ingredients = $query->where('i.id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('i.name', 'like', "%$search%")
                        ->orWhere('u.symbol', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('i.is_active', (int)$status);
            }

            $ingredients = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar bahan baku berhasil diambil',
                'data'    => $ingredients,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data bahan baku',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function getUnits()
    {
        try {
            $units = DB::table('cs_units')
                ->where('is_active', 1)
                ->select('id', 'name', 'symbol')
                ->get();

            return response()->json([
                'status' => true,
                'data'   => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data satuan',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'unit_id'           => 'required|integer|exists:cs_units,id',
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
            $pricePerUnit = $request->purchase_price / $request->quantity_purchase;
            $kode = 'ING-' . strtoupper(substr($request->name, 0, 3)) . '-' . date('YmdHis');
            $user = Auth::user()->kd_lokasi;
            
            DB::table('cs_ingredients')->insert([
                'name'              => $request->name,
                'code_ingredient'   => $kode,
                'unit_id'           => $request->unit_id,
                'purchase_price'    => $request->purchase_price,
                'quantity_purchase' => $request->quantity_purchase,
                'price_per_unit'    => $pricePerUnit,
                'losses'            => $request->losses,
                'is_active'         => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $stock_insert = $request->quantity_purchase * (1-($request->losses/100));
           
            DB::table('cs_stocks')->insert([
                'id_branch'         => $user,
                'id_ingredients'    => $kode,
                'stock_available'   => $stock_insert,
                'min_stock'         => 0,
                'created_at'        => now(),
            ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Bahan baku berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan bahan baku: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                => 'required|integer|exists:cs_ingredients,id',
            'name'              => 'required|string|max:255',
            'unit_id'           => 'required|integer|exists:cs_units,id',
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
            $pricePerUnit = $request->purchase_price / $request->quantity_purchase;

            DB::table('cs_ingredients')
                ->where('id', $request->id)
                ->update([
                    'name'              => $request->name,
                    'unit_id'           => $request->unit_id,
                    'purchase_price'    => $request->purchase_price,
                    'quantity_purchase' => $request->quantity_purchase,
                    'price_per_unit'    => $pricePerUnit,
                    'updated_at'        => now(),
                ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Bahan baku berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui bahan baku: ' . $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cs_ingredients,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'ID bahan baku tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            // Soft delete (ubah status is_active)
            DB::table('cs_ingredients')
                ->where('id', $request->id)
                ->update([
                    'is_active' => 0,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Bahan baku berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus bahan baku: ' . $e->getMessage()
            ]);
        }
    }
}
