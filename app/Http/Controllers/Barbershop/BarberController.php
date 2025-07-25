<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class BarberController extends Controller
{
    public function index()
    {
        // $barbers = DB::table('bs_barbers')->get();
        return view('barbershop.master.barbers');
    }

    public function getData(Request $request)
    {
        try {
            $id     = $request->input('id');
            $search = $request->input('search');
            $limit  = $request->input('limit', 100);
            $status = $request->input('status', 1);

            // Query dasar
            $query = DB::table('bs_barbers')
                ->select('id', 'name', 'photo', 'created_at', 'updated_at', 'description', 'is_active');
            // ->where('is_active', )

            // Jika by ID
            if ($id) {
                $barbers = $query->where('id', $id)->first();
            } else if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            } else if ($status !== null) {
                $query->where('is_active', (int)$status);
            }

            $barbers = $query->limit($limit)->get();

            return response()->json([
                'status'          => true,
                'message'         => 'Daftar cabang berhasil diambil',
                'data'            => $barbers,
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
            'name'              => 'nullable|string',
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

            DB::table('bs_barbers')->insert([
                'name' => $request->name,
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
            'id'                => 'required|integer|exists:bs_barbers,id',
            'name'              => 'nullable|string',
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
                'phone' => $request->phone,
                'updated_at' => now(),
            ];

            DB::table('bs_barbers')->where('id', $request->id)->update($dataToUpdate);
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
        // dd($request->all());
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
                // Verifikasi password (sesuaikan dengan sistem Anda)
                if (!Hash::check($request->password, auth()->user()->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password tidak valid'
                    ], 401);
                }

                // Hapus permanen
                DB::table('bs_barbers')->where('id', $request->id)->delete();
                $message = 'Data berhasil dihapus permanen';
            } else {
                // Nonaktifkan data
                DB::table('bs_barbers')
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