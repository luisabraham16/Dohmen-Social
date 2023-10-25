<?php 
    session_start();
    include "../includes/connection.php";
    include "../includes/setupUserData.php";

    $userSearch = $_POST["search"] ?? "";
    $seachedValues = $pdo->prepare("SELECT username, profile_image FROM Users WHERE username LIKE '%$userSearch%'");
    $seachedValues->execute();

    foreach ($seachedValues->fetchAll() as $row => $data) {
        $addToHTML = "<div class='searched-data' name='". $data["username"] ."'><div onclick='clickProfile(this)' class='searched-data'><img src='". $data["profile_image"] ."' alt='Profile' class='search-profile-img'><p>" . $data["username"] . "</p></div>";
        // continue here (show if user is already followed and if so then don't show the follow button)
        $follower = $user["username"];
        $following = $data["username"];
        $checkFollowed = $pdo->prepare("SELECT * FROM Follows WHERE follower='$follower' AND following='$following'");
        $checkFollowed->execute();

        $check = $checkFollowed->fetch();

        if (!$check && $user["username"] != $data["username"]) {
            $addToHTML .= "<button type='button' class='follow-btn' onclick='followBtnClick(this)' name='". $data["username"] ."'>Follow</button>";
        } else if ($user["username"] != $data["username"]) {
            $addToHTML .= "<button type='button' class='follow-btn' onclick='followBtnClick(this)' name='". $data["username"] ."'>Unfollow</button>";
        }
        
        echo $addToHTML . "</div>";
    }
?>