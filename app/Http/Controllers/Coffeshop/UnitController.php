<?php

namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class UnitController extends Controller
{
    public function index()
    {
        return view('coffeshop.master.units');
    }

    public function getUnitsData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $status = $request->input('status', 1);

            $query = DB::table('cs_units')
                ->select('id', 'name', 'symbol', 'created_at', 'updated_at', 'is_active');

            if ($id) {
                $units = $query->where('id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('symbol', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('is_active', (int)$status);
            }

            $units = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar satuan berhasil diambil',
                'data'    => $units,
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
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:cs_units,name',
            'symbol' => 'required|string|max:10|unique:cs_units,symbol',
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
            DB::table('cs_units')->insert([
                'name'       => $request->name,
                'symbol'     => $request->symbol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Satuan berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan satuan: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required|integer|exists:cs_units,id',
            'name'   => 'required|string|max:255|unique:cs_units,name,' . $request->id,
            'symbol' => 'required|string|max:10|unique:cs_units,symbol,' . $request->id,
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
            DB::table('cs_units')
                ->where('id', $request->id)
                ->update([
                    'name'       => $request->name,
                    'symbol'     => $request->symbol,
                    'updated_at' => now(),
                ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Satuan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui satuan: ' . $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cs_units,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'ID satuan tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            // Soft delete (ubah status is_active)
            DB::table('cs_units')
                ->where('id', $request->id)
                ->update([
                    'is_active' => 0,
                    'updated_at' => now(),
                ]);

            // Atau hard delete (hapus permanen)
            // DB::table('cs_units')->where('id', $request->id)->delete();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Satuan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus satuan: ' . $e->getMessage()
            ]);
        }
    }
}
