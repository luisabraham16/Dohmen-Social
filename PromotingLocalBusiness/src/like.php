<?php 
    include "../includes/connection.php";

    $likedPostLink = $_POST["postImage"];
    $likedPostLink = explode("uploads", $likedPostLink);
    $preparePostData = $pdo->prepare("SELECT PostID FROM Posts WHERE image='../uploads$likedPostLink'");
    $preparePostData->execute();
    $fetchPostData = $preparePostData->fetch(PDO::FETCH_ASSOC);

    print_r($fetchPostData["PostID"]);
?>