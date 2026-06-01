let cart = [];
let currentItem = null;

// Sticky Nav Scroll
function scrollToCategory(categoryId, element) {
    const el = document.getElementById(categoryId + '-title');
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }

    // Update active class
    const tabs = document.querySelectorAll('.category-nav li');
    tabs.forEach(tab => tab.classList.remove('active'));
    element.classList.add('active');
}

// Bottom Sheet Logic
function openItemModal(id, title, price, desc, imgSrc) {
    // Map 'title' from index.php parameter to standard 'name'
    currentItem = { id, name: title, price, qty: 1 };

    document.getElementById('sheet-title').innerText = title;
    document.getElementById('sheet-desc').innerText = desc;
    document.getElementById('sheet-price').innerText = formatRupiah(price);
    document.getElementById('sheet-img').src = imgSrc;
    document.getElementById('sheet-qty').innerText = "1";
    document.getElementById('item-notes').value = ""; // reset notes

    document.getElementById('item-overlay').classList.add('active');
    document.getElementById('item-sheet').classList.add('open');
}

function closeItemModal() {
    document.getElementById('item-overlay').classList.remove('active');
    document.getElementById('item-sheet').classList.remove('open');
    currentItem = null;
}

function adjustSheetQty(change) {
    if (!currentItem) return;
    let newQty = currentItem.qty + change;
    if (newQty < 1) newQty = 1;
    currentItem.qty = newQty;
    document.getElementById('sheet-qty').innerText = newQty;
}

function confirmAddItem() {
    if (!currentItem) return;

    const notes = document.getElementById('item-notes').value.trim();

    // Check if identical item with identical notes exists in cart
    const existingIndex = cart.findIndex(item => item.id === currentItem.id && item.notes === notes);

    if (existingIndex > -1) {
        cart[existingIndex].qty += currentItem.qty;
    } else {
        cart.push({
            id: currentItem.id,
            name: currentItem.name,
            price: currentItem.price,
            qty: currentItem.qty,
            notes: notes
        });
    }

    closeItemModal();
    updateCartUI();

    // Premium micro-animation: bounce the floating cart when a new item is added
    const floatCart = document.getElementById('floating-cart');
    if (floatCart) {
        floatCart.style.transform = 'scale(1.15)';
        floatCart.style.transition = 'transform 0.15s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        setTimeout(() => {
            floatCart.style.transform = 'scale(1)';
        }, 180);
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartUI();
}

// Cart UI & Logic
function updateCartUI() {
    // Update Floating Cart
    const floatingCart = document.getElementById('floating-cart');
    const floatCount = document.getElementById('float-cart-count');
    const floatTotal = document.getElementById('float-cart-total');

    let totalItems = 0;
    let totalPrice = 0;

    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = '';

    if (cart.length === 0) {
        floatingCart.style.display = 'none';
        cartItemsContainer.innerHTML = '<p style="text-align:center; margin-top:2rem; color:#888;">Keranjang kosong.</p>';
    } else {
        floatingCart.style.display = 'flex';

        cart.forEach((item, index) => {
            totalItems += item.qty;
            totalPrice += (item.price * item.qty);

            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `
                <div class="cart-item-header">
                    <span class="cart-item-title">${item.qty}x ${item.name}</span>
                    <span class="cart-item-price">${formatRupiah(item.price * item.qty)}</span>
                </div>
                ${item.notes ? '<div class="cart-item-notes">Catatan: ' + item.notes + '</div>' : ''}
                <div class="cart-item-footer">
                    <span class="cart-item-remove" onclick="removeFromCart(${index})">Hapus</span>
                </div>
            `;
            cartItemsContainer.appendChild(div);
        });
    }

    floatCount.innerText = totalItems;
    floatTotal.innerText = formatRupiah(totalPrice);
    document.getElementById('cart-total-price').innerText = formatRupiah(totalPrice);
}

function toggleCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    if (modal.classList.contains('open')) {
        modal.classList.remove('open');
    } else {
        modal.classList.add('open');
    }
}

let selectedPaymentMethod = 'Cash';
let qrisCountdownInterval = null;

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.getElementById('pay-cashier').classList.remove('active');
    document.getElementById('pay-qris').classList.remove('active');
    
    if (method === 'Cash') {
        document.getElementById('pay-cashier').classList.add('active');
    } else {
        document.getElementById('pay-qris').classList.add('active');
    }
}

function closeQrisModal() {
    document.getElementById('qris-overlay').classList.remove('active');
    if (qrisCountdownInterval) {
        clearInterval(qrisCountdownInterval);
    }
}

function startQrisCountdown() {
    let timeLeft = 300; // 5 minutes
    const countdownEl = document.getElementById('qris-countdown');
    
    if (qrisCountdownInterval) {
        clearInterval(qrisCountdownInterval);
    }
    
    qrisCountdownInterval = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(qrisCountdownInterval);
            countdownEl.innerText = "00:00";
            alert("Batas waktu pembayaran QRIS telah habis. Silakan coba lagi.");
            closeQrisModal();
        } else {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownEl.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
}

function verifyQrisPayment() {
    const btn = document.querySelector('.qris-verify-btn');
    const spinner = document.getElementById('qris-checking-spinner');
    
    btn.style.display = 'none';
    spinner.style.display = 'block';
    
    // Simulate beautiful 2 second verification
    setTimeout(() => {
        btn.style.display = 'flex';
        spinner.style.display = 'none';
        
        // Execute actual order creation
        executeOrderCreation();
    }, 2000);
}

function executeOrderCreation() {
    let customerName = document.getElementById('customer-name').value.trim();
    if (!customerName) {
        customerName = "Pelanggan (Meja 78)";
    } else {
        customerName = customerName + " (Meja 78)";
    }

    let total = 0;
    cart.forEach(item => total += item.price * item.qty);

    const orderData = {
        customer_name: customerName,
        items: cart,
        total: total,
        payment_method: 'QRIS',
        order_type: 'Dine In'
    };
    
    submitOrder(orderData);
}

function checkout() {
    if (cart.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    let customerName = document.getElementById('customer-name').value.trim();
    if (!customerName) {
        customerName = "Pelanggan (Meja 78)";
    } else {
        customerName = customerName + " (Meja 78)";
    }

    let total = 0;
    cart.forEach(item => total += item.price * item.qty);

    if (selectedPaymentMethod === 'QRIS') {
        // Open QRIS Modal first
        document.getElementById('qris-total-price').innerText = formatRupiah(total);
        document.getElementById('qris-overlay').classList.add('active');
        startQrisCountdown();
    } else {
        // Direct cash order
        const orderData = {
            customer_name: customerName,
            items: cart,
            total: total,
            payment_method: 'Cash',
            order_type: 'Dine In'
        };
        submitOrder(orderData);
    }
}

function submitOrder(orderData) {
    const checkoutBtn = document.querySelector('.checkout-btn');
    const originalText = checkoutBtn.innerText;
    checkoutBtn.disabled = true;
    checkoutBtn.innerText = 'Mengirim Pesanan...';

    const apiUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL + 'api.php' : 'api.php';

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pesanan berhasil dibuat! Mohon tunggu pesanan Anda diantar ke Meja 78.');
                cart = [];
                updateCartUI();
                toggleCheckoutModal();
                closeQrisModal();
                document.getElementById('customer-name').value = '';
            } else {
                alert('Gagal membuat pesanan.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi.');
        })
        .finally(() => {
            checkoutBtn.disabled = false;
            checkoutBtn.innerText = originalText;
        });
}

function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}
