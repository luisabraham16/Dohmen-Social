<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>


    <style>
        * {
            margin: 0px;
            padding: 0px;
        }
        body {
            background-color: lightsteelblue;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70vh;
        }
        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
            width: 60%;
            border-radius: 1vh;
            border: 5px solid black;
        }
        form > * {
            margin: 1vh 1vw;
        }
        .error {
            color: red;
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
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <label for="FName">First Name: </label>
            <input type="text" name="FName" id="" value="<?= $user["FName"] ?? ""; ?>">
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

    <script>
        let proof =document.querySelector("#matching");
        let pw = document.querySelector("[name='pw']");
        let pw2 = document.querySelector("[name='pw2']");


        pw2.addEventListener("change", e => {
            if (pw.value !== pw2.value) {
                proof.textContent = "Passwords are not matching";
            } else {
                proof.textContent = "";
            }
        });
    </script>
</body>
</html>