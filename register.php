<?php
// Koneksi ke database
include 'koneksi.php';

// Tangani form pendaftaran
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);

    // Cek apakah username sudah ada
    $result = mysqli_query($conn, "SELECT * FROM user WHERE Username='$username'");
    if (mysqli_num_rows($result) > 0) {
        $error = "Username sudah digunakan.";
    } else {
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Masukkan data ke database
        $query = "INSERT INTO user (Username, Password, Email, NamaLengkap) VALUES ('$username', '$hashed_password', '$email', '$nama_lengkap')";
        if (mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #3b82f6, #ec4899, #fbbf24, #22c55e);
            background-size: 400% 400%;
            animation: aurora 15s ease infinite;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
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

        .register-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .register-container h2 {
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 1.75rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
            font-size: 1rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
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

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .mt-2 {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mt-2">Register</button>
            <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        </form>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
