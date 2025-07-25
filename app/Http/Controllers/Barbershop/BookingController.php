<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $barbers = DB::table('bs_barbers')->where('is_active', 1)->get();
        $services = DB::table('bs_services')->where('is_active', 1)->get();
        
        return view('barbershop.master.bookings', compact('barbers', 'services'));
    }

    public function getBookingsData(Request $request)
    {
        try {
            $id = $request->input('id');
            $barberId = $request->input('barber_id');
            $status = $request->input('status');
            $date = $request->input('date');
            $limit = $request->input('limit', 100);

            $query = DB::table('bs_bookings')
                ->join('bs_barbers', 'bs_bookings.barber_id', '=', 'bs_barbers.id')
                ->join('bs_services', 'bs_bookings.service_id', '=', 'bs_services.id')
                ->select(
                    'bs_bookings.*',
                    'bs_barbers.name as barber_name',
                    'bs_services.name as service_name',
                    'bs_services.duration as service_duration',
                    'bs_services.price as service_price'
                );

            if ($id) {
                $bookings = $query->where('bs_bookings.id', $id)->first();
            } else {
                if ($barberId) {
                    $query->where('bs_bookings.barber_id', $barberId);
                }
                
                if ($status) {
                    $query->where('bs_bookings.status', $status);
                }
                
                if ($date) {
                    $query->whereDate('bs_bookings.booking_date', $date);
                }
            }

            $bookings = $query->orderBy('booking_date', 'desc')
                             ->orderBy('start_time', 'asc')
                             ->limit($limit)
                             ->get();

            return response()->json([
                'status' => true,
                'message' => 'Daftar booking berhasil diambil',
                'data' => $bookings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data booking',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barber_id' => 'required|exists:bs_barbers,id',
            'service_id' => 'required|exists:bs_services,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
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
            // Get service duration
            $service = DB::table('bs_services')
                        ->where('id', $request->service_id)
                        ->first();

            if (!$service) {
                throw new \Exception('Service not found');
            }

            $startTime = Carbon::createFromFormat('H:i', $request->start_time);
            $endTime = $startTime->copy()->addMinutes($service->duration);

            // Check barber availability
            $isAvailable = $this->checkBarberAvailability(
                $request->barber_id,
                $request->booking_date,
                $request->start_time,
                $endTime->format('H:i')
            );

            if (!$isAvailable) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'Barber tidak tersedia pada waktu yang dipilih'
                ]);
            }

            DB::table('bs_bookings')->insert([
                'barber_id' => $request->barber_id,
                'service_id' => $request->service_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'start_time' => $request->start_time,
                'end_time' => $endTime->format('H:i'),
                'status' => 'pending',
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Booking berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => 'Gagal membuat booking: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:bs_bookings,id',
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::table('bs_bookings')
                ->where('id', $request->id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Status booking berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => 'Gagal mengupdate status booking: ' . $e->getMessage()
            ]);
        }
    }

    private function checkBarberAvailability($barberId, $date, $startTime, $endTime)
    {
        // Check if barber has schedule for this day
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        
        $hasSchedule = DB::table('bs_schedules')
            ->where('barber_id', $barberId)
            ->where('day', $dayOfWeek)
            ->whereTime('start_time', '<=', $startTime)
            ->whereTime('end_time', '>=', $endTime)
            ->exists();

        if (!$hasSchedule) {
            return false;
        }

        // Check for existing bookings
        $hasConflict = DB::table('bs_bookings')
            ->where('barber_id', $barberId)
            ->whereDate('booking_date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        return !$hasConflict;
    }
}