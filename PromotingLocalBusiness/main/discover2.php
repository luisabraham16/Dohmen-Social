<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
        }
        form {
            padding-top: 10vw;
        }
        .search-profile-img {
            height: 3vh;
            border: 2px solid black;
            border-radius: 3vh;
        }
        .searched-data {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .searched-data:hover {
            background-color: lightgray;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        include "../includes/setupUserData.php";
        include "../includes/navbar.php";
    ?>

    <form action="" method="get" autocomplete="off">
        <input type="text" name="search" id="search" onkeyup="searchUsers(this);" placeholder="Search" value="<?= $_GET["search"] ?? ""; ?>">
    </form>
    <div id="ajaxdata"></div>
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
</body>
</html>

