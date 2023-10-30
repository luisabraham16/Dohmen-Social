<?php
include "../includes/sessions.php";
include '../includes/connection.php';
include "../includes/setupUserData.php";
include '../includes/navbar.php';
require_login($_SESSION["logged_in"]);
//query is the sql statement that runs
$query = "SELECT PostID, first_name, Text, Date, posts.image, posts.username, users.profile_image, follows.follower FROM posts JOIN users on posts.username=users.username JOIN follows ON posts.username=follows.following
WHERE follower='" . $user["username"] . "' OR posts.username='" . $user["username"] . "' GROUP BY PostID ORDER BY PostID desc;";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>
    <style>
        #posts {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .post {
            margin: 1vw;
        }
        .image-container {
            height: 400px;
            width: 400px;
            border: 8px solid black;
        }

        img {
            object-fit: cover;
            object-position: center center;
            width: 100%;
            height: 100%;
        }
        #profile-image {
            width: 3vw;
            border: 3px solid black;
            border-radius: 8vw;
        }
        #user-post-data {
            display: flex;
            align-items: center;
        }

        .heart {
            position: relative;
            width: 100px;
            height: 90px;
            margin-top: 10px;
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
    <div id="posts">
        <?php 
            echo "<b> <center>Latest posts</center> </b> <br> <br>";
            //the database you got earlier, run that sql command
            if ($result = $mysqli->query($query)) {
                //not exactly sure, but it gets each row of the database and saves them to temp vars
                while ($row = $result->fetch_assoc()) {
                    $field1name = $row["PostID"];
                    $field2name = $row["username"];
                    $field3name = $row["Text"];
                    $field4name = $row["Date"];
                    $field5name = $row["image"];
                    $field6name = $row["profile_image"];
                    //then we can display those vars below however we want
                    echo '<div class="post"><div id="user-post-data" onclick="checkProfile(this)"><img src="'. $field6name .'" id="profile-image"><h2 class="post-user">' . $field2name . '</h2></div>';
                    if($field5name != null)
                    {
                        echo '<div class="image-container"><img src="' . $field5name . '"></div>';
                    }
                    echo '<br><div><div class="heart"></div><h4>'.$field3name.'</h4><h5>' . $field4name . '</h5></div></div>';
                }
            
            /*freeresultset*/
            $result->free();
            }
        ?>
    </div>
    <script src="../includes/jquery-3.7.1.min.js"></script>
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
                    
                }
            });
        });
    </script>
</body>
</html>