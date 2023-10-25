<?php
    include "connection.php";
    $user = [
        "username" => htmlspecialchars($_SESSION["username"]) ?? null,
        "profile_image" => "",
        "first_name" => "",
        "last_name" => "",
    ];

    $temp1 = $user["username"];
    $temp2 = $pdo->prepare("SELECT first_name, last_name, username, profile_image FROM Users WHERE username='$temp1'");
    $temp2->execute();

    $myUserData = $temp2->fetch();
    $user["profile_image"] = $myUserData["profile_image"];
    $user["first_name"] = $myUserData["first_name"];
    $user["last_name"] = $myUserData["last_name"];
?>