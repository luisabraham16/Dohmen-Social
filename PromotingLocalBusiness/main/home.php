<?php
include "../includes/sessions.php";
include '../includes/connection.php';
include "../includes/setupUserData.php";
include '../includes/navbar.php';
require_login($_SESSION["logged_in"]);
// query is the sql statement that runs
$query = "SELECT PostID, first_name, Text, Date, posts.image, posts.username, users.profile_image, follows.follower FROM posts LEFT JOIN users on posts.username=users.username LEFT JOIN follows ON posts.username=follows.following WHERE follower='" . $user["username"] . "' OR posts.username='" . $user["username"] . "' GROUP BY PostID ORDER BY PostID desc;";
// SELECT PostID, first_name, Text, Date, posts.image, posts.username, users.profile_image, follows.follower FROM posts LEFT JOIN users on posts.username=users.username JOIN follows ON posts.username=follows.following WHERE follower='meatballenr' OR posts.username='meatballenr' GROUP BY PostID ORDER BY PostID desc;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .post {
            margin: 2rem auto;
            border: 1px solid #ddd;
            border-radius: 1rem;
        }
        .post-header {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #ddd;
        }
        .post-header img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .post-body img {
            max-width: 100%;
            height: auto;
        }
        .post-footer {
            padding: 0.5rem 1rem;
            border-top: 1px solid #ddd;
        }
        .heart-icon, .comment-icon {
            margin-right: 0.5rem;
            cursor: pointer;
        }
        .heart-icon {
            color: red;
        }
        .post {
            background-color: #fff;
            border: 1px solid #e6e6e6;
            border-radius: 5px;
            margin-bottom: 2rem;
            padding: 1rem;
        }

        .post-user {
            font-weight: bold;
        }

        .like-comment-container {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
        }

        .like-btn, .comment-btn {
            cursor: pointer;
            margin-right: 0.5rem;
        }

        .comment-box {
            border: 3px solid black;
            width: 100%;
            padding: 1vh 1vw;
        }

        .post-comment-btn {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5" id="posts">
        <h3 class="mb-4 text-center">Latest posts</h3>
            <?php
                if ($result = $mysqli->query($query)) {
                    while ($row = $result->fetch_assoc()) {
                        $field1name = $row["PostID"];
                        $field2name = $row["username"];
                        $field3name = $row["Text"];
                        $field4name = $row["Date"];
                        $field5name = $row["image"];
                        $field6name = $row["profile_image"];
                        echo '<div class="post">';
                        echo '<div class="post-header" onclick="checkProfile(this)"><img src="'. $field6name .'"><span>' . $field2name . '</span></div>';
                        if ($field5name != null) {
                            echo '<div class="post-body"><img src="' . $field5name . '"></div>';
                        }
                        echo '<div class="post-footer">';
                        echo '<h5>'.$field3name.'</h5>';
                        echo '<small>' . $field4name . '</small><br>';
                        if ($_SESSION["logged_in"]) {
                            echo '<i class="heart-icon fas fa-heart"></i><i class="comment-icon fas fa-comment"></i>';
                        }
                        echo '<div class="like-comment-container">';

                        $checkLike = $pdo->prepare("SELECT * FROM Likes WHERE PostID='$field1name' AND liker='" . $_SESSION["username"] . "';");
                        $checkLike->execute();
                        $fetchNumLikes = $pdo->prepare("SELECT COUNT(PostID) FROM Likes WHERE PostID='$field1name'");
                        $fetchNumLikes->execute();
                        $numLikes = $fetchNumLikes->fetch()["COUNT(PostID)"];

                        if ($checkLike->fetch()) {
                            echo '<span class="like-btn">' . $numLikes . ' ❤️ Liked</span>';
                        } else {
                            echo '<span class="like-btn">' . $numLikes . ' 🤍 Like</span>';
                        }
                        echo '<span class="comment-btn">💬 Comment</span>';
                        echo '</div>'; // End of like-comment-container
                        echo '</div>'; // End of post-footer
                        echo '</div>'; // End of post
                    }
                    echo '<br><div><div class="heart"></div><h4>'.$field3name.'</h4><h5>' . $field4name . '</h5></div></div>';
                    $result->free();
                }
            ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        function checkProfile (e) {
            let listChildren = [ ...e.children ];

            listChildren.forEach(i => {
                if (i.className = "post-user" && i.innerText != "") {
                    let myForm = "<form action='searchedProfile.php' method='post' name='tempForm'><input type='text' name='searched-user' value='" + i.innerText + "' ></form>";
                    $("body").append(myForm);

                    document.forms["tempForm"].submit();
                }
            });
        }

        const loggedIn = <?php echo $_SESSION["logged_in"] ? 'true' : 'false'; ?>;

        $('.comment-btn').click(e => {
            if (!loggedIn) {
                alert('Please log in to comment on the post.');
                return;
            }

            let postParent = e.target.parentNode.parentNode.parentNode;

            let boxExists = false;
            [ ...postParent.children ].forEach(child => {
                if (child.className === "comment-box") {
                    boxExists = true;
                }
            });

            if (!boxExists) {
                $(postParent).append("<div class='comment-box'><div class='add-comment'><input type='text' class='user-comment'><span class='post-comment-btn' onclick='sendComment(this)'>Comment</span></div></div>");
            } else {
                $(postParent).find('.comment-box').remove();
            }

            let mainParent = e.target.parentNode.parentNode.parentNode;
            let postImg;

            [ ...mainParent.children ].forEach(child => {
                if (child.className === "post-body") {
                    postImg = child.childNodes[0].src;
                }
            });

            
            $.ajax({
                type: "POST",
                data: { postImage: postImg },
                url: "../src/searchComments.php",
                success: (returnData, status) => {
                    $(postParent).find(".comment-box").append(returnData);
                }
            });

        });

        function sendComment(e) {
            // access post image to identify post
            let mainParent = e.parentNode.parentNode.parentNode;
            let postImg;
            let comment;

            [ ...mainParent.children ].forEach(child => {
                if (child.className === "post-body") {
                    postImg = child.childNodes[0].src;
                }
            });

            [ ...e.parentNode.children ].forEach(child => {
                if (child.className === "user-comment") {
                    comment = child.value;
                }
            });

            $.ajax({
                type: "POST",
                data: { postImage: postImg, userComment: comment },
                url: "../src/addComment.php",
                success: (returnData, status) => {
                    [ ...e.parentNode.children ].forEach(child => {
                        if (child.className === "user-comment") {
                            child.value = "";
                        }
                    });

                    $(e.parentNode).append(returnData);
                }
            });
        }

        // Your AJAX call to handle comments
        $(".like-btn").click(e => {
            if (!loggedIn) {
                alert('Please log in to like the post.');
                return;
            }
            // access post image to identify post
            let heartParent = e.target.parentNode.parentNode.parentNode;
            let postImg;

            [ ...heartParent.children ].forEach(child => {
                if (child.className === "post-body") {
                    postImg = child.childNodes[0].src;
                }
            })
        

            // callback function to modify 'like' table
            $.ajax({
                type: "POST",
                data: { postImage: postImg },
                url: "../src/like.php",
                success: (returnData, status) => {
                    if (returnData.split(",")[0] === "liked") {
                        e.target.innerText = returnData.split(",")[1] + " ❤️ Liked";
                    } else if (returnData.split(",")[0] === "unliked") {
                        e.target.innerText = returnData.split(",")[1] + " 🤍 Like";
                    }
                }
            })
        });
    </script>
</body>
</html>