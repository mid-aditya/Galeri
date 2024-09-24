<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['foto_id'])) {
    $foto_id = $_GET['foto_id'];
    
    $result = mysqli_query($conn, "SELECT * FROM foto WHERE FotoID='$foto_id'");
    $foto = mysqli_fetch_assoc($result);

    if (!$foto) {
        die('Foto tidak ditemukan.');
    }
    
    // Check if user is the owner of the photo
    if ($foto['UserID'] != $_SESSION['user_id']) {
        die('Anda tidak memiliki izin untuk mengedit foto ini.');
    }
} else {
    die('ID foto tidak diberikan.');
}

// Handle Update
if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    mysqli_query($conn, "UPDATE foto SET JudulFoto='$judul', Deskripsi='$deskripsi' WHERE FotoID='$foto_id'");
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Foto</h2>
        <form method="post">
            <div class="form-group">
                <label for="judul">Judul Foto</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($foto['JudulFoto']) ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Foto</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($foto['Deskripsi']) ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
