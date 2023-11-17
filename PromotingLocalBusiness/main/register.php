<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>

    <!-- Include Bootstrap CSS & JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <style>
        /* Base Reset */
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: black;
            color: #fff;
            overflow: hidden;
            height: 100vh;
        }

        body, .card {
            margin: 0;
            padding: 0;
        }

        .slideshow {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .slideshow img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 2s ease-in-out;
            object-fit: cover;
        }

        .slideshow img.active {
            opacity: 1;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        form > div {
            margin: 5px 0;
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"], input[type="file"], input[type="submit"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: black;
        }

        .error {
            color: red;
            margin-top: 5px;
        }

        input[type="submit"] {
            cursor: pointer;
            background-color: gray;
            color: white;
            margin-top: 15px;
        }

        input[type="submit"]:hover {
            background-color: gray;
        }

        a {
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            color: gray;
            transition: color 0.3s ease;
        }

        a:hover {
            color: black;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120vh;
            backdrop-filter: blur(5px); /* blur effect */
        }

        .card {
            background-color: rgba(255, 255, 255, 0.6); /* semi transparent background */
            width: auto;
        }

        /* Enhancing the form and its elements */
        .card-header {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.8); /* darker background for header */
            color: white; /* text color */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        form label {
            font-weight: 600;
        }

        form input[type="submit"] {
            background-color: #333;
            border: none;
            color: #333;
            padding: 12px 20px;
            text-transform: uppercase;
            font-weight: 600;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #333;
        }

        .mt-3.text-center a {
            color: #007BFF;
        }

        .mt-3.text-center a:hover {
            text-decoration: underline;
        }
        
    </style>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
    <?php
        require "../includes/connection.php";
        require "../includes/validations.php";

        $user = [
            "FName" => "",
            "LName" => "",
            "Email" => "",
            "Username" => "",
            "Password" => ""
        ];

        $errors = [
            "FName" => "",
            "LName" => "",
            "Email" => "",
            "Username" => "",
            "Password" => ""
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Get filtered input
            $user["FName"] = filter_input(INPUT_POST, "FName", FILTER_SANITIZE_STRING);
            $user["LName"] = filter_input(INPUT_POST, "LName", FILTER_SANITIZE_STRING);
            $user["Email"] = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
            $user["Username"] = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
            $user["Password"] = filter_input(INPUT_POST, "pw", FILTER_SANITIZE_STRING);
            $password2 = filter_input(INPUT_POST, "pw2", FILTER_SANITIZE_STRING);

            // validate data
            $errors["FName"] = validateText($user["FName"], "FName") ? "" : "Invalid text.";
            $errors["LName"] = validateText($user["LName"], "LName") ? "" : "Invalid text.";
            $errors["Email"] = isEmail($user["Email"]) ? "" : "Invalid Email.";
            $errors["Username"] = validateText($user["Username"], "username") ? "" : "Invalid Username.";
            $errors["Password"] = isPassword($user["Password"]) ? "" : "Invalid Password.<br>Make sure it contains both uppercase and lowercase letters and a number.";
            if ($user["Password"] !== $password2) {
                $errors["Password"] = "Passwords do not match.";
            }

            // Check if username is already taken
            $stmt = $pdo->prepare("SELECT username FROM Users WHERE username = :username");
            $stmt->execute([':username' => $user["Username"]]);
            $userTaken = $stmt->fetch();
            if ($userTaken) {
                $errors["Username"] = "Username is already taken.";
            }

            // Check if there are any errors before inserting into DB
            if (!array_filter($errors)) {
                // File upload
                $moved = false;
                $newfilename = null;
                if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
                    $temp = $_FILES["image"]["tmp_name"];
                    $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                    $newfilename = "../uploads/" . bin2hex(random_bytes(15)) . "." . $fileExtension;
                    $moved = move_uploaded_file($temp, $newfilename);
                }

                if (!$newfilename) {
                    $newfilename = "../uploads/default pfp.png";
                }

                $stmt = $pdo->prepare("INSERT INTO Users (username, first_name, last_name, email, password, profile_image) VALUES (:username, :fname, :lname, :email, :hashed_password, :image_path)");
                $stmt->execute([
                    ':username' => $user["Username"],
                    ':fname' => $user["FName"],
                    ':lname' => $user["LName"],
                    ':email' => $user["Email"],
                    ':hashed_password' => password_hash($user["Password"], PASSWORD_DEFAULT),
                    ':image_path' => $newfilename
                ]);

                echo '
                    <script>
                        let myForm = `<form action="login.php" method="post" name="tempForm"><input type="text" name="username" value="' . $user["Username"] . '" ><input type="text" name="pw" value="" ></form>`;
                        $("body").append(myForm);
            
            
                        document.forms["tempForm"].submit();  
                    </script>
                ';
            }
        }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Dohmen's Social</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Background Slideshow -->
    <div class="slideshow">
        <img class="active" src="../includes/Logo.png" alt="">
        <img src="../includes/Logo.png" alt="">
        <img src="../includes/Logo.png" alt="">
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Registration Form -->
        <div class="card" style="margin: 0; padding: 0;">
            <div class="card-header">Register</div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                                <!-- First Name -->
                                <div class="input-group">
                                    <label for="FName">First Name</label>
                                    <input type="text" name="FName" id="FName" placeholder="Enter your first name" required value="<?= htmlspecialchars($user['FName']) ?>">
                                    <small class="error-text"><?= $errors["FName"] ?? "" ?></small>
                                </div>

                                <!-- Last Name -->
                                <div class="input-group">
                                    <label for="LName">Last Name</label>
                                    <input type="text" name="LName" id="LName" placeholder="Enter your last name" required value="<?= htmlspecialchars($user['LName']) ?>">
                                    <small class="error-text"><?= $errors["LName"] ?? "" ?></small>
                                </div>

                                <!-- Email -->
                                <div class="input-group">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" placeholder="Enter your email" required value="<?= htmlspecialchars($user['Email']) ?>">
                                    <small class="error-text"><?= $errors["Email"] ?? "" ?></small>
                                </div>

                                <!-- Username -->
                                <div class="input-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" placeholder="Enter a username" required value="<?= htmlspecialchars($user['Username']) ?>">
                                    <small class="error-text"><?= $errors["Username"] ?? "" ?></small>
                                </div>

                                <!-- Password -->
                                <div class="input-group">
                                    <label for="pw">Password</label>
                                    <input type="password" name="pw" id="pw" placeholder="Enter a strong password" required>
                                    <small class="error-text"><?= $errors["Password"] ?? "" ?></small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="input-group">
                                    <label for="pw2">Confirm Password</label>
                                    <input type="password" name="pw2" id="pw2" placeholder="Confirm your password" required>
                                    <!-- Not adding error feedback here since the same password error is already displayed above -->
                                </div>

                                <!-- Profile Image -->
                                <div class="input-group">
                                    <label for="image">Profile Image</label>
                                    <input type="file" name="image" id="image" accept=".png, .jpg, .jpeg">
                                    <!-- Ideally, add feedback for successful uploads or issues with the file -->
                                </div>

                                <!-- Submit Button -->
                                <div class="input-group">
                                    <button type="submit" class="btn">Submit</button>
                                </div>

                                
                            </form>
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here!</a></p>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let currentImageIndex = 0;
            const images = $('.slideshow img');
            const duration = 5000;  // Change slide every 5 seconds

            setInterval(function() {
                // Hide current image
                $(images[currentImageIndex]).removeClass('active');
                currentImageIndex = (currentImageIndex + 1) % images.length;
                // Show next image
                $(images[currentImageIndex]).addClass('active');
            }, duration);
        });
        // Script to handle the blur effect for the background
        $(document).ready(function() {
            $('.slideshow').on('mouseenter', function() {
                $('.container').css('backdrop-filter', 'blur(0px)');
            });

            $('.slideshow').on('mouseleave', function() {
                $('.container').css('backdrop-filter', 'blur(5px)');
            });
        });
    </script>
</body>
</html>