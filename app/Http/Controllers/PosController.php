<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PosController extends Controller
{
     const VALID_BUSINESS_TYPES = ['coffee', 'barbershop', 'exercise', 'mixed'];
    const VALID_PAYMENT_METHODS = ['cash', 'debit', 'credit', 'e-wallet', 'qris'];

    public function index()
    {
        return view('pos.index');
    }

    // Di controller yang sama atau controller baru
    public function getCategories(Request $request)
    {
        try {
            $categories = DB::table('cs_categories')
                ->select('id', 'name', 'description')
                ->where('is_active', 1)
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProducts(Request $request)
    {
        try {
            $search = $request->input('search');
            $categoryId = $request->input('category_id');
            $limit = $request->input('limit', 50);

            $products = DB::table('cs_products')
                ->select('id', 'code', 'name', 'selling_price', 'image', 'category_id')
                ->where('is_active', 1)
                ->when($search, function($query) use ($search) {
                    return $query->where('name', 'like', "%$search%")
                                ->orWhere('code', 'like', "%$search%");
                })
                ->when($categoryId, function($query) use ($categoryId) {
                    return $query->where('category_id', $categoryId);
                })
                ->orderBy('name')
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


    private function generateInvoiceNumber($businessType)
    {        
        $prefix = 'TXN';
        $date = date('Ymd');
        $sequence = DB::table('all_transactions')
                     ->where('business_type', $businessType)
                     ->whereDate('created_at', today())
                     ->count() + 1;
        
        return sprintf("%s-%03d-%s", $prefix, $sequence, $date);
    }

    public function processTransaction(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'business_type' => 'required|in:'.implode(',', self::VALID_BUSINESS_TYPES),
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|string|in:product,service,class,quota_topup',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'sometimes|numeric|min:1',
            'items.*.subtotal' => 'required|numeric|min:0',
            'items.*.member_id' => 'nullable|numeric', // Tambahkan untuk kelas
            'payment_method' => 'required|string|in:'.implode(',', self::VALID_PAYMENT_METHODS),
            'payment_amount' => 'required|numeric|min:0',
            'customer_id' => 'nullable|numeric',
            'customer_name' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ], [
            'items.*.id.required' => 'Item ID is required.',
            'items.*.id.numeric' => 'The item ID must be a number for products and services.',
            'items.*.price.numeric' => 'The price must be a number.',
            'items.*.subtotal.numeric' => 'The subtotal must be a number.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $businessType = $request->input('business_type');
            $items = $request->input('items');
            $paymentMethod = $request->input('payment_method');
            $paymentAmount = (float)$request->input('payment_amount');
            $customerId = $request->input('customer_id');
            $customerName = $request->input('customer_name');
            $notes = $request->input('notes');

            // Calculate totals
            $subtotal = array_reduce($items, function($carry, $item) {
                return $carry + (float)$item['subtotal'];
            }, 0);
            
            $tax = 0;
            $discount = 0;
            $total = $subtotal + $tax - $discount;
            $changeAmount = $paymentAmount - $total;

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($businessType);

            // Insert transaction
            $transactionId = DB::table('all_transactions')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'business_type' => $businessType,
                'transaction_date' => now(),
                'customer_id' => $customerId,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'notes' => $notes,
                'created_at' => now()
            ]);

            // Process items
            foreach ($items as $item) {
                $this->processTransactionItem($transactionId, $item, $businessType);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction processed successfully',
                'data' => [
                    'invoice_number' => $invoiceNumber,
                    'transaction_id' => $transactionId,
                    'total' => $total,
                    'change_amount' => $changeAmount
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Transaction failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processTransactionItem($transactionId, $item, $businessType)
    {
        $metadata = [];
        // Handle different item types
        if ($item['price'] != 0) {
        
            $metadata = [
                'member_id' => $item['member_id'],
                'quota_added' => 4,
                'valid_until' => date('Y-m-d', strtotime('+1 month'))
            ];

            DB::table('s_member_quotas')
            ->where('member_id', $item['member_id'])
            ->update([
                'remaining_quota' => 4,
            ]);

            DB::table('s_quota_history')
            ->insert([
                'member_id' => $item['member_id'],
                'quota' => 4,
                'notes' => 'Pembelian Kelas Quota + 4',
                'created_at' => now()
            ]);
              
        } else {

            $metadata = [
                'member_id' => $item['member_id'] ?? null,
                'class_time' => now(),
                'instructor' => 'System' 
            ];
            
            // Jika member menggunakan kuota, kurangi kuota
            if (isset($item['member_id'])) {
                DB::table('s_member_quotas')
                    ->where('member_id', $item['member_id'])
                    ->where('is_active', true)
                    ->decrement('remaining_quota');

                DB::table('s_quota_history')->insert([
                    'member_id' => $item['member_id'],
                    'quota' => 1,
                    'notes' => 'Quota digunakan - 1',
                    'created_at' => now()
                ]);
            }
        }

        // Untuk item class, ID bisa string (UUID) atau numeric
        $itemId = $item['id'];
        if ($item['type'] === 'class' && !is_numeric($itemId)) {
            // Jika ID class bukan numeric, gunakan class_type_id atau buat ID khusus
            $itemId = isset($item['class_type_id']) ? $item['class_type_id'] : 0;
        }

        DB::table('all_transaction_items')->insert([
            'transaction_id' => $transactionId,
            'item_type' => $item['type'],
            'item_id' => $itemId,
            'name' => $item['name'],
            'quantity' => $item['quantity'] ?? 1,
            'price' => $item['price'],
            'subtotal' => $item['subtotal'],
            'metadata' => !empty($metadata) ? json_encode($metadata) : null,
            'created_at' => now()
        ]);
    }

    // private function processItem($transactionId, $item, $businessType)
    // {
    //     $metadata = [];
        
    //     // Handle special item types
    //     switch ($item['type']) {
    //         case 'quota_topup':
    //             $metadata = [
    //                 'member_id' => $item['member_id'],
    //                 'quota_added' => 4,
    //                 'valid_until' => date('Y-m-d', strtotime('+1 month'))
    //             ];
                
    //             // Update member quota
    //             DB::table('all_member_quotas')
    //                 ->where('member_id', $item['member_id'])
    //                 ->where('is_active', true)
    //                 ->update([
    //                     'remaining_quota' => DB::raw('remaining_quota + 4'),
    //                     'updated_at' => now()
    //                 ]);
    //             break;
                
    //         case 'class':
    //             $metadata = [
    //                 'class_time' => $item['class_time'] ?? null,
    //                 'instructor' => $item['instructor'] ?? null
    //             ];
    //             break;
    //     }

    //     // Insert transaction item
    //     DB::table('all_transaction_items')->insert([
    //         'transaction_id' => $transactionId,
    //         'item_type' => $item['type'],
    //         'item_id' => $item['id'],
    //         'name' => $item['name'],
    //         'quantity' => $item['quantity'] ?? 1,
    //         'price' => $item['price'],
    //         'subtotal' => $item['subtotal'],
    //         'metadata' => !empty($metadata) ? json_encode($metadata) : null,
    //         'created_at' => now()
    //     ]);
    // }

    public function getTransactionReport(Request $request)
    {
        try {
            $startDate = $request->input('start_date', date('Y-m-01'));
            $endDate = $request->input('end_date', date('Y-m-d'));
            $businessType = $request->input('business_type');

            $query = DB::table('all_transactions')
                ->select(
                    'invoice_number',
                    'business_type',
                    'transaction_date',
                    'customer_name',
                    'total',
                    'payment_method'
                )
                ->whereBetween('transaction_date', [$startDate, $endDate.' 23:59:59']);

            if ($businessType) {
                $query->where('business_type', $businessType);
            }

            $transactions = $query->orderBy('transaction_date', 'desc')
                                ->get();

            $summary = DB::table('all_transactions')
                ->select(
                    'business_type',
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(total) as total_revenue')
                )
                ->whereBetween('transaction_date', [$startDate, $endDate.' 23:59:59'])
                ->groupBy('business_type')
                ->get();

            return response()->json([
                'status' => true,
                'data' => [
                    'transactions' => $transactions,
                    'summary' => $summary
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate report',
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

     public function getExerciseClasses(Request $request)
    {
        try {
            $date = $request->input('date', date('Y-m-d'));
            // $search = $request->input('search');

            $query = DB::table('s_class_schedule as cs')
                ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
                ->leftjoin('s_instructors as i', 'cs.instructor_id', '=', 'i.id')
                ->join('s_locations as l', 'cs.location_id', '=', 'l.id')
                ->select(
                    'cs.id',
                    'cs.price',
                    'cs.class_type_id',
                    'cs.instructor_id',
                    'cs.location_id',
                    'cs.is_active',
                    'ct.name as class_name',
                    'i.name as instructor_name',
                    'l.name as location_name'
                )
                ->where('cs.is_active', true);

            $classes = $query->get();

            return response()->json([
                'status' => true,
                'data' => $classes,
                'current_date' => $date
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get exercise classes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchMembers(Request $request)
    {
        try {
            $query = $request->input('search', '');
            
            $members = DB::table('s_members')
                ->select('id', 'name', 'phone')
                ->where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->orderBy('name')
                ->limit(20)
                ->get();

            return response()->json([
                'status' => true,
                'data' => $members
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to search members',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkMember(Request $request)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'member_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $member = DB::table('s_members')
                ->where('id', $request->member_id)
                ->where('is_active', true)
                ->first();

            if (!$member) {
                return response()->json([
                    'status' => false,
                    'message' => 'Member not found or inactive'
                ], 404);
            }

            $quota = DB::table('s_member_quotas')
                ->where('member_id', $request->member_id)
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            return response()->json([
                'status' => true,
                'member' => $member,
                'quota' => $quota
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to check member',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerClass(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'class_schedule_id' => 'required|exists:s_class_schedule,id',
                'member_id' => 'nullable|exists:s_members,id',
                'non_member_name' => 'required_if:member_id,null|string|max:100',
                'non_member_phone' => 'required_if:member_id,null|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check class availability
            // $class = DB::table('s_class_schedule as cs')
            //     ->select(
            //         'cs.id',
            //         'cs.max_participants',
            //         DB::raw('(SELECT COUNT(*) FROM s_class_attendances WHERE class_schedule_id = cs.id) as participants_count')
            //     )
            //     ->where('cs.id', $request->class_schedule_id)
            //     ->first();

            // if ($class->participants_count >= $class->max_participants) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Class is full'
            //     ], 400);
            // }

            // Handle member registration
            if ($request->member_id) {
                $quota = DB::table('s_member_quotas')
                    ->where('member_id', $request->member_id)
                    ->where('is_active', true)
                    ->where('remaining_quota', '>', 0)
                    ->first();

                if (!$quota) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Member has no remaining quota'
                    ], 400);
                }

                // Reduce quota
                DB::table('s_member_quotas')
                    ->where('id', $quota->id)
                    ->decrement('remaining_quota');
            }

            // Create attendance record
            $attendanceId = DB::table('s_class_attendances')->insertGetId([
                'class_schedule_id' => $request->class_schedule_id,
                'member_id' => $request->member_id,
                'non_member_name' => $request->non_member_name,
                'non_member_phone' => $request->non_member_phone,
                'attendance_time' => now(),
                'status' => 'registered',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'attendance_id' => $attendanceId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to register for class',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Add this method to handle topup as a POS item
    public function topupQuota(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'member_id' => 'required|exists:s_members,id',
                'add_to_pos' => 'sometimes|boolean' // New flag to indicate if this should be added to POS
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Deactivate current quota
            DB::table('s_member_quotas')
                ->where('member_id', $request->member_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Create new quota
            $newQuotaId = DB::table('s_member_quotas')->insertGetId([
                'member_id' => $request->member_id,
                'total_quota' => 4,
                'remaining_quota' => 4,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $response = [
                'status' => true,
                'message' => 'Quota topped up successfully',
                'quota_id' => $newQuotaId
            ];

            // If this is a POS transaction, return item details
            if ($request->add_to_pos) {
                $member = DB::table('s_members')->find($request->member_id);
                $response['pos_item'] = [
                    'id' => 'quota_' . $newQuotaId,
                    'name' => 'Exercise Quota Topup - ' . $member->name,
                    'price' => 200000, // Fixed price for quota topup
                    'type' => 'quota_topup',
                    'member_id' => $member->id
                ];
            }

            DB::commit();
            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to top up quota',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}