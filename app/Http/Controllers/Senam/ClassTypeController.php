<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ClassTypeController extends Controller
{
    public function index()
    {
        $equipment = DB::table('s_equipment')->get();
        return view('senam.master.class-types', compact('equipment'));
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $status = $request->input('status', 1);
            $limit = $request->input('limit', 10);
            $id = $request->input('id');
            $classTypeId = $request->input('class_type_id');

            $query = DB::table('s_class_types');

            if ($id) {
                $query->where('id', $id);
                $classType = $query->first();

                if (!$classType) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data jenis senam tidak ditemukan'
                    ]);
                }

                // Get equipment requirements
                $equipmentRequirements = DB::table('s_class_requirements')
                    ->where('class_type_id', $id)
                    ->pluck('equipment_id')
                    ->toArray();

                return response()->json([
                    'status' => true,
                    'message' => 'Data jenis senam berhasil diambil',
                    'data' => [
                        'class_type' => $classType,
                        'equipment_requirements' => $equipmentRequirements
                    ]
                ]);
            }

            if ($classTypeId) {
                $equipmentRequirements = DB::table('s_class_requirements')
                    ->where('class_type_id', $classTypeId)
                    ->pluck('equipment_id')
                    ->toArray();

                return response()->json([
                    'status' => true,
                    'message' => 'Data peralatan berhasil diambil',
                    'data' => $equipmentRequirements
                ]);
            }

            if ($search) {
                $query->where('name', 'like', "%$search%");
            }

            if ($status !== null) {
                $query->where('is_active', $status);
            }

            $data = $query->orderBy('id', 'asc')
                         ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Data jenis senam berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data jenis senam',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:s_class_types,name',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'required_equipment' => 'nullable|string',
            'equipment_ids' => 'nullable|array',
            'equipment_ids.*' => 'exists:s_equipment,id'
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
            $classTypeId = DB::table('s_class_types')->insertGetId([
                'name' => $request->name,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes,
                'required_equipment' => $request->required_equipment,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Insert equipment requirements
            if ($request->has('equipment_ids')) {
                $equipmentRequirements = [];
                foreach ($request->equipment_ids as $equipmentId) {
                    $equipmentRequirements[] = [
                        'class_type_id' => $classTypeId,
                        'equipment_id' => $equipmentId,
                        'created_at' => now()
                    ];
                }
                DB::table('s_class_requirements')->insert($equipmentRequirements);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jenis senam berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan jenis senam: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_class_types,id',
            'name' => 'required|string|max:100|unique:s_class_types,name,'.$request->id,
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:15|max:180',
            'required_equipment' => 'nullable|string',
            'equipment_ids' => 'nullable|array',
            'equipment_ids.*' => 'exists:s_equipment,id',
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
            // Get equipment names for required_equipment field
            $requiredEquipmentString = $request->required_equipment;
            if ($request->filled('equipment_ids') && is_array($request->equipment_ids) && count($request->equipment_ids) > 0) {
                $equipmentNames = DB::table('s_equipment')
                    ->whereIn('id', $request->equipment_ids)
                    ->pluck('name')
                    ->toArray();
                $requiredEquipmentString = implode(', ', $equipmentNames);
            } else if (empty($request->required_equipment)) {
                $requiredEquipmentString = null;
            }

            DB::table('s_class_types')->where('id', $request->id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes,
                'required_equipment' => $requiredEquipmentString,
                'is_active' => $request->is_active,
                'updated_at' => now()
            ]);

            // Update equipment requirements
            DB::table('s_class_requirements')->where('class_type_id', $request->id)->delete();
            if ($request->filled('equipment_ids') && is_array($request->equipment_ids) && count($request->equipment_ids) > 0) {
                $equipmentRequirements = [];
                foreach ($request->equipment_ids as $equipmentId) {
                    $equipmentRequirements[] = [
                        'class_type_id' => $request->id,
                        'equipment_id' => $equipmentId,
                        'created_at' => now()
                    ];
                }
                DB::table('s_class_requirements')->insert($equipmentRequirements);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jenis senam berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui jenis senam: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_class_types,id',
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
            // Check if class type is used in any schedule
            $usedInSchedule = DB::table('s_class_schedule')
                ->where('class_type_id', $request->id)
                ->exists();

            if ($usedInSchedule && $request->action === 'delete') {
                throw new \Exception('Tidak dapat menghapus jenis senam yang sudah digunakan dalam jadwal');
            }

            if ($request->action === 'delete') {
                DB::table('s_class_types')->where('id', $request->id)->delete();
                DB::table('s_class_requirements')->where('class_type_id', $request->id)->delete();
                $message = 'Jenis senam berhasil dihapus';
            } else {
                DB::table('s_class_types')
                    ->where('id', $request->id)
                    ->update(['is_active' => false]);
                $message = 'Jenis senam berhasil dinonaktifkan';
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
            ]);
        }
    }
}