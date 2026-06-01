const getApiUrl = (path) => {
    const base = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';
    return base + path;
};

let products = [];
let cart = [];
let currentCategory = 'all';
let taxRate = 0.11;
let serviceChargeRate = 0.05;
let discountRate = 0;
let currentItem = null;
let isShiftOpen = false;
let searchQuery = '';

let shiftData = {
    status: 'closed',
    initial_cash: 0,
    open_time: '',
    total_sales_cash: 0,
    total_sales_qris: 0,
    total_transactions: 0
};

// Initialize
window.onload = () => {
    checkShiftStatus();
    loadExpensesSummary();
    updateTopBarClock();
    setInterval(updateTopBarClock, 1000);
    
    // Check onboarding setup
    if (!localStorage.getItem('kasgo_setup_completed')) {
        document.getElementById('onboarding-modal').classList.add('active');
        obCurrentStep = 1;
        updateObStepUI();
    } else {
        loadSetupData();
        switchSuite('fnb');
    }
    
    // Initialize offline orders cache if empty
    if (!localStorage.getItem('kasgo_orders')) {
        localStorage.setItem('kasgo_orders', JSON.stringify([]));
    }
    
    switchView('dashboard');
};

function isMenuItemAllowed(item) {
    const role = window.USER_ROLE || 'kasir';
    const name = item.name.toLowerCase();
    
    if (role === 'owner') return true;
    
    if (role === 'admin') {
        if (name.includes('laporan')) return false;
        return true;
    }
    
    if (role === 'kasir') {
        if (
            name.includes('produk') ||
            name.includes('kategori') ||
            (name.includes('stok') && !name.includes('kasir')) ||
            name.includes('supplier') ||
            name.includes('pembelian') ||
            name.includes('bahan baku') ||
            name.includes('pengaturan') ||
            name.includes('laporan') ||
            name.includes('dapur') ||
            name.includes('pesanan aktif') ||
            name.includes('komisi') ||
            name.includes('jasa') ||
            name.includes('pengeluaran')
        ) {
            return false;
        }
        return true;
    }
    return true;
}

// Top Bar Clock
function updateTopBarClock() {
    const clockEl = document.getElementById('top-bar-clock');
    if (!clockEl) return;

    const now = new Date();
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const pad = (n) => n < 10 ? '0' + n : n;

    const timeStr = pad(now.getHours()) + ':' + pad(now.getMinutes());
    const dateStr = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

    clockEl.innerHTML = `
        <div style="font-weight: 700; font-size: 1.05rem; letter-spacing: 0.5px;">${timeStr}</div>
        <div style="font-size: 0.72rem; opacity: 0.85; font-weight: 600;">${dateStr}</div>
    `;
}

// View switcher
function switchView(view) {
    const dashView = document.getElementById('pos-dashboard-view');
    const catalogView = document.getElementById('pos-catalog-view');
    const btnDash = document.getElementById('sidebar-btn-dash');
    const btnPos = document.getElementById('sidebar-btn-pos');
    const sidebar = document.querySelector('.pos-sidebar');

    if (view === 'dashboard') {
        if (sidebar) sidebar.style.display = 'none';
        dashView.style.display = 'flex';
        catalogView.style.display = 'none';
        if (btnDash) btnDash.classList.add('active');
        if (btnPos) btnPos.classList.remove('active');
        loadSetupData();
        updateLocalSalesMetrics();
    } else {
        if (!isShiftOpen) {
            document.getElementById('pos-shift-modal').classList.add('active');
            return;
        }
        if (sidebar) sidebar.style.display = 'flex';
        dashView.style.display = 'none';
        catalogView.style.display = 'flex';
        if (btnDash) btnDash.classList.remove('active');
        if (btnPos) btnPos.classList.add('active');
    }
}

function handleDashboardShiftClick() {
    if (isShiftOpen) {
        openShiftDetailsModal();
    } else {
        document.getElementById('pos-shift-modal').classList.add('active');
    }
}

function updateDashboardShiftCard() {
    const card = document.getElementById('dashboard-shift-card');
    const icon = document.getElementById('dashboard-shift-icon');
    const title = document.getElementById('dashboard-shift-title');
    const subtitle = document.getElementById('dashboard-shift-subtitle');

    if (!card) return;

    if (isShiftOpen) {
        card.classList.add('active-shift');
        icon.innerHTML = '<i class="fas fa-lock-open"></i>';
        title.innerText = 'Shift Aktif';
        subtitle.innerText = 'Ketuk untuk Kelola / Tutup Shift';
    } else {
        card.classList.remove('active-shift');
        icon.innerHTML = '<i class="fas fa-lock"></i>';
        title.innerText = 'Tap untuk mulai shift';
        subtitle.innerText = 'Buka Kasir';
    }
}

function checkShiftStatus() {
    fetch(getApiUrl('api.php?action=shift_status'))
        .then(res => res.json())
        .then(data => {
            if (data.status === 'open') {
                isShiftOpen = true;
                shiftData.initial_cash = data.initial_cash || 0;
                shiftData.open_time = data.open_time || '';
                loadProducts();
                loadShiftSales();
            } else {
                isShiftOpen = false;
                updateDashboardShiftCard();
                loadProducts(); // Load stocks to display on dashboard even if shift is closed
            }
        });
}

function validateInitialCash(input) {
    const btn = document.getElementById('btn-start-shift');
    if (parseFloat(input.value) > 0) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

function openShift() {
    const initialCash = document.getElementById('shift-initial-cash').value;
    if (!initialCash || parseFloat(initialCash) <= 0) {
        alert('Masukkan modal awal kasir.');
        return;
    }

    fetch(getApiUrl('api.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'open_shift', initial_cash: parseFloat(initialCash) })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('pos-shift-modal').classList.remove('active');
                isShiftOpen = true;
                shiftData.initial_cash = parseFloat(initialCash);

                // Get current timestamp for client estimation
                const now = new Date();
                const pad = (n) => n < 10 ? '0' + n : n;
                shiftData.open_time = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()) + ' ' + pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());

                loadProducts();
                loadShiftSales();

                // Automatically redirect to the sales register
                switchView('pos');
            }
        });
}

function loadProducts() {
    fetch(getApiUrl('api.php?action=products'))
        .then(res => res.json())
        .then(data => {
            products = data;
            renderProducts();

            // Update low stock widget on dashboard
            const lowStockCount = products.filter(p => p.stock < 10).length;
            const lowStockEl = document.getElementById('dash-low-stock');
            if (lowStockEl) {
                lowStockEl.innerHTML = `${lowStockCount} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">produk</span>`;
            }
            
            // Sync local sales metrics
            updateLocalSalesMetrics();
        });
}

function searchProducts(query) {
    searchQuery = query.toLowerCase();
    renderProducts();
}

// Render Products
function renderProducts() {
    const grid = document.getElementById('pos-product-grid');
    if (!grid) return;
    grid.innerHTML = '';

    let filtered = currentCategory === 'all'
        ? products
        : products.filter(p => p.category === currentCategory);

    if (searchQuery) {
        filtered = filtered.filter(p => p.name.toLowerCase().includes(searchQuery));
    }

    if (filtered.length === 0) {
        grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; color: var(--text-light); padding: 2rem;">Menu tidak ditemukan.</div>`;
        return;
    }

    filtered.forEach(product => {
        const isOutOfStock = product.stock <= 0;
        const card = document.createElement('div');
        card.className = `pos-product-card ${isOutOfStock ? 'out-of-stock' : ''}`;
        if (!isOutOfStock) {
            card.onclick = () => openItemModal(product);
        }

        let badgeClass = 'stock-badge';
        if (product.stock <= 0) badgeClass += ' danger';
        else if (product.stock < 10) badgeClass += ' warning';

        card.innerHTML = `
            <div class="${badgeClass}">${product.stock <= 0 ? 'Habis' : 'Stok: ' + product.stock}</div>
            <img src="${getApiUrl(product.img)}" alt="${product.name}">
            <div class="pos-product-info">
                <h4>${product.name}</h4>
                <div class="pos-product-price">
                    <span>${formatRupiah(product.price)}</span>
                    <span class="add-icon"><i class="fas fa-plus"></i></span>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

function filterCategory(cat) {
    currentCategory = cat;
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.innerText.toLowerCase().includes(cat.replace('-', '')) || (cat === 'all' && btn.innerText.includes('Semua'))) {
            btn.classList.add('active');
        }
    });
    renderProducts();
}

// Formatting
function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}

// Modal Item
function openItemModal(product) {
    currentItem = { ...product, qty: 1, notes: '' };
    document.getElementById('modal-item-name').innerText = product.name;
    document.getElementById('modal-item-price').innerText = formatRupiah(product.price);
    document.getElementById('modal-item-qty').innerText = 1;
    document.getElementById('modal-item-notes').value = '';

    document.getElementById('pos-item-modal').classList.add('active');
}

function closeItemModal() {
    document.getElementById('pos-item-modal').classList.remove('active');
    currentItem = null;
}

function adjustModalQty(change) {
    if (!currentItem) return;
    let newQty = currentItem.qty + change;

    // Check stock limit
    if (newQty > currentItem.stock) {
        alert('Stok tidak mencukupi.');
        return;
    }
    if (newQty < 1) newQty = 1;

    currentItem.qty = newQty;
    document.getElementById('modal-item-qty').innerText = newQty;
}

function confirmAddItem() {
    if (!currentItem) return;
    currentItem.notes = document.getElementById('modal-item-notes').value.trim();

    // Check if item exists with same notes
    const existingIndex = cart.findIndex(item => item.id === currentItem.id && item.notes === currentItem.notes);

    if (existingIndex > -1) {
        const potentialQty = cart[existingIndex].qty + currentItem.qty;
        if (potentialQty > currentItem.stock) {
            alert('Total pesanan melebihi stok tersedia.');
            return;
        }
        cart[existingIndex].qty += currentItem.qty;
    } else {
        cart.push(currentItem);
    }

    closeItemModal();
    renderCart();
}

// Cart Logic
function renderCart() {
    const cartContainer = document.getElementById('pos-cart-items');
    const checkoutBtn = document.getElementById('btn-checkout');
    if (!cartContainer) return;

    cartContainer.innerHTML = '';

    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <p>Keranjang masih kosong.<br>Pilih menu lezat di sebelah kiri.</p>
            </div>
        `;
        checkoutBtn.disabled = true;
        updateSummary(0);
        
        // Hide tebus murah if cart empty
        const promoSlot = document.getElementById('tebus-murah-slot');
        if (promoSlot) promoSlot.style.display = 'none';
        return;
    }

    checkoutBtn.disabled = false;
    let subtotal = 0;

    cart.forEach((item, index) => {
        subtotal += item.price * item.qty;

        const div = document.createElement('div');
        div.className = 'pos-cart-item';
        div.innerHTML = `
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                ${item.notes ? `<div class="cart-item-notes"><i class="fas fa-comment-alt"></i> ${item.notes}</div>` : ''}
                <div class="cart-item-price">${formatRupiah(item.price)}</div>
            </div>
            <div class="cart-item-actions">
                <div class="cart-qty-control">
                    <button onclick="adjustCartQty(${index}, -1)">-</button>
                    <span>${item.qty}</span>
                    <button onclick="adjustCartQty(${index}, 1)">+</button>
                </div>
            </div>
        `;
        cartContainer.appendChild(div);
    });

    // Tebus Murah Logic
    const promoSlot = document.getElementById('tebus-murah-slot');
    if (promoSlot) {
        if (subtotal > 50000) {
            const hasTebusItem = cart.some(item => item.id === 2 && item.isTebus);
            if (!hasTebusItem) {
                promoSlot.style.display = 'block';
                promoSlot.innerHTML = `
                    <div class="tebus-murah-banner">
                        <div class="tebus-murah-title">
                            <i class="fas fa-gift"></i> 🎉 Tebus Murah Spesial! (Promo Kasgo)
                        </div>
                        <div class="tebus-murah-item-row">
                            <span style="font-size:0.75rem; font-weight: 700; color: #b45309;">Emerald Matcha Espresso</span>
                            <div style="display:flex; align-items:center; gap:0.4rem;">
                                <span style="text-decoration: line-through; color: #94a3b8; font-size:0.75rem;">Rp 32k</span>
                                <strong style="color: #b45309; font-size:0.78rem;">Rp 12k</strong>
                                <button type="button" class="tebus-murah-add-btn" onclick="addTebusMurahItem()">Klaim</button>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                promoSlot.style.display = 'none';
            }
        } else {
            promoSlot.style.display = 'none';
        }
    }

    updateSummary(subtotal);
}

function adjustCartQty(index, change) {
    const item = cart[index];
    const newQty = item.qty + change;

    if (change > 0 && newQty > item.stock) {
        alert('Stok tidak mencukupi.');
        return;
    }

    item.qty = newQty;
    if (item.qty <= 0) {
        cart.splice(index, 1);
    }
    renderCart();
}

function clearCart() {
    if (cart.length === 0) return;
    if (confirm('Kosongkan seluruh keranjang belanja?')) {
        cart = [];
        renderCart();
    }
}

function applyDiscount(percent, element) {
    discountRate = percent / 100;
    document.querySelectorAll('.discount-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    renderCart();
}

let currentTotal = 0;
function updateSummary(subtotal) {
    const discount = subtotal * discountRate;
    const afterDiscount = subtotal - discount;
    const tax = afterDiscount * taxRate;
    const service = afterDiscount * serviceChargeRate;
    currentTotal = afterDiscount + tax + service;

    document.getElementById('pos-subtotal').innerText = formatRupiah(subtotal);
    document.getElementById('pos-discount').innerText = formatRupiah(discount);
    document.getElementById('pos-tax').innerText = formatRupiah(tax);
    document.getElementById('pos-service-charge').innerText = formatRupiah(service);
    document.getElementById('pos-total').innerText = formatRupiah(currentTotal);

    // Update Loyalty Points in cart summary
    const totalBox = document.querySelector('.cart-total-box');
    if (totalBox) {
        let pointsEl = document.getElementById('cart-loyalty-points');
        if (!pointsEl) {
            pointsEl = document.createElement('div');
            pointsEl.id = 'cart-loyalty-points';
            pointsEl.style.fontSize = '0.78rem';
            pointsEl.style.fontWeight = '700';
            pointsEl.style.color = '#b45309';
            pointsEl.style.textAlign = 'right';
            pointsEl.style.marginTop = '0.35rem';
            pointsEl.style.display = 'flex';
            pointsEl.style.alignItems = 'center';
            pointsEl.style.justifyContent = 'flex-end';
            pointsEl.style.gap = '0.25rem';
            totalBox.parentElement.insertBefore(pointsEl, totalBox.nextSibling);
        }
        
        const pts = Math.floor(currentTotal / 5000);
        if (pts > 0) {
            pointsEl.innerHTML = `<i class="fas fa-star" style="color: #f59e0b;"></i> Dapatkan +${pts} Poin Loyalty Kasgo`;
            pointsEl.style.display = 'flex';
        } else {
            pointsEl.style.display = 'none';
        }
    }
}

// Payment Modal Logic
let orderType = 'Dine In';
let paymentMethod = 'Cash';

function openPaymentModal() {
    if (cart.length === 0) return;
    document.getElementById('pos-payment-modal').classList.add('active');
    document.getElementById('payment-total-amount').innerText = formatRupiah(currentTotal);

    // Reset cash inputs
    document.getElementById('cash-received').value = '';
    document.getElementById('cash-change').innerText = 'Rp 0';
    document.getElementById('cash-change').style.color = 'var(--text-light)';

    // Reset split inputs
    document.getElementById('split-cash-amount').value = '';
    document.getElementById('split-qris-amount').value = '';
    document.getElementById('split-pending-amount').innerText = 'Rp 0';

    // Keep inputs synced
    updateCartMetaInfo();

    setPaymentMethod('Cash', document.querySelector('.pay-method-btn') || document.querySelector('.pay-method-btn.active'));
    calculateChange();
}

function closePaymentModal() {
    document.getElementById('pos-payment-modal').classList.remove('active');
}

function setOrderType(type, element) {
    orderType = type;
    document.querySelectorAll('.order-type-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    // Live reactive label updates
    document.getElementById('cart-order-type-label').innerText = type;
}

function updateCartMetaInfo() {
    const nameInput = document.getElementById('pos-customer-name').value || 'Guest';
    document.getElementById('cart-customer-label').innerText = nameInput;
}

function setPaymentMethod(method, element) {
    paymentMethod = method;
    document.querySelectorAll('.pay-method-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    const cashSection = document.getElementById('cash-input-section');
    const splitSection = document.getElementById('split-input-section');
    const bonSection = document.getElementById('bon-input-section');
    const processBtn = document.getElementById('btn-process-payment');

    // Hide all sections first
    if (cashSection) cashSection.style.display = 'none';
    if (splitSection) splitSection.style.display = 'none';
    if (bonSection) bonSection.style.display = 'none';

    if (method === 'Cash') {
        if (cashSection) cashSection.style.display = 'block';
        calculateChange();
    } else if (method === 'QRIS') {
        processBtn.disabled = false;
    } else if (method === 'Split') {
        if (splitSection) {
            splitSection.style.display = 'block';
            document.getElementById('split-cash-amount').value = Math.floor(currentTotal / 2);
            document.getElementById('split-qris-amount').value = currentTotal - Math.floor(currentTotal / 2);
            calculateSplitPay();
        }
    } else if (method === 'BON') {
        if (bonSection) {
            bonSection.style.display = 'block';
            updateBonLimit();
        }
    }
}

function setQuickCash(amount) {
    if (amount === 'exact') {
        document.getElementById('cash-received').value = Math.ceil(currentTotal);
    } else {
        document.getElementById('cash-received').value = amount;
    }
    calculateChange();
}

function calculateChange() {
    if (paymentMethod !== 'Cash') return;

    const received = parseFloat(document.getElementById('cash-received').value) || 0;
    const change = received - currentTotal;

    const changeEl = document.getElementById('cash-change');
    const processBtn = document.getElementById('btn-process-payment');

    if (received === 0) {
        changeEl.innerText = 'Rp 0';
        changeEl.style.color = 'var(--text-light)';
        processBtn.disabled = true;
        return;
    }

    if (change < 0) {
        changeEl.innerText = 'Kurang: ' + formatRupiah(Math.abs(change));
        changeEl.style.color = '#ef4444';
        processBtn.disabled = true;
    } else {
        changeEl.innerText = formatRupiah(change);
        changeEl.style.color = 'var(--accent-color)';
        processBtn.disabled = false;
    }
}

function processPayment() {
    const processBtn = document.getElementById('btn-process-payment');
    processBtn.innerText = 'Memproses...';
    processBtn.disabled = true;

    const customerName = document.getElementById('pos-customer-name').value || 'Guest';

    const orderData = {
        action: 'create',
        customer_name: customerName + ' (' + orderType + ')',
        items: cart,
        total: currentTotal,
        payment_method: paymentMethod,
        order_type: orderType
    };

    fetch(getApiUrl('api.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closePaymentModal();
                generateReceipt(orderData, data.order.id);
                document.getElementById('pos-receipt-modal').classList.add('active');

                // Save checkout locally to localStorage
                const localOrder = {
                    id: data.order.id || 'ord_' + Math.random().toString(36).substr(2, 9),
                    customer_name: customerName + ' (' + orderType + ')',
                    items: JSON.parse(JSON.stringify(cart)),
                    total: currentTotal,
                    payment_method: paymentMethod,
                    order_type: orderType,
                    timestamp: new Date().toLocaleString('id-ID'),
                    status: 'Paid'
                };
                saveLocalOrder(localOrder);

                // FnB Integration
                if (currentSuite === 'fnb') {
                    if (customerName.startsWith('Meja ')) {
                        const match = customerName.match(/^Meja\s+(\d+)/i);
                        if (match && match[1]) {
                            const tableId = parseInt(match[1]);
                            let tablesList = [];
                            try {
                                tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
                            } catch(e) { tablesList = []; }
                            
                            const tIdx = tablesList.findIndex(t => t.id === tableId);
                            if (tIdx > -1) {
                                tablesList[tIdx].status = 'Kosong';
                                tablesList[tIdx].customer = '';
                                tablesList[tIdx].bill = 0;
                                localStorage.setItem('kasgo_fnb_tables', JSON.stringify(tablesList));
                            }
                            
                            let kitchenList = [];
                            try {
                                kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
                            } catch(e) { kitchenList = []; }
                            
                            kitchenList.forEach(ticket => {
                                if (parseInt(ticket.table) === tableId) {
                                    ticket.status = 'Selesai';
                                }
                            });
                            localStorage.setItem('kasgo_fnb_kitchen', JSON.stringify(kitchenList));
                        }
                    } else {
                        const isVirtualBill = localOrder.items.some(item => item.isVirtualBill);
                        if (!isVirtualBill && localOrder.items.length > 0) {
                            let kitchenList = [];
                            try {
                                kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
                            } catch(e) { kitchenList = []; }
                            
                            const newTicket = {
                                id: 'kds_' + Math.random().toString(36).substr(2, 9),
                                table: 'Takeaway',
                                customer: customerName,
                                items: localOrder.items.map(item => ({ name: item.name, qty: item.qty })),
                                status: 'Mengantre',
                                timestamp: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
                            };
                            kitchenList.push(newTicket);
                            localStorage.setItem('kasgo_fnb_kitchen', JSON.stringify(kitchenList));
                        }
                    }
                }

                // Clean state
                cart = [];
                discountRate = 0;
                document.querySelectorAll('.discount-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.discount-btn')[0].classList.add('active'); // reset to 0%

                document.getElementById('pos-customer-name').value = '';

                renderCart();
                loadProducts(); // Refresh stock
                loadShiftSales(); // Refresh shift info & dashboard stats
            } else {
                alert('Gagal memproses pesanan.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan koneksi.');
        })
        .finally(() => {
            processBtn.innerText = 'Proses Pembayaran';
            processBtn.disabled = false;
        });
}

function generateReceipt(order, orderId) {
    let received = order.total;
    if (order.payment_method === 'Cash') {
        received = parseFloat(document.getElementById('cash-received').value) || order.total;
    } else if (order.payment_method === 'Split') {
        const c = parseFloat(document.getElementById('split-cash-amount').value) || 0;
        const q = parseFloat(document.getElementById('split-qris-amount').value) || 0;
        received = c + q;
    }
    const change = received - order.total;
    const date = new Date().toLocaleString('id-ID');

    let itemsHtml = '';
    let subtotal = 0;
    order.items.forEach(item => {
        let itemTotal = item.qty * item.price;
        subtotal += itemTotal;
        itemsHtml += `
            <tr>
                <td>${item.name} x${item.qty}</td>
                <td class="right">${formatRupiah(itemTotal)}</td>
            </tr>
            ${item.notes ? `<tr><td colspan="2" style="font-size:0.75rem; color:#ef4444; padding-left:0.5rem; font-style:italic;">* Catatan: ${item.notes}</td></tr>` : ''}
        `;
    });

    const discountAmount = subtotal * discountRate;
    const afterDiscount = subtotal - discountAmount;
    const tax = afterDiscount * taxRate;
    const service = afterDiscount * serviceChargeRate;

    const html = `
        <div class="receipt-header">
            <h3>AKSARA COFFEE</h3>
            <p>Jl. Aksara Seduhan No. 78, Jakarta</p>
            <p>${date}</p>
        </div>
        <div class="receipt-divider"></div>
        <p style="font-size: 0.78rem; line-height: 1.4; margin: 0.2rem 0;">
            Order ID: <strong>${orderId.replace('ord_', '')}</strong><br>
            Customer: ${order.customer_name}<br>
            Layanan: ${order.order_type}
        </p>
        <div class="receipt-divider"></div>
        <table class="receipt-table">
            ${itemsHtml}
        </table>
        <div class="receipt-divider"></div>
        <table class="receipt-table">
            <tr>
                <td>Subtotal</td>
                <td class="right">${formatRupiah(subtotal)}</td>
            </tr>
            ${discountAmount > 0 ? `
            <tr style="color: #ef4444;">
                <td>Diskon</td>
                <td class="right">-${formatRupiah(discountAmount)}</td>
            </tr>` : ''}
            <tr>
                <td>PPN (11%)</td>
                <td class="right">${formatRupiah(tax)}</td>
            </tr>
            <tr>
                <td>Layanan (5%)</td>
                <td class="right">${formatRupiah(service)}</td>
            </tr>
            <tr style="font-weight: bold; font-size: 0.95rem;">
                <td>TOTAL TAGIHAN</td>
                <td class="right">${formatRupiah(order.total)}</td>
            </tr>
            <tr style="color: #64748b;">
                <td>Bayar (${order.payment_method})</td>
                <td class="right">${formatRupiah(received)}</td>
            </tr>
            ${order.payment_method === 'Cash' || order.payment_method === 'Split' ? `
            <tr style="font-weight: bold;">
                <td>KEMBALIAN</td>
                <td class="right">${formatRupiah(Math.max(0, change))}</td>
            </tr>` : ''}
        </table>
        <div class="receipt-divider"></div>
        <div class="receipt-footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Instagram: @aksara.coffee</p>
        </div>
    `;
    document.getElementById('receipt-content').innerHTML = html;
}

function closeReceiptModal() {
    document.getElementById('pos-receipt-modal').classList.remove('active');
}

function printReceipt() {
    alert('Simulasi Cetak Struk: Mengirim print job bluetooth ke printer thermal 58mm...');
}

// Shift details
function loadShiftSales() {
    if (!shiftData.open_time) return;

    fetch(getApiUrl('api.php'))
        .then(res => res.json())
        .then(orders => {
            let cashSales = 0;
            let qrisSales = 0;

            const openTime = new Date(shiftData.open_time.replace(/-/g, '/')); // browser compatibility

            orders.forEach(order => {
                const orderTime = new Date(order.timestamp.replace(/-/g, '/'));
                if (orderTime >= openTime) {
                    if (order.payment_method === 'Cash') {
                        cashSales += order.total;
                    } else if (order.payment_method === 'QRIS') {
                        qrisSales += order.total;
                    }
                }
            });

            shiftData.total_sales_cash = cashSales;
            shiftData.total_sales_qris = qrisSales;

            // Count total transactions in active shift
            const activeShiftOrders = orders.filter(o => new Date(o.timestamp.replace(/-/g, '/')) >= openTime);
            shiftData.total_transactions = activeShiftOrders.length;

            // Update Dashboard Metrics UI
            const salesTotal = cashSales + qrisSales;
            const salesTotalEl = document.getElementById('dash-sales-total');
            const salesCountEl = document.getElementById('dash-sales-count');
            
            const role = window.USER_ROLE || 'kasir';

            if (salesTotalEl) {
                if (role === 'kasir') {
                    salesTotalEl.innerText = 'Rp ***';
                } else {
                    salesTotalEl.innerText = formatRupiah(salesTotal);
                }
            }
            if (salesCountEl) {
                if (role === 'kasir') {
                    salesCountEl.innerHTML = `*** <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">transaksi</span>`;
                } else {
                    salesCountEl.innerHTML = `${shiftData.total_transactions} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">transaksi</span>`;
                }
            }

            updateDashboardShiftCard();
        });
}

function openShiftDetailsModal() {
    loadShiftSales();
    setTimeout(() => {
        document.getElementById('shift-start-time').innerText = shiftData.open_time || '-';
        document.getElementById('shift-modal-awal').innerText = formatRupiah(shiftData.initial_cash);
        document.getElementById('shift-total-tunai').innerText = formatRupiah(shiftData.total_sales_cash);
        document.getElementById('shift-total-qris').innerText = formatRupiah(shiftData.total_sales_qris);

        const expectedCash = shiftData.initial_cash + shiftData.total_sales_cash;
        document.getElementById('shift-expected-laci').innerText = formatRupiah(expectedCash);

        document.getElementById('shift-actual-cash').value = '';
        document.getElementById('shift-variance-box').innerText = '';

        document.getElementById('pos-shift-details-modal').classList.add('active');
    }, 120);
}

function closeShiftDetailsModal() {
    document.getElementById('pos-shift-details-modal').classList.remove('active');
}

function calculateShiftVariance() {
    const expected = shiftData.initial_cash + shiftData.total_sales_cash;
    const actual = parseFloat(document.getElementById('shift-actual-cash').value) || 0;
    const variance = actual - expected;
    const box = document.getElementById('shift-variance-box');

    if (document.getElementById('shift-actual-cash').value === '') {
        box.innerText = '';
        return;
    }

    if (variance === 0) {
        box.innerText = 'Jumlah Sesuai (Laporan Akurat)';
        box.style.color = 'var(--accent-color)';
    } else if (variance > 0) {
        box.innerText = 'Kelebihan Uang (Surplus): ' + formatRupiah(variance);
        box.style.color = 'var(--accent-color)';
    } else {
        box.innerText = 'Kekurangan Uang (Defisit): ' + formatRupiah(variance);
        box.style.color = '#ef4444';
    }
}

function closeShift() {
    if (!confirm('Apakah Anda yakin ingin menutup shift kasir saat ini? Seluruh kas laci akan dikunci.')) return;

    fetch(getApiUrl('api.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'close_shift' })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Shift berhasil ditutup. Laci kasir telah dikunci untuk penutupan harian.');
                closeShiftDetailsModal();
                isShiftOpen = false;

                // Return to dashboard and lock view
                switchView('dashboard');
                updateDashboardShiftCard();

                // Reset dashboard metrics
                const role = window.USER_ROLE || 'kasir';
                if (role === 'kasir') {
                    document.getElementById('dash-sales-total').innerText = 'Rp ***';
                    document.getElementById('dash-sales-count').innerText = '*** transaksi';
                } else {
                    document.getElementById('dash-sales-total').innerText = 'Rp 0';
                    document.getElementById('dash-sales-count').innerText = '0 transaksi';
                }
            }
        });
}

/* ==========================================================================
   Kasgo POS Premium Core & Simulation Engines
   ========================================================================== */

// 1. Onboarding Setup Variables & Functions
let obCurrentStep = 1;
const businessTypesInfo = {
    pos: {
        title: "Kasgo Retail",
        desc: "Pilihan vertikal ini akan mengkonfigurasi sistem Kasgo Anda ke dalam mode POS Retail dengan layout modular, mendukung barcode scanning, dan laporan stok menipis.",
        theme: "mode-pos"
    },
    fnb: {
        title: "Kasgo Resto",
        desc: "Pilihan vertikal ini akan mengkonfigurasi sistem Kasgo Anda ke dalam mode FnB & Resto dengan layout modular, dapur KDS antrean, reservasi meja, dan menu dine-in.",
        theme: "mode-fnb"
    },
    laundry: {
        title: "Kasgo Laundry",
        desc: "Pilihan vertikal ini akan mengkonfigurasi sistem Kasgo Anda ke dalam mode Laundry Kiloan/Satuan, pelacakan proses cuci -> kering -> setrika -> siap diambil.",
        theme: "mode-laundry"
    },
    care: {
        title: "Kasgo Salon",
        desc: "Pilihan vertikal ini akan mengkonfigurasi sistem Kasgo Anda ke dalam mode Care & Salon, mendukung komisi stylist karyawan, pencatatan antrean pelanggan, dan treatment.",
        theme: "mode-care"
    },
    bengkel: {
        title: "Kasgo Bengkel",
        desc: "Pilihan vertikal ini akan mengkonfigurasi sistem Kasgo Anda ke dalam mode Bengkel Servis, PKB work orders, riwayat plat nomor kendaraan, dan komisi mekanik.",
        theme: "mode-bengkel"
    }
};

function simulateLogoUpload() {
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const randomLetter = letters[Math.floor(Math.random() * letters.length)];
    const colors = ['#f59e0b', '#3b82f6', '#10b981', '#ec4899', '#6366f1', '#14b8a6', '#f43f5e'];
    const randomColor = colors[Math.floor(Math.random() * colors.length)];
    
    const preview = document.getElementById('ob-logo-preview');
    if (preview) {
        preview.innerText = randomLetter;
        preview.style.background = randomColor;
        preview.style.color = 'white';
        localStorage.setItem('kasgo_shop_logo_letter', randomLetter);
        localStorage.setItem('kasgo_shop_logo_color', randomColor);
    }
}

function updateObPreview() {
    const select = document.getElementById('ob-business-type');
    const desc = document.getElementById('ob-type-description');
    if (select && desc) {
        const val = select.value;
        desc.innerHTML = `<i class="fas fa-info-circle" style="color: var(--primary-color);"></i> ${businessTypesInfo[val].desc}`;
    }
}

function updateObStepUI() {
    for (let i = 1; i <= 3; i++) {
        const pane = document.getElementById(`onboarding-pane-${i}`);
        const circle = document.getElementById(`ob-step-circle-${i}`);
        if (pane) pane.classList.remove('active');
        if (circle) {
            circle.classList.remove('active', 'completed');
            if (i < obCurrentStep) circle.classList.add('completed');
            else if (i === obCurrentStep) circle.classList.add('active');
        }
    }
    
    const activePane = document.getElementById(`onboarding-pane-${obCurrentStep}`);
    if (activePane) activePane.classList.add('active');
    
    const progressLine = document.getElementById('onboarding-progress-line');
    if (progressLine) {
        progressLine.style.width = obCurrentStep === 1 ? '0%' : obCurrentStep === 2 ? '50%' : '100%';
    }
    
    const btnPrev = document.getElementById('ob-btn-prev');
    const btnNext = document.getElementById('ob-btn-next');
    if (btnPrev) btnPrev.disabled = obCurrentStep === 1;
    if (btnNext) {
        btnNext.innerText = obCurrentStep === 3 ? 'Selesai & Buka Kasir' : 'Lanjut';
    }
}

function navigateObStep(direction) {
    if (direction === 1) {
        if (obCurrentStep === 1) {
            const name = document.getElementById('ob-owner-name').value.trim();
            const wa = document.getElementById('ob-owner-wa').value.trim();
            const pin = document.getElementById('ob-owner-pin').value.trim();
            if (!name || !wa || !pin) {
                alert('Mohon isi semua kolom owner.');
                return;
            }
            if (pin.length !== 4 || isNaN(pin)) {
                alert('PIN harus berupa 4 digit angka.');
                return;
            }
        } else if (obCurrentStep === 2) {
            const shopName = document.getElementById('ob-shop-name').value.trim();
            const address = document.getElementById('ob-shop-address').value.trim();
            if (!shopName || !address) {
                alert('Mohon isi nama toko dan alamat.');
                return;
            }
        }
        
        if (obCurrentStep < 3) {
            obCurrentStep++;
            updateObStepUI();
        } else {
            completeOnboarding();
        }
    } else {
        if (obCurrentStep > 1) {
            obCurrentStep--;
            updateObStepUI();
        }
    }
}

function completeOnboarding() {
    const ownerName = document.getElementById('ob-owner-name').value.trim();
    const ownerUsername = document.getElementById('ob-owner-username').value.trim();
    const ownerWa = document.getElementById('ob-owner-wa').value.trim();
    const ownerPin = document.getElementById('ob-owner-pin').value.trim();
    const shopName = document.getElementById('ob-shop-name').value.trim();
    const shopAddress = document.getElementById('ob-shop-address').value.trim();
    const businessType = document.getElementById('ob-business-type').value;
    const priceMode = document.querySelector('input[name="ob-price-mode"]:checked').value;
    
    localStorage.setItem('kasgo_setup_completed', 'true');
    localStorage.setItem('kasgo_owner_name', ownerName);
    localStorage.setItem('kasgo_owner_username', ownerUsername);
    localStorage.setItem('kasgo_owner_wa', ownerWa);
    localStorage.setItem('kasgo_owner_pin', ownerPin);
    localStorage.setItem('kasgo_shop_name', shopName);
    localStorage.setItem('kasgo_shop_address', shopAddress);
    localStorage.setItem('kasgo_business_type', businessType);
    localStorage.setItem('kasgo_price_mode', priceMode);
    
    if (!localStorage.getItem('kasgo_shop_logo_letter')) {
        localStorage.setItem('kasgo_shop_logo_letter', shopName.charAt(0).toUpperCase());
        localStorage.setItem('kasgo_shop_logo_color', '#2563eb');
    }
    
    document.getElementById('onboarding-modal').classList.remove('active');
    
    loadSetupData();
    switchSuite('fnb');
    alert('Konfigurasi premium Kasgo berhasil disimpan!');
}

function loadSetupData() {
    const shopName = localStorage.getItem('kasgo_shop_name') || 'Nama Toko';
    const shopAddress = localStorage.getItem('kasgo_shop_address') || 'Alamat Toko';
    const ownerName = localStorage.getItem('kasgo_owner_name') || 'Owner';
    const ownerUsername = localStorage.getItem('kasgo_owner_username') || 'owner';
    const logoLetter = localStorage.getItem('kasgo_shop_logo_letter') || 'K';
    const logoColor = localStorage.getItem('kasgo_shop_logo_color') || '#2563eb';
    
    const shopCards = document.querySelectorAll('.dash-card');
    shopCards.forEach(card => {
        const title = card.querySelector('h4');
        const desc = card.querySelector('p');
        const icon = card.querySelector('.dash-card-icon');
        
        if (title && title.innerText.trim() === 'Nama Toko') {
            title.innerText = shopName;
            if (desc) desc.innerText = shopAddress;
            if (icon) {
                icon.innerText = logoLetter;
                icon.style.background = logoColor;
                icon.style.color = 'white';
                icon.style.fontWeight = '700';
            }
        }
        
        if (title && title.innerText.includes('AyuOwner')) {
            title.innerHTML = `${ownerName} <span class="badge-owner" style="background: #f97316; color: white; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 700; text-transform: uppercase;">OWNER</span>`;
            if (desc) desc.innerText = '@' + ownerUsername;
        }
    });
}

// 2. Dynamic Industry Switching Configuration
let currentSuite = 'fnb';
const suiteMenus = {
    pos: [
        { name: "Kasir Retail", icon: "shopping-cart", color: "#2563eb", click: "switchView('pos')" },
        { name: "Produk & Stok", icon: "box", color: "#f39c12", click: "alert('Kelola produk & stok retail.')" },
        { name: "Pelanggan Grosir", icon: "users", color: "#ec4899", click: "alert('Manajemen pembeli grosir & eceran.')" },
        { name: "Penyesuaian Stok", icon: "arrows-rotate", color: "#3498db", click: "alert('Fitur penyesuaian stok & opname.')" },
        { name: "Supplier Logistik", icon: "truck", color: "#8b5a2b", click: "alert('Daftar pemasok logistik toko.')" },
        { name: "Pembelian PO", icon: "shopping-basket", color: "#64748b", click: "alert('PO pembelian stok ke supplier center.')" },
        { name: "Pengeluaran Kas", icon: "arrow-trend-down", color: "#ef4444", click: "openExpensesModal()" },
        { name: "Riwayat & Void", icon: "history", color: "#5dade2", click: "openRiwayatModal()" },
        { name: "Laporan Omset", icon: "chart-column", color: "#a855f7", click: "openShiftDetailsModal()" },
        { name: "Absensi", icon: "camera", color: "#e040fb", click: "openAbsensiModal()" }
    ],
    fnb: [
        { name: "Kasir FnB", icon: "shopping-cart", color: "#005813", click: "switchView('pos')" },
        { name: "Meja Terisi", icon: "chair", color: "#e67e22", click: "openFnbSuiteModal('tables')" },
        { name: "Pesanan Aktif", icon: "fire", color: "#e74c3c", click: "openFnbSuiteModal('kds')" },
        { name: "Dapur (KDS)", icon: "utensils", color: "#059669", click: "openFnbSuiteModal('kds')" },
        { name: "Bahan Baku", icon: "box-open", color: "#2ecc71", click: "openRawMaterialsModal()" },
        { name: "Reservasi Meja", icon: "mobile-alt", color: "#0d9488", click: "openFnbSuiteModal('tables')" },
        { name: "Pengeluaran Cafe", icon: "arrow-trend-down", color: "#ef4444", click: "openExpensesModal()" },
        { name: "Riwayat & Void", icon: "history", color: "#5dade2", click: "openRiwayatModal()" },
        { name: "Laporan Dapur", icon: "chart-column", color: "#a855f7", click: "openShiftDetailsModal()" },
        { name: "Absensi", icon: "camera", color: "#e040fb", click: "openAbsensiModal()" }
    ],
    laundry: [
        { name: "Kasir Laundry", icon: "shopping-cart", color: "#9f901b", click: "switchView('pos')" },
        { name: "Cucian Baru", icon: "soap", color: "#f1c40f", click: "alert('Terima cucian baru kiloan/satuan.')" },
        { name: "Proses Cuci", icon: "water", color: "#3498db", click: "alert('Status Cuci: Sedang dikerjakan.')" },
        { name: "Proses Kering", icon: "wind", color: "#e67e22", click: "alert('Status Kering: Sedang dikeringkan.')" },
        { name: "Proses Setrika", icon: "shirt", color: "#9b59b6", click: "alert('Status Setrika: Sedang disetrika.')" },
        { name: "Siap Diambil", icon: "circle-check", color: "#2ecc71", click: "alert('Status Selesai: Siap diserahkan.')" },
        { name: "Pengeluaran Kas", icon: "arrow-trend-down", color: "#ef4444", click: "openExpensesModal()" },
        { name: "Riwayat & Void", icon: "history", color: "#5dade2", click: "openRiwayatModal()" },
        { name: "Laporan Laundry", icon: "chart-column", color: "#a855f7", click: "openShiftDetailsModal()" },
        { name: "Absensi", icon: "camera", color: "#e040fb", click: "openAbsensiModal()" }
    ],
    care: [
        { name: "Kasir Care", icon: "shopping-cart", color: "#7c2d41", click: "switchView('pos')" },
        { name: "Antrean Salon", icon: "scissors", color: "#ec4899", click: "alert('Daftar antrean pelanggan salon.')" },
        { name: "Jasa Treatment", icon: "sparkles", color: "#9b59b6", click: "alert(\'Kelola jasa potong rambut, facial...\')" },
        { name: "Komisi Stylist", icon: "id-card", color: "#34495e", click: "alert(\'Penghitungan komisi komprehensif stylist.\')" },
        { name: "Produk Kecantikan", icon: "box", color: "#f39c12", click: "alert(\'Stok vitamin & produk rambut.\')" },
        { name: "Reservasi Slot", icon: "calendar-check", color: "#0d9488", click: "alert(\'Reservasi slot perawatan.\')" },
        { name: "Pengeluaran Kas", icon: "arrow-trend-down", color: "#ef4444", click: "alert(\'Catat pengeluaran kas salon.\')" },
        { name: "Riwayat & Void", icon: "history", color: "#5dade2", click: "openRiwayatModal()" },
        { name: "Laporan Komisi", icon: "chart-column", color: "#a855f7", click: "openShiftDetailsModal()" },
        { name: "Absensi", icon: "camera", color: "#e040fb", click: "openAbsensiModal()" }
    ],
    bengkel: [
        { name: "Kasir Bengkel", icon: "shopping-cart", color: "#1a2942", click: "switchView('pos')" },
        { name: "Work Order (PKB)", icon: "file-signature", color: "#34495e", click: "alert('Perintah Kerja Bengkel baru.')" },
        { name: "Riwayat Plat Nomor", icon: "car", color: "#2c3e50", click: "alert('Cari riwayat servis berdasarkan plat nomor.')" },
        { name: "Komisi Mekanik", icon: "users-cog", color: "#f39c12", click: "alert('Kalkulasi komisi mekanik otomatis.')" },
        { name: "Sparepart & Oli", icon: "cogs", color: "#7f8c8d", click: "alert('Kelola persediaan suku cadang & oli.')" },
        { name: "Jasa Servis", icon: "wrench", color: "#2980b9", click: "alert('Daftar biaya jasa servis kendaraan.')" },
        { name: "Pengeluaran Kas", icon: "arrow-trend-down", color: "#ef4444", click: "alert('Catat pengeluaran kas bengkel.')" },
        { name: "Riwayat & Void", icon: "history", color: "#5dade2", click: "openRiwayatModal()" },
        { name: "Laporan PKB", icon: "chart-column", color: "#a855f7", click: "openShiftDetailsModal()" },
        { name: "Absensi", icon: "camera", color: "#e040fb", click: "openAbsensiModal()" }
    ]
};

const suiteSummaries = {
    pos: [
        { name: "Net Sales", icon: "chart-line", value: "Rp 0", id: "dash-sales-total", color: "green" },
        { name: "Transaksi", icon: "receipt", value: "0 transaksi", id: "dash-sales-count", color: "blue" },
        { name: "Stok Menipis", icon: "exclamation-triangle", value: "0 produk", id: "dash-low-stock", color: "orange" },
        { name: "Pengeluaran", icon: "arrow-trend-down", value: "Rp 0", id: "dash-expense", color: "red" }
    ],
    fnb: [
        { name: "Net Sales Resto", icon: "chart-line", value: "Rp 0", id: "dash-sales-total", color: "green" },
        { name: "Pesanan Aktif", icon: "fire", value: "0 pesanan", id: "dash-sales-count", color: "blue" },
        { name: "Meja Terisi", icon: "chair", value: "0 / 12 meja", id: "dash-low-stock", color: "orange" },
        { name: "Pengeluaran", icon: "arrow-trend-down", value: "Rp 0", id: "dash-expense", color: "red" }
    ],
    laundry: [
        { name: "Net Sales Laundry", icon: "chart-line", value: "Rp 0", id: "dash-sales-total", color: "green" },
        { name: "Total Cucian", icon: "soap", value: "0 cucian", id: "dash-sales-count", color: "blue" },
        { name: "Antrean Proses", icon: "water", value: "0 proses", id: "dash-low-stock", color: "orange" },
        { name: "Siap Diambil", icon: "circle-check", value: "0 paket", id: "dash-expense", color: "red" }
    ],
    care: [
        { name: "Net Sales Salon", icon: "chart-line", value: "Rp 0", id: "dash-sales-total", color: "green" },
        { name: "Antrean Care", icon: "scissors", value: "0 antrean", id: "dash-sales-count", color: "blue" },
        { name: "Treatment Selesai", icon: "sparkles", value: "0 perawatan", id: "dash-low-stock", color: "orange" },
        { name: "Komisi Staf", icon: "wallet", value: "Rp 0", id: "dash-expense", color: "red" }
    ],
    bengkel: [
        { name: "Net Sales Bengkel", icon: "chart-line", value: "Rp 0", id: "dash-sales-total", color: "green" },
        { name: "Work Orders (PKB)", icon: "file-signature", value: "0 WO", id: "dash-sales-count", color: "blue" },
        { name: "Mekanik Bertugas", icon: "users-cog", value: "4 mekanik", id: "dash-low-stock", color: "orange" },
        { name: "Komisi Mekanik", icon: "wallet", value: "Rp 0", id: "dash-expense", color: "red" }
    ]
};

function switchSuite(suite) {
    currentSuite = suite;
    
    const suitesList = ['pos', 'fnb', 'laundry', 'care', 'bengkel'];
    suitesList.forEach(s => {
        const card = document.getElementById(`suite-nav-${s}`);
        if (card) {
            card.className = `suite-nav-card ${s === suite ? 'active-' + s : ''}`;
        }
    });
    
    document.body.className = `pos-body mode-${suite}`;
    renderSuiteRingkasan(suite);
    renderSuiteMenuUtama(suite);
    updateLocalSalesMetrics();
}

function renderSuiteRingkasan(suite) {
    const summaryGrid = document.querySelector('.summary-grid');
    if (!summaryGrid) return;
    
    summaryGrid.innerHTML = '';
    const items = suiteSummaries[suite];
    
    items.forEach(item => {
        const role = window.USER_ROLE || 'kasir';
        const name = item.name.toLowerCase();
        
        let displayValue = item.value;
        let isMasked = false;
        
        if (role === 'kasir') {
            if (name.includes('sales') || name.includes('transaksi') || name.includes('pengeluaran') || name.includes('komisi')) {
                isMasked = true;
                displayValue = (name.includes('sales') || name.includes('pengeluaran') || name.includes('komisi')) ? 'Rp ***' : '***';
            }
        }
        
        const card = document.createElement('div');
        card.className = `sum-card ${item.color}`;
        if (isMasked) {
            card.style.opacity = '0.5';
            card.style.pointerEvents = 'none';
        }
        card.innerHTML = `
            <div class="sum-card-icon"><i class="fas fa-${item.icon}"></i></div>
            <div class="sum-card-info">
                <span>${item.name}</span>
                <h4 id="${item.id}">${displayValue}</h4>
            </div>
        `;
        summaryGrid.appendChild(card);
    });
}

function renderSuiteMenuUtama(suite) {
    const menuGrid = document.querySelector('.menu-grid');
    if (!menuGrid) return;
    
    menuGrid.innerHTML = '';
    const items = suiteMenus[suite];
    
    items.forEach(item => {
        const allowed = isMenuItemAllowed(item);
        const btn = document.createElement('div');
        btn.className = 'menu-item-btn';
        
        if (allowed) {
            btn.setAttribute('onclick', item.click);
            btn.innerHTML = `
                <div class="menu-item-icon" style="background:${item.color};"><i class="fas fa-${item.icon}"></i></div>
                <span>${item.name}</span>
            `;
        } else {
            const alertMsg = window.USER_ROLE === 'admin'
                ? `Akses Ditolak: Hanya Owner yang diizinkan melihat Laporan Keuangan.`
                : `Akses Ditolak: Role Kasir tidak memiliki akses ke modul ini.`;
            
            btn.setAttribute('onclick', `alert('${alertMsg}')`);
            btn.innerHTML = `
                <div class="menu-item-icon" style="background:${item.color}; opacity: 0.5; position: relative;">
                    <i class="fas fa-${item.icon}"></i>
                    <i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i>
                </div>
                <span>${item.name}</span>
            `;
        }
        menuGrid.appendChild(btn);
    });
}

// 3. Simulated Barcode Scan trigger
function triggerBarcodeScan() {
    const scanner = document.getElementById('barcode-scanner');
    if (!scanner) return;
    
    scanner.classList.add('active');
    
    setTimeout(() => {
        scanner.classList.remove('active');
        
        const availableProducts = products.filter(p => p.stock > 0);
        if (availableProducts.length === 0) {
            alert('Semua produk habis stok!');
            return;
        }
        
        const randomProduct = availableProducts[Math.floor(Math.random() * availableProducts.length)];
        
        const existingIndex = cart.findIndex(item => item.id === randomProduct.id && !item.isTebus && !item.notes);
        if (existingIndex > -1) {
            if (cart[existingIndex].qty + 1 > randomProduct.stock) {
                alert(`Gagal menambah ${randomProduct.name}: Stok tidak mencukupi.`);
                return;
            }
            cart[existingIndex].qty += 1;
        } else {
            cart.push({ ...randomProduct, qty: 1, notes: "" });
        }
        
        renderCart();
        alert(`Scanner berhasil memindai barcode produk: ${randomProduct.name}`);
    }, 1500);
}

// 4. Tebus Murah Item Addition
function addTebusMurahItem() {
    const matchProduct = products.find(p => p.id === 2);
    if (!matchProduct || matchProduct.stock <= 0) {
        alert('Stok Emerald Matcha Espresso habis.');
        return;
    }
    
    const promoItem = {
        ...matchProduct,
        price: 12000,
        qty: 1,
        notes: "PROMO TEBUS MURAH KASGO",
        isTebus: true
    };
    
    cart.push(promoItem);
    renderCart();
}

// 5. Split & BON Payments calculations
function calculateSplitPay() {
    const cash = parseFloat(document.getElementById('split-cash-amount').value) || 0;
    const qris = parseFloat(document.getElementById('split-qris-amount').value) || 0;
    
    const totalPaid = cash + qris;
    const remaining = currentTotal - totalPaid;
    
    const pendingEl = document.getElementById('split-pending-amount');
    const processBtn = document.getElementById('btn-process-payment');
    
    if (remaining === 0) {
        pendingEl.innerText = 'Pas';
        pendingEl.style.color = 'var(--accent-color)';
        processBtn.disabled = false;
    } else if (remaining < 0) {
        pendingEl.innerText = 'Kelebihan: ' + formatRupiah(Math.abs(remaining));
        pendingEl.style.color = 'var(--accent-color)';
        processBtn.disabled = false;
    } else {
        pendingEl.innerText = 'Sisa: ' + formatRupiah(remaining);
        pendingEl.style.color = '#ef4444';
        processBtn.disabled = true;
    }
}

function updateBonLimit() {
    const remaining = 500000 - currentTotal;
    const limitEl = document.getElementById('bon-remaining-limit');
    const processBtn = document.getElementById('btn-process-payment');
    
    if (limitEl) {
        limitEl.innerText = formatRupiah(remaining);
        if (remaining < 0) {
            limitEl.style.color = '#ef4444';
            processBtn.disabled = true;
        } else {
            limitEl.style.color = 'var(--accent-color)';
            processBtn.disabled = false;
        }
    }
}

// 6. Local Orders database & Net Sales system with refund triggers
function saveLocalOrder(order) {
    let ordersList = [];
    try {
        ordersList = JSON.parse(localStorage.getItem('kasgo_orders')) || [];
    } catch(e) {
        ordersList = [];
    }
    ordersList.push(order);
    localStorage.setItem('kasgo_orders', JSON.stringify(ordersList));
    updateLocalSalesMetrics();
}

function updateLocalSalesMetrics() {
    let ordersList = [];
    try {
        ordersList = JSON.parse(localStorage.getItem('kasgo_orders')) || [];
    } catch(e) {
        ordersList = [];
    }
    
    let totalSales = 0;
    let totalTransactions = 0;
    
    ordersList.forEach(order => {
        if (order.status !== 'Refunded') {
            totalSales += order.total;
            totalTransactions++;
        }
    });
    
    const salesTotalEl = document.getElementById('dash-sales-total');
    const salesCountEl = document.getElementById('dash-sales-count');
    
    const role = window.USER_ROLE || 'kasir';
    
    if (salesTotalEl) {
        if (role === 'kasir') {
            salesTotalEl.innerText = 'Rp ***';
        } else {
            salesTotalEl.innerText = formatRupiah(totalSales);
        }
    }
    if (salesCountEl) {
        if (role === 'kasir') {
            salesCountEl.innerHTML = `*** <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">${currentSuite === 'bengkel' ? 'WO' : currentSuite === 'laundry' ? 'cucian' : currentSuite === 'fnb' ? 'pesanan' : 'transaksi'}</span>`;
        } else {
            if (currentSuite === 'bengkel') {
                salesCountEl.innerHTML = `${totalTransactions} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">WO</span>`;
            } else if (currentSuite === 'laundry') {
                salesCountEl.innerHTML = `${totalTransactions} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">cucian</span>`;
            } else if (currentSuite === 'fnb') {
                let kitchenList = [];
                try {
                    kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
                } catch(e) {
                    kitchenList = [];
                }
                const activeCount = kitchenList.filter(t => t.status !== 'Selesai').length;
                salesCountEl.innerHTML = `${activeCount} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">pesanan</span>`;
            } else {
                salesCountEl.innerHTML = `${totalTransactions} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">transaksi</span>`;
            }
        }
    }

    const lowStockEl = document.getElementById('dash-low-stock');
    if (lowStockEl) {
        if (currentSuite === 'fnb') {
            let tablesList = [];
            try {
                tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
            } catch(e) {
                tablesList = [];
            }
            if (tablesList.length === 0) {
                tablesList = initFnbTables();
            }
            const occupiedCount = tablesList.filter(t => t.status === 'Terisi').length;
            lowStockEl.innerHTML = `${occupiedCount} / 12 <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">meja</span>`;
        } else {
            const lowStockCount = products.filter(p => p.stock < 10).length;
            lowStockEl.innerHTML = `${lowStockCount} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">produk</span>`;
        }
    }
}

function openRiwayatModal() {
    document.getElementById('pos-riwayat-modal').classList.add('active');
    renderRiwayatList();
}

function closeRiwayatModal() {
    document.getElementById('pos-riwayat-modal').classList.remove('active');
}

function renderRiwayatList() {
    const container = document.getElementById('riwayat-orders-list');
    if (!container) return;
    
    container.innerHTML = '';
    let ordersList = [];
    try {
        ordersList = JSON.parse(localStorage.getItem('kasgo_orders')) || [];
    } catch(e) {
        ordersList = [];
    }
    
    if (ordersList.length === 0) {
        container.innerHTML = `<div style="text-align:center; color:#94a3b8; padding: 2rem 0;">Belum ada riwayat transaksi.</div>`;
        return;
    }
    
    [...ordersList].reverse().forEach(order => {
        const item = document.createElement('div');
        item.style.background = '#f8fafc';
        item.style.border = '1px solid #e2e8f0';
        item.style.borderRadius = '12px';
        item.style.padding = '0.75rem 0.9rem';
        item.style.cursor = 'pointer';
        item.style.transition = 'all 0.2s';
        
        if (order.status === 'Refunded') {
            item.style.opacity = '0.65';
            item.style.borderLeft = '4px solid #ef4444';
        } else {
            item.style.borderLeft = '4px solid var(--primary-color)';
        }
        
        item.onclick = () => showRiwayatOrderDetail(order.id);
        
        item.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.25rem;">
                <strong style="font-size:0.85rem; color:#1e293b;">${order.id.replace('ord_', '')}</strong>
                <span style="font-size:0.75rem; color:#64748b;">${order.timestamp.split(' ')[0]}</span>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.78rem; color:#475569; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:110px;">${order.customer_name}</span>
                <strong style="font-size:0.85rem; color:var(--primary-color);">${formatRupiah(order.total)}</strong>
            </div>
            ${order.status === 'Refunded' ? `<span style="font-size:0.65rem; color:#ef4444; font-weight:700; text-transform:uppercase;"><i class="fas fa-undo"></i> Refunded</span>` : ''}
        `;
        
        container.appendChild(item);
    });
}

function showRiwayatOrderDetail(orderId) {
    const container = document.getElementById('riwayat-order-detail');
    if (!container) return;
    
    let ordersList = [];
    try {
        ordersList = JSON.parse(localStorage.getItem('kasgo_orders')) || [];
    } catch(e) {
        ordersList = [];
    }
    
    const order = ordersList.find(o => o.id === orderId);
    if (!order) return;
    
    let itemsHtml = '';
    order.items.forEach(item => {
        itemsHtml += `
            <div style="display:flex; justify-content:space-between; font-size:0.8rem; color:#475569;">
                <span>${item.name} x${item.qty}</span>
                <span>${formatRupiah(item.price * item.qty)}</span>
            </div>
        `;
    });
    
    const isRefunded = order.status === 'Refunded';
    
    container.innerHTML = `
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:1.2rem; display:flex; flex-direction:column; gap:0.6rem;">
            <h4 style="font-weight:700; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:0.5rem; margin:0; font-size:0.92rem;">Detail Transaksi</h4>
            <div style="font-size:0.78rem; color:#64748b; display:flex; flex-direction:column; gap:0.2rem;">
                <div>ID Transaksi: <strong>${order.id}</strong></div>
                <div>Waktu: ${order.timestamp}</div>
                <div>Pelanggan: ${order.customer_name}</div>
                <div>Metode Bayar: <strong>${order.payment_method}</strong></div>
                <div>Layanan: ${order.order_type}</div>
            </div>
            <div style="border-bottom:1px dashed #e2e8f0; margin:0.4rem 0;"></div>
            <div style="display:flex; flex-direction:column; gap:0.4rem;">
                ${itemsHtml}
            </div>
            <div style="border-bottom:1px dashed #e2e8f0; margin:0.4rem 0;"></div>
            <div style="display:flex; justify-content:space-between; font-weight:700; font-size:0.95rem; color:#1e293b;">
                <span>TOTAL:</span>
                <span>${formatRupiah(order.total)}</span>
            </div>
            
            ${isRefunded ? `
                <div style="background:#fef2f2; border:1px solid #fee2e2; border-radius:10px; padding:0.6rem; color:#ef4444; font-size:0.75rem; text-align:center; font-weight:600; margin-top:0.8rem; line-height:1.35;">
                    <i class="fas fa-info-circle"></i> Transaksi ini telah di-refund. Stok produk telah dikembalikan ke katalog.
                </div>
            ` : `
                <button type="button" class="btn-primary" style="background:#dc2626; color:white; margin-top:0.8rem; box-shadow:none; padding:0.6rem;" onclick="processRefund('${order.id}')">
                    <i class="fas fa-undo"></i> Refund Transaksi
                </button>
            `}
        </div>
    `;
}

function processRefund(orderId) {
    if (!confirm('Apakah Anda yakin ingin melakukan refund untuk transaksi ini? Stok produk akan dikembalikan dan nilai penjualan dikurangi.')) return;
    
    let ordersList = [];
    try {
        ordersList = JSON.parse(localStorage.getItem('kasgo_orders')) || [];
    } catch(e) {
        ordersList = [];
    }
    
    const orderIndex = ordersList.findIndex(o => o.id === orderId);
    if (orderIndex === -1) return;
    
    const order = ordersList[orderIndex];
    order.status = 'Refunded';
    
    localStorage.setItem('kasgo_orders', JSON.stringify(ordersList));
    
    order.items.forEach(orderItem => {
        if (orderItem.isTebus) return;
        const catalogProduct = products.find(p => p.id === orderItem.id);
        if (catalogProduct) {
            catalogProduct.stock += orderItem.qty;
        }
    });
    
    renderProducts();
    
    const lowStockCount = products.filter(p => p.stock < 10).length;
    const lowStockEl = document.getElementById('dash-low-stock');
    if (lowStockEl) {
        lowStockEl.innerHTML = `${lowStockCount} <span style="font-size:0.75rem; font-weight:normal; opacity:0.85;">produk</span>`;
    }
    
    renderRiwayatList();
    showRiwayatOrderDetail(orderId);
    updateLocalSalesMetrics();
    
    alert('Refund berhasil diselesaikan! Stok produk telah berhasil dikembalikan ke katalog menu.');
}

// 7. Accordion & Modal triggers
function openPanduanModal() {
    document.getElementById('pos-panduan-modal').classList.add('active');
}

function closePanduanModal() {
    document.getElementById('pos-panduan-modal').classList.remove('active');
}

function openFeaturesModal() {
    document.getElementById('pos-features-modal').classList.add('active');
}

function closeFeaturesModal() {
    document.getElementById('pos-features-modal').classList.remove('active');
}

function toggleAccordion(header) {
    const item = header.parentElement;
    item.classList.toggle('active');
}

// 8. Multi-Kasir Server Client connection sync status toggle simulation
let isConnected = true;
function toggleSyncConnection() {
    isConnected = !isConnected;
    const badge = document.getElementById('sync-status');
    const text = document.getElementById('sync-text');
    
    if (!badge || !text) return;
    
    if (isConnected) {
        badge.className = "sync-badge online";
        text.innerText = "Multi-Kasir Terhubung";
        badge.style.background = "rgba(255,255,255,0.15)";
        alert("Sinkronisasi Multi-Kasir aktif! Database lokal tersinkronisasi otomatis dengan server.");
    } else {
        badge.className = "sync-badge offline";
        text.innerText = "Offline (Simpan Lokal)";
        badge.style.background = "rgba(239, 68, 68, 0.4)";
        alert("Sistem beralih ke Mode Offline. Semua penjualan sementara disimpan di database browser lokal Anda.");
    }
}

// ==========================================
// 9. Kasgo Resto (FnB) Live Simulator Suite
// ==========================================

let activeSelectedFnbTableId = null;

function initFnbTables() {
    const defaultTables = [];
    for (let i = 1; i <= 12; i++) {
        defaultTables.push({
            id: i,
            status: 'Kosong',
            customer: '',
            bill: 0
        });
    }
    localStorage.setItem('kasgo_fnb_tables', JSON.stringify(defaultTables));
    return defaultTables;
}

function initFnbKitchen() {
    const defaultKitchen = [
        {
            id: 'kds_1',
            table: 3,
            customer: 'Budi',
            items: [
                { name: 'Kopi Susu Aksara', qty: 2 },
                { name: 'Butter Croissant', qty: 1 }
            ],
            status: 'Mengantre',
            timestamp: new Date(Date.now() - 15 * 60 * 1000).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
        },
        {
            id: 'kds_2',
            table: 5,
            customer: 'Santi',
            items: [
                { name: 'Emerald Matcha Espresso', qty: 1 }
            ],
            status: 'Sedang Dimasak',
            timestamp: new Date(Date.now() - 5 * 60 * 1000).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
        }
    ];
    localStorage.setItem('kasgo_fnb_kitchen', JSON.stringify(defaultKitchen));
    return defaultKitchen;
}

function openFnbSuiteModal(tab = 'tables') {
    const role = window.USER_ROLE || 'kasir';
    if (tab === 'kds' && role === 'kasir') {
        alert('Akses Ditolak: Halaman Dapur hanya untuk Admin dan Owner.');
        tab = 'tables';
    }

    if (!localStorage.getItem('kasgo_fnb_tables')) {
        initFnbTables();
    }
    if (!localStorage.getItem('kasgo_fnb_kitchen')) {
        initFnbKitchen();
    }
    
    const modal = document.getElementById('pos-fnb-suite-modal');
    if (modal) {
        modal.classList.add('active');
        switchFnbTab(tab);
    }
}

function closeFnbSuiteModal() {
    const modal = document.getElementById('pos-fnb-suite-modal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function switchFnbTab(tab) {
    const role = window.USER_ROLE || 'kasir';
    if (tab === 'kds' && role === 'kasir') {
        alert('Akses Ditolak: Halaman Dapur hanya untuk Admin dan Owner.');
        return;
    }

    const btnTables = document.getElementById('fnb-tab-tables');
    const btnKds = document.getElementById('fnb-tab-kds');
    const panelTables = document.getElementById('fnb-panel-tables');
    const panelKds = document.getElementById('fnb-panel-kds');
    
    if (tab === 'tables') {
        if (btnTables) btnTables.classList.add('active');
        if (btnKds) btnKds.classList.remove('active');
        if (panelTables) panelTables.classList.add('active');
        if (panelKds) panelKds.classList.remove('active');
        renderFnbTables();
    } else {
        if (btnTables) btnTables.classList.remove('active');
        if (btnKds) btnKds.classList.add('active');
        if (panelTables) panelTables.classList.remove('active');
        if (panelKds) panelKds.classList.add('active');
        renderFnbKds();
    }
}

function renderFnbTables() {
    const container = document.getElementById('fnb-tables-container');
    if (!container) return;
    
    container.innerHTML = '';
    let tablesList = [];
    try {
        tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
    } catch(e) {
        tablesList = [];
    }
    
    if (tablesList.length === 0) {
        tablesList = initFnbTables();
    }
    
    tablesList.forEach(table => {
        const item = document.createElement('div');
        const statusClass = table.status.toLowerCase();
        const activeClass = activeSelectedFnbTableId === table.id ? 'active-selection' : '';
        
        item.className = `fnb-table-item status-${statusClass} ${activeClass}`;
        item.setAttribute('onclick', `selectFnbTable(${table.id})`);
        
        let desc = 'Kosong';
        if (table.status === 'Terisi') {
            desc = table.customer || 'Guest';
        } else if (table.status === 'Dipesan') {
            desc = `Booking: ${table.customer || 'Guest'}`;
        }
        
        item.innerHTML = `
            <div class="fnb-table-badge ${statusClass}">${table.status}</div>
            <div class="fnb-table-number">MEJA ${table.id}</div>
            <div class="fnb-table-icon"><i class="fas fa-chair"></i></div>
            <div class="fnb-table-desc">${desc}</div>
        `;
        
        container.appendChild(item);
    });
    
    if (activeSelectedFnbTableId === null && tablesList.length > 0) {
        selectFnbTable(tablesList[0].id);
    } else if (activeSelectedFnbTableId !== null) {
        const activeTableExists = tablesList.some(t => t.id === activeSelectedFnbTableId);
        if (activeTableExists) {
            renderFnbTableDetails(tablesList.find(t => t.id === activeSelectedFnbTableId));
        }
    }
}

function selectFnbTable(id) {
    activeSelectedFnbTableId = id;
    
    const cards = document.querySelectorAll('.fnb-table-item');
    cards.forEach((card, index) => {
        if (index === id - 1) {
            card.classList.add('active-selection');
        } else {
            card.classList.remove('active-selection');
        }
    });
    
    let tablesList = [];
    try {
        tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
    } catch(e) {
        tablesList = [];
    }
    
    const table = tablesList.find(t => t.id === id);
    if (!table) return;
    
    renderFnbTableDetails(table);
}

function renderFnbTableDetails(table) {
    const detailPanel = document.getElementById('fnb-table-detail-container');
    if (!detailPanel) return;
    
    let statusBadgeColor = '#94a3b8';
    if (table.status === 'Terisi') statusBadgeColor = '#ef4444';
    if (table.status === 'Dipesan') statusBadgeColor = '#ca8a04';
    
    detailPanel.innerHTML = `
        <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.8rem; margin-bottom: 1rem;">
            <h4 style="margin: 0; font-size: 1.15rem; color: #1e293b; display: flex; align-items: center; justify-content: space-between;">
                <span>Meja ${table.id}</span>
                <span class="badge-status" style="background: ${statusBadgeColor}; color: white; font-size: 0.72rem; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 700; text-transform: uppercase;">
                    ${table.status}
                </span>
            </h4>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 0.8rem; flex: 1;">
            <div>
                <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.3rem;">Status Meja</label>
                <select id="fnb-edit-status" class="form-control" style="width: 100%;" onchange="toggleFnbFormFields()">
                    <option value="Kosong" ${table.status === 'Kosong' ? 'selected' : ''}>🟢 Kosong (Tersedia)</option>
                    <option value="Terisi" ${table.status === 'Terisi' ? 'selected' : ''}>🔴 Terisi (Dine In)</option>
                    <option value="Dipesan" ${table.status === 'Dipesan' ? 'selected' : ''}>🟡 Dipesan (Reservasi)</option>
                </select>
            </div>
            
            <div id="fnb-field-customer" style="display: ${table.status === 'Kosong' ? 'none' : 'block'};">
                <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.3rem;">Nama Pelanggan</label>
                <input type="text" id="fnb-edit-customer" class="form-control" style="width: 100%;" value="${table.customer || ''}" placeholder="Contoh: Rudi">
            </div>
            
            <div id="fnb-field-bill" style="display: ${table.status === 'Kosong' ? 'none' : 'block'};">
                <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.3rem;">Total Tagihan (Rp)</label>
                <input type="number" id="fnb-edit-bill" class="form-control" style="width: 100%;" value="${table.bill || 0}" placeholder="Contoh: 150000">
            </div>
            
            <button class="btn-primary" style="margin-top: 0.5rem; width: 100%; padding: 0.55rem;" onclick="saveFnbTableDetails(${table.id})">
                <i class="fas fa-save"></i> Simpan Status Meja
            </button>
            
            ${table.status !== 'Kosong' ? `
                <div style="border-top: 1px dashed #cbd5e1; margin-top: 0.8rem; padding-top: 0.8rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <button class="btn-primary" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; box-shadow: none; font-size: 0.78rem; padding: 0.5rem;" onclick="orderFnbTableDirect(${table.id})">
                        <i class="fas fa-cart-plus"></i> Buat Pesanan di Kasir
                    </button>
                    ${table.bill > 0 ? `
                        <button class="btn-primary" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; box-shadow: none; font-size: 0.78rem; padding: 0.5rem;" onclick="checkoutFnbTable(${table.id})">
                            <i class="fas fa-cash-register"></i> Selesaikan & Bayar
                        </button>
                    ` : ''}
                </div>
            ` : ''}
        </div>
    `;
}

function toggleFnbFormFields() {
    const statusSelect = document.getElementById('fnb-edit-status');
    const custField = document.getElementById('fnb-field-customer');
    const billField = document.getElementById('fnb-field-bill');
    
    if (statusSelect && custField && billField) {
        if (statusSelect.value === 'Kosong') {
            custField.style.display = 'none';
            billField.style.display = 'none';
        } else {
            custField.style.display = 'block';
            billField.style.display = 'block';
        }
    }
}

function saveFnbTableDetails(id) {
    const statusSelect = document.getElementById('fnb-edit-status');
    const customerInput = document.getElementById('fnb-edit-customer');
    const billInput = document.getElementById('fnb-edit-bill');
    
    if (!statusSelect) return;
    
    let tablesList = [];
    try {
        tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
    } catch(e) {
        tablesList = [];
    }
    
    const idx = tablesList.findIndex(t => t.id === id);
    if (idx === -1) return;
    
    const oldStatus = tablesList[idx].status;
    const newStatus = statusSelect.value;
    const newCustomer = customerInput ? customerInput.value.trim() : '';
    const newBill = billInput ? parseFloat(billInput.value) || 0 : 0;
    
    tablesList[idx].status = newStatus;
    tablesList[idx].customer = newStatus === 'Kosong' ? '' : newCustomer;
    tablesList[idx].bill = newStatus === 'Kosong' ? 0 : newBill;
    
    localStorage.setItem('kasgo_fnb_tables', JSON.stringify(tablesList));
    
    if (newStatus === 'Terisi' && oldStatus !== 'Terisi') {
        addKdsTicketFromTable(id, newCustomer);
    }
    
    renderFnbTables();
    selectFnbTable(id);
    updateLocalSalesMetrics();
    alert(`Status Meja ${id} berhasil diperbarui!`);
}

function addKdsTicketFromTable(tableId, customerName) {
    let kitchenList = [];
    try {
        kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
    } catch(e) {
        kitchenList = [];
    }
    
    const activeProducts = products.filter(p => p.stock > 0);
    const selectedItems = [];
    if (activeProducts.length > 0) {
        const item1 = activeProducts[Math.floor(Math.random() * activeProducts.length)];
        selectedItems.push({ name: item1.name, qty: 1 });
        if (Math.random() > 0.5 && activeProducts.length > 1) {
            let item2 = activeProducts[Math.floor(Math.random() * activeProducts.length)];
            while (item2.id === item1.id) {
                item2 = activeProducts[Math.floor(Math.random() * activeProducts.length)];
            }
            selectedItems.push({ name: item2.name, qty: 1 });
        }
    } else {
        selectedItems.push({ name: 'Kopi Susu Aksara', qty: 1 });
    }
    
    const newTicket = {
        id: 'kds_' + Math.random().toString(36).substr(2, 9),
        table: tableId,
        customer: customerName || 'Pelanggan Meja ' + tableId,
        items: selectedItems,
        status: 'Mengantre',
        timestamp: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
    };
    
    kitchenList.push(newTicket);
    localStorage.setItem('kasgo_fnb_kitchen', JSON.stringify(kitchenList));
    
    playKdsSound();
}

function orderFnbTableDirect(id) {
    let tablesList = [];
    try {
        tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
    } catch(e) {
        tablesList = [];
    }
    const table = tablesList.find(t => t.id === id);
    if (!table) return;
    
    document.getElementById('pos-customer-name').value = `Meja ${table.id} - ${table.customer || 'Guest'}`;
    
    cart = [];
    renderCart();
    
    closeFnbSuiteModal();
    switchView('pos');
}

function checkoutFnbTable(id) {
    let tablesList = [];
    try {
        tablesList = JSON.parse(localStorage.getItem('kasgo_fnb_tables')) || [];
    } catch(e) {
        tablesList = [];
    }
    const table = tablesList.find(t => t.id === id);
    if (!table) return;
    
    if (table.bill <= 0) {
        alert('Meja ini tidak memiliki tagihan aktif.');
        return;
    }
    
    cart = [
        {
            id: 999,
            name: `Bill Meja ${table.id} (${table.customer || 'Guest'})`,
            price: table.bill,
            qty: 1,
            category: 'fnb',
            stock: 999,
            isVirtualBill: true
        }
    ];
    
    document.getElementById('pos-customer-name').value = `Meja ${table.id} - ${table.customer || 'Guest'}`;
    
    closeFnbSuiteModal();
    switchView('pos');
    
    renderCart();
    openPaymentModal();
}

function renderFnbKds() {
    const container = document.getElementById('fnb-kds-container');
    if (!container) return;
    
    container.innerHTML = '';
    let kitchenList = [];
    try {
        kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
    } catch(e) {
        kitchenList = [];
    }
    
    const activeTickets = kitchenList.filter(t => t.status !== 'Selesai');
    
    if (activeTickets.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1/-1; text-align: center; color: #94a3b8; padding: 3rem 0; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; margin: 1rem 0;">
                <i class="fas fa-utensils" style="font-size: 2.5rem; margin-bottom: 0.5rem; color: #cbd5e1;"></i>
                <p style="font-weight:600; margin:0;">Tidak ada antrean pesanan di dapur.</p>
                <span style="font-size:0.75rem; color:#94a3b8;">Klik tombol "Simulasikan Pesanan Masuk" di atas untuk menambahkan simulasi pesanan.</span>
            </div>
        `;
        return;
    }
    
    activeTickets.forEach(ticket => {
        const item = document.createElement('div');
        item.className = 'fnb-kds-card';
        
        let statusBadgeColor = '#2563eb';
        let statusText = 'Antrean';
        let btnText = 'Mulai Masak 🍳';
        
        if (ticket.status === 'Sedang Dimasak') {
            statusBadgeColor = '#f97316';
            statusText = 'Dimasak';
            btnText = 'Siap Sajikan 🛎️';
        } else if (ticket.status === 'Siap Saji') {
            statusBadgeColor = '#10b981';
            statusText = 'Siap Saji';
            btnText = 'Selesaikan ✓';
        }
        
        let itemsHtml = '';
        ticket.items.forEach(itm => {
            itemsHtml += `
                <li style="padding: 0.25rem 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                    <span style="font-weight: 500; color: #334155;">${itm.name}</span>
                    <span style="background: #e2e8f0; color: #1e293b; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 4px; font-size: 0.72rem;">x${itm.qty}</span>
                </li>
            `;
        });
        
        const originLabel = isNaN(ticket.table) ? ticket.table : `MEJA ${ticket.table}`;
        
        item.innerHTML = `
            <div class="fnb-kds-card-header">
                <div>
                    <h5 style="margin: 0; font-size: 0.85rem; color: #1e293b; font-weight: 800;">${originLabel}</h5>
                    <span style="font-size: 0.72rem; color: #64748b; font-weight: 600;">Pelanggan: ${ticket.customer || 'Guest'}</span>
                </div>
                <div style="text-align: right;">
                    <span style="background: ${statusBadgeColor}; color: white; font-size: 0.58rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 4px; text-transform: uppercase;">
                        ${statusText}
                    </span>
                    <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 600; margin-top: 0.15rem;">${ticket.timestamp}</div>
                </div>
            </div>
            
            <ul style="list-style: none; padding: 0.4rem 0; margin: 0; flex: 1; overflow-y: auto;">
                ${itemsHtml}
            </ul>
            
            <button class="btn-primary" style="width:100%; padding:0.4rem; font-size:0.75rem; margin-top:0.4rem;" onclick="advanceKdsStatus('${ticket.id}')">
                ${btnText}
            </button>
        `;
        
        container.appendChild(item);
    });
}

function advanceKdsStatus(id) {
    let kitchenList = [];
    try {
        kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
    } catch(e) {
        kitchenList = [];
    }
    
    const idx = kitchenList.findIndex(t => t.id === id);
    if (idx === -1) return;
    
    const ticket = kitchenList[idx];
    const oldStatus = ticket.status;
    
    if (oldStatus === 'Mengantre') {
        ticket.status = 'Sedang Dimasak';
    } else if (oldStatus === 'Sedang Dimasak') {
        ticket.status = 'Siap Saji';
    } else if (oldStatus === 'Siap Saji') {
        ticket.status = 'Selesai';
    }
    
    localStorage.setItem('kasgo_fnb_kitchen', JSON.stringify(kitchenList));
    
    renderFnbKds();
    updateLocalSalesMetrics();
}

function simulateNewKdsTicket() {
    let kitchenList = [];
    try {
        kitchenList = JSON.parse(localStorage.getItem('kasgo_fnb_kitchen')) || [];
    } catch(e) {
        kitchenList = [];
    }
    
    const isTable = Math.random() > 0.3;
    const tableId = isTable ? Math.floor(Math.random() * 12) + 1 : 'Takeaway';
    
    const randomCustomers = ['Angga', 'Bella', 'Dewi', 'Fahmi', 'Indra', 'Lisa', 'Niko', 'Putri', 'Rian', 'Sari'];
    const customer = randomCustomers[Math.floor(Math.random() * randomCustomers.length)];
    
    const selectedItems = [];
    const availableItems = [
        'Kopi Susu Aksara',
        'Classic Cappuccino',
        'Emerald Matcha Espresso',
        'Pure Matcha Latte',
        'Green Apple Mojito',
        'Signature Chocolate',
        'Matcha Brownies',
        'Butter Croissant',
        'Aksara Mix Platter'
    ];
    
    const itemCount = Math.floor(Math.random() * 3) + 1;
    for (let i = 0; i < itemCount; i++) {
        const randomName = availableItems[Math.floor(Math.random() * availableItems.length)];
        const alreadyHas = selectedItems.find(itm => itm.name === randomName);
        if (alreadyHas) {
            alreadyHas.qty += 1;
        } else {
            selectedItems.push({ name: randomName, qty: Math.floor(Math.random() * 2) + 1 });
        }
    }
    
    const newTicket = {
        id: 'kds_' + Math.random().toString(36).substr(2, 9),
        table: tableId,
        customer: customer,
        items: selectedItems,
        status: 'Mengantre',
        timestamp: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
    };
    
    kitchenList.push(newTicket);
    localStorage.setItem('kasgo_fnb_kitchen', JSON.stringify(kitchenList));
    
    playKdsSound();
    
    renderFnbKds();
    updateLocalSalesMetrics();
    
    alert(`💡 Simulasi KDS: Pesanan Baru Masuk untuk ${isTable ? 'Meja ' + tableId : 'Takeaway'} (${customer})!`);
}

function playKdsSound() {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);
        
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
        oscillator.frequency.setValueAtTime(880, audioCtx.currentTime + 0.15); // A5
        
        gainNode.gain.setValueAtTime(0.15, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.35);
        
        oscillator.start(audioCtx.currentTime);
        oscillator.stop(audioCtx.currentTime + 0.35);
    } catch (e) {
        console.log('Web Audio sound blocked/not supported: ', e);
    }
}

// ==========================================================================
// Cafe Expenses & Raw Materials Module Logic
// ==========================================================================

function loadExpensesSummary() {
    fetch(getApiUrl('api.php?action=expenses'))
        .then(res => res.json())
        .then(expenses => {
            const now = new Date();
            const pad = (n) => n < 10 ? '0' + n : n;
            const todayStr = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
            
            let totalToday = 0;
            expenses.forEach(exp => {
                if (exp.created_at && exp.created_at.startsWith(todayStr)) {
                    totalToday += parseInt(exp.amount || 0);
                }
            });
            
            const el = document.getElementById('dash-expenses-total');
            if (el) {
                el.innerText = formatRupiah(totalToday);
            }
        })
        .catch(err => console.error('Error loading expenses summary:', err));
}

function openExpensesModal() {
    document.getElementById('pos-expenses-modal').classList.add('active');
    loadExpensesList();
}

function closeExpensesModal() {
    document.getElementById('pos-expenses-modal').classList.remove('active');
    document.getElementById('expenses-form').reset();
}

function loadExpensesList() {
    fetch(getApiUrl('api.php?action=expenses'))
        .then(res => res.json())
        .then(expenses => {
            const now = new Date();
            const pad = (n) => n < 10 ? '0' + n : n;
            const todayStr = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate());
            
            const todayExpenses = expenses.filter(exp => exp.created_at && exp.created_at.startsWith(todayStr));
            const tbody = document.getElementById('expenses-list-body');
            
            if (todayExpenses.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" style="text-align: center; padding: 1rem; color: #94a3b8;">Belum ada pengeluaran hari ini.</td></tr>`;
                return;
            }
            
            tbody.innerHTML = '';
            todayExpenses.forEach(exp => {
                const timeStr = exp.created_at ? exp.created_at.substring(11, 16) : '--:--';
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid #f1f5f9';
                tr.innerHTML = `
                    <td style="padding: 0.6rem 0.5rem; color: #64748b;">${timeStr}</td>
                    <td style="padding: 0.6rem 0.5rem; font-weight: 500; color: #334155;">${exp.description}</td>
                    <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #ef4444;">${formatRupiah(exp.amount)}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error('Error loading expenses list:', err));
}

function submitExpense(event) {
    event.preventDefault();
    const desc = document.getElementById('expense-desc').value.trim();
    const amount = parseInt(document.getElementById('expense-amount').value);
    
    if (!desc || isNaN(amount) || amount <= 0) {
        alert('Mohon masukkan keterangan dan jumlah pengeluaran yang valid.');
        return;
    }
    
    fetch(getApiUrl('api.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'create_expense',
            description: desc,
            amount: amount
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Pengeluaran berhasil dicatat!');
            document.getElementById('expenses-form').reset();
            loadExpensesList();
            loadExpensesSummary();
        } else {
            alert('Gagal menyimpan pengeluaran: ' + (data.messages?.error || 'Error server'));
        }
    })
    .catch(err => console.error('Error saving expense:', err));
}

// Raw Materials CRUD Logic
function openRawMaterialsModal() {
    document.getElementById('pos-raw-materials-modal').classList.add('active');
    loadRawMaterialsList();
}

function closeRawMaterialsModal() {
    document.getElementById('pos-raw-materials-modal').classList.remove('active');
    resetRawMaterialForm();
}

function loadRawMaterialsList() {
    fetch(getApiUrl('api.php?action=raw_materials'))
        .then(res => res.json())
        .then(materials => {
            const tbody = document.getElementById('raw-materials-list-body');
            tbody.innerHTML = '';
            
            if (materials.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 1rem; color: #94a3b8;">Tidak ada bahan baku.</td></tr>`;
                return;
            }
            
            materials.forEach(mat => {
                const tr = document.createElement('tr');
                tr.style.borderBottom = '1px solid #f1f5f9';
                tr.innerHTML = `
                    <td style="padding: 0.6rem 0.5rem; font-weight: 500; color: #334155;">${mat.name}</td>
                    <td style="padding: 0.6rem 0.5rem; text-align: right; font-weight: 700; color: #005813;">${parseFloat(mat.stock).toLocaleString('id-ID')}</td>
                    <td style="padding: 0.6rem 0.5rem; color: #64748b;">${mat.unit}</td>
                    <td style="padding: 0.6rem 0.5rem; text-align: center;">
                        <button onclick="editRawMaterial(${mat.id}, '${mat.name}', ${mat.stock}, '${mat.unit}')" style="background: #3498db; border: none; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.75rem;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error('Error loading raw materials list:', err));
}

function editRawMaterial(id, name, stock, unit) {
    document.getElementById('material-id').value = id;
    document.getElementById('material-name').value = name;
    document.getElementById('material-stock').value = stock;
    document.getElementById('material-unit').value = unit;
    
    document.getElementById('material-btn-text').innerText = 'Update';
    document.getElementById('btn-cancel-edit-material').style.display = 'inline-block';
}

function resetRawMaterialForm() {
    document.getElementById('raw-material-form').reset();
    document.getElementById('material-id').value = '';
    document.getElementById('material-btn-text').innerText = 'Simpan';
    document.getElementById('btn-cancel-edit-material').style.display = 'none';
}

function submitRawMaterial(event) {
    event.preventDefault();
    const id = document.getElementById('material-id').value;
    const name = document.getElementById('material-name').value.trim();
    const stock = parseFloat(document.getElementById('material-stock').value);
    const unit = document.getElementById('material-unit').value.trim();
    
    if (!name || isNaN(stock) || stock < 0 || !unit) {
        alert('Mohon lengkapi formulir bahan baku dengan nilai yang valid.');
        return;
    }
    
    fetch(getApiUrl('api.php'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'update_raw_material',
            id: id ? parseInt(id) : null,
            name: name,
            stock: stock,
            unit: unit
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(id ? 'Bahan baku berhasil di-update!' : 'Bahan baku berhasil ditambahkan!');
            resetRawMaterialForm();
            loadRawMaterialsList();
        } else {
            alert('Gagal menyimpan bahan baku.');
        }
    })
    .catch(err => console.error('Error saving raw material:', err));
}
