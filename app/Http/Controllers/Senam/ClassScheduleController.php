<?php

namespace App\Http\Controllers\Senam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DateTime;

class ClassScheduleController extends Controller
{
    public function index()
    {
        $classTypes = DB::table('s_class_types')->where('is_active', true)->get();
        $instructors = DB::table('s_instructors')->where('is_active', true)->get();
        $locations = DB::table('s_locations')->where('is_active', true)->get();

        return view('senam.master.class-schedule', compact('classTypes', 'instructors', 'locations'));
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->input('search');
            $status = $request->input('status', 1);
            $classTypeId = $request->input('class_type_id');
            $limit = $request->input('limit', 10);
            $id = $request->input('id');

            $query = DB::table('s_class_schedule as cs')
                ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
                ->join('s_instructors as i', 'cs.instructor_id', '=', 'i.id')
                ->join('s_locations as l', 'cs.location_id', '=', 'l.id')
                ->select(
                    'cs.id',
                    'cs.services_name',
                    'cs.type_services',
                    'cs.class_type_id',
                    'cs.instructor_id',
                    'cs.location_id',
                    'cs.is_active',
                    'cs.price',
                    'ct.name as class_name',
                    'i.name as instructor_name',
                    'l.name as location_name'
                )
                ->orderBy('cs.id', 'asc');

            if ($id) {
                $query->where('cs.id', $id);
                $schedule = $query->first();
                
                if (!$schedule) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data jadwal tidak ditemukan'
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data jadwal berhasil diambil',
                    'data' => $schedule
                ]);
            }

            if ($status !== null) {
                $query->where('cs.is_active', $status);
            }

            if ($classTypeId) {
                $query->where('cs.class_type_id', $classTypeId);
            }

            $data = $query->orderBy('cs.id', 'desc')
                        ->paginate($limit);


            return response()->json([
                'status' => true,
                'message' => 'Data jadwal berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data jadwal',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_type_id' => 'required|exists:s_class_types,id',
            'instructor_id' => 'required|exists:s_instructors,id',
            'location_id' => 'required|exists:s_locations,id',
            'services_name' => 'required|string|max:255',
            'type_services' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
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
            $data = [
                'class_type_id' => $request->class_type_id,
                'instructor_id' => $request->instructor_id,
                'location_id' => $request->location_id,
                'services_name' => $request->services_name,
                'type_services' => $request->type_services,
                'price' => $request->price,
                'is_active' => true
            ];

            DB::table('s_class_schedule')->insert($data);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jadwal berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan jadwal: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_class_schedule,id',
            'class_type_id' => 'required|exists:s_class_types,id',
            'instructor_id' => 'required|exists:s_instructors,id',
            'location_id' => 'required|exists:s_locations,id',
            'services_name' => 'required|string|max:255',
            'type_services' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
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
            $data = [
                'class_type_id' => $request->class_type_id,
                'instructor_id' => $request->instructor_id,
                'location_id' => $request->location_id,
                'services_name' => $request->services_name,
                'type_services' => $request->type_services,
                'price' => $request->price,
                'is_active' => $request->is_active
            ];

            DB::table('s_class_schedule')->where('id', $request->id)->update($data);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jadwal berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:s_class_schedule,id',
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
            $schedule = DB::table('s_class_schedule')->find($request->id);

            // Check if there are bookings
            $bookings = DB::table('s_class_bookings')
                ->where('class_schedule_id', $request->id)
                ->count();

            if ($bookings > 0 && $request->action === 'delete') {
                throw new \Exception('Tidak dapat menghapus jadwal yang sudah memiliki booking');
            }

            if ($request->action === 'delete') {
                DB::table('s_class_schedule')->where('id', $request->id)->delete();
                $message = 'Jadwal berhasil dihapus';
            } else {
                DB::table('s_class_schedule')
                    ->where('id', $request->id)
                    ->update(['is_active' => false]);
                $message = 'Jadwal berhasil dinonaktifkan';
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