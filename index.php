<?php
// ============================================
// File: index.php
// MLBB ACCOUNTS BUY & SELL PLATFORM
// FULLY RESPONSIVE - ALL DEVICES
// All original functions preserved + Installment Plans
// ============================================
session_start();
require_once 'config/database.php';

// Fetch products (MLBB Accounts) from database - Separate available and sold
$stmt_available = $conn->query("SELECT * FROM products WHERE status = 'available' ORDER BY featured DESC, id DESC");
$available_products = $stmt_available->fetchAll(PDO::FETCH_ASSOC);

$stmt_sold = $conn->query("SELECT * FROM products WHERE status = 'sold' ORDER BY id DESC LIMIT 10");
$sold_products = $stmt_sold->fetchAll(PDO::FETCH_ASSOC);

// Fetch active testimonials
$stmt_testimonials = $conn->query("SELECT * FROM testimonials WHERE status = 'active' ORDER BY id DESC LIMIT 10");
$testimonials = $stmt_testimonials->fetchAll(PDO::FETCH_ASSOC);

// Fetch active installment plans (NEW)
$stmt_installments = $conn->query("SELECT * FROM installment_plans WHERE status = 'active' ORDER BY id DESC");
$installment_plans = $stmt_installments->fetchAll(PDO::FETCH_ASSOC);

// Facebook Page URL
$facebook_url = "https://web.facebook.com/JamLagz2025/";
$messenger_url = "https://m.me/JamLagz2025";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <title>JamLagz | MLBB Accounts Buy & Sell - Premium Mobile Legends Accounts</title>
    <!-- Bootstrap 5 + Icons + Custom Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --mlbb-red: #e03a3a;
            --mlbb-gold: #ffd966;
            --mlbb-dark: #0a0c15;
            --mlbb-card: #11161f;
            --mlbb-glow: rgba(224, 58, 58, 0.6);
            --epic-purple: #9b59b6;
            --legendary-orange: #f39c12;
            --mythic-blue: #3498db;
            --fb-blue: #1877f2;
            --installment-green: #2ecc71;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #05070e;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Animated Background Effect */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(224,58,58,0.08) 0%, transparent 50%),
                        radial-gradient(circle at 80% 70%, rgba(255,217,102,0.05) 0%, transparent 50%),
                        repeating-linear-gradient(45deg, rgba(224,58,58,0.02) 0px, rgba(224,58,58,0.02) 2px, transparent 2px, transparent 8px);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0a0c15; }
        ::-webkit-scrollbar-thumb { background: var(--mlbb-red); border-radius: 10px; }
        
        /* ========== FULLY RESPONSIVE NAVBAR ========== */
        .navbar {
            background: rgba(5, 7, 14, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid rgba(224, 58, 58, 0.4);
            padding: 0.8rem 0;
            position: relative;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Orbitron', monospace;
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #fff, var(--mlbb-red), var(--mlbb-gold));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        @media (min-width: 768px) {
            .navbar-brand { font-size: 1.8rem; }
        }
        
        .navbar-brand img {
            width: 30px;
        }
        
        @media (min-width: 768px) {
            .navbar-brand img { width: 40px; }
        }
        
        .nav-link {
            font-weight: 600;
            color: #e0e0e0 !important;
            margin: 0 5px;
            transition: 0.3s;
            font-size: 0.85rem;
        }
        
        @media (min-width: 768px) {
            .nav-link {
                margin: 0 8px;
                font-size: 1rem;
            }
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--mlbb-red);
            transition: 0.3s;
        }
        
        .nav-link:hover::after, .nav-link.active::after { width: 70%; }
        .nav-link:hover, .nav-link.active { color: var(--mlbb-red) !important; }
        
        /* ========== RESPONSIVE HERO SECTION ========== */
        .hero-section {
            position: relative;
            text-align: center;
            padding: 3rem 1rem 4rem;
            background: linear-gradient(180deg, rgba(5,7,14,0.9) 0%, rgba(224,58,58,0.2) 100%);
            overflow: hidden;
            z-index: 1;
        }
        
        @media (min-width: 768px) {
            .hero-section { padding: 5rem 1rem 6rem; }
        }
        
        .hero-badge {
            background: linear-gradient(135deg, rgba(224,58,58,0.3), rgba(255,217,102,0.2));
            border-radius: 50px;
            padding: 6px 16px;
            display: inline-block;
            backdrop-filter: blur(8px);
            font-size: 0.7rem;
            border: 1px solid rgba(224,58,58,0.5);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        @media (min-width: 768px) {
            .hero-badge {
                padding: 8px 24px;
                font-size: 0.85rem;
                margin-bottom: 1.5rem;
            }
        }
        
        .hero-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, var(--mlbb-red), var(--mlbb-gold), #fff);
            background-size: 300% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shine 3s linear infinite;
            margin-bottom: 0.75rem;
        }
        
        @media (min-width: 576px) {
            .hero-title { font-size: 2.5rem; }
        }
        
        @media (min-width: 768px) {
            .hero-title { font-size: 3.5rem; }
        }
        
        @keyframes shine {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }
        
        .hero-subtitle {
            font-size: 0.9rem;
            color: #b0b0b0;
            max-width: 700px;
            margin: 0 auto;
        }
        
        @media (min-width: 768px) {
            .hero-subtitle { font-size: 1.2rem; }
        }
        
        /* Rank Badges - Responsive */
        .rank-strip {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        
        @media (min-width: 576px) {
            .rank-strip { gap: 1rem; }
        }
        
        @media (min-width: 768px) {
            .rank-strip { gap: 1.5rem; margin-top: 2rem; }
        }
        
        .rank-badge {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            padding: 4px 10px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.65rem;
            transition: 0.3s;
            border-left: 2px solid;
        }
        
        @media (min-width: 576px) {
            .rank-badge {
                padding: 6px 14px;
                font-size: 0.75rem;
                border-left: 3px solid;
            }
        }
        
        @media (min-width: 768px) {
            .rank-badge {
                padding: 8px 20px;
                font-size: 0.85rem;
            }
        }
        
        .rank-warrior { border-left-color: #8e44ad; color: #8e44ad; }
        .rank-epic { border-left-color: var(--epic-purple); color: var(--epic-purple); }
        .rank-legend { border-left-color: var(--legendary-orange); color: var(--legendary-orange); }
        .rank-mythic { border-left-color: var(--mythic-blue); color: var(--mythic-blue); }
        .rank-glory { border-left-color: var(--mlbb-gold); color: var(--mlbb-gold); }
        
        /* Section Titles - Responsive */
        .section-title {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        @media (min-width: 768px) {
            .section-title { margin-bottom: 3rem; }
        }
        
        .section-title h2 {
            font-family: 'Orbitron', monospace;
            font-size: 1.3rem;
            font-weight: 700;
            display: inline-block;
            background: linear-gradient(135deg, #fff, var(--mlbb-red));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        @media (min-width: 576px) {
            .section-title h2 { font-size: 1.8rem; }
        }
        
        @media (min-width: 768px) {
            .section-title h2 { font-size: 2.5rem; }
        }
        
        .section-title p {
            color: #8a8a8a;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .section-title p { font-size: 1rem; }
        }
        
        /* Product Cards - Responsive Grid */
        .product-card {
            background: linear-gradient(145deg, #0f121c, #0a0d15);
            border-radius: 20px;
            border: 1px solid rgba(224,58,58,0.2);
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            overflow: hidden;
            height: 100%;
            position: relative;
            backdrop-filter: blur(4px);
        }
        
        @media (min-width: 768px) {
            .product-card { border-radius: 24px; }
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--mlbb-red), var(--mlbb-gold), var(--mlbb-red));
            transform: translateX(-100%);
            transition: 0.5s;
        }
        
        .product-card:hover::before { transform: translateX(0); }
        .product-card:hover {
            transform: translateY(-8px);
            border-color: var(--mlbb-red);
            box-shadow: 0 25px 40px -12px rgba(224,58,58,0.4);
        }
        
        @media (min-width: 768px) {
            .product-card:hover { transform: translateY(-12px); }
        }
        
        .card-img-top {
            object-fit: cover;
            width: 100%;
            height: 180px;
            transition: 0.5s;
            background: linear-gradient(135deg, #1a1f2e, #0f121c);
            cursor: pointer;
        }
        
        @media (min-width: 576px) {
            .card-img-top { height: 200px; }
        }
        
        @media (min-width: 768px) {
            .card-img-top { height: 220px; }
        }
        
        .card-body {
            padding: 1rem;
        }
        
        @media (min-width: 768px) {
            .card-body { padding: 1.25rem; }
        }
        
        .card-title {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 8px;
        }
        
        @media (min-width: 768px) {
            .card-title { font-size: 1.2rem; }
        }
        
        .card-text-desc {
            font-size: 0.75rem;
            color: #a0a0a0;
            min-height: 45px;
        }
        
        @media (min-width: 768px) {
            .card-text-desc {
                font-size: 0.85rem;
                min-height: 55px;
            }
        }
        
        /* Account Stats - Responsive */
        .account-stats {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin: 10px 0;
            padding: 6px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        @media (min-width: 768px) {
            .account-stats { gap: 12px; margin: 12px 0; padding: 8px 0; }
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 0.65rem;
            background: rgba(0,0,0,0.4);
            padding: 3px 6px;
            border-radius: 20px;
        }
        
        @media (min-width: 768px) {
            .stat-item {
                gap: 5px;
                font-size: 0.75rem;
                padding: 4px 10px;
            }
        }
        
        /* Price Chips */
        .badge-currency {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin: 10px 0 8px;
        }
        
        @media (min-width: 768px) {
            .badge-currency {
                gap: 10px;
                margin: 15px 0 12px;
            }
        }
        
        .price-chip {
            background: rgba(0,0,0,0.5);
            padding: 4px 8px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.7rem;
            backdrop-filter: blur(4px);
        }
        
        @media (min-width: 768px) {
            .price-chip {
                padding: 6px 14px;
                font-size: 0.9rem;
            }
        }
        
        .price-php { color: #4caf50; border-left: 2px solid #4caf50; background: rgba(76,175,80,0.1);}
        .price-usd { color: #2196f3; border-left: 2px solid #2196f3;}
        .price-thb { color: #ff9800; border-left: 2px solid #ff9800;}
        
        /* Buy Button */
        .btn-buy {
            background: linear-gradient(95deg, var(--mlbb-red), #b71c1c);
            border: none;
            border-radius: 40px;
            padding: 8px 0;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 1px;
            transition: 0.25s;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .btn-buy {
                padding: 12px 0;
                font-size: 1rem;
            }
        }
        
        .featured-badge, .sold-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            border-radius: 30px;
            padding: 3px 10px;
            font-size: 0.6rem;
            z-index: 2;
        }
        
        @media (min-width: 768px) {
            .featured-badge, .sold-badge {
                top: 15px;
                left: 15px;
                padding: 5px 15px;
                font-size: 0.7rem;
            }
        }
        
        .featured-badge {
            background: linear-gradient(135deg, var(--mlbb-gold), #ffaa33);
            color: #1a1f2e;
            font-weight: 800;
        }
        
        .sold-badge {
            background: #dc3545;
            color: white;
            font-weight: 800;
        }
        
        /* Installment Card Styles (NEW) */
        .installment-card {
            background: linear-gradient(145deg, #0f121c, #0a0d15);
            border-radius: 20px;
            border: 1px solid rgba(46, 204, 113, 0.3);
            transition: all 0.4s ease;
            overflow: hidden;
            height: 100%;
            position: relative;
        }
        
        .installment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--installment-green), #27ae60, var(--installment-green));
            transform: translateX(-100%);
            transition: 0.5s;
        }
        
        .installment-card:hover::before { transform: translateX(0); }
        .installment-card:hover {
            transform: translateY(-8px);
            border-color: var(--installment-green);
            box-shadow: 0 25px 40px -12px rgba(46, 204, 113, 0.3);
        }
        
        .installment-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, var(--installment-green), #27ae60);
            border-radius: 30px;
            padding: 4px 12px;
            font-size: 0.6rem;
            font-weight: 700;
            color: white;
            z-index: 2;
        }
        
        @media (min-width: 768px) {
            .installment-badge {
                top: 15px;
                right: 15px;
                padding: 5px 15px;
                font-size: 0.7rem;
            }
        }
        
        .installment-icon {
            font-size: 2rem;
            color: var(--installment-green);
            margin-bottom: 0.5rem;
        }
        
        @media (min-width: 768px) {
            .installment-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        }
        
        /* Image Modal - Responsive */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            backdrop-filter: blur(15px);
        }
        
        .modal-content-image {
            position: relative;
            margin: auto;
            padding: 15px;
            width: 95%;
            max-width: 1200px;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .modal-content-image img {
            max-width: 100%;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 16px;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #fff;
            font-size: 30px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(224,58,58,0.3);
            border-radius: 50%;
            cursor: pointer;
        }
        
        @media (min-width: 768px) {
            .close-modal {
                top: 30px;
                right: 50px;
                font-size: 40px;
                width: 50px;
                height: 50px;
            }
        }
        
        /* Purchase Modal - Fully Responsive with Scroll */
        .purchase-modal {
            display: none;
            position: fixed;
            z-index: 10001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            backdrop-filter: blur(15px);
        }
        
        .purchase-modal-content {
            position: relative;
            background: linear-gradient(145deg, #0f121c, #0a0d15);
            margin: 5% auto;
            padding: 0;
            width: 92%;
            max-width: 1100px;
            max-height: 90vh;
            border-radius: 24px;
            border: 1px solid rgba(224,58,58,0.3);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .purchase-modal-content {
                margin: 2% auto;
                width: 90%;
                max-height: 94vh;
                border-radius: 32px;
            }
        }
        
        .purchase-modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(224,58,58,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            background: rgba(10, 13, 21, 0.95);
        }
        
        @media (min-width: 768px) {
            .purchase-modal-header { padding: 20px 25px; }
        }
        
        .purchase-modal-header h3 {
            margin: 0;
            font-family: 'Orbitron', monospace;
            color: var(--mlbb-red);
            font-size: 1.1rem;
        }
        
        @media (min-width: 768px) {
            .purchase-modal-header h3 { font-size: 1.5rem; }
        }
        
        .close-purchase {
            font-size: 24px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        
        @media (min-width: 768px) {
            .close-purchase { font-size: 28px; width: 35px; height: 35px; }
        }
        
        /* SCROLLABLE BODY */
        .purchase-modal-body {
            padding: 15px 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            overflow-y: auto;
            overflow-x: hidden;
            flex: 1;
            scrollbar-width: thin;
        }
        
        @media (min-width: 768px) {
            .purchase-modal-body {
                padding: 25px 30px;
                gap: 35px;
            }
        }
        
        .purchase-modal-body::-webkit-scrollbar {
            width: 5px;
        }
        
        @media (min-width: 768px) {
            .purchase-modal-body::-webkit-scrollbar { width: 6px; }
        }
        
        .purchase-product-image {
            flex: 1;
            min-width: 250px;
            text-align: center;
            cursor: pointer;
            position: relative;
        }
        
        @media (min-width: 768px) {
            .purchase-product-image { min-width: 320px; }
        }
        
        .purchase-product-image img {
            width: 100%;
            max-width: 100%;
            height: auto;
            max-height: 250px;
            object-fit: contain;
            border-radius: 16px;
        }
        
        @media (min-width: 576px) {
            .purchase-product-image img { max-height: 300px; }
        }
        
        @media (min-width: 768px) {
            .purchase-product-image img { max-height: 400px; max-width: 450px; }
        }
        
        .purchase-product-info {
            flex: 1.5;
        }
        
        .purchase-product-info h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-family: 'Orbitron', monospace;
            color: var(--mlbb-gold);
        }
        
        @media (min-width: 768px) {
            .purchase-product-info h4 { font-size: 1.8rem; margin-bottom: 15px; }
        }
        
        .purchase-product-info .description {
            color: #b0b0b0;
            margin-bottom: 15px;
            line-height: 1.5;
            font-size: 0.8rem;
            background: rgba(0,0,0,0.3);
            padding: 10px;
            border-radius: 12px;
        }
        
        @media (min-width: 768px) {
            .purchase-product-info .description {
                margin-bottom: 25px;
                line-height: 1.8;
                font-size: 1rem;
                padding: 15px;
                border-radius: 16px;
            }
        }
        
        .purchase-prices {
            display: flex;
            gap: 8px;
            margin: 15px 0;
            flex-wrap: wrap;
        }
        
        @media (min-width: 768px) {
            .purchase-prices { gap: 15px; margin: 25px 0; }
        }
        
        .purchase-price {
            background: rgba(0,0,0,0.5);
            padding: 6px 12px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .purchase-price { padding: 10px 20px; font-size: 1.1rem; }
        }
        
        .contact-options {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        
        @media (min-width: 768px) {
            .contact-options { margin-top: 30px; padding-top: 25px; }
        }
        
        .contact-options h5 {
            margin-bottom: 10px;
            color: var(--mlbb-gold);
            font-size: 1rem;
        }
        
        @media (min-width: 768px) {
            .contact-options h5 { margin-bottom: 15px; font-size: 1.2rem; }
        }
        
        .contact-info-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        
        .contact-info-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            background: rgba(0,0,0,0.3);
            padding: 8px 12px;
            border-radius: 40px;
            flex-wrap: wrap;
        }
        
        @media (min-width: 576px) {
            .contact-info-list li { flex-wrap: nowrap; }
        }
        
        @media (min-width: 768px) {
            .contact-info-list li {
                gap: 12px;
                margin-bottom: 15px;
                padding: 12px 18px;
            }
        }
        
        .contact-info-list i {
            font-size: 1.1rem;
            width: 24px;
        }
        
        @media (min-width: 768px) {
            .contact-info-list i { font-size: 1.4rem; width: 32px; }
        }
        
        .contact-info-list .contact-value {
            color: #e0e0e0;
            font-weight: 600;
            margin-left: auto;
        }
        
        .btn-fb {
            background: var(--fb-blue);
            border: none;
            border-radius: 40px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: 0.3s;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        @media (min-width: 768px) {
            .btn-fb { padding: 12px 28px; font-size: 1rem; }
        }
        
        /* Feature Cards - Responsive */
        .features-section {
            background: linear-gradient(180deg, transparent, rgba(224,58,58,0.05), transparent);
            padding: 2rem 0;
            margin: 1rem 0;
        }
        
        @media (min-width: 768px) {
            .features-section { padding: 3rem 0; margin: 2rem 0; }
        }
        
        .feature-card {
            background: rgba(15, 18, 28, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.2rem;
            text-align: center;
            transition: 0.3s;
            border: 1px solid rgba(224,58,58,0.2);
            height: 100%;
        }
        
        @media (min-width: 768px) {
            .feature-card { padding: 2rem; border-radius: 28px; }
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
        
        @media (min-width: 768px) {
            .feature-icon { font-size: 3rem; margin-bottom: 1rem; }
        }
        
        .feature-card h5 {
            font-size: 0.9rem;
        }
        
        @media (min-width: 768px) {
            .feature-card h5 { font-size: 1.25rem; }
        }
        
        .feature-card p {
            font-size: 0.7rem;
        }
        
        @media (min-width: 768px) {
            .feature-card p { font-size: 0.85rem; }
        }
        
        /* Seller CTA - Responsive */
        .seller-cta {
            background: linear-gradient(135deg, rgba(224,58,58,0.15), rgba(0,0,0,0.5));
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            margin: 1rem 0;
        }
        
        @media (min-width: 768px) {
            .seller-cta {
                border-radius: 28px;
                padding: 2.5rem;
                margin: 2rem 0;
            }
        }
        
        .seller-cta h3 {
            font-size: 1.2rem;
        }
        
        @media (min-width: 768px) {
            .seller-cta h3 { font-size: 1.8rem; }
        }
        
        /* Stats Bar - Responsive */
        .stats-bar .p-3 h3 {
            font-size: 1.1rem;
        }
        
        @media (min-width: 768px) {
            .stats-bar .p-3 h3 { font-size: 1.5rem; }
        }
        
        /* Footer - Responsive */
        footer {
            background: #03050a;
            border-top: 1px solid rgba(224,58,58,0.3);
            margin-top: 2rem;
            padding: 1.5rem 0;
        }
        
        @media (min-width: 768px) {
            footer { margin-top: 4rem; padding: 2rem 0; }
        }
        
        /* Utilities */
        .image-hint, .purchase-image-hint {
            font-size: 0.6rem;
            padding: 3px 8px;
        }
        
        @media (min-width: 768px) {
            .image-hint, .purchase-image-hint {
                font-size: 0.75rem;
                padding: 6px 15px;
            }
        }
        
        .zoom-buttons {
            bottom: 80px;
            right: 20px;
        }
        
        @media (min-width: 768px) {
            .zoom-buttons { bottom: 100px; right: 50px; }
        }
        
        .zoom-btn {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        @media (min-width: 768px) {
            .zoom-btn { width: 45px; height: 45px; font-size: 1.2rem; }
        }
        
        .download-btn-modal {
            bottom: 80px;
            left: 20px;
            padding: 8px 16px;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .download-btn-modal { bottom: 100px; left: 50px; padding: 12px 20px; font-size: 1rem; }
        }
        
        .back-to-index {
            top: 15px;
            left: 15px;
            padding: 6px 12px;
            font-size: 0.7rem;
        }
        
        @media (min-width: 768px) {
            .back-to-index { top: 30px; left: 50px; padding: 10px 20px; font-size: 1rem; }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="images/skull.png" alt="Skull Icon" width="35"> JAM<span style="color: var(--mlbb-red);">LAGZ</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background: var(--mlbb-red); border-radius: 8px;">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#accounts"><i class="fas fa-users"></i> MLBB Accounts</a></li>
                <li class="nav-item"><a class="nav-link" href="#installments"><i class="fas fa-calendar-alt"></i> Installment</a></li>
                <li class="nav-item"><a class="nav-link" href="#sell"><i class="fas fa-dollar-sign"></i> Sell Account</a></li>
                <li class="nav-item"><a class="nav-link" href="admin/login.php"><i class="fas fa-crown"></i> Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-headset"></i> Support</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="hero-badge">
            <i class="fas fa-trophy"></i> MLBB Account Marketplace PH <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="hero-title">BUY & SELL<br>MLBB ACCOUNTS</h1>
        <p class="hero-subtitle">Premium Mobile Legends Accounts with High Ranks, Rare Skins, and Collector Items. 100% Safe & Secure Transactions!</p>
        
        <div class="rank-strip">
            <span class="rank-badge rank-warrior"><i class="fas fa-chess-pawn"></i> Renowned Collector</span>
            <span class="rank-badge rank-epic"><i class="fas fa-star"></i> Exalted Collector</span>
            <span class="rank-badge rank-legend"><i class="fas fa-crown"></i> Mega Collector</span>
            <span class="rank-badge rank-mythic"><i class="fas fa-dragon"></i> World Collector</span>
            <span class="rank-badge rank-glory"><i class="fas fa-gem"></i> Universal Collector</span>
        </div>
        
        <a href="#accounts" class="btn btn-buy mt-4" style="width: auto; padding: 12px 32px; display: inline-block;">
            <i class="fas fa-search"></i> BROWSE ACCOUNTS
        </a>
    </div>
</div>

<!-- Available Products Grid Section -->
<div class="container my-5" id="accounts">
    <div class="section-title">
        <h2><i class="fas fa-gem"></i> AVAILABLE MLBB ACCOUNTS</h2>
        <p>Click on any image to view full size | Click "Purchase Account" to see details and contact us</p>
    </div>

    <div class="row g-4">
        <?php if (count($available_products) > 0): ?>
            <?php foreach ($available_products as $product): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="product-card position-relative">
                        <?php if ($product['featured'] == 1): ?>
                            <span class="featured-badge"><i class="fas fa-crown"></i> FEATURED ACCOUNT</span>
                        <?php endif; ?>
                        
                        <?php
                        $rank_class = 'IMAGE DETAILS';
                        $rank_icon = '<i class="fas fa-star"></i>';
                        $rank_name = 'IMAGE DETAILS';
                        $product_text = strtolower($product['name'] . ' ' . $product['description']);
                        
                        if (strpos($product_text, 'IMAGE DETAILS') !== false) {
                            $rank_class = 'tag-mythic';
                            $rank_icon = '<i class="fas fa-dragon"></i>';
                            $rank_name = 'IMAGE DETAILS';
                        } elseif (strpos($product_text, 'legend') !== false || strpos($product_text, 'IMAGE DETAILS') !== false) {
                            $rank_class = 'tag-legend';
                            $rank_icon = '<i class="fas fa-crown"></i>';
                            $rank_name = 'IMAGE DETAILS';
                        } elseif (strpos($product_text, 'IMAGE DETAILS') !== false) {
                            $rank_class = 'tag-collector';
                            $rank_icon = '<i class="fas fa-gem"></i>';
                            $rank_name = 'IMAGE DETAILS';
                        }
                        ?>
                        <span class="account-tag <?php echo $rank_class; ?>">
                            <?php echo $rank_icon; ?> <?php echo $rank_name; ?> RANK
                        </span>
                        
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onclick="openImageModal('uploads/<?php echo htmlspecialchars($product['image']); ?>', '<?php echo htmlspecialchars($product['name']); ?>')"
                             style="cursor: pointer;">
                        
                        <div class="image-hint">
                            <i class="fas fa-search-plus"></i> Click to zoom
                        </div>
                        
                        <div class="card-body p-4">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                            
                            <!-- Account Stats (from database) -->
                            <div class="account-stats">
                                <span class="stat-item"><i class="fas fa-chart-line"></i> <?php echo $product['skins'] ?? 0; ?>+ Skins</span>
                                <span class="stat-item"><i class="fas fa-crown"></i> <?php echo $product['heroes'] ?? 0; ?> Heroes</span>
                                <span class="stat-item"><i class="fas fa-gem"></i> <?php echo $product['diamonds'] ?? 0; ?> Diamonds</span>
                                <span class="stat-item"><i class="fas fa-calendar"></i> <?php echo $product['account_year'] ?? 2050; ?> Account</span>
                            </div>
                            
                            <div class="badge-currency">
                                <span class="price-chip price-php"><i class="fas fa-coins"></i> ₱<?php echo number_format($product['price_php'], 2); ?></span>
                                <span class="price-chip price-usd"><i class="fas fa-dollar-sign"></i> $<?php echo number_format($product['price_usd'], 2); ?></span>
                                <span class="price-chip price-thb"><i class="fas fa-baht-sign"></i> ฿<?php echo number_format($product['price_thb'], 2); ?></span>
                            </div>
                            
                            <button type="button" class="btn btn-buy" onclick="openPurchaseModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                <i class="fas fa-shopping-cart me-2"></i> PURCHASE ACCOUNT
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-dark" style="background: rgba(224,58,58,0.1); border: 1px solid var(--mlbb-red);">
                    <i class="fas fa-exclamation-triangle"></i> No available accounts at the moment. Check back soon!
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ============================================ -->
<!-- INSTALLMENT PLANS SECTION (NEW)              -->
<!-- ============================================ -->
<?php if (count($installment_plans) > 0): ?>
<div class="container my-5" id="installments">
    <div class="section-title">
        <h2><i class="fas fa-calendar-alt"></i> FLEXIBLE INSTALLMENT PLANS</h2>
        <p>Own your dream MLBB account with our easy payment plans!</p>
    </div>
    
    <div class="row g-4">
        <?php foreach ($installment_plans as $plan): ?>
            <div class="col-md-6 col-lg-4">
                <div class="installment-card position-relative">
                    <span class="installment-badge">
                        <i class="fas fa-calendar-check"></i> Installment Available
                    </span>
                    
                    <?php if (!empty($plan['image'])): ?>
                        <img src="uploads/installments/<?php echo htmlspecialchars($plan['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($plan['title']); ?>"
                             style="height: 200px; object-fit: cover; cursor: pointer;"
                             onclick="openImageModal('uploads/installments/<?php echo htmlspecialchars($plan['image']); ?>', '<?php echo htmlspecialchars($plan['title']); ?>')">
                        <div class="image-hint">
                            <i class="fas fa-search-plus"></i> Click to zoom
                        </div>
                    <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center" 
                             style="height: 200px; background: linear-gradient(135deg, #1a1f2e, #0f121c);">
                            <i class="fas fa-calendar-alt installment-icon"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-hand-holding-usd installment-icon"></i>
                        </div>
                        <h5 class="card-title text-center"><?php echo htmlspecialchars($plan['title']); ?></h5>
                        <p class="card-text-desc text-center mt-3">
                            <?php echo nl2br(htmlspecialchars($plan['description'])); ?>
                        </p>
                        
                        <div class="text-center mt-4">
                            <button class="btn btn-buy" style="background: linear-gradient(95deg, #2ecc71, #27ae60);" 
                                    onclick="alert('Contact us on Facebook to avail this installment plan!\n\nFacebook: <?php echo $facebook_url; ?>')">
                                <i class="fas fa-calendar-check me-2"></i> INQUIRE NOW
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Sold Accounts Section (Recent Sales) -->
<?php if (count($sold_products) > 0): ?>
<div class="container my-5">
    <div class="section-title">
        <h2><i class="fas fa-history"></i> RECENTLY SOLD ACCOUNTS</h2>
        <p>These premium accounts have been sold. Check our available listings!</p>
    </div>
    
    <div class="row g-4">
        <?php foreach ($sold_products as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="product-card position-relative sold-card">
                    <span class="sold-badge">
                        <i class="fas fa-check-circle"></i> SOLD
                    </span>
                    
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         style="filter: grayscale(0.3); cursor: pointer;"
                         onclick="openImageModal('uploads/<?php echo htmlspecialchars($product['image']); ?>', '<?php echo htmlspecialchars($product['name']); ?> (SOLD)')">
                    
                    <div class="card-body p-3">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text-desc small"><?php echo substr(htmlspecialchars($product['description']), 0, 60); ?>...</p>
                        
                        <div class="badge-currency">
                            <span class="price-chip price-php"><i class="fas fa-coins"></i> ₱<?php echo number_format($product['price_php'], 2); ?></span>
                        </div>
                        
                        <div class="text-center mt-2">
                            <span class="badge bg-danger"><i class="fas fa-check-circle"></i> SOLD OUT</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Sell Account CTA Section -->
<div class="container" id="sell">
    <div class="seller-cta">
        <i class="fas fa-exchange-alt fa-3x text-danger mb-3"></i>
        <h3 class="fw-bold">WANT TO SELL YOUR MLBB ACCOUNT?</h3>
        <p class="text-secondary">Get the best value for your Mobile Legends account with rare skins, high rank, and collector items.</p>
        <button class="btn btn-buy" style="width: auto; padding: 12px 32px;" onclick="alert('Contact us: jameslaag1228@gmail.com or DM on Facebook!')">
            <i class="fas fa-envelope"></i> SELL YOUR ACCOUNT NOW
        </button>
        <p class="small text-secondary mt-3">✨ Free evaluation | Fast payout | 100% secure</p>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-shield-alt"></i> WHY TRUST JAMLAGZ?</h2>
            <p>Safe & secure MLBB account trading platform</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt" style="color: var(--mlbb-gold);"></i></div>
                    <h5>Account Guarantee</h5>
                    <p class="small">All accounts are safe and refundable.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-handshake" style="color: #4caf50;"></i></div>
                    <h5>Secure Escrow</h5>
                    <p class="small">Funds held securely until account transfer complete</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-credit-card" style="color: #2196f3;"></i></div>
                    <h5>Multiple Payments</h5>
                    <p class="small">GCash, PayMaya, Bank Transfer, GoTyme</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset" style="color: var(--mlbb-red);"></i></div>
                    <h5>24/7 Support</h5>
                    <p class="small">Live chat at Facebook for buyers and sellers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div class="container mb-5">
    <div class="row text-center g-3">
        <div class="col-6 col-md-3">
            <div class="p-3" style="background: rgba(224,58,58,0.1); border-radius: 20px;">
                <h3 class="text-danger fw-bold">200+</h3>
                <small>Accounts Sold</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3" style="background: rgba(224,58,58,0.1); border-radius: 20px;">
                <h3 class="text-danger fw-bold">95%</h3>
                <small>Satisfaction Rate</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3" style="background: rgba(224,58,58,0.1); border-radius: 20px;">
                <h3 class="text-danger fw-bold">24/7</h3>
                <small>Support Active</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3" style="background: rgba(224,58,58,0.1); border-radius: 20px;">
                <h3 class="text-danger fw-bold">⭐ 4.5★</h3>
                <small>Trust Rating</small>
            </div>
        </div>
    </div>
</div>

<!-- Proof of Successful Transactions Section -->
<?php if (count($testimonials) > 0): ?>
<div class="container my-5">
    <div class="section-title">
        <h2><i class="fas fa-star"></i> PROOF OF SUCCESSFUL TRANSACTIONS</h2>
        <p>Real customers, real transactions - See our happy buyers!</p>
    </div>
    
    <div class="row g-4">
        <?php foreach ($testimonials as $testimonial): ?>
            <div class="col-md-6 col-lg-4">
                <div class="product-card position-relative" style="background: linear-gradient(145deg, #0f121c, #0a0d15);">
                    <div class="position-relative">
                        <img src="uploads/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>" 
                             class="card-img-top" 
                             alt="Proof of Transaction"
                             style="height: 250px; object-fit: cover; cursor: pointer;"
                             onclick="openImageModal('uploads/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>', 'Proof of Transaction - <?php echo htmlspecialchars($testimonial['customer_name'] ?? 'Customer'); ?>')">
                        <div class="image-hint">
                            <i class="fas fa-search-plus"></i> Click to zoom
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user-circle fa-2x text-success me-2"></i>
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($testimonial['customer_name'] ?? 'Happy Customer'); ?></h5>
                        </div>
                        <p class="card-text-desc" style="font-style: italic;">
                            <i class="fas fa-quote-left text-danger me-1"></i> 
                            <?php echo nl2br(htmlspecialchars($testimonial['description'])); ?>
                        </p>
                        <div class="text-end mt-2">
                            <small class="text-muted"><i class="fas fa-check-circle text-success"></i> Verified Transaction</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Image Modal/Lightbox -->
<div id="imageModal" class="image-modal">
    <div class="modal-content-image">
        <span class="close-modal" onclick="closeImageModal()">&times;</span>
        <a href="index.php" class="back-to-index">
            <i class="fas fa-arrow-left"></i> Back to Landing Page
        </a>
        <div class="zoom-buttons">
            <div class="zoom-btn" onclick="zoomIn()"><i class="fas fa-search-plus"></i></div>
            <div class="zoom-btn" onclick="zoomOut()"><i class="fas fa-search-minus"></i></div>
            <div class="zoom-btn" onclick="resetZoom()"><i class="fas fa-sync-alt"></i></div>
        </div>
        <div class="download-btn-modal" onclick="downloadImage()">
            <i class="fas fa-download"></i> Download Image
        </div>
        <img id="modalImage" src="" alt="Product Image">
        <div class="modal-caption" id="modalCaption"></div>
    </div>
</div>

<!-- ENHANCED PURCHASE MODAL - WITH SCROLLABLE BODY -->
<div id="purchaseModal" class="purchase-modal">
    <div class="purchase-modal-content">
        <div class="purchase-modal-header">
            <h3><i class="fas fa-shopping-cart"></i> Purchase Account</h3>
            <span class="close-purchase" onclick="closePurchaseModal()">&times;</span>
        </div>
        <div class="purchase-modal-body">
            <div class="purchase-product-image" onclick="openPurchaseImageModal()">
                <img id="purchaseImage" src="" alt="Product Image">
                <div class="purchase-image-hint">
                    <i class="fas fa-search-plus"></i> Click to view full size & download
                </div>
            </div>
            <div class="purchase-product-info">
                <h4 id="purchaseName"></h4>
                <p class="description" id="purchaseDescription"></p>
                <div class="purchase-prices">
                    <span class="purchase-price price-php" id="purchasePhp"></span>
                    <span class="purchase-price price-usd" id="purchaseUsd"></span>
                    <span class="purchase-price price-thb" id="purchaseThb"></span>
                </div>
                <div class="contact-options">
                    <h5><i class="fas fa-comment"></i> How to Purchase?</h5>
                    <p class="small text-secondary">Contact us directly via phone or email to complete your purchase. We'll assist you with the payment and account transfer process.</p>
                    
                    <!-- Contact Information List with Icons -->
                    <ul class="contact-info-list">
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span class="contact-text">Contact Number:</span>
                            <span class="contact-value">09813527364</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span class="contact-text">Email Address:</span>
                            <span class="contact-value">jameslaag1228@gmail.com</span>
                        </li>
                    </ul>
                    
                    <div class="contact-buttons">
                        <a href="<?php echo $facebook_url; ?>" target="_blank" class="btn-fb">
                            <i class="fab fa-facebook me-2"></i> Message on Facebook
                        </a>
                    </div>
                    <p class="small text-secondary mt-3"><i class="fas fa-shield-alt"></i> Secure payment via GCash, PayMaya, or Bank Transfer</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5 class="text-danger">JamLagz</h5>
                <p class="small">Premium MLBB Account Marketplace since 2023</p>
                <p class="small"><i class="fas fa-check-circle text-success"></i> 100% Verified Accounts</p>
            </div>
            <div class="col-md-4 mb-3">
                <h6>Account Categories</h6>
                <p class="small">
                    <span class="d-block">Notable Skins</span>
                    <span class="d-block">Rare Accounts</span>
                    <span class="d-block">Collector Skin Accounts</span>
                    <span class="d-block">Starlight Member Accounts</span>
                </p>
            </div>
            <div class="col-md-4 mb-3">
                <h6>Connect with us</h6>
                <div>
                    <a href="<?php echo $facebook_url; ?>" target="_blank" class="text-secondary me-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <i class="fab fa-discord fa-lg me-3 text-secondary"></i>
                    <i class="fab fa-instagram fa-lg me-3 text-secondary"></i>
                    <i class="fab fa-tiktok fa-lg text-secondary"></i>
                </div>
                <p class="small mt-2"><i class="fas fa-envelope"></i> jameslaag1228@gmail.com</p>
            </div>
        </div>
        <hr class="bg-secondary">
        <p class="mb-0 small">© <?php echo date('Y'); ?> JamLagz MLBB Account Buy & Sell Platform — All rights reserved. Mobile Legends is a trademark of Moonton.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let currentZoom = 1;
    let modalImg = null;
    let currentImageSrc = '';
    
    // Image Modal Functions
    function openImageModal(imageSrc, productName) {
        modalImg = document.getElementById('modalImage');
        const modal = document.getElementById('imageModal');
        const caption = document.getElementById('modalCaption');
        
        currentImageSrc = imageSrc;
        modalImg.src = imageSrc;
        caption.innerHTML = '<i class="fas fa-image"></i> ' + productName + ' - Click outside or press ESC to close';
        modal.style.display = 'block';
        currentZoom = 1;
        modalImg.style.transform = `scale(${currentZoom})`;
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    function zoomIn() {
        if (currentZoom < 3) {
            currentZoom += 0.25;
            modalImg.style.transform = `scale(${currentZoom})`;
        }
    }
    
    function zoomOut() {
        if (currentZoom > 0.5) {
            currentZoom -= 0.25;
            modalImg.style.transform = `scale(${currentZoom})`;
        }
    }
    
    function resetZoom() {
        currentZoom = 1;
        modalImg.style.transform = `scale(${currentZoom})`;
    }
    
    function downloadImage() {
        if (currentImageSrc) {
            const link = document.createElement('a');
            link.href = currentImageSrc;
            link.download = currentImageSrc.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
    
    // Purchase Modal Functions
    let currentProduct = null;
    let currentPurchaseImageSrc = '';
    
    function openPurchaseModal(product) {
        currentProduct = product;
        currentPurchaseImageSrc = 'uploads/' + product.image;
        document.getElementById('purchaseImage').src = currentPurchaseImageSrc;
        document.getElementById('purchaseName').innerText = product.name;
        document.getElementById('purchaseDescription').innerText = product.description;
        document.getElementById('purchasePhp').innerHTML = '<i class="fas fa-coins"></i> ₱' + parseFloat(product.price_php).toFixed(2);
        document.getElementById('purchaseUsd').innerHTML = '<i class="fas fa-dollar-sign"></i> $' + parseFloat(product.price_usd).toFixed(2);
        document.getElementById('purchaseThb').innerHTML = '<i class="fas fa-baht-sign"></i> ฿' + parseFloat(product.price_thb).toFixed(2);
        
        document.getElementById('purchaseModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    // Function to open image modal from purchase modal (clickable image)
    function openPurchaseImageModal() {
        if (currentPurchaseImageSrc) {
            const productName = document.getElementById('purchaseName').innerText;
            openImageModal(currentPurchaseImageSrc, productName + ' (Account Image)');
        }
    }
    
    function closePurchaseModal() {
        document.getElementById('purchaseModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Close modals on outside click
    window.onclick = function(event) {
        const imageModal = document.getElementById('imageModal');
        const purchaseModal = document.getElementById('purchaseModal');
        if (event.target === imageModal) {
            closeImageModal();
        }
        if (event.target === purchaseModal) {
            closePurchaseModal();
        }
    }
    
    // Close with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
            closePurchaseModal();
        }
    });
    
    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    // Animation on scroll
    const cards = document.querySelectorAll('.product-card, .feature-card, .installment-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });
</script>
</body>
</html>