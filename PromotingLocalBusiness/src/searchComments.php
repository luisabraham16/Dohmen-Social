<?php 
    include "../includes/connection.php";
    include "../includes/sessions.php";

    // get the liked postID
    $likedPostLink = $_POST["postImage"];
    $likedPostLink = explode("uploads", $likedPostLink);
    $likedPostLink = $likedPostLink[1];
    $preparePostData = $pdo->prepare("SELECT PostID FROM Posts WHERE image='../uploads$likedPostLink'");
    $preparePostData->execute();
    $fetchPostData = $preparePostData->fetch(PDO::FETCH_ASSOC);
    
    $postID = $fetchPostData["PostID"];

    $searchComments = $pdo->prepare("SELECT * FROM Comments WHERE PostID='$postID' ORDER BY CommentID desc");
    $searchComments->execute();

    foreach ($searchComments->fetchAll() as $k => $v) {
        echo "<div>" . $v["User"] . " - " . $v["Comment"] . " - " . $v["Date"] . "</div>";
    }
?>