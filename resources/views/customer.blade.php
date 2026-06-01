@extends('layouts.app')

@section('title', 'Aksara Coffee Shop')

@section('content')
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
            <img src="{{ asset('images/hero_coffee.png') }}" alt="Aksara Coffee">
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
            @foreach ($coffee as $product)
                <div class="menu-card">
                    <img src="{{ asset('images/' . $product->img) }}" alt="{{ $product->name }}" class="menu-img">
                    <div class="menu-card-content">
                        <div class="menu-info">
                            <h3>{{ $product->name }}</h3>
                            <p>{{ $product->description }}</p>
                        </div>
                        <div class="menu-action">
                            <div class="menu-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <button class="add-btn" onclick="openItemModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ addslashes($product->description) }}', '{{ asset('images/' . $product->img) }}')">Tambah</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Non-Coffee -->
        <h2 class="category-title" id="non-coffee-menu-title">Non-Coffee</h2>
        <div class="menu-grid" id="non-coffee-menu">
            @foreach ($nonCoffee as $product)
                <div class="menu-card">
                    <img src="{{ asset('images/' . $product->img) }}" alt="{{ $product->name }}" class="menu-img">
                    <div class="menu-card-content">
                        <div class="menu-info">
                            <h3>{{ $product->name }}</h3>
                            <p>{{ $product->description }}</p>
                        </div>
                        <div class="menu-action">
                            <div class="menu-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <button class="add-btn" onclick="openItemModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ addslashes($product->description) }}', '{{ asset('images/' . $product->img) }}')">Tambah</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Snacks -->
        <h2 class="category-title" id="snack-menu-title">Snacks & Pastry</h2>
        <div class="menu-grid" id="snack-menu">
            @foreach ($snacks as $product)
                <div class="menu-card">
                    <img src="{{ asset('images/' . $product->img) }}" alt="{{ $product->name }}" class="menu-img">
                    <div class="menu-card-content">
                        <div class="menu-info">
                            <h3>{{ $product->name }}</h3>
                            <p>{{ $product->description }}</p>
                        </div>
                        <div class="menu-action">
                            <div class="menu-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <button class="add-btn" onclick="openItemModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ addslashes($product->description) }}', '{{ asset('images/' . $product->img) }}')">Tambah</button>
                        </div>
                    </div>
                </div>
            @endforeach
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
            <input type="text" id="customer-name" class="customer-name-input" placeholder="Nama Anda (Opsional)" required>
            <button class="checkout-btn" onclick="checkout()">Pesan Sekarang</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
