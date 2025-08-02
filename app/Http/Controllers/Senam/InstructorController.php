<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    public function index()
    {
        return view('senam.master.instructors');
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $status = $request->input('status', 1);
            $limit = $request->input('limit', 10);
            $id = $request->input('id');

            $query = DB::table('s_instructors');

            if ($id) {
                $query->where('id', $id);
                $instructor = $query->first();
                if (!$instructor) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data instruktur tidak ditemukan'
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data instruktur berhasil diambil',
                    'data' => [ 'instructor' => $instructor ]
                ]);
            }

            if ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('specialization', 'like', "%$search%");
            }

            if ($status !== null) {
                $query->where('is_active', $status);
            }

            $data = $query->orderBy('id')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data instruktur berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data instruktur',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:100'
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
            DB::table('s_instructors')->insert([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'bio' => $request->bio,
                'contact_phone' => $request->contact_phone,
                'contact_email' => $request->contact_email,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Instruktur berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan instruktur: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_instructors,id',
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:100',
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
            DB::table('s_instructors')->where('id', $request->id)->update([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'bio' => $request->bio,
                'contact_phone' => $request->contact_phone,
                'contact_email' => $request->contact_email,
                'is_active' => $request->is_active,
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Instruktur berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui instruktur: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_instructors,id',
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
            // Check if instructor is assigned to any class
            $assigned = DB::table('s_class_schedule')
                ->where('instructor_id', $request->id)
                ->exists();

            if ($assigned && $request->action === 'delete') {
                throw new \Exception('Tidak dapat menghapus instruktur yang sudah memiliki jadwal');
            }

         
            DB::table('s_instructors')
                ->where('id', $request->id)
                ->update(['is_active' => false]);
            $message = 'Instruktur berhasil dinonaktifkan';
        

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
}