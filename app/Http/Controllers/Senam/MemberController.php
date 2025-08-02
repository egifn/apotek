<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index()
    {
        return view('senam.master.members');
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $status = $request->input('status', 1);
            $limit = $request->input('limit', 10);
            $id = $request->input('id');

            $query = DB::table('s_members');

            if ($id) {
                $query->where('id', $id);
                $member = $query->first();
                if (!$member) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data member tidak ditemukan'
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data member berhasil diambil',
                    'data' => [ 'member' => $member ]
                ]);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
            }

            if ($status !== null) {
                $query->where('is_active', $status);
            }

            $data = $query->orderBy('id')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data member berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data member',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:s_members,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'membership_type' => 'required|in:regular,premium,vip',
            'total_quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            $memberId = DB::table('s_members')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'join_date' => $request->join_date,
                'membership_type' => $request->membership_type,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Add initial quota
            DB::table('s_member_quotas')->insert([
                'member_id' => $memberId,
                'total_quota' => $request->total_quota,
                'remaining_quota' => $request->total_quota,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Member berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan member: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_members,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:s_members,email,'.$request->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'membership_type' => 'required|in:regular,premium,vip',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('s_members')->where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'membership_type' => $request->membership_type,
                'is_active' => $request->is_active,
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Member berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui member: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_members,id',
            'action' => 'required|in:delete,deactivate'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            // Check if member has bookings
            $hasBookings = DB::table('s_class_bookings')
                ->where('member_id', $request->id)
                ->exists();

            if ($hasBookings && $request->action === 'delete') {
                throw new \Exception('Tidak dapat menghapus member yang sudah memiliki booking');
            }

            
                DB::table('s_members')
                ->where('id', $request->id)
                ->update(['is_active' => false]);
                // DB::table('s_member_quotas')->where('member_id', $request->id)->delete();
                // DB::table('s_members')->where('id', $request->id)->delete();
           
                // $message = 'Member berhasil dihapus';
                $message = 'Member berhasil dinonaktifkan';
            

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
            ]);
        }
    }

    public function addQuota(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:s_members,id',
            'additional_quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('s_member_quotas')->insert([
                'member_id' => $request->member_id,
                'total_quota' => $request->additional_quota,
                'remaining_quota' => $request->additional_quota,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kuota berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan kuota: ' . $e->getMessage()
            ]);
        }
    }

    public function getQuotaHistory(Request $request)
    {
        try {
            $memberId = $request->input('member_id');
            $limit = $request->input('limit', 10);

            $query = DB::table('s_member_quotas')
                ->where('member_id', $memberId)
                ->orderBy('start_date', 'desc');

            $data = $query->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'History kuota berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil history kuota',
                'error' => $e->getMessage()
            ]);
        }
    }
}