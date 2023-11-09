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
    $userComment = $_POST["userComment"];
    $commentDate = date("m/d/Y");

    $userComment = htmlspecialchars($userComment);

    $addToSQL = $pdo->prepare("INSERT INTO Comments (PostID, User, Comment, Date) VALUES ('$postID', '" . $_SESSION["username"] . "', '$userComment', '$commentDate');");
    $addToSQL->execute();

    $getUserPfp = $pdo->prepare("SELECT profile_image FROM Users WHERE username='" . $_SESSION["username"] . "'");
    $getUserPfp->execute();
    $userPfp = $getUserPfp->fetch(PDO::FETCH_ASSOC);

    echo "<div><div class='comment-user'><img class='comment-pfp' src='" . $userPfp["profile_image"] . "' alt=''>" . $_SESSION["username"] . " - " . $userComment . " - " . $commentDate . "</div>";
?>