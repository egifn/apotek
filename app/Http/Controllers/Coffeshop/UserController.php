<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
          $data_level = DB::table('users_type')
            ->orderBy('id', 'ASC')
            ->get();
            
        return view('coffeshop.master.user', compact('data_level'));
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $limit = $request->input('limit', 10);

            $query = DB::table('users')
                    ->join('users_type', 'users.type', '=', 'users_type.id')
                    ->select('users.*', 'users_type.nama as nama_type')
                    ->orderBy('users.created_at', 'ASC');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('username', 'like', "%$search%");
                });
            }

            $data = $query->orderBy('created_at', 'desc')
                         ->paginate($limit);

            // dd($data);

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
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:6',
                'lokasi' => 'required|string|max:255',
                'level' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'type' => 'validation',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek apakah username sudah ada
            $existingUser = DB::table('users')->where('username', $request->username)->first();
            if ($existingUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Username sudah digunakan'
                ], 400);
            }

            // Cek apakah email sudah ada
            $existingEmail = DB::table('users')->where('email', $request->email)->first();
            if ($existingEmail) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email sudah digunakan'
                ], 400);
            }

            // Insert data ke tabel users
            DB::table('users')->insert([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'email_verified_at' => null,
                'password' => Hash::make($request->password),
                'remember_token' => null,
                'kd_lokasi' => $request->lokasi,
                'type' => $request->level,
                'status_user' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan user',
                'error' => $e->getMessage()
            ], 500);
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