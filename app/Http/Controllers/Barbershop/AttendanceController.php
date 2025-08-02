<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('barbershop.master.attendances');
    }

    public function getAttendancesData(Request $request)
    {
        try {
            $date = $request->input('date', date('Y-m-d'));
            $barberId = $request->input('barber_id');
            $limit = $request->input('limit', 100);

            $query = DB::table('bs_attendances as a')
                ->select(
                    'a.id',
                    'a.barber_id',
                    'b.name as barber_name',
                    'a.attendance_date',
                    'a.customer_count',
                    'a.check_in',
                    'a.check_out',
                    'a.notes',
                    'a.created_at',
                    'a.updated_at'
                )
                ->leftJoin('bs_barbers as b', 'a.barber_id', '=', 'b.id')
                ->whereDate('a.attendance_date', $date)
                ->orderBy('a.created_at', 'desc');

            if ($barberId) {
                $query->where('a.barber_id', $barberId);
            }

            $attendances = $query->limit($limit)->get();
            $barbers = DB::table('bs_barbers')->where('is_active', 1)->get(['id', 'name']);

            return response()->json([
                'status' => true,
                'message' => 'Daftar absensi berhasil diambil',
                'data' => $attendances,
                'barbers' => $barbers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data absensi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barber_id' => 'required|exists:bs_barbers,id',
            'attendance_date' => 'required|date',
            'customer_count' => 'required|integer|min:0',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'notes' => 'nullable|string|max:500'
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
            $attendanceId = DB::table('bs_attendances')->updateOrInsert(
                [
                    'barber_id' => $request->barber_id,
                    'attendance_date' => $request->attendance_date
                ],
                [
                    'customer_count' => $request->customer_count,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'notes' => $request->notes,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // Untuk mendapatkan ID yang baru saja diinsert (khusus MySQL)
            if ($attendanceId === 0) {
                $attendanceId = DB::table('bs_attendances')
                    ->where('barber_id', $request->barber_id)
                    ->where('attendance_date', $request->attendance_date)
                    ->value('id');
            }

            $attendance = DB::table('bs_attendances as a')
                ->select(
                    'a.id',
                    'a.barber_id',
                    'b.name as barber_name',
                    'a.attendance_date',
                    'a.customer_count',
                    'a.check_in',
                    'a.check_out',
                    'a.notes'
                )
                ->leftJoin('bs_barbers as b', 'a.barber_id', '=', 'b.id')
                ->where('a.id', $attendanceId)
                ->first();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Absensi berhasil disimpan',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan absensi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:bs_attendances,id',
            'customer_count' => 'required|integer|min:0',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'notes' => 'nullable|string|max:500'
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
            DB::table('bs_attendances')
                ->where('id', $request->id)
                ->update([
                    'customer_count' => $request->customer_count,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'notes' => $request->notes,
                    'updated_at' => now()
                ]);

            $attendance = DB::table('bs_attendances as a')
                ->select(
                    'a.id',
                    'a.barber_id',
                    'b.name as barber_name',
                    'a.attendance_date',
                    'a.customer_count',
                    'a.check_in',
                    'a.check_out',
                    'a.notes'
                )
                ->leftJoin('bs_barbers as b', 'a.barber_id', '=', 'b.id')
                ->where('a.id', $request->id)
                ->first();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Absensi berhasil diperbarui',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui absensi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}