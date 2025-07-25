@extends('layouts.coffeshop.admin')

@section('title', 'Point of Sale')
@section('page-title', 'Point of Sale')


@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Product Selection Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Products</h5>
                        <div class="d-flex gap-2">
                            <!-- Category Filter -->
                            <select class="form-select form-select-sm" id="categoryFilter" style="width: 200px;">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>

                            <!-- Search -->
                            <input type="text" class="form-control form-control-sm" id="productSearch"
                                placeholder="Search products..." style="width: 200px;">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="productGrid">
                            @foreach ($products as $product)
                                <div class="col-md-3 col-sm-4 col-6 mb-3 product-item"
                                    data-category="{{ $product->category_name }}"
                                    data-name="{{ strtolower($product->product_name) }}">
                                    <div class="card product-card h-100" style="cursor: pointer;"
                                        onclick="addToCart({{ $product->variant_id }}, '{{ $product->product_name }}', '{{ $product->variant_name }}', {{ $product->final_price }})">
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                            style="height: 120px;">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->product_name }}" class="img-fluid"
                                                    style="max-height: 100px;">
                                            @else
                                                <i class="fas fa-coffee fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-1 text-truncate">{{ $product->product_name }}</h6>
                                            <small class="text-muted">{{ $product->variant_name }}</small>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="badge bg-primary">{{ $product->category_name }}</span>
                                                <strong class="text-success">Rp
                                                    {{ number_format($product->final_price, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Panel -->
            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cart</h5>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center text-muted py-4" id="emptyCart">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <p>Cart is empty</p>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="border-top pt-3 mt-3" id="cartSummary" style="display: none;">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="cartSubtotal">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Item Discount:</span>
                                <span id="cartItemDiscount" class="text-danger">-Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Transaction Discount:</span>
                                <div class="d-flex align-items-center">
                                    <select class="form-select form-select-sm me-2" id="transactionDiscount"
                                        style="width: 120px;">
                                        <option value="">No Discount</option>
                                        @foreach ($discounts->where('apply_to', 'transaction') as $discount)
                                            <option value="{{ $discount->id }}" data-type="{{ $discount->discount_type }}"
                                                data-value="{{ $discount->discount_value }}"
                                                data-min="{{ $discount->min_amount }}">
                                                {{ $discount->discount_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="cartTransactionDiscount" class="text-danger">-Rp 0</span>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong id="cartTotal" class="text-success">Rp 0</strong>
                            </div>

                            <!-- Customer Info -->
                            <div class="mb-3">
                                <label class="form-label">Customer (Optional)</label>
                                <input type="text" class="form-control form-control-sm mb-2" id="customerName"
                                    placeholder="Customer Name">
                                <input type="text" class="form-control form-control-sm" id="customerWhatsapp"
                                    placeholder="WhatsApp Number">
                            </div>

                            <!-- Payment Button -->
                            <button class="btn btn-success w-100" onclick="showPaymentModal()">
                                <i class="fas fa-credit-card"></i> Process Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Total Amount: <span id="paymentTotal" class="text-success">Rp 0</span></strong>
                    </div>

                    <div id="paymentMethods">
                        <div class="payment-method mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-select payment-method-select" name="payment_method[]">
                                        <option value="">Select Payment Method</option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->method_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control payment-amount" name="payment_amount[]"
                                        placeholder="Amount" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPaymentMethod()">
                        <i class="fas fa-plus"></i> Add Payment Method
                    </button>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Payment:</span>
                            <span id="totalPaymentAmount">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Change:</span>
                            <span id="changeAmount" class="text-info">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="processPayment()">
                        <i class="fas fa-check"></i> Complete Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let cart = {};
        let cartTotal = {
            subtotal: 0,
            total_item_discount: 0,
            total_hpp: 0,
            total: 0
        };

        // Add to cart function
        function addToCart(variantId, productName, variantName, price) {
            $.ajax({
                url: '{{ route('pos_add_to_cart') }}',
                method: 'POST',
                data: {
                    variant_id: variantId,
                    quantity: 1,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        cart = response.cart;
                        cartTotal = response.cart_total;
                        updateCartDisplay();

                        // Show success toast
                        showToast('Product added to cart', 'success');
                    }
                },
                error: function(xhr) {
                    showToast('Error adding product to cart', 'error');
                }
            });
        }

        // Update cart display
        function updateCartDisplay() {
            const cartItemsContainer = $('#cartItems');
            const emptyCart = $('#emptyCart');
            const cartSummary = $('#cartSummary');

            if (Object.keys(cart).length === 0) {
                emptyCart.show();
                cartSummary.hide();
                return;
            }

            emptyCart.hide();
            cartSummary.show();

            let cartHtml = '';
            Object.values(cart).forEach(item => {
                cartHtml += `
            <div class="cart-item mb-2 p-2 border rounded">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.product_name}</h6>
                        <small class="text-muted">${item.variant_name}</small>
                        ${item.discount_amount > 0 ? `<br><small class="text-danger">Discount: -Rp ${numberFormat(item.discount_amount)}</small>` : ''}
                    </div>
                    <div class="text-end">
                        <div class="input-group input-group-sm mb-1" style="width: 100px;">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.variant_id}, ${item.quantity - 1})">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.variant_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <small class="text-success">Rp ${numberFormat(item.total_price)}</small>
                    </div>
                </div>
                <div class="mt-2">
                    <select class="form-select form-select-sm" onchange="applyItemDiscount(${item.variant_id}, this.value)">
                        <option value="">No Discount</option>
                        @foreach ($discounts->where('apply_to', 'item') as $discount)
                            <option value="{{ $discount->id }}" ${item.discount_id == {{ $discount->id }} ? 'selected' : ''}>
                                {{ $discount->discount_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        `;
            });

            cartItemsContainer.html(cartHtml);

            // Update summary
            $('#cartSubtotal').text('Rp ' + numberFormat(cartTotal.subtotal));
            $('#cartItemDiscount').text('-Rp ' + numberFormat(cartTotal.total_item_discount));
            $('#cartTotal').text('Rp ' + numberFormat(cartTotal.total));
            $('#paymentTotal').text('Rp ' + numberFormat(cartTotal.total));
        }

        // Update quantity
        function updateQuantity(variantId, quantity) {
            $.ajax({
                url: '{{ route('pos_update_cart_item') }}',
                method: 'POST',
                data: {
                    variant_id: variantId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        cart = response.cart;
                        cartTotal = response.cart_total;
                        updateCartDisplay();
                    }
                }
            });
        }

        // Apply item discount
        function applyItemDiscount(variantId, discountId) {
            if (!discountId) return;

            $.ajax({
                url: '{{ route('pos_apply_item_discount') }}',
                method: 'POST',
                data: {
                    variant_id: variantId,
                    discount_id: discountId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        cart = response.cart;
                        cartTotal = response.cart_total;
                        updateCartDisplay();
                    }
                }
            });
        }

        // Clear cart
        function clearCart() {
            if (confirm('Are you sure you want to clear the cart?')) {
                $.ajax({
                    url: '{{ route('pos_clear_cart') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        cart = {};
                        cartTotal = {
                            subtotal: 0,
                            total_item_discount: 0,
                            total_hpp: 0,
                            total: 0
                        };
                        updateCartDisplay();
                        showToast('Cart cleared', 'success');
                    }
                });
            }
        }

        // Show payment modal
        function showPaymentModal() {
            if (Object.keys(cart).length === 0) {
                showToast('Cart is empty', 'error');
                return;
            }

            // Set first payment method amount to total
            $('.payment-amount').first().val(cartTotal.total);
            updatePaymentCalculation();

            $('#paymentModal').modal('show');
        }

        // Add payment method
        function addPaymentMethod() {
            const paymentMethodHtml = `
        <div class="payment-method mb-3">
            <div class="row">
                <div class="col-5">
                    <select class="form-select payment-method-select" name="payment_method[]">
                        <option value="">Select Payment Method</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->method_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-5">
                    <input type="number" class="form-control payment-amount" 
                           name="payment_amount[]" placeholder="Amount" min="0" step="0.01">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePaymentMethod(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
            $('#paymentMethods').append(paymentMethodHtml);
        }

        // Remove payment method
        function removePaymentMethod(button) {
            $(button).closest('.payment-method').remove();
            updatePaymentCalculation();
        }

        // Update payment calculation
        function updatePaymentCalculation() {
            let totalPayment = 0;
            $('.payment-amount').each(function() {
                const amount = parseFloat($(this).val()) || 0;
                totalPayment += amount;
            });

            const change = totalPayment - cartTotal.total;

            $('#totalPaymentAmount').text('Rp ' + numberFormat(totalPayment));
            $('#changeAmount').text('Rp ' + numberFormat(Math.max(0, change)));

            if (change < 0) {
                $('#changeAmount').removeClass('text-info').addClass('text-danger');
            } else {
                $('#changeAmount').removeClass('text-danger').addClass('text-info');
            }
        }

        // Process payment
        function processPayment() {
            const payments = [];
            $('.payment-method').each(function() {
                const methodId = $(this).find('.payment-method-select').val();
                const amount = parseFloat($(this).find('.payment-amount').val()) || 0;

                if (methodId && amount > 0) {
                    payments.push({
                        method_id: methodId,
                        amount: amount
                    });
                }
            });

            if (payments.length === 0) {
                showToast('Please select at least one payment method', 'error');
                return;
            }

            const totalPayment = payments.reduce((sum, payment) => sum + payment.amount, 0);
            if (totalPayment < cartTotal.total) {
                showToast('Payment amount is insufficient', 'error');
                return;
            }

            $.ajax({
                url: '{{ route('pos_process_transaction') }}',
                method: 'POST',
                data: {
                    customer_name: $('#customerName').val(),
                    customer_whatsapp: $('#customerWhatsapp').val(),
                    transaction_discount_id: $('#transactionDiscount').val(),
                    payments: payments,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast('Transaction completed successfully', 'success');

                        // Reset form
                        cart = {};
                        cartTotal = {
                            subtotal: 0,
                            total_item_discount: 0,
                            total_hpp: 0,
                            total: 0
                        };
                        updateCartDisplay();
                        $('#paymentModal').modal('hide');
                        $('#customerName').val('');
                        $('#customerWhatsapp').val('');

                        // Show receipt option
                        if (confirm('Transaction completed! Do you want to print receipt?')) {
                            window.open('{{ url('pos/receipt') }}/' + response.transaction_id, '_blank');
                        }
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showToast(response.error || 'Error processing transaction', 'error');
                }
            });
        }

        // Event listeners
        $(document).ready(function() {
            // Category filter
            $('#categoryFilter').change(function() {
                filterProducts();
            });

            // Product search
            $('#productSearch').on('input', function() {
                filterProducts();
            });

            // Payment amount change
            $(document).on('input', '.payment-amount', function() {
                updatePaymentCalculation();
            });

            // Transaction discount change
            $('#transactionDiscount').change(function() {
                // Recalculate cart total with transaction discount
                // This would need additional AJAX call to recalculate
            });
        });

        // Filter products
        function filterProducts() {
            const category = $('#categoryFilter').val();
            const search = $('#productSearch').val().toLowerCase();

            $('.product-item').each(function() {
                const productCategory = $(this).data('category');
                const productName = $(this).data('name');

                let showProduct = true;

                if (category && productCategory !== category) {
                    showProduct = false;
                }

                if (search && !productName.includes(search)) {
                    showProduct = false;
                }

                if (showProduct) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Utility functions
        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function showToast(message, type) {
            // Simple toast implementation
            const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const toast = `
        <div class="alert ${toastClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
            $('body').append(toast);

            // Auto remove after 3 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        }
    </script>
@endsection

@section('styles')
    <style>
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            background-color: #f8f9fa;
        }

        .sticky-top {
            top: 20px;
        }

        @media (max-width: 768px) {

            .col-md-8,
            .col-md-4 {
                margin-bottom: 20px;
            }

            .sticky-top {
                position: relative !important;
                top: auto !important;
            }
        }
    </style>
@endsection
