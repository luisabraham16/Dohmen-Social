<?php
    include '../includes/sessions.php';
    include '../includes/connection.php';
    include "../includes/setupUserData.php";
    include '../includes/navbar.php';
    include "../includes/validations.php";
    

    require_login(isset($_SESSION["username"]));

    $postData=[
        'userPost'=>"",
        'userImg'=>"",
        'currentDate'=>""
    ];
    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $postData['userPost']=$_POST['userPost'];
        //$postData['userImg']=$_POST['userImg'];
        $postData['currentDate'] = date("m/d/Y"); //Do time here
        
        if($_FILES['image']['error'] == 0) {

            $temp = $_FILES["image"]["tmp_name"];
            $path = "../uploads/" . $_FILES["image"]["name"];
            $temp2 = explode(".", $_FILES["image"]["name"]);
            $newfilename = "../uploads/" . generateRandomString() . round(microtime(true)) . '.' . end($temp2);

            $moved = move_uploaded_file($temp, $newfilename);
                
            if ($moved) {
                echo "<h3>  Image uploaded successfully!</h3>";
            } else {
                echo "<h3>  Failed to upload image!</h3>";
            }
            //$data = file_get_contents($_FILES['image']['tmp_name']);
            $postData['userImg'] = $newfilename;
        }else{
            
        }
        insertUser($pdo, $postData);
    }
    
    function insertUser($pdo, $postData){
        $sql = "INSERT INTO posts (Username, Text, image, Date) VALUES ('" . $_SESSION['username'] . "', '" . $postData["userPost"] . "', '" . $postData["userImg"] . "', '" . $postData["currentDate"] . "');";
        
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dohmen's Social</title>
    <style>
        form {
            padding-top: 10vw;
        }
    </style>
</head>
<body>
    <form action = "" method="POST" enctype="multipart/form-data" autocomplete="off">
        <section>
            <div>
                <label for='userPost'>Post</label>
                <input id='userPost' name='userPost' class="postInput" type="text">
            </div>
            <div>
                <label for='image'>Img</label>
                <input type="file" accept="image/*" name="image" id="image">                
            </div>
        </section>

        <input class='btnSubmit' type='submit' value='submit'>
    </form>
</body>
</html>