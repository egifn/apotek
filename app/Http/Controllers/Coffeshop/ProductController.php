<?php

namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return view('coffeshop.master.products');
    }

    public function getProductsData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $status = $request->input('status', 1);

            $query = DB::table('cs_products as p')
                ->select(
                    'p.id',
                    'p.code',
                    'p.name',
                    'p.description',
                    'p.selling_price',
                    'p.hpp',
                    'p.is_active',
                    'p.created_at',
                    'p.updated_at',
                    'c.name as category_name'
                )
                ->leftJoin('cs_categories as c', 'p.category_id', '=', 'c.id');

            if ($id) {
                $products = $query->where('p.id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('p.name', 'like', "%$search%")
                        ->orWhere('p.code', 'like', "%$search%")
                        ->orWhere('c.name', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('p.is_active', (int)$status);
            }

            $products = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar produk berhasil diambil',
                'data'    => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data produk',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function getCategories()
    {
        try {
            $categories = DB::table('cs_categories')
                ->where('is_active', 1)
                ->select('id', 'name')
                ->get();

            return response()->json([
                'status' => true,
                'data'   => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data kategori',
                'error'   => $e->getMessage()
            ]);
        }
    }


    public function getIngredients()
    {
        try {
            $ingredients = DB::table('cs_ingredients as i')
                ->join('cs_units as u', 'i.unit_id', '=', 'u.id')
                ->where('i.is_active', 1)
                ->select('i.id', 'i.name', 'u.symbol as unit_symbol', 'i.price_per_unit')
                ->get();

            return response()->json([
                'status' => true,
                'data'   => $ingredients
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data bahan baku',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function getCompositions(Request $request)
    {
        $product_id = $request->input('product_id');

        if (!$product_id) {
            return response()->json([
                'status'  => false,
                'message' => 'ID produk tidak diberikan'
            ]);
        }
    
        try {
            $compositions = DB::table('cs_product_compositions as pc')
                ->join('cs_ingredients as i', 'pc.ingredient_id', '=', 'i.id')
                ->join('cs_units as u', 'i.unit_id', '=', 'u.id')
                ->where('pc.product_id', $product_id)
                ->select(
                    'pc.ingredient_id',
                    'pc.quantity',
                    'i.price_per_unit',
                    'u.symbol as unit_symbol'
                )
                ->get();

            return response()->json([
                'status' => true,
                'data'   => $compositions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data komposisi produk',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'           => 'required|string|max:50|unique:cs_products,code',
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|integer|exists:cs_categories,id',
            'selling_price'  => 'required|numeric|min:0',
            'hpp'           => 'required|numeric|min:0',
            'compositions'   => 'required|array|min:1',
            'compositions.*.ingredient_id' => 'required|integer|exists:cs_ingredients,id',
            'compositions.*.quantity' => 'required|numeric|min:0.01',
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
            // Insert produk
            $productId = DB::table('cs_products')->insertGetId([
                'code'          => $request->code,
                'name'         => $request->name,
                'category_id'   => $request->category_id,
                'description'  => $request->description,
                'selling_price' => $request->selling_price,
                'hpp'          => $request->hpp,
                'is_active'    => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Insert komposisi bahan baku
            $compositions = [];
            foreach ($request->compositions as $comp) {
                $compositions[] = [
                    'product_id'    => $productId,
                    'ingredient_id' => $comp['ingredient_id'],
                    'quantity'      => $comp['quantity'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            DB::table('cs_product_compositions')->insert($compositions);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Produk berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
            ]);
        }
    }

   // ProductController.php

// Tambahkan method untuk menghapus komposisi
    public function deleteCompositions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:cs_products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'ID produk tidak valid'
            ]);
        }

        try {
            DB::table('cs_product_compositions')
                ->where('product_id', $request->product_id)
                ->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Komposisi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus komposisi: ' . $e->getMessage()
            ]);
        }
    }

    // Modifikasi method update
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'            => 'required|integer|exists:cs_products,id',
            'code'          => 'required|string|max:50|unique:cs_products,code,' . $request->id,
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|integer|exists:cs_categories,id',
            'selling_price' => 'required|numeric|min:0',
            'hpp'          => 'required|numeric|min:0',
            'compositions'  => 'required|array|min:1',
            'compositions.*.ingredient_id' => 'required|integer|exists:cs_ingredients,id',
            'compositions.*.quantity' => 'required|numeric|min:0.01',
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
            // Update produk
            DB::table('cs_products')
                ->where('id', $request->id)
                ->update([
                    'code'          => $request->code,
                    'name'         => $request->name,
                    'category_id'   => $request->category_id,
                    'description'  => $request->description,
                    'selling_price' => $request->selling_price,
                    'hpp'          => $request->hpp,
                    'updated_at'   => now(),
                ]);

            // Hapus komposisi lama
            DB::table('cs_product_compositions')
                ->where('product_id', $request->id)
                ->delete();

            // Insert komposisi baru
            $compositions = [];
            foreach ($request->compositions as $comp) {
                $compositions[] = [
                    'product_id'    => $request->id,
                    'ingredient_id' => $comp['ingredient_id'],
                    'quantity'      => $comp['quantity'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            DB::table('cs_product_compositions')->insert($compositions);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Produk berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
            ]);
        }
    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cs_products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'ID produk tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            // Soft delete produk
            DB::table('cs_products')
                ->where('id', $request->id)
                ->update([
                    'is_active' => 0,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ]);
        }
    }
}
