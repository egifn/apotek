<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index()
    {
        return view('barbershop.master.branches');
    }

    public function getBranchesData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $status = $request->input('status', 1);

            $query = DB::table('bs_branches')
                ->select('id', 'name', 'address', 'phone', 'description', 
                         'operational_hours', 'closed_days', 
                         'created_at', 'updated_at', 'is_active');

            if ($id) {
                $branches = $query->where('id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('address', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('is_active', (int)$status);
            }

            $branches = $query->limit($limit)->get();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar cabang berhasil diambil',
                'data'    => $branches,
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'address'           => 'required|string',
            'phone'             => 'required|string|max:20',
            'description'       => 'nullable|string',
            'operational_hours' => 'nullable|string|max:255',
            'closed_days'       => 'nullable|string|max:100',
            'open_time'         => 'required|date_format:H:i',
            'close_time'        => 'required|date_format:H:i|after:open_time',
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
            DB::table('bs_branches')->insert([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'description' => $request->description,
                'operational_hours' => $request->operational_hours,
                'closed_days' => $request->closed_days,
                'operational_hours' => $request->open_time && $request->close_time ? $request->open_time . ' - ' . $request->close_time : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();

            return response()->json([
                'status'  => true,
                'type'    => 'success',
                'message' => 'Berhasil Tambah Data Cabang'
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
            'id'                => 'required|integer|exists:bs_branches,id',
            'name'              => 'required|string|max:255',
            'address'           => 'required|string',
            'phone'             => 'required|string|max:20',
            'description'       => 'nullable|string',
            'operational_hours' => 'nullable|string|max:255',
            'closed_days'       => 'nullable|string|max:100',
            'open_time'         => 'required|date_format:H:i',
            'close_time'        => 'required|date_format:H:i|after:open_time',
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
                'description' => $request->description,
                'operational_hours' => $request->operational_hours,
                'closed_days' => $request->closed_days,
                'operational_hours' => $request->open_time && $request->close_time ? $request->open_time . ' - ' . $request->close_time : null,
                'updated_at' => now(),
            ];

            DB::table('bs_branches')->where('id', $request->id)->update($dataToUpdate);
            DB::commit();

            return response()->json([
                'status'  => true,
                'type'    => 'success',
                'message' => 'Data Cabang Berhasil di Edit'
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

                DB::table('bs_branches')->where('id', $request->id)->delete();
                $message = 'Data cabang berhasil dihapus permanen';
            } else {
                DB::table('bs_branches')
                    ->where('id', $request->id)
                    ->update(['is_active' => 0]);
                $message = 'Data cabang berhasil dinonaktifkan';
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