<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags for Bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Customized styling */
        .navbar {
            background-color: #333;
            border-bottom: 1px solid #eee;
        }
        .navbar-brand,
        .nav-link,
        .navbar-toggler-icon {
            color: white !important;
        }
        .profile-picture {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
        .d-flex a {
            margin: 0 10px;
            color: white !important;
            text-decoration: none;
            align-self: center;
        }
    </style>
</head>
<body>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">Dohmen's Social</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="discover2.php">Discover</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="demoPost.php">Post</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <a href="../main/profile.php"><img src="<?= $user['profile_image'] ?>" class="profile-picture" alt="Profile Picture"></a>
                <a href="../main/profile.php"><?= $user["username"] ?></a>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
