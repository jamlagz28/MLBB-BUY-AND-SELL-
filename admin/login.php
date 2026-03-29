<?php
session_start();

$error = "";

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Your fixed credentials
    $admin_user = "JamLagz28-Admin";
    $admin_pass = "09302413380James282003";

    if ($username === $admin_user && $password === $admin_pass) {
        // FIXED: Use the same session variable that dashboard.php checks
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_name'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - JamLagz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a0f1e, #03060c);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }
        
        /* Animated background effect */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(224,58,58,0.08) 0%, transparent 50%),
                        radial-gradient(circle at 80% 70%, rgba(255,217,102,0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .login-card {
            background: rgba(20, 28, 45, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            border: 1px solid #ff4d6d;
            box-shadow: 0 25px 45px -12px rgba(0,0,0,0.5);
            position: relative;
            z-index: 1;
        }
        
        .btn-accent {
            background: linear-gradient(95deg, #ff4d6d, #ff1e4d);
            border: none;
            border-radius: 40px;
            padding: 12px;
            font-weight: 700;
            transition: 0.3s;
        }
        
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,77,109,0.4);
        }
        
        .btn-outline-light {
            border-radius: 40px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        .form-control {
            background: #1a1f2e;
            border: 1px solid #ff4d6d;
            color: white;
        }
        
        .form-control:focus {
            background: #1a1f2e;
            border-color: #ff758f;
            box-shadow: 0 0 0 0.2rem rgba(255,77,109,0.25);
            color: white;
        }
        
        .form-control::placeholder {
            color: #8a8a8a;
        }
        
        h3 {
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #ff4d6d);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Divider style */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .divider span {
            padding: 0 10px;
            color: #8a8a8a;
            font-size: 0.85rem;
        }
        
        /* Back button animation */
        .back-btn {
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateX(-5px);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card login-card p-4 shadow-lg">
                <div class="text-center mb-4">
                    <i class="fas fa-crown fa-3x text-danger mb-2"></i>
                    <h3>JamLagz Admin</h3>
                    <p class="text-secondary small">Secure access to account management</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label text-light"><i class="fas fa-user"></i> Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter admin username" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>

                    <button type="submit" class="btn btn-accent w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>
                
                <!-- Back to Landing Page Button -->
                <a href="../index.php" class="btn btn-outline-light w-100 back-btn">
                    <i class="fas fa-arrow-left me-2"></i> Back to Landing Page
                </a>
                
                <div class="text-center mt-4">
                    <small class="text-secondary">⚠️ For authorized personnel only</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>