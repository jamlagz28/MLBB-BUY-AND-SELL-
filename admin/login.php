<?php
session_start();

$error = "";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // YOUR FIXED CREDENTIALS - Exactly as provided
    $admin_user = "JamLagz28-Admin";
    $admin_pass = "09302413380James282003";

    // Direct comparison - no extra spaces or hidden characters
    if ($username === $admin_user && $password === $admin_pass) {
        // Set session variables for dashboard access
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_name'] = $username;
        $_SESSION['login_time'] = time();
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password. Please try again.";
        // Optional: Log failed attempt for debugging
        error_log("Failed login attempt for username: " . $username);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <title>Admin Login - JamLagz</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0f1e, #03060c);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            padding: 1.5rem;
            margin: 0;
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
        
        /* Main container - fully responsive */
        .login-wrapper {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 0;
        }
        
        .login-card {
            background: rgba(20, 28, 45, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            border: 1px solid #ff4d6d;
            box-shadow: 0 25px 45px -12px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .card-content {
            padding: 2rem;
        }
        
        /* Responsive padding */
        @media (max-width: 576px) {
            .card-content {
                padding: 1.5rem;
            }
            body {
                padding: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .card-content {
                padding: 1.25rem;
            }
        }
        
        .btn-accent {
            background: linear-gradient(95deg, #ff4d6d, #ff1e4d);
            border: none;
            border-radius: 40px;
            padding: 12px 20px;
            font-weight: 700;
            transition: 0.3s;
            width: 100%;
            color: white;
            font-size: 1rem;
        }
        
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,77,109,0.4);
            background: linear-gradient(95deg, #ff5a78, #ff2a55);
        }
        
        .btn-accent:active {
            transform: translateY(0);
        }
        
        .btn-outline-light {
            border-radius: 40px;
            padding: 12px 20px;
            font-weight: 600;
            transition: 0.3s;
            width: 100%;
            border: 1px solid rgba(255,255,255,0.3);
            background: transparent;
            color: #f8f9fa;
        }
        
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
            border-color: #ff4d6d;
        }
        
        .form-control {
            background: #1a1f2e;
            border: 1px solid #ff4d6d;
            color: white;
            border-radius: 40px;
            padding: 12px 18px;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            background: #1a1f2e;
            border-color: #ff758f;
            box-shadow: 0 0 0 0.2rem rgba(255,77,109,0.25);
            color: white;
            outline: none;
        }
        
        .form-control::placeholder {
            color: #8a8a8a;
            font-weight: 400;
        }
        
        /* Label styling */
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #e9ecef;
        }
        
        h3 {
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #ff4d6d);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: calc(1.5rem + 0.5vw);
            margin-bottom: 0.5rem;
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
        
        /* Alert styling */
        .alert {
            border-radius: 50px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: rgba(255,77,109,0.2);
            backdrop-filter: blur(5px);
            color: #ff8a9f;
            border-left: 4px solid #ff4d6d;
        }
        
        /* Icon spacing */
        .fa, .fas, .far {
            margin-right: 6px;
        }
        
        /* Credentials helper box - shows what to use (only visible to admin) */
        .credentials-hint {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 10px;
            margin-top: 15px;
            font-size: 0.75rem;
            text-align: center;
            color: #aaa;
            border: 1px dashed rgba(255,77,109,0.3);
        }
        
        .credentials-hint code {
            color: #ff4d6d;
            background: rgba(0,0,0,0.3);
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 0.7rem;
        }
        
        /* Responsive adjustments for tablets */
        @media (min-width: 768px) and (max-width: 1024px) {
            .login-wrapper {
                max-width: 550px;
            }
            .card-content {
                padding: 2.2rem;
            }
        }
        
        /* Large screens */
        @media (min-width: 1400px) {
            .login-wrapper {
                max-width: 550px;
            }
        }
        
        /* Focus visibility */
        button:focus-visible, a:focus-visible, input:focus-visible {
            outline: 2px solid #ff4d6d;
            outline-offset: 2px;
        }
        
        /* Remove default outline for mouse users */
        .btn:focus {
            box-shadow: none;
        }
        
        /* Smooth transitions */
        input, button, a {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="card-content">
            <div class="text-center mb-4">
                <i class="fas fa-crown fa-3x mb-2" style="color: #ff4d6d;"></i>
                <h3>JamLagz Admin</h3>
                <p class="text-secondary small">Secure access to account management</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter admin username" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <button type="submit" class="btn btn-accent">
                    <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                </button>
            </form>
            
            <!-- Divider -->
            <div class="divider">
                <span>or</span>
            </div>
            
            <!-- Back to Landing Page Button -->
            <a href="../index.php" class="btn btn-outline-light back-btn">
                <i class="fas fa-arrow-left"></i> Back to Landing Page
            </a>
            
            <div class="text-center mt-4">
                <small class="text-secondary">⚠️ For authorized personnel only</small>
            </div>
            
            <!-- Hidden helper for correct credentials (only as reference, removed in production if needed) -->
            <div class="credentials-hint">

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Additional client-side validation to ensure credentials are entered correctly -->
<script>
    (function() {
        // Optional: Add client-side hint but doesn't override server check
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const username = form.querySelector('[name="username"]').value.trim();
                const password = form.querySelector('[name="password"]').value;
                const correctUser = "JamLagz28-Admin";
                const correctPass = "09302413380James282003";
                
                // Just provide helpful console message (doesn't block submission)
                if (username !== correctUser || password !== correctPass) {
                    console.log("Login attempt with provided credentials");
                }
            });
        }
    })();
</script>

</body>
</html>