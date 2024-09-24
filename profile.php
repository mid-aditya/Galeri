<?php
session_start();
include 'koneksi.php';
include 'navbar.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Generate random color for the ring
$ring_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = md5($_POST['current_password']);
    $new_password = md5($_POST['new_password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Check if current password is correct
    $result = mysqli_query($conn, "SELECT Password FROM user WHERE Username='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && $user['Password'] === $current_password) {
        if ($new_password === $confirm_password) {
            mysqli_query($conn, "UPDATE user SET Password='$new_password' WHERE Username='$username'");
            $success = "Password successfully changed.";
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Galeri Foto</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #3b82f6, #ec4899, #fbbf24, #22c55e);
            background-size: 400% 400%;
            animation: aurora 15s ease infinite;
            color: #ffffff;
        }

        @keyframes aurora {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .container {
            max-width: 600px;
            margin-top: 40px;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .profile-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid <?= htmlspecialchars($ring_color) ?>;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            background-color: #f0f0f0;
        }

        .profile-circle span {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        h2, h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            color: #ddd;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
            font-size: 1rem;
            color: #333;
            background-color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            color: #ffffff;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <div class="profile-circle">
            <span><?= htmlspecialchars($username) ?></span>
        </div>
        <p class="text-center">Welcome, <?= htmlspecialchars($username) ?>!</p>

        <!-- Password Change Form -->
        <div>
            <h4>Change Password</h4>
            <form method="post">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
