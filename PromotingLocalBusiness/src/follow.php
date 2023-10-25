<?php 
    include "../includes/connection.php";
    $follower = $_POST["name"];
    $following = $_POST["name2"];

    $checkFollowPrep = $pdo->prepare("SELECT * FROM Follows WHERE follower='$follower' AND following='$following'");
    $checkFollowPrep->execute();

    $checkFollow = $checkFollowPrep->fetch();
    if (!$checkFollow) {
        $addFollower = $pdo->prepare("INSERT INTO Follows (follower, following) VALUES ('$follower', '$following')");
        $addFollower->execute();
    } else {
        $removeFollower = $pdo->prepare("DELETE FROM Follows WHERE follower='$follower' AND following='$following'");
        $removeFollower->execute();
    }

?>
<script>
    console.log("HIII");
</script>