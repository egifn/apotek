<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        return view('senam.master.pegawai');
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $limit = $request->input('limit', 10);

            $query = DB::table('m_pegawai')
                    ->where('unit_kerja', 'Senam');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name_pegawai', 'like', "%$search%")
                      ->orWhere('kode_pegawai', 'like', "%$search%");
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
        // validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
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
            // generate sequence number based on how many employees in Coffeshop
            $count = DB::table('m_pegawai')
                ->where('unit_kerja', 'Senam')
                ->count();

            $no_urut = $count + 1;
            $no_urut_padded = str_pad($no_urut, 4, '0', STR_PAD_LEFT); // e.g. 0001

            // build kode: CF-{JABATAN}-{0001}
            $jabatan_code = strtoupper(preg_replace('/\s+/', '', $request->jabatan));
            $kode_pegawai = 'SN-' . $jabatan_code . '-' . $no_urut_padded;

            DB::table('m_pegawai')->insert([
                'kode_pegawai' => $kode_pegawai,
                'nik_pegawai' => $request->nik,
                'nama_pegawai' => $request->name,
                'jk' => $request->jk,
                'alamat' => $request->address,
                'tlp' => $request->phone,
                'email' => $request->email,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'status_pegawai' => 'Aktif',
                'id_user_input' => Auth::user()->id,
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