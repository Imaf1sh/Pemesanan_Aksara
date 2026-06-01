let products = [];
let cart = [];
let currentCategory = 'all';
let taxRate = 0.11;
let currentItem = null;
let isShiftOpen = false;

// Global CSRF Token helper for Laravel
const getCsrfToken = () => {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
};

// Initialize
window.onload = () => {
    checkShiftStatus();
};

function checkShiftStatus() {
    fetch('/api/shift-status')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'open') {
                isShiftOpen = true;
                loadProducts();
            } else {
                document.getElementById('pos-shift-modal').classList.add('active');
            }
        });
}

function openShift() {
    const initialCash = document.getElementById('shift-initial-cash').value;
    if (!initialCash) {
        alert('Masukkan modal awal kasir.');
        return;
    }
    
    fetch('/api/open-shift', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ initial_cash: parseFloat(initialCash) })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('pos-shift-modal').classList.remove('active');
            isShiftOpen = true;
            loadProducts();
        } else {
            alert('Gagal membuka shift: ' + (data.message || 'Error'));
        }
    });
}

function loadProducts() {
    fetch('/api/products')
        .then(res => res.json())
        .then(data => {
            products = data;
            renderProducts();
        });
}

// Render Products
function renderProducts() {
    const grid = document.getElementById('pos-product-grid');
    grid.innerHTML = '';

    const filtered = currentCategory === 'all' 
        ? products 
        : products.filter(p => p.category === currentCategory);

    filtered.forEach(product => {
        const isOutOfStock = product.stock <= 0;
        const card = document.createElement('div');
        card.className = `pos-product-card ${isOutOfStock ? 'out-of-stock' : ''}`;
        if (!isOutOfStock) {
            card.onclick = () => openItemModal(product);
        }
        
        card.innerHTML = `
            <div class="stock-badge">Stok: ${product.stock}</div>
            <img src="/images/${product.img}" alt="${product.name}">
            <div class="pos-product-info">
                <h4>${product.name}</h4>
                <div class="pos-product-price">${formatRupiah(product.price)}</div>
            </div>
        `;
        grid.appendChild(card);
    });
}

function filterCategory(cat) {
    currentCategory = cat;
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.classList.remove('active');
        if(btn.innerText.toLowerCase().includes(cat.replace('-', '')) || (cat==='all' && btn.innerText==='Semua')) {
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
    currentItem.notes = document.getElementById('modal-item-notes').value;
    
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
    
    cartContainer.innerHTML = '';
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<div class="empty-cart">Keranjang masih kosong</div>';
        checkoutBtn.disabled = true;
        updateSummary(0);
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
                <div class="cart-item-notes">${item.notes}</div>
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
    if(confirm('Kosongkan keranjang?')) {
        cart = [];
        renderCart();
    }
}

let currentTotal = 0;
function updateSummary(subtotal) {
    const tax = subtotal * taxRate;
    currentTotal = subtotal + tax;
    
    document.getElementById('pos-subtotal').innerText = formatRupiah(subtotal);
    document.getElementById('pos-tax').innerText = formatRupiah(tax);
    document.getElementById('pos-total').innerText = formatRupiah(currentTotal);
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
    document.getElementById('pos-customer-name').value = '';
    
    setPaymentMethod('Cash', document.querySelector('.pay-method-btn.active') || document.querySelector('.pay-method-btn'));
    calculateChange();
}

function closePaymentModal() {
    document.getElementById('pos-payment-modal').classList.remove('active');
}

function setOrderType(type, element) {
    orderType = type;
    document.querySelectorAll('.order-type-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
}

function setPaymentMethod(method, element) {
    paymentMethod = method;
    document.querySelectorAll('.pay-method-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    
    const cashSection = document.getElementById('cash-input-section');
    if (method === 'Cash') {
        cashSection.style.display = 'block';
    } else {
        cashSection.style.display = 'none';
        document.getElementById('btn-process-payment').disabled = false;
    }
    calculateChange();
}

function setQuickCash(amount) {
    if (amount === 'exact') {
        document.getElementById('cash-received').value = currentTotal;
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
    
    if (change < 0) {
        changeEl.innerText = 'Kurang: ' + formatRupiah(Math.abs(change));
        changeEl.style.color = '#e74c3c';
        processBtn.disabled = true;
    } else {
        changeEl.innerText = formatRupiah(change);
        changeEl.style.color = '#10b981';
        processBtn.disabled = false;
    }
}

function processPayment() {
    const processBtn = document.getElementById('btn-process-payment');
    processBtn.innerText = 'Memproses...';
    processBtn.disabled = true;

    const customerName = document.getElementById('pos-customer-name').value || 'Guest (' + orderType + ')';

    const orderData = {
        customer_name: customerName,
        items: cart,
        total: currentTotal,
        payment_method: paymentMethod,
        order_type: orderType
    };

    fetch('/api/orders', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(orderData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.status === 'success') {
            closePaymentModal();
            generateReceipt(orderData, data.order.id);
            document.getElementById('pos-receipt-modal').classList.add('active');
            
            cart = [];
            renderCart();
            loadProducts(); // Refresh stock
        } else {
            alert('Gagal memproses pesanan: ' + (data.message || 'Error'));
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
    const received = parseFloat(document.getElementById('cash-received').value) || order.total;
    const change = received - order.total;
    const date = new Date().toLocaleString('id-ID');
    
    let itemsHtml = '';
    let subtotal = 0;
    order.items.forEach(item => {
        let itemName = item.name ?? item.title ?? 'Product';
        let itemTotal = item.qty * item.price;
        subtotal += itemTotal;
        itemsHtml += `
            <tr>
                <td>${itemName} x${item.qty}</td>
                <td class="right">${formatRupiah(itemTotal)}</td>
            </tr>
        `;
    });

    const tax = subtotal * taxRate;

    const html = `
        <div class="receipt-header">
            <h3>AKSARA COFFEE</h3>
            <p>Jl. Cerita Kopi No. 1</p>
            <p>${date}</p>
        </div>
        <div class="receipt-divider"></div>
        <p>Order ID: ${orderId}<br>Customer: ${order.customer_name}<br>Type: ${order.order_type}</p>
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
            <tr>
                <td>PPN 11%</td>
                <td class="right">${formatRupiah(tax)}</td>
            </tr>
            <tr>
                <th>Total</th>
                <th class="right">${formatRupiah(order.total)}</th>
            </tr>
            <tr>
                <td>${order.payment_method === 'Cash' ? 'Tunai' : 'QRIS'}</td>
                <td class="right">${formatRupiah(received)}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="right">${formatRupiah(Math.max(0, change))}</td>
            </tr>
        </table>
        <div class="receipt-divider"></div>
        <div class="receipt-footer">
            <p>Terima kasih atas kunjungannya!</p>
            <p>IG: @aksara.coffee</p>
        </div>
    `;
    document.getElementById('receipt-content').innerHTML = html;
}

function closeReceiptModal() {
    document.getElementById('pos-receipt-modal').classList.remove('active');
}

function printReceipt() {
    alert('Mengirim data ke printer thermal bluetooth...');
}
