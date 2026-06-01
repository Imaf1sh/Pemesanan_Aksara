@extends('layouts.app')

@section('title', 'Dashboard Kasir - Aksara')

@section('content')
    <nav class="navbar">
        <div class="logo">Aksara. Kasir</div>
        <div class="nav-links" style="display: flex; gap: 1.5rem;">
            <a href="{{ route('pos') }}" style="color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600;">Point of Sales</a>
            <a href="{{ route('kasir') }}" style="color: white; text-decoration: none; font-weight: 600;">Pesanan Masuk</a>
            <a href="{{ route('customer') }}" target="_blank" style="color: rgba(255,255,255,0.7); text-decoration: none; font-weight: 600;">Menu Customer</a>
        </div>
    </nav>

    <div class="kasir-container">
        <div class="kasir-header">
            <h2>Pesanan Masuk</h2>
            <button onclick="fetchOrders()">Refresh</button>
        </div>

        <div class="orders-grid" id="orders-grid">
            <!-- Orders will be loaded here -->
            <p>Memuat pesanan...</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function fetchOrders() {
            fetch('/api/orders')
            .then(response => response.json())
            .then(orders => {
                const grid = document.getElementById('orders-grid');
                grid.innerHTML = '';

                if (orders.length === 0) {
                    grid.innerHTML = '<p>Belum ada pesanan masuk.</p>';
                    return;
                }

                orders.forEach(order => {
                    const card = document.createElement('div');
                    card.className = `order-card ${order.status === 'completed' ? 'completed' : ''}`;
                    
                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `<li><span>${item.qty}x ${item.name}</span></li>`;
                    });

                    card.innerHTML = `
                        <div class="order-header">
                            <span class="order-id">#${order.id.replace('ord_', '')}</span>
                            <span class="order-time">${new Date(order.timestamp).toLocaleString('id-ID')}</span>
                        </div>
                        <div class="order-customer">
                            ${order.customer_name}
                            <span style="font-size:0.75rem; background:var(--primary-color); color:white; padding:0.2rem 0.5rem; border-radius:12px; margin-left:0.5rem;">${order.order_type || 'Dine In'}</span>
                        </div>
                        <ul class="order-items-list">
                            ${itemsHtml}
                        </ul>
                        <div class="order-total">${formatRupiah(order.total)}</div>
                        
                        ${order.status === 'pending' ? `
                        <div class="status-actions">
                            <button class="btn-complete" onclick="markCompleted('${order.id}')">Tandai Selesai</button>
                        </div>
                        ` : '<div class="completed-badge">Selesai</div>'}
                    `;
                    
                    grid.appendChild(card);
                });
            })
            .catch(err => console.error('Error fetching orders:', err));
        }

        function markCompleted(orderId) {
            if (!confirm('Tandai pesanan ini sebagai selesai?')) return;

            fetch('/api/orders/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: orderId,
                    status: 'completed'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchOrders(); // Refresh the list
                } else {
                    alert('Gagal mengupdate status.');
                }
            })
            .catch(err => console.error(err));
        }

        // Fetch on load
        fetchOrders();

        // Auto refresh every 10 seconds
        setInterval(fetchOrders, 10000);
    </script>
@endsection
