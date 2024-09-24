<?php
session_start();
include 'koneksi.php';
include 'navbar.php';

// Handle Like
if (isset($_POST['like'])) {
    $foto_id = $_POST['foto_id'];
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO `like` (FotoID, UserID) VALUES ('$foto_id', '$user_id')");
}

// Handle Comment
if (isset($_POST['comment'])) {
    $foto_id = $_POST['foto_id'];
    $user_id = $_SESSION['user_id'];
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
    mysqli_query($conn, "INSERT INTO comment (FotoID, UserID, CommentText) VALUES ('$foto_id', '$user_id', '$comment_text')");
    header("Location: index.php");
    exit();
}

// Handle Delete
if (isset($_POST['delete'])) {
    $foto_id = $_POST['foto_id'];
    $user_id = $_SESSION['user_id'];

    $result = mysqli_query($conn, "SELECT UserID FROM foto WHERE FotoID='$foto_id'");
    $photo = mysqli_fetch_assoc($result);

    if ($photo['UserID'] == $user_id) {
        mysqli_begin_transaction($conn);

        try {
            mysqli_query($conn, "DELETE FROM `like` WHERE FotoID='$foto_id'");
            mysqli_query($conn, "DELETE FROM comment WHERE FotoID='$foto_id'");
            mysqli_query($conn, "DELETE FROM foto WHERE FotoID='$foto_id'");
            
            mysqli_commit($conn);
            
            echo '<div class="alert alert-success">Foto berhasil dihapus.</div>';
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo '<div class="alert alert-danger">Gagal menghapus foto. ' . $e->getMessage() . '</div>';
        }

        header("Location: index.php");
        exit();
    } else {
        echo '<div class="alert alert-danger">Anda tidak memiliki izin untuk menghapus foto ini.</div>';
    }
}

// Handle Update
if (isset($_POST['edit'])) {
    $foto_id = $_POST['foto_id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']); // pastikan nama kolom sesuai

    // Periksa apakah deskripsi ada dalam tabel
    $result = mysqli_query($conn, "UPDATE foto SET JudulFoto='$judul', DeskripsiFoto='$deskripsi' WHERE FotoID='$foto_id'");
    
    if ($result) {
        echo '<div class="alert alert-success">Foto berhasil diperbarui.</div>';
    } else {
        echo '<div class="alert alert-danger">Gagal memperbarui foto: ' . mysqli_error($conn) . '</div>';
    }
}


// Fetch Photos
$tampil = mysqli_query($conn, "SELECT * FROM foto INNER JOIN user ON foto.UserID=user.UserID");

if (!$tampil) {
    die('Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Galeri Foto</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
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

        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0,0,0,0.4);
        }
        .card-img-top {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .card-body {
            padding: 1rem;
            color: #ffffff;
        }
        .alert {
            margin-top: 20px;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
            animation: fadeIn 2s ease-in-out;
        }
        .welcome-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            animation: slideInLeft 1s ease-in-out;
        }
        .welcome-section img {
            border-radius: 0.5rem;
            max-width: 300px;
            margin-right: 20px;
            animation: zoomIn 1s ease-in-out;
        }
        .welcome-text {
            flex: 1;
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: #ffffff;
            padding: 40px 0;
            margin-top: 40px;
            animation: fadeInUp 1s ease-in-out;
        }
        .footer .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
        }
        .footer .footer-content .contact-info,
        .footer .footer-content .social-media {
            flex: 1;
            margin: 10px;
        }
        .footer h5 {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .footer p {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.2s ease-in-out;
        }
        .footer a:hover {
            color: #007bff;
        }
        .footer iframe {
            border: 0;
            width: 100%;
            height: 200px;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-message">
            Selamat datang di Web Galeri!
        </div>

        <!-- Welcome Section with Image and Text -->
        <div class="welcome-section">
            <img src="Foto1.jpg" alt="Welcome Image">
            <div class="welcome-text">
                <h2>Hola! Saya Galih Aditya Pratama</h2>
                <p>Di sini, Anda bisa menjelajahi berbagai foto menarik yang diunggah oleh komunitas kami. Nikmati keindahan seni fotografi dan temukan karya-karya luar biasa dari berbagai fotografer berbakat. Setiap foto yang kami tampilkan telah melalui proses seleksi yang ketat untuk memastikan hanya yang terbaik yang sampai ke galeri kami.</p>
            </div>
        </div>
        <!-- End Welcome Section -->

        <div class="row">
            <?php foreach ($tampil as $tampils): ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card">
                    <img src="uploads/<?= htmlspecialchars($tampils['NamaFile']) ?>" class="card-img-top" alt="Foto">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($tampils['JudulFoto']) ?></h5>
                        <p class="card-text text-muted">by: <?= htmlspecialchars($tampils['Username']) ?></p>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="foto_id" value="<?= htmlspecialchars($tampils['FotoID']) ?>">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button type="submit" name="like" class="btn btn-primary">Like</button>
                                <?php if ($tampils['UserID'] == $_SESSION['user_id']): ?>
                                    <a href="#edit-<?= htmlspecialchars($tampils['FotoID']) ?>" class="btn btn-warning" data-toggle="collapse">Edit</a>
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>
                        <form method="post" class="mt-2">
                            <input type="hidden" name="foto_id" value="<?= htmlspecialchars($tampils['FotoID']) ?>">
                            <textarea name="comment_text" class="form-control mb-2" placeholder="Write a comment"></textarea>
                            <button type="submit" name="comment" class="btn btn-secondary">Comment</button>
                        </form>
                        <div class="mt-2">
                            <h6>Comments:</h6>
                            <?php
                            $comments = mysqli_query($conn, "SELECT * FROM comment INNER JOIN user ON comment.UserID=user.UserID WHERE comment.FotoID=" . $tampils['FotoID']);
                            foreach ($comments as $comment):
                            ?>
                            <p><strong><?= htmlspecialchars($comment['Username']) ?>:</strong> <?= htmlspecialchars($comment['CommentText']) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Photo Form -->
                <div id="edit-<?= htmlspecialchars($tampils['FotoID']) ?>" class="collapse">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5>Edit Foto</h5>
                            <form method="post">
                                <input type="hidden" name="foto_id" value="<?= htmlspecialchars($tampils['FotoID']) ?>">
                                <div class="form-group">
                                    <label for="judul">Judul Foto</label>
                                    <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($tampils['JudulFoto']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Foto</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" required><?= htmlspecialchars($tampils['DeskripsiFoto']) ?></textarea>
                                </div>
                                <button type="submit" name="edit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Edit Photo Form -->
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="contact-info">
                    <h5>Contact Us</h5>
                    <p>Email: contact@example.com</p>
                    <p>Phone: +123 456 7890</p>
                    <p>Address: 123 Main Street, Anytown, USA</p>
                </div>
                <div class="social-media">
                    <h5>Follow Us</h5>
                    <p><a href="#">Facebook</a></p>
                    <p><a href="#">Twitter</a></p>
                    <p><a href="#">Instagram</a></p>
                </div>
                <div class="map">
                    <h5>Find Us</h5>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.0498825216764!2d106.82211897453686!3d-6.640728064916709!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c8b16ee07ef5%3A0x14ab253dd267de49!2sSMK%20Negeri%204%20Bogor%20(Nebrazka)!5e0!3m2!1sid!2sid!4v1727184008133!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
