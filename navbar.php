<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Website</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .menu-button {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #495057;
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .menu-button:hover {
            background-color: #6c757d;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        .modal-body .btn-link {
            font-size: 1.1rem;
            color: #007bff;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }

        .modal-body .btn-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="menu-button" data-toggle="modal" data-target="#menuModal">Menu</div>

    <!-- Modal -->
    <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalLabel">Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled">
                        <li><a class="btn btn-link" href="index.php">Home</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a class="btn btn-link" href="upload.php">Upload</a></li>
                        <li><a class="btn btn-link" href="profile.php">Profile</a></li>
                        <li><a class="btn btn-link" href="logout.php">Logout</a></li>
                        <?php else: ?>
                        <li><a class="btn btn-link" href="login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
