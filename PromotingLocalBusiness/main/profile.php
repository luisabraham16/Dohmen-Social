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
        #profile-image {
            width: 8vw;
            border: 3px solid black;
            border-radius: 8vw;
        }

        #user-info {
            display: flex;
            padding-top: 10vw;
        }
        #user-info > * {
            margin-left: 10vw;
        }

        #posts {
            display: grid;
            gap: 1vw;
            margin: 5vw;
            grid-template-columns: 30vw 30vw 30vw;
        }
        .post-img {
            border: 3px solid black;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <?php
        include "../includes/sessions.php";
        include "../includes/connection.php";

        //require_login(isset($_SESSION["logged-in"]));

        $user = [
            "username" => isset($_SESSION["username"]) ? $_SESSION["username"] : htmlspecialchars($_POST["username"]),
            "password" => isset($_POST["pw"]) ? htmlspecialchars($_POST["pw"]) : "",
            "profile_image" => "",
            "first_name" => "",
            "last_name" => "",
        ];

        //$pdo->exec("UPDATE Users SET UserID='Derrick' WHERE UserID='DerrickIsAweso'");

        $myUserData = null;
        $userFound = false;

        $getUser = $pdo->prepare("SELECT Password FROM Users WHERE username='" . $user["username"] . "'");
        $getUser->execute();
        $x = $getUser->fetch();
        
        if (password_verify($user["password"], $x["Password"]) || isset($_SESSION["logged_in"])) {
            $temp1 = $user["username"];
            $temp2 = $pdo->prepare("SELECT first_name, last_name, username, profile_image FROM Users WHERE username='$temp1'");
            $temp2->execute();


            $myUserData = $temp2->fetch();
            $userFound = true;
        }

        if (!$userFound) {
            header("Location: login.php", TRUE, 307);
        } if (isset($_GET["logout"])) {
            logout();
            header("Location: login.php");
            die();
        }


        if ($myUserData) {
            if (isset($_POST["username"]) && isset($_POST["pw"])) {
                $_SESSION["username"] = htmlspecialchars($_POST["username"]);
                //$_SESSION["pw"] = htmlspecialchars($_POST["pw"]);
            }
            $user["profile_image"] = $myUserData["profile_image"];
            $user["first_name"] = $myUserData["first_name"];
            $user["last_name"] = $myUserData["last_name"];

            login();
        }

        include "../includes/navbar.php";
    ?>

    <?php 
        $findFollowerData = $pdo->prepare("SELECT COUNT(following) FROM Follows WHERE following='". $user["username"] . "'");
        $findFollowingData = $pdo->prepare("SELECT COUNT(follower) FROM Follows WHERE follower='". $user["username"] . "'");
        $findFollowingData->execute();
        $fetchFollowingData = $findFollowingData->fetch(PDO::FETCH_ASSOC);
        $findFollowerData->execute();
        $fetchFollowerData = $findFollowerData->fetch(PDO::FETCH_ASSOC);
        
        $user["following"] = $fetchFollowingData["COUNT(follower)"];
        $user["followers"] = $fetchFollowerData["COUNT(following)"];
    ?>

    <div id="user-info">
        <img src="<?= $user["profile_image"] ?>" alt="" id="profile-image">
        <div>
            <h2><?= $user["username"] ?></h2>
            <h4><?= $user["first_name"] . " " . $user["last_name"] ?></h4>
        </div>
        <div>
            <h3>Followers: <?= $user["followers"]; ?></h3>
        </div>
        <div>
            <h3>Following: <?= $user["following"]; ?></h3>
        </div>
        <div>
            <form action="" method="get">
                <input type="submit" name="logout" value="Log Out">
            </form>
        </div>
    </div>

    <div id="posts">
        <?php 
            $findPosts = $pdo->prepare("SELECT * FROM Posts WHERE username='" . $user["username"] . "' AND image <> '' ORDER BY PostID desc;");
            $findPosts->execute();

            foreach($findPosts->fetchAll(PDO::FETCH_ASSOC) as $k => $v) {
                echo "<img class='post-img' src='" . $v["image"] . "'>";
            }
        ?>
    </div>
</body>
</html>


