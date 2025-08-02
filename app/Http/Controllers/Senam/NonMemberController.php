<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NonMemberController extends Controller
{
    public function index()
    {
        return view('senam.master.non-members');
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $limit = $request->input('limit', 10);

            $query = DB::table('s_non_members');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
            }

            $data = $query->orderBy('created_at', 'desc')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data non-member berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data non-member',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::table('s_non_members')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'created_at' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Non-member berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan non-member: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_non_members,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Check if non-member has bookings
            $hasBookings = DB::table('s_class_bookings')
                ->where('non_member_id', $request->id)
                ->exists();

            if ($hasBookings) {
                throw new \Exception('Tidak dapat menghapus non-member yang sudah memiliki booking');
            }

            DB::table('s_non_members')->where('id', $request->id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Non-member berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus non-member: ' . $e->getMessage()
            ]);
        }
    }
}