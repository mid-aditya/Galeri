<?php
session_start();
include 'koneksi.php';
include 'navbar.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$notification = '';

if (isset($_POST['submit'])) {
    $judul_foto = mysqli_real_escape_string($conn, $_POST['judul_foto']);
    $deskripsi_foto = mysqli_real_escape_string($conn, $_POST['deskripsi_foto']);
    $nama_file = $_FILES['namafile']['name'];
    $tmp_foto = $_FILES['namafile']['tmp_name'];
    $tanggal = date('Y-m-d');
    $album_id = isset($_POST['album_id']) ? $_POST['album_id'] : null;
    $user_id = $_SESSION['user_id'];

    // Ensure the uploads directory exists
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Full path to save the file
    $file_path = $upload_dir . basename($nama_file);

    if ($album_id && !empty($nama_file) && move_uploaded_file($tmp_foto, $file_path)) {
        $insert = mysqli_query($conn, "INSERT INTO foto (JudulFoto, DeskripsiFoto, Tanggal, NamaFile, AlbumID, UserID) VALUES ('$judul_foto', '$deskripsi_foto', '$tanggal', '$nama_file', '$album_id', '$user_id')");
        if ($insert) {
            $notification = 'success';
        } else {
            $notification = 'database';
        }
    } else {
        $notification = 'upload';
    }
}

// Fetch Albums
$albums = mysqli_query($conn, "SELECT * FROM album");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Foto</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            color: #000000; /* Ensure text is visible on background */
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #3b82f6, #ec4899, #fbbf24, #22c55e);
            background-size: 400% 400%;
            animation: aurora 15s ease infinite;
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

        /* Navbar Styles */
        .navbar {
            background-color: #343a40;
        }

        .navbar .navbar-brand {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar .navbar-brand:hover {
            color: #adb5bd;
            text-decoration: none;
        }

        .navbar .nav-link {
            color: #ffffff;
            font-size: 16px;
            margin-right: 15px;
        }

        .navbar .nav-link:hover {
            color: #adb5bd;
            text-decoration: underline;
        }

        .navbar .navbar-nav .nav-item {
            margin-left: 15px;
        }

        /* Form Container */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-wrapper {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            padding: 0.75rem 1.25rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 0.25rem;
            color: #ffffff;
            padding: 0.75rem 1.25rem;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            border-radius: 0.25rem;
            margin-top: 1rem;
            padding: 0.75rem 1.25rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-wrapper">
            <h2 class="text-center mb-4">Upload Foto</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul_foto">Judul Foto</label>
                    <input type="text" class="form-control" name="judul_foto" id="judul_foto" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi_foto">Deskripsi Foto</label>
                    <textarea class="form-control" name="deskripsi_foto" id="deskripsi_foto" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="namafile">Pilih Foto</label>
                    <input type="file" class="form-control" name="namafile" id="namafile" required>
                </div>
                <div class="form-group">
                    <label for="album_id">Pilih Album</label>
                    <select class="form-control" name="album_id" id="album_id" required>
                        <?php while ($album = mysqli_fetch_assoc($albums)): ?>
                            <option value="<?= htmlspecialchars($album['AlbumID']) ?>"><?= htmlspecialchars($album['NamaAlbum']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary mt-2 w-100">Upload</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Modal for Notifications -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($notification === 'success'): ?>
                        Foto berhasil diupload.
                    <?php elseif ($notification === 'database'): ?>
                        Gagal mengupload foto ke database.
                    <?php elseif ($notification === 'upload'): ?>
                        Gagal mengupload file.
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($notification): ?>
                $('#notificationModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>
