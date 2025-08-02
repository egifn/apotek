<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    public function index()
    {
        return view('senam.master.equipment');
    }

    public function getData(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search');
            $status = $request->input('status', 1);
            $limit = $request->input('limit', 10);

            $query = DB::table('s_equipment');

            if ($id) {
                $query->where('id', $id);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            }

             if ($status !== null) {
                $query->where('is_active', $status);
            }

            if ($id) {
                $data = $query->get();
            } else {
                $data = $query->orderBy('id')->paginate($limit);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data alat berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data alat',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:1',
            'maintenance_schedule' => 'nullable|date'
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
            DB::table('s_equipment')->insert([
                'name' => $request->name,
                'description' => $request->description,
                'total_quantity' => $request->total_quantity,
                'available_quantity' => $request->total_quantity,
                'maintenance_schedule' => $request->maintenance_schedule,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Alat berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan alat: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_equipment,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'total_quantity' => 'required|integer|min:0',
            'maintenance_schedule' => 'nullable|date'
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
            // Calculate new available quantity
            $currentEquipment = DB::table('s_equipment')->find($request->id);
            $usedQuantity = $currentEquipment->total_quantity - $currentEquipment->available_quantity;
            $newAvailableQuantity = max(0, $request->total_quantity - $usedQuantity);

            DB::table('s_equipment')->where('id', $request->id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'total_quantity' => $request->total_quantity,
                'available_quantity' => $newAvailableQuantity,
                'maintenance_schedule' => $request->maintenance_schedule,
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Alat berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui alat: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_equipment,id'
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
            // Check if equipment is used in any class
            $usedInClass = DB::table('s_class_requirements')
                ->where('equipment_id', $request->id)
                ->exists();

            if ($usedInClass) {
                throw new \Exception('Tidak dapat menghapus alat yang sudah digunakan dalam kelas');
            }

          
            DB::table('s_equipment')
            ->where('id', $request->id)
            ->update(['is_active' => false]);
            $message = 'Instruktur berhasil dinonaktifkan';

            return response()->json([
                'status' => true,
                'message' =>  $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus alat: ' . $e->getMessage()
            ]);
        }
    }
}