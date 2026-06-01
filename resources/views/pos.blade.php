@extends('layouts.app')

@section('title', 'Aksara POS System')

@section('body-class', 'pos-body')

@section('content')
    <nav class="navbar pos-navbar">
        <div class="logo">Aksara POS</div>
        <div class="nav-links">
            <a href="{{ route('pos') }}" class="active">Point of Sales</a>
            <a href="{{ route('kasir') }}">Pesanan Masuk</a>
            <a href="{{ route('customer') }}" target="_blank">Menu Customer</a>
        </div>
    </nav>

    <div class="pos-layout">
        <!-- Kiri: Katalog Produk -->
        <div class="pos-catalog">
            <div class="category-tabs">
                <button class="cat-btn active" onclick="filterCategory('all')">Semua</button>
                <button class="cat-btn" onclick="filterCategory('coffee')">Coffee</button>
                <button class="cat-btn" onclick="filterCategory('non-coffee')">Non-Coffee</button>
                <button class="cat-btn" onclick="filterCategory('snack')">Snacks</button>
            </div>

            <div class="pos-product-grid" id="pos-product-grid">
                <!-- Produk di-render via JS -->
            </div>
        </div>

        <!-- Kanan: Keranjang -->
        <div class="pos-cart">
            <div class="cart-header">
                <h3>Keranjang</h3>
                <button class="clear-cart-btn" onclick="clearCart()"><i class="fas fa-trash"></i></button>
            </div>

            <div class="cart-items" id="pos-cart-items">
                <!-- Item keranjang di-render via JS -->
                <div class="empty-cart">Keranjang masih kosong</div>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="pos-subtotal">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span>PPN (11%)</span>
                    <span id="pos-tax">Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="pos-total">Rp 0</span>
                </div>
                
                <button class="btn-checkout" onclick="openPaymentModal()" id="btn-checkout" disabled>
                    Lanjut Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Item Detail -->
    <div class="pos-modal-overlay" id="pos-item-modal">
        <div class="pos-modal">
            <div class="modal-header">
                <h3 id="modal-item-name">Nama Item</h3>
                <i class="fas fa-times close-modal" onclick="closeItemModal()"></i>
            </div>
            <div class="modal-body">
                <div class="modal-price" id="modal-item-price">Rp 0</div>
                <div class="notes-wrapper">
                    <label>Catatan Dapur (Opsional)</label>
                    <textarea id="modal-item-notes" placeholder="Contoh: Es sedikit, gula normal..."></textarea>
                </div>
                <div class="qty-control-modal">
                    <button onclick="adjustModalQty(-1)"><i class="fas fa-minus"></i></button>
                    <span id="modal-item-qty">1</span>
                    <button onclick="adjustModalQty(1)"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" onclick="confirmAddItem()">Simpan ke Keranjang</button>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div class="pos-modal-overlay" id="pos-payment-modal">
        <div class="pos-modal payment-modal">
            <div class="modal-header">
                <h3>Pembayaran</h3>
                <i class="fas fa-times close-modal" onclick="closePaymentModal()"></i>
            </div>
            <div class="modal-body">
                
                <div class="payment-details">
                    <label>Tipe Pesanan</label>
                    <div class="order-types">
                        <button class="order-type-btn active" onclick="setOrderType('Dine In', this)">Dine In</button>
                        <button class="order-type-btn" onclick="setOrderType('Takeaway', this)">Takeaway</button>
                        <button class="order-type-btn" onclick="setOrderType('Delivery', this)">Delivery</button>
                    </div>

                    <label style="margin-top: 1rem;">Nama Pelanggan / Meja</label>
                    <input type="text" id="pos-customer-name" placeholder="Misal: Meja 5 / Budi">
                </div>

                <div class="payment-total-box">
                    <span>Total Tagihan</span>
                    <h2 id="payment-total-amount">Rp 0</h2>
                </div>

                <label>Metode Pembayaran</label>
                <div class="payment-methods">
                    <button class="pay-method-btn active" onclick="setPaymentMethod('Cash', this)"><i class="fas fa-money-bill"></i> Tunai (Cash)</button>
                    <button class="pay-method-btn" onclick="setPaymentMethod('QRIS', this)"><i class="fas fa-qrcode"></i> QRIS</button>
                </div>

                <div id="cash-input-section">
                    <label>Uang Diterima</label>
                    <input type="number" id="cash-received" placeholder="0" oninput="calculateChange()">
                    
                    <div class="quick-cash">
                        <button onclick="setQuickCash(20000)">20k</button>
                        <button onclick="setQuickCash(50000)">50k</button>
                        <button onclick="setQuickCash(100000)">100k</button>
                        <button onclick="setQuickCash('exact')">Uang Pas</button>
                    </div>

                    <div class="change-box">
                        <span>Kembalian:</span>
                        <span id="cash-change" class="change-amount">Rp 0</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn-primary btn-process" onclick="processPayment()" id="btn-process-payment" disabled>Proses Pembayaran</button>
            </div>
        </div>
    </div>

    <!-- Modal Shift -->
    <div class="pos-modal-overlay" id="pos-shift-modal">
        <div class="pos-modal">
            <div class="modal-header">
                <h3>Buka Shift Kasir</h3>
            </div>
            <div class="modal-body">
                <div class="shift-info-box">
                    <i class="fas fa-info-circle"></i> Anda harus membuka shift sebelum mulai bertransaksi.
                </div>
                <label>Modal Awal (Kasir)</label>
                <input type="number" id="shift-initial-cash" placeholder="Contoh: 100000" style="width:100%; padding:0.8rem; margin-top:0.5rem; border-radius:8px; border:1px solid #ddd;">
            </div>
            <div class="modal-footer">
                <button class="btn-primary" onclick="openShift()">Buka Shift Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Modal Receipt -->
    <div class="pos-modal-overlay" id="pos-receipt-modal">
        <div class="pos-modal">
            <div class="modal-header">
                <h3>Cetak Struk</h3>
                <i class="fas fa-times close-modal" onclick="closeReceiptModal()"></i>
            </div>
            <div class="modal-body">
                <div class="receipt-paper" id="receipt-content">
                    <!-- Dinamis via JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" onclick="printReceipt()"><i class="fas fa-print"></i> Cetak Struk (Simulasi)</button>
                <button class="btn-primary" style="background:#f1f5f9; color:#0f172a; margin-top:0.5rem;" onclick="closeReceiptModal()">Tutup</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pos.js') }}"></script>
@endsection
