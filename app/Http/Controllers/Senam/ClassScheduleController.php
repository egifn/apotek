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
            $recurrenceType = $request->input('recurrence_type');
            $classTypeId = $request->input('class_type_id');
            $limit = $request->input('limit', 10);
            $id = $request->input('id');

            $query = DB::table('s_class_schedule as cs')
                ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
                ->join('s_instructors as i', 'cs.instructor_id', '=', 'i.id')
                ->join('s_locations as l', 'cs.location_id', '=', 'l.id')
                ->select(
                    'cs.id',
                    'cs.class_type_id',
                    'cs.instructor_id',
                    'cs.location_id',
                    'cs.start_datetime',
                    'cs.end_datetime',
                    'cs.max_participants',
                    'cs.is_active',
                    'cs.recurrence_type',
                    'cs.recurrence_value',
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

            if ($recurrenceType) {
                $query->where('recurrence_type', $recurrenceType);
            }

            if ($classTypeId) {
                $query->where('cs.class_type_id', $classTypeId);
            }

            $data = $query->orderBy('cs.start_datetime', 'desc')
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
            'start_datetime' => 'required',
            'end_datetime' => 'required',
            'max_participants' => 'required|integer|min:1',
            'recurrence_type' => 'required|in:one-time,weekly,monthly',
            'recurrence_value' => 'nullable',
            'end_recurrence_date' => 'nullable|date|after:start_datetime'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        // Convert time-only input to full datetime if needed
        $start = $request->start_datetime;
        $end = $request->end_datetime;
        $today = date('Y-m-d');
        // If only time (HH:mm or HH:mm:ss), prepend today
        if (preg_match('/^\d{2}:\d{2}$/', $start)) {
            $start = $today . ' ' . $start . ':00';
        } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $start)) {
            $start = $today . ' ' . $start;
        }
        if (preg_match('/^\d{2}:\d{2}$/', $end)) {
            $end = $today . ' ' . $end . ':00';
        } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $end)) {
            $end = $today . ' ' . $end;
        }

        // Pastikan end > start
        if (strtotime($end) <= strtotime($start)) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Waktu selesai harus setelah waktu mulai',
                'errors' => ['end_datetime' => ['Waktu selesai harus setelah waktu mulai']]
            ]);
        }

        DB::beginTransaction();
        try {
            // Check instructor and location availability based on recurrence type
            $recType = $request->recurrence_type;
            $recValue = $request->recurrence_value;
            $date = DateTime::createFromFormat('Y-m-d', $recValue);
            if ($date) {
                $day = $date->format('l');
                $date = $date->format('d');
            } else {
                echo "Format tanggal tidak valid.";
            }
            $conflict = false;
            $locationConflict = false;
            if ($recType === 'one-time' && $recValue) {
                // For one-time, check only for the specific date in recurrence_value
                $date = $recValue;
                $startDateTime = $date . ' ' . date('H:i:s', strtotime($start));
                $endDateTime = $date . ' ' . date('H:i:s', strtotime($end));
                $conflict = DB::table('s_class_schedule')
                    ->where('instructor_id', $request->instructor_id)
                    ->where(function($query) use ($recValue, $day, $date) {
                        $query->whereBetween('recurrence_value', [$recValue, $day, $date]);
                    })
                    ->exists();
                $locationConflict = DB::table('s_class_schedule')
                    ->where('location_id', $request->location_id)
                   ->where(function($query) use ($recValue, $day, $date) {
                        $query->whereBetween('recurrence_value', [$recValue, $day, $date]);
                    })
                    ->exists();
            } else {
                // For recurring, use original logic
                $conflict = DB::table('s_class_schedule')
                    ->where('instructor_id', $request->instructor_id)
                    ->where(function($query) use ($start, $end) {
                        $query->whereBetween('start_datetime', [$start, $end])
                              ->orWhereBetween('end_datetime', [$start, $end]);
                    })
                    ->exists();
                $locationConflict = DB::table('s_class_schedule')
                    ->where('location_id', $request->location_id)
                    ->where(function($query) use ($start, $end) {
                        $query->whereBetween('start_datetime', [$start, $end])
                              ->orWhereBetween('end_datetime', [$start, $end]);
                    })
                    ->exists();
            }

            if ($conflict) {
                throw new \Exception('Instruktur sudah memiliki jadwal pada waktu tersebut');
            }
            if ($locationConflict) {
                throw new \Exception('Lokasi sudah digunakan pada waktu tersebut');
            }

            $data = $request->only([
                'class_type_id', 'instructor_id', 'location_id', 
                'max_participants', 'recurrence_type', 'recurrence_value', 'end_recurrence_date'
            ]);
            $data['start_datetime'] = $start;
            $data['end_datetime'] = $end;
            $data['is_active'] = true;

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
            'start_datetime' => 'required',
            'end_datetime' => 'required',
            'max_participants' => 'required|integer|min:1',
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
            $schedule = DB::table('s_class_schedule')->find($request->id);

            // Check if class has past
            if (now() > $schedule->start_datetime) {
                throw new \Exception('Tidak dapat mengubah jadwal yang sudah lewat');
            }

            // Check instructor availability
            $conflict = DB::table('s_class_schedule')
                ->where('instructor_id', $request->instructor_id)
                ->where('id', '!=', $request->id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_datetime', [$request->start_datetime, $request->end_datetime])
                          ->orWhereBetween('end_datetime', [$request->start_datetime, $request->end_datetime]);
                })
                ->exists();

            if ($conflict) {
                throw new \Exception('Instruktur sudah memiliki jadwal pada waktu tersebut');
            }

            // Check location availability
            $locationConflict = DB::table('s_class_schedule')
                ->where('location_id', $request->location_id)
                ->where('id', '!=', $request->id)
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_datetime', [$request->start_datetime, $request->end_datetime])
                          ->orWhereBetween('end_datetime', [$request->start_datetime, $request->end_datetime]);
                })
                ->exists();

            if ($locationConflict) {
                throw new \Exception('Lokasi sudah digunakan pada waktu tersebut');
            }

            $data = $request->only([
                'class_type_id', 'instructor_id', 'location_id', 
                'start_datetime', 'end_datetime', 'max_participants', 'is_active'
            ]);

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