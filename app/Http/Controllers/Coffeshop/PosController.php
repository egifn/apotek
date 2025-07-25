<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $outletId = session('outlet_id', 1); // Default outlet atau dari session

        // Get Categories
        $categories = DB::table('cs_categories')
            ->where('is_active', 1)
            ->orderBy('category_name')
            ->get();

        // Get Products with Variants
        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $productsQuery = DB::table('cs_products as p')
            ->join('cs_categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('cs_product_variants as pv', 'p.id', '=', 'pv.product_id')
            ->select(
                'p.id as product_id',
                'p.product_name',
                'p.product_code',
                'p.image',
                'p.product_type',
                'c.category_name',
                'pv.id as variant_id',
                'pv.variant_name',
                'pv.final_price'
            )
            ->where('p.is_active', 1)
            ->where('pv.is_active', 1);

        if ($categoryId) {
            $productsQuery->where('p.category_id', $categoryId);
        }

        if ($search) {
            $productsQuery->where('p.product_name', 'like', "%{$search}%");
        }

        $products = $productsQuery->orderBy('p.product_name')->get();

        // Get Active Discounts
        $discounts = DB::table('cs_discounts')
            ->where('is_active', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now()->format('Y-m-d'));
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->format('Y-m-d'));
            })
            ->get();

        // Get Payment Methods
        $paymentMethods = DB::table('cs_payment_methods')
            ->where('is_active', 1)
            ->orderBy('method_name')
            ->get();

        return view('coffeshop.pos.index', compact('categories', 'products', 'discounts', 'paymentMethods'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:cs_product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variantId = $request->variant_id;
        $quantity = $request->quantity;

        // Get product variant details
        $variant = DB::table('cs_product_variants as pv')
            ->join('cs_products as p', 'pv.product_id', '=', 'p.id')
            ->select(
                'pv.id as variant_id',
                'p.product_name',
                'pv.variant_name',
                'pv.final_price',
                'p.product_type'
            )
            ->where('pv.id', $variantId)
            ->first();

        if (!$variant) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Calculate HPP for manufactured products
        $hpp = 0;
        if ($variant->product_type === 'manufactured') {
            $hpp = $this->calculateHPP($variantId);
        }

        // Get current cart from session
        $cart = session('pos_cart', []);

        // Check if item already exists in cart
        $cartKey = $variantId;
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['total_price'] = $cart[$cartKey]['quantity'] * $cart[$cartKey]['unit_price'];
            $cart[$cartKey]['total_hpp'] = $cart[$cartKey]['quantity'] * $cart[$cartKey]['unit_hpp'];
        } else {
            $cart[$cartKey] = [
                'variant_id' => $variantId,
                'product_name' => $variant->product_name,
                'variant_name' => $variant->variant_name,
                'quantity' => $quantity,
                'unit_price' => $variant->final_price,
                'unit_hpp' => $hpp,
                'total_price' => $quantity * $variant->final_price,
                'total_hpp' => $quantity * $hpp,
                'discount_id' => null,
                'discount_amount' => 0
            ];
        }

        session(['pos_cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_total' => $this->calculateCartTotal($cart)
        ]);
    }

    public function updateCartItem(Request $request)
    {
        $request->validate([
            'variant_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = session('pos_cart', []);
        $variantId = $request->variant_id;
        $quantity = $request->quantity;

        if ($quantity == 0) {
            unset($cart[$variantId]);
        } else {
            if (isset($cart[$variantId])) {
                $cart[$variantId]['quantity'] = $quantity;
                $cart[$variantId]['total_price'] = $quantity * $cart[$variantId]['unit_price'] - $cart[$variantId]['discount_amount'];
                $cart[$variantId]['total_hpp'] = $quantity * $cart[$variantId]['unit_hpp'];
            }
        }

        session(['pos_cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_total' => $this->calculateCartTotal($cart)
        ]);
    }

    public function applyItemDiscount(Request $request)
    {
        $request->validate([
            'variant_id' => 'required',
            'discount_id' => 'required|exists:cs_discounts,id'
        ]);

        $cart = session('pos_cart', []);
        $variantId = $request->variant_id;
        $discountId = $request->discount_id;

        if (!isset($cart[$variantId])) {
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        // Get discount details
        $discount = DB::table('cs_discounts')
            ->where('id', $discountId)
            ->where('apply_to', 'item')
            ->first();

        if (!$discount) {
            return response()->json(['error' => 'Invalid discount'], 400);
        }

        // Calculate discount amount
        $itemTotal = $cart[$variantId]['quantity'] * $cart[$variantId]['unit_price'];
        $discountAmount = 0;

        if ($discount->discount_type === 'percentage') {
            $discountAmount = $itemTotal * ($discount->discount_value / 100);
        } else {
            $discountAmount = $discount->discount_value;
        }

        // Update cart item
        $cart[$variantId]['discount_id'] = $discountId;
        $cart[$variantId]['discount_amount'] = $discountAmount;
        $cart[$variantId]['total_price'] = $itemTotal - $discountAmount;

        session(['pos_cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'cart_total' => $this->calculateCartTotal($cart)
        ]);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'customer_whatsapp' => 'nullable|string|max:20',
            'transaction_discount_id' => 'nullable|exists:cs_discounts,id',
            'payments' => 'required|array|min:1',
            'payments.*.method_id' => 'required|exists:cs_payment_methods,id',
            'payments.*.amount' => 'required|numeric|min:0'
        ]);

        $cart = session('pos_cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();

        try {
            $outletId = session('outlet_id', 1);
            $cashierId = Auth::id();

            // Handle customer
            $customerId = null;
            if ($request->customer_whatsapp) {
                $customer = DB::table('cs_customers')
                    ->where('whatsapp', $request->customer_whatsapp)
                    ->first();

                if (!$customer) {
                    $customerId = DB::table('cs_customers')->insertGetId([
                        'name' => $request->customer_name ?? 'Customer',
                        'whatsapp' => $request->customer_whatsapp,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    $customerId = $customer->id;
                }
            }

            // Calculate totals
            $cartTotal = $this->calculateCartTotal($cart);
            $subtotal = $cartTotal['subtotal'];
            $totalItemDiscount = $cartTotal['total_item_discount'];
            $totalHpp = $cartTotal['total_hpp'];

            // Apply transaction discount
            $transactionDiscountAmount = 0;
            if ($request->transaction_discount_id) {
                $transactionDiscount = DB::table('cs_discounts')
                    ->where('id', $request->transaction_discount_id)
                    ->where('apply_to', 'transaction')
                    ->first();

                if ($transactionDiscount && $subtotal >= $transactionDiscount->min_amount) {
                    if ($transactionDiscount->discount_type === 'percentage') {
                        $transactionDiscountAmount = $subtotal * ($transactionDiscount->discount_value / 100);
                    } else {
                        $transactionDiscountAmount = $transactionDiscount->discount_value;
                    }
                }
            }

            $totalAmount = $subtotal - $totalItemDiscount - $transactionDiscountAmount;

            // Validate payment amount
            $totalPayment = collect($request->payments)->sum('amount');
            if ($totalPayment < $totalAmount) {
                throw new \Exception('Payment amount is insufficient');
            }

            // Generate transaction number
            $transactionNumber = 'TRX' . date('Ymd') . str_pad(
                DB::table('cs_transactions')->whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Create transaction
            $transactionId = DB::table('cs_transactions')->insertGetId([
                'outlet_id' => $outletId,
                'customer_id' => $customerId,
                'transaction_number' => $transactionNumber,
                'transaction_date' => now(),
                'subtotal' => $subtotal,
                'discount_id' => $request->transaction_discount_id,
                'discount_amount' => $transactionDiscountAmount,
                'total_amount' => $totalAmount,
                'total_hpp' => $totalHpp,
                'cashier_id' => $cashierId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create transaction details and update stock
            foreach ($cart as $item) {
                // Insert transaction detail
                DB::table('cs_transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_id' => $item['discount_id'],
                    'discount_amount' => $item['discount_amount'],
                    'total_price' => $item['total_price'],
                    'unit_hpp' => $item['unit_hpp'],
                    'total_hpp' => $item['total_hpp'],
                    'created_at' => now()
                ]);

                // Update raw material stock for manufactured products
                $this->updateRawMaterialStock($outletId, $item['variant_id'], $item['quantity']);
            }

            // Create payment records
            foreach ($request->payments as $payment) {
                DB::table('cs_transaction_payments')->insert([
                    'transaction_id' => $transactionId,
                    'payment_method_id' => $payment['method_id'],
                    'amount' => $payment['amount'],
                    'reference_number' => $payment['reference_number'] ?? null,
                    'created_at' => now()
                ]);
            }

            DB::commit();

            // Clear cart
            session()->forget('pos_cart');

            return response()->json([
                'success' => true,
                'transaction_id' => $transactionId,
                'transaction_number' => $transactionNumber,
                'total_amount' => $totalAmount,
                'change' => $totalPayment - $totalAmount
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function printReceipt($transactionId)
    {
        $transaction = DB::table('cs_transactions as t')
            ->leftJoin('cs_customers as c', 't.customer_id', '=', 'c.id')
            ->leftJoin('cs_outlets as o', 't.outlet_id', '=', 'o.id')
            ->leftJoin('cs_users as u', 't.cashier_id', '=', 'u.id')
            ->select(
                't.*',
                'c.name as customer_name',
                'c.whatsapp as customer_whatsapp',
                'o.outlet_name',
                'o.address as outlet_address',
                'o.phone as outlet_phone',
                'u.name as cashier_name'
            )
            ->where('t.id', $transactionId)
            ->first();

        if (!$transaction) {
            abort(404);
        }

        $transactionDetails = DB::table('cs_transaction_details as td')
            ->join('cs_product_variants as pv', 'td.product_variant_id', '=', 'pv.id')
            ->join('cs_products as p', 'pv.product_id', '=', 'p.id')
            ->leftJoin('cs_discounts as d', 'td.discount_id', '=', 'd.id')
            ->select(
                'p.product_name',
                'pv.variant_name',
                'td.quantity',
                'td.unit_price',
                'td.discount_amount',
                'td.total_price',
                'd.discount_name'
            )
            ->where('td.transaction_id', $transactionId)
            ->get();

        $payments = DB::table('cs_transaction_payments as tp')
            ->join('cs_payment_methods as pm', 'tp.payment_method_id', '=', 'pm.id')
            ->select('pm.method_name', 'tp.amount', 'tp.reference_number')
            ->where('tp.transaction_id', $transactionId)
            ->get();

        return view('pos.receipt', compact('transaction', 'transactionDetails', 'payments'));
    }

    private function calculateHPP($variantId)
    {
        $recipes = DB::table('cs_recipes as r')
            ->join('cs_raw_materials as rm', 'r.raw_material_id', '=', 'rm.id')
            ->select('r.quantity_needed', 'rm.current_avg_price')
            ->where('r.product_variant_id', $variantId)
            ->get();

        $totalHpp = 0;
        foreach ($recipes as $recipe) {
            $totalHpp += $recipe->quantity_needed * $recipe->current_avg_price;
        }

        return $totalHpp;
    }

    private function calculateCartTotal($cart)
    {
        $subtotal = 0;
        $totalItemDiscount = 0;
        $totalHpp = 0;

        foreach ($cart as $item) {
            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $itemSubtotal;
            $totalItemDiscount += $item['discount_amount'];
            $totalHpp += $item['total_hpp'];
        }

        return [
            'subtotal' => $subtotal,
            'total_item_discount' => $totalItemDiscount,
            'total_hpp' => $totalHpp,
            'total' => $subtotal - $totalItemDiscount
        ];
    }

    private function updateRawMaterialStock($outletId, $variantId, $quantity)
    {
        // Get product type
        $productType = DB::table('cs_product_variants as pv')
            ->join('cs_products as p', 'pv.product_id', '=', 'p.id')
            ->where('pv.id', $variantId)
            ->value('p.product_type');

        // Only update stock for manufactured products
        if ($productType !== 'manufactured') {
            return;
        }

        $recipes = DB::table('cs_recipes')
            ->where('product_variant_id', $variantId)
            ->get();

        foreach ($recipes as $recipe) {
            $usedQuantity = $recipe->quantity_needed * $quantity;

            // Get current stock
            $currentStock = DB::table('cs_raw_material_stocks')
                ->where('outlet_id', $outletId)
                ->where('raw_material_id', $recipe->raw_material_id)
                ->value('current_stock') ?? 0;

            $newStock = $currentStock - $usedQuantity;

            // Update stock
            DB::table('cs_raw_material_stocks')
                ->updateOrInsert(
                    [
                        'outlet_id' => $outletId,
                        'raw_material_id' => $recipe->raw_material_id
                    ],
                    [
                        'current_stock' => $newStock,
                        'updated_at' => now()
                    ]
                );

            // Log stock movement
            DB::table('cs_stock_movements')->insert([
                'outlet_id' => $outletId,
                'raw_material_id' => $recipe->raw_material_id,
                'movement_type' => 'out',
                'reference_type' => 'sale',
                'reference_id' => null, // Could be transaction_id
                'quantity' => $usedQuantity,
                'stock_before' => $currentStock,
                'stock_after' => $newStock,
                'notes' => "Used for product sale - Variant ID: {$variantId}",
                'created_at' => now()
            ]);
        }
    }

    public function clearCart()
    {
        session()->forget('pos_cart');
        return response()->json(['success' => true]);
    }
}
