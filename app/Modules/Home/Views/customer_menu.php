<?php
$descriptions = [
    1 => 'Paduan espresso, susu krim, dan gula aren pilihan.',
    2 => 'Matcha premium Jepang dengan layer espresso.',
    3 => 'Cappuccino dengan taburan bubuk cokelat.',
    4 => 'Susu dan matcha murni yang creamy.',
    5 => 'Mocktail segar dengan perasan apel hijau dan mint.',
    6 => 'Cokelat pekat yang menenangkan.',
    7 => 'Brownies fudge dengan topping lumeran matcha.',
    8 => 'Croissant hangat yang renyah di luar, lembut di dalam.',
    9 => 'Sosis, kentang goreng, dan nugget.'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Aksara Coffee Shop</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Aksara.</div>
        <div class="table-info">Dine In - Meja 78</div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Selamat Datang di Aksara</h1>
            <p>Rasakan kopi dengan sentuhan cerita di setiap seduhannya.</p>
        </div>
        <div class="hero-image">
            <img src="hero_coffee.png" alt="Aksara Coffee">
        </div>
    </header>

    <!-- Sticky Category Nav -->
    <div class="category-nav-wrapper">
        <ul class="category-nav">
            <li class="active" onclick="scrollToCategory('coffee-menu', this)">Signature Coffee</li>
            <li onclick="scrollToCategory('non-coffee-menu', this)">Non-Coffee</li>
            <li onclick="scrollToCategory('snack-menu', this)">Snacks & Pastry</li>
        </ul>
    </div>

    <main class="menu-section">
        <!-- Signature Coffee -->
        <h2 class="category-title" id="coffee-menu-title">Signature Coffee</h2>
        <div class="menu-grid" id="coffee-menu">
            <?php if (empty($coffee)): ?>
                <p style="grid-column: 1/-1; text-align: center; color: #888;">Menu tidak tersedia.</p>
            <?php else: ?>
                <?php foreach ($coffee as $item): ?>
                    <?php $desc = $descriptions[$item['id']] ?? 'Sentuhan cita rasa khas Aksara Coffee.'; ?>
                    <div class="menu-card">
                        <img src="<?= esc($item['img']) ?>" alt="<?= esc($item['name']) ?>" class="menu-img">
                        <div class="menu-card-content">
                            <div class="menu-info">
                                <h3><?= esc($item['name']) ?></h3>
                                <p><?= esc($desc) ?></p>
                            </div>
                            <div class="menu-action">
                                <div class="menu-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                <button class="add-btn" onclick="openItemModal(<?= $item['id'] ?>, '<?= esc($item['name'], 'js') ?>', <?= $item['price'] ?>, '<?= esc($desc, 'js') ?>', '<?= esc($item['img'], 'js') ?>')">Tambah</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Non-Coffee -->
        <h2 class="category-title" id="non-coffee-menu-title">Non-Coffee</h2>
        <div class="menu-grid" id="non-coffee-menu">
            <?php if (empty($nonCoffee)): ?>
                <p style="grid-column: 1/-1; text-align: center; color: #888;">Menu tidak tersedia.</p>
            <?php else: ?>
                <?php foreach ($nonCoffee as $item): ?>
                    <?php $desc = $descriptions[$item['id']] ?? 'Sentuhan cita rasa khas Aksara Coffee.'; ?>
                    <div class="menu-card">
                        <img src="<?= esc($item['img']) ?>" alt="<?= esc($item['name']) ?>" class="menu-img">
                        <div class="menu-card-content">
                            <div class="menu-info">
                                <h3><?= esc($item['name']) ?></h3>
                                <p><?= esc($desc) ?></p>
                            </div>
                            <div class="menu-action">
                                <div class="menu-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                <button class="add-btn" onclick="openItemModal(<?= $item['id'] ?>, '<?= esc($item['name'], 'js') ?>', <?= $item['price'] ?>, '<?= esc($desc, 'js') ?>', '<?= esc($item['img'], 'js') ?>')">Tambah</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Snacks -->
        <h2 class="category-title" id="snack-menu-title">Snacks & Pastry</h2>
        <div class="menu-grid" id="snack-menu">
            <?php if (empty($snack)): ?>
                <p style="grid-column: 1/-1; text-align: center; color: #888;">Menu tidak tersedia.</p>
            <?php else: ?>
                <?php foreach ($snack as $item): ?>
                    <?php $desc = $descriptions[$item['id']] ?? 'Sentuhan cita rasa khas Aksara Coffee.'; ?>
                    <div class="menu-card">
                        <img src="<?= esc($item['img']) ?>" alt="<?= esc($item['name']) ?>" class="menu-img">
                        <div class="menu-card-content">
                            <div class="menu-info">
                                <h3><?= esc($item['name']) ?></h3>
                                <p><?= esc($desc) ?></p>
                            </div>
                            <div class="menu-action">
                                <div class="menu-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                                <button class="add-btn" onclick="openItemModal(<?= $item['id'] ?>, '<?= esc($item['name'], 'js') ?>', <?= $item['price'] ?>, '<?= esc($desc, 'js') ?>', '<?= esc($item['img'], 'js') ?>')">Tambah</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Bottom Margin for Floating Cart -->
    <div style="height: 100px;"></div>

    <!-- Floating Cart Bar -->
    <div class="floating-cart" id="floating-cart" onclick="toggleCheckoutModal()" style="display: none;">
        <div class="cart-summary-left">
            <i class="fas fa-shopping-bag"></i>
            <span class="cart-total-items" id="float-cart-count">0</span> Item
        </div>
        <div class="cart-summary-right">
            <span id="float-cart-total">Rp 0</span>
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>

    <!-- Bottom Sheet Item Detail -->
    <div class="bottom-sheet-overlay" id="item-overlay" onclick="closeItemModal()"></div>
    <div class="bottom-sheet" id="item-sheet">
        <div class="sheet-drag-handle"></div>
        <div class="sheet-content">
            <img src="" id="sheet-img" alt="Item Image" class="sheet-item-img">
            <h3 id="sheet-title">Nama Item</h3>
            <p id="sheet-desc" class="sheet-desc">Deskripsi item</p>
            <div class="sheet-price" id="sheet-price">Rp 0</div>
            
            <div class="sheet-notes-wrapper">
                <label for="item-notes">Catatan Khusus (Opsional)</label>
                <textarea id="item-notes" placeholder="Contoh: Sedikit es, gulanya dikurangi..."></textarea>
            </div>
        </div>
        <div class="sheet-footer">
            <div class="qty-control">
                <button class="qty-btn" onclick="adjustSheetQty(-1)"><i class="fas fa-minus"></i></button>
                <span id="sheet-qty">1</span>
                <button class="qty-btn" onclick="adjustSheetQty(1)"><i class="fas fa-plus"></i></button>
            </div>
            <button class="sheet-add-btn" onclick="confirmAddItem()">Tambah ke Pesanan</button>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="checkout-modal" id="checkout-modal">
        <div class="checkout-header">
            <i class="fas fa-arrow-left close-checkout" onclick="toggleCheckoutModal()"></i>
            <h3>Keranjang Pesanan</h3>
        </div>
        <div class="checkout-body" id="cart-items">
            <!-- Items injected by JS -->
            <p style="text-align:center; margin-top:2rem; color:#888;">Keranjang kosong.</p>
        </div>
        <div class="checkout-footer">
            <div class="checkout-total-row">
                <span>Total</span>
                <span id="cart-total-price">Rp 0</span>
            </div>

            <div class="payment-method-selector">
                <label class="payment-label">Metode Pembayaran</label>
                <div class="payment-options">
                    <div class="payment-option active" id="pay-cashier" onclick="selectPaymentMethod('Cash')">
                        <i class="fas fa-cash-register"></i>
                        <div class="option-info">
                            <span class="option-title">Ke Kasir</span>
                            <span class="option-desc">Bayar cash/kartu</span>
                        </div>
                    </div>
                    <div class="payment-option" id="pay-qris" onclick="selectPaymentMethod('QRIS')">
                        <i class="fas fa-qrcode"></i>
                        <div class="option-info">
                            <span class="option-title">QRIS</span>
                            <span class="option-desc">Bayar QR instan</span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="text" id="customer-name" class="customer-name-input" placeholder="Nama Anda (Opsional)" required>
            <button class="checkout-btn" onclick="checkout()">Pesan Sekarang</button>
        </div>
    </div>

    <!-- QRIS Overlay Modal -->
    <div class="qris-overlay" id="qris-overlay">
        <div class="qris-modal">
            <div class="qris-header">
                <div class="qris-brand">
                    <span class="qris-logo-text">QRIS</span>
                    <span class="qris-merchant">Aksara Coffee</span>
                </div>
                <i class="fas fa-times close-qris" onclick="closeQrisModal()"></i>
            </div>
            <div class="qris-body">
                <p class="qris-instruction">Silakan pindai kode QR di bawah ini menggunakan aplikasi e-wallet Anda (Gopay, OVO, Dana, LinkAja, BCA, dll.)</p>
                <div class="qris-price" id="qris-total-price">Rp 0</div>
                
                <div class="qris-qr-container">
                    <div class="qris-qr-wrapper">
                        <div class="scanner-bar"></div>
                        <svg class="qr-svg-mockup" viewBox="0 0 100 100">
                            <rect x="10" y="10" width="20" height="20" fill="none" stroke="#000" stroke-width="4"/>
                            <rect x="14" y="14" width="12" height="12" fill="#000"/>
                            <rect x="70" y="10" width="20" height="20" fill="none" stroke="#000" stroke-width="4"/>
                            <rect x="74" y="14" width="12" height="12" fill="#000"/>
                            <rect x="10" y="70" width="20" height="20" fill="none" stroke="#000" stroke-width="4"/>
                            <rect x="14" y="74" width="12" height="12" fill="#000"/>
                            <rect x="35" y="10" width="8" height="8" fill="#000"/>
                            <rect x="48" y="15" width="12" height="4" fill="#000"/>
                            <rect x="45" y="25" width="8" height="8" fill="#000"/>
                            <rect x="35" y="35" width="12" height="8" fill="#000"/>
                            <rect x="15" y="35" width="15" height="15" fill="none" stroke="#000" stroke-width="2"/>
                            <rect x="20" y="40" width="5" height="5" fill="#000"/>
                            <rect x="10" y="55" width="15" height="8" fill="#000"/>
                            <rect x="70" y="35" width="8" height="20" fill="#000"/>
                            <rect x="83" y="35" width="8" height="8" fill="#000"/>
                            <rect x="80" y="48" width="10" height="10" fill="#000"/>
                            <rect x="35" y="50" width="10" height="10" fill="#000"/>
                            <rect x="50" y="50" width="15" height="8" fill="#000"/>
                            <rect x="40" y="70" width="15" height="15" fill="none" stroke="#000" stroke-width="3"/>
                            <rect x="45" y="75" width="5" height="5" fill="#000"/>
                            <rect x="60" y="70" width="5" height="12" fill="#000"/>
                            <rect x="70" y="70" width="20" height="8" fill="#000"/>
                            <rect x="80" y="83" width="10" height="8" fill="#000"/>
                            <circle cx="50" cy="50" r="6" fill="#005813"/>
                            <text x="50" y="52" font-size="6" font-weight="900" fill="#fff" text-anchor="middle" font-family="sans-serif">A</text>
                        </svg>
                    </div>
                </div>
                
                <div class="qris-timer">
                    Batas Waktu Pembayaran: <span id="qris-countdown">05:00</span>
                </div>
            </div>
            <div class="qris-footer">
                <button class="qris-btn qris-verify-btn" onclick="verifyQrisPayment()">
                    <i class="fas fa-check-circle"></i> Saya Sudah Bayar
                </button>
                <div class="qris-status-checking" id="qris-checking-spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Memverifikasi Transaksi...
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
