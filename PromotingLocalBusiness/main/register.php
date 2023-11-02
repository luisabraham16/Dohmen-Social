<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>

    <!-- Include Bootstrap CSS & JS (assumed based on your classes) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <style>
        /* Styling for the page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: lightsteelblue;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .card {
            width: 400px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: dodgerblue;
            color: white;
            font-weight: bold;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="password"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: dodgerblue;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: darkblue;
        }

        .error {
            color: red;
            font-size: 12px;
        }

        a {
            text-decoration: none;
            color: dodgerblue;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>

</head>
<body>
    <!-- FRONT END PHP -->
    <?php
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
    ?>


    <!-- BACK END PHP -->
    <?php
        //$x = $pdo->prepare("INSERT INTO dohmen_s_social_profile_data___sheet1 (UserID, Password, FName, LName, Bio, Followers, Following) VALUES ('tylercoder1', 'iamtyler', 'Tyler', 'Ozburn', 'I am making a discord bot', 100, 100)");

        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            include "../includes/connection.php";
            include "../includes/validations.php";

            $user["FName"] = $_POST["FName"] ?? "";
            $user["LName"] = $_POST["LName"] ?? "";
            $user["Email"] = $_POST["email"] ?? "";
            $user["Username"] = $_POST["username"] ?? "";
            $user["Password"] = $_POST["pw"] ?? "";

            // validate data
            $errors["FName"] = validateText($user["FName"], "FName") ? "" : "Invalid text.";
            $errors["LName"] = validateText($user["LName"], "LName") ? "" : "Invalid text.";
            $errors["Email"] = isEmail($user["Email"]) ? "" : "Invalid Email.";
            $errors["Username"] = validateText($user["Username"], "username") ? "" : "Invalid Username.";
            $errors["Password"] = isPassword($user["Password"]) ? "" : "Invalid Password.<br>Make sure it contains both uppercase and lowercase letters and a number.";
            $errors["Password"] = ($_POST["pw"] == $_POST["pw2"]) ? "" : "Passwords do not match.";


            $userTakenPrep = $pdo->prepare("SELECT username FROM Users WHERE username = '" . $user["Username"] . "';");
            $userTakenPrep->execute();

            $userTaken = $userTakenPrep->fetch();

            if ($userTaken) {
                $errors["Username"] = "Username is already taken.";
            }

            $verified = true;
            
            foreach($errors as $k => $v) {
                if ($v != "") {
                    $verified = false;
                }
            }

            if ($verified) {
                sanitizeData($user);
                $moved = false;

                if ($_FILES["image"]["error"] == 0) {
                    $temp = $_FILES["image"]["tmp_name"];
                    $path = "../uploads/" . $_FILES["image"]["name"];
                    $temp2 = explode(".", $_FILES["image"]["name"]);
                    $newfilename = "../uploads/" . generateRandomString() . round(microtime(true)) . '.' . end($temp2);

                    $moved = move_uploaded_file($temp, $newfilename);
                }

                $x = $pdo->prepare("INSERT INTO Users (username, first_name, last_name, email, password, profile_image) VALUES ('" . $user["Username"] . "', '" . $user["FName"] . "', '" . $user["LName"] . "', '" . $user["Email"] . "', '" . password_hash($user["Password"], PASSWORD_DEFAULT) . "', '$newfilename')");
                $x->execute();

                $user["FName"] = "";
                $user["LName"] = "";
                $user["Email"] = "";
                $user["Username"] = "";
                $user["Password"] = "";

                header("Location: login.php");
                die;
            }
            
        }
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

    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
        <img class="active" src=".../includes/Logo.png" alt="">
        <img src=".../includes/Logo.png" alt="">
        <img src=".../includes/Logo.png" alt="">
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Registration Form -->
        <div class="card">
            <div class="card-header">Register</div>
            <div class="card-body">
                <form action="register.php" method="post" enctype="multipart/form-data">
                                <div>
                                    <label for="FName">First Name: </label>
                                    <input type="text" name="FName" value="<?= $user["FName"] ?? ""; ?>">
                                    <span class="error"><?= $errors["FName"] ?></span>
                                </div>
                                <div>
                                    <label for="LName">Last Name: </label>
                                    <input type="text" name="LName" value="<?= $user["LName"] ?? ""; ?>">
                                    <span class="error"><?= $errors["LName"]; ?></span>
                                </div>
                                <div>
                                    <label for="email">Email: </label>
                                    <input type="text" name="email" value="<?= $user["Email"] ?? ""; ?>">
                                    <span class="error"><?= $errors["Email"]; ?></span>
                                </div>
                                <div>
                                    <label for="username">Username: </label>
                                    <input type="text" name="username" value="<?= $user["Username"] ?? ""; ?>">
                                    <span class="error"><?= $errors["Username"]; ?></span>
                                </div>
                                <div>
                                    <label for="pw">Password: </label>
                                    <input type="password" name="pw">
                                </div>
                                <div>
                                    <label for="pw2">Confirm <br> Password: </label>
                                    <input type="password" name="pw2">
                                    <span id="matching" class="error"><?= $errors["Password"]; ?></span>
                                </div>
                                <div>
                                    <label for="image">Profile Picture: </label>
                                    <input type="file" accept="image/*" name="image" id="image">
                                </div>


                                <input type="submit" name="sub">
                                <a href="login.php">Already have a profile?</a>
                            </form>
                            <input type="submit" name="sub" value="Register">
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here!</a></p>
                </form>
            </div>
        </div>
    </div>

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