<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 2rem;
        }
        .search-profile-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .searched-data {
            display: flex;
            align-items: center;
            padding: 1rem;
        }
        .searched-data:hover {
            background-color: lightgray;
        }
    </style>
</head>
<body>
    <?php
        include "../includes/sessions.php";
        include "../includes/setupUserData.php";
        include "../includes/navbar.php";
    ?>
    <h1><center>Search for users</center></h1>
    <div class="container">
        <form action="" method="get" autocomplete="off" class="mb-3">
            <input type="text" name="search" id="search" onkeyup="searchUsers(this);" placeholder="Search" class="form-control" value="<?= $_GET["search"] ?? ""; ?>">
        </form>
        <div id="ajaxdata"></div>
    </div>
    
    <script src="../includes/jquery-3.7.1.min.js"></script>
    <script>
        function followBtnClick (e) {
            $.ajax({
                type: "POST",
                url: "../src/follow.php",
                data: { name: "<?= $user["username"] ?>", name2: e.name }
            });


            if (e.innerText === "Follow") {
                e.innerText = "Unfollow";
            } else {
                e.innerText = "Follow";
            }
        }


        function clickProfile (e) {
            let myForm = "<form action='searchedProfile.php' method='post' name='tempForm'><input type='text' name='searched-user' value='" + e.children[1].innerText + "' ></form>";
            $("body").append(myForm);


            document.forms["tempForm"].submit();
        }

        function searchUsers (e) {
            $.ajax({
                type: "POST",
                url: "../src/searchUsers.php",
                data: { search: e.value },
                success: function(data, textStatus) {
                    $("#ajaxdata").html(data);
                },
                error: function() {
                    alert("Boohoo");
                }
            });
        }
        
    </script>
    <?php
    include "allPosts.php"
    ?>
</body>
</html>

