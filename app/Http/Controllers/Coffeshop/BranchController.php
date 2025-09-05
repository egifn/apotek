<?php

namespace App\Http\Controllers\CoffeShop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index()
    {
        return view('coffeshop.master.branches');
    }

    public function getBranchesData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 10);
            $status = $request->input('status', 1);
            $page   = $request->input('page', 1);

            // Query dasar
            $query = DB::table('cs_branches')
                ->select('id', 'name', 'address', 'phone', 'created_at', 'updated_at', 'is_active');

            // Jika by ID
            if ($id) {
                $branches = $query->where('id', $id)->first();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data cabang berhasil diambil',
                    'data'    => [$branches],
                ]);
            }

            // Filter status
            if ($status !== null) {
                $query->where('is_active', (int)$status);
            }

            // Filter pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            }

            // Pagination
            $total = $query->count();
            $branches = $query
                ->offset(($page - 1) * $limit)
                ->limit($limit)
                ->get();

            $totalPages = ceil($total / $limit);

            return response()->json([
                'status'      => true,
                'message'     => 'Daftar cabang berhasil diambil',
                'data'        => $branches,
                'pagination'  => [
                    'current_page' => $page,
                    'total_pages'  => $totalPages,
                    'total_items'  => $total,
                    'per_page'     => $limit
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data cabang',
                'error'   => [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine()
                ]
            ]);
        }
    }

    // Fungsi store, update, destroy tetap sama seperti sebelumnya
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'nullable|string',
            'address'           => 'required|string',
            'phone'             => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'type'    => 'validation',
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try {
            DB::table('cs_branches')->insert([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil Tambah Data'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => 'Gagal Tambah Data: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                => 'required|integer|exists:cs_branches,id',
            'name'              => 'nullable|string',
            'address'           => 'required|string',
            'phone'             => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'type'    => 'validation',
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'updated_at' => now(),
            ];

            DB::table('cs_branches')->where('id', $request->id)->update($dataToUpdate);
            DB::commit();

            return response()->json([
                'status'  => true,
                'type'    => 'success',
                'message' => 'Data Berhasil di Edit'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'type'    => 'error',
                'message' => 'Gagal Edit Data: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'action' => 'required|in:delete,deactivate'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            if ($request->action === 'delete') {
                if (!Hash::check($request->password, auth()->user()->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password tidak valid'
                    ], 401);
                }

                DB::table('cs_branches')->where('id', $request->id)->delete();
                $message = 'Data berhasil dihapus permanen';
            } else {
                DB::table('cs_branches')
                    ->where('id', $request->id)
                    ->update(['is_active' => 0]);
                $message = 'Data berhasil dinonaktifkan';
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memproses data: ' . $e->getMessage()
            ], 500);
        }
    }
}