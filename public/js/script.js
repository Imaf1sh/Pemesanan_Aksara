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
    currentItem = { id, title, price, qty: 1 };

    document.getElementById('sheet-title').innerText = title;
    document.getElementById('sheet-desc').innerText = desc;
    document.getElementById('sheet-price').innerText = formatRupiah(price);
    document.getElementById('sheet-img').src = imgSrc;
    document.getElementById('sheet-qty').innerText = "1";
    document.getElementById('item-notes').value = ""; // reset notes

    document.getElementById('item-overlay').classList.add('active');
    document.getElementById('item-sheet').classList.add('open');
}

// Global CSRF Token header for Laravel request
const getCsrfToken = () => {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
};

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

    const notes = document.getElementById('item-notes').value;

    // Cek jika item yang sama persis dengan notes yang sama sudah ada di keranjang
    const existingIndex = cart.findIndex(item => item.id === currentItem.id && item.notes === notes);

    if (existingIndex > -1) {
        cart[existingIndex].qty += currentItem.qty;
    } else {
        cart.push({
            id: currentItem.id,
            title: currentItem.title,
            price: currentItem.price,
            qty: currentItem.qty,
            notes: notes
        });
    }

    closeItemModal();
    updateCartUI();
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
                    <span class="cart-item-title">${item.qty}x ${item.title}</span>
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

function checkout() {
    if (cart.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    let customerName = document.getElementById('customer-name').value;
    if (!customerName) {
        customerName = "Pelanggan (Meja 78)";
    }

    let total = 0;
    cart.forEach(item => total += item.price * item.qty);

    const orderData = {
        customer: customerName,
        items: cart,
        total: total,
        table: '78'
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
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' || data.success) {
                alert('Pesanan berhasil dibuat! Mohon tunggu pesanan Anda diantar ke Meja 78.');
                cart = [];
                updateCartUI();
                toggleCheckoutModal();
                document.getElementById('customer-name').value = '';
            } else {
                alert('Gagal membuat pesanan: ' + (data.message || 'Error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan.');
        });
}

function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}
