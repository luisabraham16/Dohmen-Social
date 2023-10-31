<?php
include "../includes/sessions.php";
include '../includes/connection.php';
include "../includes/setupUserData.php";
include '../includes/navbar.php';
require_login($_SESSION["logged_in"]);
// query is the sql statement that runs
$query = "SELECT PostID, first_name, Text, Date, posts.image, posts.username, users.profile_image, follows.follower FROM posts JOIN users on posts.username=users.username JOIN follows ON posts.username=follows.following WHERE follower='" . $user["username"] . "' OR posts.username='" . $user["username"] . "' GROUP BY PostID ORDER BY PostID desc;";
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

        .heart::before, .heart::after {
            content: "";
            position: absolute;
            top: 0;
            width: 52px;
            height: 80px;
            border-radius: 50px 50px 0 0;
            background: lightgray;
        }

        .heart::before {
            left: 50px;
            transform: rotate(-45deg);
            transform-origin: 0 100%;
        }

        .heart::after {
            left: 0;
            transform: rotate(45deg);
            transform-origin: 100% 100%;
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
                        echo '<span class="like-btn">❤️ Like</span>';
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

        $('.like-btn').click(function() {
            if (!loggedIn) {
                alert('Please log in to like the post.');
                return;
            }
            // Your AJAX call to handle likes
        });

        $('.comment-btn').click(function() {
            if (!loggedIn) {
                alert('Please log in to comment on the post.');
                return;
            }
            // Your AJAX call to handle comments
        $(".heart").click(e => {
            // access post image to identify post
            let heartParent = e.target.parentNode.parentNode;
            let postImg;

            [ ...heartParent.children ].forEach(child => {
                if (child.className === "image-container") {
                    postImg = child.childNodes[0].src;
                }
            })

            // callback function to modify 'like' table
            $.ajax({
                type: "POST",
                data: { postImage: postImg },
                url: "../src/like.php",
                success: (returnData, status) => {
                    console.log(returnData);
                }
            });
        });
    </script>
</body>
</html>