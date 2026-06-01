<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aksara Coffee POS System</title>
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        window.USER_ROLE = 'kasir';
        const BASE_URL = '<?= base_url() ?>';
    </script>
    <style>
        /* ==========================================================================
           Kasgo Dashboard Style System
           ========================================================================== */
        .pos-screen-area {
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
            height: 100vh;
        }

        .pos-dashboard-view {
            display: flex;
            flex: 1;
            padding: 2.2rem;
            gap: 2.8rem;
            background: #f8fafc;
            overflow-y: auto;
        }

        .dash-left-col {
            flex: 1;
            max-width: 480px;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .dash-right-col {
            flex: 1.3;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .dash-card {
            background: white;
            border-radius: 16px;
            padding: 1.1rem 1.4rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            border: 1px solid #f1f5f9;
            position: relative;
        }

        .dash-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 1.3rem;
        }

        .dash-card-icon.profile {
            background: #f97316;
            color: white;
            border-radius: 50%;
        }

        .dash-card-info h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .dash-card-info p {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.15rem;
            margin-bottom: 0;
        }

        .badge-owner {
            background: #f97316;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            text-transform: uppercase;
        }

        .edit-pencil {
            position: absolute;
            right: 1.2rem;
            color: #3b82f6;
            cursor: pointer;
            transition: color 0.2s;
        }
        .edit-pencil:hover {
            color: var(--primary-light);
        }

        .backup-warning-banner {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            color: #b45309;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(251, 191, 36, 0.05);
            transition: all 0.2s;
        }
        .backup-warning-banner:hover {
            transform: translateY(-1px);
            background: #fff9db;
        }
        .backup-warning-banner i.warn-icon {
            font-size: 1.1rem;
            color: #d97706;
            margin-right: 0.6rem;
        }

        .purple-shift-card {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
            border-radius: 18px;
            padding: 2rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(124, 58, 237, 0.3);
            transition: transform 0.25s, box-shadow 0.25s;
            position: relative;
            overflow: hidden;
        }
        .purple-shift-card::after {
            content: '';
            position: absolute;
            width: 140px;
            height: 140px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            right: -30px;
            top: -30px;
        }
        .purple-shift-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.45);
        }

        .shift-lock-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .purple-shift-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            margin-top: 0;
        }
        .purple-shift-card p {
            font-size: 0.88rem;
            opacity: 0.9;
            margin: 0;
        }

        .purple-shift-card.active-shift {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            box-shadow: 0 10px 20px -5px rgba(0, 88, 19, 0.3);
        }

        .summary-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #475569;
            margin-top: 0.4rem;
            margin-bottom: 0.2rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
        }

        .sum-card {
            border-radius: 16px;
            padding: 1.1rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
        }
        .sum-card:hover {
            transform: translateY(-2px);
        }

        .sum-card.green { background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%); }
        .sum-card.blue { background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%); }
        .sum-card.orange { background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%); }
        .sum-card.red { background: linear-gradient(135deg, #ef5350 0%, #e53935 100%); }

        .sum-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .sum-card-info span {
            font-size: 0.75rem;
            opacity: 0.9;
            display: block;
        }
        .sum-card-info h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 0.1rem;
            margin-bottom: 0;
        }

        .menu-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
        }

        .menu-item-btn {
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 0.8rem 0.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .menu-item-btn:hover {
            transform: translateY(-3px);
            box-shadow: none;
            border-color: transparent;
        }

        .menu-item-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .menu-item-btn:hover .menu-item-icon {
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .menu-item-btn span {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            text-align: center;
        }

        /* Top Bar next to sidebar */
        .pos-top-bar {
            background: var(--primary-color);
            background-image: linear-gradient(to right, var(--primary-color), var(--primary-light));
            color: white;
            padding: 0.9rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 90;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .top-bar-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .top-bar-logo-k {
            background: white;
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1.25rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .top-bar-logo-text {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            color: white;
        }

        .top-bar-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.85rem;
        }

        .top-bar-clock {
            text-align: right;
            color: white;
            font-size: 0.8rem;
            line-height: 1.2;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .top-bar-logout {
            color: white;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            transition: background 0.2s, transform 0.2s;
            cursor: pointer;
        }

        .top-bar-logout:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .pos-dashboard-view {
                flex-direction: column;
                padding: 1rem;
                gap: 1.5rem;
            }
            .dash-left-col {
                max-width: 100%;
            }
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.6rem;
            }
        }

        /* ==========================================================================
           Kasgo Multi-Suite & Dynamic Theming Engine Styles
           ========================================================================== */
        body.mode-pos {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --accent-color: #3b82f6;
            --accent-hover: #1d4ed8;
        }
        body.mode-fnb {
            --primary-color: #005813;
            --primary-light: #00871d;
            --accent-color: #10b981;
            --accent-hover: #059669;
        }
        body.mode-laundry {
            --primary-color: #9f901b;
            --primary-light: #c4a81d;
            --accent-color: #eab308;
            --accent-hover: #ca8a04;
        }
        body.mode-care {
            --primary-color: #7c2d41;
            --primary-light: #c0748a;
            --accent-color: #ec4899;
            --accent-hover: #db2777;
        }
        body.mode-bengkel {
            --primary-color: #1a2942;
            --primary-light: #2c4163;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
        }

        /* 5-Column Sub-Navigation Segment */
        .kasgo-suite-nav {
            display: none !important;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.8rem;
            padding: 0.8rem 2.2rem;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            z-index: 85;
            flex-shrink: 0;
        }
        @media (max-width: 900px) {
            .kasgo-suite-nav {
                grid-template-columns: repeat(2, 1fr);
                padding: 0.8rem 1rem;
                gap: 0.5rem;
            }
            .kasgo-suite-nav .suite-nav-card:last-child {
                grid-column: span 2;
            }
        }
        .suite-nav-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.55rem 0.8rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
        }
        .suite-nav-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04);
            border-color: #cbd5e1;
        }
        .suite-nav-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            transition: transform 0.2s;
        }
        .suite-nav-card:hover .suite-nav-icon {
            transform: scale(1.08);
        }
        .suite-nav-info {
            min-width: 0;
            flex: 1;
        }
        .suite-nav-title {
            font-size: 0.8rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.25;
            color: #334155;
            transition: color 0.2s;
        }
        .suite-nav-desc {
            font-size: 0.65rem;
            color: #64748b;
            margin: 0;
            margin-top: 0.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Active Suite Nav Cards */
        .suite-nav-card.active-pos {
            background: #eff6ff;
            border-color: #3b82f6;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
        }
        .suite-nav-card.active-pos .suite-nav-title { color: #2563eb; }
        .suite-nav-card.active-pos .suite-nav-icon { background: #dbeafe; }

        .suite-nav-card.active-fnb {
            background: #f0fdf4;
            border-color: #10b981;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
        }
        .suite-nav-card.active-fnb .suite-nav-title { color: #005813; }
        .suite-nav-card.active-fnb .suite-nav-icon { background: #dcfce7; }

        .suite-nav-card.active-laundry {
            background: #fefbeb;
            border-color: #eab308;
            box-shadow: 0 2px 8px rgba(234, 179, 8, 0.08);
        }
        .suite-nav-card.active-laundry .suite-nav-title { color: #9f901b; }
        .suite-nav-card.active-laundry .suite-nav-icon { background: #fef9c3; }

        .suite-nav-card.active-care {
            background: #fff5f5;
            border-color: #ec4899;
            box-shadow: 0 2px 8px rgba(236, 72, 153, 0.08);
        }
        .suite-nav-card.active-care .suite-nav-title { color: #7c2d41; }
        .suite-nav-card.active-care .suite-nav-icon { background: #ffe4e6; }

        .suite-nav-card.active-bengkel {
            background: #f8fafc;
            border-color: #475569;
            box-shadow: 0 2px 8px rgba(71, 85, 105, 0.08);
        }
        .suite-nav-card.active-bengkel .suite-nav-title { color: #1a2942; }
        .suite-nav-card.active-bengkel .suite-nav-icon { background: #e2e8f0; }

        /* FnB (Resto) Suite Modal & Simulator Styles */
        .fnb-tabs-header {
            display: flex;
            gap: 1rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.8rem;
            margin-bottom: 1.2rem;
        }
        .fnb-tab-btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
        }
        .fnb-tab-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .fnb-tab-btn.active {
            background: #dcfce7;
            color: #005813;
            border-color: #bbf7d0;
        }
        .fnb-tab-content {
            display: none;
            gap: 1.5rem;
            height: 55vh;
            overflow: hidden;
        }
        .fnb-tab-content.active {
            display: flex;
        }
        
        /* Table Map Styles */
        .fnb-tables-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            overflow-y: auto;
            flex: 1.3;
            padding-right: 0.5rem;
        }
        @media (max-width: 600px) {
            .fnb-tables-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .fnb-table-item {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.9rem 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 95px;
            position: relative;
        }
        .fnb-table-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .fnb-table-item.status-kosong {
            border-color: #cbd5e1;
            background: white;
        }
        .fnb-table-item.status-kosong .fnb-table-icon {
            color: #94a3b8;
        }
        .fnb-table-item.status-terisi {
            border-color: #fca5a5;
            background: #fef2f2;
        }
        .fnb-table-item.status-terisi .fnb-table-icon {
            color: #ef4444;
        }
        .fnb-table-item.status-dipesan {
            border-color: #fde047;
            background: #fefce8;
        }
        .fnb-table-item.status-dipesan .fnb-table-icon {
            color: #ca8a04;
        }
        .fnb-table-item.active-selection {
            box-shadow: 0 0 0 3px rgba(0, 88, 19, 0.3);
            border-color: var(--primary-color);
        }
        .fnb-table-number {
            font-weight: 700;
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 0.2rem;
        }
        .fnb-table-icon {
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
        .fnb-table-desc {
            font-size: 0.72rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }
        .fnb-table-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.15rem 0.35rem;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .fnb-table-badge.kosong {
            background: #e2e8f0;
            color: #475569;
        }
        .fnb-table-badge.terisi {
            background: #fee2e2;
            color: #ef4444;
        }
        .fnb-table-badge.dipesan {
            background: #fef9c3;
            color: #ca8a04;
        }
        
        .fnb-table-details-panel {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            overflow-y: auto;
        }
        
        /* Kitchen Display System Styles */
        .fnb-kds-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
            overflow-y: auto;
            width: 100%;
            padding-right: 0.5rem;
        }
        @media (max-width: 600px) {
            .fnb-kds-grid {
                grid-template-columns: 1fr;
            }
        }
        .fnb-kds-ticket {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            position: relative;
        }
        .fnb-kds-ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 0.5rem;
        }
        .fnb-kds-ticket-id {
            font-weight: 700;
            font-size: 0.85rem;
            color: #0f172a;
        }
        .fnb-kds-ticket-time {
            font-size: 0.7rem;
            color: #94a3b8;
        }
        .fnb-kds-ticket-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
        }
        .fnb-kds-ticket-items {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin: 0.2rem 0;
            flex: 1;
        }
        .fnb-kds-ticket-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #334155;
            font-weight: 500;
        }
        .fnb-kds-status-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            text-transform: uppercase;
        }
        .fnb-kds-status-badge.mengantre {
            background: #f1f5f9;
            color: #475569;
        }
        .fnb-kds-status-badge.memasak {
            background: #ffedd5;
            color: #ea580c;
        }
        .fnb-kds-status-badge.saji {
            background: #dcfce7;
            color: #16a34a;
        }

        /* Onboarding On-Welcome Flow Overlay */
        .onboarding-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .onboarding-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .onboarding-card {
            background: white;
            width: 100%;
            max-width: 500px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transform: scale(0.95);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .onboarding-overlay.active .onboarding-card {
            transform: scale(1);
        }
        .onboarding-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            padding: 1.8rem;
            text-align: center;
            position: relative;
        }
        .onboarding-header h3 {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .onboarding-header p {
            font-size: 0.82rem;
            opacity: 0.85;
            margin: 0.3rem 0 0 0;
        }
        .onboarding-steps {
            display: flex;
            justify-content: space-between;
            padding: 1.1rem 2.2rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }
        .onboarding-step-line {
            position: absolute;
            top: 50%;
            left: 3rem;
            right: 3rem;
            height: 2px;
            background: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }
        .onboarding-step-progress-line {
            position: absolute;
            top: 50%;
            left: 3rem;
            height: 2px;
            background: var(--accent-color);
            transform: translateY(-50%);
            z-index: 2;
            width: 0%;
            transition: width 0.3s ease;
        }
        .onboarding-step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: 2px solid #cbd5e1;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
            position: relative;
            z-index: 3;
            transition: all 0.3s ease;
        }
        .onboarding-step-circle.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 4px rgba(0, 88, 19, 0.15);
        }
        .onboarding-step-circle.completed {
            border-color: var(--accent-color);
            background: var(--accent-color);
            color: white;
        }
        .onboarding-body {
            padding: 2rem;
            max-height: 55vh;
            overflow-y: auto;
        }
        .onboarding-step-pane {
            display: none;
            flex-direction: column;
            gap: 1.1rem;
        }
        .onboarding-step-pane.active {
            display: flex;
            animation: paneFade 0.25s ease;
        }
        @keyframes paneFade {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .onboarding-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .onboarding-form-group label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #475569;
        }
        .onboarding-form-group input, .onboarding-form-group select {
            width: 100%;
            padding: 0.75rem 0.9rem;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .onboarding-form-group input:focus, .onboarding-form-group select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 88, 19, 0.08);
        }
        .onboarding-footer {
            padding: 1.2rem 2rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
        }

        /* Accordion user-guides and features explorer */
        .accordion-item {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 0.8rem;
            background: white;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.01);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .accordion-item.active {
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(0, 88, 19, 0.04);
        }
        .accordion-header {
            padding: 1rem 1.2rem;
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
            transition: background 0.2s;
        }
        .accordion-header:hover {
            background: #f1f5f9;
        }
        .accordion-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .accordion-icon {
            font-size: 1.1rem;
            transition: transform 0.3s;
        }
        .accordion-item.active .accordion-icon {
            transform: rotate(180deg);
        }
        .accordion-content {
            display: none;
            padding: 1.2rem;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #475569;
            line-height: 1.6;
        }
        .accordion-item.active .accordion-content {
            display: block;
            animation: accordionSlide 0.2s ease-out;
        }
        @keyframes accordionSlide {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Split Payment Controls */
        .split-pay-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-top: 0.8rem;
        }
        .split-pay-field {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.6rem 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }
        .split-pay-field span {
            font-size: 0.7rem;
            font-weight: 600;
            color: #64748b;
        }
        .split-pay-field input {
            border: none;
            background: transparent;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            width: 100%;
            outline: none;
            padding: 0.1rem 0;
        }

        /* Tebus Murah Promo Card */
        .tebus-murah-banner {
            background: #fffbeb;
            border: 1.5px dashed #f59e0b;
            border-radius: 14px;
            padding: 0.8rem 1rem;
            margin-bottom: 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .tebus-murah-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: #b45309;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .tebus-murah-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #475569;
        }
        .tebus-murah-add-btn {
            background: #f59e0b;
            color: white;
            border: none;
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .tebus-murah-add-btn:hover {
            background: #d97706;
        }

        /* Barcode Scanner overlay */
        .barcode-scanner-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        .barcode-scanner-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .scanner-viewport {
            background: #000;
            width: 320px;
            height: 240px;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            border: 3px solid #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        .scanner-laser {
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
            top: 10%;
            animation: laserScan 2s linear infinite;
        }
        @keyframes laserScan {
            0% { top: 10%; }
            50% { top: 90%; }
            100% { top: 10%; }
        }
        .scanner-overlay-brackets {
            position: absolute;
            inset: 20px;
            border: 2px dashed rgba(255, 255, 255, 0.5);
            pointer-events: none;
        }

        /* Sync status details */
        .sync-badge {
            background: rgba(255,255,255,0.15);
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .sync-badge.online i {
            color: #10b981;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }
    </style>
</head>

<?php
$role = session()->get('role') ?? 'kasir';
$isKasir = ($role === 'kasir');
$isAdmin = ($role === 'admin');
$isOwner = ($role === 'owner');
?>
<?php if (session()->getFlashdata('error')): ?>
    <script>
        alert("<?= esc(session()->getFlashdata('error'), 'js') ?>");
    </script>
<?php endif; ?>
<body class="pos-body">

    <!-- Kiri: Sidebar POS Premium -->
    <div class="pos-sidebar">
        <div class="sidebar-logo">A.</div>
        <div class="sidebar-menu">
            <button class="sidebar-btn active" id="sidebar-btn-dash" onclick="switchView('dashboard')">
                <i class="fas fa-th-large"></i>
                <span>Home</span>
            </button>
            <button class="sidebar-btn" id="sidebar-btn-pos" onclick="switchView('pos')">
                <i class="fas fa-cash-register"></i>
                <span>Kasir</span>
            </button>

            <button class="sidebar-btn" onclick="openShiftDetailsModal()">
                <i class="fas fa-wallet"></i>
                <span>Shift</span>
            </button>
            <button class="sidebar-btn" onclick="openPanduanModal()" title="Panduan Lengkap">
                <i class="fas fa-book-open"></i>
                <span>Panduan</span>
            </button>
            <button class="sidebar-btn" onclick="openFeaturesModal()" title="Fitur Unggulan">
                <i class="fas fa-star"></i>
                <span>Fitur</span>
            </button>
            <a href="<?= base_url() ?>" target="_blank" class="sidebar-btn">
                <i class="fas fa-store"></i>
                <span>Menu</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <a href="<?= base_url('logout') ?>" class="sidebar-btn-exit" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <!-- Kanan: Layout POS Utama dan Dashboard -->
    <div class="pos-screen-area">
        
        <!-- Top Green Bar (Matches Kasgo screen) -->
        <div class="pos-top-bar">
            <div class="top-bar-logo">
                <div class="top-bar-logo-k"></div>
                <div class="top-bar-logo-text">AKSARA</div>
            </div>
            <div class="top-bar-info">
                <div class="sync-badge online" id="sync-status" onclick="toggleSyncConnection()" style="cursor: pointer;">
                    <i class="fas fa-link"></i> <span id="sync-text">Multi-Kasir Terhubung</span>
                </div>
                <div class="top-bar-clock" id="top-bar-clock">
                    <div style="font-weight: 700; font-size: 1.05rem; letter-spacing: 0.5px;">00:00</div>
                    <div style="font-size: 0.72rem; opacity: 0.85; font-weight: 600;">11 Mei 2026</div>
                </div>
                <a href="<?= base_url('logout') ?>" class="top-bar-logout" title="Keluar">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <!-- 5-Column Sub-Navigation Segment -->
        <div class="kasgo-suite-nav">
            <div class="suite-nav-card active-pos" id="suite-nav-pos" onclick="switchSuite('pos')">
                <div class="suite-nav-icon"><i class="fas fa-store"></i></div>
                <div class="suite-nav-info">
                    <p class="suite-nav-title">Kasgo Retail</p>
                    <p class="suite-nav-desc">Toko & Grosir</p>
                </div>
            </div>
            <div class="suite-nav-card" id="suite-nav-fnb" onclick="switchSuite('fnb')">
                <div class="suite-nav-icon"><i class="fas fa-utensils"></i></div>
                <div class="suite-nav-info">
                    <p class="suite-nav-title">Kasgo Resto</p>
                    <p class="suite-nav-desc">FnB & Cafe</p>
                </div>
            </div>
            <div class="suite-nav-card" id="suite-nav-laundry" onclick="switchSuite('laundry')">
                <div class="suite-nav-icon"><i class="fas fa-soap"></i></div>
                <div class="suite-nav-info">
                    <p class="suite-nav-title">Kasgo Laundry</p>
                    <p class="suite-nav-desc">Kiloan & Satuan</p>
                </div>
            </div>
            <div class="suite-nav-card" id="suite-nav-care" onclick="switchSuite('care')">
                <div class="suite-nav-icon"><i class="fas fa-scissors"></i></div>
                <div class="suite-nav-info">
                    <p class="suite-nav-title">Kasgo Salon</p>
                    <p class="suite-nav-desc">Care & Treatment</p>
                </div>
            </div>
            <div class="suite-nav-card" id="suite-nav-bengkel" onclick="switchSuite('bengkel')">
                <div class="suite-nav-icon"><i class="fas fa-wrench"></i></div>
                <div class="suite-nav-info">
                    <p class="suite-nav-title">Kasgo Bengkel</p>
                    <p class="suite-nav-desc">Servis & Sparepart</p>
                </div>
            </div>
        </div>

        <!-- 1. DASHBOARD VIEW (Kasgo Style Home) -->
        <div class="pos-dashboard-view" id="pos-dashboard-view">
            <!-- Left column -->
            <div class="dash-left-col">
                <!-- Shop Name Card -->
                <div class="dash-card" style="border-radius: 12px; padding: 0.9rem 1.2rem;">
                    <div class="dash-card-icon" style="background: #94a3b8; color: white; border-radius: 8px; width: 44px; height: 44px;"><i class="far fa-image"></i></div>
                    <div class="dash-card-info">
                        <h4 style="font-weight: 700; color: #334155; font-size: 0.95rem; margin: 0;">Nama Toko</h4>
                        <p style="color: #94a3b8; font-size: 0.8rem; margin: 0; margin-top: 0.1rem;">Alamat Toko</p>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="dash-card">
                    <?php 
                    $roleBadgeColor = '#f59e0b'; // Amber for Kasir
                    if ($role === 'admin') $roleBadgeColor = '#3b82f6'; // Blue
                    if ($role === 'owner') $roleBadgeColor = '#ec4899'; // Pink/Magenta
                    ?>
                    <div class="dash-card-icon profile" style="background: <?= $roleBadgeColor ?>; color: white; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user"></i></div>
                    <div class="dash-card-info">
                        <h4 style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: #1e293b; font-size: 0.95rem; margin: 0;">
                            <?= esc(session()->get('name') ?? 'Pengguna') ?> 
                            <span class="badge-owner" style="background: <?= $roleBadgeColor ?>; color: white; font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 700; text-transform: uppercase;"><?= strtoupper($role) ?></span>
                        </h4>
                        <p style="color: #64748b; font-size: 0.78rem; margin: 0; margin-top: 0.1rem;">@<?= esc(session()->get('username') ?? 'user') ?></p>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="edit-pencil" style="position: absolute; right: 1.2rem; color: #ef4444; text-decoration: none;" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>

                <!-- Backup Banner -->
                <div class="backup-warning-banner" onclick="alert('Penyimpanan Backup: Semua data POS tersimpan lokal di database browser Anda.')">
                    <div style="display:flex; align-items:center;">
                        <i class="fas fa-exclamation-triangle warn-icon" style="color: #f59e0b; margin-right: 0.8rem; font-size: 1.1rem;"></i>
                        <span>Anda belum pernah backup data. Tap untuk backup sekarang.</span>
                    </div>
                    <i class="fas fa-chevron-right" style="font-size:0.75rem; color: #d97706; opacity:0.8;"></i>
                </div>

                <!-- Shift Purple Card -->
                <div class="purple-shift-card" id="dashboard-shift-card" onclick="handleDashboardShiftClick()">
                    <div class="shift-lock-circle" id="dashboard-shift-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div>
                        <h3 id="dashboard-shift-title" style="font-weight: 700; margin: 0; font-size: 1.25rem;">Tap untuk mulai shift</h3>
                        <p id="dashboard-shift-subtitle" style="margin: 0; font-size: 0.85rem; opacity: 0.9; margin-top: 0.15rem;">Buka Kasir</p>
                    </div>
                </div>

                <!-- Ringkasan Title & Grid -->
                <div class="summary-title">Ringkasan Hari Ini</div>
                <div class="summary-grid">
                    <!-- Penjualan -->
                    <div class="sum-card green" style="<?= $isKasir ? 'opacity:0.4; pointer-events:none;' : '' ?>">
                        <div class="sum-card-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="sum-card-info">
                            <span>Penjualan</span>
                            <h4 id="dash-sales-total"><?= $isKasir ? 'Rp ***' : 'Rp 0' ?></h4>
                        </div>
                    </div>
                    <!-- Transaksi -->
                    <div class="sum-card blue" style="<?= $isKasir ? 'opacity:0.4; pointer-events:none;' : '' ?>">
                        <div class="sum-card-icon"><i class="fas fa-receipt"></i></div>
                        <div class="sum-card-info">
                            <span>Transaksi</span>
                            <h4 id="dash-sales-count"><?= $isKasir ? '***' : '0' ?> <span style="font-size:0.75rem; font-weight:normal; opacity:0.8;">transaksi</span></h4>
                        </div>
                    </div>
                    <!-- Stok Menipis -->
                    <div class="sum-card orange">
                        <div class="sum-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="sum-card-info">
                            <span>Stok Menipis</span>
                            <h4 id="dash-low-stock">0 <span style="font-size:0.75rem; font-weight:normal; opacity:0.8;">produk</span></h4>
                        </div>
                    </div>
                    <!-- Pengeluaran -->
                    <div class="sum-card red" style="<?= $isKasir ? 'opacity:0.4; pointer-events:none;' : '' ?>">
                        <div class="sum-card-icon"><i class="fas fa-arrow-trend-down"></i></div>
                        <div class="sum-card-info">
                            <span>Pengeluaran</span>
                            <h4 id="dash-expenses-total"><?= $isKasir ? 'Rp ***' : 'Rp 0' ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column (Menu Utama) -->
            <div class="dash-right-col">
                <div class="menu-title">Menu Utama</div>
                <div class="menu-grid">
                    <!-- Kasir -->
                    <div class="menu-item-btn" onclick="switchView('pos')">
                        <div class="menu-item-icon" style="background:#10b981;"><i class="fas fa-shopping-cart"></i></div>
                        <span>Kasir</span>
                    </div>
                    <!-- Produk -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Role Kasir tidak dapat mengelola produk.')" : "alert('Modul Produk: Fitur kelola produk lengkap dapat diakses melalui portal administrasi.')" ?>">
                        <div class="menu-item-icon" style="background:#f39c12; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-box"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Produk</span>
                    </div>
                    <!-- Kategori -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Role Kasir tidak dapat mengelola kategori.')" : "alert('Modul Kategori: Kelola pengelompokkan kategori menu.')" ?>">
                        <div class="menu-item-icon" style="background:#ec4899; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-shapes"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Kategori</span>
                    </div>
                    <!-- Stok -->
                    <div class="menu-item-btn" onclick="switchView('pos'); setTimeout(() => filterCategory('all'), 200);">
                        <div class="menu-item-icon" style="background:#3498db;"><i class="fas fa-chart-simple"></i></div>
                        <span>Stok</span>
                    </div>
                    <!-- Pelanggan -->
                    <div class="menu-item-btn" onclick="alert('Modul Pelanggan: Manajemen loyalitas & riwayat pelanggan.')">
                        <div class="menu-item-icon" style="background:#4f46e5;"><i class="fas fa-users"></i></div>
                        <span>Pelanggan</span>
                    </div>
                    <!-- Supplier -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Role Kasir tidak dapat mengelola supplier.')" : "alert('Modul Supplier: Kelola daftar pemasok logistik kopi.')" ?>">
                        <div class="menu-item-icon" style="background:#8b5a2b; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-truck"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Supplier</span>
                    </div>
                    <!-- Bahan Baku -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Role Kasir tidak dapat mengelola bahan baku.')" : "alert('Modul Bahan Baku: Kelola persediaan biji kopi dan susu.')" ?>">
                        <div class="menu-item-icon" style="background:#2ecc71; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-box-open"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Bahan Baku</span>
                    </div>
                    <!-- Pembelian -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Role Kasir tidak dapat melakukan pembelian bahan baku.')" : "alert('Modul Pembelian: Kelola PO dan pengadaan barang.')" ?>">
                        <div class="menu-item-icon" style="background:#64748b; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-shopping-basket"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Pembelian</span>
                    </div>
                    <!-- Konsinyasi -->
                    <div class="menu-item-btn" onclick="alert('Modul Konsinyasi: Kelola penitipan produk pastri pihak ketiga.')">
                        <div class="menu-item-icon" style="background:#f59e0b;"><i class="fas fa-handshake"></i></div>
                        <span>Konsinyasi</span>
                    </div>
                    <!-- Reservasi -->
                    <div class="menu-item-btn" onclick="alert('Modul Reservasi: Pesan meja cafe.')">
                        <div class="menu-item-icon" style="background:#0d9488;"><i class="fas fa-mobile-alt"></i></div>
                        <span>Reservasi</span>
                    </div>
                    <!-- Pengeluaran -->
                    <div class="menu-item-btn" onclick="alert('Modul Pengeluaran: Catat pengeluaran kas operasional.')">
                        <div class="menu-item-icon" style="background:#ef4444;"><i class="fas fa-arrow-trend-down"></i></div>
                        <span>Pengeluaran</span>
                    </div>
                    <!-- Riwayat -->
                    <div class="menu-item-btn" onclick="openRiwayatModal()">
                        <div class="menu-item-icon" style="background:#5dade2;"><i class="fas fa-history"></i></div>
                        <span>Riwayat</span>
                    </div>
                    <!-- Laporan -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= !$isOwner ? "alert('Akses Ditolak: Hanya Owner yang diizinkan melihat Laporan Keuangan.')" : "openShiftDetailsModal()" ?>">
                        <div class="menu-item-icon" style="background:#a855f7; <?= !$isOwner ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-chart-column"></i>
                            <?php if (!$isOwner): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Laporan</span>
                    </div>
                    <!-- Pengaturan -->
                    <div class="menu-item-btn" style="position: relative;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Role Kasir tidak dapat mengubah pengaturan.')" : "alert('Pengaturan POS: Konfigurasi printer thermal dan printer bluetooth 58mm.')" ?>">
                        <div class="menu-item-icon" style="background:#475569; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Pengaturan</span>
                    </div>
                    <!-- KDS Dapur -->
                    <a href="<?= $isKasir ? '#' : base_url('kds') ?>" class="menu-item-btn" style="text-decoration:none;" onclick="<?= $isKasir ? "alert('Akses Ditolak: Halaman Dapur hanya untuk Admin dan Owner.')" : "" ?>">
                        <div class="menu-item-icon" style="background:#059669; <?= $isKasir ? 'opacity: 0.5;' : '' ?>">
                            <i class="fas fa-utensils"></i>
                            <?php if ($isKasir): ?><i class="fas fa-lock" style="position: absolute; font-size: 0.65rem; background: rgba(0,0,0,0.65); padding: 0.2rem; border-radius: 50%; color: white; top: -5px; right: -5px;"></i><?php endif; ?>
                        </div>
                        <span>Dapur (KDS)</span>
                    </a>
                    <!-- Absensi Pekerja -->
                    <div class="menu-item-btn" onclick="openAbsensiModal()">
                        <div class="menu-item-icon" style="background:#e040fb;"><i class="fas fa-camera"></i></div>
                        <span>Absensi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. POS CATALOG & CART VIEW (Original Premium Catalog Screen) -->
        <div class="pos-layout" id="pos-catalog-view" style="display: none;">
            
            <!-- Area Tengah: Katalog Produk -->
            <div class="pos-catalog">
                <div class="catalog-header">
                    <div class="catalog-title">
                        <h2>Katalog Menu</h2>
                        <p id="catalog-subtitle">Sentuh item untuk menambah ke pesanan</p>
                    </div>
                    <div class="search-bar-wrapper" style="gap: 0.5rem; display: flex; align-items: center;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="pos-search-input" class="pos-search-input" placeholder="Cari menu kopi, snack..." oninput="searchProducts(this.value)">
                        <button class="discount-btn" id="btn-simulate-scan" onclick="triggerBarcodeScan()" title="Simulasi Scan Barcode" style="padding: 0.5rem 0.8rem; display: flex; align-items: center; justify-content: center; height: 38px; border-radius: 10px; flex-shrink: 0; border: 1px solid #cbd5e1; background: white;">
                            <i class="fas fa-barcode" style="font-size: 1.15rem; color: #475569;"></i>
                        </button>
                    </div>
                </div>

                <div class="category-tabs">
                    <button class="cat-btn active" onclick="filterCategory('all')">Semua Menu</button>
                    <button class="cat-btn" onclick="filterCategory('coffee')">Coffee</button>
                    <button class="cat-btn" onclick="filterCategory('non-coffee')">Non-Coffee</button>
                    <button class="cat-btn" onclick="filterCategory('snack')">Snacks & Pastry</button>
                </div>

                <div class="pos-product-grid" id="pos-product-grid">
                    <!-- Produk di-render via JS -->
                </div>
            </div>

            <!-- Area Kanan: Panel Keranjang -->
            <div class="pos-cart">
                <div class="cart-header">
                    <h3>Keranjang Pesanan</h3>
                    <div class="active-shift-badge">
                        <i class="fas fa-circle" style="font-size:0.5rem; color:var(--accent-color);"></i> Shift Aktif
                    </div>
                    <button class="clear-cart-btn" onclick="clearCart()" title="Kosongkan Keranjang">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

                <!-- Meta info: Dine In / Customer -->
                <div class="cart-meta-info">
                    <div>Tipe: <span id="cart-order-type-label">Dine In</span></div>
                    <div>Pelanggan: <span id="cart-customer-label">Guest</span></div>
                </div>

                <div class="cart-items" id="pos-cart-items">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-basket"></i>
                        <p>Keranjang masih kosong.<br>Pilih menu lezat di sebelah kiri.</p>
                    </div>
                </div>

                <!-- Tebus Murah Container Dynamic Slot -->
                <div id="tebus-murah-slot" style="padding: 0.8rem 1.2rem 0; display: none;"></div>

                <div class="cart-summary">
                    <!-- Quick Diskon Capsule -->
                    <div class="discount-section">
                        <div class="discount-label">Diskon Penjualan</div>
                        <div class="discount-capsules">
                            <button class="discount-btn active" onclick="applyDiscount(0, this)">0%</button>
                            <button class="discount-btn" onclick="applyDiscount(5, this)">5%</button>
                            <button class="discount-btn" onclick="applyDiscount(10, this)">10%</button>
                            <button class="discount-btn" onclick="applyDiscount(15, this)">15%</button>
                        </div>
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="pos-subtotal">Rp 0</span>
                    </div>
                    <div class="summary-row" style="color: #ef4444;">
                        <span>Diskon</span>
                        <span id="pos-discount">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span>PPN (11%)</span>
                        <span id="pos-tax">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span>Biaya Layanan (5%)</span>
                        <span id="pos-service-charge">Rp 0</span>
                    </div>
                    
                    <div class="cart-total-box">
                        <div class="total-label">Total Pembayaran</div>
                        <div class="total-amount" id="pos-total">Rp 0</div>
                    </div>
                    
                    <button class="btn-checkout" onclick="openPaymentModal()" id="btn-checkout" disabled>
                        Lanjut Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Item Detail (untuk notes/qty) -->
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
                    <textarea id="modal-item-notes" placeholder="Contoh: Es sedikit, gulanya dikurangi, ekstra shot..."></textarea>
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
                <h3>Pembayaran Transaksi</h3>
                <i class="fas fa-times close-modal" onclick="closePaymentModal()"></i>
            </div>
            <div class="modal-body">
                <div class="payment-grid">
                    <!-- Left Col -->
                    <div class="payment-left-col">
                        <div class="payment-section-title">Tipe Pesanan</div>
                        <div class="order-types">
                            <button class="order-type-btn active" onclick="setOrderType('Dine In', this)">
                                <i class="fas fa-chair"></i>
                                Dine In
                            </button>
                            <button class="order-type-btn" onclick="setOrderType('Takeaway', this)">
                                <i class="fas fa-shopping-bag"></i>
                                Takeaway
                            </button>
                            <button class="order-type-btn" onclick="setOrderType('Delivery', this)">
                                <i class="fas fa-motorcycle"></i>
                                Delivery
                            </button>
                        </div>

                        <div class="payment-section-title">Nama Pelanggan / Nomor Meja</div>
                        <div class="payment-field-wrapper">
                            <input type="text" id="pos-customer-name" placeholder="Misal: Meja 12 / Budi" oninput="updateCartMetaInfo()">
                        </div>

                        <div class="payment-section-title">Metode Pembayaran</div>
                        <div class="payment-methods" style="grid-template-columns: repeat(2, 1fr); display: grid; gap: 0.6rem;">
                            <button class="pay-method-btn active" onclick="setPaymentMethod('Cash', this)">
                                <i class="fas fa-money-bill-wave"></i>
                                Tunai
                            </button>
                            <button class="pay-method-btn" onclick="setPaymentMethod('QRIS', this)">
                                <i class="fas fa-qrcode"></i>
                                QRIS
                            </button>
                            <button class="pay-method-btn" onclick="setPaymentMethod('Split', this)">
                                <i class="fas fa-columns"></i>
                                Split Pay
                            </button>
                            <button class="pay-method-btn" onclick="setPaymentMethod('BON', this)">
                                <i class="fas fa-book"></i>
                                BON
                            </button>
                        </div>
                    </div>

                    <!-- Right Col -->
                    <div class="payment-right-col">
                        <div class="payment-total-box">
                            <span>Total Tagihan</span>
                            <h2 id="payment-total-amount">Rp 0</h2>
                        </div>

                        <div id="cash-input-section">
                            <div class="payment-section-title">Uang Tunai Diterima</div>
                            <input type="number" id="cash-received" placeholder="0" oninput="calculateChange()">
                            
                            <div class="quick-cash">
                                <button onclick="setQuickCash(20000)">20k</button>
                                <button onclick="setQuickCash(50000)">50k</button>
                                <button onclick="setQuickCash(100000)">100k</button>
                                <button onclick="setQuickCash('exact')">Pas</button>
                            </div>

                            <div class="change-box">
                                <span>Kembalian:</span>
                                <span id="cash-change" class="change-amount">Rp 0</span>
                            </div>
                        </div>

                        <!-- Split Payment Section -->
                        <div id="split-input-section" style="display: none; margin-top: 1rem;">
                            <div class="payment-section-title">Split Pembayaran</div>
                            <div class="split-pay-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin-top: 0.8rem;">
                                <div class="split-pay-field" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.6rem 0.8rem; display: flex; flex-direction: column; gap: 0.2rem;">
                                    <span style="font-size: 0.7rem; font-weight: 600; color: #64748b;">Nominal Tunai (Cash)</span>
                                    <input type="number" id="split-cash-amount" placeholder="0" oninput="calculateSplitPay()" style="border: none; background: transparent; font-size: 0.95rem; font-weight: 700; color: #1e293b; width: 100%; outline: none; padding: 0.1rem 0;">
                                </div>
                                <div class="split-pay-field" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.6rem 0.8rem; display: flex; flex-direction: column; gap: 0.2rem;">
                                    <span style="font-size: 0.7rem; font-weight: 600; color: #64748b;">Nominal Non-Tunai (QRIS)</span>
                                    <input type="number" id="split-qris-amount" placeholder="0" oninput="calculateSplitPay()" style="border: none; background: transparent; font-size: 0.95rem; font-weight: 700; color: #1e293b; width: 100%; outline: none; padding: 0.1rem 0;">
                                </div>
                            </div>
                            <div class="change-box" style="margin-top: 0.8rem;">
                                <span>Sisa Tagihan:</span>
                                <span id="split-pending-amount" class="change-amount" style="color: #ef4444;">Rp 0</span>
                            </div>
                        </div>

                        <!-- BON / Piutang Section -->
                        <div id="bon-input-section" style="display: none; margin-top: 1rem;">
                            <div class="payment-section-title">Informasi Piutang (BON)</div>
                            <div class="split-pay-field" style="width: 100%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.6rem 0.8rem; display: flex; flex-direction: column; gap: 0.2rem;">
                                <span style="font-size: 0.7rem; font-weight: 600; color: #64748b;">Limit Kredit Pelanggan</span>
                                <div style="font-weight: 700; font-size: 0.95rem; color: #b45309; padding: 0.2rem 0;">Rp 500.000</div>
                            </div>
                            <div class="change-box" style="margin-top: 0.8rem; background: #fffbeb; border-color: #fef3c7;">
                                <span style="color: #b45309;">Sisa Limit Kredit Pasca Transaksi:</span>
                                <span id="bon-remaining-limit" style="font-weight: 700; color: #d97706;">Rp 500.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" onclick="processPayment()" id="btn-process-payment" disabled>Proses Pembayaran</button>
            </div>
        </div>
    </div>

    <!-- Modal Buka Shift -->
    <div class="pos-modal-overlay" id="pos-shift-modal">
        <div class="pos-modal">
            <div class="modal-header">
                <h3>Buka Shift Kasir</h3>
                <i class="fas fa-times close-modal" onclick="document.getElementById('pos-shift-modal').classList.remove('active')"></i>
            </div>
            <div class="modal-body">
                <div class="shift-info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>Harap menginput modal awal kasir untuk memulai transaksi hari ini.</div>
                </div>
                <div class="notes-wrapper">
                    <label>Jumlah Modal Awal (Rp)</label>
                    <input type="number" id="shift-initial-cash" placeholder="Contoh: 100000" style="width:100%; padding:0.8rem; border-radius:12px; border:1px solid #e2e8f0; font-size:1rem; outline:none;" oninput="validateInitialCash(this)">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" id="btn-start-shift" onclick="openShift()" disabled>Buka Shift Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Modal Detail & Tutup Shift -->
    <div class="pos-modal-overlay" id="pos-shift-details-modal">
        <div class="pos-modal" style="max-width: 480px;">
            <div class="modal-header">
                <h3>Informasi Shift Aktif</h3>
                <i class="fas fa-times close-modal" onclick="closeShiftDetailsModal()"></i>
            </div>
            <div class="modal-body">
                <div class="shift-stats-grid">
                    <div class="shift-stat-card">
                        <span>Waktu Buka Shift</span>
                        <h4 id="shift-start-time">-</h4>
                    </div>
                    <div class="shift-stat-card">
                        <span>Modal Awal Laci</span>
                        <h4 id="shift-modal-awal">Rp 0</h4>
                    </div>
                    <div class="shift-stat-card">
                        <span>Total Tunai Masuk</span>
                        <h4 id="shift-total-tunai">Rp 0</h4>
                    </div>
                    <div class="shift-stat-card">
                        <span>Total QRIS / Elektrik</span>
                        <h4 id="shift-total-qris">Rp 0</h4>
                    </div>
                </div>

                <div class="change-box" style="margin-bottom: 1.5rem; background: var(--bg-color);">
                    <span>Estimasi Uang di Laci:</span>
                    <span id="shift-expected-laci" style="color: var(--primary-color);">Rp 0</span>
                </div>

                <div class="notes-wrapper">
                    <label>Uang Tunai Riil di Laci (Kas Masuk Aktual)</label>
                    <input type="number" id="shift-actual-cash" placeholder="Masukan uang riil yang ada di laci..." style="width:100%; padding:0.8rem; border-radius:12px; border:1px solid #e2e8f0; font-size:1rem; outline:none;" oninput="calculateShiftVariance()">
                    <div id="shift-variance-box" style="font-size: 0.82rem; margin-top: 0.5rem; font-weight: 600;"></div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; flex-direction: column; gap: 0.6rem;">
                <button class="btn-primary" style="background: #dc2626;" onclick="closeShift()">Tutup Shift Kasir</button>
                <button class="btn-primary" style="background: #f1f5f9; color: var(--text-dark); box-shadow: none;" onclick="closeShiftDetailsModal()">Kembali</button>
            </div>
        </div>
    </div>

    <!-- Modal Cetak Struk (Simulasi) -->
    <div class="pos-modal-overlay" id="pos-receipt-modal">
        <div class="pos-modal receipt-modal">
            <div class="modal-header">
                <h3>Transaksi Berhasil</h3>
                <i class="fas fa-times close-modal" onclick="closeReceiptModal()"></i>
            </div>
            <div class="modal-body">
                <div class="receipt-paper" id="receipt-content">
                    <!-- Dinamis via JS -->
                </div>
            </div>
            <div class="modal-footer" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <button class="btn-primary" onclick="printReceipt()"><i class="fas fa-print"></i> Cetak Bluetooth Thermal</button>
                <button class="btn-primary" style="background:#f1f5f9; color:var(--text-dark); box-shadow: none;" onclick="closeReceiptModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Onboarding Welcome Flow (Multi-step) -->
    <div class="onboarding-overlay" id="onboarding-modal">
        <div class="onboarding-card">
            <div class="onboarding-header">
                <h3>Selamat Datang di Kasgo</h3>
                <p>Mari konfigurasikan sistem kasir Anda dalam beberapa detik</p>
            </div>
            <div class="onboarding-steps">
                <div class="onboarding-step-line"></div>
                <div class="onboarding-step-progress-line" id="onboarding-progress-line"></div>
                <div class="onboarding-step-circle active" id="ob-step-circle-1">1</div>
                <div class="onboarding-step-circle" id="ob-step-circle-2">2</div>
                <div class="onboarding-step-circle" id="ob-step-circle-3">3</div>
            </div>
            <div class="onboarding-body">
                <!-- Step 1: Owner Setup -->
                <div class="onboarding-step-pane active" id="onboarding-pane-1">
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <i class="fas fa-user-shield" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                        <h4 style="margin-top: 0.5rem; font-weight: 700;">Konfigurasi Akun Owner</h4>
                    </div>
                    <div class="onboarding-form-group">
                        <label>Nama Lengkap Owner</label>
                        <input type="text" id="ob-owner-name" placeholder="Contoh: Ayu Lestari" value="Ayu Lestari">
                    </div>
                    <div class="onboarding-form-group">
                        <label>Username</label>
                        <input type="text" id="ob-owner-username" placeholder="Contoh: ayuowner" value="ayuowner">
                    </div>
                    <div class="onboarding-form-group">
                        <label>Nomor WhatsApp (Aktif)</label>
                        <input type="text" id="ob-owner-wa" placeholder="Contoh: 08123456789" value="08123456789">
                    </div>
                    <div class="onboarding-form-group">
                        <label>PIN Keamanan (4 Digit Angka)</label>
                        <input type="password" id="ob-owner-pin" maxlength="4" placeholder="Contoh: 1234" value="1234">
                    </div>
                </div>

                <!-- Step 2: Shop Setup -->
                <div class="onboarding-step-pane" id="onboarding-pane-2">
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <i class="fas fa-store-alt" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                        <h4 style="margin-top: 0.5rem; font-weight: 700;">Konfigurasi Identitas Toko</h4>
                    </div>
                    <div class="onboarding-form-group">
                        <label>Nama Usaha / Toko</label>
                        <input type="text" id="ob-shop-name" placeholder="Contoh: Aksara Coffee" value="Aksara Coffee">
                    </div>
                    <div class="onboarding-form-group">
                        <label>Alamat Toko Lengkap</label>
                        <input type="text" id="ob-shop-address" placeholder="Contoh: Jl. Aksara Seduhan No. 78, Jakarta" value="Jl. Aksara Seduhan No. 78, Jakarta">
                    </div>
                    <div class="onboarding-form-group">
                        <label>Simulasi Logo Toko (Pilih gambar atau warna)</label>
                        <div style="display: flex; gap: 0.8rem; align-items: center;">
                            <div id="ob-logo-preview" style="width: 58px; height: 58px; border-radius: 12px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 1.5rem; font-weight: 700;">A</div>
                            <button type="button" class="discount-btn" style="padding: 0.5rem 1rem; border: 1px solid #cbd5e1; background: white;" onclick="simulateLogoUpload()">Pilih Logo</button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Business Setup -->
                <div class="onboarding-step-pane" id="onboarding-pane-3">
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <i class="fas fa-briefcase" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                        <h4 style="margin-top: 0.5rem; font-weight: 700;">Pilih Spesialisasi Bisnis</h4>
                    </div>
                    <div class="onboarding-form-group" style="display: none;">
                        <label>Tipe Vertikal Bisnis Utama</label>
                        <select id="ob-business-type" onchange="updateObPreview()">
                            <option value="fnb" selected>FnB & Resto (Kasgo Resto 🍽️)</option>
                        </select>
                    </div>
                    <div class="onboarding-form-group">
                        <label>Mode Harga Penjualan</label>
                        <div style="display: flex; gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.4rem; font-weight: normal; font-size: 0.85rem; cursor: pointer;">
                                <input type="radio" name="ob-price-mode" value="retail" checked> Retail Standard
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.4rem; font-weight: normal; font-size: 0.85rem; cursor: pointer;">
                                <input type="radio" name="ob-price-mode" value="grosir"> Grosir / Multi-Satuan
                            </label>
                        </div>
                    </div>
                    <div style="background: #f1f5f9; padding: 0.9rem; border-radius: 12px; font-size: 0.78rem; color: #475569; border: 1px solid #e2e8f0; line-height: 1.45;">
                        <i class="fas fa-info-circle" style="color: var(--primary-color);"></i>
                        <span id="ob-type-description">Pilihan vertikal ini mengkonfigurasi sistem Kasgo Anda ke dalam mode FnB & Resto dengan peta meja interaktif, antrean dapur KDS Monitor, dan menu Dine-in/Takeaway.</span>
                    </div>
                </div>
            </div>
            <div class="onboarding-footer">
                <button type="button" class="discount-btn" id="ob-btn-prev" onclick="navigateObStep(-1)" style="border: 1px solid #cbd5e1; background: white;" disabled>Kembali</button>
                <button type="button" class="btn-checkout" id="ob-btn-next" onclick="navigateObStep(1)" style="padding: 0.5rem 1.4rem; width: auto; font-size: 0.88rem;">Lanjut</button>
            </div>
        </div>
    </div>

    <!-- Modal Panduan Penggunaan Kasgo (Accordion) -->
    <div class="pos-modal-overlay" id="pos-panduan-modal">
        <div class="pos-modal" style="max-width: 580px; width: 90%;">
            <div class="modal-header">
                <h3>📖 Panduan Lengkap Kasgo</h3>
                <i class="fas fa-times close-modal" onclick="closePanduanModal()"></i>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <div class="accordion-item active">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-mobile-alt"></i> 1. Instalasi & Setup Pertama</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Aplikasi Kasgo POS dirancang cloud-first dengan kapabilitas offline. Buka aplikasi di web browser Anda, lakukan 3-langkah Onboarding Setup untuk membuat akun Owner dan Toko baru Anda. Data Anda akan terenkripsi dan disimpan dengan aman di penyimpanan lokal peramban Anda.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-print"></i> 2. Panduan Koneksi Printer Thermal</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Untuk mencetak struk transaksi pelanggan secara profesional:</p>
                        <ol style="margin-left: 1.2rem; margin-top: 0.3rem;">
                            <li>Aktifkan bluetooth di device Anda (HP/Tablet/PC).</li>
                            <li>Nyalakan printer Bluetooth Thermal (ukuran lebar kertas 58mm atau 80mm).</li>
                            <li>Lakukan pairing perangkat Bluetooth dengan printer.</li>
                            <li>Buka <strong>Kasir &gt; Lanjut Bayar &gt; Cetak Bluetooth Thermal</strong> di Kasgo untuk mencetak struk kasir secara instan.</li>
                        </ol>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-lock"></i> 3. Manajemen Shift Kasir</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Kasgo melindungi kas laci Anda dengan sistem Shift Lock. Sebelum memulai penjualan, kasir wajib memasukkan Modal Awal Laci. Di akhir hari, kasir wajib melakukan audit dengan memasukkan jumlah Kas Aktual untuk mengkalkulasi selisih/varian kas sebelum menutup shift.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-sync-alt"></i> 4. Multi-Kasir & Sinkronisasi</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Gunakan tombol koneksi di bar atas untuk mensimulasikan sinkronisasi database server-client multi-kasir. Ketika aktif, setiap transaksi yang diselesaikan kasir cabang akan tersinkronisasi otomatis dengan server pusat secara real-time.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" onclick="closePanduanModal()">Tutup Panduan</button>
            </div>
        </div>
    </div>

    <!-- Modal 11 Fitur Kasgo Premium -->
    <div class="pos-modal-overlay" id="pos-features-modal">
        <div class="pos-modal" style="max-width: 580px; width: 90%;">
            <div class="modal-header">
                <h3>⭐ 11 Fitur Unggulan Kasgo Premium</h3>
                <i class="fas fa-times close-modal" onclick="closeFeaturesModal()"></i>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <div class="accordion-item active">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-barcode"></i> 1. Barcode Scanner Interaktif</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Pindai barcode produk secara instan menggunakan kamera internal gadget Anda. Simulasikan dengan menekan tombol barcode scanner laser di kasir untuk langsung memasukkan barang ke dalam keranjang.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-wallet"></i> 2. Split Payment (Metode Gabungan)</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Terima pembayaran multi-metode sekaligus untuk satu transaksi! Pelanggan dapat membayar sebagian dengan uang Tunai dan sebagian lagi dengan QRIS, dengan verifikasi kalkulator sisa tagihan otomatis.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-hands-helping"></i> 3. Sistem Tebus Murah & Loyalty</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Kasgo menghitung Loyalty Points secara otomatis (1 poin per Rp 5.000 belanja). Jika belanjaan mencapai lebih dari Rp 50.000, promo spesial "Tebus Murah" (matcha espresso diskon 60%) akan langsung aktif di keranjang belanja!</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-undo"></i> 4. Interactive Refund (Pengembalian Stok)</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Kesalahan input pesanan? Masuk ke Riwayat Transaksi, klik tombol <strong>Refund</strong>. Stok produk di katalog akan kembali secara dinamis, status pesanan ditandai Refunded, dan Net Sales (Total Penjualan) dikurangi secara otomatis.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-hand-holding-usd"></i> 5. Pencatatan BON / Hutang</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Pencatatan BON piutang pelanggan setia dengan limit kredit yang terekam aman. Transaksi piutang akan tercatat secara khusus dalam riwayat omset piutang Anda.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-users-cog"></i> 6. Manajemen Karyawan & Komisi</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Penghitungan komisi staf (mekanik bengkel atau stylist salon) secara otomatis berdasarkan persentase atau nominal jasa yang mereka kerjakan.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-boxes"></i> 7. Multi-Satuan & Grosir</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Dukungan untuk penetapan harga berjenjang (Grosir, Eceran, Satuan Dus/Pcs) yang terkelola secara akurat di dalam database produk.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-house-user"></i> 8. Status Tracking Laundry</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Khusus vertikal laundry, lacak tahap pekerjaan cucian kiloan/satuan pelanggan dari proses Cuci -> Kering -> Setrika -> Siap Diambil secara digital.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-receipt"></i> 9. KDS (Kitchen Display System)</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Layar antrean dapur reaktif yang terhubung langsung dengan kasir kasgo. Setiap pesanan baru otomatis terkirim ke dapur agar langsung diproses koki.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-calendar-check"></i> 10. Reservasi & Pemesanan Meja</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Manajemen reservasi meja kafe dan slot antrean pelanggan salon secara efisien langsung dari layar POS utama.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-title"><i class="fas fa-shield-alt"></i> 11. Shift Lock & Pencegah Fraud</div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Keamanan ketat dengan enkripsi PIN owner, riwayat audit kas laci, dan pencatatan riwayat void transaksi kasir.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" onclick="closeFeaturesModal()">Tutup Fitur</button>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Transaksi & Interactive Refund -->
    <div class="pos-modal-overlay" id="pos-riwayat-modal">
        <div class="pos-modal" style="max-width: 650px; width: 95%;">
            <div class="modal-header">
                <h3>📜 Riwayat Transaksi & Refund</h3>
                <i class="fas fa-times close-modal" onclick="closeRiwayatModal()"></i>
            </div>
            <div class="modal-body" style="display: flex; gap: 1rem; max-height: 60vh;">
                <!-- Left: List -->
                <div style="flex: 1.2; overflow-y: auto; border-right: 1px solid #e2e8f0; padding-right: 0.8rem; display: flex; flex-direction: column; gap: 0.6rem;" id="riwayat-orders-list">
                    <!-- Dinamis via JS -->
                </div>
                <!-- Right: Details & Action -->
                <div style="flex: 1.5; overflow-y: auto; display: flex; flex-direction: column; gap: 0.8rem;" id="riwayat-order-detail">
                    <div style="text-align: center; color: #94a3b8; margin: auto; padding: 2rem 0;">
                        <i class="fas fa-receipt" style="font-size: 2.5rem; margin-bottom: 0.5rem;"></i>
                        <p>Pilih transaksi untuk melihat detail struk & opsi refund.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" style="background: #f1f5f9; color: var(--text-dark); box-shadow: none;" onclick="closeRiwayatModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Barcode Laser Scanner Overlay -->
    <div class="barcode-scanner-overlay" id="barcode-scanner">
        <div class="scanner-viewport">
            <div class="scanner-laser"></div>
            <div class="scanner-overlay-brackets"></div>
            <div style="position: absolute; bottom: 15px; width: 100%; text-align: center; color: white; font-size: 0.8rem; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.8); z-index: 10;">
                Simulasi Memindai...
            </div>
        </div>
    </div>

    <!-- Modal Resto/FnB Suite (Interactive Tables Map & KDS Dapur) -->
    <div class="pos-modal-overlay" id="pos-fnb-suite-modal">
        <div class="pos-modal" style="max-width: 900px; width: 95%;">
            <div class="modal-header">
                <h3>🍽️ Kasgo Resto Suite - Live Simulator</h3>
                <i class="fas fa-times close-modal" onclick="closeFnbSuiteModal()"></i>
            </div>
            
            <div class="fnb-tabs-header">
                <div class="fnb-tab-btn active" id="fnb-tab-tables" onclick="switchFnbTab('tables')">
                    <i class="fas fa-chair"></i> Peta Meja & Reservasi
                </div>
                <div class="fnb-tab-btn" id="fnb-tab-kds" onclick="switchFnbTab('kds')">
                    <i class="fas fa-utensils"></i> Dapur (KDS Monitor)
                </div>
            </div>
            
            <div class="modal-body" style="padding-top: 0;">
                
                <!-- TAB 1: TABLES MAP -->
                <div class="fnb-tab-content active" id="fnb-panel-tables">
                    <!-- Left: Grid 12 Tables -->
                    <div class="fnb-tables-grid" id="fnb-tables-container">
                        <!-- Filled dynamically via JS -->
                    </div>
                    
                    <!-- Right: Table Details & Actions -->
                    <div class="fnb-table-details-panel" id="fnb-table-detail-container">
                        <div style="text-align: center; color: #94a3b8; margin: auto; padding: 2rem 0;">
                            <i class="fas fa-chair" style="font-size: 2.5rem; margin-bottom: 0.5rem; color: #cbd5e1;"></i>
                            <p>Pilih meja di sebelah kiri untuk melihat detail, mengatur reservasi, atau memproses pesanan kasir.</p>
                        </div>
                    </div>
                </div>
                
                <!-- TAB 2: KITCHEN DISPLAY SYSTEM -->
                <div class="fnb-tab-content" id="fnb-panel-kds" style="flex-direction: column;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">
                            KDS (Kitchen Display System) - Memantau & memproses antrean pesanan makanan/minuman dapur secara real-time.
                        </span>
                        <button class="btn-primary" style="padding: 0.3rem 0.8rem; font-size: 0.75rem; width: auto;" onclick="simulateNewKdsTicket()">
                            <i class="fas fa-plus"></i> Simulasikan Pesanan Masuk
                        </button>
                    </div>
                    
                    <div class="fnb-kds-grid" id="fnb-kds-container">
                        <!-- Filled dynamically via JS -->
                    </div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button class="btn-primary" style="background: #f1f5f9; color: #475569; box-shadow: none;" onclick="closeFnbSuiteModal()">Tutup Simulator</button>
            </div>
        </div>
    </div>

    <!-- Modal Pengeluaran Cafe -->
    <div class="pos-modal-overlay" id="pos-expenses-modal">
        <div class="pos-modal" style="max-width: 600px; width: 95%;">
            <div class="modal-header">
                <h3 style="display:flex; align-items:center; gap:0.6rem;">
                    <i class="fas fa-arrow-trend-down" style="color: #ef4444;"></i>
                    Catat Pengeluaran Cafe
                </h3>
                <i class="fas fa-times close-modal" onclick="closeExpensesModal()"></i>
            </div>
            <div class="modal-body" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.2rem; max-height: 70vh; overflow-y: auto;">
                <!-- Form Tambah Pengeluaran -->
                <form id="expenses-form" onsubmit="submitExpense(event)" style="display: flex; flex-direction: column; gap: 1rem; background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <label for="expense-desc" style="font-size: 0.85rem; font-weight: 600; color: #475569;">Keterangan Pengeluaran</label>
                        <input type="text" id="expense-desc" required placeholder="Contoh: Beli Es Batu, Isi Ulang Gas..." style="padding: 0.6rem 0.8rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <label for="expense-amount" style="font-size: 0.85rem; font-weight: 600; color: #475569;">Jumlah Uang (Rupiah)</label>
                        <input type="number" id="expense-amount" required min="1" placeholder="Contoh: 15000" style="padding: 0.6rem 0.8rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem;">
                    </div>
                    <button type="submit" class="btn-primary" style="background: #ef4444; border: none; color: white; padding: 0.7rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-plus"></i> Tambah Pengeluaran
                    </button>
                </form>

                <!-- Daftar Pengeluaran Hari Ini -->
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.6rem;">Daftar Pengeluaran Hari Ini</h4>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b;">
                                    <th style="padding: 0.5rem;">Waktu</th>
                                    <th style="padding: 0.5rem;">Keterangan</th>
                                    <th style="padding: 0.5rem; text-align: right;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="expenses-list-body">
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 1rem; color: #94a3b8;">Belum ada pengeluaran hari ini.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 1rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button class="btn-primary" style="background: #f1f5f9; color: #475569; box-shadow: none; border: 1px solid #cbd5e1;" onclick="closeExpensesModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Bahan Baku -->
    <div class="pos-modal-overlay" id="pos-raw-materials-modal">
        <div class="pos-modal" style="max-width: 700px; width: 95%;">
            <div class="modal-header">
                <h3 style="display:flex; align-items:center; gap:0.6rem;">
                    <i class="fas fa-box-open" style="color: #2ecc71;"></i>
                    Persediaan Bahan Baku
                </h3>
                <i class="fas fa-times close-modal" onclick="closeRawMaterialsModal()"></i>
            </div>
            <div class="modal-body" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.2rem; max-height: 70vh; overflow-y: auto;">
                <!-- Form Tambah/Update Bahan Baku -->
                <form id="raw-material-form" onsubmit="submitRawMaterial(event)" style="display: flex; gap: 0.8rem; align-items: flex-end; background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0; flex-wrap: wrap;">
                    <input type="hidden" id="material-id">
                    <div style="display: flex; flex-direction: column; gap: 0.4rem; flex: 1; min-width: 150px;">
                        <label for="material-name" style="font-size: 0.8rem; font-weight: 600; color: #475569;">Nama Bahan Baku</label>
                        <input type="text" id="material-name" required placeholder="Biji Kopi Gayo, Susu..." style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem; width: 100px;">
                        <label for="material-stock" style="font-size: 0.8rem; font-weight: 600; color: #475569;">Stok</label>
                        <input type="number" id="material-stock" required step="0.01" min="0" placeholder="5000" style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem; width: 100px;">
                        <label for="material-unit" style="font-size: 0.8rem; font-weight: 600; color: #475569;">Satuan</label>
                        <input type="text" id="material-unit" required placeholder="gram, ml, pcs" style="padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                    </div>
                    <button type="submit" class="btn-primary" style="background: #2ecc71; border: none; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 700; height: 36px; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fas fa-save"></i> <span id="material-btn-text">Simpan</span>
                    </button>
                    <button type="button" id="btn-cancel-edit-material" onclick="resetRawMaterialForm()" style="display: none; background: #cbd5e1; border: none; color: #475569; padding: 0.5rem; border-radius: 6px; font-weight: 700; height: 36px; cursor: pointer;">
                        Batal
                    </button>
                </form>

                <!-- Tabel Bahan Baku -->
                <div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b;">
                                    <th style="padding: 0.5rem;">Nama Bahan</th>
                                    <th style="padding: 0.5rem; text-align: right;">Stok</th>
                                    <th style="padding: 0.5rem;">Satuan</th>
                                    <th style="padding: 0.5rem; text-align: center; width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="raw-materials-list-body">
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 1rem; color: #94a3b8;">Memuat data bahan baku...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 1rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button class="btn-primary" style="background: #f1f5f9; color: #475569; box-shadow: none; border: 1px solid #cbd5e1;" onclick="closeRawMaterialsModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Absensi Pekerja dengan Kamera -->
    <div class="pos-modal-overlay" id="pos-absensi-modal">
        <div class="pos-modal" style="max-width: 800px; width: 95%;">
            <div class="modal-header">
                <h3 style="display:flex; align-items:center; gap:0.6rem;">
                    <i class="fas fa-camera" style="color: #e040fb;"></i>
                    Fitur Absensi Pekerja
                </h3>
                <i class="fas fa-times close-modal" onclick="closeAbsensiModal()"></i>
            </div>
            <div class="modal-body" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Main Content Grid -->
                <div class="absensi-grid-container">
                    <!-- Left: Camera Viewer -->
                    <div style="background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; position: relative;">
                        <!-- Video Area with pulse indicator -->
                        <div style="width: 100%; aspect-ratio: 4/3; border-radius: 12px; overflow: hidden; background: #0f172a; position: relative; border: 3px solid #e2e8f0; box-shadow: inset 0 2px 8px rgba(0,0,0,0.5);">
                            <video id="absensi-video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); display: none;"></video>
                            <canvas id="absensi-canvas" style="display: none;"></canvas>
                            
                            <!-- Camera Placeholder Image/Icon -->
                            <div id="absensi-placeholder" style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748b; gap: 0.8rem;">
                                <i class="fas fa-video-slash" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                <span style="font-size: 0.85rem; font-weight: 600;">Kamera Belum Aktif</span>
                            </div>
                            
                            <!-- Snapshot Preview Image -->
                            <img id="absensi-preview" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: none; border-radius: 9px; transform: scaleX(-1);" />
                            
                            <!-- Live Pulse Dot when stream is active -->
                            <div id="absensi-pulse" class="camera-pulse-dot" style="position: absolute; top: 12px; right: 12px; background: #ef4444; width: 10px; height: 10px; border-radius: 50%; display: none; box-shadow: 0 0 8px #ef4444;"></div>
                        </div>
                        
                        <button class="btn-primary" id="btn-toggle-camera" onclick="toggleCamera()" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; width: auto; box-shadow: 0 4px 10px rgba(124, 58, 237, 0.2);">
                            <i class="fas fa-video"></i> Aktifkan Kamera
                        </button>
                    </div>
                    
                    <!-- Right: Date/Time Info & Clock In/Out Action -->
                    <div style="display: flex; flex-direction: column; justify-content: space-between; gap: 1rem;">
                        <!-- User & Real-time Clock Info -->
                        <div style="background: #f8fafc; padding: 1.2rem; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 0.8rem;">
                            <div>
                                <label for="absensi-worker-name" style="font-size: 0.72rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 0.3rem;">Nama Pekerja</label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <i class="fas fa-user" style="position: absolute; left: 12px; color: #64748b; font-size: 0.9rem;"></i>
                                    <input type="text" id="absensi-worker-name" placeholder="Masukkan nama pekerja..." value="<?= esc(session()->get('name') ?? '') ?>" style="width: 100%; padding: 0.6rem 0.6rem 0.6rem 2.2rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem; font-weight: 600; color: #1e293b; outline: none; transition: border-color 0.2s;" oninput="validateWorkerName(this)">
                                </div>
                            </div>
                            
                            <div style="border-top: 1px dashed #cbd5e1; padding-top: 0.8rem;">
                                <span style="font-size: 0.72rem; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.5px;">Waktu Saat Ini</span>
                                <div id="absensi-digital-clock" style="font-size: 1.6rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin-top: 0.15rem; display: flex; align-items: baseline; gap: 0.3rem;">
                                    <span id="clock-time">00:00:00</span>
                                    <span id="clock-ampm" style="font-size: 0.9rem; font-weight: 600; color: #64748b;">WIB</span>
                                </div>
                                <div id="absensi-digital-date" style="font-size: 0.82rem; color: #64748b; font-weight: 600; margin-top: 0.1rem;">
                                    Memuat tanggal...
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <button class="btn-primary" id="btn-capture-photo" onclick="capturePhoto()" style="background: #94a3b8; color: white; padding: 0.8rem; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; transition: all 0.2s;" disabled>
                                <i class="fas fa-camera"></i> Ambil Foto
                            </button>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 0.25rem;">
                                <button id="btn-clock-in" onclick="submitAttendance('Masuk')" style="background: #94a3b8; color: white; padding: 0.8rem; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem; transition: all 0.2s;" disabled>
                                    <span style="font-size: 0.9rem;">Absen Masuk</span>
                                    <span style="font-size: 0.65rem; opacity: 0.85; font-weight: 500;">Mulai Kerja</span>
                                </button>
                                <button id="btn-clock-out" onclick="submitAttendance('Keluar')" style="background: #94a3b8; color: white; padding: 0.8rem; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.2rem; transition: all 0.2s;" disabled>
                                    <span style="font-size: 0.9rem;">Absen Keluar</span>
                                    <span style="font-size: 0.65rem; opacity: 0.85; font-weight: 500;">Selesai Kerja</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom: Attendance History Timeline / Table -->
                <div style="border-top: 1px solid #e2e8f0; padding-top: 1.2rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 700; color: #475569; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem; margin-top: 0;">
                        <i class="fas fa-history" style="color: #64748b;"></i>
                        Riwayat Absensi Anda (Hari Ini)
                    </h4>
                    
                    <!-- History Container -->
                    <div style="max-height: 180px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px; background: white;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 600;">
                                    <th style="padding: 0.6rem 1rem;">Foto</th>
                                    <th style="padding: 0.6rem 1rem;">Nama</th>
                                    <th style="padding: 0.6rem 1rem;">Tipe</th>
                                    <th style="padding: 0.6rem 1rem;">Tanggal</th>
                                    <th style="padding: 0.6rem 1rem;">Waktu</th>
                                    <th style="padding: 0.6rem 1rem;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="absensi-history-body">
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 1.5rem; color: #94a3b8; font-style: italic;">
                                        Belum ada data absensi hari ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .absensi-grid-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 1.5rem;
        }
        
        @media (max-width: 600px) {
            .absensi-grid-container {
                grid-template-columns: 1fr;
            }
        }
        
        .camera-pulse-dot {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
    </style>

    <script>
        function validateWorkerName(input) {
            const btnCapture = document.getElementById('btn-toggle-camera');
            if (input.value.trim() === '') {
                btnCapture.disabled = true;
                btnCapture.style.background = '#94a3b8';
            } else {
                btnCapture.disabled = false;
                btnCapture.style.background = attendanceStream ? '#64748b' : 'linear-gradient(135deg, #a855f7 0%, #7c3aed 100%)';
            }
        }

        // Live digital clock logic
        let digitalClockInterval = null;
        function startAbsensiClock() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            if (digitalClockInterval) clearInterval(digitalClockInterval);
            
            digitalClockInterval = setInterval(() => {
                const now = new Date();
                let hours = now.getHours();
                let minutes = now.getMinutes();
                let seconds = now.getSeconds();
                
                hours = hours < 10 ? '0' + hours : hours;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;
                
                document.getElementById('clock-time').textContent = `${hours}:${minutes}:${seconds}`;
                
                const dayName = days[now.getDay()];
                const dayDate = now.getDate();
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();
                
                document.getElementById('absensi-digital-date').textContent = `${dayName}, ${dayDate} ${monthName} ${year}`;
            }, 1000);
        }

        // Camera variables
        let attendanceStream = null;
        let capturedPhotoData = null; // Store base64 data

        // Toggle Camera stream
        async function toggleCamera() {
            const videoEl = document.getElementById('absensi-video');
            const placeholderEl = document.getElementById('absensi-placeholder');
            const previewEl = document.getElementById('absensi-preview');
            const pulseEl = document.getElementById('absensi-pulse');
            const btnToggleCam = document.getElementById('btn-toggle-camera');
            const btnCapture = document.getElementById('btn-capture-photo');
            const btnClockIn = document.getElementById('btn-clock-in');
            const btnClockOut = document.getElementById('btn-clock-out');

            if (attendanceStream) {
                // Stop camera
                stopCamera();
            } else {
                // Start camera
                try {
                    attendanceStream = await navigator.mediaDevices.getUserMedia({
                        video: { width: 640, height: 480, facingMode: 'user' },
                        audio: false
                    });
                    videoEl.srcObject = attendanceStream;
                    videoEl.style.display = 'block';
                    placeholderEl.style.display = 'none';
                    previewEl.style.display = 'none';
                    pulseEl.style.display = 'block';
                    
                    btnToggleCam.innerHTML = '<i class="fas fa-video-slash"></i> Matikan Kamera';
                    btnToggleCam.style.background = '#64748b'; // Muted color
                    btnCapture.disabled = false;
                    btnCapture.style.background = '#1e293b'; // Enable color
                    
                    // Clear any previously captured photo preview when turning on camera
                    capturedPhotoData = null;
                    btnClockIn.disabled = true;
                    btnClockOut.disabled = true;
                    btnClockIn.style.background = '#94a3b8';
                    btnClockOut.style.background = '#94a3b8';
                } catch (err) {
                    console.error("Gagal mengakses kamera:", err);
                    alert("Gagal mengakses kamera. Harap izinkan akses kamera di pengaturan browser Anda.");
                }
            }
        }

        function stopCamera() {
            const videoEl = document.getElementById('absensi-video');
            const placeholderEl = document.getElementById('absensi-placeholder');
            const pulseEl = document.getElementById('absensi-pulse');
            const btnToggleCam = document.getElementById('btn-toggle-camera');
            const btnCapture = document.getElementById('btn-capture-photo');

            if (attendanceStream) {
                attendanceStream.getTracks().forEach(track => track.stop());
                attendanceStream = null;
            }
            videoEl.srcObject = null;
            videoEl.style.display = 'none';
            placeholderEl.style.display = 'flex';
            pulseEl.style.display = 'none';
            
            btnToggleCam.innerHTML = '<i class="fas fa-video"></i> Aktifkan Kamera';
            btnToggleCam.style.background = 'linear-gradient(135deg, #a855f7 0%, #7c3aed 100%)';
            btnCapture.disabled = true;
            btnCapture.style.background = '#94a3b8';
        }

        // Capture photo from video stream
        function capturePhoto() {
            const videoEl = document.getElementById('absensi-video');
            const canvasEl = document.getElementById('absensi-canvas');
            const previewEl = document.getElementById('absensi-preview');
            const pulseEl = document.getElementById('absensi-pulse');
            const btnToggleCam = document.getElementById('btn-toggle-camera');
            const btnClockIn = document.getElementById('btn-clock-in');
            const btnClockOut = document.getElementById('btn-clock-out');

            if (!attendanceStream) return;
            
            // Match canvas dimensions to video
            canvasEl.width = videoEl.videoWidth || 640;
            canvasEl.height = videoEl.videoHeight || 480;
            
            const ctx = canvasEl.getContext('2d');
            // Mirror the image to match the video mirror preview
            ctx.translate(canvasEl.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);
            
            // Reset transformation matrix
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            
            // Convert to base64 jpeg
            capturedPhotoData = canvasEl.toDataURL('image/jpeg', 0.9);
            
            // Show flash effect on video viewport
            const flashOverlay = document.createElement('div');
            flashOverlay.style.position = 'absolute';
            flashOverlay.style.inset = '0';
            flashOverlay.style.background = 'white';
            flashOverlay.style.opacity = '0.9';
            flashOverlay.style.transition = 'opacity 0.25s ease';
            flashOverlay.style.zIndex = '5';
            videoEl.parentElement.appendChild(flashOverlay);
            
            setTimeout(() => {
                flashOverlay.style.opacity = '0';
                setTimeout(() => flashOverlay.remove(), 250);
            }, 50);
            
            // Display preview
            previewEl.src = capturedPhotoData;
            previewEl.style.display = 'block';
            videoEl.style.display = 'none';
            
            // Disable video stream tracking to save resources but don't full stop if user wants to recapt
            pulseEl.style.display = 'none';
            
            // Enable Clock In & Out buttons
            btnClockIn.disabled = false;
            btnClockOut.disabled = false;
            btnClockIn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            btnClockOut.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
            
            // Update toggle camera button state
            btnToggleCam.innerHTML = '<i class="fas fa-redo"></i> Ambil Ulang Foto';
            btnToggleCam.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
            
            // Safely stop stream to release webcam hardware
            if (attendanceStream) {
                attendanceStream.getTracks().forEach(track => track.stop());
                attendanceStream = null;
            }
        }

        // Submit attendance via AJAX
        function submitAttendance(type) {
            const btnClockIn = document.getElementById('btn-clock-in');
            const btnClockOut = document.getElementById('btn-clock-out');

            if (!capturedPhotoData) {
                alert("Harap ambil foto terlebih dahulu menggunakan kamera!");
                return;
            }
            
            // Show loading status
            const activeBtn = type === 'Masuk' ? btnClockIn : btnClockOut;
            const inactiveBtn = type === 'Masuk' ? btnClockOut : btnClockIn;
            
            activeBtn.disabled = true;
            inactiveBtn.disabled = true;
            const originalHtml = activeBtn.innerHTML;
            activeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            const workerName = document.getElementById('absensi-worker-name').value.trim();
            if (!workerName) {
                alert("Harap masukkan nama pekerja terlebih dahulu!");
                return;
            }

            const formData = new FormData();
            formData.append('type', type);
            formData.append('photo', capturedPhotoData);
            formData.append('worker_name', workerName);
            
            fetch('<?= base_url('attendance/submit') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.success || (res.status && res.status === 200)) {
                    alert(`Absensi ${type} Berhasil!\n\nNama: ${workerName}\nTanggal: ${res.data.date}\nWaktu: ${res.data.time}`);
                    closeAbsensiModal();
                    loadAttendanceHistory(); // Refresh history
                } else {
                    // Handle duplicate entry or database error
                    alert("Gagal Absen: " + (res.messages?.error || res.message || "Terjadi kesalahan pada server."));
                    // Restore buttons
                    activeBtn.disabled = false;
                    inactiveBtn.disabled = false;
                    activeBtn.innerHTML = originalHtml;
                }
            })
            .catch(err => {
                console.error("Koneksi gagal:", err);
                alert("Koneksi gagal. Pastikan server Anda aktif!");
                // Restore buttons
                activeBtn.disabled = false;
                inactiveBtn.disabled = false;
                activeBtn.innerHTML = originalHtml;
            });
        }

        // Load recent history logs
        function loadAttendanceHistory() {
            const historyBody = document.getElementById('absensi-history-body');
            historyBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 1.5rem; color: #94a3b8;"><i class="fas fa-spinner fa-spin"></i> Memuat riwayat...</td></tr>';
            
            fetch('<?= base_url('attendance/history') ?>')
            .then(response => response.json())
            .then(res => {
                if (res.success && res.history && res.history.length > 0) {
                    historyBody.innerHTML = '';
                    res.history.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.style.borderBottom = '1px solid #f1f5f9';
                        tr.style.transition = 'background 0.2s';
                        tr.onmouseenter = () => tr.style.background = '#f8fafc';
                        tr.onmouseleave = () => tr.style.background = 'transparent';
                        
                        const typeBadge = row.type === 'Masuk' 
                            ? `<span style="background: #dcfce7; color: #15803d; font-weight:700; font-size:0.75rem; padding:0.15rem 0.5rem; border-radius:6px; display:inline-block;">Masuk</span>`
                            : `<span style="background: #fee2e2; color: #b91c1c; font-weight:700; font-size:0.75rem; padding:0.15rem 0.5rem; border-radius:6px; display:inline-block;">Keluar</span>`;
                        
                        // Format date
                        const dbDate = new Date(row.date);
                        const formattedDate = dbDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                        
                        // Check base_url compatibility for absolute paths
                        const photoUrl = '<?= base_url() ?>' + row.photo;
                        
                        tr.innerHTML = `
                            <td style="padding: 0.6rem 1rem;">
                                <img src="${photoUrl}" style="width: 42px; height: 32px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; cursor: zoom-in;" onclick="zoomAbsensiPhoto('${photoUrl}')" />
                            </td>
                            <td style="padding: 0.6rem 1rem; font-weight:600; color:#334155;">${row.user_name || 'Pekerja'}</td>
                            <td style="padding: 0.6rem 1rem; font-weight:600; color:#334155;">${typeBadge}</td>
                            <td style="padding: 0.6rem 1rem; color:#475569;">${formattedDate}</td>
                            <td style="padding: 0.6rem 1rem; font-weight:700; color:#0f172a;">${row.time} WIB</td>
                            <td style="padding: 0.6rem 1rem;"><span style="color:#10b981; font-weight:600;"><i class="fas fa-check-circle"></i> Berhasil</span></td>
                        `;
                        historyBody.appendChild(tr);
                    });
                } else {
                    historyBody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 1.5rem; color: #94a3b8; font-style: italic;">
                                Belum ada riwayat absensi hari ini.
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(err => {
                console.error("Gagal memuat riwayat:", err);
                historyBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 1.5rem; color: #ef4444; font-weight: 600;">
                            Gagal memuat riwayat absensi.
                        </td>
                    </tr>
                `;
            });
        }

        // Zoom Image lightbox
        function zoomAbsensiPhoto(photoSrc) {
            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.inset = '0';
            overlay.style.background = 'rgba(15, 23, 42, 0.8)';
            overlay.style.backdropFilter = 'blur(10px)';
            overlay.style.zIndex = '99999';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.cursor = 'zoom-out';
            overlay.onclick = () => overlay.remove();
            
            const img = document.createElement('img');
            img.src = photoSrc;
            img.style.maxWidth = '90%';
            img.style.maxHeight = '80%';
            img.style.borderRadius = '16px';
            img.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.5)';
            img.style.border = '4px solid white';
            img.style.transform = 'scale(0.95)';
            img.style.transition = 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
            
            overlay.appendChild(img);
            document.body.appendChild(overlay);
            
            setTimeout(() => img.style.transform = 'scale(1)', 50);
        }

        // Modal Control Functions
        function openAbsensiModal() {
            const modal = document.getElementById('pos-absensi-modal');
            const previewEl = document.getElementById('absensi-preview');
            const videoEl = document.getElementById('absensi-video');
            const placeholderEl = document.getElementById('absensi-placeholder');
            const pulseEl = document.getElementById('absensi-pulse');
            const btnToggleCam = document.getElementById('btn-toggle-camera');
            const btnCapture = document.getElementById('btn-capture-photo');
            const btnClockIn = document.getElementById('btn-clock-in');
            const btnClockOut = document.getElementById('btn-clock-out');

            modal.classList.add('active');
            
            // Reset states
            stopCamera();
            capturedPhotoData = null;
            previewEl.style.display = 'none';
            videoEl.style.display = 'none';
            placeholderEl.style.display = 'flex';
            pulseEl.style.display = 'none';
            
            btnToggleCam.innerHTML = '<i class="fas fa-video"></i> Aktifkan Kamera';
            btnToggleCam.style.background = 'linear-gradient(135deg, #a855f7 0%, #7c3aed 100%)';
            btnCapture.disabled = true;
            btnCapture.style.background = '#94a3b8';
            btnClockIn.disabled = true;
            btnClockOut.disabled = true;
            btnClockIn.style.background = '#94a3b8';
            btnClockOut.style.background = '#94a3b8';
            
            startAbsensiClock();
            loadAttendanceHistory();
        }

        function closeAbsensiModal() {
            document.getElementById('pos-absensi-modal').classList.remove('active');
            stopCamera();
            if (digitalClockInterval) {
                clearInterval(digitalClockInterval);
                digitalClockInterval = null;
            }
        }
    </script>

    <script src="<?= base_url('pos_dashboard.js') ?>"></script>
</body>
</html>
