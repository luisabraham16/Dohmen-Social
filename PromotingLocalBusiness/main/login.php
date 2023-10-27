<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>
</head>
<body>
    <?php
        $userFound = false;
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user = [
                "username" => htmlspecialchars($_POST["username"]),
                "password" => htmlspecialchars($_POST["pw"])
            ];

            include "../includes/sessions.php";
            include "../includes/connection.php";
           
            $getUser = $pdo->prepare("SELECT Password FROM Users WHERE Username='" . $user["username"] . "'");
            $getUser->execute();
            $x = $getUser->fetch();
            
            if (password_verify($user["password"], $x["Password"]) && $x["username"] == $user["username"]) {
                    $temp1 = $user["username"];
                    $temp2 = $pdo->prepare("SELECT first_name, last_name, username FROM Users WHERE UserID='$temp1'");
                    $temp2->execute();

                    $userFound = true;
                    login();
            }
           
        }
    ?>
    <form method="post" action="profile.php">
        <p><?php echo isset($_POST["sub"]) ? "Username or password not found." : ""; ?></p>
        <label for="username">Username: </label>
        <input type="text" name="username" value="<?= $_POST["username"] ?? ""; ?>">


        <label for="pw">Password: </label>
        <input type="password" name="pw" value="<?= $_POST["pw"] ?? ""; ?>">


        <input type="submit" name="sub">
        <p>Don't have an account? <a href="register.php">Register here!</a></p>
    </form>
    <?php
        if ($userFound) {
            header("Location: profile.php");
            die();
        }
    ?>
</body>
</html>