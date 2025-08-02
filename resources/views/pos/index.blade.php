@extends('layouts.barbershop.admin')
@section('page-title', 'Point of Sale')

@push('style')
    <style>
        /* Add this to your CSS file or style tag */
        .quota-item {
            background-color: #f8f9fa;
            border-left: 4px solid #17a2b8;
        }

        .quota-item td {
            font-weight: 500;
        }
    </style>
@endpush

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
                        <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">Barbershop</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="exercise-tab" data-bs-toggle="tab" data-bs-target="#exercise" type="button" role="tab">Senam</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="posTabsContent">
                    <!-- Coffee Products Tab -->
                    <div class="tab-pane fade show active" id="products" role="tabpanel">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="productSearch" placeholder="Search coffee products...">
                        </div>
                        <div class="row" id="productList">
                            <div class="col-12 text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barbershop Services Tab -->
                    <div class="tab-pane fade" id="services" role="tabpanel">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="serviceSearch" placeholder="Search barbershop services...">
                        </div>
                        <div class="row" id="serviceList">
                            <div class="col-12 text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Exercise Classes Tab -->
                    <div class="tab-pane fade" id="exercise" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="classDate" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="classSearch" placeholder="Search exercise classes...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="classTable">
                                <thead>
                                    <tr>
                                        <th>Class</th>
                                        <th>Instructor</th>
                                        <th>Time</th>
                                        <th>Availability</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="classList">
                                    <tr>
                                        <td colspan="5" class="text-center py-3">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Exercise Registration Form (hidden by default) -->
                        <div class="card mt-3" id="exerciseFormCard" style="display: none;">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Exercise Registration</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="userType" id="memberRadio" value="member" checked>
                                        <label class="form-check-label" for="memberRadio">Member</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="userType" id="nonMemberRadio" value="non-member">
                                        <label class="form-check-label" for="nonMemberRadio">Non-Member</label>
                                    </div>
                                </div>

                                <div id="memberForm">
                                    <div class="mb-3">
                                        <label for="memberId" class="form-label">Member ID/Phone</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="memberId" placeholder="Enter member ID or phone">
                                            <button class="btn btn-primary" id="checkMemberBtn">Check</button>
                                        </div>
                                    </div>
                                    <div class="member-details mb-3" id="memberDetails" style="display: none;">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title" id="memberName"></h6>
                                                <p class="card-text mb-1">
                                                    <small>Membership:</small> <span id="memberType"></span>
                                                </p>
                                                <p class="card-text mb-1">
                                                    <small>Quota:</small> <span id="memberQuota"></span>/<span id="memberTotalQuota"></span>
                                                </p>
                                                <p class="card-text mb-0">
                                                    <small>Valid until:</small> <span id="memberQuotaEnd"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="nonMemberForm" style="display: none;">
                                    <div class="mb-3">
                                        <label for="nonMemberName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="nonMemberName">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nonMemberPhone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="nonMemberPhone">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="selectedClass" class="form-label">Selected Class</label>
                                    <input type="text" class="form-control" id="selectedClass" readonly>
                                    <input type="hidden" id="selectedClassId">
                                </div>

                                <button class="btn btn-primary w-100" id="registerExerciseBtn" disabled>Register</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Summary Section -->
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
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Payment Method</label>
                    <select class="form-control" id="paymentMethod">
                        <option value="cash">Cash</option>
                        <option value="debit">Debit Card</option>
                        <option value="credit">Credit Card</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100" id="processTransaction">Process Transaction</button>
            </div>
        </div>
    </div>
</div>

<!-- Quota Modal -->
<div class="modal fade" id="quotaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quota Exhausted</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <span id="quotaMessage"></span>
                </div>
                <div class="mb-3">
                    <p>Would you like to top up your quota for Rp 200,000?</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addToOrderCheck" checked>
                        <label class="form-check-label" for="addToOrderCheck">
                            Add to current order
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="topupBtn">Top Up Quota</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registration Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    <span id="successMessage"></span>
                </div>
                <div class="registration-details">
                    <p><strong>Class:</strong> <span id="regClass"></span></p>
                    <p><strong>Time:</strong> <span id="regTime"></span></p>
                    <p><strong>Participant:</strong> <span id="regParticipant"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let orderItems = [];
    let currentMember = null;
    let selectedClassData = null;
    
    // DOM Elements - Coffee Products
    const productSearch = document.getElementById('productSearch');
    const productList = document.getElementById('productList');
    
    // DOM Elements - Barbershop Services
    const serviceSearch = document.getElementById('serviceSearch');
    const serviceList = document.getElementById('serviceList');
    
    // DOM Elements - Exercise Classes
    const classDate = document.getElementById('classDate');
    const classSearch = document.getElementById('classSearch');
    const classList = document.getElementById('classList');
    const exerciseFormCard = document.getElementById('exerciseFormCard');
    const memberRadio = document.getElementById('memberRadio');
    const nonMemberRadio = document.getElementById('nonMemberRadio');
    const memberForm = document.getElementById('memberForm');
    const nonMemberForm = document.getElementById('nonMemberForm');
    const memberIdInput = document.getElementById('memberId');
    const checkMemberBtn = document.getElementById('checkMemberBtn');
    const memberDetails = document.getElementById('memberDetails');
    const registerExerciseBtn = document.getElementById('registerExerciseBtn');
    const selectedClass = document.getElementById('selectedClass');
    const selectedClassId = document.getElementById('selectedClassId');
    const quotaModal = new bootstrap.Modal('#quotaModal');
    const successModal = new bootstrap.Modal('#successModal');
    
    // DOM Elements - Order Summary
    const orderTable = document.getElementById('orderTable').getElementsByTagName('tbody')[0];
    const emptyOrderMessage = document.getElementById('emptyOrderMessage');
    const totalAmount = document.getElementById('totalAmount');
    const paymentAmount = document.getElementById('paymentAmount');
    const changeAmount = document.getElementById('changeAmount');
    const processBtn = document.getElementById('processTransaction');

    // ======================
    // COFFEE PRODUCTS SECTION
    // ======================
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

    // ======================
    // BARBERSHOP SERVICES SECTION
    // ======================
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

    // ======================
    // EXERCISE CLASSES SECTION
    // ======================
    async function loadClasses(date = '', search = '') {
        classList.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </td>
            </tr>
        `;
        
        try {
            const params = new URLSearchParams();
            if (date) params.append('date', date);
            if (search) params.append('search', search);
            
            const response = await fetch(`{{ route('pos.exercise-classes') }}?${params.toString()}`);
            const data = await response.json();
            
            if (data.status) {
                let html = '';
                
                if (data.data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">
                                No classes available for ${new Date(data.current_date).toLocaleDateString()}
                            </td>
                        </tr>
                    `;
                } else {
                    data.data.forEach(cls => {
                        const startTime = new Date(cls.start_datetime);
                        const endTime = new Date(cls.end_datetime);
                        const available = cls.max_participants - cls.participants_count;
                        
                        html += `
                            <tr>
                                <td>${cls.class_name}</td>
                                <td>${cls.instructor_name}</td>
                                <td>
                                    ${startTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - 
                                    ${endTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                </td>
                                <td>
                                    <span class="badge ${available > 0 ? 'bg-success' : 'bg-danger'}">
                                        ${available}/${cls.max_participants}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary select-class-btn" 
                                            data-id="${cls.id}"
                                            data-name="${cls.class_name}"
                                            data-start="${cls.start_datetime}"
                                            data-end="${cls.end_datetime}"
                                            data-available="${available}"
                                            ${available <= 0 ? 'disabled' : ''}>
                                        Select
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                
                classList.innerHTML = html;
                exerciseFormCard.style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading classes:', error);
            classList.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-3 text-danger">
                        Error loading classes
                    </td>
                </tr>
            `;
        }
    }
    
    // Handle class selection
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-class-btn')) {
            selectedClassData = {
                id: e.target.dataset.id,
                name: e.target.dataset.name,
                start: e.target.dataset.start,
                end: e.target.dataset.end,
                available: e.target.dataset.available
            };
            
            selectedClass.value = `${selectedClassData.name} (${new Date(selectedClassData.start).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})})`;
            selectedClassId.value = selectedClassData.id;
            
            // Show the registration form
            exerciseFormCard.style.display = 'block';
            
            // Enable register button if member is verified or non-member form is filled
            if ((memberRadio.checked && currentMember) || 
                (nonMemberRadio.checked && document.getElementById('nonMemberName').value)) {
                registerExerciseBtn.disabled = false;
            }
        }
    });
    
    // Handle user type change
    memberRadio.addEventListener('change', function() {
        memberForm.style.display = 'block';
        nonMemberForm.style.display = 'none';
        registerExerciseBtn.disabled = true;
    });
    
    nonMemberRadio.addEventListener('change', function() {
        memberForm.style.display = 'none';
        nonMemberForm.style.display = 'block';
        registerExerciseBtn.disabled = !selectedClassData || !document.getElementById('nonMemberName').value;
    });
    
    // Check member
    checkMemberBtn.addEventListener('click', async function() {
        const memberId = memberIdInput.value.trim();
        if (!memberId) return;
        
        checkMemberBtn.disabled = true;
        checkMemberBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Checking...';
        
        try {
            const response = await fetch("{{ route('pos.check-member') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ member_id: memberId })
            });
            
            const data = await response.json();
            
            if (data.status) {
                currentMember = data.member;
                
                // Display member details
                document.getElementById('memberName').textContent = currentMember.name;
                document.getElementById('memberType').textContent = currentMember.membership_type.toUpperCase();
                
                if (data.quota) {
                    document.getElementById('memberQuota').textContent = data.quota.remaining_quota;
                    document.getElementById('memberTotalQuota').textContent = data.quota.total_quota;
                    document.getElementById('memberQuotaEnd').textContent = new Date(data.quota.end_date).toLocaleDateString();
                } else {
                    document.getElementById('memberQuota').textContent = '0';
                    document.getElementById('memberTotalQuota').textContent = '0';
                    document.getElementById('memberQuotaEnd').textContent = 'No active quota';
                }
                
                memberDetails.style.display = 'block';
                
                // Enable register button if class is selected
                if (selectedClassData) {
                    registerExerciseBtn.disabled = false;
                }
            } else {
                alert(data.message || 'Member not found');
                memberDetails.style.display = 'none';
                currentMember = null;
            }
        } catch (error) {
            console.error('Error checking member:', error);
            alert('Failed to check member');
        } finally {
            checkMemberBtn.disabled = false;
            checkMemberBtn.textContent = 'Check';
        }
    });
    
    // Handle non-member form changes
    document.getElementById('nonMemberName').addEventListener('input', function() {
        registerExerciseBtn.disabled = !selectedClassData || !this.value;
    });
    
    // Register for class
    registerExerciseBtn.addEventListener('click', async function() {
        if (!selectedClassData) {
            alert('Please select a class first');
            return;
        }
        
        // For members, check quota
        if (memberRadio.checked && currentMember) {
            const remainingQuota = parseInt(document.getElementById('memberQuota').textContent);
            if (remainingQuota <= 0) {
                document.getElementById('quotaMessage').textContent = 
                    `${currentMember.name} has no remaining quota. Please top up to continue.`;
                quotaModal.show();
                return;
            }
        }
        
        registerExerciseBtn.disabled = true;
        registerExerciseBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
        
        try {
            const formData = {
                class_schedule_id: selectedClassData.id
            };
            
            if (memberRadio.checked && currentMember) {
                formData.member_id = currentMember.id;
            } else {
                formData.non_member_name = document.getElementById('nonMemberName').value;
                formData.non_member_phone = document.getElementById('nonMemberPhone').value;
            }
            
            const response = await fetch("{{ route('pos.register-class') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.status) {
                // Show success message
                document.getElementById('successMessage').textContent = 
                    memberRadio.checked ? 
                    `Member ${currentMember.name} registered successfully` :
                    `Non-member ${formData.non_member_name} registered successfully`;
                
                document.getElementById('regClass').textContent = selectedClassData.name;
                document.getElementById('regTime').textContent = 
                    `${new Date(selectedClassData.start).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - 
                     ${new Date(selectedClassData.end).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
                
                document.getElementById('regParticipant').textContent = 
                    memberRadio.checked ? currentMember.name : formData.non_member_name;
                
                successModal.show();
                
                // Reload classes to update availability
                loadClasses(classDate.value, classSearch.value);
                
                // Reset form if non-member
                if (nonMemberRadio.checked) {
                    document.getElementById('nonMemberName').value = '';
                    document.getElementById('nonMemberPhone').value = '';
                } else {
                    // Update quota display
                    if (currentMember) {
                        const newQuota = parseInt(document.getElementById('memberQuota').textContent) - 1;
                        document.getElementById('memberQuota').textContent = newQuota;
                    }
                }
                
                selectedClassData = null;
                selectedClass.value = '';
                selectedClassId.value = '';
                exerciseFormCard.style.display = 'none';
            } else {
                alert(data.message || 'Failed to register for class');
            }
        } catch (error) {
            console.error('Registration error:', error);
            alert('Failed to register for class');
        } finally {
            registerExerciseBtn.disabled = !selectedClassData;
            registerExerciseBtn.textContent = 'Register';
        }
    });
    
    // Top up quota - modified to add to order summary
    document.getElementById('topupBtn').addEventListener('click', async function() {
        if (!currentMember) return;
        
        const btn = document.getElementById('topupBtn');
        const addToOrder = document.getElementById('addToOrderCheck').checked;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
        
        try {
            const response = await fetch("{{ route('pos.topup-quota') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    member_id: currentMember.id,
                    add_to_pos: addToOrder 
                })
            });
            
            const data = await response.json();
            
            if (data.status) {
                // Update quota display
                document.getElementById('memberQuota').textContent = '4';
                document.getElementById('memberTotalQuota').textContent = '4';
                document.getElementById('memberQuotaEnd').textContent = 
                    new Date(new Date().setMonth(new Date().getMonth() + 1)).toLocaleDateString();
                
                // If added to order, update the order summary
                if (addToOrder && data.pos_item) {
                    const topupItem = data.pos_item;
                    
                    // Check if item exists
                    const existingItem = orderItems.find(item => item.id === topupItem.id);
                    
                    if (existingItem) {
                        existingItem.quantity += 1;
                        existingItem.subtotal = existingItem.quantity * existingItem.price;
                    } else {
                        orderItems.push({
                            id: topupItem.id,
                            name: topupItem.name,
                            price: topupItem.price,
                            quantity: 1,
                            subtotal: topupItem.price,
                            type: topupItem.type,
                            member_id: topupItem.member_id
                        });
                    }
                    
                    updateOrderTable();
                }
                
                // Show success message
                // const successMsg = addToOrder ? 
                //     'Quota topped up successfully and added to order!' : 
                //     'Quota topped up successfully!';
                // alert(successMsg);
                
                quotaModal.hide();
            } else {
                alert(data.message || 'Failed to top up quota');
            }
        } catch (error) {
            console.error('Topup error:', error);
            alert('Failed to top up quota');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Top Up Quota';
        }
    });

    // Update the processTransaction function to handle quota topups
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
            // Get the current active tab to determine business type
            const activeTab = document.querySelector('#posTabs .nav-link.active');
            let businessType = 'coffee'; // default
            
            if (activeTab.id === 'services-tab') {
                businessType = 'barbershop';
            } else if (activeTab.id === 'exercise-tab') {
                businessType = 'exercise';
            }
            
            const response = await fetch("{{ route('pos.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    business_type: businessType,
                    items: orderItems,
                    payment_method: 'cash', // You can add a payment method selector
                    payment_amount: payment,
                    customer_name: 'Walk-in Customer' // You can add customer input
                })
            });
            
            const data = await response.json();
            
            if (data.status) {
                alert(`Transaction processed successfully. Invoice: ${data.data.invoice_number}`);
                // Reset order
                orderItems = [];
                paymentAmount.value = '';
                changeAmount.value = '';
                updateOrderTable();
                
                // Optionally print receipt or redirect
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
    // ======================
    // ORDER SUMMARY SECTION
    // ======================
    // Add item to order (for coffee and barbershop)
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
    
    // Update order table
    function updateOrderTable() {
        let html = '';
        let total = 0;
        
        orderItems.forEach((item, index) => {
            total += item.subtotal;
            const rowClass = item.type === 'quota_topup' ? 'quota-item' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td>${item.name}</td>
                    <td>
                        ${item.type === 'quota_topup' ? 
                        '1' : 
                        `<input type="number" class="form-control form-control-sm quantity-input" 
                                data-index="${index}" value="${item.quantity}" min="1">`}
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
    
    // Process transaction (for coffee and barbershop)
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

        // Get the current active tab to determine business type
        const activeTab = document.querySelector('#posTabs .nav-link.active');
        let businessType = 'coffee'; // default
        
        if (activeTab.id === 'services-tab') {
            businessType = 'barbershop';
        } else if (activeTab.id === 'exercise-tab') {
            businessType = 'exercise';
        }
        
        // Prepare the request data
        const requestData = {
            business_type: businessType,
            items: orderItems.map(item => {
                let idValue = item.id;
                // Untuk quota_topup, id tetap string
                if (item.type !== 'quota_topup') {
                    idValue = Number(item.id);
                }
                return {
                    ...item,
                    id: idValue,
                    price: Number(item.price),
                    subtotal: Number(item.subtotal),
                    quantity: Number(item.quantity || 1)
                };
            }),
            payment_method: 'cash', // Default to cash, or get from a select input
            payment_amount: payment,
            customer_name: 'Walk-in Customer' // Default or get from input
        };

        // Add customer_id if available
        if (currentMember) {
            requestData.customer_id = currentMember.id;
            requestData.customer_name = currentMember.name;
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
                body: JSON.stringify(requestData)
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                // Handle validation errors
                if (data.errors) {
                    let errorMessages = [];
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMessages.push(...messages);
                    }
                    alert('Validation errors:\n' + errorMessages.join('\n'));
                    return;
                }
                
                throw new Error(data.message || 'Failed to process transaction');
            }
            
            alert(`Transaction processed successfully. Invoice: ${data.data.invoice_number}`);
            // Reset order
            orderItems = [];
            paymentAmount.value = '';
            changeAmount.value = '';
            updateOrderTable();
            
        } catch (error) {
            console.error('Transaction error:', error);
            alert(error.message || 'Error processing transaction');
        } finally {
            processBtn.disabled = false;
            processBtn.textContent = 'Process Transaction';
        }
    }

    const classDateInput = document.getElementById('classDate');
    if (classDateInput) classDateInput.min = new Date().toISOString().split('T')[0];

    // Initialize time picker with business hours (e.g., 9AM-5PM)
    const bookingTimeInput = document.getElementById('booking_time');
    if (bookingTimeInput) {
        bookingTimeInput.min = '09:00';
        bookingTimeInput.max = '17:00';
    }
    
    // Event listeners for all sections
    productSearch.addEventListener('input', () => loadProducts(productSearch.value));
    serviceSearch.addEventListener('input', () => loadServices(serviceSearch.value));
    classDate.addEventListener('change', function() {
        loadClasses(this.value, classSearch.value);
    });
    classSearch.addEventListener('input', function() {
        loadClasses(classDate.value, this.value);
    });
    
    // Order summary event listeners
    document.addEventListener('click', handleItemClick);
    orderTable.addEventListener('click', handleRemoveItem);
    orderTable.addEventListener('change', handleQuantityChange);
    paymentAmount.addEventListener('input', calculateChange);
    processBtn.addEventListener('click', processTransaction);
    
    // Initial load
    loadProducts();
    loadServices();
    loadClasses();
});
</script>
@endpush