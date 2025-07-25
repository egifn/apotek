<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        return view('barbershop.master.services');
    }

    public function getServicesData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $status = $request->input('status', 1);

            $query = DB::table('bs_services')
                ->select('id', 'name', 'description', 'duration', 'price', 'created_at', 'updated_at', 'is_active');

            if ($id) {
                $services = $query->where('id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('is_active', (int)$status);
            }

            $services = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar layanan berhasil diambil',
                'data'    => $services,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data layanan',
                'error'   => [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine()
                ]
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
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
            DB::table('bs_services')->insert([
                'name'        => $request->name,
                'description' => $request->description,
                'duration'    => $request->duration,
                'price'       => $request->price,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil Tambah Data Layanan'
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
            'id'          => 'required|integer|exists:bs_services,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
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
                'name'        => $request->name,
                'description' => $request->description,
                'duration'    => $request->duration,
                'price'       => $request->price,
                'updated_at'  => now(),
            ];

            DB::table('bs_services')->where('id', $request->id)->update($dataToUpdate);
            DB::commit();

            return response()->json([
                'status'  => true,
                'type'    => 'success',
                'message' => 'Data Layanan Berhasil di Edit'
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
            'id'     => 'required',
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

                DB::table('bs_services')->where('id', $request->id)->delete();
                $message = 'Data layanan berhasil dihapus permanen';
            } else {
                DB::table('bs_services')
                    ->where('id', $request->id)
                    ->update(['is_active' => 0]);
                $message = 'Data layanan berhasil dinonaktifkan';
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