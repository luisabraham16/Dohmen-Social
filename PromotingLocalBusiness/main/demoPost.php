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
        $postData['userPost']=htmlspecialchars($_POST['userPost']);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            padding-top: 5vw;
            max-width: 600px;
        }
        label, input {
            margin-bottom: 1rem;
        }
        .btnSubmit {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btnSubmit:hover {
            background-color: #0056b3;
        }
        .img-preview {
            max-width: 100%;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="userPost" class="form-label">Description</label>
                <input type="text" class="form-control" id="userPost" name="userPost">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" accept="image/*" name="image" id="image">
            </div>
            <!-- Image preview -->
            <img id="imgPreview" class="img-preview" src="" alt="Image preview..." style="display:none;">
            <input type="submit" class="btnSubmit" value="Post">
        </form>
    </div>

    <script>
        document.getElementById("image").addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById("imgPreview").style.display = "block";
                    document.getElementById("imgPreview").src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
