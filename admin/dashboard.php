<?php
// ============================================
// File: admin/dashboard.php
// Admin Dashboard - Manage Products, Testimonials & Installment Plans
// ============================================
session_start();

// Check for the correct session variable
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';

// ==================== PRODUCT MANAGEMENT ====================
// Handle product deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard.php?msg=deleted");
    exit;
}

// Handle mark as sold / available
if (isset($_GET['toggle_status'])) {
    $id = intval($_GET['toggle_status']);
    $status = isset($_GET['set_status']) ? $_GET['set_status'] : 'sold';
    $stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: dashboard.php?msg=status_updated");
    exit;
}

// Handle add/edit product
$editProduct = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $editProduct = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price_php = floatval($_POST['price_php']);
    $price_usd = floatval($_POST['price_usd']);
    $price_thb = floatval($_POST['price_thb']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : 'available';
    
    // Account Stats
    $skins = intval($_POST['skins']);
    $heroes = intval($_POST['heroes']);
    $diamonds = intval($_POST['diamonds']);
    $account_year = intval($_POST['account_year']);
    
    $image = $_POST['existing_image'] ?? '';
    
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } elseif (empty($image) && isset($_POST['existing_image_hidden'])) {
        $image = $_POST['existing_image_hidden'];
    }
    
    if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
        // update
        $id = $_POST['product_id'];
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, image=?, price_php=?, price_usd=?, price_thb=?, featured=?, status=?, skins=?, heroes=?, diamonds=?, account_year=? WHERE id=?");
        $stmt->execute([$name, $desc, $image, $price_php, $price_usd, $price_thb, $featured, $status, $skins, $heroes, $diamonds, $account_year, $id]);
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO products (name, description, image, price_php, price_usd, price_thb, featured, status, skins, heroes, diamonds, account_year) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$name, $desc, $image, $price_php, $price_usd, $price_thb, $featured, $status, $skins, $heroes, $diamonds, $account_year]);
    }
    header("Location: dashboard.php?msg=saved");
    exit;
}

// ==================== TESTIMONIALS MANAGEMENT ====================
// Handle testimonial deletion
if (isset($_GET['delete_testimonial'])) {
    $id = intval($_GET['delete_testimonial']);
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard.php?msg=testimonial_deleted");
    exit;
}

// Handle testimonial status toggle
if (isset($_GET['toggle_testimonial'])) {
    $id = intval($_GET['toggle_testimonial']);
    $status = isset($_GET['set_status']) ? $_GET['set_status'] : 'active';
    $stmt = $conn->prepare("UPDATE testimonials SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: dashboard.php?msg=testimonial_updated");
    exit;
}

// Handle add/edit testimonial
$editTestimonial = null;
if (isset($_GET['edit_testimonial'])) {
    $id = intval($_GET['edit_testimonial']);
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $editTestimonial = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_testimonial'])) {
    $description = trim($_POST['description']);
    $customer_name = trim($_POST['customer_name']);
    $status = isset($_POST['status']) ? $_POST['status'] : 'active';
    $image = $_POST['existing_image'] ?? '';
    
    // Handle image upload
    if (!empty($_FILES['testimonial_image']['name'])) {
        $target_dir = "../uploads/testimonials/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = time() . '_' . basename($_FILES["testimonial_image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["testimonial_image"]["tmp_name"], $target_file);
    }
    
    if (isset($_POST['testimonial_id']) && $_POST['testimonial_id'] > 0) {
        // update
        $id = $_POST['testimonial_id'];
        if ($image) {
            $stmt = $conn->prepare("UPDATE testimonials SET image=?, description=?, customer_name=?, status=? WHERE id=?");
            $stmt->execute([$image, $description, $customer_name, $status, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE testimonials SET description=?, customer_name=?, status=? WHERE id=?");
            $stmt->execute([$description, $customer_name, $status, $id]);
        }
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO testimonials (image, description, customer_name, status) VALUES (?,?,?,?)");
        $stmt->execute([$image, $description, $customer_name, $status]);
    }
    header("Location: dashboard.php?msg=testimonial_saved");
    exit;
}

// ==================== INSTALLMENT PLANS MANAGEMENT ====================
// Handle installment deletion
if (isset($_GET['delete_installment'])) {
    $id = intval($_GET['delete_installment']);
    $stmt = $conn->prepare("DELETE FROM installment_plans WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard.php?msg=installment_deleted");
    exit;
}

// Handle installment status toggle
if (isset($_GET['toggle_installment'])) {
    $id = intval($_GET['toggle_installment']);
    $status = isset($_GET['set_status']) ? $_GET['set_status'] : 'active';
    $stmt = $conn->prepare("UPDATE installment_plans SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: dashboard.php?msg=installment_updated");
    exit;
}

// Handle add/edit installment plan
$editInstallment = null;
if (isset($_GET['edit_installment'])) {
    $id = intval($_GET['edit_installment']);
    $stmt = $conn->prepare("SELECT * FROM installment_plans WHERE id = ?");
    $stmt->execute([$id]);
    $editInstallment = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_installment'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = isset($_POST['status']) ? $_POST['status'] : 'active';
    $image = $_POST['existing_image'] ?? '';
    
    // Handle image upload
    if (!empty($_FILES['installment_image']['name'])) {
        $target_dir = "../uploads/installments/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = time() . '_' . basename($_FILES["installment_image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["installment_image"]["tmp_name"], $target_file);
    }
    
    if (isset($_POST['installment_id']) && $_POST['installment_id'] > 0) {
        // update
        $id = $_POST['installment_id'];
        if ($image) {
            $stmt = $conn->prepare("UPDATE installment_plans SET image=?, title=?, description=?, status=? WHERE id=?");
            $stmt->execute([$image, $title, $description, $status, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE installment_plans SET title=?, description=?, status=? WHERE id=?");
            $stmt->execute([$title, $description, $status, $id]);
        }
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO installment_plans (image, title, description, status) VALUES (?,?,?,?)");
        $stmt->execute([$image, $title, $description, $status]);
    }
    header("Location: dashboard.php?msg=installment_saved");
    exit;
}

// fetch all products by status
$available_products = $conn->query("SELECT * FROM products WHERE status = 'available' ORDER BY featured DESC, id DESC")->fetchAll();
$sold_products = $conn->query("SELECT * FROM products WHERE status = 'sold' ORDER BY id DESC")->fetchAll();

// fetch testimonials
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();

// fetch installment plans
$installment_plans = $conn->query("SELECT * FROM installment_plans ORDER BY id DESC")->fetchAll();

// Count stats
$total_products = count($available_products) + count($sold_products);
$featured_count = count(array_filter($available_products, function($p) { return $p['featured'] == 1; })) + count(array_filter($sold_products, function($p) { return $p['featured'] == 1; }));
$available_count = count($available_products);
$sold_count = count($sold_products);
$testimonial_count = count($testimonials);
$installment_count = count($installment_plans);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <title>Admin Dashboard - JamLagz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ========== RESET & BASE ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background: #0f1219; 
            color: white; 
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        /* ========== FULLY RESPONSIVE SIDEBAR ========== */
        .sidebar { 
            background: linear-gradient(180deg, #11161f 0%, #0a0d15 100%);
            min-height: 100vh; 
            border-right: 1px solid rgba(255,77,109,0.2);
            transition: all 0.3s ease;
        }
        
        .main-content { 
            padding: 1.5rem; 
        }
        
        /* Responsive Sidebar - collapses on tablets */
        @media (max-width: 992px) {
            .sidebar {
                min-height: auto;
                padding: 1rem !important;
            }
            .sidebar .nav {
                flex-direction: row !important;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }
            .sidebar .nav-item {
                margin-bottom: 0 !important;
            }
            .sidebar .nav-link {
                padding: 8px 16px !important;
                font-size: 0.85rem;
            }
            .sidebar hr {
                display: none;
            }
            .sidebar .text-center {
                margin-bottom: 1rem !important;
            }
            .sidebar .text-center h4 {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar .nav-link {
                padding: 6px 12px !important;
                font-size: 0.75rem;
            }
            .sidebar .text-center i {
                font-size: 2rem !important;
            }
            .sidebar .text-center h4 {
                font-size: 1.1rem;
            }
        }
        
        /* ========== CARD STYLES ========== */
        .card-dark { 
            background: #1a1f2e; 
            border: none; 
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-available {
            border-left: 4px solid #28a745;
        }
        
        .card-sold {
            border-left: 4px solid #dc3545;
        }
        
        .card-testimonial {
            border-left: 4px solid #ffc107;
        }
        
        .card-installment {
            border-left: 4px solid #17a2b8;
        }
        
        /* ========== BUTTON STYLES ========== */
        .btn-danger-custom { 
            background: linear-gradient(95deg, #ff4d6d, #ff1e4d);
            border: none;
            border-radius: 40px;
            padding: 10px 24px;
            transition: 0.3s;
            color: white;
            font-weight: 600;
        }
        
        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,77,109,0.4);
            color: white;
        }
        
        .btn-view-site {
            background: linear-gradient(95deg, #28a745, #218838);
            border: none;
            border-radius: 40px;
            padding: 10px 24px;
            transition: 0.3s;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-view-site:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40,167,69,0.4);
            color: white;
        }
        
        .btn-sold {
            background: linear-gradient(95deg, #6c757d, #495057);
            border: none;
            border-radius: 40px;
            padding: 5px 12px;
            font-size: 0.75rem;
            transition: 0.3s;
            color: white;
        }
        
        .btn-available {
            background: linear-gradient(95deg, #28a745, #218838);
            border: none;
            border-radius: 40px;
            padding: 5px 12px;
            font-size: 0.75rem;
            transition: 0.3s;
            color: white;
        }
        
        /* ========== TABLE RESPONSIVE ========== */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        table { 
            color: #eef2ff; 
            min-width: 600px;
        }
        
        @media (max-width: 768px) {
            table {
                font-size: 0.8rem;
            }
            table td, table th {
                padding: 8px 6px !important;
            }
            .btn-sold, .btn-available, .btn-warning-custom {
                padding: 3px 8px;
                font-size: 0.65rem;
            }
        }
        
        .table-dark {
            background: #1a1f2e;
        }
        
        .table-dark thead th {
            border-bottom-color: #ff4d6d;
            color: #ff4d6d;
            font-weight: 600;
        }
        
        /* ========== HEADER & STATS ========== */
        .admin-header {
            background: linear-gradient(135deg, rgba(255,77,109,0.1), rgba(0,0,0,0));
            padding: 1.5rem;
            border-radius: 24px;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .admin-header {
                padding: 1rem;
            }
            .admin-header h2 {
                font-size: 1.3rem;
            }
            .admin-header p {
                font-size: 0.8rem;
            }
        }
        
        .stat-card {
            background: #1a1f2e;
            border-radius: 20px;
            padding: 1rem;
            text-align: center;
            border-left: 3px solid #ff4d6d;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        @media (max-width: 576px) {
            .stat-card h3 {
                font-size: 1.5rem;
            }
            .stat-card small {
                font-size: 0.7rem;
            }
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(255,77,109,0.3);
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .section-header h4 {
            margin: 0;
            font-family: 'Orbitron', monospace;
            font-size: 1.1rem;
        }
        
        @media (max-width: 576px) {
            .section-header h4 {
                font-size: 0.9rem;
            }
            .section-header .count-badge {
                font-size: 0.7rem;
            }
        }
        
        .section-header .count-badge {
            background: rgba(255,77,109,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        /* ========== BADGES & STATS ========== */
        .badge-sold {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        
        .badge-available {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        
        .stat-badge {
            background: rgba(255,77,109,0.2);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            display: inline-block;
            margin: 2px 0;
        }
        
        .stats-group {
            background: rgba(0,0,0,0.3);
            padding: 15px;
            border-radius: 16px;
            margin-top: 10px;
        }
        
        .stats-group label {
            font-size: 0.85rem;
            margin-bottom: 5px;
            color: #ff4d6d;
        }
        
        .stats-group input {
            background: #2a2f3e;
            border: 1px solid #ff4d6d;
            color: white;
        }
        
        .stats-group input:focus {
            background: #2a2f3e;
            border-color: #ff758f;
            color: white;
        }
        
        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        /* ========== TABS RESPONSIVE ========== */
        .nav-tabs-custom {
            border-bottom: 1px solid rgba(255,77,109,0.3);
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .nav-tabs-custom .nav-link {
            color: white;
            background: transparent;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.2s;
        }
        
        @media (max-width: 576px) {
            .nav-tabs-custom .nav-link {
                padding: 8px 12px;
                font-size: 0.8rem;
            }
        }
        
        .nav-tabs-custom .nav-link.active {
            color: #ff4d6d;
            border-bottom: 2px solid #ff4d6d;
            background: transparent;
        }
        
        .nav-tabs-custom .nav-link:hover {
            color: #ff4d6d;
        }
        
        /* ========== MODAL RESPONSIVE ========== */
        .modal-content {
            border-radius: 24px;
        }
        
        @media (max-width: 576px) {
            .modal-body {
                padding: 1rem;
            }
            .modal-footer {
                padding: 0.75rem;
            }
        }
        
        /* ========== IMAGE PREVIEW ========== */
        .testimonial-image-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }
        
        /* ========== UTILITIES ========== */
        .header-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .header-buttons {
                margin-top: 10px;
            }
            .btn-view-site, .btn-danger-custom {
                padding: 6px 16px;
                font-size: 0.8rem;
            }
        }
        
        .btn-sm {
            margin: 2px;
        }
        
        /* Row gutter fix */
        .row {
            margin-left: 0;
            margin-right: 0;
        }
        
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
            overflow-x: hidden;
        }
        
        /* Sold items opacity */
        .card-sold tr {
            opacity: 0.85;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row g-0">
        <!-- Sidebar - Fully Responsive -->
        <div class="col-md-3 col-lg-2 sidebar p-3">
            <div class="text-center mb-4">
                <i class="fas fa-crown fa-3x text-danger"></i>
                <h4 class="text-danger mt-2">JamLagz</h4>
                <p class="small text-secondary">Admin Panel</p>
            </div>
            <hr class="bg-secondary">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white active" style="background: rgba(255,77,109,0.1); border-radius: 12px;">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#products" class="nav-link text-white" data-bs-toggle="tab" onclick="document.querySelector('#products-tab').click()">
                        <i class="fas fa-box me-2"></i> Products
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#testimonials" class="nav-link text-white" data-bs-toggle="tab" onclick="document.querySelector('#testimonials-tab').click()">
                        <i class="fas fa-star me-2"></i> Testimonials
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#installments" class="nav-link text-white" data-bs-toggle="tab" onclick="document.querySelector('#installments-tab').click()">
                        <i class="fas fa-calendar-alt me-2"></i> Installment Plan
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="logout.php" class="nav-link text-white">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="admin-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2><i class="fas fa-store me-2"></i>Admin Dashboard</h2>
                        <p class="text-secondary mb-0">Manage products, proof of successful transactions, and installment plans</p>
                    </div>
                    <div class="header-buttons">
                        <span class="badge bg-danger me-2"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                        <a href="../index.php" target="_blank" class="btn-view-site">
                            <i class="fas fa-eye"></i> View Landing Page
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Stats Row - Fully Responsive -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <h3 class="text-danger mb-1"><?php echo $total_products; ?></h3>
                        <small>Total Products</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <h3 class="text-warning mb-1"><?php echo $featured_count; ?></h3>
                        <small>Featured Items</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <h3 class="text-success mb-1"><?php echo $available_count; ?></h3>
                        <small>Available</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <h3 class="text-info mb-1"><?php echo $installment_count; ?></h3>
                        <small>Installment Plans</small>
                    </div>
                </div>
            </div>
            
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> ✅ Operation successful!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs-custom" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-panel" type="button" role="tab">
                        <i class="fas fa-box"></i> Products Management
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="testimonials-tab" data-bs-toggle="tab" data-bs-target="#testimonials-panel" type="button" role="tab">
                        <i class="fas fa-star"></i> Proof of Transactions
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="installments-tab" data-bs-toggle="tab" data-bs-target="#installments-panel" type="button" role="tab">
                        <i class="fas fa-calendar-alt"></i> Installment Plan
                    </button>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Products Tab -->
                <div class="tab-pane fade show active" id="products-panel" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-danger-custom" data-bs-toggle="modal" data-bs-target="#productModal" onclick="clearForm()">
                            <i class="fas fa-plus"></i> Add New Product
                        </button>
                    </div>
                    
                    <!-- Available Products Section -->
                    <div class="card-dark card-available mb-4">
                        <div class="card-header bg-transparent border-bottom border-secondary p-3">
                            <div class="section-header">
                                <h4><i class="fas fa-check-circle text-success me-2"></i>Available for Sale</h4>
                                <span class="count-badge"><?php echo $available_count; ?> products</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <?php if(count($available_products) > 0): ?>
                                <div class="table-responsive-custom">
                                    <table class="table table-dark table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Stats</th>
                                                <th>PHP</th>
                                                <th>USD</th>
                                                <th>THB</th>
                                                <th>Featured</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($available_products as $p): ?>
                                            <tr>
                                                <td><?php echo $p['id']; ?></td>
                                                <td><img src="../uploads/<?php echo htmlspecialchars($p['image']); ?>" width="50" height="50" style="object-fit:cover; border-radius: 12px;" onerror="this.src='https://placehold.co/50x50?text=No+Image'"></td>
                                                <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                                                <td>
                                                    <span class="stat-badge"><i class="fas fa-chart-line"></i> <?php echo $p['skins'] ?? 0; ?> Skins</span><br>
                                                    <span class="stat-badge"><i class="fas fa-crown"></i> <?php echo $p['heroes'] ?? 0; ?> Heroes</span><br>
                                                    <span class="stat-badge"><i class="fas fa-gem"></i> <?php echo $p['diamonds'] ?? 0; ?> Diamonds</span><br>
                                                    <span class="stat-badge"><i class="fas fa-calendar"></i> <?php echo $p['account_year'] ?? 2024; ?></span>
                                                </td>
                                                <td class="text-success">₱<?php echo number_format($p['price_php'], 2); ?></td>
                                                <td class="text-info">$<?php echo number_format($p['price_usd'], 2); ?></td>
                                                <td class="text-warning">฿<?php echo number_format($p['price_thb'], 2); ?></td>
                                                <td><?php echo $p['featured'] ? '<span class="badge bg-warning text-dark">⭐ Featured</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
                                                <td>
                                                    <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning" onclick="editProduct(<?php echo htmlspecialchars(json_encode($p)); ?>); return false;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?toggle_status=<?php echo $p['id']; ?>&set_status=sold" class="btn btn-sm btn-sold" onclick="return confirm('Mark this account as SOLD?')">
                                                        <i class="fas fa-check-circle"></i> Mark Sold
                                                    </a>
                                                    <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete product?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                     </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <p>No available products. Click "Add New Product" to get started.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Sold Products Section -->
                    <div class="card-dark card-sold">
                        <div class="card-header bg-transparent border-bottom border-secondary p-3">
                            <div class="section-header">
                                <h4><i class="fas fa-check-circle text-danger me-2"></i>Already Sold</h4>
                                <span class="count-badge"><?php echo $sold_count; ?> products</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <?php if(count($sold_products) > 0): ?>
                                <div class="table-responsive-custom">
                                    <table class="table table-dark table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Stats</th>
                                                <th>PHP</th>
                                                <th>USD</th>
                                                <th>THB</th>
                                                <th>Featured</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($sold_products as $p): ?>
                                            <tr style="opacity: 0.8;">
                                                <td><?php echo $p['id']; ?></td>
                                                <td><img src="../uploads/<?php echo htmlspecialchars($p['image']); ?>" width="50" height="50" style="object-fit:cover; border-radius: 12px; filter: grayscale(0.3);" onerror="this.src='https://placehold.co/50x50?text=No+Image'"></td>
                                                <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                                                <td>
                                                    <span class="stat-badge"><i class="fas fa-chart-line"></i> <?php echo $p['skins'] ?? 0; ?> Skins</span><br>
                                                    <span class="stat-badge"><i class="fas fa-crown"></i> <?php echo $p['heroes'] ?? 0; ?> Heroes</span><br>
                                                    <span class="stat-badge"><i class="fas fa-gem"></i> <?php echo $p['diamonds'] ?? 0; ?> Diamonds</span><br>
                                                    <span class="stat-badge"><i class="fas fa-calendar"></i> <?php echo $p['account_year'] ?? 2024; ?></span>
                                                </td>
                                                <td class="text-success">₱<?php echo number_format($p['price_php'], 2); ?></td>
                                                <td class="text-info">$<?php echo number_format($p['price_usd'], 2); ?></td>
                                                <td class="text-warning">฿<?php echo number_format($p['price_thb'], 2); ?></td>
                                                <td><?php echo $p['featured'] ? '<span class="badge bg-warning text-dark">⭐ Featured</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
                                                <td>
                                                    <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning" onclick="editProduct(<?php echo htmlspecialchars(json_encode($p)); ?>); return false;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?toggle_status=<?php echo $p['id']; ?>&set_status=available" class="btn btn-sm btn-available" onclick="return confirm('Mark this account as AVAILABLE again?')">
                                                        <i class="fas fa-undo-alt"></i> Restore
                                                    </a>
                                                    <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete product?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-history"></i>
                                    <p>No sold products yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonials Tab -->
                <div class="tab-pane fade" id="testimonials-panel" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-danger-custom" data-bs-toggle="modal" data-bs-target="#testimonialModal" onclick="clearTestimonialForm()">
                            <i class="fas fa-plus"></i> Add Proof of Transaction
                        </button>
                    </div>
                    
                    <div class="card-dark card-testimonial">
                        <div class="card-header bg-transparent border-bottom border-secondary p-3">
                            <div class="section-header">
                                <h4><i class="fas fa-star text-warning me-2"></i>Proof of Successful Transactions</h4>
                                <span class="count-badge"><?php echo $testimonial_count; ?> entries</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <?php if(count($testimonials) > 0): ?>
                                <div class="table-responsive-custom">
                                    <table class="table table-dark table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Customer</th>
                                                <th>Description</th>
                                                <th>Date Added</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($testimonials as $t): ?>
                                            <tr>
                                                <td><?php echo $t['id']; ?></td>
                                                <td>
                                                    <img src="../uploads/testimonials/<?php echo htmlspecialchars($t['image']); ?>" width="60" height="60" style="object-fit:cover; border-radius: 12px;" onerror="this.src='https://placehold.co/60x60?text=No+Image'">
                                                </td>
                                                <td><strong><?php echo htmlspecialchars($t['customer_name'] ?? 'Anonymous'); ?></strong></td>
                                                <td><?php echo substr(htmlspecialchars($t['description']), 0, 50); ?>...</td>
                                                <td><?php echo date('M d, Y', strtotime($t['date_added'])); ?></td>
                                                <td>
                                                    <?php if($t['status'] == 'active'): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="?edit_testimonial=<?php echo $t['id']; ?>" class="btn btn-sm btn-warning" onclick="editTestimonial(<?php echo htmlspecialchars(json_encode($t)); ?>); return false;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if($t['status'] == 'active'): ?>
                                                        <a href="?toggle_testimonial=<?php echo $t['id']; ?>&set_status=inactive" class="btn btn-sm btn-secondary" onclick="return confirm('Hide this proof of transaction?')">
                                                            <i class="fas fa-eye-slash"></i> Hide
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?toggle_testimonial=<?php echo $t['id']; ?>&set_status=active" class="btn btn-sm btn-success" onclick="return confirm('Show this proof of transaction?')">
                                                            <i class="fas fa-eye"></i> Show
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="?delete_testimonial=<?php echo $t['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this proof?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-star"></i>
                                    <p>No proof of transactions yet. Add screenshots of successful sales!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Installment Plans Tab -->
                <div class="tab-pane fade" id="installments-panel" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-danger-custom" data-bs-toggle="modal" data-bs-target="#installmentModal" onclick="clearInstallmentForm()">
                            <i class="fas fa-plus"></i> Add Installment Plan
                        </button>
                    </div>
                    
                    <div class="card-dark card-installment">
                        <div class="card-header bg-transparent border-bottom border-secondary p-3">
                            <div class="section-header">
                                <h4><i class="fas fa-calendar-alt text-info me-2"></i>Installment Plans</h4>
                                <span class="count-badge"><?php echo $installment_count; ?> plans</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <?php if(count($installment_plans) > 0): ?>
                                <div class="table-responsive-custom">
                                    <table class="table table-dark table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Date Added</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($installment_plans as $plan): ?>
                                            <tr>
                                                <td><?php echo $plan['id']; ?></td>
                                                <td>
                                                    <?php if(!empty($plan['image'])): ?>
                                                        <img src="../uploads/installments/<?php echo htmlspecialchars($plan['image']); ?>" width="60" height="60" style="object-fit:cover; border-radius: 12px;" onerror="this.src='https://placehold.co/60x60?text=No+Image'">
                                                    <?php else: ?>
                                                        <img src="https://placehold.co/60x60?text=No+Image" width="60" height="60" style="object-fit:cover; border-radius: 12px;">
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?php echo htmlspecialchars($plan['title']); ?></strong></td>
                                                <td><?php echo substr(htmlspecialchars($plan['description']), 0, 60); ?>...</td>
                                                <td><?php echo date('M d, Y', strtotime($plan['date_added'])); ?></td>
                                                <td>
                                                    <?php if($plan['status'] == 'active'): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                 </td>
                                                <td>
                                                    <a href="?edit_installment=<?php echo $plan['id']; ?>" class="btn btn-sm btn-warning" onclick="editInstallment(<?php echo htmlspecialchars(json_encode($plan)); ?>); return false;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if($plan['status'] == 'active'): ?>
                                                        <a href="?toggle_installment=<?php echo $plan['id']; ?>&set_status=inactive" class="btn btn-sm btn-secondary" onclick="return confirm('Deactivate this installment plan?')">
                                                            <i class="fas fa-eye-slash"></i> Hide
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?toggle_installment=<?php echo $plan['id']; ?>&set_status=active" class="btn btn-sm btn-success" onclick="return confirm('Activate this installment plan?')">
                                                            <i class="fas fa-eye"></i> Show
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="?delete_installment=<?php echo $plan['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this installment plan?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                 </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar-alt"></i>
                                    <p>No installment plans yet. Add installment options for your customers!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title"><i class="fas fa-gem text-danger me-2"></i>Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id" value="0">
                    <input type="hidden" name="existing_image" id="existing_image" value="">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" id="prod_name" class="form-control bg-secondary text-white" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="prod_desc" rows="3" class="form-control bg-secondary text-white" required></textarea>
                    </div>
                    
                    <div class="stats-group">
                        <h6 class="text-danger mb-3"><i class="fas fa-chart-simple"></i> Account Statistics</h6>
                        <div class="row g-2">
                            <div class="col-6 col-md-3 mb-2">
                                <label class="form-label"><i class="fas fa-chart-line"></i> Skins</label>
                                <input type="number" name="skins" id="skins" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <label class="form-label"><i class="fas fa-crown"></i> Heroes</label>
                                <input type="number" name="heroes" id="heroes" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <label class="form-label"><i class="fas fa-gem"></i> Diamonds</label>
                                <input type="number" name="diamonds" id="diamonds" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <label class="form-label"><i class="fas fa-calendar"></i> Account Year</label>
                                <input type="number" name="account_year" id="account_year" class="form-control" value="2024" min="2010" max="2050">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-2 mt-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label">Price (PHP)</label>
                            <input type="number" step="0.01" name="price_php" id="price_php" class="form-control bg-secondary text-white" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" step="0.01" name="price_usd" id="price_usd" class="form-control bg-secondary text-white" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Price (THB)</label>
                            <input type="number" step="0.01" name="price_thb" id="price_thb" class="form-control bg-secondary text-white" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control bg-secondary text-white" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                        <div id="currentImagePreview" class="mt-2"></div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured">
                        <label class="form-check-label"> Mark as Featured Product</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_available" value="available" checked>
                        <label class="form-check-label me-3"> Available for Sale</label>
                        <input class="form-check-input" type="radio" name="status" id="status_sold" value="sold">
                        <label class="form-check-label"> Already Sold</label>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_product" class="btn btn-danger">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Testimonial Modal -->
<div class="modal fade" id="testimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title"><i class="fas fa-star text-warning me-2"></i>Proof of Transaction</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="testimonial_id" id="testimonial_id" value="0">
                    <input type="hidden" name="existing_image" id="testimonial_existing_image" value="">
                    <div class="mb-3">
                        <label class="form-label">Screenshot / Proof Image</label>
                        <input type="file" name="testimonial_image" class="form-control bg-secondary text-white" accept="image/*">
                        <small class="text-muted">Upload screenshot of successful transaction</small>
                        <div id="testimonialImagePreview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Name (Optional)</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control bg-secondary text-white" placeholder="e.g., John D. or Anonymous">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description / Testimonial</label>
                        <textarea name="description" id="testimonial_desc" rows="4" class="form-control bg-secondary text-white" required placeholder="e.g., Thank you for the smooth transaction! Got my Mythical Glory account within minutes!"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="testimonial_active" value="active" checked>
                        <label class="form-check-label me-3"> Show on Website</label>
                        <input class="form-check-input" type="radio" name="status" id="testimonial_inactive" value="inactive">
                        <label class="form-check-label"> Hide from Website</label>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_testimonial" class="btn btn-danger">Save Proof</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Installment Plan Modal -->
<div class="modal fade" id="installmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title"><i class="fas fa-calendar-alt text-info me-2"></i>Installment Plan Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="installment_id" id="installment_id" value="0">
                    <input type="hidden" name="existing_image" id="installment_existing_image" value="">
                    <div class="mb-3">
                        <label class="form-label">Plan Title</label>
                        <input type="text" name="title" id="installment_title" class="form-control bg-secondary text-white" required placeholder="e.g., 3-Month Installment Plan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan Image</label>
                        <input type="file" name="installment_image" class="form-control bg-secondary text-white" accept="image/*">
                        <small class="text-muted">Upload an image for this installment plan (optional)</small>
                        <div id="installmentImagePreview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="installment_desc" rows="5" class="form-control bg-secondary text-white" required placeholder="Describe the installment plan details, terms, payment schedule, etc..."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="installment_active" value="active" checked>
                        <label class="form-check-label me-3"> Active (Show on Website)</label>
                        <input class="form-check-input" type="radio" name="status" id="installment_inactive" value="inactive">
                        <label class="form-check-label"> Inactive (Hide from Website)</label>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_installment" class="btn btn-danger">Save Installment Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function clearForm() {
    document.getElementById('product_id').value = 0;
    document.getElementById('prod_name').value = '';
    document.getElementById('prod_desc').value = '';
    document.getElementById('price_php').value = '';
    document.getElementById('price_usd').value = '';
    document.getElementById('price_thb').value = '';
    document.getElementById('skins').value = 0;
    document.getElementById('heroes').value = 0;
    document.getElementById('diamonds').value = 0;
    document.getElementById('account_year').value = 2024;
    document.getElementById('featured').checked = false;
    document.getElementById('status_available').checked = true;
    document.getElementById('existing_image').value = '';
    document.getElementById('currentImagePreview').innerHTML = '';
}

function editProduct(product) {
    document.getElementById('product_id').value = product.id;
    document.getElementById('prod_name').value = product.name;
    document.getElementById('prod_desc').value = product.description;
    document.getElementById('price_php').value = product.price_php;
    document.getElementById('price_usd').value = product.price_usd;
    document.getElementById('price_thb').value = product.price_thb;
    document.getElementById('skins').value = product.skins || 0;
    document.getElementById('heroes').value = product.heroes || 0;
    document.getElementById('diamonds').value = product.diamonds || 0;
    document.getElementById('account_year').value = product.account_year || 2024;
    document.getElementById('featured').checked = product.featured == 1;
    if (product.status == 'sold') {
        document.getElementById('status_sold').checked = true;
    } else {
        document.getElementById('status_available').checked = true;
    }
    document.getElementById('existing_image').value = product.image;
    let preview = `<img src="../uploads/${product.image}" width="80" class="rounded me-2"> <span class="text-info">Current: ${product.image}</span>`;
    document.getElementById('currentImagePreview').innerHTML = preview;
    var modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
}

function clearTestimonialForm() {
    document.getElementById('testimonial_id').value = 0;
    document.getElementById('customer_name').value = '';
    document.getElementById('testimonial_desc').value = '';
    document.getElementById('testimonial_active').checked = true;
    document.getElementById('testimonial_existing_image').value = '';
    document.getElementById('testimonialImagePreview').innerHTML = '';
}

function editTestimonial(testimonial) {
    document.getElementById('testimonial_id').value = testimonial.id;
    document.getElementById('customer_name').value = testimonial.customer_name || '';
    document.getElementById('testimonial_desc').value = testimonial.description;
    if (testimonial.status == 'active') {
        document.getElementById('testimonial_active').checked = true;
    } else {
        document.getElementById('testimonial_inactive').checked = true;
    }
    document.getElementById('testimonial_existing_image').value = testimonial.image;
    let preview = `<img src="../uploads/testimonials/${testimonial.image}" width="80" class="rounded me-2"> <span class="text-info">Current image</span>`;
    document.getElementById('testimonialImagePreview').innerHTML = preview;
    var modal = new bootstrap.Modal(document.getElementById('testimonialModal'));
    modal.show();
}

function clearInstallmentForm() {
    document.getElementById('installment_id').value = 0;
    document.getElementById('installment_title').value = '';
    document.getElementById('installment_desc').value = '';
    document.getElementById('installment_active').checked = true;
    document.getElementById('installment_existing_image').value = '';
    document.getElementById('installmentImagePreview').innerHTML = '';
}

function editInstallment(plan) {
    document.getElementById('installment_id').value = plan.id;
    document.getElementById('installment_title').value = plan.title;
    document.getElementById('installment_desc').value = plan.description;
    if (plan.status == 'active') {
        document.getElementById('installment_active').checked = true;
    } else {
        document.getElementById('installment_inactive').checked = true;
    }
    document.getElementById('installment_existing_image').value = plan.image;
    if (plan.image) {
        let preview = `<img src="../uploads/installments/${plan.image}" width="80" class="rounded me-2"> <span class="text-info">Current image</span>`;
        document.getElementById('installmentImagePreview').innerHTML = preview;
    } else {
        document.getElementById('installmentImagePreview').innerHTML = '';
    }
    var modal = new bootstrap.Modal(document.getElementById('installmentModal'));
    modal.show();
}

<?php if($editProduct): ?>
window.addEventListener('DOMContentLoaded', function() { editProduct(<?php echo json_encode($editProduct); ?>); });
<?php endif; ?>
<?php if($editTestimonial): ?>
window.addEventListener('DOMContentLoaded', function() { editTestimonial(<?php echo json_encode($editTestimonial); ?>); });
<?php endif; ?>
<?php if($editInstallment): ?>
window.addEventListener('DOMContentLoaded', function() { editInstallment(<?php echo json_encode($editInstallment); ?>); });
<?php endif; ?>
</script>
</body>
</html>
