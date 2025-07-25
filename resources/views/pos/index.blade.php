@extends('layouts.barbershop.admin')
@section('page-title', 'Point of Sale')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs" id="posTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Kopi Tiga</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">Barbersop</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#senam" type="button" role="tab">Senam</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="posTabsContent">
                    <div class="tab-pane fade show active" id="products" role="tabpanel">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="productSearch" placeholder="Search products...">
                        </div>
                        <div class="row" id="productList">
                            <!-- Products will be loaded here -->
                            <div class="col-12 text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="services" role="tabpanel">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="serviceSearch" placeholder="Search services...">
                        </div>
                        <div class="row" id="serviceList">
                            <!-- Services will be loaded here -->
                            <div class="col-12 text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="orderTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Order items will be added here -->
                            <tr id="emptyOrderMessage">
                                <td colspan="5" class="text-center text-muted py-3">No items added</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <h5>Total:</h5>
                    </div>
                    <div class="col-6 text-end">
                        <h5 id="totalAmount">Rp 0</h5>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="mb-3">
                    <label for="paymentAmount" class="form-label">Payment Amount</label>
                    <input type="number" class="form-control" id="paymentAmount" placeholder="Enter payment amount">
                </div>
                <div class="mb-3">
                    <label for="changeAmount" class="form-label">Change</label>
                    <input type="text" class="form-control" id="changeAmount" readonly>
                </div>
                <button class="btn btn-primary w-100" id="processTransaction">Process Transaction</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let orderItems = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // DOM Elements
    const productSearch = document.getElementById('productSearch');
    const serviceSearch = document.getElementById('serviceSearch');
    const productList = document.getElementById('productList');
    const serviceList = document.getElementById('serviceList');
    const orderTable = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
    const emptyOrderMessage = document.getElementById('emptyOrderMessage');
    const totalAmount = document.getElementById('totalAmount');
    const paymentAmount = document.getElementById('paymentAmount');
    const changeAmount = document.getElementById('changeAmount');
    const processBtn = document.getElementById('processTransaction');
    
    // Load products
    async function loadProducts(search = '') {
        productList.innerHTML = `
            <div class="col-12 text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        try {
            const response = await fetch(`{{ route('pos.products') }}?search=${encodeURIComponent(search)}`);
            const data = await response.json();
            
            if (data.status) {
                let html = '';
                data.data.forEach(product => {
                    html += `
                        <div class="col-md-4 mb-3">
                            <div class="card product-card" data-id="${product.id}" 
                                 data-name="${product.name}" 
                                 data-price="${product.selling_price}" 
                                 data-type="product">
                                <div class="card-body">
                                    <h6 class="card-title">${product.name}</h6>
                                    <p class="card-text">Rp ${product.selling_price.toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                productList.innerHTML = html || '<div class="col-12 text-center py-3 text-muted">No products found</div>';
            }
        } catch (error) {
            console.error('Error loading products:', error);
            productList.innerHTML = '<div class="col-12 text-center py-3 text-danger">Error loading products</div>';
        }
    }
    
    // Load services
    async function loadServices(search = '') {
        serviceList.innerHTML = `
            <div class="col-12 text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        try {
            const response = await fetch(`{{ route('pos.services') }}?search=${encodeURIComponent(search)}`);
            const data = await response.json();
            
            if (data.status) {
                let html = '';
                data.data.forEach(service => {
                    html += `
                        <div class="col-md-4 mb-3">
                            <div class="card service-card" data-id="${service.id}" 
                                 data-name="${service.name}" 
                                 data-price="${service.price}" 
                                 data-type="service">
                                <div class="card-body">
                                    <h6 class="card-title">${service.name}</h6>
                                    <p class="card-text">Rp ${service.price.toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                serviceList.innerHTML = html || '<div class="col-12 text-center py-3 text-muted">No services found</div>';
            }
        } catch (error) {
            console.error('Error loading services:', error);
            serviceList.innerHTML = '<div class="col-12 text-center py-3 text-danger">Error loading services</div>';
        }
    }
    
    // Update order table
    function updateOrderTable() {
        let html = '';
        let total = 0;
        
        orderItems.forEach((item, index) => {
            total += item.subtotal;
            html += `
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input" 
                               data-index="${index}" value="${item.quantity}" min="1">
                    </td>
                    <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                    <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        orderTable.innerHTML = html;
        
        if (orderItems.length > 0) {
            emptyOrderMessage.style.display = 'none';
        } else {
            emptyOrderMessage.style.display = '';
        }
        
        totalAmount.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        calculateChange();
    }
    
    // Calculate change
    function calculateChange() {
        const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
        const payment = parseFloat(paymentAmount.value) || 0;
        const change = payment - total;
        
        changeAmount.value = change >= 0 ? `Rp ${change.toLocaleString('id-ID')}` : 'Insufficient payment';
    }
    
    // Add item to order
    function handleItemClick(event) {
        const card = event.target.closest('.product-card, .service-card');
        if (!card) return;
        
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const type = card.dataset.type;
        
        // Check if item exists
        const existingItem = orderItems.find(item => item.id === id && item.type === type);
        
        if (existingItem) {
            existingItem.quantity += 1;
            existingItem.subtotal = existingItem.quantity * existingItem.price;
        } else {
            orderItems.push({
                id: id,
                name: name,
                price: price,
                quantity: 1,
                subtotal: price,
                type: type
            });
        }
        
        updateOrderTable();
    }
    
    // Remove item
    function handleRemoveItem(event) {
        if (!event.target.closest('.remove-item')) return;
        
        const index = event.target.closest('.remove-item').dataset.index;
        orderItems.splice(index, 1);
        updateOrderTable();
    }
    
    // Update quantity
    function handleQuantityChange(event) {
        if (!event.target.classList.contains('quantity-input')) return;
        
        const index = event.target.dataset.index;
        const quantity = parseInt(event.target.value);
        
        if (quantity > 0) {
            orderItems[index].quantity = quantity;
            orderItems[index].subtotal = quantity * orderItems[index].price;
            updateOrderTable();
        } else {
            event.target.value = orderItems[index].quantity;
        }
    }
    
    // Process transaction
    async function processTransaction() {
        const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
        const payment = parseFloat(paymentAmount.value) || 0;
        
        if (orderItems.length === 0) {
            alert('Please add items to the order');
            return;
        }
        
        if (payment < total) {
            alert('Payment amount is less than total amount');
            return;
        }
        
        processBtn.disabled = true;
        processBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        
        try {
            const response = await fetch("{{ route('pos.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    items: orderItems,
                    total: total,
                    payment: payment,
                    change: payment - total
                })
            });
            
            const data = await response.json();
            
            if (data.status) {
                alert(`Transaction processed successfully. Transaction ID: ${data.transaction_id}`);
                // Reset order
                orderItems = [];
                paymentAmount.value = '';
                changeAmount.value = '';
                updateOrderTable();
            } else {
                alert(`Error: ${data.message}`);
            }
        } catch (error) {
            console.error('Transaction error:', error);
            alert('Error processing transaction');
        } finally {
            processBtn.disabled = false;
            processBtn.textContent = 'Process Transaction';
        }
    }
    
    // Initialize
    loadProducts();
    loadServices();
    
    // Event listeners
    productSearch.addEventListener('input', () => loadProducts(productSearch.value));
    serviceSearch.addEventListener('input', () => loadServices(serviceSearch.value));
    document.addEventListener('click', handleItemClick);
    orderTable.addEventListener('click', handleRemoveItem);
    orderTable.addEventListener('change', handleQuantityChange);
    paymentAmount.addEventListener('input', calculateChange);
    processBtn.addEventListener('click', processTransaction);
});
</script>
@endpush