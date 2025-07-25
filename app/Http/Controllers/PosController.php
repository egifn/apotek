<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function getProducts(Request $request)
    {
        try {
            $search = $request->input('search');
            $limit = $request->input('limit', 10);

            $products = DB::table('cs_products')
                ->select('id', 'code', 'name', 'selling_price')
                ->where('is_active', 1)
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%$search%")
                                ->orWhere('code', 'like', "%$search%");
                })
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getServices(Request $request)
    {
        try {
            $search = $request->input('search');
            $limit = $request->input('limit', 10);

            $services = DB::table('bs_services')
                ->select('id', 'name', 'price', 'duration')
                ->where('is_active', 1)
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%$search%");
                })
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processTransaction(Request $request)
    {
        try {
            $items = $request->input('items');
            $total = $request->input('total');
            $payment = $request->input('payment');
            $change = $request->input('change');

            // Start transaction
            DB::beginTransaction();

            // Create transaction record
            $transactionId = DB::table('bs_transactions')->insertGetId([
                'total_amount' => $total,
                'payment_amount' => $payment,
                'change_amount' => $change,
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Add transaction items
            foreach ($items as $item) {
                if ($item['type'] === 'product') {
                    DB::table('bs_transaction_products')->insert([
                        'transaction_id' => $transactionId,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    DB::table('bs_transaction_services')->insert([
                        'transaction_id' => $transactionId,
                        'service_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction processed successfully',
                'transaction_id' => $transactionId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to process transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processBooking(Request $request)
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'service_id' => 'required|exists:bs_services,id',
                'booking_date' => 'required|date',
                'booking_time' => 'required'
            ]);

            DB::beginTransaction();

            // Get service duration
            $service = DB::table('bs_services')
                ->select('duration')
                ->where('id', $request->service_id)
                ->first();

            $bookingDateTime = $request->booking_date . ' ' . $request->booking_time;

            // Create booking record
            $bookingId = DB::table('bs_bookings')->insertGetId([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'service_id' => $request->service_id,
                'booking_date' => $bookingDateTime,
                'end_time' => date('Y-m-d H:i:s', strtotime($bookingDateTime) + ($service->duration * 60)),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully',
                'booking_id' => $bookingId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}