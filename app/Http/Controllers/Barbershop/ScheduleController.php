<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
   public function index()
    {
        $barbers = DB::table('bs_barbers')
                    ->where('is_active', 1)
                    ->select('id', 'name')
                    ->get();
                    
        return view('barbershop.master.schedules', compact('barbers'));
    }

    public function getSchedulesData(Request $request)
    {
        try {
            $id = $request->input('id');
            $barberId = $request->input('barber_id');
            $day = $request->input('day');
            $limit = $request->input('limit', 100);

            $query = DB::table('bs_schedules')
                ->join('bs_barbers', 'bs_schedules.barber_id', '=', 'bs_barbers.id')
                ->select(
                    'bs_schedules.id',
                    'bs_schedules.barber_id',
                    'bs_barbers.name as barber_name',
                    'bs_schedules.day',
                    'bs_schedules.start_time',
                    'bs_schedules.end_time',
                    'bs_schedules.created_at',
                    'bs_schedules.updated_at'
                );

            if ($id) {
                $schedules = $query->where('bs_schedules.id', $id)->first();
            } else if ($barberId) {
                $query->where('bs_schedules.barber_id', $barberId);
            }

            if ($day) {
                $query->where('bs_schedules.day', $day);
            }

            $schedules = $query->limit($limit)->get();

            return response()->json([
                'status' => true,
                'message' => 'Daftar jadwal berhasil diambil',
                'data' => $schedules,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data jadwal',
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barber_id' => 'required|integer|exists:bs_barbers,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            // Check for overlapping schedules
            $overlap = DB::table('bs_schedules')
                ->where('barber_id', $request->barber_id)
                ->where('day', $request->day)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->exists();

            if ($overlap) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'Jadwal bertabrakan dengan jadwal yang sudah ada'
                ]);
            }

            DB::table('bs_schedules')->insert([
                'barber_id' => $request->barber_id,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil Tambah Jadwal'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => 'Gagal Tambah Jadwal: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:bs_schedules,id',
            'barber_id' => 'required|integer|exists:bs_barbers,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            // Check for overlapping schedules (excluding current schedule)
            $overlap = DB::table('bs_schedules')
                ->where('barber_id', $request->barber_id)
                ->where('day', $request->day)
                ->where('id', '!=', $request->id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->exists();

            if ($overlap) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'Jadwal bertabrakan dengan jadwal yang sudah ada'
                ]);
            }

            $dataToUpdate = [
                'barber_id' => $request->barber_id,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'updated_at' => now(),
            ];

            DB::table('bs_schedules')->where('id', $request->id)->update($dataToUpdate);
            DB::commit();

            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Jadwal Berhasil di Edit'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => 'Gagal Edit Jadwal: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:bs_schedules,id',
            'password' => 'required_if:action,delete|string'
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
            if (!Hash::check($request->password, auth()->user()->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password tidak valid'
                ], 401);
            }

            DB::table('bs_schedules')->where('id', $request->id)->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jadwal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}