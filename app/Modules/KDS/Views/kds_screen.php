<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display System (KDS) - Aksara</title>
    <!-- Google Fonts: Outfit -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap">
    <!-- Font Awesome for premium icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #005813;
            --primary-light: #00871d;
            --accent-color: #10b981;
            --accent-hover: #059669;
            --bg-color: #0f172a; /* Sleek Dark Theme for Kitchen KDS high-contrast visibility */
            --card-bg: #1e293b;
            --border-color: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-premium: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            
            /* Order Type Colors */
            --color-dinein: #10b981;    /* Emerald Green */
            --color-takeaway: #f59e0b;  /* Amber */
            --color-delivery: #3b82f6;  /* Royal Blue */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top KDS Header Bar */
        .kds-header {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
            flex-shrink: 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .kds-brand {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .kds-logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .kds-logo span {
            color: var(--accent-color);
        }

        .kds-status-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #10b981;
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.9); opacity: 0.6; }
            50% { transform: scale(1.2); opacity: 1; box-shadow: 0 0 12px #10b981; }
            100% { transform: scale(0.9); opacity: 0.6; }
        }

        .kds-clock {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            background: rgba(15, 23, 42, 0.6);
            padding: 0.4rem 1.2rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            letter-spacing: 1px;
            font-variant-numeric: tabular-nums;
        }

        .kds-stats-panel {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-badge {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border-color);
            padding: 0.4rem 1rem;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .stat-badge i {
            color: var(--accent-color);
        }

        .stat-badge.pending i {
            color: #f59e0b;
        }

        .stat-badge .stat-count {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        /* Sound Control Button */
        .sound-toggle-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .sound-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .sound-toggle-btn.muted i {
            color: #ef4444;
        }

        /* KDS Grid Workspace */
        .kds-workspace {
            display: flex;
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        /* Main KDS Grid Container */
        .kds-content-area {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            height: 100%;
        }

        .kds-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            align-content: start;
        }

        /* Order Cards Styling */
        .kds-card {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-premium);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
            position: relative;
            max-height: 480px;
        }

        /* Border highlights based on order type */
        .kds-card.type-dine-in { border-left: 6px solid var(--color-dinein); }
        .kds-card.type-takeaway { border-left: 6px solid var(--color-takeaway); }
        .kds-card.type-delivery { border-left: 6px solid var(--color-delivery); }

        .kds-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        /* Flash animation for new orders */
        .kds-card.new-order-flash {
            animation: card-flash-glow 1.5s ease-out 3;
        }

        @keyframes card-flash-glow {
            0% { box-shadow: 0 0 0px var(--accent-color); }
            50% { box-shadow: 0 0 25px var(--accent-color); border-color: var(--accent-color); }
            100% { box-shadow: 0 0 0px var(--accent-color); }
        }

        /* Card Header */
        .kds-card-header {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--border-color);
            background: rgba(15, 23, 42, 0.25);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-number {
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
        }

        /* Elapsed Timer Badges */
        .order-timer {
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .order-timer.warn {
            background-color: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .order-timer.danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.4);
            animation: pulse-red-border 1.2s infinite;
        }

        @keyframes pulse-red-border {
            0% { box-shadow: 0 0 0px rgba(239, 68, 68, 0.5); }
            50% { box-shadow: 0 0 8px rgba(239, 68, 68, 0.8); }
            100% { box-shadow: 0 0 0px rgba(239, 68, 68, 0.5); }
        }

        /* Card Body */
        .kds-card-body {
            padding: 1.2rem;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .customer-title {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Order Type Badge */
        .type-badge {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .type-badge.dine-in { background-color: rgba(16, 185, 129, 0.12); color: var(--color-dinein); border: 1px solid rgba(16, 185, 129, 0.2); }
        .type-badge.takeaway { background-color: rgba(245, 158, 11, 0.12); color: var(--color-takeaway); border: 1px solid rgba(245, 158, 11, 0.2); }
        .type-badge.delivery { background-color: rgba(59, 130, 246, 0.12); color: var(--color-delivery); border: 1px solid rgba(59, 130, 246, 0.2); }

        /* Payment Status Badges */
        .payment-status-row {
            display: flex;
            margin-top: 0.4rem;
            margin-bottom: 0.2rem;
        }

        .payment-badge {
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0.35rem 0.6rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            width: 100%;
        }

        .payment-badge.lunas {
            background-color: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .payment-badge.belum-bayar {
            background-color: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Items checklist */
        .kds-items-checklist {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            margin-top: 0.2rem;
        }

        .kds-item-row {
            display: flex;
            flex-direction: column;
            background: rgba(15, 23, 42, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            padding: 0.5rem 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }

        .kds-item-row:hover {
            background: rgba(15, 23, 42, 0.4);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .kds-item-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kds-item-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s;
        }

        .kds-item-name span.qty {
            background: rgba(255, 255, 255, 0.1);
            color: var(--accent-color);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .item-checkbox {
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        /* Checklist checked state */
        .kds-item-row.checked {
            opacity: 0.45;
            background: rgba(15, 23, 42, 0.1);
        }

        .kds-item-row.checked .kds-item-name {
            text-decoration: line-through;
            color: var(--text-muted);
        }

        .kds-item-row.checked .item-checkbox {
            color: var(--accent-color);
        }

        /* Kitchen Notes Badge */
        .kds-item-notes {
            margin-top: 0.3rem;
            padding: 0.25rem 0.6rem;
            background-color: rgba(239, 68, 68, 0.08);
            border-left: 3px solid #ef4444;
            color: #f87171;
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 0 4px 4px 0;
            font-style: italic;
        }

        /* Card Footer action */
        .kds-card-footer {
            padding: 1rem 1.2rem;
            border-top: 1px solid var(--border-color);
            background: rgba(15, 23, 42, 0.15);
        }

        .btn-complete-kds {
            background-color: var(--accent-color);
            color: white;
            border: none;
            width: 100%;
            padding: 0.75rem;
            border-radius: var(--radius-md);
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-complete-kds:hover {
            background-color: var(--accent-hover);
            transform: scale(1.02);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
        }

        .btn-complete-kds:active {
            transform: scale(0.98);
        }

        /* Sidebar History Panel Drawer */
        .kds-sidebar-drawer {
            width: 320px;
            background: #111827;
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .drawer-header {
            padding: 1.2rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.3);
        }

        .drawer-header i {
            color: var(--accent-color);
        }

        .drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .history-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            font-size: 0.85rem;
        }

        .history-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            color: #fff;
        }

        .history-card-customer {
            color: var(--text-muted);
            font-weight: 600;
        }

        .btn-restore {
            background: transparent;
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.3rem;
            align-self: flex-start;
        }

        .btn-restore:hover {
            background: var(--accent-color);
            color: #fff;
        }

        /* Empty states */
        .empty-kds {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
        }

        .empty-kds i {
            font-size: 4rem;
            color: var(--border-color);
            animation: float-slow 3s ease-in-out infinite;
        }

        @keyframes float-slow {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .empty-kds h3 {
            font-size: 1.4rem;
            color: #fff;
            font-weight: 600;
        }

        /* Responsive Layouts */
        @media (max-width: 1024px) {
            .kds-sidebar-drawer {
                display: none; /* Hide history drawer on smaller screens */
            }
        }

        @media (max-width: 768px) {
            .kds-header {
                flex-direction: column !important;
                gap: 0.8rem !important;
                padding: 1rem !important;
                align-items: stretch !important;
                text-align: center !important;
            }
            .kds-brand {
                justify-content: center !important;
            }
            .kds-clock {
                align-self: center !important;
                font-size: 1.3rem !important;
                padding: 0.3rem 1rem !important;
            }
            .kds-stats-panel {
                flex-direction: column !important;
                gap: 0.6rem !important;
                width: 100% !important;
            }
            .stat-badge, .sound-toggle-btn {
                width: 100% !important;
                justify-content: center !important;
            }
            body {
                overflow-y: auto !important;
                height: auto !important;
            }
            .kds-workspace {
                flex-direction: column !important;
                height: auto !important;
                overflow: visible !important;
            }
            .kds-content-area {
                overflow: visible !important;
                height: auto !important;
                padding: 1rem !important;
            }
        }
    </style>
</head>
<body>

    <!-- KDS Header Bar -->
    <header class="kds-header">
        <div class="kds-brand">
            <span class="kds-status-dot"></span>
            <h1 class="kds-logo">AKSARA <span>KDS</span></h1>
            <span style="font-size: 0.75rem; background: rgba(255,255,255,0.06); padding: 0.25rem 0.6rem; border-radius: 20px; border: 1px solid var(--border-color);">V1.2 - Premium</span>
        </div>

        <div class="kds-clock" id="kds-digital-clock">00:00:00</div>

        <div class="kds-stats-panel">
            <div class="stat-badge pending">
                <i class="fas fa-clock fa-spin-hover"></i>
                <span>Menunggu:</span>
                <span class="stat-count" id="count-pending">0</span>
            </div>
            <div class="stat-badge">
                <i class="fas fa-check-circle"></i>
                <span>Selesai Hari Ini:</span>
                <span class="stat-count" id="count-completed">0</span>
            </div>
            <button class="sound-toggle-btn" id="btn-sound-toggle" onclick="toggleSound()">
                <i class="fas fa-volume-up"></i>
                <span>Bunyi: ON</span>
            </button>
            <button class="sound-toggle-btn" style="background:var(--primary-color); border-color:var(--primary-light);" onclick="fetchOrders()">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
            </button>
        </div>
    </header>

    <!-- KDS Workspace -->
    <div class="kds-workspace">
        <!-- Main cards display grid area -->
        <main class="kds-content-area">
            <div class="kds-grid" id="orders-grid">
                <div class="empty-kds">
                    <i class="fas fa-cookie-bite"></i>
                    <h3>Memuat data dapur...</h3>
                </div>
            </div>
        </main>

        <!-- Sidebar Drawer for completed order history -->
        <aside class="kds-sidebar-drawer">
            <div class="drawer-header">
                <span><i class="fas fa-history"></i> Riwayat Baru Selesai</span>
                <span id="history-count" style="font-size:0.75rem; background:rgba(255,255,255,0.1); padding:0.2rem 0.5rem; border-radius:10px;">0</span>
            </div>
            <div class="drawer-body" id="history-items">
                <!-- Injected completed items -->
                <p style="text-align:center; color:var(--text-muted); font-size:0.8rem; margin-top:2rem;">Belum ada pesanan selesai.</p>
            </div>
        </aside>
    </div>

    <script>
        let currentOrders = [];
        let previousPendingCount = 0;
        let isSoundEnabled = true;

        // Sound Synthesizer via Web Audio API (Chime)
        function playChime() {
            if (!isSoundEnabled) return;
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                
                // First Note (E5)
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, audioCtx.currentTime); // E5
                gain1.gain.setValueAtTime(0.08, audioCtx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                
                // Second Note (A5)
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880.00, audioCtx.currentTime + 0.08); // A5
                gain2.gain.setValueAtTime(0, audioCtx.currentTime);
                gain2.gain.setValueAtTime(0.08, audioCtx.currentTime + 0.08);
                gain2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                
                osc1.start();
                osc1.stop(audioCtx.currentTime + 0.25);
                osc2.start(audioCtx.currentTime + 0.08);
                osc2.stop(audioCtx.currentTime + 0.35);
            } catch(e) {
                console.log('Web Audio context blocked or unsupported:', e);
            }
        }

        function toggleSound() {
            isSoundEnabled = !isSoundEnabled;
            const btn = document.getElementById('btn-sound-toggle');
            if (isSoundEnabled) {
                btn.classList.remove('muted');
                btn.innerHTML = '<i class="fas fa-volume-up"></i> <span>Bunyi: ON</span>';
                // Play test tone
                playChime();
            } else {
                btn.classList.add('muted');
                btn.innerHTML = '<i class="fas fa-volume-mute"></i> <span>Bunyi: OFF</span>';
            }
        }

        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toTimeString().split(' ')[0];
            document.getElementById('kds-digital-clock').innerText = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Calculate Elapsed wait time
        function getElapsedTime(timestamp) {
            // Replace dash with slash for wider Safari compatibility
            const orderTime = new Date(timestamp.replace(/-/g, '/'));
            const now = new Date();
            const diffMs = now - orderTime;
            const diffMins = Math.floor(diffMs / 60000);
            return diffMins;
        }

        // Toggle Prep Item Checked State
        function toggleItemRow(element) {
            element.classList.toggle('checked');
            // Play a micro click beep
            try {
                if (isSoundEnabled) {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(element.classList.contains('checked') ? 1200 : 800, audioCtx.currentTime);
                    gain.gain.setValueAtTime(0.02, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.05);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.05);
                }
            } catch(e) {}
        }

        // Fetch orders from API
        function fetchOrders() {
            fetch('<?= base_url('api.php') ?>')
            .then(response => response.json())
            .then(orders => {
                currentOrders = orders;
                renderKDS();
            })
            .catch(err => console.error('Error fetching KDS orders:', err));
        }

        // Render KDS Interface
        function renderKDS() {
            const grid = document.getElementById('orders-grid');
            const historyContainer = document.getElementById('history-items');
            
            // Filter categories
            const pendingOrders = currentOrders.filter(o => o.status === 'pending');
            // In KDS, we want the OLDEST pending orders first so we prepare them in queue order
            pendingOrders.reverse();

            const completedOrders = currentOrders.filter(o => o.status === 'completed');
            
            // Update Stats Headers
            document.getElementById('count-pending').innerText = pendingOrders.length;
            document.getElementById('count-completed').innerText = completedOrders.length;
            document.getElementById('history-count').innerText = Math.min(10, completedOrders.length);

            // Chime notification if there are new pending orders
            if (pendingOrders.length > previousPendingCount) {
                // Determine if this is initial load vs a real new order arrival
                if (previousPendingCount > 0) {
                    playChime();
                }
            }
            previousPendingCount = pendingOrders.length;

            // Render Pending Queue
            grid.innerHTML = '';
            if (pendingOrders.length === 0) {
                grid.innerHTML = `
                    <div class="empty-kds">
                        <i class="fas fa-mug-hot"></i>
                        <h3>Semua Pesanan Selesai!</h3>
                        <p style="color:var(--text-muted); font-size:0.9rem;">Dapur sedang bersih. Selamat istirahat!</p>
                    </div>
                `;
            } else {
                pendingOrders.forEach((order, idx) => {
                    const elapsed = getElapsedTime(order.timestamp);
                    let timerClass = 'order-timer';
                    let timerIcon = 'fa-clock';
                    
                    if (elapsed >= 15) {
                        timerClass += ' danger';
                        timerIcon = 'fa-exclamation-triangle';
                    } else if (elapsed >= 8) {
                        timerClass += ' warn';
                    }

                    // Tipe Pesanan details
                    const type = order.order_type || 'Dine In';
                    let typeClass = 'type-badge dine-in';
                    let typeIcon = 'fa-utensils';
                    let cardTypeClass = 'type-dine-in';

                    if (type === 'Takeaway') {
                        typeClass = 'type-badge takeaway';
                        typeIcon = 'fa-shopping-bag';
                        cardTypeClass = 'type-takeaway';
                    } else if (type === 'Delivery') {
                        typeClass = 'type-badge delivery';
                        typeIcon = 'fa-motorcycle';
                        cardTypeClass = 'type-delivery';
                    }

                    // Assemble items checklist
                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `
                            <li class="kds-item-row" onclick="toggleItemRow(this)">
                                <div class="kds-item-main">
                                    <div class="kds-item-name">
                                        <span class="qty">x${item.qty}</span>
                                        <span>${item.title || item.name}</span>
                                    </div>
                                    <i class="fas fa-circle-notch item-checkbox"></i>
                                </div>
                                ${item.notes ? `<div class="kds-item-notes"><i class="fas fa-comment-alt"></i> ${item.notes}</div>` : ''}
                            </li>
                        `;
                    });

                    // Check payment status
                    const paymentMethod = order.payment_method || 'Cash';
                    let paymentBadgeHtml = '';
                    if (paymentMethod === 'QRIS') {
                        paymentBadgeHtml = `
                            <div class="payment-status-row">
                                <span class="payment-badge lunas"><i class="fas fa-check-circle"></i> LUNAS via QRIS</span>
                            </div>
                        `;
                    } else {
                        paymentBadgeHtml = `
                            <div class="payment-status-row">
                                <span class="payment-badge belum-bayar"><i class="fas fa-cash-register"></i> Ke Kasir (Belum Bayar)</span>
                            </div>
                        `;
                    }

                    // Build Card
                    const card = document.createElement('div');
                    card.className = `kds-card ${cardTypeClass}`;
                    card.setAttribute('id', `card-${order.id}`);
                    
                    // Add micro-flash animation to newly fetched cards (within 10 seconds of creation)
                    const orderAgeSec = (new Date() - new Date(order.timestamp.replace(/-/g, '/'))) / 1000;
                    if (orderAgeSec < 20) {
                        card.classList.add('new-order-flash');
                    }

                    card.innerHTML = `
                        <div class="kds-card-header">
                            <span class="order-number">#${order.id.replace('ord_', '')}</span>
                            <span class="${timerClass}" data-timestamp="${order.timestamp}">
                                <i class="fas ${timerIcon} ${elapsed >= 15 ? 'fa-beat' : ''}"></i>
                                <span class="timer-text">${elapsed} mnt lalu</span>
                            </span>
                        </div>
                        <div class="kds-card-body">
                            <div class="customer-title">
                                <span>${order.customer_name.split(' (')[0]}</span>
                                <span class="${typeClass}"><i class="fas ${typeIcon}"></i> ${type}</span>
                            </div>
                            ${paymentBadgeHtml}
                            <ul class="kds-items-checklist">
                                ${itemsHtml}
                            </ul>
                        </div>
                        <div class="kds-card-footer">
                            <button class="btn-complete-kds" onclick="markCompleted('${order.id}')">
                                <i class="fas fa-check"></i>
                                <span>Selesai Dibuat</span>
                            </button>
                        </div>
                    `;
                    grid.appendChild(card);
                });
            }

            // Render Completed History sidebar (10 limit)
            historyContainer.innerHTML = '';
            const historyList = completedOrders.slice(0, 10);
            if (historyList.length === 0) {
                historyContainer.innerHTML = '<p style="text-align:center; color:var(--text-muted); font-size:0.8rem; margin-top:2rem;">Belum ada pesanan selesai.</p>';
            } else {
                historyList.forEach(order => {
                    const timeOnly = order.timestamp.split(' ')[1].substring(0, 5);
                    const type = order.order_type || 'Dine In';
                    const historyPaymentMethod = order.payment_method || 'Cash';
                    const historyPaymentText = historyPaymentMethod === 'QRIS' ? 'QRIS' : 'Cash';
                    
                    const div = document.createElement('div');
                    div.className = 'history-card';
                    div.innerHTML = `
                        <div class="history-card-header">
                            <span>#${order.id.replace('ord_', '')}</span>
                            <span style="color:var(--accent-color); font-size:0.75rem;">${timeOnly}</span>
                        </div>
                        <div class="history-card-customer">${order.customer_name.split(' (')[0]}</div>
                        <div style="font-size:0.72rem; color:var(--text-muted);">${type} - ${order.items.length} item - <strong style="color: ${historyPaymentMethod === 'QRIS' ? '#10b981' : '#f59e0b'}">${historyPaymentText}</strong></div>
                        <button class="btn-restore" onclick="restoreOrder('${order.id}')">
                            <i class="fas fa-undo"></i> Batal Selesai
                        </button>
                    `;
                    historyContainer.appendChild(div);
                });
            }
        }

        // Live timers tick every 10 seconds without full re-render
        function tickTimers() {
            document.querySelectorAll('.kds-card .order-timer').forEach(badge => {
                const timestamp = badge.getAttribute('data-timestamp');
                const elapsed = getElapsedTime(timestamp);
                
                const textEl = badge.querySelector('.timer-text');
                const iconEl = badge.querySelector('i');
                
                textEl.innerText = `${elapsed} mnt lalu`;
                
                // Update warning levels
                badge.className = 'order-timer';
                iconEl.className = 'fas fa-clock';
                
                if (elapsed >= 15) {
                    badge.classList.add('danger');
                    iconEl.className = 'fas fa-exclamation-triangle fa-beat';
                } else if (elapsed >= 8) {
                    badge.classList.add('warn');
                }
            });
        }
        setInterval(tickTimers, 10000);

        // Mark completed status
        function markCompleted(orderId) {
            // Apply scale down premium animation before updating
            const card = document.getElementById(`card-${orderId}`);
            if (card) {
                card.style.transform = 'scale(0.8) translateY(20px)';
                card.style.opacity = '0';
                card.style.transition = 'all 0.4s cubic-bezier(0.6, -0.28, 0.735, 0.045)';
            }

            setTimeout(() => {
                fetch('<?= base_url('api.php') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update_status', id: orderId, status: 'completed' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        fetchOrders();
                        // Synthesize a completion chime tone
                        if (isSoundEnabled) {
                            try {
                                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                                const osc = audioCtx.createOscillator();
                                const gain = audioCtx.createGain();
                                osc.type = 'triangle';
                                osc.frequency.setValueAtTime(880.00, audioCtx.currentTime); // A5
                                osc.frequency.setValueAtTime(1046.50, audioCtx.currentTime + 0.1); // C6
                                gain.gain.setValueAtTime(0.04, audioCtx.currentTime);
                                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.25);
                                osc.connect(gain);
                                gain.connect(audioCtx.destination);
                                osc.start();
                                osc.stop(audioCtx.currentTime + 0.25);
                            } catch(e) {}
                        }
                    } else {
                        alert('Gagal memperbarui status dapur.');
                    }
                });
            }, 300);
        }

        // Restore completed order back to pending state
        function restoreOrder(orderId) {
            fetch('<?= base_url('api.php') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_status', id: orderId, status: 'pending' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchOrders();
                } else {
                    alert('Gagal mengembalikan pesanan.');
                }
            });
        }

        // Initialize KDS
        fetchOrders();
        // Poll every 5 seconds for new kitchen requests
        setInterval(fetchOrders, 5000);
    </script>
</body>
</html>
