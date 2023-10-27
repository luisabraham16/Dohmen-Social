
<!-- <style>
    * {
        margin: 0px;
        padding: 0px;
    }
    nav {
        background-color: darkgray;
        display: flex;
        align-items: center;
        margin-bottom: 2vh;
        position: fixed;
        width: 100%;
    }
    
    nav > * {
        margin-left: 15vw;
        font-size: large;
        font-weight: bold;
    }
    nav > div {
        display: flex;
        align-items: center;
    }
    nav > a {
        text-decoration: none;
        color: white
    }
    nav > div > a {
        text-decoration: none;
        color: white;
        margin-left: 0.25vw;
    }
    nav > div > a:hover {
        color: lightgray;
    }
    nav > a:hover {
        color: lightgray;
    }
    #nav-image {
        height: 3vh;
        border: 2px solid black;
        border-radius: 5vh;
    }

</style> -->

<style>
    .profile-picture {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }
    .profile-container {
        text-align: center;
        margin-top: 50px;
    }
</style>

<!-- navbar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags for Bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">Dohmen's Social</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="home.php" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="discover2.php">Discover</a>
                    </li>
                    <!-- Adding the Profile Button -->
                    <li class="nav-item">
                    <a class="nav-link" href="demoPost.php">Post</a>
                    </li>
                </ul>
            </div>
            <!-- Optionally, add logout or other user-specific actions on the right side of navbar -->
            <div class="d-flex">
                <a href="../main/profile.php"><img src="<?= $user['profile_image'] ?>" class="profile-picture mb-3" alt="Profile Picture" id="nav-image"></a>
                <a href="../main/profile.php"><?= $user["username"] ?></a>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>