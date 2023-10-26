<!DOCTYPE html>
<html lang="en">
<?php 
    session_start();
    include "../includes/setupUserData.php";
    include "../includes/navbar.php";

    //show the seached user data
    $findUserData = $pdo->prepare("SELECT username, first_name, last_name, email, profile_image FROM Users WHERE username='" . $_POST["searched-user"] . "'");
    $findUserData->execute();
    $fetchUserData = $findUserData->fetch(PDO::FETCH_ASSOC);

    $userData = [
        "username"=> $fetchUserData["username"],
        "email"=> $fetchUserData["email"],
        "first_name"=> $fetchUserData["first_name"],
        "last_name"=> $fetchUserData["last_name"],
        "profile_image"=> $fetchUserData["profile_image"],
    ];
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $userData["username"]; ?></title>
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
        $findFollowerData = $pdo->prepare("SELECT COUNT(following) FROM Follows WHERE following='". $userData["username"] . "'");
        $findFollowingData = $pdo->prepare("SELECT COUNT(follower) FROM Follows WHERE follower='". $userData["username"] . "'");
        $findFollowingData->execute();
        $fetchFollowingData = $findFollowingData->fetch(PDO::FETCH_ASSOC);
        $findFollowerData->execute();
        $fetchFollowerData = $findFollowerData->fetch(PDO::FETCH_ASSOC);
        
        $userData["following"] = $fetchFollowingData["COUNT(follower)"];
        $userData["followers"] = $fetchFollowerData["COUNT(following)"];
    ?>
    <div id="user-info">
        <img src="<?= $userData["profile_image"] ?>" alt="" id="profile-image">
        <div>
            <h2><?= $userData["username"] ?></h2>
            <h4><?= $userData["first_name"] . " " . $userData["last_name"] ?></h4>
        </div>
        <div>
            <h3>Followers: <?= $userData["followers"]; ?></h3>
        </div>
        <div>
            <h3>Following: <?= $userData["following"]; ?></h3>
        </div>
    </div>

    <div id="posts">
        <?php 
            $findPosts = $pdo->prepare("SELECT * FROM Posts WHERE username='" . $userData["username"] . "' AND image <> '' ORDER BY PostID desc;");
            $findPosts->execute();

            foreach($findPosts->fetchAll(PDO::FETCH_ASSOC) as $k => $v) {
                echo "<img class='post-img' src='" . $v["image"] . "'>";
            }
        ?>
    </div>
</body>
</html>