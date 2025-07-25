<?php

namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return view('coffeshop.master.category');
    }

    public function getCategoriesData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $status = $request->input('status', 1);

            $query = DB::table('cs_categories')
                ->select('id', 'name', 'description', 'created_at', 'updated_at', 'is_active');

            if ($id) {
                $categories = $query->where('id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('is_active', (int)$status);
            }

            $categories = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar kategori berhasil diambil',
                'data'    => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data kategori',
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
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
            DB::table('cs_categories')->insert([
                'name'        => $request->name,
                'description' => $request->description,
                'is_active' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Kategori berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'          => 'required|integer|exists:cs_categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
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
            DB::table('cs_categories')
                ->where('id', $request->id)
                ->update([
                    'name'        => $request->name,
                    'description' => $request->description,
                    'updated_at'  => now(),
                ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Kategori berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui kategori: ' . $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cs_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'ID kategori tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            // Soft delete (ubah status is_active)
            DB::table('cs_categories')
                ->where('id', $request->id)
                ->update([
                    'is_active' => 0,
                    'updated_at' => now(),
                ]);

            // Atau hard delete (hapus permanen)
            // DB::table('cs_categories')->where('id', $request->id)->delete();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
            ]);
        }
    }
}
