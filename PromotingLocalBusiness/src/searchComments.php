<?php 
    include "../includes/connection.php";
    include "../includes/sessions.php";

    // get the liked postID
    $postID;
    if (isset($_POST["postImage"])) {
        $likedPostLink = $_POST["postImage"];
        $likedPostLink = explode("uploads", $likedPostLink);
        $likedPostLink = $likedPostLink[1];
        $preparePostData = $pdo->prepare("SELECT PostID FROM Posts WHERE image='../uploads$likedPostLink'");
        $preparePostData->execute();
        $fetchPostData = $preparePostData->fetch(PDO::FETCH_ASSOC);
        
        $postID = $fetchPostData["PostID"];
        $searchComments = $pdo->prepare("SELECT User, Comment, Date, users.profile_image FROM Comments LEFT JOIN Users ON comments.user=users.username WHERE PostID='$postID' ORDER BY CommentID desc");
        $searchComments->execute();
    
        foreach ($searchComments->fetchAll() as $k => $v) {
            echo "<div><div class='comment-user'><img class='comment-pfp' src='" . $v["profile_image"] . "' alt=''>" . $v["User"] . "</div> - " . $v["Comment"] . " - " . $v["Date"] . "</div>";
        }
    }
?>