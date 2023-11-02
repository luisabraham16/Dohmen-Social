<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .slideshow {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            z-index: -1;
            filter: brightness(80%) blur(2px);
        }

        .slideshow img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            opacity: 0;
        }

        .slideshow img.active {
            opacity: 1;
            transition: opacity 2s;
        }

        /* Blur Effect */
        body:before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: radial-gradient(circle at center, transparent, black 75%);
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Dohmen's Social</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Background Slideshow -->
    <div class="slideshow">
        <img class="active" src=".../includes/Logo.png" alt="">
        <img src=".../includes/Logo.png" alt="">
        <img src=".../includes/Logo.png" alt="">
    </div>

    <!-- Main Container -->
    <div class="container mt-5">

    <!-- Login Form -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="post" action="profile.php">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" name="username" value="<?= $_POST["username"] ?? ""; ?>">
                            </div>
                            <div class="form-group">
                                <label for="pw">Password:</label>
                                <input type="password" class="form-control" name="pw" value="<?= $_POST["pw"] ?? ""; ?>">
                            </div>
                            <input type="submit" class="btn btn-primary" name="sub" value="Login">
                        </form>
                        <p class="mt-3">
                            Don't have an account? <a href="register.php">Register here!</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        $userFound = false;
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user = [
                "username" => htmlspecialchars($_POST["username"]),
                "password" => htmlspecialchars($_POST["pw"])
            ];

            include "../includes/sessions.php";
            include "../includes/connection.php";
        
            // Updated to use prepared statements properly
            $getUser = $pdo->prepare("SELECT Password FROM Users WHERE Username= :username");
            $getUser->bindParam(":username", $user["username"]);
            $getUser->execute();
            $x = $getUser->fetch();
            
            if (password_verify($user["password"], $x["Password"]) && $x["username"] == $user["username"]) {
                    $temp1 = $user["username"];
                    // Updated this too
                    $temp2 = $pdo->prepare("SELECT first_name, last_name, username FROM Users WHERE UserID= :userID");
                    $temp2->bindParam(":userID", $temp1);
                    $temp2->execute();

                    $userFound = true;
                    login();
            }
        
        }
    ?>
    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            let currentImageIndex = 0;
            const images = $('.slideshow img');
            const duration = 5000;  // Change slide every 5 seconds

            setInterval(function() {
                images.eq(currentImageIndex).removeClass('active');
                currentImageIndex = (currentImageIndex + 1) % images.length;
                images.eq(currentImageIndex).addClass('active');
            }, duration);
        });
    </script>
</body>

</html>