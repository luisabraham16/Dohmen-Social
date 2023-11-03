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
    
    // check if post is already liked
    $checkLike = $pdo->prepare("SELECT * FROM Likes WHERE PostID='$postID' AND liker='" . $_SESSION["username"] . "';");
    $checkLike->execute();

    if (!$checkLike->fetch(PDO::FETCH_ASSOC)) {
        $addToSQL = $pdo->prepare("INSERT INTO Likes (PostID, liker) VALUES ('$postID', '" . $_SESSION["username"] . "');");
        $addToSQL->execute();
        echo "liked";
    } else {
        $removeFromSQL = $pdo->prepare("DELETE FROM Likes WHERE PostID='$postID' AND liker='" . $_SESSION["username"] . "';");
        $removeFromSQL->execute();
        echo "unliked";
    }

    $fetchNumLikes = $pdo->prepare("SELECT COUNT(PostID) FROM Likes WHERE PostID='$postID'");
    $fetchNumLikes->execute();
    $numLikes = $fetchNumLikes->fetch()["COUNT(PostID)"];

    echo "," . $numLikes;
?>